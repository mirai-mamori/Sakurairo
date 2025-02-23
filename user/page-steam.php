<?php

/**
 * Template Name: Steam库模板
 */
get_header(); 
?>
<meta name="referrer" content="same-origin">
<style>

    .site-content {
        max-width: 1280px;
    }

    .steam-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        padding: 20px;
        margin: 0 auto;
    }

    .steam-card {
        display: block;
        width: 280px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
        transition: all 0.3s ease;
    }

    .steam-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 4px 12px rgba(0, 0, 0);
    }

    .steam-info {
        padding: 10px;
    }

    .steam-title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 8px;
        color: var(--theme-skin, #505050);
    }

    body.dark .steam-title {
        color: #cbcbcb;
    }

    .steam-desc {
        font-size: 12px;
        line-height: 1.4;
        margin-bottom: 2px;
        color: var(--theme-skin, #505050);
    }

    body.dark .steam-desc {
        color: #cbcbcb;
    }

</style>
</head>

<?php while (have_posts()) : the_post(); ?>
<?php 
    if (!iro_opt('patternimg') || !get_post_thumbnail_id(get_the_ID())) { 
    ?>
        <span class="linkss-title"><?php the_title(); ?></span>
    <?php 
    } 
    ?>

<article <?php post_class("post-item"); ?>>
    <?php the_content('', true); ?>
    <section class="steam-row">
        <?php 
        $steam = new \Sakura\API\Steam();
        echo $steam->get_steam_items();
        ?>
    </section>
</article>
<?php endwhile; ?>

<?php get_footer(); ?> 