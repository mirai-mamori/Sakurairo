<?php

/**
 * Template part for displaying posts and shuoshuo.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sakurairo
 */

$paged = max(1, (int) get_query_var('paged'));

// Determine if we are on an author page
$is_author_page = is_author() && !is_home() && !is_category() && !is_tag();

$sticky_posts = array_filter(array_map('intval', (array) get_option('sticky_posts')));

$show_shuoshuo_on_home_page = iro_opt('show_shuoshuo_on_home_page');

$post_types = array('post', 'shuoshuo');
if (is_home() && !$show_shuoshuo_on_home_page) {
    $post_types = array('post');
}

// Query for sticky posts (only on the first page)
if ($paged === 1 && !empty($sticky_posts)) {
    $sticky_args = array(
        'post_type' => $post_types,
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'post__in' => $sticky_posts,
        'orderby' => 'post_date',
        'order' => 'DESC',
        'no_found_rows' => true,
        'ignore_sticky_posts' => 1,
    );

    if ($is_author_page) {
        $sticky_args['author'] = get_the_author_meta('ID');
    }

    $sticky_query = new WP_Query($sticky_args);

    if ($sticky_query->have_posts()) {
        sakura_prime_post_caches($sticky_query->posts);
        while ($sticky_query->have_posts()) {
            $sticky_query->the_post();
            get_template_part('tpl/content', 'thumbcard');
        }
        wp_reset_postdata();
    }
}

// Non-sticky posts: reuse main query on home/author to avoid duplicate DB round-trips
$use_main_query = is_home() || $is_author_page;

if ($use_main_query) {
    global $wp_query;
    if ($wp_query->have_posts()) {
        sakura_prime_post_caches($wp_query->posts);
        while ($wp_query->have_posts()) {
            $wp_query->the_post();
            get_template_part('tpl/content', 'thumbcard');
        }
    }
} else {
    $non_sticky_args = array(
        'post_type' => $post_types,
        'post_status' => 'publish',
        'posts_per_page' => get_option('posts_per_page'),
        'orderby' => 'post_date',
        'order' => 'DESC',
        'paged' => $paged,
        'post__not_in' => $sticky_posts,
        'ignore_sticky_posts' => 1,
    );

    if ($is_author_page) {
        $non_sticky_args['author'] = get_the_author_meta('ID');
    }

    $non_sticky_query = new WP_Query($non_sticky_args);

    if ($non_sticky_query->have_posts()) {
        sakura_prime_post_caches($non_sticky_query->posts);
        while ($non_sticky_query->have_posts()) {
            $non_sticky_query->the_post();
            get_template_part('tpl/content', 'thumbcard');
        }
        wp_reset_postdata();
    }
}
