<?php
/**
 * 仅对boolean类型的设置项有效
 */
function font_end_js_control() {
    $check = function($a):bool {
        return  $a ? true : false;
    };
    $ecs_src = function(string $a):string {
        return get_template_directory_uri().'/css/theme/'.$a.'.css?'.IRO_VERSION.iro_opt('cookie_version', '');
    };
    $mashiro_opt = [
        'NProgressON' => $check(iro_opt('nprogress_on')),
        'audio' => $check(iro_opt('note_effects')),
        'yiyan' => $check(iro_opt('footer_yiyan')),
        'baguetteBox' => $check(iro_opt('baguetteBox')),
        'fancybox' => $check(iro_opt('fancybox')),
        'darkmode' => $check(iro_opt('theme_darkmode_auto')),
        'email_domain' => iro_opt('email_domain', ''),
        'email_name' => iro_opt('email_name', ''),
        'cookie_version_control' => iro_opt('cookie_version', ''),
        'qzone_autocomplete' => false,
        'site_name' => iro_opt('site_name', ''),
        'author_name' => iro_opt('author_name', ''),
        'template_url' => get_template_directory_uri(),
        'site_url' => site_url(),
        'qq_api_url' => rest_url('sakura/v1/qqinfo/json'),
        'live_search' => $check(iro_opt('live_search')),
        'land_at_home' => $check(is_home()),
        'clipboardCopyright' => iro_opt('clipboard_copyright') == "0" ? false:true,
        'entry_content_style' => iro_opt('entry_content_style'),
        'cover_api' => rest_url('sakura/v1/image/cover'),
        'random_graphs_mts' => $check(iro_opt('random_graphs_mts' )),
        'code_highlight' => iro_opt('code_highlight_method','hljs'),
        'comment_upload_img' => iro_opt('img_upload_api')=='off' ? false : true,
        'cache_cover' => $check(iro_opt('cache_cover')),
        'site_bg_as_cover' => $check(iro_opt('site_bg_as_cover')),
        'yiyan_api' => json_decode(iro_opt('yiyan_api')),
        'skin_bg0' => 'none',
        'skin_bg1' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/foreground/bg1.png',
        'skin_bg2' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/foreground/bg2.png',
        'skin_bg3' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/foreground/bg3.png',
        'skin_bg4' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/foreground/bg4.png',
    ];
    $reception_background = iro_opt('reception_background');
    // 判空 empty 如果变量不存在也会返回true
    !empty($reception_background['img1']) && $mashiro_opt['skin_bg0'] = $reception_background['img1'];
    !empty($reception_background['img2']) && $mashiro_opt['skin_bg1'] = $reception_background['img2'];
    !empty($reception_background['img3']) && $mashiro_opt['skin_bg2'] = $reception_background['img3'];
    !empty($reception_background['img4']) && $mashiro_opt['skin_bg3'] = $reception_background['img4'];
    !empty($reception_background['img5']) && $mashiro_opt['skin_bg4'] = $reception_background['img5'];
    $mashiro_opt['entry_content_style_src'] = iro_opt('entry_content_style') == 'sakurairo' ? $ecs_src('sakura') : $ecs_src('github');
    $mashiro_opt['jsdelivr_css_src'] = iro_opt('local_global_library') ? (get_template_directory_uri().'/css/lib.css?'.IRO_VERSION.iro_opt('cookie_version', '')) : ('https://cdn.jsdelivr.net/gh/mirai-mamori/Sakurairo@'.IRO_VERSION.'/css/lib.css');
    if (iro_opt('lightgallery')){$mashiro_opt['lightGallery'] = iro_opt('lightgallery_option');}
    if (iro_opt('theme_darkmode_auto')){$mashiro_opt['dm_strategy'] = iro_opt('theme_darkmode_strategy','time');}
    if (iro_opt('preload_blur',0)){$mashiro_opt['preload_blur'] = iro_opt('preload_blur',0);}
    if (iro_opt('aplayer_server') != 'off'){
        $mashiro_opt['float_player_on'] = true;
        $mashiro_opt['meting_api_url'] = rest_url('sakura/v1/meting/aplayer');
    }
    if (iro_opt('code_highlight_method','hljs')=='prism'){
        $mashiro_opt['code_highlight_prism'] = [
            'line_number_all' => $check(iro_opt('code_highlight_prism_line_number_all')),
            'autoload_path' => iro_opt('code_highlight_prism_autoload_path','') ?: 'undefined'
        ];
        $theme_light = iro_opt('code_highlight_prism_theme_light');
        $theme_dark = iro_opt('code_highlight_prism_theme_dark');
        if ($theme_light != ''){
            $mashiro_opt['code_highlight_prism']['theme'] = ['light' => $theme_light];
        }
        if ($theme_dark != ''){
            $mashiro_opt['code_highlight_prism']['theme'] = ['dark' => $theme_dark];
        }
    }
    wp_add_inline_script('app', 'var mashiro_option = '.json_encode($mashiro_opt,JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE),'before');
}
add_action('wp_head', 'font_end_js_control');
