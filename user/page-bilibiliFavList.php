<?php

/**
  Template Name: Bilibili FavList Template
 */
get_header();
?>
<meta name="referrer" content="same-origin" />
<style>
    .comments {
        display: none;
    }

    .site-content {
        max-width: 1280px;
    }
</style>
</head>

<?php while (have_posts()) : the_post(); ?>
    <?php $bgm = (iro_opt('bilibili_id')) ? new \Sakura\API\BilibiliFavList() : null; ?>
    <?php if (!empty($bgm) && (!iro_opt('patternimg') || !get_post_thumbnail_id(get_the_ID()))) : ?>
        <span class="linkss-title"><?php the_title(); ?></span>
    <?php endif; ?>
    <article <?php post_class("post-item"); ?>>
        <?php the_content('', true); ?>

        <?php if (!empty($bgm)) : ?>
            <section class="fav-list">
                <?php echo $bgm->get_folders(); ?>
            </section>
        <?php else : ?>
            <div class="row">
                <p> <?php _e("Please fill in the Bilibili UID in Sakura Options.", "sakurairo"); ?></p>
            </div>
        <?php endif; ?>
    </article>
<?php endwhile; ?>

<script src="<?php global $shared_lib_basepath;
                echo $shared_lib_basepath ?>/js/page-bilibilifav.js" type="text/javascript"></script>
<?php
get_footer();
