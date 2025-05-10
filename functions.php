<?php

/**
 * iro functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package iro
 */

include_once('inc/classes/IpLocation.php');

define('IRO_VERSION', wp_get_theme()->get('Version'));
define('BUILD_VERSION', '3');
define('INT_VERSION', '20.0.5');
define('SSU_URL', 'https://api.fuukei.org/update/ssu.json');

function check_php_version($preset_version)
{
    $current_version = phpversion();
    return version_compare($current_version, $preset_version, '>=') ? true : false;
}

//Option-Framework

require get_template_directory() . '/opt/option-framework.php';

if (!function_exists('iro_opt')) {
    $GLOBALS['iro_options'] = get_option('iro_options');
    function iro_opt($option = '', $default = null)
    {
        if ( is_customize_preview() ) {
            $theme_mod = get_theme_mod('iro_options',[]);
            if (isset( $theme_mod[$option])) {
                return $theme_mod[$option]; //预览模式优先使用预览值
            } else {
                return $GLOBALS['iro_options'][$option] ?? $default;
            }
        } else {
            return $GLOBALS['iro_options'][$option] ?? $default;
        }
    }
}
if (!function_exists('iro_opt_update')) {
    function iro_opt_update($option = '', $value = null)
    {
        $options = get_option('iro_options'); // 当数据库没有指定项时，WordPress会返回false
        if ($options) {
            $options[$option] = $value;
        } else {
            $options = array($option => $value);
        }
        update_option('iro_options', $options);
    }
}

$shared_lib_basepath = iro_opt('shared_library_basepath') ? get_template_directory_uri() : (iro_opt('lib_cdn_path', 'https://fastly.jsdelivr.net/gh/mirai-mamori/Sakurairo@') . IRO_VERSION);
$core_lib_basepath = iro_opt('core_library_basepath') ? get_template_directory_uri() : (iro_opt('lib_cdn_path', 'https://fastly.jsdelivr.net/gh/mirai-mamori/Sakurairo@') . IRO_VERSION);

// 屏蔽php日志信息
if (iro_opt('php_notice_filter') != 'inner') {

    if (iro_opt('php_notice_filter','normal') == 'normal') { //仅显示严重错误
        error_reporting(E_ALL & ~E_DEPRECATED);
        ini_set('display_errors', '1');
    }
    if (iro_opt('php_notice_filter') == 'all') { //屏蔽大部分错误
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
        ini_set('display_errors', '0');
    }
}

//Update-Checker

require 'update-checker/update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

function UpdateCheck($url, $flag = 'Sakurairo')
{
    return PucFactory::buildUpdateChecker(
        $url,
        __FILE__,
        $flag
    );
}
switch (iro_opt('iro_update_source')) {
    case 'github':
        $iroThemeUpdateChecker = UpdateCheck('https://github.com/mirai-mamori/Sakurairo', 'Sakurairo');
        break;
    case 'upyun':
        $iroThemeUpdateChecker = UpdateCheck('https://api.fuukei.org/update/jsdelivr.json');
        break;
    case 'official_building':
        $iroThemeUpdateChecker = UpdateCheck('https://api.fuukei.org/update/' . iro_opt('iro_update_channel') . '/check.json');
}

add_action('init', 'set_user_locale');
function set_user_locale() {
    if (is_user_logged_in()) {
        $user_locale = get_user_locale();
        switch_to_locale($user_locale);
    }
}

if (!function_exists('akina_setup')) {
    function akina_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on Akina, use a find and replace
         * to change 'akina' to the name of your theme in all the template files.
         */
        load_theme_textdomain('sakurairo', get_template_directory() . '/languages');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(150, 150, true);

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(
            array(
                'primary' => __('Nav Menus', 'sakurairo'), //导航菜单
            )
        );

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support(
            'html5',
            array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
            )
        );

        /*
         * Enable support for Post Formats.
         * See https://developer.wordpress.org/themes/functionality/post-formats/
         */
        add_theme_support(
            'post-formats',
            array(
                'aside',
                'image',
                'status',
            )
        );

        // 注册小工具支持
        add_theme_support('widgets');

        /**
         * 废弃过时的wp_title
         * @seealso https://make.wordpress.org/core/2015/10/20/document-title-in-4-4/
         */
        add_theme_support('title-tag');

        add_filter('pre_option_link_manager_enabled', '__return_true');

        // 优化代码
        //去除头部冗余代码
        remove_action('wp_head', 'feed_links_extra', 3);
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'index_rel_link');
        remove_action('wp_head', 'start_post_rel_link', 10);
        remove_action('wp_head', 'wp_generator');
        remove_filter('the_content', 'wptexturize'); 
        remove_action('template_redirect', 'rest_output_link_header', 11);

        function coolwp_remove_open_sans_from_wp_core()
        {
            wp_deregister_style('open-sans');
            wp_register_style('open-sans', false);
            wp_enqueue_style('open-sans', '');
        }
        add_action('init', 'coolwp_remove_open_sans_from_wp_core');

        if (!function_exists('disable_emojis')) {
            /**
             * Disable the emoji's
             * @see https://wordpress.org/plugins/disable-emojis/
             */
            function disable_emojis()
            {
                remove_action('wp_head', 'print_emoji_detection_script', 7);
                remove_action('admin_print_scripts', 'print_emoji_detection_script');
                remove_action('wp_print_styles', 'print_emoji_styles');
                remove_action('admin_print_styles', 'print_emoji_styles');
                remove_filter('the_content_feed', 'wp_staticize_emoji');
                remove_filter('comment_text_rss', 'wp_staticize_emoji');
                remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
                add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
            }
            add_action('init', 'disable_emojis');
        }
        if (!function_exists('disable_emojis_tinymce')) {
            /**
             * Filter function used to remove the tinymce emoji plugin.
             *
             * @param    array  $plugins
             * @return   array             Difference betwen the two arrays
             */
            function disable_emojis_tinymce($plugins)
            {
                if (is_array($plugins)) {
                    return array_diff($plugins, array('wpemoji'));
                } else {
                    return array();
                }
            }
        }
        // 移除菜单冗余代码
        add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
        add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
        add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
        function my_css_attributes_filter($var)
        {
            return is_array($var) ? array_intersect($var, array('current-menu-item', 'current-post-ancestor', 'current-menu-ancestor', 'current-menu-parent')) : '';
        }
    }
}
;
add_action('after_setup_theme', 'akina_setup');

function i18n_templates_name ($translated_name, $original_name) {
    $lang = get_user_locale();

    $template_names = array(
        'Friendly Links Template' => array(
            'zh_CN' => '友情链接模板',
            'zh_TW' => '友情連結模板',
            'ja'    => 'フレンドリーリンクテンプレート',
        ),
        'Bangumi Template' => array(
            'zh_CN' => '追番模板',
            'zh_TW' => '追番模板',
            'ja'    => 'バンガミテンプレート',
        ),
        'Bilibili FavList Template' => array(
            'zh_CN' => 'Bilibili 收藏模板',
            'zh_TW' => 'Bilibili 收藏模板',
            'ja'    => 'Bilibili お気に入りテンプレート',
        ),
        'Bilibili FollowVideos Template' => array(
            'zh_CN' => 'Bilibili 追剧模板',
            'zh_TW' => 'Bilibili 追劇模板',
            'ja'    => 'Bilibili フォロービデオテンプレート',
        ),
        'Steam Library Template' => array(
            'zh_CN' => 'Steam 库模板',
            'zh_TW' => 'Steam 庫模板',
            'ja'    => 'Steamライブラリテンプレート',
        ),
        'Timearchive Template' => array(
            'zh_CN' => '时光归档模板',
            'zh_TW' => '時光歸檔模板',
            'ja'    => 'タイムアーカイブテンプレート',
        ),
    );
    
    if ( isset( $template_names[ $original_name ] ) && isset( $template_names[ $original_name ][ $lang ] ) ) {
        return $template_names[ $original_name ][ $lang ];
    }
    // 英语/无翻译，返回gettext处理后的文本，防止原生翻译丢失
    return $translated_name;
}

add_filter('gettext', 'i18n_templates_name', 10, 2);

function register_shuoshuo_post_type() {
    $labels = array(
        'name'               => _x('Shuoshuo', 'post type general name', 'sakurairo'),
        'singular_name'      => _x('Shuoshuo', 'post type singular name', 'sakurairo'),
        'menu_name'          => _x('Shuoshuo', 'admin menu', 'sakurairo'),
        'name_admin_bar'     => _x('Shuoshuo', 'add new on admin bar', 'sakurairo'),
        'add_new'            => _x('Add New', 'shuoshuo', 'sakurairo'),
        'add_new_item'       => __('Add New Shuoshuo', 'sakurairo'),
        'new_item'           => __('New Shuoshuo', 'sakurairo'),
        'edit_item'          => __('Edit Shuoshuo', 'sakurairo'),
        'view_item'          => __('View Shuoshuo', 'sakurairo'),
        'all_items'          => __('All Shuoshuo', 'sakurairo'),
        'search_items'       => __('Search Shuoshuo', 'sakurairo'),
        'parent_item_colon'  => __('Parent Shuoshuo:', 'sakurairo'),
        'not_found'          => __('No shuoshuo found.', 'sakurairo'),
        'not_found_in_trash' => __('No shuoshuo found in Trash.', 'sakurairo')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'shuoshuo'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'custom-fields', 'comments'),
        'taxonomies'         => array('category') 
    );

    register_post_type('shuoshuo', $args);
}
add_action('init', 'register_shuoshuo_post_type');

function register_emotion_meta_boxes() {
    register_meta('post', 'emotion', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'auth_callback' => function() {
            return current_user_can('edit_posts');
        }
    ));
    register_meta('post', 'emotion_color', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'auth_callback' => function() {
            return current_user_can('edit_posts');
        }
    ));
}
add_action('init', 'register_emotion_meta_boxes');

function add_emotion_meta_box() {
    add_meta_box(
        'emotion_meta_box_id',
        __('Emotion Meta Box', 'sakurairo'),
        'render_emotion_meta_box',
        'shuoshuo', // 仅在shuoshuo内容类型中显示
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_emotion_meta_box');

function render_emotion_meta_box($post) {
    $emotion_value = get_post_meta($post->ID, 'emotion', true);
    $emotion_color_value = get_post_meta($post->ID, 'emotion_color', true);
    wp_nonce_field('emotion_meta_box_nonce', 'emotion_meta_box_nonce_field');
    echo '<label for="emotion">' . __('Emotion', 'sakurairo') . '</label>';
    echo '<input type="text" id="emotion" name="emotion" value="' . esc_attr($emotion_value) . '" />';
    echo '<br><br>';
    echo '<label for="emotion_color">' . __('Emotion Color', 'sakurairo') . '</label>';
    echo '<input type="text" id="emotion_color" name="emotion_color" value="' . esc_attr($emotion_color_value) . '" />';
    echo '<br><br>';
    echo '<p>' . __('For the Emotion, please fill in the Unicode value of the Fontawesome icon, and for the Emotion Color, please fill in the RGBA or hexadecimal color.', 'sakurairo') . '</p>';
}

function save_emotion_meta_box($post_id) {
    if (!isset($_POST['emotion_meta_box_nonce_field']) || !wp_verify_nonce($_POST['emotion_meta_box_nonce_field'], 'emotion_meta_box_nonce')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['emotion'])) {
        update_post_meta($post_id, 'emotion', sanitize_text_field($_POST['emotion']));
    }
    if (isset($_POST['emotion_color'])) {
        update_post_meta($post_id, 'emotion_color', sanitize_text_field($_POST['emotion_color']));
    }
}
add_action('save_post', 'save_emotion_meta_box');

function register_custom_meta_boxes() {
    register_meta('post', 'title_style', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'auth_callback' => function() {
            return current_user_can('edit_posts');
        }
    ));
    register_meta('post', 'license', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'auth_callback' => function() {
            return current_user_can('edit_posts');
        }
    ));
}
add_action('init', 'register_custom_meta_boxes');

