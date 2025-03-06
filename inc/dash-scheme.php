<?php
/*
 * https://2heng.xin/wp-content/themes/Sakura/inc/dash-schame.php?color_1=&color_2=&color_3=&color_4=
 */

//ini_set('display_errors', true);
//error_reporting(E_ALL);

header("Content-type: text/css; charset: UTF-8");
#header('Access-Control-Allow-Origin: *');

function _get($str){
    $val = !empty($_GET[$str]) ? $_GET[$str] : null;
    return $val;
}

if(_get('color_1')==NULL) {
	$color_1="#85ABFC";
} else {
	$color_1="#"._get('color_1');
}

if(_get('color_2')==NULL) {
	$color_2="#99B8FC";
} else {
	$color_2="#"._get('color_2');
}

if(_get('color_3')==NULL) {
	$color_3="#F181A2";
} else {
	$color_3="#"._get('color_3');
}

if(_get('color_4')==NULL) {
	$color_4="#F181A2";
} else {
	$color_4="#"._get('color_4');
}

// Convert hex to rgba with higher transparency values
function hex2rgba($color, $opacity = 1) {
    $default = 'rgba(0,0,0,0.3)'; // Increased default transparency
    
    // Return default if no color provided
    if(empty($color))
        return $default; 
    
    // Sanitize $color if "#" is provided 
    if ($color[0] == '#' ) {
        $color = substr($color, 1);
    }

    // Check if color has 6 or 3 characters and get values
    if (strlen($color) == 6) {
        $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
    } elseif (strlen($color) == 3) {
        $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
    } else {
        return $default;
    }
    
    // Convert hexadec to rgb
    $rgb = array_map('hexdec', $hex);
    
    // Check if opacity is set(rgba or rgb)
    if($opacity){
        if(abs($opacity) > 1)
            $opacity = 1.0;
        $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
    } else {
        $output = 'rgb('.implode(",",$rgb).')';
    }
    
    return $output;
}

// Create transparent versions of our colors with higher transparency
$color_1_85 = hex2rgba($color_1, 0.40); // Increased transparency from 0.45 to 0.40
$color_1_75 = hex2rgba($color_1, 0.30); // Increased transparency from 0.35 to 0.30
$color_1_50 = hex2rgba($color_1, 0.20); // Increased transparency from 0.25 to 0.20
$color_2_85 = hex2rgba($color_2, 0.40); // Increased transparency from 0.45 to 0.40
$color_2_75 = hex2rgba($color_2, 0.30); // Increased transparency from 0.35 to 0.30
$color_2_50 = hex2rgba($color_2, 0.20); // Increased transparency from 0.25 to 0.20
$color_3_85 = hex2rgba($color_3, 0.40); // Increased transparency from 0.45 to 0.40
$color_3_75 = hex2rgba($color_3, 0.30); // Increased transparency from 0.35 to 0.30
$color_3_50 = hex2rgba($color_3, 0.20); // Increased transparency from 0.25 to 0.20
$color_4_85 = hex2rgba($color_4, 0.40); // Increased transparency from 0.45 to 0.40
$color_4_75 = hex2rgba($color_4, 0.30); // Increased transparency from 0.35 to 0.30
$color_4_50 = hex2rgba($color_4, 0.20); // Increased transparency from 0.25 to 0.20

if(_get('rules')==NULL) {
	$rules="";
} else {
	$rules=urldecode(_get('rules'));
}

?>

