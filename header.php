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

$mashiro_logo = iro_opt('mashiro_logo');
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
	<link rel="stylesheet" href="https://s4.zstatic.net/ajax/libs/font-awesome/6.6.0/css/all.min.css" type="text/css" media="all"/>
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
	<script type="text/javascript">
		if (!!window.ActiveXObject || "ActiveXObject" in window) { //is IE?
			alert('朋友，IE浏览器未适配哦~\n如果是 360、QQ 等双核浏览器，请关闭 IE 模式！(Are you using IE? Some of the web elements might be broken, please use the latest browser to access！)');
		}
	</script>
	<?php if (iro_opt('google_analytics_id')) : ?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?= esc_attr(iro_opt('google_analytics_id')); ?>"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag() {dataLayer.push(arguments)}
			gtag('js', new Date());
			gtag('config', '<?= esc_attr(iro_opt('google_analytics_id')); ?>');
		</script>
	<?php endif; ?>
	<?= iro_opt("site_header_insert"); ?>

	<?php if (iro_opt('poi_pjax')): ?>
    <script>
		function loadScript(url) {
			fetch(url)
				.then(response => {
					if (!response.ok) {
						throw new Error(`Failed to load script: ${url}`);
					}
					return response.text();
				})
				.then(scriptContent => {
					new Function(scriptContent)();
				})
				.catch(error => {
					console.error(error);
				});
		}
        const srcs = `
            <?php echo iro_opt("pjax_keep_loading"); ?>
        `;
        document.addEventListener("pjax:complete", () => {
            srcs.split(/[\n,]+/).forEach(path => {
                path = path.trim();
                if (!path) return; 
                if (path.endsWith('.js')) {
                    loadScript(path);
                } else if (path.endsWith('.css')) {
                    const style = document.createElement('link');
                    style.rel = 'stylesheet';
                    style.href = path;
                    document.head.appendChild(style);
                }
            });
        });
    </script>
    <?php endif; ?>

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
	<header class="site-header no-select" role="banner">
		<div class="site-top">
			<div class="site-branding">
				<?php if (iro_opt('iro_logo') && !iro_opt('mashiro_logo_option', false)) { ?>
					<div class="site-title">
					<a href="<?php echo bloginfo('url'); ?>"><img alt="<?= esc_attr(get_option('blogname')); ?>" src="<?= esc_url(iro_opt('iro_logo')); ?>"></a>
					</div>
				<?php } else { ?>
					<span class="site-title">
						<span class="logolink moe-mashiro">
						    <a href="<?= bloginfo('url'); ?>">
								<ruby>
									<span class="sakuraso"><?= esc_html($mashiro_logo['text_a'] ?? ""); ?></span>
									<span class="no"><?= esc_html($mashiro_logo['text_b'] ?? ""); ?></span>
									<span class="shironeko"><?= esc_html($mashiro_logo['text_c'] ?? ""); ?></span>
									<rp></rp>
									<rt class="chinese-font"><?= esc_html($mashiro_logo['text_secondary'] ?? ""); ?></rt>
									<rp></rp>
								</ruby>
							</a>
						</span>
					</span>
				<?php } ?>
				<!-- logo end -->
			</div><!-- .site-branding -->
			<?php header_user_menu();
			if (iro_opt('nav_menu_search') == '1') { ?>
				<div class="searchbox js-toggle-search"><i class="fa-solid fa-magnifying-glass"></i></div>
			<?php } ?>
			<div class="lower"><?php if (iro_opt('nav_menu_display') == 'fold') { ?>
					<div id="show-nav" class="showNav">
						<div class="line line1"></div>
						<div class="line line2"></div>
						<div class="line line3"></div>
					</div><?php } ?>
				<nav><?php wp_nav_menu(['depth' => 2, 'theme_location' => 'primary', 'container' => false]); ?></nav><!-- #site-navigation -->
			</div>
		</div>
	</header><!-- #masthead -->
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
