<?php
function get_meta_category_html()
{
	$the_cat = get_the_category();
	$categorized = isset($the_cat[0]);
	return "<span><i class=\"fa-regular fa-folder\"></i> "
		. ($categorized ? "<a href=\"" . esc_url(get_category_link($the_cat[0]->cat_ID  ?? '')) . "\">" . $the_cat[0]->cat_name . "</a>" : "未分类")
		. "</span>";
}
