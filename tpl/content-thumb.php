<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Akina
 */
//function custom_short_excerpt($excerpt){
//	return substr($excerpt, 0, 120);
//}
//add_filter('the_excerpt', 'custom_short_excerpt');
$i = 0;
while (have_posts()) : the_post();
	$i++;
	if ($i == 1) {
		$class = ' post-list-show';
	}
	//封面视频
	//@seealso https://github.com/mashirozx/Sakura/wiki/%E6%96%87%E7%AB%A0%E5%B0%81%E9%9D%A2%E8%A7%86%E9%A2%91
	$use_as_thumb = get_post_meta(get_the_ID(), 'use_as_thumb', true); //'true','only',(default)
	$cover_type = ($use_as_thumb == 'true' || $use_as_thumb == 'only') ? get_post_meta(get_the_ID(), 'cover_type', true) : '';
	$cover_html = "";
	switch ($cover_type) {
		case 'hls':
			$video_cover = get_post_meta(get_the_ID(), 'video_cover', true);
			$cover_html = '<video class="hls" poster="' . iro_opt('load_out_svg') . '#lazyload-blur" src="' .  $video_cover . '" loop muted="true" disablePictureInPicture disableRemotePlayback playsinline>'
				. __('Your browser does not support HTML5 video.', 'sakurairo')
				. '</video>';
			break;
		case 'normal':
			$video_cover = get_post_meta(get_the_ID(), 'video_cover', true);
			$cover_html = '<video class="lazyload" poster="' . iro_opt('load_out_svg') . '#lazyload-blur" data-src="' .  $video_cover . '" autoplay loop muted="true" disablePictureInPicture disableRemotePlayback playsinline>'
				. __('Your browser does not support HTML5 video.', 'sakurairo')
				. '</video>';
			break;
		default:
			$post_img = '';
			if (has_post_thumbnail()) {
				$post_thumbnail_id = get_post_thumbnail_id($post->ID);
				$large_image_url = wp_get_attachment_image_src($post_thumbnail_id, 'large');
				if ($large_image_url == false) {
					$large_image_url = wp_get_attachment_image_src($post_thumbnail_id, 'medium');
					if ($large_image_url == false) {
						$large_image_url = wp_get_attachment_image_src($post_thumbnail_id);
						if ($large_image_url == false) {
							$post_img = DEFAULT_FEATURE_IMAGE();
						}
					}
				}
				$post_img = $large_image_url[0] ?? DEFAULT_FEATURE_IMAGE('th');
			} else {
				$post_img = DEFAULT_FEATURE_IMAGE('th');
			}
			$cover_html = '<img class="lazyload" src="' . iro_opt('load_out_svg') . '#lazyload-blur" data-src="' . $post_img . '"/>';
			break;
	}

	$the_cat = get_the_category();
	// 摘要字数限制

	//add_filter( 'excerpt_length', 'custom_excerpt_length', 120 );
?>
	<article class="post post-list-thumb <?php echo $class; ?>" itemscope="" itemtype="http://schema.org/BlogPosting">
		<div class="post-thumb">
			<a href="<?php the_permalink(); ?>">
				<?php echo $cover_html; ?>
			</a>
		</div><!-- thumbnail-->
		<div class="post-content-wrap">
			<div class="post-content">
				<div class="post-date">
					<i class="iconfont icon-time"></i><?php echo poi_time_since(strtotime($post->post_date)); ?>
					<?php if (is_sticky()) : ?>
						&nbsp;<i class="iconfont hotpost icon-hot"></i>
					<?php endif ?>
				</div>
				<a href="<?php the_permalink(); ?>" class="post-title">
					<h3><?php the_title(); ?></h3>
				</a>
				<div class="post-meta">
					<?php
					if (iro_opt("is_author_meta_show")) {
						if (!function_exists('get_author_meta_spans')) {
							require get_stylesheet_directory() . '/tpl/meta-author.php';
						}
						get_author_meta_spans();
					}
					?>
					<span><i class="iconfont icon-attention"></i><?php echo get_post_views(get_the_ID()) . ' ' . _n('Hit', 'Hits', get_post_views(get_the_ID()), 'sakurairo')/*热度*/ ?></span>
					<span class="comments-number">
						<i class="iconfont icon-mark"></i>
						<?php comments_popup_link(__("NOTHING", "sakurairo"), __("1 Comment", "sakurairo")/*条评论*/, '% ' . __("Comments", "sakurairo")/*条评论*/, '', __("Comment Closed", "sakurairo")
							/**评论关闭 */
						); ?>
					</span>
					<span><i class="iconfont icon-file"></i>
					<a href="<?php
						if (isset($the_cat[0])) {
							echo $cat_id = esc_url(get_category_link($the_cat[0]->cat_ID  ?? ''));
						}
					?>">
					<?php
						if (isset($the_cat[0])) {
							echo $the_cat[0]->cat_name ?? '未分类';
						} else {
							echo '未分类';
						}
					?>
					</a>
					</span>
				</div>
				<div class="float-content">
					<?php the_excerpt() ?>
					<div class="post-bottom">
						<a href="<?php the_permalink(); ?>" class="button-normal"><i class="iconfont icon-caidan"></i></a>
					</div>
				</div>
			</div>
		</div>
	</article>
<?php
endwhile;
