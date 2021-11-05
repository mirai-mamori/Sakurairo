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
    --theme-skin: <?php echo iro_opt('theme_skin'); ?>;
}
.author-profile i , .post-like a , .post-share .show-share , .sub-text , .we-info a , span.sitename { color: <?php echo iro_opt('theme_skin'); ?> }
<?php if (iro_opt('theme_commemorate_mode')) {?>
    html{
        filter: grayscale(100%) !important;
    }
<?php } ?>
.feature i , /*.feature-title span ,*/ .download , .links ul li:before , .ar-time i , span.ar-circle , .object , .comment .comment-reply-link , .siren-checkbox-radio:checked + .siren-checkbox-radioInput:after { background: <?php echo iro_opt('theme_skin'); ?> }
.download ,  .link-title  { border-color: <?php echo iro_opt('theme_skin'); ?> }
 .comment h4 a , #comments-navi a.prev , #comments-navi a.next , #archives-temp h3 , span.page-numbers.current , blockquote:before, blockquote:after { color: <?php echo iro_opt('theme_skin'); ?> }

#aplayer-float .aplayer-lrc-current { color: <?php echo iro_opt('theme_skin'); ?> !important}

.linkdes { border-top: 1px dotted <?php echo iro_opt('theme_skin'); ?> !important}

.the-feature.from_left_and_right .info,.the-feature.from_left_and_right .info h3{background: <?php echo iro_opt('exhibition_background_color'); ?> ;}

.is-active-link::before, .commentbody:not(:placeholder-shown)~.input-label, .commentbody:focus~.input-label {
    background-color: <?php echo iro_opt('theme_skin'); ?> !important
}
.links ul li {
    border: 1px solid <?php echo iro_opt('theme_skin'); ?>;
}
.links ul li img {
    box-shadow: inset 0 0 10px <?php echo iro_opt('theme_skin'); ?>;
}
.commentbody:focus {
    border-color: <?php echo iro_opt('theme_skin'); ?> !important
}
.post-list-thumb i ,
.float-content i ,
.post-more i { 
    color: <?php echo iro_opt('post_icon_color'); ?>;
}
i.iconfont.js-toggle-search.iconsearch {
    color: <?php echo iro_opt('theme_skin'); ?>;
}
.ins-section .fa {
    color: <?php echo iro_opt('theme_skin'); ?>
}
.ins-section .ins-search-item.active .ins-slug,.ins-section .ins-search-item.active .ins-search-preview {
	color: <?php echo iro_opt('theme_skin'); ?>
}
.ins-section .ins-section-header {
    color: <?php echo iro_opt('theme_skin'); ?>;
    border-bottom: 3px solid <?php echo iro_opt('theme_skin'); ?>;
    border-color: <?php echo iro_opt('theme_skin'); ?>;
}
.the-feature.from_left_and_right .info h3{
    color: <?php echo iro_opt('theme_skin'); ?>;
}
.the-feature.from_left_and_right .info p {
    color: <?php echo iro_opt('theme_skin'); ?>;
}
.err-button.back a {
    border: 1px solid <?php echo iro_opt('theme_skin'); ?>;
    color: <?php echo iro_opt('theme_skin'); ?>;
}

.header-info {
    color: <?php echo iro_opt('theme_skin'); ?>;
    background: <?php echo iro_opt('infor_bar_bgcolor'); ?>;
}
.top-social img {
    background: <?php echo iro_opt('infor_bar_bgcolor'); ?>;
}
.skin-menu{
    background-color: <?php echo iro_opt('style_menu_background_color'); ?>;
    border-radius: <?php echo iro_opt('style_menu_selection_radius', ''); ?>px;
}

.post-more i:hover , #pagination a:hover , .post-content a:hover , .float-content i:hover , .entry-content a:hover , .site-info a:hover , .comment h4 a:hover , .site-top ul li a:hover , .entry-title a:hover , .sorry li a:hover , .site-title a:hover , i.iconfont.js-toggle-search.iconsearch:hover , .comment-respond input[type='submit']:hover { color: <?php echo iro_opt('theme_skin_matching'); ?> }
.navigator i:hover { background: <?php echo iro_opt('theme_skin_matching'); ?> }
.navigator i:hover , .links ul li:hover , #pagination a:hover , .comment-respond input[type='submit']:hover { border-color: <?php echo iro_opt('theme_skin_matching'); ?> }
.insert-image-tips:hover, .insert-image-tips-hover{ 
    color: <?php echo iro_opt('theme_skin_matching'); ?>;
    border: 1px solid <?php echo iro_opt('theme_skin_matching'); ?>
}
.ins-section .ins-search-item:hover .ins-slug,.ins-section .ins-search-item:hover .ins-search-preview,.ins-section .ins-search-item:hover header,.ins-section .ins-search-item:hover .iconfont {
	color: <?php echo iro_opt('theme_skin_matching'); ?>
}
.sorry li a:hover {
    color: <?php echo iro_opt('theme_skin_matching'); ?>;
}
.header-info p:hover {
    color: <?php echo iro_opt('theme_skin_matching'); ?>;
	transition: all .4s;
}
.post-tags a:hover {
    color: <?php echo iro_opt('theme_skin_matching'); ?>
}
.post-share ul li a:hover {
    color: <?php echo iro_opt('theme_skin_matching'); ?>
}
#pagination a:hover {
    border: 1px solid <?php echo iro_opt('theme_skin_matching'); ?>;
    color: <?php echo iro_opt('theme_skin_matching'); ?>
}
.ex-login input.login-button:hover,
.ex-register input.register-button:hover {
    background-color: <?php echo iro_opt('theme_skin_matching'); ?>;
    border-color: <?php echo iro_opt('theme_skin_matching'); ?>;
}
.site-top ul li a:after {
    background-color: <?php echo iro_opt('theme_skin_matching'); ?>
}
input[type=color],
input[type=date],
input[type=datetime-local],
input[type=datetime],
input[type=email],
input[type=month],
input[type=number],
input[type=password],
input[type=range],
input[type=search],
input[type=tel],
input[type=text],
input[type=time],
input[type=url],
input[type=week],
textarea {
    border: 1px solid <?php echo iro_opt('theme_skin'); ?>;
}
.post-date {
    color: <?php echo iro_opt('post_date_text_color'); ?>
}
.linkdes {
    color: <?php echo iro_opt('theme_skin'); ?>
}
.link-title span.link-fix {
    border-left:3px solid <?php echo iro_opt('theme_skin'); ?>;
}
.scrollbar,.butterBar-message {
    background: <?php echo iro_opt('theme_skin'); ?> !important
}
h1.cat-title {
    color: <?php echo iro_opt('theme_skin'); ?>
}
.comment .body .comment-at {
    color: <?php echo iro_opt('theme_skin'); ?>
}
.focusinfo .header-tou img {
    box-shadow: inset 0 0 10px <?php echo iro_opt('theme_skin'); ?>;
}
.double-bounce1, .double-bounce2 {
    background-color: <?php echo iro_opt('theme_skin'); ?>;
  }