function add_custom_meta_box() {
    add_meta_box(
        'custom_meta_box_id',
        __('Custom Meta Box', 'sakurairo'),
        'render_custom_meta_box',
        'post', // 仅在post内容类型中显示
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_custom_meta_box');

function render_custom_meta_box($post) {
    $title_style_value = get_post_meta($post->ID, 'title_style', true);
    $license_value = get_post_meta($post->ID, 'license', true);
    wp_nonce_field('custom_meta_box_nonce', 'custom_meta_box_nonce_field');
    echo '<label for="title_style">' . __('Title Style', 'sakurairo') . '</label>';
    echo '<input type="text" id="title_style" name="title_style" value="' . esc_attr($title_style_value) . '" />';
    echo '<br><br>';
    echo '<label for="license">' . __('License', 'sakurairo') . '</label>';
    echo '<input type="text" id="license" name="license" value="' . esc_attr($license_value) . '" />';
    echo '<br><br>';
    echo '<p>' . __('For the Title Style, Please fill in the css style, part of the style need to add !important effective, and for the License, please go to Theme Options to learn how to set it up.', 'sakurairo') . '</p>';
}

function save_custom_meta_box($post_id) {
    if (!isset($_POST['custom_meta_box_nonce_field']) || !wp_verify_nonce($_POST['custom_meta_box_nonce_field'], 'custom_meta_box_nonce')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['title_style'])) {
        update_post_meta($post_id, 'title_style', sanitize_text_field($_POST['title_style']));
    }
    if (isset($_POST['license'])) {
        update_post_meta($post_id, 'license', sanitize_text_field($_POST['license']));
    }
}
add_action('save_post', 'save_custom_meta_box');

//主查询逻辑，类型只能多不能少，主查询通过后模版页查询才能干扰拓展
function customize_query_functions($query) {
    //只影响前端
    if ($query->is_main_query() && !is_admin()) {
        //主页可以显示文章和说说
        if (is_home()) {
            //index引用content-thumb，其中根据设置项决定是否在主页排除说说
            $post_types = array('post','shuoshuo');
            $query->set('post_type', $post_types);
        } elseif (is_archive() || is_category() || is_author()) {
            // 保持其他页面的原有逻辑
            $query->set('post_type', array('post', 'shuoshuo'));
        }

        // 在搜索页面中排除分类页和特定类别
        if ($query->is_search) {
            $post_types = array('post', 'link','shuoshuo','page');
            $query->set('post_type', $post_types);
            $tax_query = array(
                array(
                    'taxonomy' => 'category',
                    'field'    => 'name',
                    'terms'    => get_search_query(),
                    'operator' => 'NOT IN'
                )
            );
            $query->set('tax_query', $tax_query);
        }
    }
}

add_action('pre_get_posts', 'customize_query_functions');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function akina_content_width()
{
    $GLOBALS['content_width'] = apply_filters('akina_content_width', 640);
}
add_action('after_setup_theme', 'akina_content_width', 0);

/**
 * Enqueue scripts and styles.
 */
function sakura_scripts()
{
    global $core_lib_basepath;
    global $shared_lib_basepath;

    // 预加载主要样式文件
    if(iro_opt('dev_mode',false) == false) { // 压缩并缓存主题样式
        
        function add_cache_control_header() { // 添加缓存策略
            if ( ! is_user_logged_in() ) {
                header( 'Cache-Control: public, max-age=86400, s-maxage=86400' );
            }
        }
        add_action( 'send_headers', 'add_cache_control_header' );

        $sakura_header = (iro_opt('choice_of_nav_style') == 'sakura' ? 'sakura_header' : 'iro_header');
        $wave = (iro_opt('wave_effects', 'false') == true ? 'wave' : 'no_wave');
        $content_style = (iro_opt('entry_content_style') == 'sakurairo' ? 'sakura' : 'github');
        $index = '';
        if (strpos(get_option('permalink_structure'), 'index.php') !== false) {
            $index = 'index.php';
        }
        $iro_css = $core_lib_basepath . '/css/' . $index . '?' . $sakura_header . '&' . $content_style . '&' . $wave . '&minify&' . IRO_VERSION;
        add_action('wp_head', function() use ($iro_css) {
            echo '<link rel="preload" href="' .$iro_css. '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
            echo '<link rel="stylesheet" href="' . $iro_css . '">';
        }, 9);
    } else {        wp_enqueue_style('iro-css', $core_lib_basepath . '/style.css', array(), IRO_VERSION);
        wp_enqueue_style('iro-dark', $core_lib_basepath . '/css/dark.css', array('iro-css'), IRO_VERSION);
        wp_enqueue_style('iro-responsive', $core_lib_basepath . '/css/responsive.css', array('iro-css'), IRO_VERSION);
        wp_enqueue_style('iro-animation', $core_lib_basepath . '/css/animation.css', array('iro-css'), IRO_VERSION);
        wp_enqueue_style('iro-templates', $core_lib_basepath . '/css/templates.css', array('iro-css'), IRO_VERSION);

        $content_style = (iro_opt('entry_content_style') == 'sakurairo' ? 'sakura' : 'github');
        wp_enqueue_style(
            'entry-content',
            $core_lib_basepath . '/css/content-style/' . $content_style . '.css',
            array(),
            IRO_VERSION
        );
        if (iro_opt('wave_effects', 'false')){
            wp_enqueue_style('wave', $core_lib_basepath . '/css/wave.css', array(), IRO_VERSION);
        }
        if(iro_opt('choice_of_nav_style') == 'sakura'){
            wp_enqueue_style('sakura_header', $core_lib_basepath . '/css/sakura_header.css', array(), IRO_VERSION);
        }
    }

    if(!is_404()){
        wp_enqueue_script('app', $core_lib_basepath . '/js/app.js', array('polyfills'), IRO_VERSION, true);
        if (!is_home()) {
            //非主页的资源
            wp_enqueue_script('app-page', $core_lib_basepath . '/js/page.js', array('app', 'polyfills'), IRO_VERSION, true);
        }
    }
    wp_enqueue_script('polyfills', $core_lib_basepath . '/js/polyfill.js', array(), IRO_VERSION, true);
    // defer加载
    add_filter('script_loader_tag', function($tag, $handle) {
        if ('polyfills' === $handle) {
            return str_replace('src', 'defer src', $tag);
        }
        return $tag;
    }, 10, 2);
    
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    //前端脚本本地化
    if (get_user_locale() != 'zh_CN') {
        wp_localize_script(
            'app',
            '_sakurairoi18n',
            array(
                '复制成功！' => __("Copied!", 'sakurairo'),
                '拷贝代码' => __("Copy Code", 'sakurairo'),
                '你的封面API好像不支持跨域调用,这种情况下缓存是不会生效的哦' => __("Your cover API seems to not support Cross Origin Access. In this case, Cover Cache won't take effect.", 'sakurairo'),
                '提交中....' => __('Commiting....', 'sakurairo'),
                '提交成功' => __('Succeed', 'sakurairo'),
                '每次上传上限为10张' => __('10 files max per request', 'sakurairo'),
                "图片上传大小限制为5 MB\n\n「{0}」\n\n这张图太大啦~请重新上传噢！" => __("5 MB max per file.\n\n「{0}」\n\nThis image is too large~Please reupload!", 'sakurairo'),
                '上传中...' => __('Uploading...', 'sakurairo'),
                '图片上传成功~' => __('Uploaded successfully~', 'sakurairo'),
                "上传失败！\n文件名=> {0}\ncode=> {1}\n{2}" => __("Upload failed!\nFile Name=> {0}\ncode=> {1}\n{2}", 'sakurairo'),
                '上传失败，请重试.' => __('Upload failed, please retry.', 'sakurairo'),
                '页面加载出错了 HTTP {0}' => __("Page Load failed. HTTP {0}", 'sakurairo'),
                '很高兴你翻到这里，但是真的没有了...' => __("Glad you come, but we've got nothing left.", 'sakurairo'),
                "文章" => __("Post", 'sakurairo'),
                "标签" => __("Tag", 'sakurairo'),
                "分类" => __("Category", 'sakurairo'),
                "页面" => __("Page", 'sakurairo'),
                "评论" => __("Comment", 'sakurairo'),
                "已暂停..." => __("Paused...", 'sakurairo'),
                "正在载入视频 ..." => __("Loading Video...", 'sakurairo'),
                "将从网络加载字体，流量请注意" => __("Downloading fonts, be aware of your data usage.", 'sakurairo'),
                "您真的要设为私密吗？" => __("Are you sure you want set it private?", 'sakurairo'),
                "您之前已设过私密评论" => __("You had set private comment before", 'sakurairo')
            )
        );
    }
    
    // 平滑滚动脚本优化为延迟加载
    if (iro_opt('smoothscroll_option')) {
        wp_enqueue_script('SmoothScroll', $shared_lib_basepath . '/js/smoothscroll.js', array(), IRO_VERSION . iro_opt('cookie_version', ''), true);
    }
}
add_action('wp_enqueue_scripts', 'sakura_scripts');

/**
 * load .php.
 */
require get_template_directory() . '/inc/decorate.php';
require get_template_directory() . '/inc/swicher.php';
require get_template_directory() . '/inc/api.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * 初始化Bilibili收藏夹缓存定时任务
 */
add_action('init', function() {
    if (class_exists('Sakura\API\BilibiliFavListCron')) {
        Sakura\API\BilibiliFavListCron::init();
    }
});

// 加载缓存设置页

require get_template_directory() . '/inc/cache_settings.php';

/**
 * Customizer功能
 * 仅在Customizer预览框架中和Customizer编辑器载入时加载
 */
add_action( 'customize_register', function () {
    require_once get_template_directory() . '/inc/customizer.php';
} );
if ( is_customize_preview() ) {
    require_once get_template_directory() . '/inc/customizer.php';
}
function update_customize_to_iro_options() { //从key映射表中重组并保存设置项至iro_options中
    $theme_mod_options = get_theme_mod( 'iro_options', [] );
    $mapping = get_theme_mod( 'iro_options_map', [] );
	$iro_options = get_option('iro_options');
    
    foreach ( $mapping as $setting_id => $map ) {
        $preview_value = get_theme_mod( $setting_id, null );
        if ( null !== $preview_value ) {
            $iro_key = isset( $map['iro_key'] ) ? $map['iro_key'] : $setting_id;
            $iro_subkey = isset( $map['iro_subkey'] ) ? $map['iro_subkey'] : '';
            if ( $iro_subkey ) {
                if ( ! isset( $theme_mod_options[ $iro_key ] ) || ! is_array( $theme_mod_options[ $iro_key ] ) ) {
                    $theme_mod_options[ $iro_key ] = [];
                }
                $theme_mod_options[ $iro_key ][ $iro_subkey ] = $preview_value;
            } else {
                $theme_mod_options[ $iro_key ] = $preview_value;
            }
            // 移除已保存的值，确保下次还能同步
            remove_theme_mod( $setting_id );
        }
    }
	$theme_mod_options = array_merge($iro_options,$theme_mod_options);
    update_option( 'iro_options', $theme_mod_options );
}
add_action( 'customize_save_after', 'update_customize_to_iro_options' );

/**
 * function update
 */
require get_template_directory() . '/inc/theme-plus.php';
require get_template_directory() . '/inc/categories-images.php';

if (!function_exists('akina_comment_format')) {
    function akina_comment_format($comment, $args, $depth)
    {
        $GLOBALS['comment'] = $comment;
        ?>
        <li <?php comment_class(); ?> id="comment-<?php echo esc_attr(comment_ID()); ?>">
            <div class="contents">
                <div class="comment-arrow">
                    <div class="main shadow">
                        <div class="profile">
                            <a href="<?php comment_author_url(); ?>" target="_blank" rel="nofollow"><?php echo str_replace('src=', 'src="' . iro_opt('load_in_svg') . '" onerror="imgError(this,1)" data-src=', get_avatar($comment->comment_author_email, 80, '', get_comment_author(), array('class' => array('lazyload')))); ?></a>
                        </div>
                        <div class="commentinfo">
                            <section class="commeta">
                                <div class="left">
                                    <h4 class="author">
                                        <a href="<?php comment_author_url(); ?>" target="_blank" rel="nofollow"><?php echo get_avatar($comment->comment_author_email, 24, '', get_comment_author()); ?>
                                            <span class="bb-comment isauthor" title="<?php _e('Author', 'sakurairo'); ?>"><?php _e('Blogger', 'sakurairo'); /*博主*/?></span>
                                            <?php comment_author(); ?><?php echo get_author_class($comment->comment_author_email, $comment->user_id); ?>
                                        </a>
                                    </h4>
                                </div>
                                <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                                <div class="right">
                                    <div class="info"><time datetime="<?php comment_date('Y-m-d'); ?>"><?php echo poi_time_since(strtotime($comment->comment_date), true); //comment_date(get_option('date_format'));  
                                                ?></time><?= siren_get_useragent($comment->comment_agent); ?><?php echo mobile_get_useragent_icon($comment->comment_agent); ?>&nbsp;<?php if (iro_opt('comment_location')) {
                                                        _e('Location', 'sakurairo'); /*来自*/?>: <?php echo \Sakura\API\IpLocationParse::getIpLocationByCommentId($comment->comment_ID);
                                                    } ?>
                                    <?php if (current_user_can('manage_options') and (wp_is_mobile() == false)) {
                                        $comment_ID = $comment->comment_ID;
                                        $i_private = get_comment_meta($comment_ID, '_private', true);
                                        $flag = null;
                                        $flag .= ' <i class="fa-regular fa-snowflake"></i> <a href="javascript:;" data-actionp="set_private" data-idp="' . get_comment_id() . '" id="sp" class="sm">' . __("Private", "sakurairo") . ': <span class="has_set_private">';
                                        if (!empty($i_private)) {
                                            $flag .= __("Yes", "sakurairo") . ' <i class="fa-solid fa-lock"></i>';
                                        } else {
                                            $flag .= __("No", "sakurairo") . ' <i class="fa-solid fa-lock-open"></i>';
                                        }
                                        $flag .= '</span></a>';
                                        $flag .= edit_comment_link('<i class="fa-solid fa-pen-to-square"></i> ' . __("Edit", "mashiro"), ' <span style="color:rgba(0,0,0,.35)">', '</span>');
                                        echo $flag;
                                    } ?></div>
                                </div>
                            </section>
                        </div>
                        <div class="body">
                            <?php comment_text(); ?>
                        </div>
                    </div>
                    <div class="arrow-left"></div>
                </div>
            </div>
            <hr>
        <?php
    }
}

/**
 * 获取访客VIP样式
 */
function get_author_class($comment_author_email, $user_id)
{
    global $wpdb;
    $author_count = count(
        $wpdb->get_results(
            "SELECT comment_ID as author_count FROM $wpdb->comments WHERE comment_author_email = '$comment_author_email' "
        )
    );
    # 等级梯度
    $lv_array = [0, 5, 10, 20, 40, 80, 160];
    $Lv = 0;
    foreach ($lv_array as $key => $value) {
        if ($value >= $author_count)
            break;
        $Lv = $key;
        if (user_can($user_id, 'administrator')) {
            $Lv = 6;
        }
    }

    // $Lv = $author_count < 5 ? 0 : ($author_count < 10 ? 1 : ($author_count < 20 ? 2 : ($author_count < 40 ? 3 : ($author_count < 80 ? 4 : ($author_count < 160 ? 5 : 6)))));
    echo "<span class=\"showGrade{$Lv}\" title=\"Lv{$Lv}\"><img alt=\"level_img\" src=\"" . iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@3.0/') . "comment_level/level_{$Lv}.svg\" style=\"height: 1.5em; max-height: 1.5em; display: inline-block;\"></span>";
}

/**
 * post views
 */
function restyle_text($input)
{
    // 类型修复
    if (is_numeric($input)) {
        $number = (float)$input;
    } elseif (is_string($input)) {
        if (preg_match('/[-+]?[0-9]*\.?[0-9]+/', $input, $matches)) {
            $number = (float)$matches[0];
        } else {
            $number = 0;
        }
    } else {
        $number = 0;
    }

    switch (iro_opt('statistics_format')) {
        case "type_2": //23,333 次访问
            return number_format($number);
        case "type_3": //23 333 次访问
            return number_format($number, 0, '.', ' ');
        case "type_4": //23k 次访问
            if ($number >= 1000) {
                return round($number / 1000, 2) . 'k';
            }
            return $number;
        default:
            return $number;
    }
}

function set_post_views()
{
    if (!is_singular())
        return;

    global $post;
    $post_id = intval($post->ID);
    if (!$post_id)
        return;
    $views = (int) get_post_meta($post_id, 'views', true);
    if (!update_post_meta($post_id, 'views', ($views + 1))) {
        add_post_meta($post_id, 'views', 1, true);
    }
}

add_action('get_header', 'set_post_views');

function get_post_views($post_id)
{
    // 检查传入的参数是否有效
    if (empty($post_id) || !is_numeric($post_id)) {
        return 'Error: Invalid post ID.';
    }
    // 检查 WP-Statistics 插件是否安装
    if ((function_exists('wp_statistics_pages')) && (iro_opt('statistics_api') == 'wp_statistics')) {
        // 使用 WP-Statistics 插件获取浏览量
        $views = wp_statistics_pages('total', 'uri', $post_id);
        return empty($views) ? 0 : $views;
    } else {
        // 使用文章自定义字段获取浏览量
        $views = get_post_meta($post_id, 'views', true);
        // 格式化浏览量
        $views = restyle_text($views);
        return empty($views) ? 0 : $views;
    }
}

// 引入post_metas方法
require_once get_template_directory() . "/inc/post_metas.php";

function is_webp(): bool
{
    return (isset($_COOKIE['su_webp']) || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp')));
}

/**
 * 获取友情链接列表
 * @Param: string $sorting_mode 友情链接列表排序模式，name、updated、rating、rand四种模式
 * @Param: string $link_order 友情链接列表排序方法，ASC、DESC（升序或降序）
 * @Param: mixed $id 友情链接ID
 * @Param: string $output HTML格式化输出
 */
function get_the_link_items($id = null)
{
    $sorting_mode = iro_opt('friend_link_sorting_mode');
    $link_order = iro_opt('friend_link_order');
    $bookmarks = get_bookmarks(
        array(
            'orderby' => $sorting_mode,
            'order' => $link_order,
            'category' => $id
        )
    );
    $output = '';
    if (!empty($bookmarks)) {
        $output .= '<ul class="link-items fontSmooth">';
        foreach ($bookmarks as $bookmark) {
            if (empty($bookmark->link_description)) {
                $bookmark->link_description = __('This guy is so lazy ╮(╯▽╰)╭', 'sakurairo');
            }

            if (empty($bookmark->link_image)) {
                $bookmark->link_image = 'https://weavatar.com/avatar/?s=80&d=mm&r=g';
            }
            
            // 获取链接状态
            $link_status = get_post_meta($bookmark->link_id, '_link_check_status', true);
            $status_class = '';
            if ($link_status === 'success') {
                $status_class = 'link-status-success';
            } elseif ($link_status === 'failure') {
                $status_class = 'link-status-failure';
            }

            $output .= '<li class="link-item ' . $status_class . '"><a class="link-item-inner effect-apollo" href="' . $bookmark->link_url . '" title="' . $bookmark->link_description . '" target="_blank" rel="friend"><div class="link-avatar-wrapper"><img alt="friend_avator" class="lazyload" onerror="imgError(this,1)" data-src="' . $bookmark->link_image . '" src="' . iro_opt('load_in_svg') . '"></div><span class="sitename" style="' . $bookmark->link_notes . '">' . $bookmark->link_name . '</span><div class="linkdes">' . $bookmark->link_description . '</div></a></li>';
        }
        $output .= '</ul>';
    }
    return $output;
}

function get_link_items() {
    // 获取链接分类并按优先级降序排列
    $linkcats = get_terms(array(
        'taxonomy'   => 'link_category',
        'meta_key'   => 'term_priority', // 优先级字段
        'orderby'    => 'meta_value_num', 
        'order'      => 'DESC', 
        'hide_empty' => false
    ));

    $result = null;
    if (empty($linkcats)) {
        return get_the_link_items(); // 友链无分类，直接返回全部列表  
    }
    
    $pending_cat_name = __('Pending Links', 'sakurairo'); // 未审核链接分类名称
    
    foreach ($linkcats as $linkcat) {
        // 跳过未审核链接分类
        if ($linkcat->name === $pending_cat_name) {
            continue;
        }
        
        $result .= '<h3 class="link-title"><span class="link-fix">' . $linkcat->name . '</span></h3>';
        if ($linkcat->description) {
            $result .= '<div class="link-description">' . $linkcat->description . '</div>';
        }

        $result .= get_the_link_items($linkcat->term_id);
    }
    return $result;
}

/*
 * Gravatar头像使用中国服务器
 */
function gravatar_cn(string $url): string
{
    $gravatar_url = array('0.gravatar.com/avatar', '1.gravatar.com/avatar', '2.gravatar.com/avatar', 'secure.gravatar.com/avatar');
    if (iro_opt('gravatar_proxy') == 'custom_proxy_address_of_gravatar') {
        return str_replace($gravatar_url, iro_opt('custom_proxy_address_of_gravatar'), $url);
    } else {
        return str_replace($gravatar_url, iro_opt('gravatar_proxy'), $url);
    }
}
if (iro_opt('gravatar_proxy')) {
    add_filter('get_avatar_url', 'gravatar_cn', 4);
}

/*
 * 检查主题版本号，并在更新主题后执行设置选项值的更新
 */
function visual_resource_updates($specified_version, $option_name, $new_value)
{
    $theme = wp_get_theme();
    $current_version = $theme->get('Version');

    // Check if the function has already been triggered
    $function_triggered = get_transient('visual_resource_updates_triggered20');
    if ($function_triggered) {
        return; // Function has already been triggered, do nothing
    }

    if (version_compare($current_version, $specified_version, '>')) {
        $option_value = iro_opt($option_name);
        if (empty($option_value)) {
            $option_value = "https://s.nmxc.ltd/sakurairo_vision/@3.0/";
        } else if (strpos($option_value, '@') === false || substr($option_value, strpos($option_value, '@') + 1) !== $new_value) {
            $option_value = preg_replace('/@.*/', '@' . $new_value, $option_value);
        }
        iro_opt_update($option_name, $option_value);

        // Set transient to indicate that the function has been triggered
        set_transient('visual_resource_updates_triggered20', true);
    }
}

visual_resource_updates('2.5.6', 'vision_resource_basepath', '3.0/');

function unlisted_avatar_updates() {
    $theme = wp_get_theme();
    $current_version = $theme->get('Version');

    // Check if the function has already been triggered
    $function_triggered = get_transient('unlisted_avatar_updates_triggered20');
    if ($function_triggered) {
        return; // Function has already been triggered, do nothing
    }

    if (version_compare($current_version, '2.5.6', '>')) {
        $option_value = iro_opt('unlisted_avatar');
        $old_values = array(
            'https://s.nmxc.ltd/sakurairo_vision/@2.7/basic/topavatar.png',
            'https://s.nmxc.ltd/sakurairo_vision/@2.6/basic/topavatar.png',  
            'https://s.nmxc.ltd/sakurairo_vision/@2.5/basic/topavatar.png'
        );
        
        if (in_array($option_value, $old_values)) {
            iro_opt_update('unlisted_avatar', '');
        }

        // Set transient to indicate that the function has been triggered
        set_transient('unlisted_avatar_updates_triggered20', true);
    }
}

unlisted_avatar_updates();

/*
 * 阻止站内文章互相Pingback
 */
function theme_noself_ping(&$links)
{
    $home = get_option('home');
    foreach ($links as $l => $link) {
        if (0 === strpos($link, $home)) {
            unset($links[$l]);
        }
    }
}
add_action('pre_ping', 'theme_noself_ping');

/*
 * 订制body类
 */
function akina_body_classes($classes)
{
    // Adds a class of group-blog to blogs with more than 1 published author.
    if (is_multi_author()) {
        $classes[] = 'group-blog';
    }
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }
    // 定制中文字体class
    $classes[] = 'chinese-font';
    /*if(!wp_is_mobile()) {
    $classes[] = 'serif';
    }*/
    if (isset($_COOKIE['dark' . iro_opt('cookie_version', '')])) {
        $classes[] = $_COOKIE['dark' . iro_opt('cookie_version', '')] == '1' ? 'dark' : ' ';
    } else {
        $classes[] = ' ';
    }
    return $classes;
}
add_filter('body_class', 'akina_body_classes');

/*
 * 图片CDN
 */
add_filter('upload_dir', 'wpjam_custom_upload_dir');
function wpjam_custom_upload_dir($uploads)
{
    /*     $upload_path = '';
     */$upload_url_path = iro_opt('image_cdn');

    $uploads['path'] = $uploads['basedir'] . $uploads['subdir'];

    if ($upload_url_path) {
        $uploads['baseurl'] = $upload_url_path;
        $uploads['url'] = $uploads['baseurl'] . $uploads['subdir'];
    }
    return $uploads;
}

/*
 * 删除自带小工具
 */
function unregister_default_widgets()
{
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Nav_Menu_Widget');
}
add_action("widgets_init", "unregister_default_widgets", 11);

/**
 * Jetpack setup function.
 *
 * See: https://jetpack.com/support/infinite-scroll/
 * See: https://jetpack.com/support/responsive-videos/
 */
function akina_jetpack_setup()
{
    // Add theme support for Infinite Scroll.
    add_theme_support(
        'infinite-scroll',
        array(
            'container' => 'main',
            'render' => 'akina_infinite_scroll_render',
            'footer' => 'page',
        )
    );

    // Add theme support for Responsive Videos.
    add_theme_support('jetpack-responsive-videos');
}
add_action('after_setup_theme', 'akina_jetpack_setup');

/**
 * Custom render function for Infinite Scroll.
 */
function akina_infinite_scroll_render()
{
    while (have_posts()) {
        the_post();
        get_template_part('tpl/content', is_search() ? 'search' : get_post_format());
    }
}

/*
 * 编辑器增强
 */
function enable_more_buttons($buttons)
{
    $buttons[] = 'hr';
    $buttons[] = 'del';
    $buttons[] = 'sub';
    $buttons[] = 'sup';
    $buttons[] = 'fontselect';
    $buttons[] = 'fontsizeselect';
    $buttons[] = 'cleanup';
    $buttons[] = 'styleselect';
    $buttons[] = 'wp_page';
    $buttons[] = 'anchor';
    $buttons[] = 'backcolor';
    return $buttons;
}
add_filter("mce_buttons_3", "enable_more_buttons");

/*
 * 后台登录页
 */
$custom_login_switch = iro_opt('custom_login_switch');
if ($custom_login_switch) {
    // Add custom login styles
    function custom_login() {
        ?>
        <style type="text/css">body.login{background-image:url('<?php echo DEFAULT_FEATURE_IMAGE(); ?>');background-size:cover;background-position:center;background-repeat:no-repeat;background-attachment:fixed;}.login h1 a{background-image:url('<?php echo iro_opt('login_logo_img') ?: get_site_icon_url(); ?>') !important;background-size:contain;width:100%;max-height:100px;}.login form{box-shadow:0 1px 30px -4px #e8e8e880;border:1px solid #FFFFFF;background:rgba(255,255,255,0.8);-webkit-backdrop-filter:saturate(180%) blur(10px);backdrop-filter:saturate(180%) blur(10px);border-radius:10px;}.login form input[type=checkbox],.login input[type=password],.login input[type=text],.login input[type=email]{background:rgba(255,255,255,0.7);box-shadow:0 1px 30px -4px #e8e8e880;border:1px solid #FFFFFF;-webkit-backdrop-filter:saturate(180%) blur(10px);backdrop-filter:saturate(180%) blur(10px);font-size:15px;padding:0.6rem;border-radius:8px;}.wp-core-ui .button-primary,#wp-webauthn{background:<?php echo iro_opt('theme_skin') ?: '#FF69B4'; ?>;border-color:transparent;border-radius:6px;padding:1px 18px !important;transition:all 0.3s ease;}.wp-core-ui .button-primary:hover,#wp-webauthn:hover{background:<?php echo iro_opt('theme_skin_matching') ?: '#FF69B4'; ?>;border-color:transparent;transition:all 0.3s ease;}.vaptchaContainer{margin:5px 0 20px;}.login form .forgetmenot{margin-top: 6px;}.login .button.wp-hide-pw .dashicons{color:<?php echo iro_opt('theme_skin') ?: '#FF69B4'; ?>;}#language-switcher{color:<?php echo iro_opt('theme_skin') ?: '#FF69B4'; ?>;backdrop-filter:none;-webkit-backdrop-filter:none;}.login #nav{font-size:12px;padding:8px 12px;background:rgba(255,255,255,0.7);box-shadow:0 1px 30px -4px #e8e8e8;border:1px solid #FFFFFF;-webkit-backdrop-filter:saturate(180%) blur(10px);backdrop-filter:saturate(180%) blur(10px);width:fit-content;border-radius:8px;margin:auto;margin-top:-13%;}.login #backtoblog{display:none;}.captcha{display:flex !important;align-items:center;margin-bottom:20px !important;margin-top:10px;gap:10px;}.login form input[name=yzm]{margin:0;}.login label{margin-bottom:5px;}.wp-webauthn-notice{height: 40px !important;margin-bottom: 15px;}#wp-webauthn span{color:#fff;}.vp-dark-btn.vp-basic-btn{border-radius: 8px !important;}</style>
        <?php
    }
    add_action('login_head', 'custom_login');

    // Login Page Title
    function custom_headertitle($title) {
        return get_bloginfo('name');
    }
    add_filter('login_headertext', 'custom_headertitle');

    // Login Page Link
    function custom_loginlogo_url($url) {
        return esc_url(home_url('/'));
    }
    add_filter('login_headerurl', 'custom_loginlogo_url');
}

//Login message
//* Add custom message to WordPress login page
function smallenvelop_login_message($message)
{
    return empty($message) ? '<p class="message"><strong>You may try 3 times for every 5 minutes!</strong></p>' : $message;
}

//Fix password reset bug </>
function resetpassword_message_fix($message)
{
    return str_replace(['>', '<'], '', $message);
}
add_filter('retrieve_password_message', 'resetpassword_message_fix');

//Fix register email bug </>
function new_user_message_fix($message)
{
    $show_register_ip = '注册IP | Registration IP: ' . get_the_user_ip() . ' (' . \Sakura\API\IpLocationParse::getIpLocationByIp(get_the_user_ip()) . ")\r\n\r\n如非本人操作请忽略此邮件 | Please ignore this email if this was not your operation.\r\n\r\n";
    $message = str_replace('To set your password, visit the following address:', $show_register_ip . '在此设置密码 | To set your password, visit the following address:', $message);
    $message = str_replace('<', '', $message);
    $message = str_replace('>', "\r\n\r\n设置密码后在此登录 | Login here after setting password: ", $message);
    return $message;
}
add_filter('wp_new_user_notification_email', 'new_user_message_fix');

/*
 * 评论邮件回复
 */
function comment_mail_notify($comment_id)
{
    $mail_user_name = iro_opt('mail_user_name') ? iro_opt('mail_user_name') : 'poi';
    $comment = get_comment($comment_id);
    $parent_id = $comment->comment_parent ?: '';
    $spam_confirmed = $comment->comment_approved;
    $mail_notify = iro_opt('mail_notify') ? get_comment_meta($parent_id, 'mail_notify', false) : false;
    $admin_notify = iro_opt('admin_notify') ? '1' : ((isset(get_comment($parent_id)->comment_author_email) && get_comment($parent_id)->comment_author_email) != get_bloginfo('admin_email') ? '1' : '0');
    
    if (($parent_id != '') && ($spam_confirmed != 'spam') && ($admin_notify != '0') && (!$mail_notify)) {
        $wp_email = $mail_user_name . '@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
        $to = trim(get_comment($parent_id)->comment_author_email);
        
        // 主题主色调
        $theme_color = iro_opt('theme_skin_matching') ?: '#FE9600';
        
        // 获取用户语言环境
        $comment_author_locale = get_comment_meta($parent_id, 'comment_author_locale', true);
        $locale = $comment_author_locale ?: get_locale();
        
        // 多语言支持
        switch ($locale) {
            case 'zh_TW':
                $subject = '你在 [' . get_option("blogname") . '] 的留言有了回應';
                $notification_title = '評論回覆通知';
                $dear = '親愛的';
                $new_reply = '您有一條來自';
                $new_reply_2 = '的回覆';
                $your_comment = '您在文章《';
                $your_comment_2 = '》上發表的評論：';
                $reply_to_you = '給您的回覆：';
                $view_complete = '查看完整對話';
                $auto_notify = '此郵件由系統自動發送，請勿直接回覆';
                break;
            case 'ja':
            case 'ja_JP':
                $subject = '[' . get_option("blogname") . '] のコメントに返信がありました';
                $notification_title = 'コメント返信通知';
                $dear = '尊敬する';
                $new_reply = 'からの新しい返信があります';
                $new_reply_2 = '';
                $your_comment = '記事「';
                $your_comment_2 = '」へのあなたのコメント：';
                $reply_to_you = 'さんからの返信：';
                $view_complete = '完全な会話を見る';
                $auto_notify = 'このメールはシステムによって自動的に送信されたものです。直接返信しないでください';
                break;
            case 'en_US':
            case 'en_GB':
                $subject = 'New Reply to Your Comment on [' . get_option("blogname") . ']';
                $notification_title = 'Comment Reply Notification';
                $dear = 'Dear';
                $new_reply = 'You have a new reply from';
                $new_reply_2 = '';
                $your_comment = 'Your comment on the article "';
                $your_comment_2 = '":';
                $reply_to_you = '\'s reply to you:';
                $view_complete = 'View Complete Conversation';
                $auto_notify = 'This email was automatically sent by the system, please do not reply directly';
                break;
            default: // 默认中文
                $subject = '你在 [' . get_option("blogname") . '] 的留言有了回应';
                $notification_title = '评论回复通知';
                $dear = '尊敬的';
                $new_reply = '您有一条来自';
                $new_reply_2 = '的回复';
                $your_comment = '您在文章《';
                $your_comment_2 = '》上发表的评论：';
                $reply_to_you = '给您的回复：';
                $view_complete = '查看完整对话';
                $auto_notify = '此邮件由系统自动发送，请勿直接回复';
                break;
        }
        
        // 现代化邮件模板
        $message = '
        <!DOCTYPE html>
        <html lang="' . str_replace('_', '-', $locale) . '">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . $notification_title . '</title>
        </head>
        <body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Arial, sans-serif; color: #333; background-color: #f5f5f5;">
            <div style="max-width: 600px; margin: 20px auto; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
                <!-- 头部 -->
                <div style="background-color: ' . $theme_color . '; padding: 20px; text-align: center;">
                    <h1 style="color: #fff; margin: 0; font-size: 22px;">' . $notification_title . '</h1>
                </div>
                
                <!-- 内容区 -->
                <div style="padding: 20px;">
                    <p style="font-size: 16px; margin-top: 0;">' . $dear . ' <strong>' . trim(get_comment($parent_id)->comment_author) . '</strong>：</p>
                    <p style="font-size: 16px;">' . $new_reply . ' <a href="' . home_url() . '" style="color: ' . $theme_color . '; text-decoration: none; font-weight: bold;">' . get_option("blogname") . '</a> ' . $new_reply_2 . '</p>
                    
                    <!-- 您的评论 -->
                    <p style="font-size: 14px;">' . $your_comment . '<a href="' . get_permalink($comment->comment_post_ID) . '" style="color: ' . $theme_color . '; text-decoration: none; font-weight: bold;">' . get_the_title($comment->comment_post_ID) . '</a>' . $your_comment_2 . '</p>
                    <div style="margin: 15px 0; padding: 15px; background-color: #f9f9f9; border-left: 4px solid #ddd; border-radius: 4px;">
                        <p style="margin: 0; font-size: 15px; color: #666;">' . trim(get_comment($parent_id)->comment_content) . '</p>
                    </div>
                    
                    <!-- 回复评论 -->
                    <p style="font-size: 14px;"><strong style="color: ' . $theme_color . ';">' . trim($comment->comment_author) . '</strong>' . $reply_to_you . '</p>
                    <div style="margin: 15px 0; padding: 15px; background-color: #f0f8ff; border-left: 4px solid ' . $theme_color . '; border-radius: 4px;">
                        <p style="margin: 0; font-size: 15px;">' . trim($comment->comment_content) . '</p>
                    </div>
                    
                    <!-- 查看回复按钮 -->
                    <div style="text-align: center; margin: 25px 0 15px;">
                        <a href="' . htmlspecialchars(get_comment_link($parent_id)) . '" style="background-color: ' . $theme_color . '; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; display: inline-block;">' . $view_complete . '</a>
                    </div>
                </div>
                
                <!-- 页脚 -->
                <div style="background-color: #f5f5f5; padding: 15px; text-align: center; font-size: 13px; color: #999;">
                    <p style="margin: 5px 0;">' . $auto_notify . '</p>
                    <p style="margin: 5px 0;">&copy; ' . date('Y') . ' <a href="' . home_url() . '" style="color: ' . $theme_color . '; text-decoration: none;">' . get_option("blogname") . '</a></p>
                </div>
            </div>
        </body>
        </html>';
        
        // 处理表情符号和特殊格式
        $message = convert_smilies($message);
        $message = str_replace('{{', '<img src="' . iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@3.0/') . '/smilies/bilipng/emoji_', $message);
        $message = str_replace('}}', '.png" alt="emoji" style="height: 1.5em; max-height: 1.5em; vertical-align: middle;">', $message);
        
        // 处理图片
        $message = str_replace('{UPLOAD}', 'https://i.loli.net/', $message);
        $message = str_replace('[/img][img]', '[/img^img]', $message);
        $message = str_replace('[img]', '<img src="', $message);
        $message = str_replace('[/img]', '" style="max-width: 100%; height: auto; margin: 10px 0; border-radius: 4px;">', $message);
        $message = str_replace('[/img^img]', '" style="max-width: 100%; height: auto; margin: 10px 0; border-radius: 4px;"><img src="', $message);
        
        $from = 'From: "' . get_option('blogname') . "\" <$wp_email>";
        $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
        wp_mail($to, $subject, $message, $headers);
    }
}
add_action('comment_post', 'comment_mail_notify');

/*
 * 链接新窗口打开
 */
function rt_add_link_target($content)
{
    $content = str_replace('<a', '<a rel="nofollow"', $content);
    // use the <a> tag to split into segments
    $bits = explode('<a ', $content);
    // loop though the segments
    foreach ($bits as $key => $bit) {
        // fix the target="_blank" bug after the link
        if (strpos($bit, 'href') === false) {
            continue;
        }

        // fix the target="_blank" bug in the codeblock
        if (strpos(preg_replace('/code([\s\S]*?)\/code[\s]*/m', 'temp', $content), $bit) === false) {
            continue;
        }

        // find the end of each link
        $pos = strpos($bit, '>');
        // check if there is an end (only fails with malformed markup)
        if ($pos !== false) {
            // get a string with just the link's attibutes
            $part = substr($bit, 0, $pos);
            // for comparison, get the current site/network url
            $siteurl = network_site_url();
            // if the site url is in the attributes, assume it's in the href and skip, also if a target is present
            if (strpos($part, $siteurl) === false && strpos($part, 'target=') === false) {
                // add the target attribute
                $bits[$key] = 'target="_blank" ' . $bits[$key];
            }
        }
    }
    // re-assemble the content, and return it
    return implode('<a ', $bits);
}
add_filter('comment_text', 'rt_add_link_target');

// 评论通过BBCode插入图片
function comment_picture_support($content)
{
    $content = str_replace('http://', 'https://', $content); // 干掉任何可能的 http
    $content = str_replace('{UPLOAD}', 'https://i.loli.net/', $content);
    $content = str_replace('[/img][img]', '[/img^img]', $content);
    $content = str_replace('[img]', '<br><img src="' . iro_opt('load_in_svg') . '" data-src="', $content);
    $content = str_replace('[/img]', '" class="lazyload comment_inline_img" onerror="imgError(this)"><br>', $content);
    $content = str_replace('[/img^img]', '" class="lazyload comment_inline_img" onerror="imgError(this)"><img src="' . iro_opt('load_in_svg') . '" data-src="', $content);
    return $content;
}
add_filter('comment_text', 'comment_picture_support');

/*
 * 修改评论表情调用路径
 */

// 简单遍历系统表情库，今后应考虑标识表情包名——使用增加的扩展名，同时保留原有拓展名
// 还有一个思路是根据表情调用路径来判定<-- 此法最好！
// 贴吧

function make_onclick_grin($name,$type,$before='',$after=''){
    $extra_params = "";
    if($before || $after){
        $extra_params = ",'$before','$after'";
    }
    return "onclick=\"grin('$name','$type'$extra_params)\"";
}
/**
 * 通过文件夹获取自定义表情列表，使用Transients来存储获得的列表，除非手动清除，数据永不过期。
 * 数据格式如下：
 * Array
 * (
 *     [0] => Array
 *         (
 *             [path] => C:\xampp\htdocs\wordpress/wp-content/uploads/sakurairo_vision/@2.4/smilies\bilipng\emoji_2233_chijing.png
 *             [little_path] => /sakurairo_vision/@2.4/smilies\bilipng\emoji_2233_chijing.png
 *             [file_url] => http://192.168.233.174/wordpress/wp-content/uploads/sakurairo_vision/@2.4/smilies\bilipng\emoji_2233_chijing.png
 *             [name] => emoji_2233_chijing.png
 *             [base_name] => emoji_2233_chijing
 *             [extension] => png
 *         )
 *     ...
 * ）    
 *
 * @return array
 */
function get_custom_smilies_list()
{

    $custom_smilies_list = get_transient("custom_smilies_list");

    if ($custom_smilies_list !== false) {
        return $custom_smilies_list;
    }

    $custom_smilies_list = array();
    $custom_smilies_dir = iro_opt('smilies_dir');

    if (!$custom_smilies_dir) {
        return $custom_smilies_list;
    }

    $custom_smilies_extension = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'avif', 'webp'];
    $custom_smilies_path = wp_get_upload_dir()['basedir'] . $custom_smilies_dir;

    if (!is_dir($custom_smilies_path)) {
        return $custom_smilies_list;
    }

    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($custom_smilies_path), RecursiveIteratorIterator::LEAVES_ONLY);
    foreach ($files as $file) {
        if ($file->isFile()) {
            $file_name = $file->getFilename();
            $file_base_name = pathinfo($file_name, PATHINFO_FILENAME);
            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $file_path = $file->getPathname();
            $file_little_path = str_replace(wp_get_upload_dir()['basedir'], '', $file_path);
            $file_url = wp_get_upload_dir()['baseurl'] . $file_little_path;
            if (in_array($file_extension, $custom_smilies_extension)) {
                $custom_smilies_list[] = array(
                    'path' => $file_path,
                    'little_path' => $file_little_path,
                    'file_url' => $file_url,
                    'name' => $file_name,
                    'base_name' => $file_base_name,
                    'extension' => $file_extension
                );
            }
        }
    }
    set_transient("custom_smilies_list", $custom_smilies_list);

    return $custom_smilies_list;
}

/**
 * 通过 GET 方法更新自定义表情包列表
 */
function update_custom_smilies_list()
{

    if (!is_admin() || !current_user_can('manage_options')) {
        return;
    }

    if (!isset($_GET['update_custom_smilies'])) {
        return;
    }

    $transient_name = sanitize_key($_GET['update_custom_smilies']);

    if ($transient_name === 'true') {
        delete_transient("custom_smilies_list");
        $custom_smilies_list = get_custom_smilies_list();
        $much = count($custom_smilies_list);
        $custom_smilies_dir = iro_opt('smilies_dir');
        $custom_smilies_path = wp_get_upload_dir()['basedir'] . $custom_smilies_dir;
        echo '自定义表情列表更新完成！总共有' . $much . '个表情。<br>';
        echo 'Custom smilies updated!Total' . $much . '.';
        echo "<pre>调试信息：
        - 表情目录设置为: $custom_smilies_dir
        - 实际读取的路径为: $custom_smilies_path
        Debug info:
        - Smilies path set is: $custom_smilies_dir
        - The directory actually read is: $custom_smilies_path
        </pre>
        <p>以下图片已被收录至自定义表情中（The following images have been included in the custom emoticons）：</p>";
    }
    if (!empty($custom_smilies_list)) {
            echo '<ul style="list-style: none; padding: 0; max-width: 600px;">';
            foreach ($custom_smilies_list as $smiley) {
                echo '<li style="margin-bottom: 10px; display: flex; align-items: center;">';
                echo '<img src="' . esc_url($smiley['file_url']) . '" alt="' . esc_attr($smiley['base_name']) . '" style="height: 60px; margin-right: 10px;">';
                echo '<span>' . esc_html($smiley['base_name']) . '</span>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>没有任何图片被加入表情包中（No emoticons found）。</p>';
        }
}
update_custom_smilies_list();


$custom_smiliestrans = array();
function push_custom_smilies()
{

    global $custom_smiliestrans;
    $custom_smilies_panel = '';
    $custom_smilies_list = get_custom_smilies_list();

    if (!$custom_smilies_list) {
        $custom_smilies_panel = '<div style="font-size: 20px;text-align: center;width: 300px;height: 100px;line-height: 100px;">File does not exist!</div>';
        return $custom_smilies_panel;
    }

    $custom_smilies_cdn = iro_opt('smilies_proxy');
    foreach ($custom_smilies_list as $smiley) {

        if ($custom_smilies_cdn) {
            $smiley_url = $custom_smilies_cdn . $smiley['little_path'];
        } else {
            $smiley_url = $smiley['file_url'];
        }
        $custom_smilies_panel = $custom_smilies_panel . '<span title="' . $smiley['base_name'].'" ' . make_onclick_grin($smiley['base_name'],'Math').'><img alt="custom_smilies" loading="lazy" style="height: 60px;" src="' . $smiley_url . '" /></span>';
        $custom_smiliestrans['{{' . $smiley['base_name'] . '}}'] = '<span title="' . $smiley['base_name'] . '" ><img alt="custom_smilies" loading="lazy" style="height: 60px;" src="' . $smiley_url . '" /></span>';
    }

    return $custom_smilies_panel;
}

/**
 * 替换评论、文章中的表情符号
 *
 */
function custom_smilies_filter($content)
{
    push_custom_smilies();
    global $custom_smiliestrans;
    $content = str_replace(array_keys($custom_smiliestrans), $custom_smiliestrans, $content);
    return $content;
}
add_filter('the_content', 'custom_smilies_filter');
add_filter('comment_text', 'custom_smilies_filter');


$wpsmiliestrans = array();
function push_tieba_smilies()
{
    global $wpsmiliestrans;
    // don't bother setting up smilies if they are disabled
    if (!get_option('use_smilies'))
        return;
    $tiebaname = array('good', 'han', 'spray', 'Grievance', 'shui', 'reluctantly', 'anger', 'tongue', 'se', 'haha', 'rmb', 'doubt', 'tear', 'surprised2', 'Happy', 'ku', 'surprised', 'theblackline', 'smilingeyes', 'spit', 'huaji', 'bbd', 'hu', 'shame', 'naive', 'rbq', 'britan', 'aa', 'niconiconi', 'niconiconi_t', 'niconiconit', 'awesome');
    $return_smiles = '';
    $type = is_webp() ? 'webp' : 'png';
    $tiebaimgdir = 'tieba' . $type . '/';
    $smiliesgs = '.' . $type;
    foreach ($tiebaname as $tieba_Name) {
        $grin = make_onclick_grin($tieba_Name,'tieba');
        // 选择面版
        $return_smiles = $return_smiles . '<span title="' . $tieba_Name . '" '.$grin.'><img alt="tieba_smilie" loading="lazy" src="' . iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@3.0/') . 'smilies/' . $tiebaimgdir . 'icon_' . $tieba_Name . $smiliesgs . '" /></span>';
        // 正文转换
        $wpsmiliestrans['::' . $tieba_Name . '::'] = '<span title="' . $tieba_Name . '" '.$grin.'><img alt="tieba_smilie" loading="lazy" src="' . iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@3.0/') . 'smilies/' . $tiebaimgdir . 'icon_' . $tieba_Name . $smiliesgs . '" /></span>';
    }
    return $return_smiles;
}
push_tieba_smilies();

function tieba_smile_filter($content)
{
    global $wpsmiliestrans;
    $content = str_replace(array_keys($wpsmiliestrans), $wpsmiliestrans, $content);
    return $content;
}
add_filter('the_content', 'tieba_smile_filter'); //替换文章关键词
add_filter('comment_text', 'tieba_smile_filter'); //替换评论关键词

function push_emoji_panel()
{
    $emojis = ['(⌒▽⌒)', '（￣▽￣）', '(=・ω・=)', '(｀・ω・´)', '(〜￣△￣)〜', '(･∀･)', '(°∀°)ﾉ', '(￣3￣)', '╮(￣▽￣)╭', '(´_ゝ｀)', '←_←', '→_→', '(&lt;_&lt;)', '(&gt;_&gt;)', '(;¬_¬)', '("▔□▔)/', '(ﾟДﾟ≡ﾟдﾟ)!?', 'Σ(ﾟдﾟ;)', 'Σ(￣□￣||)', '(’；ω；‘)', '（/TДT)/', '(^・ω・^ )', '(｡･ω･｡)', '(●￣(ｴ)￣●)', 'ε=ε=(ノ≧∇≦)ノ', '(’･_･‘)', '(-_-#)', '（￣へ￣）', '(￣ε(#￣)Σ', 'ヽ(‘Д’)ﾉ', '（#-_-)┯━┯', '(╯°口°)╯(┴—┴', '←◡←', '( ♥д♥)', '_(:3」∠)_', 'Σ&gt;―(〃°ω°〃)♡→', '⁄(⁄ ⁄•⁄ω⁄•⁄ ⁄)⁄', '(╬ﾟдﾟ)▄︻┻┳═一', '･*･:≡(　ε:)', '(笑)', '(汗)', '(泣)', '(苦笑)'];
    return join('', array_map(function ($emoji) {
        return '<span class="emoji-item">' . $emoji . '</span>';
    }, $emojis));
}

// bilibili smiles
$bilismiliestrans = array();
function push_bili_smilies()
{
    global $bilismiliestrans;
    $name = array('baiyan', 'bishi', 'bizui', 'chan', 'dai', 'daku', 'dalao', 'dalian', 'dianzan', 'doge', 'facai', 'fanu', 'ganga', 'guilian', 'guzhang', 'haixiu', 'heirenwenhao', 'huaixiao', 'jingxia', 'keai', 'koubizi', 'kun', 'lengmo', 'liubixue', 'liuhan', 'liulei', 'miantian', 'mudengkoudai', 'nanguo', 'outu', 'qinqin', 'se', 'shengbing', 'shengqi', 'shuizhao', 'sikao', 'tiaokan', 'tiaopi', 'touxiao', 'tuxue', 'weiqu', 'weixiao', 'wunai', 'xiaoku', 'xieyanxiao', 'yiwen', 'yun', 'zaijian', 'zhoumei', 'zhuakuang');
    $return_smiles = '';
    $type = is_webp() ? 'webp' : 'png';
    $biliimgdir = 'bili' . $type . '/';
    $smiliesgs = '.' . $type;
    foreach ($name as $smilies_Name) {
        $grin = make_onclick_grin($smilies_Name,'Math');
        // 选择面版
        $return_smiles = $return_smiles . '<span title="' . $smilies_Name . '" '.$grin.'><img alt="bili_smilies" loading="lazy" src="' . iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@3.0/') . 'smilies/' . $biliimgdir . 'emoji_' . $smilies_Name . $smiliesgs . '" /></span>';
        // 正文转换
        $bilismiliestrans['{{' . $smilies_Name . '}}'] = '<span title="' . $smilies_Name . '" '.$grin.'><img alt="bili_smilies" loading="lazy" src="' . iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@3.0/') . 'smilies/' . $biliimgdir . 'emoji_' . $smilies_Name . $smiliesgs . '" /></span>';
    }
    return $return_smiles;
}
push_bili_smilies();

function bili_smile_filter($content)
{
    global $bilismiliestrans;
    $content = str_replace(array_keys($bilismiliestrans), $bilismiliestrans, $content);
    return $content;
}
add_filter('the_content', 'bili_smile_filter'); //替换文章关键词
add_filter('comment_text', 'bili_smile_filter'); //替换评论关键词

function featuredtoRSS($content)
{
    global $post;
    if (has_post_thumbnail($post->ID)) {
        $content = '<div>' . get_the_post_thumbnail($post->ID, 'medium', array('style' => 'margin-bottom: 15px;')) . '</div>' . $content;
    }
    return $content;
}
add_filter('the_excerpt_rss', 'featuredtoRSS');
add_filter('the_content_feed', 'featuredtoRSS');

//
function bili_smile_filter_rss($content)
{
    $type = is_webp() ? 'webp' : 'png';
    $biliimgdir = 'bili' . $type . '/';
    $smiliesgs = '.' . $type;
    $content = str_replace('{{', '<img src="' . iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@3.0/') . 'smilies/' . $biliimgdir, $content);
    $content = str_replace('}}', $smiliesgs . '" alt="emoji" style="height: 2em; max-height: 2em;">', $content);
    $content = str_replace('[img]', '<img src="', $content);
    $content = str_replace('[/img]', '" style="display: block;margin-left: auto;margin-right: auto;">', $content);
    return $content;
}
add_filter('comment_text_rss', 'bili_smile_filter_rss'); //替换评论rss关键词

function toc_support($content)
{
    $content = str_replace('[toc]', '<div class="has-toc have-toc"></div>', $content); // TOC 支持
    $content = str_replace('[begin]', '<span class="begin">', $content); // 首字格式支持
    $content = str_replace('[/begin]', '</span>', $content); // 首字格式支持
    return $content;
}
add_filter('the_content', 'toc_support');
add_filter('the_excerpt_rss', 'toc_support');
add_filter('the_content_feed', 'toc_support');

function check_title_tags($content)
{
    if (!empty($content)) {
        $dom = new DOMDocument();
        @$dom->loadHTML($content);
        $headings = $dom->getElementsByTagName('h1');
        for ($i = 1; $i <= 6; $i++) {
            $headings = $dom->getElementsByTagName('h' . $i);
            foreach ($headings as $heading) {
                if (trim($heading->nodeValue) != '') {
                    return true;
                }
            }
        }
    }
    return false;
}

/*私密评论*/
add_action('wp_ajax_nopriv_siren_private', 'siren_private');
add_action('wp_ajax_siren_private', 'siren_private');
function siren_private()
{
    $comment_id = $_POST["p_id"];
    $action = $_POST["p_action"];
    if ($action == 'set_private') {
        update_comment_meta($comment_id, '_private', 'true');
        $i_private = get_comment_meta($comment_id, '_private', true);
        echo empty($i_private) ? '是' : '否';
    }
    die;
}

require_once __DIR__ . '/inc/word-stat.php';
/**
 * 字数、词数统计
 */
function count_post_words($post_ID)
{
    $post = get_post($post_ID);
    if (!in_array($post->post_type, ['post', 'shuoshuo'])) {
        return;
    }
    $content = $post->post_content;
    $content = strip_tags($content);
    $count = word_stat($content);
    update_post_meta($post_ID, 'post_words_count', $count);
    return $count;
}

add_action('save_post', 'count_post_words');

/*
 * 隐藏 Dashboard
 */
/* Remove the "Dashboard" from the admin menu for non-admin users */
function remove_dashboard()
{
    global $current_user, $menu, $submenu;
    wp_get_current_user();

    if (!in_array('administrator', $current_user->roles)) {
        reset($menu);
        $page = key($menu);
        while ((__('Dashboard') != $menu[$page][0]) && next($menu)) {
            $page = key($menu);
        }
        if (__('Dashboard') == $menu[$page][0]) {
            unset($menu[$page]);
        }
        reset($menu);
        $page = key($menu);
        while (!$current_user->has_cap($menu[$page][1]) && next($menu)) {
            $page = key($menu);
        }
        if (
            preg_match('#wp-admin/?(index.php)?$#', $_SERVER['REQUEST_URI']) &&
            ('index.php' != $menu[$page][2])
        ) {
            wp_redirect(get_option('siteurl') . '/wp-admin/profile.php');
        }
    }
}
add_action('admin_menu', 'remove_dashboard');

/**
 * Filter the except length to 20 words. 限制摘要长度
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */

function GBsubstr($string, $start, $length)
{
    if (strlen($string) > $length) {
        $str = null;
        $len = 0;
        $i = $start;
        while ($len < $length) {
            if (ord(substr($string, $i, 1)) > 0xc0) {
                $str .= substr($string, $i, 3);
                $i += 3;
            } elseif (ord(substr($string, $i, 1)) > 0xa0) {
                $str .= substr($string, $i, 2);
                $i += 2;
            } else {
                $str .= substr($string, $i, 1);
                $i++;
            }
            $len++;
        }
        return $str;
    } else {
        return $string;
    }
}

/**
 * chatgpt excerpt
 */
require_once __DIR__ . '/inc/chatgpt/hooks.php';
IROChatGPT\apply_chatgpt_hook();

function excerpt_length($exp)
{
    if (!function_exists('mb_substr')) {
        $exp = GBsubstr($exp, 0, 110);
    } else {
        /*
         * To use mb_substr() function, you should uncomment "extension=php_mbstring.dll" in php.ini
         */
        $exp = mb_substr($exp, 0, 110);
    }
    return $exp;
}
add_filter('the_excerpt', 'excerpt_length', 11);

/*
 * 评论表情修复
 */

function admin_ini()
{
    wp_enqueue_style('cus-styles-fit', get_template_directory_uri() . '/css/dashboard-emoji-fix.css');
}
add_action('admin_enqueue_scripts', 'admin_ini');

/*
 * 后台通知
 */
/**
 * 在提供权限的情况下，为管理员用户显示通知并更新 meta 值
 */
function theme_admin_notice_callback()
{
    // 判断当前用户是否为管理员
    if (!current_user_can('manage_options')) {
        return;
    }

    // 读取 meta 值
    $meta_value = get_user_meta(get_current_user_id(), 'theme_admin_notice', true);

    // 判断 meta 值是否存在
    if ($meta_value) {
        return; // 如果存在，退出函数，避免重复加载通知
    }

    // 显示通知
    $theme_name = 'Sakurairo';
    switch (get_user_locale()) {
        case 'zh_CN':
            $thankyou = '感谢你使用 ' . $theme_name . ' 主题！这里有一些需要你的许可的东西(*/ω＼*)';
            $dislike = '不，谢谢';
            $allow_send = '允许发送你的主题版本数据以便官方统计';
            break;

        case 'zh_TW':
            $thankyou = '感謝你使用 ' . $theme_name . ' 主題！以下是一些需要你許可的內容。';
            $dislike = '謝謝，不用了';
            $allow_send = '允許出於統計目的發送主題版本数据';
            break;

        case 'ja':
        case 'ja_JP':
            $thankyou = 'ご使用いただきありがとうございます！以下は、あなたの許可が必要なコンテンツです。';
            $dislike = 'いいえ、結構です';
            $allow_send = '統計目的のためにあなたのテーマバージョンを送信することを許可する';
            break;

        default:
            $thankyou = 'Thank you for using the ' . $theme_name . ' theme! There is something that needs your approval.';
            $dislike = 'No, thanks';
            $allow_send = 'Allow sending your theme version for statistical purposes';
            break;
    }
    ?>
                                <div class="notice notice-success" id="send-ver-tip">
                                    <p><?php echo $thankyou; ?></p>
                                    <button class="button" onclick="dismiss_notice()"><?php echo $dislike; ?></button>
                                    <button class="button" onclick="update_option()"><?php echo $allow_send; ?></button>
                                </div>
                                <script>
                                    function dismiss_notice() {
                                        // 隐藏通知
                                        document.getElementById( "send-ver-tip" ).style.display = "none";
                                        // 写入 1 到 meta
                                        var data = new FormData();
                                        data.append( 'action', 'update_theme_admin_notice_meta' );
                                        data.append( 'user_id', '<?php echo get_current_user_id(); ?>' );
                                        data.append( 'meta_key', 'theme_admin_notice' );
                                        data.append( 'meta_value', '1' );
                                        fetch( '<?php echo admin_url('admin-ajax.php'); ?>', {
                                            method: 'POST',
                                            body: data
                                        } );
                                    }

                                    function update_option() {
                                        // 隐藏通知
                                        document.getElementById( "send-ver-tip" ).style.display = "none";
                                        // 发送 AJAX 请求
                                        var xhr = new XMLHttpRequest();
                                        xhr.open( "POST", "<?php echo admin_url('admin-ajax.php'); ?>", true );
                                        xhr.setRequestHeader( "Content-Type", "application/x-www-form-urlencoded" );
                                        xhr.send( "action=update_theme_option&option=send_theme_version&value=true" );

                                        // 写入 1 到 meta
                                        var data = new FormData();
                                        data.append( 'action', 'update_theme_admin_notice_meta' );
                                        data.append( 'user_id', '<?php echo get_current_user_id(); ?>' );
                                        data.append( 'meta_key', 'theme_admin_notice' );
                                        data.append( 'meta_value', '1' );
                                        fetch( '<?php echo admin_url('admin-ajax.php'); ?>', {
                                            method: 'POST',
                                            body: data
                                        } );
                                    }
                                </script>
                                <?php
}
add_action('admin_notices', 'theme_admin_notice_callback');

/**
 * 检查父主题文件夹名称是否正确
 * 如果名称不正确，尝试重命名或显示管理员警告信息
 */

function theme_folder_check_on_admin_init() {
    // 获取当前父主题文件夹名称及路径
    $current_theme_path = get_template_directory();
    $theme_folder_name = basename($current_theme_path);
    $correct_theme_folder = 'Sakurairo';
    $user_locale = get_user_locale();

    // 仅管理员用户处理
    if (!current_user_can('manage_options')) {
        return;
    }

    // 当主题文件夹名称不正确时
    if ($theme_folder_name !== $correct_theme_folder) {
        $correct_theme_path = trailingslashit(dirname($current_theme_path)) . $correct_theme_folder;

        // 如果目标路径已存在
        if (file_exists($correct_theme_path)) {
            if (is_writable($correct_theme_path)) {
                $is_writable = true;
            } else {
                $is_writable = false;
            }
            add_action('admin_notices', function () use ($theme_folder_name, $correct_theme_folder, $user_locale,$is_writable) {
                switch ( $user_locale ) {
                    case 'zh_CN':
                        ?>
                        <div class="notice notice-error is-dismissible">
                            <p><strong>警告：</strong> 当前父主题文件夹名称为 <code><?php echo esc_html( $theme_folder_name ); ?></code>，但目标名称 <code><?php echo esc_html( $correct_theme_folder ); ?></code> 已存在。请手动检查主题文件夹。</p>
                            <?php if ($is_writable) { ?> <br><a href="/wp-admin/admin.php?iro_act=del_exist_theme" class="page-title-action">点击此处立即删除重名主题</a> <?php } ?>
                        </div>
                        <?php
                        break;
                    case 'zh_TW':
                        ?>
                        <div class="notice notice-error is-dismissible">
                            <p><strong>警告：</strong> 目前父主題資料夾名稱為 <code><?php echo esc_html( $theme_folder_name ); ?></code>，但目標名稱 <code><?php echo esc_html( $correct_theme_folder ); ?></code> 已存在。請手動檢查主題資料夾。</p>
                            <?php if ($is_writable) { ?> <br><a href="/wp-admin/admin.php?iro_act=del_exist_theme" class="page-title-action">點擊此處立即刪除重名的主題</a> <?php } ?>
                        </div>
                        <?php
                        break;
                    case 'ja':
                    case 'ja_JP':
                        ?>
                        <div class="notice notice-error is-dismissible">
                            <p><strong>警告：</strong> 現在の親テーマフォルダ名は <code><?php echo esc_html( $theme_folder_name ); ?></code> ですが、対象の名前 <code><?php echo esc_html( $correct_theme_folder ); ?></code> は既に存在します。テーマフォルダを手動で確認してください。</p>
                            <?php if ($is_writable) { ?> <br><a href="/wp-admin/admin.php?iro_act=del_exist_theme" class="page-title-action">ここをクリックして、重複するテーマをすぐに削除します</a> <?php } ?>
                        </div>
                        <?php
                        break;
                    default :
                        ?>
                        <div class="notice notice-error is-dismissible">
                            <p><strong>Warning:</strong> The current parent theme folder name is <code><?php echo esc_html( $theme_folder_name ); ?></code>, but the target name <code><?php echo esc_html( $correct_theme_folder ); ?></code> already exists. Please manually check the theme folder.</p>
                            <?php if ($is_writable) { ?> <br><a href="/wp-admin/admin.php?iro_act=del_exist_theme" class="page-title-action">Click here to immediately delete the duplicate theme</a> <?php } ?>
                        </div>
                        <?php
                        break;
                }
            });
            return;
        }

        // 尝试重命名文件夹
        if (rename($current_theme_path, $correct_theme_path)) {
            switch_theme($correct_theme_folder);
        } else {
            add_action('admin_notices', function () use ($theme_folder_name, $correct_theme_folder, $user_locale) {
                switch ( $user_locale ) {
                    case 'zh_CN':
                        ?>
                        <div class="notice notice-error is-dismissible">
                            <p><strong>警告：</strong> 当前父主题文件夹名称为 <code><?php echo esc_html( $theme_folder_name ); ?></code>，无法重命名为 <code><?php echo esc_html( $correct_theme_folder ); ?></code>。请检查文件系统权限。</p>
                        </div>
                        <?php
                        break;
                    case 'zh_TW':
                        ?>
                        <div class="notice notice-error is-dismissible">
                            <p><strong>警告：</strong> 目前父主題資料夾名稱為 <code><?php echo esc_html( $theme_folder_name ); ?></code>，無法重新命名為 <code><?php echo esc_html( $correct_theme_folder ); ?></code>。請檢查檔案系統權限。</p>
                        </div>
                        <?php
                        break;
                    case 'ja':
                    case 'ja_JP':
                        ?>
                        <div class="notice notice-error is-dismissible">
                            <p><strong>警告：</strong> 現在の親テーマフォルダ名は <code><?php echo esc_html( $theme_folder_name ); ?></code> ですが、<code><?php echo esc_html( $correct_theme_folder ); ?></code> にリネームできませんでした。ファイルシステムの権限を確認してください。</p>
                        </div>
                        <?php
                        break;
                    default:
                        ?>
                        <div class="notice notice-error is-dismissible">
                            <p><strong>Warning:</strong> The current parent theme folder name is <code><?php echo esc_html( $theme_folder_name ); ?></code>, and it cannot be renamed to <code><?php echo esc_html( $correct_theme_folder ); ?></code>. Please check the file system permissions.</p>
                        </div>
                        <?php
                        break;
                }
            });
        }
    } 
    // 当主题文件夹名称正确时，检查目录权限
    else {
        if (!is_writable($current_theme_path)) {
            add_action('admin_notices', function () use ($current_theme_path, $user_locale) {
                switch ($user_locale) {
                    case 'zh_CN':
                        ?>
                        <div class="notice notice-error is-dismissible">
                            <p><strong>警告：</strong> 当前主题目录 <code><?php echo esc_html($current_theme_path); ?></code> 不可写。请检查文件系统权限。</p>
                        </div>
                        <?php
                        break;
                    case 'zh_TW':
                        ?>
                        <div class="notice notice-error is-dismissible">
                            <p><strong>警告：</strong> 目前主題目錄 <code><?php echo esc_html($current_theme_path); ?></code> 不可寫。請檢查檔案系統權限。</p>
                        </div>
                        <?php
                        break;
                    case 'ja':
                    case 'ja_JP':
                        ?>
                        <div class="notice notice-error is-dismissible">
                            <p><strong>警告：</strong> 現在のテーマディレクトリ <code><?php echo esc_html($current_theme_path); ?></code> は書き込み不可です。ファイルシステムの権限を確認してください。</p>
                        </div>
                        <?php
                        break;
                    default:
                        ?>
                        <div class="notice notice-error is-dismissible">
                            <p><strong>Warning:</strong> The current theme directory <code><?php echo esc_html($current_theme_path); ?></code> is not writable. Please check the file system permissions.</p>
                        </div>
                        <?php
                        break;
                }
            });
        }
    }
}

// 在后台初始化时执行检查
add_action('admin_init', 'theme_folder_check_on_admin_init');

// AJAX 处理函数 - 更新主题选项
add_action('wp_ajax_update_theme_option', 'update_theme_option');
function update_theme_option()
{
    $option = $_POST['option'];
    $value = sanitize_text_field($_POST['value']);
    iro_opt_update($option, $value);
    wp_die();
}

// AJAX 处理函数 - 写入 theme_admin_notice 元值
add_action('wp_ajax_update_theme_admin_notice_meta', 'update_theme_admin_notice_meta');
function update_theme_admin_notice_meta()
{
    $user_id = $_POST['user_id'];
    $meta_key = $_POST['meta_key'];
    $meta_value = sanitize_text_field($_POST['meta_value']);
    update_user_meta($user_id, $meta_key, $meta_value);
    wp_die();
}

// 主动resize触发wp_scripts后台排版修正，防止左侧导航栏飞出
add_action('admin_footer',function() {
    ?><script>
        document.addEventListener('DOMContentLoaded',function() {
            window.dispatchEvent(new Event("resize"));
        })
    </script>
    <?php
});

//dashboard scheme
function dash_scheme($key, $name, $col1, $col2, $col3, $base, $focus, $current, $rules = "") {
    $hash = 'rules=' . urlencode($rules);
    if ($col1) {
        $hash .= '&color_1=' . str_replace("#", "", $col1); 
    }
    if ($col2) {
        $hash .= '&color_2=' . str_replace("#", "", $col2);
    }
    if ($col3) {
        $hash .= '&color_3=' . str_replace("#", "", $col3); 
    }

    wp_admin_css_color(
        $key,
        $name,
        get_template_directory_uri() . "/inc/dash-scheme.php?" . $hash,
        array($col1, $col2, $col3),
        array('base' => $base, 'focus' => $focus, 'current' => $current)
    );
}

//Sakurairo
dash_scheme(
    $key = "sakurairo",
    $name = "Sakurairo🌸",
    $col1 = iro_opt('admin_second_class_color'),
    $col2 = iro_opt('admin_first_class_color'), 
    $col3 = iro_opt('admin_emphasize_color'),
    $base = "#FFF",
    $focus = "#FFF",
    $current = "#FFF",
    $rules = 'body{background-image:url(' . iro_opt('admin_background') . ');background-attachment:fixed;background-size:cover;}'
);

// WordPress Custom style @ Admin
function custom_admin_open_sans_style()
{
    require get_template_directory() . '/inc/option-scheme.php';
}
add_action('admin_head', 'custom_admin_open_sans_style');

// WordPress Custom Font @ Admin
function custom_admin_open_sans_font()
{
    echo '<link href="https://' . iro_opt('gfonts_api', 'fonts.googleapis.com') . '/css?family=Noto+Serif+SC&display=swap" rel="stylesheet">' . PHP_EOL;
    echo '<style>body, #wpadminbar *:not([class="ab-icon"]), .wp-core-ui, .media-menu, .media-frame *, .media-modal *{font-family: "Noto Serif SC", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif !important;}</style>' . PHP_EOL;
}
add_action('admin_head', 'custom_admin_open_sans_font');

// WordPress Custom Font @ Admin Frontend Toolbar
function custom_admin_open_sans_font_frontend_toolbar()
{
    if (current_user_can('manage_options') && is_admin_bar_showing()) {
        echo '<link href="https://' . iro_opt('gfonts_api', 'fonts.googleapis.com') . '/css?family=Noto+Serif+SC&display=swap" rel="stylesheet">' . PHP_EOL;
        echo '<style>#wpadminbar *:not([class="ab-icon"]){font-family: "Noto Serif SC", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif !important;}</style>' . PHP_EOL;
    }
}
add_action('wp_head', 'custom_admin_open_sans_font_frontend_toolbar');

// WordPress Custom Font @ Admin Login
function custom_admin_open_sans_font_login_page()
{
    if (stripos($_SERVER["SCRIPT_NAME"], strrchr(wp_login_url(), '/')) !== false) {
        echo '<link href="https://' . iro_opt('gfonts_api', 'fonts.googleapis.com') . '/css?family=Noto+Serif+SC&display=swap" rel="stylesheet">' . PHP_EOL;
        echo '<style>body{font-family: "Noto Serif SC", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif !important;}</style>' . PHP_EOL;
    }
}
add_action('login_head', 'custom_admin_open_sans_font_login_page');

function array_html_props(array $props)
{
    $props_string = '';
    foreach ($props as $key => $value) {
        $props_string .= ' ' . $key . '="' . $value . '"';
    }
    return $props_string;
}
/**
 * 渲染一个懒加载的<img>
 * @author KotoriK
 */
function lazyload_img(string $src, string $class = '', array $otherParam = array())
{
    $noscriptParam = $otherParam;
    if ($class)
        $noscriptParam['class'] = $class;
    $noscriptParam['src'] = $src;
    $otherParam['class'] = 'lazyload' . ($class ? ' ' . $class : '');
    $otherParam['data-src'] = $src;
    $otherParam['onerror'] = 'imgError(this)';
    $otherParam['src'] = iro_opt('page_lazyload_spinner');
    $noscriptProps = '';
    $props = array_html_props($otherParam);
    $noscriptProps = array_html_props($noscriptParam);
    return "<img$props/><noscript><img$noscriptProps/></noscript>";
}

// html 标签处理器
function html_tag_parser($content)
{
    if (!is_feed()) {
        //图片懒加载标签替换
        if (iro_opt('page_lazyload') && iro_opt('page_lazyload_spinner')) {
            $img_elements = array();
            $is_matched = preg_match_all('/<img[^<]*>/i', $content, $img_elements);
            if ($is_matched) {
                array_walk($img_elements[0], function ($img) use (&$content) {
                    $class_found = 0;
                    $new_img = preg_replace('/class=[\'"]([^\'"]+)[\'"]/i', 'class="$1 lazyload"', $img, -1, $class_found);
                    if ($class_found == 0) {
                        $new_img = str_replace('<img ', '<img class="lazyload"', $new_img);
                    }
                    $new_img = preg_replace('/srcset=[\'"]([^\'"]+)[\'"]/i', 'data-srcset="$1"', $new_img);
                    $new_img = preg_replace('/src=[\'"]([^\'"]+)[\'"]/i', 'data-src="$1" src="' . iro_opt('page_lazyload_spinner') . '" onerror="imgError(this)"', $new_img);
                    $content = str_replace($img, $new_img . '<noscript>' . $img . '</noscript>', $content);
                });
            }
        }

        //Fancybox
        /* Markdown Regex Pattern for Matching URLs:
         * https://daringfireball.net/2010/07/improved_regex_for_matching_urls
         */
        $url_regex = '((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))';

        //With Thumbnail: !{alt}(url)[th_url]
        if (preg_match_all('/\!\{.*?\)\[.*?\]/i', $content, $matches)) {
            foreach ($matches as $result) {
                $content = str_replace(
                    $result,
                    preg_replace(
                        '/!\{([^\{\}]+)*\}\(' . $url_regex . '\)\[' . $url_regex . '\]/i',
                        '<a data-fancybox="gallery"
                        data-caption="$1"
                        class="fancybox"
                        href="$2"
                        alt="$1"
                        title="$1"><img src="$7" target="_blank" rel="nofollow" class="fancybox"></a>',
                        $result
                    ),
                    $content
                );
            }
        }

        //Without Thumbnail :!{alt}(url)
        $content = preg_replace(
            '/!\{([^\{\}]+)*\}\(' . $url_regex . '\)/i',
            '<a data-fancybox="gallery"
                data-caption="$1"
                class="fancybox"
                href="$2"
                alt="$1"
                title="$1"><img src="$2" target="_blank" rel="nofollow" class="fancybox"></a>',
            $content
        );
    }
    //html tag parser for rss
    if (is_feed()) {
        //Fancybox
        $url_regex = '((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))';
        if (preg_match_all('/\!\{.*?\)\[.*?\]/i', $content, $matches)) {
            foreach ($matches as $result) {
                $content = str_replace(
                    $result,
                    preg_replace('/!\{([^\{\}]+)*\}\(' . $url_regex . '\)\[' . $url_regex . '\]/i', '<a href="$2"><img src="$7" alt="$1" title="$1"></a>', $result),
                    $content
                );
            }
        }
        $content = preg_replace('/!\{([^\{\}]+)*\}\(' . $url_regex . '\)/i', '<a href="$2"><img src="$2" alt="$1" title="$1"></a>', $content);
    }
    return $content;
}
add_filter('the_content', 'html_tag_parser'); //替换文章关键词

/*
 * QQ 评论
 */
// 数据库插入评论表单的qq字段
add_action('wp_insert_comment', 'sql_insert_qq_field', 10, 2);
function sql_insert_qq_field($comment_ID, $commmentdata)
{
    $qq = isset($_POST['new_field_qq']) ? $_POST['new_field_qq'] : false;
    update_comment_meta($comment_ID, 'new_field_qq', $qq); // new_field_qq 是表单name值，也是存储在数据库里的字段名字
}
// 后台评论中显示qq字段
add_filter('manage_edit-comments_columns', 'add_comments_columns');
add_action('manage_comments_custom_column', 'output_comments_qq_columns', 10, 2);
function add_comments_columns($columns)
{
    $columns['new_field_qq'] = __('QQ'); // 新增列名称
    return $columns;
}
function output_comments_qq_columns($column_name, $comment_id)
{
    switch ($column_name) {
        case "new_field_qq":
            // 这是输出值，可以拿来在前端输出，这里已经在钩子manage_comments_custom_column上输出了
            echo get_comment_meta($comment_id, 'new_field_qq', true);
            break;
    }
}
/**
 * 头像调用路径
 */
add_filter('get_avatar', 'change_avatar', 10, 3);
function change_avatar($avatar)
{
    global $comment, $sakura_privkey;
    if ($comment && get_comment_meta($comment->comment_ID, 'new_field_qq', true)) {
        $qq_number = get_comment_meta($comment->comment_ID, 'new_field_qq', true);
        if (iro_opt('qq_avatar_link') == 'off') {
            return '<img src="https://q2.qlogo.cn/headimg_dl?dst_uin=' . $qq_number . '&spec=100" class="lazyload avatar avatar-24 photo" alt="😀" width="24" height="24" onerror="imgError(this,1)">';
        }
        if (iro_opt('qq_avatar_link') == 'type_3') {
            $qqavatar = file_get_contents('http://ptlogin2.qq.com/getface?appid=1006102&imgtype=3&uin=' . $qq_number);
            preg_match('/:\"([^\"]*)\"/i', $qqavatar, $matches);
            return '<img src="' . $matches[1] . '" class="lazyload avatar avatar-24 photo" alt="😀" width="24" height="24" onerror="imgError(this,1)">';
        }
        
        // Ensure $sakura_privkey is defined and not null
        if (isset($sakura_privkey) && !is_null($sakura_privkey)) {
            // 生成一个合适长度的初始化向量
            $iv_length = openssl_cipher_iv_length('aes-128-cbc');
            $iv = openssl_random_pseudo_bytes($iv_length);
            
            // 加密数据
            $encrypted = openssl_encrypt($qq_number, 'aes-128-cbc', $sakura_privkey, 0, $iv);
            
            // 将初始化向量和加密数据一起编码
            $encrypted = urlencode(base64_encode($iv . $encrypted));
            
            return '<img src="' . rest_url("sakura/v1/qqinfo/avatar") . '?qq=' . $encrypted . '" class="lazyload avatar avatar-24 photo" alt="😀" width="24" height="24" onerror="imgError(this,1)">';
        } else {
            // Handle the case where $sakura_privkey is not set or is null
            return '<img src="default_avatar_url" class="lazyload avatar avatar-24 photo" alt="😀" width="24" height="24" onerror="imgError(this,1)">';
        }
    }
    return $avatar;
}

//生成随机链接，防止浏览器缓存策略
function get_random_url(string $url): string
{
    $array = parse_url($url);
    if (!isset($array['query'])) {
        // 无参数
        $url .= '?';
    } else {
        // 有参数
        $url .= '&';
    }
    return $url . random_int(1, 100);
}

// default feature image
function DEFAULT_FEATURE_IMAGE()
{
    //使用独立外部api
    if (iro_opt('post_cover_options') == 'type_2') {
        $url = iro_opt('post_cover');
        return $url ? get_random_url($url) : '';
    }
    //使用内建
    if (iro_opt('random_graphs_options') == 'gallery') {
        $url = rest_url('sakura/v1/gallery') . '?img=w';
        return get_random_url($url);
    }
    //使用封面外部
    if (iro_opt('random_graphs_options') == 'external_api') {
        $url = iro_opt('random_graphs_link');
        return $url ? get_random_url($url) : '';
    }
    //意外情况
    $url = iro_opt('random_graphs_link');
    return $url ? get_random_url($url) : '';
}

//评论回复
function sakura_comment_notify($comment_id)
{
    if (!isset($_POST['mail-notify'])) {
        update_comment_meta($comment_id, 'mail_notify', 'false');
    }
}
add_action('comment_post', 'sakura_comment_notify');

//侧栏小工具
if (iro_opt('sakura_widget')) {
    if (function_exists('register_sidebar')) {
        register_sidebar(
            array(
                'name' => __('Sidebar'), //侧栏
                'id' => 'sakura_widget',
                'before_widget' => '<div class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="title"><h2>',
                'after_title' => '</h2></div>',
            )
        );
    }
}

// 评论Markdown解析
function markdown_parser($incoming_comment)
{
    global $wpdb, $comment_markdown_content;
    global $allowedtags;

    $enable_markdown = isset($_POST['enable_markdown']) ? (bool) $_POST['enable_markdown'] : false;

    if ($enable_markdown) {
        $may_script = array(
            '/<script.*?>.*?<\/script>/is', //<script>标签
            '/on\w+\s*=\s*(["\']).*?\1/is',
            '/on\w+\s*=\s*[^\s>]+/is'//on属性
        );
    
        foreach ($may_script as $pattern) {
            if (preg_match($pattern, $incoming_comment['comment_content'])) {
                siren_ajax_comment_err(__("Please do not try to use Javascript in your comments!")); //恶意内容警告
                return ($incoming_comment);
            }
        }
        $incoming_comment['comment_content'] = wp_kses($incoming_comment['comment_content'], $allowedtags); // 自行调用kses
    } else {
        $incoming_comment['comment_content'] = htmlspecialchars($incoming_comment['comment_content'], ENT_QUOTES, 'UTF-8'); //未启用markdown直接转义
    }

    // $column_names = $wpdb->get_row("SELECT * FROM information_schema.columns where 
    // table_name='$wpdb->comments' and column_name = 'comment_markdown' LIMIT 1");
    // //Add column if not present.
    // if (!isset($column_names)) {
    //     $wpdb->query("ALTER TABLE $wpdb->comments ADD comment_markdown text");
    // }
    $comment_markdown_content = $incoming_comment['comment_content'];

    if ($enable_markdown) { //未启用markdown不做解析
        include 'inc/Parsedown.php';
        $Parsedown = new Parsedown();
        $Parsedown->setSafeMode(false);
        $incoming_comment['comment_content'] = $Parsedown->setUrlsLinked(false)->text($incoming_comment['comment_content']);
    }
    return $incoming_comment;
}
add_filter('preprocess_comment', 'markdown_parser');
remove_filter('comment_text', 'make_clickable', 9);

// //保存Markdown评论
// function save_markdown_comment($comment_ID, $comment_approved)
// {
//     global $wpdb, $comment_markdown_content;
//     $comment = get_comment($comment_ID);
//     $comment_content = $comment_markdown_content;
//     //store markdow content
//     $wpdb->query("UPDATE $wpdb->comments SET comment_markdown='" . $comment_content . "' WHERE comment_ID='" . $comment_ID . "';");
// }
// add_action('comment_post', 'save_markdown_comment', 10, 2);

//打开评论HTML标签限制
function allow_more_tag_in_comment()
{
    global $allowedtags;
    $allowedtags['img'] = [
        'src' => [],
        'alt' => [],
        'width' => [],
        'height' => [],
        'title' => [],
    ];
    $allowedtags['a'] = [
        'href' => [],
        'title' => [],
        'target' => [],
        'rel' => [],
    ];
    $allowedtags['b'] = array('class' => array());
    $allowedtags['br'] = array('class' => array());
    $allowedtags['blockquote'] = array('class' => array());
    $allowedtags['p'] = array('class' => array());
    $allowedtags['pre'] = array('class' => array());
    $allowedtags['code'] = array('class' => array());
    $allowedtags['h1'] = array('class' => array());
    $allowedtags['h2'] = array('class' => array());
    $allowedtags['h3'] = array('class' => array());
    $allowedtags['h4'] = array('class' => array());
    $allowedtags['h5'] = array('class' => array());
    $allowedtags['ul'] = array('class' => array());
    $allowedtags['ol'] = array('class' => array());
    $allowedtags['li'] = array('class' => array());
    $allowedtags['td'] = array('class' => array());
    $allowedtags['th'] = array('class' => array());
    $allowedtags['tr'] = array('class' => array());
    $allowedtags['table'] = array('class' => array());
    $allowedtags['thead'] = array('class' => array());
    $allowedtags['tbody'] = array('class' => array());
    $allowedtags['span'] = array('class' => array());
}
add_action('init', 'allow_more_tag_in_comment');
// 移除wp核心内置的两阶段评论过滤
remove_filter('pre_comment_content', 'wp_filter_kses');
remove_filter('comment_save_pre', 'wp_filter_kses');

/**
 * 检查数据库是否支持MyISAM引擎
 */
function check_myisam_support()
{
    global $wpdb;
    $results = $wpdb->get_results("SHOW ENGINES");
    if (!$results)
        return false;
    foreach ($results as $result) {
        if ($result->Engine == "MyISAM") {
            return $result->Support == "YES";
        }
    }
    return false;
}

//rest api支持
function permalink_tip()
{
    if (!get_option('permalink_structure')) {
        $msg = __('<b> For a better experience, please do not set <a href="/wp-admin/options-permalink.php"> permalink </a> as plain. To do this, you may need to configure <a href="https://www.wpdaxue.com/wordpress-rewriterule.html" target="_blank"> pseudo-static </a>. </ b>', 'sakurairo'); /*<b>为了更好的使用体验，请不要将<a href="/wp-admin/options-permalink.php">固定链接</a>设置为朴素。为此，您可能需要配置<a href="https://www.wpdaxue.com/wordpress-rewriterule.html" target="_blank">伪静态</a>。</b>*/
        echo '<div class="notice notice-success is-dismissible" id="scheme-tip"><p><b>' . $msg . '</b></p></div>';
    }
}
add_action('admin_notices', 'permalink_tip');
//code end

//发送主题版本号 
function send_theme_version()
{
    $theme = wp_get_theme();
    $version = $theme->get('Version');
    $data = array(
        'date' => date('Y-m-d H:i:s'),
        'version' => $version
    );
    $args = array(
        'body' => $data,
        'timeout' => '5',
        'redirection' => '5',
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'cookies' => array()
    );
    wp_remote_post('https://api.fuukei.org/version-stat/index.php', $args);
}

if (iro_opt('send_theme_version') == '1') {
    if (!wp_next_scheduled('daily_event')) {
        wp_schedule_event(time(), 'daily', 'daily_event');
    }
    add_action('daily_event', 'send_theme_version');
}

//解析短代码  
function register_shortcodes() {
    add_shortcode('task', function($attr, $content = '') {
        return '<div class="task shortcodestyle"><i class="fa-solid fa-clipboard-list"></i>' . $content . '</div>';
    });

    add_shortcode('warning', function($attr, $content = '') {
        return '<div class="warning shortcodestyle"><i class="fa-solid fa-triangle-exclamation"></i>' . $content . '</div>';
    });

    add_shortcode('noway', function($attr, $content = '') {
        return '<div class="noway shortcodestyle"><i class="fa-solid fa-square-xmark"></i>' . $content . '</div>';
    });

    add_shortcode('buy', function($attr, $content = '') {
        return '<div class="buy shortcodestyle"><i class="fa-solid fa-square-check"></i>' . $content . '</div>';
    });

    add_shortcode('ghcard', function($attr, $content = '') {
        //获取内容
        $atts = shortcode_atts(array("path" => "mirai-mamori/Sakurairo"), $attr);

        $path = trim($atts['path']);

        if (strpos($path, 'https://github.com/') === 0) {
            $path = str_replace('https://github.com/', '', $path);
        }

        if (!preg_match('/^[a-zA-Z0-9_-]+\/[a-zA-Z0-9_.-]+$/', $path)) {
            return '<p>Invalid GitHub repository path: ' . esc_html($path) . '</p>';
        }
    
        list($username, $repo) = explode('/', $path, 2);
    
        //构造卡片内容
        $card_content = '';
    
        if (iro_opt('ghcard_proxy')) {
            
            $svg_url = 'https://github-readme-stats.vercel.app/api/pin/?hide_border=true&username=' . esc_attr($username) . '&repo=' . esc_attr($repo);
            $response = wp_remote_get($svg_url);
    
            if (!is_wp_error($response)) {
                $svg_content = wp_remote_retrieve_body($response);
                if (!empty($svg_content)) {
                    $card_content = $svg_content;
                } else {
                    $card_content = '';
                }
            } else {
                $card_content = '';
            }
        }
    
        //获取失败或未启用代理
        if (empty($card_content)) {
            $card_content = '<img decoding="async" src="https://github-readme-stats.vercel.app/api/pin/?hide_border=true&username=' . esc_attr($username) . '&repo=' . esc_attr($repo) . '" alt="Github-Card">';
        }
    
        //输出内容
        $ghcard = '<div class="ghcard">';
        $ghcard .= '<a href="https://github.com/' . esc_attr($path) . '" target="_blank" rel="noopener noreferrer">';
        $ghcard .= $card_content;
        $ghcard .= '</a>';
        $ghcard .= '</div>';
    
        return $ghcard;
    });

    add_shortcode('showcard', function($attr, $content = '') {
        $atts = shortcode_atts(array("icon" => "", "title" => "", "img" => "", "color" => ""), $attr);
        return sprintf(
            '<div class="showcard">
                <div class="img" alt="Show-Card" style="background:url(%s);background-size:cover;background-position: center;">
                    <a href="%s"><button class="showcard-button" style="color:%s !important;"><i class="fa-solid fa-angle-right"></i></button></a>
                </div>
                <div class="icon-title">
                    <i class="%s" style="color:%s !important;"></i>
                    <span class="title">%s</span>
                </div>
            </div>',
            $atts['img'],
            $content,
            esc_attr($atts['color']),
            esc_attr($atts['icon']),
            esc_attr($atts['color']),
            $atts['title'],
        );
    });

    add_shortcode('conversations', function($attr, $content = '') {
        $atts = shortcode_atts(array("avatar" => "", "direction" => "", "username" => ""), $attr);
        if (empty($atts['avatar']) && !empty($atts['username'])) {
            $user = get_user_by('login', $atts['username']);
            if ($user) {
                $atts['avatar'] = get_avatar_url($user->ID, 40);
            }
        }
        $speaker_alt = $atts['username'] ? '<span class="screen-reader-text">' . sprintf(__("%s says: ", "sakurairo"), esc_html($atts['username'])) . '</span>' : "";
        return sprintf(
            '<div class="conversations-code" style="flex-direction: %s;">
                <img src="%s">
                <div class="conversations-code-text">%s%s</div>
            </div>',
            esc_attr($atts['direction']),
            $atts['avatar'],
            $speaker_alt,
            $content
        );
    });

    add_shortcode('collapse', function($atts, $content = null) {
        $atts = shortcode_atts(array("title" => ""), $atts);
        ob_start();
        ?>
        <a href="javascript:void(0)" class="collapseButton">
            <div class="collapse shortcodestyle">
                <i class="fa-solid fa-angle-down"></i>
                <span class="xTitle"><?= $atts['title'] ?></span>
                <span class="ecbutton"><?php _e('Expand / Collapse', 'sakurairo'); ?></span>
            </div>
        </a>
        <div class="xContent" style="display: none;"><?= do_shortcode($content) ?></div>
        <?php
        return ob_get_clean();
    });

    add_shortcode('vbilibili', function ($atts, $content = null) {
        preg_match_all('/av([0-9]+)/', $content, $av_matches);
        preg_match_all('/BV([a-zA-Z0-9]+)/', $content, $bv_matches);
        $iframes = '';

        // av号
        if (!empty($av_matches[1])) {
            foreach ($av_matches[1] as $av) {
                $av = intval($av);
             
                $iframe_url = 'https://player.bilibili.com/player.html?avid=' . $av . '&page=1&autoplay=0&danmaku=0';
                $iframe = '<div style="position: relative; padding: 30% 45%;"><iframe src="' . $iframe_url . '" frameborder="no" scrolling="no" sandbox="allow-top-navigation allow-same-origin allow-forms allow-scripts" allowfullscreen="allowfullscreen" style="position: absolute; width: 100%; height: 100%; left: 0; top: 0;"> </iframe></div><br>';
                $iframes .= $iframe;
            }
        }
        // bv号
        if (!empty($bv_matches[1])) {
            foreach ($bv_matches[1] as $bv) {
                 
                $iframe_url = 'https://player.bilibili.com/player.html?bvid=' . $bv . '&page=1&autoplay=0&danmaku=0';
                $iframe = '<div style="position: relative; padding: 30% 45%;"><iframe src="' . $iframe_url . '" frameborder="no" scrolling="no" sandbox="allow-top-navigation allow-same-origin allow-forms allow-scripts" allowfullscreen="allowfullscreen" style="position: absolute; width: 100%; height: 100%; left: 0; top: 0;"> </iframe></div><br>';
                $iframes .= $iframe;
            }
        }
        return $iframes;
     });

     add_shortcode('steamuser', function ($atts, $content = null) {
        $key = iro_opt('steam_key');
        if (empty($key)) {
            // 多语言支持
            $lang = get_user_locale();
            if ($lang == 'zh_TW') {
                return '<div class="steam-error">需在Steam模板設置填寫Steam API KEY</div>';
            } elseif ($lang == 'ja') {
                return '<div class="steam-error">SteamテンプレートでSteam API KEYを設定してください</div>';
            } elseif ($lang == 'en_US') {
                return '<div class="steam-error">Please fill in Steam API KEY in Steam template settings</div>';
            } else {
                return '<div class="steam-error">需在Steam模板设置填写Steam API KEY</div>';
            }
        }
        preg_match_all('/\b7656\d{13}\b/', $content, $matches);
        $output = '<div class="steam-user-card">';
        foreach ($matches[0] as $steamid) {
            $url = 'https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . $key . '&steamids=' . $steamid;
            $response = get_transient('steam_stat_'.$steamid);
            if (!$response) {
                $response = wp_remote_get($url);
                set_transient('steam_stat_'.$steamid, $response, 180);
            }
            
            // 添加错误检查，防止WP_Error被当作数组使用
            if (is_wp_error($response)) {
                $output .= '<div class="steam-error">API错误: ' . $response->get_error_message() . '</div>';
                continue;
            }
            
            $data = json_decode($response["body"], true);
            $player = $data['response']['players'][0] ?? [];
            
            // 多语言支持
            $lang = get_user_locale();
            $status_text = [
                'offline' => [
                    'zh_CN' => '离线',
                    'zh_TW' => '離線',
                    'ja' => 'オフライン',
                    'en_US' => 'Offline'
                ],
                'online' => [
                    'zh_CN' => '在线',
                    'zh_TW' => '在線',
                    'ja' => 'オンライン',
                    'en_US' => 'Online'
                ],
                'away' => [
                    'zh_CN' => '离开',
                    'zh_TW' => '離開',
                    'ja' => '退席中',
                    'en_US' => 'Away'
                ],
                'unknown' => [
                    'zh_CN' => '未知状态，请提交issue',
                    'zh_TW' => '未知狀態，請提交issue',
                    'ja' => '不明なステータス、issueを提出してください',
                    'en_US' => 'Unknown status, please submit an issue'
                ],
                'playing' => [
                    'zh_CN' => '正在玩',
                    'zh_TW' => '正在玩',
                    'ja' => 'プレイ中',
                    'en_US' => 'Playing'
                ],
                'last_online' => [
                    'zh_CN' => '上次在线',
                    'zh_TW' => '上次在線',
                    'ja' => '最終オンライン',
                    'en_US' => 'Last online'
                ],
                'error' => [
                    'zh_CN' => 'ID填写错误，请检验',
                    'zh_TW' => 'ID填寫錯誤，請檢驗',
                    'ja' => 'IDが間違っています、確認してください',
                    'en_US' => 'ID error, please check'
                ]
            ];
            
            $status = match($player['personastate'] ?? 0) {
                0 => $status_text['offline'][$lang] ?? $status_text['offline']['zh_CN'],
                1 => $status_text['online'][$lang] ?? $status_text['online']['zh_CN'],
                3 => $status_text['away'][$lang] ?? $status_text['away']['zh_CN'],
                default => $status_text['unknown'][$lang] ?? $status_text['unknown']['zh_CN']
            };
            
            if (empty($data['response']['players'][0])) {
                $output .= '<div class="steam-error">' . ($status_text['error'][$lang] ?? $status_text['error']['zh_CN']) . '</div>';
            } else {
                $avatar = esc_attr(substr($player['avatar'], 0, strrpos($player['avatar'], '.')) . '_full' . substr($player['avatar'], strrpos($player['avatar'], '.')));
                $output .= '<div class="steam-profile">';
                $output .= '<div class="steam-profile-header">';
                $output .= '<img class="steam-avatar" src="' . $avatar . '" alt="Steam Avatar">';
                $output .= '<div class="steam-profile-info">';
                $output .= '<a href="' . esc_attr($player['profileurl']) . '" target="_blank" class="steam-username"><i class="fa-brands fa-steam"></i> ' . esc_attr($player['personaname']) . '</a>';
                $output .= '<div class="steam-status status-' . strtolower(str_replace(' ', '-', $status)) . '">' . $status . '</div>';
                $output .= '</div>'; // .steam-profile-info
                $output .= '</div>'; // .steam-profile-header

                if(!empty($player['gameextrainfo'])) {
                    $output .= '<div class="steam-game-info">';
                    $output .= '<a href="https://store.steampowered.com/app/' . esc_attr($player['gameid']) . '/" target="_blank" class="steam-game-name"><i class="fa-solid fa-gamepad"></i> ' . 
                        ($status_text['playing'][$lang] ?? $status_text['playing']['zh_CN']) . ': ' . esc_attr($player['gameextrainfo']) . '</a>';
                    $output .= '<img class="steam-game-banner" src="https://shared.cdn.steamchina.queniuam.com/store_item_assets/steam/apps/' . esc_attr($player['gameid']) . '/header.jpg" alt="Game Banner">';
                    $output .= '</div>'; // .steam-game-info
                }
                
                if (($player['personastate'] ?? 0) === 0 && isset($player['lastlogoff'])) {
                    $last_online = wp_date('Y-m-d H:i', $player['lastlogoff']);
                    $output .= '<div class="steam-last-online"><i class="fa-regular fa-clock"></i> ' . 
                        ($status_text['last_online'][$lang] ?? $status_text['last_online']['zh_CN']) . '：' . esc_attr($last_online) . '</div>';
                }
                
                $output .= '</div>'; // .steam-profile
            }
        }
        $output .= '</div>'; // .steam-user-card
        return $output;
    });
}
add_action('init', 'register_shortcodes');
//code end

//WEBP支持
function mimvp_filter_mime_types($array)
{
    $array['webp'] = 'image/webp';
    return $array;
}
add_filter('mime_types', 'mimvp_filter_mime_types', 10, 1);
function mimvp_file_is_displayable_image($result, $path)
{
    $info = @getimagesize($path);
    // if ($info['mime'] == 'image/webp') {
    //     $result = true;
    // }
    // return $result;
    return (bool) ($info); // 根据文档这里需要返回一个bool
}
add_filter('file_is_displayable_image', 'mimvp_file_is_displayable_image', 10, 2);

//code end

if (!iro_opt('login_language_opt') == '1') {
    add_filter('login_display_language_dropdown', '__return_false');
}

if (iro_opt('captcha_select') === 'iro_captcha') {
    function login_CAPTCHA()
    {
        include_once('inc/classes/Captcha.php');
        $img = new Sakura\API\Captcha;
        $test = $img->create_captcha_img();
        echo '<p><label for="captcha" class="captcha"><img id="captchaimg" width="120" height="40" style="border-radius: 8px;" src="', $test['data'], '"><input type="text" name="yzm" id="yzm" class="input" value="" size="20" tabindex="4" placeholder="请输入验证码"><input type="hidden" name="timestamp" value="', $test['time'], '"><input type="hidden" name="id" value="', $test['id'], '">'
            . "</label></p>";
    }
    add_action('login_form', 'login_CAPTCHA');
    add_action('register_form', 'login_CAPTCHA');
    add_action('lostpassword_form', 'login_CAPTCHA');

    /**
     * 登录界面验证码验证
     */
    function CAPTCHA_CHECK($user, $username, $password)
    {
        // Skip captcha check if it's a passwordless login
        if (isset($_POST['skip_captcha_check']) && $_POST['skip_captcha_check'] == '1') {
            return $user;
        }
        
        if (empty($_POST)) {
            return new WP_Error();
        }
        if (!(isset($_POST['yzm']) && !empty(trim($_POST['yzm'])))) {
            return new WP_Error('prooffail', '<strong>错误</strong>：验证码为空！');
        }
        if (!isset($_POST['timestamp']) || !isset($_POST['id']) || !preg_match('/^[\w$.\/]+$/', $_POST['id']) || !ctype_digit($_POST['timestamp'])) {
            return new WP_Error('prooffail', '<strong>错误</strong>：非法数据');
        }
        include_once('inc/classes/Captcha.php');
        $img = new Sakura\API\Captcha;
        $check = $img->check_captcha($_POST['yzm'], $_POST['timestamp'], $_POST['id']);
        if ($check['code'] == 5) {
            return $user;
        }
        return new WP_Error('prooffail', '<strong>错误</strong>：' . $check['msg']);
    }
    add_filter('authenticate', 'CAPTCHA_CHECK', 20, 3);
    
    // Add JavaScript to check for password field and toggle captcha visibility
    function add_captcha_check_script() {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var loginForm = document.getElementById('loginform');
            if (!loginForm) return;
            
            // Add hidden field for skipping captcha check
            var hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = 'skip_captcha_check';
            hiddenField.id = 'skip_captcha_check';
            hiddenField.value = '0';
            loginForm.appendChild(hiddenField);
            
            // Get elements once at initialization
            var passwordField = document.getElementById('user_pass');
            var captchaImg = document.getElementById('captchaimg');
            var yzmField = document.getElementById('yzm');
            
            // Find the captcha container (the parent element that contains the captcha)
            var captchaContainer = null;
            if (yzmField) {
                // Try to find the parent paragraph or label
                captchaContainer = yzmField.closest('p') || yzmField.closest('label');
                if (!captchaContainer && yzmField.parentNode) {
                    captchaContainer = yzmField.parentNode;
                }
            }
            
            function checkPasswordField() {
                // Check if password field is hidden or not present
                var isPasswordVisible = passwordField && 
                                        passwordField.style.display !== 'none' && 
                                        passwordField.offsetParent !== null;
                
                if (!isPasswordVisible) {
                    // Hide captcha elements
                    if (captchaContainer) {
                        captchaContainer.style.display = 'none';
                    }
                    
                    hiddenField.value = '1';
                } else {
                    // Show captcha elements
                    if (captchaContainer) {
                        captchaContainer.style.display = '';
                    }
                    
                    hiddenField.value = '0';
                }
            }
            
            // Initial check
            checkPasswordField();
            
            // Set up a less frequent interval to reduce performance impact
            var checkInterval = setInterval(checkPasswordField, 500);
            
            // Use MutationObserver for efficiency
            if (typeof MutationObserver !== 'undefined') {
                var observer = new MutationObserver(checkPasswordField);
                
                observer.observe(loginForm, {
                    childList: true,
                    subtree: true,
                    attributes: true,
                    attributeFilter: ['style', 'class', 'display']
                });
            }
            
            // Add event listener for form submission
            loginForm.addEventListener('submit', checkPasswordField);
        });
        </script>
        <?php
    }
    add_action('login_footer', 'add_captcha_check_script');
    /**
     * 忘记密码界面验证码验证
     */
    function lostpassword_CHECK($errors)
    {
        if (empty($_POST)) {
            return false;
        }
        if (isset($_POST['yzm']) && !empty(trim($_POST['yzm']))) {
            if (!isset($_POST['timestamp']) || !isset($_POST['id']) || !preg_match('/^[\w$.\/]+$/', $_POST['id']) || !ctype_digit($_POST['timestamp'])) {
                return new WP_Error('prooffail', '<strong>错误</strong>：非法数据');
            }
            include_once('inc/classes/Captcha.php');
            $img = new Sakura\API\Captcha;
            $check = $img->check_captcha($_POST['yzm'], $_POST['timestamp'], $_POST['id']);
            if ($check['code'] != 5) {
                return $errors->add('invalid_department ', '<strong>错误</strong>：' . $check['msg']);
            }
        } else {
            return $errors->add('invalid_department', '<strong>错误</strong>：验证码为空！');
        }
    }

    add_action('lostpassword_post', 'lostpassword_CHECK');
    /** 
     *   注册界面验证码验证
     */
    function registration_CAPTCHA_CHECK($errors, $sanitized_user_login, $user_email)
    {
        if (empty($_POST)) {
            return new WP_Error();
        }
        if (!(isset($_POST['yzm']) && !empty(trim($_POST['yzm'])))) {
            return new WP_Error('prooffail', '<strong>错误</strong>：验证码为空！');
        }
        if (!isset($_POST['timestamp']) || !isset($_POST['id']) || !preg_match('/^[\w$.\/]+$/', $_POST['id']) || !ctype_digit($_POST['timestamp'])) {
            return new WP_Error('prooffail', '<strong>错误</strong>：非法数据');
        }
        include_once('inc/classes/Captcha.php');
        $img = new Sakura\API\Captcha;
        $check = $img->check_captcha($_POST['yzm'], $_POST['timestamp'], $_POST['id']);
        if ($check['code'] == 5)
            return $errors;

        return new WP_Error('prooffail', '<strong>错误</strong>：' . $check['msg']);

    }
    add_filter('registration_errors', 'registration_CAPTCHA_CHECK', 2, 3);
} elseif ((iro_opt('captcha_select') === 'vaptcha') && (!empty(iro_opt("vaptcha_vid")) && !empty(iro_opt("vaptcha_key")))) {
    function vaptchaInit()
    {
        include_once('inc/classes/Vaptcha.php');
        $vaptcha = new Sakura\API\Vaptcha;
        echo $vaptcha->html();
        echo $vaptcha->script();
    }
    add_action('login_form', 'vaptchaInit');

    function checkVaptchaAction($user)
    {
        if (empty($_POST)) {
            return new WP_Error();
        }
        if (!(isset($_POST['vaptcha_server']) && isset($_POST['vaptcha_token']))) {
            return new WP_Error('prooffail', '<strong>错误</strong>：请先进行人机验证');

        }
        if (!preg_match('/^https:\/\/([\w-]+\.)+[\w-]*([^<>=?\"\'])*$/', $_POST['vaptcha_server']) || !preg_match('/^[\w\-\$]+$/', $_POST['vaptcha_token'])) {
            return new WP_Error('prooffail', '<strong>错误</strong>：非法数据');
        }
        include_once('inc/classes/Vaptcha.php');
        $url = $_POST['vaptcha_server'];
        $token = $_POST['vaptcha_token'];
        $ip = get_the_user_ip();
        $vaptcha = new Sakura\API\Vaptcha;
        $response = $vaptcha->checkVaptcha($url, $token, $ip);
        if ($response->msg && $response->success && $response->score) {
            if ($response->success === 1 && $response->score >= 70) {
                return $user;
            }
            if ($response->success === 0) {
                $errorcode = $response->msg;
                return new WP_Error('prooffail', '<strong>错误</strong>：' . $errorcode);
            }
            return new WP_Error('prooffail', '<strong>错误</strong>：人机验证失败');

        } else if (is_string($response)) {
            return new WP_Error('prooffail', '<strong>错误</strong>：' . $response);
        }
        return new WP_Error('prooffail', '<strong>错误</strong>：未知错误');


    }
    add_filter('authenticate', 'checkVaptchaAction', 20, 3);
}

// 获取访客 IP
function get_the_user_ip()
{
    // if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    //     //check ip from share internet
    //     $ip = $_SERVER['HTTP_CLIENT_IP'];
    // } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    //     //to check ip is pass from proxy
    //     $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    // } else {
    //     $ip = $_SERVER['REMOTE_ADDR'];
    // }
    // 简略版
    // $ip = $_SERVER['HTTP_CLIENT_IP'] ?: ($_SERVER['HTTP_X_FORWARDED_FOR'] ?: $_SERVER['REMOTE_ADDR']);
    $ip = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
    return apply_filters('wpb_get_ip', $ip);
}

//归档页信息缓存
function get_archive_info($get_page = false) {
    // 获取所有文章和说说
    $args = [
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
        'post_type' => array('post', 'shuoshuo'),
        'post_status'    => 'publish',
        'suppress_filters' => false // 同时获取文章和说说
    ];
    if ($get_page){
        $args['post_type'] = array('post', 'shuoshuo', 'page');
    }
    $posts = get_posts($args);
    // 统计
    $years = [];
    $stats = [
        'total' => [
            'posts' => 0,
            'views' => 0,
            'words' => 0,
            'comments' => 0
        ],
        'shuoshuo' => [
            'posts' => 0,
            'views' => 0,
            'words' => 0,
            'comments' => 0
        ],
        'article' => [
            'posts' => 0,
            'views' => 0,
            'words' => 0,
            'comments' => 0
        ],
        'page' => [
            'posts' => 0,
            'views' => 0,
            'words' => 0,
            'comments' => 0
        ]
    ];
    foreach ($posts as $post) {
        $views = get_post_views($post->ID);
        $words = get_meta_words_count($post->ID);
        $comments = get_comments_number($post->ID);
        
        // 判断页面类型
        if ($post->post_type == 'post') {
            $post_type = 'article';
        }
        if ($post->post_type == 'shuoshuo') {
            $post_type = 'shuoshuo';
        }
        if ($post->post_type == 'page') {
            $post_type = 'page';
        }
        
        // 更新统计数据
        $stats[$post_type]['posts']++;
        $stats[$post_type]['views'] += intval($views);
        $stats[$post_type]['words'] += intval($words);
        $stats[$post_type]['comments'] += intval($comments);
        
        $stats['total']['posts']++;
        $stats['total']['views'] += intval($views);
        $stats['total']['words'] += intval($words);
        $stats['total']['comments'] += intval($comments);
        
        $year = date('Y', strtotime($post->post_date));
        $month = date('n', strtotime($post->post_date));
        if ($post->post_password != ''){
            $post->post_title = __("It's a secret",'sakurairo'); // 隐藏受密码保护文章的标题
        }
        $post = [ //仅保存需要的数据（归档、展示区）
            'post_title'    => $post->post_title,
            'post_author'     => $post->post_author,
            'post_date'     => $post->post_date,
            'post_modified'     => $post->post_modified,
            'comment_count' => $comments,
            'guid'          => $post->guid,
            'meta' => [
                'views' => $views,
                'words' => $words,
                'type' => $post_type
            ]
        ];
        
        if (!isset($years[$year])) $years[$year] = [];
        if (!isset($years[$year][$month])) $years[$year][$month] = [];
        $years[$year][$month][] = $post;
    }

    return $years;
}

//更新文章后更新缓存
add_action('save_post', function(){
    get_archive_info();
});

/*
 * 友情链接提交功能
 */
function sakurairo_link_submission_handler() {
    // 确保所有错误和输出都不会干扰JSON响应
    try {
        // 验证请求方法，只允许POST请求
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            wp_send_json_error(array('message' => __('Invalid request method.', 'sakurairo')));
            return;
        }

        // 验证Referer，防止跨站请求
        check_ajax_referer('link_submission_nonce', 'link_submission_nonce');

        // 限制提交频率，防止滥用
        $ip = get_the_user_ip();
        $transient_key = 'link_submit_' . md5($ip);
        if (false !== get_transient($transient_key)) {
            wp_send_json_error(array('message' => __('You are submitting too frequently. Please try again later.', 'sakurairo')));
            return;
        }

        // 验证nonce
        if (!isset($_POST['link_submission_nonce']) || !wp_verify_nonce($_POST['link_submission_nonce'], 'link_submission_nonce')) {
            wp_send_json_error(array('message' => __('Security verification failed.', 'sakurairo')));
            return; 
        }

        // 验证必填字段
        $required_fields = array('siteName', 'siteUrl', 'siteDescription', 'siteImage', 'contactEmail', 'yzm', 'timestamp', 'id');
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                wp_send_json_error(array('message' => __('Please fill in all required fields.', 'sakurairo')));
                return;
            }
        }

        // 验证验证码
        include_once('inc/classes/Captcha.php');
        $img = new Sakura\API\Captcha;
        $captcha_check = $img->check_captcha(
            sanitize_text_field($_POST['yzm']), 
            sanitize_text_field($_POST['timestamp']), 
            sanitize_text_field($_POST['id'])
        );
        
        if ($captcha_check['code'] != 5) {
            wp_send_json_error(array('message' => $captcha_check['msg']));
            return;
        }
        
        // 设置提交频率限制（10分钟）
        set_transient($transient_key, 1, 600);

        // 检查是否达到草稿链接上限 (20个)
        // 首先确保分类存在
        $pending_cat_id = 0;
        $pending_cat_name = __('Pending Links', 'sakurairo');
        $link_categories = get_terms('link_category', array('hide_empty' => false));
        
        foreach ($link_categories as $category) {
            if ($category->name === $pending_cat_name) {
                $pending_cat_id = $category->term_id;
                break;
            }
        }
        
        // 如果分类不存在，创建它
        if ($pending_cat_id === 0) {
            $new_cat = wp_insert_term($pending_cat_name, 'link_category');
            if (!is_wp_error($new_cat)) {
                $pending_cat_id = $new_cat['term_id'];
            }
        }
        
        // 检查是否达到待审核链接上限
        if (sakurairo_check_pending_links_limit()) {
            wp_send_json_error(array('message' => __('Sorry, we are not accepting new link submissions at this time due to backlog. Please try again later.', 'sakurairo')));
            return;
        }

        // 清理和验证输入数据
        $site_name = sanitize_text_field($_POST['siteName']);
        $site_url = esc_url_raw($_POST['siteUrl']);
        $site_description = sanitize_textarea_field($_POST['siteDescription']);
        $site_image = esc_url_raw($_POST['siteImage']);
        $contact_email = sanitize_email($_POST['contactEmail']);

        // 验证数据长度，防止过长的输入
        if (mb_strlen($site_name) > 100) {
            wp_send_json_error(array('message' => __('Site name is too long.', 'sakurairo')));
            return;
        }
        
        if (mb_strlen($site_description) > 200) {
            wp_send_json_error(array('message' => __('Site description is too long.', 'sakurairo')));
            return;
        }

        // 验证URL格式
        if (!filter_var($site_url, FILTER_VALIDATE_URL)) {
            wp_send_json_error(array('message' => __('Please enter a valid URL.', 'sakurairo')));
            return;
        }
        
        // 验证图片URL格式
        if (!filter_var($site_image, FILTER_VALIDATE_URL)) {
            wp_send_json_error(array('message' => __('Please enter a valid image URL.', 'sakurairo')));
            return;
        }

        // 验证邮箱格式
        if (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
            wp_send_json_error(array('message' => __('Please enter a valid email address.', 'sakurairo')));
            return;
        }

        // 检查URL是否已存在
        $existing_links = get_bookmarks();
        foreach ($existing_links as $link) {
            if (trailingslashit($link->link_url) === trailingslashit($site_url)) {
                wp_send_json_error(array('message' => __('This website URL is already in our links list.', 'sakurairo')));
                return;
            }
        }

        // 准备链接数据
        $link_data = array(
            'link_name' => $site_name,
            'link_url' => $site_url,
            'link_description' => $site_description,
            'link_image' => $site_image,
            'link_target' => '_blank',
            'link_owner' => $contact_email,
            'link_rating' => 0,
            'link_visible' => 'N', // 默认不可见（待审核）
            'link_rel' => 'friend',
            'link_notes' => '',
            'link_rss' => ''
        );

        // 保存链接
        if (function_exists('wp_insert_link')) {
            // 直接插入（需要管理员审核）
            $link_id = wp_insert_link($link_data);
            
            if (is_wp_error($link_id)) {
                wp_send_json_error(array('message' => __('Failed to submit link. Please try again later.', 'sakurairo')));
                return;
            }
            
            // 将链接分配到待审核分类
            if ($pending_cat_id > 0) {
                wp_set_object_terms($link_id, array($pending_cat_id), 'link_category');
            }
            
            // 发送通知邮件给管理员
            $admin_email = get_option('admin_email');
            $blog_name = get_bloginfo('name');
            $subject = sprintf(__('[%s] New Friend Link Submission', 'sakurairo'), $blog_name);
            
            $message = sprintf(__("A new friend link has been submitted on your website %s:\n\n", 'sakurairo'), $blog_name);
            $message .= sprintf(__("Site Name: %s\n", 'sakurairo'), $site_name);
            $message .= sprintf(__("Site URL: %s\n", 'sakurairo'), $site_url);
            $message .= sprintf(__("Site Description: %s\n", 'sakurairo'), $site_description);
            $message .= sprintf(__("Site Image: %s\n", 'sakurairo'), $site_image);
            $message .= sprintf(__("Contact Email: %s\n", 'sakurairo'), $contact_email);
            $message .= sprintf(__("IP Address: %s\n\n", 'sakurairo'), get_the_user_ip());
            $message .= __("Please review this submission in your WordPress admin panel.\n", 'sakurairo');
            $message .= admin_url('link-manager.php');
            
            wp_mail($admin_email, $subject, $message);
            
            // 记录IP和提交时间，用于后续分析
            update_post_meta($link_id, '_link_submit_ip', get_the_user_ip());
            update_post_meta($link_id, '_link_submit_time', current_time('mysql'));
            
            wp_send_json_success(array(
                'message' => __('Thank you! Your link has been submitted for review.', 'sakurairo')
            ));
            return;
        } else {
            // 备用方法：创建一个含有链接详情的文章
            $post_content = sprintf(__('Site Name: %s', 'sakurairo'), $site_name) . "\n";
            $post_content .= sprintf(__('Site URL: %s', 'sakurairo'), $site_url) . "\n";
            $post_content .= sprintf(__('Site Description: %s', 'sakurairo'), $site_description) . "\n";
            $post_content .= sprintf(__('Site Image: %s', 'sakurairo'), $site_image) . "\n";
            $post_content .= sprintf(__('Contact Email: %s', 'sakurairo'), $contact_email) . "\n";
            $post_content .= sprintf(__('IP Address: %s', 'sakurairo'), get_the_user_ip()) . "\n";

            $post_data = array(
                'post_title' => __('Friend Link Submission: ', 'sakurairo') . $site_name,
                'post_content' => $post_content,
                'post_status' => 'pending',
                'post_type' => 'post',
                'post_author' => 1, // 默认管理员用户
                'meta_input' => array(
                    'link_submission_data' => array(
                        'site_name' => $site_name,
                        'site_url' => $site_url,
                        'site_description' => $site_description,
                        'site_image' => $site_image,
                        'contact_email' => $contact_email,
                        'submit_ip' => get_the_user_ip(),
                        'submit_time' => current_time('mysql')
                    )
                )
            );

            $post_id = wp_insert_post($post_data);

            if (is_wp_error($post_id)) {
                wp_send_json_error(array('message' => __('Failed to submit link. Please try again later.', 'sakurairo')));
                return;
            }

            // 发送通知邮件给管理员
            $admin_email = get_option('admin_email');
            $blog_name = get_bloginfo('name');
            $subject = sprintf(__('[%s] New Friend Link Submission', 'sakurairo'), $blog_name);
            
            $message = sprintf(__("A new friend link has been submitted on your website %s:\n\n", 'sakurairo'), $blog_name);
            $message .= $post_content;
            $message .= __("\nPlease review this submission in your WordPress admin panel.\n", 'sakurairo');
            $message .= admin_url('edit.php?post_status=pending&post_type=post');
            
            wp_mail($admin_email, $subject, $message);
            
            wp_send_json_success(array(
                'message' => __('Thank you! Your link has been submitted for review.', 'sakurairo')
            ));
            return;
        }
    } catch (Exception $e) {
        // 捕获异常并返回友好的错误信息
        wp_send_json_error(array(
            'message' => __('An unexpected error occurred. Please try again later.', 'sakurairo')
        ));
        return;
    }
}
add_action('wp_ajax_link_submission', 'sakurairo_link_submission_handler');
add_action('wp_ajax_nopriv_link_submission', 'sakurairo_link_submission_handler');

