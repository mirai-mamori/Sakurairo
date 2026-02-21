<?php
/*
Template Name: Categories Page
*/
get_header();
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php if (!iro_opt('patternimg') || !get_post_thumbnail_id(get_the_ID())) { ?>
            <header class="page-header">
                <h1 class="cat-title"><?php the_title(); ?></h1>
                <span class="cat-des"><?php the_content(); ?></span>
            </header>
        <?php } ?>
        <div class="taxonomy-container">
            <?php
            $categories = get_terms(array(
                'taxonomy' => 'category',
                'orderby' => 'count',
                'order' => 'DESC',
                'hide_empty' => true,
            ));
            if (!empty($categories) && !is_wp_error($categories)) {
                foreach ($categories as $category) {
                    echo '<a href="' . get_term_link($category) . '" class="taxonomy-item">';
                    echo '<span class="taxonomy-name">' . $category->name . '</span>';
                    echo '<span class="taxonomy-count">' . $category->count . '</span>';
                    echo '</a>';
                }
            }
            ?>
        </div>
    </main>
</div>
<style>
.taxonomy-container {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin: 2rem 0;
    padding: 0 1rem;
}
.taxonomy-item {
    padding: 10px 20px;
    background: rgba(255, 255, 255, 0.6);
    border-radius: 30px;
    display: flex;
    align-items: center;
    border: 1.5px solid #e0e0e0;
    transition: all 0.3s cubic-bezier(.4,2,.6,1);
    color: #555;
    text-decoration: none;
}
.taxonomy-item:hover {
    background: var(--theme-skin-matching, #efefef);
    border-color: var(--theme-skin-matching, #505050);
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    color: var(--theme-skin-matching, #222);
}
.taxonomy-name {
    margin-right: 10px;
    font-weight: 600;
}
.taxonomy-count {
    background: rgba(0, 0, 0, 0.08);
    padding: 2px 10px;
    border-radius: 12px;
    font-size: 0.85em;
    color: #888;
}
body.dark .taxonomy-item {
    background: rgba(30, 30, 30, 0.7);
    border-color: #333;
    color: #ccc;
}
body.dark .taxonomy-item:hover {
    background: rgba(45, 45, 45, 0.9);
    border-color: var(--theme-skin-matching, #fff);
    color: #fff;
}
body.dark .taxonomy-count {
    background: rgba(255, 255, 255, 0.1);
    color: #aaa;
}
</style>
<?php get_footer(); ?>
