<?php

/**
 * NEXT / PREVIOUS POSTS (精华版)
 */

if (iro_opt('article_nextpre') == '1') {
?>
	<section class="post-squares nextprev">
		<?php
		$classify_display_id = iro_opt('classify_display');
		$load_svg_url = iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@2.7/') . 'load_svg/outload.svg';
		$prev_link_html = '<div class="background lazyload" style="background-image:url(' . $load_svg_url . ');" data-src="' . get_prev_thumbnail_url() . '"></div>' .
			'<span class="label">' .
			__("Previous Post", 'sakurairo') .
			'</span>' .
			'<div class="info">' .
			'<h3>%title</h3><hr>' .
			'</div>';


		$next_link_html = '<div class="background lazyload" style="background-image:url(' . $load_svg_url . ');" data-src="' . get_next_thumbnail_url() . '"></div>' .
			'<span class="label">' .
			__("Next Post", 'sakurairo') .
			'</span>' .
			'<div class="info">' .
			'<h3>%title</h3><hr>' .
			'</div>';

		previous_post_link('%link', $prev_link_html, false, $classify_display_id);
		next_post_link('%link', $next_link_html, false, $classify_display_id)  ?>
	</section>
<?php } ?>