/**
 * 检查友情链接待审核数量，返回是否达到上限
 */
function sakurairo_check_pending_links_limit() {
    // 获取待审核链接分类
    $pending_cat_id = 0;
    $pending_cat_name = __('Pending Links', 'sakurairo');
    $link_categories = get_terms('link_category', array('hide_empty' => false));
    
    foreach ($link_categories as $category) {
        if ($category->name === $pending_cat_name) {
            $pending_cat_id = $category->term_id;
            break;
        }
    }
    
    // 如果分类不存在，返回false（未达到上限）
    if ($pending_cat_id === 0) {
        return false;
    }
    
    // 获取该分类下的链接数量
    $pending_links = get_bookmarks(array(
        'category' => $pending_cat_id,
        'hide_invisible' => false // 确保获取所有链接，包括不可见的链接
    ));
    $pending_links_count = count($pending_links);
    
    // 检查是否达到上限
    return $pending_links_count >= 20;
}

/**
 * 检测已审核通过的友情链接状态
 * 使用稳健的方法，每周一次，分批进行检测
 */
function sakurairo_check_approved_links_status() {
    // 获取上次检查的批次
    $last_batch = get_option('sakurairo_link_check_last_batch', 0);
    $batch_size = 5; // 每次检查5个链接
    
    // 获取所有可见的友情链接（已审核通过的）
    $approved_links = get_bookmarks(array(
        'hide_invisible' => true, // 只获取可见的链接
    ));
    
    // 如果没有链接，直接返回
    if (empty($approved_links)) {
        return;
    }
    
    // 计算总批次数
    $total_batches = ceil(count($approved_links) / $batch_size);
    
    // 确定当前批次
    $current_batch = ($last_batch + 1) % $total_batches;
    
    // 计算当前批次的起始和结束索引
    $start_index = $current_batch * $batch_size;
    $end_index = min($start_index + $batch_size, count($approved_links));
    
    // 获取当前批次的链接
    $current_batch_links = array_slice($approved_links, $start_index, $end_index - $start_index);
    
    // 检查每个链接的状态
    foreach ($current_batch_links as $link) {
        sakurairo_check_single_link_status($link);
    }
    
    // 更新最后检查的批次
    update_option('sakurairo_link_check_last_batch', $current_batch);
    
    // 记录检查时间
    update_option('sakurairo_link_check_last_time', current_time('mysql'));
}

