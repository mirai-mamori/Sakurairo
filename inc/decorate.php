<?php
function customizer_css() { ?>
<style>
<?php 
/* 主题皮肤与根变量 */
if (iro_opt('theme_skin')) { ?>
:root {
    --theme-skin: <?=iro_opt('theme_skin'); ?>;
    --theme-skin-matching: <?=iro_opt('theme_skin_matching'); ?>;
    --homepage_widget_transparency:<?=iro_opt('homepage_widget_transparency'); ?>;
    --style_menu_selection_radius:<?=iro_opt('style_menu_selection_radius', ''); ?>px;
    --load_nextpage_svg:url("<?=iro_opt('load_nextpage_svg'); ?>");
    --style_menu_radius:<?=iro_opt('style_menu_radius', ''); ?>px;
    --inline_code_background_color:<?=iro_opt('inline_code_background_color');?>;
    --theme-skin-dark:  <?=iro_opt('theme_skin_dark'); ?>;
    --global-font-weight:<?=iro_opt('global_font_weight');?>;
    --theme-dm-background_transparency:<?=iro_opt('theme_darkmode_background_transparency')?>;
    --inline_code_background_color_in_dark_mode:<?=iro_opt('inline_code_background_color_in_dark_mode');?>;
    --front_background-transparency:<?=iro_opt('reception_background_transparency'); ?>;
}

/* 纪念模式 */
<?php if (iro_opt('theme_commemorate_mode')) { ?>
html {
    filter: grayscale(100%) !important;
}
<?php } ?>

/* 字体优化 */
html {
    font-display: swap;
}

<?php $text_logo = iro_opt('text_logo'); ?>
.center-text{
color: <?=$text_logo['color']; ?> ;
font-size: <?=$text_logo['size']; ?>px;
}
.Ubuntu-font,.center-text{
font-family: <?= isset($text_logo['font']) ? $text_logo['font'] : 'Noto Serif SC'; ?> !important;
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

<?php 
//$image_api = 'background-image: url("'.rest_url('sakura/v1/gallery').'");';
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
    z-index: 999;
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

body.dark .header-info
{color:#fff;background:rgba(51,51,51,<?=iro_opt('theme_darkmode_widget_transparency'); ?>);transition: all 0.6s ease-in-out;}

body.dark .top-social img
{background:rgba(51,51,51,<?=iro_opt('theme_darkmode_widget_transparency'); ?>);transition: all 0.6s ease-in-out;}

body.dark .top-social_v2 i
{color:#ababab;transition: all 0.6s ease-in-out;}

body.dark .top-social i
{color:#ababab;background:rgba(51,51,51,<?=iro_opt('theme_darkmode_widget_transparency'); ?>);transition: all 0.6s ease-in-out;}

body.dark input[type=submit] 
{background-color:rgba(38,38,38,<?=iro_opt('theme_darkmode_widget_transparency'); ?>) !important;transition: all 0.6s ease-in-out;}

body.dark .headertop-down svg path 
{fill: <?=iro_opt('drop_down_arrow_dark_color'); ?> !important;transition: all 0.6s ease-in-out;}

body.dark img,
body.dark .highlight-wrap,
body.dark iframe,
body.dark .entry-content .aplayer,
body.dark .post-thumb video
{filter:brightness(<?=iro_opt('theme_darkmode_img_bright'); ?>);}

body.dark .headertop {backdrop-filter:brightness(<?=iro_opt('theme_darkmode_img_bright'); ?>);}

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

.site-header a,.header-user-name,.header-user-menu a {
font-family:<?=iro_opt('nav_menu_font'); ?> !important;
}

<?php if (iro_opt('nav_text_logo')) {
$nav_text_logo = iro_opt('nav_text_logo');
?>

.site-title a{
font-family: '<?php echo $nav_text_logo['font_name']; ?>';
}

<?php } ?>

<?php if(iro_opt('footer_direction','columns') == 'center'): ?>
.footer-content {
    flex-direction: column;
    align-items: center;
    text-align: center;
}
<?php endif; ?>

.site-info,.site-info a{
font-family:<?=iro_opt('footer_text_font'); ?> !important;
}

.skin-menu p{
font-family:<?=iro_opt('style_menu_font'); ?> !important;
}

h1.main-title,h1.fes-title{
font-family:<?=iro_opt('area_title_font'); ?>;
}

.header-info p{
font-family:<?=iro_opt('signature_font'); ?> !important;
font-size: <?=iro_opt('signature_font_size'); ?>px;
}

.post-list-thumb .post-title h3{
font-size: <?=iro_opt('post_title_font_size'); ?>px !important;
}

.pattern-center h1.cat-title,
.pattern-center h1.entry-title {
font-size: <?=iro_opt('page_temp_title_font_size'); ?>px ;
}

.single-center .single-header h1.entry-title {
font-size: <?=iro_opt('article_title_font_size'); ?>px ;
}

/*背景类*/
.comment-respond textarea {
background-image: url(<?=iro_opt('comment_area_image'); ?>); 
background-size: contain;
background-repeat: no-repeat;
background-position: right;
}

.search-form.is-visible{
background-image: url(<?=iro_opt('search_area_background'); ?>);
}

.site-footer {
background-color: rgba(255, 255, 255,var(--front_background-transparency,<?=iro_opt('reception_background_transparency'); ?>));
}

.wrapper {
background-color: rgba(255, 255, 255,var(--front_background-transparency,<?=iro_opt('reception_background_transparency'); ?>));
}

/*首页圆角设置*/
.header-info{
border-radius: <?=iro_opt('signature_radius'); ?>px;
}

.top-social i,
.focusinfo img{
border-radius: <?=iro_opt('social_area_radius'); ?>px;
}

.focusinfo .header-tou img{
border-radius: <?=iro_opt('avatar_radius'); ?>px;
}

/*标题横线动画*/
<?php if (iro_opt('article_title_line', 'true')): ?>
@media (min-width:860px) {
.single-center .single-header h1.entry-title::after {
    content: '';
    position: absolute;
    top: 40%;
    left: 10%;
    border-radius: 10px;
    display: inline-block;
    width: 20%;
    height: 10px;
    z-index: 1;
    background-color: var(--article-theme-highlight,var(--theme-skin-matching));
    animation: lineWidth 2s <?=iro_opt('page_title_animation_time'); ?>s forwards;
    opacity: 0;
}
}

@keyframes lineWidth {
    0% {
        width: 0;
        opacity: 0;
    }
    100% {
        width: 20%;
        opacity: 0.5;
    }
}
<?php endif; ?>

/*标题动画*/
<?php if (iro_opt('page_title_animation', 'true')): ?>
.entry-title,.single-center .entry-census,.entry-census,.p-time{
    -webkit-animation:homepage-load-animation <?=iro_opt('page_title_animation_time'); ?>s;
	animation: homepage-load-animation <?=iro_opt('page_title_animation_time'); ?>s;
}
 
@-webkit-keyframes fadeInUp {
	0% {
		-webkit-transform: translateY(200%);
		opacity: 0
	}
 
	50% {
		-webkit-transform: translateY(200%);
		opacity: 0
	}
 
	100% {
		-webkit-transform: translateY(0%);
		opacity: 1
	}
}
 
@keyframes fadeInUp {
	0% {
		transform: translateY(200%);
		opacity: 0
	}
 
	50% {
		transform: translateY(200%);
		opacity: 0
	}
 
	100% {
		transform: translateY(0%);
		opacity: 1
	}
}
<?php endif; ?>

/*首页封面动画*/
<?php if (iro_opt('cover_animation', 'true')): ?>
.header-info p,.header-info,.focusinfo .header-tou img,.top-social img,.top-social i,.center-text{
    -webkit-animation:homepage-load-animation  <?=iro_opt('cover_animation_time'); ?>s;
	animation: homepage-load-animation  <?=iro_opt('cover_animation_time'); ?>s;
}
<?php endif; ?>

/*其他*/

.site-branding,
.nav-search-wrapper,
.user-menu-wrapper,
.nav-search-wrapper nav ul li a,
.searchbox.js-toggle-search i,
.bg-switch i {
    border-radius: <?=iro_opt('nav_menu_cover_radius', ''); ?>px;
}

<?php if (!iro_opt('author_profile_avatar', 'true')): ?>
.post-footer .info {
display:none;
}
<?php endif; ?>

<?php if (!iro_opt('author_profile_name', 'true')): ?>
.post-footer .meta {
display:none;
}
<?php endif; ?>

<?php if (!iro_opt('article_tag', 'true')): ?>
.post-tags {
display:none;
}
<?php endif; ?>

<?php if (!iro_opt('article_modified_time', 'true')): ?>
.post-modified-time {
display:none;
}
<?php endif; ?>

<?php if (!iro_opt('nav_menu_user_avatar', 'true')): ?>
.header-user-avatar{
display:none;
}
<?php endif; ?>

<?php if (iro_opt('footer_sakura', 'true')): ?>
@keyframes slow-rotate {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
.sakura-icon{
    width:max-content;height:max-content;margin: auto;
}
.sakura-svg {
animation: slow-rotate 10s linear infinite;
} 
<?php endif; ?>

<?php if (!iro_opt('drop_down_arrow_mobile', 'true')): ?>
@media (max-width: 860px) {
.headertop-down {
        display: none
    }
}
<?php endif; ?>

<?php if (!iro_opt('chatgpt_article_summarize', 'true')): ?>
.ai-excerpt,
.ai-excerpt-tip {
    display: none;
}
<?php endif; ?>

<?php if (!iro_opt('social_area', 'true')): ?>
.top-social_v2,.top-social{
    display: none;
}
<?php endif; ?>

<?php if(iro_opt('post_list_design') == 'ticket'){ ?>
@media (min-width:768px) {
.post-thumb {
    height: 100%;
    width: 80%;
}

.post-excerpt {
    top: 3%;
    width: 18.5%;
    left: 81%;
    max-height: 90%;
    letter-spacing: 1px;
    flex-direction: column;
    display: flex;
    position: relative;
}

.post-title {
    bottom: 6%;
    max-width: 70%;
}

.ai-excerpt-tip{
    margin-right: 40%;
}

.post-excerpt p {
    max-height: 100%;
    -webkit-line-clamp: 11;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    overflow: hidden;
    position: relative;
}

.post-list-thumb:nth-child(2n) .post-thumb{
    right: 0;
}

.post-list-thumb:nth-child(2n) .post-excerpt{
    left: 1%;
}

.post-list-thumb:nth-child(2n) .post-title{
    left: 22%;
}

.post-list-thumb:nth-child(2n) .post-date{
    left: 21.3%;
}

.post-list-thumb:nth-child(2n) .post-meta{
    right: 1.3%;
    max-width: 59.5%;
}

}

@media (min-width:860px) {
.post-meta{
    right: 21.5%;
    max-width: 59.5%;
}

}

@media (max-width:860px) {
.post-meta{
    right: 10px;
    height: fit-content;
    width: fit-content;
    max-height: 32%;
    max-width: 32%;
    top: 10px;
    flex-direction: column;
    transition: all 0.6s ease-in-out;
    -webkit-transition: all 0.6s ease-in-out;
}

}

@media (min-width: 768px) and (max-width: 860px) {
.post-meta{
    right: 21.5%;
    height: fit-content;
    width: fit-content;
    max-height: 32%;
    max-width: 32%;
    top: 10px;
    flex-direction: column;
    transition: all 0.6s ease-in-out;
    -webkit-transition: all 0.6s ease-in-out;
}

}

<?php } ?>

<?php if(iro_opt('post_list_design') == 'ticket' && iro_opt('post_list_ticket_type') == 'non-card'){ ?>
@media (min-width:768px) {
.post-thumb:after{
  content: "";
  position: absolute;
  bottom:0;
  left:0;
  width:100%;
  height:81px;
  background:linear-gradient( 
    #fff0, 
    #000d 
  );
  backdrop-filter: saturate(180%) blur(10px);
}
.post-title {
  background-color: transparent;
  border: none;
  backdrop-filter: none;
  -webkit-backdrop-filter: none;
  box-shadow: none;
}
body.dark .post-title {
  background-color: transparent;
  border: none;
  box-shadow: none;
}
.post-title:hover {
  background-color: transparent;
  border: none;
  backdrop-filter: none;
  -webkit-backdrop-filter: none;
  box-shadow: none;
}
body.dark .post-title:hover{
  background-color: transparent;
  border: none;
  box-shadow: none;
}
.post-list-thumb .post-title h3 {
  color: #EEE9E9;
}
}
<?php } ?>

<?php 
// Menu style settings
$nav_menu_style = iro_opt('nav_menu_style');
$has_user_avatar = iro_opt('nav_menu_user_avatar');
$has_logo = !empty(iro_opt('iro_logo')) || !empty($nav_text_logo['text']); 

// Space between menu items when avatar and logo are enabled
if($nav_menu_style == 'space-between' && ($has_user_avatar || $has_logo)){ ?> 
.site-header {
    justify-content: space-between; 
}
.header-user-menu {
    right: -5%;
}
.site-branding{
  background: none;
  -webkit-backdrop-filter: none;
  backdrop-filter: none;
  border: none;
  box-shadow: none;
}
body.dark .site-branding{
  background: none;
  -webkit-backdrop-filter: none;
  backdrop-filter: none;
  border: none;
  box-shadow: none;
}
<?php } else { ?>
@media (min-width: 860px) {
.site-header {
    justify-content: center;
}
}
.header-user-menu {
    right: -105%;
}
<?php } ?>

<?php if ($nav_menu_style == 'space-between' && $has_user_avatar): ?>
.site-title {
    display: none;
}
.site-branding{
    min-width: 45px;
}
<?php endif; ?>

<?php if (!iro_opt('article_meta_displays', 'true')): ?>
.post-meta {
    display: none;
}
<?php endif; ?>

.shuoshuo-item,
.post-list-thumb {
    border-radius: <?=iro_opt('post_list_card_radius'); ?>px !important;
}

.post-date, .post-meta {
    border-radius: <?=iro_opt('post_meta_radius'); ?>px !important;
}

.post-title {
    border-radius: <?=iro_opt('post_list_title_radius'); ?>px !important;
}

<?php if (iro_opt('article_meta_background_compatible', 'true')): ?>
.post-date, .post-meta {
  color: rgba(255, 255, 255, 0.8) !important;
  background-color: #33333360 !important;
  border: 1px solid #7d7d7d30 !important;
  transition: all 0.6s ease !important;
  -webkit-transition: all 0.6s ease !important;
}
.post-meta a {
  color: rgba(255, 255, 255, 0.8) !important;
  transition: all 0.6s ease !important;
  -webkit-transition: all 0.6s ease !important;
}
.post-list-thumb i{
  color: rgba(255, 255, 255, 0.8);
  transition: all 0.6s ease !important;
  -webkit-transition: all 0.6s ease !important;
}


body.dark .post-date,
body.dark .post-meta {
  color: rgba(255, 255, 255, 0.8) !important;
  background-color: #33333360 !important;
  border: 1px solid #7d7d7d30 !important;
  transition: all 0.6s ease !important;
  -webkit-transition: all 0.6s ease !important;
}

body.dark .post-meta a {
  color: rgba(255, 255, 255, 0.8) !important;
  transition: all 0.6s ease !important;
  -webkit-transition: all 0.6s ease !important;
}

body.dark .post-list-thumb i{
  color: rgba(255, 255, 255, 0.8);
  transition: all 0.6s ease !important;
  -webkit-transition: all 0.6s ease !important;
}

<?php endif; ?>

<?php if(iro_opt('area_title_text_align') == 'center'){ ?>
h1.fes-title,
h1.main-title {
    justify-content: center;
}

<?php }else if(iro_opt('area_title_text_align') == 'right'){ ?>
h1.fes-title,
h1.main-title {
    justify-content: right;
}

<?php } ?>

<?php 
if(iro_opt('cover_half_screen_curve',true)){
   ?> 
   .headertop-bar::after {
    content: '';
    width: 150%;
    height: 4.375rem;
    background-color: rgba(255, 255, 255,var(--front_background-transparency,<?=iro_opt('reception_background_transparency'); ?>));
<?php if (iro_opt('reception_background_blur', 'false')): ?> backdrop-filter: saturate(180%) blur(10px); <?php endif; ?>
<?php if (iro_opt('reception_background_blur', 'false')): ?> -webkit-backdrop-filter: saturate(180%) blur(10px); <?php endif; ?>
    left: -25%;
    bottom: -2.875rem;
    border-radius: 100%;
    position: absolute;
    z-index: 4;
}
   .headertop{
    border-radius:0 !important;
}
   .wrapper{
    border-top:0 !important;
}
   <?php
}
?>
body{
    background-size:<?=iro_opt(('reception_background_size'),'auto')
?>;
}
#video-add{
    background-image: url(<?=iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@3.0/')?>basic/add.svg);
}
@media (max-width:860px) {
  .headertop.filter-dot::before {
    background-image: url(<?=iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@3.0/')?>basic/grid.png);
  }
}
.headertop.filter-grid::before {
  background-image: url(<?=iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@3.0/')?>basic/grid.png);
}

.headertop.filter-dot::before {
  background-image: url(<?=iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@3.0/')?>basic/dot.gif);
}
.loadvideo,.video-play {
  background-image: url(<?=iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@3.0/')?>basic/play.svg);
}

.video-pause {
  background-image: url(<?=iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@3.0/')?>basic/pause.svg);
}

#loading-comments {
background-image: url(<?=iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@3.0/')?>basic/puff-load.svg);
}

<?php if (iro_opt('wave_effects', 'true')): ?>
#banner_wave_1 {
    background: url(<?=iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@3.0/')?>basic/wave1.png) repeat-x;
}
#banner_wave_2 {
    background: url(<?=iro_opt('vision_resource_basepath', 'https://s.nmxc.ltd/sakurairo_vision/@3.0/')?>basic/wave2.png) repeat-x;
}
.headertop{
    border-radius:0 !important;
}
<?php endif; ?>

</style>
<?php }
add_action('wp_head', 'customizer_css', 10);
