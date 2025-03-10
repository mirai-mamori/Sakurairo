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