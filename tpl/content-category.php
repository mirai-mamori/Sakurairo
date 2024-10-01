<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sakurairo
 */

?>

<article class="post works-list" itemscope itemtype="http://schema.org/BlogPosting">
	<div class="works-entry">
		<div class="works-main">
			<div class="works-feature">
				<?php if (has_post_thumbnail()) : ?>
					<a href="<?= esc_url(get_permalink()); ?>"><?= get_the_post_thumbnail(get_the_ID(), 'large'); ?></a>
				<?php else : ?>
					<a href="<?= esc_url(get_permalink()); ?>"><img src="<?= esc_url(DEFAULT_FEATURE_IMAGE()); ?>" alt="<?= esc_attr(get_the_title()); ?>" /></a>
				<?php endif; ?>
			</div>

			<div class="works-overlay">
				<h1 class="works-title"><a href="<?= esc_url(get_permalink()); ?>"><?= esc_html(get_the_title()); ?></a></h1>
				<div class="works-p-time">
					<i class="fa-solid fa-calendar-day"></i> <?= esc_html(poi_time_since(strtotime($post->post_date))); ?>
				</div>
				<div class="works-meta">
					<div class="works-comnum">
						<span><i class="fa-regular fa-comment"></i>
							<?php comments_popup_link(
								__("NOTHING", "sakurairo"),
								__("1 Comment", "sakurairo"),
								'% ' . __("Comments", "sakurairo"),
								'',
								__("Comment Closed", "sakurairo")
							); ?>
						</span>
					</div>
					<div class="works-views">
						<span><i class="fa-regular fa-eye"></i> <?= esc_html(get_post_views(get_the_ID())); ?> </span>
					</div>
				</div>
				<a class="worksmore" href="<?= esc_url(get_permalink()); ?>"></a>
			</div>
		</div>
	</div>
</article><!-- #post-## -->