/**
 * 检查单个友情链接的状态
 * 
 * @param object $link 友情链接对象
 */
function sakurairo_check_single_link_status($link) {
    // 如果链接不存在或URL为空，直接返回
    if (!$link || empty($link->link_url)) {
        return;
    }
    
    $link_id = $link->link_id;
    $link_url = $link->link_url;
    
    // 获取上次检查状态
    $last_check_status = get_post_meta($link_id, '_link_check_status', true);
    $last_check_time = get_post_meta($link_id, '_link_check_time', true);
    $failure_count = intval(get_post_meta($link_id, '_link_failure_count', true));
    
    // 使用 wp_safe_remote_head 进行安全的HTTP请求检查
    // 设置较短的超时时间和用户代理，避免长时间等待
    $args = array(
        'timeout' => 10, // 10秒超时
        'redirection' => 5, // 最多允许5次重定向
        'user-agent' => 'WordPress/' . get_bloginfo('version') . '; ' . get_bloginfo('url'),
        'sslverify' => false, // 不验证SSL证书，提高兼容性
    );
    
    $response = wp_safe_remote_head($link_url, $args);
    
    // 检查响应
    $is_success = false;
    $status_code = 0;
    $error_message = '';
    
    if (is_wp_error($response)) {
        // 请求出错
        $is_success = false;
        $error_message = $response->get_error_message();
    } else {
        // 获取HTTP状态码
        $status_code = wp_remote_retrieve_response_code($response);
        
        // 2xx 和 3xx 状态码视为成功
        $is_success = ($status_code >= 200 && $status_code < 400);
    }
    
    // 更新检查状态
    if ($is_success) {
        // 链接正常
        update_post_meta($link_id, '_link_check_status', 'success');
        update_post_meta($link_id, '_link_failure_count', 0); // 重置失败计数
    } else {
        // 链接异常
        update_post_meta($link_id, '_link_check_status', 'failure');
        update_post_meta($link_id, '_link_status_code', $status_code);
        update_post_meta($link_id, '_link_error_message', $error_message);
        
        // 增加失败计数
        $failure_count++;
        update_post_meta($link_id, '_link_failure_count', $failure_count);
        
        // 如果连续失败3次以上，发送通知邮件给管理员
        if ($failure_count == 3) { // Only notify once when the failure count reaches 3
            sakurairo_send_link_failure_notification($link);
        }
    }
    
    // 更新检查时间
    update_post_meta($link_id, '_link_check_time', current_time('mysql'));
}

