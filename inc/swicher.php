<?php
function font_end_js_control() { ?>
<script>
/*Initial Variables*/
var mashiro_option = new Object();
var mashiro_global = new Object();
mashiro_option.NProgressON = <?php if ( akina_option('nprogress_on') ){ echo 'true'; } else { echo 'false'; } ?>;
mashiro_option.audio = <?php if ( akina_option('audio') ){ echo 'true'; } else { echo 'false'; } ?>;
mashiro_option.darkmode = <?php if ( akina_option('darkmode') ){ echo 'true'; } else { echo 'false'; } ?>;
mashiro_option.email_domain = "<?php echo akina_option('email_domain', ''); ?>";
mashiro_option.email_name = "<?php echo akina_option('email_name', ''); ?>";
mashiro_option.cookie_version_control = "<?php echo akina_option('cookie_version', ''); ?>";
mashiro_option.qzone_autocomplete = false;
mashiro_option.site_name = "<?php echo akina_option('site_name', ''); ?>";
mashiro_option.author_name = "<?php echo akina_option('author_name', ''); ?>";
mashiro_option.template_url = "<?php echo get_template_directory_uri(); ?>";
mashiro_option.site_url = "<?php echo site_url(); ?>";
mashiro_option.qq_api_url = "<?php echo rest_url('sakura/v1/qqinfo/json'); ?>"; 
// mashiro_option.qq_avatar_api_url = "https://api.2heng.xin/qqinfo/";
mashiro_option.live_search = <?php if ( akina_option('live_search') ){ echo 'true'; } else { echo 'false'; } ?>;

<?php if( akina_option('sakura_skin_bg1' )){ $bg_arry=explode(",", akina_option('sakura_skin_bg1' ));?>
mashiro_option.skin_bg0 = "<?php echo $bg_arry[0] ?>";
<?php }else {?>
mashiro_option.skin_bg0 = "none";
<?php } ?>

<?php if( akina_option('sakura_skin_bg2' )){ $bg_arry=explode(",", akina_option('sakura_skin_bg2' ));?>
mashiro_option.skin_bg1 = "<?php echo $bg_arry[0] ?>";
<?php }else {?>
mashiro_option.skin_bg1 = "https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/foreground/bg1.png";
<?php } ?>

<?php if( akina_option('sakura_skin_bg3' )){ $bg_arry=explode(",", akina_option('sakura_skin_bg3' ));?>
mashiro_option.skin_bg2 = "<?php echo $bg_arry[0] ?>";
<?php }else {?>
mashiro_option.skin_bg2 = "https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/foreground/bg2.png";
<?php } ?>

<?php if( akina_option('sakura_skin_bg4' )){ $bg_arry=explode(",", akina_option('sakura_skin_bg4' ));?>
mashiro_option.skin_bg3 = "<?php echo $bg_arry[0] ?>";
<?php }else {?>
mashiro_option.skin_bg3 = "https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/foreground/bg3.png";
<?php } ?>

<?php if( akina_option('sakura_skin_bg5' )){ $bg_arry=explode(",", akina_option('sakura_skin_bg5' ));?>
mashiro_option.skin_bg4 = "<?php echo $bg_arry[0] ?>";
<?php }else {?>
mashiro_option.skin_bg4 = "https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/foreground/bg4.png";
<?php } ?>

<?php if( is_home() ){ ?>
mashiro_option.land_at_home = true;
<?php }else {?>
mashiro_option.land_at_home = false;
<?php } ?>

<?php if(akina_option('image_viewer') == 0){ ?>
mashiro_option.baguetteBoxON = false;
<?php }else {?>
mashiro_option.baguetteBoxON = true;
<?php } ?>

<?php if(akina_option('clipboard_copyright') == 0){ ?>
mashiro_option.clipboardCopyright = false;
<?php }else {?>
mashiro_option.clipboardCopyright = true;
<?php } ?>

<?php if(akina_option('entry_content_theme') == "sakurairo"){ ?>
mashiro_option.entry_content_theme_src = "<?php echo get_template_directory_uri() ?>/cdn/theme/sakura.css?<?php echo SAKURA_VERSION.akina_option('cookie_version', ''); ?>";
<?php }elseif(akina_option('entry_content_theme') == "github") {?>
mashiro_option.entry_content_theme_src = "<?php echo get_template_directory_uri() ?>/cdn/theme/github.css?<?php echo SAKURA_VERSION.akina_option('cookie_version', ''); ?>";
<?php } ?>
mashiro_option.entry_content_theme = "<?php echo akina_option('entry_content_theme'); ?>";

<?php if(akina_option('jsdelivr_cdn_test')){ ?>
mashiro_option.jsdelivr_css_src = "<?php echo get_template_directory_uri() ?>/cdn/css/lib.css?<?php echo SAKURA_VERSION.akina_option('cookie_version', ''); ?>";
<?php } else { ?>
mashiro_option.jsdelivr_css_src = "https://cdn.jsdelivr.net/gh/mirai-mamori/Sakurairo@<?php echo SAKURA_VERSION; ?>/cdn/css/lib.min.css";
<?php } ?>
<?php if (akina_option('aplayer_server') != 'off'): ?>
mashiro_option.float_player_on = true;
mashiro_option.meting_api_url = "<?php echo rest_url('sakura/v1/meting/aplayer'); ?>";
<?php endif; ?>

mashiro_option.cover_api = "<?php echo rest_url('sakura/v1/image/cover'); ?>";
<?php if(akina_option('cover_beta' )){?>
mashiro_option.cover_beta = true;
<?php }else {?>
mashiro_option.cover_beta = false;
<?php } ?>
mashiro_option.windowheight ='auto';
/*End of Initial Variables*/
</script>
<?php }
add_action('wp_head', 'font_end_js_control');
