<?php

namespace HaoZiTeam\ChatGPT;

use Exception;
use Generator;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Ramsey\Uuid\Uuid;

class V1
{
    private $baseUrl = 'https://ai.fakeopen.com/api/';

    private $accounts = [];

    private $http;

    public function __construct(string $baseUrl = null, int $timeout = 360)
    {
        if ($baseUrl) {
            $this->baseUrl = $baseUrl;
        }

        $this->http = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => $timeout,
            'stream' => true,
        ]);
    }

    /**
     * 设置账号
     *
     * @param string $accessToken
     * @param mixed $name
     * @param string $model
     * @param bool $historyAndTrainingDisabled
     * @param string|null $arkoseToken
     * @return void
     */
    public function addAccount(string $accessToken, $name = null, string $model = 'text-davinci-002-render-sha', bool $historyAndTrainingDisabled = false, string $arkoseToken = null): void
    {
        if ($name === null) {
            $this->accounts[] = [
                'access_token' => $accessToken,
                'model' => $model,
                'history_and_training_disabled' => $historyAndTrainingDisabled,
                'arkose_token' => $arkoseToken,
            ];
            if (substr($this->accounts[count($this->accounts) - 1]['model'], 0, 5) === 'gpt-4' && $arkoseToken === null) {
                try {
                    $this->accounts[count($this->accounts) - 1]['arkose_token'] = $this->getArkoseToken();
                } catch (Exception $e) {
                    $this->accounts[count($this->accounts) - 1]['arkose_token'] = '';
                }
            }
        } else {
            $this->accounts[$name] = [
                'access_token' => $accessToken,
                'model' => $model,
                'history_and_training_disabled' => $historyAndTrainingDisabled,
                'arkose_token' => $arkoseToken,
            ];
            if (substr($this->accounts[$name]['model'], 0, 5) === 'gpt-4' && $arkoseToken === null) {
                try {
                    $this->accounts[$name]['arkose_token'] = $this->getArkoseToken();
                } catch (Exception $e) {
                    $this->accounts[$name]['arkose_token'] = '';
                }
            }
        }
    }

    /**
     * 获取账号
     *
     * @param string $name
     *
     * @return array
     */
    public function getAccount(string $name): array
    {
        return $this->accounts[$name];
    }

    /**
     * 获取所有账号
     * @return array
     */
    public function getAccounts(): array
    {
        return $this->accounts;
    }

    /**
     * 发送消息
     *
     * @param string $prompt
     * @param string|null $conversationId
     * @param string|null $parentId
     * @param mixed $account
     * @param bool $stream
     *
     * @return Generator
     * @throws Exception|GuzzleException
     */
    public function ask(
        string $prompt,
        string $conversationId = null,
        string $parentId = null,
        $account = null,
        bool   $stream = false
    ): Generator {
        // 如果账号为空，则随机选择一个账号
        if ($account === null) {
            $account = array_rand($this->accounts);

            try {
                $token = $this->accessTokenToJWT($this->accounts[$account]['access_token']);
            } catch (Exception $e) {
                throw new Exception("Account " . $account . " is invalid");
            }
        } else {
            $token = isset($this->accounts[$account]['access_token']) ? $this->accessTokenToJWT($this->accounts[$account]['access_token']) : null;
        }

        // 如果账号为空，则抛出异常
        if ($token === null) {
            throw new Exception("No account available");
        }

        // 设置了父消息ID，必须设置会话ID
        if ($parentId !== null && $conversationId === null) {
            throw new Exception("conversation_id must be set once parent_id is set");
        }

        // 如果会话ID与父消息ID都为空，则开启新的会话
        if ($conversationId === null && $parentId === null) {
            $parentId = (string)Uuid::uuid4();
        }

        // 如果会话ID不为空，但是父消息ID为空，则尝试从ChatGPT获取历史记录
        if ($conversationId !== null && $parentId === null) {
            try {
                $response = $this->http->get('conversation/' . $conversationId, [
                    'headers' => [
                        'Authorization' => $token,
                        'Content-Type' => 'application/json',
                        'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 Edg/110.0.1587.63',
                        'Referer' => 'https://chat.openai.com/chat',
                    ],
                ]);
            } catch (GuzzleException $e) {
                throw new Exception("Request failed: " . $e->getMessage());
            }

            $response = json_decode($response->getBody()->getContents(), true);
            if (isset($response['current_node'])) {
                // 如果获取到了父消息ID，则使用该父消息ID
                $conversationId = $response['current_node'];
            } else {
                // 如果没有获取到父消息ID，则开启新的会话
                $conversationId = null;
                $parentId = (string)Uuid::uuid4();
            }
        }

        $data = [
            'action' => 'next',
            'messages' => [
                [
                    'id' => (string)Uuid::uuid4(),
                    'role' => 'user',
                    'author' => ['role' => 'user'],
                    'content' => ['content_type' => 'text', 'parts' => [$prompt]],
                ],
            ],
            'conversation_id' => $conversationId,
            'parent_message_id' => $parentId,
            'model' => $this->accounts[$account]['model'],
            'arkose_token' => $this->accounts[$account]['arkose_token'],
            'history_and_training_disabled' => $this->accounts[$account]['history_and_training_disabled'],
        ];

        try {
            $response = $this->http->post(
                'conversation',
                [
                    'json' => $data,
                    'headers' => [
                        'Authorization' => $token,
                        'Accept' => 'text/event-stream',
                        'Content-Type' => 'application/json',
                        'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 Edg/110.0.1587.63',
                        'X-Openai-Assistant-App-Id' => '',
                        'Connection' => 'close',
                        'Accept-Language' => 'en-US,en;q=0.9',
                        'Referer' => 'https://chat.openai.com/chat',
                    ],
                    'stream' => true,
                ]
            );
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                throw new Exception(Psr7\Message::toString($e->getResponse()));
            } else {
                throw new Exception($e->getMessage());
            }
        }

        $answer = '';
        $conversationId = '';
        $messageId = '';
        $model = '';

        // 流模式下，返回一个生成器
        if ($stream) {
            $data = $response->getBody();
            while (! $data->eof()) {
                $raw = Psr7\Utils::readLine($data);
                $line = self::formatStreamMessage($raw);
                if (self::checkFields($line)) {
                    $answer = $line['message']['content']['parts'][0];
                    $conversationId = $line['conversation_id'] ?? null;
                    $messageId = $line['message']['id'] ?? null;
                    $model = $line["message"]["metadata"]["model_slug"] ?? null;
                    $finish_details = $line["message"]["metadata"]["finish_details"]['type'] ?? null;
                    $end_turn = $line["message"]["end_turn"] ?? true;
                    $recipient = $line["message"]["recipient"] ?? "all";
                    $citations = $line["message"]["metadata"]["citations"] ?? [];

                    yield [
                        "answer" => $answer,
                        "id" => $messageId,
                        'conversation_id' => $conversationId,
                        "model" => $model,
                        "account" => $account,
                        'finish_details' => $finish_details,
                        'end_turn' => $end_turn,
                        'recipient' => $recipient,
                        'citations' => $citations,
                    ];
                }
                unset($raw, $line);
            }
        } else {
            foreach (explode("\n", $response->getBody()) as $line) {
                $line = trim($line);
                if ($line === 'Internal Server Error') {
                    throw new Exception($line);
                }
                if ($line === '') {
                    continue;
                }

                $line = $this->formatStreamMessage($line);

                if (! $this->checkFields($line)) {
                    if (isset($line["detail"]) && $line["detail"] === "Too many requests in 1 hour. Try again later.") {
                        throw new Exception("Rate limit exceeded");
                    }
                    if (isset($line["detail"]) && $line["detail"] === "Conversation not found") {
                        throw new Exception("Conversation not found");
                    }
                    if (isset($line["detail"]) && $line["detail"] === "Something went wrong, please try reloading the conversation.") {
                        throw new Exception("Something went wrong, please try reloading the conversation.");
                    }
                    if (isset($line["detail"]) && $line["detail"] === "invalid_api_key") {
                        throw new Exception("Invalid access token");
                    }
                    if (isset($line["detail"]) && $line["detail"] === "invalid_token") {
                        throw new Exception("Invalid access token");
                    }

                    continue;
                }

                if ($line['message']['content']['parts'][0] === $prompt) {
                    continue;
                }

                $answer = $line['message']['content']['parts'][0];
                $conversationId = $line['conversation_id'] ?? null;
                $messageId = $line['message']['id'] ?? null;
                $model = $line["message"]["metadata"]["model_slug"] ?? null;
                $finish_details = $line["message"]["metadata"]["finish_details"]['type'] ?? null;
                $end_turn = $line["message"]["end_turn"] ?? true;
                $recipient = $line["message"]["recipient"] ?? "all";
                $citations = $line["message"]["metadata"]["citations"] ?? [];
            }

            yield [
                'answer' => $answer,
                'id' => $messageId,
                'conversation_id' => $conversationId,
                'model' => $model,
                'account' => $account,
                'finish_details' => $finish_details,
                'end_turn' => $end_turn,
                'recipient' => $recipient,
                'citations' => $citations,
            ];
        }
    }

    /**
     * 续写
     *
     * @param string|null $conversationId
     * @param string|null $parentId
     * @param mixed $account
     * @param bool $stream
     *
     * @return Generator
     * @throws Exception|GuzzleException
     */
    public function continueWrite(
        string $prompt,
        string $conversationId = null,
        string $parentId = null,
        $account = null,
        bool   $stream = false
    ): Generator {
        if ($account === null) {
            throw new Exception("Continue write must set account");
        } else {
            $token = isset($this->accounts[$account]['access_token']) ? $this->accessTokenToJWT($this->accounts[$account]['access_token']) : null;
        }

        if ($token === null) {
            throw new Exception("No account available");
        }

        if ($parentId == null || $conversationId === null) {
            throw new Exception("Continue write must set conversationId and parentId");
        }

        $data = [
            'action' => 'continue',
            'conversation_id' => $conversationId,
            'parent_message_id' => $parentId,
            'model' => $this->accounts[$account]['model'],
            'arkose_token' => $this->accounts[$account]['arkose_token'],
            'history_and_training_disabled' => $this->accounts[$account]['history_and_training_disabled'],
        ];

        try {
            $response = $this->http->post(
                'conversation',
                [
                    'json' => $data,
                    'headers' => [
                        'Authorization' => $token,
                        'Accept' => 'text/event-stream',
                        'Content-Type' => 'application/json',
                        'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 Edg/110.0.1587.63',
                        'X-Openai-Assistant-App-Id' => '',
                        'Connection' => 'close',
                        'Accept-Language' => 'en-US,en;q=0.9',
                        'Referer' => 'https://chat.openai.com/chat',
                    ],
                    'stream' => true,
                ]
            );
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                throw new Exception(Psr7\Message::toString($e->getResponse()));
            } else {
                throw new Exception($e->getMessage());
            }
        }

        $answer = '';
        $conversationId = '';
        $messageId = '';
        $model = '';

        // 流模式下，返回一个生成器
        if ($stream) {
            $data = $response->getBody();
            while (! $data->eof()) {
                $raw = Psr7\Utils::readLine($data);
                $line = self::formatStreamMessage($raw);
                if (self::checkFields($line)) {
                    $answer = $line['message']['content']['parts'][0];
                    $conversationId = $line['conversation_id'] ?? null;
                    $messageId = $line['message']['id'] ?? null;
                    $model = $line["message"]["metadata"]["model_slug"] ?? null;
                    $finish_details = $line["message"]["metadata"]["finish_details"]['type'] ?? null;
                    $end_turn = $line["message"]["end_turn"] ?? true;
                    $recipient = $line["message"]["recipient"] ?? "all";
                    $citations = $line["message"]["metadata"]["citations"] ?? [];

                    yield [
                        "answer" => $answer,
                        "id" => $messageId,
                        'conversation_id' => $conversationId,
                        "model" => $model,
                        "account" => $account,
                        'finish_details' => $finish_details,
                        'end_turn' => $end_turn,
                        'recipient' => $recipient,
                        'citations' => $citations,
                    ];
                }
                unset($raw, $line);
            }
        } else {
            foreach (explode("\n", $response->getBody()) as $line) {
                $line = trim($line);
                if ($line === 'Internal Server Error') {
                    throw new Exception($line);
                }
                if ($line === '') {
                    continue;
                }

                $line = $this->formatStreamMessage($line);

                if (! $this->checkFields($line)) {
                    if (isset($line["detail"]) && $line["detail"] === "Too many requests in 1 hour. Try again later.") {
                        throw new Exception("Rate limit exceeded");
                    }
                    if (isset($line["detail"]) && $line["detail"] === "Conversation not found") {
                        throw new Exception("Conversation not found");
                    }
                    if (isset($line["detail"]) && $line["detail"] === "Something went wrong, please try reloading the conversation.") {
                        throw new Exception("Something went wrong, please try reloading the conversation.");
                    }
                    if (isset($line["detail"]) && $line["detail"] === "invalid_api_key") {
                        throw new Exception("Invalid access token");
                    }
                    if (isset($line["detail"]) && $line["detail"] === "invalid_token") {
                        throw new Exception("Invalid access token");
                    }

                    continue;
                }

                if ($line['message']['content']['parts'][0] === $prompt) {
                    continue;
                }

                $answer = $line['message']['content']['parts'][0];
                $conversationId = $line['conversation_id'] ?? null;
                $messageId = $line['message']['id'] ?? null;
                $model = $line["message"]["metadata"]["model_slug"] ?? null;
                $finish_details = $line["message"]["metadata"]["finish_details"]['type'] ?? null;
                $end_turn = $line["message"]["end_turn"] ?? true;
                $recipient = $line["message"]["recipient"] ?? "all";
                $citations = $line["message"]["metadata"]["citations"] ?? [];
            }

            yield [
                'answer' => $answer,
                'id' => $messageId,
                'conversation_id' => $conversationId,
                'model' => $model,
                'account' => $account,
                'finish_details' => $finish_details,
                'end_turn' => $end_turn,
                'recipient' => $recipient,
                'citations' => $citations,
            ];
        }
    }

    /**
     * 获取会话列表
     *
     * @param int $offset
     * @param int $limit
     * @param mixed $account
     *
     * @return array
     * @throws Exception
     */
    public function getConversations(int $offset = 0, int $limit = 20, $account = 0): array
    {
        try {
            $token = $this->accessTokenToJWT($this->accounts[$account]['access_token']);
        } catch (Exception $e) {
            throw new Exception("Invalid account");
        }

        try {
            $response = $this->http->get('conversations', [
                'headers' => [
                    'Authorization' => $token,
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 Edg/110.0.1587.63',
                    'Referer' => 'https://chat.openai.com/chat',
                ],
                'query' => [
                    'offset' => $offset,
                    'limit' => $limit,
                ],
            ])->getBody()->getContents();
        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage());
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Response is not json');
        }

        if (! isset($data['items'])) {
            throw new Exception('Field missing');
        }

        return $data['items'];
    }

    /**
     * 获取会话消息列表
     *
     * @param string $conversationId
     * @param mixed $account
     *
     * @return array
     * @throws Exception
     */
    public function getConversationMessages(string $conversationId, $account = 0): array
    {
        try {
            $token = $this->accessTokenToJWT($this->accounts[$account]['access_token']);
        } catch (Exception $e) {
            throw new Exception("Invalid account");
        }

        try {
            $response = $this->http->get('conversation/' . $conversationId, [
                'headers' => [
                    'Authorization' => $token,
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 Edg/110.0.1587.63',
                    'Referer' => 'https://chat.openai.com/chat',
                ],
            ])->getBody()->getContents();
        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage());
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Response is not json');
        }

        return $data;
    }

    /**
     * 生成会话标题
     *
     * @param string $conversationId
     * @param string $messageId
     * @param mixed $account
     *
     * @return bool
     * @throws Exception
     */
    public function generateConversationTitle(string $conversationId, string $messageId, $account = 0): bool
    {
        try {
            $token = $this->accessTokenToJWT($this->accounts[$account]['access_token']);
        } catch (Exception $e) {
            throw new Exception("Invalid account");
        }

        try {
            $response = $this->http->post('conversation/gen_title/' . $conversationId, [
                'headers' => [
                    'Authorization' => $token,
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 Edg/110.0.1587.63',
                    'Referer' => 'https://chat.openai.com/chat',
                ],
                'json' => [
                    'message_id' => $messageId,
                    'model' => 'text-davinci-002-render',
                ],
            ])->getBody()->getContents();
        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage());
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Response is not json');
        }

        if (isset($data['title'])) {
            return true;
        }

        return false;
    }

    /**
     * 修改会话标题
     *
     * @param string $conversationId
     * @param string $title
     * @param mixed $account
     *
     * @return bool
     * @throws Exception
     */
    public function updateConversationTitle(string $conversationId, string $title, $account = 0): bool
    {
        try {
            $token = $this->accessTokenToJWT($this->accounts[$account]['access_token']);
        } catch (Exception $e) {
            throw new Exception("Invalid account");
        }

        try {
            $response = $this->http->patch('conversation/' . $conversationId, [
                'headers' => [
                    'Authorization' => $token,
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 Edg/110.0.1587.63',
                    'Referer' => 'https://chat.openai.com/chat',
                ],
                'json' => [
                    'title' => $title,
                ],
            ])->getBody()->getContents();
        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage());
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Response is not json');
        }

        if (isset($data['success']) && $data['success'] === true) {
            return true;
        }

        return false;
    }

    /**
     * 删除会话
     *
     * @param string $conversationId
     * @param mixed $account
     *
     * @return bool
     * @throws Exception
     */
    public function deleteConversation(string $conversationId, $account = 0): bool
    {
        try {
            $token = $this->accessTokenToJWT($this->accounts[$account]['access_token']);
        } catch (Exception $e) {
            throw new Exception("Invalid account");
        }

        try {
            $response = $this->http->patch('conversation/' . $conversationId, [
                'headers' => [
                    'Authorization' => $token,
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 Edg/110.0.1587.63',
                    'Referer' => 'https://chat.openai.com/chat',
                ],
                'json' => [
                    'is_visible' => false,
                ],
            ])->getBody()->getContents();
        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage());
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Response is not json');
        }

        if (isset($data['success']) && $data['success'] === true) {
            return true;
        }

        return false;
    }

    /**
     * 清空会话
     *
     * @param mixed $account
     *
     * @return bool
     * @throws Exception
     */
    public function clearConversations($account = 0): bool
    {
        try {
            $token = $this->accessTokenToJWT($this->accounts[$account]['access_token']);
        } catch (Exception $e) {
            throw new Exception("Invalid account");
        }

        try {
            $response = $this->http->patch('conversations', [
                'headers' => [
                    'Authorization' => $token,
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 Edg/110.0.1587.63',
                    'Referer' => 'https://chat.openai.com/chat',
                ],
                'json' => [
                    'is_visible' => false,
                ],
            ])->getBody()->getContents();
        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage());
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Response is not json');
        }

        if (isset($data['success']) && $data['success'] === true) {
            return true;
        }

        return false;
    }

    /**
     * 获取插件列表
     *
     * @param int $offset
     * @param int $limit
     * @param string $status
     * @param mixed $account
     *
     * @return array
     */
    public function getPlugins(int $offset = 0, int $limit = 250, string $status = 'approved', $account = 0): array
    {
        try {
            $token = $this->accessTokenToJWT($this->accounts[$account]['access_token']);
        } catch (Exception $e) {
            throw new Exception("Invalid account");
        }

        try {
            $response = $this->http->get('aip/p', [
                'headers' => [
                    'Authorization' => $token,
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 Edg/110.0.1587.63',
                    'Referer' => 'https://chat.openai.com/chat',
                ],
                'query' => [
                    'offset' => $offset,
                    'limit' => $limit,
                    'status' => $status,
                ],
            ])->getBody()->getContents();
        } catch (GuzzleException $e) {
            return [];
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        return $data;
    }

    /**
     * 安装插件
     *
     * @param string $pluginId
     * @param mixed $account
     *
     * @return bool
     */
    public function installPlugin(string $pluginId, $account = 0): bool
    {
        try {
            $token = $this->accessTokenToJWT($this->accounts[$account]['access_token']);
        } catch (Exception $e) {
            throw new Exception("Invalid account");
        }

        try {
            $response = $this->http->patch('aip/p/' . $pluginId . '/user-settings', [
                'headers' => [
                    'Authorization' => $token,
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 Edg/110.0.1587.63',
                    'Referer' => 'https://chat.openai.com/chat',
                ],
            ])->getBody()->getContents();
        } catch (GuzzleException $e) {
            return false;
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }

        return true;
    }

    /**
     * 获取未验证插件
     *
     * @param string $domain
     * @param mixed $account
     *
     * @return array
     */
    public function getUnverifiedPlugins(string $domain = '', $account = 0): array
    {
        try {
            $token = $this->accessTokenToJWT($this->accounts[$account]['access_token']);
        } catch (Exception $e) {
            throw new Exception("Invalid account");
        }

        try {
            $response = $this->http->get('aip/p/domain', [
                'headers' => [
                    'Authorization' => $token,
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 Edg/110.0.1587.63',
                    'Referer' => 'https://chat.openai.com/chat',
                ],
                'query' => [
                    'domain' => $domain,
                ],
            ])->getBody()->getContents();
        } catch (GuzzleException $e) {
            return [];
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        return $data;
    }

    /**
     * 设置保存聊天记录与训练
     *
     * @param bool $save
     * @param mixed $account
     *
     * @return bool
     */
    public function setChatHistoryAndTraining(bool $save, $account = 0): bool
    {
        try {
            $token = $this->accessTokenToJWT($this->accounts[$account]['access_token']);
        } catch (Exception $e) {
            throw new Exception("Invalid account");
        }

        try {
            $response = $this->http->get('models', [
                'headers' => [
                    'Authorization' => $token,
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36 Edg/110.0.1587.63',
                    'Referer' => 'https://chat.openai.com/chat',
                ],
                'query' => [
                    'history_and_training_disabled' => ! $save,
                ],
            ])->getBody()->getContents();
        } catch (GuzzleException $e) {
            return false;
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }

        return true;
    }

    /**
     * 检查响应行是否包含必要的字段
     *
     * @param mixed $line
     *
     * @return bool
     */
    public function checkFields($line): bool
    {
        return isset($line['message']['content']['parts'][0])
            && isset($line['conversation_id'])
            && isset($line['message']['id']);
    }

    /**
     * 格式化流消息为数组
     *
     * @param string $line
     *
     * @return array|false
     */
    public function formatStreamMessage(string $line)
    {
        preg_match('/data: (.*)/', $line, $matches);
        if (empty($matches[1])) {
            return false;
        }

        $line = $matches[1];
        $data = json_decode($line, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }

        return $data;
    }

    /**
     * access_token 转换为 JWT
     *
     * @param string $accessToken
     *
     * @return string
     * @throws Exception
     */
    private function accessTokenToJWT(string $accessToken): string
    {
        try {
            $sAccessToken = explode(".", $accessToken);
            $sAccessToken[1] .= str_repeat("=", (4 - strlen($sAccessToken[1]) % 4) % 4);
            $dAccessToken = base64_decode($sAccessToken[1]);
            $dAccessToken = json_decode($dAccessToken, true);
        } catch (Exception $e) {
            throw new Exception("Access token invalid");
        }

        // 检查是否过期
        $exp = $dAccessToken['exp'] ?? null;
        if ($exp !== null && $exp < time()) {
            throw new Exception("Access token expired");
        }

        return 'Bearer ' . $accessToken;
    }

    /**
     * 获取arkose_token
     *
     * @return string
     * @throws Exception
     */
    public function getArkoseToken(): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://ai.fakeopen.com/api/arkose/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new Exception('Request arkose token failed');
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Request arkose response is not json');
        }

        if (! isset($data['token'])) {
            throw new Exception('Request arkose token failed');
        }

        return $data['token'];
    }
}