/*! This file is auto-generated with transparency and blur effects */
body{background:#f1f1f1}

a{color:#0073aa}
a:active,a:focus,a:hover{color:#0096dd}

#media-upload a.del-link:hover,.subsubsub a.current:hover,.subsubsub a:hover,div.dashboard-widget-submit input:hover{
    color:#0096dd
}

input[type=checkbox]:checked:before{
    color:<?php echo $color_2; ?>;
    width: 1.3125rem;
}

input[type=radio]:checked:before{
    background:<?php echo $color_2; ?>;
}

.wp-core-ui input[type=reset]:active,.wp-core-ui input[type=reset]:hover{
    color:#0096dd
}

/* Button styling with transparency and improved padding */
.wp-core-ui .button-primary{
    background:<?php echo $color_3_85; ?>;
    border-color:transparent;
    color:#fff;
    box-shadow:0 1px 0 rgba(0,0,0,0.1);
    text-shadow:none;
    border-radius: 4px;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.wp-core-ui .button-primary:focus,.wp-core-ui .button-primary:hover{
    background:<?php echo $color_3_75; ?>;
    border-color:transparent;
    color:#fff;
    box-shadow:0 1px 0 rgba(0,0,0,0.1);
}

.wp-core-ui .button-primary:focus{
    box-shadow:0 0 3px rgba(0,0,0,0.2);
}

.wp-core-ui .button-primary.active,.wp-core-ui .button-primary.active:focus,
.wp-core-ui .button-primary.active:hover,.wp-core-ui .button-primary:active{
    background:<?php echo $color_3; ?>;
    border-color:transparent;
    box-shadow:inset 0 2px 0 rgba(0,0,0,0.1);
}

.wp-core-ui .button-primary.button-primary-disabled,.wp-core-ui .button-primary.disabled,
.wp-core-ui .button-primary:disabled,.wp-core-ui .button-primary[disabled]{
    color:rgba(255,255,255,0.7) !important;
    background:<?php echo hex2rgba($color_3, 0.3); ?> !important;
    border-color:transparent !important;
    text-shadow:none !important;
}

.wp-core-ui .button-primary.button-hero{
    box-shadow:0 2px 0 rgba(0,0,0,0.1) !important;
}

.wp-core-ui .button-primary.button-hero:active{
    box-shadow:inset 0 3px 0 rgba(0,0,0,0.1) !important;
}

.wp-core-ui .wp-ui-primary{
    color:#fff;
    background-color:<?php echo $color_2_85; ?>;
}

.wp-core-ui .wp-ui-text-primary{
    color:<?php echo $color_2; ?>;
}

.wp-core-ui .wp-ui-highlight{
    color:#fff;
    background-color:<?php echo $color_3_85; ?>;
}

.wp-core-ui .wp-ui-text-highlight{
    color:<?php echo $color_3; ?>;
}

.wp-core-ui .wp-ui-notification{
    color:#fff;
    background-color:<?php echo $color_4_85; ?>;
}

.wp-core-ui .wp-ui-text-notification{
    color:<?php echo $color_4; ?>;
}

.wp-core-ui .wp-ui-text-icon{
    color:#f3f2f1;
}

.wrap .add-new-h2:hover,.wrap .page-title-action:hover{
    color:#fff;
    background-color:<?php echo $color_2_85; ?>;
    border-color:transparent;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.view-switch a.current:before{
    color:<?php echo $color_2; ?>;
}

.view-switch a:hover:before{
    color:<?php echo $color_4; ?>;
}

/* Admin menu with transparency and blur - fixed stacking issue by applying background only to #adminmenuwrap */
#adminmenu, #adminmenuback {
    background: transparent;
}

#adminmenuwrap {
    background: <?php echo $color_2_85; ?>;
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
}

#adminmenu a{
    color:#fff;
    transition: all 0.3s ease;
}

#adminmenu div.wp-menu-image:before{
    color:rgba(255,255,255,0.8);
}

#adminmenu a:hover,#adminmenu li.menu-top:hover,
#adminmenu li.opensub>a.menu-top,#adminmenu li>a.menu-top:focus{
    color:#fff;
    background-color:<?php echo $color_3_75; ?>;
    border-radius: 4px;
}

#adminmenu li.menu-top:hover div.wp-menu-image:before,
#adminmenu li.opensub>a.menu-top div.wp-menu-image:before{
    color:#fff;
}

.about-wrap .nav-tab-active,.nav-tab-active,.nav-tab-active:hover{
    background-color:#f1f1f1;
    border-bottom-color:#f1f1f1;
}

#adminmenu .wp-has-current-submenu .wp-submenu,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu,
#adminmenu .wp-submenu,
#adminmenu a.wp-has-current-submenu:focus+.wp-submenu,
.folded #adminmenu .wp-has-current-submenu .wp-submenu{
    background:<?php echo $color_1_75; ?>;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-radius: 0 4px 4px 0;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

#adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after{
    border-right-color:<?php echo $color_1_75; ?>;
}

