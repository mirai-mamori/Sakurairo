<?php
/**
 * Template Name: 相册模板
 */
get_header();
?>
<span class="linkss-title">
    <?php the_title();?>
</span>
<div id="photo-container" data-id="<?= get_queried_object()->ID?>">
</div>
<?php
        wp_enqueue_script('page_photo', $core_lib_basepath . '/js/page-photo.js', array(), IRO_VERSION, true);
        wp_enqueue_style('page_photo', $core_lib_basepath . '/js/page-photo.css', array(), IRO_VERSION);
?>

<?php
get_footer();
?>