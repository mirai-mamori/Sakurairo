<?php

/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Sakurairo
 */

get_header(); ?>
<section id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
    
    <?php
    $paged = max(1, get_query_var('paged'));
    $search_query = get_search_query();
    $sticky_posts = get_option('sticky_posts');
    $show_pages_filter = true;

    // 默认勾选的选项
    $default_checked = array('post');
    if (iro_opt('search_for_shuoshuo')) $default_checked[] = 'shuoshuo';
    if (iro_opt('search_for_pages')) {
        if (iro_opt('only_admin_can_search_pages')) {
            //仅限管理员检索页面
            if (current_user_can('administrator')) {
                $default_checked[] = 'page';
            } else {
                $show_pages_filter = false;
            }
        } else {
            $default_checked[] = 'page';
        }
    } else {
        $show_pages_filter = false;
    }

    // 获取当前查询参数中的content_type内容
    $content_types = isset($_GET['content_type']) ? explode(',', $_GET['content_type']) : $default_checked;

    // 搜索页标题
    if (!iro_opt('patternimg') || !get_random_bg_url()) : ?>
        <header class="page-header">
            <h1 class="page-title"><?php printf(esc_html__('Search result: %s', 'sakurairo'), '<span>' . esc_html($search_query) . '</span>'); ?></h1>
        </header><!-- .page-header -->
    <?php endif; ?>

    <?php
    $all_results_args = array(
        'post_type' => $content_types,
        'post_status' => 'publish',
        's' => $search_query,
        'posts_per_page' => -1,
        'orderby' => 'relevance',
        'order' => 'DESC',
    );

    if (iro_opt('only_admin_can_search_pages')) {
        // 只允许管理员检索页面
        if (!current_user_can('administrator')) {
            // 不是管理员就移除page
            $all_results_args['post_type'] = array_diff($content_types, array('page'));
        }
    }

    $all_results_args['post__not_in'] = array_map('intval', explode(',', iro_opt('custom_exclude_search_results')));
    //排除自定义内容id

    $all_results_query = new WP_Query($all_results_args);

    if (iro_opt('enable_search_filter')) : ?>
        <!-- 筛选器部分 -->
        <div id="filter-container">
            <div class="filter-count">
                <?php echo $all_results_query->found_posts; ?> <?php echo __('results found', 'sakurairo'); ?>
            </div>

            <form id="search-filter-form" action="" method="GET">
                <?php if ($search_query) : ?>
                    <input type="hidden" name="s" value="<?php echo esc_attr($search_query); ?>">
                <?php endif; ?>

                <label>
                    <input type="checkbox" name="content_type[]" value="post" <?php echo in_array('post', $content_types) ? 'checked' : ''; ?>> <?php echo __('Post', 'sakurairo'); ?>
                </label>

                <?php if (iro_opt('search_for_shuoshuo')) : ?>
                    <label>
                        <input type="checkbox" name="content_type[]" value="shuoshuo" <?php echo in_array('shuoshuo', $content_types) ? 'checked' : ''; ?>> <?php echo __('shuoshuo', 'sakurairo'); ?>
                    </label>
                <?php endif; ?>

                <?php if ($show_pages_filter) : ?>
                    <label>
                        <input type="checkbox" name="content_type[]" value="page" <?php echo in_array('page', $content_types) ? 'checked' : ''; ?>> <?php echo __('Page', 'sakurairo'); ?>
                    </label>
                <?php endif; ?>
            </form>

            <div id="filter-toggle" title="<?php echo __('If no option is selected, all results are retrieved by default', 'sakurairo'); ?>" onclick="applyFilter()">
                <i class="fas fa-filter"></i> <?php echo __('Click to filter', 'sakurairo'); ?>
        </div>
    </div>
    <?php endif; ?>

    <script>
    function applyFilter() {
        var filterForm = document.getElementById('search-filter-form');
        var checkboxes = filterForm.querySelectorAll('input[name="content_type[]"]');
        var selected = [];
        checkboxes.forEach(function (checkbox) {
            if (checkbox.checked) selected.push(checkbox.value);
        });

        var searchParams = new URLSearchParams(window.location.search);
        searchParams.set('content_type', selected.join(','));
        var newUrl = window.location.pathname + '?' + searchParams.toString();

        window.location.href = newUrl;
    }
    </script>

    <?php
    // 结果处理，排序，展示
    $all_results = [];
    if ($all_results_query->have_posts()) :
        if (iro_opt('sticky_pinned_content')) {
            // 置顶文章是否在检索中也置顶
            $sticky_results = [];
            $non_sticky_results = [];
            
            while ($all_results_query->have_posts()) : $all_results_query->the_post();
                if (in_array(get_the_ID(), $sticky_posts)) {
                    $sticky_results[] = $post;
                } else {
                    $non_sticky_results[] = $post;
                }
            endwhile;
            
            $all_results = array_merge($sticky_results, $non_sticky_results);
        } else {
            while ($all_results_query->have_posts()) : $all_results_query->the_post();
                $all_results[] = $post;
            endwhile;
        }
    endif;
    wp_reset_postdata();

    // 内容分页
    $total_results = count($all_results);
    $posts_per_page = 10;
    $total_pages = ceil($total_results / $posts_per_page);
    $current_page_results = array_slice($all_results, ($paged - 1) * $posts_per_page, $posts_per_page);

    // 输出当前页内容
    if (!empty($current_page_results)) :
        foreach ($current_page_results as $post) :
            setup_postdata($post);
            get_template_part('tpl/content', 'thumbcard');
        endforeach;

        the_posts_pagination(array(
            'total' => $total_pages,
            'current' => $paged,
        ));
    else :
        ?>
        <div class="search-box" style="margin-top: 15px;">
            <!-- search start -->
            <form class="s-search">
                <input class="text-input" type="search" name="s" placeholder="<?php esc_attr_e('Search...', 'sakurairo'); ?>" required>
            </form>
            <!-- search end -->
        </div>
        <?php get_template_part('tpl/content', 'none'); ?>
    <?php
    endif;
    wp_reset_postdata();
    ?>


		<style>
			.nav-previous,
			.nav-next {
				padding: 20px 0;
				text-align: center;
				margin: 40px 0 80px;
				display: inline-block;
				font-family: 'Fira Code', 'Noto Sans SC';
			}

			.nav-previous a,
			.nav-next a {
				padding: 13px 35px;
				border: 1px solid #D6D6D6;
				border-radius: 50px;
				color: #ADADAD;
				text-decoration: none;
			}

			.nav-previous span,
			.nav-next span {
				color: #989898;
				font-size: 15px;
			}

			.nav-previous a:hover,
			.nav-next a:hover {
				border: 1px solid #A0DAD0;
				color: #A0DAD0;
			}
		</style>
	</main><!-- #main -->
</section><!-- #primary -->

<?php get_footer(); ?>
