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

.logolink .sakuraso {
    color: <?php echo akina_option('theme_skin'); ?>;
}

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
.logolink a {
    color: <?php echo akina_option('theme_skin'); ?>;
}
.logolink.moe-mashiro a {
    color: <?php echo akina_option('theme_skin'); ?>;
}
.logolink a:hover .sakuraso {
    color: <?php echo akina_option('theme_skin'); ?>;
}
.logolink a:hover .shironeko, .logolink a:hover .no, .logolink a:hover rt {
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
.changeSkin-gear,.toc{
    background:rgba(255,255,255,<?php echo akina_option('sakura_skin_alpha','') ?>);
}
.art-content #archives .al_mon_list .al_mon,
.art-content #archives .al_mon_list span {
    color: <?php echo akina_option('theme_skin'); ?>;
}
<?php if(akina_option('entry_content_theme') == "sakura"){ ?>
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
</style>
<?php }
add_action('wp_head', 'customizer_css');