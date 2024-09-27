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

$author_description = '';
if (iro_opt('author_profile_quote') == '1') {
	$author_description = get_the_author_meta('description');
	if (!$author_description) {
		$author_description = iro_opt('author_profile_quote_text', 'Carpe Diem and Do what I like');
	}
}
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
