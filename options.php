<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 */

function optionsframework_option_name()
{

    // 从样式表获取主题名称
    $themename = wp_get_theme();
    $themename = preg_replace("/\W/", "_", strtolower($themename));

    $optionsframework_settings = get_option('optionsframework');
    $optionsframework_settings['id'] = $themename;
    update_option('optionsframework', $optionsframework_settings);
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'sakurairo'
 * with the actual text domain for your theme.
 *
 * Frame from: https://github.com/devinsays/options-framework-plugin/
 */

function optionsframework_options()
{
    // 测试数据
    $test_array = array(
        'one' => __('1', 'sakurairo'),
        'two' => __('2', 'sakurairo'),
        'three' => __('3', 'sakurairo'),
        'four' => __('4', 'sakurairo'),
        'five' => __('5', 'sakurairo'),
        'six' => __('6', 'sakurairo'),
        'seven' => __('7', 'sakurairo'),
    );

    // 复选框数组
    $multicheck_array = array(
        'one' => __('1', 'sakurairo'),
        'two' => __('2', 'sakurairo'),
        'three' => __('3', 'sakurairo'),
        'four' => __('4', 'sakurairo'),
        'five' => __('5', 'sakurairo'),
    );

    // 复选框默认值
    $multicheck_defaults = array(
        'one' => '1',
        'five' => '1',
    );

    // 背景默认值
    $background_defaults = array(
        'color' => '',
        'image' => 'https://view.moezx.cc/images/2018/12/23/knights-of-the-frozen-throne-8k-qa.jpg',
        'repeat' => 'repeat',
        'position' => 'top center',
        'attachment' => 'scroll');

    // 版式默认值
    $typography_defaults = array(
        'size' => '15px',
        'face' => 'georgia',
        'style' => 'bold',
        'color' => '#bada55');

    // 版式设置选项
    $typography_options = array(
        'sizes' => array('6', '12', '14', '16', '20'),
        'faces' => array('Helvetica Neue' => 'Helvetica Neue', 'Arial' => 'Arial'),
        'styles' => array('normal' => '普通', 'bold' => '粗体'),
        'color' => false,
    );

    // 将所有分类（categories）加入数组
    $options_categories = array();
    $options_categories_obj = get_categories();
    foreach ($options_categories_obj as $category) {
        $options_categories[$category->cat_ID] = $category->cat_name;
    }

    // 将所有标签（tags）加入数组
    $options_tags = array();
    $options_tags_obj = get_tags();
    foreach ($options_tags_obj as $tag) {
        $options_tags[$tag->term_id] = $tag->name;
    }

    // 将所有页面（pages）加入数组
    $options_pages = array();
    $options_pages_obj = get_pages('sort_column=post_parent,menu_order');
    $options_pages[''] = 'Select a page:';
    foreach ($options_pages_obj as $page) {
        $options_pages[$page->ID] = $page->post_title;
    }

    // 如果使用图片单选按钮, define a directory path
    $imagepath = get_template_directory_uri() . '/images/';

    $options = array();

    //基本设置
    $options[] = array(
        'name' => __('Basic', 'sakurairo'), 
        'type' => 'heading');

    $options[] = array(
        'name' => __('Basic-Setting', 'sakurairo'), 
        'desc' => __(' ', 'sakurairo'), 
        'id' => 'setting_basic',
        'std' => 'tag',
        'type' => "images",
        'options' => array(
            'tag' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img@latest/setting-img/Set-Basic.png',
                ),
            );        
        
    $options[] = array(
        'name' => __('Support For You', 'sakurairo'), 
        'desc' => __(' ', 'sakurairo'), 
        'id' => 'setting_sfu',
        'std' => 'tag',
        'type' => "images",
        'options' => array(
            'tag' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img@latest/setting-img/Set-Support.png',
                ),
            );
    
    $options[] = array(
        'name' => __('Site title', 'sakurairo'), 
        'desc' => __('Mashiro\'s Blog', 'sakurairo'),
        'id' => 'site_name',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Author', 'sakurairo'), 
        'desc' => __('Mashiro', 'sakurairo'),
        'id' => 'author_name',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Personal avatar', 'sakurairo'), 
        'desc' => __('The best size is 130px*130px.', 'sakurairo'), 
        'id' => 'focus_logo',
        'type' => 'upload');

    $options[] = array(
        'name' => __('Text LOGO', 'sakurairo'), 
        'desc' => __('The home page does not display the avatar above, but displays a paragraph of text (use the avatar above if left blank).The text is recommended not to be too long, about 16 bytes is appropriate.', 'sakurairo'), 
        'id' => 'focus_logo_text',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Logo', 'sakurairo'),
        'desc' => __('The best height size is 40px。', 'sakurairo'), 
        'id' => 'akina_logo',
        'type' => 'upload');

    $options[] = array(
        'name' => __('Favicon', 'sakurairo'),
        'desc' => __('It is the small logo on the browser tab, fill in the url', 'sakurairo'), 
        'id' => 'favicon_link',
        'std' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/favicon.ico',
        'type' => 'text');

    $options[] = array(
        'name' => __('Custom keywords and descriptions ', 'sakurairo'), 
        'desc' => __('Customize keywords and descriptions after opening', 'sakurairo'), 
        'id' => 'akina_meta',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Site keywords', 'sakurairo'), 
        'desc' => __('Each keyword is divided by a comma "," and the number is within 5.', 'sakurairo'), 
        'id' => 'akina_meta_keywords',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Site descriptions', 'sakurairo'), 
        'desc' => __('Describe the site in concise text, with a maximum of 120 words.', 'sakurairo'), 
        'id' => 'akina_meta_description',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Expand the nav menu', 'sakurairo'), 
        'desc' => __('By default, it is enabled (checked), and the check and collapse are cancelled.', 'sakurairo'), 
        'id' => 'shownav',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Head decoration', 'sakurairo'), 
        'desc' => __('Enable by default, check off, display on the article page, separate page and category page', 'sakurairo'), 
        'id' => 'patternimg',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Search button', 'sakurairo'), 
        'id' => 'top_search',
        'std' => "yes",
        'type' => "radio",
        'options' => array(
            'yes' => __('Open', 'sakurairo'),
            'no' => __('Close', 'sakurairo'),
        ));

    $options[] = array(
        'name' => __('Home article style', 'sakurairo'), 
        'id' => 'post_list_style',
        'std' => "imageflow",
        'type' => "radio",
        'options' => array(
            'standard' => __('Standard', 'sakurairo'), 
            'imageflow' => __('Graphic', 'sakurairo'), 
        ));

    $options[] = array(
        'name' => __('Home article feature images (only valid for standard mode)', 'sakurairo'), 
        'id' => 'list_type',
        'std' => "round",
        'type' => "radio",
        'options' => array(
            'round' => __('Round', 'sakurairo'), 
            'square' => __('Square', 'sakurairo'), 
        ));

    $options[] = array(
        'name' => __('Home article feature images alignment (only for graphic mode, default left and right alternate)', 'sakurairo'), 
        'id' => 'feature_align',
        'std' => "alternate",
        'type' => "radio",
        'options' => array(
            'left' => __('Left', 'sakurairo'), 
            'right' => __('Right', 'sakurairo'), 
            'alternate' => __('Alternate', 'sakurairo'), 
        ));

    $options[] = array(
        'name' => __('Paging mode', 'sakurairo'), 
        'id' => 'pagenav_style',
        'std' => "ajax",
        'type' => "radio",
        'options' => array(
            'ajax' => __('Ajax load', 'sakurairo'), 
            'np' => __('Previous and next page', 'sakurairo'), 
        ));

    $options[] = array(
        'name' => __('Automatically load the next page', 'sakurairo'), 
        'desc' => __('(seconds) Set to automatically load the next page time, the default is not automatically loaded', 'sakurairo'), 
        'id' => 'auto_load_post',
        'std' => '233',
        'type' => 'select',
        'options' => array(
            '0' => __('0', 'sakurairo'),
            '1' => __('1', 'sakurairo'),
            '2' => __('2', 'sakurairo'),
            '3' => __('3', 'sakurairo'),
            '4' => __('4', 'sakurairo'),
            '5' => __('5', 'sakurairo'),
            '6' => __('6', 'sakurairo'),
            '7' => __('7', 'sakurairo'),
            '8' => __('8', 'sakurairo'),
            '9' => __('9', 'sakurairo'),
            '10' => __('10', 'sakurairo'),
            '233' => __('Do not load automatically', 'sakurairo'), 
        ));

    $options[] = array(
        'name' => __('Footer info', 'sakurairo'), 
        'desc' => __('Footer description, support for HTML code', 'sakurairo'), 
        'id' => 'footer_info',
        'std' => 'Copyright &copy; by Hitomi All Rights Reserved.',
        'type' => 'textarea');

    $options[] = array(
        'name' => __('About', 'sakurairo'), 
        'desc' => sprintf(__('Sakurairo v %s  |  <a href="https://asuhe.jp/daily/sakurairo-user-manual/">Theme document</a>  |  <a href="https://github.com/mirai-mamori/Sakurairo">Source code</a><a href="https://github.com/mirai-mamori/Sakurairo/releases/latest"><img src="https://img.shields.io/github/v/release/mirai-mamori/Sakurairo.svg?style=flat-square" alt="GitHub release"></a>', 'sakurairo'), SAKURA_VERSION), 
        'id' => 'theme_intro',
        'std' => '',
        'type' => 'typography ');

   $options[] = array(
        'name' => __('Sponsor US', 'sakurairo'), 
        'desc' => __(' ', 'sakurairo'), 
        'id' => 'setting_sus',
        'std' => 'tag',
        'type' => "images",
        'options' => array(
            'tag' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img@latest/setting-img/Set-Sponsor.png',
                ),
            );
    
    $options[] = array(
        'name' => __('Check for Updates', 'sakurairo'), 
        'desc' => '<a href="https://github.com/mirai-mamori/Sakurairo/releases/latest">Download the latest version</a>',
        'id' => "release_info",
        'std' => "tag",
        'type' => "images",
        'options' => array(
        'tag' => 'https://img.shields.io/github/v/release/mirai-mamori/Sakurairo.svg?style=flat-square',
        'tag2' => 'https://img.shields.io/github/release-date/mirai-mamori/Sakurairo?style=flat-square',
        'tag3' => 'https://data.jsdelivr.com/v1/package/gh/mirai-mamori/sakurairo/badge',
            ),
        );

    //首页设置
    $options[] = array(
        'name' => __('HomePage', 'sakurairo'), 
        'type' => 'heading');

    $options[] = array(
        'name' => __('HomePage-Setting', 'sakurairo'), 
        'desc' => __(' ', 'sakurairo'), 
        'id' => 'setting_home',
        'std' => 'tag',
        'type' => "images",
        'options' => array(
            'tag' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img@latest/setting-img/Set-HomePage.png',
                ),
            );         

    $options[] = array(
        'name' => __('Main Switch', 'sakurairo'), 
        'desc' => __('Default on, check off', 'sakurairo'), 
        'id' => 'main-switch',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Information Bar', 'sakurairo'), 
        'desc' => __('It is on by default and checked off to display the avatar / text logo, signature bar and social card.', 'sakurairo'), 
        'id' => 'infor-bar',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Social Card', 'sakurairo'), 
        'desc' => __('On by default, check off. When the social card is turned off, the switch button of background random graph and social network icon will not be displayed', 'sakurairo'), 
        'id' => 'social-card',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Background Random Graphs Switch', 'sakurairo'), 
        'desc' => __('Default on (check), cancel check to turn off display. If the check is not checked, the switch button of background random graph will be turned off', 'sakurairo'), 
        'id' => 'background-rgs',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Information Bar Style', 'sakurairo'),
        'id' => 'infor-bar-style',
        'std' => "v1",
        'type' => "radio",
        'options' => array(
            'v2' => __('Social card and signature merge', 'sakurairo'), 
            'v1' => __('Social card and signature independent', 'sakurairo'), 
        ));

    $options[] = array(
        'name' => __('Signature Bar Fillet', 'sakurairo'),
        'desc' => __('Fill in the number, the recommended value is 10 to 20', 'sakurairo'), 
        'id' => 'info-radius',
        'std' => '15',
        'class' => 'mini',
        'type' => 'text');    

    $options[] = array(
        'name' => __('Social Card Fillet', 'sakurairo'), 
        'desc' => __('Fill in the number, the recommended value is 10 to 20', 'sakurairo'),
        'id' => 'img-radius',
        'std' => '15',
        'class' => 'mini',
        'type' => 'text');  

    $options[] = array(
        'name' => __('Information Bar Avatar Fillet', 'sakurairo'), 
        'desc' => __('Fill in the number, the recommended value is 90 to 110', 'sakurairo'),
        'id' => 'head-radius',
        'std' => '100',
        'class' => 'mini',
        'type' => 'text');  

    $options[] = array(
        'name' => __('Cover Random Graphs Option', 'sakurairo'), 
        'desc' => __('Select how to call the cover random image', 'sakurairo'), 
        'id' => 'cover_cdn_options',
        'std' => "type_3",
        'type' => "select",
        'options' => array(
            'type_1' => __('Webp Optimized Random Graphs', 'sakurairo'), 
            'type_2' => __('Local Random Graphs', 'sakurairo'), 
            'type_3' => __('External API Random Graphs', 'sakurairo'), 
        )
    );

    $options[] = array(
        'name' => __('Multi Terminal Separation of Home Random Graphs', 'sakurairo'), 
        'desc' => __('It is off by default and enabled by check', 'sakurairo'), 
        'id' => 'cover_beta',
        'std' => '0',
        'type' => 'checkbox');
    
    $options[] = array(
        'name' => __('Webp Optimization / External API PC Random Graphs Url', 'sakurairo'),
        'desc' => sprintf(__('Fill in the manifest path for random picture display, please refer to <a href = "https: //github.com/mashirozx/Sakura/wiki/options">Wiki </a>. If you select webp images above, click <a href = "%s">here</a> to update manifest', 'sakurairo'), rest_url('sakura/v1/database/update')), 
        'id' => 'cover_cdn',
        'std' => 'https://api.btstu.cn/sjbz/api.php?lx=dongman&format=images',
        'type' => 'text');

    $options[] = array(
        'name' => __('External API Mobile Random Graphs Url', 'sakurairo'), 
        'desc' => __('When you use the external API random graph and check the multi terminal separation option, please fill in the random graph API mobile terminal address here, otherwise the mobile terminal random graph will be blank','sakurairo'),
        'id' => 'cover_cdn_mobile',
        'std' => 'https://api.uomg.com/api/rand.img2?sort=二次元&format=images',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Full-Screen Display', 'sakurairo'), 
        'desc' => __('Default on, check off', 'sakurairo'), 
        'id' => 'focus_height',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Cover Video', 'sakurairo'),
        'desc' => __('Check on', 'sakurairo'), 
        'id' => 'focus_amv',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Cover Video Loop', 'sakurairo'),
        'desc' => __('Check to enable, the video will continue to play automatically, you need to enable Pjax', 'sakurairo'), 
        'id' => 'focus_mvlive',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Video Url', 'sakurairo'), 
        'desc' => __('The source address of the video, the address is spliced below the video name, the slash is not required at the end of the address', 'sakurairo'), 
        'id' => 'amv_url',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Video Name', 'sakurairo'), 
        'desc' => __('abc.mp4, just fill in the video file name abc, multiple videos separated by commas such as abc, efg, do not care about the order, because the loading is random extraction', 'sakurairo'), 
        'id' => 'amv_title',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Cover Random Graphs Filter', 'sakurairo'), 
        'id' => 'focus_img_filter',
        'std' => "filter-nothing",
        'type' => "radio",
        'options' => array(
            'filter-nothing' => __('Nothing', 'sakurairo'), 
            'filter-undertint' => __('Undertint', 'sakurairo'), 
            'filter-dim' => __('Dim', 'sakurairo'), 
            'filter-grid' => __('Grid', 'sakurairo'), 
            'filter-dot' => __('Dot', 'sakurairo'), 
        ));

    $options[] = array(
        'name' => __('Announcement', 'sakurairo'),
        'desc' => __('Default off, check on', 'sakurairo'), 
        'id' => 'head_notice',
        'std' => '0',
        'type' => 'checkbox');
    
    $options[] = array(
        'name' => __('Announcement Content', 'sakurairo'),
        'desc' => __('Announcement content, the text exceeds 142 bytes will be scrolled display (mobile device is invalid)', 'sakurairo'), 
        'id' => 'notice_title',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Focus Area', 'sakurairo'), 
        'desc' => __('Default on', 'sakurairo'),
        'id' => 'focus-area',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Focus Area Style', 'sakurairo'), 
        'id' => 'focus-area-style',
        'std' => "left_and_right",
        'type' => "radio",
        'options' => array(
            'left_and_right' => __('Alternate left and right', 'sakurairo'), 
            'bottom_to_top' => __('From bottom to top', 'sakurairo'), 
        ));

    $options[] = array(
        'name' => __('Focus Area Title', 'sakurairo'), 
        'desc' => __('Default is 聚焦, you can also change it to other, of course you can\'t use it as an advertisement!Not allowed!!', 'sakurairo'), 
        'id' => 'focus-area-title',
        'std' => '聚焦',
        'class' => 'mini',
        'type' => 'text');

    $options[] = array(
        'name' => __('Focus Area First Image', 'sakurairo'), 
        'desc' => __('size 257px*160px', 'sakurairo'), 
        'id' => 'feature1_img',
        'std' => "https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/temp.png",
        'type' => 'upload');

    $options[] = array(
        'name' => __('Focus Area First Title', 'sakurairo'), 
        'desc' => __('Focus Area First Title', 'sakurairo'), 
        'id' => 'feature1_title',
        'std' => 'First Focus',
        'type' => 'text');

    $options[] = array(
        'name' => __('Focus Area First Description', 'sakurairo'), 
        'desc' => __('Focus Area First Description', 'sakurairo'), 
        'id' => 'feature1_description',
        'std' => 'This is a brief introduction of the focus area',
        'type' => 'text');

    $options[] = array(
        'name' => __('Focus Area First Link', 'sakurairo'), 
        'desc' => __('Focus Area First Link', 'sakurairo'), 
        'id' => 'feature1_link',
        'std' => '#',
        'type' => 'text');

    $options[] = array(
        'name' => __('Focus Area Second Image', 'sakurairo'), 
        'desc' => __('size 257px*160px', 'sakurairo'), 
        'id' => 'feature2_img',
        'std' => "https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/temp.png",
        'type' => 'upload');

    $options[] = array(
        'name' => __('Focus Area Second Title', 'sakurairo'),
        'desc' => __('Focus Area Second Title', 'sakurairo'), 
        'id' => 'feature2_title',
        'std' => 'Second Focus',
        'type' => 'text');

    $options[] = array(
        'name' => __('Focus Area Second Description', 'sakurairo'), 
        'desc' => __('Focus Area Second Description', 'sakurairo'), 
        'id' => 'feature2_description',
        'std' => 'This is a brief introduction of the focus area',
        'type' => 'text');

    $options[] = array(
        'name' => __('Focus Area Second Link', 'sakurairo'), 
        'desc' => __('Focus Area Second Link', 'sakurairo'), 
        'id' => 'feature2_link',
        'std' => '#',
        'type' => 'text');

    $options[] = array(
        'name' => __('Focus Area Third Image', 'sakurairo'), 
        'desc' => __('size 257px*160px', 'sakurairo'), 
        'id' => 'feature3_img',
        'std' => "https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/temp.png",
        'type' => 'upload');

    $options[] = array(
        'name' => __('Focus Area Third Title', 'sakurairo'), 
        'desc' => __('Focus Area Third Title', 'sakurairo'), 
        'id' => 'feature3_title',
        'std' => 'Third Focus',
        'type' => 'text');

    $options[] = array(
        'name' => __('Focus Area Third Description', 'sakurairo'), 
        'desc' => __('Focus Area Third Description', 'sakurairo'), 
        'id' => 'feature3_description',
        'std' => 'This is a brief introduction of the focus area',
        'type' => 'text');

    $options[] = array(
        'name' => __('Focus Area Third Link', 'sakurairo'), 
        'desc' => __('Focus Area Third Link', 'sakurairo'), 
        'id' => 'feature3_link',
        'std' => '#',
        'type' => 'text');

    $options[] = array(
        'name' => __('Main page article title', 'sakurairo'), 
        'desc' => __('Default is 記事, you can also change it to other, of course you can\'t use it as an advertisement!Not allowed!!', 'sakurairo'), 
        'id' => 'homepage_title',
        'std' => '記事',
        'class' => 'mini',
        'type' => 'text');

    $options[] = array(
        'name' => __('Home page article details Icon Switch', 'sakurairo'), 
        'desc' => __('Default on, check off', 'sakurairo'),
        'id' => 'hpage-art-dis',
        'std' => '0',
        'type' => 'checkbox');

    //文章页
    $options[] = array(
        'name' => __('Post', 'sakurairo'), 
        'type' => 'heading');

    $options[] = array(
        'name' => __('Post-Setting', 'sakurairo'), 
        'desc' => __(' ', 'sakurairo'), 
        'id' => 'setting_post',
        'std' => 'tag',
        'type' => "images",
        'options' => array(
            'tag' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img@latest/setting-img/Set-Post.png',
                ),
            );         
    
    $options[] = array(
        'name' => __('Post style', 'sakurairo'), 
        'id' => 'entry_content_theme',
        'std' => "sakurairo",
        'type' => "radio",
        'options' => array(
            'sakurairo' => __('Default Style', 'sakurairo'), 
            'github' => __('GitHub Style', 'sakurairo'),
        ));

    $options[] = array(
        'name' => __('Post like', 'sakurairo'), 
        'id' => 'post_like',
        'std' => "yes",
        'type' => "radio",
        'options' => array(
            'yes' => __('Open', 'sakurairo'), 
            'no' => __('Close', 'sakurairo'), 
        ));

    $options[] = array(
        'name' => __('Post share', 'sakurairo'), 
        'id' => 'post_share',
        'std' => "yes",
        'type' => "radio",
        'options' => array(
            'yes' => __('Open', 'sakurairo'), 
            'no' => __('Close', 'sakurairo'), 
        ));

    $options[] = array(
        'name' => __('Previous and Next', 'sakurairo'), 
        'id' => 'post_nepre',
        'std' => "yes",
        'type' => "radio",
        'options' => array(
            'yes' => __('Open', 'sakurairo'), 
            'no' => __('Close', 'sakurairo'), 
        ));

    $options[] = array(
        'name' => __('Author profile', 'sakurairo'), 
        'id' => 'author_profile',
        'std' => "yes",
        'type' => "radio",
        'options' => array(
            'yes' => __('Open', 'sakurairo'), 
            'no' => __('Close', 'sakurairo'), 
        ));

    $options[] = array(
        'name' => __('Comment shrink', 'sakurairo'), 
        'id' => 'toggle-menu',
        'std' => "yes",
        'type' => "radio",
        'options' => array(
            'yes' => __('Open', 'sakurairo'), 
            'no' => __('Close', 'sakurairo'), 
        ));

    $options[] = array(
        'name' => __('Comment Textarea image', 'sakurairo'),
        'desc' => __('NO image if left this blank', 'sakurairo'), 
        'id' => 'comment-image',
        'type' => 'upload');

    $options[] = array(
        'name' => __('Author information at the end of the paper', 'sakurairo'), 
        'desc' => __('Check to enable', 'sakurairo'), 
        'id' => 'show_authorprofile',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Post lincenses', 'sakurairo'), 
        'desc' => __('Check close', 'sakurairo'), 
        'id' => 'post-lincenses',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Alipay reward', 'sakurairo'), 
        'desc' => __('Alipay qrcode', 'sakurairo'), 
        'id' => 'alipay_code',
        'type' => 'upload');

    $options[] = array(
        'name' => __('Wechat reward', 'sakurairo'), 
        'desc' => __('Wechat qrcode ', 'sakurairo'),
        'id' => 'wechat_code',
        'type' => 'upload');

    //社交选项
    $options[] = array(
        'name' => __('Social', 'sakurairo'), 
        'type' => 'heading');

    $options[] = array(
        'name' => __('Social-Setting', 'sakurairo'), 
        'desc' => __(' ', 'sakurairo'), 
        'id' => 'setting_social',
        'std' => 'tag',
        'type' => "images",
        'options' => array(
            'tag' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img@latest/setting-img/Set-Social.png',
                ),
            );         

    $options[] = array(
        'name' => __('Wechat', 'sakurairo'), 
        'desc' => __('Wechat qrcode', 'sakurairo'), 
        'id' => 'wechat',
        'type' => 'upload');

    $options[] = array(
        'name' => __('Sina Weibo', 'sakurairo'), 
        'desc' => __('Sina Weibo address', 'sakurairo'), 
        'id' => 'sina',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Tencent QQ', 'sakurairo'), 
        'desc' => __('tencent://message/?uin={{QQ number}}. for example, tencent://message/?uin=123456', 'sakurairo'), 
        'id' => 'qq',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Telegram', 'sakurairo'),
        'desc' => __('Telegram link', 'sakurairo'), 
        'id' => 'telegram',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Qzone', 'sakurairo'), 
        'desc' => __('Qzone address', 'sakurairo'), 
        'id' => 'qzone',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('GitHub', 'sakurairo'),
        'desc' => __('GitHub address', 'sakurairo'), 
        'id' => 'github',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Lofter', 'sakurairo'),
        'desc' => __('Lofter address', 'sakurairo'), 
        'id' => 'lofter',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('BiliBili', 'sakurairo'),
        'desc' => __('BiliBili address', 'sakurairo'), 
        'id' => 'bili',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Youku video', 'sakurairo'), 
        'desc' => __('Youku video address', 'sakurairo'), 
        'id' => 'youku',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Netease Cloud Music', 'sakurairo'), 
        'desc' => __('Netease Cloud Music address', 'sakurairo'), 
        'id' => 'wangyiyun',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Twitter', 'sakurairo'),
        'desc' => __('Twitter address', 'sakurairo'), 
        'id' => 'twitter',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Facebook', 'sakurairo'),
        'desc' => __('Facebook address', 'sakurairo'),
        'id' => 'facebook',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Jianshu', 'sakurairo'), 
        'desc' => __('Jianshu address', 'sakurairo'), 
        'id' => 'jianshu',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('CSDN', 'sakurairo'),
        'desc' => __('CSND community address', 'sakurairo'), 
        'id' => 'csdn',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Zhihu', 'sakurairo'), 
        'desc' => __('Zhihu address', 'sakurairo'), 
        'id' => 'zhihu',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Email-name', 'sakurairo'), 
        'desc' => __('The name part of name@domain.com, only the frontend has js runtime environment can get the full address, you can rest assured to fill in', 'sakurairo'), 
        'id' => 'email_name',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Email-domain', 'sakurairo'), 
        'desc' => __('The domain.com part of name@domain.com', 'sakurairo'), 
        'id' => 'email_domain',
        'std' => '',
        'type' => 'text');

    //前台设置
    $options[] = array(
        'name' => __('Foreground', 'sakurairo'),
        'type' => 'heading');

    $options[] = array(
        'name' => __('Foreground-Setting', 'sakurairo'), 
        'desc' => __(' ', 'sakurairo'), 
        'id' => 'setting_fore',
        'std' => 'tag',
        'type' => "images",
        'options' => array(
            'tag' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img@latest/setting-img/Set-Foreground.png',
                ),
            );         

    $options[] = array(
        'name' => __('Foreground switch full mode', 'sakurairo'), 
        'desc' => __('Check on by default, uncheck to switch to simple mode', 'sakurairo'), 
        'id' => 'full-mode',
        'std' => '1',
        'type' => 'checkbox');        
    
    $options[] = array(
        'name' => __('Extra background switching(Heart-shaped icon)', 'sakurairo'), 
        'desc' => __('Check on by default', 'sakurairo'), 
        'id' => 'extra-bg',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Extra background switching(Star-shaped icon)', 'sakurairo'), 
        'desc' => __('Check on by default', 'sakurairo'), 
        'id' => 'extra-bg2',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Extra background switching(Square-shaped icon)', 'sakurairo'), 
        'desc' => __('Check on by default', 'sakurairo'), 
        'id' => 'extra-bg3',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Extra background switching(Lemon-shaped icon)', 'sakurairo'), 
        'desc' => __('Check on by default', 'sakurairo'), 
        'id' => 'extra-bg4',
        'std' => '1',
        'type' => 'checkbox');
            
    $options[] = array(
        'name' => __('Default Foreground Background', 'sakurairo'), 
        'desc' => __('Default foreground background, fill in URL', 'sakurairo'), 
        'id' => 'sakura_skin_bg1',
        'std' => 'none',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('DIY(heart-shaped icon) foreground background', 'sakurairo'), 
        'desc' => __('DIY(heart-shaped icon) foreground background, fill in URL', 'sakurairo'), 
        'id' => 'sakura_skin_bg2',
        'std' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/bg1.png',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('DIY(Star-shaped icon) foreground background', 'sakurairo'), 
        'desc' => __('DIY(Star-shaped icon) foreground background, fill in URL', 'sakurairo'), 
        'id' => 'sakura_skin_bg3',
        'std' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/bg2.png',
        'type' => 'text');
 
    $options[] = array(
        'name' => __('DIY(Square-shaped icon) foreground background', 'sakurairo'), 
        'desc' => __('DIY(Square-shaped icon) foreground background, fill in URL', 'sakurairo'), 
        'id' => 'sakura_skin_bg4',
        'std' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/bg3.png',
        'type' => 'text');

    $options[] = array(
        'name' => __('DIY(Lemon-shaped icon) foreground background', 'sakurairo'), 
        'desc' => __('DIY(Lemon-shaped icon) foreground background, fill in URL', 'sakurairo'), 
        'id' => 'sakura_skin_bg5',
        'std' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/bg4.png',
        'type' => 'text');

    $options[] = array(
        'name' => __('Foreground transparency', 'sakurairo'),
        'desc' => __('Fill in numbers between 0.1 and 1', 'sakurairo'), 
        'id' => 'homepagebgtmd',
        'std' => '0.8',
        'class' => 'mini',
        'type' => 'text');

    $options[] = array(
        'name' => __('Homepage animation', 'sakurairo'), 
        'desc' => __('Check open', 'sakurairo'), 
        'id' => 'homepage-ani',
        'std' => '1',
        'type' => 'checkbox');
            
    $options[] = array(
        'name' => __('Article title line animation', 'sakurairo'), 
        'desc' => __('Check open', 'sakurairo'), 
        'id' => 'title-line',
        'std' => '0',
        'type' => 'checkbox');
        
    $options[] = array(
        'name' => __('Article title animation', 'sakurairo'), 
        'desc' => __('Check open', 'sakurairo'), 
        'id' => 'title-ani',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Homepage animation Time', 'sakurairo'),
        'desc' => __('Fill in Number', 'sakurairo'),
        'id' => 'hp-ani-t',
        'std' => '2',
        'class' => 'mini',
        'type' => 'text');

    $options[] = array(
        'name' => __('Article title animation Time', 'sakurairo'),
        'desc' => __('Fill in Number', 'sakurairo'),
        'id' => 'title-ani-t',
        'std' => '2',
        'class' => 'mini',
        'type' => 'text');

    $options[] = array(
        'name' => __('Close the front desk login entry', 'sakurairo'), 
        'desc' => __('Check off by default', 'sakurairo'), 
        'id' => 'user-avatar',
        'std' => '0',
        'type' => 'checkbox'); 

    $options[] = array(
        'name' => __('Search Icon Normal Size', 'sakurairo'), 
        'desc' => __('Check off by default', 'sakurairo'), 
        'id' => 'search-ico',
        'std' => '0',
        'type' => 'checkbox');   

    $options[] = array(
        'name' => __('Footer float music player', 'sakurairo'), 
        'desc' => __('Choose which platform you\'ll use.', 'sakurairo'),
        'id' => 'aplayer_server',
        'std' => "netease",
        'type' => "select",
        'options' => array(
            'netease' => __('Netease Cloud Music (default)', 'sakurairo'),
            'xiami' => __('Xiami Music', 'sakurairo'),
            'kugou' => __('KuGou Music (may fail)', 'sakurairo'),
            'baidu' => __('Baidu Music（Overseas server does not support）', 'sakurairo'),
            'tencent' => __('QQ Music (may fail) ', 'sakurairo'),
            'off' => __('Off', 'sakurairo'),
        ));
    
    $options[] = array(
        'name' => __('Song list ID', 'sakurairo'),
        'desc' => __('Fill in the "song list" ID, eg: https://music.163.com/#/playlist?id=3124382377 The ID is 3124382377', 'sakurairo'),
        'id' => 'aplayer_playlistid',
        'std' => '3124382377',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Netease Cloud Music cookie', 'sakurairo'),
        'desc' => __('For Netease Cloud Music, fill in your vip account\'s cookies if you want to play special tracks.<b>If you don\'t know what does mean, left it blank.</b>', 'sakurairo'),
        'id' => 'aplayer_cookie',
        'std' => '',
        'type' => 'textarea');
    
    $options[] = array(
        'name' => __('Pjax Refresh', 'sakurairo'), 
        'desc' => __('Check on, and the principle is the same as Ajax.', 'sakurairo'), 
        'id' => 'poi_pjax',
        'std' => '0',
        'type' => 'checkbox');
    
    $options[] = array(
        'name' => __('NProgress progress bar', 'sakurairo'), 
        'desc' => __('Check on by default', 'sakurairo'), 
        'id' => 'nprogress_on',
        'std' => '1',
        'type' => 'checkbox');
    
    $options[] = array(
        'name' => __('sidebar widget', 'sakurairo'), 
        'desc' => __('Default off, check on', 'sakurairo'), 
        'id' => 'sakura_widget',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('sidebar widget background image', 'sakurairo'), 
        'desc' => __('Sidebar widget background settings, fill in the URL', 'sakurairo'), 
        'id' => 'sakura_widget_bg',
        'std' => '',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('More JavaScript', 'sakurairo'), 
        'desc' => __('check on', 'sakurairo'), 
        'id' => 'addmorejs',
        'std' => '0',
        'type' => 'checkbox');
        
    $options[] = array(
        'name' => __('More JavaScript Url', 'sakurairo'), 
        'desc' => __('Add More JavaScript Url, Fill in the URL', 'sakurairo'), 
        'id' => 'addmorejsurl',
        'std' => '',
        'type' => 'text');

    //后台设置
    $options[] = array(
        'name' => __('Backstage', 'sakurairo'), 
        'type' => 'heading');

    $options[] = array(
        'name' => __('Backstage-Setting', 'sakurairo'), 
        'desc' => __(' ', 'sakurairo'), 
        'id' => 'setting_back',
        'std' => 'tag',
        'type' => "images",
        'options' => array(
            'tag' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img@latest/setting-img/Set-Backstage.png',
                ),
            );         

    $options[] = array(
        'name' => __('Backstage Background Image', 'sakurairo'), 
        'desc' => __('Backstage Background Image', 'sakurairo'), 
        'id' => 'admin_menu_bg',
        'std' => "https://view.moezx.cc/images/2018/01/03/sakura.png",
        'type' => 'upload');

    $options[] = array(
        'name' => __('Login interface background image', 'sakurairo'), 
        'desc' => __('Use the default image if left this blank', 'sakurairo'), 
        'id' => 'login_bg',
        'type' => 'upload');
        
    $options[] = array(
        'name' => __('Background Virtualization of Login Interface', 'sakurairo'), 
        'desc' => __('It is off by default and enabled by check', 'sakurairo'), 
        'id' => 'login_blur',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Login interface logo', 'sakurairo'), 
        'desc' => __('Used for login interface display', 'sakurairo'),
        'id' => 'logo_img',
        'std' => "https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/Login-Logo.png",
        'type' => 'upload');

    $options[] = array(
        'name' => __('Login/registration related settings', 'sakurairo'), 
        'desc' => __(' ', 'space', 'sakurairo'),
        'id' => 'login_tip',
        'std' => '',
        'type' => 'typography ');

    $options[] = array(
        'name' => __('Specify login address', 'sakurairo'), 
        'desc' => __('Forcibly do not use the background address to log in, fill in the new landing page address, such as http://www.xxx.com/login [Note] Before you fill out, test your new page can be opened normally, so as not to enter the background or other problems happening', 'sakurairo'), /*强制不使用后台地址登陆，填写新建的登陆页面地址，比如 http://www.xxx.com/login【注意】填写前先测试下你新建的页面是可以正常打开的，以免造成无法进入后台等情况*/
        'id' => 'exlogin_url',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Specify registered address', 'sakurairo'), 
        'desc' => __('This link is used on the login page as a registration entry', 'sakurairo'), 
        'id' => 'exregister_url',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Allow users to register', 'sakurairo'), 
        'desc' => __('Check to allow users to register at the frontend', 'sakurairo'), 
        'id' => 'ex_register_open',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Automatically redirect after login', 'sakurairo'), 
        'desc' => __('After checken, the administrator redirects to the background and the user redirects to the home page.', 'sakurairo'), 
        'id' => 'login_urlskip',
        'std' => '0',
        'type' => 'checkbox');
    
    $options[] = array(
        'name' => __('Login Validation', 'sakurairo'), 
        'desc' => __('Check to enable slide verification', 'sakurairo'), 
        'id' => 'login_pf',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Registration verification (frontend only, backend forced open)', 'sakurairo'), 
        'desc' => __('Check to enable slide verification', 'sakurairo'), 
        'id' => 'login_validate',
        'std' => '0',
        'type' => 'checkbox');

    //进阶
    $options[] = array(
        'name' => __('Advanced', 'sakurairo'),
        'type' => 'heading');

    $options[] = array(
        'name' => __('Advanced-Setting', 'sakurairo'), 
        'desc' => __(' ', 'sakurairo'), 
        'id' => 'setting_adv',
        'std' => 'tag',
        'type' => "images",
        'options' => array(
            'tag' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img@latest/setting-img/Set-Advanced.png',
                ),
            );         
    
    $options[] = array(
        'name' => __('Use the front-end library locally (lib.js、lib.css)', 'sakurairo'), 
        'desc' => __('The front-end library don\'t load from jsDelivr, not recommand', 'sakurairo'), 
        'id' => 'jsdelivr_cdn_test',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Use js and css file of the theme (sakura-app.js、style.css) locally', 'sakurairo'), 
        'desc' => __('The js and css files of the theme do not load from jsDelivr, please open when DIY', 'sakurairo'),
        'id' => 'app_no_jsdelivr_cdn',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Gravatar avatar proxy', 'sakurairo'),
        'desc' => __('A front-ed proxy for Gravatar, eg. sdn.geekzu.org/avatar . Leave it blank if you do not need.', 'sakurairo'),
        'id' => 'gravatar_proxy',
        'std' => "sdn.geekzu.org/avatar",
        'type' => "text");    

    $options[] = array(
        'name' => __('Images CDN', 'sakurairo'), 
        'desc' => __('Note: Fill in the format http(s)://your CDN domain name/. <br>In other words, the original path is http://your.domain/wp-content/uploads/2018/05/xx.png and the picture will load from http://your CDN domain/2018/05/xx.png', 'sakurairo'), 
        'id' => 'qiniu_cdn',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Google analytics', 'sakurairo'), 
        'desc' => __('UA-xxxxx-x', 'sakurairo'),
        'id' => 'google_analytics_id',
        'std' => '',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('CNZZ Statistics (not recommand)', 'sakurairo'), 
        'desc' => __('Statistics code, which will be invisible in web page.', 'sakurairo'), 
        'id' => 'site_statistics',
        'std' => '',
        'type' => 'textarea');
    
    $options[] = array(
        'name' => __('Customize CSS styles', 'sakurairo'), 
        'desc' => __('Fill in the CSS code directly, no need to write style tags', 'sakurairo'), 
        'id' => 'site_custom_style',
        'std' => '',
        'type' => 'textarea');

    $options[] = array(
        'name' => __('The categories of articles that don\'t not show on homepage', 'sakurairo'), 
        'desc' => __('Fill in category ID, multiple IDs are divided by a comma ","', 'sakurairo'), 
        'id' => 'classify_display',
        'std' => '',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Images category', 'sakurairo'), 
        'desc' => __('Fill in category ID, multiple IDs are divided by a comma ","', 'sakurairo'), 
        'id' => 'image_category',
        'std' => '',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Version Control', 'sakurairo'), 
        'desc' => __('Used to update frontend cookies and browser caches, any string can be used', 'sakurairo'), 
        'id' => 'cookie_version',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Time Zone adjustment', 'sakurairo'), 
        'desc' => __('If the comment has a time difference problem adjust here, fill in an integer, the calculation method: actual_time = display_error_time - the_integer_you_entered (unit: hour)', 'sakurairo'), 
        'id' => 'time_zone_fix',
        'std' => '0',
        'class' => 'mini',
        'type' => 'text');

   /* 
   $options[] = array(
        'name' => __('BaguetteBox Funtion', 'sakurairo'), 
        'desc' => __('Default off，<a href="https://github.com/mashirozx/Sakura/wiki/Fancybox">please read wiki</a>', 'sakurairo'), 
        'id' => 'image_viewer',
        'std' => '0',
        'type' => 'checkbox');
   */

    //功能
    $options[] = array(
        'name' => __('Function', 'sakurairo'), 
        'type' => 'heading');

    $options[] = array(
        'name' => __('Function-Setting', 'sakurairo'), 
        'desc' => __(' ', 'sakurairo'), 
        'id' => 'setting_func',
        'std' => 'tag',
        'type' => "images",
        'options' => array(
            'tag' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img@latest/setting-img/Set-Function.png',
                ),
            );     

    $options[] = array(
        'name' => __('Bilibili UID', 'sakurairo'), 
        'desc' => __('Fill in your UID, eg.https://space.bilibili.com/13972644/, only fill in with the number part.', 'sakurairo'),
        'id' => 'bilibili_id',
        'std' => '13972644',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Bilibili Cookie', 'sakurairo'), 
        'desc' => __('Fill in your Cookies, go to your bilibili homepage, you can get cookies in brownser network pannel with pressing F12. If left this blank, you\'ll not get the progress.', 'sakurairo'),
        'id' => 'bilibili_cookie',
        'std' => 'LIVE_BUVID=',
        'type' => 'textarea');

    $options[] = array(
        'name' => __('Statistics Interface', 'sakurairo'), 
        'id' => 'statistics_api',
        'std' => "theme_build_in",
        'type' => "radio",
        'options' => array(
            'wp_statistics' => __('WP-Statistics plugin (Professional statistics, can exclude invalid access)', 'sakurairo'), 
            'theme_build_in' => __('Theme built-in (simple statistics, calculate each page access request)', 'sakurairo'), 
        ));

    $options[] = array(
        'name' => __('Statistical data display format', 'sakurairo'), 
        'id' => 'statistics_format',
        'std' => "type_1",
        'type' => "radio",
        'options' => array(
            'type_1' => __('23333 Views (default)', 'sakurairo'), 
            'type_2' => __('23,333 Views (britain)', 'sakurairo'), 
            'type_3' => __('23 333 Views (french)', 'sakurairo'), 
            'type_4' => __('23k Views (chinese)', 'sakurairo'), 
        ));

    $options[] = array(
        'name' => __('Comment image upload API', 'sakurairo'), 
        'id' => 'img_upload_api',
        'std' => "imgur",
        'type' => "radio",
        'options' => array(
            'imgur' => __('Imgur (https://imgur.com)', 'sakurairo'),
            'smms' => __('SM.MS (https://sm.ms)', 'sakurairo'),
            'chevereto' => __('Chevereto (https://chevereto.com)', 'sakurairo'),
        ));

    $options[] = array(
        'name' => __('Imgur Client ID', 'sakurairo'),
        'desc' => __('Register your application <a href="https://api.imgur.com/oauth2/addclient">here</a>, note we only need the Client ID here.', 'sakurairo'),
        'id' => 'imgur_client_id',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('SM.MS Secret Token', 'sakurairo'),
        'desc' => __('Register your application <a href="https://sm.ms/home/apitoken">here</a>.', 'sakurairo'),
        'id' => 'smms_client_id',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Chevereto API v1 key', 'sakurairo'),
        'desc' => __('Get your API key here: ' . akina_option('cheverto_url') . '/dashboard/settings/api', 'sakurairo'),
        'id' => 'chevereto_api_key',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Chevereto URL', 'sakurairo'),
        'desc' => __('Your Chevereto homepage url, no slash in the end, eg. https://your.cherverto.com', 'sakurairo'),
        'id' => 'cheverto_url',
        'std' => 'https://your.cherverto.com',
        'type' => 'text');

    $options[] = array(
        'name' => __('Comment images proxy', 'sakurairo'),
        'desc' => __('A front-ed proxy for the uploaded images. Leave it blank if you do not need.', 'sakurairo'),
        'id' => 'cmt_image_proxy',
        'std' => 'https://images.weserv.nl/?url=',
        'type' => 'text');

    $options[] = array(
        'name' => __('Imgur upload proxy', 'sakurairo'),
        'desc' => __('A back-ed proxy to upload images. You may set a self hosted proxy with Nginx, following my <a href="https://2heng.xin/2018/06/06/javascript-upload-images-with-imgur-api/">turtal</a>. This feature is mainly for Chinese who cannot access to Imgur due to the GFW. The default and official setting is 【<a href="https://api.imgur.com/3/image/">https://api.imgur.com/3/image/</a>】', 'sakurairo'),
        'id' => 'imgur_upload_image_proxy',
        'std' => 'https://api.imgur.com/3/image/',
        'type' => 'text');

    $options[] = array(
        'name' => __('Search background customization', 'sakurairo'), 
        'desc' => __('It is the cute one that opens the search interface', 'sakurairo'), 
        'id' => 'search-image',
        'std' => "https://cdn.jsdelivr.net/gh/moezx/cdn@3.2.1/img/other/iloli.gif",
        'type' => 'upload');

    $options[] = array(
        'name' => __('Real Time Search Function', 'sakurairo'), 
        'desc' => __('Real-time search in the foreground, call the Rest API to update the cache every hour, you can manually set the cache time in api.php', 'sakurairo'), 
        'id' => 'live_search',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Include Comments in Real Time Search', 'sakurairo'), 
        'desc' => __('Search for comments in real-time search (not recommended if there are too many comments on the site)', 'sakurairo'), 
        'id' => 'live_search_comment',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Article Page Lazyload Function', 'sakurairo'), 
        'desc' => __('Default on', 'sakurairo'), 
        'id' => 'lazyload',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('lazyload spinner', 'sakurairo'),
        'desc' => __('The placeholder to display when the image loads, fill in the image url', 'sakurairo'), 
        'id' => 'lazyload_spinner',
        'std' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/loadimg/inload.svg',
        'type' => 'text');

    $options[] = array(
        'name' => __('Clipboard Copyright ID', 'sakurairo'), 
        'desc' => __('Check on by default. When copying more than 30 bytes, the copyright mark will be added to the clipboard automatically.', 'sakurairo'), 
        'id' => 'clipboard_copyright',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Email address prefix', 'sakurairo'), 
        'desc' => __('For sending system mail, the sender address displayed in the user\'s mailbox, do not use Chinese, the default system email address is bibi@your_domain_name', 'sakurairo'),  
        'id' => 'mail_user_name',
        'std' => 'bibi',
        'type' => 'text');

    $options[] = array(
        'name' => __('Comments reply notification', 'sakurairo'), 
        'desc' => __('WordPress will use email to notify users when their comments receive a reply by default. Tick this item allows users to set their own comments reply notification', 'sakurairo'), 
        'id' => 'mail_notify',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Administrator comment notification', 'sakurairo'), 
        'desc' => __('Whether to use email notification when the administrator\'s comments receive a reply', 'sakurairo'), 
        'id' => 'admin_notify',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Private Comment Function', 'sakurairo'), 
        'desc' => __('It is not checked by default. It is checked to enable. This feature allows users to set their own comments invisible to others', 'sakurairo'), 
        'id' => 'open_private_message',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Human verification', 'sakurairo'), 
        'desc' => __('Enable human verification', 'sakurairo'), 
        'id' => 'norobot',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('QQ avatar link encryption', 'sakurairo'), 
        'desc' => __('Do not display the user\'s qq avatar links directly.', 'sakurairo'), 
        'id' => 'qq_avatar_link',
        'std' => "off",
        'type' => "select",
        'options' => array(
            'off' => __('Off (default)', 'sakurairo'), 
            'type_1' => __('use redirect (general security)', 'sakurairo'), 
            'type_2' => __('fetch data at backend (high security)', 'sakurairo'), 
            'type_3' => __('fetch data at backend (high security，slow)', 'sakurairo'), 
        ));

    $options[] = array(
        'name' => __('Comment UA infomation', 'sakurairo'), 
        'desc' => __('Check to enable, display the user\'s browser, operating system information', 'sakurairo'), 
        'id' => 'open_useragent',
        'std' => '0',
        'type' => 'checkbox');

  //增强功能
    $options[] = array(
        'name' => __('Enhanced', 'sakurairo'),
        'type' => 'heading');
 
    $options[] = array(
        'name' => __('Enhanced-Setting', 'sakurairo'), 
        'desc' => __(' ', 'sakurairo'), 
        'id' => 'setting_enhan',
        'std' => 'tag',
        'type' => "images",
        'options' => array(
            'tag' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img@latest/setting-img/Set-Enhanced.png',
                ),
            );     
  
    $options[] = array(
        'name' => __('Preload animation', 'sakurairo'), 
        'desc' => __('Check open', 'sakurairo'), 
        'id' => 'yjzdh',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Cherry Blossom falling effect', 'sakurairo'), 
        'desc' => __('Check open', 'sakurairo'), 
        'id' => 'sakurajs',
        'std' => '1',
        'type' => 'checkbox');
    
    $options[] = array(
        'name' => __('Cherry Blossom falling quantity', 'sakurairo'), 
        'desc' => __('Four kinds of quantity, default native quantity', 'sakurairo'), 
        'id' => 'sakura-falling-quantity',
        'std' => 'native',
        'type' => 'select',
        'options' => array(
            'native' => __('native', 'sakurairo'),
            'quarter' => __('quarter', 'sakurairo'),
            'half' => __('half', 'sakurairo'),
            'less' => __('less', 'sakurairo'),
            ));

    $options[] = array(
        'name' => __('Wave effects', 'sakurairo'), 
        'desc' => __('Check open', 'sakurairo'), 
        'id' => 'bolangcss',
        'std' => '1',
        'type' => 'checkbox');
        
    $options[] = array(
        'name' => __('Footer suspension player default volume', 'sakurairo'), 
        'desc' => __('Maximum 1 minimum 0', 'sakurairo'),
        'id' => 'playlist_mryl',
        'std' => '0.5',
        'class' => 'mini',
        'type' => 'text');

    $options[] = array(
        'name' => __('live2D', 'sakurairo'), 
        'desc' => __('Check open', 'sakurairo'), 
        'id' => 'live2djs',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Live2D Custom Resource Address', 'sakurairo'), 
        'desc' => __('Fill in Live2D Custom Resource Address', 'sakurairo'),
        'id' => 'live2d-custom',
        'std' => 'mirai-mamori',
        'type' => 'text');

    $options[] = array(
        'name' => __('Live2D Custom Resource Version', 'sakurairo'), 
        'desc' => __('Fill in Live2D Custom Resource Version', 'sakurairo'),
        'id' => 'live2d-custom-ver',
        'std' => 'latest',
        'class' => 'mini',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Drop-down arrow', 'sakurairo'), 
        'desc' => __('Check open', 'sakurairo'), 
        'id' => 'godown',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Turn off Drop-down arrow mobile display', 'sakurairo'), 
        'desc' => __('Check by default, cancel opening', 'sakurairo'),
        'id' => 'godown-mb',
        'std' => '1',
        'type' => 'checkbox');
    
    $options[] = array(
        'name' => __('a brief remark', 'sakurairo'), 
        'desc' => __('Check open', 'sakurairo'), 
        'id' => 'oneword',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('One word typing effect of home page', 'sakurairo'), 
        'desc' => __('Check open', 'sakurairo'), 
        'id' => 'dazi',
        'std' => '0',
        'type' => 'checkbox');
    
    $options[] = array(
        'name' => __('Double quotation marks for typing effect', 'sakurairo'), 
        'desc' => __('Check open', 'sakurairo'), 
        'id' => 'dazi_yh',
        'std' => '0',
        'type' => 'checkbox');
    
    $options[] = array(
        'name' => __('Typewriting effect text', 'sakurairo'), 
        'desc' => __('Fill in the text part of the typing effect (double quotation marks must be used outside the text, and English commas shall be used to separate the two sentences. Support for HTML tags)', 'sakurairo'),
        'id' => 'dazi_a',
        'std' => '"寒蝉黎明之时,便是重生之日。"',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Homepage one word blogger description', 'sakurairo'), 
        'desc' => __('A self description', 'sakurairo'), 
        'id' => 'admin_des',
        'std' => '粉色的花瓣，美丽地缠绕在身上。依在风里。',
        'type' => 'textarea');
    
    $options[] = array(
        'name' => __('Blog description at the end of the article', 'sakurairo'), 
        'desc' => __('A self description', 'sakurairo'), 
        'id' => 'admin_destwo',
        'std' => '粉色的花瓣，美丽地缠绕在身上。依在风里。',
        'type' => 'textarea');
    
    $options[] = array(
        'name' => __('Note effects', 'sakurairo'), 
        'desc' => __('Check open', 'sakurairo'), 
        'id' => 'audio',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Logo special effects', 'sakurairo'), 
        'desc' => __('Check open', 'sakurairo'), 
        'id' => 'logocss',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Logo text A', 'sakurairo'), 
        'desc' => __('Fill in the front part of your logo text', 'sakurairo'),
        'id' => 'logo_a',
        'std' => ' ',
        'type' => 'text');
        
    $options[] = array(
        'name' => __('Logo text B', 'sakurairo'), 
        'desc' => __('Fill in the middle part of your logo text', 'sakurairo'),
        'id' => 'logo_b',
        'std' => ' ',
        'type' => 'text');
        
    $options[] = array(
        'name' => __('Logo text C', 'sakurairo'), 
        'desc' => __('Fill in the back of your logo', 'sakurairo'),
        'id' => 'logo_c',
        'std' => ' ',
        'type' => 'text');
        
     $options[] = array(
        'name' => __('Logo secondary text', 'sakurairo'), 
        'desc' => __('Fill in the secondary text of your logo.', 'sakurairo'),
        'id' => 'logo_two',
        'std' => '',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Mail template header', 'sakurairo'), 
        'desc' => __('Set the background picture above your message', 'sakurairo'), 
        'id' => 'mail_img',
        'std' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/loadimg/head.jpg',
        'type' => 'upload');


    $options[] = array(
        'name' => __('Custom mouse style - Standard', 'sakurairo'), 
        'desc' => __('Apply to global, fill in link.', 'sakurairo'),
        'id' => 'cursor-nor',
        'std' => 'https://cdn.jsdelivr.net/gh/moezx/cdn@3.1.9/img/Sakura/cursor/normal.cur',
        'type' => 'text'); 
            
    $options[] = array(
        'name' => __('Custom mouse style - Selected', 'sakurairo'), 
        'desc' => __('Return to the above for PC', 'sakurairo'),
        'id' => 'cursor-no',
        'std' => 'https://cdn.jsdelivr.net/gh/moezx/cdn@3.1.9/img/Sakura/cursor/No_Disponible.cur',
        'type' => 'text');   
            
    $options[] = array(
        'name' => __('Custom mouse style - Selected elements', 'sakurairo'), 
        'desc' => __('Used to select a place', 'sakurairo'),
        'id' => 'cursor-ayu',
        'std' => 'https://cdn.jsdelivr.net/gh/moezx/cdn@3.1.9/img/Sakura/cursor/ayuda.cur',
        'type' => 'text');  
    
    $options[] = array(
        'name' => __('Custom mouse style - Selected text', 'sakurairo'), 
        'desc' => __('Used to select a Text', 'sakurairo'),
        'id' => 'cursor-text',
        'std' => 'https://cdn.jsdelivr.net/gh/moezx/cdn@3.1.9/img/Sakura/cursor/texto.cur',
        'type' => 'text'); 
            
    $options[] = array(
        'name' => __('Custom mouse style - Working state', 'sakurairo'), 
        'desc' => __('Used to working condition', 'sakurairo'),
        'id' => 'cursor-work',
        'std' => 'https://cdn.jsdelivr.net/gh/moezx/cdn@3.1.9/img/Sakura/cursor/work.cur',
        'type' => 'text');   

    //字体
    $options[] = array(
        'name' => __('Fonts', 'sakurairo'),
        'type' => 'heading');

    $options[] = array(
        'name' => __('Fonts-Setting', 'sakurairo'), 
        'desc' => __(' ', 'sakurairo'), 
        'id' => 'setting_fonts',
        'std' => 'tag',
        'type' => "images",
        'options' => array(
            'tag' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img@latest/setting-img/Set-Fonts.png',
                ),
            );     
  
    $options[] = array(
        'name' => __('fontweight', 'sakurairo'),
        'desc' => __('Fill in a number, maximum 900, minimum 100. Between 300 and 500 is recommended.', 'sakurairo'),
        'id' => 'fontweight',
        'std' => '',
        'class' => 'mini',
        'type' => 'text');

    $options[] = array(
        'name' => __('Reference external font', 'sakurairo'), 
        'desc' => __('Check open', 'sakurairo'), 
        'id' => 'refer-ext-font',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('External font address', 'sakurairo'), 
        'desc' => __('Fill in font address.', 'sakurairo'),
        'id' => 'ext-font-address',
        'std' => '',
        'type' => 'text');   

    $options[] = array(
        'name' => __('External font name', 'sakurairo'), 
        'desc' => __('Fill in the font name.', 'sakurairo'),
        'id' => 'ext-font-name',
        'std' => '',
        'type' => 'text'); 

    $options[] = array(
        'name' => __('Google Fonts Api Address', 'sakurairo'), 
        'desc' => __('Fill in Google Fonts API Address', 'sakurairo'),
        'id' => 'gfontsapi',
        'std' => 'fonts.googleapis.com',
        'type' => 'text');

    $options[] = array(
        'name' => __('Google Fonts Name', 'sakurairo'), 
        'desc' => __('Please make sure that the fonts you add can be referenced in Google font library. Fill in the font name. If multiple fonts are referenced, please use "|" as the separator.', 'sakurairo'),
        'id' => 'addfonts',
        'std' => '',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Global Default Font', 'sakurairo'), 
        'desc' => __('Fill in font name', 'sakurairo'),
        'id' => 'global-default-font',
        'std' => '',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Global Font 2', 'sakurairo'), 
        'desc' => __('Fill in font name', 'sakurairo'),
        'id' => 'global-font2',
        'std' => '',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Front Page Title Font', 'sakurairo'), 
        'desc' => __('Fill in font name', 'sakurairo'),
        'id' => 'font-title',
        'std' => '',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Front Page One Word Font', 'sakurairo'), 
        'desc' => __('Fill in font name', 'sakurairo'),
        'id' => 'font-oneword',
        'std' => '',
        'type' => 'text');        
    
    $options[] = array(
        'name' => __('Front page key title font', 'sakurairo'), 
        'desc' => __('Fill in font name', 'sakurairo'),
        'id' => 'keytitlefont',
        'std' => '',
        'type' => 'text');  
    
    $options[] = array(
        'name' => __('Global fontsize', 'sakurairo'), 
        'desc' => __('Fill in Number. Between 10 and 20 is recommended', 'sakurairo'),
        'id' => 'global-fontsize',
        'std' => '',
        'class' => 'mini',
        'type' => 'text'); 
    
    $options[] = array(
        'name' => __('Article Title Size', 'sakurairo'), 
        'desc' => __('Fill in Number. Between 15 and 20 is recommended', 'sakurairo'),
        'id' => 'article-title-size',
        'std' => '',
        'class' => 'mini',
        'type' => 'text'); 
    
    $options[] = array(
        'name' => __('Article Tips Size', 'sakurairo'), 
        'desc' => __('Fill in Number. Between 10 and 15 is recommended', 'sakurairo'),
        'id' => 'article-tips-size',
        'std' => '',
        'class' => 'mini',
        'type' => 'text'); 
    
    $options[] = array(
        'name' => __('Front Page One Word FontSize', 'sakurairo'), 
        'desc' => __('Fill in Number. Between 10 and 20 is recommended', 'sakurairo'),
        'id' => 'fontsize-oneword',
        'std' => '',
        'class' => 'mini',
        'type' => 'text');  

    $options[] = array(
        'name' => __('Font size of the first key title', 'sakurairo'), 
        'desc' => __('Fill in Number. Between 70 and 90 is recommended', 'sakurairo'),
        'id' => 'keytitle_size',
        'std' => '80',
        'class' => 'mini',
        'type' => 'text');  

    $options[] = array(
        'name' => __('Article Page Title Size', 'sakurairo'), 
        'desc' => __('Fill in Number. Between 25 and 30 is recommended', 'sakurairo'),
        'id' => 'article-paget',
        'std' => '',
        'class' => 'mini',
        'type' => 'text'); 

    $options[] = array(
        'name' => __('Logo font link', 'sakurairo'), 
        'desc' => __('When the font is ready, do this again <a href = "https://www.fontke.com/tool/fontface/">@font-face生成器</a>It can generate a bunch of files, which are all useful. It can be placed on a accessible server, OOS, CDN, etc. here, you only need to fill in the CSS style sheet file link <a href = "https://blog.ukenn.top/sakura6/#toc-head-4">Detailed tutorial</a>', 'sakurairo'),
        'id' => 'logo_zt',
        'std' => 'https://cdn.jsdelivr.net/gh/acai66/mydl/fonts/wenyihei/wenyihei-subfont.css',
        'type' => 'text');
        
    $options[] = array(
        'name' => __('Logo font name', 'sakurairo'), 
        'desc' => __('Fill in the font name of your logo, write the name directly without the format suffix', 'sakurairo'),
        'id' => 'logo_ztmc',
        'std' => 'wenyihei-subfont',
        'type' => 'text');

    //主题
    $options[] = array(
        'name' => __('Theme', 'sakurairo'),
        'type' => 'heading');

    $options[] = array(
        'name' => __('Theme-Setting', 'sakurairo'), 
        'desc' => __(' ', 'sakurairo'), 
        'id' => 'setting_theme',
        'std' => 'tag',
        'type' => "images",
        'options' => array(
            'tag' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img@latest/setting-img/Set-Theme.png',
                ),
            );     
  
    $options[] = array(
        'name' => __('Display icon selection', 'sakurairo'), 
        'desc' => __('Choose icon color', 'sakurairo'), 
        'id' => 'webweb_img',
        'std' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/color-img/pink',
        'type' => 'select',
        'options' => array(
            'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/color-img/pink' => __('「Common Colors」Pink（EE9CA7）', 'sakurairo'), 
            'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/color-img/orange' => __('「Common Colors」Orange（FF8000）', 'sakurairo'), 
            'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/color-img/blue' => __('「Nippon Colors」Hanaasagi（1E88A8）', 'sakurairo'), 
            'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/color-img/yellow' => __('「Nippon Colors」Beniukon（E98B2A）', 'sakurairo'), 
            'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/color-img/sangosyu' => __('「Nippon Colors」Sangosyu（F17C67）', 'sakurairo'), 
            'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/color-img/sora' => __('「Nippon Colors」Sora（58B2DC）', 'sakurairo'), 
            'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/color-img/nae' => __('「Nippon Colors」Nae（86C166）', 'sakurairo'), 
            'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/color-img/macaronblue' => __('「Macaron Colors」Blue（B8F1ED）', 'sakurairo'), 
            'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/color-img/macarongreen' => __('「Macaron Colors」Green（B8F1CC）', 'sakurairo'), 
            'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/color-img/macaronpurple' => __('「Macaron Colors」Purple（D9B8F1）', 'sakurairo'), 
            'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/color-img/colorful' => __('「Others」ColorFul', 'sakurairo'), 
    ));
    
    $options[] = array(
        'name' => __('Theme Color', 'sakurairo'), 
        'id' => 'theme_skin',
        'std' => "#FB98C0",
        'desc' => __('Custom theme color', 'sakurairo'), 
        'type' => "color",
    );
    
    $options[] = array(
        'name' => __('Theme Color Matching Color', 'sakurairo'), 
        'id' => 'theme_skinm',
        'std' => "#87B6FA",
        'desc' => __('Custom theme color', 'sakurairo'), 
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Theme Light Color Management', 'sakurairo'),
        'id' => 'light-cmanage',
        'std' => "sep",
        'type' => "radio",
        'options' => array(
            'mer' => __('Merge Options', 'sakurairo'), 
            'sep' => __('Separation Options', 'sakurairo'), 
        ));

    $options[] = array(
        'name' => __('Theme Color (Light)', 'sakurairo'), 
        'id' => 'light-color',
        'std' => "#FFE1ED",
        'desc' => __('Custom theme color', 'sakurairo'), 
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Theme Color (Dark mode)', 'sakurairo'), 
        'id' => 'theme_dark',
        'std' => "#BD144A",
        'desc' => __('Custom theme color', 'sakurairo'), 
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Automatic Switching Dark Mode (22:00-7:00)', 'sakurairo'), 
        'desc' => __('Check open', 'sakurairo'), 
        'id' => 'darkmode',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Image Brightness in Dark Mode', 'sakurairo'),
        'desc' => __('Fill in Number', 'sakurairo'),
        'id' => 'dark_imgbri',
        'std' => '0.8',
        'class' => 'mini',
        'type' => 'text');     

    $options[] = array(
        'name' => __('Dark Mode Widget Transparency', 'sakurairo'), 
        'id' => 'dark-widget-tmd',
        'std' => "0.7",
        'desc' => __('Fill in the alpha value from 0 to 1 here', 'sakurairo'), 
        'type' => "text",
        'class' => 'mini',
    );

    $options[] = array(
        'name' => __('Information Bar Background Color (RGBA) Red', 'sakurairo'), 
        'id' => 'infor-bar-bg-cr',
        'std' => "255",
        'desc' => __('Fill in the red value from 0 to 255 here', 'sakurairo'), 
        'type' => "text",
        'class' => 'mini',
    );

    $options[] = array(
        'name' => __('Information Bar Background Color (RGBA) Green', 'sakurairo'), 
        'id' => 'infor-bar-bg-cg',
        'std' => "255",
        'desc' => __('Fill in the green value from 0 to 255 here', 'sakurairo'), 
        'type' => "text",
        'class' => 'mini',
    );

    $options[] = array(
        'name' => __('Information Bar Background Color (RGBA) Blue', 'sakurairo'), 
        'id' => 'infor-bar-bg-cb',
        'std' => "255",
        'desc' => __('Fill in the blue value from 0 to 255 here', 'sakurairo'), 
        'type' => "text",
        'class' => 'mini',
    );

    $options[] = array(
        'name' => __('Information Bar Background Color (RGBA) Alpha', 'sakurairo'), 
        'id' => 'infor-bar-bg-ca',
        'std' => "0.8",
        'desc' => __('Fill in the alpha value from 0 to 1 here', 'sakurairo'), 
        'type' => "text",
        'class' => 'mini',
    );

    $options[] = array(
        'name' => __('Foreground Menu background Color (RGBA) Red', 'sakurairo'), 
        'id' => 'fore-switch-bcr',
        'std' => "255",
        'desc' => __('Fill in the red value from 0 to 255 here', 'sakurairo'), 
        'type' => "text",
        'class' => 'mini',
    );

    $options[] = array(
        'name' => __('Foreground Menu background Color (RGBA) Green', 'sakurairo'), 
        'id' => 'fore-switch-bcg',
        'std' => "255",
        'desc' => __('Fill in the green value from 0 to 255 here', 'sakurairo'), 
        'type' => "text",
        'class' => 'mini',
    );

    $options[] = array(
        'name' => __('Foreground Menu background Color (RGBA) Blue', 'sakurairo'), 
        'id' => 'fore-switch-bcb',
        'std' => "255",
        'desc' => __('Fill in the blue value from 0 to 255 here', 'sakurairo'), 
        'type' => "text",
        'class' => 'mini',
    );

    $options[] = array(
        'name' => __('Foreground Menu background Color (RGBA) Alpha', 'sakurairo'), 
        'id' => 'fore-switch-bca',
        'std' => "0.8",
        'desc' => __('Fill in the alpha value from 0 to 1 here', 'sakurairo'), 
        'type' => "text",
        'class' => 'mini',
    );

    $options[] = array(
        'name' => __('Foreground selection menu background color (Light colors are recommended)', 'sakurairo'), 
        'id' => 'fore-switch-sele-bc',
        'std' => "#FFE1ED",
        'desc' => __('Custom colors', 'sakurairo'), 
        'type' => "color",
    );
    
    $options[] = array(
        'name' => __('Home page article separator (Light colors are recommended)', 'sakurairo'), 
        'id' => 'hpage-art-sc',
        'std' => "#FFE1ED",
        'desc' => __('Custom colors', 'sakurairo'), 
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Home page article time prompt emphasize background (Light colors are recommended)', 'sakurairo'), 
        'id' => 'hpage-art-tpebc',
        'std' => "#FFE1ED",
        'desc' => __('Custom colors', 'sakurairo'), 
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Home page article border shadow (Light colors are recommended)', 'sakurairo'), 
        'id' => 'hpage-art-bsc',
        'std' => "#FFE1ED",
        'desc' => __('Custom colors', 'sakurairo'), 
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Drop down arrow color (Light colors are recommended)', 'sakurairo'), 
        'id' => 'godown_skin',
        'std' => "#FFF",
        'desc' => __('Custom colors', 'sakurairo'), 
        'type' => "color",
    );  

    $options[] = array(
        'name' => __('Home page article time prompt accent (Theme colors are recommended)', 'sakurairo'), 
        'id' => 'hpage-art-tpac',
        'std' => "#FB98C0",
        'desc' => __('Custom colors', 'sakurairo'), 
        'type' => "color",
    );
    
    $options[] = array(
        'name' => __('Home page article Prompt Icon Color (Theme colors are recommended)', 'sakurairo'), 
        'id' => 'hpage-art-pic',
        'std' => "#FB98C0",
        'desc' => __('Custom colors', 'sakurairo'), 
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Home page Focus background color (RGBA) Red', 'sakurairo'), 
        'id' => 'hpage-focus-bcr',
        'std' => "255",
        'desc' => __('Fill in the red value from 0 to 255 here', 'sakurairo'), 
        'type' => "text",
        'class' => 'mini',
    );

    $options[] = array(
        'name' => __('Home page Focus background color (RGBA) Green', 'sakurairo'), 
        'id' => 'hpage-focus-bcg',
        'std' => "225",
        'desc' => __('Fill in the green value from 0 to 255 here', 'sakurairo'), 
        'type' => "text",
        'class' => 'mini',
    );

    $options[] = array(
        'name' => __('Home page Focus background color (RGBA) Blue', 'sakurairo'), 
        'id' => 'hpage-focus-bcb',
        'std' => "237",
        'desc' => __('Fill in the blue value from 0 to 255 here', 'sakurairo'), 
        'type' => "text",
        'class' => 'mini',
    );

    $options[] = array(
        'name' => __('Home page Focus background color (RGBA) Alpha', 'sakurairo'), 
        'id' => 'hpage-focus-bca',
        'std' => "0.7",
        'desc' => __('Fill in the alpha value from 0 to 1 here', 'sakurairo'), 
        'type' => "text",
        'class' => 'mini',
    );

    $options[] = array(
        'name' => __('Home page key title font color', 'sakurairo'), 
        'id' => 'hpage-ket-tfc',
        'std' => "#FFF",
        'desc' => __('Custom theme color', 'sakurairo'), 
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Preload animation color A', 'sakurairo'), 
        'id' => 'preload-ani-c1',
        'std' => "#FFE1ED",
        'desc' => __('Custom colors', 'sakurairo'), 
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Preload animation color B', 'sakurairo'), 
        'id' => 'preload-ani-c2',
        'std' => "#FB98C0",
        'desc' => __('Custom colors', 'sakurairo'), 
        'type' => "color",
    );

    //后台面板自定义配色方案
    $options[] = array(
        'name' => __('Dashboard panel custom color scheme', 'sakurairo'), 
        'desc' => __('You can design the dashboard panel (/wp-admin/) style yourself below, but before you start, please go to <a href="/wp-admin/profile.php">here</a> to change the color scheme to custom.(Custom).<br><b>Tip: </b>How to match colors? Maybe <a href="https://mashiro.top/color-thief/">this</a> can help you.', 'sakurairo'), 
        'id' => 'scheme_tip',
        'std' => '',
        'type' => 'typography ');

    $options[] = array(
        'name' => __('Panel main color A', 'sakurairo'), 
        'id' => 'dash_scheme_color_a',
        'std' => "#c6742b",
        'desc' => __('Custom color', 'sakurairo'),
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Panel main color B', 'sakurairo'),
        'id' => 'dash_scheme_color_b',
        'std' => "#d88e4c",
        'desc' => __('Custom color', 'sakurairo'),
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Panel main color C', 'sakurairo'),
        'id' => 'dash_scheme_color_c',
        'std' => "#695644",
        'desc' => __('Custom color', 'sakurairo'),
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Panel main color D', 'sakurairo'),
        'id' => 'dash_scheme_color_d',
        'std' => "#a19780",
        'desc' => __('Custom color', 'sakurairo'),
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Panel icon color——base', 'sakurairo'), 
        'id' => 'dash_scheme_color_base',
        'std' => "#e5f8ff",
        'desc' => __('SVG Icons Custom Color', 'sakurairo'),
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Panel icon color——focus', 'sakurairo'),
        'id' => 'dash_scheme_color_focus',
        'std' => "#fff",
        'desc' => __('SVG Icons Custom Color', 'sakurairo'),
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Panel icon color——current', 'sakurairo'),
        'id' => 'dash_scheme_color_current',
        'std' => "#fff",
        'desc' => __('SVG Icons Custom Color', 'sakurairo'),
        'type' => "color",
    );
    
    $options[] = array(
        'name' => __('Backstage Font Color', 'sakurairo'),
        'id' => 'admin_font_skin',
        'std' => "#f3f2f1",
        'desc' => __('Custom color', 'sakurairo'),
        'type' => "color",
    );
    
    $options[] = array(
        'name' => __('Backstage Button Color', 'sakurairo'),
        'id' => 'admin_pb_skin',
        'std' => "#8fbbb1",
        'desc' => __('Custom color', 'sakurairo'),
        'type' => "color",
    );
 
    $options[] = array(
        'name' => __('Other custom panel styles(CSS)', 'sakurairo'), 
        'desc' => __('If you need to adjust other styles of the panel, put the style here.', 'sakurairo'), 
        'id' => 'dash_scheme_css_rules',
        'std' => '',
        'type' => 'textarea');
    
    return $options;
}
