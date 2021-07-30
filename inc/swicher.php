<?php
/**
 * 仅对boolean类型的设置项有效
 */
function echo_if_true($key,$overwrite_key = null){
$value = iro_opt($key);
if($value)echo ($overwrite_key ?? $key).":true,";
}
function font_end_js_control() { ?>
<script id="_mashiro_">
/*Initial Variables*/
var mashiro_option = {
    <?php 
    echo_if_true('nprogress_on','NProgressON');
    echo_if_true('note_effects','audio');
    echo_if_true('footer_yiyan','yiyan');
    echo_if_true('baguetteBox','baguetteBoxON');
    echo_if_true('fancybox','baguetteBoxON');
    ?>
    darkmode :<?php echo iro_opt('theme_darkmode_auto') ? 'true':'false';?>,
    <?php if ( iro_opt('theme_darkmode_auto') ):echo 'dm_strategy:"'.iro_opt('theme_darkmode_strategy','time').'",'.PHP_EOL;endif; ?>
    <?php 
    $preload_blur = iro_opt('preload_blur',0);
    if($preload_blur){
        echo "preload_blur:$preload_blur,";
    }
    ?>
    email_domain:"<?php echo iro_opt('email_domain', ''); ?>",
    email_name:"<?php echo iro_opt('email_name', ''); ?>",
    cookie_version_control:"<?php echo iro_opt('cookie_version', ''); ?>",
    qzone_autocomplete:false,
    site_name:"<?php echo iro_opt('site_name', ''); ?>",
    author_name:"<?php echo iro_opt('author_name', ''); ?>",
    template_url:"<?php echo get_template_directory_uri(); ?>",
    site_url:"<?php echo site_url(); ?>",
    qq_api_url:"<?php echo rest_url('sakura/v1/qqinfo/json'); ?>",
    // qq_avatar_api_url:"https://api.2heng.xin/qqinfo/",
    live_search:<?php echo iro_opt('live_search') ? 'true':'false'?>,
    <?php $reception_background = iro_opt('reception_background'); ?>
    <?php if(isset($reception_background['img1'])){ $bg_arry=explode(",", $reception_background['img1']);?>
    skin_bg0:"<?php echo $bg_arry[0] ?>",
    <?php }else {?>
    skin_bg0:"none",
    <?php } ?>

    <?php if(isset($reception_background['img2'])){ $bg_arry=explode(",", $reception_background['img2']);?>
    skin_bg1:"<?php echo $bg_arry[0] ?>",
    <?php }else {?>
    skin_bg1:"https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/foreground/bg1.png",
    <?php } ?>

    <?php if(isset($reception_background['img3'])){ $bg_arry=explode(",", $reception_background['img3']);?>
    skin_bg2:"<?php echo $bg_arry[0] ?>",
    <?php }else {?>
    skin_bg2:"https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/foreground/bg2.png",
    <?php } ?>

    <?php if(isset($reception_background['img4'])){ $bg_arry=explode(",", $reception_background['img4']);?>
    skin_bg3:"<?php echo $bg_arry[0] ?>",
    <?php }else {?>
    skin_bg3:"https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/foreground/bg3.png",
    <?php } ?>

    <?php if(isset($reception_background['img5'])){ $bg_arry=explode(",", $reception_background['img5']);?>
    skin_bg4:"<?php echo $bg_arry[0] ?>",
    <?php }else {?>
    skin_bg4:"https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/foreground/bg4.png",
    <?php } ?>
    land_at_home:<?php echo is_home() ? 'true':'false'; ?>,
    clipboardCopyright:<?php echo iro_opt('clipboard_copyright') == "0" ? 'false':'true' ?>,

    <?php if(iro_opt('entry_content_style') == "sakurairo"){ ?>
    entry_content_style_src:"<?php echo get_template_directory_uri() ?>/css/theme/sakura.css?<?php echo IRO_VERSION.iro_opt('cookie_version', ''); ?>",
    <?php }elseif(iro_opt('entry_content_style') == "github") {?>
    entry_content_style_src:"<?php echo get_template_directory_uri() ?>/css/theme/github.css?<?php echo IRO_VERSION.iro_opt('cookie_version', ''); ?>",
    <?php } ?>
    entry_content_style:"<?php echo iro_opt('entry_content_style'); ?>",

    <?php if(iro_opt('local_global_library')){ ?>
    jsdelivr_css_src:"<?php echo get_template_directory_uri() ?>/css/lib.css?<?php echo IRO_VERSION.iro_opt('cookie_version', ''); ?>",
    <?php } else { ?>
    jsdelivr_css_src:"https://cdn.jsdelivr.net/gh/mirai-mamori/Sakurairo@<?php echo IRO_VERSION; ?>/css/lib.min.css",
    <?php } ?>
    <?php if (iro_opt('aplayer_server') != 'off'): ?>
    float_player_on:true,
    meting_api_url:"<?php echo rest_url('sakura/v1/meting/aplayer'); ?>",
    <?php endif; ?>

    cover_api:"<?php echo rest_url('sakura/v1/image/cover'); ?>",
    random_graphs_mts:<?php echo iro_opt('random_graphs_mts' ) ? 'true':'false'?>,
    <?php
    $highlight_method = iro_opt('code_highlight_method','hljs');
    echo 'code_highlight:"'.$highlight_method.'",';

    if($highlight_method=='prism'){
        $autoload_path=iro_opt('code_highlight_prism_autoload_path');
        $theme_light=iro_opt('code_highlight_prism_theme_light');
        $theme_dark=iro_opt('code_highlight_prism_theme_dark');
        ?>

    code_highlight_prism: {
        line_number_all:<?php echo iro_opt('code_highlight_prism_line_number_all')?'true':'false' ?>,
        autoload_path:<?php  echo ($autoload_path=='' ? 'undefined' : '"'.$autoload_path.'"')  ?>,
        theme:{
                <?php  echo ($theme_light=='' ? '' : 'light:"'.$theme_light.'",');
                echo ($theme_dark=='' ? '' : 'dark:"'.$theme_dark.'",') ; ?>
              },
    },   
        <?php }?>    
    comment_upload_img:<?php echo iro_opt('img_upload_api')=='off'?'false':'true' ?>,
    <?php
    echo_if_true('cache_cover');
    echo_if_true('site_bg_as_cover');
    $yiyan_api = iro_opt('yiyan_api');
    if($yiyan_api) echo "yiyan_api:$yiyan_api,";
    ?>
};
/*End of Initial Variables*/
</script>
<script>const mashiro_global = {};</script>
<?php }
add_action('wp_head', 'font_end_js_control');
