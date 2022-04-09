<?php

/**
Template Name: My Anime List追番模板
 */
get_header();
?>
	<meta name="referrer" content="same-origin">
	<style>
        .comments{display: none}
        .site-content{max-width:1280px}
	</style>
	</head>

<?php while(have_posts()) : the_post(); ?>
	<?php if(!iro_opt('patternimg') || !get_post_thumbnail_id(get_the_ID())) { ?>
		<span class="linkss-title"><?php the_title();?></span>
	<?php } ?>
	<article <?php post_class("post-item"); ?>>
		<?php the_content(); ?>
		<section class="bangumi">
			<?php if (iro_opt('bilibili_id') ):?>
			<div class="row">
				<?php
				$bgm2 = new \Sakura\API\MyAnimeList();
				echo $bgm2->get_all_items();
				?>
				<?php else: ?>
					<div class="row">
						<p> <?php _e("Please fill in the Bilibili UID in Sakura Options.","sakura"); ?></p>
					</div>
				<?php endif; ?>
		</section>
	</article>
<?php endwhile; ?>

<?php
get_footer();