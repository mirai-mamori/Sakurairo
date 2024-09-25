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

function render_meta_views(){
	?><span><i class="fa-regular fa-eye"></i><?= get_post_views(get_the_ID()) . ' ' . _n('Hit', 'Hits', get_post_views(get_the_ID()), 'sakurairo')/*热度*/ ?></span><?php
}

// @related inc/post-metadata.php
function render_article_meta()
{
	$article_meta_display_options = iro_opt("article_meta_displays", array("post_views", "comment_count", "category"));
	foreach ($article_meta_display_options as $key) {
		switch ($key) {
			case "author":
				require_once get_stylesheet_directory() . '/tpl/meta-author.php';
				render_author_meta();
				break;
			case "category":
				require_once get_stylesheet_directory() . '/tpl/meta-category.php';
				echo get_meta_category_html();
				break;
			case "comment_count":
				require_once get_stylesheet_directory() . '/tpl/meta-comments.php';
				 render_meta_comments();
				break;
			case "post_views":
				render_meta_views();
				break;
			case "post_words_count":
				require_once get_stylesheet_directory() . '/tpl/meta-words-count.php';
				$str = get_meta_words_count();
				if($str){
					?><span><i class="fa-regular fa-pen-to-square"></i> <?=$str?></span><?php
				}
				break;
			case "reading_time":
				require_once get_stylesheet_directory() . '/tpl/meta-ert.php';
				$str = get_meta_estimate_reading_time();
				if ($str) {
					?><span title="<?=__("Estimate Reading Time","sakurairo")?>"><i class="fa-solid fa-hourglass"></i> <?=$str?></span><?php
				}
			default:
		}
	}
}

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
			$cover_html = '<img alt="post_img" class="lazyload" src="' . iro_opt('load_out_svg') . '#lazyload-blur" data-src="' . $post_img . '"/>';
			break;
	}

	// 摘要字数限制
	$ai_excerpt = get_post_meta($post->ID, POST_METADATA_KEY, true); 
	$excerpt = has_excerpt(); 
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
					<i class="fa-regular fa-clock"></i><?= poi_time_since(strtotime($post->post_date)) ?>
					<?php if (is_sticky()) : ?>
						&nbsp;<i class="fa-regular fa-gem"></i>
					<?php endif ?>
				</div>
				<a href="<?php the_permalink(); ?>" class="post-title">
					<h3><?php the_title(); ?></h3>
				</a>
				<div class="post-meta">
					<?php render_article_meta()?>			
				</div>
				<div class="float-content">
				<?php if(!empty($ai_excerpt) && empty($excerpt)) { ?>
				<div class="ai-excerpt-tip"><i class="fa-regular fa-lightbulb"></i><?php _e("AI Excerpt", "sakurairo") ?></div>
				<?php } ?>
				<?php the_excerpt() ?>
				</div>
			</div>
		</div>
	</article>
<?php
endwhile;
