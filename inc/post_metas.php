<?php
/**
 * 显示作者meta
 * @author KotoriK<https://github.com/KotoriK>
 */
 
 function render_author_meta($user_id=false){
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

// 分类
function get_meta_category_html($post_id)
{
	$the_cat = get_the_category($post_id);
	$categorized = isset($the_cat[0]);
	return "<span><i class=\"fa-regular fa-folder\"></i> "
		. ($categorized ? "<a href=\"" . esc_url(get_category_link($the_cat[0]->cat_ID  ?? '')) . "\">" . $the_cat[0]->cat_name . "</a>" : "未分类")
		. "</span>";
}

// 阅读时间
function get_meta_estimate_reading_time($post_id)
{
    $words_count = get_post_meta($post_id, 'post_words_count', true);
    if ($words_count) {
        $ert = round($words_count / 220);
        if ($ert  < 1) {
            return __("Less than 1 minute", "sakurairo");
        } else if ($ert > 60) {
            $hour = round($ert / 60);
            return sprintf(_n('%s Hour', '%s Hours', $hour, "sakurairo"), number_format_i18n($hour));
        } else {
            return sprintf(_n('%s Minute', '%s Minutes', $ert, "sakurairo"), number_format_i18n($ert));
        }
    }
}

// 字数统计
function get_meta_words_count($post_id)
{
    $id = $post_id;
    $words_count = get_post_meta($post_id, 'post_words_count', true);
    if(!$words_count) {
        $words_count = count_post_words($id);
    }
    if (!is_numeric($words_count) || $words_count < 0) {
        $words_count = count_post_words($post_id);
    }
    return sprintf(_n("%s Word", "%s Words", $words_count, "sakurairo"), $words_count);
}

// 评论统计
function render_meta_comments(){
	?><span class="comments-number">
	<i class="fa-regular fa-comment"></i> 
	<?php comments_popup_link(__("NOTHING", "sakurairo"), __("1 Comment", "sakurairo")/*条评论*/, '% ' . __("Comments", "sakurairo")/*条评论*/, '', __("Comment Closed", "sakurairo")
		/**评论关闭 */
	); ?></span><?php
}
?>