#adminmenu .wp-submenu .wp-submenu-head{
    color:#FFF;
    border-radius: 4px;
    backdrop-filter: blur(0);
    -webkit-backdrop-filter: blur(0);
}

#adminmenu .wp-has-current-submenu .wp-submenu a,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu a,
#adminmenu .wp-submenu a,
#adminmenu a.wp-has-current-submenu:focus+.wp-submenu a,
.folded #adminmenu .wp-has-current-submenu .wp-submenu a{
    color:#FFF;
    border-radius: 4px;
    transition: all 0.2s ease;
}

#adminmenu .wp-has-current-submenu .wp-submenu a:focus,
#adminmenu .wp-has-current-submenu .wp-submenu a:hover,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu a:focus,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu a:hover,
#adminmenu .wp-submenu a:focus,#adminmenu .wp-submenu a:hover,
#adminmenu a.wp-has-current-submenu:focus+.wp-submenu a:focus,
#adminmenu a.wp-has-current-submenu:focus+.wp-submenu a:hover,
.folded #adminmenu .wp-has-current-submenu .wp-submenu a:focus,
.folded #adminmenu .wp-has-current-submenu .wp-submenu a:hover{
    color:<?php echo $color_3; ?>;
    background: rgba(255,255,255,0.07);
    box-shadow: none;
}

#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a,
#adminmenu .wp-submenu li.current a,
#adminmenu a.wp-has-current-submenu:focus+.wp-submenu li.current a{
    color:#fff;
}

#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a:focus,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a:hover,
#adminmenu .wp-submenu li.current a:focus,
#adminmenu .wp-submenu li.current a:hover,
#adminmenu a.wp-has-current-submenu:focus+.wp-submenu li.current a:focus,
#adminmenu a.wp-has-current-submenu:focus+.wp-submenu li.current a:hover{
    color:<?php echo $color_3; ?>;
}

ul#adminmenu a.wp-has-current-submenu:after,
ul#adminmenu>li.current>a.current:after{
    border-right-color:#f1f1f1;
}

#adminmenu li.current a.menu-top,
#adminmenu li.wp-has-current-submenu .wp-submenu .wp-submenu-head,
#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu,
.folded #adminmenu li.current.menu-top{
    color:#fff;
    background:<?php echo $color_3_85; ?>;
    border-radius: 4px;
}

#adminmenu a.current:hover div.wp-menu-image:before,
#adminmenu li a:focus div.wp-menu-image:before,
#adminmenu li.opensub div.wp-menu-image:before,
#adminmenu li.wp-has-current-submenu a:focus div.wp-menu-image:before,
#adminmenu li.wp-has-current-submenu div.wp-menu-image:before,
#adminmenu li.wp-has-current-submenu.opensub div.wp-menu-image:before,
#adminmenu li:hover div.wp-menu-image:before,
.ie8 #adminmenu li.opensub div.wp-menu-image:before{
    color:#fff;
}

#adminmenu .awaiting-mod,#adminmenu .update-plugins{
    color:#fff;
    background:<?php echo $color_4_85; ?>;
    border-radius: 10px;
}

#adminmenu li a.wp-has-current-submenu .update-plugins,
#adminmenu li.current a .awaiting-mod,
#adminmenu li.menu-top:hover>a .update-plugins,
#adminmenu li:hover a .awaiting-mod{
    color:#fff;
    background:<?php echo $color_1_85; ?>;
}

#collapse-button{
    color:rgba(255,255,255,0.8);
    border-radius: 50%;
    transition: all 0.3s ease;
}

#collapse-button:focus,#collapse-button:hover{
    color:<?php echo $color_3; ?>;
    background: rgba(255,255,255,0.07);
}

/* Admin bar with transparency and blur */
#wpadminbar{
    color:#fff;
    background:<?php echo $color_2_85; ?>;
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

#wpadminbar .ab-item,
#wpadminbar a.ab-item,
#wpadminbar>#wp-toolbar span.ab-label,
#wpadminbar>#wp-toolbar span.noticon{
    color:#fff;
}

#wpadminbar .ab-icon,
#wpadminbar .ab-icon:before,
#wpadminbar .ab-item:after,
#wpadminbar .ab-item:before{
    color:rgba(255,255,255,0.8);
}

