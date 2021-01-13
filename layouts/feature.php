<?php 

	/**
	 * DISQUS COMMENTS
	 */

	$exhibition = iro_opt('exhibition');
	
?>
	<div class="top-feature">
		<h1 class="fes-title" style="font-family: 'Ubuntu', sans-serif;"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> <?php echo iro_opt('exhibition_area_title', '展示'); ?></h1>
		<div class="feature-content">
			<li class="feature-1">
				<a href="<?php echo $exhibition['link1']; ?>" target="_blank"><div class="feature-title"><span class="foverlay-bg"></span><span class="foverlay"><?php echo $exhibition['title1']; ?></span></div><img class="lazyload" src="<?php echo iro_opt('display_icon'); ?>/load/outload.svg" data-src="<?php echo $exhibition['img1']; ?>"></a>
			</li>
			<li class="feature-2">
				<a href="<?php echo $exhibition['link2']; ?>" target="_blank"><div class="feature-title"><span class="foverlay-bg"></span><span class="foverlay"><?php echo $exhibition['title2']; ?></span></div><img src="<?php echo $exhibition['img2']; ?>"></a>
			</li>
			<li class="feature-3">
				<a href="<?php echo $exhibition['link3']; ?>" target="_blank"><div class="feature-title"><span class="foverlay-bg"></span><span class="foverlay"><?php echo $exhibition['title3']; ?></span></div><img src="<?php echo $exhibition['img3']; ?>"></a>
			</li>
		</div>
	</div>
