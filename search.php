<?php

/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Akina
 */

get_header(); ?>

<section id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php
		if (have_posts()) : ?>
			<?php if (!iro_opt('patternimg') || !get_random_bg_url()) { ?>
				<header class="page-header">
					<h1 class="page-title"><?php printf(__('search result: %s', 'sakurairo')/*搜索结果*/, '<span>' . get_search_query() . '</span>'); ?></h1>
				</header><!-- .page-header -->
			<?php } ?>
			<?php
			/* Start the Loop */
			if (iro_opt('post_list_style') == 'akinastyle') {
				while (have_posts()) : the_post();
					get_template_part('tpl/content', get_post_format());
				endwhile;
			} else {
				get_template_part('tpl/content', 'thumb');
			}

			the_posts_navigation();

		else : ?>
			<div class="search-box">
				<!-- search start -->
				<form class="s-search">
					<i class="iconfont icon-search"></i>
					<input class="text-input" type="search" name="s" placeholder="<?php _e('Search...', 'sakurairo') ?>" required>
				</form>
				<!-- search end -->
			</div>
		<?php
			get_template_part('tpl/content', 'none');

		endif; ?>

		<style>
			.nav-previous,
			.nav-next {

				padding: 20px 0;
				text-align: center;
				margin: 40px 0 80px;
				display: inline-block;
				font-family: miranafont, "Hiragino Sans GB", "Microsoft YaHei", STXihei, SimSun, sans-serif;
			}

			.nav-previous,
			.nav-next a {
				padding: 13px 35px;
				border: 1px solid #D6D6D6;
				border-radius: 50px;
				color: #ADADAD;
			}

			.nav-previous,
			.nav-next span {
				color: #989898;
				font-size: 15px;
			}

			.nav-previous,
			.nav-next a:hover {
				border: 1px solid #A0DAD0;
				color: #A0DAD0;
			}
		</style>

	</main><!-- #main -->
</section><!-- #primary -->
<?php
get_footer();
