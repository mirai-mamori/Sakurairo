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
			<a href="<?php the_permalink();?>"><div class="overlay"><i class="fa-solid fa-box-archive"></i></div><?php the_post_thumbnail(); ?></a>
			<?php } else {?>
			<a href="<?php the_permalink();?>"><div class="overlay"><i class="fa-solid fa-box-archive"></i></div><img src="<?=iro_opt('vision_resource_basepath')?>basic/content-image-<?php echo rand(1,4)?>.jpg" /></a>
			<?php } ?>
		</div>	
		<h1 class="entry-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h1>
		<div class="p-time">
		<?php if(is_sticky()) : ?>
			<i class="fa-regular fa-gem"></i>
		 <?php endif ?>
	  	<i class="fa-regular fa-clock"></i><?php echo poi_time_since(strtotime($post->post_date)); ?>
	  	</div>
		<?php the_excerpt(); ?>
		<footer class="entry-footer">
		<div class="info-meta">
	       <div class="comnum">  
	        <span>
			<i class="fa-regular fa-comment"></i>
		<?php comments_popup_link(__("NOTHING","sakurairo"), __("1 Comment","sakurairo")/*条评论*/, '% '.__("Comments","sakurairo")/*条评论*/,'',__("Comment Closed","sakurairo")/**评论关闭 */); ?>
			</span>
			</div>
			<div class="views"> 
			<span><i class="fa-regular fa-eye"></i><?php echo get_post_views(get_the_ID()).' '._n('Hit','Hits',get_post_views(get_the_ID()),'sakurairo')/*热度*/?></span>
			 </div>   
	    </div>		
		</footer><!-- .entry-footer -->
		</div>	
	<hr>
	</article><!-- #post-## -->