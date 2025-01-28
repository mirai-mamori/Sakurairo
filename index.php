<?php
get_header();

if (iro_opt('bulletin_board') == '1') {
    $text = iro_opt('bulletin_text');
    ?>
    <div class="notice" style="margin-top:60px">
        <?php if (iro_opt('bulletin_board_icon', 'true')) : ?>
            <div class="notice-icon"><?php esc_html_e('Notice', 'sakurairo'); ?></div>
        <?php endif; ?>
        <div class="notice-content">
            <?php if (strlen($text) > 142) { ?>
                <div class="scrolling-text"><?php echo esc_html($text); ?></div>
            <?php } else { ?>
                <?php echo esc_html($text); ?>
            <?php } ?>
        </div>
    </div>
    <?php
}

if (iro_opt('exhibition_area') == '1') {
    get_template_part('layouts/' . 'feature');
}
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

<?php
// 获取主页显示选项
$show_on_front = get_option('show_on_front');
if ($show_on_front === 'page') {
    // 获取静态页面
    $posts_page_id = get_option('page_for_posts');
    if ($posts_page_id) { ?>
        <h1 class="main-title">
            <br>
            <?php echo esc_html(get_the_title($posts_page_id)); ?>
        </h1>
        <?php
        $posts_page = get_post($posts_page_id);
        echo apply_filters('the_content', $posts_page->post_content);
    }
}
//文章容器开始
if ($show_on_front === 'posts' || !get_theme_mod('hide_homepage_post_list_control', false)) {
    ?>
    <h1 class="main-title">
        <i class="<?php echo esc_attr(iro_opt('post_area_icon', 'fa-regular fa-bookmark')); ?>" aria-hidden="true"></i>
        <br>
        <?php echo esc_html(iro_opt('post_area_title', '文章')); ?>
    </h1>

    <?php
    if (have_posts()) {
        if (is_home() && !is_front_page()) { ?>
            <header>
                <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
            </header>
        <?php }
        get_template_part('tpl/content', 'thumb');
    } else {
        get_template_part('tpl/content', 'none');
    }
} ?>
</main>
<?php //文章容器结束

        // 处理分页逻辑
        if (iro_opt('pagenav_style') == 'ajax') { ?>
            <div id="pagination"><?php next_posts_link(__(' Previous', 'sakurairo')); ?></div>
            <div id="add_post">
                <span id="add_post_time" style="visibility: hidden;" title="<?php echo esc_attr(iro_opt('page_auto_load', '')); ?>"></span>
            </div>
        <?php } else { ?>
            <nav class="navigator">
                <?php
                echo paginate_links(array(
                    'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                    'format' => '?paged=%#%',
                    'current' => max(1, get_query_var('paged')),
                    'total' => $wp_query->max_num_pages,
                    'prev_text' => '<i class="fa-solid fa-angle-left"></i>',
                    'next_text' => '<i class="fa-solid fa-angle-right"></i>'
                ));
                ?>
            </nav>
        <?php }
?>
</div>

<?php
get_footer();
?>