/**
 * 发送友情链接失败通知
 * 
 * @param object $link 友情链接对象
 */
function sakurairo_send_link_failure_notification($link) {
    // 获取管理员邮箱
    $admin_email = get_option('admin_email');
    if (empty($admin_email)) {
        return;
    }
    
    $blog_name = get_bloginfo('name');
    $link_name = $link->link_name;
    $link_url = $link->link_url;
    $failure_count = intval(get_post_meta($link->link_id, '_link_failure_count', true));
    $status_code = get_post_meta($link->link_id, '_link_status_code', true);
    $error_message = get_post_meta($link->link_id, '_link_error_message', true);
    
    // 邮件主题
    $subject = sprintf(__('[%s] Friend Link Check Failure: %s', 'sakurairo'), $blog_name, $link_name);
    
    // 邮件内容
    $message = sprintf(__("The friend link '%s' has failed our status check %d times.\n\n", 'sakurairo'), $link_name, $failure_count);
    $message .= sprintf(__("Link URL: %s\n", 'sakurairo'), $link_url);
    
    if ($status_code) {
        $message .= sprintf(__("HTTP Status Code: %s\n", 'sakurairo'), $status_code);
    }
    
    if ($error_message) {
        $message .= sprintf(__("Error Message: %s\n", 'sakurairo'), $error_message);
    }
    
    $message .= sprintf(__("\nLast Check Time: %s\n\n", 'sakurairo'), current_time('mysql'));
    $message .= __("You may want to check this link and consider removing it if it remains unavailable.\n", 'sakurairo');
    $message .= admin_url('link-manager.php');
    
    // 发送邮件
    wp_mail($admin_email, $subject, $message);
}