#wpadminbar .ab-top-menu>li.menupop.hover>.ab-item,
#wpadminbar.nojq .quicklinks .ab-top-menu>li>.ab-item:focus,
#wpadminbar.nojs .ab-top-menu>li.menupop:hover>.ab-item,
#wpadminbar:not(.mobile) .ab-top-menu>li:hover>.ab-item,
#wpadminbar:not(.mobile) .ab-top-menu>li>.ab-item:focus{
    color:<?php echo $color_3; ?>;
    background:<?php echo $color_1_75; ?>;
    border-radius: 4px;
    box-shadow: none;
}

#wpadminbar:not(.mobile)>#wp-toolbar a:focus span.ab-label,
#wpadminbar:not(.mobile)>#wp-toolbar li.hover span.ab-label,
#wpadminbar:not(.mobile)>#wp-toolbar li:hover span.ab-label{
    color:<?php echo $color_3; ?>;
}

#wpadminbar:not(.mobile) li:hover #adminbarsearch:before,
#wpadminbar:not(.mobile) li:hover .ab-icon:before,
#wpadminbar:not(.mobile) li:hover .ab-item:after,
#wpadminbar:not(.mobile) li:hover .ab-item:before{
    color:#fff;
}

#wpadminbar .menupop .ab-sub-wrapper{
    background:<?php echo $color_1_75; ?>;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-radius: 4px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

#wpadminbar .quicklinks .menupop ul.ab-sub-secondary,
#wpadminbar .quicklinks .menupop ul.ab-sub-secondary .ab-submenu{
    background:rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-radius: 4px;
}

#wpadminbar .ab-submenu .ab-item,
#wpadminbar .quicklinks .menupop ul li a,
#wpadminbar .quicklinks .menupop.hover ul li a,
#wpadminbar.nojs .quicklinks .menupop:hover ul li a{
    color:#FFF;
    border-radius: 4px;
    transition: all 0.2s ease;
}

#wpadminbar .menupop .menupop>.ab-item:before,
#wpadminbar .quicklinks li .blavatar{
    color:rgba(255,255,255,0.8);
}

#wpadminbar .quicklinks .ab-sub-wrapper .menupop.hover>a,
#wpadminbar .quicklinks .menupop ul li a:focus,
#wpadminbar .quicklinks .menupop ul li a:focus strong,
#wpadminbar .quicklinks .menupop ul li a:hover,
#wpadminbar .quicklinks .menupop ul li a:hover strong,
#wpadminbar .quicklinks .menupop.hover ul li a:focus,
#wpadminbar .quicklinks .menupop.hover ul li a:hover,
#wpadminbar li #adminbarsearch.adminbar-focused:before,
#wpadminbar li .ab-item:focus .ab-icon:before,
#wpadminbar li .ab-item:focus:before,
#wpadminbar li a:focus .ab-icon:before,
#wpadminbar li.hover .ab-icon:before,
#wpadminbar li.hover .ab-item:before,
#wpadminbar li:hover #adminbarsearch:before,
#wpadminbar li:hover .ab-icon:before,
#wpadminbar li:hover .ab-item:before,
#wpadminbar.nojs .quicklinks .menupop:hover ul li a:focus,
#wpadminbar.nojs .quicklinks .menupop:hover ul li a:hover{
    color:<?php echo $color_3; ?>;
    background: rgba(255,255,255,0.07);
}

#wpadminbar .menupop .menupop>.ab-item:hover:before,
#wpadminbar .quicklinks .ab-sub-wrapper .menupop.hover>a .blavatar,
#wpadminbar .quicklinks li a:focus .blavatar,
#wpadminbar .quicklinks li a:hover .blavatar,
#wpadminbar.mobile .quicklinks .ab-icon:before,
#wpadminbar.mobile .quicklinks .ab-item:before{
    color:<?php echo $color_3; ?>;
}

#wpadminbar.mobile .quicklinks .hover .ab-icon:before,
#wpadminbar.mobile .quicklinks .hover .ab-item:before{
    color:rgba(255,255,255,0.8);
}

#wpadminbar #adminbarsearch:before{
    color:rgba(255,255,255,0.8);
}

