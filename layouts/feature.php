<?php 

	/**
	 * DISQUS COMMENTS
	 */

?>
	<div class="top-feature">
		<h1 class="fes-title" style="font-family: 'Ubuntu', sans-serif;"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> <?php echo akina_option('focus-area-title', '聚焦'); ?></h1>
		<div class="feature-content">
			<li class="feature-1">
				<a href="<?php echo akina_option('feature1_link', '#'); ?>" target="_blank"><div class="feature-title"><span class="foverlay-bg"></span><span class="foverlay"><?php echo akina_option('feature1_title', 'feature1'); ?></span></div><img class="lazyload" src="<?php echo akina_option('webweb_img'); ?>/load/outload.svg" data-src="<?php echo akina_option('feature1_img', ''); ?>"></a>
			</li>
			<li class="feature-2">
				<a href="<?php echo akina_option('feature2_link', '#'); ?>" target="_blank"><div class="feature-title"><span class="foverlay-bg"></span><span class="foverlay"><?php echo akina_option('feature2_title', 'feature2'); ?></span></div><img src="<?php echo akina_option('feature2_img', ''); ?>"></a>
			</li>
			<li class="feature-3">
				<a href="<?php echo akina_option('feature3_link', '#'); ?>" target="_blank"><div class="feature-title"><span class="foverlay-bg"></span><span class="foverlay"><?php echo akina_option('feature3_title', 'feature3'); ?></span></div><img src="<?php echo akina_option('feature3_img', ''); ?>"></a>
			</li>
		</div>
	</div>