/**
 * 注册每周一次的友情链接检查计划任务
 */
function sakurairo_register_link_check_cron() {
    if (!wp_next_scheduled('sakurairo_weekly_link_check')) {
        wp_schedule_event(time(), 'weekly', 'sakurairo_weekly_link_check');
    }
}
add_action('wp', 'sakurairo_register_link_check_cron');

/**
 * 执行友情链接检查的钩子
 */
add_action('sakurairo_weekly_link_check', 'sakurairo_check_approved_links_status');

/**
 * 在主题停用时清除计划任务
 */
function sakurairo_deactivate_link_check_cron() {
    $timestamp = wp_next_scheduled('sakurairo_weekly_link_check');
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'sakurairo_weekly_link_check');
    }
}
register_deactivation_hook(__FILE__, 'sakurairo_deactivate_link_check_cron');

require_once(get_template_directory() . '/inc/link-status.php'); // 友情链接状态检测

/**
 * 返回是否应当显示文章标题。
 * 
 */
function should_show_title(): bool
{
    $id = get_the_ID();
    $use_as_thumb = get_post_meta($id, 'use_as_thumb', true); //'true','only',(default)
    return !iro_opt('patternimg')
        || !get_post_thumbnail_id($id)
        && $use_as_thumb != 'true' && !get_post_meta($id, 'video_cover', true);
}

