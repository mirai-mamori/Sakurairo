<?php
get_header();

// 获取组件顺序数据
$component_order = json_decode(get_theme_mod('component_order', '[]'), true);

// 默认组件顺序
$default_order = ['bulletin', 'static_page', 'exhibition', 'primary'];
$component_order = !empty($component_order) ? $component_order : $default_order;

// 按顺序动态渲染组件
foreach ($component_order as $component) {
    switch ($component) {
        // 公告栏模块
        case 'bulletin':
            if (iro_opt('bulletin_board') == '1') {
                $text = iro_opt('bulletin_text');
                ?>
                <div class="notice" style="margin-top:60px">
                    <?php if (iro_opt('bulletin_board_icon', 'true')) : ?>
                        <div class="notice-icon"><?php esc_html_e('Notice', 'sakurairo'); ?></div>
                    <?php endif; ?>
                    <div class="notice-content">
                        <?php if (strlen($text) > 142) : ?>
                            <div class="scrolling-text"><?php echo esc_html($text); ?></div>
                        <?php else : ?>
                            <?php echo esc_html($text); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
            }
            break;

        // 静态页面
        case 'static_page':
            $static_page_id = get_theme_mod("home_static_page", 0);
            if ($static_page_id && ($static_page = get_post($static_page_id))) :
                ?>
                <section class="custom-static-section">
                    <h1 class="main-title static-page-title">
                        <?php echo esc_html(get_the_title($static_page)); ?>
                    </h1>
                    <div class="static-page-content">
                        <?php echo apply_filters('the_content', $static_page->post_content); ?>
                    </div>
                </section>
            <?php
            endif;
            break;

        // 特色区域
        case 'exhibition':
            if (iro_opt('exhibition_area') == '1') {
                get_template_part('layouts/' . 'feature');
            }
            break;

        // 文章列表
        case 'primary':
            ?>
            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">
                    <h1 class="main-title posts-area-title">
                        <i class="<?php echo esc_attr(iro_opt('post_area_icon', 'fa-regular fa-bookmark')); ?>" aria-hidden="true"></i>
                        <br>
                        <?php echo esc_html(iro_opt('post_area_title', '文章列表')); ?>
                    </h1>

                    <?php if (have_posts()) : ?>
                        <?php if (is_home() && !is_front_page()) : ?>
                            <header class="archive-header">
                                <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                            </header>
                        <?php endif; ?>

                        <?php get_template_part('tpl/content', 'thumb'); ?>

                    <?php else : ?>
                        <?php get_template_part('tpl/content', 'none'); ?>
                    <?php endif; ?>
                </main>

                <?php if (iro_opt('pagenav_style') == 'ajax') : ?>
                    <div id="pagination"><?php next_posts_link(__(' Previous', 'sakurairo')); ?></div>
                    <div id="add_post">
                        <span id="add_post_time" style="visibility: hidden;" 
                            title="<?php echo esc_attr(iro_opt('page_auto_load', '')); ?>">
                        </span>
                    </div>
                <?php else : ?>
                    <nav class="traditional-pagination">
                        <?php echo paginate_links(array(
                            'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                            'format' => '?paged=%#%',
                            'current' => max(1, get_query_var('paged')),
                            'total' => $wp_query->max_num_pages,
                            'prev_text' => '<i class="fa-solid fa-angle-left"></i>',
                            'next_text' => '<i class="fa-solid fa-angle-right"></i>'
                        )); ?>
                    </nav>
                <?php endif; ?>
            </div>
            <?php
            break;
    }
}

get_footer();
?>