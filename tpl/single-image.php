<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Akina
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if(should_show_title()) { ?>
	<div class="Extendfull">
  	<?php the_post_thumbnail('full'); ?>
  	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<p class="entry-census"><?php echo poi_time_since(strtotime($post->post_date)); ?>&nbsp;&nbsp;<?php echo get_post_views(get_the_ID()).' '._n('View','Views',get_post_views(get_the_ID()),'sakurairo')/*次阅读*/?></p>
		<hr>
	</header>
	</div>
	<?php } ?>
	<!-- .entry-header -->
	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'ondemand' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
	<?php the_reward(); ?>
	<footer class="post-footer">
	<div class="post-tags">
		<?php if ( has_tag() ) { echo '<i class="iconfont icon-tags"></i> '; the_tags('', ' ', ' ');}?>
	</div>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
