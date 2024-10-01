<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sakurairo
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php 
		$title_style = get_post_meta(get_the_ID(), 'title_style', true);
		the_title(
			sprintf(
				'<h2 class="entry-title" style="%s"><a href="%s" rel="bookmark">', 
				esc_attr($title_style),
				esc_url(get_permalink())
			), 
			'</a></h2>'
		); 
		?>

		<?php if ('post' === get_post_type()) : ?>
		<div class="entry-meta">
			<?php akina_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<footer class="entry-footer">
		<?php akina_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
