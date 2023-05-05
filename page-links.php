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
			<div class="entry-content">
				<?php the_content( '', true ); ?>
			</div>
			<div class="links">
				<?php echo get_link_items(); ?>
			</div>
		</article>
	<?php
		$post = get_post();
		if (iro_opt('article_auto_toc', 'true') && (check_title_tags($post->post_content))) echo '<div class="has-toc have-toc"></div>';
	?>
	<?php get_template_part('layouts/sidebox'); //加载目录容器?> 
	<?php endwhile; ?>
<?php
get_footer();
