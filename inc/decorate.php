<?php
function customizer_css() { ?>
<style type="text/css">
<?php // Style Settings
if ( akina_option('shownav') ) { ?>
.site-top .lower nav {display: block !important;}
<?php } // Style Settings ?>
<?php // theme-skin
if ( akina_option('theme_skin') ) { ?>
.author-profile i , .post-like a , .post-share .show-share , .sub-text , .we-info a , span.sitename , .post-more i:hover , #pagination a:hover , .post-content a:hover , .float-content i:hover{ color: <?php echo akina_option('theme_skin'); ?> }
.feature i , /*.feature-title span ,*/ .download , .navigator i:hover , .links ul li:before , .ar-time i , span.ar-circle , .object , .comment .comment-reply-link , .siren-checkbox-radio:checked + .siren-checkbox-radioInput:after { background: <?php echo akina_option('theme_skin'); ?> }
::-webkit-scrollbar-thumb { background: <?php echo akina_option('theme_skin'); ?> }
.download , .navigator i:hover , .link-title , .links ul li:hover , #pagination a:hover , .comment-respond input[type='submit']:hover { border-color: <?php echo akina_option('theme_skin'); ?> }
.entry-content a:hover , .site-info a:hover , .comment h4 a , #comments-navi a.prev , #comments-navi a.next , .comment h4 a:hover , .site-top ul li a:hover , .entry-title a:hover , #archives-temp h3 , span.page-numbers.current , .sorry li a:hover , .site-title a:hover , i.iconfont.js-toggle-search.iconsearch:hover , .comment-respond input[type='submit']:hover, blockquote:before, blockquote:after { color: <?php echo akina_option('theme_skin'); ?> }

#aplayer-float .aplayer-lrc-current { color: <?php echo akina_option('theme_skin'); ?> !important}

.linkdes { border-top: 1px dotted <?php echo akina_option('theme_skin'); ?> !important}

.the-feature.from_left_and_right .info{background: <?php echo akina_option('theme_skin_jjbj'); ?> }

.is-active-link::before, .commentbody:not(:placeholder-shown)~.input-label, .commentbody:focus~.input-label {
    background-color: <?php echo akina_option('theme_skin'); ?> !important
}
.links ul li {
    border: 1px solid <?php echo akina_option('theme_skin'); ?>;
}
.links ul li img {
    box-shadow: inset 0 0 10px <?php echo akina_option('theme_skin'); ?>;
}
.commentbody:focus {
    border-color: <?php echo akina_option('theme_skin'); ?> !important
}

.insert-image-tips:hover, .insert-image-tips-hover{ 
    color: <?php echo akina_option('theme_skin'); ?>;
    border: 1px solid <?php echo akina_option('theme_skin'); ?>
}
.search_close:after,
.search_close:before {
    background-color: <?php echo akina_option('theme_skin'); ?>
}
.site-top ul li a:after {
    background-color: <?php echo akina_option('theme_skin'); ?>
}
.search-form input::-webkit-input-placeholder {
    color: <?php echo akina_option('theme_skin'); ?>
}
.search-form input::-moz-placeholder {
    color: <?php echo akina_option('theme_skin'); ?>
}
.search-form input::-webkit-input-placeholder {
    color: <?php echo akina_option('theme_skin'); ?>
}
.search-form input:-ms-input-placeholder {
    color: <?php echo akina_option('theme_skin'); ?>
}
.s-search i {
    color: <?php echo akina_option('theme_skin'); ?>
}
i.iconfont.js-toggle-search.iconsearch {
    color: <?php echo akina_option('theme_skin'); ?>;
}
.ins-section .fa {
    color: <?php echo akina_option('theme_skin'); ?>
}
.ins-section .ins-search-item:hover .ins-slug,.ins-section .ins-search-item.active .ins-slug,.ins-section .ins-search-item:hover .ins-search-preview,.ins-section .ins-search-item.active .ins-search-preview,.ins-section .ins-search-item:hover header,.ins-section .ins-search-item:hover .iconfont {
	color: <?php echo akina_option('theme_skin'); ?>
}
.ins-section .ins-section-header {
    color: <?php echo akina_option('theme_skin'); ?>;
    border-bottom: 3px solid <?php echo akina_option('theme_skin'); ?>;
    border-color: <?php echo akina_option('theme_skin'); ?>;
}
.the-feature.from_left_and_right .info p {
    color: <?php echo akina_option('theme_skin'); ?>;
}
.sorry li a {
    color: <?php echo akina_option('theme_skin'); ?>;
}
.sorry li a:hover {
    color: <?php echo akina_option('theme_skin'); ?>;
}
.sorry-inner {
    border: 1px solid <?php echo akina_option('theme_skin'); ?>;
}
.err-button.back a {
    border: 1px solid <?php echo akina_option('theme_skin'); ?>;
    color: <?php echo akina_option('theme_skin'); ?>;
}

.sorry {
    color: <?php echo akina_option('theme_skin'); ?>;
}
.site-top ul li a:hover {
    color: <?php echo akina_option('theme_skin'); ?>
}
.site-top ul li a {
    color: <?php echo akina_option('theme_skin'); ?>
}
.header-info {
    color: <?php echo akina_option('theme_skin'); ?>;
    background: <?php echo akina_option('theme_skin_yybj'); ?>;
}
.top-social img {
    background: <?php echo akina_option('theme_skin_yybj'); ?>;
}
body,
button,
input,
select,
textarea {
    color: <?php echo akina_option('theme_skin'); ?>
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
    border: 1px solid <?php echo akina_option('theme_skin'); ?>;
}
.post-date {
    background-color: <?php echo akina_option('theme_skin_sjbj'); ?>;
    color: <?php echo akina_option('theme_skin_sjwz'); ?>
}
.post-tags a:hover {
    color: <?php echo akina_option('theme_skin'); ?>
}
.post-share ul li a:hover {
    color: <?php echo akina_option('theme_skin'); ?>
}
.linkdes {
    color: <?php echo akina_option('theme_skin'); ?>
}
.link-title span.link-fix {
    border-left:3px solid <?php echo akina_option('theme_skin'); ?>;
}
.scrollbar,.butterBar-message {
    background: <?php echo akina_option('theme_skin'); ?> !important
}
h1.cat-title {
    color: <?php echo akina_option('theme_skin'); ?>
}
h1.fes-title,
h1.main-title {
    color: <?php echo akina_option('theme_skin'); ?>;
    border-bottom: 6px dotted <?php echo akina_option('theme_skin_fgf'); ?>;
}
.comment .body .comment-at {
    color: <?php echo akina_option('theme_skin'); ?>
}
.focusinfo .header-tou img {
    box-shadow: inset 0 0 10px <?php echo akina_option('theme_skin'); ?>;
}
.double-bounce1, .double-bounce2 {
    background-color: <?php echo akina_option('theme_skin'); ?>;
  }
  
#pagination .loading {
    background: url(<?php echo akina_option('webweb_img'); ?>/load/ball.svg);
    background-position: center;
    background-repeat: no-repeat;
    color: #555;
    border: none;
    background-size: auto 100%
}
#pagination .loading,#bangumi-pagination .loading {
    background: url(<?php echo akina_option('webweb_img'); ?>/load/ball.svg);
    background-position: center;
    background-repeat: no-repeat;
    color: #555;
    border: none;
    background-size: auto 100%
}
#nprogress .spinner-icon {
    border-top-color: <?php echo akina_option('theme_skin'); ?>;
    border-left-color: <?php echo akina_option('theme_skin'); ?>;
}
#pagination a:hover {
    border: 1px solid <?php echo akina_option('theme_skin'); ?>;
    color: <?php echo akina_option('theme_skin'); ?>
}
#nprogress .peg {
    box-shadow: 0 0 10px <?php echo akina_option('theme_skin'); ?>, 0 0 5px <?php echo akina_option('theme_skin'); ?>;
}
#nprogress .bar {
    background: <?php echo akina_option('theme_skin'); ?>;
}
#gohome {
    background: <?php echo akina_option('theme_skin'); ?>;
}
#archives-temp h2 {
    color: <?php echo akina_option('theme_skin'); ?>
}
#archives-temp h3 {
    color: <?php echo akina_option('theme_skin'); ?>
}
#moblieGoTop {
    color: <?php echo akina_option('theme_skin'); ?>
}
#changskin {
    color: <?php echo akina_option('theme_skin'); ?>
}
#show-nav .line {
    background: <?php echo akina_option('theme_skin'); ?>
}
#nprogress .spinner-icon{ 
    border-top-color: <?php echo akina_option('theme_skin'); ?>; 
    border-left-color: <?php echo akina_option('theme_skin'); ?>
}
#nprogress .bar {
    background: <?php echo akina_option('theme_skin'); ?>
}
#aplayer-float .aplayer-lrc-current {
    color: <?php echo akina_option('theme_skin'); ?>;
}
@media (max-width:860px) {
    .links ul li:hover .sitename {
        color: <?php echo akina_option('theme_skin'); ?>
    }
    .openNav .icon {
     background-color: <?php echo akina_option('theme_skin'); ?>;
    }
    .openNav .icon:after,
    .openNav .icon:before {
     background-color: <?php echo akina_option('theme_skin'); ?>;
    }
    #mo-nav ul li a {
        color: <?php echo akina_option('theme_skin'); ?>;
    }
    #mo-nav ul li a:hover {
        color: <?php echo akina_option('theme_skin'); ?>;
    }
    ::-webkit-scrollbar-thumb {
    background-color: <?php echo akina_option('theme_skin'); ?>;
    }
}
.ex-login input.login-button:hover,
.ex-register input.register-button:hover {
    background-color: <?php echo akina_option('theme_skin'); ?>;
    border-color: <?php echo akina_option('theme_skin'); ?>;
}
.herder-user-name {
	color: <?php echo akina_option('theme_skin'); ?>;
}
.header-user-menu a {
    color: <?php echo akina_option('theme_skin'); ?>;
}
.no-logged a {
    color: <?php echo akina_option('theme_skin'); ?>;
}
/*logocss*/
<?php if (akina_option('logocss', '1')): ?>
.logolink .sakuraso {
    background-color: rgba(255, 255, 255, .5);
    border-radius: 5px;
    color: <?php echo akina_option('theme_skin'); ?>;
    height: auto;
    line-height: 25px;
    margin-right: 0;
    padding-bottom: 0px;
    padding-top: 1px;
    text-size-adjust: 100%;
    width: auto
}
 