#wpadminbar>#wp-toolbar>#wp-admin-bar-top-secondary>#wp-admin-bar-search #adminbarsearch input.adminbar-input:focus{
    color:#fff;
    background:rgba(0, 0, 0, 0.15);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    border-radius: 4px;
    box-shadow: 0 0 2px rgba(0,0,0,0.2) inset;
}

#wpadminbar #wp-admin-bar-recovery-mode{
    color:#fff;
    background-color:<?php echo $color_4_85; ?>;
    border-radius: 4px;
}

#wpadminbar #wp-admin-bar-recovery-mode .ab-item,
#wpadminbar #wp-admin-bar-recovery-mode a.ab-item{
    color:#fff;
}

#wpadminbar .ab-top-menu>#wp-admin-bar-recovery-mode.hover>.ab-item,
#wpadminbar.nojq .quicklinks .ab-top-menu>#wp-admin-bar-recovery-mode>.ab-item:focus,
#wpadminbar:not(.mobile) .ab-top-menu>#wp-admin-bar-recovery-mode:hover>.ab-item,
#wpadminbar:not(.mobile) .ab-top-menu>#wp-admin-bar-recovery-mode>.ab-item:focus{
    color:#fff;
    background-color:rgba(0, 0, 0, 0.2);
    border-radius: 4px;
}

#wpadminbar .quicklinks li#wp-admin-bar-my-account.with-avatar>a img{
    border-color:rgba(255, 255, 255, 0.1);
    background-color:rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}

#wpadminbar #wp-admin-bar-user-info .display-name{
    color:#fff;
}

#wpadminbar #wp-admin-bar-user-info a:hover .display-name{
    color:<?php echo $color_3; ?>;
}

#wpadminbar #wp-admin-bar-user-info .username{
    color:rgba(255, 255, 255, 0.7);
}

.wp-pointer .wp-pointer-content h3{
    background-color:<?php echo $color_3_85; ?>;
    border:none;
    border-radius: 8px 8px 0 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.wp-pointer .wp-pointer-content h3:before{
    color:<?php echo $color_3; ?>;
}

.wp-pointer.wp-pointer-top .wp-pointer-arrow,
.wp-pointer.wp-pointer-top .wp-pointer-arrow-inner,
.wp-pointer.wp-pointer-undefined .wp-pointer-arrow,
.wp-pointer.wp-pointer-undefined .wp-pointer-arrow-inner{
    border-bottom-color:<?php echo $color_3; ?>;
}

.media-item .bar,.media-progress-bar div{
    background-color:<?php echo $color_3_85; ?>;
    border-radius: 4px;
}

.details.attachment{
    box-shadow:inset 0 0 0 3px #fff,inset 0 0 0 7px <?php echo $color_3; ?>;
}

.attachment.details .check{
    background-color:<?php echo $color_3_85; ?>;
    box-shadow:0 0 0 1px #fff,0 0 0 2px <?php echo $color_3; ?>;
    border-radius: 50%;
}

.media-selection .attachment.selection.details .thumbnail{
    box-shadow:0 0 0 1px #fff,0 0 0 3px <?php echo $color_3; ?>;
}

.theme-browser .theme.active .theme-name,
.theme-browser .theme.add-new-theme a:focus:after,
.theme-browser .theme.add-new-theme a:hover:after{
    background:<?php echo $color_2_85; ?>;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 0 0 4px 4px;
}

.theme-browser .theme.add-new-theme a:focus span:after,
.theme-browser .theme.add-new-theme a:hover span:after{
    color:<?php echo $color_2; ?>;
}

.theme-filter.current,.theme-section.current{
    border-bottom-color:<?php echo $color_2; ?>;
}

body.more-filters-opened .more-filters{
    color:#fff;
    background-color:<?php echo $color_2_85; ?>;
    border-radius: 4px;
    border-color:transparent;
}

body.more-filters-opened .more-filters:before{
    color:#fff;
}

body.more-filters-opened .more-filters:focus,
body.more-filters-opened .more-filters:hover{
    background-color:<?php echo $color_3_85; ?>;
    color:#fff;
    border-color:transparent;
}

body.more-filters-opened .more-filters:focus:before,
body.more-filters-opened .more-filters:hover:before{
    color:#fff;
}

.widgets-chooser li.widgets-chooser-selected{
    background-color:<?php echo $color_3_85; ?>;
    color:#fff;
    border-radius: 4px;
    border:none;
}

.widgets-chooser li.widgets-chooser-selected:before,
.widgets-chooser li.widgets-chooser-selected:focus:before{
    color:#fff;
}

div#wp-responsive-toggle a:before{
    color:rgba(255,255,255,0.8);
}

