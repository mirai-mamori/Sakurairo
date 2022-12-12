<?php

/**
 * iro functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package iro
 */


define('IRO_VERSION', wp_get_theme()->get('Version'));
define('INT_VERSION', '17.6.0');
define('BUILD_VERSION', '2');

//Option-Framework

require get_template_directory() . '/opt/option-framework.php';

if (!function_exists('iro_opt')) {
    $GLOBALS['iro_options'] = get_option('iro_options');
    function iro_opt($option = '', $default = null)
    {
        return $GLOBALS['iro_options'][$option] ?? $default;
    }
}
$shared_lib_basepath = iro_opt('shared_library_basepath') ? get_template_directory_uri() : (iro_opt('lib_cdn_path','https://fastly.jsdelivr.net/gh/mirai-mamori/Sakurairo@'). IRO_VERSION);
$core_lib_basepath =  iro_opt('core_library_basepath') ? get_template_directory_uri() : (iro_opt('lib_cdn_path','https://fastly.jsdelivr.net/gh/mirai-mamori/Sakurairo@'). IRO_VERSION);
//Update-Checker

require 'update-checker/update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
function UpdateCheck($url,$flag = 'Sakurairo'){
    return PucFactory::buildUpdateChecker(
        $url,
        __FILE__,
        $flag
    );
}
switch(iro_opt('iro_update_source')){
    case 'github':
        $iroThemeUpdateChecker = UpdateCheck('https://github.com/mirai-mamori/Sakurairo','unique-plugin-or-theme-slug');
        break;
    case 'upyun':
        $iroThemeUpdateChecker = UpdateCheck('https://update.maho.cc/jsdelivr.json');
        break;
    case 'official_building':
        $iroThemeUpdateChecker = UpdateCheck('https://update.maho.cc/'.iro_opt('iro_update_channel').'/check.json');
}
//ini_set('display_errors', true);
//error_reporting(E_ALL);
error_reporting(E_ALL & ~E_NOTICE);

