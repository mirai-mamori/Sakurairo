<?php

namespace IROChatGPT {

    use Exception;
    use WP_Post;

    define("DEFAULT_INIT_PROMPT", "请以作者的身份，以激发好奇吸引阅读为目的，结合文章核心观点来提取的文章中最吸引人的内容，为以下文章编写一个用词精炼简短、110字以内、与文章语言一致的引言。");
    define("DEFAULT_MODEL", "gpt-3.5-turbo");
    define('POST_METADATA_KEY', "ai_summon_excerpt");

    function apply_chatgpt_hook()
    {
        if (iro_opt('chatgpt_article_summarize')) {
            $exclude_ids = iro_opt('chatgpt_exclude_ids', '');
            add_action('save_post_post', function (int $post_id, WP_Post $post, bool $update) use ($exclude_ids) {
                if (!has_excerpt($post_id) && !in_array($post_id, explode(",", $exclude_ids), false)) {
                    try {
                        $excerpt = summon_article_excerpt($post);
                        update_post_meta($post_id, POST_METADATA_KEY, $excerpt);
                    } catch (\Throwable $th) {
                        error_log('ChatGPT-excerpt-err:' . $th);
                    }
                }
            }, 10, 3);
            add_filter('the_excerpt', function (string $post_excerpt) {
                global $post;
                if (has_excerpt($post)) {
                    return $post_excerpt;
                } else {
                    $ai_excerpt =  get_post_meta($post->ID, POST_METADATA_KEY, true);
                    return $ai_excerpt ? $ai_excerpt : $post_excerpt;
                }
            });
        }
    }

    function summon_article_excerpt(WP_Post $post)
    {
        $chatgpt_endpoint = iro_opt('chatgpt_endpoint');
        $chatGPT_access_token = iro_opt('chatgpt_access_token');
        $chatGPT_prompt_init = iro_opt('chatgpt_init_prompt', DEFAULT_INIT_PROMPT);
        $chatGPT_model = iro_opt('chatgpt_model', DEFAULT_MODEL);

        if (empty($chatgpt_endpoint) || empty($chatGPT_access_token) || empty($chatGPT_prompt_init) || empty($chatGPT_model)) {
            throw new Exception("Missing required ChatGPT configuration.");
        }


        // 构造请求 payload
        $payload = [
            "model"    => $chatGPT_model,
            "messages" => [
                [
                    "role"    => "system",
                    "content" => $chatGPT_prompt_init,
                ],
                [
                    "role"    => "user",
                    "content" => "Title：" . $post->post_title . "\n\n" .
                                 "Content：" . mb_substr(
                                     preg_replace(
                                         "/(\\s)\\s{2,}/",
                                         "$1",
                                         wp_strip_all_tags(apply_filters('the_content', $post->post_content))
                                     ),
                                     0,
                                     4050
                                 ),
                ],
            ],
        ];

        // === 替换开始：使用 cURL 发出请求 ===
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $chatgpt_endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $chatGPT_access_token
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
        $chat = curl_exec($ch);
        if ($chat === false) {
            throw new Exception("cURL error: " . curl_error($ch));
        }
        curl_close($ch);
        // === 替换结束 ===

        // 输出 API 原始响应调试信息
        error_log("GPT error: " . $chat);

        $decoded_chat = json_decode($chat);
        if (is_null($decoded_chat) || isset($decoded_chat->error)) {
            throw new Exception("ChatGPT error: " . json_encode($decoded_chat));
        }

        return $decoded_chat->choices[0]->message->content;
    }

    // 添加文章保存钩子，用于生成注释
    // add_action('save_post', __NAMESPACE__ . '\maybe_generate_annotations', 10, 3);
    // 添加内容过滤器，显示注释
    // 时机9，提前于短代码处理
    add_filter('the_content', __NAMESPACE__ . '\display_term_annotations', 9);

    /**
     * 当文章保存时，检查是否需要生成注释
     */
    function maybe_generate_annotations($post_id, $post, $update) {
        // 避免自动保存和修订版本
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (wp_is_post_revision($post_id)) return;
        if (wp_is_post_autosave($post_id)) return;
        
        // 只处理文章和页面
        if (!in_array($post->post_type, ['post', 'page'])) {
            return;
        }
        
        // 检查ChatGPT功能是否启用
        if (!get_option('iro_chatgpt_enabled', false)) {
            return;
        }
        
        // 添加调试日志
        error_log('IRO ChatGPT: 开始为文章 ' . $post_id . ' 生成注释');
        
        // 强制重新生成注释 (可根据需要修改为仅在内容变更时生成)
        delete_post_meta($post_id, 'iro_chatgpt_annotations');
        
        // 异步生成注释，避免拖慢保存过程
        wp_schedule_single_event(time(), 'iro_generate_post_annotations', array($post_id));
    }

