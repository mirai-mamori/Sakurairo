<?php

/**
 * 文章短代码
 * @author Seaton Jiang <seaton@vtrois.com>
 * @license MIT License
 * @version 2020.04.12
 */
define('BOOTSTRAP_CSS_URI', 'https://cdn.jsdelivr.net/gh/KotoriK/bspart/bs-partial.min.css'); //按需加载bootstrap
function loadbs($atts, $content = null, $code = "")
{
    wp_enqueue_style('bspart', BOOTSTRAP_CSS_URI);
    return '';
}
function h2title($atts, $content = null, $code = "")
{
    wp_enqueue_style('bspart', BOOTSTRAP_CSS_URI);
    $return = '<h2 class="title">';
    $return .= $content;
    $return .= '</h2>';
    return $return;
}
add_shortcode('h2title', 'h2title');

function success($atts, $content = null, $code = "")
{
    wp_enqueue_style('bspart', BOOTSTRAP_CSS_URI);
    $return = '<div class="alert alert-success">';
    $return .= $content;
    $return .= '</div>';
    return $return;
}
add_shortcode('success', 'success');

function info($atts, $content = null, $code = "")
{
    wp_enqueue_style('bspart', BOOTSTRAP_CSS_URI);
    $return = '<div class="alert alert-info">';
    $return .= $content;
    $return .= '</div>';
    return $return;
}
add_shortcode('info', 'info');

function warning($atts, $content = null, $code = "")
{
    wp_enqueue_style('bspart', BOOTSTRAP_CSS_URI);
    $return = '<div class="alert alert-warning">';
    $return .= $content;
    $return .= '</div>';
    return $return;
}
add_shortcode('warning', 'warning');

function danger($atts, $content = null, $code = "")
{
    wp_enqueue_style('bspart', BOOTSTRAP_CSS_URI);
    $return = '<div class="alert alert-danger">';
    $return .= $content;
    $return .= '</div>';
    return $return;
}
add_shortcode('danger', 'danger');

function wymusic($atts, $content = null, $code = "")
{
    extract(shortcode_atts(array("autoplay" => '0'), $atts));
    $return = '<div class="mb-3"><iframe style="width:100%" frameborder="no" border="0" marginwidth="0" marginheight="0" height=86 src="//music.163.com/outchain/player?type=2&id=';
    $return .= $content;
    $return .= '&auto=' . $autoplay . '&height=66"></iframe></div>';
    return $return;
}
add_shortcode('music', 'wymusic');

function bdbtn($atts, $content = null, $code = "")
{
    $return = '<a class="downbtn" href="';
    $return .= $content;
    $return .= '" target="_blank"><i class="kicon i-download mr-1"></i>立即下载</a>';
    return $return;
}
add_shortcode('bdbtn', 'bdbtn');

function kbd($atts, $content = null, $code = "")
{
    $return = '<kbd>';
    $return .= $content;
    $return .= '</kbd>';
    return $return;
}
add_shortcode('kbd', 'kbd');

function nrmark($atts, $content = null, $code = "")
{
    $return = '<mark>';
    $return .= $content;
    $return .= '</mark>';
    return $return;
}
add_shortcode('mark', 'nrmark');

function striped($atts, $content = null, $code = "")
{
    $return = '<div class="progress"><div class="progress-bar" role="progressbar" style="width:';
    $return .= $content;
    $return .= '%;" aria-valuenow="';
    $return .= $content;
    $return .= '" aria-valuemin="0" aria-valuemax="100">';
    $return .= $content;
    $return .= '%</div></div>';
    return $return;
}
add_shortcode('striped', 'striped');

function successbox($atts, $content = null, $code = "")
{
    wp_enqueue_style('bspart', BOOTSTRAP_CSS_URI);
    extract(shortcode_atts(array("title" => '标题内容'), $atts));
    $return = '<div class="card border-success text-white mb-3"><div class="card-header bg-success">';
    $return .= $title;
    $return .= '</div><div class="card-body alert-success"><p class="card-text">';
    $return .= $content;
    $return .= '</p></div></div>';
    return $return;
}
add_shortcode('successbox', 'successbox');

