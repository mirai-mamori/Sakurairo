<?php

namespace Sakura\API;

class Cache
{
    public static function search_json() {
        global $more;
        $vowels = array("[", "{", "]", "}", "<", ">", "\r\n", "\r", "\n", "-", "'", '"', '`', " ", ":", ";", '\\', "  ", "toc");
        $regex = <<<EOS
/<\/?[a-zA-Z]+("[^"]*"|'[^']*'|[^'">])*>|begin[\S\s]*\/begin|hermit[\S\s]*\/hermit|img[\S\s]*\/img|{{.*?}}|:.*?:/m
EOS;
        $more = 1;
        $output = array();

        $posts = new \WP_Query('posts_per_page=-1&post_status=publish&post_type=post');
        while ($posts->have_posts()): $posts->the_post();
            $output[] = array(
                "type" => "post",
                "link" => get_permalink(),
                "title" => get_the_title(),
                "comments" => get_comments_number('0', '1', '%'),
                "text" => str_replace($vowels, " ", preg_replace($regex, ' ', apply_filters('the_content', get_the_content())))
            );
        endwhile;
        wp_reset_postdata();

        $pages = new \WP_Query('posts_per_page=-1&post_status=publish&post_type=page');
        while ($pages->have_posts()): $pages->the_post();
            $output[] = array(
                "type" => "page",
                "link" => get_permalink(),
                "title" => get_the_title(),
                "comments" => get_comments_number('0', '1', '%'),
                "text" => str_replace($vowels, " ", preg_replace($regex, ' ', apply_filters('the_content', get_the_content())))
            );
        endwhile;
        wp_reset_postdata();

        $tags = get_tags();
        foreach ($tags as $tag) {
            $output[] = array(
                "type" => "tag",
                "link" => get_term_link($tag),
                "title" => $tag->name,
                "comments" => "",
                "text" => ""
            );
        }

        $categories = get_categories();
        foreach ($categories as $category) {
            $output[] = array(
                "type" => "category",
                "link" => get_term_link($category),
                "title" => $category->name,
                "comments" => "",
                "text" => ""
            );
        }
        if (akina_option('live_search_comment')) {
            $comments = get_comments();
            foreach ($comments as $comment) {
                $is_private = get_comment_meta($comment->comment_ID, '_private', true);
                $output[] = array(
                    "type" => "comment",
                    "link" => get_comment_link($comment),
                    "title" => get_the_title($comment->comment_post_ID),
                    "comments" => "",
                    "text" => $is_private ? ($comment->comment_author . ": " . __('The comment is private', 'sakura')) : str_replace($vowels, ' ', preg_replace($regex, ' ', $comment->comment_author . "：" . $comment->comment_content))
                );
            }
        }
        return $output;
    }


    public static function update_database() {
        global $wpdb;
        $sakura_table_name = $wpdb->base_prefix . 'sakura';
        $img_domain = akina_option('cover_cdn') ? akina_option('cover_cdn') : get_template_directory();
        $manifest = file_get_contents($img_domain . "/manifest/manifest.json");
        if ($manifest) {
            $manifest = array(
                "mate_key" => "manifest_json",
                "mate_value" => $manifest
            );
            $time = array(
                "mate_key" => "json_time",
                "mate_value" => date("Y-m-d H:i:s", time())
            );

            $wpdb->query("DELETE FROM  $sakura_table_name WHERE `mate_key` ='manifest_json'");
            $wpdb->query("DELETE FROM  $sakura_table_name WHERE `mate_key` ='json_time'");
            $wpdb->insert($sakura_table_name, $manifest);
            $wpdb->insert($sakura_table_name, $time);
            $output = "manifest.json has been stored into database.";
        } else {
            $output = "manifest.json not found, please ensure your url ($img_domain) is corrent.";
        }
        return $output;
    }
}