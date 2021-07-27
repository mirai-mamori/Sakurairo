<?php

/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Akina
 */

$mashiro_logo = iro_opt('mashiro_logo');

?>
<?php header('X-Frame-Options: SAMEORIGIN'); ?>
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
	<link rel="stylesheet" href="https://<?php echo iro_opt('google_fonts_api'); ?>/css?family=Noto+SerifMerriweather|Merriweather+Sans|Source+Code+Pro|Ubuntu:400,700|Noto+Serif+SC<?php echo iro_opt('google_fonts_add'); ?>" media="all">
	<meta name="theme-color" content="<?php echo iro_opt('theme_skin'); ?>">
	<meta charset="<?php bloginfo('charset'); ?>">
	<!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
	<title itemprop="name"><?php global $page, $paged;
							wp_title('-', true, 'right');
							bloginfo('name');
							$site_description = get_bloginfo('description', 'display');
							if ($site_description && (is_home() || is_front_page())) echo " - $site_description";
							if ($paged >= 2 || $page >= 2) echo ' - ' . sprintf(__('page %s ', 'sakurairo'), max($paged, $page));/*第 %s 页*/ ?>
	</title>
	<?php
	if (iro_opt('iro_meta') == true) {
		$keywords = '';
		$description = '';
		if (is_singular()) {
			$keywords = '';
			$tags = get_the_tags();
			$categories = get_the_category();
			if ($tags) {
				foreach ($tags as $tag) {
					$keywords .= $tag->name . ',';
				};
			};
			if ($categories) {
				foreach ($categories as $category) {
					$keywords .= $category->name . ',';
				};
			};
			$description = mb_strimwidth(str_replace("\r\n", '', strip_tags($post->post_content)), 0, 240, '…');
		} else {
			$keywords = iro_opt('iro_meta_keywords');
			$description = iro_opt('iro_meta_description');
		};
	?>
		<meta name="description" content="<?php echo $description; ?>" />
		<meta name="keywords" content="<?php echo $keywords; ?>" />
	<?php } ?>
	<link rel="shortcut icon" href="<?php echo iro_opt('favicon_link', ''); ?>" />
	<meta http-equiv="x-dns-prefetch-control" content="on">
	<?php wp_head(); ?>
	<script type="text/javascript">
		if (!!window.ActiveXObject || "ActiveXObject" in window) { //is IE?
			alert('朋友，IE浏览器未适配哦~\n如果是 360、QQ 等双核浏览器，请关闭 IE 模式！');
		}
	</script>
	<?php if (iro_opt('google_analytics_id', '')) : ?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo iro_opt('google_analytics_id', ''); ?>"></script>
		<script>
			window.dataLayer = window.dataLayer || [];

			function gtag() {
				dataLayer.push(arguments)
			}
			gtag('js', new Date());
			gtag('config', '<?php echo iro_opt('google_analytics_id', ''); ?>');
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
						<a href="<?php bloginfo('url'); ?>"><img src="<?php echo iro_opt('iro_logo'); ?>"></a>
					</div>
				<?php } else { ?>
					<span class="site-title">
						<span class="logolink moe-mashiro">
							<a href="<?php bloginfo('url'); ?>">
								<ruby>
									<span class="sakuraso" style="font-family: '<?php echo $mashiro_logo['font_name']; ?>', 'Merriweather Sans', Helvetica, Tahoma, Arial, 'PingFang SC', 'Hiragino Sans GB', 'Microsoft Yahei', 'WenQuanYi Micro Hei', sans-serif;;"><?php echo $mashiro_logo['text_a']; ?></span>
									<span class="no" style="font-family: '<?php echo $mashiro_logo['font_name']; ?>', 'Merriweather Sans', Helvetica, Tahoma, Arial, 'PingFang SC', 'Hiragino Sans GB', 'Microsoft Yahei', 'WenQuanYi Micro Hei', sans-serif;"><?php echo $mashiro_logo['text_b']; ?></span>
									<span class="shironeko" style="font-family: '<?php echo $mashiro_logo['font_name']; ?>', 'Merriweather Sans', Helvetica, Tahoma, Arial, 'PingFang SC', 'Hiragino Sans GB', 'Microsoft Yahei', 'WenQuanYi Micro Hei', sans-serif;"><?php echo iro_opt('logo_text'); ?><?php echo $mashiro_logo['text_c']; ?></span>
									<rp></rp>
									<rt class="chinese-font"><?php echo $mashiro_logo['text_secondary']; ?></rt>
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
				<div class="searchbox"><i class="iconfont js-toggle-search iconsearch icon-search"></i></div>
			<?php } ?>
			<div class="lower"><?php if (iro_opt('nav_menu_display') == 'fold') { ?>
					<div id="show-nav" class="showNav">
						<div class="line line1"></div>
						<div class="line line2"></div>
						<div class="line line3"></div>
					</div><?php } ?>
				<nav><?php wp_nav_menu(array('depth' => 2, 'theme_location' => 'primary', 'container' => false)); ?></nav><!-- #site-navigation -->
			</div>
		</div>
	</header><!-- #masthead -->
	<div class="openNav no-select">
		<div class="iconflat no-select">
			<div class="icon"></div>
		</div>
	</div><!-- m-nav-bar -->
	<section id="main-container">
		<?php
		if (iro_opt('cover_switch')) {
			$filter = iro_opt('random_graphs_filter');
		?>
			<div class="headertop <?php echo $filter; ?>">
				<?php get_template_part('layouts/imgbox'); ?>
			</div>
		<?php } ?>
		<div id="page" class="site wrapper">
			<?php if (get_post_meta(get_the_ID(), 'cover_type', true) == 'hls') {
				the_video_headPattern_hls();
			} elseif (get_post_meta(get_the_ID(), 'cover_type', true) == 'normal') {
				the_video_headPattern_normal();
			} else {
				the_headPattern();
			} ?>
			<div id="content" class="site-content">