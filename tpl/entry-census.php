<?php
function get_edit_html(): string
{
    $url = get_edit_post_link();
    if ($url) {
        return ' <a href="' . $url . '">' . __("EDIT", "sakurairo") . '</a>';
    }
    return '';
}
function __wrap_with_span($content): string
{
    return " <span>$content</span>";
}
function get_entry_census_meta_html($has_splitter)
{
    $post = get_post();
    $post_id = get_the_ID();
    $meta_display = iro_opt("article_meta_show_in_head", array("last_edit_time_relative", "post_views"));
    if (!is_array($meta_display) && !is_object($meta_display)) {
        $meta_display = array("last_edit_time_relative", "post_views");
    }
    foreach ($meta_display as $meta_key) {
        $content = false;
        switch ($meta_key) {
            case "author":
                ob_start();
                render_author_meta();
                $content = ob_get_contents();
                ob_end_clean();
                break;
            case "category":
                $content = get_meta_category_html($post_id);
                break;
            case "comment_count":
                ob_start();
                render_meta_comments();
                $content = ob_get_contents();
                ob_end_clean();
                break;
            case "post_views":
                $content = get_post_views($post_id) . ' ' . _n('View', 'Views', get_post_views($post_id), 'sakurairo');/*次阅读*/
                if ($has_splitter) {
                    $content = __wrap_with_span($content);
                } else {
                    $content = ' ' . $content;
                }
                break;
            case "post_words_count":
                $content = get_meta_words_count($post_id);
                if ($has_splitter) {
                    $content = __wrap_with_span($content);
                } else {
                    $content = ' ' . $content;
                }
                break;
            case "reading_time":
                $content = __("Estimate Reading Time", "sakurairo") . ": " . get_meta_estimate_reading_time($post_id);
                if ($has_splitter) {
                    $content = __wrap_with_span($content);
                } else {
                    $content = ' ' . $content;
                }
                break;
            case "publish_time_relative":
                $content = poi_time_since(strtotime($post->post_date));
                if ($has_splitter) {
                    $content = __wrap_with_span($content);
                } else {
                    $content = ' ' . $content;
                }
                break;
            case "last_edit_time_relative":
                $content = poi_time_since(strtotime($post->post_modified), false, __('Last updated on ', 'sakurairo'));
                if ($has_splitter) {
                    $content = __wrap_with_span(__("Last updated on", "sakurairo") . ' ' . $content);
                } else {
                    $content = ' ' . __("Last updated on", "sakurairo") . ' ' . $content;
                }
                break;
            case "EDIT":
                $content = get_edit_html();
                break;
        }
        if ($content) {
            yield $content;
        }
    }
}

function get_entry_census_html($has_splitter = false)
{
    $additional_class = $has_splitter ? " has-splitter" : "";
    $content = iterator_to_string(get_entry_census_meta_html($has_splitter));
    return "<p class=\"entry-census$additional_class\">$content</p>";
}
