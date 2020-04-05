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
 * If you are making your theme translatable, you should replace 'sakura'
 * with the actual text domain for your theme.
 *
 * Frame from: https://github.com/devinsays/options-framework-plugin/
 */

function optionsframework_options()
{
    // 测试数据
    $test_array = array(
        'one' => __('1', 'sakura'),
        'two' => __('2', 'sakura'),
        'three' => __('3', 'sakura'),
        'four' => __('4', 'sakura'),
        'five' => __('5', 'sakura'),
        'six' => __('6', 'sakura'),
        'seven' => __('7', 'sakura'),
    );

    // 复选框数组
    $multicheck_array = array(
        'one' => __('1', 'sakura'),
        'two' => __('2', 'sakura'),
        'three' => __('3', 'sakura'),
        'four' => __('4', 'sakura'),
        'five' => __('5', 'sakura'),
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
        'name' => __('Basic settings', 'sakura'), /*基本设置*/
        'type' => 'heading');

    $options[] = array(
        'name' => __('Site title', 'sakura'), /*站点名称*/
        'desc' => __('Mashiro\'s Blog', 'sakura'),
        'id' => 'site_name',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Author', 'sakura'), /*作者*/
        'desc' => __('Mashiro', 'sakura'),
        'id' => 'author_name',
        'std' => '',
        'type' => 'text');

    //$options[] = array(
    //    'name' => __('Theme style', 'sakura'), /*主题风格*/
    //    'id' => 'theme_skin',
    //    'std' => "#EE9CA7",
    //    'desc' => __('Custom theme color', 'sakura'), /*自定义主题颜色*/
    //    'type' => "color",
    //);

    $options[] = array(
        'name' => __('Theme scheme tool transparency', 'sakura'), /*切换主题菜单透明度*/
        'desc' => __('Adjust the theme scheme menu transparency, the smaller the value, the more transparent. The default value is 0.8', 'sakura'), /*调整切换主题菜单透明度，值越小越透明。默认透明度0.8*/
        'id' => 'sakura_skin_alpha',
        'std' => '0.8',
        'type' => 'select',
        'options' => array(
            '0' => __('Transparent', 'sakura'), /*全透明*/
            '0.1' => __('Transparency 0.1', 'sakura'), /*透明度0.1*/
            '0.2' => __('Transparency 0.2', 'sakura'), /*透明度0.2*/
            '0.3' => __('Transparency 0.3', 'sakura'), /*透明度0.3*/
            '0.4' => __('Transparency 0.4', 'sakura'), /*透明度0.4*/
            '0.5' => __('Transparency 0.5', 'sakura'), /*透明度0.5*/
            '0.6' => __('Transparency 0.6', 'sakura'), /*透明度0.6*/
            '0.7' => __('Transparency 0.7', 'sakura'), /*透明度0.7*/
            '0.8' => __('Transparency 0.8', 'sakura'), /*透明度0.8*/
            '0.9' => __('Transparency 0.9', 'sakura'), /*透明度0.9*/
            '1' => __('Opaque', 'sakura'), /*不透明*/
        ));

    $options[] = array(
        'name' => __('Change web background', 'sakura'), /*切换网页背景*/
        'desc' => __('The foreground switches the background of the webpage. There are 8 urls separated by commas. The order corresponds to the foreground scheme tool button position (the order of the buttons is from left to right, top to bottom). If no background is needed, fill in the corresponding position as none.<strong>Note: If the theme is updated from v3.2.3 and below, be sure to change the [Version Control] parameter under the [Other] tab of this configuration page to any new value!</strong>', 'sakura'), /*前台切换网页背景，共8个url，使用英文逗号分隔，顺序对应前台切换主题按钮位置（按钮顺序从左至右，从上至下）,如不需要背景则填写对应位置为none。<strong>注意：如果主题是从v3.2.3及以下更新过来的，请务必将本配置页的【其他】标签下的【版本控制】参数修改为任意新值！</strong>*/
        'id' => 'sakura_skin_bg',
        'std' => 'none,https://cdn.jsdelivr.net/gh/spirit1431007/cdn@1.6/img/sakura.png,https://cdn.jsdelivr.net/gh/spirit1431007/cdn@1.6/img/plaid2dbf8.jpg,https://cdn.jsdelivr.net/gh/spirit1431007/cdn@1.6/img/star02.png,https://cdn.jsdelivr.net/gh/spirit1431007/cdn@1.6/img/kyotoanimation.png,https://cdn.jsdelivr.net/gh/spirit1431007/cdn@1.6/img/dot_orange.gif,https://api.mashiro.top/bing/,https://cdn.jsdelivr.net/gh/moezx/cdn@3.1.2/other-sites/api-index/images/me.png',
        'type' => 'textarea');

    $options[] = array(
        'name' => __('Personal avatar', 'sakura'), /*个人头像*/
        'desc' => __('The best size is 130px*130px.', 'sakura'), /*最佳尺寸130px*130px。*/
        'id' => 'focus_logo',
        'type' => 'upload');

    $options[] = array(
        'name' => __('Text LOGO', 'sakura'), /*文字版LOGO*/
        'desc' => __('The home page does not display the avatar above, but displays a paragraph of text (use the avatar above if left blank).The text is recommended not to be too long, about 16 bytes is appropriate.', 'sakura'), /*首页不显示上方的头像，而是显示一段文字（此处留空则使用上方的头像）。文字建议不要过长，16个字节左右为宜。*/
        'id' => 'focus_logo_text',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('logo', 'sakura'),
        'desc' => __('The best height size is 40px。', 'sakura'), /*最佳高度尺寸40px。*/
        'id' => 'akina_logo',
        'type' => 'upload');

    $options[] = array(
        'name' => __('Favicon', 'sakura'),
        'desc' => __('It is the small logo on the browser tab, fill in the url', 'sakura'), /*就是浏览器标签栏上那个小 logo，填写url*/
        'id' => 'favicon_link',
        'std' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/favicon.ico',
        'type' => 'text');

    $options[] = array(
        'name' => __('Custom keywords and descriptions ', 'sakura'), /*自定义关键词和描述*/
        'desc' => __('Customize keywords and descriptions after opening', 'sakura'), /*开启之后可自定义填写关键词和描述*/
        'id' => 'akina_meta',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Site keywords', 'sakura'), /*网站关键词*/
        'desc' => __('Each keyword is divided by a comma "," and the number is within 5.', 'sakura'), /*各关键字间用半角逗号","分割，数量在5个以内最佳。*/
        'id' => 'akina_meta_keywords',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Site descriptions', 'sakura'), /*网站描述*/
        'desc' => __('Describe the site in concise text, with a maximum of 120 words.', 'sakura'), /*用简洁的文字描述本站点，字数建议在120个字以内。*/
        'id' => 'akina_meta_description',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Expand the nav menu', 'sakura'), /*展开导航菜单*/
        'desc' => __('Check to enable, default shrink', 'sakura'), /*勾选开启，默认收缩*/
        'id' => 'shownav',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Head decoration', 'sakura'), /*头部装饰图*/
        'desc' => __('Enable by default, check off, display on the article page, separate page and category page', 'sakura'), /*默认开启，勾选关闭，显示在文章页面，独立页面以及分类页*/
        'id' => 'patternimg',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Search button', 'sakura'), /*搜索按钮*/
        'id' => 'top_search',
        'std' => "yes",
        'type' => "radio",
        'options' => array(
            'yes' => __('Open', 'sakura'),
            'no' => __('Close', 'sakura'),
        ));

    $options[] = array(
        'name' => __('Home article style', 'sakura'), /*首页文章风格*/
        'id' => 'post_list_style',
        'std' => "imageflow",
        'type' => "radio",
        'options' => array(
            'standard' => __('Standard', 'sakura'), /*标准*/
            'imageflow' => __('Graphic', 'sakura'), /*图文*/
        ));

    $options[] = array(
        'name' => __('Home article feature images (only valid for standard mode)', 'sakura'), /*首页文章特色图（仅对标准风格生效）*/
        'id' => 'list_type',
        'std' => "round",
        'type' => "radio",
        'options' => array(
            'round' => __('Round', 'sakura'), /*圆形*/
            'square' => __('Square', 'sakura'), /*方形*/
        ));

    $options[] = array(
        'name' => __('Home article feature images alignment (only for graphic mode, default left and right alternate)', 'sakura'), /*首页文章特色图对齐方式（仅对图文风格生效，默认左右交替）*/
        'id' => 'feature_align',
        'std' => "alternate",
        'type' => "radio",
        'options' => array(
            'left' => __('Left', 'sakura'), /*向左对齐*/
            'right' => __('Right', 'sakura'), /*向右对齐*/
            'alternate' => __('Alternate', 'sakura'), /*左右交替*/
        ));

    $options[] = array(
        'name' => __('Comment shrink', 'sakura'), /*评论收缩*/
        'id' => 'toggle-menu',
        'std' => "yes",
        'type' => "radio",
        'options' => array(
            'yes' => __('Open', 'sakura'), /*开启*/
            'no' => __('Close', 'sakura'), /*关闭*/
        ));

    $options[] = array(
        'name' => __('Display author information at the end of the article?', 'sakura'), /*文章末尾显示作者信息？*/
        'desc' => __('Check to enable', 'sakura'), /*勾选启用*/
        'id' => 'show_authorprofile',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Paging mode', 'sakura'), /*分页模式*/
        'id' => 'pagenav_style',
        'std' => "ajax",
        'type' => "radio",
        'options' => array(
            'ajax' => __('Ajax load', 'sakura'), /*ajax加载*/
            'np' => __('Previous and next page', 'sakura'), /*上一页和下一页*/
        ));

    $options[] = array(
        'name' => __('Automatically load the next page', 'sakura'), /*自动加载下一页*/
        'desc' => __('(seconds) Set to automatically load the next page time, the default is not automatically loaded', 'sakura'), /*（秒）设置自动加载下一页时间，默认不自动加载*/
        'id' => 'auto_load_post',
        'std' => '233',
        'type' => 'select',
        'options' => array(
            '0' => __('0', 'sakura'),
            '1' => __('1', 'sakura'),
            '2' => __('2', 'sakura'),
            '3' => __('3', 'sakura'),
            '4' => __('4', 'sakura'),
            '5' => __('5', 'sakura'),
            '6' => __('6', 'sakura'),
            '7' => __('7', 'sakura'),
            '8' => __('8', 'sakura'),
            '9' => __('9', 'sakura'),
            '10' => __('10', 'sakura'),
            '233' => __('Do not load automatically', 'sakura'), /*不自动加载*/
        ));

    //$options[] = array(
    //    'name' => __('Blogger description', 'sakura'), /*博主描述*/
    //    'desc' => __('A self-described statement', 'sakura'), /*一段自我描述的话*/
    //    'id' => 'admin_des',
    //    'std' => '粉色的花瓣，美丽地缠绕在身上。依在风里。',
    //    'type' => 'textarea');

    $options[] = array(
        'name' => __('Footer info', 'sakura'), /*页脚信息*/
        'desc' => __('Footer description, support for HTML code', 'sakura'), /*页脚说明文字，支持HTML代码*/
        'id' => 'footer_info',
        'std' => 'Copyright &copy; by Mashiro All Rights Reserved.',
        'type' => 'textarea');

    $options[] = array(
        'name' => __('Google analytics', 'sakura'), /*Google 统计代码*/
        'desc' => __('UA-xxxxx-x', 'sakura'),
        'id' => 'google_analytics_id',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('CNZZ Statistics (not recommand)', 'sakura'), /*站长统计（不建议使用）*/
        'desc' => __('Statistics code, which will be invisible in web page.', 'sakura'), /*填写统计代码，将被隐藏*/
        'id' => 'site_statistics',
        'std' => '',
        'type' => 'textarea');

    $options[] = array(
        'name' => __('Customize CSS styles', 'sakura'), /*自定义CSS样式*/
        'desc' => __('Fill in the CSS code directly, no need to write style tags', 'sakura'), /*直接填写CSS代码，不需要写style标签*/
        'id' => 'site_custom_style',
        'std' => '',
        'type' => 'textarea');

    //第一屏
    $options[] = array(
        'name' => __('First screen', 'sakura'), /*第一屏*/
        'type' => 'heading');

    $options[] = array(
        'name' => __('Main switch', 'sakura'), /*总开关*/
        'desc' => __('Default on, check off', 'sakura'), /*默认开启，勾选关闭*/
        'id' => 'head_focus',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Social information', 'sakura'), /*社交信息*/
        'desc' => __('Enable by default, check off, display avatar, signature, SNS', 'sakura'), /*默认开启，勾选关闭，显示头像、签名、SNS*/
        'id' => 'focus_infos',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Social information style', 'sakura'), /*社交信息样式*/
        'id' => 'social_style',
        'std' => "v1",
        'type' => "radio",
        'options' => array(
            'v2' => __('Merge with signature', 'sakura'), /*与签名合并*/
            'v1' => __('Independent line', 'sakura'), /*独立成行*/
        ));

    $options[] = array(
        'name' => __('Cover manifest', 'sakura'), /*封面图片库选项*/
        'desc' => __('Select how to call the cover random image', 'sakura'), /*选择封面随机图的调用方式*/
        'id' => 'cover_cdn_options',
        'std' => "type_3",
        'type' => "select",
        'options' => array(
            'type_1' => __('webp images (optimization)', 'sakura'), /*webp优化随机图*/
            'type_2' => __('built-in api (default)', 'sakura'), /*内置原图随机图*/
            'type_3' => __('custom api (advanced)', 'sakura'), /*外部随机图API*/
        )
    );

    $options[] = array(
        'name' => __('Cover images url', 'sakura'), /*图片库url*/
        'desc' => sprintf(__('Fill in the manifest path for random picture display, please refer to <a href = "https: //github.com/mashirozx/Sakura/wiki/options">Wiki </a>. If you select webp images above, click <a href = "%s">here</a> to update manifest', 'sakura'), rest_url('sakura/v1/database/update')), /*填写 manifest 路径，更多信息请参考<a href="https://github.com/mashirozx/Sakura/wiki/options">Wiki</a>,，如果你在上面选择了webp优化，点击<a href = "%s">这里</a>更新 manifest*/
        'id' => 'cover_cdn',
        'std' => 'https://api.btstu.cn/sjbz/api.php?lx=dongman&format=images',
        'type' => 'text');

    $options[] = array(
        'name' => __('full-screen display', 'sakura'), /*全屏显示*/
        'desc' => __('Default on, check off', 'sakura'), /*默认开启，勾选关闭*/
        'id' => 'focus_height',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Enable video', 'sakura'), /*开启视频*/
        'desc' => __('Check on', 'sakura'), /*勾选开启*/
        'id' => 'focus_amv',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Live', 'sakura'),
        'desc' => __('Check to enable, the video will continue to play automatically, you need to enable Pjax', 'sakura'), /*勾选开启，视频自动续播，需要开启Pjax功能*/
        'id' => 'focus_mvlive',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Video address', 'sakura'), /*视频地址*/
        'desc' => __('The source address of the video, the address is spliced below the video name, the slash is not required at the end of the address', 'sakura'), /*视频的来源地址，该地址拼接下面的视频名，地址尾部不需要加斜杠*/
        'id' => 'amv_url',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Video name', 'sakura'), /*视频名称*/
        'desc' => __('abc.mp4, just fill in the video file name abc, multiple videos separated by commas such as abc, efg, do not care about the order, because the loading is random extraction', 'sakura'), /*abc.mp4 ，只需要填写视频文件名 abc 即可，多个用英文逗号隔开如 abc,efg ，无需在意顺序，因为加载是随机的抽取的 */
        'id' => 'amv_title',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Background image filter', 'sakura'), /*背景图滤镜*/
        'id' => 'focus_img_filter',
        'std' => "filter-undertint",
        'type' => "radio",
        'options' => array(
            'filter-nothing' => __('Nothing', 'sakura'), /*无*/
            'filter-undertint' => __('Undertint', 'sakura'), /*浅色*/
            'filter-dim' => __('Dim', 'sakura'), /*暗淡*/
            'filter-grid' => __('Grid', 'sakura'), /*网格*/
            'filter-dot' => __('Dot', 'sakura'), /*点点*/
        ));

    $options[] = array(
        'name' => __('Whether to turn on the top-feature', 'sakura'), /*是否开启聚焦*/
        'desc' => __('Default on', 'sakura'),
        'id' => 'top_feature',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Top-feature style', 'sakura'), /*聚焦样式*/
        'id' => 'top_feature_style',
        'std' => "left_and_right",
        'type' => "radio",
        'options' => array(
            'left_and_right' => __('Alternate left and right', 'sakura'), /*左右交替*/
            'bottom_to_top' => __('From bottom to top', 'sakura'), /*从下往上*/
        ));

    $options[] = array(
        'name' => __('Top-feature title', 'sakura'), /*聚焦标题*/
        'desc' => __('Default is Discovery, you can also change it to other, of course you can\'t use it as an advertisement!Not allowed!!', 'sakura'), /*默认为聚焦，你也可以修改为其他，当然不能当广告用！不允许！！*/
        'id' => 'feature_title',
        'std' => '聚焦',
        'class' => 'mini',
        'type' => 'text');

    $options[] = array(
        'name' => __('Top-feature 1 image', 'sakura'), /*聚焦图一*/
        'desc' => __('size 257px*160px', 'sakura'), /*尺寸257px*160px*/
        'id' => 'feature1_img',
        'std' => "https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/temp.png",
        'type' => 'upload');

    $options[] = array(
        'name' => __('Top-feature 1 title', 'sakura'), /*聚焦图一标题*/
        'desc' => __('Top-feature 1 title', 'sakura'), /*聚焦图一标题*/
        'id' => 'feature1_title',
        'std' => 'feature1',
        'type' => 'text');

    $options[] = array(
        'name' => __('Top-feature 1 description', 'sakura'), /*聚焦图一描述*/
        'desc' => __('Top-feature 1 description', 'sakura'), /*聚焦图一描述*/
        'id' => 'feature1_description',
        'std' => 'Description goes here 1',
        'type' => 'text');

    $options[] = array(
        'name' => __('Top-feature 1 link', 'sakura'), /*聚焦图一链接*/
        'desc' => __('Top-feature 1 link', 'sakura'), /*聚焦图一链接*/
        'id' => 'feature1_link',
        'std' => '#',
        'type' => 'text');

    $options[] = array(
        'name' => __('Top-feature 2 image', 'sakura'), /*聚焦图二*/
        'desc' => __('size 257px*160px', 'sakura'), /*尺寸257px*160px*/
        'id' => 'feature2_img',
        'std' => "https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/temp.png",
        'type' => 'upload');

    $options[] = array(
        'name' => __('Top-feature 2 title', 'sakura'), /*聚焦图二标题*/
        'desc' => __('Top-feature 2 title', 'sakura'), /*聚焦图二标题*/
        'id' => 'feature2_title',
        'std' => 'feature2',
        'type' => 'text');

    $options[] = array(
        'name' => __('Top-feature 2 description', 'sakura'), /*聚焦图二描述*/
        'desc' => __('Top-feature 2 description', 'sakura'), /*聚焦图二描述*/
        'id' => 'feature2_description',
        'std' => 'Description goes here 2',
        'type' => 'text');

    $options[] = array(
        'name' => __('Top-feature 2 link', 'sakura'), /*聚焦图二链接*/
        'desc' => __('Top-feature 2 link', 'sakura'), /*聚焦图二链接*/
        'id' => 'feature2_link',
        'std' => '#',
        'type' => 'text');

    $options[] = array(
        'name' => __('Top-feature 3 image', 'sakura'), /*聚焦图三*/
        'desc' => __('size 257px*160px', 'sakura'), /*尺寸257px*160px*/
        'id' => 'feature3_img',
        'std' => "https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/temp.png",
        'type' => 'upload');

    $options[] = array(
        'name' => __('Top-feature 3 title', 'sakura'), /*聚焦图三标题*/
        'desc' => __('Top-feature 3 title', 'sakura'), /*聚焦图三标题*/
        'id' => 'feature3_title',
        'std' => 'feature3',
        'type' => 'text');

    $options[] = array(
        'name' => __('Top-feature 3 description', 'sakura'), /*聚焦图三描述*/
        'desc' => __('Top-feature 3 description', 'sakura'), /*聚焦图三描述*/
        'id' => 'feature3_description',
        'std' => 'Description goes here 3',
        'type' => 'text');

    $options[] = array(
        'name' => __('Top-feature 3 link', 'sakura'), /*聚焦图三链接*/
        'desc' => __('Top-feature 3 link', 'sakura'), /*聚焦图三链接*/
        'id' => 'feature3_link',
        'std' => '#',
        'type' => 'text');

    //文章页
    $options[] = array(
        'name' => __('Post page', 'sakura'), /*文章页*/
        'type' => 'heading');

    $options[] = array(
        'name' => __('Post style', 'sakura'), /*文章样式*/
        'id' => 'entry_content_theme',
        'std' => "sakura",
        'type' => "radio",
        'options' => array(
            'sakura' => __('Sakura', 'sakura'), /*默认样式*/
            'github' => __('GitHub', 'sakura'),
        ));

    $options[] = array(
        'name' => __('Post like', 'sakura'), /*文章点赞*/
        'id' => 'post_like',
        'std' => "yes",
        'type' => "radio",
        'options' => array(
            'yes' => __('Open', 'sakura'), /*开启*/
            'no' => __('Close', 'sakura'), /*关闭*/
        ));

    $options[] = array(
        'name' => __('Post share', 'sakura'), /*文章分享*/
        'id' => 'post_share',
        'std' => "yes",
        'type' => "radio",
        'options' => array(
            'yes' => __('Open', 'sakura'), /*开启*/
            'no' => __('Close', 'sakura'), /*关闭*/
        ));

    $options[] = array(
        'name' => __('Previous and Next', 'sakura'), /*上一篇下一篇*/
        'id' => 'post_nepre',
        'std' => "yes",
        'type' => "radio",
        'options' => array(
            'yes' => __('Open', 'sakura'), /*开启*/
            'no' => __('Close', 'sakura'), /*关闭*/
        ));

    $options[] = array(
        'name' => __('Author profile', 'sakura'), /*博主信息*/
        'id' => 'author_profile',
        'std' => "yes",
        'type' => "radio",
        'options' => array(
            'yes' => __('Open', 'sakura'), /*开启*/
            'no' => __('Close', 'sakura'), /*关闭*/
        ));

    $options[] = array(
        'name' => __('Alipay reward', 'sakura'), /*支付宝打赏*/
        'desc' => __('Alipay qrcode', 'sakura'), /*支付宝二维码*/
        'id' => 'alipay_code',
        'type' => 'upload');

    $options[] = array(
        'name' => __('Wechat reward', 'sakura'), /*微信打赏*/
        'desc' => __('Wechat qrcode ', 'sakura'), /*微信二维码*/
        'id' => 'wechat_code',
        'type' => 'upload');

    //社交选项
    $options[] = array(
        'name' => __('Social network', 'sakura'), /*社交网络*/
        'type' => 'heading');

    $options[] = array(
        'name' => __('Wechat', 'sakura'), /*微信*/
        'desc' => __('Wechat qrcode', 'sakura'), /*微信二维码*/
        'id' => 'wechat',
        'type' => 'upload');

    $options[] = array(
        'name' => __('Sina Weibo', 'sakura'), /*新浪微博*/
        'desc' => __('Sina Weibo address', 'sakura'), /*新浪微博地址*/
        'id' => 'sina',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Tencent QQ', 'sakura'), /*腾讯QQ*/
        'desc' => __('tencent://message/?uin={{QQ number}}. for example, tencent://message/?uin=123456', 'sakura'), /*tencent://message/?uin={{QQ号码}}，如tencent://message/?uin=123456*/
        'id' => 'qq',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Telegram', 'sakura'),
        'desc' => __('Telegram link', 'sakura'), /*Telegram链接*/
        'id' => 'telegram',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Qzone', 'sakura'), /*QQ空间*/
        'desc' => __('Qzone address', 'sakura'), /*QQ空间地址*/
        'id' => 'qzone',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('GitHub', 'sakura'),
        'desc' => __('GitHub address', 'sakura'), /*GitHub地址*/
        'id' => 'github',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Lofter', 'sakura'),
        'desc' => __('Lofter address', 'sakura'), /*lofter地址*/
        'id' => 'lofter',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('BiliBili', 'sakura'),
        'desc' => __('BiliBili address', 'sakura'), /*B站地址*/
        'id' => 'bili',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Youku video', 'sakura'), /*优酷视频*/
        'desc' => __('Youku video address', 'sakura'), /*优酷地址*/
        'id' => 'youku',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Netease Cloud Music', 'sakura'), /*网易云音乐*/
        'desc' => __('Netease Cloud Music address', 'sakura'), /*网易云音乐地址*/
        'id' => 'wangyiyun',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Twitter', 'sakura'),
        'desc' => __('Twitter address', 'sakura'), /*推特地址*/
        'id' => 'twitter',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Facebook', 'sakura'),
        'desc' => __('Facebook address', 'sakura'), /*脸书地址*/
        'id' => 'facebook',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Jianshu', 'sakura'), /*简书*/
        'desc' => __('Jianshu address', 'sakura'), /*简书地址*/
        'id' => 'jianshu',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('CSDN', 'sakura'),
        'desc' => __('CSND community address', 'sakura'), /*CSND社区地址*/
        'id' => 'csdn',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Zhihu', 'sakura'), /*知乎*/
        'desc' => __('Zhihu address', 'sakura'), /*知乎地址*/
        'id' => 'zhihu',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Email-name', 'sakura'), /*邮箱-用户名*/
        'desc' => __('The name part of name@domain.com, only the frontend has js runtime environment can get the full address, you can rest assured to fill in', 'sakura'), /*name@domain.com 的 name 部分，前端仅具有js运行环境时才能获取完整地址，可放心填写*/
        'id' => 'email_name',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Email-domain', 'sakura'), /*邮箱-域名*/
        'desc' => __('The domain.com part of name@domain.com', 'sakura'), /*ame@domain.com 的 domain.com 部分*/
        'id' => 'email_domain',
        'std' => '',
        'type' => 'text');

    //后台配置
    $options[] = array(
        'name' => __('Dashboard configuration', 'sakura'), /*后台配置*/
        'type' => 'heading');

    //后台面板自定义配色方案
    $options[] = array(
        'name' => __('Dashboard panel custom color scheme', 'sakura'), /*后台面板自定义配色方案*/
        'desc' => __('You can design the dashboard panel (/wp-admin/) style yourself below, but before you start, please go to <a href="/wp-admin/profile.php">here</a> to change the color scheme to custom.(Custom).<br><b>Tip: </b>How to match colors? Maybe <a href="https://mashiro.top/color-thief/">this</a> can help you.', 'sakura'), /*你可以在下面自行设计后台面板（/wp-admin/）样式，不过在开始之前请到<a href="/wp-admin/profile.php">这里</a>将配色方案改为自定义（Custom）。<br><b>Tip: </b>如何搭配颜色？或许<a href="https://mashiro.top/color-thief/">这个</a>可以帮到你。*/
        'id' => 'scheme_tip',
        'std' => '',
        'type' => 'typography ');

    $options[] = array(
        'name' => __('Panel main color A', 'sakura'), /*面板主色调A*/
        'id' => 'dash_scheme_color_a',
        'std' => "#c6742b",
        'desc' => __('<i>(array) (optional)</i> An array of CSS color definitions which are used to give the user a feel for the theme.', 'sakura'),
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Panel main color B', 'sakura'),
        'id' => 'dash_scheme_color_b',
        'std' => "#d88e4c",
        'desc' => __('<i>(array) (optional)</i> An array of CSS color definitions which are used to give the user a feel for the theme.', 'sakura'),
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Panel main color C', 'sakura'),
        'id' => 'dash_scheme_color_c',
        'std' => "#695644",
        'desc' => __('<i>(array) (optional)</i> An array of CSS color definitions which are used to give the user a feel for the theme.', 'sakura'),
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Panel main color D', 'sakura'),
        'id' => 'dash_scheme_color_d',
        'std' => "#a19780",
        'desc' => __('<i>(array) (optional)</i> An array of CSS color definitions which are used to give the user a feel for the theme.', 'sakura'),
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Panel icon color——base', 'sakura'), /*面板图标配色——base*/
        'id' => 'dash_scheme_color_base',
        'std' => "#e5f8ff",
        'desc' => __('<i>(array) (optional)</i> An array of CSS color definitions used to color any SVG icons.', 'sakura'),
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Panel icon color——focus', 'sakura'),
        'id' => 'dash_scheme_color_focus',
        'std' => "#fff",
        'desc' => __('<i>(array) (optional)</i> An array of CSS color definitions used to color any SVG icons.', 'sakura'),
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Panel icon color——current', 'sakura'),
        'id' => 'dash_scheme_color_current',
        'std' => "#fff",
        'desc' => __('<i>(array) (optional)</i> An array of CSS color definitions used to color any SVG icons.', 'sakura'),
        'type' => "color",
    );

    $options[] = array(
        'name' => __('Other custom panel styles(CSS)', 'sakura'), /*其他自定义面板样式(CSS)*/
        'desc' => __('If you need to adjust other styles of the panel, put the style here.', 'sakura'), /*如果还需要对面板其他样式进行调整可以把style放到这里*/
        'id' => 'dash_scheme_css_rules',
        'std' => '#adminmenu .wp-has-current-submenu .wp-submenu a,#adminmenu .wp-has-current-submenu.opensub .wp-submenu a,#adminmenu .wp-submenu a,#adminmenu a.wp-has-current-submenu:focus+.wp-submenu a,#wpadminbar .ab-submenu .ab-item,#wpadminbar .quicklinks .menupop ul li a,#wpadminbar .quicklinks .menupop.hover ul li a,#wpadminbar.nojs .quicklinks .menupop:hover ul li a,.folded #adminmenu .wp-has-current-submenu .wp-submenu a{color:#f3f2f1}body{background-image:url(https://view.moezx.cc/images/2019/04/21/windows10-2019-4-21-i3.jpg);background-size:cover;background-repeat:no-repeat;background-attachment:fixed;}#wpcontent{background:rgba(255,255,255,.8)}',
        'type' => 'textarea');

    $options[] = array(
        'name' => __('Login interface background image', 'sakura'), /*后台登陆界面背景图*/
        'desc' => __('Use the default image if left this blank', 'sakura'), /*该地址为空则使用默认图片*/
        'id' => 'login_bg',
        'type' => 'upload');

    $options[] = array(
        'name' => __('Login interface logo', 'sakura'), /*后台登陆界面logo*/
        'desc' => __('Used for login interface display', 'sakura'), /*用于登录界面显示*/
        'id' => 'logo_img',
        'std' => "https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/img/mashiro-logo-s.png",
        'type' => 'upload');

    $options[] = array(
        'name' => __('Login/registration related settings', 'sakura'), /*登陆/注册相关设定*/
        'desc' => __(' ', 'space', 'sakura'),
        'id' => 'login_tip',
        'std' => '',
        'type' => 'typography ');

    $options[] = array(
        'name' => __('Specify login address', 'sakura'), /*指定登录地址*/
        'desc' => __('Forcibly do not use the background address to log in, fill in the new landing page address, such as http://www.xxx.com/login [Note] Before you fill out, test your new page can be opened normally, so as not to enter the background or other problems happening', 'sakura'), /*强制不使用后台地址登陆，填写新建的登陆页面地址，比如 http://www.xxx.com/login【注意】填写前先测试下你新建的页面是可以正常打开的，以免造成无法进入后台等情况*/
        'id' => 'exlogin_url',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Specify registered address', 'sakura'), /*指定注册地址*/
        'desc' => __('This link is used on the login page as a registration entry', 'sakura'), /*该链接使用在登录页面作为注册入口，建议填写*/
        'id' => 'exregister_url',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Allow users to register', 'sakura'), /*允许用户注册*/
        'desc' => __('Check to allow users to register at the frontend', 'sakura'), /*勾选开启，允许用户在前台注册*/
        'id' => 'ex_register_open',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Automatically redirect after login', 'sakura'), /*登录后自动跳转*/
        'desc' => __('After checken, the administrator redirects to the background and the user redirects to the home page.', 'sakura'), /*勾选开启，管理员跳转至后台，用户跳转至主页*/
        'id' => 'login_urlskip',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Registration verification (frontend only, backend forced open)', 'sakura'), /*注册验证（仅前端，后端强制开启）*/
        'desc' => __('Check to enable slide verification', 'sakura'), /*勾选开启滑动验证*/
        'id' => 'login_validate',
        'std' => '0',
        'type' => 'checkbox');

    //CDN 优化
    $options[] = array(
        'name' => __('CDN', 'sakura'),
        'type' => 'heading');

    $options[] = array(
        'name' => __('Images CDN', 'sakura'), /*图片库*/
        'desc' => __('Note: Fill in the format http(s)://your CDN domain name/. <br>In other words, the original path is http://your.domain/wp-content/uploads/2018/05/xx.png and the picture will load from http://your CDN domain/2018/05/xx.png', 'sakura'), /*注意：填写格式为 http(s)://你的CDN域名/。<br>也就是说，原路径为 http://your.domain/wp-content/uploads/2018/05/xx.png 的图片将从 http://你的CDN域名/2018/05/xx.png 加载*/
        'id' => 'qiniu_cdn',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Use the front-end library locally (lib.js、lib.css)', 'sakura'), /*本地调用前端库（lib.js、lib.css）*/
        'desc' => __('The front-end library don\'t load from jsDelivr, not recommand', 'sakura'), /*前端库不走 jsDelivr，不建议启用*/
        'id' => 'jsdelivr_cdn_test',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Use js and css file of the theme (sakura-app.js、style.css) locally', 'sakura'), /*本地调用主题 js、css 文件（sakura-app.js、style.css）*/
        'desc' => __('The js and css files of the theme do not load from jsDelivr, please open when DIY', 'sakura'), /*主题的 js、css 文件不走 jsDelivr，DIY 时请开启*/
        'id' => 'app_no_jsdelivr_cdn',
        'std' => '1',
        'type' => 'checkbox');

    //其他
    $options[] = array(
        'name' => __('Others', 'sakura'), /*其他*/
        'type' => 'heading');

    $options[] = array(
        'name' => __('About', 'sakura'), /*关于*/
        'desc' => sprintf(__('Sakurairo v %s  |  <a href="https://2heng.xin/theme-sakura/">Theme document</a>  |  <a href="https://github.com/mirai-mamori/Sakurairo">Source code</a><a href="https://github.com/mirai-mamori/Sakurairo/releases/latest"><img src="https://img.shields.io/github/v/release/mirai-mamori/Sakurairo.svg?style=flat-square" alt="GitHub release"></a>', 'sakura'), SAKURA_VERSION), /*Theme Sakura v'.SAKURA_VERSION.'  |  <a href="https://2heng.xin/theme-sakura/">主题说明</a>  |  <a href="https://github.com/mashirozx/Sakura/">源码</a><a href="https://github.com/mashirozx/Sakura/releases/latest"><img src="https://img.shields.io/github/release/mashirozx/Sakura.svg?style=flat-square" alt="GitHub release"></a>*/
        'id' => 'theme_intro',
        'std' => '',
        'type' => 'typography ');

    $options[] = array(
        'name' => __('Check for Updates', 'sakura'), /*检查更新*/
        'desc' => '<a href="https://github.com/mirai-mamori/Sakurairo/releases/latest">Download the latest version</a>',
        'id' => "release_info",
        'std' => "tag",
        'type' => "images",
        'options' => array(
            'tag' => 'https://img.shields.io/github/v/release/mirai-mamori/Sakurairo.svg?style=flat-square',
        ),
    );

    $options[] = array(
        'name' => __('Footer float music player', 'sakura'), /*页脚悬浮播放器*/
        'desc' => __('If you don\'t need the player just leave it blank.Fill in the "song list" ID of Netease Cloud Music, eg: https://music.163.com/#/playlist?id=2288037900 The ID is 2288037900', 'sakura'), /*如果不需要播放器留空即可。填写网易云音乐的「歌单」ID，eg：https://music.163.com/#/playlist?id=2288037900的ID是2288037900*/
        'id' => 'playlist_id',
        'std' => '2288037900',
        'type' => 'text');

    $options[] = array(
        'name' => __('Version Control', 'sakura'), /*版本控制*/
        'desc' => __('Used to update frontend cookies and browser caches, any string can be used', 'sakura'), /*用于更新前端 cookie 及浏览器缓存，可使用任意字符串*/
        'id' => 'cookie_version',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Enable PJAX (recommand on)', 'sakura'), /*开启PJAX局部刷新（建议开启）*/
        'desc' => __('The principle is the same as Ajax', 'sakura'), /*原理与Ajax相同*/
        'id' => 'poi_pjax',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Enable NProgress progress bar', 'sakura'), /*开启NProgress加载进度条*/
        'desc' => __('Default off, check on', 'sakura'), /*默认不开启，勾选开启*/
        'id' => 'nprogress_on',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Enable sidebar widget', 'sakura'), /*支持侧栏小部件*/
        'desc' => __('Default off, check on', 'sakura'), /*默认不开启，勾选开启*/
        'id' => 'sakura_widget',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Enable Announcement', 'sakura'),
        'desc' => __('Default off, check on', 'sakura'), /*默认不显示，勾选开启*/
        'id' => 'head_notice',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Announcement content', 'sakura'),
        'desc' => __('Announcement content, the text exceeds 142 bytes will be scrolled display (mobile device is invalid)', 'sakura'), /*公告内容，文字超出142个字节将会被滚动显示（移动端无效），一个汉字 = 3字节，一个字母 = 1字节，自己计算吧*/
        'id' => 'notice_title',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Bilibili UID', 'sakura'), /*bilibiliUID*/
        'desc' => __('Fill in your UID, eg.https://space.bilibili.com/13972644/, only fill in with the number part.', 'sakura'),
        'id' => 'bilibili_id',
        'std' => '13972644',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Bilibili Cookie', 'sakura'), /*Bilibili Cookie*/
        'desc' => __('Fill in your Cookies, go to your bilibili homepage, you can get cookies in brownser network pannel with pressing F12. If left this blank, you\'ll not get the progress.', 'sakura'),
        'id' => 'bilibili_cookie',
        'std' => 'LIVE_BUVID=',
        'type' => 'textarea');

    $options[] = array(
        'name' => __('The categories of articles that don\'t not show on homepage', 'sakura'), /*首页不显示的分类文章*/
        'desc' => __('Fill in category ID, multiple IDs are divided by a comma ","', 'sakura'), /*填写分类ID，多个用英文“ , ”分开*/
        'id' => 'classify_display',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Images category', 'sakura'), /*图片展示分类*/
        'desc' => __('Fill in category ID, multiple IDs are divided by a comma ","', 'sakura'), /*填写分类ID，多个用英文“ , ”分开*/
        'id' => 'image_category',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Statistics Interface', 'sakura'), /*统计接口*/
        'id' => 'statistics_api',
        'std' => "theme_build_in",
        'type' => "radio",
        'options' => array(
            'wp_statistics' => __('WP-Statistics plugin (Professional statistics, can exclude invalid access)', 'sakura'), /*WP-Statistics 插件（专业性统计，可排除无效访问）*/
            'theme_build_in' => __('Theme built-in (simple statistics, calculate each page access request)', 'sakura'), /*主题内建（简单的统计，计算每一次页面访问请求）*/
        ));

    $options[] = array(
        'name' => __('Statistical data display format', 'sakura'), /*统计数据显示格式*/
        'id' => 'statistics_format',
        'std' => "type_1",
        'type' => "radio",
        'options' => array(
            'type_1' => __('23333 Views (default)', 'sakura'), /*23333 次访问（默认）*/
            'type_2' => __('23,333 Views (britain)', 'sakura'), /*23,333 次访问（英式）'*/
            'type_3' => __('23 333 Views (french)', 'sakura'), /*23 333 次访问（法式）*/
            'type_4' => __('23k Views (chinese)', 'sakura'), /*23k 次访问（中式）*/
        ));

    $options[] = array(
        'name' => __('Gravatar avatar proxy', 'sakura'),
        'desc' => __('A front-ed proxy for Gravatar, eg. gravatar.2heng.xin/avatar . Leave it blank if you do not need.', 'sakura'),
        'id' => 'gravatar_proxy',
        'std' => "cn.gravatar.com",
        'type' => "text");    

    $options[] = array(
        'name' => __('Comment image upload API', 'sakura'), /*评论图片上传接口*/
        'id' => 'img_upload_api',
        'std' => "imgur",
        'type' => "radio",
        'options' => array(
            'imgur' => __('Imgur (https://imgur.com)', 'sakura'),
            'smms' => __('SM.MS (https://sm.ms)', 'sakura'),
            'chevereto' => __('Chevereto (https://chevereto.com)', 'sakura'),
        ));

    $options[] = array(
        'name' => __('Imgur Client ID', 'sakura'),
        'desc' => __('Register your application <a href="https://api.imgur.com/oauth2/addclient">here</a>, note we only need the Client ID here.', 'sakura'),
        'id' => 'imgur_client_id',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('SM.MS Secret Token', 'sakura'),
        'desc' => __('Register your application <a href="https://sm.ms/home/apitoken">here</a>.', 'sakura'),
        'id' => 'smms_client_id',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Chevereto API v1 key', 'sakura'),
        'desc' => __('Get your API key here: ' . akina_option('cheverto_url') . '/dashboard/settings/api', 'sakura'),
        'id' => 'chevereto_api_key',
        'std' => '',
        'type' => 'text');

    $options[] = array(
        'name' => __('Chevereto URL', 'sakura'),
        'desc' => __('Your Chevereto homepage url, no slash in the end, eg. https://your.cherverto.com', 'sakura'),
        'id' => 'cheverto_url',
        'std' => 'https://your.cherverto.com',
        'type' => 'text');

    $options[] = array(
        'name' => __('Comment images proxy', 'sakura'),
        'desc' => __('A front-ed proxy for the uploaded images. Leave it blank if you do not need.', 'sakura'),
        'id' => 'cmt_image_proxy',
        'std' => 'https://images.weserv.nl/?url=',
        'type' => 'text');

    $options[] = array(
        'name' => __('Imgur upload proxy', 'sakura'),
        'desc' => __('A back-ed proxy to upload images. You may set a self hosted proxy with Nginx, following my <a href="https://2heng.xin/2018/06/06/javascript-upload-images-with-imgur-api/">turtal</a>. This feature is mainly for Chinese who cannot access to Imgur due to the GFW. The default and official setting is 【<a href="https://api.imgur.com/3/image/">https://api.imgur.com/3/image/</a>】', 'sakura'),
        'id' => 'imgur_upload_image_proxy',
        'std' => 'https://api.imgur.com/3/image/',
        'type' => 'text');

    $options[] = array(
        'name' => __('Enable live search', 'sakura'), /*启用实时搜索*/
        'desc' => __('Real-time search in the foreground, call the Rest API to update the cache every hour, you can manually set the cache time in api.php', 'sakura'), /*前台实现实时搜索，调用 Rest API 每小时更新一次缓存，可在 functions.php 里手动设置缓存时间*/
        'id' => 'live_search',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Include comments in live search', 'sakura'), /*实时搜索包含评论*/
        'desc' => __('Search for comments in real-time search (not recommended if there are too many comments on the site)', 'sakura'), /*在实时搜索中搜索评论（如果网站评论数量太多不建议开启）*/
        'id' => 'live_search_comment',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Enable baguetteBox', 'sakura'), /*启用 baguetteBox*/
        'desc' => __('Default off，<a href="https://github.com/mashirozx/Sakura/wiki/Fancybox">please read wiki</a>', 'sakura'), /*默认禁用，<a href="https://github.com/mashirozx/Sakura/wiki/Fancybox">请阅读说明</a>*/
        'id' => 'image_viewer',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Enable lazyload in posts', 'sakura'), /*文章内图片启用 lazyload*/
        'desc' => __('Default on', 'sakura'), /*默认启用*/
        'id' => 'lazyload',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('lazyload spinner', 'sakura'),
        'desc' => __('The placeholder to display when the image loads, fill in the image url', 'sakura'), /*图片加载时要显示的占位图，填写图片 url*/
        'id' => 'lazyload_spinner',
        'std' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/loadimg/inload.svg',
        'type' => 'text');

    $options[] = array(
        'name' => __('Whether to enable the clipboard copyright', 'sakura'), /*是否开启剪贴板版权标识*/
        'desc' => __('Automatically add a copyright to the clipboard when copying more than 30 bytes, which is enabled by default.', 'sakura'), /*复制超过30个字节时自动向剪贴板添加版权标识，默认开启*/
        'id' => 'clipboard_copyright',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Email address prefix', 'sakura'), /*发件地址前缀*/
        'desc' => __('For sending system mail, the sender address displayed in the user\'s mailbox, do not use Chinese, the default system email address is bibi@your_domain_name', 'sakura'), /*用于发送系统邮件，在用户的邮箱中显示的发件人地址，不要使用中文，默认系统邮件地址为 bibi@你的域名*/
        'id' => 'mail_user_name',
        'std' => 'bibi',
        'type' => 'text');

    $options[] = array(
        'name' => __('Comments reply notification', 'sakura'), /*邮件回复通知*/
        'desc' => __('WordPress will use email to notify users when their comments receive a reply by default. Tick this item allows users to set their own comments reply notification', 'sakura'), /*WordPress默认会使用邮件通知用户评论收到回复，开启此项允许用户设置自己的评论收到回复时是否使用邮件通知*/
        'id' => 'mail_notify',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Administrator comment notification', 'sakura'), /*邮件回复通知管理员*/
        'desc' => __('Whether to use email notification when the administrator\'s comments receive a reply', 'sakura'), /*当管理员评论收到回复时是否使用邮件通知*/
        'id' => 'admin_notify',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Enable private comment', 'sakura'), /*允许私密评论*/
        'desc' => __('Allow users to set their own comments to be invisible to others', 'sakura'), /*允许用户设置自己的评论对其他人不可见*/
        'id' => 'open_private_message',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Human verification', 'sakura'), /*机器人验证*/
        'desc' => __('Enable human verification', 'sakura'), /*开启机器人验证*/
        'id' => 'norobot',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('QQ avatar link encryption', 'sakura'), /*QQ头像链接加密*/
        'desc' => __('Do not display the user\'s qq avatar links directly.', 'sakura'), /*不直接暴露用户qq头像链接*/
        'id' => 'qq_avatar_link',
        'std' => "off",
        'type' => "select",
        'options' => array(
            'off' => __('Off (default)', 'sakura'), /*关闭（默认）*/
            'type_1' => __('use redirect (general security)', 'sakura'), /*使用 重定向（安全性一般）'*/
            'type_2' => __('fetch data at backend (high security)', 'sakura'), /*后端获取数据（安全性高）*/
            'type_3' => __('fetch data at backend (high security，slow)', 'sakura'), /*后端获取数据（安全性高, 慢）*/
        ));

    $options[] = array(
        'name' => __('Comment UA infomation', 'sakura'), /*评论UA信息*/
        'desc' => __('Check to enable, display the user\'s browser, operating system information', 'sakura'), /*勾选开启，显示用户的浏览器，操作系统信息*/
        'id' => 'open_useragent',
        'std' => '0',
        'type' => 'checkbox');

    //$options[] = array(
    //    'name' => __('Enable disqus', 'sakura'), /*开启多说插件支持*/
    //    'desc' => __('Enable disqus for comment', 'sakura'), /*多说已经凉了*/
    //    'id' => 'general_disqus_plugin_support',
    //    'std' => '0',
    //    'type' => 'checkbox');

    $options[] = array(
        'name' => __('Time Zone adjustment', 'sakura'), /*时区调整*/
        'desc' => __('If the comment has a time difference problem adjust here, fill in an integer, the calculation method: actual_time = display_error_time - the_integer_you_entered (unit: hour)', 'sakura'), /*如果评论出现时差问题在这里调整，填入一个整数，计算方法：实际时间=显示错误的时间-你输入的整数（单位：小时）*/
        'id' => 'time_zone_fix',
        'std' => '0',
        'type' => 'text');

        //特色
    $options[] = array(
        'name' => __('特色', 'sakura'),
        'type' => 'heading');
    
    $options[] = array(
        'name' => __('预加载动画', 'sakura'), /*预加载动画*/
        'desc' => __('勾选开启', 'sakura'), /*勾选开启*/
        'id' => 'yjzdh',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('樱花飘落特效', 'sakura'), /*樱花飘落特效*/
        'desc' => __('勾选开启', 'sakura'), /*勾选开启*/
        'id' => 'sakurajs',
        'std' => '1',
        'type' => 'checkbox');
    
    $options[] = array(
        'name' => __('首页波浪特效', 'sakura'), /*首页波浪特效*/
        'desc' => __('勾选开启', 'sakura'), /*勾选开启*/
        'id' => 'bolangcss',
        'std' => '1',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('看板娘', 'sakura'), /*看板娘*/
        'desc' => __('勾选开启', 'sakura'), /*勾选开启*/
        'id' => 'live2djs',
        'std' => '1',
        'type' => 'checkbox');
    
    $options[] = array(
        'name' => __('下拉箭头', 'sakura'), /*下拉箭头*/
        'desc' => __('勾选开启', 'sakura'), /*勾选开启*/
        'id' => 'godown',
        'std' => '1',
        'type' => 'checkbox');
    
    $options[] = array(
        'name' => __('页脚一言', 'sakura'), /*页脚一言*/
        'desc' => __('勾选开启', 'sakura'), /*勾选开启*/
        'id' => 'oneword',
        'std' => '1',
        'type' => 'checkbox');
        
    $options[] = array(
        'name' => __('首页一言打字效果', 'sakura'), /*首页一言打字效果*/
        'desc' => __('勾选开启', 'sakura'), /*勾选开启*/
        'id' => 'dazi',
        'std' => '0',
        'type' => 'checkbox');
    
    $options[] = array(
        'name' => __('打字效果双引号', 'sakura'), /*首页一言打字效果*/
        'desc' => __('勾选开启', 'sakura'), /*勾选开启*/
        'id' => 'dazi_yh',
        'std' => '0',
        'type' => 'checkbox');
    
    $options[] = array(
        'name' => __('打字效果文字', 'sakura'), /*打字效果文字*/
        'desc' => __('填写打字效果文字部分(文字外必须使用英文双引号，二句话之间使用英文逗号隔开。支持html标签)', 'sakura'),
        'id' => 'dazi_a',
        'std' => '"寒蝉黎明之时,便是重生之日。"',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('主页一言博主描述', 'sakura'), /*主页一言博主描述*/
        'desc' => __('一段自我描述的话', 'sakura'), /*一段自我描述的话*/
        'id' => 'admin_des',
        'std' => '粉色的花瓣，美丽地缠绕在身上。依在风里。',
        'type' => 'textarea');
    
    $options[] = array(
        'name' => __('文章末尾博主描述', 'sakura'), /*文章末尾博主描述*/
        'desc' => __('一段自我描述的话', 'sakura'), /*一段自我描述的话*/
        'id' => 'admin_destwo',
        'std' => '粉色的花瓣，美丽地缠绕在身上。依在风里。',
        'type' => 'textarea');
    
    $options[] = array(
        'name' => __('Logo特效', 'sakura'), /*Logo特效*/
        'desc' => __('勾选开启', 'sakura'), /*勾选开启*/
        'id' => 'logocss',
        'std' => '0',
        'type' => 'checkbox');

    $options[] = array(
        'name' => __('Logo文字A', 'sakura'), /*Logo文字*/
        'desc' => __('填写你的logo文字前部分', 'sakura'),
        'id' => 'logo_a',
        'std' => ' ',
        'type' => 'text');
        
    $options[] = array(
        'name' => __('Logo文字B', 'sakura'), /*Logo文字*/
        'desc' => __('填写你的logo文字中部分', 'sakura'),
        'id' => 'logo_b',
        'std' => ' ',
        'type' => 'text');
        
    $options[] = array(
        'name' => __('Logo文字C', 'sakura'), /*Logo文字*/
        'desc' => __('填写你的logo文字后部分', 'sakura'),
        'id' => 'logo_c',
        'std' => ' ',
        'type' => 'text');
        
     $options[] = array(
        'name' => __('Logo二级文字', 'sakura'), /*Logo文字*/
        'desc' => __('填写你的logo二级文字，不需要可以不填', 'sakura'),
        'id' => 'logo_two',
        'std' => '',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Logo字体链接', 'sakura'), /*Logo文字*/
        'desc' => __('准备好字体后在再这个<a href = "https://www.fontke.com/tool/fontface/">@font-face生成器</a>里来转换为方便网页使用的格式，在这个能生成一堆文件，文件都有用，放在一个可以访问的服务器、oos、cdn等上，这里只需要填写那个css格式的样式表文件链接<a href = "https://blog.ukenn.top/sakura6/#toc-head-4">详细教程</a>', 'sakura'),
        'id' => 'logo_zt',
        'std' => 'https://cdn.jsdelivr.net/gh/acai66/mydl/fonts/wenyihei/wenyihei-subfont.css',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('Logo字体名称', 'sakura'), /*Logo文字*/
        'desc' => __('填写你的logo字体名称，直接写名字不用加格式后缀', 'sakura'),
        'id' => 'logo_ztmc',
        'std' => 'wenyihei-subfont',
        'type' => 'text');
    
    $options[] = array(
        'name' => __('邮件模板头部图', 'sakura'), 
        'desc' => __('设置你的邮件上方背景图片', 'sakura'), 
        'id' => 'mail_img',
        'std' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/loadimg/head.jpg',
        'type' => 'text');
        
    //色彩
    $options[] = array(
        'name' => __('色彩', 'sakura'),
        'type' => 'heading');

    $options[] = array(
        'name' => __('显示图标选择', 'sakura'), /*社交图标选择*/
        'desc' => __('选择图标颜色', 'sakura'), /*社交图标选择*/
        'id' => 'webweb_img',
        'std' => 'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/color-img/pink',
        'type' => 'select',
        'options' => array(
            'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/color-img/pink' => __('「日系」粉色（EE9CA7）', 'sakura'), /*粉色*/
            'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/color-img/blue' => __('「日系」蓝色（1E88A8）', 'sakura'), /*蓝色*/
            'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/color-img/yellow' => __('「日系」黄色（E98B2A）', 'sakura'), /*黄色*/
            'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/color-img/orange' => __('「日系」橙色（FF8000）', 'sakura'), /*橙色*/
            'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/color-img/macaronblue' => __('「马卡龙色系」蓝色（B8F1ED）', 'sakura'), 
            'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/color-img/macarongreen' => __('「马卡龙色系」绿色（B8F1CC）', 'sakura'), 
            'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/color-img/macaronpurple' => __('「马卡龙色系」紫色（D9B8F1）', 'sakura'), 
            'https://cdn.jsdelivr.net/gh/mirai-mamori/web-img/color-img/colorful' => __('「其他」彩色', 'sakura'), 
    ));
    $options[] = array(
            'name' => __('Theme style', 'sakura'), /*主题风格*/
            'id' => 'theme_skin',
            'std' => "#EE9CA7",
            'desc' => __('Custom theme color', 'sakura'), /*自定义主题颜色*/
            'type' => "color",
    );
    
    $options[] = array(
        'name' => __('首页一言社交背景', 'sakura'), /*首页一言文字背景*/
        'id' => 'theme_skin_yybj',
        'std' => "#FFF",
        'desc' => __('自定义颜色', 'sakura'), /*自定义背景颜色*/
        'type' => "color",
    );

    $options[] = array(
            'name' => __('主题选择菜单背景颜色', 'sakura'), /*主题选择菜单背景颜色*/
            'id' => 'theme_skin_cdbj',
            'std' => "#FFF",
            'desc' => __('自定义颜色', 'sakura'), /*自定义背景颜色*/
            'type' => "color",
    );
    
    $options[] = array(
        'name' => __('下拉箭头颜色', 'sakura'), /*下拉箭头颜色*/
        'id' => 'godown_skin',
        'std' => "#FFF",
        'desc' => __('自定义下拉箭头颜色', 'sakura'), /*自定义下拉箭头颜色*/
        'type' => "color",
    );  
    
    $options[] = array(
        'name' => __('首页文章分割符', 'sakura'), /*首页文章分割符*/
        'id' => 'theme_skin_fgf',
        'std' => "#FFEEEB",
        'desc' => __('自定义颜色', 'sakura'), /*自定义背景颜色*/
        'type' => "color",
    );

    $options[] = array(
        'name' => __('首页文章时间提示强调文字', 'sakura'), /*首页文章时间提示强调文字*/
        'id' => 'theme_skin_sjwz',
        'std' => "#EE9CA7",
        'desc' => __('自定义颜色', 'sakura'), /*自定义背景颜色*/
        'type' => "color",
    );
    
    $options[] = array(
        'name' => __('首页文章时间提示强调背景', 'sakura'), /*首页文章时间提示强调背景*/
        'id' => 'theme_skin_sjbj',
        'std' => "#FFEEEB",
        'desc' => __('自定义颜色', 'sakura'), /*自定义背景颜色*/
        'type' => "color",
    );
    
    $options[] = array(
        'name' => __('首页文章边框阴影', 'sakura'), /*首页文章边框阴影*/
        'id' => 'theme_skin_bkyy',
        'std' => "#FFEEEB",
        'desc' => __('自定义颜色', 'sakura'), /*自定义背景颜色*/
        'type' => "color",
    );
    $options[] = array(
        'name' => __('预加载动画颜色A', 'sakura'), /*预加载动画颜色A*/
        'id' => 'theme_skin_yjjone',
        'std' => "#FFEEEB",
        'desc' => __('自定义颜色', 'sakura'), /*自定义背景颜色*/
        'type' => "color",
    );
    $options[] = array(
        'name' => __('预加载动画颜色B', 'sakura'), /*预加载动画颜色B*/
        'id' => 'theme_skin_yjjtwo',
        'std' => "#EE9CA7",
        'desc' => __('自定义颜色', 'sakura'), /*自定义背景颜色*/
        'type' => "color",
    );
    
    return $options;
}