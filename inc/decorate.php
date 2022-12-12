<?php
function customizer_css() { ?>
<style>
<?php // Style Settings
if(iro_opt('nav_menu_display') == 'unfold'){ ?>
.site-top .lower nav {display: block !important;}
<?php } // Style Settings ?>
<?php 
/**
 * theme-skin
 *  */ 

if (iro_opt('theme_skin')) { ?>
:root{
    --theme-skin: <?=iro_opt('theme_skin'); ?>;
    --theme-skin-matching:<?=iro_opt('theme_skin_matching'); ?>;
    --infor_bar_bgcolor:<?=iro_opt('infor_bar_bgcolor'); ?>;
    --style_menu_background_color:<?=iro_opt('style_menu_background_color'); ?>;
    --style_menu_selection_radius:<?=iro_opt('style_menu_selection_radius', ''); ?>px;
    --post_list_matching_color:<?=iro_opt('post_list_matching_color'); ?>;
    --load_nextpage_svg:url("<?=iro_opt('load_nextpage_svg'); ?>");
    --style_menu_radius:<?=iro_opt('style_menu_radius', ''); ?>px;
    --post-list-thumb: <?=iro_opt('post_border_shadow_color'); ?>;
    --friend-link-shadow: <?=iro_opt('friend_link_shadow_color'); ?>;
    --friend-link-title: <?=iro_opt('friend_link_title_matching_color'); ?>;
    --comment_area_matching: <?=iro_opt('comment_area_matching_color'); ?>;
    --comment_area_shadow: <?=iro_opt('comment_area_shadow_color'); ?>;
    --style_menu_selection_color: <?=iro_opt('style_menu_selection_color'); ?>;
    --shuoshuo_background_color1:<?=iro_opt('shuoshuo_background_color1');?>;
    --shuoshuo_background_color2:<?=iro_opt('shuoshuo_background_color2');?>;
    <?php //深色模式主题色 ?>
    --theme-skin-dark:  <?=iro_opt('theme_skin_dark'); ?>;
    --global-font-weight:<?=iro_opt('global_font_weight');?>;
    --theme-dm-background_transparency:<?=iro_opt('theme_darkmode_background_transparency')?>;
    --exhibition_area_matching_color:<?=iro_opt('exhibition_area_matching_color');?>;
}
<?php if (iro_opt('theme_commemorate_mode')) {?>
    html{
        filter: grayscale(100%) !important;
    }
<?php } ?>
.the-feature.from_left_and_right .info,.the-feature.from_left_and_right .info h3{background: <?=iro_opt('exhibition_background_color'); ?> ;}

/*白猫样式Logo*/
<?php if (iro_opt('mashiro_logo_option', 'true')) {
     $mashiro_logo = iro_opt('mashiro_logo');
    ?>
    .logolink{
        font-family: '<?= $mashiro_logo['font_name']; ?>', 'Merriweather Sans', Helvetica, Tahoma, Arial, 'PingFang SC', 'Hiragino Sans GB', 'Microsoft Yahei', 'WenQuanYi Micro Hei', sans-serif;
    }

.logolink .sakuraso {
    background-color: rgba(255, 255, 255, .5);
    border-radius: 5px;
    color: <?=iro_opt('theme_skin'); ?>;
    height: auto;
    line-height: 25px;
    margin-right: 0;
    padding-bottom: 0px;
    padding-top: 1px;
    text-size-adjust: 100%;
    width: auto
}
 
.logolink a:hover .sakuraso {
    background-color: <?=iro_opt('theme_skin_matching'); ?>;
    color: #fff;
}
 
.logolink a:hover .shironeko,
.logolink a:hover .no,
.logolink a:hover rt {
    color: <?=iro_opt('theme_skin_matching'); ?>;
}
 
.logolink.moe-mashiro a {
    color: <?=iro_opt('theme_skin'); ?>;
    float: left;
    font-size: 25px;
    font-weight: 800;
    height: 56px;
    line-height: 56px;
    padding-left: 8px;
    padding-right: 8px;
    padding-top: 8px;
    text-decoration-line: none;
}
@media (max-width:860px) {
.logolink.moe-mashiro a {
    color: <?=iro_opt('theme_skin'); ?>;
    float: left;
    font-size: 25px;
    font-weight: 800;
    height: 56px;
    line-height: 56px;
    padding-left: 6px;
    padding-right: 15px;
    padding-top: 11px;
}
}
.logolink.moe-mashiro .sakuraso,.logolink.moe-mashiro .no {
    font-size: 25px;
    border-radius: 9px;
    padding-bottom: 2px;
    padding-top: 5px;
}
 
.logolink.moe-mashiro .no {
    font-size: 20px;
    display: inline-block;
    margin-left: 5px;
}
 
.logolink a:hover .no {
    -webkit-animation: spin 1.5s linear infinite;
    animation: spin 1.5s linear infinite;
}
 
.logolink ruby {
    ruby-position: under;
    -webkit-ruby-position: after;
}
 
.logolink ruby rt {
    font-size: 10px;
    letter-spacing:2px;
    transform: translateY(-15px);
    opacity: 0;
    transiton-property: opacity;
    transition-duration: 0.5s, 0.5s;
}
 
.logolink a:hover ruby rt {
    opacity: 1
}
<?php } ?>


/*非全局色彩管理*/
.post-date {
    background-color: <?=iro_opt('post_list_matching_color'); ?>26;
}

<?php $text_logo = iro_opt('text_logo'); ?>
.center-text{
color: <?=$text_logo['color']; ?> ;
font-size: <?=$text_logo['size']; ?>px;
}
.Ubuntu-font,.center-text{
font-family: <?=$text_logo['font']; ?> ;
}

.notice i ,
.notice {
    color: <?=iro_opt('bulletin_text_color'); ?>;
}

.notice {
    border: 1px solid <?=iro_opt('bulletin_board_border_color'); ?>;
}

<?php if(iro_opt('entry_content_style') == "sakurairo"){ ?>
.entry-content th {
    background-color: <?=iro_opt('theme_skin'); ?>
}
<?php } ?>

<?php if(iro_opt('live_search')){ ?>
.search-form--modal .search-form__inner {
    bottom: unset !important;
    top: 10% !important;
}
<?php } ?>

<?php } // theme-skin ?>
<?php // Custom style
if ( iro_opt('site_custom_style') ) {
  echo iro_opt('site_custom_style');
} 
// Custom style end ?>
<?php // liststyle
if ( iro_opt('post_list_akina_type') == 'square') { ?>
.feature img{ border-radius: 0px !important; }
.feature i { border-radius: 0px !important; }
<?php } // liststyle ?>
<?php 
//$image_api = 'background-image: url("'.rest_url('sakura/v1/image/cover').'");';
$bg_style = iro_opt('cover_full_screen') ?'': 'background-position: center center;background-attachment: inherit;';
?>
#centerbg{<?php 
echo $bg_style;
echo iro_opt('site_bg_as_cover',false)? 'background:#0000;':'';
 ?>}

