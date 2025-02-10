<?php

/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Sakurairo
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$core_lib_basepath = iro_opt('core_library_basepath') ? get_template_directory_uri() : (iro_opt('lib_cdn_path', 'https://fastly.jsdelivr.net/gh/mirai-mamori/Sakurairo@') . IRO_VERSION);
$nav_text_logo = iro_opt('nav_text_logo');
$vision_resource_basepath = iro_opt('vision_resource_basepath');
header('X-Frame-Options: SAMEORIGIN');
?>
<!DOCTYPE html>
<!-- 
            ◢＼　 ☆　　 ／◣
           ∕　　﹨　╰╮∕　　﹨
           ▏　　～～′′～～ 　｜
           ﹨／　　　　　　 　＼∕
           ∕ 　　●　　　 ●　＼
        ＝＝　○　∴·╰╯　∴　○　＝＝
           ╭──╮　　　　　╭──╮
  ╔═ ∪∪∪═Mashiro&Hitomi═∪∪∪═╗
-->
<html <?php language_attributes(); ?>>

<head>
    <meta name="theme-color">
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <link rel="stylesheet" href="https://s4.zstatic.net/ajax/libs/font-awesome/6.7.1/css/all.min.css" type="text/css" media="all" />
    <?php
    if (iro_opt('iro_meta')) {
        $keywords = iro_opt('iro_meta_keywords');
        $description = iro_opt('iro_meta_description');
        if (is_singular()) {
            $tags = get_the_tags();
            if ($tags) {
                $keywords = implode(',', array_column($tags, 'name'));
            }
            if (!empty($post->post_content)) {
                $description = trim(mb_strimwidth(preg_replace('/\s+/', ' ', strip_tags($post->post_content)), 0, 240, '…'));
            }
        }
        if (is_category()) {
            $categories = get_the_category();
            if ($categories) {
                $keywords = implode(',', array_column($categories, 'name'));
            }
            $description = trim(category_description()) ?: $description;
        }
    ?>
        <meta name="description" content="<?= esc_attr($description); ?>" />
        <meta name="keywords" content="<?= esc_attr($keywords); ?>" />
    <?php } ?>
    <link rel="shortcut icon" href="<?= esc_url(iro_opt('favicon_link', '')); ?>" />
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <?php
    if (is_home()) {
        global $core_lib_basepath;
    ?>
        <link id="entry-content-css" rel="prefetch" as="style" href="<?= esc_url($core_lib_basepath . '/css/theme/' . (iro_opt('entry_content_style') == 'sakurairo' ? 'sakura' : 'github') . '.css?ver=' . IRO_VERSION) ?>" />
        <link rel="prefetch" as="script" href="<?= esc_url($core_lib_basepath . '/js/page.js?ver=' . IRO_VERSION) ?>" />
    <?php
    }
    ?>
    <?php wp_head(); ?>
    <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?>｜<?php bloginfo('description'); ?>" href="<?php bloginfo('rss2_url'); ?>" />
    <link rel="stylesheet" href="https://<?= esc_attr(iro_opt('gfonts_api', 'fonts.googleapis.com')); ?>/css?family=Noto+Serif+SC|Noto+Sans+SC|Dela+Gothic+One|Fira+Code<?= esc_attr(iro_opt('gfonts_add_name')); ?>&display=swap" media="all">
    <?php if (iro_opt('google_analytics_id')) : ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?= esc_attr(iro_opt('google_analytics_id')); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments)
            }
            gtag('js', new Date());
            gtag('config', '<?= esc_attr(iro_opt('google_analytics_id')); ?>');
        </script>
    <?php endif; ?>
    <?= iro_opt("site_header_insert"); ?>

    <?php if (iro_opt('poi_pjax')) {
        $script_leep_loading_list = iro_opt("pjax_keep_loading");
        if (strlen($script_leep_loading_list) > 0) :
    ?>
            <script>
                const srcs = `<?php echo iro_opt("pjax_keep_loading"); ?>`;
                document.addEventListener("pjax:complete", () => {
                    srcs.split(/[\n,]+/).forEach(path => {
                        path = path.trim();
                        if (!path) return;
                        if (path.endsWith('.js')) {
                            const script = document.createElement('script');
                            script.src = path;
                            script.async = true;
                            document.body.appendChild(script);
                        } else if (path.endsWith('.css')) {
                            const style = document.createElement('link');
                            style.rel = 'stylesheet';
                            style.href = path;
                            document.head.appendChild(style);
                        }
                    })
                });
            </script>
    <?php endif;
    } ?>

    <!--WordPress 脚注仅在本页内跳转-->
    <script  type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        // Ensure all footnote links jump within the same page
        document.querySelectorAll('a[href^="#"]').forEach(function (link) {
            link.addEventListener('click', function (event) {
                // Prevent default behavior if unnecessary
                event.preventDefault();
                // Find the target element
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                // Scroll to the target element
                targetElement.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    });
	</script>
    <script src="<?= $core_lib_basepath . '/js/nav.js' ?>" defer></script>
</head>

<body <?php body_class(); ?>>
    <?php if (iro_opt('preload_animation', 'true')) : ?>
        <div id="preload">
            <li data-id="3" class="active">
                <div id="preloader_3"></div>
            </li>
        </div>
    <?php endif; ?>
    <div class="scrollbar" id="bar"></div>

    <!-- 导航菜单 -->
     <?php if(iro_opt('sakura_nav_style')){
        ?>
        <link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/css/sakura_header.css'; ?>">
        <?php
        get_template_part('layouts/' . 'sakura_header');
     } else {
    ?>
    <header class="site-header no-select" role="banner">
        <?php
        // Logo Section - Only process if logo or text is configured
        if (iro_opt('iro_logo') || !empty($nav_text_logo['text'])): ?>
            <div class="site-branding">
                <a href="<?= esc_url(home_url('/')); ?>">
                    <?php if (iro_opt('iro_logo')): ?>
                        <div class="site-title-logo">
                            <img alt="<?= esc_attr(get_bloginfo('name')); ?>"
                                src="<?= esc_url(iro_opt('iro_logo')); ?>"
                                width="auto" height="auto"
                                loading="lazy"
                                decoding="async">
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($nav_text_logo['text'])): ?>
                        <div class="site-title">
                            <?= esc_html($nav_text_logo['text']); ?>
                        </div>
                    <?php endif; ?>
                </a>
            </div>
        <?php endif;

        // Cache commonly used options
        $show_search = (bool)iro_opt('nav_menu_search');
        $show_user_avatar = (bool)iro_opt('nav_menu_user_avatar');
        $enable_random_graphs = (bool)iro_opt('cover_switch', true) && (bool)iro_opt('cover_random_graphs_switch', true);
        ?>

        <!-- Navigation and Search Section -->
        <div class="nav-search-wrapper">
            <!-- Nav menu -->
            <nav>
                <?php 
                wp_nav_menu([
                    'depth' => 2,
                    'theme_location' => 'primary',
                    'container' => false
                    ]); 
                ?>
            </nav>

            <!-- Search and background switch -->
            <?php if ($enable_random_graphs || $show_search): ?>
                <div class="nav-search-divider"></div>
            <?php endif; ?>

            <?php if ($show_search): ?>
                <div class="searchbox js-toggle-search">
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    <span class="screen-reader-text">
                        <?php esc_html_e('Search', 'sakurairo'); ?>
                    </span>
                </div>
            <?php endif; ?>

            <?php if ($enable_random_graphs): ?>
                <div class="bg-switch" id="bg-next" style="display:none">
                    <i class="fa-solid fa-dice" aria-hidden="true"></i>
                    <span class="screen-reader-text">
                        <?php esc_html_e('Random Background', 'sakurairo'); ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>

        <!-- User Menu Section -->
        <?php if ($show_user_avatar): ?>
            <div class="user-menu-wrapper">
                <?php header_user_menu(); ?>
            </div>
        <?php endif; ?>
    </header>
    <!-- 导航菜单结束 -->
     <?php
     }
     ?>
     
    <div class="openNav no-select">
        <div class="iconflat no-select" style="padding: 30px;">
            <div class="icon"></div>
        </div>
    </div><!-- m-nav-bar -->
    <section id="main-container">
        <?php
        if (iro_opt('cover_switch')) {
            $filter = iro_opt('random_graphs_filter');
        ?>
            <div class="headertop <?= esc_attr($filter); ?>">
                <?php get_template_part('layouts/imgbox'); ?>
            </div>
        <?php } ?>
        <div id="page" class="site wrapper">
            <?php
            $use_as_thumb = get_post_meta(get_the_ID(), 'use_as_thumb', true); //'true','only',(default)
            if ($use_as_thumb != 'only') {
                $cover_type = get_post_meta(get_the_ID(), 'cover_type', true);
                if ($cover_type == 'hls') {
                    the_video_headPattern(true);
                } elseif ($cover_type == 'normal') {
                    the_video_headPattern(false);
                } else {
                    the_headPattern();
                }
            } else {
                the_headPattern();
            } ?>
            <div id="content" class="site-content">
