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
    add_filter('the_content', __NAMESPACE__ . '\display_term_annotations', 20);

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
        
        $max_length = 3000;
        
        // 截取内容
        if (strlen($content) > $max_length) {
            $content = substr($content, 0, $max_length);
        }
        
        // 构建提示词
        $prompt = "分析以下文章内容，识别出专业术语、复杂概念或难懂的名词，并提供简短的解释。仅返回JSON格式，格式为：{\"术语1\":\"解释1\", \"术语2\":\"解释2\", ...}。只需返回3-5个最重要的术语：\n\n" . $content;
        
        // API请求参数
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key  // 修复这里的引号问题
        ];
        
        $data = [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => '你是一个专业的文章分析助手，擅长识别专业术语并提供简明解释。'],
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
        
        // 添加注释脚本和样式
        add_action('wp_footer', __NAMESPACE__ . '\add_annotations_script');
        
        // 将注释数据添加为全局变量
        add_action('wp_footer', function() use ($annotations) {
            echo '<script>window.iroAnnotations = ' . json_encode($annotations) . ';</script>';
        });
        
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
            $textNodes = $xpath->query('//text()[not(ancestor::script) and not(ancestor::style)]');
            
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
            
            error_log("IROChatGPT: 成功处理注释标记，找到术语: " . implode(", ", array_keys($annotationMap)));
            
            return $newContent;
        } catch (\Exception $e) {
            error_log("IROChatGPT 错误: " . $e->getMessage());
            return $content; // 出错时返回原内容
        }
    }

    /**
     * 添加前端注释脚本和样式
     */
    function add_annotations_script() {
        // 添加内联样式
        ?>
        <style>
        .iro-term-annotation {
            color: #e74c3c;
            cursor: pointer;
            font-size: 0.8em;
            margin-left: 2px;
            vertical-align: super;
        }
        .iro-annotation-popup {
            position: absolute;
            background: #fff;
            border: 1px solid #ddd;
            padding: 10px 15px;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 9999;
            max-width: 300px;
            display: none;
        }
        .iro-annotation-popup .close {
            position: absolute;
            top: 5px;
            right: 8px;
            cursor: pointer;
            font-size: 16px;
            color: #999;
        }
        .iro-annotation-popup .term {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        .iro-annotation-popup .explanation {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
        }
        </style>
        
        <!-- 直接添加内联脚本以确保执行 -->
        <script>
        (function() {
            console.log("IROChatGPT 注释脚本已加载");
            
            // 创建注释弹出层
            function createAnnotationPopup() {
                let popup = document.getElementById('iro-annotation-popup');
                if (popup) return popup;
                
                popup = document.createElement('div');
                popup.id = 'iro-annotation-popup';
                popup.className = 'iro-annotation-popup';
                popup.innerHTML = '<span class="close">×</span><div class="term"></div><div class="explanation"></div>';
                document.body.appendChild(popup);
                
                popup.querySelector('.close').addEventListener('click', function() {
                    popup.style.display = 'none';
                });
                
                return popup;
            }
            
            // 初始化注释功能
            function initAnnotations() {
                console.log("初始化注释功能");
                createAnnotationPopup();
                
                // 检查注释数据
                if (window.iroAnnotations) {
                    console.log("找到注释数据:", Object.keys(window.iroAnnotations).length, "个术语");
                } else {
                    console.log("未找到注释数据");
                    return;
                }
                
                // 查找注释标记
                const annotationMarks = document.querySelectorAll('.iro-term-annotation');
                console.log("找到注释标记:", annotationMarks.length, "个");
                
                // 为所有注释标记添加点击事件
                annotationMarks.forEach(mark => {
                    mark.addEventListener('click', function(event) {
                        event.preventDefault();
                        event.stopPropagation();
                        
                        const term = this.getAttribute('data-term');
                        if (window.iroAnnotations && window.iroAnnotations[term]) {
                            const explanation = window.iroAnnotations[term];
                            const popup = document.getElementById('iro-annotation-popup');
                            
                            popup.querySelector('.term').textContent = term;
                            popup.querySelector('.explanation').textContent = explanation;
                            
                            const rect = this.getBoundingClientRect();
                            popup.style.top = (window.pageYOffset + rect.bottom + 10) + 'px';
                            popup.style.left = (rect.left - 50) + 'px';
                            popup.style.display = 'block';
                        }
                    });
                });
                
                // 点击其他区域关闭弹窗
                document.addEventListener('click', function(event) {
                    const popup = document.getElementById('iro-annotation-popup');
                    if (popup && !event.target.closest('.iro-term-annotation, .iro-annotation-popup')) {
                        popup.style.display = 'none';
                    }
                });
            }
            
            // 在DOM加载完成后初始化
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initAnnotations);
            } else {
                initAnnotations();
            }
            
            // 适配pjax
            document.addEventListener('pjax:complete', initAnnotations);
            
            // 暴露初始化函数给全局使用
            window.iroInitAnnotations = initAnnotations;
        })();
        </script>
        <?php
    }
}