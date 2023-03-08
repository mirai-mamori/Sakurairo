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
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'ondemand' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
	<footer class="post-footer">
	<div class="post-lincenses"><a href="https://creativecommons.org/licenses/by-nc-sa/4.0/deed.zh" target="_blank" rel="nofollow"><i class="fa fa-creative-commons" aria-hidden="true"></i></a></div>
	<?php the_reward(); ?>
	<section class="author-profile">
		<div class="info" itemprop="author" itemscope="" itemtype="http://schema.org/Person">
			<a href="<?= esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="profile gravatar"><img class="faa-spin animated-hover" src="<?php echo get_avatar_profile_url(); ?>" itemprop="image" alt="<?php the_author(); ?>" height="30" width="30"></a>
		</div>
		<div class="meta">
			<a href="<?= esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" itemprop="url" rel="author"><?php the_author(); ?></a>						
		</div>
		<?php if($author_description){?>
		<div class="desc">
		<i class="iconfont icon-write"></i><?=$author_description ?>
		</div>
<?php
		}
		?>
	</section>
	<div class="post-tags">
		<?php if ( has_tag() ) { echo '<i class="iconfont icon-tags"></i> '; the_tags('', ' ', ' ');} else { echo '<i class="iconfont icon-tags"></i> '; _e('Nothing~', 'sakurairo');} ?>?>
	</div>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
