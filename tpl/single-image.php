<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sakurairo
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if(should_show_title()) { ?>
	<div class="Extendfull">
  	<?php the_post_thumbnail('full');
    require get_template_directory() . '/tpl/single-entry-header.php';
    ?>
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
