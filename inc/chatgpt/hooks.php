<?php

namespace IROChatGPT {

    use HaoZiTeam\ChatGPT\V2 as ChatGPTV2;
    use WP_Post;

    define('MAX_MESSAGE_LENGTH', 4096);
    define("DEFAULT_INIT_PROMPT", "You are a excerpt generator. " .
        "You can summarize articles given their title and full text. " .
        "You should use the same language as the article for your excerpt. " .
        "You do not need to write in third person.");
    define('DEFAULT_ASK_PROMPT', "Please summarize articles provided before.");
    define('POST_METADATA_KEY', "ai_summon_excerpt");

    function is_not_in_excluded_ids($id_list_str, $post_id) {
        $id_list_str = str_replace(" ", "", $id_list_str);
        $id_list = explode(",", $id_list_str);
        $id_list = array_map('intval', $id_list);
        return !in_array($post_id, $id_list, true);
    }

    function apply_chatgpt_hook() {
        $exclude_ids = iro_opt('chatgpt_exclude_ids','');
        $exclude_ids_arr = explode(',',$exclude_ids);
        if (iro_opt('chatgpt_article_summarize')) {
            add_action('save_post_post', function (int $post_id, WP_Post $post, bool $update) use ($exclude_ids) {
                if (is_not_in_excluded_ids($exclude_ids, $post_id) && !has_excerpt($post_id)) {
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

    function summon_article_excerpt(WP_Post $post) {
        $chatGPT_base_url = iro_opt('chatgpt_base_url');
        $chatGPT_access_token = iro_opt('chatgpt_access_token');
        $chatGPT_prompt_init = iro_opt('chatgpt_init_prompt', DEFAULT_INIT_PROMPT);
        $chatGPT_prompt_ask = iro_opt('chatgpt_ask_prompt', DEFAULT_ASK_PROMPT);

        $chatGPT = new ChatGPTV2($chatGPT_access_token, $chatGPT_base_url);
        $chatGPT->addMessage($chatGPT_prompt_init, 'system');
        $chatGPT->addMessage("文章标题：" . $post->post_title, 'user');

        $content = $post->post_content;
        $content = substr(wp_strip_all_tags(apply_filters('the_content', $content)), 0, 4050);

        $chatGPT->addMessage("正文：" . $content, 'user');
        $answer = '';

        foreach ($chatGPT->ask($chatGPT_prompt_ask) as $item) {
            $answer .= $item['answer'];
        }
        return $answer;
    }
}