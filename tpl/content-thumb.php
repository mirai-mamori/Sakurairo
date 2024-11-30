<?php

/**
 * Template part for displaying posts and shuoshuo.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sakurairo
 */

// Combine posts and shuoshuo
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// Determine if we are on an author page
$is_author_page = is_author() && !is_home() && !is_category() && !is_tag();

$sticky_posts = get_option('sticky_posts');

$show_shuoshuo_on_home_page = iro_opt('show_shuoshuo_on_home_page');

// Query for sticky posts (only on the first page)
if ($paged == 1 && !empty($sticky_posts)) {
    $sticky_args = array(
        'post_type' => array('post', 'shuoshuo'),
        'post_status' => 'publish',
        'posts_per_page' => -1, // Get all sticky posts
        'post__in' => $sticky_posts,
        'orderby' => 'post_date',
        'order' => 'DESC',
    );

    if (is_home() && !$show_shuoshuo_on_home_page) {
        $all_results_args['post_type'] = array('post');
    }
	
    if ($is_author_page) {
        $sticky_args['author'] = get_the_author_meta('ID');
    }

    $sticky_query = new WP_Query($sticky_args);

    // Display sticky posts
    if ($sticky_query->have_posts()) :
        while ($sticky_query->have_posts()) : $sticky_query->the_post();
            get_template_part('tpl/content', 'thumbcard');
        endwhile;
    endif;
}

// Query for non-sticky posts
$non_sticky_args = array(
    'post_type' => array('post', 'shuoshuo'),
    'post_status' => 'publish',
    'posts_per_page' => get_option('posts_per_page'), // 每页显示文章数量由 WordPress 设置决定
    'orderby' => 'post_date',
    'order' => 'DESC',
    'paged' => $paged,
    'post__not_in' => $sticky_posts,
    'ignore_sticky_posts' => 1
);

if (is_home() && !$show_shuoshuo_on_home_page) {
    $all_results_args['post_type'] = array('post');
}

if ($is_author_page) {
    $non_sticky_args['author'] = get_the_author_meta('ID'); // 只获取当前作者的文章
}

$non_sticky_query = new WP_Query($non_sticky_args);

// Display non-sticky posts
if ($non_sticky_query->have_posts()) :
    while ($non_sticky_query->have_posts()) : $non_sticky_query->the_post();
        get_template_part('tpl/content', 'thumbcard');
    endwhile;
endif;
?>