if (!function_exists('akina_setup'))
{
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
        register_nav_menus(array(
            'primary' => __('Nav Menus', 'sakurairo'), //导航菜单
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        /*
         * Enable support for Post Formats.
         * See https://developer.wordpress.org/themes/functionality/post-formats/
         */
        add_theme_support('post-formats', array(
            'aside',
            'image',
            'status',
        ));

        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('akina_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));
        /**
         * 废弃过时的wp_title
         * @seealso https://make.wordpress.org/core/2015/10/20/document-title-in-4-4/
         */
        add_theme_support( 'title-tag' );

        add_filter('pre_option_link_manager_enabled', '__return_true');

        // 优化代码
        //去除头部冗余代码
        remove_action('wp_head', 'feed_links_extra', 3);
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'index_rel_link');
        remove_action('wp_head', 'start_post_rel_link', 10, 0);
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wp_generator'); //隐藏wordpress版本
        remove_filter('the_content', 'wptexturize'); //取消标点符号转义

        //remove_action('rest_api_init', 'wp_oembed_register_route');
        //remove_filter('rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4);
        //remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
        //remove_filter('oembed_response_data', 'get_oembed_response_data_rich', 10, 4);
        //remove_action('wp_head', 'wp_oembed_add_discovery_links');
        //remove_action('wp_head', 'wp_oembed_add_host_js');
        remove_action('template_redirect', 'rest_output_link_header', 11, 0);

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
        if (!function_exists('disable_emojis_tinymce')) 
        {
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
};
add_action('after_setup_theme', 'akina_setup');

//说说页面
function shuoshuo_custom_init()
{
    $labels = array(
        'name' => __("Ideas", "sakurairo"),
        'singular_name' => __("Idea", "sakurairo"),
        'add_new' => __("Publish New Idea", "sakurairo"),
        'add_new_item' => __("Publish New Idea", "sakurairo"),
        'edit_item' => __("Edit Idea", "sakurairo"),
        'new_item' => __("New Idea", "sakurairo"),
        'view_item' => __("View Idea", "sakurairo"),
        'search_items' => __("Search Idea", "sakurairo"),
        'not_found' => __("Not Found Idea", "sakurairo"),
        'not_found_in_trash' => __("No Idea in the Trash", "sakurairo"),
        'parent_item_colon' => '',
        'menu_name' => __("Ideas", "sakurairo")
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array(
            'title',
            'editor',
            'author'
        )
    );
    register_post_type('shuoshuo', $args);
}
add_action('init', 'shuoshuo_custom_init');

function admin_lettering()
{
    echo '<style>body{font-family: Microsoft YaHei;}</style>';
}
add_action('admin_head', 'admin_lettering');

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

    if (iro_opt('smoothscroll_option')) {
        wp_enqueue_script('SmoothScroll', $shared_lib_basepath. '/js/smoothscroll.js', array(), IRO_VERSION . iro_opt('cookie_version', ''), true);
    }

    wp_enqueue_style('saukra-css',  $core_lib_basepath.'/style.css', array(), IRO_VERSION);
    if (!is_home()){
        //非主页的资源
        wp_enqueue_style('entry-content',     
     $core_lib_basepath.'/css/theme/'.(iro_opt('entry_content_style') == 'sakurairo' ?'sakura' : 'github').'.css',
    array(), IRO_VERSION);
        wp_enqueue_script('app-page', $core_lib_basepath . '/js/page.js', array('app','polyfills'), IRO_VERSION, true);
    }
    wp_enqueue_script('app', $core_lib_basepath . '/js/app.js', array('polyfills'), IRO_VERSION, true);
    wp_enqueue_script('polyfills', $core_lib_basepath . '/js/polyfill.js', array(), IRO_VERSION, true);
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    //前端脚本本地化
    if (get_locale() != 'zh_CN') {
        wp_localize_script('app', '_sakurairoi18n', array(
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
        ));
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
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * function update
 */
require get_template_directory() . '/inc/theme_plus.php';
require get_template_directory() . '/inc/categories-images.php';

//Comment Location Start
function convertip($ip)
{
    if (empty($ip)) $ip = get_comment_author_IP();
    $ch = curl_init();
    $url = 'https://api.nmxc.ltd/ip/' . $ip;
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt ($ch, CURLOPT_URL, 'http://ip.taobao.com/outGetIpInfo?accessKey=alibaba-inc&ip='.$ip);  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    $result = null;
    $result = json_decode($file_contents, true);
    if ($result && $result['code'] != 0) {
        return "未知";
    }
    if ($result['data']['country'] != '中国') {
        return $result['data']['country'];
    }
    return $result['data']['country'] . '&nbsp;·&nbsp;' . $result['data']['region'] . '&nbsp;·&nbsp;' . $result['data']['city'];
}
//Comment Location End

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
                            <a href="<?php comment_author_url(); ?>" target="_blank" rel="nofollow"><?php echo str_replace('src=', 'src="' . iro_opt('load_in_svg') . '" onerror="imgError(this,1)" data-src=', get_avatar($comment->comment_author_email, '80', '', get_comment_author(), array('class' => array('lazyload')))); ?></a>
                        </div>
                        <div class="commentinfo">
                            <section class="commeta">
                                <div class="left">
                                    <h4 class="author"><a href="<?php comment_author_url(); ?>" target="_blank" rel="nofollow"><?php echo get_avatar($comment->comment_author_email, '24', '', get_comment_author()); ?><span class="bb-comment isauthor" title="<?php _e('Author', 'sakurairo'); ?>"><?php _e('Blogger', 'sakurairo'); /*博主*/ ?></span> <?php comment_author(); ?> <?php echo get_author_class($comment->comment_author_email, $comment->user_id); ?></a></h4>
                                </div>
                                <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                                <div class="right">
                                    <div class="info"><time datetime="<?php comment_date('Y-m-d'); ?>"><?php echo poi_time_since(strtotime($comment->comment_date_gmt), true); //comment_date(get_option('date_format'));  
                                                                                                        ?></time><?= siren_get_useragent($comment->comment_agent); ?><?php echo mobile_get_useragent_icon($comment->comment_agent); ?>&nbsp;<?php if (iro_opt('comment_location')) {
                                                                                                                                                                                                                                                        _e('Location', 'sakurairo'); /*来自*/ ?>: <?php echo convertip(get_comment_author_ip());
                                                                                                                                                                                                                                                                                                                                                                                                                } ?>
                                    <?php if (current_user_can('manage_options') and (wp_is_mobile() == false)) {
                                        $comment_ID = $comment->comment_ID;
                                        $i_private = get_comment_meta($comment_ID, '_private', true);
                                        $flag = null;
                                        $flag .= ' <i class="fa fa-snowflake-o" aria-hidden="true"></i> <a href="javascript:;" data-actionp="set_private" data-idp="' . get_comment_id() . '" id="sp" class="sm">' . __("Private", "sakurairo") . ': <span class="has_set_private">';
                                        if (!empty($i_private)) {
                                            $flag .= __("Yes", "sakurairo") . ' <i class="fa fa-lock" aria-hidden="true"></i>';
                                        } else {
                                            $flag .= __("No", "sakurairo") . ' <i class="fa fa-unlock" aria-hidden="true"></i>';
                                        }
                                        $flag .= '</span></a>';
                                        $flag .= edit_comment_link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i> ' . __("Edit", "mashiro"), ' <span style="color:rgba(0,0,0,.35)">', '</span>');
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
    $author_count = count($wpdb->get_results(
        "SELECT comment_ID as author_count FROM $wpdb->comments WHERE comment_author_email = '$comment_author_email' "
    ));
    # 等级梯度
    $lv_array = [0, 5, 10, 20, 40, 80, 160];
    $Lv = 0;
    foreach ($lv_array as $key => $value) {
        if ($value >= $author_count) break;
        $Lv = $key;
    }

    // $Lv = $author_count < 5 ? 0 : ($author_count < 10 ? 1 : ($author_count < 20 ? 2 : ($author_count < 40 ? 3 : ($author_count < 80 ? 4 : ($author_count < 160 ? 5 : 6)))));
    echo "<span class=\"showGrade{$Lv}\" title=\"Lv{$Lv}\"><img src=\"".iro_opt('vision_resource_basepath','https://s.nmxc.ltd/sakurairo_vision/@2.5/')."comment_level/level_{$Lv}.svg\" style=\"height: 1.5em; max-height: 1.5em; display: inline-block;\"></span>";
}

/**
 * post views
 */
function restyle_text($number)
{
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
    if (!is_singular()) return;
    
    global $post;
    $post_id = intval($post->ID);
    if (!$post_id) return;
    $views = (int) get_post_meta($post_id, 'views', true);
    if (!update_post_meta($post_id, 'views', ($views + 1))) {
        add_post_meta($post_id, 'views', 1, true);
    }
}

add_action('get_header', 'set_post_views');

function get_post_views($post_id)
{
    if (iro_opt('statistics_api') == 'wp_statistics') {
        if (!function_exists('wp_statistics_pages')) {
            return __('Please install pulgin <a href="https://wordpress.org/plugins/wp-statistics/" target="_blank">WP-Statistics</a>', 'sakurairo');
        }
        return restyle_text(wp_statistics_pages('total', 'uri', $post_id));
    } 
    $views = get_post_meta($post_id, 'views', true);
    return $views == '' ? 0 :restyle_text($views);
}

function is_webp(): bool
{
    return (isset($_COOKIE['su_webp']) || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp')));
}

/*
 * 友情链接
 */
function get_the_link_items($id = null)
{
    $bookmarks = get_bookmarks('orderby=date&category=' . $id);
    $output = '';
    if (!empty($bookmarks)) {
        $output .= '<ul class="link-items fontSmooth">';
        foreach ($bookmarks as $bookmark) {
            if (empty($bookmark->link_description)) {
                $bookmark->link_description = __('This guy is so lazy ╮(╯▽╰)╭', 'sakurairo');
            }

            if (empty($bookmark->link_image)) {
                $bookmark->link_image = 'https://s.nmxc.ltd/sakurairo_vision/@2.5/basic/friendlink.jpg';
            }

            $output .= '<li class="link-item"><a class="link-item-inner effect-apollo" href="' . $bookmark->link_url . '" title="' . $bookmark->link_description . '" target="_blank" rel="friend"><img class="lazyload" onerror="imgError(this,1)" data-src="' . $bookmark->link_image . '" src="' . iro_opt('load_in_svg') . '"></br><span class="sitename">' . $bookmark->link_name . '</span><div class="linkdes">' . $bookmark->link_description . '</div></a></li>';
        }
        $output .= '</ul>';
    }
    return $output;
}

function get_link_items()
{
    $linkcats = get_terms('link_category');
    $result = null;
    if (empty($linkcats)) return get_the_link_items();    
    foreach ($linkcats as $linkcat) {
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
function gravatar_cn(string $url):string
{
    $gravatar_url = array('0.gravatar.com/avatar', '1.gravatar.com/avatar', '2.gravatar.com/avatar', 'secure.gravatar.com/avatar');
    return str_replace($gravatar_url, iro_opt('gravatar_proxy'), $url);
}
if (iro_opt('gravatar_proxy')) {
    add_filter('get_avatar_url', 'gravatar_cn', 4);
}

/*
 * 自定义默认头像
 */
add_filter('avatar_defaults', 'mytheme_default_avatar');

function mytheme_default_avatar($avatar_defaults)
{
    //$new_avatar_url = get_template_directory_uri() . '/images/default_avatar.png';
    $new_avatar_url = 'https://cn.gravatar.com/avatar/b745710ae6b0ce9dfb13f5b7c0956be1';
    $avatar_defaults[$new_avatar_url] = 'Default Avatar';
    return $avatar_defaults;
}

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
    $upload_path = '';
    $upload_url_path = iro_opt('image_cdn');

    if (empty($upload_path) || 'wp-content/uploads' == $upload_path) {
        $uploads['basedir'] = WP_CONTENT_DIR . '/uploads';
    } elseif (0 !== strpos($upload_path, ABSPATH)) {
        $uploads['basedir'] = path_join(ABSPATH, $upload_path);
    } else {
        $uploads['basedir'] = $upload_path;
    }

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
    //unregister_widget('WP_Widget_Text');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    //unregister_widget('WP_Widget_Recent_Comments');
    //unregister_widget('WP_Widget_RSS');
    //unregister_widget('WP_Widget_Tag_Cloud');
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
    add_theme_support('infinite-scroll', array(
        'container' => 'main',
        'render' => 'akina_infinite_scroll_render',
        'footer' => 'page',
    ));

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
// 下载按钮
function download($atts, $content = null)
{
    return '<a class="download" href="' . $content . '" rel="external"
target="_blank" title="'.__("Download Link","sakurairo").'">
<span><i class="iconfont down icon-pulldown"></i>Download</span></a>';
}
add_shortcode("download", "download");

add_action('after_wp_tiny_mce', 'bolo_after_wp_tiny_mce');
function bolo_after_wp_tiny_mce($mce_settings)
{
        ?>
        <script type="text/javascript">
            QTags.addButton('download', '下载按钮', "[download]下载地址[/download]");

            function bolo_QTnextpage_arg1() {}
        </script>
    <?php }

/*
 * 后台登录页
 * @M.J
 */
//Login Page style
function custom_login()
{
    require get_template_directory() . '/inc/login_addcss.php';
    //echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('template_directory') . '/inc/login.css" />'."\n";
    echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/inc/login.css?' . IRO_VERSION . '" />' . "\n";
    //echo '<script type="text/javascript" src="'.get_bloginfo('template_directory').'/js/jquery.min.js"></script>'."\n";
}

add_action('login_head', 'custom_login');

//Login Page Title
function custom_headertitle($title)
{
    return get_bloginfo('name');
}
add_filter('login_headertext', 'custom_headertitle');

//Login Page Link
function custom_loginlogo_url($url)
{
    return esc_url(home_url('/'));
}
add_filter('login_headerurl', 'custom_loginlogo_url');

//Login Page Footer
function custom_html()
{
    $loginbg = iro_opt('login_background') ?: iro_opt('vision_resource_basepath','https://s.nmxc.ltd/sakurairo_vision/@2.5/').'series/login_background.webp'; ?>
        <script type="text/javascript">
            document.body.insertAdjacentHTML("afterbegin", "<div class=\"loading\"><img src=\"<?=iro_opt('vision_resource_basepath','https://s.nmxc.ltd/sakurairo_vision/@2.5/')?>basic/login_loading.gif\" width=\"58\" height=\"10\"></div>");
            document.head.insertAdjacentHTML("afterbegin", "<style>.show{opacity:1;}.hide{opacity:0;transition: opacity 400ms;}</style>");
            const loading = document.querySelector(".loading"),
             src = "<?= $loginbg ?>",
                afterLoaded = () => {
                    document.body.style.backgroundImage = `url(${src})`
                    loading.classList.add("hide");
                    loading.classList.remove("show");
                    loading.addEventListener("transitionend", () => {
                        loading.remove()
                    });
                },
                img = document.createElement('img')
            img.src = src
            img.addEventListener('load',afterLoaded,{once:true})
            <?php //3秒钟内加载不到图片也移除加载中提示
            ?>
            setTimeout(afterLoaded, 3000)
            document.addEventListener("DOMContentLoaded", ()=>{
        document.querySelector("h1 a").style.backgroundImage = "url('<?= iro_opt('login_logo_img')?>')";
        forgetmenot = document.querySelector(".forgetmenot");
        if (forgetmenot){
            forgetmenot.outerHTML = '<p class="forgetmenot"><?=__("Remember me","sakurairo")?><input name="rememberme" id="rememberme" value="forever" type="checkbox"><label for="rememberme" style="float: right;margin-top: 5px;transform: scale(2);margin-right: -10px;"></label></p>';
        }
        const captchaimg = document.getElementById("captchaimg");
        captchaimg && captchaimg.addEventListener("click",(e)=>{
            fetch("<?= rest_url('sakura/v1/captcha/create')?>")
            .then(resp=>resp.json())
            .then(json=>{
                e.target.src = json["data"];
                document.querySelector("input[name=\'timestamp\']").value = json["time"];
                document.querySelector("input[name=\'id\']").value = json["id"];
            });
        })
    }, false);
        </script>
    <?php
}
add_action('login_footer', 'custom_html');

//Login message
//* Add custom message to WordPress login page
function smallenvelop_login_message($message)
{
    return empty($message) ? '<p class="message"><strong>You may try 3 times for every 5 minutes!</strong></p>' : $message;
}
//add_filter( 'login_message', 'smallenvelop_login_message' );

//Fix password reset bug </>
function resetpassword_message_fix($message)
{
    return str_replace(['>','<'], '', $message);
    // $message = str_replace('<', '', $message);
    // $message = str_replace('>', '', $message);
    // return $message;
}
add_filter('retrieve_password_message', 'resetpassword_message_fix');

//Fix register email bug </>
function new_user_message_fix($message)
{
    $show_register_ip = '注册IP | Registration IP: ' . get_the_user_ip() . ' (' . convertip(get_the_user_ip()) . ")\r\n\r\n如非本人操作请忽略此邮件 | Please ignore this email if this was not your operation.\r\n\r\n";
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
        $subject = '你在 [' . get_option("blogname") . '] 的留言有了回应';
        $message = '
      <div style="background: white;
      width: 95%;
      max-width: 800px;
      margin: auto auto;
      border-radius: 5px;
      border: ' . iro_opt('theme_skin') . ' 1px solid;
      overflow: hidden;
      -webkit-box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.12);
      box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.18);">
        <header style="overflow: hidden;">
            <img style="width:100%;z-index: 666;" src="' . iro_opt('mail_img') . '">
        </header>
        <div style="padding: 5px 20px;">
        <p style="position: relative;
        color: white;
        float: left;
        z-index: 999;
        background: ' . iro_opt('theme_skin') . ';
        padding: 5px 30px;
        margin: -25px auto 0 ;
        box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.30)">Dear&nbsp;' . trim(get_comment($parent_id)->comment_author) . '</p>
        <br>
        <h3>您有一条来自<a style="text-decoration: none;color: ' . iro_opt('theme_skin') . ' " target="_blank" href="' . home_url() . '/">' . get_option("blogname") . '</a>的回复</h3>
        <br>
        <p style="font-size: 14px;">您在文章《' . get_the_title($comment->comment_post_ID) . '》上发表的评论：</p>
        <div style="border-bottom:#ddd 1px solid;border-left:#ddd 1px solid;padding-bottom:20px;background-color:#eee;margin:15px 0px;padding-left:20px;padding-right:20px;border-top:#ddd 1px solid;border-right:#ddd 1px solid;padding-top:20px">'
            . trim(get_comment($parent_id)->comment_content) . '</div>
        <p style="font-size: 14px;">' . trim($comment->comment_author) . ' 给您的回复如下：</p>
        <div style="border-bottom:#ddd 1px solid;border-left:#ddd 1px solid;padding-bottom:20px;background-color:#eee;margin:15px 0px;padding-left:20px;padding-right:20px;border-top:#ddd 1px solid;border-right:#ddd 1px solid;padding-top:20px">'
            . trim($comment->comment_content) . '</div>

      <div style="text-align: center;">
          <img src="'.iro_opt('vision_resource_basepath','https://s.nmxc.ltd/sakurairo_vision/@2.5/').'basic/comment-mail.png" alt="hr" style="width:100%;
                                                                                                  margin:5px auto 5px auto;
                                                                                                  display: block;">
          <a style="text-transform: uppercase;
                      text-decoration: none;
                      font-size: 14px;
                      border: 2px solid #6c7575;
                      color: #2f3333;
                      padding: 10px;
                      display: inline-block;
                      margin: 10px auto 0; " target="_blank" href="' . htmlspecialchars(get_comment_link($parent_id)) . '">点击查看回复的完整內容</a>
      </div>
        <p style="font-size: 12px;text-align: center;color: #999;">本邮件为系统自动发出，请勿直接回复<br>
        &copy; ' . date('Y') . ' ' . get_option("blogname") . '</p>
      </div>
    </div>
';
        $message = convert_smilies($message);
        $message = str_replace('{{', '<img src="'.iro_opt('vision_resource_basepath','https://s.nmxc.ltd/sakurairo_vision/@2.5/').'/smilies/bilipng/emoji_', $message);
        $message = str_replace('}}', '.png" alt="emoji" style="height: 2em; max-height: 2em;">', $message);

        $message = str_replace('{UPLOAD}', 'https://i.loli.net/', $message);
        $message = str_replace('[/img][img]', '[/img^img]', $message);

        $message = str_replace('[img]', '<img src="', $message);
        $message = str_replace('[/img]', '" style="width:80%;display: block;margin-left: auto;margin-right: auto;">', $message);

        $message = str_replace('[/img^img]', '" style="width:80%;display: block;margin-left: auto;margin-right: auto;"><img src="', $message);
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
        // 选择面版
        $return_smiles = $return_smiles . '<span title="' . $tieba_Name . '" onclick="grin(' . "'" . $tieba_Name . "'" . ',type = \'tieba\')"><img loading="lazy" src="'.iro_opt('vision_resource_basepath','https://s.nmxc.ltd/sakurairo_vision/@2.5/').'smilies/' . $tiebaimgdir . 'icon_' . $tieba_Name . $smiliesgs . '" /></span>';
        // 正文转换
        $wpsmiliestrans['::' . $tieba_Name . '::'] = '<span title="' . $tieba_Name . '" onclick="grin(' . "'" . $tieba_Name . "'" . ',type = \'tieba\')"><img loading="lazy" src="'.iro_opt('vision_resource_basepath','https://s.nmxc.ltd/sakurairo_vision/@2.5/').'smilies/' . $tiebaimgdir . 'icon_' . $tieba_Name . $smiliesgs . '" /></span>';
    }
    return $return_smiles;
}
push_tieba_smilies();

function tieba_smile_filter($content)
{
    global $wpsmiliestrans;
    $content =  str_replace(array_keys($wpsmiliestrans), $wpsmiliestrans, $content);
    return $content;
}
add_filter('the_content', 'tieba_smile_filter'); //替换文章关键词
add_filter('comment_text', 'tieba_smile_filter'); //替换评论关键词

function push_emoji_panel()
{
    return '
        <a class="emoji-item">(⌒▽⌒)</a>
        <a class="emoji-item">（￣▽￣）</a>
        <a class="emoji-item">(=・ω・=)</a>
        <a class="emoji-item">(｀・ω・´)</a>
        <a class="emoji-item">(〜￣△￣)〜</a>
        <a class="emoji-item">(･∀･)</a>
        <a class="emoji-item">(°∀°)ﾉ</a>
        <a class="emoji-item">(￣3￣)</a>
        <a class="emoji-item">╮(￣▽￣)╭</a>
        <a class="emoji-item">(´_ゝ｀)</a>
        <a class="emoji-item">←_←</a>
        <a class="emoji-item">→_→</a>
        <a class="emoji-item">(&lt;_&lt;)</a>
        <a class="emoji-item">(&gt;_&gt;)</a>
        <a class="emoji-item">(;¬_¬)</a>
        <a class="emoji-item">("▔□▔)/</a>
        <a class="emoji-item">(ﾟДﾟ≡ﾟдﾟ)!?</a>
        <a class="emoji-item">Σ(ﾟдﾟ;)</a>
        <a class="emoji-item">Σ(￣□￣||)</a>
        <a class="emoji-item">(’；ω；‘)</a>
        <a class="emoji-item">（/TДT)/</a>
        <a class="emoji-item">(^・ω・^ )</a>
        <a class="emoji-item">(｡･ω･｡)</a>
        <a class="emoji-item">(●￣(ｴ)￣●)</a>
        <a class="emoji-item">ε=ε=(ノ≧∇≦)ノ</a>
        <a class="emoji-item">(’･_･‘)</a>
        <a class="emoji-item">(-_-#)</a>
        <a class="emoji-item">（￣へ￣）</a>
        <a class="emoji-item">(￣ε(#￣)Σ</a>
        <a class="emoji-item">ヽ(‘Д’)ﾉ</a>
        <a class="emoji-item">（#-_-)┯━┯</a>
        <a class="emoji-item">(╯°口°)╯(┴—┴</a>
        <a class="emoji-item">←◡←</a>
        <a class="emoji-item">( ♥д♥)</a>
        <a class="emoji-item">_(:3」∠)_</a>
        <a class="emoji-item">Σ&gt;―(〃°ω°〃)♡→</a>
        <a class="emoji-item">⁄(⁄ ⁄•⁄ω⁄•⁄ ⁄)⁄</a>
        <a class="emoji-item">(╬ﾟдﾟ)▄︻┻┳═一</a>
        <a class="emoji-item">･*･:≡(　ε:)</a>
        <a class="emoji-item">(笑)</a>
        <a class="emoji-item">(汗)</a>
        <a class="emoji-item">(泣)</a>
        <a class="emoji-item">(苦笑)</a>
    ';
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
        // 选择面版
        $return_smiles = $return_smiles . '<span title="' . $smilies_Name . '" onclick="grin(' . "'" . $smilies_Name . "'" . ',type = \'Math\')"><img loading="lazy" src="'.iro_opt('vision_resource_basepath','https://s.nmxc.ltd/sakurairo_vision/@2.5/').'smilies/' . $biliimgdir . 'emoji_' . $smilies_Name . $smiliesgs . '" /></span>';
        // 正文转换
        $bilismiliestrans['{{' . $smilies_Name . '}}'] = '<span title="' . $smilies_Name . '" onclick="grin(' . "'" . $smilies_Name . "'" . ',type = \'Math\')"><img loading="lazy" src="'.iro_opt('vision_resource_basepath','https://s.nmxc.ltd/sakurairo_vision/@2.5/').'smilies/' . $biliimgdir . 'emoji_' . $smilies_Name . $smiliesgs . '" /></span>';
    }
    return $return_smiles;
}
push_bili_smilies();

function bili_smile_filter($content)
{
    global $bilismiliestrans;
    $content =  str_replace(array_keys($bilismiliestrans), $bilismiliestrans, $content);
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
    $content = str_replace('{{', '<img src="'.iro_opt('vision_resource_basepath','https://s.nmxc.ltd/sakurairo_vision/@2.5/').'smilies/' . $biliimgdir, $content);
    $content = str_replace('}}', $smilesgs . '" alt="emoji" style="height: 2em; max-height: 2em;">', $content);
    $content =  str_replace('[img]', '<img src="', $content);
    $content =  str_replace('[/img]', '" style="display: block;margin-left: auto;margin-right: auto;">', $content);
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

// 显示访客当前 IP
function get_the_user_ip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        //check ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //to check ip is pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // 简略版
    // $ip = $_SERVER['HTTP_CLIENT_IP'] ?: ($_SERVER['HTTP_X_FORWARDED_FOR'] ?: $_SERVER['REMOTE_ADDR']);
    return apply_filters('wpb_get_ip', $ip);
}

add_shortcode('show_ip', 'get_the_user_ip');

/*歌词*/
function hero_get_lyric()
{
    /** These are the lyrics to Hero */
    $lyrics = "";

    // Here we split it into lines
    $lyrics = explode("\n", $lyrics);

    // And then randomly choose a line
    return wptexturize($lyrics[mt_rand(0, count($lyrics) - 1)]);
}

// This just echoes the chosen line, we'll position it later
function hello_hero()
{
    $chosen = hero_get_lyric();
    echo $chosen;
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
        $i_private = get_comment_meta($comment_ID, '_private', true);
        echo empty($i_private) ? '是' : '否';
    }
    die;
}

//时间序列
function memory_archives_list()
{
    // 始终为true, 为什么要这么做呢 
    if (true) {
        $output = '<div id="archives"><p style="text-align:right;">[<span id="al_expand_collapse">' . __("All expand/collapse", "sakurairo") /*全部展开/收缩*/ . '</span>]<!-- (注: 点击月份可以展开)--></p>';
        $the_query = new WP_Query('posts_per_page=-1&ignore_sticky_posts=1&post_type=post'); //update: 加上忽略置顶文章
        $year = 0;
        $mon = 0;
        $i = 0;
        $j = 0;
        while ($the_query->have_posts()) : $the_query->the_post();
            $year_tmp = get_the_time('Y');
            $mon_tmp = get_the_time('m');
            $y = $year;
            $m = $mon;
            if ($mon != $mon_tmp && $mon > 0) {
                $output .= '</ul></li>';
            }

            if ($year != $year_tmp && $year > 0) {
                $output .= '</ul>';
            }

            if ($year != $year_tmp) {
                $year = $year_tmp;
                $output .= '<h3 class="al_year">' . $year . __(" ", "year", "sakurairo") . /*年*/ ' </h3><ul class="al_mon_list">'; //输出年份
            }
            if ($mon != $mon_tmp) {
                $mon = $mon_tmp;
                $output .= '<li class="al_li"><span class="al_mon"><span style="color:' . iro_opt('theme_skin') . ';">' . get_the_time('M') . '</span> (<span id="post-num"></span>' . __(" post(s)", "sakurairo") /*篇文章*/ . ')</span><ul class="al_post_list">'; //输出月份
            }
            $output .= '<li>' . '<a href="' . get_permalink() . '"><span style="color:' . iro_opt('theme_skin') . ';">' /*get_the_time('d'.__(" ","sakurairo")) 日*/ . '</span>' . get_the_title() . ' <span>(' . get_post_views(get_the_ID()) . ' <span class="fa fa-fire" aria-hidden="true"></span> / ' . get_comments_number('0', '1', '%') . ' <span class="fa fa-commenting" aria-hidden="true"></span>)</span></a></li>'; //输出文章日期和标题
        endwhile;
        wp_reset_postdata();
        $output .= '</ul></li></ul> <!--<ul class="al_mon_list"><li><ul class="al_post_list" style="display: block;"><li>博客已经萌萌哒运行了<span id="monitorday"></span>天</li></ul></li></ul>--></div>';
        #update_option('memory_archives_list', $output);
    }
    echo $output;
}

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

function excerpt_length($exp)
{
    if (!function_exists('mb_substr')) {
        $exp = GBsubstr($exp, 0, 80);
    } else {
        /*
         * To use mb_substr() function, you should uncomment "extension=php_mbstring.dll" in php.ini
         */
        $exp = mb_substr($exp, 0, 80);
    }
    return $exp;
}
add_filter('the_excerpt', 'excerpt_length');

/*
 * 后台路径
 */
/*
add_filter('site_url',  'wpadmin_filter', 10, 3);
function wpadmin_filter( $url, $path, $orig_scheme ) {
$old  = array( "/(wp-admin)/");
$admin_dir = WP_ADMIN_DIR;
$new  = array($admin_dir);
return preg_replace( $old, $new, $url, 1);
}
 */

function admin_ini()
{
    wp_enqueue_style('admin-styles-fix-icon', get_site_url() . '/wp-includes/css/dashicons.css');
    wp_enqueue_style('cus-styles-fit', get_template_directory_uri() . '/css/dashboard-fix.css');
}
add_action('admin_enqueue_scripts', 'admin_ini');

/*
 * 后台通知
 */
function scheme_tip()
{
    switch(get_user_locale(get_current_user_id())){
        case 'zh_CN':
            $msg = '<b>试一试新后台界面<a href="/wp-admin/profile.php">配色方案</a>吧？</b>';
            break;
        case 'zh_TW':
            $msg = '<b>試一試新後台界面<a href="/wp-admin/profile.php">色彩配置</a>吧？</b>';
            break;
        case 'ja-JP':
            $msg = '<b>新しい<a href="/wp-admin/profile.php">管理画面の配色</a>を試しますか？</b>';
            break;
        default:
            $msg = '<b>Why not try the new admin dashboard color scheme <a href="/wp-admin/profile.php">here</a>?</b>';
    }
    $user_id = get_current_user_id();
    if (!get_user_meta($user_id, 'scheme-tip-dismissed' . BUILD_VERSION)) {
        echo '<div class="notice notice-success is-dismissible" id="scheme-tip"><p><b>' . $msg . '</b></p></div>';
    }
}

add_action('admin_notices', 'scheme_tip');

function scheme_tip_dismissed()
{
    $user_id = get_current_user_id();
    if (isset($_GET['scheme-tip-dismissed' . BUILD_VERSION])) {
        add_user_meta($user_id, 'scheme-tip-dismissed' . BUILD_VERSION, 'true', true);
    }
}
add_action('admin_init', 'scheme_tip_dismissed');

//dashboard scheme
function dash_scheme($key, $name, $col1, $col2, $col3, $col4, $base, $focus, $current, $rules = "")
{
    $hash = "color_1=" . str_replace("#", "", $col1) .
        "&color_2=" . str_replace("#", "", $col2) .
        "&color_3=" . str_replace("#", "", $col3) .
        "&color_4=" . str_replace("#", "", $col4) .
        "&rules=" . urlencode($rules);

    wp_admin_css_color(
        $key,
        $name,
        get_template_directory_uri() . "/inc/dash-scheme.php?" . $hash,
        array($col1, $col2, $col3, $col4),
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
    $col4 = iro_opt('admin_emphasize_color'),
    $base = "#FFF",
    $focus = "#FFF",
    $current = "#FFF",
    $rules = '#adminmenu .wp-has-current-submenu .wp-submenu a,#adminmenu .wp-has-current-submenu.opensub .wp-submenu a,#adminmenu .wp-submenu a,#adminmenu a.wp-has-current-submenu:focus+.wp-submenu a,#wpadminbar .ab-submenu .ab-item,#wpadminbar .quicklinks .menupop ul li a,#wpadminbar .quicklinks .menupop.hover ul li a,#wpadminbar.nojs .quicklinks .menupop:hover ul li a,.folded #adminmenu .wp-has-current-submenu .wp-submenu a{color:' . iro_opt('admin_text_color') . '}body{background-image:url(' . iro_opt('admin_background') . ');background-attachment:fixed;background-size:cover;}#wpcontent{background:rgba(255,255,255,.0)}.wp-core-ui .button-primary{background:' . iro_opt('admin_button_color') . '!important;border-color:' . iro_opt('admin_button_color') . '!important;color:' . iro_opt('admin_text_color') . '!important;box-shadow:0 1px 0 ' . iro_opt('admin_button_color') . '!important;text-shadow:0 -1px 1px ' . iro_opt('admin_button_color') . ',1px 0 1px ' . iro_opt('admin_button_color') . ',0 1px 1px ' . iro_opt('admin_button_color') . ',-1px 0 1px ' . iro_opt('admin_button_color') . '!important}'
);

//Set Default Admin Color Scheme for New Users
function set_default_admin_color($user_id)
{
    $args = array(
        'ID' => $user_id,
        'admin_color' => 'sunrise',
    );
    wp_update_user($args);
}
//add_action('user_register', 'set_default_admin_color');

//Stop Users From Switching Admin Color Schemes
//if ( !current_user_can('manage_options') ) remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );

// WordPress Custom style @ Admin
function custom_admin_open_sans_style()
{
    require get_template_directory() . '/inc/admin_addcss.php';
}
add_action('admin_head', 'custom_admin_open_sans_style');

// WordPress Custom Font @ Admin
function custom_admin_open_sans_font()
{
    echo '<link href="https://' . iro_opt('gfonts_api','fonts.loli.net') . '/css?family=Merriweather+Sans|Noto+Serif+SC&display=swap" rel="stylesheet">' . PHP_EOL;
    echo '<style>body, #wpadminbar *:not([class="ab-icon"]), .wp-core-ui, .media-menu, .media-frame *, .media-modal *{font-family:"Noto Serif SC","Source Han Serif SC","Source Han Serif","source-han-serif-sc","PT Serif","SongTi SC","MicroSoft Yahei",Georgia,serif !important;}</style>' . PHP_EOL;
}
add_action('admin_head', 'custom_admin_open_sans_font');

// WordPress Custom Font @ Admin Frontend Toolbar
function custom_admin_open_sans_font_frontend_toolbar()
{
    if (current_user_can('administrator') && is_admin_bar_showing()) {
        echo '<link href="https://' . iro_opt('gfonts_api','fonts.loli.net') . '/css?family=Merriweather+Sans&display=swap" rel="stylesheet">' . PHP_EOL;
        echo '<style>#wpadminbar *:not([class="ab-icon"]){font-family:"Noto Serif SC","Source Han Serif SC","Source Han Serif","source-han-serif-sc","PT Serif","SongTi SC","MicroSoft Yahei",Georgia,serif !important;}</style>' . PHP_EOL;
    }
}
add_action('wp_head', 'custom_admin_open_sans_font_frontend_toolbar');

// WordPress Custom Font @ Admin Login
function custom_admin_open_sans_font_login_page()
{
    if (stripos($_SERVER["SCRIPT_NAME"], strrchr(wp_login_url(), '/')) !== false) {
        echo '<link href="https://' . iro_opt('gfonts_api','fonts.loli.net') . '/css?family=Noto+Serif+SC&display=swap" rel="stylesheet">' . PHP_EOL;
        echo '<style>body{font-family:"Noto Serif SC","Source Han Serif SC","Source Han Serif","source-han-serif-sc","PT Serif","SongTi SC","MicroSoft Yahei",Georgia,serif !important;}</style>' . PHP_EOL;
    }
}
add_action('login_head', 'custom_admin_open_sans_font_login_page');

// 阻止垃圾注册
add_action('register_post', 'codecheese_register_post', 10, 3);

function codecheese_register_post($sanitized_user_login, $user_email, $errors)
{

    // Blocked domains
    $domains = array(
        'net.buzzcluby.com',
        'buzzcluby.com',
        'mail.ru',
        'h.captchaeu.info',
        'edge.codyting.com'
    );

    // Get visitor email domain
    $email = explode('@', $user_email);

    // Check and display error message for the registration form if exists
    if (in_array($email[1], $domains)) {
        $errors->add('invalid_email', __('<b>ERROR</b>: This email domain (<b>@' . $email[1] . '</b>) has been blocked. Please use another email.'));
    }
}
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
    if ($class) $noscriptParam['class'] = $class;
    $noscriptParam['src'] = $src;
    $otherParam['class'] = 'lazyload' . ($class ? ' ' . $class : '');
    $otherParam['data-src'] = $src;
    $otherParam['onerror'] = 'imgError(this)';
    $otherParam['src'] =  iro_opt('page_lazyload_spinner');
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
                    $new_img =  preg_replace('/srcset=[\'"]([^\'"]+)[\'"]/i', 'data-srcset="$1"', $new_img);
                    $new_img =  preg_replace('/src=[\'"]([^\'"]+)[\'"]/i', 'data-src="$1" src="' . iro_opt('page_lazyload_spinner') . '" onerror="imgError(this)"', $new_img);
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
            foreach($matches as $result){
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
            foreach ($matches as $result){
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
//add_filter( 'comment_text', 'html_tag_parser' );//替换评论关键词

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
        $iv = str_repeat($sakura_privkey, 2);
        $encrypted = openssl_encrypt($qq_number, 'aes-128-cbc', $sakura_privkey, 0, $iv);
        
        $encrypted = urlencode(base64_encode($encrypted));
        return '<img src="' . rest_url("sakura/v1/qqinfo/avatar") . '?qq=' . $encrypted . '" class="lazyload avatar avatar-24 photo" alt="😀" width="24" height="24" onerror="imgError(this,1)">';
        
    }
    return $avatar;
}


function get_random_url(string $url):string
{
    $array = parse_url($url);
    if (!isset($array['query'])) {
        // 无参数
        return $url.'?'.rand(1,100);
    }
    // 有参数
    return $url.'&'.rand(1,100);
}


// default feature image
function DEFAULT_FEATURE_IMAGE(string $size='source'):string
{
    if (iro_opt('post_cover_options') == 'type_2') {
        return get_random_url(iro_opt('post_cover'));
    }
    if (iro_opt('random_graphs_options') == 'external_api'){
        return get_random_url(iro_opt('random_graphs_link'));
    }
    $_api_url = rest_url('sakura/v1/image/feature');
    $rand = rand(1, 100);
    # 拼接符
    $splice = strpos($_api_url, 'index.php?') !== false ? '&' : '?';
    $_api_url = "{$_api_url}{$splice}size={$size}&$rand";

    // if (strpos($_api_url, 'index.php?') !== false) {
    //     $_api_url = "{$_api_url}&size={$size}&$rand";
    // }else{
    //     $_api_url = "{$_api_url}?size={$size}&$rand";
    // }
    return $_api_url;
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
        register_sidebar(array(
            'name' => __('Sidebar'), //侧栏
            'id' => 'sakura_widget',
            'before_widget' => '<div class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<div class="title"><h2>',
            'after_title' => '</h2></div>',
        ));
    }
}

// 评论Markdown解析
function markdown_parser($incoming_comment)
{
    global $wpdb, $comment_markdown_content;
    $re = '/```([\s\S]*?)```[\s]*|`{1,2}[^`](.*?)`{1,2}|\[.*?\]\([\s\S]*?\)/m';
    if (preg_replace($re, 'temp', $incoming_comment['comment_content']) != strip_tags(preg_replace($re, 'temp', $incoming_comment['comment_content']))) {
        siren_ajax_comment_err('评论只支持Markdown啦，见谅╮(￣▽￣)╭<br>Markdown Supported while <i class="fa fa-code" aria-hidden="true"></i> Forbidden');
        return ($incoming_comment);
    }
    $column_names = $wpdb->get_row("SELECT * FROM information_schema.columns where 
    table_name='wp_comments' and column_name = 'comment_markdown' LIMIT 1");
    //Add column if not present.
    if (!isset($column_names)) {
        $wpdb->query("ALTER TABLE wp_comments ADD comment_markdown text");
    }
    $comment_markdown_content = $incoming_comment['comment_content'];
    include 'inc/Parsedown.php';
    $Parsedown = new Parsedown();
    $incoming_comment['comment_content'] = $Parsedown->setUrlsLinked(false)->text($incoming_comment['comment_content']);
    return $incoming_comment;
}
add_filter('preprocess_comment', 'markdown_parser');
remove_filter('comment_text', 'make_clickable', 9);

//保存Markdown评论
function save_markdown_comment($comment_ID, $comment_approved)
{
    global $wpdb, $comment_markdown_content;
    $comment = get_comment($comment_ID);
    $comment_content = $comment_markdown_content;
    //store markdow content
    $wpdb->query("UPDATE wp_comments SET comment_markdown='" . $comment_content . "' WHERE comment_ID='" . $comment_ID . "';");
}
add_action('comment_post', 'save_markdown_comment', 10, 2);

//打开评论HTML标签限制
function allow_more_tag_in_comment()
{
    global $allowedtags;
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
add_action('pre_comment_on_post', 'allow_more_tag_in_comment');

/*
 * 随机图
 */
function create_sakura_table()
{
    if (iro_opt('random_graphs_mts')) {
        global $wpdb, $sakura_image_array, $sakura_mobile_image_array, $sakura_privkey;
    } else {
        global $wpdb, $sakura_image_array, $sakura_privkey;
    }
    $sakura_table_name = $wpdb->base_prefix . 'sakurairo';
    require_once ABSPATH . "wp-admin/includes/upgrade.php";
    dbDelta("CREATE TABLE IF NOT EXISTS `" . $sakura_table_name . "` (
        `mate_key` varchar(50) COLLATE utf8_bin NOT NULL,
        `mate_value` text COLLATE utf8_bin NOT NULL,
        PRIMARY KEY (`mate_key`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");
    //default data
    if (!$wpdb->get_var("SELECT COUNT(*) FROM $sakura_table_name WHERE mate_key = 'manifest_json'")) {
        $manifest = array(
            "mate_key" => "manifest_json",
            "mate_value" => file_get_contents(get_template_directory() . "/manifest/manifest.json"),
        );
        $wpdb->insert($sakura_table_name, $manifest);
    }
    if (iro_opt('random_graphs_mts') && !$wpdb->get_var("SELECT COUNT(*) FROM $sakura_table_name WHERE mate_key = 'mobile_manifest_json'")) {
        $mobile_manifest = array(
            "mate_key" => "mobile_manifest_json",
            "mate_value" => file_get_contents(get_template_directory() . "/manifest/manifest_mobile.json"),
        );
        $wpdb->insert($sakura_table_name, $mobile_manifest);
        
    }
    if (!$wpdb->get_var("SELECT COUNT(*) FROM $sakura_table_name WHERE mate_key = 'json_time'")) {
        $time = array(
            "mate_key" => "json_time",
            "mate_value" => date("Y-m-d H:i:s", time()),
        );
        $wpdb->insert($sakura_table_name, $time);
    }
    if (!$wpdb->get_var("SELECT COUNT(*) FROM $sakura_table_name WHERE mate_key = 'privkey'")) {
        $privkey = array(
            "mate_key" => "privkey",
            "mate_value" => wp_generate_password(8),
        );
        $wpdb->insert($sakura_table_name, $privkey);
    }
    //reduce sql query
    $sakura_image_array = $wpdb->get_var("SELECT `mate_value` FROM  $sakura_table_name WHERE `mate_key`='manifest_json'");
    if (iro_opt('random_graphs_mts')) {
        $sakura_mobile_image_array = $wpdb->get_var("SELECT `mate_value` FROM  $sakura_table_name WHERE `mate_key`='mobile_manifest_json'");
    }
    $sakura_privkey = $wpdb->get_var("SELECT `mate_value` FROM  $sakura_table_name WHERE `mate_key`='privkey'");
}
add_action('after_setup_theme', 'create_sakura_table');

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

//解析短代码  
add_shortcode('task', 'task_shortcode');
function task_shortcode($attr, $content = '')
{
    $out = '<div class="task shortcodestyle"><i class="fa fa-tasks"></i>' . $content . '</div>';
    return $out;
}
add_shortcode('warning', 'warning_shortcode');
function warning_shortcode($attr, $content = '')
{
    $out = '<div class="warning shortcodestyle"><i class="fa fa fa-exclamation-triangle"></i>' . $content . '</div>';
    return $out;
}
add_shortcode('noway', 'noway_shortcode');
function noway_shortcode($attr, $content = '')
{
    $out = '<div class="noway shortcodestyle"><i class="fa fa-times-rectangle"></i>' . $content . '</div>';
    return $out;
}
add_shortcode('buy', 'buy_shortcode');
function buy_shortcode($attr, $content = '')
{
    $out = '<div class="buy shortcodestyle"><i class="fa fa-check-square"></i>' . $content . '</div>';
    return $out;
}

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
    return $info['mime'] == 'image/webp';
}
add_filter('file_is_displayable_image', 'mimvp_file_is_displayable_image', 10, 2);

//code end

//展开收缩功能
function xcollapse($atts, $content = null)
{
    extract(shortcode_atts(array("title" => ""), $atts));
    return '<div style="margin: 0.5em 0;">
    <div class="xControl">
    <i class="fa fa-arrow-down" style="color: #9F6F26;"></i> &nbsp;&nbsp;
    <span class="xTitle">' . $title . '</span>&nbsp;&nbsp;==>&nbsp;&nbsp;<a href="javascript:void(0)" class="collapseButton xButton"><span class="xbtn02">展开 / 收缩</span></a>
    <div style="clear: both;"></div>
    </div>
    <div class="xContent" style="display: none;">' . $content . '</div>
    </div>';
}
add_shortcode('collapse', 'xcollapse');

//code end


// add_action("wp_ajax_nopriv_getPhoto", "get_photo");
// add_action("wp_ajax_getPhoto", "get_photo");
// /**
//  * 相册模板
//  * @author siroi <mrgaopw@hotmail.com>
//  * @return Json
//  */
// function get_photo()
// {
//     $postId = $_GET['post'];
//     $page = get_post($postId);
//     if ($page->post_type != "page") {
//         $back['code'] = 201;
//     } else {
//         $back['code'] = 200;
//         $back['imgs'] = array();
//         $dom = new DOMDocument('1.0', 'utf-8');
//         $meta = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
//         $dom->loadHTML($meta . $page->post_content);
//         $imgS = $dom->getElementsByTagName('img');
//         //<img src="..." data-header="标题" data-info="信息" vertical=false>
//         foreach ($imgS as $key => $value) {
//             $attr = $value->attributes;
//             $header = $attr->getNamedItem('header');
//             $info = $attr->getNamedItem('data-info');
//             $vertical = $attr->getNamedItem('vertical');

//             //图片资源地址
//             $temp['img'] = $value->attributes->getNamedItem('src')->nodeValue;
//             //图片上的标题
//             $temp['header'] = $header->nodeValue ?? null;
//             //图片上的信息
//             $temp['info'] = $info->nodeValue ?? null;
//             //是否竖向展示 默认false
//             $temp['vertical'] = $vertical->nodeValue ?? null;
//             array_push($back['imgs'], $temp);
//         }
//     }
//     header('Content-Type:application/json;charset=utf-8');
//     echo json_encode($back);
//     exit();
// }

if (!iro_opt('login_language_opt') == '1') {
add_filter( 'login_display_language_dropdown', '__return_false' );
}

if (iro_opt('captcha_select') === 'iro_captcha') {
    function login_CAPTCHA()
    {
        include_once('inc/classes/Captcha.php');
        $img = new Sakura\API\Captcha;
        $test = $img->create_captcha_img();
        echo '<p><label for="captcha" class="captcha">验证码<br><img id="captchaimg" width="120" height="40" src="', $test['data'], '"><input type="text" name="yzm" id="yzm" class="input" value="" size="20" tabindex="4" placeholder="请输入验证码"><input type="hidden" name="timestamp" value="', $test['time'], '"><input type="hidden" name="id" value="', $test['id'], '">'
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
            //return home_url('/wp-admin/');
            
        
    }
    add_filter('authenticate', 'CAPTCHA_CHECK', 20, 3);
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
        }
        return $errors->add('invalid_department', '<strong>错误</strong>：验证码为空！');
        
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
        if ($check['code'] == 5) return $errors;

        return new WP_Error('prooffail', '<strong>错误</strong>：' . $check['msg']);
        
    }
    add_filter('registration_errors', 'registration_CAPTCHA_CHECK', 2, 3);
} elseif (iro_opt('captcha_select') === 'vaptcha') {
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
/**
 * 返回是否应当显示文章标题。
 * 
 */
function should_show_title():bool{
    $id = get_the_ID();
    $use_as_thumb = get_post_meta($id, 'use_as_thumb', true); //'true','only',(default)
    return !iro_opt('patternimg') 
    || !get_post_thumbnail_id($id) 
    && $use_as_thumb != 'true' && !get_post_meta($id, 'video_cover', true);
}
