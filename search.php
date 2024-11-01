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

	//搜索页标题
	if (!iro_opt('patternimg') || !get_random_bg_url()) : ?>
	    <header class="page-header">
		    <h1 class="page-title"><?php printf(esc_html__('Search result: %s', 'sakurairo'), '<span>' . esc_html($search_query) . '</span>'); ?></h1>
		</header><!-- .page-header -->
	<?php endif;

	//结果排序方式
    $all_results_args = array(
		'post_type' => array('post', 'shuoshuo'),
		'post_status' => 'publish',
		's' => $search_query,
		'posts_per_page' => -1,
		'orderby' => 'relevance',
		'order' => 'DESC',
	);
	
	$all_results_query = new WP_Query($all_results_args);
	
	//结果
	$all_results = [];
	$sticky_results = [];
	$non_sticky_results = [];
	
	//分类置顶内容和非置顶内容
	if ($all_results_query->have_posts()) :
		while ($all_results_query->have_posts()) : $all_results_query->the_post();
		if (in_array(get_the_ID(), $sticky_posts)) {
			$sticky_results[] = $post;
		} else {
			$non_sticky_results[] = $post;
		}
	    endwhile;
	endif;
	wp_reset_postdata();

	//合并结果,优先展示置顶文章
	$all_results = array_merge($sticky_results, $non_sticky_results);

	// 内容分页
	$total_results = count($all_results);
	$posts_per_page = 10;
	$total_pages = ceil($total_results / $posts_per_page);
	$current_page_results = array_slice($all_results, ($paged - 1) * $posts_per_page, $posts_per_page);

	//输出当前页内容
	if (!empty($current_page_results)) :
		foreach ($current_page_results as $post) :
			setup_postdata($post);
			get_template_part('tpl/content', 'thumbcard');
		endforeach;

		//分页跳转
		the_posts_pagination(array(
			'total' => $total_pages,
			'current' => $paged,
		));
	else :
		//未找到搜索结果
		?>
		<div class="search-box">
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
