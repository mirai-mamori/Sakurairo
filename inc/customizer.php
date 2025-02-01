<?php
/**
 * Akina Theme Customizer.
 *
 * @package Sakurairo
 */
use Sakura\Customizer\{Homepage_Component_Order_Control};

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
// function akina_customize_preview_js() {
// 	wp_enqueue_script( 'akina_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
// }
// add_action( 'customize_preview_init', 'akina_customize_preview_js' );


function set_homepage_controls($wp_customize) {
	require_once get_theme_file_path('inc/classes/Customizer.php');
	$wp_customize->remove_control( 'show_on_front' );
    $wp_customize->remove_control( 'page_on_front' );
    $wp_customize->remove_control( 'page_for_posts' );
	$section = $wp_customize->get_section( 'static_front_page' );
	if ( $section ) {
		$section->description = __('部分首页设置项，如果您安装了缓存插件，请在设置后清除缓存。',"sakura");
	}
	$wp_customize->add_setting('home_static_page', array(
        'default'           => 0, 
        'sanitize_callback' => 'absint', 
        'transport'         => 'refresh', 
    ));

	$wp_customize->add_setting('component_order', array(
        'default'           => json_encode(['bulletin', 'static_page', 'exhibition', 'primary']),
        'transport'         => 'refresh',
        'sanitize_callback' => function($input) {
            $valid = ['bulletin', 'static_page', 'exhibition', 'primary'];
            $data = json_decode($input, true);
            return json_encode(array_filter($data, function($item) use ($valid) {
                return in_array($item, $valid);
            }));
        }
	));

    $wp_customize->add_control('mytheme_selected_page_control', array(
        'type'            => 'dropdown-pages',
        'label'           => __('页面',"sakura"),
        'description'     => __('显示一个静态页面',"sakura"),
        'section'         => 'static_front_page',
        'settings'        => 'home_static_page', 
        'allow_addition'  => true, 
	));



    $wp_customize->add_control(new Homepage_Component_Order_Control($wp_customize, 'component_order', [
        'section' => 'static_front_page',
        'label'   => __('页面组件顺序',"sakura")
    ]));
};
add_action('customize_register', 'set_homepage_controls');