function infobox($atts, $content = null, $code = "")
{
    wp_enqueue_style('bspart', BOOTSTRAP_CSS_URI);
    extract(shortcode_atts(array("title" => '标题内容'), $atts));
    $return = '<div class="card border-info text-white mb-3"><div class="card-header bg-info">';
    $return .= $title;
    $return .= '</div><div class="card-body alert-info"><p class="card-text">';
    $return .= $content;
    $return .= '</p></div></div>';
    return $return;
}
add_shortcode('infobox', 'infobox');

function warningbox($atts, $content = null, $code = "")
{
    wp_enqueue_style('bspart', BOOTSTRAP_CSS_URI);
    extract(shortcode_atts(array("title" => '标题内容'), $atts));
    $return = '<div class="card border-warning text-white mb-3"><div class="card-header bg-warning">';
    $return .= $title;
    $return .= '</div><div class="card-body alert-warning"><p class="card-text">';
    $return .= $content;
    $return .= '</p></div></div>';
    return $return;
}
add_shortcode('warningbox', 'warningbox');

function dangerbox($atts, $content = null, $code = "")
{
    wp_enqueue_style('bspart', BOOTSTRAP_CSS_URI);
    extract(shortcode_atts(array("title" => '标题内容'), $atts));
    $return = '<div class="card border-danger text-white mb-3"><div class="card-header bg-danger">';
    $return .= $title;
    $return .= '</div><div class="card-body alert-danger"><p class="card-text">';
    $return .= $content;
    $return .= '</p></div></div>';
    return $return;
}
add_shortcode('dangerbox', 'dangerbox');

function vqq($atts, $content = null, $code = "")
{
    wp_enqueue_style('bspart', BOOTSTRAP_CSS_URI);

    $return = '<div class="embed-responsive embed-responsive-16by9"><iframe frameborder="0" src="https://v.qq.com/txp/iframe/player.html?vid=';
    $return .= $content;
    $return .= '" allowFullScreen="true"></iframe></div>';
    return $return;
}
add_shortcode('vqq', 'vqq');

function youtube($atts, $content = null, $code = "")
{
    wp_enqueue_style('bspart', BOOTSTRAP_CSS_URI);

    $return = '<div class="embed-responsive embed-responsive-16by9"><iframe src="https://www.youtube.com/embed/';
    $return .= $content;
    $return .= '" frameborder="0" allowfullscreen="allowfullscreen"></iframe></div>';
    return $return;
}
add_shortcode('youtube', 'youtube');