// 管理员访问任何页面更新最后在线时间
function sakurairo_record_admin_login() {
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        if (in_array('administrator', (array) $user->roles)) {
            update_user_meta($user->ID, 'last_online', current_time('mysql'));
        }
    }
}
add_action('wp_loaded', 'sakurairo_record_admin_login');

// 添加钩子，在发布/更新文章或者评论时刷新缓存
function sakurairo_refresh_stats_on_action() {
    if (current_user_can('edit_post')) {
        delete_transient('sakurairo_site_stats');
    }
}
add_action('wp_insert_post', 'sakurairo_refresh_stats_on_action');
add_action('edit_post', 'sakurairo_refresh_stats_on_action');
add_action('wp_insert_comment', 'sakurairo_refresh_stats_on_action');

// 格式化时间差函数 - 将分钟转换为友好的文本格式
function format_time_diff($minutes) {
    if ($minutes < 1) {
        return __('Just now', 'sakurairo');
    } elseif ($minutes < 60) {
        return sprintf(_n('%d minute ago', '%d minutes ago', $minutes, 'sakurairo'), $minutes);
    } elseif ($minutes < 1440) { // 小于一天
        $hours = floor($minutes / 60);
        return sprintf(_n('%d hour ago', '%d hours ago', $hours, 'sakurairo'), $hours);
    } elseif ($minutes < 10080) { // 小于一周
        $days = floor($minutes / 1440);
        return sprintf(_n('%d day ago', '%d days ago', $days, 'sakurairo'), $days);
    } elseif ($minutes < 43200) { // 小于一个月
        $weeks = floor($minutes / 10080);
        return sprintf(_n('%d week ago', '%d weeks ago', $weeks, 'sakurairo'), $weeks);
    } else {
        $months = floor($minutes / 43200);
        return sprintf(_n('%d month ago', '%d months ago', $months, 'sakurairo'), $months);
    }
}