    // 注册异步事件钩子
    add_action('iro_generate_post_annotations', __NAMESPACE__ . '\process_scheduled_annotations');

    /**
     * 异步处理文章注释生成
     */
    function process_scheduled_annotations($post_id) {
        $post = get_post($post_id);
        if (!$post) {
            error_log('IRO ChatGPT: 找不到ID为 ' . $post_id . ' 的文章');
            return;
        }
        
        error_log('IRO ChatGPT: 处理文章 ' . $post_id . ' 的注释生成');
        generate_post_annotations($post);
    }

    /**
     * 生成文章的复杂名词注释
     */
    function generate_post_annotations($post) {
        // 获取API密钥
        $api_key = get_option('iro_chatgpt_api_key', '');
        if (empty($api_key)) {
            error_log('IRO ChatGPT: API密钥未设置');
            return false;
        }
        
        // 处理文章内容
        $content = wp_strip_all_tags($post->post_content);
        if (strlen($content) < 100) {
            error_log('IRO ChatGPT: 文章内容太短，跳过注释生成');
            return false;
        }
        
        // 调用ChatGPT API生成注释
        $annotations = call_chatgpt_for_annotations($content);
        
        // 保存注释到文章自定义字段
        if (!empty($annotations)) {
            error_log('IRO ChatGPT: 为文章 ' . $post->ID . ' 生成了 ' . count($annotations) . ' 个注释');
            update_post_meta($post->ID, 'iro_chatgpt_annotations', $annotations);
            return true;
        } else {
            error_log('IRO ChatGPT: 未能为文章 ' . $post->ID . ' 生成注释');
            return false;
        }
    }

    /**
     * 调用ChatGPT API生成复杂名词注释
     */
    function call_chatgpt_for_annotations($content) {
        // 使用正确的选项名获取API配置
        $api_endpoint = iro_opt('chatgpt_endpoint', 'https://api.openai.com/v1/chat/completions');
        $api_key = iro_opt('chatgpt_access_token', '');
        $model = iro_opt('chatgpt_model', 'gpt-3.5-turbo');
        
        // 如果找不到API密钥，返回空数组
        if (empty($api_key)) {
            error_log('IROChatGPT: No API key found');
            return [];
        }
        
        $max_length = 7000;
        
        // 截取内容
        if (strlen($content) > $max_length) {
            $content = substr($content, 0, $max_length);
        }
        
        // 构建提示词
        $prompt = "分析以下文章正文内容(排除标题及引语类文本)，用最认真的态度和较为严格的识别标准筛选出专业术语、复杂概念、事件、社会热点、网络黑话烂梗热词、晦涩难懂、与文章语言不同的名词，并根据文章主要语言提供对应语言的简短解释。若文章出现与“事件”，“热点”，“介绍”等具有提示上下文功能的含义的名词时，请务必用最高优先级在前后查找符合要求的名词。名词选取时需要排除日常常用的名词、非著名人物的人名。仅返回JSON格式，格式为：{\"术语1\":\"解释1\", \"术语2\":\"解释2\", ...}。注意不要出现在原文中并没有出现的名词，生成的名词越多越好：\n\n" . $content;
        
        // API请求参数
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key  // 修复这里的引号问题
        ];
        
        $data = [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => '你是一个专业的文章分析助手，擅长识别文章中专业术语、复杂概念、事件、社会热点、网络黑话烂梗热词、晦涩难懂等内容并提供简明解释。'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.3,
            'max_tokens' => 800
        ];
        
        error_log('IROChatGPT: 发送API请求到 ' . $api_endpoint);
        
        // 发送请求
        $ch = curl_init($api_endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);
        
        if ($err) {
            error_log('IROChatGPT API Error: ' . $err);
            return [];
        }
        
        error_log('IROChatGPT: API返回状态码: ' . $http_code);
        
        // 解析响应
        $result = json_decode($response, true);
        if (isset($result['choices'][0]['message']['content'])) {
            $content = $result['choices'][0]['message']['content'];
            
            // 记录原始响应
            error_log('IROChatGPT: API返回内容: ' . substr($content, 0, 200) . '...');
            
            // 尝试提取JSON部分
            preg_match('/\{.*\}/s', $content, $matches);
            if (!empty($matches[0])) {
                $json_str = $matches[0];
                error_log('IROChatGPT: 提取的JSON: ' . $json_str);
                
                $annotations = json_decode($json_str, true);
                if (is_array($annotations) && !empty($annotations)) {
                    error_log('IROChatGPT: 成功解析JSON，获取到 ' . count($annotations) . ' 个注释');
                    return $annotations;
                } else {
                    error_log('IROChatGPT: JSON解析失败: ' . json_last_error_msg());
                }
            } else {
                error_log('IROChatGPT: 未能从响应中提取JSON，原始响应: ' . $content);
            }
        } else {
            error_log('IROChatGPT: API响应格式无效，无法获取内容。响应: ' . substr($response, 0, 200) . '...');
        }
        
