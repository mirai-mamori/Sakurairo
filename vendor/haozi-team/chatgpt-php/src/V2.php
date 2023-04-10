<?php

namespace HaoZiTeam\ChatGPT;

use Exception;
use Generator;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;

class V2
{
    private $baseUrl = 'https://api.openai.com/';

    private $model = 'gpt-3.5-turbo';

    private $key;

    private $temperature = 1;

    private $topP = 1;

    private $messages = [];

    private $http;

    public function __construct(
        string $key,
        string $baseUrl = null,
        string $model = null,
        int $temperature = null,
        int $topP = null,
        int $timeout = 360
    ) {
        $this->key = 'Bearer '.$key;
        if ($baseUrl) {
            $this->baseUrl = $baseUrl;
        }
        if ($model) {
            $this->model = $model;
        }
        if ($temperature) {
            $this->temperature = $temperature;
        }
        if ($topP) {
            $this->topP = $topP;
        }

        $this->http = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => $timeout,
            'stream' => true,
        ]);
    }

    /**
     * 添加消息
     *
     * @param  string  $message
     * @param  string  $role
     *
     * @return void
     */
    public function addMessage(string $message, string $role = 'user'): void
    {
        $this->messages[] = [
            'role' => $role,
            'content' => $message,
        ];
    }

    /**
     * 发送消息
     *
     * @param  string  $prompt
     * @param  string|null  $user
     * @param  bool  $stream
     *
     * @return Generator
     * @throws Exception|GuzzleException
     */
    public function ask(string $prompt, string $user = null, bool $stream = false): Generator
    {
        // 将消息添加到消息列表中
        $this->addMessage($prompt);

        $data = [
            'model' => $this->model,
            'messages' => $this->messages,
            'stream' => $stream,
            'temperature' => $this->temperature,
            'top_p' => $this->topP,
            'n' => 1,
            'user' => $user ?? 'chatgpt-php',
        ];

        try {
            $response = $this->http->post(
                'v1/chat/completions',
                [
                    'json' => $data,
                    'headers' => [
                        'Authorization' => $this->key,
                    ],
                    'stream' => $stream,
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

        // 流模式下，返回一个生成器
        if ($stream) {
            $data = $response->getBody();
            while (! $data->eof()) {
                $raw = Psr7\Utils::readLine($data);
                $line = self::formatStreamMessage($raw);
                if (self::checkStreamFields($line)) {
                    $answer = $line['choices'][0]['delta']['content'];
                    $messageId = $line['id'];

                    yield [
                        "answer" => $answer,
                        "id" => $messageId,
                        "model" => $this->model,
                    ];
                }
                unset($raw, $line);
            }
            $this->addMessage($answer, 'assistant');
        } else {
            $data = json_decode($response->getBody()->getContents(), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Response is not json');
            }

            if (! $this->checkFields($data)) {
                throw new Exception('Field missing');
            }

            $answer = $data['choices'][0]['message']['content'];
            $this->addMessage($answer, 'assistant');

            yield [
                'answer' => $answer,
                'id' => $data['id'],
                'model' => $this->model,
                'usage' => $data['usage'],
            ];
        }
    }

    /**
     * 检查响应行是否包含必要的字段
     *
     * @param  mixed  $line
     *
     * @return bool
     */
    public function checkFields($line): bool
    {
        return isset($line['choices'][0]['message']['content']) && isset($line['id']) && isset($line['usage']);
    }

    /**
     * 检查流响应行是否包含必要的字段
     *
     * @param  mixed  $line
     *
     * @return bool
     */
    public function checkStreamFields($line): bool
    {
        return isset($line['choices'][0]['delta']['content']) && isset($line['id']);
    }

    /**
     * 格式化流消息为数组
     *
     * @param  string  $line
     *
     * @return mixed
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
}
