<?php 

/**
 * NEXT / PREVIOUS POSTS (精华版)
 */

if ( akina_option('post_nepre') == 'yes') {
?>
<section class="post-squares nextprev">
	<div class="post-nepre <?php if(get_next_post()){echo 'half';}else{echo 'full';} ?> previous">
		<?php previous_post_link('%link','<div class="background lazyload" style="background-image:url(https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/theme/sakura/load/outload.svg);" data-src="'.get_prev_thumbnail_url().'"></div><span class="label">Previous Post</span><div class="info"><h3>%title</h3><hr></div>') ?>
	</div>
	<div class="post-nepre <?php if(get_previous_post()){echo 'half';}else{echo 'full';} ?> next">
		<?php next_post_link('%link','<div class="background lazyload" style="background-image:url(https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/theme/sakura/load/outload.svg);" data-src="'.get_next_thumbnail_url().'"></div><span class="label">Next Post</span><div class="info"><h3>%title</h3><hr></div>') ?>
	</div>
</section>
<?php } ?>