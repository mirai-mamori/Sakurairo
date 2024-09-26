<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Akina
 */
$ai_excerpt = get_post_meta($post->ID, POST_METADATA_KEY, true); 
$excerpt = has_excerpt(); 
?>

<?php
$post = get_post();
if (iro_opt('article_auto_toc', 'true') && (check_title_tags($post->post_content))) {
    echo '<div class="has-toc have-toc"></div>';
}
?>

<?php 
$author_description = '';
if (iro_opt('author_profile_quote') == '1') {
	$author_meta =  get_the_author_meta( 'description' );
	$author_description = $author_meta ? $author_meta :iro_opt('author_profile_quote_text', 'Carpe Diem and Do what I like');
} ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if(should_show_title()) { 
	    require get_template_directory() . '/tpl/single-entry-header.php';
	 } ?>
	<!--<div class="toc-entry-content">--><!-- 套嵌目录使用（主要为了支援评论）-->
	<?php if(!empty($ai_excerpt) && empty($excerpt)) { ?>
	<div class="ai-excerpt">
	<h4><i class="fa-solid fa-atom"></i><?php _e("AI Excerpt", "sakurairo") ?></h4><?php echo $ai_excerpt; ?>
	</div>
	<?php } ?>
	<div class="entry-content">
		<?php the_content( '', true ); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'ondemand' ),
				'after'  => '</div>',
			) );
		?>
		<!--<div class="oshimai"></div>-->
		<!--<h2 style="opacity:0;max-height:0;margin:0">Comments</h2>--><!-- 评论跳转标记 -->
	</div><!-- .entry-content -->
    <?php require_once get_template_directory() . "/tpl/section-article-function.php";?>
</article><!-- #post-## -->
