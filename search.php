<?php

/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Sakurairo
 */

get_header(); ?>

<section id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php if (have_posts()) : ?>
			<?php if (!iro_opt('patternimg') || !get_random_bg_url()) : ?>
				<header class="page-header">
					<h1 class="page-title"><?php printf(esc_html__('Search result: %s', 'sakurairo'), '<span>' . esc_html(get_search_query()) . '</span>'); ?></h1>
				</header><!-- .page-header -->
			<?php endif; ?>
			<?php
			while ( have_posts() ) :
			/* Start the Loop */
			    the_post();
			    get_template_part('tpl/content', 'thumbcard');
			endwhile;
		else : ?>
			<div class="search-box">
				<!-- search start -->
				<form class="s-search">
					<input class="text-input" type="search" name="s" placeholder="<?php esc_attr_e('Search...', 'sakurairo'); ?>" required>
				</form>
				<!-- search end -->
			</div>
			<?php get_template_part('tpl/content', 'none'); ?>
		<?php endif; ?>

		<style>
			.nav-previous,
			.nav-next {
				padding: 20px 0;
				text-align: center;
				margin: 40px 0 80px;
				display: inline-block;
				font-family: 'Fira Code', 'Noto Sans SC';
			}

			.nav-previous a,
			.nav-next a {
				padding: 13px 35px;
				border: 1px solid #D6D6D6;
				border-radius: 50px;
				color: #ADADAD;
				text-decoration: none;
			}

			.nav-previous span,
			.nav-next span {
				color: #989898;
				font-size: 15px;
			}

			.nav-previous a:hover,
			.nav-next a:hover {
				border: 1px solid #A0DAD0;
				color: #A0DAD0;
			}
		</style>

	</main><!-- #main -->
</section><!-- #primary -->

<?php get_footer(); ?>
