<?php
/**
 * Sakurairo Theme Customizer.
 * Use Kirki
 * https://github.com/themeum/kirki
 * @package Sakurairo
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 载入Kirki
if ( ! class_exists( 'Kirki' ) ) {
	require_once __DIR__ . '/kirki/kirki.php';
	new \Kirki\Pro\Init();

	define( 'KIRKI_NO_OUTPUT', true );
	define( 'KIRKI_NO_GUTENBERG_OUTPUT', true );
}

load_textdomain( 'Sakurairo_C', __DIR__ . '/lang/' . get_locale() . '.mo' );

// 面板部分
// 面板：每个面板至少包含 id、title，可选description（可选priority 将自动分配，描述不填自动为空）
$panels = [
	[
        'id'          => 'iro_global',
        'title'       => esc_html__( 'Global Options', 'Sakurairo_C' ),
		'priority'    => 10,
    ],
	[
        'id'          => 'iro_cover',
        'title'       => esc_html__( 'Homepage Cover', 'Sakurairo_C' ),
		'priority'    => 10,
    ],
    [
        'id'          => 'iro_homepage',
        'title'       => esc_html__( 'Homepage Components', 'Sakurairo_C' ),
		'priority'    => 10,
    ],
	[
        'id'          => 'iro_pages',
        'title'       => esc_html__( 'Pages Options', 'Sakurairo_C' ),
		'priority'    => 10,
    ],
];

// 所有可以传递的参数列表（按 Themeum/Kirki 官方文档）
$allowed_params = [
	'tab',              // 所属section中的选项卡，
	'active_callback',  // 回调函数，决定该字段是否显示
	'capability',       // 所需权限
	'choices',          // 可选项，适用于下拉、单选、复选等类型
	'default',          // 默认值
	'description',      // 描述信息
	'fields',           // 用于 repeater 等控件，定义子字段
	'js_vars',          // 用于 postMessage 实时预览的 JS 配置
	'label',            // 字段标签（必填，未设置则默认空字符串）
	'multiple',         // 允许多选时使用
	'option_name',      // 当保存到 option 时指定 option 名称
	'option_type',      // 保存类型，'theme_mod' 或 'option'
	'output',           // 自动输出前端 CSS 的配置数组
	'partial_refresh',  // 部分刷新设置
	'preset',           // 预设值（如预设色板）
	'priority',         // 排序权重（必填，未填写将自动赋值）
	'sanitize_callback',// 数据过滤函数
	'section',          // 所属区块 ID（必填）
	'settings',         // 设置项 ID（必填）
	'tooltip',          // 字段提示信息
	'transport',        // 数据传输方式，如 'refresh' 或 'postMessage'，未设置的请设置iro_key，将请求php端渲染
	'iro_key',          // Sakurairo options键，
	                    // 使用的选项将实时上报更改信息，以进行复杂更改的渲染，
						// 同时也不用设置默认值，直接从iro_options中获取当前值
						// 也可以不设置回调，默认更新至 iro_options[iro_key]
	'iro_subkey'        // key的子键
];

$vision_resource_basepath = iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@3.0/');

// 分组和设置项部分
// 分组：每个分组至少包含 id、title、description、所属面板 panel
// 设置项（Field）数组：每个设置项至少包含 type、settings、label、所属区块 section
$sections = [
	// ====================导航栏====================
	[
        'id'          => 'iro_nav',
        'title'       => esc_html__( 'Nav Menu', 'Sakurairo_C' ),
        'description' => '',
        'panel'       => 'iro_global',

		'fields'      =>[
			[
				'type'     => 'custom',
				'settings' => 'nav_menu_notice',
				'default'  => __('<p>You can edit your nav menu options <a href="/wp-admin/nav-menus.php">here</a></p>','Sakurairo_C'),
			],
			[
				'type'     => 'radio_image',
				'settings' => 'choice_of_nav_style',
				'iro_key'  => 'choice_of_nav_style',
				'label'    => esc_html__( 'Nav Menu Style', 'Sakurairo_C' ),
				'choices'     => [
					'iro' => $vision_resource_basepath . 'options/nav_menu_style_Island.webp',
					'sakura' => $vision_resource_basepath . 'options/nav_menu_style_bar.webp',
				],
			],
			[
				'type'     => 'select',
				'settings' => 'nav_menu_style',
				'iro_key'  => 'nav_menu_style',
				'label'    => esc_html__( 'Spirit Island Nav Style', 'Sakurairo_C' ),
				'choices'     => [
					'center' => __('Always centered','Sakurairo_C'),
					'space-between' => __('Dispersed','Sakurairo_C'),
				],
				'active_callback' => [
					[
						'setting'  => 'choice_of_nav_style',
						'operator' => '==',
						'value'    => 'iro',
					]
				],
			],
			[
				'type'     => 'slider',
				'settings' => 'nav_menu_cover_radius',
				'label'    => esc_html__( 'Nav Menu Radius', 'Sakurairo_C' ),
				'iro_key'  => 'nav_menu_cover_radius',
				'transport'   => 'auto',
				'choices'     => [
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				],
				'output' => array(
					array(
						'element'  => array('.site-branding',
										'.nav-search-wrapper',
										'.user-menu-wrapper',
										'.nav-search-wrapper nav ul li a',
										'.searchbox.js-toggle-search i',
										'.bg-switch i',
										'.site-header'),
						'property' => 'border-radius',
						'value_pattern' => '$px !important',
					),
				)
			],
			[
				'type'     => 'select',
				'settings' => 'sakura_nav_style_style',
				'label'    => esc_html__( 'Classic Nav Style', 'Sakurairo_C' ),
				'iro_key'  => 'sakura_nav_style',
				'iro_subkey'  => 'style',
				'choices'     => [
					'sakura' => __('Loose','Sakurairo_C'),
					'sakurairo' => __('Standered','Sakurairo_C'),
				],
				'active_callback' => [
					[
						'setting'  => 'choice_of_nav_style',
						'operator' => '!=',
						'value'    => 'iro',
					]
				],
			],
			[
				'type'     => 'select',
				'settings' => 'sakura_nav_style_distribution', //分布
				'label'    => esc_html__( 'Nav Menu Options Display Method', 'Sakurairo_C' ),
				'iro_key'  => 'sakura_nav_style',
				'iro_subkey'  => 'distribution',
				'choices'     => [
					'left' => __('Keep to the left','Sakurairo_C'),
					'right' => __('Keep to the right','Sakurairo_C'),
					'center' => __('Always centered','Sakurairo_C'),
				],
				'active_callback' => [
					[
						'setting'  => 'choice_of_nav_style',
						'operator' => '!=',
						'value'    => 'iro',
					]
				],
				'transport'   => 'postMessage',
				'output' => array(
					array(
						'element'  => '.menu-wrapper .sakura_nav .menu',
						'property' => 'justify-content',
						'value_pattern' => '$ !important',
					),
				)
			],
			[
				'type'     => 'slider',
				'settings' => 'sakura_nav_style_option_spacing',
				'label'    => esc_html__( 'Menu option left and right spacing', 'Sakurairo_C' ),
				'iro_key'  => 'sakura_nav_style',
				'iro_subkey'  => 'option_spacing',
				'active_callback' => [
					[
						'setting'  => 'choice_of_nav_style',
						'operator' => '!=',
						'value'    => 'iro',
					]
				],
				'transport'   => 'auto',
				'choices'     => [
					'min'  => 1,
					'max'  => 150,
					'step' => 1,
				],
				'output' => array(
					array(
						'element'  => 'nav ul li',
						'property' => 'margin', 
						'value_pattern' => '0 $px !important',
					),
				),
			],
			[
				'type'     => 'text',
				'settings' => 'nav_menu_font',
				'label'    => esc_html__( 'Nav Menu Font', 'Sakurairo_C' ),
				'iro_key'  => 'nav_menu_font',
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => array( '.site-header a','.header-user-name','.header-user-menu a' ),
						'property' => 'font-family',
					),
				)
			],
			[
				'type'     => 'image',
				'settings' => 'iro_logo',
				'label'    => esc_html__( 'Navigation Menu Logo', 'Sakurairo_C' ),
				'iro_key'  => 'iro_logo',
				'js_vars'   => [
					[
						'element'  => '.site-title-logo',
						'function' => 'html',
					],
				],
			],
			[
				'type'     => 'text',
				'settings' => 'nav_text_logo_text',
				'label'    => esc_html__( 'Nav Menu Text Logo Text', 'Sakurairo_C' ),
				'iro_key'  => 'nav_text_logo',
				'iro_subkey'  => 'text',
				'transport'   => 'postMessage',
				'js_vars'   => [
					[
						'element'  => '.site-title',
						'function' => 'html',
					],
				],
			],
			[
				'type'     => 'text',
				'settings' => 'nav_text_logo_font',
				'label'    => esc_html__( 'Nav Menu Text Logo Font', 'Sakurairo_C' ),
				'iro_key'  => 'nav_text_logo',
				'iro_subkey'  => 'font_name',
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => '.site-title',
						'property' => 'font-family',
					),
				)
			],
			[
				'type'     => 'switch',
				'settings' => 'cover_random_graphs_switch',
				'iro_key'  => 'cover_random_graphs_switch',
				'label'    => esc_html__( 'Switch Button of Random Images', 'Sakurairo_C' ),
			],
			[
				'type'     => 'switch',
				'settings' => 'nav_user_menu',
				'label'    => esc_html__( 'Nav User Menu', 'Sakurairo_C' ),
				'description' => esc_html__( 'It is on by default. The user avatar and menu will be displayed.', 'Sakurairo_C' ),
				'section'  => 'iro_nav',
				'iro_key'  => 'nav_user_menu',
			],
			[
				'type'     => 'switch',
				'settings' => 'nav_menu_search',
				'iro_key'  => 'nav_menu_search',
				'label'    => esc_html__( 'Nav Menu Search', 'Sakurairo_C' ),
			],
			[
				'type'     => 'image',
				'settings' => 'search_area_background',
				'iro_key'  => 'search_area_background',
				'label'    => esc_html__( 'Search Area Background Image', 'Sakurairo_C' ),
				'transport'   => 'auto',
				'output'      => array(
					array(
						'element'  => '.search-form.is-visible',
						'property' => 'background-image',
					),
				),
			],
		],
    ],
	// ====================主题色部分====================
	[
        'id'          => 'iro_color',
        'title'       => esc_html__( 'Theme Colors', 'Sakurairo_C' ),
        'description' => '',
        'panel'       => 'iro_global',

		'fields'      =>[
			// ====================主题色====================
			[
				'type'     => 'switch',
				'settings' => 'extract_theme_skin_from_cover',
				'iro_key'  => 'extract_theme_skin_from_cover',
				'label'    => esc_html__( 'Extract Theme Color from Cover Image', 'Sakurairo_C' ),
				'description' => esc_html__('After turning on,the theme color will be taken from the homepage cover', 'Sakurairo_C' ),
			],
			[
				'type'     => 'switch',
				'settings' => 'extract_article_highlight_from_feature',
				'iro_key'  => 'extract_article_highlight_from_feature',
				'label'    => esc_html__( 'Extract Article Highlight from Featured Image', 'Sakurairo_C' ),
				'description' => esc_html__('After turning on,the colors displayed on the article page will be taken from the article featured image', 'Sakurairo_C' ),
			],
			[
				'type'     => 'color',
				'settings' => 'theme_skin',
				'label'    => esc_html__( 'Theme Color', 'Sakurairo_C' ),
				'iro_key'  => 'theme_skin',
				'choices'     => [
					'alpha' => true,
				],
			],
			[
				'type'     => 'color',
				'settings' => 'theme_skin_matching',
				'label'    => esc_html__( 'Matching Color', 'Sakurairo_C' ),
				'iro_key'  => 'theme_skin_matching',
				'choices'     => [
					'alpha' => true,
				],
				'transport'   => 'auto',
				'output'      => array(
					array(
						'element'  => ':root',
						'property' => '--theme-skin-matching',
					),
				),
			],
			// ====================深色模式====================
			[
				'type'     => 'color',
				'settings' => 'theme_skin_dark',
				'label'    => esc_html__( 'Dark Mode Theme Color', 'Sakurairo_C' ),
				'iro_key'  => 'theme_skin_dark',
				'choices'     => [
					'alpha' => true,
				],
				'transport'   => 'auto',
				'output'      => array(
					array(
						'element'  => ':root',
						'property' => '--theme-skin-dark',
					),
				),
			],
			[
				'type'     => 'slider',
				'settings' => 'theme_darkmode_img_bright',
				'label'    => esc_html__( 'Dark Mode Image Brightness', 'Sakurairo_C' ),
				'iro_key'  => 'theme_darkmode_img_bright',
				'choices'     => [
					'min'  => 0.4,
					'max'  => 1,
					'step' => 0.01,
				],
			],
			[
				'type'     => 'slider',
				'settings' => 'theme_darkmode_widget_transparency',
				'label'    => esc_html__( 'Dark Mode Component Transparency', 'Sakurairo_C' ),
				'iro_key'  => 'theme_darkmode_widget_transparency',
				'choices'     => [
					'min'  => 0.2,
					'max'  => 1,
					'step' => 0.01,
				],
			],
		],
    ],
	// ====================封面LOGO====================
    [
        'id'          => 'iro_cover_logo',
        'title'       => esc_html__( 'Logo', 'Sakurairo_C' ),
        'description' => '',
        'panel'       => 'iro_cover',

		'fields'      =>[
			[
				'type'     => 'image',
				'settings' => 'personal_avatar',
				'label'    => esc_html__( 'Cover Personal Avatar', 'Sakurairo_C' ),
				'iro_key'  => 'personal_avatar',
				'transport'   => 'postMessage',
				'js_vars'   => [
					[
						'element'  => '.header-tou a',
						'function' => 'html',
					],
				],
			],
			[
				'type'     => 'switch',
				'settings' => 'text_logo_options',
				'label'    => esc_html__( 'Enable Mashiro Special Effects Text', 'Sakurairo_C' ),
				'description' => __('After turning it on, it will replace your avatar on the homepage','Sakurairo_C'),
				'iro_key'  => 'text_logo_options',
			],
			[
				'type'     => 'text',
				'settings' => 'text_logo_text',
				'label'    => esc_html__( 'Mashiro Special Effects Text', 'Sakurairo_C' ),
				'iro_key'  => 'text_logo',
				'iro_subkey'  => 'text',
				'active_callback' => [
					[
						'setting'  => 'text_logo_options',
						'operator' => '==',
						'value'    => true,
					]
				],
				'transport'   => 'postMessage',
				'js_vars'     => [
					[
						'element'  => '.center-text',
						'function' => 'html',
					],
				],
			],
			[
				'type'     => 'color',
				'settings' => 'text_logo_color',
				'label'    => esc_html__( 'Mashiro Special Effects Text Color', 'Sakurairo_C' ),
				'iro_key'  => 'text_logo',
				'iro_subkey'  => 'color',
				'choices'     => [
					'alpha' => true,
				],
				'active_callback' => [
					[
						'setting'  => 'text_logo_options',
						'operator' => '==',
						'value'    => true,
					]
				],
				'transport'   => 'auto',
				'output'      => array(
					array(
						'element'  => '.center-text',
						'property' => 'color',
					),
				),
			],
			[
				'type'     => 'text',
				'settings' => 'text_logo_font',
				'label'    => esc_html__( 'Mashiro Special Effects Font', 'Sakurairo_C' ),
				'iro_key'  => 'text_logo',
				'iro_subkey'  => 'font',
				'active_callback' => [
					[
						'setting'  => 'text_logo_options',
						'operator' => '==',
						'value'    => true,
					]
				],
				'transport'   => 'auto',
				'output'      => array(
					array(
						'element'  => '.center-text',
						'property' => 'font-family',
					),
				),
			],
			[
				'type'     => 'slider',
				'settings' => 'text_logo_size',
				'label'    => esc_html__( 'Mashiro Special Effects Size', 'Sakurairo_C' ),
				'iro_key'  => 'text_logo',
				'iro_subkey'  => 'size',
				'active_callback' => [
					[
						'setting'  => 'text_logo_options',
						'operator' => '==',
						'value'    => true,
					]
				],
				'transport'   => 'auto',
				'choices'     => [
					'min'  => 40 ,
					'max'  => 140,
					'step' => 1,
				],
				'output' => array(
					array(
						'element'  => '.center-text',
						'property' => 'font-size',
						'value_pattern' => '$px !important',
					),
				),
			],
		],
    ],
	// ====================封面外观====================
	[
        'id'          => 'iro_cover_display',
        'title'       => esc_html__( 'Apperance', 'Sakurairo_C' ),
        'description' => '',
        'panel'       => 'iro_cover',

		'fields'      =>[
			[
				'type'     => 'switch',
				'settings' => 'cover_switch',
				'label'    => esc_html__( 'Enable Cover', 'Sakurairo_C' ),
				'iro_key'  => 'cover_switch',
			],
			[
				'type'     => 'switch',
				'settings' => 'cover_full_screen',
				'label'    => esc_html__( 'Cover Full Screen', 'Sakurairo_C' ),
				'iro_key'  => 'cover_full_screen',
				'active_callback' => [
					[
						'setting'  => 'cover_switch',
						'operator' => '==',
						'value'    => true,
					]
				],
			],
			[
				'type'     => 'select',
				'settings' => 'random_graphs_filter',
				'iro_key'  => 'random_graphs_filter',
				'label'    => esc_html__( 'Cover Random Images Filter', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'cover_switch',
						'operator' => '==',
						'value'    => true,
					]
				],
				'choices'     => [
					'filter-nothing' => __('No filter','Sakurairo_C'),
					'filter-undertint' => __('Light filter','Sakurairo_C'),
					'filter-dim' => __('Dimmed filter','Sakurairo_C'),
					'filter-grid' => __('Grid filter','Sakurairo_C'),
					'filter-dot' => __('Dot filter','Sakurairo_C'),
				],
			],
			[
				'type'     => 'switch',
				'settings' => 'cover_half_screen_curve',
				'label'    => esc_html__( 'Cover Arc Occlusion (Below)', 'Sakurairo_C' ),
				'iro_key'  => 'cover_half_screen_curve',
				'active_callback' => [
					[
						'setting'  => 'cover_switch',
						'operator' => '==',
						'value'    => true,
					],
					[
						'setting'  => 'cover_full_screen',
						'operator' => '==',
						'value'    => false,
					]
				],
			],
			[
				'type'     => 'switch',
				'settings' => 'cover_animation',
				'label'    => esc_html__( 'Cover Animation', 'Sakurairo_C' ),
				'iro_key'  => 'cover_animation',
				'active_callback' => [
					[
						'setting'  => 'cover_switch',
						'operator' => '==',
						'value'    => true,
					]
				],
			],
			[
				'type'     => 'slider',
				'settings' => 'cover_animation_time',
				'label'    => esc_html__( 'Cover Animation Time', 'Sakurairo_C' ),
				'iro_key'  => 'cover_animation_time',
				'choices'     => [
					'min'  => 0,
					'max'  => 5,
					'step' => 0.01,
				],
				'active_callback' => [
					[
						'setting'  => 'cover_switch',
						'operator' => '==',
						'value'    => true,
					],
					[
						'setting'  => 'cover_animation',
						'operator' => '==',
						'value'    => true,
					]
				],
			],
		],
    ],
	// ====================封面信息栏====================
	[
        'id'          => 'iro_cover_info',
        'title'       => esc_html__( 'Infos', 'Sakurairo_C' ),
        'description' => '',
        'panel'       => 'iro_cover',

		'fields'      =>[
			[
				'type'     => 'switch',
				'settings' => 'infor_bar',
				'iro_key'  => 'infor_bar',
				'label'    => esc_html__( 'Cover Info Bar', 'Sakurairo_C' ),
			],
			[
				'type'     => 'radio_image',
				'settings' => 'infor_bar_style',
				'iro_key'  => 'infor_bar_style',
				'label'    => esc_html__( 'Cover Info Bar Style', 'Sakurairo_C' ),
				'transport'   => 'auto',
				'choices'     => [
					'v1' => $vision_resource_basepath . 'options/nav_menu_style_Island.webp',
					'v2' => $vision_resource_basepath . 'options/infor_bar_style_v2.webp',
				],
				'active_callback' => [
					[
						'setting'  => 'infor_bar',
						'operator' => '==',
						'value'    => true,
					]
				],
			],
			[
				'type'     => 'slider',
				'settings' => 'homepage_widget_transparency',
				'iro_key'  => 'homepage_widget_transparency',
				'label'    => esc_html__( 'Cover Widget Transparency', 'Sakurairo_C' ),
				'choices'     => [
					'min'  => 0.2,
					'max'  => 1,
					'step' => 0.01,
				],
				'active_callback' => [
					[
						'setting'  => 'infor_bar',
						'operator' => '==',
						'value'    => true,
					]
				],
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => ':root',
						'property' => '--homepage_widget_transparency',
					),
				),
			],
			[
				'type'     => 'slider',
				'settings' => 'avatar_radius',
				'iro_key'  => 'avatar_radius',
				'label'    => esc_html__( 'Cover Info Bar Avatar Radius', 'Sakurairo_C' ),
				'choices'     => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
				'active_callback' => [
					[
						'setting'  => 'infor_bar',
						'operator' => '==',
						'value'    => true,
					]
				],
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => '.focusinfo .header-tou img',
						'property' => 'border-radius',
						'value_pattern' => '$px !important',
					),
				),
			],
			[
				'type'     => 'slider',
				'settings' => 'signature_radius',
				'iro_key'  => 'signature_radius',
				'label'    => esc_html__( 'Cover Info Bar Rounded', 'Sakurairo_C' ),
				'choices'     => [
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				],
				'active_callback' => [
					[
						'setting'  => 'infor_bar',
						'operator' => '==',
						'value'    => true,
					]
				],
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => '.header-info',
						'property' => 'border-radius',
						'value_pattern' => '$px !important',
					),
				),
			],
			[
				'type'     => 'text',
				'settings' => 'signature_text',
				'iro_key'  => 'signature_text',
				'label'    => esc_html__( 'Cover Signature Field Text', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'infor_bar',
						'operator' => '==',
						'value'    => true,
					]
				],
				'transport'   => 'postMessage',
				'js_vars'     => [
					[
						'element'  => '.header-info p',
						'function' => 'html',
					],
				],
			],
			[
				'type'     => 'text',
				'settings' => 'signature_font',
				'iro_key'  => 'signature_font',
				'label'    => esc_html__( 'Cover Signature Field Text Font', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'infor_bar',
						'operator' => '==',
						'value'    => true,
					]
				],
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => '.header-info p',
						'property' => 'font-family',
						'value_pattern' => '$ !important',
					),
				),
			],
			[
				'type'     => 'slider',
				'settings' => 'signature_font_size',
				'iro_key'  => 'signature_font_size',
				'label'    => esc_html__( 'Cover Signature Field Text Font Size', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'infor_bar',
						'operator' => '==',
						'value'    => true,
					]
				],
				'choices'     => [
					'min'  => 5,
					'max'  => 20,
					'step' => 1,
				],
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => '.header-info p',
						'property' => 'font-size',
						'value_pattern' => '$px !important',
					),
				),
			],
			[
				'type'     => 'switch',
				'settings' => 'signature_typing',
				'iro_key'  => 'signature_typing',
				'label'    => esc_html__( 'Cover Signature Bar Typing Effects', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'infor_bar',
						'operator' => '==',
						'value'    => true,
					]
				],
			],
			[
				'type'     => 'switch',
				'settings' => 'signature_typing_marks',
				'iro_key'  => 'signature_typing_marks',
				'label'    => esc_html__( 'Cover Signature Field Typing Effects Double Quotes', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'infor_bar',
						'operator' => '==',
						'value'    => true,
					],
					[
						'setting'  => 'signature_typing',
						'operator' => '==',
						'value'    => true,
					]
				],
			],
			[
				'type'     => 'code',
				'settings' => 'signature_typing_json',
				'iro_key'  => 'signature_typing_json',
				'label'    => esc_html__( 'Typed.js initial option', 'Sakurairo_C' ),
				'choices'     => [
					'language' => 'json',
				],
				'active_callback' => [
					[
						'setting'  => 'infor_bar',
						'operator' => '==',
						'value'    => true,
					],
					[
						'setting'  => 'signature_typing',
						'operator' => '==',
						'value'    => true,
					]
				],
			],
		],
    ],
	// ====================杂项====================
	[
        'id'          => 'iro_cover_other',
        'title'       => esc_html__( 'Others', 'Sakurairo_C' ),
        'description' => '',
        'panel'       => 'iro_cover',

		'fields'      =>[
			[
				'type'     => 'switch',
				'settings' => 'site_bg_as_cover',
				'iro_key'  => 'site_bg_as_cover',
				'label'    => esc_html__( 'Cover and Frontend Background Integration', 'Sakurairo_C' ),
				'description' => esc_html__( 'When enabled, the background of the cover will be set to transparent, while the frontend background will use the cover\'s random image API', 'Sakurairo_C' ),
			],
			[
				'type'     => 'switch',
				'settings' => 'post_cover_as_bg',
				'iro_key'  => 'post_cover_as_bg',
				'label'    => esc_html__( 'Post Cover As Background', 'Sakurairo_C' ),
				'description' => esc_html__( 'Use post feature image as background in post pages', 'Sakurairo_C' ),
			    'active_callback' => [
					[
						'setting'  => 'site_bg_as_cover',
						'operator' => '==',
						'value'    => true,
					],
				],
			],
			[
				'type'     => 'switch',
				'settings' => 'wave_effects',
				'iro_key'  => 'wave_effects',
				'label'    => esc_html__( 'Cover Wave Effects', 'Sakurairo_C' ),
				'description' => __('It will be forced off in the dark mode','Sakurairo_C'),
			],
			[
				'type'     => 'switch',
				'settings' => 'drop_down_arrow',
				'iro_key'  => 'drop_down_arrow',
				'label'    => esc_html__( 'Cover Dropdown Arrow', 'Sakurairo_C' ),
			],
			[
				'type'     => 'switch',
				'settings' => 'drop_down_arrow_mobile',
				'iro_key'  => 'drop_down_arrow_mobile',
				'label'    => esc_html__( 'Cover Dropdown Arrow Display on Mobile Devices', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'drop_down_arrow',
						'operator' => '==',
						'value'    => true,
					]
				],
			],
			[
				'type'     => 'color',
				'settings' => 'drop_down_arrow_color',
				'iro_key'  => 'drop_down_arrow_color',
				'label'    => esc_html__( 'Cover Dropdown Arrow Color', 'Sakurairo_C' ),
				'choices'     => [
					'alpha' => true,
				],
				'active_callback' => [
					[
						'setting'  => 'drop_down_arrow',
						'operator' => '==',
						'value'    => true,
					]
				],
				'transport'   => 'auto',
				'output'      => array(
					array(
						'element'  => '.headertop-down svg path',
						'property' => 'fill',
						'value_pattern' => '$ !important',
					),
				),
			],
			[
				'type'     => 'color',
				'settings' => 'drop_down_arrow_dark_color',
				'iro_key'  => 'drop_down_arrow_dark_color',
				'label'    => esc_html__( 'Cover Dropdown Arrow Color (Dark Mode)', 'Sakurairo_C' ),
				'choices'     => [
					'alpha' => true,
				],
				'active_callback' => [
					[
						'setting'  => 'drop_down_arrow',
						'operator' => '==',
						'value'    => true,
					],
				],
				'transport'   => 'auto',
				'output'      => array(
					array(
						'element'  => 'body.dark .headertop-down svg path ',
						'property' => 'color',
						'value_pattern' => '$ !important',
					),
				),
			],
		],
	],
	// ====================主页整体布局====================
	[
        'id'          => 'iro_homepages_sections',
        'title'       => esc_html__( 'Overall layout', 'Sakurairo_C' ),
        'description' => '',
        'panel'       => 'iro_homepage',

		'fields'      =>[
			[
				'type'     => 'sortable',
				'settings' => 'homepage_components',
				'iro_key'  => 'homepage_components',
				'label'    => esc_html__( 'Homepage Components', 'Sakurairo_C' ),
				'choices'     => [
          			'exhibition' => __('Display Area','Sakurairo_C'),
					'primary' => __('Article Area','Sakurairo_C'),
					'static_page' => __('Static Page','Sakurairo_C'),
				],
			],
			[
				'type'     => 'dropdown_pages',
				'settings' => 'static_page_id',
				'iro_key'  => 'static_page_id',
				'label'    => esc_html__( 'Select a page', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'homepage_components',
						'operator' => 'contains',
						'value'    => 'static_page',
					],
				],
			],
			[
				'type'     => 'text',
				'settings' => 'exhibition_area_icon',
				'iro_key'  => 'exhibition_area_icon',
				'label'    => esc_html__( 'Display Area Icon', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'homepage_components',
						'operator' => 'contains',
						'value'    => 'exhibition',
					],
				],
			],
			[
				'type'     => 'text',
				'settings' => 'exhibition_area_title',
				'iro_key'  => 'exhibition_area_title',
				'label'    => esc_html__( 'Display Area Title', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'homepage_components',
						'operator' => 'contains',
						'value'    => 'exhibition',
					],
				],
			],
			[
				'type'     => 'text',
				'settings' => 'post_area_icon',
				'iro_key'  => 'post_area_icon',
				'label'    => esc_html__( 'Post Area Icon', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'homepage_components',
						'operator' => 'contains',
						'value'    => 'primary',
					],
				],
			],
			[
				'type'     => 'text',
				'settings' => 'post_area_title',
				'iro_key'  => 'post_area_title',
				'label'    => esc_html__( 'Post Area Title', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'homepage_components',
						'operator' => 'contains',
						'value'    => 'primary',
					],
				],
			],
			[
				'type'     => 'text',
				'settings' => 'area_title_font',
				'iro_key'  => 'area_title_font',
				'label'    => esc_html__( 'Area Title Font', 'Sakurairo_C' ),
				'transport'   => 'postMessage',
				'output' => array(
					array(
						'element'  => array('h1.fes-title','h1.main-title'),
						'property' => 'font-family',
						'value_pattern' => '$ !important',
					),
				)
			],
			[
				'type'     => 'radio_image',
				'settings' => 'area_title_text_align',
				'iro_key'  => 'area_title_text_align',
				'label'    => esc_html__( 'Area Title Alignment', 'Sakurairo_C' ),
				'transport'   => 'auto',
				'choices'     => [
					'left' => $vision_resource_basepath . 'options/area_title_text_left.webp',
					'right' => $vision_resource_basepath . 'options/area_title_text_right.webp',
					'center' => $vision_resource_basepath . 'options/area_title_text_center.webp',
				],
				'output' => array(
					array(
						'element'  => array('h1.fes-title','h1.main-title'),
						'property' => 'justify-content',
						'value_pattern' => '$ !important',
					),
				)
			],
		],
    ],
	// ====================展示区====================
	[
        'id'          => 'iro_display_aera',
        'title'       => esc_html__( 'Display Aera', 'Sakurairo_C' ),
        'description' => '',
        'panel'       => 'iro_homepage',
		'fields'      =>[
			[
				'type'     => 'sortable',
				'settings' => 'capsule_components',
				'iro_key'  => 'capsule_components',
				'label'    => esc_html__( 'Capsule Components', 'Sakurairo_C' ),
				'choices'     => [
          			'post_count'     => __('Posts Capsule','Sakurairo_C'),
					'comment_count'  => __('Comments Capsule','Sakurairo_C'),
					'view_count'  => __('Visitors Capsule','Sakurairo_C'),
					'link_count'     => __('Links Capsule','Sakurairo_C'),
					'author_count'     => __('Authors Capsule','Sakurairo_C'),
					'total_words'     => __('Total Words Capsule','Sakurairo_C'),
					'blog_days'     => __('Blog Running Capsule','Sakurairo_C'),
					'admin_online'     => __('Last Online Capsule','Sakurairo_C'),
					'random_link'     => __('Random Link Capsule','Sakurairo_C'),
					'announcement'     => __('Announcement Capsule','Sakurairo_C'),
				],
			],
			[
				'type'     => 'switch',
				'settings' => 'show_medal_capsules',
				'iro_key'  => 'show_medal_capsules',
				'label'    => esc_html__( 'Show Medal Badges Style Capsule', 'Sakurairo_C' ),
				'default'  => true,
				'description' => esc_html__( 'Enable to show bronze/silver/gold medal badges for blog milestones, Requires you to unlock the relevant achievement to replace the relevant capsule', 'Sakurairo_C' ),
			],
			[
				'type'     => 'textarea',
				'settings' => 'stat_announcement_text',
				'iro_key'  => 'stat_announcement_text',
				'label'    => esc_html__( 'Announcement Text', 'Sakurairo_C' ),
				'description' => esc_html__( 'Set the text for announcement capsule. The front-end will automatically split the text into two lines, you can also use line breaks for manual line breaks', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'capsule_components',
						'operator' => 'contains',
						'value'    => 'announcement',
					],
				],
			],
		],
    ],
	// ====================文章区====================
	[
        'id'          => 'iro_article_aera',
        'title'       => esc_html__( 'Article Aera', 'Sakurairo_C' ),
        'description' => '',
        'panel'       => 'iro_homepage',

		'fields'      =>[
			[
				'type'     => 'select',
				'settings' => 'article_meta_displays',
				'iro_key'  => 'article_meta_displays',
				'label'    => esc_html__( 'Article Area Meta Displays', 'Sakurairo_C' ),
				'multiple'    => 0, // 想选多少选多少
				'choices'     => [
					"author" => __("Author","Sakurairo_C"),
					"category" => __("Category","Sakurairo_C"),
					"comment_count" => __("Number of Comments","Sakurairo_C"),
					"post_views" => __("Number of Views","Sakurairo_C"),
					"post_words_count" => __("Number of Words","Sakurairo_C"),
					"reading_time" => __("Estimate Reading Time","Sakurairo_C"),
				],
			],
			[
				'type'     => 'radio_image',
				'settings' => 'post_list_design',
				'iro_key'  => 'post_list_design',
				'label'    => esc_html__( 'Article Area Card Design', 'Sakurairo_C' ),
				'choices'     => [
					'letter' => $vision_resource_basepath . 'options/post_list_design_letter.webp',
          			'ticket' => $vision_resource_basepath . 'options/post_list_design_ticket.webp',
				],
			],
			[
				'type'     => 'radio_image',
				'settings' => 'post_list_ticket_type',
				'iro_key'  => 'post_list_ticket_type',
				'label'    => esc_html__( 'Article Area Card Design', 'Sakurairo_C' ),
				'choices'     => [
					'card' => $vision_resource_basepath . 'options/post_list_design_ticket.webp',
          			'non-card' => $vision_resource_basepath . 'options/post_list_design_ticket_2.webp',
				],
				'active_callback' => [
					[
						'setting'  => 'post_list_design',
						'operator' => '==',
						'value'    => 'ticket',
					]
				],
			],
			[
				'type'     => 'switch',
				'settings' => 'article_meta_background_compatible',
				'iro_key'  => 'article_meta_background_compatible',
				'label'    => esc_html__( 'Article Area Card Information Meta Background Compatible', 'Sakurairo_C' ),
			],
			[
				'type'     => 'switch',
				'settings' => 'show_shuoshuo_on_home_page',
				'iro_key'  => 'show_shuoshuo_on_home_page',
				'label'    => esc_html__( 'Show shuoshuo on home page', 'Sakurairo_C' ),
			],
			[
				'type'     => 'slider',
				'settings' => 'post_meta_radius', //信息
				'iro_key'  => 'post_meta_radius',
				'label'    => esc_html__( 'Article Area Card Information Meta Rounded Corners', 'Sakurairo_C' ),
				'choices'     => [
					'min'  => 0,
					'max'  => 30,
					'step' => 1,
				],
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => array('.post-date', '.post-meta'),
						'property' => 'border-radius',
						'value_pattern' => '$px !important',
					),
				),
			],
			[
				'type'     => 'slider',
				'settings' => 'post_list_title_radius', //标题
				'iro_key'  => 'post_list_title_radius',
				'label'    => esc_html__( 'Article Area Card Title Meta Rounded Corners', 'Sakurairo_C' ),
				'choices'     => [
					'min'  => 0,
					'max'  => 30,
					'step' => 1,
				],
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => '.post-title',
						'property' => 'border-radius',
						'value_pattern' => '$px !important',
					),
				),
			],
			[
				'type'     => 'slider',
				'settings' => 'post_list_card_radius', //卡片
				'iro_key'  => 'post_list_card_radius',
				'label'    => esc_html__( 'Article Area Card Rounded Corners', 'Sakurairo_C' ),
				'choices'     => [
					'min'  => 5,
					'max'  => 20,
					'step' => 1,
				],
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => array('.shuoshuo-item','.post-list-thumb'),
						'property' => 'border-radius',
						'value_pattern' => '$px !important',
					),
				),
			],
			[
				'type'     => 'slider',
				'settings' => 'post_title_font_size', //字体
				'iro_key'  => 'post_title_font_size',
				'label'    => esc_html__( 'Article Area Title Font Size', 'Sakurairo_C' ),
				'choices'     => [
					'min'  => 1,
					'max'  => 30,
					'step' => 1,
				],
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => '.post-list-thumb .post-title h3',
						'property' => 'font-size',
						'value_pattern' => '$px !important',
					),
				),
			],
		],
    ],
	// ====================前台背景、字体====================
	[
        'id'          => 'iro_front',
        'title'       => esc_html__( 'Frontend Background', 'Sakurairo_C' ),
        'description' => '',
        'panel'       => 'iro_global',

		'fields'      =>[
			[
				'type'     => 'image',
				'settings' => 'reception_background_img1',
				'iro_key'  => 'reception_background',
				'iro_subkey'  => 'img1',
				'label'    => esc_html__( 'Default Frontend Background', 'Sakurairo_C' ),
			],
			[
				'type'     => 'slider',
				'settings' => 'reception_background_transparency',
				'iro_key'  => 'reception_background_transparency',
				'label'    => esc_html__( 'Background Transparency in the Frontend', 'Sakurairo_C' ),
				'choices'     => [
					'min'  => 0.2,
					'max'  => 1,
					'step' => 0.01,
				],
			],
			[
				'type'     => 'switch',
				'settings' => 'reception_background_blur',
				'iro_key'  => 'reception_background_blur',
				'label'    => esc_html__( 'Background Transparency Blur', 'Sakurairo_C' ),
			],
			[
				'type'     => 'select',
				'settings' => 'reception_background_size',
				'iro_key'  => 'reception_background_size',
				'label'    => esc_html__( 'Frontend Background Scaling Method', 'Sakurairo_C' ),
				'choices'     => [
					'cover' => __('Cover','Sakurairo_C'),
					'contain' => __('Contain','Sakurairo_C'),
					'auto' => __('Auto','Sakurairo_C'),
				],
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => 'body',
						'background-size' => 'font-size',
						'value_pattern' => '$ !important',
					),
				),
			],
			[
				'type'     => 'text',
				'settings' => 'global_default_font',
				'iro_key'  => 'global_default_font',
				'label'    => esc_html__( 'Global Default Font', 'Sakurairo_C' ),
				'description' => esc_html__( 'Fill in the font name,and you can add your customize font in Font Options of Global Options in iro-Options.', 'Sakurairo_C' ),
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => '.serif',
						'property' => 'font-family',
						'value_pattern' => '$ !important',
					),
				),
			],
			[
				'type'     => 'slider',
				'settings' => 'global_font_weight',
				'iro_key'  => 'global_font_weight',
				'label'    => esc_html__( 'Non-Emphasis Text Weight', 'Sakurairo_C' ),
				'choices'     => [
					'min'  => 100,
					'max'  => 700,
					'step' => 10,
				],
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => ':root',
						'property' => '--global-font-weight',
						'value_pattern' => '$px !important',
					),
				),
			],
			[
				'type'     => 'slider',
				'settings' => 'global_font_size',
				'iro_key'  => 'global_font_size',
				'label'    => esc_html__( 'Global Font Size', 'Sakurairo_C' ),
				'choices'     => [
					'min'  => 10,
					'max'  => 20,
					'step' => 1,
				],
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => array('.serif','body'),
						'property' => 'font-size',
						'value_pattern' => '$px !important',
					),
				),
			],
		],
    ],
	// ====================小组件====================
	[
        'id'          => 'iro_widgets',
        'title'       => esc_html__( 'Widgets Panel', 'Sakurairo_C' ),
        'description' => '',
        'panel'       => 'iro_global',

		'fields'      =>[
			[
				'type'     => 'slider',
				'settings' => 'style_menu_radius',
				'iro_key'  => 'style_menu_radius',
				'label'    => esc_html__( 'Widgets Panel Button Radius', 'Sakurairo_C' ),
				'choices'     => [
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				],
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => ':root',
						'property' => '--style_menu_radius',
						'value_pattern' => '$px !important',
					),
				),
			],
			[
				'type'     => 'slider',
				'settings' => 'style_menu_selection_radius',
				'iro_key'  => 'style_menu_selection_radius',
				'label'    => esc_html__( 'Widgets Panel Widget Radius', 'Sakurairo_C' ),
				'choices'     => [
					'min'  => 0,
					'max'  => 30,
					'step' => 1,
				],
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => ':root',
						'property' => '--style_menu_selection_radius',
						'value_pattern' => '$px !important',
					),
				),
			],
			[
				'type'     => 'text',
				'settings' => 'style_menu_font',
				'iro_key'  => 'style_menu_font',
				'label'    => esc_html__( 'Widgets Panel Font', 'Sakurairo_C' ),
			],
			[
				'type'     => 'switch',
				'settings' => 'sakura_widget',
				'iro_key'  => 'sakura_widget',
				'label'    => esc_html__( 'Widgets Panel WP Widget Area', 'Sakurairo_C' ),
			],
			[
				'type'     => 'switch',
				'settings' => 'iro_widget_daynight',
				'iro_key'  => 'widget_daynight',
				'label'    => esc_html__( 'Widgets Panel Day&Night Switching', 'Sakurairo_C' ),
			],
			[
				'type'     => 'switch',
				'settings' => 'iro_widget_font',
				'iro_key'  => 'widget_font',
				'label'    => esc_html__( 'Widgets Panel Font Switching', 'Sakurairo_C' ),
			],
			[
				'type'     => 'text',
				'settings' => 'global_default_font',
				'iro_key'  => 'global_default_font',
				'label'    => esc_html__( 'Global Default Font&Widgets Panel Font Switching A', 'Sakurairo_C' ),
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => '.serif',
						'property' => 'font-family',
						'value_pattern' => '$ !important',
					),
				),
			],
			[
				'type'     => 'text',
				'settings' => 'global_font_2',
				'iro_key'  => 'global_font_2',
				'label'    => esc_html__( 'Widgets Panel Font Switching B', 'Sakurairo_C' ),
			],
			//四个背景按钮
			[
				'type'     => 'switch',
				'settings' => 'reception_background_heart_shaped',
				'iro_key'  => 'reception_background',
				'iro_subkey'  => 'heart_shaped',
				'label'    => esc_html__( '♡Option Switcher', 'Sakurairo_C' ),
			],
			[
				'type'     => 'image',
				'settings' => 'reception_background_img2',
				'iro_key'  => 'reception_background',
				'iro_subkey'  => 'img2',
				'label'    => esc_html__( '♡Corresponding Background', 'Sakurairo_C' ),
			],
			[
				'type'     => 'switch',
				'settings' => 'reception_background_star_shaped',
				'iro_key'  => 'reception_background',
				'iro_subkey'  => 'star_shaped',
				'label'    => esc_html__( '☆Option Switcher', 'Sakurairo_C' ),
			],
			[
				'type'     => 'image',
				'settings' => 'reception_background_img3',
				'iro_key'  => 'reception_background',
				'iro_subkey'  => 'img3',
				'label'    => esc_html__( '☆Corresponding Background', 'Sakurairo_C' ),
			],
			[
				'type'     => 'switch',
				'settings' => 'reception_background_square_shaped',
				'iro_key'  => 'reception_background',
				'iro_subkey'  => 'square_shaped',
				'label'    => esc_html__( '□Option Switcher', 'Sakurairo_C' ),
			],
			[
				'type'     => 'image',
				'settings' => 'reception_background_img4',
				'iro_key'  => 'reception_background',
				'iro_subkey'  => 'img4',
				'label'    => esc_html__( '□Corresponding Background', 'Sakurairo_C' ),
			],
			[
				'type'     => 'switch',
				'settings' => 'reception_background_lemon_shaped',
				'iro_key'  => 'reception_background',
				'iro_subkey'  => 'lemon_shaped',
				'label'    => esc_html__( '🍋Option Switcher', 'Sakurairo_C' ),
			],
			[
				'type'     => 'image',
				'settings' => 'reception_background_img5',
				'iro_key'  => 'reception_background',
				'iro_subkey'  => 'img5',
				'label'    => esc_html__( '🍋Corresponding Background', 'Sakurairo_C' ),
			],
		],
    ],
	// ====================粒子特效====================
	[
        'id'          => 'iro_particles',
        'title'       => esc_html__( 'Particles', 'Sakurairo_C' ),
        'description' => '',
        'panel'       => 'iro_global',

		'fields'      =>[
			[
				'type'     => 'select',
				'settings' => 'sakura_falling_effects',
				'iro_key'  => 'sakura_falling_effects',
				'label'    => esc_html__( 'Sakura Falling Effects', 'Sakurairo_C' ),
				'choices'     => [
					'off' => __('Off','Sakurairo_C'),
					'native' => __('Native Quantity','Sakurairo_C'),
					'quarter' => __('Quarter Quantity','Sakurairo_C'),
					'half' => __('Half Quantity','Sakurairo_C'),
					'less' => __('Less Quantity','Sakurairo_C'),
				],
			],
			[
				'type'     => 'switch',
				'settings' => 'particles_effects',
				'iro_key'  => 'particles_effects',
				'label'    => esc_html__( 'Particles Effects', 'Sakurairo_C' ),
			],
			[
				'type'     => 'code',
				'settings' => 'particles_json',
				'iro_key'  => 'particles_json',
				'label'    => esc_html__( 'Particles JSON', 'Sakurairo_C' ),
				'description' => esc_html__( 'Vist "https://vincentgarreau.com/particles.js/" for more help', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'particles_effects',
						'operator' => '==',
						'value'    => true,
					]
				],
				'choices'     => [
					'language' => 'json',
				],
			],
		],
    ],
	// ====================页脚====================
	[
        'id'          => 'iro_footer',
        'title'       => esc_html__( 'Footer Info', 'Sakurairo_C' ),
        'description' => '',
        'panel'       => 'iro_global',

		'fields'      =>[
			[
				'type'     => 'select',
				'settings' => 'footer_direction',
				'iro_key'  => 'footer_direction',
				'label'    => esc_html__( 'Footer Content Distribution', 'Sakurairo_C' ),
				'choices'     => [
					'center' => __('Center','Sakurairo_C'),
					'columns' => __('Two Coloumns','Sakurairo_C'),
				],
			],
			[
				'type'     => 'switch',
				'settings' => 'footer_sakura',
				'iro_key'  => 'footer_sakura',
				'label'    => esc_html__( 'Footer Sakura Icon', 'Sakurairo_C' ),
			],
			[
				'type'     => 'code',
				'settings' => 'footer_info',
				'iro_key'  => 'footer_info',
				'label'    => esc_html__( 'Footer Info', 'Sakurairo_C' ),
				'choices'     => [
					'language' => 'html',
				],
				'transport'   => 'postMessage',
				'js_vars' => [
					[
						'element'  => '.footer_info',
						'function' => 'html',
					],
				],
			],
			[
				'type'     => 'text',
				'settings' => 'footer_text_font',
				'iro_key'  => 'footer_text_font',
				'label'    => esc_html__( 'Footer Text Font', 'Sakurairo_C' ),
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => array('.site-info','.site-info a'),
						'property' => 'font-family',
						'value_pattern' => '$ !important',
					),
				),
			],
			[
				'type'     => 'switch',
				'settings' => 'footer_load_occupancy',
				'iro_key'  => 'footer_load_occupancy',
				'label'    => esc_html__( 'Footer Load Occupancy Query', 'Sakurairo_C' ),
			],
			[
				'type'     => 'switch',
				'settings' => 'footer_upyun',
				'iro_key'  => 'footer_upyun',
				'label'    => esc_html__( 'Footer Upyun League Logo', 'Sakurairo_C' ),
			],
			[
				'type'     => 'switch',
				'settings' => 'footer_yiyan',
				'iro_key'  => 'footer_yiyan',
				'label'    => esc_html__( 'Footer Hitokoto', 'Sakurairo_C' ),
			],
			[
				'type'     => 'code',
				'settings' => 'yiyan_api',
				'iro_key'  => 'yiyan_api',
				'label'    => esc_html__( 'Hitokoto API address', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'footer_yiyan',
						'operator' => '==',
						'value'    => true,
					]
				],
				'choices'     => [
					'language' => 'json',
				],
			],
		],
    ],
	// ====================全局杂项====================
	[
        'id'          => 'iro_global_others',
        'title'       => esc_html__( 'Others', 'Sakurairo_C' ),
        'description' => '',
        'panel'       => 'iro_global',

		'fields'      =>[
			[
				'type'     => 'switch',
				'settings' => 'nprogress_on',
				'iro_key'  => 'nprogress_on',
				'label'    => esc_html__( 'NProgress Loading Progress Bar', 'Sakurairo_C' ),
				'description' => esc_html__('Enabled by default, when loading page there will be a progress bar alert','Sakurairo_C'),
			],
			[
				'type'     => 'switch',
				'settings' => 'smoothscroll_option',
				'iro_key'  => 'smoothscroll_option',
				'label'    => esc_html__( 'Global Smooth Scroll', 'Sakurairo_C' ),
				'description' => esc_html__('Enabled by default, page scrolling will be smoother','Sakurairo_C'),
			],
			[
				'type'     => 'select',
				'settings' => 'pagenav_style',
				'iro_key'  => 'pagenav_style',
				'label'    => esc_html__( 'Pagination Mode', 'Sakurairo_C' ),
				'choices'     => [
					'ajax' => __('Ajax Load','Sakurairo_C'),
					'np' => __('Page Up/Down','Sakurairo_C'),
				],
			],
			[
				'type'     => 'select',
				'settings' => 'page_auto_load',
				'iro_key'  => 'page_auto_load',
				'label'    => esc_html__( 'Next Page Auto Load', 'Sakurairo_C' ),
				'choices'     => [
					'0' => __('0 Sec','Sakurairo_C'),
					'1' => __('1 Sec','Sakurairo_C'),
					'2' => __('2 Sec','Sakurairo_C'),
					'3' => __('3 Sec','Sakurairo_C'),
					'4' => __('4 Sec','Sakurairo_C'),
					'5' => __('5 Sec','Sakurairo_C'),
					'6' => __('6 Sec','Sakurairo_C'),
					'7' => __('7 Sec','Sakurairo_C'),
					'8' => __('8 Sec','Sakurairo_C'),
					'9' => __('9 Sec','Sakurairo_C'),
					'10' => __('10 Sec','Sakurairo_C'),
					'233' => __('Do not autoload','Sakurairo_C'),
				],
			],
			[
				'type'     => 'image',
				'settings' => 'load_nextpage_svg',
				'iro_key'  => 'load_nextpage_svg',
				'label'    => esc_html__( 'Placeholder SVG when loading the next page', 'Sakurairo_C' ),
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => ':root',
						'property' => '--load_nextpage_svg',
					),
				),
			],
		],
    ],
	// ====================页面通用设置====================
	[
        'id'          => 'iro_pages_common',
        'title'       => esc_html__( 'Common Options', 'Sakurairo_C' ),
        'description' => '',
        'panel'       => 'iro_pages',

		'fields'      =>[
			[
				'type'     => 'radio',
				'settings' => 'entry_content_style',
				'iro_key'  => 'entry_content_style',
				'label'    => esc_html__( 'Page Layout Style', 'Sakurairo_C' ),
				'choices'     => [
					'sakurairo' => __('Default Style','Sakurairo_C'),
          			'github' => __('Github Style','Sakurairo_C'),
				],
			],
			[
				'type'     => 'switch',
				'settings' => 'patternimg',
				'iro_key'  => 'patternimg',
				'label'    => esc_html__( 'Page Decoration Image', 'Sakurairo_C' ),
			],
			[
				'type'     => 'switch',
				'settings' => 'page_title_animation',
				'iro_key'  => 'page_title_animation',
				'label'    => esc_html__( 'Page Title Animation', 'Sakurairo_C' ),
			],
			[
				'type'     => 'slider',
				'settings' => 'page_title_animation_time',
				'iro_key'  => 'page_title_animation_time',
				'label'    => esc_html__( 'Page Title Animation Time', 'Sakurairo_C' ),
				'choices'     => [
					'min'  => 0,
					'max'  => 5,
					'step' => 0.01,
				],
				'active_callback' => [
					[
						'setting'  => 'page_title_animation',
						'operator' => '==',
						'value'    => true,
					]
				],
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => '.entry-title,.single-center .entry-census,.entry-census,.p-time',
						'property' => 'animation',
						'value_pattern' => 'homepage-load-animation $s !important',
					),
					array(
						'element'  => '.single-center .single-header h1.entry-title::after',
						'property' => 'animation',
						'value_pattern' => 'lineWidth 2s $s forwards !important',
					),
				),
			],
			[
				'type'     => 'image',
				'settings' => 'load_in_svg',
				'iro_key'  => 'load_in_svg',
				'label'    => esc_html__( 'Page Image Placeholder SVG', 'Sakurairo_C' ),
			],
		],
    ],
	// ====================文章页设置====================
	[
        'id'          => 'iro_pages_post',
        'title'       => esc_html__( 'Posts Pages', 'Sakurairo_C' ),
        'description' => '',
        'panel'       => 'iro_pages',

		'fields'      =>[
			[
				'type'     => 'slider',
				'settings' => 'article_title_font_size',
				'iro_key'  => 'article_title_font_size',
				'description' => esc_html__( 'This option is only valid for articles with cover', 'Sakurairo_C' ),
				'label'    => esc_html__( 'Article Page Title Font Size', 'Sakurairo_C' ),
				'choices'     => [
					'min'  => 16,
					'max'  => 48,
					'step' => 1,
				],
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => '.single-center .single-header h1.entry-title',
						'property' => 'font-size',
						'value_pattern' => '$px !important',
					),
				),
			],
			[
				'type'     => 'switch',
				'settings' => 'article_title_line',
				'iro_key'  => 'article_title_line',
				'label'    => esc_html__( 'Article Page Title Underline Animation', 'Sakurairo_C' ),
			],
			[
				'type'     => 'select',
				'settings' => 'article_meta_show_in_head',
				'iro_key'  => 'article_meta_show_in_head',
				'label'    => esc_html__( 'Article Area Meta Displays', 'Sakurairo_C' ),
				'multiple'    => 0,
				'choices'     => [
					"author" => __("Author","Sakurairo_C"),
					"category" => __("Category","Sakurairo_C"),
					"comment_count" => __("Number of Comments","Sakurairo_C"),
					"post_views" => __("Number of Views","Sakurairo_C"),
					"post_words_count" => __("Number of Words","Sakurairo_C"),
					"reading_time" => __("Estimate Reading Time","Sakurairo_C"),
					"publish_time_relative" => __("Publish Time (Relatively)","Sakurairo_C"),
  					"last_edit_time_relative" => __("Last Edit Time (Relatively)","Sakurairo_C"),
  					"EDIT" => __("Action Edit (only displays while user has sufficient permissions)","Sakurairo_C"),
				],
			],
			[
				'type'     => 'switch',
				'settings' => 'article_auto_toc',
				'iro_key'  => 'article_auto_toc',
				'label'    => esc_html__( 'Article Page Auto Show Menu', 'Sakurairo_C' ),
			],
			[
				'type'     => 'color',
				'settings' => 'inline_code_background_color',
				'iro_key'  => 'inline_code_background_color',
				'label'    => esc_html__( 'Inline Code Background Color', 'Sakurairo_C' ),
				'choices'     => [
					'alpha' => true,
				],
				'transport'   => 'auto',
				'output'      => array(
					array(
						'element'  => ':root',
						'property' => '--inline_code_background_color',
						'value_pattern' => '$ !important',
					),
				),
			],
			[
				'type'     => 'color',
				'settings' => 'inline_code_background_color_in_dark_mode',
				'iro_key'  => 'inline_code_background_color_in_dark_mode',
				'label'    => esc_html__( 'Inline Code Background Color In Dark Mode', 'Sakurairo_C' ),
				'choices'     => [
					'alpha' => true,
				],
				'transport'   => 'auto',
				'output'      => array(
					array(
						'element'  => ':root',
						'property' => '--inline_code_background_color_in_dark_mode',
						'value_pattern' => '$ !important',
					),
				),
			],
		],
    ],
	// ====================文章扩展====================
	[
        'id'          => 'iro_pages_extra',
        'title'       => esc_html__( 'Pages Extend Options', 'Sakurairo_C' ),
        'description' => '',
        'panel'       => 'iro_pages',

		'fields'      =>[
			[
				'type'     => 'switch',
				'settings' => 'article_function',
				'iro_key'  => 'article_function',
				'label'    => esc_html__( 'Article Page Function Bar', 'Sakurairo_C' ),
			],
			[
				'type'     => 'select',
				'settings' => 'article_lincenses',
				'iro_key'  => 'article_lincenses',
				'label'    => esc_html__( 'Article License', 'Sakurairo_C' ),
				'choices'     => [
					false => __("Not Display","Sakurairo_C"),
					"cc0" => "CC0 1.0",
					"cc-by" => "CC BY 4.0",
					"cc-by-nc" => "CC BY-NC 4.0",
					"cc-by-nc-nd" => "CC BY-NC-ND 4.0",
					true => "CC BY-NC-SA 4.0",
					"cc-by-nd" => "CC BY-ND 4.0",
					"cc-by-sa" => "CC BY-SA 4.0",
				],
				'active_callback' => [
					[
						'setting'  => 'article_function',
						'operator' => '==',
						'value'    => true,
					]
				],
			],
			[
				'type'     => 'text',
				'settings' => 'reward_area_link',
				'iro_key'  => 'reward_area',
				'iro_subkey' => 'link',
				'label'    => esc_html__( 'Reward Button Link', 'Sakurairo_C' ),
				'description' => esc_html__( 'The link click the reward button will redirect to', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'article_function',
						'operator' => '==',
						'value'    => true,
					]
				],
			],
			[
				'type'     => 'image',
				'settings' => 'reward_area_image1',
				'iro_key'  => 'reward_area',
				'iro_subkey' => 'image1',
				'label'    => esc_html__( 'Reward Image', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'article_function',
						'operator' => '==',
						'value'    => true,
					]
				],
			],
			[
				'type'     => 'text',
				'settings' => 'reward_area_link1',
				'iro_key'  => 'reward_area',
				'iro_subkey' => 'link1',
				'label'    => esc_html__( 'Reward Image Link', 'Sakurairo_C' ),
				'description' => esc_html__( 'The link click the image will redirect to', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'article_function',
						'operator' => '==',
						'value'    => true,
					]
				],
			],
			[
				'type'     => 'image',
				'settings' => 'reward_area_image2',
				'iro_key'  => 'reward_area',
				'iro_subkey' => 'image2',
				'label'    => esc_html__( 'Reward Image', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'article_function',
						'operator' => '==',
						'value'    => true,
					]
				],
			],
			[
				'type'     => 'text',
				'settings' => 'reward_area_link2',
				'iro_key'  => 'reward_area',
				'iro_subkey' => 'link2',
				'label'    => esc_html__( 'Reward Image Link', 'Sakurairo_C' ),
				'description' => esc_html__( 'The link click the image will redirect to', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'article_function',
						'operator' => '==',
						'value'    => true,
					]
				],
			],
			[
				'type'     => 'switch',
				'settings' => 'author_profile_avatar',
				'iro_key'  => 'author_profile_avatar',
				'label'    => esc_html__( 'Article Page Author Avatar', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'article_function',
						'operator' => '==',
						'value'    => true,
					]
				],
			],
			[
				'type'     => 'switch',
				'settings' => 'author_profile_name',
				'iro_key'  => 'author_profile_name',
				'label'    => esc_html__( 'Article Page Author Name', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'article_function',
						'operator' => '==',
						'value'    => true,
					]
				],
			],
			[
				'type'     => 'switch',
				'settings' => 'author_profile_quote',
				'iro_key'  => 'author_profile_quote',
				'label'    => esc_html__( 'Article Page Author Signature', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'article_function',
						'operator' => '==',
						'value'    => true,
					]
				],
			],
			[
				'type'     => 'switch',
				'settings' => 'article_modified_time',
				'iro_key'  => 'article_modified_time',
				'label'    => esc_html__( 'Article Last Update Time', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'article_function',
						'operator' => '==',
						'value'    => true,
					]
				],
			],
			[
				'type'     => 'switch',
				'settings' => 'article_tag',
				'iro_key'  => 'article_tag',
				'label'    => esc_html__( 'Article Tag', 'Sakurairo_C' ),
				'active_callback' => [
					[
						'setting'  => 'article_function',
						'operator' => '==',
						'value'    => true,
					]
				],
			],
			[
				'type'     => 'switch',
				'settings' => 'article_nextpre',
				'iro_key'  => 'article_nextpre',
				'label'    => esc_html__( 'Article Page Prev/Next Article Switcher', 'Sakurairo_C' ),
			],
		],
	],
	// ====================评论区====================
	[
        'id'          => 'iro_pages_comment',
        'title'       => esc_html__( 'Comment Options', 'Sakurairo_C' ),
        'description' => '',
        'panel'       => 'iro_pages',

		'fields'      =>[
			[
				'type'     => 'radio',
				'settings' => 'comment_area',
				'iro_key'  => 'comment_area',
				'label'    => esc_html__( 'Page Comment Area Display', 'Sakurairo_C' ),
				'choices'     => [
					'unfold' => __('Expand','Sakurairo_C'),
          			'fold' => __('Fold','Sakurairo_C'),
				],
			],
			[
				'type'     => 'text',
				'settings' => 'comment_placeholder_text',
				'iro_key'  => 'comment_placeholder_text',
				'label'    => esc_html__( 'Custom CommentBox Placeholder', 'Sakurairo_C' ),
			],
			[
				'type'     => 'text',
				'settings' => 'comment_submit_button_text',
				'iro_key'  => 'comment_submit_button_text',
				'label'    => esc_html__( 'Custom Submit Button Content', 'Sakurairo_C' ),
			],
			[
				'type'     => 'image',
				'settings' => 'comment_area_image',
				'iro_key'  => 'comment_area_image',
				'label'    => esc_html__( 'Page Comment Area Bottom Right Background Image', 'Sakurairo_C' ),
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => '.comment-respond textarea',
						'property' => 'background-image',
						'value_pattern' => '$ !important',
					),
				),
			],
			[
				'type'     => 'select',
				'settings' => 'smilies_list',
				'iro_key'  => 'smilies_list',
				'label'    => esc_html__( 'Comment Area Emoticon', 'Sakurairo_C' ),
				'description' => esc_html__( 'Please go to the backend to configure your custom emoticon pack', 'Sakurairo_C' ),
				'multiple'    => 0,
				'choices'     => [
					'bilibili'   => __('BiliBili Emoticon Pack','Sakurairo_C'),
					'tieba'   => __('Baidu Tieba Emoticon Pack','Sakurairo_C'),
					'yanwenzi' => __('Emoji','Sakurairo_C'),
					'custom' => __('Customized Emoticon Pack','Sakurairo_C'),
				],
			],
			[
				'type'     => 'custom',
				'settings' => 'nav_menu_notice',
				'default'  => __('For more detailed configuration of the comment area, please go to the backend configuration','Sakurairo_C'),
			],
		],
	],
];

// ====================Panel注册====================
$panelAutoPriority = 10;
foreach ( $panels as &$panel ) {
    if ( empty( $panel['priority'] ) ) {
        $panel['priority'] = $panelAutoPriority;
        $panelAutoPriority += 10;
    }
}
unset( $panel );

$sectionAutoPriority = 10;
foreach ( $sections as &$section ) {
    if ( empty( $section['priority'] ) ) {
        $section['priority'] = $sectionAutoPriority;
        $sectionAutoPriority += 10;
    }
}
unset( $section );

foreach ( $panels as $panel ) {
    // 必须字段：id、title
    // 可选字段：description、priority
    switch ( 'panel' ) {
        case 'panel':
			new \Kirki\Panel(
                $panel['id'],
                [
                    'title'       => $panel['title'],
                    'description' => isset( $panel['description'] ) ? $panel['description'] : '',
                    'priority'    => $panel['priority'],
                ]
            );
            break;
    }
}

// ====================分组和设置项注册====================
// 定义各字段类型的默认值映射（必填项 default 若未设置时使用）  
$type_defaults = [
	'background'       => [],
	'checkbox'         => false,
	'code'             => '',
	'color'            => '#000000',
	'color_palette'    => '#000000',
	'dashicons'        => '',
	'date'             => '',
	'dimension'        => '',
	'dimensions'       => [],
	'dropdown_pages'   => '',
	'editor'           => '',
	'generic'          => '',
	'image'            => '',
	'url'              => '',
	'multicheck'       => [],
	'multicolor'       => [],
	'number'           => 0,
	'palette'          => '',
	'radio'            => '',
	'radio_buttonset'  => '',
	'radio_image'      => '',
	'repeater'         => [],
	'select'           => '',
	'slider'           => 0,
	'sortable'         => [],
	'switch'           => false,
	'text'             => '',
	'textarea'         => '',
	'toggle'           => false,
	'typography'       => [],
	'upload'           => '',
	'input_slider'     => 0,
];
foreach ( $sections as $section ) {
    // 必须字段：id、title、panel
    // 可选字段：description、priority
    $section_id = $section['id'];
	new \Kirki\Section(
		$section['id'],
		[
			'title'       => $section['title'],
			'description' => isset( $panel['description'] ) ? $panel['description'] : '',
			'panel'       => $section['panel'],
			'priority'    => $section['priority'],
		]
	);

	// 自动设置字段排序
	$priority = 10;
	if ( isset( $section['fields'] ) && is_array( $section['fields'] ) ) {
		foreach ( $section['fields'] as &$field ) {
			if ( empty( $field['priority'] ) ) {
				$field['priority'] = $priority;
				$priority += 10;
			}
		}
		unset( $field );
	}

	// 含有fields设置项
	if ( isset( $section['fields'] ) && is_array( $section['fields'] ) ) {
		foreach ( $section['fields'] as $field ) {
			// 自动将当前 section 的 id 分配给字段
			$field['section'] = $section_id;

			// 构造设置项，仅提取允许的参数
			$args = [];
			foreach ( $allowed_params as $param ) {
				if ( isset( $field[ $param ] ) ) {
					$args[ $param ] = $field[ $param ];
				}
			}

			// 对必填项做检查与默认处理
			// 必须字段：label、settings、section、priority，
			if ( ! isset( $args['label'] ) ) {
				$args['label'] = '';
			}
			if ( ! isset( $args['settings'] ) ) {
				// 如果没有设置 settings，则跳过此字段（或记录错误）
				error_log( 'Customize filed setting name missed.' );
				continue;
			}
			if ( ! isset( $args['capability'] ) ) { // Kirki 4.0
				$args['capability'] = 'edit_theme_options';
			}
			if ( ! isset( $args['option_type'] ) ) {
				$args['option_type'] = 'theme_mod';
			}
			// if ( ! isset( $args['option_name'] ) ) { // 仅限option类型，theme_mod无效
			// 	$args['option_name'] = 'iro_options';
			// }

			// 自动根据类型补充默认值
			if ( ! isset( $args['default'] ) ) {
				// 将 type 转为小写并用下划线替换空格
				$type_key = strtolower($field['type'] ?? '');
				if ( isset( $type_defaults[ $type_key ] ) ) {
					$args['default'] = $type_defaults[ $type_key ];
				} else {
					$args['default'] = '';
				}
			}

			if ( isset( $args['iro_key'] ) ) {
				$setting_id    = $args['settings'];
				$iro_key       = $args['iro_key'];
				$type_default  = $args['default'];
				$iro_default   = $GLOBALS['iro_options'][$iro_key];
				$iro_subkey    = isset( $args['iro_subkey'] ) ? $args['iro_subkey'] : '';

				if ( ! isset( $args['transport'] ) ) { // 没设置预览方式的默认请求php渲染
					$args['transport'] = 'refresh';
				}

				$iro_options_map = get_theme_mod('iro_options_map', []);
				// 构建映射结构
				$iro_options_map[$setting_id] = [
					'iro_key'    => $iro_key,
					'iro_subkey' => $iro_subkey,
					'default'    => $iro_default,
				];

				// 存储映射表
				set_theme_mod('iro_options_map', $iro_options_map);

				// 自动default
				$args['default'] = isset($args['iro_subkey']) 
								? (is_array($iro_default) && isset($iro_default[$args['iro_subkey']]) ? $iro_default[$args['iro_subkey']] : $type_default) 
								: ($iro_default !== null ? $iro_default : $type_default); //从iro_opt中获取默认值，或使用种类默认值
				
				// set_theme_mod($setting_id, $args['default']);
			}

			// 根据字段类型选择对应的 Kirki 类注册组件
			// 将类型字符串统一转换为小写，下划线格式
			// 分类按需实例化
			$field_type_key = str_replace( ' ', '_', strtolower( $field['type'] ) );
			switch ( $field_type_key ) {
				case 'checkbox':
					new \Kirki\Field\Checkbox( $args );
					break;
				case 'code':
					new \Kirki\Field\Code( $args );
					break;
				case 'color':
					new \Kirki\Field\Color( $args );
					break;
				case 'custom':
					new \Kirki\Field\Custom( $args );
					break;
				case 'dashicons':
					new \Kirki\Field\Dashicons( $args );
					break;
				case 'dropdown_pages':
					new \Kirki\Field\Dropdown_Pages( $args );
					break;
				case 'generic':
					new \Kirki\Field\Generic( $args );
					break;
				case 'image':
					new \Kirki\Field\Image( $args );
					break;
				case 'url':
					new \Kirki\Field\URL( $args );
					break;
				case 'multicheck':
					new \Kirki\Field\Multicheck( $args );
					break;
				case 'number':
					new \Kirki\Field\Number( $args );
					break;
				case 'radio':
					new \Kirki\Field\Radio( $args );
					break;
				case 'radio_buttonset':
					new \Kirki\Field\Radio_Buttonset( $args );
					break;
				case 'radio_image':
					new \Kirki\Field\Radio_Image( $args );
					break;
				case 'repeater':
					new \Kirki\Field\Repeater( $args );
					break;
				case 'select':
					new \Kirki\Field\Select( $args );
					break;
				case 'slider':
					new \Kirki\Field\Slider( $args );
					break;
				case 'sortable':
					new \Kirki\Field\Sortable( $args );
					break;
				case 'switch':
					new \Kirki\Field\Checkbox_Switch( $args );
					break;
				case 'text':
					new \Kirki\Field\Text( $args );
					break;
				case 'textarea':
					new \Kirki\Field\Textarea( $args );
					break;
					case 'toggle':
					new \Kirki\Field\Checkbox_Toggle( $args );
					break;
				case 'upload':
					new \Kirki\Field\Upload( $args );
					break;
				case 'input_slider':
					new \Kirki\Pro\Field\InputSlider( $args );
					break;
				case 'divider':
					new \Kirki\Pro\Field\Divider( $args );
					break;
				default:
					error_log( 'Unknown Kirki field type: ' . $field['type'] );
					break;
			}
		}
	}
}