/*预加载部分*/

<?php if (iro_opt('preload_animation', 'true')): ?>
#preload {
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background: #ffffff;
    z-index: 99999;
}

#preload li.active {
    position: absolute;
    top: 49%;
    left: 49%;
    list-style: none;
}

html {
    overflow-y: hidden;
}

#preloader_3 {
    position:relative;
}
#preloader_3:before {
    background:<?=iro_opt('preload_animation_color2'); ?>;
    -webkit-animation: preloader_3_before 1.5s infinite ease-in-out;
    -moz-animation: preloader_3_before 1.5s infinite ease-in-out;
    -ms-animation: preloader_3_before 1.5s infinite ease-in-out;
    animation: preloader_3_before 1.5s infinite ease-in-out;
}
#preloader_3:after {
    background:<?=iro_opt('preload_animation_color1'); ?>;
    left:22px;
    -webkit-animation: preloader_3_after 1.5s infinite ease-in-out;
    -moz-animation: preloader_3_after 1.5s infinite ease-in-out;
    -ms-animation: preloader_3_after 1.5s infinite ease-in-out;
    animation: preloader_3_after 1.5s infinite ease-in-out;
}
#preloader_3:before,#preloader_3:after {
    width:20px;
    height:20px;
    border-radius:20px;
    background:<?=iro_opt('preload_animation_color1'); ?>;
    position:absolute;
    content:'';
}
@-webkit-keyframes preloader_3_before {
    0% {
        -webkit-transform: translateX(0px) rotate(0deg)
    }
    50% {
        -webkit-transform: translateX(50px) scale(1.2) rotate(260deg);
        background:<?=iro_opt('preload_animation_color1'); ?>;
        border-radius:0px;
    }
    100% {
        -webkit-transform: translateX(0px) rotate(0deg)
    }
}
@-webkit-keyframes preloader_3_after {
    0% {
        -webkit-transform: translateX(0px)
    }
    50% {
        -webkit-transform: translateX(-50px) scale(1.2) rotate(-260deg);
        background:<?=iro_opt('preload_animation_color2'); ?>;
        border-radius:0px;
    }
    100% {
        -webkit-transform: translateX(0px)
    }
}
@-moz-keyframes preloader_3_before {
    0% {
        -moz-transform: translateX(0px) rotate(0deg)
    }
    50% {
        -moz-transform: translateX(50px) scale(1.2) rotate(260deg);
        background:<?=iro_opt('preload_animation_color1'); ?>;
        border-radius:0px;
    }
    100% {
        -moz-transform: translateX(0px) rotate(0deg)
    }
}
@-moz-keyframes preloader_3_after {
    0% {
        -moz-transform: translateX(0px)
    }
    50% {
        -moz-transform: translateX(-50px) scale(1.2) rotate(-260deg);
        background:<?=iro_opt('preload_animation_color2'); ?>;
        border-radius:0px;
    }
    100% {
        -moz-transform: translateX(0px)
    }
}
@-ms-keyframes preloader_3_before {
    0% {
        -ms-transform: translateX(0px) rotate(0deg)
    }
    50% {
        -ms-transform: translateX(50px) scale(1.2) rotate(260deg);
        background:<?=iro_opt('preload_animation_color1'); ?>;
        border-radius:0px;
    }
    100% {
        -ms-transform: translateX(0px) rotate(0deg)
    }
}
@-ms-keyframes preloader_3_after {
    0% {
        -ms-transform: translateX(0px)
    }
    50% {
        -ms-transform: translateX(-50px) scale(1.2) rotate(-260deg);
        background:<?=iro_opt('preload_animation_color2'); ?>;
        border-radius:0px;
    }
    100% {
        -ms-transform: translateX(0px)
    }
}
@keyframes preloader_3_before {
    0% {
        transform: translateX(0px) rotate(0deg)
    }
    50% {
        transform: translateX(50px) scale(1.2) rotate(260deg);
        background:<?=iro_opt('preload_animation_color1'); ?>;
        border-radius:0px;
    }
    100% {
        transform: translateX(0px) rotate(0deg)
    }
}
@keyframes preloader_3_after {
    0% {
        transform: translateX(0px)
    }
    50% {
        transform: translateX(-50px) scale(1.2) rotate(-260deg);
        background:<?=iro_opt('preload_animation_color2'); ?>;
        border-radius:0px;
    }
    100% {
        transform: translateX(0px)
    }
}
<?php endif; ?>

