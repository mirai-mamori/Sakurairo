<?php 

/*
 Template Name: Friendly Links Template
 */

get_header(); 

?>
<style>
	.links ul {
		margin-top: 50px;
		width: 100%;
		display: inline-block;
	}

	.links ul li {
		width: 22%;
		float: left;
		box-shadow: 0 1px 30px -4px var(--friend-link-shadow);
		background: rgba(255, 255, 255, 0.5);
		padding: 12px 12px;
		margin: 12px 12px;
		position: relative;
		overflow: hidden;
		border-radius: 10px;
		border: 1.5px solid #FFFFFF;
	}

	.links ul li:hover {
		box-shadow: 0 1px 20px 10px var(--friend-link-shadow);
		background: rgba(255, 255, 255, 0.8);
	}

	.links ul li img {
		float: left;
		padding: 1px;
		opacity: 1;
		transform: rotate(0);
		-webkit-transform: rotate(0);
		margin: 3px 3px 0;
		width: 90px;
		height: 90px;
		border-radius: 100%;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
	}

	.links ul li:hover img {
		transform: rotate(360deg);
		-webkit-transform: rotate(360deg);
	}

	.links {
		margin-bottom: 80px;
	}

	.links ul li:hover:before {
		width: 180%;
	}

	.link-title {
		font-weight: 600;
		color: #6D6D6D;
		padding-left: 10px;
		border-left: none;
		border-color: var(--theme-skin);
		margin: 50px 0 10px;
		text-underline-offset: 10px;
		text-decoration: underline solid var(--friend-link-title, #ffeeeB);
	}

	span.sitename {
		font-size: 20px;
		margin-top: 84px;
		margin-left: 8px;
		margin-right: 8px;
		color: #505050;
		padding-bottom: 10px;
		display: block;
		transition: all 0.4s ease-in-out;
		-webkit-transition: all 0.4s ease-in-out;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		font-weight: var(--global-font-weight);
		text-underline-offset: 10px;
		text-decoration: underline wavy var(--friend-link-title, #ffeeeB)
	}

	.linkdes {
		font-size: 14px;
		margin-left: 8px;
		margin-right: 8px;
		text-overflow: ellipsis;
		color: #505050;
		overflow: hidden;
		white-space: nowrap;
		line-height: 30px;
		transition: all 0.4s ease-in-out;
		-webkit-transition: all 0.4s ease-in-out;
	}

	body.dark .links ul li {
		box-shadow: var(--dark-shadow-normal);
		background: var(--dark-bg-secondary);
		border: 1.5px solid var(--dark-border-color);
	}

	body.dark .links ul li:hover {
		box-shadow: 0 1px 30px -2px var(--friend-link-title) !important;
		background: var(--dark-bg-hover);
	}

	body.dark .links ul li img{
		box-shadow: 0 4px 12px var(--dark-header-shadow);
	}

	body.dark .link-title {
		color: var(--dark-text-secondary) !important;
	}

	body.dark .linkdes,
	body.dark span.sitename {
		color: var(--dark-text-primary);
	}
	
</style>
	<?php while (have_posts()) : the_post(); ?>
		<?php $post = get_post(); ?>
		<?php if (!iro_opt('patternimg') || !get_post_thumbnail_id(get_the_ID())) : ?>
			<span class="linkss-title"><?php echo esc_html(get_the_title()); ?></span>
		<?php endif; ?>
		<article <?php post_class("post-item"); ?>>
			<?php if (iro_opt('article_auto_toc', 'true') && check_title_tags($post->post_content)) : //加载目录 ?>
				<div class="has-toc have-toc"></div>
			<?php endif; ?>
			<div class="entry-content">
				<?php the_content('', true); ?>
			</div>			
			<div class="links">
				<?php echo get_link_items(); ?>
			</div>
		</article>
		<?php get_template_part('layouts/sidebox'); //加载目录容器 ?> 
	<?php endwhile; ?>
<?php
get_footer();
?>
