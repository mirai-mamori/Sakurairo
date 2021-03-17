<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Akina
 */

?>

<article class="post post-list" itemscope="" itemtype="http://schema.org/BlogPosting">
<div class="post-entry">
  <div class="feature">
	<?php if ( has_post_thumbnail() ) { ?>
		<a href="<?php the_permalink();?>"><div class="overlay"><i class="iconfont icon-text"></i></div><?php the_post_thumbnail(); ?></a>
		<?php } else {?>
		<a href="<?php the_permalink();?>"><div class="overlay"><i class="iconfont icon-text"></i></div><img src="<?php bloginfo('template_url'); ?>/inc/content-image/d-<?php echo rand(1,4)?>.jpg" /></a>
		<?php } ?>
	</div>	
	<h1 class="entry-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h1>
	<div class="p-time">
	 <?php if(is_sticky()) : ?>
			<i class="iconfont hotpost icon-hot"></i>
		 <?php endif ?>
	  <i class="iconfont icon-time"></i><?php echo poi_time_since(strtotime($post->post_date_gmt));//the_time('Y-m-d');?>
	  </div>
		<p><?php echo mb_strimwidth(strip_shortcodes(strip_tags(apply_filters('the_content', $post->post_content))), 0, 150,"...");?></p>
	<footer class="entry-footer">
	<div class="post-more">
			<a href="<?php the_permalink(); ?>"><i class="iconfont icon-caidan"></i></a>
		</div>
	<div class="info-meta">
       <div class="comnum">  
        <span><i class="iconfont icon-mark"></i><?php comments_popup_link('NOTHING', '1 '.__("Comment","sakurairo")/*条评论*/, '% '.__("Comments","sakurairo")/*条评论*/); ?></span>
		</div>
		<div class="views"> 
			<?php
			wp_enqueue_script('r17', "https://cdn.jsdelivr.net/npm/react@17.0.1/umd/react.production.min.js", array(), null, true);
			wp_enqueue_script('r17-dom', "https://cdn.jsdelivr.net/npm/react-dom@17.0.1/umd/react-dom.production.min.js", array(), null, true);
			wp_enqueue_script('pv',  "https://cdn.jsdelivr.net/gh/kotorik/yukicat-attach@s1.5/dist/pv.js", array('r17', 'r17-dom'), null, true);
			wp_enqueue_style("pv_style", "https://cdn.jsdelivr.net/gh/kotorik/yukicat-attach@latest/dist/pv.css") ?>
			?>
		<span><i class="iconfont icon-attention"></i><?php echo hack_pv('Hit').' '._n('Hit','Hits',hack_pv('Hit'),'sakurairo')/*热度*/?></span>
		 </div>   
        </div>		
	</footer><!-- .entry-footer -->
	</div>	
	<hr>
</article><!-- #post-## -->

