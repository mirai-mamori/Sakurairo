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
	<?php if(should_show_title()) { ?>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<p class="entry-census"><?php echo poi_time_since(strtotime($post->post_date)); ?>&nbsp;&nbsp;<?php echo get_post_views(get_the_ID()).' '. _n('View','Views',get_post_views(get_the_ID()),'sakurairo')/*次阅读*/?> </p>
		<hr>
	</header><!-- .entry-header -->
	<?php } ?>
	<!--<div class="toc-entry-content">--><!-- 套嵌目录使用（主要为了支援评论）-->
	<?php if(!empty($ai_excerpt) && empty($excerpt)) { ?>
	<div class="ai-excerpt">
	<h4><i class="fa-regular fa-lightbulb"></i><?php _e("AI Excerpt", "sakurairo") ?></h4><?php echo $ai_excerpt; ?>
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
	<footer class="post-footer">
    <div class="post-lincenses">
        <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/deed.zh" target="_blank" rel="nofollow">
            <i class="fa-brands fa-creative-commons"></i>
        </a>
    </div>
    <?php the_reward(); ?>
    <section class="author-profile">
        <?php
            $author_id = get_the_author_meta('ID');
            $author_url = esc_url(get_author_posts_url($author_id));
            $author_name = get_the_author();
        ?>
        <div class="info" itemprop="author" itemscope="" itemtype="http://schema.org/Person">
            <a href="<?= $author_url; ?>" class="profile gravatar">
                <img class="fa-spin" style="--fa-animation-duration: 15s;" src="<?php echo get_avatar_profile_url(); ?>" itemprop="image" alt="<?= $author_name; ?>" height="30" width="30">
            </a>
        </div>
        <div class="meta">
            <a href="<?= $author_url; ?>" itemprop="url" rel="author"><?= $author_name; ?></a>						
        </div>
        <?php if($author_description){?>
            <div class="desc">
                <i class="fa-solid fa-feather" aria-hidden="true"></i><?= $author_description ?>
            </div>
        <?php } ?>
    </section>
    <div class="post-modified-time">
        <i class="fa-solid fa-calendar-day" aria-hidden="true"></i><?php _e('Last updated on ', 'sakurairo'); echo get_the_modified_time('Y-m-d'); ?>
    </div>
    <div class="post-tags">
        <?php if ( has_tag() ) { 
            echo '<i class="fa-solid fa-tag" aria-hidden="true"></i> '; 
            the_tags('', ' ', ' ');
        } else { 
            echo '<i class="fa-solid fa-tag" aria-hidden="true"></i> '; 
            _e('Nothing~', 'sakurairo');
        } ?>
    </div>
</footer><!-- .entry-footer -->
</article><!-- #post-## -->
