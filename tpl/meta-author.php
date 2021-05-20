<?php
/**
 * 显示作者meta
 * @author KotoriK<https://github.com/KotoriK>
 */
 
 function get_author_meta_spans($user_id=false){
    global $post;
    if (get_the_author()) {
        if (function_exists('get_multiple_authors')) {
            //plugin_support: PublishPress_Authors
            $authors = get_multiple_authors($post, false);
        } else {
            if (!$user_id) {
                global $authordata;
                $user_id = isset($authordata->ID) ? $authordata->ID : 0;
            } else {
                $authordata = get_userdata($user_id);
            }
            $authors = array($authordata);
        }
        $returns = array_map(function ($author) {
            $author_post_url=get_author_posts_url($author->ID);
            return '<span class="meta-author">' .
                '<a href="' . $author_post_url . '">' .
                get_avatar($author,16,'',$author->display_name,array("class"=>"avatar")).
                '</a>' .
                '<a rel="author" title="' . $author->display_name . '" href="' . $author_post_url . '">'
                . $author->display_name .
                '</a>' .
                '</span>';
        }, $authors);
        echo join('', $returns);
    }
 }
?>