.logolink a:hover .sakuraso {
    background-color: <?php echo akina_option('theme_skin'); ?>;
    color: #fff;
}
 
.logolink a:hover .shironeko,
.logolink a:hover .no,
.logolink a:hover rt {
    color: <?php echo akina_option('theme_skin'); ?>;
}
 
.logolink.moe-mashiro a {
    color: <?php echo akina_option('theme_skin'); ?>;
    float: left;
    font-size: 25px;
    font-weight: 800;
    height: 56px;
    line-height: 56px;
    padding-left: 6px;
    padding-right: 15px;
    padding-top: 11px;
    text-decoration-line: none;
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
    color: <?php echo akina_option('theme_skin'); ?>;
}

.art .art-content .al_mon_list .al_post_list>li:after {
    background: <?php echo akina_option('theme_skin'); ?>;
}
@media (min-width:861px) {
    .hide-live2d {
        background-color: <?php echo akina_option('theme_skin'); ?>;
    }
}
.art-content #archives .al_mon_list .al_mon:after {
    background: <?php echo akina_option('theme_skin'); ?>;
}
.art-content #archives .al_mon_list:before {
    background: <?php echo akina_option('theme_skin'); ?>;
}
.changeSkin-gear {
    color: <?php echo akina_option('theme_skin'); ?>;
}
.art .art-content .al_mon_list .al_post_list>li:after,
.art-content #archives .al_mon_list .al_mon:after {
    background: <?php echo akina_option('theme_skin'); ?>;
}
.is-active-link::before {
    background-color: <?php echo akina_option('theme_skin'); ?>; /*!important*/ /*mark*/
}
.motion-switcher-table th:hover {
    color: <?php echo akina_option('theme_skin'); ?>;
}
.motion-switcher-table .on-hover {
    color: <?php echo akina_option('theme_skin'); ?>;
}
.menhera-container .emoji-item {
    color: <?php echo akina_option('theme_skin'); ?>;
}
.float-content i {
    color: <?php echo akina_option('theme_skin'); ?>;
}
.scrollbar {
    background: <?php echo akina_option('theme_skin'); ?>;
}
.insert-image-tips:hover {
    color: <?php echo akina_option('theme_skin'); ?>;
    border: 1px solid <?php echo akina_option('theme_skin'); ?>;
}
.insert-image-tips-hover {
    color: <?php echo akina_option('theme_skin'); ?>;
    border: 1px solid <?php echo akina_option('theme_skin'); ?>;
}
.the-feature a {
    color: <?php echo akina_option('theme_skin'); ?>;
}
.menu-list li:hover {
    background-color: <?php echo akina_option('theme_skin'); ?>;
}
.menu-list li {
    background-color: <?php echo akina_option('theme_skin_cdbj'); ?>;
}
.post-list-thumb {
    box-shadow: 0 1px 30px -4px <?php echo akina_option('theme_skin_bkyy'); ?>;
}
.font-family-controls button {
    color: <?php echo akina_option('theme_skin'); ?>;
    background-color: <?php echo akina_option('theme_skin_cdbj'); ?>;
}
.art .art-content #archives a:hover {
    color: <?php echo akina_option('theme_skin'); ?>;
}
.art .art-content .al_mon_list .al_post_list>li,
.art-content #archives .al_mon_list .al_mon {
	color: <?php echo akina_option('theme_skin'); ?>;
}
.font-family-controls button.selected {
    background-color: <?php echo akina_option('theme_skin'); ?>;
}
.art-content #archives .al_mon_list .al_mon,
.art-content #archives .al_mon_list span {
    color: <?php echo akina_option('theme_skin'); ?>;
}
<?php if(akina_option('entry_content_theme') == "sakurairo"){ ?>
.entry-content th {
    background-color: <?php echo akina_option('theme_skin'); ?>
}
<?php } ?>
<?php if(akina_option('live_search')){ ?>
.search-form--modal .search-form__inner {
    bottom: unset !important;
    top: 10% !important;
}
<?php } ?>

