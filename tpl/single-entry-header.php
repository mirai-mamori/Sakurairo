<header class="entry-header">
    <h1 class="entry-title"><?php the_title(); ?></h1>
    <?php
    require_once get_stylesheet_directory() . '/tpl/entry-census.php';
    echo get_entry_census_html();
    ?>
    <hr>
</header>