/*深色模式*/
/*可变项目*/

/*深色模式控件透明度*/
body.dark .header-info,
body.dark .top-social img
{color:#fff;background:rgba(51,51,51,<?=iro_opt('theme_darkmode_widget_transparency'); ?>);}

body.dark .the-feature.from_left_and_right .info
{background-color: rgba(51,51,51,<?=iro_opt('theme_darkmode_widget_transparency'); ?>);}

body.dark .yya,
body.dark .widget-area,
body.dark .skin-menu,
body.dark input[type=submit] 
{background-color:rgba(38,38,38,<?=iro_opt('theme_darkmode_widget_transparency'); ?>) !important;}

/*深色模式自定义颜色*/
body.dark .headertop-down i 
{color: <?=iro_opt('drop_down_arrow_dark_color'); ?> !important;}

/*深色模式图像亮度*/
body.dark img,
body.dark .highlight-wrap,
body.dark iframe,
body.dark .entry-content .aplayer,
body.dark .post-thumb video
{filter:brightness(<?=iro_opt('theme_darkmode_img_bright'); ?>);}
@media (max-width: 1200px){
body.dark .site-top .lower nav.navbar ul 
{background: rgba(255,255,255,0);}
}

/*字体*/

<?php if (iro_opt('reference_exter_font', 'true')) {
$exter_font = iro_opt('exter_font');
?>

@font-face {
font-family: '<?php echo $exter_font['font1']; ?>';
src : url('<?php echo $exter_font['link1']; ?>');
}

@font-face {
font-family: '<?php echo $exter_font['font2']; ?>';
src : url('<?php echo $exter_font['link2']; ?>');
}

<?php } ?>

.serif{
font-family:<?=iro_opt('global_default_font'); ?> !important ;
font-size: <?=iro_opt('global_font_size'); ?>px;
}

body{
font-family:<?=iro_opt('global_font_2'); ?> !important;
font-size: <?=iro_opt('global_font_size'); ?>px;
}

.site-top ul li a,.header-user-name,.header-user-menu a {
font-family:<?=iro_opt('nav_menu_font'); ?> !important;
}

<?php if (iro_opt('mashiro_logo')) {
$mashiro_logo = iro_opt('mashiro_logo');
?>

.site-title a{
font-family: '<?php echo $mashiro_logo['font_name']; ?>';
}

<?php } ?>

.site-info,.site-info a{
font-family:<?=iro_opt('footer_text_font'); ?> !important;
}

.skin-menu {
font-family:<?=iro_opt('style_menu_font'); ?> !important;
}

h1.main-title,h1.fes-title{
font-family:<?=iro_opt('area_title_font'); ?>;
}

.header-info p{
font-family:<?=iro_opt('signature_font'); ?> !important;
font-size: <?=iro_opt('signature_font_size'); ?>px;
}

.cbp_tmtimeline > li .cbp_tmlabel {
font-family:<?=iro_opt('shuoshuo_font'); ?> !important;
}

.post-list-thumb .post-title h3{
font-size: <?=iro_opt('post_title_font_size'); ?>px !important;
}

.post-meta, .post-meta a{
font-size: <?=iro_opt('post_date_font_size'); ?>px !important;
}

.pattern-center h1.cat-title,
.pattern-center h1.entry-title {
font-size: <?=iro_opt('page_temp_title_font_size'); ?>px ;
}
.pattern-center-sakura h1.cat-title,
.pattern-center-sakura h1.entry-title {
font-size: <?=iro_opt('page_temp_title_font_size'); ?>px !important;
}

.single-center .single-header h1.entry-title {
font-size: <?=iro_opt('article_title_font_size'); ?>px ;
}

/*鼠标*/
body{
cursor: url(<?=iro_opt('cursor_nor'); ?>), auto;
}

.headertop-down i,
.faa-parent.animated-hover:hover>.faa-spin,
.faa-spin.animated,
.faa-spin.animated-hover:hover,
i.iconfont.js-toggle-search.iconsearch,
#waifu #live2d,
.aplayer svg,
.aplayer.aplayer-narrow .aplayer-body,
.aplayer.aplayer-narrow .aplayer-pic,
#emotion-toggle,
.emoji-item,
.emotion-box,
.emotion-item,
.on-hover,
.tieba-container span,
#moblieGoTop,
#changskin{
cursor: url(<?=iro_opt('cursor_no'); ?>), auto;
}

