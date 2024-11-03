<?php

/**
 * Template part for displaying posts and shuoshuo.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sakurairo
 */

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$is_author_page = is_author() && !is_home() && !is_category() && !is_tag();

$sticky_posts = get_option('sticky_posts');

$all_results_args = array(
    'post_type' => array('post', 'shuoshuo'),
    'post_status' => 'publish',
    'posts_per_page' => -1,
	'posts_per_page' => 10, // 每页显示10篇文章
	'paged' => $paged,
    'orderby' => 'post_date',
    'order' => 'DESC',
);

if (is_home() && !iro_opt('show_shuoshuo_on_home_page')) {
    //是否在主页显示说说
    $all_results_args['post_type'] = array('post');
}

if ($is_author_page) {
    $all_results_args['author'] = get_the_author_meta('ID');
}

$all_results_query = new WP_Query($all_results_args);

	//结果
	$all_results = [];
	$sticky_results = [];
	$non_sticky_results = [];
	
	//分类置顶内容和非置顶内容
	if ($all_results_query->have_posts()) :
		while ($all_results_query->have_posts()) : $all_results_query->the_post();
		if (in_array(get_the_ID(), $sticky_posts)) {
			$sticky_results[] = $post;
		} else {
			$non_sticky_results[] = $post;
		}
	    endwhile;
	endif;
	wp_reset_postdata();

// 合并结果, 优先展示置顶文章
$all_results = array_merge($sticky_results, $non_sticky_results);

//输出所有内容
if (!empty($all_results)) :
    foreach ($all_results as $post) :
        setup_postdata($post);
        get_template_part('tpl/content', 'thumbcard');
    endforeach;
endif;
?>