.herder-user-name no-logged{
    color: <?php echo iro_opt('theme_skin'); ?>;
}
#pagination .loading {
    background: url(<?php echo iro_opt('load_nextpage_svg'); ?>);
    background-position: center;
    background-repeat: no-repeat;
    color: #555;
    border: none;
    background-size: auto 100%
}
#pagination .loading,#bangumi-pagination .loading {
    background: url(<?php echo iro_opt('load_nextpage_svg'); ?>);
    background-position: center;
    background-repeat: no-repeat;
    color: #555;
    border: none;
    background-size: auto 100%
}
#nprogress .spinner-icon {
    border-top-color: <?php echo iro_opt('theme_skin'); ?>;
    border-left-color: <?php echo iro_opt('theme_skin'); ?>;
}
#nprogress .peg {
    box-shadow: 0 0 10px <?php echo iro_opt('theme_skin'); ?>, 0 0 5px <?php echo iro_opt('theme_skin'); ?>;
}
#nprogress .bar {
    background: <?php echo iro_opt('theme_skin'); ?>;
}
#gohome {
    background: <?php echo iro_opt('theme_skin'); ?>;
}
#archives-temp h2 {
    color: <?php echo iro_opt('theme_skin'); ?>
}
#archives-temp h3 {
    color: <?php echo iro_opt('theme_skin'); ?>
}
#moblieGoTop {
    color: <?php echo iro_opt('theme_skin'); ?>;
    border-radius: <?php echo iro_opt('style_menu_radius', ''); ?>px;
}
#changskin {
    color: <?php echo iro_opt('theme_skin'); ?>;
    border-radius: <?php echo iro_opt('style_menu_radius', ''); ?>px;
}
#show-nav .line {
    background: <?php echo iro_opt('theme_skin'); ?>
}
#nprogress .spinner-icon{ 
    border-top-color: <?php echo iro_opt('theme_skin'); ?>; 
    border-left-color: <?php echo iro_opt('theme_skin'); ?>
}
#nprogress .bar {
    background: <?php echo iro_opt('theme_skin'); ?>
}
#aplayer-float .aplayer-lrc-current {
    color: <?php echo iro_opt('theme_skin'); ?>;
}
@media (max-width:860px) {
    .links ul li:hover .sitename {
        color: <?php echo iro_opt('theme_skin_matching'); ?>
    }
    .openNav .icon {
     background-color: <?php echo iro_opt('theme_skin'); ?>;
    }
    .openNav .icon:after,
    .openNav .icon:before {
     background-color: <?php echo iro_opt('theme_skin'); ?>;
    }
    #mo-nav ul li a {
        color: <?php echo iro_opt('theme_skin'); ?>;
    }
    #mo-nav ul li a:hover {
        color: <?php echo iro_opt('theme_skin_matching'); ?>;
    }
}
.herder-user-name {
	color: <?php echo iro_opt('theme_skin'); ?>;
}
.header-user-menu a {
    color: <?php echo iro_opt('theme_skin'); ?>;
}
.no-logged a {
    color: <?php echo iro_opt('theme_skin'); ?>;
}
.single-reward .reward-open {
    background: <?php echo iro_opt('theme_skin_matching'); ?>;
    color: <?php echo iro_opt('theme_skin'); ?>;
}

/*白猫样式Logo*/
<?php if (iro_opt('mashiro_logo_option', 'true')): ?>
.logolink .sakuraso {
    background-color: rgba(255, 255, 255, .5);
    border-radius: 5px;
    color: <?php echo iro_opt('theme_skin'); ?>;
    height: auto;
    line-height: 25px;
    margin-right: 0;
    padding-bottom: 0px;
    padding-top: 1px;
    text-size-adjust: 100%;
    width: auto
}
 
.logolink a:hover .sakuraso {
    background-color: <?php echo iro_opt('theme_skin_matching'); ?>;
    color: #fff;
}
 