function bilibili($atts, $content = null, $code = "")
{
    wp_enqueue_style('bspart', BOOTSTRAP_CSS_URI);

    extract(shortcode_atts(array("cid" => 'cid'), $atts));
    $return = '<div class="embed-responsive embed-responsive-16by9"><iframe src="//player.bilibili.com/player.html?cid=';
    $return .= $cid;
    $return .= '&aid=';
    $return .= $content;
    $return .= '&page=1" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true"> </iframe></div>';
    return $return;
}
add_shortcode('bilibili', 'bilibili');
//“快速”CC授权协议标记
function cc_share(string $is_allow_share, string $is_share_alike, string $text_share_alike, string $text_no_derivatives): string
{
    return $is_allow_share == 'true' ? ($is_share_alike == 'true' ? $text_share_alike : '') : $text_no_derivatives;
}
function cc_svgs(string $src): string
{
    return '<img src="' . $src . '" style="width: 2em;margin-right: .2em;"/>';
}
function license($atts, $content = null, $code = "")
{
    wp_enqueue_style('bspart', BOOTSTRAP_CSS_URI);
    global $post;
    extract(shortcode_atts(array(
        "show_title" => false,
        "name" => function_exists('get_multiple_authors') ? implode(', ', array_map(function ($e) {
            return '<a xmlns:cc="http://creativecommons.org/ns#" href="' . get_author_posts_url($e->ID) . '" property="cc:attributionName" rel="cc:attributionURL">' . $e->display_name . '</a>';
        }, get_multiple_authors())) : false,
        "source" => false, //链接本作品演绎自的作品
        "url_more_permit" => false,
        "allow_share" => 'true',
        "share_alike" => 'true',
        "no_c" => 'true'
    ), $atts));
    $license_href = '"http://creativecommons.org/licenses/by' . ($no_c === 'true' ? '-nc' : '') . (cc_share($allow_share, $share_alike, '-sa', '-nd')) . '/4.0/"'; //放在href=时，不用打双引号
    $license_title = str_replace(
        '{attach}',
        ($no_c === 'true' ? __('-NonCommercial', 'blocksy') : "") . cc_share($allow_share, $share_alike, __('-ShareAlike', 'blocksy'), __('-NoDerivatives', 'blocksy')),
        __('Creative Commons Attribution{attach} 4.0 International License', 'blocksy')
    );
    $return = '<div class="card"><p class="card-body"><a rel="license" href="' . $license_href . '" title=",' . $license_title . '">'
        . cc_svgs('https://cdn.jsdelivr.net/gh/DazaiYuki/PicStorage/img/cc.svg')
        . cc_svgs('https://cdn.jsdelivr.net/gh/DazaiYuki/PicStorage/img/by.svg')
        . ($no_c === 'true' ? cc_svgs('https://cdn.jsdelivr.net/gh/DazaiYuki/PicStorage/img/nc.svg') : "")
        . cc_share($allow_share, $share_alike, cc_svgs('https://cdn.jsdelivr.net/gh/DazaiYuki/PicStorage/img/sa.svg'),  cc_svgs('https://cdn.jsdelivr.net/gh/DazaiYuki/PicStorage/img/nd.svg'))
        . '</a><br />'
        . '<span>' . ($show_title ? '《' . $post->post_title . '》' : __('This work ', 'blocksy')) . '</span>'
        . ($name ? __('by ', 'blocksy') . $name : '')
        . (str_replace(
            '{license}',
            '<a rel="license" href=' . $license_href . '>'
                . $license_title
                . '</a>',
            __('is licensed under a {license}.', 'blocksy')
        ));
    $return .= $source ? str_replace('{source}', '<br/><a xmlns:dct="http://purl.org/dc/terms/" href="' . $source . '" rel="dct:source">' . $source . '</a>', __('Based on a work at {source}', 'blocksy')) : ''
        . ($url_more_permit ? '<br/>' .
            str_replace('{url_more_permit}', '<a xmlns:cc="http://creativecommons.org/ns#" href="' . $url_more_permit . '" rel="cc:morePermissions">' . $url_more_permit .
                '</a>', __('Permissions beyond the scope of this license may be available at {url_more_permit}.', 'blocksy')) : '')
        . ' </p></div>';
    return $return;
}
add_shortcode('license', 'license');
function card($atts, $content = null, $code = "")
{
    wp_enqueue_style('bspart', BOOTSTRAP_CSS_URI);
    return '<div class="card"><div class="card-body">' . $content . '</div></div>';
}
add_shortcode('card', 'card');
/* function include_css_extra()
{
    include_once 'prism_lang.php';
    wp_enqueue_script(get_script_handle('css'), PRISM_LINK_HEAD . 'components/prism-css.min.js', array('prism-core'), null, true);
    wp_enqueue_script(get_script_handle('css-extras'), PRISM_LINK_HEAD . 'components/prism-css-extras.min.js', array('prism-core', get_script_handle('css')), null, true);
}
function autoload_lang(string $lang_name)
{
    include_once 'prism_lang.php';
    enqueue_lang_script(check_alias($lang_name));
}

function code($atts, $content = null, $code = "")
{
    define('PRISM_LINK_HEAD', 'https://cdn.jsdelivr.net/npm/prismjs@1.21.0/');
    extract(shortcode_atts(array(
        "theme" => null, //一个页面只能用一个theme，跟随第一个有theme参数的[code]
        "lang" => '',
        'line_number_start' => -1,
        'cmd_user' => null,
        'cmd_prompt' => null,
        'cmd_host' => null,
        'src' => '',
        "match_brace" => false, //与src不兼容
        //单页面作用域
        'client_autoload' => true, //遇到不兼容功能时会自动关闭
        'show_lang' => false,
        'autolink' => false,
        'preview' => false,
        "inline_color" => false,
        ///end
    ), $atts));
    wp_enqueue_script('prism-core', PRISM_LINK_HEAD . 'components/prism-core.min.js', array(), null, true);

    if ($theme && $theme >= 0 && $theme < 8) {
        define('prism_themes', array('', 'coy', 'dark', 'funky', 'okaidia', 'solarizedlight', 'tomorrow', 'twilight'));
        wp_enqueue_style('prism-s', PRISM_LINK_HEAD . "themes/prism-" . constant('prism_themes')[$theme] . ".min.css");
    } else {
        wp_enqueue_style('prism-s', PRISM_LINK_HEAD . "themes/prism.min.css");
    }
    /////
    $code_class = array();
    $pre_options = array();
    $pre_class = array();
    if ($line_number_start > -1) {
        array_push($pre_class, 'line-numbers');
        wp_enqueue_script('prism-line-number', PRISM_LINK_HEAD . 'plugins/line-numbers/prism-line-numbers.min.js', array('prism-core'), null, true);
        wp_enqueue_style('prism-s-ln', PRISM_LINK_HEAD . 'plugins/line-numbers/prism-line-numbers.css');
        array_push($pre_options, 'data-start="' . $line_number_start . '"');
    }
    //cmd

    $cmd_switch = false;
    if ($cmd_prompt) //命令行的完整前缀
    {
        array_push($pre_options, 'data-prompt="' . $cmd_prompt . '"');
        $cmd_switch = true;
    } else {
        if ($cmd_user) {
            array_push($pre_options, 'data-user="' . $cmd_user . '"');
            $cmd_switch = true;
        }
        if ($cmd_host) {
            array_push($pre_options, 'data-host="' . $cmd_host . '"');
            $cmd_switch = true;
        }
    }
    if ($cmd_switch) {
        $client_autoload = false;
        array_push($pre_class, 'command-line');
        wp_enqueue_style('prism-s-cmd', PRISM_LINK_HEAD . 'plugins/command-line/prism-command-line.css');
        wp_enqueue_script('prism-cmd', PRISM_LINK_HEAD . 'plugins/command-line/prism-command-line.min.js', array('prism-core'), null, true);
    }
    //src
    if ($src) {
        array_push($pre_options, 'data-src="' . $src . '"');
        wp_enqueue_script('prism-file-highlight', PRISM_LINK_HEAD . 'plugins/file-highlight/prism-file-highlight.min.js', array('prism-core'), null, true);
    }

    //show_lang
    if ($show_lang) {
        wp_enqueue_script('prism-toolbar', PRISM_LINK_HEAD . 'plugins/toolbar/prism-toolbar.min.js', array('prism-core'), null, true);
        wp_enqueue_style('prism-s-toolbar', PRISM_LINK_HEAD . 'plugins/toolbar/prism-toolbar.css');
        wp_enqueue_script('prism-show-lang', PRISM_LINK_HEAD . 'plugins/show-language/prism-show-language.min.js', array('prism-core', 'prism-toolbar'), null, true);
    }
    //auto-linker
    if ($autolink) {
        wp_enqueue_style('prism-s-autolink', PRISM_LINK_HEAD . 'plugins/autolinker/prism-autolinker.css');
        wp_enqueue_script('prism-autolink', PRISM_LINK_HEAD . 'plugins/autolinker/prism-autolinker.min.js', array('prism-core'), null, true);
    }
    //preview
    if ($preview) {
        include_css_extra();
        wp_enqueue_style('prism-s-preview', PRISM_LINK_HEAD . 'plugins/previewers/prism-previewers.min.css');
        wp_enqueue_script('prism-preview', PRISM_LINK_HEAD . 'plugins/previewers/prism-previewers.min.js', array('prism-core', get_script_handle('css-extras')), null, true);
    }
    //inline color
    if ($inline_color) {
        include_css_extra();
        wp_enqueue_style('prism-s-color', PRISM_LINK_HEAD . 'plugins/inline-color/prism-inline-color.min.css');
        wp_enqueue_script('prism-color', PRISM_LINK_HEAD . 'plugins/inline-color/prism-inline-color.min.js', array('prism-core', get_script_handle('css-extras')), null, true);
    }
    //match_brace
    if ($match_brace) {
        wp_enqueue_style('prism-s-match-brace', PRISM_LINK_HEAD . 'plugins/match-braces/prism-match-braces.css');
        wp_enqueue_script('prism-match-brace', PRISM_LINK_HEAD . 'plugins/match-braces/prism-match-braces.min.js', array('prism-core'), null, true);
        array_push($code_class, 'match-braces');
    }
    ///client_autoload & lang
    if ($client_autoload) {
        $lang && array_push($code_class, 'language-' . $lang);
        wp_enqueue_script('prism-autoloader', PRISM_LINK_HEAD . 'plugins/autoloader/prism-autoloader.min.js', array('prism-core'), null, true);
    } else {
        if ($lang) {
            autoload_lang(($lang));
            array_push($code_class, 'language-' . $lang);
        }
    }
    return '<pre' . ((count($pre_class) > 0) ? ' class="' . join(' ', $pre_class) . '"' : '') . ' ' . join(' ', $pre_options) . '><code class="' . join(' ', $code_class) . '">' . $content . '</code></pre>';
}
add_shortcode('code', 'code');
 */
