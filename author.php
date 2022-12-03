<?php

get_header();

?>
<div class="author_info">
	<div class="avatar" style="height: 75px;width: 75px;">
	    <?php echo get_avatar($author) ?>
	</div>
	<div class="author-center">
		<h3><?php the_author() ?></h3>
		<div class="description"><?php echo get_the_author_meta('description') ? get_the_author_meta('description') : __("No personal profile set yet","sakurairo"); ?></div>
	</div>
</div>
<style>
	.author_info {
		margin-top: 50px;
		overflow: hidden;
		padding: 40px 0;
		position: relative;
		font-weight: var(--global-font-weight);
	}

	.author_info .avatar {
		float: left;
		margin-right: 30px;
		margin-left: 0px;
	}

	.author_info .avatar img {
		border-radius: 100%;
		border: 2px solid #fff;
		background: #fff;
		vertical-align: middle;
	}

	.author_info .author-center {
		line-height: 27px;
		padding-top: 10px;
	}

	.author_info .author-center h3 {
		font-weight: 700;
		font-size: 30px;
		line-height: 1;
		margin-bottom: 5px;
		display: inline;
	}

	.author_info .author-center .description {
		font-size: 14px;
		color: rgba(0, 0, 0, .4);
		line-height: 1.6;
		font-weight: var(--global-font-weight);
	}
</style>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php
		if (have_posts()) :
			/* Start the Loop */
			if (iro_opt('post_list_style') == 'akinastyle') {
				while (have_posts()) : the_post();
					get_template_part('tpl/content', get_post_format());
				endwhile;
			} else {
				get_template_part('tpl/content', 'thumb');
			}
		?>
			<div class="clearer"></div>
		<?php else :

			get_template_part('tpl/content', 'none');

		endif; ?>

	</main><!-- #main -->
	<?php if (iro_opt('pagenav_style') == 'ajax') { ?>
		<div id="pagination"><?php next_posts_link(__(' Previous', 'sakurairo')); ?></div>
		<div id="add_post"><span id="add_post_time" style="visibility: hidden;" title="<?php echo iro_opt('page_auto_load', ''); ?>"></span></div>
	<?php } else { ?>
		<nav class="navigator">
			<?php previous_posts_link('<i class="iconfont icon-back"></i>') ?><?php next_posts_link('<i class="iconfont icon-right"></i>') ?>
		</nav>
	<?php } ?>
</div><!-- #primary -->

<?php
get_footer();