a,
.ins-section .ins-section-header,
.font-family-controls button,
.menu-list li,.ins-section .ins-search-item,
.ins-section .ins-search-item .ins-search-preview{
cursor: url(<?=iro_opt('cursor_ayu'); ?>), auto;
}

p,
.highlight-wrap code,
.highlight-wrap,
.hljs-ln-code .hljs-ln-line{
cursor: url(<?=iro_opt('cursor_text'); ?>), auto;
}

a:active{
cursor: url(<?=iro_opt('cursor_work'); ?>), alias;
}

/*背景类*/
.comment-respond textarea {
background-image: url(<?=iro_opt('comment_area_image'); ?>); 
}

.search-form.is-visible{
background-image: url(<?=iro_opt('search_area_background'); ?>);
}

.site-footer {
background-color: rgba(255, 255, 255,<?=iro_opt('reception_background_transparency'); ?>);
<?php if (iro_opt('reception_background_blur', 'false')): ?> backdrop-filter: blur(10px); <?php endif; ?>
<?php if (iro_opt('reception_background_blur', 'false')): ?> -webkit-backdrop-filter: blur(10px); <?php endif; ?>
}

.wrapper {
background-color: rgba(255, 255, 255,<?=iro_opt('reception_background_transparency'); ?>);
<?php if (iro_opt('reception_background_blur', 'false')): ?> backdrop-filter: blur(10px); <?php endif; ?>
<?php if (iro_opt('reception_background_blur', 'false')): ?> -webkit-backdrop-filter: blur(10px); <?php endif; ?>
}