add_action('init', 'more_button');
function more_button()
{
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }
    if (get_user_option('rich_editing') == 'true') {
        add_filter('mce_external_plugins', 'add_plugin');
        add_filter('mce_buttons', 'register_button');
    }
}

function add_more_buttons($buttons)
{
    $buttons[] = 'hr';
    $buttons[] = 'wp_page';
    $buttons[] = 'fontsizeselect';
    $buttons[] = 'styleselect';
    return $buttons;
}
add_filter("mce_buttons", "add_more_buttons");

function register_button($buttons)
{
    array_push($buttons, " ", "h2title");
    array_push($buttons, " ", "kbd");
    array_push($buttons, " ", "mark");
    array_push($buttons, " ", "striped");
    array_push($buttons, " ", "bdbtn");
    array_push($buttons, " ", "music");
    array_push($buttons, " ", "vqq");
    array_push($buttons, " ", "youtube");
    array_push($buttons, " ", "bilibili");
    array_push($buttons, " ", "success");
    array_push($buttons, " ", "info");
    array_push($buttons, " ", "warning");
    array_push($buttons, " ", "danger");
    array_push($buttons, " ", "successbox");
    array_push($buttons, " ", "infoboxs");
    array_push($buttons, " ", "warningbox");
    array_push($buttons, " ", "dangerbox");
    return $buttons;
}

