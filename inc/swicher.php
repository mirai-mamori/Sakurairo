<?php
function font_end_js_control() { ?>
<script>
/*Initial Variables*/
var mashiro_option = new Object();
var mashiro_global = new Object();
mashiro_option.NProgressON = <?php if ( iro_opt('nprogress_on') ){ echo 'true'; } else { echo 'false'; } ?>;
mashiro_option.audio = <?php if ( iro_opt('note_effects') ){ echo 'true'; } else { echo 'false'; } ?>;
mashiro_option.darkmode = <?php if ( iro_opt('theme_darkmode_auto') ){ echo 'true'; } else { echo 'false'; } ?>;
mashiro_option.email_domain = "<?php echo iro_opt('email_domain', ''); ?>";
mashiro_option.email_name = "<?php echo iro_opt('email_name', ''); ?>";
mashiro_option.cookie_version_control = "<?php echo iro_opt('cookie_version', ''); ?>";
mashiro_option.qzone_autocomplete = false;
mashiro_option.site_name = "<?php echo iro_opt('site_name', ''); ?>";
mashiro_option.author_name = "<?php echo iro_opt('author_name', ''); ?>";
mashiro_option.template_url = "<?php echo get_template_directory_uri(); ?>";
mashiro_option.site_url = "<?php echo site_url(); ?>";
mashiro_option.qq_api_url = "<?php echo rest_url('sakura/v1/qqinfo/json'); ?>"; 
// mashiro_option.qq_avatar_api_url = "https://api.2heng.xin/qqinfo/";
mashiro_option.live_search = <?php if ( iro_opt('live_search') ){ echo 'true'; } else { echo 'false'; } ?>;

<?php $reception_background = iro_opt('reception_background'); ?>

<?php if($reception_background['img1']){ $bg_arry=explode(",", $reception_background['img1']);?>
mashiro_option.skin_bg0 = "<?php echo $bg_arry[0] ?>";
<?php }else {?>
mashiro_option.skin_bg0 = "none";
<?php } ?>

<?php if($reception_background['img2']){ $bg_arry=explode(",", $reception_background['img2']);?>
mashiro_option.skin_bg1 = "<?php echo $bg_arry[0] ?>";
<?php }else {?>
mashiro_option.skin_bg1 = "https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/foreground/bg1.png";
<?php } ?>

<?php if($reception_background['img3']){ $bg_arry=explode(",", $reception_background['img3']);?>
mashiro_option.skin_bg2 = "<?php echo $bg_arry[0] ?>";
<?php }else {?>
mashiro_option.skin_bg2 = "https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/foreground/bg2.png";
<?php } ?>

<?php if($reception_background['img4']){ $bg_arry=explode(",", $reception_background['img4']);?>
mashiro_option.skin_bg3 = "<?php echo $bg_arry[0] ?>";
<?php }else {?>
mashiro_option.skin_bg3 = "https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/foreground/bg3.png";
<?php } ?>

<?php if($reception_background['img5']){ $bg_arry=explode(",", $reception_background['img5']);?>
mashiro_option.skin_bg4 = "<?php echo $bg_arry[0] ?>";
<?php }else {?>
mashiro_option.skin_bg4 = "https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/foreground/bg4.png";
<?php } ?>

<?php if( is_home() ){ ?>
mashiro_option.land_at_home = true;
<?php }else {?>
mashiro_option.land_at_home = false;
<?php } ?>

<?php if(iro_opt('image_viewer') == "0"){ ?>
mashiro_option.baguetteBoxON = false;
<?php }else {?>
mashiro_option.baguetteBoxON = true;
<?php } ?>

<?php if(iro_opt('clipboard_copyright') == "0"){ ?>
mashiro_option.clipboardCopyright = false;
<?php }else {?>
mashiro_option.clipboardCopyright = true;
<?php } ?>

<?php if(iro_opt('entry_content_style') == "sakurairo"){ ?>
mashiro_option.entry_content_style_src = "<?php echo get_template_directory_uri() ?>/cdn/theme/sakura.css?<?php echo SAKURA_VERSION.iro_opt('cookie_version', ''); ?>";
<?php }elseif(iro_opt('entry_content_style') == "github") {?>
mashiro_option.entry_content_style_src = "<?php echo get_template_directory_uri() ?>/cdn/theme/github.css?<?php echo SAKURA_VERSION.iro_opt('cookie_version', ''); ?>";
<?php } ?>
mashiro_option.entry_content_style = "<?php echo iro_opt('entry_content_style'); ?>";

<?php if(iro_opt('local_global_library')){ ?>
mashiro_option.jsdelivr_css_src = "<?php echo get_template_directory_uri() ?>/cdn/css/lib.css?<?php echo SAKURA_VERSION.iro_opt('cookie_version', ''); ?>";
<?php } else { ?>
mashiro_option.jsdelivr_css_src = "https://cdn.jsdelivr.net/gh/mirai-mamori/Sakurairo@<?php echo SAKURA_VERSION; ?>/cdn/css/lib.min.css";
<?php } ?>
<?php if (iro_opt('aplayer_server') != 'off'): ?>
mashiro_option.float_player_on = true;
mashiro_option.meting_api_url = "<?php echo rest_url('sakura/v1/meting/aplayer'); ?>";
<?php endif; ?>

mashiro_option.cover_api = "<?php echo rest_url('sakura/v1/image/cover'); ?>";
<?php if(iro_opt('random_graphs_mts' )){?>
mashiro_option.random_graphs_mts = true;
<?php }else {?>
mashiro_option.random_graphs_mts = false;
<?php } ?>
mashiro_option.windowheight ='auto';
/*End of Initial Variables*/
</script>
<?php }
add_action('wp_head', 'font_end_js_control');
