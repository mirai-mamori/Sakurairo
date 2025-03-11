<?php

/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sakurairo
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php
		if (have_posts()) : ?>

			<?php if (!iro_opt('patternimg') || !z_taxonomy_image_url()) { ?>
				<header class="page-header">
					<h1 class="cat-title"><?php single_cat_title('', true); ?></h1>
					<span class="cat-des">
						<?php
						if (category_description() != "") {
							echo "" . category_description();
						}
						?>
					</span>
				</header><!-- .page-header -->
			<?php } // page-header 
			// TODO： 'image_category'功能待实现
			while (have_posts()) : the_post();
				get_template_part('tpl/content', 'thumbcard');
			endwhile; ?>
			<div class="clearer"></div>
		<?php else :
			get_template_part('tpl/content', 'none');
		endif; ?>

	</main><!-- #main -->
	<?php if (iro_opt('pagenav_style') == 'ajax') { ?>
		<div id="pagination" <?php if (iro_opt('image_category') && is_category(explode(',', iro_opt('image_category')))) echo 'class="pagination-archive"'; ?>><?php next_posts_link(__(' Previous', 'sakurairo')); ?></div>
		<div id="add_post"><span id="add_post_time" style="visibility: hidden;" title="<?php echo iro_opt('page_auto_load', ''); ?>"></span></div>
	<?php } else { ?>
		<nav class="navigator">
			<?php previous_posts_link('<i class="fa-solid fa-angle-left"></i>') ?><?php next_posts_link('<i class="fa-solid fa-angle-right"></i>') ?>
		</nav>
	<?php } ?>
</div><!-- #primary -->

<?php
get_footer();
