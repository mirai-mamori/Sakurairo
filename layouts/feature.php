<?php 

	/**
	 * DISQUS COMMENTS
	 */

	$exhibition = iro_opt('exhibition');
	if (iro_opt('exhibition_area_style') === 'bottom_to_top') {
?>
    <div class="top-feature">
        <h1 class="fes-title"> <i class="<?php echo iro_opt('exhibition_area_icon', 'fa-solid fa-laptop'); ?>" aria-hidden="true"></i> <br> <?php echo iro_opt('exhibition_area_title', '展示'); ?> </h1>
        <div class="feature-content">
            <?php foreach ($exhibition as $key => $item) { ?>
                <li class="feature">
                    <a href="<?php echo $item['link']; ?>" target="_blank"><div class="feature-title"><span class="foverlay-bg"></span><span class="foverlay"><?php echo $item['title']; ?></span></div><img class="lazyload" src="<?php echo iro_opt('load_out_svg'); ?>" data-src="<?php echo $item['img']; ?>"></a>
                </li>
            <?php } ?>
        </div>
    </div>
<?php } else { ?>
    <div class="top-feature-row">
        <h1 class="fes-title"> <i class="<?php echo iro_opt('exhibition_area_icon', 'fa-solid fa-laptop'); ?>" aria-hidden="true"></i> <br> <?php echo iro_opt('exhibition_area_title', '展示'); ?> </h1>
        <?php foreach ($exhibition as $key => $item) { ?>
            <div class="top-feature-v2">
                <div class="the-feature square from_left_and_right">
                    <a href="<?php echo $item['link']; ?>" target="_blank">
                        <div class="img">
                            <img src="<?php echo $item['img']; ?>">
                        </div>
                        <div class="info">
                            <h3><?php echo $item['title']; ?></h3>
                            <p><?php echo $item['description']; ?></p>
                        </div>
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>