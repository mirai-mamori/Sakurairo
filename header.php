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

	<?php if (iro_opt('poi_pjax')){
		$script_leep_loading_list = iro_opt("pjax_keep_loading");
		if(strlen($script_leep_loading_list) > 0) :
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
		})});
    </script>
    <?php endif;} ?>

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
		$enable_random_graphs = (bool)iro_opt('cover_random_graphs_switch', 'true');
		?>

		<!-- Navigation and Search Section -->
		<div class="nav-search-wrapper">
			<nav>
				<?php
				/**
				 * Limit menu items based on total byte count
				 * @param array $items Menu items
				 * @param array $args Menu arguments
				 * @return array Filtered menu items
				 */
				function limit_menu_by_bytes($items, $args) {
					$byte_count = 0;
					$byte_limit = 70;
					$cut_index = -1;
					$main_items = array();
					
					// 收集所有主菜单项
					foreach($items as $key => $item) {
						if($item->menu_item_parent == 0) {
							$bytes = strlen(strip_tags($item->title));
							// 找出第一个导致总和超过70的位置
							if($byte_count + $bytes > $byte_limit) {
								$cut_index = $key;
								break;
							}
							$byte_count += $bytes;
						}
					}
					
					// 移除截断位置及之后的主菜单项
					if($cut_index >= 0) {
						foreach($items as $key => $item) {
							if($key >= $cut_index && $item->menu_item_parent == 0) {
								unset($items[$key]);
							}
						}
					}
					
					return $items;
				}
				
				add_filter('wp_nav_menu_objects', 'limit_menu_by_bytes', 10, 2);
				
				wp_nav_menu([
					'depth' => 2,
					'theme_location' => 'primary',
					'container' => false,
					'fallback_cb' => false
				]); ?>
			</nav>

			<?php if ($enable_random_graphs || $show_search): ?>
				<div class="nav-search-divider"></div>
			<?php endif; ?>

			<?php if ($show_search): ?>
				<div class="searchbox js-toggle-search">
					<i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
					<span class="screen-reader-text"><?php esc_html_e('Search', 'sakurairo'); ?></span>
				</div>
			<?php endif; ?>

			<?php if ($enable_random_graphs): ?>
				<div class="bg-switch" id="bg-next" style="display:none">
					<i class="fa-solid fa-dice" aria-hidden="true"></i>
					<span class="screen-reader-text"><?php esc_html_e('Random Background', 'sakurairo'); ?></span>
				</div>
				<script>
					// 初始化状态存储
					if (!sessionStorage.getItem('bgNextState')) {
						sessionStorage.setItem('bgNextState', JSON.stringify({
							lastPageWasHome: window.location.pathname === '/' || 
										   window.location.pathname === '/index.php',
							isTransitioning: false
						}));
					}

					const animateTransition = (isEntering, state) => {
						const bgNext = document.getElementById('bg-next');
						const navSearchWrapper = document.querySelector('.nav-search-wrapper'); 
						const searchbox = document.querySelector('.searchbox.js-toggle-search');
						const divider = document.querySelector('.nav-search-divider');
						const easing = 'cubic-bezier(0.34, 1.56, 0.64, 1)';
						const duration = '0.6s';

						state.isTransitioning = true;
						sessionStorage.setItem('bgNextState', JSON.stringify(state));

						// 初始化宽度计算
						const clone = bgNext.cloneNode(true);
						clone.style.cssText = 'display:block;opacity:0;position:fixed;pointer-events:none;';
						document.body.appendChild(clone);
						const bgNextWidth = clone.offsetWidth;
						document.body.removeChild(clone);
						
						// 重置过渡效果
						[bgNext, navSearchWrapper, searchbox, divider].forEach(el => {
							if(el) el.style.transition = 'none';
						});

						const initialWidth = navSearchWrapper.offsetWidth;
						navSearchWrapper.style.width = initialWidth + 'px';

						 // 如果没有搜索框但有分隔符，控制分隔符的显示/隐藏
						if (!searchbox && divider) {
							if (isEntering) {
								divider.style.display = 'block';
								divider.style.opacity = '0';
							}
						}

						// 设置初始状态
						bgNext.style.display = 'block';
						bgNext.style.opacity = isEntering ? '0' : '1';
						bgNext.style.transform = `translateX(${isEntering ? '20px' : '0'})`; // 改为从右侧进入

						// 设置搜索框和分隔符的初始位置
						if(isEntering) {
							if(searchbox) searchbox.style.transform = `translateX(${bgNextWidth + 6.5}px)`; // 增加补偿
							if(divider) {
								if (!searchbox) {
									divider.style.display = 'block';
									divider.style.opacity = '0';
									divider.style.transform = `translateX(${bgNextWidth + 6.5}px)`; // 添加与搜索框一致的位移
								} else {
									divider.style.transform = `translateX(${bgNextWidth + 6.5}px)`; // 增加补偿
								}
							}
						}

						// 强制回流
						[bgNext, navSearchWrapper, searchbox, divider].forEach(el => {
							if(el) void el.offsetWidth;
						});

						// 开始动画
						requestAnimationFrame(() => {
							[bgNext, navSearchWrapper].forEach(el => {
								el.style.transition = `all ${duration} ${easing}`;
							});
							if(searchbox) searchbox.style.transition = `transform ${duration} ${easing}`;
							if(divider) {
								if (!searchbox) {
									divider.style.transition = `all ${duration} ${easing}`; // 修改为all以同时处理opacity和transform
								} else {
									divider.style.transition = `transform ${duration} ${easing}`;
								}
							}

							bgNext.style.opacity = isEntering ? '1' : '0';
							bgNext.style.transform = `translateX(${isEntering ? '0' : '20px'})`; // 改为向右侧退出
							navSearchWrapper.style.width = `${initialWidth + (isEntering ? bgNextWidth : -bgNextWidth)}px`;

							if(!isEntering) {
								if(searchbox) searchbox.style.transform = `translateX(${bgNextWidth + 3.5}px)`; // 增加补偿
								if(divider) {
									if (!searchbox) {
										divider.style.opacity = '0';
										divider.style.transform = `translateX(${bgNextWidth + 3.5}px)`; // 添加退出时的位移
									} else {
										divider.style.transform = `translateX(${bgNextWidth + 3.5}px)`; // 增加补偿
									}
								}
							} else {
								if(searchbox) searchbox.style.transform = 'translateX(0)'; // 向左移动到原位
								if(divider) {
									if (!searchbox) {
										divider.style.opacity = '1';
										divider.style.transform = 'translateX(0)'; // 重置为初始位置
									} else {
										divider.style.transform = 'translateX(0)'; // 向左移动到原位
									}
								}
							}

							setTimeout(() => {
								if(!isEntering) {
									bgNext.style.display = 'none';
									navSearchWrapper.style.width = 'auto';
									if (!searchbox && divider) {
										divider.style.display = 'none';
									}
									[searchbox, divider].forEach(el => {
										if(el) {
											el.style.transition = 'none';
											el.style.transform = '';
										}
									});
								}
								state.isTransitioning = false;
								sessionStorage.setItem('bgNextState', JSON.stringify(state));
							}, parseFloat(duration) * 1000);
						});
					};

					const showBgNext = () => {
						const isHomePage = window.location.pathname === '/' || 
										  window.location.pathname === '/index.php';
						const state = JSON.parse(sessionStorage.getItem('bgNextState'));
						const searchbox = document.querySelector('.searchbox.js-toggle-search');
						const divider = document.querySelector('.nav-search-divider');

						if (state.isTransitioning) return;

						// 处理首次加载时的分隔符显示逻辑
						if (!state.hasOwnProperty('firstLoad')) {
							state.firstLoad = true;
							sessionStorage.setItem('bgNextState', JSON.stringify(state));
							
							const bgNext = document.getElementById('bg-next');
							if (isHomePage) {
								bgNext.style.display = 'block';
								bgNext.style.opacity = '1';
								bgNext.style.transform = 'translateX(0)';
							} else {
								bgNext.style.display = 'none';
								// 如果不是主页且没有搜索框，隐藏分隔符
								if (!searchbox && divider) {
									divider.style.display = 'none';
								}
							}
							return;
						}

						if (isHomePage && !state.lastPageWasHome) {
							animateTransition(true, state);
						} else if (!isHomePage && state.lastPageWasHome) {
							animateTransition(false, state);
						} else {
							const bgNext = document.getElementById('bg-next');
							bgNext.style.display = isHomePage ? 'block' : 'none';
							// 非主页且无搜索框时隐藏分隔符
							if (!isHomePage && !searchbox && divider) {
								divider.style.display = 'none';
							}
							if (isHomePage) {
								bgNext.style.opacity = '1';
								bgNext.style.transform = 'translateX(0)';
							}
						}
						
						state.lastPageWasHome = isHomePage;
						sessionStorage.setItem('bgNextState', JSON.stringify(state));
					};

					// PJAX事件监听
					document.addEventListener('pjax:send', () => {
						const state = JSON.parse(sessionStorage.getItem('bgNextState'));
						state.lastPageWasHome = window.location.pathname === '/' || 
											 window.location.pathname === '/index.php';
						sessionStorage.setItem('bgNextState', JSON.stringify(state));
					}, false);

					document.addEventListener('pjax:complete', () => {
						setTimeout(showBgNext, 0);
					}, false);

					// 初始执行
					showBgNext();
				</script>
			<?php endif; ?>
		</div>

		<!-- User Menu Section -->
		<?php if ($show_user_avatar): ?>
			<div class="user-menu-wrapper">
				<?php header_user_menu(); ?>
			</div>
		<?php endif; ?>
	</header>
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
