<?php
function font_end_js_control() {
    function check($a) {
        if ($a) {
            return true;
        }
        return false;
    };
    function ecs_src($a) {
        global $core_lib_basepath;
        return $core_lib_basepath.'/css/theme/'.$a.'.css?'.IRO_VERSION.iro_opt('cookie_version', '');
    };
    $vision_resource_basepath = iro_opt('vision_resource_basepath','https://s.nmxc.ltd/sakurairo_vision/@2.5/');
    $movies = iro_opt('cover_video') ?
    array(
        'url' => iro_opt('cover_video_link'),
        'name' => iro_opt('cover_video_title'),
        'live' => iro_opt('cover_video_live') ? true : false,
        'loop' => iro_opt('cover_video_loop') ? true : false
    )
    : 'close';
    $auto_height = !iro_opt('cover_full_screen') ? 'fixed' : 'auto';
    $gravatar_url = iro_opt('gravatar_proxy') ?: 'secure.gravatar.com/avatar';
    $iro_opt = [
        // Poi
        'pjax' => check(iro_opt('poi_pjax')),
        'movies' => $movies,
        'windowheight' => $auto_height,
        'ajaxurl' => admin_url('admin-ajax.php'),
        'order' => get_option('comment_order'), // ajax comments
        'formpostion' => 'bottom', // ajax comments 默认为bottom，如果你的表单在顶部则设置为top。
        'api' => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest'),
        'google_analytics_id' => iro_opt('google_analytics_id', ''),
        'gravatar_url' => $gravatar_url,
        // options
        'NProgressON' => check(iro_opt('nprogress_on')),
        'audio' => check(iro_opt('note_effects')),
        'baguetteBox' => check(iro_opt('baguetteBox')),
        'fancybox' => check(iro_opt('fancybox')),
        'darkmode' => check(iro_opt('theme_darkmode_auto')),
        'email_domain' => iro_opt('email_domain', ''),
        'email_name' => iro_opt('email_name', ''),
        'ext_shared_lib'=>iro_opt('external_vendor_lib'),
        'cookie_version_control' => iro_opt('cookie_version', ''),
        'qzone_autocomplete' => false,
        'site_name' => iro_opt('site_name', ''),
        'author_name' => iro_opt('author_name', ''),
        'template_url' => get_template_directory_uri(),
        'site_url' => site_url(),
        'qq_api_url' => rest_url('sakura/v1/qqinfo/json'),
        'land_at_home' => check(is_home()),
        'live_search' => check(iro_opt('live_search')),
        'loading_ph'=>iro_opt('load_in_svg'),
        'clipboardCopyright' => iro_opt('clipboard_copyright') == '0' ? false:true,
        'entry_content_style' => iro_opt('entry_content_style'),
        'random_graphs_mts' => check(iro_opt('random_graphs_mts' )),
        'code_highlight' => iro_opt('code_highlight_method','hljs'),
        'comment_upload_img' => iro_opt('img_upload_api')=='off' ? false : true,
        'cache_cover' => check(iro_opt('cache_cover')),
        'site_bg_as_cover' => check(iro_opt('site_bg_as_cover')),
        'yiyan_api' => json_decode(iro_opt('yiyan_api')),
        'skin_bg0' => '',
        'skin_bg1' => $vision_resource_basepath.'background/foreground/bg1.png',
        'skin_bg2' => $vision_resource_basepath.'background/foreground/bg2.png',
        'skin_bg3' => $vision_resource_basepath.'background/foreground/bg3.png',
        'skin_bg4' => $vision_resource_basepath.'background/foreground/bg4.png',
    ];
    $reception_background = iro_opt('reception_background');
    // 判空 empty 如果变量不存在也会返回true
    if (iro_opt('random_graphs_options') == 'external_api') {
        if (wp_is_mobile()) {
            $iro_opt['cover_api'] = iro_opt('random_graphs_mts')?iro_opt('random_graphs_link_mobile'):iro_opt('random_graphs_link');
        }else{
            $iro_opt['cover_api'] = iro_opt('random_graphs_link');
        }
    } else {
        $iro_opt['cover_api'] =  rest_url('sakura/v1/image/cover');
    }
    !empty($reception_background['img1']) && $iro_opt['skin_bg0'] = $reception_background['img1'];
    !empty($reception_background['img2']) && $iro_opt['skin_bg1'] = $reception_background['img2'];
    !empty($reception_background['img3']) && $iro_opt['skin_bg2'] = $reception_background['img3'];
    !empty($reception_background['img4']) && $iro_opt['skin_bg3'] = $reception_background['img4'];
    !empty($reception_background['img5']) && $iro_opt['skin_bg4'] = $reception_background['img5'];
    $iro_opt['jsdelivr_css_src'] = iro_opt('shared_library_basepath') ? (get_template_directory_uri().'/css/lib.css?'.IRO_VERSION.iro_opt('cookie_version', '')) : ('https://s.nmxc.ltd/sakurairo/@'.IRO_VERSION.'/css/lib.css');
    if (iro_opt('lightgallery')){
        # 请务必使用正确标准的json格式
        $lightGallery = str_replace(PHP_EOL, '', iro_opt('lightgallery_option')); 
        $iro_opt['lightGallery'] = json_decode($lightGallery,true);
    }
    if (iro_opt('aplayer_server') != 'off'){
        $iro_opt['float_player_on'] = true;
        $iro_opt['meting_api_url'] = rest_url('sakura/v1/meting/aplayer');
    }
    if (iro_opt('code_highlight_method','hljs')=='prism'){
        $iro_opt['code_highlight_prism'] = [
            'line_number_all' => check(iro_opt('code_highlight_prism_line_number_all')),
            'autoload_path' => iro_opt('code_highlight_prism_autoload_path','') ?: null
        ];
        $theme_light = iro_opt('code_highlight_prism_theme_light');
        $theme_dark = iro_opt('code_highlight_prism_theme_dark');
        $preload_blur = iro_opt('preload_blur',0);
        if ($preload_blur) $iro_opt['preload_blur'] = $preload_blur;
        if ($theme_light) $iro_opt['code_highlight_prism']['theme'] = ['light' => $theme_light];
        if ($theme_dark) $iro_opt['code_highlight_prism']['theme'] = ['dark' => $theme_dark];
    }
    $sakura_effect = iro_opt('sakura_falling_effects');
    if($sakura_effect != 'off') $iro_opt['effect'] = array('amount'=>$sakura_effect);
    if (iro_opt('theme_darkmode_auto')) $iro_opt['dm_strategy'] = iro_opt('theme_darkmode_strategy','time');
    wp_add_inline_script('app', 'var _iro = '.json_encode($iro_opt,JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE),'before');
}
add_action('wp_head', 'font_end_js_control');