/*首页圆角设置*/
.header-info{
border-radius: <?=iro_opt('signature_radius'); ?>px;
}

.focusinfo img{
border-radius: <?=iro_opt('social_area_radius'); ?>px;
}

.focusinfo .header-tou img{
border-radius: <?=iro_opt('avatar_radius'); ?>px;
}

/*标题横线动画*/
<?php if (iro_opt('article_title_line', 'true')): ?>
.single-center header.single-header .toppic-line{
position:relative;bottom:0;left:0;display:block;width:100%;height:2px;background-color:#fff;animation:lineWidth 2.5s;animation-fill-mode:forwards;}
@keyframes lineWidth{0%{width:0;}
100%{width:100%;}
}
<?php endif; ?>

/*标题动画*/
<?php if (iro_opt('page_title_animation', 'true')): ?>
.entry-title,.single-center .entry-census a,.entry-census,.post-list p,.post-more i,.p-time,.feature{
	-moz-animation: fadeInDown <?=iro_opt('page_title_animation_time'); ?>s;
    -webkit-animation:fadeInDown <?=iro_opt('page_title_animation_time'); ?>s;
	animation: fadeInDown <?=iro_opt('page_title_animation_time'); ?>s;
}
@-moz-keyframes fadeInUp {
	0% {
		-moz-transform: translateY(200%);
		transform: translateY(200%);
		opacity: 0
	}
 
	50% {
		-moz-transform: translateY(200%);
		transform: translateY(200%);
		opacity: 0
	}
 
	100% {
		-moz-transform: translateY(0%);
		transform: translateY(0%);
		opacity: 1
	}
}
 
@-webkit-keyframes fadeInUp {
	0% {
		-webkit-transform: translateY(200%);
		transform: translateY(200%);
		opacity: 0
	}
 
	50% {
		-webkit-transform: translateY(200%);
		transform: translateY(200%);
		opacity: 0
	}
 
	100% {
		-webkit-transform: translateY(0%);
		transform: translateY(0%);
		opacity: 1
	}
}
 
@keyframes fadeInUp {
	0% {
		-moz-transform: translateY(200%);
		-ms-transform: translateY(200%);
		-webkit-transform: translateY(200%);
		transform: translateY(200%);
		opacity: 0
	}
 
	50% {
		-moz-transform: translateY(200%);
		-ms-transform: translateY(200%);
		-webkit-transform: translateY(200%);
		transform: translateY(200%);
		opacity: 0
	}
 
	100% {
		-moz-transform: translateY(0%);
		-ms-transform: translateY(0%);
		-webkit-transform: translateY(0%);
		transform: translateY(0%);
		opacity: 1
	}
}
<?php endif; ?>

/*首页封面动画*/
<?php if (iro_opt('cover_animation', 'true')): ?>
h1.main-title, h1.fes-title,.the-feature.from_left_and_right .info,.header-info p,.header-info,.focusinfo .header-tou img,.top-social img,.center-text{
	-moz-animation: fadeInDown  <?=iro_opt('cover_animation_time'); ?>s;
    -webkit-animation:fadeInDown  <?=iro_opt('cover_animation_time'); ?>s;
	animation: fadeInDown  <?=iro_opt('cover_animation_time'); ?>s;
}
@-moz-keyframes fadeInDown {
	0% {
		-moz-transform: translateY(-100%);
		transform: translateY(-100%);
		opacity: 0
	}
 
	50% {
		-moz-transform: translateY(-100%);
		transform: translateY(-100%);
		opacity: 0
	}
 
	100% {
		-moz-transform: translateY(0%);
		transform: translateY(0%);
		opacity: 1
	}
}
 
@-webkit-keyframes fadeInDown {
	0% {
		-webkit-transform: translateY(-100%);
		transform: translateY(-100%);
		opacity: 0
	}
 
	50% {
		-webkit-transform: translateY(-100%);
		transform: translateY(-100%);
		opacity: 0
	}
 
	100% {
		-webkit-transform: translateY(0%);
		transform: translateY(0%);
		opacity: 1
	}
}
 
@keyframes fadeInDown {
	0% {
		-moz-transform: translateY(-100%);
		-ms-transform: translateY(-100%);
		-webkit-transform: translateY(-100%);
		transform: translateY(-100%);
		opacity: 0
	}
 
	50% {
		-moz-transform: translateY(-100%);
		-ms-transform: translateY(-100%);
		-webkit-transform: translateY(-100%);
		transform: translateY(-100%);
		opacity: 0
	}
 
	100% {
		-moz-transform: translateY(0%);
		-ms-transform: translateY(0%);
		-webkit-transform: translateY(0%);
		transform: translateY(0%);
		opacity: 1
	}
}
<?php endif; ?>

/*导航菜单动画*/
<?php if (iro_opt('nav_menu_animation', 'true')): ?>
.site-top ul {
	-moz-animation: fadeInLeft  <?=iro_opt('nav_menu_animation_time'); ?>s;
    -webkit-animation:fadeInLeft  <?=iro_opt('nav_menu_animation_time'); ?>s;
	animation: fadeInLeft  <?=iro_opt('nav_menu_animation_time'); ?>s;
}
@-moz-keyframes fadeInLeft {
	0% {
		-moz-transform: translateX(100%);
		transform: translateX(100%);
		opacity: 0
	}
 
	50% {
		-moz-transform: translateX(100%);
		transform: translateX(100%);
		opacity: 0
	}
 
	100% {
		-moz-transform: translateX(0%);
		transform: translateX(0%);
		opacity: 1
	}
}
 
@-webkit-keyframes fadeInLeft {
	0% {
		-webkit-transform: translateX(100%);
		transform: translateX(100%);
		opacity: 0
	}
 
	50% {
		-webkit-transform: translateX(100%);
		transform: translateX(100%);
		opacity: 0
	}
 
	100% {
		-webkit-transform: translateX(0%);
		transform: translateX(0%);
		opacity: 1
	}
}
 
@keyframes fadeInLeft {
	0% {
		-moz-transform: translateX(100%);
		-ms-transform: translateX(100%);
		-webkit-transform: translateX(100%);
		transform: translateX(100%);
		opacity: 0
	}
 
	50% {
		-moz-transform: translateX(100%);
		-ms-transform: translateX(100%);
		-webkit-transform: translateX(100%);
		transform: translateX(100%);
		opacity: 0
	}
 
	100% {
		-moz-transform: translateX(0%);
		-ms-transform: translateX(0%);
		-webkit-transform: translateX(0%);
		transform: translateX(0%);
		opacity: 1
	}
}
<?php endif; ?>

/*其他*/

.widget-area .sakura_widget{
    background-image: url(<?=iro_opt('sakura_widget_background', ''); ?>);
}

.headertop{
    border-radius: 0 0 <?=iro_opt('cover_radius', ''); ?>px <?=iro_opt('cover_radius', ''); ?>px;
}

<?php if (!iro_opt('article_lincenses', 'true')): ?>
.post-footer {
display:none;
}
<?php endif; ?>

<?php if (!iro_opt('nav_menu_user_avatar', 'true')): ?>
.header-user-avatar{
display:none;
}
<?php endif; ?>

<?php if (!iro_opt('drop_down_arrow_mobile', 'true')): ?>
@media (max-width: 860px) {
.headertop-down {
        display: none
    }
}
<?php endif; ?>

<?php if (!iro_opt('post_icon_more', 'true')): ?>
.float-content i {
    display: none;
}
<?php endif; ?>

<?php if (!iro_opt('social_area', 'true')): ?>
.top-social_v2,.top-social{
    display: none;
}
<?php endif; ?>

<?php if(iro_opt('nav_menu_icon_size') == 'large'){ ?>
i.iconfont.js-toggle-search.iconsearch {
    font-size: 25px;
}
.lower li ul {
    right: -13px;
}
#show-nav .line {
    width: 28px;
    margin-left: -15px;
}
#show-nav {
    width: 30px;
    height: 33px;
}