.logolink a:hover .shironeko,
.logolink a:hover .no,
.logolink a:hover rt {
    color: <?php echo iro_opt('theme_skin_matching'); ?>;
}
 
.logolink.moe-mashiro a {
    color: <?php echo iro_opt('theme_skin'); ?>;
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
    color: <?php echo iro_opt('theme_skin'); ?>;
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
<?php endif; ?>

.logolink a {
    color: <?php echo iro_opt('theme_skin'); ?>;
}

.art .art-content .al_mon_list .al_post_list>li:after {
    background: <?php echo iro_opt('theme_skin'); ?>;
}
@media (min-width:861px) {
    .hide-live2d {
        background-color: <?php echo iro_opt('theme_skin'); ?>;
    }
}
.art-content #archives .al_mon_list .al_mon:after {
    background: <?php echo iro_opt('theme_skin'); ?>;
}
.art-content #archives .al_mon_list:before {
    background: <?php echo iro_opt('theme_skin'); ?>;
}
.changeSkin-gear {
    color: <?php echo iro_opt('theme_skin'); ?>;
}
.art .art-content .al_mon_list .al_post_list>li:after,
.art-content #archives .al_mon_list .al_mon:after {
    background: <?php echo iro_opt('theme_skin'); ?>;
}
.is-active-link::before {
    background-color: <?php echo iro_opt('theme_skin'); ?>; /*!important*/ /*mark*/
}
.motion-switcher-table th:hover {
    color: <?php echo iro_opt('theme_skin_matching'); ?>;
}
.motion-switcher-table .on-hover {
    color: <?php echo iro_opt('theme_skin_matching'); ?>;
}
.menhera-container .emoji-item {
    color: <?php echo iro_opt('theme_skin'); ?>;
}
.scrollbar {
    background: <?php echo iro_opt('theme_skin'); ?>;
}
.insert-image-tips:hover {
    color: <?php echo iro_opt('theme_skin_matching'); ?>;
    border: 1px solid <?php echo iro_opt('theme_skin_matching'); ?>;
}
.insert-image-tips-hover {
    color: <?php echo iro_opt('theme_skin_matching'); ?>;
    border: 1px solid <?php echo iro_opt('theme_skin_matching'); ?>;
}
.the-feature a {
    color: <?php echo iro_opt('theme_skin'); ?>;
}
#moblieGoTop:hover,
#changskin:hover {
    color: <?php echo iro_opt('theme_skin_matching'); ?>;
    background-color: #fff;
    opacity: .8;
}
.menu-list li:hover {
    background-color: <?php echo iro_opt('theme_skin_matching'); ?>;
}
.art .art-content #archives a:hover {
    color: <?php echo iro_opt('theme_skin_matching'); ?>;
}
.art .art-content .al_mon_list .al_post_list>li,
.art-content #archives .al_mon_list .al_mon {
	color: <?php echo iro_opt('theme_skin'); ?>;
}
.font-family-controls button.selected{
    background-color: <?php echo iro_opt('theme_skin'); ?>;
}
.font-family-controls button:hover{
    background-color: <?php echo iro_opt('theme_skin_matching'); ?>;
}
.art-content #archives .al_mon_list .al_mon,
.art-content #archives .al_mon_list span {
    color: <?php echo iro_opt('theme_skin'); ?>;
}
h1.fes-title,
h1.main-title {
    color: <?php echo iro_opt('theme_skin'); ?>;
}
.font-family-controls button {
    color: <?php echo iro_opt('theme_skin'); ?>;
    border-radius: <?php echo iro_opt('style_menu_radius', ''); ?>px;
}

/*非全局色彩管理*/

:root{
    --post-list-thumb: <?php echo iro_opt('post_border_shadow_color'); ?>;
}

.menu-list li {
    background-color: <?php echo iro_opt('style_menu_selection_color'); ?>;
    border-radius: <?php echo iro_opt('style_menu_radius', ''); ?>px;
}
.font-family-controls button {
    background-color: <?php echo iro_opt('style_menu_selection_color'); ?>;
}
h1.fes-title,
h1.main-title {
    border-bottom: 6px dotted <?php echo iro_opt('area_title_bottom_color'); ?>;
}
.post-date {
    background-color: <?php echo iro_opt('post_date_background_color'); ?>;
}
.cbp_tmtimeline > li:nth-child(odd) .cbp_tmlabel {
    background: <?php echo iro_opt('shuoshuo_background_color1'); ?>;
}
.cbp_tmtimeline > li .cbp_tmlabel {
    background: <?php echo iro_opt('shuoshuo_background_color2'); ?>;
}
.cbp_tmtimeline > li:nth-child(odd) .cbp_tmlabel:after {
    border-right-color: <?php echo iro_opt('shuoshuo_background_color1'); ?>;
}
.cbp_tmtimeline > li .cbp_tmlabel:after {
    border-right-color: <?php echo iro_opt('shuoshuo_background_color2'); ?>;
}

<?php $text_logo = iro_opt('text_logo'); ?>
.center-text{
color: <?php echo $text_logo['color']; ?> ;
font-size: <?php echo $text_logo['size']; ?>px;
}
.Ubuntu-font,.center-text{
font-family: <?php echo $text_logo['font']; ?> ;
}

.notice i ,
.notice {
    color: <?php echo iro_opt('bulletin_text_color'); ?>;
}

.notice {
    border: 1px solid <?php echo iro_opt('bulletin_board_border_color'); ?>;
}

