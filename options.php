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
        'name' => __('Basic settings', 'sakurairo'), /*基本设置*/
        'type' => 'heading');

    $options[] = array(
        'name' => __('Support', 'sakurairo'), 
        'desc' => __(' ', 'sakurairo'), 
        'id' => 'theme_support',
        'std' => 'tag',
        'type' => "images",
        'options' => array(
            'tag' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/supportqq.png',
                ),
            );
    
    $options[] = array(
        'name' => __('Site title', 'sakurairo'), /*站点名称*/
        'desc' => __('Mashiro\'s Blog', 'sakurairo'),
        'id' => 'site_name',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Author', 'sakurairo'), /*作者*/
        'desc' => __('Mashiro', 'sakurairo'),
        'id' => 'author_name',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Change web background', 'sakurairo'), /*切换网页背景*/
        'desc' => __('The foreground switches the background of the webpage. There are 8 urls separated by commas. The order corresponds to the foreground scheme tool button position (the order of the buttons is from left to right, top to bottom). If no background is needed, fill in the corresponding position as none.<strong>Note: If the theme is updated from v3.2.3 and below, be sure to change the [Version Control] parameter under the [Other] tab of this configuration page to any new value!</strong>', 'sakurairo'), /*前台切换网页背景，共8个url，使用英文逗号分隔，顺序对应前台切换主题按钮位置（按钮顺序从左至右，从上至下）,如不需要背景则填写对应位置为none。<strong>注意：如果主题是从v3.2.3及以下更新过来的，请务必将本配置页的【其他】标签下的【版本控制】参数修改为任意新值！</strong>*/
        'id' => 'sakura_skin_bg',
        'std' => 'none,https://cdn.jsdelivr.net/gh/spirit1431007/cdn@1.6/img/sakura.png,https://cdn.jsdelivr.net/gh/spirit1431007/cdn@1.6/img/plaid2dbf8.jpg,https://cdn.jsdelivr.net/gh/spirit1431007/cdn@1.6/img/star02.png,https://cdn.jsdelivr.net/gh/spirit1431007/cdn@1.6/img/kyotoanimation.png,https://cdn.jsdelivr.net/gh/spirit1431007/cdn@1.6/img/dot_orange.gif,https://api.mashiro.top/bing/,https://cdn.jsdelivr.net/gh/moezx/cdn@3.1.2/other-sites/api-index/images/me.png',
        'type' => 'textarea');

    $options[] = array(
        'name' => __('Personal avatar', 'sakurairo'), /*个人头像*/
        'desc' => __('The best size is 130px*130px.', 'sakurairo'), /*最佳尺寸130px*130px。*/
        'id' => 'focus_logo',
        'type' => 'upload');

    $options[] = array(
        'name' => __('Text LOGO', 'sakurairo'), /*文字版LOGO*/
        'desc' => __('The home page does not display the avatar above, but displays a paragraph of text (use the avatar above if left blank).The text is recommended not to be too long, about 16 bytes is appropriate.', 'sakurairo'), /*首页不显示上方的头像，而是显示一段文字（此处留空则使用上方的头像）。文字建议不要过长，16个字节左右为宜。*/
        'id' => 'focus_logo_text',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('logo', 'sakurairo'),
        'desc' => __('The best height size is 40px。', 'sakurairo'), /*最佳高度尺寸40px。*/
        'id' => 'akina_logo',
        'type' => 'upload');

    $options[] = array(
        'name' => __('Favicon', 'sakurairo'),
        'desc' => __('It is the small logo on the browser tab, fill in the url', 'sakurairo'), /*就是浏览器标签栏上那个小 logo，填写url*/
        'id' => 'favicon_link',
        'std' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/favicon.ico',
        'type' => 'text');

    $options[] = array(
        'name' => __('Custom keywords and descriptions ', 'sakurairo'), /*自定义关键词和描述*/
        'desc' => __('Customize keywords and descriptions after opening', 'sakurairo'), /*开启之后可自定义填写关键词和描述*/
        'id' => 'akina_meta',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Site keywords', 'sakurairo'), /*网站关键词*/
        'desc' => __('Each keyword is divided by a comma "," and the number is within 5.', 'sakurairo'), /*各关键字间用半角逗号","分割，数量在5个以内最佳。*/
        'id' => 'akina_meta_keywords',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Site descriptions', 'sakurairo'), /*网站描述*/
        'desc' => __('Describe the site in concise text, with a maximum of 120 words.', 'sakurairo'), /*用简洁的文字描述本站点，字数建议在120个字以内。*/
        'id' => 'akina_meta_description',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Expand the nav menu', 'sakurairo'), /*展开导航菜单*/
        'desc' => __('Check to enable, default shrink', 'sakurairo'), /*勾选开启，默认收缩*/
        'id' => 'shownav',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Head decoration', 'sakurairo'), /*头部装饰图*/
        'desc' => __('Enable by default, check off, display on the article page, separate page and category page', 'sakurairo'), /*默认开启，勾选关闭，显示在文章页面，独立页面以及分类页*/
        'id' => 'patternimg',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Search button', 'sakurairo'), /*搜索按钮*/
        'id' => 'top_search',
        'std' => "yes",
        'type' => "radio",
        'options' => array(
            'yes' => __('Open', 'sakurairo'),
            'no' => __('Close', 'sakurairo'),
        ));

    $options[] = array(
        'name' => __('Home article style', 'sakurairo'), /*首页文章风格*/
        'id' => 'post_list_style',
        'std' => "imageflow",
        'type' => "radio",
        'options' => array(
            'standard' => __('Standard', 'sakurairo'), /*标准*/
            'imageflow' => __('Graphic', 'sakurairo'), /*图文*/
        ));

    $options[] = array(
        'name' => __('Home article feature images (only valid for standard mode)', 'sakurairo'), /*首页文章特色图（仅对标准风格生效）*/
        'id' => 'list_type',
        'std' => "round",
        'type' => "radio",
        'options' => array(
            'round' => __('Round', 'sakurairo'), /*圆形*/
            'square' => __('Square', 'sakurairo'), /*方形*/
        ));

    $options[] = array(
        'name' => __('Home article feature images alignment (only for graphic mode, default left and right alternate)', 'sakurairo'), /*首页文章特色图对齐方式（仅对图文风格生效，默认左右交替）*/
        'id' => 'feature_align',
        'std' => "alternate",
        'type' => "radio",
        'options' => array(
            'left' => __('Left', 'sakurairo'), /*向左对齐*/
            'right' => __('Right', 'sakurairo'), /*向右对齐*/
            'alternate' => __('Alternate', 'sakurairo'), /*左右交替*/
        ));

    $options[] = array(
        'name' => __('Comment shrink', 'sakurairo'), /*评论收缩*/
        'id' => 'toggle-menu',
        'std' => "yes",
        'type' => "radio",
        'options' => array(
            'yes' => __('Open', 'sakurairo'), /*开启*/
            'no' => __('Close', 'sakurairo'), /*关闭*/
        ));

    $options[] = array(
        'name' => __('Display author information at the end of the article?', 'sakurairo'), /*文章末尾显示作者信息？*/
        'desc' => __('Check to enable', 'sakurairo'), /*勾选启用*/
        'id' => 'show_authorprofile',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Paging mode', 'sakurairo'), /*分页模式*/
        'id' => 'pagenav_style',
        'std' => "ajax",
        'type' => "radio",
        'options' => array(
            'ajax' => __('Ajax load', 'sakurairo'), /*ajax加载*/
            'np' => __('Previous and next page', 'sakurairo'), /*上一页和下一页*/
        ));

    $options[] = array(
        'name' => __('Automatically load the next page', 'sakurairo'), /*自动加载下一页*/
        'desc' => __('(seconds) Set to automatically load the next page time, the default is not automatically loaded', 'sakurairo'), /*（秒）设置自动加载下一页时间，默认不自动加载*/
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
            '233' => __('Do not load automatically', 'sakurairo'), /*不自动加载*/
        ));

    $options[] = array(
        'name' => __('Footer info', 'sakurairo'), /*页脚信息*/
        'desc' => __('Footer description, support for HTML code', 'sakurairo'), /*页脚说明文字，支持HTML代码*/
        'id' => 'footer_info',
        'std' => 'Copyright &copy; by Mashiro All Rights Reserved.',
        'type' => 'textarea');

    $options[] = array(
        'name' => __('About', 'sakurairo'), /*关于*/
        'desc' => sprintf(__('Sakurairo v %s  |  <a href="https://asuhe.jp/daily/sakurairo-user-manual/">Theme document</a>  |  <a href="https://github.com/mirai-mamori/Sakurairo">Source code</a><a href="https://github.com/mirai-mamori/Sakurairo/releases/latest"><img src="https://img.shields.io/github/v/release/mirai-mamori/Sakurairo.svg?style=flat-square" alt="GitHub release"></a>', 'sakurairo'), SAKURA_VERSION), 
        'id' => 'theme_intro',
        'std' => '',
        'type' => 'typography ');

    $options[] = array(
        'name' => __('Check for Updates', 'sakurairo'), /*检查更新*/
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
        'name' => __('HomePage', 'sakurairo'), /*首页设置*/
        'type' => 'heading');

    $options[] = array(
        'name' => __('Main switch', 'sakurairo'), /*总开关*/
        'desc' => __('Default on, check off', 'sakurairo'), /*默认开启，勾选关闭*/
        'id' => 'head_focus',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Social information', 'sakurairo'), /*社交信息*/
        'desc' => __('Enable by default, check off, display avatar, signature, SNS', 'sakurairo'), /*默认开启，勾选关闭，显示头像、签名、SNS*/
        'id' => 'focus_infos',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Social information style', 'sakurairo'), /*社交信息样式*/
        'id' => 'social_style',
        'std' => "v1",
        'type' => "radio",
        'options' => array(
            'v2' => __('Merge with signature', 'sakurairo'), /*与签名合并*/
            'v1' => __('Independent line', 'sakurairo'), /*独立成行*/
        ));

    $options[] = array(
        'name' => __('Cover manifest', 'sakurairo'), /*封面图片库选项*/
        'desc' => __('Select how to call the cover random image', 'sakurairo'), /*选择封面随机图的调用方式*/
        'id' => 'cover_cdn_options',
        'std' => "type_3",
        'type' => "select",
        'options' => array(
            'type_1' => __('webp images (optimization)', 'sakurairo'), /*webp优化随机图*/
            'type_2' => __('built-in api (default)', 'sakurairo'), /*内置原图随机图*/
            'type_3' => __('custom api (advanced)', 'sakurairo'), /*外部随机图API*/
        )
    );

    $options[] = array(
        'name' => __('Cover images url', 'sakurairo'), /*图片库url*/
        'desc' => sprintf(__('Fill in the manifest path for random picture display, please refer to <a href = "https: //github.com/mashirozx/Sakura/wiki/options">Wiki </a>. If you select webp images above, click <a href = "%s">here</a> to update manifest', 'sakurairo'), rest_url('sakurairo/v1/database/update')), /*填写 manifest 路径，更多信息请参考<a href="https://github.com/mashirozx/Sakura/wiki/options">Wiki</a>,，如果你在上面选择了webp优化，点击<a href = "%s">这里</a>更新 manifest*/
        'id' => 'cover_cdn',
        'std' => 'https://api.btstu.cn/sjbz/api.php?lx=dongman&format=images',
        'type' => 'text');

    $options[] = array(
        'name' => __('full-screen display', 'sakurairo'), /*全屏显示*/
        'desc' => __('Default on, check off', 'sakurairo'), /*默认开启，勾选关闭*/
        'id' => 'focus_height',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Enable video', 'sakurairo'), /*开启视频*/
        'desc' => __('Check on', 'sakurairo'), /*勾选开启*/
        'id' => 'focus_amv',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Live', 'sakurairo'),
        'desc' => __('Check to enable, the video will continue to play automatically, you need to enable Pjax', 'sakurairo'), /*勾选开启，视频自动续播，需要开启Pjax功能*/
        'id' => 'focus_mvlive',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Video address', 'sakurairo'), /*视频地址*/
        'desc' => __('The source address of the video, the address is spliced below the video name, the slash is not required at the end of the address', 'sakurairo'), /*视频的来源地址，该地址拼接下面的视频名，地址尾部不需要加斜杠*/
        'id' => 'amv_url',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Video name', 'sakurairo'), /*视频名称*/
        'desc' => __('abc.mp4, just fill in the video file name abc, multiple videos separated by commas such as abc, efg, do not care about the order, because the loading is random extraction', 'sakurairo'), /*abc.mp4 ，只需要填写视频文件名 abc 即可，多个用英文逗号隔开如 abc,efg ，无需在意顺序，因为加载是随机的抽取的 */
        'id' => 'amv_title',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Background image filter', 'sakurairo'), /*背景图滤镜*/
        'id' => 'focus_img_filter',
        'std' => "filter-undertint",
        'type' => "radio",
        'options' => array(
            'filter-nothing' => __('Nothing', 'sakurairo'), /*无*/
            'filter-undertint' => __('Undertint', 'sakurairo'), /*浅色*/
            'filter-dim' => __('Dim', 'sakurairo'), /*暗淡*/
            'filter-grid' => __('Grid', 'sakurairo'), /*网格*/
            'filter-dot' => __('Dot', 'sakurairo'), /*点点*/
        ));

    $options[] = array(
        'name' => __('Main page article title', 'sakurairo'), 
        'desc' => __('Default is 記事, you can also change it to other, of course you can\'t use it as an advertisement!Not allowed!!', 'sakurairo'), 
        'id' => 'homepage_title',
        'std' => '記事',
        'class' => 'mini',
        'type' => 'text');

    $options[] = array(
        'name' => __('Whether to turn on the top-feature', 'sakurairo'), /*是否开启聚焦*/
        'desc' => __('Default on', 'sakurairo'),
        'id' => 'top_feature',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Top-feature style', 'sakurairo'), /*聚焦样式*/
        'id' => 'top_feature_style',
        'std' => "left_and_right",
        'type' => "radio",
        'options' => array(
            'left_and_right' => __('Alternate left and right', 'sakurairo'), /*左右交替*/
            'bottom_to_top' => __('From bottom to top', 'sakurairo'), /*从下往上*/
        ));

    $options[] = array(
        'name' => __('Top-feature title', 'sakurairo'), /*聚焦标题*/
        'desc' => __('Default is 聚焦, you can also change it to other, of course you can\'t use it as an advertisement!Not allowed!!', 'sakurairo'), /*默认为聚焦，你也可以修改为其他，当然不能当广告用！不允许！！*/
        'id' => 'feature_title',
        'std' => '聚焦',
        'class' => 'mini',
        'type' => 'text');

    $options[] = array(
        'name' => __('Top-feature 1 image', 'sakurairo'), /*聚焦图一*/
        'desc' => __('size 257px*160px', 'sakurairo'), /*尺寸257px*160px*/
        'id' => 'feature1_img',
        'std' => "https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/temp.png",
        'type' => 'upload');

    $options[] = array(
        'name' => __('Top-feature 1 title', 'sakurairo'), /*聚焦图一标题*/
        'desc' => __('Top-feature 1 title', 'sakurairo'), /*聚焦图一标题*/
        'id' => 'feature1_title',
        'std' => 'feature1',
        'type' => 'text');

    $options[] = array(
        'name' => __('Top-feature 1 description', 'sakurairo'), /*聚焦图一描述*/
        'desc' => __('Top-feature 1 description', 'sakurairo'), /*聚焦图一描述*/
        'id' => 'feature1_description',
        'std' => 'Description goes here 1',
        'type' => 'text');

    $options[] = array(
        'name' => __('Top-feature 1 link', 'sakurairo'), /*聚焦图一链接*/
        'desc' => __('Top-feature 1 link', 'sakurairo'), /*聚焦图一链接*/
        'id' => 'feature1_link',
        'std' => '#',
        'type' => 'text');

    $options[] = array(
        'name' => __('Top-feature 2 image', 'sakurairo'), /*聚焦图二*/
        'desc' => __('size 257px*160px', 'sakurairo'), /*尺寸257px*160px*/
        'id' => 'feature2_img',
        'std' => "https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/temp.png",
        'type' => 'upload');

    $options[] = array(
        'name' => __('Top-feature 2 title', 'sakurairo'), /*聚焦图二标题*/
        'desc' => __('Top-feature 2 title', 'sakurairo'), /*聚焦图二标题*/
        'id' => 'feature2_title',
        'std' => 'feature2',
        'type' => 'text');

    $options[] = array(
        'name' => __('Top-feature 2 description', 'sakurairo'), /*聚焦图二描述*/
        'desc' => __('Top-feature 2 description', 'sakurairo'), /*聚焦图二描述*/
        'id' => 'feature2_description',
        'std' => 'Description goes here 2',
        'type' => 'text');

    $options[] = array(
        'name' => __('Top-feature 2 link', 'sakurairo'), /*聚焦图二链接*/
        'desc' => __('Top-feature 2 link', 'sakurairo'), /*聚焦图二链接*/
        'id' => 'feature2_link',
        'std' => '#',
        'type' => 'text');

    $options[] = array(
        'name' => __('Top-feature 3 image', 'sakurairo'), /*聚焦图三*/
        'desc' => __('size 257px*160px', 'sakurairo'), /*尺寸257px*160px*/
        'id' => 'feature3_img',
        'std' => "https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/temp.png",
        'type' => 'upload');

    $options[] = array(
        'name' => __('Top-feature 3 title', 'sakurairo'), /*聚焦图三标题*/
        'desc' => __('Top-feature 3 title', 'sakurairo'), /*聚焦图三标题*/
        'id' => 'feature3_title',
        'std' => 'feature3',
        'type' => 'text');

    $options[] = array(
        'name' => __('Top-feature 3 description', 'sakurairo'), /*聚焦图三描述*/
        'desc' => __('Top-feature 3 description', 'sakurairo'), /*聚焦图三描述*/
        'id' => 'feature3_description',
        'std' => 'Description goes here 3',
        'type' => 'text');

    $options[] = array(
        'name' => __('Top-feature 3 link', 'sakurairo'), /*聚焦图三链接*/
        'desc' => __('Top-feature 3 link', 'sakurairo'), /*聚焦图三链接*/
        'id' => 'feature3_link',
        'std' => '#',
        'type' => 'text');

    //文章页
    $options[] = array(
        'name' => __('Post page', 'sakurairo'), /*文章页*/
        'type' => 'heading');

    $options[] = array(
        'name' => __('Post style', 'sakurairo'), /*文章样式*/
        'id' => 'entry_content_theme',
        'std' => "sakurairo",
        'type' => "radio",
        'options' => array(
            'sakurairo' => __('sakurairo', 'sakurairo'), /*默认样式*/
            'github' => __('GitHub', 'sakurairo'),
        ));

    $options[] = array(
        'name' => __('Post like', 'sakurairo'), /*文章点赞*/
        'id' => 'post_like',
        'std' => "yes",
        'type' => "radio",
        'options' => array(
            'yes' => __('Open', 'sakurairo'), /*开启*/
            'no' => __('Close', 'sakurairo'), /*关闭*/
        ));

    $options[] = array(
        'name' => __('Post share', 'sakurairo'), /*文章分享*/
        'id' => 'post_share',
        'std' => "yes",
        'type' => "radio",
        'options' => array(
            'yes' => __('Open', 'sakurairo'), /*开启*/
            'no' => __('Close', 'sakurairo'), /*关闭*/
        ));

    $options[] = array(
        'name' => __('Previous and Next', 'sakurairo'), /*上一篇下一篇*/
        'id' => 'post_nepre',
        'std' => "yes",
        'type' => "radio",
        'options' => array(
            'yes' => __('Open', 'sakurairo'), /*开启*/
            'no' => __('Close', 'sakurairo'), /*关闭*/
        ));

    $options[] = array(
        'name' => __('Author profile', 'sakurairo'), /*博主信息*/
        'id' => 'author_profile',
        'std' => "yes",
        'type' => "radio",
        'options' => array(
            'yes' => __('Open', 'sakurairo'), /*开启*/
            'no' => __('Close', 'sakurairo'), /*关闭*/
        ));

    $options[] = array(
        'name' => __('Alipay reward', 'sakurairo'), /*支付宝打赏*/
        'desc' => __('Alipay qrcode', 'sakurairo'), /*支付宝二维码*/
        'id' => 'alipay_code',
        'type' => 'upload');

    $options[] = array(
        'name' => __('Wechat reward', 'sakurairo'), /*微信打赏*/
        'desc' => __('Wechat qrcode ', 'sakurairo'), /*微信二维码*/
        'id' => 'wechat_code',
        'type' => 'upload');

    //社交选项
    $options[] = array(
        'name' => __('Social network', 'sakurairo'), /*社交网络*/
        'type' => 'heading');

    $options[] = array(
        'name' => __('Wechat', 'sakurairo'), /*微信*/
        'desc' => __('Wechat qrcode', 'sakurairo'), /*微信二维码*/
        'id' => 'wechat',
        'type' => 'upload');

    $options[] = array(
        'name' => __('Sina Weibo', 'sakurairo'), /*新浪微博*/
        'desc' => __('Sina Weibo address', 'sakurairo'), /*新浪微博地址*/
        'id' => 'sina',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Tencent QQ', 'sakurairo'), /*腾讯QQ*/
        'desc' => __('tencent://message/?uin={{QQ number}}. for example, tencent://message/?uin=123456', 'sakurairo'), /*tencent://message/?uin={{QQ号码}}，如tencent://message/?uin=123456*/
        'id' => 'qq',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Telegram', 'sakurairo'),
        'desc' => __('Telegram link', 'sakurairo'), /*Telegram链接*/
        'id' => 'telegram',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Qzone', 'sakurairo'), /*QQ空间*/
        'desc' => __('Qzone address', 'sakurairo'), /*QQ空间地址*/
        'id' => 'qzone',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('GitHub', 'sakurairo'),
        'desc' => __('GitHub address', 'sakurairo'), /*GitHub地址*/
        'id' => 'github',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Lofter', 'sakurairo'),
        'desc' => __('Lofter address', 'sakurairo'), /*lofter地址*/
        'id' => 'lofter',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('BiliBili', 'sakurairo'),
        'desc' => __('BiliBili address', 'sakurairo'), /*B站地址*/
        'id' => 'bili',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Youku video', 'sakurairo'), /*优酷视频*/
        'desc' => __('Youku video address', 'sakurairo'), /*优酷地址*/
        'id' => 'youku',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Netease Cloud Music', 'sakurairo'), /*网易云音乐*/
        'desc' => __('Netease Cloud Music address', 'sakurairo'), /*网易云音乐地址*/
        'id' => 'wangyiyun',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Twitter', 'sakurairo'),
        'desc' => __('Twitter address', 'sakurairo'), /*推特地址*/
        'id' => 'twitter',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Facebook', 'sakurairo'),
        'desc' => __('Facebook address', 'sakurairo'), /*脸书地址*/
        'id' => 'facebook',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Jianshu', 'sakurairo'), /*简书*/
        'desc' => __('Jianshu address', 'sakurairo'), /*简书地址*/
        'id' => 'jianshu',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('CSDN', 'sakurairo'),
        'desc' => __('CSND community address', 'sakurairo'), /*CSND社区地址*/
        'id' => 'csdn',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Zhihu', 'sakurairo'), /*知乎*/
        'desc' => __('Zhihu address', 'sakurairo'), /*知乎地址*/
        'id' => 'zhihu',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Email-name', 'sakurairo'), /*邮箱-用户名*/
        'desc' => __('The name part of name@domain.com, only the frontend has js runtime environment can get the full address, you can rest assured to fill in', 'sakurairo'), /*name@domain.com 的 name 部分，前端仅具有js运行环境时才能获取完整地址，可放心填写*/
        'id' => 'email_name',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Email-domain', 'sakurairo'), /*邮箱-域名*/
        'desc' => __('The domain.com part of name@domain.com', 'sakurairo'), /*ame@domain.com 的 domain.com 部分*/
        'id' => 'email_domain',
        'std' => '',
        'type' => 'text');

    //后台配置
    $options[] = array(
        'name' => __('Dashboard configuration', 'sakurairo'), /*后台配置*/
        'type' => 'heading');

    //后台面板自定义配色方案
    $options[] = array(
        'name' => __('Dashboard panel custom color scheme', 'sakurairo'), /*后台面板自定义配色方案*/
        'desc' => __('You can design the dashboard panel (/wp-admin/) style yourself below, but before you start, please go to <a href="/wp-admin/profile.php">here</a> to change the color scheme to custom.(Custom).<br><b>Tip: </b>How to match colors? Maybe <a href="https://mashiro.top/color-thief/">this</a> can help you.', 'sakurairo'), /*你可以在下面自行设计后台面板（/wp-admin/）样式，不过在开始之前请到<a href="/wp-admin/profile.php">这里</a>将配色方案改为自定义（Custom）。<br><b>Tip: </b>如何搭配颜色？或许<a href="https://mashiro.top/color-thief/">这个</a>可以帮到你。*/
        'id' => 'scheme_tip',
        'std' => '',
        'type' => 'typography ');

    $options[] = array(
        'name' => __('Panel main color A', 'sakurairo'), /*面板主色调A*/
        'id' => 'dash_scheme_color_a',
        'std' => "#c6742b",
        'desc' => __('<i>(array) (optional)</i> An array of CSS color definitions which are used to give the user a feel for the theme.', 'sakurairo'),
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Panel main color B', 'sakurairo'),
        'id' => 'dash_scheme_color_b',
        'std' => "#d88e4c",
        'desc' => __('<i>(array) (optional)</i> An array of CSS color definitions which are used to give the user a feel for the theme.', 'sakurairo'),
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Panel main color C', 'sakurairo'),
        'id' => 'dash_scheme_color_c',
        'std' => "#695644",
        'desc' => __('<i>(array) (optional)</i> An array of CSS color definitions which are used to give the user a feel for the theme.', 'sakurairo'),
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Panel main color D', 'sakurairo'),
        'id' => 'dash_scheme_color_d',
        'std' => "#a19780",
        'desc' => __('<i>(array) (optional)</i> An array of CSS color definitions which are used to give the user a feel for the theme.', 'sakurairo'),
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Panel icon color——base', 'sakurairo'), /*面板图标配色——base*/
        'id' => 'dash_scheme_color_base',
        'std' => "#e5f8ff",
        'desc' => __('<i>(array) (optional)</i> An array of CSS color definitions used to color any SVG icons.', 'sakurairo'),
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Panel icon color——focus', 'sakurairo'),
        'id' => 'dash_scheme_color_focus',
        'std' => "#fff",
        'desc' => __('<i>(array) (optional)</i> An array of CSS color definitions used to color any SVG icons.', 'sakurairo'),
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Panel icon color——current', 'sakurairo'),
        'id' => 'dash_scheme_color_current',
        'std' => "#fff",
        'desc' => __('<i>(array) (optional)</i> An array of CSS color definitions used to color any SVG icons.', 'sakurairo'),
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Other custom panel styles(CSS)', 'sakurairo'), /*其他自定义面板样式(CSS)*/
        'desc' => __('If you need to adjust other styles of the panel, put the style here.', 'sakurairo'), /*如果还需要对面板其他样式进行调整可以把style放到这里*/
        'id' => 'dash_scheme_css_rules',
        'std' => '#adminmenu .wp-has-current-submenu .wp-submenu a,#adminmenu .wp-has-current-submenu.opensub .wp-submenu a,#adminmenu .wp-submenu a,#adminmenu a.wp-has-current-submenu:focus+.wp-submenu a,#wpadminbar .ab-submenu .ab-item,#wpadminbar .quicklinks .menupop ul li a,#wpadminbar .quicklinks .menupop.hover ul li a,#wpadminbar.nojs .quicklinks .menupop:hover ul li a,.folded #adminmenu .wp-has-current-submenu .wp-submenu a{color:#f3f2f1}body{background-image:url(https://view.moezx.cc/images/2019/04/21/windows10-2019-4-21-i3.jpg);background-size:cover;background-repeat:no-repeat;background-attachment:fixed;}#wpcontent{background:rgba(255,255,255,.8)}',
        'type' => 'textarea');

    $options[] = array(
        'name' => __('Login interface background image', 'sakurairo'), /*后台登陆界面背景图*/
        'desc' => __('Use the default image if left this blank', 'sakurairo'), /*该地址为空则使用默认图片*/
        'id' => 'login_bg',
        'type' => 'upload');

    $options[] = array(
        'name' => __('Login interface logo', 'sakurairo'), /*后台登陆界面logo*/
        'desc' => __('Used for login interface display', 'sakurairo'), /*用于登录界面显示*/
        'id' => 'logo_img',
        'std' => "https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/mashiro-logo-s.png",
        'type' => 'upload');

    $options[] = array(
        'name' => __('Login/registration related settings', 'sakurairo'), /*登陆/注册相关设定*/
        'desc' => __(' ', 'space', 'sakurairo'),
        'id' => 'login_tip',
        'std' => '',
        'type' => 'typography ');

    $options[] = array(
        'name' => __('Specify login address', 'sakurairo'), /*指定登录地址*/
        'desc' => __('Forcibly do not use the background address to log in, fill in the new landing page address, such as http://www.xxx.com/login [Note] Before you fill out, test your new page can be opened normally, so as not to enter the background or other problems happening', 'sakurairo'), /*强制不使用后台地址登陆，填写新建的登陆页面地址，比如 http://www.xxx.com/login【注意】填写前先测试下你新建的页面是可以正常打开的，以免造成无法进入后台等情况*/
        'id' => 'exlogin_url',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Specify registered address', 'sakurairo'), /*指定注册地址*/
        'desc' => __('This link is used on the login page as a registration entry', 'sakurairo'), /*该链接使用在登录页面作为注册入口，建议填写*/
        'id' => 'exregister_url',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Allow users to register', 'sakurairo'), /*允许用户注册*/
        'desc' => __('Check to allow users to register at the frontend', 'sakurairo'), /*勾选开启，允许用户在前台注册*/
        'id' => 'ex_register_open',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Automatically redirect after login', 'sakurairo'), /*登录后自动跳转*/
        'desc' => __('After checken, the administrator redirects to the background and the user redirects to the home page.', 'sakurairo'), /*勾选开启，管理员跳转至后台，用户跳转至主页*/
        'id' => 'login_urlskip',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Registration verification (frontend only, backend forced open)', 'sakurairo'), /*注册验证（仅前端，后端强制开启）*/
        'desc' => __('Check to enable slide verification', 'sakurairo'), /*勾选开启滑动验证*/
        'id' => 'login_validate',
        'std' => '0',
        'type' => 'checkbox');

    //进阶
    $options[] = array(
        'name' => __('Advanced', 'sakurairo'),
        'type' => 'heading');

    $options[] = array(
        'name' => __('Warning', 'sakurairo'), 
        'desc' => __(' ', 'sakurairo'), 
        'id' => 'theme_warning',
        'std' => 'tag',
        'type' => "images",
        'options' => array(
            'tag' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/warning.png',
                ),
            );
        
    $options[] = array(
        'name' => __('Images CDN', 'sakurairo'), /*图片库*/
        'desc' => __('Note: Fill in the format http(s)://your CDN domain name/. <br>In other words, the original path is http://your.domain/wp-content/uploads/2018/05/xx.png and the picture will load from http://your CDN domain/2018/05/xx.png', 'sakurairo'), /*注意：填写格式为 http(s)://你的CDN域名/。<br>也就是说，原路径为 http://your.domain/wp-content/uploads/2018/05/xx.png 的图片将从 http://你的CDN域名/2018/05/xx.png 加载*/
        'id' => 'qiniu_cdn',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Use the front-end library locally (lib.js、lib.css)', 'sakurairo'), /*本地调用前端库（lib.js、lib.css）*/
        'desc' => __('The front-end library don\'t load from jsDelivr, not recommand', 'sakurairo'), /*前端库不走 jsDelivr，不建议启用*/
        'id' => 'jsdelivr_cdn_test',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Use js and css file of the theme (sakura-app.js、style.css) locally', 'sakurairo'), /*本地调用主题 js、css 文件（sakura-app.js、style.css）*/
        'desc' => __('The js and css files of the theme do not load from jsDelivr, please open when DIY', 'sakurairo'), /*主题的 js、css 文件不走 jsDelivr，DIY 时请开启*/
        'id' => 'app_no_jsdelivr_cdn',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Gravatar avatar proxy', 'sakurairo'),
        'desc' => __('A front-ed proxy for Gravatar, eg. gravatar.2heng.xin/avatar . Leave it blank if you do not need.', 'sakurairo'),
        'id' => 'gravatar_proxy',
        'std' => "cn.gravatar.com",
        'type' => "text");    
    
    $options[] = array(
        'name' => __('Google analytics', 'sakurairo'), /*Google 统计代码*/
        'desc' => __('UA-xxxxx-x', 'sakurairo'),
        'id' => 'google_analytics_id',
        'std' => '',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('CNZZ Statistics (not recommand)', 'sakurairo'), /*站长统计（不建议使用）*/
        'desc' => __('Statistics code, which will be invisible in web page.', 'sakurairo'), /*填写统计代码，将被隐藏*/
        'id' => 'site_statistics',
        'std' => '',
        'type' => 'textarea');
    
    $options[] = array(
        'name' => __('Customize CSS styles', 'sakurairo'), /*自定义CSS样式*/
        'desc' => __('Fill in the CSS code directly, no need to write style tags', 'sakurairo'), /*直接填写CSS代码，不需要写style标签*/
        'id' => 'site_custom_style',
        'std' => '',
        'type' => 'textarea');

    $options[] = array(
        'name' => __('The categories of articles that don\'t not show on homepage', 'sakurairo'), /*首页不显示的分类文章*/
        'desc' => __('Fill in category ID, multiple IDs are divided by a comma ","', 'sakurairo'), /*填写分类ID，多个用英文“ , ”分开*/
        'id' => 'classify_display',
        'std' => '',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Images category', 'sakurairo'), /*图片展示分类*/
        'desc' => __('Fill in category ID, multiple IDs are divided by a comma ","', 'sakurairo'), /*填写分类ID，多个用英文“ , ”分开*/
        'id' => 'image_category',
        'std' => '',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Version Control', 'sakurairo'), /*版本控制*/
        'desc' => __('Used to update frontend cookies and browser caches, any string can be used', 'sakurairo'), /*用于更新前端 cookie 及浏览器缓存，可使用任意字符串*/
        'id' => 'cookie_version',
        'std' => '',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Enable baguetteBox', 'sakurairo'), /*启用 baguetteBox*/
        'desc' => __('Default off，<a href="https://github.com/mashirozx/Sakura/wiki/Fancybox">please read wiki</a>', 'sakurairo'), /*默认禁用，<a href="https://github.com/mashirozx/Sakura/wiki/Fancybox">请阅读说明</a>*/
        'id' => 'image_viewer',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Time Zone adjustment', 'sakurairo'), /*时区调整*/
        'desc' => __('If the comment has a time difference problem adjust here, fill in an integer, the calculation method: actual_time = display_error_time - the_integer_you_entered (unit: hour)', 'sakurairo'), /*如果评论出现时差问题在这里调整，填入一个整数，计算方法：实际时间=显示错误的时间-你输入的整数（单位：小时）*/
        'id' => 'time_zone_fix',
        'std' => '0',
        'type' => 'text');
    
    //功能
    $options[] = array(
        'name' => __('Function', 'sakurairo'), /*功能*/
        'type' => 'heading');

    $options[] = array(
        'name' => __('Darkmode', 'sakurairo'), /*夜间模式*/
        'desc' => __('Check open', 'sakurairo'), /*勾选开启*/
        'id' => 'darkmode',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Footer float music player', 'sakurairo'), /*页脚悬浮播放器*/
        'desc' => __('Choose which platform you\'ll use.', 'sakurairo'),
        'id' => 'aplayer_server',
        'std' => "netease",
        'type' => "select",
        'options' => array(
            'netease' => __('Netease Cloud Music (default)', 'sakurairo'),
            'xiami' => __('Xiami Music', 'sakurairo'),
//            'kugou' => __('KuGou Music', 'sakurairo'),
//            'baidu' => __('Baidu Music（Overseas server does not support）', 'sakurairo'),
//            'tencent' => __('QQ Music (may fail) ', 'sakurairo'),
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
        'name' => __('Enable PJAX (recommand on)', 'sakurairo'), /*开启PJAX局部刷新（建议开启）*/
        'desc' => __('The principle is the same as Ajax', 'sakurairo'), /*原理与Ajax相同*/
        'id' => 'poi_pjax',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Enable NProgress progress bar', 'sakurairo'), /*开启NProgress加载进度条*/
        'desc' => __('Default off, check on', 'sakurairo'), /*默认不开启，勾选开启*/
        'id' => 'nprogress_on',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Enable sidebar widget', 'sakurairo'), /*支持侧栏小部件*/
        'desc' => __('Default off, check on', 'sakurairo'), /*默认不开启，勾选开启*/
        'id' => 'sakura_widget',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Enable Announcement', 'sakurairo'),
        'desc' => __('Default off, check on', 'sakurairo'), /*默认不显示，勾选开启*/
        'id' => 'head_notice',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Announcement content', 'sakurairo'),
        'desc' => __('Announcement content, the text exceeds 142 bytes will be scrolled display (mobile device is invalid)', 'sakurairo'), /*公告内容，文字超出142个字节将会被滚动显示（移动端无效），一个汉字 = 3字节，一个字母 = 1字节，自己计算吧*/
        'id' => 'notice_title',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Bilibili UID', 'sakurairo'), /*bilibiliUID*/
        'desc' => __('Fill in your UID, eg.https://space.bilibili.com/13972644/, only fill in with the number part.', 'sakurairo'),
        'id' => 'bilibili_id',
        'std' => '13972644',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Bilibili Cookie', 'sakurairo'), /*Bilibili Cookie*/
        'desc' => __('Fill in your Cookies, go to your bilibili homepage, you can get cookies in brownser network pannel with pressing F12. If left this blank, you\'ll not get the progress.', 'sakurairo'),
        'id' => 'bilibili_cookie',
        'std' => 'LIVE_BUVID=',
        'type' => 'textarea');

    $options[] = array(
        'name' => __('Statistics Interface', 'sakurairo'), /*统计接口*/
        'id' => 'statistics_api',
        'std' => "theme_build_in",
        'type' => "radio",
        'options' => array(
            'wp_statistics' => __('WP-Statistics plugin (Professional statistics, can exclude invalid access)', 'sakurairo'), /*WP-Statistics 插件（专业性统计，可排除无效访问）*/
            'theme_build_in' => __('Theme built-in (simple statistics, calculate each page access request)', 'sakurairo'), /*主题内建（简单的统计，计算每一次页面访问请求）*/
        ));

    $options[] = array(
        'name' => __('Statistical data display format', 'sakurairo'), /*统计数据显示格式*/
        'id' => 'statistics_format',
        'std' => "type_1",
        'type' => "radio",
        'options' => array(
            'type_1' => __('23333 Views (default)', 'sakurairo'), /*23333 次访问（默认）*/
            'type_2' => __('23,333 Views (britain)', 'sakurairo'), /*23,333 次访问（英式）'*/
            'type_3' => __('23 333 Views (french)', 'sakurairo'), /*23 333 次访问（法式）*/
            'type_4' => __('23k Views (chinese)', 'sakurairo'), /*23k 次访问（中式）*/
        ));

    $options[] = array(
        'name' => __('Comment image upload API', 'sakurairo'), /*评论图片上传接口*/
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
        'name' => __('Enable live search', 'sakurairo'), /*启用实时搜索*/
        'desc' => __('Real-time search in the foreground, call the Rest API to update the cache every hour, you can manually set the cache time in api.php', 'sakurairo'), /*前台实现实时搜索，调用 Rest API 每小时更新一次缓存，可在 functions.php 里手动设置缓存时间*/
        'id' => 'live_search',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Include comments in live search', 'sakurairo'), /*实时搜索包含评论*/
        'desc' => __('Search for comments in real-time search (not recommended if there are too many comments on the site)', 'sakurairo'), /*在实时搜索中搜索评论（如果网站评论数量太多不建议开启）*/
        'id' => 'live_search_comment',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Enable lazyload in posts', 'sakurairo'), /*文章内图片启用 lazyload*/
        'desc' => __('Default on', 'sakurairo'), /*默认启用*/
        'id' => 'lazyload',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('lazyload spinner', 'sakurairo'),
        'desc' => __('The placeholder to display when the image loads, fill in the image url', 'sakurairo'), /*图片加载时要显示的占位图，填写图片 url*/
        'id' => 'lazyload_spinner',
        'std' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/loadimg/inload.svg',
        'type' => 'text');

    $options[] = array(
        'name' => __('Whether to enable the clipboard copyright', 'sakurairo'), /*是否开启剪贴板版权标识*/
        'desc' => __('Automatically add a copyright to the clipboard when copying more than 30 bytes, which is enabled by default.', 'sakurairo'), /*复制超过30个字节时自动向剪贴板添加版权标识，默认开启*/
        'id' => 'clipboard_copyright',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Email address prefix', 'sakurairo'), /*发件地址前缀*/
        'desc' => __('For sending system mail, the sender address displayed in the user\'s mailbox, do not use Chinese, the default system email address is bibi@your_domain_name', 'sakurairo'), /*用于发送系统邮件，在用户的邮箱中显示的发件人地址，不要使用中文，默认系统邮件地址为 bibi@你的域名*/
        'id' => 'mail_user_name',
        'std' => 'bibi',
        'type' => 'text');

    $options[] = array(
        'name' => __('Comments reply notification', 'sakurairo'), /*邮件回复通知*/
        'desc' => __('WordPress will use email to notify users when their comments receive a reply by default. Tick this item allows users to set their own comments reply notification', 'sakurairo'), /*WordPress默认会使用邮件通知用户评论收到回复，开启此项允许用户设置自己的评论收到回复时是否使用邮件通知*/
        'id' => 'mail_notify',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Administrator comment notification', 'sakurairo'), /*邮件回复通知管理员*/
        'desc' => __('Whether to use email notification when the administrator\'s comments receive a reply', 'sakurairo'), /*当管理员评论收到回复时是否使用邮件通知*/
        'id' => 'admin_notify',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Enable private comment', 'sakurairo'), /*允许私密评论*/
        'desc' => __('Allow users to set their own comments to be invisible to others', 'sakurairo'), /*允许用户设置自己的评论对其他人不可见*/
        'id' => 'open_private_message',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Human verification', 'sakurairo'), /*机器人验证*/
        'desc' => __('Enable human verification', 'sakurairo'), /*开启机器人验证*/
        'id' => 'norobot',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('QQ avatar link encryption', 'sakurairo'), /*QQ头像链接加密*/
        'desc' => __('Do not display the user\'s qq avatar links directly.', 'sakurairo'), /*不直接暴露用户qq头像链接*/
        'id' => 'qq_avatar_link',
        'std' => "off",
        'type' => "select",
        'options' => array(
            'off' => __('Off (default)', 'sakurairo'), /*关闭（默认）*/
            'type_1' => __('use redirect (general security)', 'sakurairo'), /*使用 重定向（安全性一般）'*/
            'type_2' => __('fetch data at backend (high security)', 'sakurairo'), /*后端获取数据（安全性高）*/
            'type_3' => __('fetch data at backend (high security，slow)', 'sakurairo'), /*后端获取数据（安全性高, 慢）*/
        ));

    $options[] = array(
        'name' => __('Comment UA infomation', 'sakurairo'), /*评论UA信息*/
        'desc' => __('Check to enable, display the user\'s browser, operating system information', 'sakurairo'), /*勾选开启，显示用户的浏览器，操作系统信息*/
        'id' => 'open_useragent',
        'std' => '0',
        'type' => 'checkbox');

  //增强功能
    $options[] = array(
        'name' => __('Enhanced', 'sakurairo'),
        'type' => 'heading');
    
    $options[] = array(
        'name' => __('You Should Know', 'sakurairo'), 
        'desc' => __(' ', 'sakurairo'), 
        'id' => 'theme_knowledge',
        'std' => 'tag',
        'type' => "images",
        'options' => array(
            'tag' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/supportknow.png',
                ),
            );
    
    $options[] = array(
        'name' => __('fontweight', 'sakurairo'),//全局字重
        'desc' => __('Fill in a number, maximum 900, minimum 100. Between 300 and 500 is recommended.', 'sakurairo'),
        'id' => 'fontweight',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Add Fonts', 'sakurairo'), 
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
        'name' => __('Front Page One Word FontSize', 'sakurairo'), 
        'desc' => __('Fill in Number. Between 10 and 20 is recommended', 'sakurairo'),
        'id' => 'fontsize-oneword',
        'std' => '',
        'type' => 'text');  

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
    
    $options[] = array(
        'name' => __('Preload animation', 'sakurairo'), /*预加载动画*/
        'desc' => __('Check open', 'sakurairo'), /*勾选开启*/
        'id' => 'yjzdh',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Cherry Blossom falling effect', 'sakurairo'), /*樱花飘落特效*/
        'desc' => __('Check open', 'sakurairo'), /*勾选开启*/
        'id' => 'sakurajs',
        'std' => '1',
        'type' => 'checkbox');
    
    $options[] = array(
        'name' => __('Wave effects', 'sakurairo'), /*首页波浪特效*/
        'desc' => __('Check open', 'sakurairo'), /*勾选开启*/
        'id' => 'bolangcss',
        'std' => '1',
        'type' => 'checkbox');
    
    $options[] = array(
        'name' => __('Footer suspension player default volume', 'sakurairo'), /*页脚悬浮播放器*/
        'desc' => __('Maximum 1 minimum 0', 'sakurairo'),
        'id' => 'playlist_mryl',
        'std' => '0.5',
        'type' => 'text');

    $options[] = array(
        'name' => __('live2d', 'sakurairo'), /*看板娘*/
        'desc' => __('Check open', 'sakurairo'), /*勾选开启*/
        'id' => 'live2djs',
        'std' => '1',
        'type' => 'checkbox');
    
    $options[] = array(
        'name' => __('Drop-down arrow', 'sakurairo'), /*下拉箭头*/
        'desc' => __('Check open', 'sakurairo'), /*勾选开启*/
        'id' => 'godown',
        'std' => '1',
        'type' => 'checkbox');
    
    $options[] = array(
        'name' => __('a brief remark', 'sakurairo'), /*页脚一言*/
        'desc' => __('Check open', 'sakurairo'), /*勾选开启*/
        'id' => 'oneword',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Comment Textarea image', 'sakurairo'), /*后台登陆界面背景图*/
        'desc' => __('NO image if left this blank', 'sakurairo'), /*该地址为空则使用默认图片*/
        'id' => 'comment-image',
        'type' => 'upload');

    $options[] = array(
        'name' => __('One word typing effect of home page', 'sakurairo'), /*首页一言打字效果*/
        'desc' => __('Check open', 'sakurairo'), /*勾选开启*/
        'id' => 'dazi',
        'std' => '0',
        'type' => 'checkbox');
    
    $options[] = array(
        'name' => __('Double quotation marks for typing effect', 'sakurairo'), /*首页一言打字效果*/
        'desc' => __('Check open', 'sakurairo'), /*勾选开启*/
        'id' => 'dazi_yh',
        'std' => '0',
        'type' => 'checkbox');
    
    $options[] = array(
        'name' => __('Typewriting effect text', 'sakurairo'), /*打字效果文字*/
        'desc' => __('Fill in the text part of the typing effect (double quotation marks must be used outside the text, and English commas shall be used to separate the two sentences. Support for HTML tags)', 'sakurairo'),
        'id' => 'dazi_a',
        'std' => '"寒蝉黎明之时,便是重生之日。"',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Homepage one word blogger description', 'sakurairo'), /*主页一言博主描述*/
        'desc' => __('A self description', 'sakurairo'), /*一段自我描述的话*/
        'id' => 'admin_des',
        'std' => '粉色的花瓣，美丽地缠绕在身上。依在风里。',
        'type' => 'textarea');
    
    $options[] = array(
        'name' => __('Blog description at the end of the article', 'sakurairo'), /*文章末尾博主描述*/
        'desc' => __('A self description', 'sakurairo'), /*一段自我描述的话*/
        'id' => 'admin_destwo',
        'std' => '粉色的花瓣，美丽地缠绕在身上。依在风里。',
        'type' => 'textarea');
    
    $options[] = array(
        'name' => __('Note effects', 'sakurairo'), /*音符特效*/
        'desc' => __('Check open', 'sakurairo'), /*勾选开启*/
        'id' => 'audio',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Logo special effects', 'sakurairo'), /*Logo特效*/
        'desc' => __('Check open', 'sakurairo'), /*勾选开启*/
        'id' => 'logocss',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Logo text A', 'sakurairo'), /*Logo文字*/
        'desc' => __('Fill in the front part of your logo text', 'sakurairo'),
        'id' => 'logo_a',
        'std' => ' ',
        'type' => 'text');
        
    $options[] = array(
        'name' => __('Logo text B', 'sakurairo'), /*Logo文字*/
        'desc' => __('Fill in the middle part of your logo text', 'sakurairo'),
        'id' => 'logo_b',
        'std' => ' ',
        'type' => 'text');
        
    $options[] = array(
        'name' => __('Logo text C', 'sakurairo'), /*Logo文字*/
        'desc' => __('Fill in the back of your logo', 'sakurairo'),
        'id' => 'logo_c',
        'std' => ' ',
        'type' => 'text');
        
     $options[] = array(
        'name' => __('Logo secondary text', 'sakurairo'), /*Logo文字*/
        'desc' => __('Fill in the secondary text of your logo.', 'sakurairo'),
        'id' => 'logo_two',
        'std' => '',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Logo font link', 'sakurairo'), /*Logo文字*/
        'desc' => __('When the font is ready, do this again <a href = "https://www.fontke.com/tool/fontface/">@font-face生成器</a>It can generate a bunch of files, which are all useful. It can be placed on a accessible server, OOS, CDN, etc. here, you only need to fill in the CSS style sheet file link <a href = "https://blog.ukenn.top/sakura6/#toc-head-4">Detailed tutorial</a>', 'sakurairo'),
        'id' => 'logo_zt',
        'std' => 'https://cdn.jsdelivr.net/gh/acai66/mydl/fonts/wenyihei/wenyihei-subfont.css',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Logo font name', 'sakurairo'), /*Logo文字*/
        'desc' => __('Fill in the font name of your logo, write the name directly without the format suffix', 'sakurairo'),
        'id' => 'logo_ztmc',
        'std' => 'wenyihei-subfont',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Mail template header', 'sakurairo'), 
        'desc' => __('Set the background picture above your message', 'sakurairo'), 
        'id' => 'mail_img',
        'std' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/loadimg/head.jpg',
        'type' => 'text');
        
    //色彩
    $options[] = array(
        'name' => __('Color', 'sakurairo'),
        'type' => 'heading');

    $options[] = array(
        'name' => __('Display icon selection', 'sakurairo'), /*社交图标选择*/
        'desc' => __('Choose icon color', 'sakurairo'), /*社交图标选择*/
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
            'name' => __('Theme style', 'sakurairo'), /*主题风格*/
            'id' => 'theme_skin',
            'std' => "#EE9CA7",
            'desc' => __('Custom theme color', 'sakurairo'), /*自定义主题颜色*/
            'type' => "color",
    );
    
    $options[] = array(
        'name' => __('Social background', 'sakurairo'), /*首页一言文字背景*/
        'id' => 'theme_skin_yybj',
        'std' => "#FFF",
        'desc' => __('Custom colors', 'sakurairo'), /*自定义背景颜色*/
        'type' => "color",
    );

    $options[] = array(
            'name' => __('Theme selection menu background color', 'sakurairo'), /*主题选择菜单背景颜色*/
            'id' => 'theme_skin_cdbj',
            'std' => "#FFF",
            'desc' => __('Custom colors', 'sakurairo'), /*自定义背景颜色*/
            'type' => "color",
    );
    
    $options[] = array(
        'name' => __('Drop down arrow color', 'sakurairo'), /*下拉箭头颜色*/
        'id' => 'godown_skin',
        'std' => "#FFF",
        'desc' => __('Custom colors', 'sakurairo'), /*自定义下拉箭头颜色*/
        'type' => "color",
    );  
    
    $options[] = array(
        'name' => __('Home page article separator', 'sakurairo'), /*首页文章分割符*/
        'id' => 'theme_skin_fgf',
        'std' => "#FFEEEB",
        'desc' => __('Custom colors', 'sakurairo'), /*自定义背景颜色*/
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Home page article time prompt accent', 'sakurairo'), /*首页文章时间提示强调文字*/
        'id' => 'theme_skin_sjwz',
        'std' => "#EE9CA7",
        'desc' => __('Custom colors', 'sakurairo'), /*自定义背景颜色*/
        'type' => "color",
    );
    
    $options[] = array(
        'name' => __('Home page article time prompt emphasize background', 'sakurairo'), /*首页文章时间提示强调背景*/
        'id' => 'theme_skin_sjbj',
        'std' => "#FFEEEB",
        'desc' => __('Custom colors', 'sakurairo'), /*自定义背景颜色*/
        'type' => "color",
    );
    
    $options[] = array(
        'name' => __('First page article border shadow', 'sakurairo'), /*首页文章边框阴影*/
        'id' => 'theme_skin_bkyy',
        'std' => "#FFEEEB",
        'desc' => __('Custom colors', 'sakurairo'), /*自定义背景颜色*/
        'type' => "color",
    );

    $options[] = array(
        'name' => __('First page Focus background color', 'sakurairo'), 
        'id' => 'theme_skin_jjbj',
        'std' => "#FFEEEB",
        'desc' => __('Custom colors', 'sakurairo'), 
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Preload animation color A', 'sakurairo'), /*预加载动画颜色A*/
        'id' => 'theme_skin_yjjone',
        'std' => "#FFEEEB",
        'desc' => __('Custom colors', 'sakurairo'), /*自定义背景颜色*/
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Preload animation color B', 'sakurairo'), /*预加载动画颜色B*/
        'id' => 'theme_skin_yjjtwo',
        'std' => "#EE9CA7",
        'desc' => __('Custom colors', 'sakurairo'), /*自定义背景颜色*/
        'type' => "color",
    );
    
    return $options;
}