<?php }else if(iro_opt('nav_menu_search_size') == 'standard'){ ?>

<?php } ?>

<?php if(iro_opt('friend_link_align') == 'right'){ ?>

span.sitename {
   margin-bottom: 0px;
   margin-top: 8px;
}
li.link-item {
    text-align: right;
}
.links ul li img{
	float:none;
}

<?php }else if(iro_opt('friend_link_align') == 'center'){ ?>

span.sitename {
   margin-bottom: 0px;
   margin-top: 8px;
}
li.link-item {
    text-align: center;
}
.links ul li img{
	float:none;
}

<?php } ?>


<?php if(iro_opt('post_list_image_align') == 'left'){ ?>
.post-list-thumb .post-content-wrap {
    float: left;
    padding-left: 30px;
    padding-right: 0;
    text-align: right;
    margin: 20px 10px 10px 0
}
.post-list-thumb .post-thumb {
    float: left
}

.post-list-thumb .post-thumb a {
    border-radius: 10px 0 0 10px
}

<?php }else if(iro_opt('post_list_image_align') == 'alternate'){ ?>
.post-list-thumb:nth-child(2n) .post-content-wrap {
    float: left;
    padding-left: 30px;
    padding-right: 0;
    text-align: right;
    margin: 20px 10px 10px 0
}
.post-list-thumb:nth-child(2n) .post-thumb {
    float: left
}

.post-list-thumb:nth-child(2n) .post-thumb a {
    border-radius: 10px 0 0 10px
}
<?php } ?>

