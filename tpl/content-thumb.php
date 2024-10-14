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
$args = array(
    'post_type' => array('post', 'shuoshuo'),
    'post_status' => 'publish',
    'posts_per_page' => 10, // 每页显示10篇文章
    'orderby' => 'post_date',
    'order' => 'DESC',
    'author' => get_the_author_meta('ID'), // 只获取当前作者的文章
    'paged' => $paged
);

$combined_query = new WP_Query($args);

if ($combined_query->have_posts()) :
    while ($combined_query->have_posts()) : $combined_query->the_post();
        get_template_part('tpl/content', 'thumbcard');
    endwhile;
endif;