        return [];
    }

    /**
     * 在前端显示文章中的复杂名词注释
     */
    function display_term_annotations($content) {
        if (!is_singular()) {
            return $content;
        }
        
        global $post;
        $annotations = get_post_meta($post->ID, 'iro_chatgpt_annotations', true);
        
        if (empty($annotations) || !is_array($annotations)) {
            error_log("IROChatGPT: 文章 {$post->ID} 没有注释数据或数据格式不正确");
            return $content;
        }
        
        error_log("IROChatGPT: 文章 {$post->ID} 具有 " . count($annotations) . " 个注释");

        // 短代码占位
        $shortcode_placeholders = [];
        $content = preg_replace_callback('/(\[\/?[^\]]+\])/', function($matches) use (&$shortcode_placeholders) {
            $token = '###SHORTCODE_' . count($shortcode_placeholders) . '###';
            $shortcode_placeholders[$token] = $matches[1];
            return $token;
        }, $content);
        
        // 处理内容，标记复杂名词
        $terms = array_keys($annotations);
        // 按长度降序排序，确保先替换长词汇
        usort($terms, function($a, $b) {
            return mb_strlen($b) - mb_strlen($a);
        });
        
        try {
            $dom = new \DOMDocument();
            // 使用libxml错误控制
            $prev = libxml_use_internal_errors(true);
            // 添加UTF-8标头以确保正确处理中文
            $html = '<?xml encoding="UTF-8"><div>' . $content . '</div>';
            $dom->loadHTML($html, LIBXML_HTML_NODEFDTD);
            libxml_clear_errors();
            libxml_use_internal_errors($prev);
            
            $xpath = new \DOMXPath($dom);
            $textNodes = $xpath->query('//text()[
                not(ancestor::script) and 
                not(ancestor::style) and 
                not(ancestor::a) and 
                not(ancestor::header) and 
                not(ancestor::nav) and 
                not(ancestor::meting) and 
                not(ancestor::img) and 
                not(ancestor::video) and 
                not(ancestor::audio) and 
                not(ancestor::input) and 
                not(ancestor::option) and 
                not(ancestor::select) and 
                not(ancestor::button) and 
                not(ancestor::code) and 
                not(ancestor::h1) and 
                not(ancestor::h2) and 
                not(ancestor::h3) and 
                not(ancestor::h4) and 
                not(ancestor::h5) and 
                not(ancestor::h6)
            ]');
            
            // 调试信息
            error_log("IROChatGPT: 找到 " . $textNodes->length . " 个文本节点");
            
            $annotationIndex = 1;
            $annotationMap = [];
            $termsFound = false;
            
            foreach ($textNodes as $textNode) {
                $text = $textNode->nodeValue;
                $modified = false;
                
                foreach ($terms as $term) {
                    if (empty($term) || mb_strlen($term) < 2) continue;
                    
                    if (mb_stripos($text, $term) !== false) {
                        // 创建注释索引
                        if (!isset($annotationMap[$term])) {
                            $annotationMap[$term] = $annotationIndex++;
                        }
                        
                        // 给术语添加标记
                        $pattern = '/' . preg_quote($term, '/') . '/ui';
                        $replacement = '$0<sup class="iro-term-annotation" data-term="' . htmlspecialchars($term, ENT_QUOTES) . '" data-id="' . $annotationMap[$term] . '">【' . $annotationMap[$term] . '】</sup>';
                        $newText = preg_replace($pattern, $replacement, $text, 1);
                        
                        if ($newText !== $text) {
                            $text = $newText;
                            $modified = true;
                            $termsFound = true;
                        }
                    }
                }
                
                if ($modified) {
                    // 创建文档片段
                    $fragment = $dom->createDocumentFragment();
                    // 确保正确处理HTML实体
                    @$fragment->appendXML($text);
                    $textNode->parentNode->replaceChild($fragment, $textNode);
                }
            }
            
            if (!$termsFound) {
                error_log("IROChatGPT: 未在内容中找到任何匹配的术语");
                return $content;
            }
            
            // 获取修改后的内容
            $newContent = $dom->saveHTML($dom->documentElement);
            // 移除添加的外层div
            $newContent = preg_replace('/<\/?div[^>]*>/', '', $newContent);
            // 移除XML声明
            $newContent = preg_replace('/<\?xml[^>]+>/', '', $newContent);

            foreach ($shortcode_placeholders as $token => $shortcode) {
                $newContent = str_replace($token, $shortcode, $newContent);
            }
            
            error_log("IROChatGPT: 成功处理注释标记，找到术语: " . implode(", ", array_keys($annotationMap)));
            
            return $newContent;
        } catch (\Exception $e) {
            error_log("IROChatGPT 错误: " . $e->getMessage());
            return $content; // 出错时返回原内容
        }
    }
}