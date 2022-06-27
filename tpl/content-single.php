<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Akina
 */

?>

<?php if (iro_opt('article_auto_toc', 'true')): ?>
<div class="has-toc have-toc"></div>
<?php endif; ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if(should_show_title()) { ?>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<p class="entry-census"><?php echo poi_time_since(strtotime($post->post_date)); ?>&nbsp;&nbsp;<?php echo get_post_views(get_the_ID()).' '. _n('View','Views',get_post_views(get_the_ID()),'sakurairo')/*次阅读*/?> </p>
		<hr>
	</header><!-- .entry-header -->
	<?php } ?>
	<!--<div class="toc-entry-content"><!-- 套嵌目录使用（主要为了支援评论）-->
	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'ondemand' ),
				'after'  => '</div>',
			) );
		?>
		<!--<div class="oshimai"></div>-->
		<!--<h2 style="opacity:0;max-height:0;margin:0">Comments</h2>--><!-- 评论跳转标记 -->
	</div><!-- .entry-content -->
	<?php the_reward(); ?>
	<footer class="post-footer">
	<div class="post-tags">
		<?php if ( has_tag() ) { echo '<i class="iconfont icon-tags"></i> '; the_tags('', ' ', ' ');}?>
	</div>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
