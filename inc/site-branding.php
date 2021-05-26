<?php 
function print_site_branding(){
?>
<div class="site-branding">
    <?php if (iro_opt('iro_logo') && !iro_opt('mashiro_logo_option',false)) { ?>
        <div class="site-title">
            <a href="<?php bloginfo('url'); ?>"><img src="<?php echo iro_opt('iro_logo'); ?>"></a>
        </div>
    <?php } else { ?>
        <span class="site-title">
            <span class="logolink moe-mashiro">
                <a href="<?php bloginfo('url'); ?>">
                    <ruby>
                        <!-- <span class="site-name"><?php echo iro_opt('site_name', ''); ?></span> -->
                        <span class="sakuraso" style="font-family: '<?php echo $mashiro_logo['font_name']; ?>', 'Merriweather Sans', Helvetica, Tahoma, Arial, 'PingFang SC', 'Hiragino Sans GB', 'Microsoft Yahei', 'WenQuanYi Micro Hei', sans-serif;;"><?php echo $mashiro_logo['text_a']; ?></span>
                        <span class="no" style="font-family: '<?php echo $mashiro_logo['font_name']; ?>', 'Merriweather Sans', Helvetica, Tahoma, Arial, 'PingFang SC', 'Hiragino Sans GB', 'Microsoft Yahei', 'WenQuanYi Micro Hei', sans-serif;"><?php echo $mashiro_logo['text_b']; ?></span>
                        <span class="shironeko" style="font-family: '<?php echo $mashiro_logo['font_name']; ?>', 'Merriweather Sans', Helvetica, Tahoma, Arial, 'PingFang SC', 'Hiragino Sans GB', 'Microsoft Yahei', 'WenQuanYi Micro Hei', sans-serif;"><?php echo iro_opt('logo_text'); ?><?php echo $mashiro_logo['text_c']; ?></span>
                        <rp></rp>
                        <rt class="chinese-font"><?php echo $mashiro_logo['text_secondary']; ?></rt>
                        <rp></rp>
                    </ruby>
                </a>
            </span>
        </span>
    <?php } ?>
    <!-- logo end -->
</div>
<?php
}?>