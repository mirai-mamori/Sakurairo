<?php 

/**
 * NEXT / PREVIOUS POSTS (精华版)
 */

if (iro_opt('article_nextpre') == '1') {
?>
<section class="post-squares nextprev">
	<?php iro_opt('classify_display') ? $classify_display_id = iro_opt('classify_display') : $classify_display_id = null; ?>
	<div class="post-nepre <?php if(get_next_post($excluded_terms = $classify_display_id)){echo 'half';}else{echo 'full';} ?> previous">
		<?php previous_post_link( $format = '%link', $link = '<div class="background lazyload" style="background-image:url('.iro_opt('vision_resource_basepath','https://s.nmxc.ltd/sakurairo_vision/@2.6/').'load_svg/outload.svg);" data-src="'.get_prev_thumbnail_url().'"></div><span class="label">'.__("Previous Post",'sakurairo').'</span><div class="info"><h3>%title</h3><hr></div>', $in_same_term = false, $excluded_terms = $classify_display_id ) ?>
	</div>
	<div class="post-nepre <?php if(get_previous_post($excluded_terms = $classify_display_id)){echo 'half';}else{echo 'full';} ?> next">
		<?php next_post_link( $format = '%link', $link = '<div class="background lazyload" style="background-image:url('.iro_opt('vision_resource_basepath','https://s.nmxc.ltd/sakurairo_vision/@2.6/').'load_svg/outload.svg);" data-src="'.get_next_thumbnail_url().'"></div><span class="label">'.__("Next Post",'sakurairo').'</span><div class="info"><h3>%title</h3><hr></div>', $in_same_term = false, $excluded_terms = $classify_display_id ) ?>
	</div>
</section>
<?php } ?>