.post-list-thumb{opacity: 0}
.post-list-show {opacity: 1}

<?php } // theme-skin ?>
<?php // Custom style
if ( akina_option('site_custom_style') ) {
  echo akina_option('site_custom_style');
} 
// Custom style end ?>
<?php // liststyle
if ( akina_option('list_type') == 'square') { ?>
.feature img{ border-radius: 0px; !important; }
.feature i { border-radius: 0px; !important; }
<?php } // liststyle ?>
<?php // comments
if ( akina_option('toggle-menu') == 'no') { ?>
.comments .comments-main {display:block !important;}
.comments .comments-hidden {display:none !important;}
<?php } // comments ?>
<?php 
$image_api = 'background-image: url("'.rest_url('sakura/v1/image/cover').'");';
$bg_style = akina_option('focus_height') ? 'background-position: center center;background-attachment: inherit;' : '';
?>
.centerbg{<?php echo $image_api.$bg_style ?>background-position: center center;background-attachment: inherit;}
.rotating {
    -webkit-animation: rotating 6s linear infinite;
    -moz-animation: rotating 6s linear infinite;
    -ms-animation: rotating 6s linear infinite;
    -o-animation: rotating 6s linear infinite;
    animation: rotating 6s linear infinite;
}
/*预加载部分*/
<?php if (akina_option('yjzdh', '1')): ?>
#preload {
    position: absolute;
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
    background:<?php echo akina_option('theme_skin_yjjone'); ?>;
    content:'';
    position:absolute;
    background:<?php echo akina_option('theme_skin_yjjtwo'); ?>;
    -webkit-animation: preloader_3_before 1.5s infinite ease-in-out;
    -moz-animation: preloader_3_before 1.5s infinite ease-in-out;
    -ms-animation: preloader_3_before 1.5s infinite ease-in-out;
    animation: preloader_3_before 1.5s infinite ease-in-out;
}
#preloader_3:after {
    width:20px;
    height:20px;
    border-radius:20px;
    background:<?php echo akina_option('theme_skin_yjjone'); ?>;
    content:'';
    position:absolute;
    background:<?php echo akina_option('theme_skin_yjjone'); ?>;
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
        background:<?php echo akina_option('theme_skin_yjjone'); ?>;
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
        background:<?php echo akina_option('theme_skin_yjjtwo'); ?>;
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
        background:<?php echo akina_option('theme_skin_yjjone'); ?>;
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
        background:<?php echo akina_option('theme_skin_yjjtwo'); ?>;
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
        background:<?php echo akina_option('theme_skin_yjjone'); ?>;
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
        background:<?php echo akina_option('theme_skin_yjjtwo'); ?>;
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
        background:<?php echo akina_option('theme_skin_yjjone'); ?>;
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
        background:<?php echo akina_option('theme_skin_yjjtwo'); ?>;
        border-radius:0px;
    }
    100% {
        transform: translateX(0px)
    }
}
<?php endif; ?>

