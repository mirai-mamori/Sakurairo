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
    $layout = iro_opt('exhibition_area_style') == 'left_and_right' ? 'feature_v2' : 'feature';
    get_template_part('layouts/' . $layout);
}
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <h1 class="main-title">
            <i class="<?php echo esc_attr(iro_opt('post_area_icon', 'fa-regular fa-bookmark')); ?>" aria-hidden="true"></i>
            <br>
            <?php echo esc_html(iro_opt('post_area_title', '文章')); ?>
        </h1>
        <?php
        if (have_posts()) :
            if (is_home() && !is_front_page()) : ?>
                <header>
                    <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                </header>
            <?php endif;
            get_template_part('tpl/content', 'thumb');
        else :
            get_template_part('tpl/content', 'none');
        endif;
        ?>
    </main>
    <?php if (iro_opt('pagenav_style') == 'ajax') { ?>
        <div id="pagination"><?php next_posts_link(__(' Previous', 'sakurairo')); ?></div>
        <div id="add_post"><span id="add_post_time" style="visibility: hidden;" title="<?php echo esc_attr(iro_opt('page_auto_load', '')); ?>"></span></div>
    <?php } else { ?>
        <nav class="navigator">
            <?php 
            echo paginate_links(array(
                'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))), // 设置分页的基础链接
                'format' => '?paged=%#%', // 分页格式
                'current' => max(1, get_query_var('paged')), // 当前页码
                'total' => $wp_query->max_num_pages, // 总页数
                'prev_text' => '<i class="fa-solid fa-angle-left"></i>', // 自定义上一页按钮
                'next_text' => '<i class="fa-solid fa-angle-right"></i>' // 自定义下一页按钮
            ));
            ?>
        </nav>
    <?php } ?>
</div>

<?php
get_footer();
?>
