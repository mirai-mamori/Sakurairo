<?php
/**
 * Akina Theme Customizer.
 *
 * @package Sakurairo
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function akina_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'akina_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function akina_customize_preview_js() {
	wp_enqueue_script( 'akina_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'akina_customize_preview_js' );


function add_homepage_controls_description($wp_customize) {
    // 移除静态主页选择器
    $wp_customize->remove_control('page_on_front');
    // 为静态页面选择器添加描述
    $wp_customize->get_control('page_for_posts')->description = '选择取代文章列表显示的页面。此页面的标题将替代文章区域标题，其内容将替代文章列表。';
}
add_action('customize_register', 'add_homepage_controls_description');

