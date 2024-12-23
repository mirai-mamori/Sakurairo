<?php

/**
 * Template Name: 追番模板
 */
get_header(); 
?>
<meta name="referrer" content="same-origin">
<style>
.comments{display: none}
.site-content{max-width:1280px}
</style>
</head>

<?php while (have_posts()) : the_post(); ?>
    <?php if (!iro_opt('patternimg') || !get_post_thumbnail_id(get_the_ID())) : ?>
        <span class="linkss-title"><?php the_title(); ?></span>
    <?php endif; ?>

    <article <?php post_class("post-item"); ?>>
        <?php the_content('', true); ?>
        <section class="bangumi">
            <?php
            $bangumi_source = iro_opt('bangumi_source');
            $bilibili_id = iro_opt('bilibili_id');
            $mal_username = iro_opt('my_anime_list_username');
            $bangumi_id = iro_opt('bangumi_id');
            ?>

            <div class="row">
                <?php if ($bangumi_source === 'bilibili') : ?>
                    <?php if ($bilibili_id) : ?>
                        <?php
                        $bgm = new \Sakura\API\Bilibili();
                        echo $bgm->get_bgm_items();
                        ?>
                    <?php else : ?>
                        <p><?php _e("Please fill in the Bilibili UID in Sakura Options.", "sakurairo"); ?></p>
                    <?php endif; ?>
                <?php elseif ($bangumi_source === 'bangumi') : ?>
                    <?php if (!empty($bangumi_id)) : ?>
                        <?php
                        $bgmList = new \Sakura\API\BangumiList();
                        echo $bgmList->get_bgm_items($bangumi_id);
                        ?>
                    <?php else : ?>
                        <p><?php _e("Please fill in the Bangumi UID in Sakura Options.", "sakurairo"); ?></p>
                    <?php endif; ?>
                <?php elseif ($mal_username) : ?>
                    <?php
                    $bgm = new \Sakura\API\MyAnimeList();
                    echo $bgm->get_all_items();
                    ?>
                <?php else : ?>
                    <p><?php _e("Please fill in the My Anime List Username in Sakura Options.", "sakurairo"); ?></p>
                <?php endif; ?>
            </div>
        </section>
    </article>
<?php endwhile; ?>

<?php
get_footer();