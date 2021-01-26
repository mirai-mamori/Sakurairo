<?php 

/**
 Template Name: 友情链接模版
 */

get_header(); 

?>
	<?php while(have_posts()) : the_post(); ?>
	<?php if(!iro_opt('patternimg') || !get_post_thumbnail_id(get_the_ID())) { ?>
	<span class="linkss-title"><?php the_title();?></span>
	<?php } ?>
		<article <?php post_class("post-item"); ?>>
			<?php the_content(); ?>
			<div class="links">
				<?php echo get_link_items(); ?>
			</div>
		</article>
        <div class="have-toc"></div><div class="toc-container"><div class="toc"></div></div>
	<?php endwhile; ?>
<?php
get_footer();
