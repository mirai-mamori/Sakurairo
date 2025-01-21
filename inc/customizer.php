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


function set_homepage_controls($wp_customize) {
	// 添加设置：是否显示文章列表
	$wp_customize->add_setting('hide_homepage_post_list', array(
		'default'           => false,
		'transport' => 'refresh',
	));

	$wp_customize->add_control('hide_homepage_post_list_control', array(
		'label'       => '仅展示静态页面，不显示文章列表',
		'section'     => 'static_front_page',
		'settings'    => 'hide_homepage_post_list',
		'type'        => 'checkbox',
		'description' => '勾选此框后，主页只显示静态页面内容，下方不会显示文章列表。',
	));
	
	
	// 移除静态主页选择器
	$wp_customize->remove_control('page_on_front');
    // 为静态页面选择器添加描述
    $wp_customize->get_control('page_for_posts')->description = '在文章列表上方显示一个静态页面';


    // 从阅读设置中移除「主页显示」设置部分
    remove_settings_section('default', 'reading');
    remove_settings_field('show_on_front', 'reading');
    remove_settings_field('page_on_front', 'reading');
    remove_settings_field('page_for_posts', 'reading');

}
add_action('customize_register', 'set_homepage_controls');