function add_plugin($plugin_array)
{
    $plugin_array['h2title'] = get_template_directory_uri() . '/static/buttons/more.js';
    $plugin_array['kbd'] = get_template_directory_uri() . '/static/buttons/more.js';
    $plugin_array['mark'] = get_template_directory_uri() . '/static/buttons/more.js';
    $plugin_array['striped'] = get_template_directory_uri() . '/static/buttons/more.js';
    $plugin_array['bdbtn'] = get_template_directory_uri() . '/static/buttons/more.js';
    $plugin_array['music'] = get_template_directory_uri() . '/static/buttons/more.js';
    $plugin_array['vqq'] = get_template_directory_uri() . '/static/buttons/more.js';
    $plugin_array['youtube'] = get_template_directory_uri() . '/static/buttons/more.js';
    $plugin_array['bilibili'] = get_template_directory_uri() . '/static/buttons/more.js';
    $plugin_array['success'] = get_template_directory_uri() . '/static/buttons/more.js';
    $plugin_array['info'] = get_template_directory_uri() . '/static/buttons/more.js';
    $plugin_array['warning'] = get_template_directory_uri() . '/static/buttons/more.js';
    $plugin_array['danger'] = get_template_directory_uri() . '/static/buttons/more.js';
    $plugin_array['successbox'] = get_template_directory_uri() . '/static/buttons/more.js';
    $plugin_array['infoboxs'] = get_template_directory_uri() . '/static/buttons/more.js';
    $plugin_array['warningbox'] = get_template_directory_uri() . '/static/buttons/more.js';
    $plugin_array['dangerbox'] = get_template_directory_uri() . '/static/buttons/more.js';
    return $plugin_array;
}