.wp-responsive-open div#wp-responsive-toggle a{
    border-color:transparent;
    background:<?php echo $color_3_85; ?>;
    border-radius: 4px;
}

.wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle a{
    background:<?php echo $color_1_85; ?>;
    border-radius: 4px;
}

.wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle .ab-icon:before{
    color:rgba(255,255,255,0.8);
}

.mce-container.mce-menu .mce-menu-item-normal.mce-active,
.mce-container.mce-menu .mce-menu-item-preview.mce-active,
.mce-container.mce-menu .mce-menu-item.mce-selected,
.mce-container.mce-menu .mce-menu-item:focus,
.mce-container.mce-menu .mce-menu-item:hover{
    background:<?php echo $color_3_85; ?>;
    border-radius: 4px;
}

/* Additional custom styles for better focus states */
:focus {
    outline: 2px solid <?php echo $color_3_75; ?>;
    outline-offset: 1px;
}

/* Custom scrollbars for a more integrated look */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: rgba(241, 241, 241, 0.2);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: <?php echo $color_2_50; ?>;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: <?php echo $color_2_75; ?>;
}

/* 二级菜单样式优化 - 降低透明度，增强模糊效果 */
#adminmenu .wp-submenu-wrap,
#adminmenu .wp-submenu,
#adminmenu .wp-has-current-submenu .wp-submenu,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu,
#adminmenu a.wp-has-current-submenu:focus+.wp-submenu,
.folded #adminmenu .wp-has-current-submenu .wp-submenu {
    background: <?php echo hex2rgba($color_1, 0.7); ?>;
    backdrop-filter: blur(25px);
    -webkit-backdrop-filter: blur(25px);
    border-radius: 0 4px 4px 0;
    box-shadow: 0 2px 5px rgba(0,0,0,0.08);
}

#adminmenu .wp-submenu .wp-submenu-head {
    color: #FFF;
    background: <?php echo hex2rgba($color_1, 0.8); ?>;
    border-radius: 4px 4px 0 0;
    backdrop-filter: blur(25px);
    -webkit-backdrop-filter: blur(25px);
}

#adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after {
    border-right-color: <?php echo hex2rgba($color_1, 0.8); ?>;
}

/* 二级菜单项样式优化 */
#adminmenu .wp-submenu a,
#adminmenu .wp-has-current-submenu .wp-submenu a,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu a,
#adminmenu a.wp-has-current-submenu:focus+.wp-submenu a,
.folded #adminmenu .wp-has-current-submenu .wp-submenu a {
    color: #fff;
    border-radius: 4px;
    transition: all 0.2s ease;
    padding: 5px 12px;
}

#adminmenu .wp-submenu a:focus,
#adminmenu .wp-submenu a:hover,
#adminmenu .wp-has-current-submenu .wp-submenu a:focus,
#adminmenu .wp-has-current-submenu .wp-submenu a:hover,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu a:focus,
#adminmenu .wp-has-current-submenu.opensub .wp-submenu a:hover,
#adminmenu a.wp-has-current-submenu:focus+.wp-submenu a:focus,
#adminmenu a.wp-has-current-submenu:focus+.wp-submenu a:hover,
.folded #adminmenu .wp-has-current-submenu .wp-submenu a:focus,
.folded #adminmenu .wp-has-current-submenu .wp-submenu a:hover {
    color: <?php echo $color_3; ?>;
    background: <?php echo hex2rgba('#FFFFFF', 0.1); ?>;
    box-shadow: 0 0 3px rgba(0,0,0,0.05);
}

#adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a,
#adminmenu .wp-submenu li.current a,
#adminmenu a.wp-has-current-submenu:focus+.wp-submenu li.current a {
    background: <?php echo hex2rgba($color_1, 0.8); ?>;
    color: #fff;
    font-weight: 500;
}

<?php echo $rules; ?>


