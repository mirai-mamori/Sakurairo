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
?>
<?php header('X-Frame-Options: SAMEORIGIN'); ?>
<!DOCTYPE html>
<!-- 
Theme by Mashiro
                      /^--^\     /^--^\     /^--^\
                      \____/     \____/     \____/
                     /      \   /      \   /      \
                    |        | |        | |        |
                     \__  __/   \__  __/   \__  __/
|^|^|^|^|^|^|^|^|^|^|^|^\ \^|^|^|^/ /^|^|^|^|^\ \^|^|^|^|^|^|^|^|^|^|^|^|
| | | | | | | | | | | | |\ \| | |/ /| | | | | | \ \ | | | | | | | | | | |
########################/ /######\ \###########/ /#######################
| | | | | | | | | | | | \/| | | | \/| | | | | |\/ | | | | | | | | | | | |
|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|_|

-->
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<title itemprop="name"><?php global $page, $paged;wp_title( '-', true, 'right' );
bloginfo( 'name' );$site_description = get_bloginfo( 'description', 'display' );
if ( $site_description && ( is_home() || is_front_page() ) ) echo " - $site_description";if ( $paged >= 2 || $page >= 2 ) echo ' - ' . sprintf( __( 'page %s ','sakura'), max( $paged, $page ) );/*第 %s 页*/?>
</title>
<?php
if (akina_option('akina_meta') == true) {
	$keywords = '';
	$description = '';
	if ( is_singular() ) {
		$keywords = '';
		$tags = get_the_tags();
		$categories = get_the_category();
		if ($tags) {
			foreach($tags as $tag) {
				$keywords .= $tag->name . ','; 
			};
		};
		if ($categories) {
			foreach($categories as $category) {
				$keywords .= $category->name . ','; 
			};
		};
		$description = mb_strimwidth( str_replace("\r\n", '', strip_tags($post->post_content)), 0, 240, '…');
	} else {
		$keywords = akina_option('akina_meta_keywords');
		$description = akina_option('akina_meta_description');
	};
?>
<meta name="description" content="<?php echo $description; ?>" />
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<link rel="shortcut icon" href="<?php echo akina_option('favicon_link', ''); ?>"/> 
<meta http-equiv="x-dns-prefetch-control" content="on">
<?php wp_head(); ?>
<script type="text/javascript">
if (!!window.ActiveXObject || "ActiveXObject" in window) { //is IE?
  alert('朋友，IE浏览器未适配哦~\n如果是 360、QQ 等双核浏览器，请关闭 IE 模式！');
}
</script>
<?php if(akina_option('google_analytics_id', '')):?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo akina_option('google_analytics_id', ''); ?>"></script>
<script>
window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());gtag('config','<?php echo akina_option('google_analytics_id', ''); ?>');
</script>
<?php endif; ?>
</head>
<body <?php body_class(); ?>>
	<div class="scrollbar" id="bar"></div>
	<section id="main-container">
		<?php 
		if(!akina_option('head_focus')){ 
		$filter = akina_option('focus_img_filter');
		?>
		<div class="headertop <?php echo $filter; ?>">
			<?php get_template_part('layouts/imgbox'); ?>
		</div>	
		<?php } ?>
		<div id="page" class="site wrapper">
			<header class="site-header no-select" role="banner">
				<div class="site-top">
					<div class="site-branding">
						<?php if (akina_option('akina_logo')){ ?>
						<div class="site-title">
							<a href="<?php bloginfo('url');?>" ><img src="<?php echo akina_option('akina_logo'); ?>"></a>
						</div>
						<?php }else{ ?>
						<span class="site-title">
							<span class="logolink serif">
								<a href="<?php bloginfo('url');?>">
									<span class="site-name"><?php echo akina_option('site_name', ''); ?></span>
								</a>
							</span>
						</span>	
						<?php } ?><!-- logo end -->
					</div><!-- .site-branding -->
					<?php header_user_menu(); if(akina_option('top_search') == 'yes') { ?>
					<div class="searchbox"><i class="iconfont js-toggle-search iconsearch icon-search"></i></div>
					<?php } ?>
					<div class="lower"><?php if(!akina_option('shownav')){ ?>
						<div id="show-nav" class="showNav">
							<div class="line line1"></div>
							<div class="line line2"></div>
							<div class="line line3"></div>
						</div><?php } ?>
						<nav><?php wp_nav_menu( array( 'depth' => 2, 'theme_location' => 'primary', 'container' => false ) ); ?></nav><!-- #site-navigation -->
					</div>	
				</div>
			</header><!-- #masthead -->
			<?php if (get_post_meta(get_the_ID(), 'cover_type', true) == 'hls') {
                the_video_headPattern_hls();
            } elseif (get_post_meta(get_the_ID(), 'cover_type', true) == 'normal') { 
                the_video_headPattern_normal();
            }else {
                the_headPattern();
            } ?>
		    <div id="content" class="site-content">