<?php if(iro_opt('page_style') == 'sakurairo'){ ?>
.pattern-center::after {
    display:none;
}
.pattern-center-blank {
    display:none;
}

<?php }else if(iro_opt('page_style') == 'sakura'){ ?>
.pattern-center {
    position: relative;
    top: 0;
    left: 0;
    width: 100%;
    border-radius: 0px;
    overflow: hidden;
}
<?php } ?>
<?php if(iro_opt('nav_menu_style') == 'sakurairo'){ ?>
.yya {
    position: fixed;
    -webkit-transition: all 1s ease !important;
    transition: all 1s ease !important;
    width:<?=iro_opt('nav_menu_shrink_animation', ''); ?>% ;
	left:calc(97.5% - <?=iro_opt('nav_menu_shrink_animation', ''); ?>%);
    box-shadow: 0 1px 40px -8px rgba(255, 255, 255, .4);
    border-radius: <?=iro_opt('nav_menu_radius', ''); ?>px !important;
}
.site-title img {
    margin-left: 10px;
}
.site-header {
    border-radius: <?=iro_opt('nav_menu_radius', ''); ?>px !important;
}
.header-user-menu {
    border-radius: <?=iro_opt('nav_menu_secondary_radius', ''); ?>px !important;
}
.lower li ul {
    border-radius: <?=iro_opt('nav_menu_secondary_radius', ''); ?>px !important;
}
@media (max-width:860px) {
.openNav .icon {
        left: 5vw;
    }
.site-header {
    width: 100%;
    height: 60px;
    top: 0;
    left: 0;
    background: 0 0;
    position: fixed;
    border-radius: 0 !important;
}
.yya {
    border-radius: 0 !important;
}
}

<?php }if(iro_opt('nav_menu_style') == 'sakura'){ ?>
.site-header {
    width: 100%;
    height: 75px;
    top: 0;
    left: 0;
    background: 0 0;
    -webkit-transition: all .4s ease;
    transition: all .4s ease;
    position: fixed;
    z-index: 999;
    border-radius: 0px;
}
@media (max-width:860px) {
.site-header {
    height: 60px;
}
}
<?php } ?>

