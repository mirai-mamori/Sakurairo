<?php
if( class_exists( 'CSF' ) ) {

  $prefix = 'iro_options';

  CSF::createOptions( $prefix, array(
    'menu_title' => 'iro 主题设置',
    'menu_slug'  => 'iro_options',
  ) );

  CSF::createSection( $prefix, array(
    'id'    => 'preliminary',
    'title' => '初步设置',
    'icon'      => 'fa fa-sliders',
    'fields' => array(

      array(
        'id'    => 'site_name',
        'type'  => 'text',
        'title' => '站点名称',
        'desc'   => '例如：Fuukei Blog',
      ),

      array(
        'id'    => 'author_name',
        'type'  => 'text',
        'title' => '作者名称',
        'desc'   => '例如：Fuukei',
      ),

      array(
        'id'    => 'personal_avatar',
        'type'  => 'upload',
        'title' => '个人头像',
        'desc'   => '最佳比例1比1',
        'library'      => 'image',
      ),

      array(
        'id'    => 'text_logo_options',
        'type'  => 'switcher',
        'title' => '白猫特效文字',
        'label'   => '开启之后将替换个人头像作为首页显示内容',
        'default' => false
      ),

      array(
        'id'        => 'text_logo',
        'type'      => 'fieldset',
        'title'     => '白猫特效文字',
        'dependency' => array( 'text_logo_options', '==', 'true' ),
        'fields'    => array(
          array(
            'id'     => 'text',
            'type'   => 'text',
            'title'  => '文本',
            'desc'   => '文本内容建议不要过长，推荐长度为16个字节。',
          ),
          array(
            'id'     => 'font',
            'type'   => 'text',
            'title'  => '字体',
            'desc'   => '填写字体名称。例如：Ma Shan Zheng',
          ),
          array(
            'id'     => 'size',
            'type'   => 'slider',
            'title'  => '字体大小',
            'desc'   => '滑动滑块，推荐数值范围为70-90',
            'unit'    => 'px',
            'min'   => '40',
            'max'   => '140',
          ),
          array(
            'id'      => 'color',
            'type'    => 'color',
            'title'   => '字体颜色',
            'desc'    => '自定义颜色，建议使用浅色系颜色',
          ),      
        ),
        'default'        => array(
          'text'    => 'ぐんじょう',
          'size'    => '80',
          'color'    => '#FFF',
        ),
      ),

      array(
        'id'    => 'iro_logo',
        'type'  => 'upload',
        'title' => '导航菜单Logo',
        'desc'   => '最佳尺寸40px，填写后导航菜单文字Logo不显示',
        'library'      => 'image',
      ),

      array(
        'id'    => 'favicon_link',
        'type'  => 'text',
        'title' => '站点Logo',
        'desc'   => '填写地址，站点Logo即浏览器上方标题旁的图标',
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/basic/favicon.ico'
      ),

      array(
        'id'    => 'iro_meta',
        'type'  => 'switcher',
        'title' => '自定义站点关键词和描述',
        'label'   => '开启之后可自定义填写站点关键词和描述',
        'default' => false
      ),

      array(
        'id'     => 'iro_meta_keywords',
        'type'   => 'text',
        'title'  => '站点关键词',
        'dependency' => array( 'iro_meta', '==', 'true' ),
        'desc'   => '各关键字间用半角逗号","分割，数量在5个以内最佳',
      ),

      array(
        'id'     => 'iro_meta_description',
        'type'   => 'text',
        'title'  => '站点描述',
        'dependency' => array( 'iro_meta', '==', 'true' ),
        'desc'   => '用简洁的文字描述本站点，字数建议在120个字以内',
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'id'    => 'global', 
    'title' => '全局设置',
    'icon'      => 'fa fa-globe',
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'global', 
    'title'  => '外观设置',
    'icon'      => 'fa fa-tree',
    'fields' => array(

      array(
        'type'    => 'subheading',
        'content' => '主题配色',
      ),

      array(
        'id'      => 'theme_skin',
        'type'    => 'color',
        'title'   => '主题色',
        'desc'    => '自定义颜色',
        'default' => '#505050'
      ),  

      array(
        'id'      => 'theme_skin_matching',
        'type'    => 'color',
        'title'   => '主题色搭配色',
        'desc'    => '自定义颜色',
        'default' => '#ffe066'
      ),  

      array(
        'type'    => 'subheading',
        'content' => '深色模式',
      ),

      array(
        'id'      => 'theme_skin_dark',
        'type'    => 'color',
        'title'   => '深色模式主题色',
        'desc'    => '自定义颜色',
        'default' => '#ffcc00'
      ),  

      array(
        'id'    => 'theme_darkmode_auto',
        'type'  => 'switcher',
        'title' => '深色模式自动切换',
        'label'   => '默认开启',
        'default' => true
      ),
      array(
        'type'    => 'content',
        'content' => '<p><strong>客户端当地时间:</strong>深色模式会在22:00-7:00自动切换</p>'
        .'<p><strong>跟随客户端设置:</strong>跟随客户端浏览器的设置</p>'
        .'<p><strong>永远开启:</strong>永远开启，除非客户端另有配置</p>',
        'dependency' => array( 'theme_darkmode_auto', '==', 'true' ),

      ),
      array(
        'id'    => 'theme_darkmode_strategy',
        'type'  => 'select',
        'title' => '深色模式自动切换策略',
        'dependency' => array( 'theme_darkmode_auto', '==', 'true' ),
        'options'     => array(
          'time'  => '客户端当地时间',
          'client'  => '跟随客户端设置',
          'eien'  => '永远开启',
        ),
        "default"=>"time"
      ),

      array(
        'id'     => 'theme_darkmode_img_bright',
        'type'   => 'slider',
        'title'  => '深色模式图像亮度',
        'desc'   => '滑动滑块，推荐数值范围为0.6-0.8',
        'step'   => '0.01',
        'min'   => '0.4',
        'max'   => '1',
        'default' => '0.8'
      ),

      array(
        'id'     => 'theme_darkmode_widget_transparency',
        'type'   => 'slider',
        'title'  => '深色模式部件透明度',
        'desc'   => '滑动滑块，推荐数值范围为0.6-0.8',
        'step'   => '0.01',
        'min'   => '0.2',
        'max'   => '1',
        'default' => '0.8'
      ),

      array(
        'type'    => 'subheading',
        'content' => '其他',
      ),

      array(
        'id'     => 'load_out_svg',
        'type'   => 'text',
        'title'  => '加载控件单元占位SVG',
        'desc'   => '填写地址，此为加载控件单元时占位显示的SVG',
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/load_svg/outload.svg'
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'global', 
    'title'  => '字体设置',
    'icon'      => 'fa fa-font',
    'fields' => array(

      array(
        'type'    => 'subheading',
        'content' => '全局',
      ),

      array(
        'id'     => 'global_font_weight',
        'type'   => 'slider',
        'title'  => '非强调文本字重',
        'desc'   => '滑动滑块，推荐数值范围为300-500',
        'step'   => '10',
        'min'   => '100',
        'max'   => '700',
        'default' => '300'
      ),

      array(
        'id'     => 'global_font_size',
        'type'   => 'slider',
        'title'  => '文本字体大小',
        'desc'   => '滑动滑块，推荐数值范围为15-18',
        'step'   => '1',
        'unit'    => 'px',
        'min'   => '10',
        'max'   => '20',
        'default' => '15'
      ),

      array(
        'type'    => 'subheading',
        'content' => '外部字体',
      ),

      array(
        'id'    => 'reference_exter_font',
        'type'  => 'switcher',
        'title' => '引用外部字体',
        'label'   => '开启之后可以使用外部字体作为默认字体或其他部件字体，但可能影响性能',
        'default' => false
      ),

      array(
        'id'     => 'exter_font_link',
        'type'   => 'text',
        'title'  => '外部字体地址',
        'dependency' => array( 'reference_exter_font', '==', 'true' ),
      ),

      array(
        'id'     => 'exter_font_name',
        'type'   => 'text',
        'title'  => '外部字体名称',
        'dependency' => array( 'reference_exter_font', '==', 'true' ),
      ),

      array(
        'id'     => 'google_fonts_api',
        'type'   => 'text',
        'title'  => '谷歌字体API地址',
        'default' => 'fonts.googleapis.com'
      ),

      array(
        'id'     => 'google_fonts_add',
        'type'   => 'text',
        'title'  => '谷歌字体名称',
        'desc'   => '请确保添加的字体在谷歌字体库内可被引用，填写字体名称。添加的字体前面必须有”|“。如果引用多个字体，请使用“|”作为分割符，如果字体名称有空格，请用加号替代。例如：|ZCOOL+XiaoWei|Ma+Shan+Zheng ',
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'global', 
    'title'  => '导航菜单设置',
    'icon'      => 'fa fa-map-signs',
    'fields' => array(

      array(
        'id'         => 'nav_menu_style',
        'type'       => 'image_select',
        'title'      => '导航菜单样式',
        'options'    => array(
          'sakurairo' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/nav_menu_style_iro.png',
          'sakura' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/nav_menu_style_sakura.png',
        ),
        'default'    => 'sakurairo'
      ),

      array(
        'id'    => 'nav_menu_radius',
        'type'  => 'slider',
        'title' => '导航菜单圆角',
        'dependency' => array( 'nav_menu_style', '==', 'sakurairo' ),
        'desc'   => '滑动滑块，推荐数值为15',
        'unit'    => 'px',
        'max'   => '50',
        'default' => '15'
      ),

      array(
        'id'     => 'nav_menu_shrink_animation',
        'type'   => 'slider',
        'title'  => '导航菜单收缩比率',
        'dependency' => array( 'nav_menu_style', '==', 'sakurairo' ),
        'desc'   => '滑动滑块，根据导航菜单的内容长度自行设置合适的比率，当比率设置为95时则关闭收缩，默认关闭收缩',
        'step'   => '0.5',
        'unit'    => '%',
        'max'   => '95',
        'min'   => '30',
        'default' => '95'
      ),

      array(
        'id'         => 'nav_menu_display',
        'type'       => 'radio',
        'title'      => '导航菜单内容显示',
        'desc'    => '你可以选择展开显示或者收缩显示导航菜单内容',
        'options'    => array(
          'unfold' => '展开显示',
          'fold' => '收缩显示',
        ),
        'default'    => 'unfold'
      ),

      array(
        'id'    => 'nav_menu_animation',
        'type'  => 'switcher',
        'title' => '导航菜单动画',
        'label'   => '默认开启，如果关闭，则导航内容将直接显示',
        'default' => true
      ),

      array(
        'id'     => 'nav_menu_animation_time',
        'type'   => 'slider',
        'title'  => '导航菜单动画时间',
        'dependency' => array( 'nav_menu_animation', '==', 'true' ),
        'desc'   => '滑动滑块，推荐数值范围为1-2',
        'step'   => '0.01',
        'unit'    => 's',
        'max'   => '5',
        'default' => '2'
      ),

      array(
        'id'         => 'nav_menu_icon_size',
        'type'       => 'radio',
        'title'      => '导航菜单图标大小',
        'options'    => array(
          'standard' => '标准图标',
          'large' => '大图标',
        ),
        'default'    => 'standard'
      ),

      array(
        'id'    => 'nav_menu_search',
        'type'  => 'switcher',
        'title' => '导航菜单搜索',
        'label'   => '默认开启，点击将进入搜索区域',
        'default' => true
      ),
      array(
        'id'    => 'nav_menu_blur',
        'type'  => 'slider',
        'title' => '导航菜单背景模糊',
        'desc'   => '滑动滑块，推荐数值为5px，为0px时关闭',
        'unit'    => 'px',
        'max'   => '20',
        'default' => '0'
      ),

      array(
        'id'    => 'search_area_background',
        'type'  => 'upload',
        'title' => '导航菜单搜索区域背景图片',
        'desc'   => '设置你的搜索区域背景图片，此选项留空则显示白色背景',
        'library'      => 'image',
        'default'     => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/basic/iloli.gif'
      ),

      array(
        'id'    => 'nav_menu_user_avatar',
        'type'  => 'switcher',
        'title' => '导航菜单用户头像',
        'label'   => '默认开启，点击将进入登录界面',
        'default' => true
      ),

      array(
        'id'     => 'unlisted_avatar',
        'type'  => 'upload',
        'title' => '导航菜单用户未登录头像',
        'dependency' => array( 'nav_menu_user_avatar', '==', 'true' ),
        'desc'   => '最佳比例1比1',
        'library'      => 'image',
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/basic/topavatar.png'
      ),

      array(
        'id'    => 'nav_menu_secondary_arrow',
        'type'  => 'switcher',
        'title' => '导航菜单二级菜单提示箭头',
        'label'   => '开启之后菜单提示箭头将出现在导航菜单二级菜单',
        'dependency' => array( 'nav_menu_style', '==', 'sakura' ),
        'default' => false
      ),

      array(
        'id'    => 'nav_menu_secondary_radius',
        'type'  => 'slider',
        'title' => '导航菜单二级菜单圆角',
        'dependency' => array( 'nav_menu_style', '==', 'sakurairo' ),
        'desc'   => '滑动滑块，推荐数值为15',
        'unit'    => 'px',
        'max'   => '30',
        'default' => '15'
      ),

      array(
        'id'     => 'logo_text',
        'type'   => 'text',
        'title'  => '导航菜单文字Logo文本',
        'desc'   => '填写文本内容，如开启白猫样式Logo则此选项无效',
        'dependency' => array( 'mashiro_logo_option', '==', 'false' ),
      ),

      array(
        'id'    => 'mashiro_logo_option',
        'type'  => 'switcher',
        'title' => '导航菜单白猫样式Logo',
        'label'   => '开启之后白猫样式Logo将出现并替换导航菜单Logo位置',
        'default' => false
      ),

      array(
        'id'     => 'mashiro_logo',
        'type'   => 'fieldset',
        'title'  => '白猫样式Logo选项',
        'dependency' => array( 'mashiro_logo_option', '==', 'true' ),
        'fields' => array(
          array(
            'id'    => 'text_a',
            'type'  => 'text',
            'title' => '文字A',
          ),
          array(
            'id'    => 'text_b',
            'type'  => 'text',
            'title' => '文字B',
          ),
          array(
            'id'    => 'text_c',
            'type'  => 'text',
            'title' => '文字C',
          ),
          array(
            'id'    => 'text_secondary',
            'type'  => 'text',
            'title' => '二级文字',
          ),
          array(
            'id'    => 'font_link',
            'type'  => 'text',
            'title' => '字体链接',
          ),
          array(
            'id'    => 'font_name',
            'type'  => 'text',
            'title' => '字体名称',
          ),
        ),
        'default'        => array(
          'text_a'     => '',
          'text_b'     => '',
          'text_c'     => '',
          'text_secondary' => '',
          'font_link'     => 'https://cdn.jsdelivr.net/gh/acai66/mydl/fonts/wenyihei/wenyihei-subfont.css',
          'font_name'    => 'wenyihei-subfont',
        ),
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'global', 
    'title'  => '样式菜单和前台背景相关设置',
    'icon'      => 'fa fa-th-large',
    'fields' => array(

      array(
        'type'    => 'subheading',
        'content' => '样式菜单',
      ),

      array(
        'id'         => 'style_menu_display',
        'type'       => 'image_select',
        'title'      => '样式菜单显示',
        'desc'    => '你可以选择完整显示或者简单显示样式菜单，完整显示将显示字体切换功能和文本提示',
        'options'    => array(
          'full' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/style_menu_full.png',
          'mini' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/style_menu_mini.png',
        ),
        'default'    => 'full'
      ),

      array(
        'id'    => 'style_menu_radius',
        'type'  => 'slider',
        'title' => '样式菜单按钮圆角',
        'desc'   => '滑动滑块，推荐数值为10',
        'unit'    => 'px',
        'max'   => '50',
        'default' => '10'
      ),

      array(
        'id'      => 'style_menu_background_color',
        'type'    => 'color',
        'title'   => '样式菜单背景颜色',
        'desc'    => '自定义颜色，建议使用浅色系颜色',
        'default' => 'rgba(255,255,255,0.8)'
      ),   

      array(
        'id'      => 'style_menu_selection_color',
        'type'    => 'color',
        'title'   => '样式菜单选项背景颜色',
        'desc'    => '自定义颜色，建议使用与主题色相同色系且属于浅色系的颜色',
        'default' => '#e8e8e8'
      ),

      array(
        'id'    => 'style_menu_selection_radius',
        'type'  => 'slider',
        'title' => '样式菜单选项界面圆角',
        'desc'   => '滑动滑块，推荐数值为15',
        'unit'    => 'px',
        'max'   => '30',
        'default' => '15'
      ),

      array(
        'type'    => 'subheading',
        'content' => '前台背景',
      ),

      array(
        'id'            => 'reception_background',
        'type'          => 'tabbed',
        'title'         => '前台背景设置',
        'tabs'          => array(
          array(
            'title'     => '默认',
            'icon'      => 'fa fa-television',
            'fields'    => array(
              array(
                'id'    => 'img1',
                'type'  => 'upload',
                'title' => '图片',
              ),
            )
          ),
          array(
            'title'     => '心形图标',
            'icon'      => 'fa fa-heart-o',
            'fields'    => array(
              array(
                'id'    => 'heart_shaped',
                'type'  => 'switcher',
                'title' => '开关',
              ),
              array(
                'id'    => 'img2',
                'type'  => 'upload',
                'title' => '图片',
              ),
            )
          ),
          array(
            'title'     => '星形图标',
            'icon'      => 'fa fa-star-o',
            'fields'    => array(
              array(
                'id'    => 'star_shaped',
                'type'  => 'switcher',
                'title' => '开关',
              ),
              array(
                'id'    => 'img3',
                'type'  => 'upload',
                'title' => '图片',
              ),
            )
          ),
          array(
            'title'     => '方形图标',
            'icon'      => 'fa fa-delicious',
            'fields'    => array(
              array(
                'id'    => 'square_shaped',
                'type'  => 'switcher',
                'title' => '开关',
              ),
              array(
                'id'    => 'img4',
                'type'  => 'upload',
                'title' => '图片',
              ),
            )
          ),
          array(
            'title'     => '柠檬形图标',
            'icon'      => 'fa fa-lemon-o',
            'fields'    => array(
              array(
                'id'    => 'lemon_shaped',
                'type'  => 'switcher',
                'title' => '开关',
              ),
              array(
                'id'    => 'img5',
                'type'  => 'upload',
                'title' => '图片',
              ),
            )
          ),
        ),
        'default'       => array(
          'heart_shaped'  => true,
          'star_shaped'  => true,
          'square_shaped'  => true,
          'lemon_shaped'  => true,
          'img2'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/foreground/bg1.png',
          'img3'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/foreground/bg2.png',
          'img4' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/foreground/bg3.png',
          'img5' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/foreground/bg4.png',
        )
      ),

      array(
        'id'     => 'reception_background_transparency',
        'type'   => 'slider',
        'title'  => '前台背景透明度',
        'desc'   => '滑动滑块，推荐数值范围为0.6-0.8',
        'step'   => '0.01',
        'min'   => '0.2',
        'max'   => '1',
        'default' => '0.8'
      ),

      array(
        'type'    => 'subheading',
        'content' => '字体区域',
      ),

      array(
        'id'     => 'global_default_font',
        'type'   => 'text',
        'title'  => '默认字体/样式菜单字体A',
        'desc'   => '填写字体名称。例如：Ma Shan Zheng',
      ),

      array(
        'id'     => 'global_font_2',
        'type'   => 'text',
        'title'  => '样式菜单字体B',
        'dependency' => array( 'style_menu_display', '==', 'full' ),
        'desc'   => '填写字体名称。例如：Ma Shan Zheng',
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'global', 
    'title'  => '页尾设置',
    'icon'      => 'fa fa-caret-square-o-down',
    'fields' => array(

      array(
        'id'          => 'aplayer_server',
        'type'        => 'select',
        'title'       => '页尾在线播放器',
        'desc'   => '开启之后页尾左下角将出现按钮，点击按钮后页尾在线播放器将显示',
        'options'     => array(
          'off'  => '关闭',
          'netease'  => '网易云音乐',
          'kugou'  => '酷狗音乐（可能无法使用）',
          'baidu'  => '千千音乐（海外服务器无法使用）',
          'tencent'  => 'QQ音乐（可能无法使用）',
        ),
        'default'     => 'off'
      ),

      array(
        'id'     => 'aplayer_playlistid',
        'type'   => 'text',
        'title'  => '页尾在线播放器歌单ID',
        'dependency' => array( 'aplayer_server', '!=', 'off' ),
        'desc'   => '填写歌单ID，例如：https://music.163.com/#/playlist?id=5380675133的歌单ID是5380675133',
        'default' => '5380675133'
      ),

      array(
        'id'     => 'aplayer_volume',
        'type'   => 'slider',
        'title'  => '页尾在线播放器默认音量',
        'dependency' => array( 'aplayer_server', '!=', 'off' ),
        'desc'   => '滑动滑块，推荐数值范围为0.4-0.6',
        'step'   => '0.01',
        'max'   => '1',
        'default' => '0.5'
      ),

      array(
        'id'     => 'aplayer_cookie',
        'type'   => 'textarea',
        'title'  => '页尾在线播放器网易云音乐Cookies',
        'dependency' => array( 'aplayer_server', '==', 'netease' ),
        'desc'   => '如果你想播放网易云音乐会员专享音乐，请在此选项填入你的帐号Cookies。',
      ),

      array(
        'id'    => 'sakura_widget',
        'type'  => 'switcher',
        'title' => '页尾小部件区',
        'label'   => '开启之后页尾左下角将出现按钮，点击按钮后页尾小部件区将显示，如果你开启了页尾在线播放器，则会一起显示',
        'default' => false
      ),

      array(
        'id'     => 'sakura_widget_background',
        'type'  => 'upload',
        'title' => '页尾小部件区背景',
        'dependency' => array( 'sakura_widget', '==', 'true' ),
        'desc'   => '最佳宽度400px，最佳高度460px',
        'library'      => 'image',
      ),

      array(
        'id'    => 'footer_sakura_icon',
        'type'  => 'switcher',
        'title' => '页尾动态樱花图标',
        'label'   => '开启之后页尾将出现动态樱花图标',
        'default' => false
      ),

      array(
        'id'    => 'footer_random_word',
        'type'  => 'switcher',
        'title' => '页尾随机话语',
        'label'   => '开启之后页尾将出现随机话语',
        'default' => false
      ),

      array(
        'id'    => 'footer_load_occupancy',
        'type'  => 'switcher',
        'title' => '页尾负载占用查询',
        'label'   => '开启之后页尾将出现负载占用信息',
        'default' => false
      ),

      array(
        'id'     => 'footer_info',
        'type'   => 'textarea',
        'title'  => '页尾信息',
        'desc'   => '页尾说明文字，支持HTML代码',
        'default' => 'Copyright &copy; by FUUKEI All Rights Reserved.'
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'global', 
    'title'  => '鼠标设置',
    'icon'      => 'fa fa-i-cursor',
    'fields' => array(

      array(
        'id'     => 'cursor_nor',
        'type'   => 'text',
        'title'  => '标准鼠标样式',
        'desc'   => '应用于全局，填写Cur鼠标文件链接',
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/cursor/normal.cur'
      ),

      array(
        'id'     => 'cursor_no',
        'type'   => 'text',
        'title'  => '选定鼠标样式',
        'desc'   => '应用于多种样式，填写Cur鼠标文件链接',
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/cursor/No_Disponible.cur'
      ),

      array(
        'id'     => 'cursor_ayu',
        'type'   => 'text',
        'title'  => '选中控件单元鼠标样式',
        'desc'   => '应用于选中某个控件单元，填写Cur鼠标文件链接',
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/cursor/ayuda.cur'
      ),

      array(
        'id'     => 'cursor_text',
        'type'   => 'text',
        'title'  => '选中文本鼠标样式',
        'desc'   => '应用于选中文本，填写Cur鼠标文件链接',
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/cursor/texto.cur'
      ),

      array(
        'id'     => 'cursor_work',
        'type'   => 'text',
        'title'  => '工作状态鼠标样式',
        'desc'   => '应用于加载控件单元，填写Cur鼠标文件链接',
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/cursor/work.cur'
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'global', 
    'title'  => '额外设置',
    'icon'      => 'fa fa-gift',
    'fields' => array(

      array(
        'type'    => 'subheading',
        'content' => '特效及动画',
      ),

      array(
        'id'    => 'preload_animation',
        'type'  => 'switcher',
        'title' => '预加载动画',
        'label'   => '开启之后新页面加载前会有预加载动画，此选项需确保你的页面资源正常加载。',
        'default' => false
      ),

      array(
        'id'      => 'preload_animation_color1',
        'type'    => 'color',
        'title'   => '预加载动画颜色A',
        'dependency' => array( 'preload_animation', '==', 'true' ),
        'desc'    => '自定义颜色',
        'default' => '#ffea99'
      ),   

      array(
        'id'      => 'preload_animation_color2',
        'type'    => 'color',
        'title'   => '预加载动画颜色B',
        'dependency' => array( 'preload_animation', '==', 'true' ),
        'desc'    => '自定义颜色',
        'default' => '#ffcc00'
      ),   

      array(
        'id'      => 'preload_blur',
        'title'   => '预加载模糊过渡效果',
        'dependency' => array( 'preload_animation', '==', 'true' ),
        'desc'    => '模糊过渡持续时间，单位毫秒ms，为0时关闭。',
        'default' => '0',
        'type'   => 'slider',
        'step'   => '10',
        'max'   => '10000',
      ), 

      array(
        'id'          => 'falling_effects',
        'type'        => 'select',
        'title'       => '飘落特效',
        'options'     => array(
          'off'  => '关闭',
          'sakura-native'  => '樱花 原生数量',
          'sakura-quarter'  => '樱花 四分之一数量',
          'sakura-half'  => '樱花 二分之一数量',
          'sakura-less'  => '樱花 较少数量',
          'yuki-native'  => '雪花 原生数量',
          'yuki-half'  => '雪花 二分之一数量',
        ),
        'default'     => 'off'
      ),

      array(
        'id'    => 'live2d_options',
        'type'  => 'switcher',
        'title' => 'Live2D看板娘',
        'label'   => '开启之后页面左下角将加载Live2D看板娘',
        'default' => false
      ),

      array(
        'id'     => 'live2d_custom_user',
        'type'   => 'text',
        'title'  => 'Live2D看板娘自定义Github项目用户名',
        'dependency' => array( 'live2d_options', '==', 'true' ),
        'desc'   => '如果想自定义本选项，你需要先去Github Fork本项目并对本项目进行修改，此处填写Github项目的用户名',
        'default' => 'mirai-mamori'
      ),

      array(
        'id'     => 'live2d_custom_user_ver',
        'type'   => 'text',
        'title'  => 'Live2D看板娘自定义Github项目版本',
        'dependency' => array( 'live2d_options', '==', 'true' ),
        'desc'   => '如果想自定义本选项，你需要先去Github Fork本项目并对本项目进行修改，此处填写Github项目的版本',
        'default' => 'latest'
      ),

      array(
        'id'    => 'note_effects',
        'type'  => 'switcher',
        'title' => '音符触动特效',
        'label'   => '开启之后返回顶部按钮和白猫样式Logo触碰时将有音符声音提示',
        'default' => false
      ),

      array(
        'type'    => 'subheading',
        'content' => '功能',
      ),

      array(
        'id'     => 'poi_pjax',
        'type'   => 'switcher',
        'title'  => 'PJAX局部刷新',
        'label'   => '开启之后点击新内容将不需要重新加载',
        'default' => false
      ),

      array(
        'id'     => 'nprogress_on',
        'type'   => 'switcher',
        'title'  => 'NProgress加载进度条',
        'label'   => '默认开启，加载页面将有进度条提示',
        'default' => true
      ),

      array(
        'id'     => 'smoothscroll_option',
        'type'   => 'switcher',
        'title'  => '全局平滑滚动',
        'label'   => '默认开启，页面滚动将更加平滑',
        'default' => true
      ),

      array(
        'id'         => 'pagenav_style',
        'type'       => 'radio',
        'title'      => '分页模式',
        'options'    => array(
          'ajax' => 'Ajax加载',
          'np' => '上下页',
        ),
        'default'    => 'ajax'
      ),

      array(
        'id'          => 'page_auto_load',
        'type'        => 'select',
        'title'       => '下一页自动加载',
        'dependency' => array( 'pagenav_style', '==', 'ajax' ),
        'options'     => array(
          '233'  => '不自动加载',
          '0'  => '0秒',
          '1'  => '1秒',
          '2'  => '2秒',
          '3'  => '3秒',
          '4'  => '4秒',
          '5'  => '5秒',
          '6'  => '6秒',
          '7'  => '7秒',
          '8'  => '8秒',
          '9'  => '9秒',
          '10'  => '10秒',
        ),
        'default'     => '233'
      ),

      array(
        'id'     => 'load_nextpage_svg',
        'type'   => 'text',
        'title'  => '下一页加载占位SVG',
        'desc'   => '填写地址，此为加载下一页时占位显示的SVG',
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/load_svg/ball.svg'
      ),
    )
  ) );

  CSF::createSection( $prefix, array(
    'id'    => 'homepage', 
    'title' => '主页设置',
    'icon'      => 'fa fa-home',
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'homepage', 
    'title'  => '封面设置',
    'icon'      => 'fa fa-laptop',
    'fields' => array(

      array(
        'id'    => 'cover_switch',
        'type'  => 'switcher',
        'title' => '封面',
        'label'   => '默认开启，如果关闭，则下文所有选项均将失效',
        'default' => true
      ),

      array(
        'id'    => 'cover_full_screen',
        'type'  => 'switcher',
        'title' => '封面全屏显示',
        'label'   => '默认开启',
        'default' => true
      ),

      array(
        'id'    => 'cover_radius',
        'type'  => 'slider',
        'title' => '封面圆角',
        'desc'   => '滑动滑块，推荐数值范围为15-20',
        'unit'    => 'px',
        'max'    => '60',
        'default' => '15'
      ),

      array(
        'id'    => 'cover_animation',
        'type'  => 'switcher',
        'title' => '封面动画',
        'label'   => '默认开启，如果关闭，则封面将直接显示',
        'default' => true
      ),

      array(
        'id'     => 'cover_animation_time',
        'type'   => 'slider',
        'title'  => '封面动画时间',
        'dependency' => array( 'cover_animation', '==', 'true' ),
        'desc'   => '滑动滑块，推荐数值范围为1-2',
        'step'   => '0.01',
        'unit'    => 's',
        'max'   => '5',
        'default' => '2'
      ),

      array(
        'id'    => 'infor_bar',
        'type'  => 'switcher',
        'title' => '封面信息栏',
        'label'   => '默认开启，显示头像、白猫特效文字、签名栏、社交区域',
        'default' => true
      ),

      array(
        'id'         => 'infor_bar_style',
        'type'       => 'image_select',
        'title'      => '封面信息栏样式',
        'options'    => array(
          'v1' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/infor_bar_style_v1.png',
          'v2' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/infor_bar_style_v2.png',
        ),
        'default'    => 'v1'
      ),

      array(
        'id'      => 'infor_bar_bgcolor',
        'type'    => 'color',
        'title'   => '封面信息栏背景颜色',
        'desc'    => '自定义颜色，建议使用浅色系颜色',
        'default' => 'rgba(255,255,255,0.8)'
      ),     

      array(
        'id'    => 'avatar_radius',
        'type'  => 'slider',
        'title' => '封面信息栏头像圆角',
        'desc'   => '滑动滑块，推荐数值为100',
        'unit'    => 'px',
        'default' => '100'
      ),

      array(
        'id'    => 'signature_radius',
        'type'  => 'slider',
        'title' => '封面签名栏圆角',
        'desc'   => '滑动滑块，推荐数值范围为10-20',
        'unit'    => 'px',
        'max'    => '50',
        'default' => '15'
      ),

      array(
        'id'     => 'signature_text',
        'type'   => 'text',
        'title'  => '封面签名栏文本',
        'desc'   => '一段自我描述的话',
        'default' => '本当の声を響かせてよ'
      ),

      array(
        'id'     => 'signature_font',
        'type'   => 'text',
        'title'  => '封面签名栏文本字体',
        'desc'   => '填写字体名称。例如：Ma Shan Zheng',
        'default' => 'Noto Serif SC'
      ),

      array(
        'id'     => 'signature_font_size',
        'type'   => 'slider',
        'title'  => '封面签名栏文本字体大小',
        'desc'   => '滑动滑块，推荐数值范围为15-18',
        'unit'    => 'px',
        'min'   => '5',
        'max'   => '20',
        'default' => '16'
      ),

      array(
        'id'     => 'signature_typing',
        'type'   => 'switcher',
        'title'  => '封面签名栏打字特效',
        'label'   => '开启之后签名栏文本将增加一段文本并呈现打字特效',
        'default' => false
      ),

      array(
        'id'     => 'signature_typing_marks',
        'type'   => 'switcher',
        'title'  => '封面签名栏打字特效双引号',
        'dependency' => array( 'signature_typing', '==', 'true' ),
        'label'   => '开启之后打字特效将追加双引号',
        'default' => false
      ),

      array(
        'id'     => 'signature_typing_text',
        'type'   => 'text',
        'title'  => '封面签名栏打字特效文本',
        'dependency' => array( 'signature_typing', '==', 'true' ),
        'desc'   => '填写打字特效文本部分，文本外必须使用英文双引号，二句话之间使用英文逗号隔开，支持HTML标签',
        'default' => '"寒蝉黎明之时,便是重生之日。"'
      ),

      array(
        'id'          => 'random_graphs_options',
        'type'        => 'select',
        'title'       => '封面随机图片选项',
        'options'     => array(
          'external_api'  => '外部API随机图片',
          'webp_optimization'  => 'Webp优化随机图片',
          'local'  => '本地随机图片',
        ),
        'default'     => 'external_api'
      ),

      array(
        'id'    => 'random_graphs_mts',
        'type'  => 'switcher',
        'title' => '封面随机图片多终端分离',
        'label'   => '默认开启，桌面端和移动端会分别使用不同的随机图片地址',
        'default' => true
      ),

      array(
        'id'     => 'random_graphs_link',
        'type'   => 'text',
        'title'  => 'Webp优化/外部API桌面端随机图片地址',
        'desc'   => '填写地址',
        'default' => 'https://api.iro.tw/webp_pc.php'
      ),

      array(
        'type'    => 'submessage',
        'style'   => 'info',
        'content' => sprintf(__('如果你选择使用Webp优化随机图片，请点击 <a href = "%s">这里</a> 来更新 Manifest 路径', 'sakurairo'), rest_url('sakura/v1/database/update')), 
      ),

      array(
        'id'     => 'random_graphs_link_mobile',
        'type'   => 'text',
        'title'  => '外部API手机端随机图片地址',
        'dependency' => array( 'random_graphs_mts', '==', 'true' ),
        'desc'   => '填写地址',
        'default' => 'https://api.iro.tw/webp_mb.php'
      ),

      array(
        'id'          => 'random_graphs_filter',
        'type'        => 'select',
        'title'       => '封面随机图片滤镜',
        'options'     => array(
          'filter-nothing'  => '无滤镜',
          'filter-undertint'  => '浅色滤镜',
          'filter-dim'  => '暗淡滤镜',
          'filter-grid'  => '网格滤镜',
          'filter-dot'  => '点状滤镜',
        ),
        'default'     => 'filter-nothing'
      ),

      array(
        'id'    => 'wave_effects',
        'type'  => 'switcher',
        'title' => '封面波浪特效',
        'label'   => '开启之后首页封面底部将出现波浪特效',
        'default' => false
      ),

      array(
        'id'    => 'drop_down_arrow',
        'type'  => 'switcher',
        'title' => '封面下拉箭头',
        'label'   => '默认开启，首页封面底部显示下拉箭头',
        'default' => true
      ),

      array(
        'id'    => 'drop_down_arrow_mobile',
        'type'  => 'switcher',
        'title' => '封面下拉箭头移动端显示',
        'dependency' => array( 'drop_down_arrow', '==', 'true' ),
        'label'   => '开启之后移动端首页封面底部将出现下拉箭头',
        'default' => false
      ),

      array(
        'id'      => 'drop_down_arrow_color',
        'type'    => 'color',
        'title'   => '封面下拉箭头颜色',
        'dependency' => array( 'drop_down_arrow', '==', 'true' ),
        'desc'    => '自定义颜色，建议使用浅色系颜色',
        'default' => 'rgba(255,255,255,0.8)'
      ),  

      array(
        'id'      => 'drop_down_arrow_dark_color',
        'type'    => 'color',
        'title'   => '封面下拉箭头深色模式颜色',
        'dependency' => array( 'drop_down_arrow', '==', 'true' ),
        'desc'    => '自定义颜色，建议使用深色系颜色',
        'default' => 'rgba(51,51,51,0.8)'
      ),  

      array(
        'id'    => 'cover_video',
        'type'  => 'switcher',
        'title' => '封面视频',
        'label'   => '开启之后将替代封面随机图片作为主要显示内容',
        'default' => false
      ),

      array(
        'id'    => 'cover_video_loop',
        'type'  => 'switcher',
        'title' => '封面视频循环',
        'dependency' => array( 'cover_video', '==', 'true' ),
        'label'   => '开启之后视频将自动循环，需要开启Pjax功能',
        'default' => false
      ),

      array(
        'id'     => 'cover_video_link',
        'type'   => 'text',
        'title'  => '封面视频地址',
        'dependency' => array( 'cover_video', '==', 'true' ),
        'desc'   => '填写地址，该地址拼接下面的视频名，地址尾部不需要加斜杠',
      ),

      array(
        'id'     => 'cover_video_title',
        'type'   => 'text',
        'title'  => '封面视频名称',
        'dependency' => array( 'cover_video', '==', 'true' ),
        'desc'   => '例如：abc.mp4，只需要填写视频文件名abc即可，多个用英文逗号隔开如abc,efg，默认随机播放',
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'homepage', 
    'title'  => '封面社交区域设置',
    'icon'      => 'fa fa-share-square-o',
    'fields' => array(

      array(
        'type'    => 'subheading',
        'content' => '选项',
      ),

      array(
        'id'    => 'social_area',
        'type'  => 'switcher',
        'title' => '封面社交区域',
        'label'   => '默认开启，显示封面随机图片切换按钮和社交网络图标',
        'default' => true
      ),

      array(
        'id'          => 'social_display_icon',
        'type'        => 'image_select',
        'title'       => '社交网络图标',
        'desc'   => '选择你喜欢的图标包。图标包引用信息详见关于主题',
        'options'     => array(
          'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/display_icon/fluent_design'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/display_icon_fd.gif',
          'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/display_icon/muh2'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/display_icon_h2.gif',
          'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/display_icon/flat_colorful'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/display_icon_fc.gif',
          'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/display_icon/sakura'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/display_icon_sa.gif',
          'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/display_icon/macaronblue'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/display_icon_mb.png',
          'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/display_icon/macarongreen'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/display_icon_mg.png',
          'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/display_icon/macaronpurple'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/display_icon_mp.png',
          'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/display_icon/pink'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/display_icon_sp.png',
          'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/display_icon/orange'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/display_icon_so.png',
          'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/display_icon/sangosyu'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/display_icon_sg.png',
          'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/display_icon/sora'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/display_icon_ts.png',
          'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/display_icon/nae'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/display_icon_nn.png',
        ),
        'default'     => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/display_icon/fluent_design'
      ),

      array(
        'id'    => 'social_area_radius',
        'type'  => 'slider',
        'title' => '封面社交区域圆角',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '滑动滑块，推荐数值范围为10-20',
        'unit'    => 'px',
        'max'   => '30',
        'default' => '15'
      ),

      array(
        'id'    => 'cover_random_graphs_switch',
        'type'  => 'switcher',
        'title' => '封面随机图片切换按钮',
        'dependency' => array( 'social_area', '==', 'true' ),
        'label'   => '默认开启，显示封面随机图切换按钮',
        'default' => true
      ),

      array(
        'type'    => 'subheading',
        'content' => '社交网络',
      ),

      array(
        'id'     => 'wechat',
        'type'  => 'upload',
        'title' => '微信',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '最佳比例1比1',
        'library'      => 'image',
      ),

      array(
        'id'     => 'qq',
        'type'   => 'text',
        'title'  => 'QQ',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '请注意填写格式，例如：tencent://message/?uin=123456',
      ),

      array(
        'id'     => 'bili',
        'type'   => 'text',
        'title'  => '哔哩哔哩',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '填写地址',
      ),

      array(
        'id'     => 'wangyiyun',
        'type'   => 'text',
        'title'  => '网易云音乐',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '填写地址',
      ),

      array(
        'id'     => 'sina',
        'type'   => 'text',
        'title'  => '新浪微博',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '填写地址',
      ),

      array(
        'id'     => 'github',
        'type'   => 'text',
        'title'  => 'Github',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '填写地址',
      ),

      array(
        'id'     => 'telegram',
        'type'   => 'text',
        'title'  => 'Telegram',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '填写地址',
      ),

      array(
        'id'     => 'steam',
        'type'   => 'text',
        'title'  => 'Steam',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '填写地址',
      ),

      array(
        'id'     => 'zhihu',
        'type'   => 'text',
        'title'  => '知乎',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '填写地址',
      ),

      array(
        'id'     => 'qzone',
        'type'   => 'text',
        'title'  => 'QQ空间',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '填写地址',
      ),

      array(
        'id'     => 'lofter',
        'type'   => 'text',
        'title'  => 'Lofter',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '填写地址',
      ),

      array(
        'id'     => 'youku',
        'type'   => 'text',
        'title'  => '优酷视频',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '填写地址',
      ),

      array(
        'id'     => 'linkedin',
        'type'   => 'text',
        'title'  => 'Linkedin',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '填写地址',
      ),

      array(
        'id'     => 'twitter',
        'type'   => 'text',
        'title'  => '推特',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '填写地址',
      ),

      array(
        'id'     => 'facebook',
        'type'   => 'text',
        'title'  => '脸书',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '填写地址',
      ),

      array(
        'id'     => 'csdn',
        'type'   => 'text',
        'title'  => 'CSDN',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '填写地址',
      ),

      array(
        'id'     => 'jianshu',
        'type'   => 'text',
        'title'  => '简书',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '填写地址',
      ),

      array(
        'id'     => 'socialdiy1',
        'type'   => 'text',
        'title'  => '自定义社交网络Ⅰ',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '填写地址',
      ),

      array(
        'id'     => 'socialdiyp1',
        'type'  => 'upload',
        'title' => '自定义社交网络Ⅰ图标',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '最佳比例1比1',
        'library'      => 'image',
      ),

      array(
        'id'     => 'socialdiy2',
        'type'   => 'text',
        'title'  => '自定义社交网络Ⅱ',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '填写地址',
      ),

      array(
        'id'     => 'socialdiyp2',
        'type'  => 'upload',
        'title' => '自定义社交网络Ⅱ图标',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => '最佳比例1比1',
        'library'      => 'image',
      ),

      array(
        'id'     => 'email_name',
        'type'   => 'text',
        'title'  => '邮箱用户名',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => 'name@domain.com的name部分，前端仅具有 js 运行环境时才能获取完整地址，可放心填写',
      ),

      array(
        'id'     => 'email_domain',
        'type'   => 'text',
        'title'  => '邮箱用户名',
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc'   => 'name@domain.com的domain.com部分',
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'homepage', 
    'title'  => '公告栏和区域标题设置',
    'icon'      => 'fa fa-bullhorn',
    'fields' => array(

      array(
        'type'    => 'subheading',
        'content' => '公告栏',
      ),

      array(
        'id'    => 'announce_bar',
        'type'  => 'switcher',
        'title' => '公告栏',
        'label'   => '开启之后公告栏将在首页封面下方显示',
        'default' => false
      ),

      array(
        'id'         => 'announce_bar_style',
        'type'       => 'radio',
        'title'      => '公告栏样式',
        'dependency' => array( 'announce_bar', '==', 'true' ),
        'options'    => array(
          'picture' => '图片背景',
          'pure' => '纯色背景',
        ),
        'default' => 'picture'
      ),

      array(
        'id'    => 'announce_bar_icon',
        'type'  => 'switcher',
        'title' => '公告栏“广播”图标',
        'dependency' => array( 'announce_bar', '==', 'true' ),
        'label'   => '“广播”图标将显示在公告栏的左侧',
        'default' => true
      ),

      array(
        'id'     => 'announcement_bg',
        'type'  => 'upload',
        'title' => '公告栏背景',
        'dependency' => array(
          array( 'announce_bar', '==', 'true' ),
          array( 'announce_bar_style',   '==', 'picture' ),
        ),
        'desc'   => '最佳宽度820px，最佳高度67px',
        'library'      => 'image',
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/encore/announcement_bg.jpg'
      ),

      array(
        'id'      => 'announce_bar_border_color',
        'type'    => 'color',
        'title'   => '公告栏边框颜色',
        'dependency' => array(
          array( 'announce_bar', '==', 'true' ),
          array( 'announce_bar_style',   '==', 'pure' ),
        ),
        'desc'    => '自定义颜色，建议使用与主题色相同色系且属于浅色系的颜色',
        'default' => '#E6E6E6'
      ),

      array(
        'id'     => 'announce_text',
        'type'   => 'text',
        'title'  => '公告文本',
        'dependency' => array( 'announce_bar', '==', 'true' ),
        'desc'   => '填写公告文本，文本超出142个字节将会被隐藏',
      ),

      array(
        'id'          => 'announce_text_align',
        'type'        => 'image_select',
        'title'       => '公告文本对齐方向',
        'dependency' => array( 'announce_bar', '==', 'true' ),
        'options'     => array(
          'left'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/announce_text_left.png',
          'right'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/announce_text_right.png',
          'center'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/announce_text_center.png',
        ),
        'default'     => 'left'
      ),

      array(
        'id'      => 'announce_text_color',
        'type'    => 'color',
        'title'   => '公告文本颜色',
        'dependency' => array( 'announce_bar', '==', 'true' ),
        'desc'    => '自定义颜色，建议根据背景颜色搭配合适的颜色',
        'default' => '#999'
      ),    

      array(
        'type'    => 'subheading',
        'content' => '区域标题',
      ),

      array(
        'id'     => 'exhibition_area_title',
        'type'   => 'text',
        'title'  => '展示区域标题',
        'desc'   => '默认为“展示”，你可以修改为其他，当然不能当广告用！不允许！！',
        'default' => '展示'
      ),

      array(
        'id'     => 'post_area_title',
        'type'   => 'text',
        'title'  => '文章区域标题',
        'desc'   => '默认为“文章”，你可以修改为其他，当然不能当广告用！不允许！！',
        'default' => '文章'
      ),

      array(
        'id'     => 'area_title_font',
        'type'   => 'text',
        'title'  => '区域标题字体',
        'desc'   => '填写字体名称。例如：Ma Shan Zheng',
        'default' => 'Noto Serif SC'
      ),

      array(
        'id'          => 'area_title_text_align',
        'type'        => 'image_select',
        'title'       => '区域标题对齐方向',
        'options'     => array(
          'left'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/area_title_text_left.png',
          'right'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/area_title_text_right.png',
          'center'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/area_title_text_center.png',
        ),
        'default'     => 'left'
      ),

      array(
        'id'      => 'area_title_bottom_color',
        'type'    => 'color',
        'title'   => '区域标题下分隔线颜色',
        'desc'    => '自定义颜色，建议使用与主题色相同色系且属于浅色系的颜色',
        'default' => '#e8e8e8'
      ),  

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'homepage', 
    'title'  => '展示区域设置',
    'icon'      => 'fa fa-bookmark',
    'fields' => array(

      array(
        'id'    => 'exhibition_area',
        'type'  => 'switcher',
        'title' => '展示区域',
        'label'   => '默认开启，展示区域显示在文章区域上方',
        'default' => true
      ),

      array(
        'id'         => 'exhibition_area_style',
        'type'       => 'image_select',
        'title'      => '展示区域样式',
        'options'    => array(
          'left_and_right' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/exhibition_area_style_lr.png',
          'bottom_to_top' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/exhibition_area_style_ud.png',
        ),
        'default'    => 'left_and_right'
      ),

      array(
        'id'    => 'exhibition_area_compat',
        'type'  => 'switcher',
        'title' => '展示区域兼容模式',
        'dependency' => array( 'exhibition_area_style', '==', 'left_and_right' ),
        'label'   => '默认开启，此选项避免了展示区域错位的问题',
        'default' => true
      ),

      array(
        'id'      => 'exhibition_background_color',
        'type'    => 'color',
        'title'   => '展示区域背景颜色',
        'dependency' => array( 'exhibition_area_style', '==', 'left_and_right' ),
        'desc'    => '自定义颜色，建议使用浅色系颜色',
        'default' => 'rgba(255,255,255,0.4)'
      ),
      
      array(
        'id'    => 'exhibition_radius',
        'type'  => 'slider',
        'title' => '展示区域圆角',
        'desc'   => '滑动滑块，推荐数值为15',
        'unit'    => 'px',
        'default' => '15'
      ),

      array(
        'id'            => 'exhibition',
        'type'          => 'tabbed',
        'title'         => '展示区域设置',
        'tabs'          => array(
          array(
            'title'     => '第一展示区域',
            'fields'    => array(
              array(
                'id'    => 'img1',
                'type'  => 'upload',
                'title' => '图片',
                'desc'  => '最佳宽度260px，最佳高度160px',
              ),
              array(
                'id'    => 'title1',
                'type'  => 'text',
                'title' => '标题',
              ),
              array(
                'id'    => 'description1',
                'type'  => 'text',
                'title' => '描述',
              ),
              array(
                'id'    => 'link1',
                'type'  => 'text',
                'title' => '地址',
              ),
            )
          ),
          array(
            'title'     => '第二展示区域',
            'fields'    => array(
              array(
                'id'    => 'img2',
                'type'  => 'upload',
                'title' => '图片',
                'desc'  => '最佳宽度260px，最佳高度160px',
              ),
              array(
                'id'    => 'title2',
                'type'  => 'text',
                'title' => '标题',
              ),
              array(
                'id'    => 'description2',
                'type'  => 'text',
                'title' => '描述',
              ),
              array(
                'id'    => 'link2',
                'type'  => 'text',
                'title' => '地址',
              ),
            )
          ),
          array(
            'title'     => '第三展示区域',
            'fields'    => array(
              array(
                'id'    => 'img3',
                'type'  => 'upload',
                'title' => '图片',
                'desc'    => '最佳宽度260px，最佳高度160px',
              ),
              array(
                'id'    => 'title3',
                'type'  => 'text',
                'title' => '标题',
              ),
              array(
                'id'    => 'description3',
                'type'  => 'text',
                'title' => '描述',
              ),
              array(
                'id'    => 'link3',
                'type'  => 'text',
                'title' => '地址',
              ),
            )
          ),
        ),
        'default'       => array(
          'link1' => '',
          'link2' => '',
          'link3' => '',
          'img1'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/encore/exhibition1.jpg',
          'img2'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/encore/exhibition2.jpg',
          'img3' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/encore/exhibition3.jpg',
          'title1' => 'アンコール',
          'title2' => 'ハルジオン',
          'title3' => 'かいぶつ',
          'description1' => 'ここは夜のない世界',
          'description2' => '過ぎてゆく時間の中',
          'description3' => '素晴らしき世界に今日も乾杯',
        )
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'homepage', 
    'title'  => '文章区域设置',
    'icon'      => 'fa fa-book',
    'fields' => array(

      array(
        'id'         => 'post_list_style',
        'type'       => 'image_select',
        'title'      => '文章区域展示样式',
        'options'    => array(
          'imageflow' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/post_list_style_sakura_left.png',
          'akinastyle' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/post_list_style_akina.png',
        ),
        'default'    => 'imageflow'
      ),

      array(
        'id'         => 'post_list_akina_type',
        'type'       => 'image_select',
        'title'      => '文章区域装饰特色图片展示形状',
        'dependency' => array( 'post_list_style', '==', 'akinastyle' ),
        'desc'   => '你可以选择圆形展示或者矩形展示文章区域装饰特色图片',
        'options'    => array(
          'round' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/post_list_style_akina.png',
          'square' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/post_list_style_akina2.png',
        ),
        'default'    => 'round'
      ),

      array(
        'id'          => 'post_list_image_align',
        'type'        => 'image_select',
        'title'       => '文章区域装饰特色图片对齐方向',
        'dependency' => array( 'post_list_style', '==', 'imageflow' ),
        'desc'   => '你可以选择不同方向展示文章区域装饰特色图片',
        'options'     => array(
          'alternate'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/post_list_style_sakura1.png',
          'left'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/post_list_style_sakura2.png',
          'right'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/post_list_style_sakura3.png',
        ),
        'default'     => 'alternate'
      ),

      array(
        'id'         => 'post_cover_options',
        'type'       => 'radio',
        'title'      => '文章区域装饰特色图片选项',
        'options'    => array(
          'type_1' => '封面随机图片',
          'type_2' => '外部API随机图片',
        ),
        'default'    => 'type_1'
      ),

      array(
        'id'     => 'post_cover',
        'type'   => 'text',
        'title'  => '文章区域装饰特色图片外部API随机图片地址',
        'dependency' => array( 'post_cover_options', '==', 'type_2' ),
        'desc'   => '填写地址',
      ),

      array(
        'id'     => 'post_title_font_size',
        'type'   => 'slider',
        'title'  => '文章区域标题字体大小',
        'desc'   => '滑动滑块，推荐数值范围为16-20',
        'dependency' => array( 'post_list_style', '==', 'imageflow' ),
        'unit'    => 'px',
        'step'   => '1',
        'min'   => '10',
        'max'   => '30',
        'default' => '18'
      ),

      array(
        'id'      => 'post_date_background_color',
        'type'    => 'color',
        'title'   => '文章区域时间提示区域背景颜色',
        'dependency' => array( 'post_list_style', '==', 'imageflow' ),
        'desc'    => '自定义颜色，建议使用与主题色搭配色相同色系且属于浅色系的颜色',
        'default' => '#fff5e0'
      ),    

      array(
        'id'      => 'post_date_text_color',
        'type'    => 'color',
        'title'   => '文章区域时间提示区域文本颜色',
        'dependency' => array( 'post_list_style', '==', 'imageflow' ),
        'desc'    => '自定义颜色，建议使用与主题色搭配色相同色系的颜色',
        'default' => '#ffcc00'
      ),    

      array(
        'id'     => 'post_date_font_size',
        'type'   => 'slider',
        'title'  => '文章区域时间提示区域字体大小',
        'dependency' => array( 'post_list_style', '==', 'imageflow' ),
        'desc'   => '滑动滑块，推荐数值范围为10-14',
        'unit'    => 'px',
        'step'   => '1',
        'min'   => '6',
        'max'   => '20',
        'default' => '12'
      ),

      array(
        'id'    => 'post_icon_more',
        'type'  => 'switcher',
        'title' => '文章区域“详细”图标',
        'label'   => '开启之后“详细”图标将显示在文章区域内下方',
        'default' => false
      ),array(
        'id'    => 'is_author_meta_show',
        'type'  => 'switcher',
        'title' => '文章区域“作者信息”',
        'label'   => '开启之后，文章元数据部分将增加作者信息。',
        'default' => false
      ),

      array(
        'id'      => 'post_icon_color',
        'type'    => 'color',
        'title'   => '文章区域图标颜色',
        'dependency' => array( 'post_list_style', '==', 'imageflow' ),
        'desc'    => '自定义颜色，建议使用与主题色搭配色相同色系的颜色',
        'default' => '#ffcc00'
      ),    

      array(
        'id'      => 'post_border_shadow_color',
        'type'    => 'color',
        'title'   => '文章区域边框阴影颜色',
        'dependency' => array( 'post_list_style', '==', 'imageflow' ),
        'desc'    => '自定义颜色，建议使用与主题色相同色系且属于浅色系的颜色',
        'default' => '#e8e8e8'
      ),    

    )
  ) );

  CSF::createSection( $prefix, array(
    'id'    => 'page', 
    'title' => '页面设置',
    'icon'      => 'fa fa-file-text',
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'page', 
    'title'  => '综合设置',
    'icon'      => 'fa fa-compass',
    'fields' => array(

      array(
        'id'         => 'page_style',
        'type'       => 'image_select',
        'title'      => '页面样式',
        'options'    => array(
          'sakurairo' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/page_style_iro.png',
          'sakura' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/page_style_sakura.png',
        ),
        'default'    => 'sakurairo'
      ),

      array(
        'id'         => 'entry_content_style',
        'type'       => 'radio',
        'title'      => '页面排版样式',
        'options'    => array(
          'sakurairo' => '默认样式',
          'github' => 'Github样式',
        ),
        'default'    => 'sakurairo'
      ),

      array(
        'id'    => 'patternimg',
        'type'  => 'switcher',
        'title' => '页面装饰图片',
        'label'   => '默认开启，显示在文章页面，独立页面和分类页',
        'default' => true
      ),

      array(
        'id'    => 'page_title_animation',
        'type'  => 'switcher',
        'title' => '页面标题动画',
        'label'   => '开启之后页面标题将有浮入动画',
        'default' => true
      ),

      array(
        'id'     => 'page_title_animation_time',
        'type'   => 'slider',
        'title'  => '页面标题动画时间',
        'dependency' => array( 'page_title_animation', '==', 'true' ),
        'desc'   => '滑动滑块，推荐数值范围为1-2',
        'step'   => '0.01',
        'unit'    => 's',
        'max'   => '5',
        'default' => '2'
      ),

      array(
        'id'    => 'clipboard_copyright',
        'type'  => 'switcher',
        'title' => '页面剪切板版权提示',
        'label'   => '默认开启，用户在复制文字内容超过30字节时，会有版权提示文本',
        'default' => true
      ),

      array(
        'id'    => 'page_lazyload',
        'type'  => 'switcher',
        'title' => '页面LazyLoad加载',
        'label'   => '默认开启，页面图片会有LazyLoad加载效果',
        'default' => true
      ),

      array(
        'id'     => 'page_lazyload_spinner',
        'type'   => 'text',
        'title'  => '页面LazyLoad加载占位SVG',
        'dependency' => array( 'page_lazyload', '==', 'true' ),
        'desc'   => '填写地址，此为页面LazyLoad加载时会显示的占位图',
        'default'    => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/load_svg/inload.svg'
      ),

      array(
        'id'     => 'load_in_svg',
        'type'   => 'text',
        'title'  => '页面图像加载占位SVG',
        'desc'   => '填写地址，此为加载页面图像时占位显示的SVG',
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/load_svg/inload.svg'
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'page', 
    'title'  => '文章页面设置',
    'icon'      => 'fa fa-archive',
    'fields' => array(

      array(
        'id'     => 'article_title_font_size',
        'type'   => 'slider',
        'title'  => '文章页面标题字体大小',
        'desc'   => '滑动滑块，推荐数值范围为28-36。此选项仅对已经设置了特色图片的文章页面生效',
        'unit'    => 'px',
        'min'   => '16',
        'max'   => '48',
        'default' => '32'
      ),

      array(
        'id'    => 'article_title_line',
        'type'  => 'switcher',
        'title' => '文章页面标题下划线动画',
        'label'   => '开启且文章设置了特色图片之后，文章标题将有下划线动画',
        'default' => false
      ),

      array(
        'id'    => 'article_auto_toc',
        'type'  => 'switcher',
        'title' => '文章页面自动显示菜单',
        'label'   => '默认开启，文章页面会自动显示菜单',
        'default' => true
      ),

      array(
        'id'    => 'article_nextpre',
        'type'  => 'switcher',
        'title' => '文章页面上下文章切换',
        'label'   => '开启之后文章页面将出现上下文章切换',
        'default' => false
      ),

      array(
        'id'    => 'article_lincenses',
        'type'  => 'switcher',
        'title' => '文章页面版权提示和标签',
        'label'   => '开启之后文章页面将出现版权提示和标签',
        'default' => false
      ),

      array(
        'id'    => 'author_profile',
        'type'  => 'switcher',
        'title' => '文章页面作者信息',
        'label'   => '开启之后文章页面将出现作者信息',
        'default' => false
      ),

      array(
        'id'     => 'author_profile_text',
        'type'   => 'text',
        'title'  => '文章页面作者信息签名栏文本',
        'dependency' => array( 'author_profile', '==', 'true' ),
        'desc'   => '一段自我描述的话',
        'default'    => '本当の声を響かせてよ'
      ),

      array(
        'id'    => 'alipay_code',
        'type'  => 'upload',
        'title' => '文章页面赞赏按钮支付宝二维码',
        'desc'   => '上传支付宝收款码图片',
        'library'      => 'image',
      ),

      array(
        'id'    => 'wechat_code',
        'type'  => 'upload',
        'title' => '文章页面赞赏按钮微信二维码',
        'desc'   => '上传微信收款码图片',
        'library'      => 'image',
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'page', 
    'title'  => '模板页面设置',
    'icon'      => 'fa fa-window-maximize',
    'fields' => array(

      array(
        'id'     => 'page_temp_title_font_size',
        'type'   => 'slider',
        'title'  => '模板页面标题字体大小',
        'desc'   => '滑动滑块，推荐数值范围为36-48。此选项仅对已经设置了特色图片的模板页面生效',
        'unit'    => 'px',
        'min'   => '20',
        'max'   => '64',
        'default' => '40'
      ),

      array(
        'id'      => 'shuoshuo_background_color1',
        'type'    => 'color',
        'title'   => '说说模板说说背景颜色Ⅰ',
        'desc'    => '自定义颜色',
        'default' => '#ffe066'
      ),    

      array(
        'id'      => 'shuoshuo_background_color2',
        'type'    => 'color',
        'title'   => '说说模板说说背景颜色Ⅱ',
        'desc'    => '自定义颜色',
        'default' => '#ffcc00'
      ),    

      array(
        'id'    => 'shuoshuo_arrow',
        'type'  => 'switcher',
        'title' => '说说模板说说提示箭头',
        'label'   => '开启之后提示箭头将出现在说说左侧上方',
        'default' => false
      ),

      array(
        'id'     => 'shuoshuo_font',
        'type'   => 'text',
        'title'  => '说说模板说说字体',
        'desc'   => '填写字体名称。例如：Ma Shan Zheng',
        'default' => 'Noto Serif SC'
      ),

      array(
        'id'     => 'bilibili_id',
        'type'   => 'text',
        'title'  => '哔哩哔哩追番模板帐号ID',
        'desc'   => '填写你的帐号ID，例如：https://space.bilibili.com/13972644/，只需填写数字“13972644”部分',
        'default'    => '13972644'
      ),

      array(
        'id'     => 'bilibili_cookie',
        'type'   => 'text',
        'title'  => '哔哩哔哩追番模板帐号Cookies',
        'desc'   => '填写你的帐号Cookies，F12打开浏览器网络面板，前往你的B站主页获取Cookies。如果留空，将不会显示追番进度',
        'default'    => 'LIVE_BUVID='
      ),

      array(
        'id'          => 'friend_link_align',
        'type'        => 'image_select',
        'title'       => '友情链接模板单元对齐方向',
        'options'     => array(
          'left'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/friend_link_left.png',
          'right'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/friend_link_right.png',
          'center'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/friend_link_center.png',
        ),
        'default'     => 'left'
      ),

      array(
        'id'     => 'ex_register_open',
        'type'   => 'switcher',
        'title'  => '登录模板注册功能',
        'label'   => '开启之后登录模板将允许注册',
        'default' => false
      ),

      array(
        'id'     => 'registration_validation',
        'type'   => 'switcher',
        'title'  => '登录模板注册滑动验证',
        'label'   => '开启之后登录模板注册需要经过滑动验证',
        'default' => false
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'page', 
    'title'  => '评论相关设置',
    'icon'      => 'fa fa-comments-o',
    'fields' => array(

      array(
        'id'         => 'comment_area',
        'type'       => 'radio',
        'title'      => '页面评论区域显示',
        'desc'    => '你可以选择展开显示或者收缩显示评论区域内容',
        'options'    => array(
          'unfold' => '展开显示',
          'fold' => '收缩显示',
        ),
        'default'    => 'unfold'
      ),

      array(
        'id'     => 'comment_smile_bilibili',
        'type'   => 'switcher',
        'title'  => '页面评论区域哔哩哔哩表情包',
        'label'   => '默认开启，哔哩哔哩表情包显示在评论框的下方',
        'default' => true
      ),

      array(
        'id'    => 'comment_area_image',
        'type'  => 'upload',
        'title' => '页面评论区域右下背景图片',
        'desc'   => '如果此选项为空白，则没有图像，无最佳推荐',
        'library'      => 'image',
      ),

      array(
        'id'     => 'comment_useragent',
        'type'   => 'switcher',
        'title'  => '页面评论区域UA信息',
        'label'   => '开启之后页面评论区域将显示用户的浏览器，操作系统信息',
        'default' => false
      ),

      array(
        'id'     => 'comment_location',
        'type'   => 'switcher',
        'title'  => '页面评论区域位置信息',
        'label'   => '开启之后页面评论区域将显示用户的位置信息',
        'default' => false
      ),

      array(
        'id'     => 'comment_private_message',
        'type'   => 'switcher',
        'title'  => '私人评论功能',
        'label'   => '开启之后将允许用户设置自己的评论对其他人不可见',
        'default' => false
      ),

      array(
        'id'     => 'not_robot',
        'type'   => 'switcher',
        'title'  => '页面评论区域机器人验证',
        'label'   => '开启之后用户评论前需要经过验证后才可发布',
        'default' => false
      ),

      array(
        'id'          => 'qq_avatar_link',
        'type'        => 'select',
        'title'       => 'QQ头像链接加密',
        'options'     => array(
          'off'  => '关闭',
          'type_1'  => '重定向（低安全性）',
          'type_2'  => '后端获取头像数据（中安全性）',
          'type_3'  => '后端解析头像接口（高安全性，慢速）',
        ),
        'default'     => 'off'
      ),

      array(
        'id'          => 'img_upload_api',
        'type'        => 'select',
        'title'       => '页面评论区域上传图片接口',
        'options'     => array(
          'off'  => '关闭',
          'imgur'  => 'Imgur (https://imgur.com)',
          'smms'  => 'SM.MS (https://sm.ms)',
          'chevereto'  => 'Chevereto (https://chevereto.com)',
        ),
        'default'     => 'off'
      ),

      array(
        'id'     => 'imgur_client_id',
        'type'   => 'text',
        'title'  => 'Imgur Client ID',
        'dependency' => array( 'img_upload_api', '==', 'imgur' ),
        'desc'   => '此处填写Client ID，注册请访问 https://api.imgur.com/oauth2/addclient ',
      ),

      array(
        'id'     => 'imgur_upload_image_proxy',
        'type'   => 'text',
        'title'  => 'Imgur上传代理',
        'dependency' => array( 'img_upload_api', '==', 'imgur' ),
        'desc'   => '后端上传图片到 Imgur 的时候使用的代理。你可以参考教程：https://2heng.xin/2018/06/06/javascript-upload-images-with-imgur-api/',
        'default'     => 'https://api.imgur.com/3/image/'
      ),

      array(
        'id'     => 'smms_client_id',
        'type'   => 'text',
        'title'  => 'SM.MS Secret Token',
        'dependency' => array( 'img_upload_api', '==', 'smms' ),
        'desc'   => '此处填写Key，获取请访问 https://sm.ms/home/apitoken ',
      ),

      array(
        'id'     => 'chevereto_api_key',
        'type'   => 'text',
        'title'  => 'Chevereto API v1 Key',
        'dependency' => array( 'img_upload_api', '==', 'chevereto' ),
        'desc'   => '此处填写Key，获取请访问你的Chevereto首页地址/dashboard/settings/api ',
      ),

      array(
        'id'     => 'cheverto_url',
        'type'   => 'text',
        'title'  => 'Chevereto地址',
        'dependency' => array( 'img_upload_api', '==', 'chevereto' ),
        'desc'   => '你的Chevereto首页地址, 注意结尾没有 /, 例如：https://your.cherverto.com',
      ),

      array(
        'id'     => 'comment_image_proxy',
        'type'   => 'text',
        'title'  => '评论图片代理',
        'desc'   => '前端显示的图片的代理',
        'default'     => 'https://images.weserv.nl/?url='
      ),

      array(
        'id'    => 'mail_img',
        'type'  => 'upload',
        'title' => '邮件模板特色图片',
        'desc'   => '设置你的回复邮件上方背景图片',
        'library'      => 'image',
        'default'     => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/encore/mail_head.jpg'
      ),

      array(
        'id'     => 'mail_user_name',
        'type'   => 'text',
        'title'  => '邮件模板发件地址前缀',
        'desc'   => '用于发送系统邮件，在用户的邮箱中显示的发件人地址，不要使用中文，默认系统邮件地址为 bibi@你的域名',
        'default'     => 'bibi'
      ),

      array(
        'id'     => 'mail_notify',
        'type'   => 'switcher',
        'title'  => '用户邮件回复通知',
        'label'   => 'WordPress默认会使用邮件通知用户评论收到回复，开启之后允许用户设置自己的评论收到回复时是否使用邮件通知',
        'default' => false
      ),

      array(
        'id'     => 'admin_notify',
        'type'   => 'switcher',
        'title'  => '管理员邮件回复通知',
        'label'   => '开启之后当管理员评论收到回复时使用邮件通知',
        'default' => false
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'id'    => 'others', 
    'title' => '其他设置',
    'icon'      => 'fa fa-coffee',
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'others', 
    'title'  => '登录界面和仪表盘相关设置',
    'icon'      => 'fa fa-sign-in',
    'fields' => array(

      array(
        'type'    => 'subheading',
        'content' => '登录界面',
      ),

      array(
        'id'    => 'login_background',
        'type'  => 'upload',
        'title' => '登录界面背景图片',
        'desc'   => '设置你的登录界面背景图片，此选项留空则显示默认背景',
        'library'      => 'image',
        'default'     => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/encore/login_background.jpg'
      ),

      array(
        'id'     => 'login_blur',
        'type'   => 'switcher',
        'title'  => '登录界面背景虚化',
        'label'   => '开启之后登录界面背景图片将被虚化',
        'default' => false
      ),

      array(
        'id'    => 'login_logo_img',
        'type'  => 'upload',
        'title' => '登录界面Logo',
        'desc'   => '设置你的登录界面Logo',
        'library'      => 'image',
        'default'     => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/encore/login_logo.png'
      ),

      array(
        'id'     => 'login_urlskip',
        'type'   => 'switcher',
        'title'  => '登录后跳转',
        'label'   => '开启之后管理员跳转至后台，用户跳转至主页',
        'default' => false
      ),

      array(
        'type'    => 'subheading',
        'content' => '仪表盘',
      ),

      array(
        'id'    => 'admin_background',
        'type'  => 'upload',
        'title' => '仪表盘背景图片',
        'desc'   => '设置你的仪表盘背景图片，此选项留空则显示白色背景',
        'library'      => 'image',
        'default'     => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/encore/admin_background.jpg'
      ),

      array(
        'id'      => 'admin_first_class_color',
        'type'    => 'color',
        'title'   => '仪表盘一级菜单颜色',
        'desc'    => '自定义颜色',
        'default' => '#ffa670'
      ),  

      array(
        'id'      => 'admin_second_class_color',
        'type'    => 'color',
        'title'   => '仪表盘二级菜单颜色',
        'desc'    => '自定义颜色',
        'default' => '#ffb281'
      ),  

      array(
        'id'      => 'admin_emphasize_color',
        'type'    => 'color',
        'title'   => '仪表盘强调颜色',
        'desc'    => '自定义颜色',
        'default' => '#634a4c'
      ),  

      array(
        'id'      => 'admin_button_color',
        'type'    => 'color',
        'title'   => '仪表盘按钮颜色',
        'desc'    => '自定义颜色',
        'default' => '#ab705f'
      ),  

      array(
        'id'      => 'admin_text_color',
        'type'    => 'color',
        'title'   => '仪表盘文本颜色',
        'desc'    => '自定义颜色',
      ),  

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'others', 
    'title'  => '低使用设置',
    'icon'      => 'fa fa-low-vision',
    'fields' => array(

      array(
        'id'         => 'statistics_api',
        'type'       => 'radio',
        'title'      => '统计接口',
        'desc'    => '你可以选择WP-Statistics插件统计或者主题内建统计作为统计结果',
        'options'    => array(
          'theme_build_in' => '主题内建统计',
          'wp_statistics' => 'WP-Statistics插件统计',
        ),
        'default'    => 'theme_build_in'
      ),

      array(
        'id'          => 'statistics_format',
        'type'        => 'select',
        'title'       => '统计数据显示格式',
        'desc'   => '你可以选择四种不同的数据显示格式',
        'options'     => array(
          'type_1'  => '23333次访问',
          'type_2'  => '23,333次访问',
          'type_3'  => '23 333次访问',
          'type_4'  => '23K次访问',
        ),
        'default'     => 'type_1'
      ),

      array(
        'id'     => 'live_search',
        'type'   => 'switcher',
        'title'  => '实时搜索',
        'label'   => '开启之后将在前台实现实时搜索，调用 Rest API 每小时更新一次缓存，可在 api.php 里手动设置缓存时间',
        'default' => false
      ),

      array(
        'id'     => 'live_search_comment',
        'type'   => 'switcher',
        'title'  => '实时搜索评论支援',
        'label'   => '开启之后将在可在实时搜索中搜索评论（如果网站评论数量太多不建议开启）',
        'default' => false
      ),

      array(
        'id'     => 'gravatar_proxy',
        'type'   => 'text',
        'title'  => 'Gravatar头像代理',
        'desc'   => '填写Gravatar头像的代理地址，默认使用极客族代理，留空则不使用代理',
        'default'     => 'sdn.geekzu.org/avatar'
      ),

      array(
        'id'     => 'google_analytics_id',
        'type'   => 'text',
        'title'  => '谷歌统计代码',
      ),

      array(
        'id'     => 'site_custom_style',
        'type'   => 'textarea',
        'title'  => '自定义CSS样式',
        'desc'   => '填写CSS代码，不需要写style标签',
      ),

      array(
        'id'     => 'block_library_css',
        'type'   => 'switcher',
        'title'  => 'WordPress区块编辑器CSS',
        'label'   => '开启之后将加载WordPress区块编辑器CSS',
        'default' => false
      ),

      array(
        'id'     => 'time_zone_fix',
        'type'   => 'slider',
        'title'  => '时区修正',
        'desc'   => '滑动滑块，如果评论出现时差问题在这里调整，填入一个整数，计算方法：实际时间=显示错误的时间-你输入的整数（单位：小时）',
        'step'   => '1',
        'max'   => '24',
        'default'    => '0'
      ),

      array(
        'type'    => 'submessage',
        'style'   => 'danger',
        'content' => '以下设置不推荐盲目进行修改，请在他人的指导下使用',
      ),

      array(
        'id'     => 'image_cdn',
        'type'   => 'text',
        'title'  => '图片CDN',
        'desc'   => '注意：填写格式为 http(s)://你的CDN域名/。也就是说，原路径为 http://your.domain/wp-content/uploads/2018/05/xx.png 的图片将从 http://你的CDN域名/2018/05/xx.png 加载',
        'default'     => ''
      ),

      array(
        'id'     => 'classify_display',
        'type'   => 'text',
        'title'  => '不显示的文章分类',
        'desc'   => '填写分类ID，多个用英文“ , ”分开',
      ),

      array(
        'id'     => 'image_category',
        'type'   => 'text',
        'title'  => '图片展示分类',
        'desc'   => '填写分类ID，多个用英文“ , ”分开',
      ),

      array(
        'id'     => 'exlogin_url',
        'type'   => 'text',
        'title'  => '指定登录地址',
        'desc'   => '强制不使用WordPress登录页面地址登录，填写新建的登陆页面地址，比如：http://www.xxx.com/login。注意填写前先测试下你新建的页面是可以正常打开的，以免造成无法进入后台等情况',
      ),

      array(
        'id'     => 'exregister_url',
        'type'   => 'text',
        'title'  => '指定注册地址',
        'desc'   => '该地址在登录页面作为注册入口，如果你指定了登录地址，则建议填写',
      ),

      array(
        'id'     => 'cookie_version',
        'type'   => 'text',
        'title'  => '版本控制',
        'desc'   => '用于更新前端Cookie和浏览器缓存，可使用任意字符串',
      ),

      array(
        'id'     => 'image_viewer',
        'type'   => 'switcher',
        'title'  => 'BaguetteBox灯箱效果',
        'label'   => '开启之后将替换Fancybox作为图片灯箱效果，不建议使用',
        'default' => false
      ),

    )
  ) );

  CSF::createSection($prefix, array(
    'title'       => '备份恢复',
    'icon'        => 'fa fa-shield',
    'description' => '备份或恢复你的主题设置',
    'fields'      => array(

        array(
            'type' => 'backup',
        ),

    )
  ) );

  CSF::createSection($prefix, array(
    'title'       => '关于主题',
    'icon'        => 'fa fa-paperclip',
    'fields'      => array(

      array(
        'type'    => 'subheading',
        'content' => '版本信息',
      ),

      array(
        'type'    => 'content',
        'content' => '<img src="https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/encorelogo.gif"  alt="主题信息" />',
      ),

      array(
        'type'    => 'submessage',
        'style'   => 'success',
        'content' => sprintf(__('正在使用 iro 主题 版本 %s  |  <a href="https://iro.tw">主题文档</a>  |  <a href="https://github.com/mirai-mamori/Sakurairo">源码地址</a>', 'sakurairo'), SAKURA_VERSION), 
      ),

      array(
        'type'    => 'subheading',
        'content' => '更新相关',
      ),

      array(
        'id'          => 'iro_update_source',
        'type'        => 'image_select',
        'title'       => '主题更新源',
        'options'     => array(
          'github'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/update_source_github.png',
          'jsdelivr'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/update_source_jsd.png',
          'official_building'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/update_source_iro.png',
        ),
        'default'     => 'github'
      ),

      array(
        'id'       => 'channel_validate_value',
        'type'     => 'text',
        'title'    => '主题更新测试通道免责声明',
        'dependency' => array(
          array( 'local_global_library',   '==', 'true' ),
          array( 'iro_update_source',   '==', 'official_building' ),
        ),
        'desc'   => '如果你想要使用测试通道版本的主题，请在确保你已经认真了解参与测试带来的风险并且愿意自行承担一切后果（包括但不限于可能的数据丢失）之后，复制后文引号内的文本到选项文本框内“我已了解测试带来的风险并愿意承担所有后果”',
      ),

      array(
        'id'         => 'iro_update_channel',
        'type'       => 'radio',
        'title'      => '主题更新频道',
        'dependency' => array(
          array( 'channel_validate_value', '==', '我已了解测试带来的风险并愿意承担所有后果' ),
          array( 'local_global_library',   '==', 'true' ),
          array( 'iro_update_source',   '==', 'official_building' ),
        ),
        'desc'    => '你可以在此切换更新频道以参与到新版本的测试中',
        'options'    => array(
          'stable' => '正式频道',
          'beta' => '公共测试频道',
          'preview' => '预览测试频道',
        ),
        'default'    => 'stable'
      ),

      array(
        'type'    => 'subheading',
        'content' => '本地化',
      ),

      array(
        'id'     => 'local_global_library',
        'type'   => 'switcher',
        'title'  => '本地化前端库',
        'label'   => '开启之后前端库将不走jsDelivr CDN',
        'default' => false
      ),

      array(
        'id'     => 'local_application_library',
        'type'   => 'switcher',
        'title'  => '本地化JS/CSS文件',
        'label'   => '默认开启，部分JS文件和CSS文件不走jsDelivr CDN',
        'default' => true
      ),

      array(
        'type'    => 'subheading',
        'content' => '引用信息',
      ),

      array(
        'type'    => 'content',
        'content' => '<p>流畅设计图标引用了由 Paradox 设计的 <a href="https://wwi.lanzous.com/ikyq5kgx0wb">Fluent图标包</a></p>
        <p>沐氢图标引用了由 缄默 设计的 <a href="https://www.coolapk.com/apk/com.muh2.icon">沐氢图标包</a></p>
        <p>看板娘引用了由 Stevenjoezhang 开源的 <a href="https://github.com/stevenjoezhang/live2d-widget">Live2d-Widget</a> 项目</p>
        <p>白猫样式Logo参考原主题作者白猫，由 <a href="https://hyacm.com/acai/ui/143/sakura-logo/">Hyacm</a> 提供方案并引用</p>',
      ),

      array(
        'type'    => 'subheading',
        'content' => '依赖信息',
      ),

      array(
        'type'    => 'content',
        'content' => '<p>静态资源依赖于主题官方 Fuukei 创建的 <a href="https://github.com/Fuukei/Public_Repository">Public Repository</a> 项目</p>
        <p>设置框架依赖于 Codestar 开源的 <a href="https://github.com/Codestar/codestar-framework">Codestar-Framework</a> 项目</p>
        <p>更新功能依赖于 YahnisElsts 开源的 <a href="https://github.com/YahnisElsts/plugin-update-checker">Plugin-Update-Checker</a> 项目</p>',
      ),

      array(
        'type'    => 'content',
        'content' => '<img src="https://img.shields.io/github/v/release/mirai-mamori/Sakurairo.svg?style=flat-square"  alt="主题最新版本" />  <img src="https://img.shields.io/github/release-date/mirai-mamori/Sakurairo?style=flat-square"  alt="主题最新版本发布时间" />  <img src="https://data.jsdelivr.com/v1/package/gh/Fuukei/Public_Repository/badge"  alt="主题CDN资源访问量" />',
      ),

    )
  ) );

}

