<?php
/*
  Template Name: Steam Library Template
*/
get_header();
?>

<style>
    .site-content {
        max-width: 1280px;
    }

    span.linkss-title {
        font-size: 30px;
        text-align: center;
        display: block;
        margin: 6.5% 0 7.5%;
        letter-spacing: 2px;
        font-weight: var(--global-font-weight);
    }

    .steam-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin: 0 auto;
        justify-content: center;
        margin-top: 20px;
    }
    .steam-card {
        width: 23%;
        overflow: hidden;
        position: relative;
        border-radius: 12px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.06);
        transition: all 0.4s ease;
        transform: translateY(0);
        border: 1px solid rgba(255, 255, 255, 0.8);
        will-change: transform, box-shadow;
        display: flex;
        flex-direction: column;
        background-color: rgba(255, 255, 255, 0.7);
    }
    .steam-card-image {
        position: relative;
        overflow: hidden;
        aspect-ratio: 16 / 9;
        border-radius: 12px 12px 0 0;
    }
    .steam-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s cubic-bezier(0.25, 1, 0.5, 1);
        will-change: transform;
    }
    
    .steam-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 35px rgba(0, 0, 0, 0.1);
        background-color: rgba(255, 255, 255, 0.9);
    }
    
    .steam-card:hover img {
        transform: scale(1.07);
    }
    
    .steam-card-image, .steam-info {
        flex: 0 0 auto;
    }
    .steam-title-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        padding: 12px 15px;
        background: linear-gradient(to bottom, 
                    rgba(0, 0, 0, 0.7) 0%, 
                    rgba(0, 0, 0, 0.4) 60%, 
                    transparent 100%);
        transition: all 0.5s cubic-bezier(0.25, 1, 0.5, 1);
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
        z-index: 2;
        transform: translateY(0);
        mask-image: linear-gradient(180deg, #000000 20%, #0000004d);
        -webkit-mask-image: linear-gradient(180deg, #000000 20%, #0000004d);
    }
    
    .steam-title {
        margin: 0;
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: all 0.5s ease;
        opacity: 1;
        transform: translateY(0);
    }
    
    .steam-card:hover .steam-title-overlay {
        opacity: 0;
        transform: translateY(-15px);
    }
    
    .steam-card:hover .steam-title {
        opacity: 0;
        transform: translateY(-5px);
    }
    .steam-info {
        padding: 12px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: rgba(255, 255, 255, 0.7);
        border-radius: 0 0 12px 12px;
        transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);
        will-change: transform;
    }
    .steam-stat {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 500;
        color: #505050;
        transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);
    }
    
    .steam-stat:nth-child(2) {
        transition-delay: 0.05s;
    }
    
    /* Dark mode styles */
    body.dark .steam-card {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(70, 70, 70, 0.3);
        background-color: var(--dark-bg-secondary);
    }

    body.dark .steam-card:hover {
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
    }
    
    body.dark .steam-info {
        background-color: var(--dark-bg-secondary);
    }
    
    body.dark .steam-stat {
        color: var(--dark-text-secondary);
    }
    
    .steam-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(120deg, 
                    rgba(255, 255, 255, 0) 30%, 
                    rgba(255, 255, 255, 0.08) 50%, 
                    rgba(255, 255, 255, 0) 70%);
        background-size: 200% 100%;
        z-index: 1;
        opacity: 0;
        transition: all 1.2s ease;
    }
    
    .steam-card:hover::before {
        opacity: 1;
        background-position: 100% 0;
    }
    
    .steam-card::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 12px;
        padding: 1px;
        background: linear-gradient(120deg, 
                    transparent, 
                    rgba(255, 255, 255, 0.3), 
                    transparent);
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity 0.8s ease;
    }
    
    .steam-card:hover::after {
        opacity: 1;
    }

    /* Responsive styles */
    @media (max-width: 1024px) {
        .steam-card {
            width: 31%;
        }
    }
    
    @media (max-width: 768px) {
        .steam-card {
            width: 96.5%;
        }
        .steam-title-overlay{
            padding: 25px 30px;
        }
        .steam-title{
            font-size: 20px;
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