<?php if(iro_opt('entry_content_style') == "sakurairo"){ ?>
.entry-content th {
    background-color: <?php echo iro_opt('theme_skin'); ?>
}
<?php } ?>

<?php if(iro_opt('live_search')){ ?>
.search-form--modal .search-form__inner {
    bottom: unset !important;
    top: 10% !important;
}
<?php } ?>

.post-list-thumb{opacity: 0}
.post-list-show {opacity: 1}

<?php } // theme-skin ?>
<?php // Custom style
if ( iro_opt('site_custom_style') ) {
  echo iro_opt('site_custom_style');
} 
// Custom style end ?>
<?php // liststyle
if ( iro_opt('post_list_akina_type') == 'square') { ?>
.feature img{ border-radius: 0px; !important; }
.feature i { border-radius: 0px; !important; }
<?php } // liststyle ?>
<?php 
//$image_api = 'background-image: url("'.rest_url('sakura/v1/image/cover').'");';
$bg_style = !iro_opt('cover_full_screen') ? 'background-position: center center;background-attachment: inherit;' : '';
?>
.centerbg{<?php echo $bg_style ?>background-position: center center;background-attachment: inherit;}
.rotating {
    -webkit-animation: rotating 6s linear infinite;
    -moz-animation: rotating 6s linear infinite;
    -ms-animation: rotating 6s linear infinite;
    -o-animation: rotating 6s linear infinite;
    animation: rotating 6s linear infinite;
}

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
    width:20px;
    height:20px;
    border-radius:20px;
    background:<?php echo iro_opt('preload_animation_color1'); ?>;
    content:'';
    position:absolute;
    background:<?php echo iro_opt('preload_animation_color2'); ?>;
    -webkit-animation: preloader_3_before 1.5s infinite ease-in-out;
    -moz-animation: preloader_3_before 1.5s infinite ease-in-out;
    -ms-animation: preloader_3_before 1.5s infinite ease-in-out;
    animation: preloader_3_before 1.5s infinite ease-in-out;
}
#preloader_3:after {
    width:20px;
    height:20px;
    border-radius:20px;
    background:<?php echo iro_opt('preload_animation_color1'); ?>;
    content:'';
    position:absolute;
    background:<?php echo iro_opt('preload_animation_color1'); ?>;
    left:22px;
    -webkit-animation: preloader_3_after 1.5s infinite ease-in-out;
    -moz-animation: preloader_3_after 1.5s infinite ease-in-out;
    -ms-animation: preloader_3_after 1.5s infinite ease-in-out;
    animation: preloader_3_after 1.5s infinite ease-in-out;
}
@-webkit-keyframes preloader_3_before {
    0% {
        -webkit-transform: translateX(0px) rotate(0deg)
    }
    50% {
        -webkit-transform: translateX(50px) scale(1.2) rotate(260deg);
        background:<?php echo iro_opt('preload_animation_color1'); ?>;
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
        background:<?php echo iro_opt('preload_animation_color2'); ?>;
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
        background:<?php echo iro_opt('preload_animation_color1'); ?>;
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
        background:<?php echo iro_opt('preload_animation_color2'); ?>;
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
        background:<?php echo iro_opt('preload_animation_color1'); ?>;
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
        background:<?php echo iro_opt('preload_animation_color2'); ?>;
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
        background:<?php echo iro_opt('preload_animation_color1'); ?>;
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
        background:<?php echo iro_opt('preload_animation_color2'); ?>;
        border-radius:0px;
    }
    100% {
        transform: translateX(0px)
    }
}
<?php endif; ?>

/*深色模式*/

/*固定项目*/
body.dark #main-container,
body.dark .comments,
body.dark .site-footer,
body.dark .wrapper,
body.dark .post-list-show,
body.dark .post-list hr,
body.dark .lower li ul,
body.dark .header-user-menu,
body.dark .headertop-bar::after 
{background:rgba(51,51,51,<?php echo iro_opt('theme_darkmode_background_transparency')?>) !important;}

body.dark .toc,
body.dark .search-form input
{background:rgba(51,51,51,0.8);}

body.dark #mo-nav{
    background:#333333;
}

