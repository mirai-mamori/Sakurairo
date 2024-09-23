<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Akina
 */

?>
<?php 

if (iro_opt('author_profile_quote') == '1') {
	$author_description = get_the_author_meta( 'description' ) ? get_the_author_meta( 'description' ) :iro_opt('author_profile_quote_text', 'Carpe Diem and Do what I like');
?>
<?php } ?>

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
		<?php the_content( '', true ); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'ondemand' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
    <?php require_once get_template_directory() . "/tpl/section-article-function.php";?>
</article><!-- #post-## -->