<?php if(akina_option('comment_info_box_width', '')): ?>
.cmt-popup {
    --widthA: <?php echo akina_option('comment_info_box_width', ''); ?>%;
    --widthB: calc(var(--widthA) - 71px);
    --widthC: calc(var(--widthB) / 3);
    width: var(--widthC);
}
<?php endif;?>

body.dark #main-container,body.dark .pattern-center:after,body.dark #mo-nav,body.dark .headertop-bar::after,body.dark .site-content,body.dark .comments,body.dark .site-footer{background:#31363b !important;}
body.dark .pattern-center-blank,body.dark .yya,body.dark .blank,body.dark .toc,body.dark .search-form input{background:rgba(49,54,59,0.85);}
body.dark .single-reward .reward-row{background:#bebebe;}
body.dark .ins-section .ins-search-item:hover,body.dark .ins-section .ins-search-item.active,body.dark .ins-section .ins-search-item:hover .ins-slug,body.dark .ins-section .ins-search-item.active .ins-slug,body.dark .ins-section .ins-search-item:hover .ins-search-preview,body.dark .ins-section .ins-search-item.active .ins-search-preview,body.dark .ins-section .ins-search-item:hover header,body.dark .herder-user-name-u,body.dark .herder-user-name,body.dark .ins-section .ins-search-item:hover .iconfont,body.dark .header-user-menu .user-menu-option{color:#fff;background:#31363b;}
body.dark .single-reward .reward-row:before{border-bottom:13px solid #bebebe;}
body.dark .search-form--modal,body.dark .ins-section .ins-section-header{border-bottom:none;background:rgba(49,54,59,0.95);}
body.dark .search_close:after,body.dark .search_close:before{background-color:#eee;}
body.dark input.m-search-input{background:#bebebe;}
body.dark .openNav .icon,body.dark .openNav .icon:after,body.dark .openNav .icon:before{background-color:#eee;}
body.dark .site-header:hover{background:#31363b;}
body.dark .centerbg{background-blend-mode:hard-light;background-color:#31363b;}
body.dark input[type=color]:focus,body.dark input[type=date]:focus,body.dark input[type=datetime-local]:focus,body.dark input[type=datetime]:focus,body.dark input[type=email]:focus,body.dark input[type=month]:focus,body.dark input[type=number]:focus,body.dark input[type=password]:focus,body.dark input[type=range]:focus,body.dark input[type=search]:focus,body.dark input[type=tel]:focus,body.dark input[type=text]:focus,body.dark input[type=time]:focus,body.dark input[type=url]:focus,body.dark input[type=week]:focus,body.dark textarea:focus,body.dark .post-date,body.dark .header-info,body.dark .top-social img,body.dark #mo-nav .m-search form{color:#eee;background-color:#31363b;}
body.dark .lower li ul,body.dark .header-user-avatar:hover,.header-user-menu{background:#31363b;}
body.dark .header-user-menu::before,body.dark .lower li ul::before{border-color:transparent transparent #31363b transparent;}
body.dark .post-date,body.dark .header-user-menu .user-menu-option,body.dark .post-list-thumb a,body.dark .menhera-container .emoji-item:hover{color:#424952;}
body.dark .entry-content p,body.dark .entry-content ul,body.dark .entry-content ol,body.dark .comments .body p,body.dark .float-content,body.dark .post-list p,body.dark .link-title{color:#bebebe !important;}
body.dark .entry-title a,body.dark .post-list-thumb .post-title,body.dark .post-list-thumb,body.dark .art-content #archives .al_mon_list .al_mon,body.dark .art-content #archives .al_mon_list span,body.dark .art .art-content #archives a,body.dark .menhera-container .emoji-item{color:#bebebe;}
body.dark .site-top ul li a,body.dark .header-user-menu a,body.dark #mo-nav ul li a,body.dark .site-title a,body.dark header.page-header,body.dark h1.cat-title{color:#eee;}
body.dark .art .art-content #archives .al_year,body.dark .comment-respond input,body.dark .comment-respond textarea,body.dark .siren-checkbox-label{color:#eee;}
body.dark .post-date,body.dark .post-list-thumb a,body.dark .post-meta,body.dark .info-meta a,body.dark .info-meta span{color:#888;}
body.dark img,body.dark .highlight-wrap,body.dark iframe,body.dark .entry-content .aplayer{filter:brightness(0.6);}
body.dark .post-list-thumb{box-shadow:0 1px 35px -8px rgba(0,0,0,0.8);}
body.dark .notice{color:#EFF0F1;background:#232629;border:none;}
body.dark h1.fes-title,body.dark h1.main-title{border-bottom:6px dotted #ababab;}
body.dark #moblieGoTop,body.dark .notification,body.dark .the-feature.from_left_and_right .info,body.dark #changskin{color:#eee;background-color:#232629;}
body.dark #moblieGoTop:hover,body.dark #changskin:hover{background-color:#232629;opacity:.8;}
body.dark .widget-area{background-color:rgba(35,38,41,0.8);}
body.dark .skin-menu,body.dark .menu-list li,body.dark .widget-area .heading,body.dark .widget-area .show-hide svg,body.dark #aplayer-float,body.dark .aplayer.aplayer-fixed .aplayer-body,body.dark .aplayer .aplayer-miniswitcher,body.dark .aplayer .aplayer-pic{color:#eee;background-color:#232629 !important;}
body.dark .aplayer .aplayer-list ol li .aplayer-list-author{color:#eee;}
body.dark #aplayer-float .aplayer-lrc-current{color:transparent !important;}
body.dark .aplayer.aplayer-fixed .aplayer-lrc{text-shadow:-1px -1px 0 #989898;}
body.dark .aplayer.aplayer-fixed .aplayer-list{border:none !important;}
body.dark .aplayer.aplayer-fixed .aplayer-info,body.dark .aplayer .aplayer-list ol li{border-top:none !important;}
body.dark .yya{box-shadow: 0 1px 40px -8px #21252b;}
body.dark .font-family-controls button.selected{background-color:#31363b;}
body.dark .user-menu-option a:hover{background-color:#535a63;}
body.dark .font-family-controls button{background-color:#535a63;}
body.dark #banner_wave_1,body.dark #banner_wave_2{display:none;}
body.dark .skin-menu::after{display:none;}
body.dark .ex-login-username,body.dark .admin-login-check p,body.dark .user-login-check p,body.dark .ex-logout a, .ex-new-account a{color:#bebebe;}
body.dark .aplayer .aplayer-info .aplayer-controller .aplayer-time .aplayer-icon:hover path,body.dark .widget-area .show-hide svg path{fill:#fafafa;}
body.dark,body.dark button,body.dark input,body.dark select,body.dark textarea{color:#eee;}
body.dark button,body.dark input[type=button],body.dark input[type=reset],body.dark input[type=submit]{box-shadow:none;}
body.dark .comment .info,body.dark .comment-respond .logged-in-as,body.dark .notification,body.dark .comment-respond .logged-in-as a,body.dark .comment-respond .logged-in-as a:hover{color:#9499a8;}
html,#main-container,.pattern-center:after,#mo-nav,.headertop-bar::after,.site-content,.comments,.site-footer,.pattern-center-blank,.yya,.blank,.toc,.search-form input{transition:background 1s;}
.entry-content p,.entry-content ul,.entry-content ol,.comments .body p,.float-content,.post-list p,.link-title{transition:color 1s;}

/*字重*/

h1,small,sub,sup,code,kbd,pre,samp,body,button,input,select,textarea,blockquote:before,blockquote:after,code,kbd,tt,var,big,button,input[type=button],input[type=reset],input[type=submit],.video-stu,.pattern-center h1.cat-title,.pattern-center h1.entry-title,.pattern-center .cat-des,.single-center .single-header h1.entry-title,.single-center .entry-census,.pattern-center-sakura h1.cat-title,.pattern-center-sakura h1.entry-title,.pattern-center-sakura .cat-des,.single-center .single-header h1.entry-title,.single-center .entry-census,.site-top .lower,.site-top .menu-item-has-children li a,.feature i,.p-time,.p-time i,i.iconfont.hotpost,.post-list p,.post-more i,.info-meta span,.post-list-thumb i,.post-date,.post-meta,.post-meta a,.float-content .post-text,.float-content i,.s-time,.s-time i,.navigator i,.site-info,.entry-census,h1.page-title,.post-lincenses,.post-tags,.post-like a,.post-like i,.post-share ul li i ,.post-squares .category,.post-squares h3,.post-squares .label,.author-profile .meta h3 a,.author-profile p,.author-profile i,.mashiro-profile .box a,#comments-list-title span,.butterBar-message ,h1.works-title,.works-p-time,.works-p-time i,.works-meta i,.works-meta span,#archives-temp h3,h1.cat-title,span.sitename,span.linkss-title,.comment .commeta,.comment .comment-reply-link,.comment .info,.comment .info .useragent-info-m,.comment h4,.comment h4 a,.comment-respond #cancel-comment-reply-link,.comment-respond input,.comment-respond textarea,.comment-respond .logged-in-as i,.notification i,.notification span,.siren-checkbox-label,h1.fes-title,h1.main-title,.foverlay-bg,.feature-title .foverlay,.notice i,.bio-text,.header-info,.site-header.iconsearch,i.iconfont.js-toggle-search.iconsearch,.search-form i ,.search-form input,.s-search input,.s-search i,.ins-section,.ins-section .iconfont.icon-mark,.ins-section .fa ,.ins-section.ins-search-item .search-keyword,.ins-section .ins-search-item .ins-search-preview,i.iconfont.down,#pagination span,#bangumi-pagination span,.ex-login-title ,.ex-register-title h3,.ex-login input,.ex-register input,.admin-login-check,.user-login-check,.ex-login-username,.ex-new-account a,.ex-register .user-error,.register-close,.herder-user-name,.header-user-menu a,.no-logged,.no-logged a,.single-reward .reward-open,.reward-row li::after ,.botui-container,button.botui-actions-buttons-button,input.botui-actions-text-input,.load-button span,.prpr,.mashiro-tips,.live2d-tool,.center-text,.bb-comment,.finish-icon-container,.skill div,#footer-sponsor,.comment-user-avatar .socila-check ,.insert-image-tips ,.the-feature.from_left_and_right .info h3,.the-feature.from_left_and_right .info p,.highlight-wrap .copy-code ,#bangumi-pagination {font-weight: <?php echo akina_option('fontweight'); ?> !important}

/*字体*/
.serif{font-family:<?php echo akina_option('global-default-font'); ?> !important ;}

body{font-family:<?php echo akina_option('global-font2'); ?> !important;}

h1.main-title,h1.fes-title{font-family:<?php echo akina_option('font-title'); ?> !important;}

.header-info p{font-family:<?php echo akina_option('font-oneword'); ?> !important;}

.header-info p {font-size: <?php echo akina_option('fontsize-oneword'); ?>px !important;}

/*鼠标*/
body {cursor: url(<?php echo akina_option('cursor-nor'); ?>), auto}

.botui-actions-buttons-button,.headertop-down i,.faa-parent.animated-hover:hover>.faa-spin, .faa-spin.animated, .faa-spin.animated-hover:hover,i.iconfont.js-toggle-search.iconsearch,#waifu #live2d,.aplayer svg,.aplayer.aplayer-narrow .aplayer-body, .aplayer.aplayer-narrow .aplayer-pic,button.botui-actions-buttons-button,#emotion-toggle,.emoji-item, .emotion-box,.emotion-item,.on-hover,.tieba-container span,#moblieGoTop,#changskin{cursor: url(<?php echo akina_option('cursor-no'); ?>), auto;}

a,.ins-section .ins-section-header,.font-family-controls button,.menu-list li,.ins-section .ins-search-item,.ins-section .ins-search-item .ins-search-preview{cursor: url(<?php echo akina_option('cursor-ayu'); ?>), auto}

p ,.highlight-wrap code,.highlight-wrap,.hljs-ln-code .hljs-ln-line,.hljs-ln-code .hljs-ln-line{cursor: url(<?php echo akina_option('cursor-text'); ?>), auto}

a:active {cursor: url(<?php echo akina_option('cursor-work'); ?>), alias}

/*其他*/
.comment-respond textarea {
background-image: url(<?php echo akina_option('comment-image'); ?>); 
}
.search-form.is-visible{
background-image: url(<?php echo akina_option('search-image'); ?>);
}

</style>
<?php }
add_action('wp_head', 'customizer_css');