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
    /**
     * 调用ChatGPT API生成指定文章的摘要并返回
     */
    function summon_article_excerpt(WP_Post $post)
    {
        $chatGPT = new ChatGPTV2(iro_opt('chatgpt_access_token'), iro_opt('chatgpt_base_url'));

        $chatGPT->addMessage(iro_opt('chatgpt_init_prompt', DEFAULT_INIT_PROMPT), 'system');
        $chatGPT->addMessage("文章标题：" . $post->post_title, 'user');
        $content = $post->post_content;
        $content = substr(wp_strip_all_tags(apply_filters('the_content', $content)), 0, 4050);

        $chatGPT->addMessage("正文：" . $content, 'user');

        $answer = '';
        foreach ($chatGPT->ask(iro_opt('chatgpt_ask_prompt', DEFAULT_ASK_PROMPT)) as $item) {
            $answer .= $item['answer'];
        }
        return  $answer;
    }

    function apply_chatgpt_hook()
    {
        if (iro_opt('chatgpt_article_summarize')) {
            add_action('save_post_post', function (int $post_id, WP_Post $post, bool $update) {
                if (!has_excerpt($post_id)) {
                    try {
                        $excerpt = summon_article_excerpt($post);
                        update_post_meta($post_id, POST_METADATA_KEY, $excerpt);
                    } catch (\Throwable $th) {
                        //throw $th;
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
}
