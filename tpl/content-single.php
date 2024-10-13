<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sakurairo
 */
$post_id = get_the_ID();
$ai_excerpt = get_post_meta($post_id, POST_METADATA_KEY, true); 
?>

<?php
$post = get_post();
if (iro_opt('article_auto_toc', 'true') && check_title_tags($post->post_content)) {
	echo '<div class="has-toc have-toc"></div>';
}
?>

<article id="post-<?php echo esc_attr($post_id); ?>" <?php post_class(); ?>>
	<?php if (should_show_title()) { 
		get_template_part('tpl/single-entry-header');
	} ?>
	<!--<div class="toc-entry-content">--><!-- 套嵌目录使用（主要为了支援评论）-->
	<?php if ($ai_excerpt) { ?>
	<div class="ai-excerpt">
		<h4><i class="fa-solid fa-atom"></i><?php esc_html_e("AI Excerpt", "sakurairo"); ?></h4><?php echo esc_html($ai_excerpt); ?>
	</div>
	<?php } ?>
	<div class="entry-content">
		<?php the_content('', true); ?>
		<?php
			wp_link_pages(array(
				'before' => '<div class="page-links">' . esc_html__('Pages:', 'ondemand'),
				'after'  => '</div>',
			));
		?>
		<!--<div class="oshimai"></div>-->
		<!--<h2 style="opacity:0;max-height:0;margin:0">Comments</h2>--><!-- 评论跳转标记 -->
	</div><!-- .entry-content -->
	<?php get_template_part('tpl/section-article-function'); ?>
</article><!-- #post-## -->
