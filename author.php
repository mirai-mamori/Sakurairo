<?php

get_header();

?>
<div class="author_info">
	<div class="avatar">
		<?php echo get_avatar(get_the_author_meta('ID')); ?>
	</div>
	<div class="author-center">
		<h3><?php the_author(); ?></h3>
		<div class="description">
			<?php 
			$description = get_the_author_meta('description');
			echo $description ? nl2br($description) : __("No personal profile set yet", "sakurairo"); 
			?>
		</div>
	</div>
</div>
<style>
	.author_info {
    min-width: 200px;
    max-width: 70%;
    width: fit-content;
    position: relative;
	height: 110px;
    left: 50%;
    transform: translateX(-50%);
    margin: 10% 0;
    float: left;
    display: flex;
    box-shadow: 0 1px 30px -4px var(--friend-link-shadow);
    background: rgba(255, 255, 255, 0.5);
    padding: 12px 12px;
    -webkit-transition: all .8s;
    transition: all .8s;
    border-radius: 10px;
    border: 1.5px solid #FFFFFF;
    flex-direction: column;
    font-weight: var(--global-font-weight);
}

	.author_info .avatar {
		float: left;
        height: 75px;
        width: 75px;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        top: -24%;
        display: flex;
		float: left;
		box-shadow: none;
	}

	.author_info .avatar img {
		border-radius: 100%;
		vertical-align: middle;
	}

	.author_info .author-center {
		position: relative;
		margin-top: 10px;
		-webkit-transition: all .8s;
		transition: all .8s;
		Color: #505050;
	}

	.author_info .author-center h3 {
		font-weight: 700;
		font-size: 24px;
		margin: 0;
		display: inline-flex;
		position: relative;
        left: 50%;
        transform: translateX(-50%);
		overflow: hidden;
        max-height: 36px;
        height: fit-content;
	}

	.author_info .author-center .description {
		font-size: 14px;
		font-weight: var(--global-font-weight);
		text-align: center;
		overflow: hidden;
		max-height: 20px;
		height: fit-content;
	}
</style>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php if (have_posts()) : ?>
			<?php get_template_part('tpl/content', 'thumb'); ?>
			<div class="clearer"></div>
		<?php else : ?>
			<?php get_template_part('tpl/content', 'none'); ?>
		<?php endif; ?>

	</main><!-- #main -->
	<?php if (iro_opt('pagenav_style') == 'ajax') : ?>
		<div id="pagination"><?php next_posts_link(__(' Previous', 'sakurairo')); ?></div>
		<div id="add_post"><span id="add_post_time" style="visibility: hidden;" title="<?php echo iro_opt('page_auto_load', ''); ?>"></span></div>
	<?php else : ?>
		<nav class="navigator">
			<?php previous_posts_link('<i class="fa-solid fa-angle-left"></i>'); ?><?php next_posts_link('<i class="fa-solid fa-angle-right"></i>'); ?>
		</nav>
	<?php endif; ?>
</div><!-- #primary -->

<?php
get_footer();
?>
