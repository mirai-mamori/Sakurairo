<?php

/**
 * Template Name: Steam Library Template
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
        margin: 0 auto;
        justify-content: center;
    }

    .steam-card {
        display: flex;
        flex-direction: column;
        width: 23%;
        background: rgba(255, 255, 255, 0.6);
        box-shadow: 0 1px 30px -4px #e8e8e8;
        overflow: hidden;
        border-radius: 10px;
        border: 1.5px solid #FFFFFF;
        transition: all 0.3s ease;
    }

    .steam-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 1px 20px 10px #e8e8e8;
        background: rgba(255, 255, 255, 0.8);
    }

    .steam-desc {
        font-size: 12px;
        line-height: 1.4;
        margin-bottom: 2px;
        color: var(--theme-skin, #505050);
    }

    .steam-info {
        padding: 15px;
    }

    .steam-title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 8px;
        color: var(--theme-skin, #505050);
        font-weight: bold;
        font-size: 18px;
    }

    body.dark .steam-title ,body.dark .steam-desc{
        color: #cbcbcb;
    }
    
    body.dark .steam-card {
        box-shadow: var(--dark-shadow-normal);
        background: var(--dark-bg-secondary);
        border: 1.5px solid var(--dark-border-color);
    }

    body.dark .steam-card:hover {
        box-shadow: 0 1px 30px -2px var(--dark-shadow-hover);
        background: var(--dark-bg-hover);
    }

    @media (max-width: 860px) {
    .steam-card {
        width: 100%;
    }
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
    <section class="steam-row have-columns row">
        <?php 
        $steam = new \Sakura\API\Steam();
        echo $steam->get_steam_items();
        ?>
    </section>
</article>
<?php endwhile; ?>

<?php get_footer(); ?> 