<?php if (!iro_opt('nav_menu_secondary_arrow', 'true')): ?>
.header-user-menu::before {
    display: none;
}
.lower li ul::before {
    display: none;
}
<?php endif; ?>

<?php if (!iro_opt('shuoshuo_arrow', 'true')): ?>
.cbp_tmtimeline > li:nth-child(odd) .cbp_tmlabel:after {
    display: none;
}
.cbp_tmtimeline > li .cbp_tmlabel:after {
    display: none;
}
<?php endif; ?>

<?php if (iro_opt('exhibition_area_compat', 'true')): ?>
.the-feature.from_left_and_right {
    position: relative;
    border-radius: <?=iro_opt('exhibition_radius', ''); ?>px;
    height: 160px;
    width: 258px;
    margin: 6px 6px 0 6px;
}

.the-feature img {
    height: 160px;
    width: 258px;
}
<?php endif; ?>

<?php if(iro_opt('bulletin_board_text_align') == 'center'){ ?>
.notice {
    text-align: center;
}

<?php }if(iro_opt('bulletin_board_text_align') == 'right'){ ?>
.notice {
    text-align: right;
}

<?php } ?>

<?php if(iro_opt('area_title_text_align') == 'center'){ ?>
h1.fes-title,
h1.main-title {
    text-align: center;
}

<?php }else if(iro_opt('area_title_text_align') == 'right'){ ?>
h1.fes-title,
h1.main-title {
    text-align: right;
}

<?php } ?>

<?php if(iro_opt('bulletin_board_style') == 'picture'){ ?>
.notice {
    background-image:url(<?=iro_opt('bulletin_board_bg', ''); ?>);
    background-repeat: round;
    border: none;
    box-shadow: 1px 1px 3px rgba(0, 0, 0, .3);
}

<?php }if(iro_opt('bulletin_board_style') == 'pure'){ ?>
.notice {
    background: #fbfbfb50;
}

<?php } 
$nav_menu_blur=iro_opt('nav_menu_blur','0');
if($nav_menu_blur != '0'){
?>
.yya{
    backdrop-filter: blur(<?=$nav_menu_blur?>px);
    -webkit-backdrop-filter: blur(<?=$nav_menu_blur?>px);
}
<?php
}
?>
<?php 
if(iro_opt('cover_half_screen_curve',true)){
   ?> 
   .headertop-bar::after {
    content: '';
    width: 150%;
    height: 4.375rem;
    background: #fff;
    left: -25%;
    bottom: -2.875rem;
    border-radius: 100%;
    position: absolute;
    z-index: 4;
}
   <?php
}
?>
body{
    background-size:<?=iro_opt(('reception_background_size'),'auto')
?>;
}
#video-add{
    background-image: url(<?=iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@2.5/')?>basic/add.png);
}
body.dark .post-footer {
  background-image: url(<?=iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@2.5/')?>basic/creativecommons-dark.png);
}
@media (max-width:860px) {
  .headertop.filter-dot::before {
    background-image: url(<?=iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@2.5/')?>basic/grid.png);
  }
}
.post-footer{background-image: url(<?=iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@2.5/')?>basic/creativecommons-light.png);}
.headertop.filter-grid::before {
  background-image: url(<?=iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@2.5/')?>basic/grid.png);
}

.headertop.filter-dot::before {
  background-image: url(<?=iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@2.5/')?>basic/dot.gif);
}
.loadvideo,.video-play {
  background-image: url(<?=iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@2.5/')?>basic/play.png);
}

.video-pause {
  background-image: url(<?=iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@2.5/')?>basic/stop.png);
}
#loading-comments {
background-image: url(<?=iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@2.5/')?>load_svg/ball.svg);
}

<?php if (iro_opt('wave_effects', 'true')): ?>
#banner_wave_1 {
    background: url(<?=iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@2.5/')?>basic/wave1.png) repeat-x;
}
#banner_wave_2 {
    background: url(<?=iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@2.5/')?>basic/wave2.png) repeat-x;
}
<?php endif; ?>

</style>
<?php }
add_action('wp_head', 'customizer_css');
