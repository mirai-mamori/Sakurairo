<?php

/**
 Template Name: 追番模板
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
                <?php if (iro_opt('bangumi_source') == 'bilibili'):?>
                    <?php if (iro_opt('bilibili_id') ):?>
                        <div class="row">
                        <?php
                        $bgm = new \Sakura\API\Bilibili();
                        echo $bgm->get_bgm_items();
                        ?>
                    <?php else: ?>
                        <div class="row">
                            <p> <?php _e("Please fill in the Bilibili UID in Sakura Options.","sakura"); ?></p>
                        </div>
                    <?php endif; ?>
                <?php else:?>
                    <?php if (iro_opt('my_anime_list_username') ):?>
                        <div class="row">
                        <?php
                        $bgm = new \Sakura\API\MyAnimeList();
                        echo $bgm->get_all_items();
                        ?>
                    <?php else: ?>
                        <div class="row">
                            <p> <?php _e("Please fill in the My Anime List Username in Sakura Options.","sakura"); ?></p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </section>
	</article>
<?php endwhile; ?>

<?php
get_footer();