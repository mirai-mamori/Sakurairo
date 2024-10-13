<?php

namespace IROChatGPT {

    use Exception;
    use Orhanerday\OpenAi\OpenAi;
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
        $chatGPT_base_url = iro_opt('chatgpt_base_url');
        $chatGPT_access_token = iro_opt('chatgpt_access_token');
        $chatGPT_prompt_init = iro_opt('chatgpt_init_prompt', DEFAULT_INIT_PROMPT);
        $chatGPT_model = iro_opt('chatgpt_model', DEFAULT_MODEL);

        if (empty($chatGPT_base_url) || empty($chatGPT_access_token) || empty($chatGPT_prompt_init) || empty($chatGPT_model)) {
            throw new Exception("Missing required ChatGPT configuration.");
        }

        $open_ai = new OpenAi($chatGPT_access_token);
        if (str_ends_with($chatGPT_base_url, '/')) {
            $chatGPT_base_url = substr($chatGPT_base_url, 0, -1);
        }
        $open_ai->setBaseURL($chatGPT_base_url);

        $chat = $open_ai->chat([
            "model" => $chatGPT_model,
            "messages" => [
                [
                    "role" => "system",
                    "content" => $chatGPT_prompt_init
                ],
                [
                    "role" => "user",
                    "content" => "Title: " . $post->post_title
                ],
                [
                    "role" => "user",
                    "content" => "Context: " . mb_substr(preg_replace("/(\\s)\\s{2,}/", "$1",wp_strip_all_tags(apply_filters('the_content', $post->post_content))), 0, 4050)
                ],
            ]
        ]);

        $decoded_chat = json_decode($chat);
        if (is_null($decoded_chat) || isset($decoded_chat->error)) {
            throw new Exception("ChatGPT error: " . json_encode($decoded_chat));
        }

        return $decoded_chat->choices[0]->message->content;
    }
}