body.dark .single-reward .reward-row,
body.dark input.m-search-input
{background:#999999;}

body.dark .ins-section .ins-search-item.active,
body.dark .ins-section .ins-search-item.active .ins-slug,
body.dark .ins-section .ins-search-item.active .ins-search-preview,
body.dark .herder-user-name-u,
body.dark .herder-user-name,
body.dark .herder-user-name no-logged,
body.dark .header-user-menu .user-menu-option
{color:#fff;background:#333333;}

body.dark .single-reward .reward-row:before
{border-bottom:13px solid #999999;}

body.dark .search-form--modal,
body.dark .ins-section .ins-section-header
{border-bottom:none;background:rgba(51,51,51,0.9) !important;}

body.dark .search_close:after,
body.dark .search_close:before,
body.dark .openNav .icon,
body.dark .openNav .icon:before
{background-color:#CCCCCC;}

body.dark .centerbg
{background-blend-mode:hard-light;background-color:#333333;}

body.dark input[type=color]:focus,
body.dark input[type=date]:focus,
body.dark input[type=datetime-local]:focus,
body.dark input[type=datetime]:focus,
body.dark input[type=email]:focus,
body.dark input[type=month]:focus,
body.dark input[type=number]:focus,
body.dark input[type=password]:focus,
body.dark input[type=range]:focus,
body.dark input[type=search]:focus,
body.dark input[type=tel]:focus,
body.dark input[type=text]:focus,
body.dark input[type=time]:focus,
body.dark input[type=url]:focus,
body.dark input[type=week]:focus,
body.dark textarea:focus,
body.dark .post-date,
body.dark #mo-nav .m-search form
{color:#CCCCCC;background-color:#333333;}

body.dark .header-user-menu::before,
body.dark .lower li ul::before
{border-color:transparent transparent #333333 transparent;}

body.dark .header-user-menu .user-menu-option,
body.dark .post-list-thumb a 
{color:#333333;}

body.dark .entry-content p,
body.dark .entry-content ul,
body.dark .entry-content ol,
body.dark .comments .body p,
body.dark .float-content,
body.dark .post-list p,
body.dark .link-title
{color:#999999 !important;}

body.dark .entry-title a,
body.dark .post-list-thumb .post-title,
body.dark .art-content #archives .al_mon_list .al_mon,
body.dark .art-content #archives .al_mon_list span,
body.dark .art .art-content #archives a,
body.dark .menhera-container .emoji-item,
body.dark .ex-login-username,
body.dark .admin-login-check p,
body.dark .user-login-check p,
body.dark .ex-logout a,
body.dark .ex-new-account a
{color:#999999;}

body.dark .site-top ul li a,
body.dark .header-user-menu a,
body.dark #mo-nav ul li a,
body.dark .site-title a,
body.dark header.page-header,
body.dark h1.cat-title,
body.dark .art .art-content #archives .al_year,
body.dark .comment-respond input,
body.dark .comment-respond textarea,
body.dark .siren-checkbox-label,
body.dark .aplayer .aplayer-list ol li .aplayer-list-author,
body.dark,
body.dark button,
body.dark input,
body.dark select,
body.dark textarea,
body.dark i.iconfont.js-toggle-search.iconsearch,
body.dark .info-meta a,
body.dark .info-meta span,
body.dark .skin-menu
{color:#CCCCCC;}

body.dark .post-date,
body.dark .post-list-thumb a,
body.dark .post-list-thumb i,
body.dark .post-meta
{color:#666666;}

body.dark .post-list-thumb
{box-shadow:0 1px 35px -8px rgba(26,26,26,0.6);}

body.dark .notice,
body.dark .notification 
{color:#CCCCCC;background:#666666;border:none;}

body.dark .author-profile p
{border-top: 1px solid #3B3B3B;border-bottom: 1px solid #3B3B3B;color: #CCCCCC;}

body.dark .author-profile .meta h3 a,
body.dark .author-profile i 
{color: #CCCCCC;}

body.dark h1.page-title
{border: 1px dashed #3B3B3B;color: #CCCCCC;}

body.dark .entry-header hr
{background:#3B3B3B;}

body.dark h1.fes-title,
body.dark h1.main-title
{border-bottom:6px dotted #666666;color:#CCCCCC;}

body.dark .widget-area .heading,
body.dark .widget-area .show-hide svg,
body.dark #aplayer-float,
body.dark .aplayer.aplayer-fixed .aplayer-body,
body.dark .aplayer .aplayer-miniswitcher,
body.dark .aplayer .aplayer-pic
{color:#CCCCCC;background-color:#262626 !important;}

body.dark #aplayer-float .aplayer-lrc-current
{color:transparent !important;}

body.dark .aplayer.aplayer-fixed .aplayer-lrc
{text-shadow:-1px -1px 0 #999999;}

body.dark .aplayer.aplayer-fixed .aplayer-list
{border:none !important;}

body.dark .aplayer.aplayer-fixed .aplayer-info,
body.dark .aplayer .aplayer-list ol li
{border-top:none !important;}

body.dark .yya
{box-shadow: 0 1px 40px -8px #333333;}

body.dark .font-family-controls button,
body.dark .menu-list li
{background-color:#666666;}

body.dark .s-content,body.dark .font-family-controls button.selected
{background-color:#808080;}

body.dark #banner_wave_1,
body.dark #banner_wave_2
{display:none;}

body.dark .widget-area .show-hide svg path
{fill:#CCCCCC;}

body.dark button,
body.dark input[type=button],
body.dark input[type=reset],
body.dark input[type=submit]
{box-shadow:none;}

body.dark .comment .info,
body.dark .comment-respond .logged-in-as,
body.dark .comment-respond .logged-in-as a,
body.dark span.sitename 
{color:#CCCCCC;}

body.dark .aplayer .aplayer-list ol li.aplayer-list-light ,
body.dark .site-header:hover 
{background:#333333;}

body.dark #preload
{background: #333333;}

body.dark #moblieGoTop,
body.dark #changskin
{background-color:#262626;}

body.dark #show-nav .line 
{background: #CCCCCC;}

body.dark .linkdes
{
    border-top: 1px dotted #CCCCCC !important;
    color:#CCCCCC;
}

body.dark .links ul li
{
    background-color: #666666;
    border: none;
    opacity:.8;
}

body.dark .post-footer 
{background-image: url(https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/basic/creativecommons-dark.png);}

/*可变项目*/

/*深色模式控件透明度*/
body.dark .header-info,
body.dark .top-social img
{color:#fff;background:rgba(51,51,51,<?php echo iro_opt('theme_darkmode_widget_transparency'); ?>);}

body.dark .the-feature.from_left_and_right .info
{background-color: rgba(51,51,51,<?php echo iro_opt('theme_darkmode_widget_transparency'); ?>);}

body.dark .yya,
body.dark .widget-area,
body.dark .skin-menu,
body.dark input[type=submit] 
{background-color:rgba(38,38,38,<?php echo iro_opt('theme_darkmode_widget_transparency'); ?>) !important;}

/*深色模式自定义颜色*/
body.dark .headertop-down i 
{color: <?php echo iro_opt('drop_down_arrow_dark_color'); ?> !important;}

/*深色模式图像亮度*/
body.dark img,
body.dark .highlight-wrap,
body.dark iframe,
body.dark .entry-content .aplayer,
body.dark .post-thumb video
{filter:brightness(<?php echo iro_opt('theme_darkmode_img_bright'); ?>);}

/*深色模式主题色*/
:root{
    --theme-skin-dark: <?php echo iro_opt('theme_skin_dark'); ?>;
}
body.dark .scrollbar,
body.dark .butterBar-message,
body.dark .aplayer .aplayer-list ol li:hover ,
body.dark .pattern-center:after 
{background: <?php echo iro_opt('theme_skin_dark'); ?> !important;}

body.dark .aplayer .aplayer-list ol li.aplayer-list-light .aplayer-list-cur,
body.dark .user-menu-option a:hover ,
body.dark .menu-list li:hover ,
body.dark .font-family-controls button:hover ,
body.dark .openNav .icon, 
body.dark .openNav .icon:before ,
body.dark .openNav .icon:after ,
body.dark .openNav .icon:after ,
body.dark .site-top ul li a:after 
{background-color: <?php echo iro_opt('theme_skin_dark'); ?>;}

body.dark #moblieGoTop,
body.dark #changskin,
body.dark .post-content a:hover ,
body.dark .entry-title a:hover ,
body.dark .art .art-content #archives a:hover ,
body.dark .the-feature.from_left_and_right a:hover .info p,
body.dark .the-feature.from_left_and_right .info,
body.dark .ins-section .ins-search-item:hover,
body.dark .ins-section .ins-search-item:hover .ins-slug,
body.dark .ins-section .ins-search-item:hover .ins-search-preview,
body.dark .ins-section .ins-search-item:hover .iconfont ,
body.dark .float-content i:hover,
body.dark .menhera-container .emoji-item:hover ,
body.dark .comment-respond .logged-in-as a:hover ,
body.dark .site-top ul li a:hover ,
body.dark i.iconfont.js-toggle-search.iconsearch:hover
{color:<?php echo iro_opt('theme_skin_dark'); ?>;}

body.dark .aplayer .aplayer-info .aplayer-controller .aplayer-time .aplayer-icon:hover path 
{fill:<?php echo iro_opt('theme_skin_dark'); ?>;}

body.dark #moblieGoTop:hover , 
body.dark #changskin:hover 
{color:<?php echo iro_opt('theme_skin_dark'); ?>;opacity:.8;}

body.dark .focusinfo .header-tou img 
{box-shadow: inset 0 0 10px <?php echo iro_opt('theme_skin_dark'); ?>;}


@media (max-width: 1200px){
body.dark .site-top .lower nav.navbar ul 
{background: rgba(255,255,255,0);}
}

/*切换动画*/
html,
#main-container,
.pattern-center:after,
#mo-nav,
.headertop-bar::after,
.comments,
.site-footer,
.pattern-center-blank,
.yya,
.blank,
.toc
{transition:background 0.8s;}

.notice,
.notification,
.author-profile p,
.author-profile .meta h3 a,
.author-profile i,
.entry-header hr,
.header-info p,
.header-info,
.focusinfo .header-tou img,
.top-social img, 
.center-text,
.entry-content p,
.entry-content ul,
.entry-content ol,
.comments .body p,
.float-content,
.post-list p,
.link-title,
.search-form input,
.wrapper,
.site-footer,
.site-wrapper,
#moblieGoTop,
#changskin,
#moblieGoTop:hover,
#changskin:hover,
.post-list-show,
.post-list hr,
.post-date,
.float-content i:hover,
h1.fes-title,
h1.main-title,
h1.page-title
{transition:all 0.8s ease !important;}

/*字重*/

h1,small,sub,sup,code,kbd,pre,samp,body,button,input,select,textarea,blockquote:before,blockquote:after,code,kbd,tt,var,big,button,input[type=button],input[type=reset],input[type=submit],.video-stu,.pattern-center h1.cat-title,.pattern-center h1.entry-title,.pattern-center .cat-des,.single-center .single-header h1.entry-title,.single-center .entry-census,.pattern-center-sakura h1.cat-title,.pattern-center-sakura h1.entry-title,.pattern-center-sakura .cat-des,.single-center .single-header h1.entry-title,.single-center .entry-census,.site-top .lower,.site-top .menu-item-has-children li a,.feature i,.p-time,.p-time i,i.iconfont.hotpost,.post-list p,.post-more i,.info-meta span,.post-list-thumb i,.post-date,.post-meta,.post-meta a,.float-content .post-text,.float-content i,.s-time,.s-time i,.navigator i,.site-info,.entry-census,h1.page-title,.post-tags,.post-like a,.post-like i,.post-share ul li i ,.post-squares .category,.post-squares h3,.post-squares .label,.author-profile .meta h3 a,.author-profile p,.author-profile i,.mashiro-profile .box a,#comments-list-title span,.butterBar-message ,h1.works-title,.works-p-time,.works-p-time i,.works-meta i,.works-meta span,#archives-temp h3,h1.cat-title,span.sitename,span.linkss-title,.comment .commeta,.comment .comment-reply-link,.comment .info,.comment .info .useragent-info-m,.comment h4,.comment h4 a,.comment-respond #cancel-comment-reply-link,.comment-respond input,.comment-respond textarea,.comment-respond .logged-in-as i,.notification i,.notification span,.siren-checkbox-label,h1.fes-title,h1.main-title,.foverlay-bg,.feature-title .foverlay,.notice i,.bio-text,.header-info,.site-header.iconsearch,i.iconfont.js-toggle-search.iconsearch,.search-form i ,.search-form input,.s-search input,.s-search i,.ins-section,.ins-section .iconfont.icon-mark,.ins-section .fa ,.ins-section.ins-search-item .search-keyword,.ins-section .ins-search-item .ins-search-preview,i.iconfont.down,#pagination span,#bangumi-pagination span,.ex-login-title ,.ex-register-title h3,.ex-login input,.ex-register input,.admin-login-check,.user-login-check,.ex-login-username,.ex-new-account a,.ex-register .user-error,.register-close,.herder-user-name,.header-user-menu a,.no-logged,.no-logged a,.single-reward .reward-open,.reward-row li::after ,.botui-container,button.botui-actions-buttons-button,input.botui-actions-text-input,.load-button span,.prpr,.mashiro-tips,.live2d-tool,.center-text,.bb-comment,.finish-icon-container,.skill div,#footer-sponsor,.comment-user-avatar .socila-check ,.insert-image-tips ,.the-feature.from_left_and_right .info h3,.the-feature.from_left_and_right .info p,.highlight-wrap .copy-code ,#bangumi-pagination {font-weight: <?php echo iro_opt('global_font_weight'); ?> !important}

/*字体*/

<?php if (iro_opt('reference_exter_font', 'true')): ?>
@font-face {
font-family: '<?php echo iro_opt('exter_font_name'); ?>';
src : url('<?php echo iro_opt('exter_font_link'); ?>');
}
<?php endif; ?>

.serif{
font-family:<?php echo iro_opt('global_default_font'); ?> !important ;
font-size: <?php echo iro_opt('global_font_size'); ?>px !important;
}

body{
font-family:<?php echo iro_opt('global_font_2'); ?> !important;
font-size: <?php echo iro_opt('global_font_size'); ?>px !important;
}

h1.main-title,h1.fes-title{
font-family:<?php echo iro_opt('area_title_font'); ?> !important;
}

.header-info p{
font-family:<?php echo iro_opt('signature_font'); ?> !important;
font-size: <?php echo iro_opt('signature_font_size'); ?>px !important;
}

.cbp_tmtimeline > li .cbp_tmlabel {
font-family:<?php echo iro_opt('shuoshuo_font'); ?> !important;
}

.post-list-thumb .post-title h3{
font-size: <?php echo iro_opt('post_title_font_size'); ?>px !important;
}

.post-meta, .post-meta a{
font-size: <?php echo iro_opt('post_date_font_size'); ?>px !important;
}

.pattern-center h1.cat-title,
.pattern-center h1.entry-title {
font-size: <?php echo iro_opt('page_temp_title_font_size'); ?>px !important;
}
.pattern-center-sakura h1.cat-title,
.pattern-center-sakura h1.entry-title {
font-size: <?php echo iro_opt('page_temp_title_font_size'); ?>px !important;
}

.single-center .single-header h1.entry-title {
font-size: <?php echo iro_opt('article_title_font_size'); ?>px !important;
}

/*鼠标*/
body{
cursor: url(<?php echo iro_opt('cursor_nor'); ?>), auto;
}

.botui-actions-buttons-button,
.headertop-down i,
.faa-parent.animated-hover:hover>.faa-spin,
.faa-spin.animated,
.faa-spin.animated-hover:hover,
i.iconfont.js-toggle-search.iconsearch,
#waifu #live2d,
.aplayer svg,
.aplayer.aplayer-narrow .aplayer-body,
.aplayer.aplayer-narrow .aplayer-pic,
button.botui-actions-buttons-button,
#emotion-toggle,
.emoji-item,
.emotion-box,
.emotion-item,
.on-hover,
.tieba-container span,
#moblieGoTop,
#changskin{
cursor: url(<?php echo iro_opt('cursor_no'); ?>), auto;
}

a,
.ins-section .ins-section-header,
.font-family-controls button,
.menu-list li,.ins-section .ins-search-item,
.ins-section .ins-search-item .ins-search-preview{
cursor: url(<?php echo iro_opt('cursor_ayu'); ?>), auto;
}

p,
.highlight-wrap code,
.highlight-wrap,
.hljs-ln-code .hljs-ln-line{
cursor: url(<?php echo iro_opt('cursor_text'); ?>), auto;
}

a:active{
cursor: url(<?php echo iro_opt('cursor_work'); ?>), alias;
}

/*背景类*/
.comment-respond textarea {
background-image: url(<?php echo iro_opt('comment_area_image'); ?>); 
}

.search-form.is-visible{
background-image: url(<?php echo iro_opt('search_area_background'); ?>);
}

.site-footer {
background-color: rgba(255, 255, 255,<?php echo iro_opt('reception_background_transparency'); ?>);
<?php if (iro_opt('reception_background_blur', 'false')): ?> backdrop-filter: blur(10px); <?php endif; ?>
<?php if (iro_opt('reception_background_blur', 'false')): ?> -webkit-backdrop-filter: blur(10px); <?php endif; ?>
}

.wrapper {
background-color: rgba(255, 255, 255,<?php echo iro_opt('reception_background_transparency'); ?>);
<?php if (iro_opt('reception_background_blur', 'false')): ?> backdrop-filter: blur(10px); <?php endif; ?>
<?php if (iro_opt('reception_background_blur', 'false')): ?> -webkit-backdrop-filter: blur(10px); <?php endif; ?>
}

/*首页圆角设置*/
.header-info{
border-radius: <?php echo iro_opt('signature_radius'); ?>px;
}

.focusinfo img{
border-radius: <?php echo iro_opt('social_area_radius'); ?>px;
}

.focusinfo .header-tou img{
border-radius: <?php echo iro_opt('avatar_radius'); ?>px;
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
	-moz-animation: fadeInUp <?php echo iro_opt('page_title_animation_time'); ?>s;
    -webkit-animation:fadeInUp <?php echo iro_opt('page_title_animation_time'); ?>s;
	animation: fadeInUp <?php echo iro_opt('page_title_animation_time'); ?>s;
}
<?php endif; ?>

/*首页封面动画*/
<?php if (iro_opt('cover_animation', 'true')): ?>
h1.main-title, h1.fes-title,.the-feature.from_left_and_right .info,.header-info p,.header-info,.focusinfo .header-tou img,.top-social img,.center-text{
	-moz-animation: fadeInDown  <?php echo iro_opt('cover_animation_time'); ?>s;
    -webkit-animation:fadeInDown  <?php echo iro_opt('cover_animation_time'); ?>s;
	animation: fadeInDown  <?php echo iro_opt('cover_animation_time'); ?>s;
}
<?php endif; ?>

/*导航菜单动画*/
<?php if (iro_opt('nav_menu_animation', 'true')): ?>
.site-top ul {
	-moz-animation: fadeInLeft  <?php echo iro_opt('nav_menu_animation_time'); ?>s;
    -webkit-animation:fadeInLeft  <?php echo iro_opt('nav_menu_animation_time'); ?>s;
	animation: fadeInLeft  <?php echo iro_opt('nav_menu_animation_time'); ?>s;
}
<?php endif; ?>

/*浮起动画*/
/*向上*/
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
/*向下*/
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
/*向左*/
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

/*其他*/
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

.widget-area .sakura_widget{
    background-image: url(<?php echo iro_opt('sakura_widget_background', ''); ?>);
}

.headertop{
    border-radius: 0 0 <?php echo iro_opt('cover_radius', ''); ?>px <?php echo iro_opt('cover_radius', ''); ?>px;
}

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

<?php }if(iro_opt('nav_menu_search_size') == 'standard'){ ?>

<?php } ?>

<?php if(iro_opt('friend_link_align') == 'right'){ ?>

span.sitename {
   margin: 0px;
}
.linkdes {
    margin: 0px;
}
li.link-item {
    text-align: right;
}
.links ul li img{
	float:none;
}

<?php }if(iro_opt('friend_link_align') == 'center'){ ?>

span.sitename {
   margin: 0px;
}
.linkdes {
    margin: 0px;
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

<?php }if(iro_opt('post_list_image_align') == 'alternate'){ ?>
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

<?php }if(iro_opt('page_style') == 'sakura'){ ?>
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
    width:<?php echo iro_opt('nav_menu_shrink_animation', ''); ?>% ;
	left:calc(97.5% - <?php echo iro_opt('nav_menu_shrink_animation', ''); ?>%);
    background: rgba(255, 255, 255, .75);
    box-shadow: 0 1px 40px -8px rgba(255, 255, 255, .4);
    border-radius: <?php echo iro_opt('nav_menu_radius', ''); ?>px !important;
}
.site-title img {
    margin-left: 10px;
}
.site-header {
    border-radius: <?php echo iro_opt('nav_menu_radius', ''); ?>px !important;
}
.header-user-menu {
    border-radius: <?php echo iro_opt('nav_menu_secondary_radius', ''); ?>px !important;
}
.lower li ul {
    border-radius: <?php echo iro_opt('nav_menu_secondary_radius', ''); ?>px !important;
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
    position: fixed;
    background: rgba(255, 255, 255, .75);
    border-radius: 0 !important;
    box-shadow: 0 1px 40px -8px rgba(255, 255, 255, .4)
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

.yya {
    position: fixed;
    left: 0;
    background: rgba(255, 255, 255, .75);
    box-shadow: 0 1px 40px -8px rgba(255, 255, 255, .4)
}

@media(max-width:1200px) {
.yya {
    position: fixed;
    left: 0;
    background: rgba(255, 255, 255, .75);
    box-shadow: 0 1px 40px -8px rgba(255, 255, 255, .4)
}
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
    border-radius: <?php echo iro_opt('exhibition_radius', ''); ?>px;
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

<?php }if(iro_opt('area_title_text_align') == 'right'){ ?>
h1.fes-title,
h1.main-title {
    text-align: right;
}

<?php } ?>

<?php if(iro_opt('bulletin_board_style') == 'picture'){ ?>
.notice {
    background-image:url(<?php echo iro_opt('bulletin_board_bg', ''); ?>);
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
    backdrop-filter: blur(<?php echo $nav_menu_blur?>px);
    -webkit-backdrop-filter: blur(<?php echo $nav_menu_blur?>px);
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
    background-size:<?php
echo iro_opt(('reception_background_size'),'auto')
?>;
}
</style>
<?php }
add_action('wp_head', 'customizer_css');
