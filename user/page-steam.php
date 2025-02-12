<?php

/**
 * Template Name: Steam库模板
 */
get_header(); 
?>
<meta name="referrer" content="same-origin">
<style>
    .comments {
        display: none;
    }
    .site-content {
        max-width:1280px;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        padding: 20px;
        margin: 0 auto;
    }

    .anime-card {
        display: block;
        width: 280px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .anime-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .anime-info {
        padding: 10px;
    }

    .anime-desc {
        font-size: 12px;
        color: #666;
        line-height: 1.4;
        margin-bottom: 2px;
    }
</style>
</head>

<?php while(have_posts()) : the_post(); ?>
<?php 
    if (!iro_opt('patternimg') || !get_post_thumbnail_id(get_the_ID())) { 
    ?>
        <span class="linkss-title"><?php the_title(); ?></span>
    <?php 
    } 
    ?>

<article <?php post_class("post-item"); ?>>
    <?php the_content( '', true ); ?>
    <section class="steamlibrary">
        <div class="row">
            <?php 
            $steam = new \Sakura\API\Steam();
            echo $steam->get_steam_items();
            ?>
        </div>
    </section>
</article>
<?php endwhile; ?>

<?php get_footer(); ?> 