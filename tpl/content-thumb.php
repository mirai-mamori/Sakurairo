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

// Query for sticky posts (only on the first page)
if ($paged == 1) {
    $sticky_args = array(
        'post_type' => array('post', 'shuoshuo'),
        'post_status' => 'publish',
        'posts_per_page' => -1, // Get all sticky posts
        'post__in' => get_option('sticky_posts'),
        'orderby' => 'post_date',
        'order' => 'DESC',
        'author' => get_the_author_meta('ID')
    );

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
    'posts_per_page' => 10, // 每页显示10篇文章
    'orderby' => 'post_date',
    'order' => 'DESC',
    'author' => get_the_author_meta('ID'), // 只获取当前作者的文章
    'paged' => $paged,
    'post__not_in' => get_option('sticky_posts'),
    'ignore_sticky_posts' => 1
);

$non_sticky_query = new WP_Query($non_sticky_args);

// Display non-sticky posts
if ($non_sticky_query->have_posts()) :
    while ($non_sticky_query->have_posts()) : $non_sticky_query->the_post();
        get_template_part('tpl/content', 'thumbcard');
    endwhile;
endif;
?>