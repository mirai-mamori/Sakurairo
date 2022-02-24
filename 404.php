<?php

/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Akina
 */

?>
<html <?php language_attributes();
add_action('wp_head',function(){
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('entry-content');
},5);
?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?php echo iro_opt('favicon_link', ''); ?>" />
    <link rel="stylesheet" href="<?= $shared_lib_basepath ?>/css/404_lib.css" type="text/css" media="all"/>
    <?php wp_head(); ?>
    <?php
    /* <script>
var the_url=window.location.href;
var the_dom="<?php echo str_replace("http://", "", str_replace("https://", "", get_site_url())); ?>";
var no_report = false;
if (the_dom!= '2heng.xin') {
    no_report = true;
}
var the_ua=navigator.userAgent;
var the_ref=document.referrer;
function httpGet(theUrl) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
    xmlHttp.send( null );
    return xmlHttp.responseText;
}
var report_url = "https://api.mashiro.top/count/404/?" + "url="+the_url+"&ua="+the_ua+"&ref="+the_ref;
if (!no_report) httpGet(report_url);
</script> */
    ?>
</head>

<body <?php body_class(); ?>>
    <section class="error-404 not-found">
        <div class="error-img">
            <div class="anim-icon" id="404" style="height: 66%;"></div>
        </div>
        <div class="err-button back">
            <a id="golast" href=javascript:history.go(-1);><?php _e('return to previous page', 'sakurairo');/*返回上一页*/ ?></a>
            <a id="gohome" href="<?php bloginfo('url'); ?>"><?php _e('return to home page', 'sakurairo');/*返回主页*/ ?></a>
        </div>
        <div style="display:block; width:284px;margin: auto;">
            <p style="margin-bottom: 1em;margin-top: 1.5em;text-align: center;font-size: 15px;"><?php _e('Don\'t worry, search in site?', 'sakurairo');/*别急，试试站内搜索？*/ ?></p>
            <form class="s-search" method="get" action="/" role="search">
                <i class="iconfont icon-search" style="bottom: 9px;left: 15px;"></i>
                <input class="text-input" style="padding: 8px 20px 8px 46px;" type="search" name="s" placeholder="<?php _e('Search...', 'akina') ?>" required>
            </form>
        </div>
    </section>
    <script src="<?php echo $shared_lib_basepath . '/js/anf.js' ?>" type="text/javascript"></script>
</body>