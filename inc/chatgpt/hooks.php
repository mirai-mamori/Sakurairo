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
        $chatGPT->addMessage(iro_opt('chatgpt_system_prompt', DEFAULT_INIT_PROMPT), 'system');
        $chatGPT->addMessage("文章标题：" . $post->post_title);
        $content = $post->post_content;
        $content = apply_filters('the_content', $content);

        //将$content分割成多个子字符串，每个子字符串的长度不超过限制
        $chunks = str_split($content, MAX_MESSAGE_LENGTH);

        //遍历每个子字符串，并用$chatGPT->addMessage加入消息
        foreach ($chunks as $chunk) {
            //去除子字符串两端的空白字符
            $chunk = trim($chunk);
            //如果子字符串不为空，则加入消息
            if ($chunk !== '') {
                $chatGPT->addMessage($chunk, 'user');
            }
        }
        return $chatGPT->ask(iro_opt('chatgpt_ask_prompt', DEFAULT_ASK_PROMPT))['answer'];
    }

    function apply_chatgpt_hook()
    {
        if (iro_opt('chatgpt_article_summarize')) {
            add_action('save_post_post', function (int $post_id, WP_Post $post, bool $update)
            {
                if (!has_excerpt($post_id)) {
                    try {
                        $excerpt = summon_article_excerpt($post);
                        update_post_meta($post_id, POST_METADATA_KEY, $excerpt);
                    } catch (\Throwable $th) {
                        //throw $th;
                        error_log('使用ChatGPT生成文章摘要时出现了下述错误'.$th);
                    }
                }
            },10,3);
            add_filter('the_excerpt', function (string $post_excerpt)
            {
                global $post;
                if (has_excerpt($post)) {
                     return $post_excerpt;
                } else {
                    return get_post_meta($post->ID, POST_METADATA_KEY, true);
                }
            });
        }
    }
}