// 获取站点统计信息
function get_site_stats() {
    // 尝试从缓存获取数据
    $cached_stats = get_transient('sakurairo_site_stats');
    if ($cached_stats !== false) {
        return $cached_stats;
    }

    $posts_stat = get_archive_info(true);
    
    $total_posts = 0;
    $total_words = 0;
    $total_authors = 0;
    $total_comments =0;
    $total_views = 0;
    $first_post_date = null;

    foreach ($posts_stat as $year => $months) {
        foreach ($months as $month => $posts) {
            foreach ($posts as $post) {
                if ($post['meta']['type'] != "page"){
                    $total_posts++;
    
                    // 字数
                    if (isset($post['meta']['words'])) {
                        preg_match('/\d+/', $post['meta']['words'], $matches);
                        $total_words += isset($matches[0]) ? intval($matches[0]) : 0;
                    }
                }
    
                // 作者
                $authors[$post['post_author']] = true;
    
                // 评论数
                $total_comments += intval($post['comment_count']);
    
                // 浏览数
                if (isset($post['meta']['views'])) {
                    $total_views += intval($post['meta']['views']);
                }
    
                // 最早发表时间
                $post_time = strtotime($post['post_date']);
                if ($first_post_date === null || $post_time < $first_post_date) {
                    $first_post_date = $post_time;
                }
            }
        }
    }

    $total_authors = count($authors);
    // 第一篇文章的发布日期
    $first_post_date = date('Y-m-d H:i:s', $first_post_date);
    
    // 友情链接数量
    $link_count = count(get_bookmarks(['hide_invisible' => true]));
    
    // 随机友情链接
    $random_link = get_bookmarks([
        'hide_invisible' => true,
        'orderby' => 'rand',
        'limit' => 1
    ]);
    $random_link_data = !empty($random_link) ? $random_link[0] : null;
    
    // 计算从第一篇文章发布到现在的天数
    $blog_days = 0;
    if ($first_post_date) {
        $first_post_datetime = new DateTime($first_post_date);
        $now = new DateTime();
        $interval = $now->diff($first_post_datetime);
        $blog_days = $interval->days;
    }

    function get_latest_admin_online_time() {
        $admins = get_users(['role' => 'administrator']);
        $latest_time = 0;
        $latest_admin = null;
    
        foreach ($admins as $admin) {
            $last_online = get_user_meta($admin->ID, 'last_online', true);
            if ($last_online) {
                $timestamp = strtotime($last_online);
                if ($timestamp > $latest_time) {
                    $latest_time = $timestamp;
                    $latest_admin = [
                        'user' => $admin,
                        'time' => $last_online
                    ];
                }
            }
        }
    
        return $latest_admin;
    }
    
    $latest_admin_info = get_latest_admin_online_time();
    
    if (!empty($latest_admin_info)) {
        $admin_last_online = $latest_admin_info['time'];
        $last_online_timestamp = strtotime($admin_last_online);
        $current_timestamp = current_time('timestamp');
    
        // 计算以分钟为单位的时间差
        $admin_last_online_diff = max(0, floor(($current_timestamp - $last_online_timestamp) / 60));
    } else {
        // 没有记录，使用当前时间
        $admin_last_online = current_time('mysql');
        $admin_last_online_diff = 0;
    }
    
    $stats = [
        'post_count' => $total_posts,
        'comment_count' => $total_comments,
        'visitor_count' => $total_views,
        'link_count' => $link_count,
        'random_link' => $random_link_data,
        'first_post_date' => $first_post_date,
        'blog_days' => $blog_days,
        'admin_last_online' => $admin_last_online,
        'admin_last_online_diff' => $admin_last_online_diff,
        'author_count' => $total_authors,
        'total_words' => $total_words,
    ];
    
    // 缓存数据1小时
    set_transient('sakurairo_site_stats', $stats, 3600);
    
    return $stats;
}

/**
 * 修复 WordPress 搜索结果为空，返回为 200 的问题。
 * @author ivampiresp <im@ivampiresp.com>
 */
function search_404_fix_template_redirect()
{
    if (is_search()) {
        global $wp_query;

        if ($wp_query->found_posts == 0) {
            status_header(404);
        }
    }
}

add_action('template_redirect', 'search_404_fix_template_redirect');

// 给上传图片增加时间戳
add_filter('wp_handle_upload_prefilter', function ($file) {
    $file['name'] = time() . '-' . $file['name'];
    return $file;
});

/**
 * 在后台评论列表中添加IP地理位置信息列
 *
 * @param string[] $columns 列表标题的标签
 * @return void
 */
function iro_add_location_to_comments_columns($columns)
{
    $columns['iro_ip_location'] = __('Location', 'sakurairo');
    return $columns;
}

/**
 * 将IP地理位置信息输出到评论列表中
 *
 * @param string $column_name 列表标题的标签
 * @param int $comment_id 评论ID
 * @return void
 */
function iro_output_ip_location_columns($column_name, $comment_id)
{
    switch ($column_name) {
        case "iro_ip_location":
            echo \Sakura\API\IpLocationParse::getIpLocationByCommentId($comment_id);
            break;
    }
}
if (iro_opt('show_location_in_manage')) {
    add_filter('manage_edit-comments_columns', 'iro_add_location_to_comments_columns');
    add_action('manage_comments_custom_column', 'iro_output_ip_location_columns', 10, 2);
}

function iterator_to_string(Iterator $iterator): string
{
    $content = '';
    foreach ($iterator as $item) {
        $content .= $item;
    }
    return $content;
}

/*GET参数操作*/
function iro_action_operator()
{
    if (!isset($_GET['iro_act']) || empty($_GET['iro_act'])) {
        return;
    }

    if (!is_admin() || !current_user_can('manage_options')) {
        echo __("Access denied.", "sakurairo");
        return;
    }

    $direct_info = sanitize_key($_GET['iro_act']);

    switch($direct_info){
        case 'bangumi' :
            $direct_url = 'https://api.bgm.tv/v0/users/' . (iro_opt('bangumi_id') ?: '944883') . '/collections';
            header("Location: $direct_url", true, 302);
            break;

        case 'mal' :
            switch (iro_opt('my_anime_list_sort')) {
                case 1: // Status and Last Updated
                    $sort = 'order=16&order2=5&status=7';
                    break;
                case 2: // Last Updated
                    $sort = 'order=5&status=7';
                    break;
                case 3: // Status
                    $sort = 'order=16&status=7';
                    break;
            }
            $direct_url = 'https://myanimelist.net/animelist/' . (iro_opt('my_anime_list_username') ?: 'username') . '/load.json?' . $sort;
            header("Location: $direct_url", true, 302);
            break;
        
        case 'steam_library' :
            $direct_url = 'https://api.steampowered.com/IPlayerService/GetOwnedGames/v1/?key=' . iro_opt('steam_key') .'&steamid=' . iro_opt('steam_id') .'&include_appinfo=1&include_played_free_games=1&include_free_games=1';
            header("Location: $direct_url", true, 302);
            break;

        case 'playlist' :
            $direct_url = rest_url('sakura/v1/meting/aplayer') . '?_wpnonce=' . wp_create_nonce('wp_rest') . '&server=' . (iro_opt('aplayer_server') ?: 'netease') . '&type=playlist&id=' . (iro_opt('aplayer_playlistid') ?: '5380675133');
            header("Location: $direct_url", true, 302);
            break;

        case 'gallery_init':
            include_once('inc/classes/gallery.php');
            $gallery = new Sakura\API\gallery();
            echo $gallery->init();
            echo 'Done!';
            break;

        case 'gallery_webp':
            include_once('inc/classes/gallery.php');
            $gallery = new Sakura\API\gallery();
            echo $gallery->webp();
            echo 'Done!';
            break;
        case 'del_exist_theme':
            $current_theme_folder = basename(get_template_directory());
            if ($current_theme_folder != 'Sakurairo') {
                if (!function_exists('WP_Filesystem')) {
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                }
                WP_Filesystem();
                global $wp_filesystem;
                $wp_filesystem->delete(get_theme_root() . '/Sakurairo', true);
                wp_redirect(admin_url(), 302); //重载theme_folder_check_on_admin_init流程
            } else {
                wp_redirect(admin_url(), 302);
                return;
            }
    }
}
iro_action_operator();
