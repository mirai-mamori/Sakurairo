<?php
if( class_exists( 'CSF' ) ) {

  $prefix = 'iro_options';

  CSF::createOptions( $prefix, array(
    'menu_title' => __('iro-Options','csf'),
    'menu_slug'  => 'iro_options',
  ) );

  CSF::createSection( $prefix, array(
    'id'    => 'preliminary',
    'title' => __('Preliminary','csf'),
    'icon'      => 'fa fa-sliders',
    'fields' => array(

      array(
        'id'    => 'site_name',
        'type'  => 'text',
        'title' => __('Site Name','csf'),
        'desc'   => __('For example:Sakurairo Blog','csf'),
      ),

      array(
        'id'    => 'author_name',
        'type'  => 'text',
        'title' => __('Author Name','csf'),
        'desc'   => __('For example:Fuukei','csf'),
      ),

      array(
        'id'    => 'personal_avatar',
        'type'  => 'upload',
        'title' => __('Personal Avatar','csf'),
        'desc'   => __('The best ratio is 1:1','csf'),
        'library'      => 'image',
      ),

      array(
        'id'    => 'text_logo_options',
        'type'  => 'switcher',
        'title' => __('Mashiro Special Effects Text','csf'),
        'label'   => __('After opening, the personal avatar will be replaced as the home page display content','csf'),
        'default' => false
      ),

      array(
        'id'        => 'text_logo',
        'type'      => 'fieldset',
        'title'     => __('Mashiro Special Effects Text Options','csf'),
        'dependency' => array( 'text_logo_options', '==', 'true' ),
        'fields'    => array(
          array(
            'id'     => 'text',
            'type'   => 'text',
            'title'  => __('Text','csf'),
            'desc'   => __('It is recommended that the text content should not be too long, and the recommended length is 16 bytes.','csf'),
          ),
          array(
            'id'     => 'font',
            'type'   => 'text',
            'title'  => __('Font','csf'),
            'desc'   => __('Fill in the font name. For example: Ma Shan Zheng','csf'),
          ),
          array(
            'id'     => 'size',
            'type'   => 'slider',
            'title'  => __('Size','csf'),
            'desc'   => __('Sliding slider, the recommended value range is 70-90','csf'),
            'unit'    => 'px',
            'min'   => '40',
            'max'   => '140',
          ),
          array(
            'id'      => 'color',
            'type'    => 'color',
            'title'   => __('Color','csf'),
            'desc'    => __('Custom colors, light colors are recommended','csf'),
          ),      
        ),
        'default'        => array(
          'text'    => 'ひょうりゅ',
          'size'    => '80',
          'color'    => '#FFF',
        ),
      ),

      array(
        'id'    => 'iro_logo',
        'type'  => 'upload',
        'title' => __('Navigation Menu Logo','csf'),
        'desc'   => __('The best size is 40px, and the navigation menu text logo will not be displayed after filling in','csf'),
        'library'      => 'image',
      ),

      array(
        'id'    => 'favicon_link',
        'type'  => 'text',
        'title' => __('Site Icon','csf'),
        'desc'   => __('Fill in the address, which is the icon next to the title above the browser','csf'),
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/basic/favicon.ico'
      ),

      array(
        'id'    => 'iro_meta',
        'type'  => 'switcher',
        'title' => __('Custom Site Keywords and Descriptions','csf'),
        'label'   => __('After opening, you can customize the site keywords and descriptions','csf'),
        'default' => false
      ),

      array(
        'id'     => 'iro_meta_keywords',
        'type'   => 'text',
        'title'  => __('Site Keywords','csf'),
        'dependency' => array( 'iro_meta', '==', 'true' ),
        'desc'   => __('The keywords are separated with half width comma "," and the number is best within 5','csf'),
      ),

      array(
        'id'     => 'iro_meta_description',
        'type'   => 'text',
        'title'  => __('Site Descriptions','csf'),
        'dependency' => array( 'iro_meta', '==', 'true' ),
        'desc'   => __('Use concise words to describe the site, the number of words is recommended within 120 words','csf'),
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'id'    => 'global', 
    'title' => __('Global','csf'),
    'icon'      => 'fa fa-globe',
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'global', 
    'title'  => __('Appearance Options','csf'),
    'icon'      => 'fa fa-tree',
    'fields' => array(

      array(
        'type'    => 'subheading',
        'content' => __('Color Schemes','csf'),
      ),

      array(
        'id'      => 'theme_skin',
        'type'    => 'color',
        'title'   => __('Theme Color','csf'),
        'desc'    => __('Custom colors','csf'),
        'default' => '#505050'
      ),  

      array(
        'id'      => 'theme_skin_matching',
        'type'    => 'color',
        'title'   => __('Theme Matching Color','csf'),
        'desc'    => __('Custom colors','csf'),
        'default' => '#ffe066'
      ),  

      array(
        'type'    => 'subheading',
        'content' => __('Dark Mode','csf'),
      ),

      array(
        'id'      => 'theme_skin_dark',
        'type'    => 'color',
        'title'   => __('Dark Mode Theme Color','csf'),
        'desc'    => __('Custom colors','csf'),
        'default' => '#ffcc00'
      ),  
      array(
        'id'    => 'theme_darkmode_auto',
        'type'  => 'switcher',
        'title' => __('Automatic Switch of Dark Mode','csf'),
        'label'   => __('Default on','csf'),
        'default' => true
      ),
      array(
        'type'    => 'content',
        'content' => __(
         '<p><strong>Client local time:</strong>Dark mode will switch automatically from 22:00 to 7:00</p>'
        .'<p><strong>Follow client settings:</strong>Follow client browser settings</p>'
        .'<p><strong>Always on:</strong>Always on, unless otherwise configured by the client</p>','csf'),
        'dependency' => array( 'theme_darkmode_auto', '==', 'true' ),

      ),
      array(
        'id'    => 'theme_darkmode_strategy',
        'type'  => 'select',
        'title' => __('Automatic Switch Strategy of Dark Mode','csf'),
        'dependency' => array( 'theme_darkmode_auto', '==', 'true' ),
        'options'     => array(
          'time'  => __('Client local time','csf'),
          'client'  => __('Follow client settings','csf'),
          'eien'  => __('Always on','csf'),
        ),
        "default"=>"time"
      ),

      array(
        'id'     => 'theme_darkmode_img_bright',
        'type'   => 'slider',
        'title'  => __('Dark Mode Image Brightness','csf'),
        'desc'   => __('Sliding slider, the recommended sliding value range is 0.6-0.8','csf'),
        'step'   => '0.01',
        'min'   => '0.4',
        'max'   => '1',
        'default' => '0.8'
      ),

      array(
        'id'     => 'theme_darkmode_widget_transparency',
        'type'   => 'slider',
        'title'  => __('Dark mode Component Transparency','csf'),
        'desc'   => __('Sliding slider, the recommended sliding value range is 0.6-0.8','csf'),
        'step'   => '0.01',
        'min'   => '0.2',
        'max'   => '1',
        'default' => '0.8'
      ),
      array(
        'id'     => 'theme_darkmode_background_transparency',
        'type'   => 'slider',
        'title'  => __('Dark mode Background Transparency','csf'),
        'desc'   => __('Sliding slider, the recommended sliding value range is 0.6-0.8.In order to ensure the best appearance, it is better to keep the display of foreground background image','csf'),
        'step'   => '0.01',
        'min'   => '0.2',
        'max'   => '1',
        'default' => '1'
      ),

      array(
        'type'    => 'subheading',
        'content' => __('Other','csf'),
      ),

      array(
        'id'    => 'theme_commemorate_mode',
        'type'  => 'switcher',
        'title' => __('Commemorate Mode','csf'),
        'label'   => __('After opening, black and white filters will be added to the global theme','csf'),
        'default' => false
      ),

      array(
        'id'     => 'load_out_svg',
        'type'   => 'text',
        'title'  => __('Load Control Unit Space Occupying SVG','csf'),
        'desc'   => __('Fill in the address, which is the SVG displayed when loading the control unit','csf'),
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/load_svg/outload.svg'
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'global', 
    'title'  => __('Fonts Options','csf'),
    'icon'      => 'fa fa-font',
    'fields' => array(

      array(
        'type'    => 'subheading',
        'content' => __('Globally','csf'),
      ),

      array(
        'id'     => 'global_font_weight',
        'type'   => 'slider',
        'title'  => __('Non Emphasis Text Word Weight','csf'),
        'desc'   => __('Sliding slider, recommended value range is 300-500','csf'),
        'step'   => '10',
        'min'   => '100',
        'max'   => '700',
        'default' => '300'
      ),

      array(
        'id'     => 'global_font_size',
        'type'   => 'slider',
        'title'  => __('Text Font Size','csf'),
        'desc'   => __('Sliding slider, recommended value range is 15-18','csf'),
        'step'   => '1',
        'unit'    => 'px',
        'min'   => '10',
        'max'   => '20',
        'default' => '15'
      ),

      array(
        'type'    => 'subheading',
        'content' => __('External Font','csf'),
      ),

      array(
        'id'    => 'reference_exter_font',
        'type'  => 'switcher',
        'title' => __('Reference External Font','csf'),
        'label'   => __('After Opening, you can use external fonts as the default font or other component fonts, but it may affect performance','csf'),
        'default' => false
      ),

      array(
        'id'     => 'exter_font_link',
        'type'   => 'text',
        'title'  => __('External Font Link','csf'),
        'dependency' => array( 'reference_exter_font', '==', 'true' ),
      ),

      array(
        'id'     => 'exter_font_name',
        'type'   => 'text',
        'title'  => __('External Font Name','csf'),
        'dependency' => array( 'reference_exter_font', '==', 'true' ),
      ),

      array(
        'id'     => 'google_fonts_api',
        'type'   => 'text',
        'title'  => __('Google Fonts Api Link','csf'),
        'default' => 'fonts.maho.cc'
      ),

      array(
        'id'     => 'google_fonts_add',
        'type'   => 'text',
        'title'  => __('Google Fonts Name','csf'),
        'desc'   => __('Please make sure that the added font can be referenced in Google font library and fill in the font name. The added font must be preceded by "|". If multiple fonts are referenced, use "|" as the separator. If the font name has spaces, use a plus sign instead. For example: | zcool + xiaowei| Ma + Shan + Zheng','csf'),
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'global', 
    'title'  => __('Navigation Menu Options','csf'),
    'icon'      => 'fa fa-map-signs',
    'fields' => array(

      array(
        'id'         => 'nav_menu_style',
        'type'       => 'image_select',
        'title'      => __('Navigation Menu Style','csf'),
        'options'    => array(
          'sakurairo' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/nav_menu_style_iro.png',
          'sakura' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/nav_menu_style_sakura.png',
        ),
        'default'    => 'sakurairo'
      ),

      array(
        'id'    => 'nav_menu_radius',
        'type'  => 'slider',
        'title' => __('Navigation Menu Radius','csf'),
        'dependency' => array( 'nav_menu_style', '==', 'sakurairo' ),
        'desc'   => __('Sliding slider, the recommended value is 15','csf'),
        'unit'    => 'px',
        'max'   => '50',
        'default' => '15'
      ),

      array(
        'id'     => 'nav_menu_shrink_animation',
        'type'   => 'slider',
        'title'  => __('Navigation Menu Shrinkage Ratio','csf'),
        'dependency' => array( 'nav_menu_style', '==', 'sakurairo' ),
        'desc'   => __('Sliding slider to set the appropriate ratio according to the content length of the navigation menu. When the ratio is set to 95, shrinkage will be turned off. Shrinkage is turned off by default','csf'),
        'step'   => '0.5',
        'unit'    => '%',
        'max'   => '95',
        'min'   => '30',
        'default' => '95'
      ),

      array(
        'id'         => 'nav_menu_display',
        'type'       => 'radio',
        'title'      => __('Navigation Menu Content Display','csf'),
        'desc'    => __('You can choose to expand or shrink the navigation menu','csf'),
        'options'    => array(
          'unfold' => __('Unfold','csf'),
          'fold' => __('Fold','csf'),
        ),
        'default'    => 'unfold'
      ),

      array(
        'id'    => 'nav_menu_animation',
        'type'  => 'switcher',
        'title' => __('Navigation Menu Animation','csf'),
        'label'   => __('It is on by default. If it is off, the navigation content will be displayed directly','csf'),
        'default' => true
      ),

      array(
        'id'     => 'nav_menu_animation_time',
        'type'   => 'slider',
        'title'  => __('Navigation Menu Animation Time','csf'),
        'dependency' => array( 'nav_menu_animation', '==', 'true' ),
        'desc'   => __('Sliding slider,the recommended value range is 1-2','csf'),
        'step'   => '0.01',
        'unit'    => 's',
        'max'   => '5',
        'default' => '2'
      ),

      array(
        'id'         => 'nav_menu_icon_size',
        'type'       => 'radio',
        'title'      => __('Navigation Menu Icon Size','csf'),
        'options'    => array(
          'standard' => __('Standard','csf'),
          'large' => __('Large','csf'),
        ),
        'default'    => 'standard'
      ),

      array(
        'id'    => 'nav_menu_search',
        'type'  => 'switcher',
        'title' => __('Navigation Menu Search','csf'),
        'label'   => __('It is on by default. Click to enter the search area','csf'),
        'default' => true
      ),
      array(
        'id'    => 'nav_menu_blur',
        'type'  => 'slider',
        'title' => __('Navigation Menu Blur','csf'),
        'desc'   => __('Sliding slider, the recommended value is 5px, and close when it is 0px','csf'),
        'unit'    => 'px',
        'max'   => '20',
        'default' => '0'
      ),

      array(
        'id'    => 'search_area_background',
        'type'  => 'upload',
        'title' => __('Navigation Menu Search Area Background Image','csf'),
        'desc'   => __('Set the background image of your search area. Leave this option blank to display a white background','csf'),
        'library'      => 'image',
        'default'     => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/basic/iloli.gif'
      ),

      array(
        'id'    => 'nav_menu_user_avatar',
        'type'  => 'switcher',
        'title' => __('Navigation Menu User Avatar','csf'),
        'label'   => __('Open by default, click to enter the login interface','csf'),
        'default' => true
      ),

      array(
        'id'     => 'unlisted_avatar',
        'type'  => 'upload',
        'title' => __('Navigation Menu Unlisted User Avatar','csf'),
        'dependency' => array( 'nav_menu_user_avatar', '==', 'true' ),
        'desc'   => __('The best ratio is 1:1','csf'),
        'library'      => 'image',
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/basic/topavatar.png'
      ),

      array(
        'id'    => 'nav_menu_secondary_arrow',
        'type'  => 'switcher',
        'title' => __('Navigation Menu Secondary Menu Prompt Arrow','csf'),
        'label'   => __('After opening, the menu prompt arrow will appear in the secondary menu of the navigation menu','csf'),
        'dependency' => array( 'nav_menu_style', '==', 'sakura' ),
        'default' => false
      ),

      array(
        'id'    => 'nav_menu_secondary_radius',
        'type'  => 'slider',
        'title' => __('Navigation Menu Secondary Menu Radius','csf'),
        'dependency' => array( 'nav_menu_style', '==', 'sakurairo' ),
        'desc'   => __('Sliding slider, the recommended value is 15','csf'),
        'unit'    => 'px',
        'max'   => '30',
        'default' => '15'
      ),

      array(
        'id'     => 'logo_text',
        'type'   => 'text',
        'title'  => __('Navigation Menu Logo Text','csf'),
        'desc'   => __('Fill in the text. If the Mashiro logo is turned on, this option is invalid','csf'),
        'dependency' => array( 'mashiro_logo_option', '==', 'false' ),
      ),

      array(
        'id'    => 'mashiro_logo_option',
        'type'  => 'switcher',
        'title' => __('Navigation Menu Mashiro Logo','csf'),
        'label'   => __('After opening, the Mashiro Logo will appear and replace the navigation menu logo position','csf'),
        'default' => false
      ),

      array(
        'id'     => 'mashiro_logo',
        'type'   => 'fieldset',
        'title'  => __('Navigation Menu Mashiro Logo Option','csf'),
        'dependency' => array( 'mashiro_logo_option', '==', 'true' ),
        'fields' => array(
          array(
            'id'    => 'text_a',
            'type'  => 'text',
            'title' => __('Text A','csf'),
          ),
          array(
            'id'    => 'text_b',
            'type'  => 'text',
            'title' => __('Text B','csf'),
          ),
          array(
            'id'    => 'text_c',
            'type'  => 'text',
            'title' => __('Text C','csf'),
          ),
          array(
            'id'    => 'text_secondary',
            'type'  => 'text',
            'title' => __('Secondary Text','csf'),
          ),
          array(
            'id'    => 'font_link',
            'type'  => 'text',
            'title' => __('Font Link','csf'),
          ),
          array(
            'id'    => 'font_name',
            'type'  => 'text',
            'title' => __('Font Name','csf'),
          ),
        ),
        'default'        => array(
          'text_a'     => '',
          'text_b'     => '',
          'text_c'     => '',
          'text_secondary' => '',
          'font_link'     => 'https://fonts.maho.cc/css?family=ZCOOL+QingKe+HuangYou&display=swap',
          'font_name'    => 'ZCOOL QingKe HuangYou',
        ),
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'global', 
    'title' => __('Style Menu and Foreground Background Related Options','csf'),
    'icon' => 'fa fa-th-large',
    'fields' => array(

      array(
        'type' => 'subheading',
        'content' => __('Style Menu','csf'),
      ),

      array(
        'id' => 'style_menu_display',
        'type' => 'image_select',
        'title' => __('Style Menu Display','csf'),
        'desc' => __('You can choose to display the style menu in full or simply, the full display will show the font toggle function and text hints','csf'),
        'options' => array(
          'full' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/style_menu_full.png',
          'mini' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/style_menu_mini.png',
        ),
        'default' => 'full'
      ),

      array(
        'id' => 'style_menu_radius',
        'type' => 'slider',
        'title' => __('Style Menu Button Rounded Corners','csf'),
        'desc' => __('Sliding slider, the recommended value is 10','csf'),
        'unit' => 'px',
        'max' => '50',
        'default' => '10'
      ),

      array(
        'id' => 'style_menu_background_color',
        'type' => 'color',
        'title' => __('Style Menu Background Color','csf'),
        'desc' => __('Custom color, lighter colors are recommended','csf'),
        'default' => 'rgba(255,255,255,0.8)'
      ),   

      array(
        'id' => 'style_menu_selection_color',
        'type' => 'color',
        'title' => __('Style Menu Options Background Color','csf'),
        'desc' => __('Custom color, it is recommended to use a color that is the same color as the theme color and is a lighter color','csf'),
        'default' => '#e8e8e8'
      ),

      array(
        'id' => 'style_menu_selection_radius',
        'type' => 'slider',
        'title' => __('Style Menu Options Interface Rounded Corners','csf'),
        'desc' => __('Sliding slider, the recommended value is 15','csf'),
        'unit' => 'px',
        'max' => '30',
        'default' => '15'
      ),

      array(
        'type' => 'subheading',
        'content' => __('Foreground Background','csf'),
      ),

      array(
        'id' => 'reception_background_size',
        'type' => 'select',
        'options' => array(
          'cover' => __('Cover','csf'),
          'contain' => __('Contain','csf'),
          'auto' => __('Auto','csf'),
        ),
        'title' => __('Foreground Background Scaling Method','csf'), 
        'desc' => __('You can choose two ways to scale the foreground background, the default is auto-scaling','csf'),
        'default' => 'auto'
      ),

      array(
        'id' => 'reception_background',
        'type' => 'tabbed',
        'title' => __('Foreground Background Options','csf'),
        'tabs' => array(
          array(
            'title' => __('Default','csf'),
            'icon' => 'fa fa-television',
            'fields' => array(
              array(
                'id' => 'img1',
                'type' => 'upload',
                'title' => __('Image','csf'),
              ),
            )
          ),
          array(
            'title' => __('Heart Shaped','csf'),
            'icon' => 'fa fa-heart-o',
            'fields' => array(
              array(
                'id' => 'heart_shaped',
                'type' => 'switcher',
                'title' => __('Switch','csf'),
              ),
              array(
                'id' => 'img2',
                'type' => 'upload',
                'title' => __('Image','csf'),
              ),
            )
          ),
          array(
            'title' => __('Star Shaped','csf'),
            'icon' => 'fa fa-star-o',
            'fields' => array(
              array(
                'id' => 'star_shaped',
                'type' => 'switcher',
                'title' => __('Switch','csf'),
              ),
              array(
                'id' => 'img3',
                'type' => 'upload',
                'title' => __('Image','csf'),
              ),
            )
          ),
          array(
            'title' => __('Square Shaped','csf'),
            'icon' => 'fa fa-delicious',
            'fields' => array(
              array(
                'id' => 'square_shaped',
                'type' => 'switcher',
                'title' => __('Switch','csf'),
              ),
              array(
                'id' => 'img4',
                'type' => 'upload',
                'title' => __('Image','csf'),
              ),
            )
          ),
          array(
            'title' => __('Lemon Shaped','csf'),
            'icon' => 'fa fa-lemon-o',
            'fields' => array(
              array(
                'id' => 'lemon_shaped',
                'type' => 'switcher',
                'title' => __('Switch','csf'),
              ),
              array(
                'id' => 'img5',
                'type' => 'upload',
                'title' => __('Image','csf'),
              ),
            )
          ),
        ),
        'default'       => array(
          'heart_shaped'  => true,
          'star_shaped'  => true,
          'square_shaped'  => true,
          'lemon_shaped'  => true,
          'img2'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/bg1.png',
          'img3'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/bg2.png',
          'img4' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/bg3.png',
          'img5' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/background/bg4.png',
        )
      ),

      array(
        'id' => 'reception_background_transparency',
        'type' => 'slider',
        'title' => __('Background Transparency in Foreground','csf'),
        'desc' => __('Sliding slider, the recommended sliding value range is 0.6-0.8','csf'),
        'step' => '0.01',
        'min' => '0.2',
        'max' => '1',
        'default' => '0.8'
      ),

      array(
        'type' => 'subheading',
        'content' => __('Fonts Area','csf'),
      ),

      array(
        'id' => 'global_default_font',
        'type' => 'text',
        'title' => __('Global Default Font/Style Menu Font A','csf'),
        'desc' => __('Fill in the font name. For example: Ma Shan Zheng','csf'),
      ),

      array(
        'id' => 'global_font_2',
        'type' => 'text',
        'title' => __('Style Menu Font B','csf'),
        'dependency' => array( 'style_menu_display', '==', 'full' ),
        'desc' => __('Fill in the font name. For example: Ma Shan Zheng','csf'),
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'global', 
    'title' => __('Footer Options','csf'),
    'icon' => 'fa fa-caret-square-o-down',
    'fields' => array(

      array(
        'id' => 'aplayer_server',
        'type' => 'select',
        'title' => __('Footer Online Music Player','csf'),
        'desc' => __('A button will appear at the bottom left corner of the footer after opening, click it and the footer online player will be displayed','csf'),
        'options' => array(
          'off' => __('Off','csf'),
          'netease' => __('Netease Cloud Music','csf'),
          'kugou' => __('Kugou Music(may not be available)','csf'),
          'baidu' => __('Baidu Music(overseas servers are not available)','csf'),
          'tencent' => __('QQ Music(may not be available)','csf'),
        ),
        'default' => 'off'
      ),

      array(
        'id' => 'aplayer_playlistid',
        'type' => 'text',
        'title' => __('Footer Online Music Player Songlist ID','csf'),
        'dependency' => array( 'aplayer_server', '! =', 'off' ),
        'desc' => __('Fill in the song ID, e.g. https://music.163.com/#/playlist?id=5380675133 SongID:5380675133','csf'),
        'default' => '5380675133'
      ),

      array(
        'id' => 'aplayer_order',
        'type' => 'select',
        'title' => __('Footer Online Music Player Mode','csf'),
        'dependency' => array( 'aplayer_server', '! =', 'off' ),
        'desc' => __('Select music player mode','csf'),
        'options' => array(
          'list' => __('List','csf'),
          'random' => __('Random','csf'),
        ),
        'default' => 'list'
      ),

      array(
        'id' => 'aplayer_preload',
        'type' => 'select',
        'title' => __('Footer Online Music Player Preload','csf'),
        'dependency' => array( 'aplayer_server', '! =', 'off' ),
        'desc' => __('Whether to preload songs','csf'),
        'options' => array(
          'none' => __('Turn Off','csf'),
          'metadata' => __('Preload Metadata','csf'),
          'auto' => __('Auto','csf'),
        ),
        'default' => 'auto'
      ),

      array(
        'id' => 'aplayer_volume',
        'type' => 'slider',
        'title' => __('Default Volume for Footer Online Music Player','csf'),
        'dependency' => array( 'aplayer_server', '! =', 'off' ),
        'desc' => __('Sliding slider, the recommended sliding value range is 0.4-0.6','csf'),
        'step' => '0.01',
        'max' => '1',
        'default' => '0.5'
      ),

      array(
        'id' => 'aplayer_cookie',
        'type' => 'textarea',
        'title' => __('Footer Online Music Player Netease Cloud Music Cookies','csf'),
        'dependency' => array( 'aplayer_server', '==', 'netease' ),
        'desc' => __('If you want to play VIP musics on Netease Cloud Music Platform,please fill in your account cookies in this option.','csf'),
      ),

      array(
        'id' => 'sakura_widget',
        'type' => 'switcher',
        'title' => __('Footer Widget Area','csf'),
        'label' => __('After turning it on a button will appear in the bottom left corner of the footer, when you click the button the footer widget area will be displayed, if you have the footer online player turned on it will be displayed together','csf'),
        'default' => false
      ),

      array(
        'id' => 'sakura_widget_background',
        'type' => 'upload',
        'title' => __('Footer Widget Area Background','csf'),
        'dependency' => array( 'sakura_widget', '==', 'true' ),
        'desc' => __('The best picture size is 400px × 460px','csf'),
        'library' => 'image',
      ),

      array(
        'id' => 'footer_sakura_icon',
        'type' => 'switcher',
        'title' => __('Footer Dynamic Sakura Icon','csf'),
        'label' => __('Dynamic Sakura icon will appear at the end of the page after turning on','csf'),
        'default' => false
      ),

      array(
        'id' => 'footer_load_occupancy',
        'type' => 'switcher',
        'title' => __('Footer Load Occupancy Query','csf'),
        'label' => __('Load occupancy information will appear at the end of the page after turning it on','csf'),
        'default' => false
      ),

      array(
        'id' => 'footer_info',
        'type' => 'textarea',
        'title' => __('Footer Info','csf'),
        'desc' => __('Footer description text, support HTML code','csf'),
        'default' => 'Copyright &copy; by FUUKEI All Rights Reserved.'
      ),

      array(
        'type' => 'subheading',
        'content' => __('Brief Remark','csf'),
      ),

      array(
        'id' => 'footer_yiyan',
        'type' => 'switcher',
        'title' => __('Footer Brief Remark','csf'),
        'label' => __('A Brief Remark will appear at the end of the page after turning it on','csf'),
        'default' => false
      ),

      array(
        'type' => 'content',
        'dependency' => array( 'footer_yiyan', '==', 'true' ),
        'content' => __('<h4>Brief Remark API Setup Instructions</h4>'
        .' <p>Fill in the example:<code> ["https://api.maho.cc/yiyan/", "https://v1.hitokoto.cn/"]</code>, where the first API will be used first and the next one will be the backup. </p>'
        .' <p><strong>Official API:</strong> See the <a href="https://developer.hitokoto.cn/sentence/"> documentation</a> for how to use it, and the parameter "return code" should not be set to anything other than JSON. <a href="https://v1.hitokoto.cn/">https://v1.hitokoto.cn/</a></p>'
        .' <p><strong>Maho API:</strong> An anti-generation mirror of the official API. <a href="https://api.maho.cc/yiyan/">https://api.maho.cc/yiyan/</a></p>','csf'),
      ),

      array(
        'id' => 'yiyan_api',
        'type' => 'textarea',
        'title' => __('Brief Remark API address','csf'),
        'dependency' => array( 'footer_yiyan', '==', 'true' ),
        'desc' => __('fill address in JavaScript array format','csf'),
        'default' => '["https://api.maho.cc/yiyan/", "https://v1.hitokoto.cn/"]'
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'global', 
    'title' => __('Cursor Options','csf'),
    'icon' => 'fa fa-i-cursor',
    'fields' => array(

      array(
        'id' => 'cursor_nor',
        'type' => 'text',
        'title' => __('Standard Cursor Style','csf'),
        'desc' => __('Apply to global, fill in Cur mouse file link','csf'),
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/cursor/normal.cur'
      ),

      array(
        'id' => 'cursor_no',
        'type' => 'text',
        'title' => __('Selected Cursor Style','csf'),
        'desc' => __('Apply to multiple styles, fill in Cur file link','csf'),
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/cursor/No_Disponible.cur'
      ),

      array(
        'id' => 'cursor_ayu',
        'type' => 'text',
        'title' => __('Selected Control Unit Cursor Style','csf'),
        'desc' => __('Apply to selected control unit, fill in Cur file link','csf'),
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/cursor/ayuda.cur'
      ),

      array(
        'id' => 'cursor_text',
        'type' => 'text',
        'title' => __('Selected Text Cursor Style','csf'),
        'desc' => __('Apply to selected text, fill in Cur file link','csf'),
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/cursor/texto.cur'
      ),

      array(
        'id' => 'cursor_work',
        'type' => 'text',
        'title' => __('Work Status Cursor Style','csf'),
        'desc' => __('Apply to load control unit, fill in Cur file link','csf'),
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/cursor/work.cur'
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'global', 
    'title' => __('Additional Options','csf'),
    'icon' => 'fa fa-gift',
    'fields' => array(

      array(
        'type' => 'subheading',
        'content' => __('Effects&Animations','csf'),
      ),
      
      array(
        'id' => 'preload_animation',
        'type' => 'switcher',
        'title' => __('Preload Animation','csf'),
        'label' => __('Preload animation before new pages load after turning on, this option is needed to ensure your page resources load properly.' ,'csf'),
        'default' => false
      ),

      array(
        'id' => 'preload_animation_color1',
        'type' => 'color',
        'title' => __('Preload Animation Color A','csf'),
        'dependency' => array( 'preload_animation', '==', 'true' ),
        'desc' => __('Custom colors','csf'),
        'default' => '#ffea99'
      ),   

      array(
        'id' => 'preload_animation_color2',
        'type' => 'color',
        'title' => __('Preload Animation Color B','csf'),
        'dependency' => array( 'preload_animation', '==', 'true' ),
        'desc' => __('Custom colors','csf'),
        'default' => '#ffcc00'
      ),   

      array(
        'id' => 'preload_blur',
        'title' => __('Preload Animation Blur Transition Effect','csf'),
        'dependency' => array( 'preload_animation', '==', 'true' ),
        'desc' => __('Blur transition duration in milliseconds ms, off when 0.' ,'csf'),
        'default' => '0',
        'type' => 'slider',
        'step' => '10',
        'max' => '10000',
      ), 

      array(
        'id' => 'falling_effects',
        'type' => 'select',
        'title' => __('Falling Effects','csf'),
        'options' => array(
          'off' => __('Off','csf'),
          'sakura-native' => __('Sakura Native Quantity','csf'),
          'sakura-quarter' => __('Sakura Quarter Quantity','csf'),
          'sakura-half' => __('Sakura Half Quantity','csf'),
          'sakura-less' => __('Sakura Less Quantity','csf'),
          'yuki-native' => __('Yuki Native Quantity','csf'),
          'yuki-half' => __('Yuki Half Quantity','csf'),
        ),
        'default' => 'off'
      ),

      array(
        'id' => 'live2d_options',
        'type' => 'switcher',
        'title' => __('Live2D Model','csf'),
        'label' => __('Live2D Model will be loaded in the bottom left corner of the page after opening','csf'),
        'default' => false
      ),

      array(
        'id' => 'live2d_custom_user',
        'type' => 'text',
        'title' => __('Live2D Model Custom Github Project Username','csf'),
        'dependency' => array( 'live2d_options', '==', 'true' ),
        'desc' => __('If you want to customize this option, you need to go to Github Fork this project and make changes to this project first, fill in the Github project username here','csf'),
        'default' => 'mirai-mamori'
      ),

      array(
        'id' => 'live2d_custom_user_ver',
        'type' => 'text',
        'title' => __('Live2D Model Custom Github Project Version','csf'),
        'dependency' => array( 'live2d_options', '==', 'true' ),
        'desc' => __('If you want to customize this option, you need to first go to Github Fork this project and make changes to this project, fill in the version of the Github project here','csf'),
        'default' => 'latest'
      ),

      array(
        'id' => 'note_effects',
        'type' => 'switcher',
        'title' => __('Note Touch Effects','csf'),
        'label' => __('After turn on, there will be a note sound alert when the back to top button and white cat style logo touch','csf'),
        'default' => false
      ),

      array(
        'type' => 'subheading',
        'content' => __('Feature','csf'),
      ),

      array(
        'id' => 'poi_pjax',
        'type' => 'switcher',
        'title' => __('PJAX Partial Refresh','csf'),
        'label' => __('Enabled by default, clicking on a new page will not require reloading','csf'),
        'default' => true
      ),

      array(
        'id' => 'nprogress_on',
        'type' => 'switcher',
        'title' => __('NProgress Loading Progress Bar','csf'),
        'label' => __('Enabled by default, loading page will have progress bar alert','csf'),
        'default' => true
      ),

      array(
        'id' => 'smoothscroll_option',
        'type' => 'switcher',
        'title' => __('Global SmoothScroll','csf'),
        'label' => __('Enabled by default, page scrolling will be smoother','csf'),
        'default' => true
      ),

      array(
        'id' => 'captcha_switch',
        'type' => 'switcher',
        'title' => __('Login Captcha','csf'),
        'label' => __('After turning on the captcha will appear in the backend login page and login template, this feature conflicts with other captcha related plugins, please do not turn on both','csf'),
        'default' => false
      ),

      array(
        'id' => 'pagenav_style',
        'type' => 'radio',
        'title' => __('Pagination Mode','csf'),
        'options' => array(
          'ajax' => __('Ajax Load','csf'),
          'np' => __('Page Up/Down','csf'),
        ),
        'default' => 'ajax'
      ),

      array(
        'id' => 'page_auto_load',
        'type' => 'select',
        'title' => __('Next Page Auto Load','csf'),
        'dependency' => array( 'pagenav_style', '==', 'ajax' ),
        'options' => array(
          '233' => __('do not autoload','csf'),
          '0' => __('0 Sec','csf'),
          '1' => __('1 Sec','csf'),
          '2' => __('2 Sec','csf'),
          '3' => __('3 Sec','csf'),
          '4' => __('4 Sec','csf'),
          '5' => __('5 Sec','csf'),
          '6' => __('6 Sec','csf'),
          '7' => __('7 Sec','csf'),
          '8' => __('8 Sec','csf'),
          '9' => __('9 Sec','csf'),
          '10' => __('10 Sec','csf'),
        ),
        'default' => '233'
      ),

      array(
        'id' => 'load_nextpage_svg',
        'type' => 'text',
        'title' => __('Load Next Page Placeholder SVG','csf'),
        'desc' => __('Fill address, this is the SVG that will be displayed as a placeholder when the next page is loaded','csf'),
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/load_svg/ball.svg'
      ),
    )
  ) );

  CSF::createSection( $prefix, array(
    'id' => 'homepage', 
    'title' => __('homepage settings','csf'),
    'icon' => 'fa fa-home',
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'homepage', 
    'title' => __('cover-set','csf'),
    'icon' => 'fa fa-laptop',
    'fields' => array(

      array(
        'id' => 'cover_switch',
        'type' => 'switcher',
        'title' => __('cover','csf'),
        'label' => __('On by default, if off, all options below will be disabled','csf'),
        'default' => true
      ),

      array(
        'id' => 'cover_full_screen',
        'type' => 'switcher',
        'title' => __('cover_full_screen','csf'),
        'label' => __('Default on','csf'),
        'default' => true
      ),

      array(
        'id' => 'cover_half_screen_curve',
        'type' => 'switcher',
        'dependency' => array( 'cover_full_screen', '==', 'false' ),
        'title' => __('curved mask below cover','csf'),
        'label' => __('Arc masking will appear below the cover when turned on','csf'),
        'default' => false
      ),

      array(
        'id' => 'cover_radius',
        'type' => 'slider',
        'title' => __('cover_rounded','csf'),
        'desc' => __('slider, recommended value range 15-20','csf'),
        'unit' => 'px',
        'max' => '60',
        'default' => '15'
      ),

      array(
        'id' => 'cover_animation',
        'type' => 'switcher',
        'title' => __('cover_animation','csf'),
        'label' => __('On by default, if off, the cover will be displayed directly','csf'),
        'default' => true
      ),

      array(
        'id' => 'cover_animation_time',
        'type' => 'slider',
        'title' => __('cover_animation_time','csf'),
        'dependency' => array( 'cover_animation', '==', 'true' ),
        'desc' => __('slider, recommended values range 1-2','csf'),
        'step' => '0.01',
        'unit' => 's',
        'max' => '5',
        'default' => '2'
      ),

      array(
        'id' => 'infor_bar',
        'type' => 'switcher',
        'title' => __('Cover info bar','csf'),
        'label' => __('Enabled by default, show avatar, white cat effects text, signature bar, social area','csf'),
        'default' => true
      ),

      array(
        'id' => 'infor_bar_style',
        'type' => 'image_select',
        'title' => __('cover_info_bar_style','csf'),
        'options' => array(
          'v1' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/infor_bar_style_v1.png',
          'v2' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/infor_bar_style_v2.png',
        ),
        'default' => 'v1'
      ),

      array(
        'id' => 'infor_bar_bgcolor',
        'type' => 'color',
        'title' => __('cover_info_bar_background_color','csf'),
        'desc' => __('Custom color, lighter colors are recommended','csf'),
        'default' => 'rgba(255,255,255,0.8)'
      ),     

      array(
        'id' => 'avatar_radius',
        'type' => 'slider',
        'title' => __('Cover info bar avatar rounded corners','csf'),
        'desc' => __('slider with recommended value of 100','csf'),
        'unit' => 'px',
        'default' => '100'
      ),

      array(
        'id' => 'signature_radius',
        'type' => 'slider',
        'title' => __('Cover signature bar rounded','csf'),
        'desc' => __('slider, recommended value range 10-20','csf'),
        'unit' => 'px',
        'max' => '50',
        'default' => '15'
      ),

      array(
        'id' => 'signature_text',
        'type' => 'text',
        'title' => __('cover signature field text','csf'),
        'desc' => __('A self-descriptive quote','csf'),
        'default' => '本当の声音を響かせてよ'
      ),

      array(
        'id' => 'signature_font',
        'type' => 'text',
        'title' => __('cover signature field text font','csf'),
        'desc' => __('Fill in the font name. For example: Ma Shan Zheng','csf'),
        'default' => 'Noto Serif SC'
      ),

      array(
        'id' => 'signature_font_size',
        'type' => 'slider',
        'title' => __('Cover signature field text font size','csf'),
        'desc' => __('slider, recommended value range is 15-18','csf'),
        'unit' => 'px',
        'min' => '5',
        'max' => '20',
        'default' => '16'
      ),

      array(
        'id' => 'signature_typing',
        'type' => 'switcher',
        'title' => __('Cover signature bar typing effects','csf'),
        'label' => __('When turned on, the signature bar text will have an additional paragraph of text and will be rendered with typing effects','csf'),
        'default' => false
      ),

      array(
        'id' => 'signature_typing_marks',
        'type' => 'switcher',
        'title' => __('Cover signature field typing effects double quotes','csf'),
        'dependency' => array( 'signature_typing', '==', 'true' ),
        'label' => __('Typing effects will be appended with double quotes when turned on','csf'),
        'default' => false
      ),

      array(
        'id' => 'signature_typing_text',
        'type' => 'text',
        'title' => __('cover_signature_typing_text','csf'),
        'dependency' => array( 'signature_typing', '==', 'true' ),
        'desc' => __('Fill in the text part of the typing effect, must use English double quotes outside the text, use English commas to separate the two sentences, support HTML tags','csf'),
        'default' => '"寒蝉黎明之时,便是重生之日"'
      ),

      array(
        'id' => 'random_graphs_options',
        'type' => 'select',
        'title' => __('Cover random graphs options','csf'),
        'options' => array(
          'external_api' => __('External API random image','csf'),
          'webp_optimization' => __('Webp optimized random images','csf'),
          'local' => __('local_random_image','csf'),
        ),
        'default' => 'external_api'
      ),

      array(
        'id' => 'random_graphs_mts',
        'type' => 'switcher',
        'title' => __('Cover random image multi-terminal separation','csf'),
        'label' => __('Enabled by default, desktop and mobile will use separate random image addresses','csf'),
        'default' => true
      ),

      array(
        'id' => 'random_graphs_link',
        'type' => 'text',
        'title' => __('Webp Optimization/External API Desktop Side Random Graphics Address','csf'),
        'desc' => __('fill address','csf'),
        'default' => 'https://api.maho.cc/random-img/pc.php'
      ),

      array(
        'type' => 'submessage',
        'style' => 'info',
        'content' => sprintf(__('If you choose to use Webp to optimize random images, click <a href="%s">here</a> to update the Manifest path', 'sakurairo', 'csf'), rest_url('sakura/v1/ database/update')), 
      ),

      array(
        'id' => 'random_graphs_link_mobile',
        'type' => 'text',
        'title' => __('External API mobile random image address','csf'),
        'dependency' => array( 'random_graphs_mts', '==', 'true' ),
        'desc' => __('fill address','csf'),
        'default' => 'https://api.maho.cc/random-img/mobile.php'
      ),

      array(
        'id' => 'cache_cover',
        'type' => 'switcher',
        'title' => __('Cover random background image cache','csf'),
        'label' => __('Enabled by default, this feature will cache a cover image locally, which can improve the loading speed of the first cover after entering the homepage. Note: This feature requires the cover API to be able to accept cross-domain requests.' ,'csf'),
        'default' => true
      ),

      array(
        'id' => 'site_bg_as_cover',
        'type' => 'switcher',
        'title' => __('Cover and foreground background integration','csf'),
        'label' => __('When enabled, the background of the cover will be set to transparent, while the foreground background will use the cover‘s random image API','csf'),
        'default' => false
      ),

      array(
        'id' => 'random_graphs_filter',
        'type' => 'select',
        'title' => __('cover_random_graphs_filter','csf'),
        'options' => array(
          'filter-nothing' => __('no-filter','csf'),
          'filter-undertint' => __('light-filter','csf'),
          'filter-dim' => __('dull filter','csf'),
          'filter-grid' => __('grid-filter','csf'),
          'filter-dot' => __('dot-filter','csf'),
        ),
        'default' => 'filter-nothing'
      ),

      array(
        'id' => 'wave_effects',
        'type' => 'switcher',
        'title' => __('cover wave effects','csf'),
        'label' => __('Wave effect will appear at the bottom of the cover of the home page after opening, will be forced off in dark mode','csf'),
        'default' => false
      ),

      array(
        'id' => 'drop_down_arrow',
        'type' => 'switcher',
        'title' => __('cover_down_arrow','csf'),
        'label' => __('Enabled by default, show dropdown arrow at bottom of home cover','csf'),
        'default' => true
      ),

      array(
        'id' => 'drop_down_arrow_mobile',
        'type' => 'switcher',
        'title' => __('Cover dropdown arrow mobile display','csf'),
        'dependency' => array( 'drop_down_arrow', '==', 'true' ),
        'label' => __('Drop down arrow will appear at the bottom of the mobile home cover after turning it on','csf'),
        'default' => false
      ),

      array(
        'id' => 'drop_down_arrow_color',
        'type' => 'color',
        'title' => __('Cover dropdown arrow color','csf'),
        'dependency' => array( 'drop_down_arrow', '==', 'true' ),
        'desc' => __('Custom color, lighter colors are recommended','csf'),
        'default' => 'rgba(255,255,255,0.8)'
      ),  

      array(
        'id' => 'drop_down_arrow_dark_color',
        'type' => 'color',
        'title' => __('Cover dropdown arrow dark mode color','csf'),
        'dependency' => array( 'drop_down_arrow', '==', 'true' ),
        'desc' => __('Custom colors, dark colors are recommended','csf'),
        'default' => 'rgba(51,51,51,0.8)'
      ),  

      array(
        'id' => 'cover_video',
        'type' => 'switcher',
        'title' => __('cover_video','csf'),
        'label' => __('Replace cover random image as the main display content when turned on','csf'),
        'default' => false
      ),

      array(
        'id' => 'cover_video_loop',
        'type' => 'switcher',
        'title' => __('cover_video_loop','csf'),
        'dependency' => array( 'cover_video', '==', 'true' ),
        'label' => __('Video will loop automatically when enabled, need to enable Pjax','csf'),
        'default' => false
      ),

      array(
        'id' => 'cover_video_link',
        'type' => 'text',
        'title' => __('cover video address','csf'),
        'dependency' => array( 'cover_video', '==', 'true' ),
        'desc' => __('Fill in the address which splices the video name below, no slash at the end of the address','csf'),
      ),

      array(
        'id' => 'cover_video_title',
        'type' => 'text',
        'title' => __('cover video name','csf'),
        'dependency' => array( 'cover_video', '==', 'true' ),
        'desc' => __('For example: abc.mp4, just fill in the video file name abc, multiple separated by English commas like abc,efg, default random play','csf'),
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'homepage', 
    'title' => __('cover social area settings','csf'),
    'icon' => 'fa fa-share-square-o',
    'fields' => array(

      array(
        'type' => 'subheading',
        'content' => __('options','csf'),
      ),

      array(
        'id' => 'social_area',
        'type' => 'switcher',
        'title' => __('cover social area','csf'),
        'label' => __('Enabled by default, show cover random image toggle button and social network icons','csf'),
        'default' => true
      ),

      array(
        'id' => 'social_display_icon',
        'type' => 'image_select',
        'title' => __('social_icon','csf'),
        'desc' => __('Select your favorite icon pack. Icon pack references are detailed in About theme','csf'),
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
        'id' => 'social_area_radius',
        'type' => 'slider',
        'title' => __('Cover social area rounded corners','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('slider, recommended values range 10-20','csf'),
        'unit' => 'px',
        'max' => '30',
        'default' => '15'
      ),

      array(
        'id' => 'cover_random_graphs_switch',
        'type' => 'switcher',
        'title' => __('cover_random_graphs_switch_button','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'label' => __('Enabled by default, show cover random image toggle button','csf'),
        'default' => true
      ),

      array(
        'type' => 'subheading',
        'content' => __('social network','csf'),
      ),

      array(
        'id'     => 'wechat',
        'type'  => 'upload',
        'title' => __(' Wechat ','csf'),
        'dependency' => array( 'social_ area', '==', 'true' ),
        'desc' => __('The best ratio is 1:1','csf'),
        'library'      => 'image',
      ),

      array(
        'id'     => 'qq',
        'type'   => 'text',
        'title' => __('QQ','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('Please note the format of filling out the form, e.g. tencent://message/?uin=123456','csf'),
      ),

      array(
        'id'     => 'bili',
        'type'   => 'text',
        'title' => __('Bilibili','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('add URL','csf'),
      ),

      array(
        'id'     => 'wangyiyun',
        'type'   => 'text',
        'title' => __('NetEase Music','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('add URL','csf'),
      ),

      array(
        'id'     => 'sina',
        'type'   => 'text',
        'title' => __('Weibo','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('add URL','csf'),
      ),

      array(
        'id'     => 'github',
        'type'   => 'text',
        'title' => __('Github','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('add URL','csf'),
      ),

      array(
        'id'     => 'telegram',
        'type'   => 'text',
        'title' => __('Telegram','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('add URL','csf'),
      ),

      array(
        'id'     => 'steam',
        'type'   => 'text',
        'title' => __('Steam','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('add URL','csf'),
      ),

      array(
        'id'     => 'zhihu',
        'type'   => 'text',
        'title' => __('ZhiHu','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('add URL','csf'),
      ),

      array(
        'id'     => 'qzone',
        'type'   => 'text',
        'title' => __('QQ space','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('add URL','csf'),
      ),

      array(
        'id'     => 'lofter',
        'type'   => 'text',
        'title' => __('Lofter','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('add URL','csf'),
      ),

      array(
        'id'     => 'youku',
        'type'   => 'text',
        'title' => __('Youku','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('add URL','csf'),
      ),

      array(
        'id'     => 'linkedin',
        'type'   => 'text',
        'title' => __('Linkedin','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('add URL','csf'),
      ),

      array(
        'id'     => 'twitter',
        'type'   => 'text',
        'title' => __('Twitter','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('add URL','csf'),
      ),

      array(
        'id'     => 'facebook',
        'type'   => 'text',
        'title' => __('Facebook','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('add URL','csf'),
      ),

      array(
        'id'     => 'csdn',
        'type'   => 'text',
        'title' => __('CSDN','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('add URL','csf'),
      ),

      array(
        'id'     => 'jianshu',
        'type'   => 'text',
        'title' => __('JianShu','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('add URL','csf'),
      ),

      array(
        'id'     => 'socialdiy1',
        'type'   => 'text',
        'title' => __('Customized social networkingⅠ','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('add URL','csf'),
      ),

      array(
        'id'     => 'socialdiyp1',
        'type'  => 'upload',
        'title' => __('Customized social networking Ⅰ icon','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('The best ratio is 1:1','csf'),
        'library'      => 'image',
      ),

      array(
        'id'     => 'socialdiy2',
        'type'   => 'text',
        'title' => __('Customized social networking Ⅱ','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('add URL','csf'),
      ),

      array(
        'id'     => 'socialdiyp2',
        'type'  => 'upload',
        'title' => __('Customized social networking Ⅱ icon','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('The best ratio is 1:1','csf'),
        'library'      => 'image',
      ),

      array(
        'id' => 'email_name',
        'type' => 'text',
        'title' => __('email_username','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('name@domain.com fo name, front-end only has js runtime environment to get the full address, you can fill in with confidence','csf'),
      ),

      array(
        'id' => 'email_domain',
        'type' => 'text',
        'title' => __('email_username','csf'),
        'dependency' => array( 'social_area', '==', 'true' ),
        'desc' => __('name@domain.com fo domain.com','csf'),
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'homepage', 
    'title' => __('Bulletin Board and Area Title Settings','csf'),
    'icon' => 'fa fa-bullhorn',
    'fields' => array(

      array(
        'type' => 'subheading',
        'content' => __('bulletin-board','csf'),
      ),

      array(
        'id' => 'announce_bar',
        'type' => 'switcher',
        'title' => __('announce_bar','csf'),
        'label' => __('When enabled the bulletin board will be displayed below the front cover','csf'),
        'default' => false
      ),

      array(
        'id' => 'announce_bar_style',
        'type' => 'radio',
        'title' => __('announce_bar_style','csf'),
        'dependency' => array( 'announce_bar', '==', 'true' ),
        'options' => array(
          'picture' => 'picture background',
          'pure' => 'solid background',
        ),
        'default' => 'picture'
      ),

      array(
        'id' => 'announce_bar_icon',
        'type' => 'switcher',
        'title' => __('Announcement bar "broadcast" icon','csf'),
        'dependency' => array( 'announce_bar', '==', 'true' ),
        'label' => __('The "broadcast" icon will be displayed on the left side of the announcement bar','csf'),
        'default' => true
      ),

      array(
        'id' => 'announcement_bg',
        'type' => 'upload',
        'title' => __('announcement_bg_background','csf'),
        'dependency' => array(
          array( 'announce_bar', '==', 'true' ),
          array( 'announce_bar_style', '==', 'picture' ),
        ),
        'desc' => __('best width 820px, best height 67px','csf'),
        'library' => 'image',
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/hyouryu/announcement_bg.jpg'
      ),

      array(
        'id' => 'announce_bar_border_color',
        'type' => 'color',
        'title' => __('announce_bar_border_color','csf'),
        'dependency' => array(
          array( 'announce_bar', '==', 'true' ),
          array( 'announce_bar_style', '==', 'pure' ),
        ),
        'desc' => __('Custom color, it is recommended to use a color that is the same color as the theme color and is a lighter color','csf'),
        'default' => '#E6E6E6'
      ),

      array(
        'id' => 'announce_text',
        'type' => 'text',
        'title' => __('announcement text','csf'),
        'dependency' => array( 'announce_bar', '==', 'true' ),
        'desc' => __('Fill in the announcement text, text beyond 142 bytes will be hidden','csf'),
      ),

      array(
        'id' => 'announce_text_align',
        'type' => 'image_select',
        'title' => __('Announcement text alignment direction','csf'),
        'dependency' => array( 'announce_bar', '==', 'true' ),
        'options'     => array(
          'left'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/announce_text_left.png',
          'right'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/announce_text_right.png',
          'center'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/announce_text_center.png',
        ),
        'default'     => 'left'
      ),

      array(
        'id' => 'announce_text_color',
        'type' => 'color',
        'title' => __('announcement text color','csf'),
        'dependency' => array( 'announce_bar', '==', 'true' ),
        'desc' => __('Custom color, suggest matching appropriate color to background color','csf'),
        'default' => '#999'
      ),    

      array(
        'type' => 'subheading',
        'content' => __('area title','csf'),
      ),

      array(
        'id' => 'exhibition_area_title',
        'type' => 'text',
        'title' => __('display area title','csf'),
        'desc' => __('Default is "display", you can change it to something else, but of course it can-t be used as an ad! Not allowed!!!' ,'csf'),
        'default' => 'show'
      ),

      array(
        'id' => 'post_area_title',
        'type' => 'text',
        'title' => __('post area title','csf'),
        'desc' => __('Default is "article", you can change it to something else, but of course it can-t be used as an advertisement! Not allowed!!!' ,'csf'),
        'default' => 'article'
      ),

      array(
        'id' => 'area_title_font',
        'type' => 'text',
        'title' => __('area title font','csf'),
        'desc' => __('Fill in the font name. For example: Ma Shan Zheng','csf'),
        'default' => 'Noto Serif SC'
      ),

      array(
        'id' => 'area_title_text_align',
        'type' => 'image_select',
        'title' => __('area title alignment direction','csf'),
        'options' => array(
          'left' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/area_title_text_left.png',
          'right' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/area_title_text_right.png',
          'center' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/area_title_text_center.png',
        ),
        'default' => 'left'
      ),

      array(
        'id' => 'area_title_bottom_color',
        'type' => 'color',
        'title' => __('area_title_bottom_color','csf'),
        'desc' => __('Custom color, it is recommended to use a color that is the same color as the theme color and is a lighter color','csf'),
        'default' => '#e8e8e8'
      ),  

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'homepage', 
    'title' => __('display area settings','csf'),
    'icon' => 'fa fa-bookmark',
    'fields' => array(

      array(
        'id' => 'exhibition_area',
        'type' => 'switcher',
        'title' => __('display_area','csf'),
        'label' => __('Enabled by default, display area above article area','csf'),
        'default' => true
      ),

      array(
        'id' => 'exhibition_area_style',
        'type' => 'image_select',
        'title' => __('display_area_style','csf'),
        'options' => array(
          'left_and_right' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/exhibition_area_style_lr.png',
          'bottom_to_top' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/exhibition_area_style_ud.png',
        ),
        'default' => 'left_and_right'
      ),

      array(
        'id' => 'exhibition_area_compat',
        'type' => 'switcher',
        'title' => __('display area compatibility mode','csf'),
        'dependency' => array( 'exhibition_area_style', '==', 'left_and_right' ),
        'label' => __('Enabled by default, this option avoids the problem of misaligned display areas','csf'),
        'default' => true
      ),

      array(
        'id' => 'exhibition_background_color',
        'type' => 'color',
        'title' => __('display area background color','csf'),
        'dependency' => array( 'exhibition_area_style', '==', 'left_and_right' ),
        'desc' => __('Custom colors, lighter colors are recommended','csf'),
        'default' => 'rgba(255,255,255,0.4)'
      ),
      
      array(
        'id' => 'exhibition_radius',
        'type' => 'slider',
        'title' => __('display area rounded corners','csf'),
        'desc' => __('Slider with recommended value of 15','csf'),
        'unit' => 'px',
        'default' => '15'
      ),

      array(
        'id' => 'exhibition',
        'type' => 'tabbed',
        'title' => __('display area settings','csf'),
        'tabs' => array(
          array(
            'title' => __('First display area','csf'),
            'fields' => array(
              array(
                'id' => 'img1',
                'type' => 'upload',
                'title' => __('image','csf'),
                'desc' => __('best width 260px, best height 160px','csf'),
              ),
              array(
                'id' => 'title1',
                'type' => 'text',
                'title' => __('title','csf'),
              ),
              array(
                'id' => 'description1',
                'type' => 'text',
                'title' => __('description','csf'),
              ),
              array(
                'id' => 'link1',
                'type' => 'text',
                'title' => __('add URL','csf'),
              ),
            )
          ),
          array(
            'title' => __('Second display area','csf'),
            'fields' => array(
              array(
                'id' => 'img2',
                'type' => 'upload',
                'title' => __('image','csf'),
                'desc' => __('best width 260px, best height 160px','csf'),
              ),
              array(
                'id' => 'title2',
                'type' => 'text',
                'title' => __('title','csf'),
              ),
              array(
                'id' => 'description2',
                'type' => 'text',
                'title' => __('description','csf'),
              ),
              array(
                'id' => 'link2',
                'type' => 'text',
                'title' => __('add URL','csf'),
              ),
            )
          ),
          array(
            'title' => __('Third display area','csf'),
            'fields' => array(
              array(
                'id' => 'img3',
                'type' => 'upload',
                'title' => __('image','csf'),
                'desc' => __('best width 260px, best height 160px','csf'),
              ),
              array(
                'id'    => 'title3',
                'type'  => 'text',
                'title' => __('title','csf'),
              ),
              array(
                'id'    => 'description3',
                'type'  => 'text',
                'title' => __('description','csf'),
              ),
              array(
                'id'    => 'link3',
                'type'  => 'text',
                'title' => __('add URL','csf'),
              ),
            )
          ),
        ),
        'default'       => array(
          'link1' => '',
          'link2' => '',
          'link3' => '',
          'img1'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/hyouryu/exhibition1.jpg',
          'img2'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/hyouryu/exhibition2.jpg',
          'img3' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/hyouryu/exhibition3.jpg',
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
    'title' => __('article area settings','csf'),
    'icon'      => 'fa fa-book',
    'fields' => array(

      array(
        'id'         => 'post_list_style',
        'type'       => 'image_select',
        'title' => __('article area display style','csf'),
        'options'    => array(
          'imageflow' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/post_list_style_sakura_left.png',
          'akinastyle' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/post_list_style_akina.png',
        ),
        'default'    => 'imageflow'
      ),

      array(
        'id'         => 'post_list_akina_type',
        'type'       => 'image_select',
        'title' => __('Article area decorated with featured image display shapes','csf'),
        'dependency' => array( 'post_list_style', '==', 'akinastyle' ),
        'desc' => __('You can choose between a circular display or a rectangular display of the article area decorated with featured images','csf'),
        'options'    => array(
          'round' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/post_list_style_akina.png',
          'square' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/post_list_style_akina2.png',
        ),
        'default'    => 'round'
      ),

      array(
        'id' => 'post_list_image_align',
        'type' => 'image_select',
        'title' => __('post_area_decoration_featured_image_align_direction','csf'),
        'dependency' => array( 'post_list_style', '==', 'imageflow' ),
        'desc' => __('You can choose different directions to display the post area decorated with featured images','csf'),
        'options' => array(
          'alternate' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/post_list_style_sakura1.png',
          'left' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/post_list_style_sakura2.png',
          'right' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/post_list_style_sakura3.png',
        ),
        'default' => 'alternate'
      ),

      array(
        'id' => 'post_cover_options',
        'type' => 'radio',
        'title' => __('Post area decorated with featured image options','csf'),
        'options' => array(
          'type_1' => 'cover random image',
          'type_2' => 'External API random image',
        ),
        'default' => 'type_1'
      ),

      array(
        'id' => 'post_cover',
        'type' => 'text',
        'title' => __('post_area_decoration_featured_image_external_API_random_image_address','csf'),
        'dependency' => array( 'post_cover_options', '==', 'type_2' ),
        'desc' => __('add URL','csf'),
      ),

      array(
        'id' => 'post_title_font_size',
        'type' => 'slider',
        'title' => __('post area title font size','csf'),
        'desc' => __('Slider, recommended value range 16-20','csf'),
        'dependency' => array( 'post_list_style', '==', 'imageflow' ),
        'unit' => 'px',
        'step' => '1',
        'min' => '10',
        'max' => '30',
        'default' => '18'
      ),

      array(
        'id' => 'post_date_background_color',
        'type' => 'color',
        'title' => __('Post area time alert area background color','csf'),
        'dependency' => array( 'post_list_style', '==', 'imageflow' ),
        'desc' => __('Custom color, it is recommended to use a color that is the same color as the theme color and belongs to a lighter color','csf'),
        'default' => '#fff5e0'
      ),    

      array(
        'id' => 'post_date_text_color',
        'type' => 'color',
        'title' => __('Post area time alert area text color','csf'),
        'dependency' => array( 'post_list_style', '==', 'imageflow' ),
        'desc' => __('Custom color, suggest using the same color as the theme color matching color','csf'),
        'default' => '#ffcc00'
      ),    

      array(
        'id' => 'post_date_font_size',
        'type' => 'slider',
        'title' => __('Post Area Time Alert Area Font Size','csf'),
        'dependency' => array( 'post_list_style', '==', 'imageflow' ),
        'desc' => __('slider, recommended values range 10-14','csf'),
        'unit' => 'px',
        'step' => '1',
        'min' => '6',
        'max' => '20',
        'default' => '12'
      ),

      array(
        'id' => 'post_icon_more',
        'type' => 'switcher',
        'title' => __('Post area "detail" icon','csf'),
        'label' => __('When enabled the "detail" icon will be displayed below the article area','csf'),
        'default' => false
      ),array(
        'id' => 'is_author_meta_show',
        'type' => 'switcher',
        'title' => __('Article Area "Author Info"','csf'),
        'label' => __('When turned on, author information will be added to the article metadata section.' ,'csf'),
        'default' => false
      ),

      array(
        'id' => 'post_icon_color',
        'type' => 'color',
        'title' => __('Post area icon color','csf'),
        'dependency' => array( 'post_list_style', '==', 'imageflow' ),
        'desc' => __('Custom color, suggest using the same color as the theme color matching color','csf'),
        'default' => '#ffcc00'
      ),    

      array(
        'id' => 'post_border_shadow_color',
        'type' => 'color',
        'title' => __('Post area border shadow color','csf'),
        'dependency' => array( 'post_list_style', '==', 'imageflow' ),
        'desc' => __('Custom color, it is recommended to use a color that is the same color as the theme color and is a lighter color','csf'),
        'default' => '#e8e8e8'
      ),    

    )
  ) );

  CSF::createSection( $prefix, array(
    'id' => 'page', 
    'title' => __('page settings','csf'),
    'icon' => 'fa fa-file-text',
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'page', 
    'title' => __('integrated-setting','csf'),
    'icon' => 'fa fa-compass',
    'fields' => array(

      array(
        'id' => 'page_style',
        'type' => 'image_select',
        'title' => __('page_style','csf'),
        'options' => array(
          'sakurairo' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/page_style_iro.png',
          'sakura' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/page_style_sakura.png',
        ),
        'default' => 'sakurairo'
      ),

      array(
        'id' => 'entry_content_style',
        'type' => 'radio',
        'title' => __('Page Layout Style','csf'),
        'options' => array(
          'sakurairo' => __('default style','csf'),
          'github' => __('Github style','csf'),
        ),
        'default' => 'sakurairo'
      ),

      array(
        'id' => 'patternimg',
        'type' => 'switcher',
        'title' => __('page decoration image','csf'),
        'label' => __('enabled by default, show on article pages, standalone pages and category pages','csf'),
        'default' => true
      ),

      array(
        'id' => 'page_title_animation',
        'type' => 'switcher',
        'title' => __('page title animation','csf'),
        'label' => __('Page title will have float-in animation when turned on','csf'),
        'default' => true
      ),

      array(
        'id' => 'page_title_animation_time',
        'type' => 'slider',
        'title' => __('page title animation time','csf'),
        'dependency' => array( 'page_title_animation', '==', 'true' ),
        'desc' => __('slider, recommended values range 1-2','csf'),
        'step' => '0.01',
        'unit' => 's',
        'max' => '5',
        'default' => '2'
      ),

      array(
        'id' => 'clipboard_copyright',
        'type' => 'switcher',
        'title' => __('Page Clipboard Copyright Notice','csf'),
        'label' => __('Enabled by default, users will have copyright notice text when copying text content over 30 bytes','csf'),
        'default' => true
      ),

      array(
        'id' => 'page_lazyload',
        'type' => 'switcher',
        'title' => __('Page LazyLoad loading','csf'),
        'label' => __('LazyLoad effect for page images after turning on, WordPress block editor already comes with similar effect, not recommended to turn on','csf'),
        'default' => false
      ),

      array(
        'id' => 'page_lazyload_spinner',
        'type' => 'text',
        'title' => __('Page LazyLoad loading placeholder SVG','csf'),
        'dependency' => array( 'page_lazyload', '==', 'true' ),
        'desc' => __('Fill in the address, this is the placeholder image that will be displayed when the page LazyLoad is loaded','csf'),
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/load_svg/inload.svg'
      ),

      array(
        'id' => 'load_in_svg',
        'type' => 'text',
        'title' => __('Page image loading placeholder SVG','csf'),
        'desc' => __('Fill address, this is the SVG that will be displayed as a placeholder when the page image is loaded','csf'),
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/load_svg/inload.svg'
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'page', 
    'title' => __('article page settings','csf'),
    'icon' => 'fa fa-archive',
    'fields' => array(

      array(
        'id' => 'article_title_font_size',
        'type' => 'slider',
        'title' => __('article page title font size','csf'),
        'desc' => __('Slider, recommended value range is 28-36. This option is only available for article pages that have a featured image set','csf'),
        'unit' => 'px',
        'min' => '16',
        'max' => '48',
        'default' => '32'
      ),

      array(
        'id' => 'article_title_line',
        'type' => 'switcher',
        'title' => __('Article page title underline animation','csf'),
        'label' => __('Article title will have underline animation when enabled and article has featured image set','csf'),
        'default' => false
      ),

      array(
        'id' => 'article_auto_toc',
        'type' => 'switcher',
        'title' => __('Article page auto show menu','csf'),
        'label' => __('Enabled by default, the article page will automatically show the menu','csf'),
        'default' => true
      ),

      array(
        'id' => 'article_nextpre',
        'type' => 'switcher',
        'title' => __('Article page up/down article switcher','csf'),
        'label' => __('After turning on the article page will appear the upper and lower article switch','csf'),
        'default' => false
      ),

      array(
        'id' => 'article_lincenses',
        'type' => 'switcher',
        'title' => __('Article page copyright tips and labels','csf'),
        'label' => __('Copyright hint and label will appear on article pages when turned on','csf'),
        'default' => false
      ),

      array(
        'id' => 'author_profile',
        'type' => 'switcher',
        'title' => __('Article page author info','csf'),
        'label' => __('Author information will appear on the article page after turning it on','csf'),
        'default' => false
      ),

      array(
        'id' => 'author_profile_text',
        'type' => 'text',
        'title' => __('Article page author info signature field text','csf'),
        'dependency' => array( 'author_profile', '==', 'true' ),
        'desc' => __('A self-descriptive quote','csf'),
        'default' => '本当の声音を響かせてよ'
      ),

      array(
        'id' => 'alipay_code',
        'type' => 'upload',
        'title' => __('article page appreciation button paypal QR code','csf'),
        'desc' => __('Upload Alipay Receipt Code Image','csf'),
        'library' => 'image',
      ),

      array(
        'id' => 'wechat_code',
        'type' => 'upload',
        'title' => __('article page appreciation button wechat code','csf'),
        'desc' => __('Upload WeChat Receipt Code Image','csf'),
        'library' => 'image',
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'page', 
    'title' => __('template page settings','csf'),
    'icon' => 'fa fa-window-maximize',
    'fields' => array(

      array(
        'id' => 'page_temp_title_font_size',
        'type' => 'slider',
        'title' => __('template page title font size','csf'),
        'desc' => __('Slider, recommended value range is 36-48. This option is only available for template pages with featured images already set','csf'),
        'unit' => 'px',
        'min' => '20',
        'max' => '64',
        'default' => '40'
      ),

      array(
        'id' => 'shuoshuo_background_color1',
        'type' => 'color',
        'title' => __('say template say background colorⅠ','csf'),
        'desc' => __('Custom colors','csf'),
        'default' => '#ffe066'
      ),    

      array(
        'id' => 'shuoshuo_background_color2',
        'type' => 'color',
        'title' => __('talk about template talk about background color II','csf'),
        'desc' => __('Custom colors','csf'),
        'default' => '#ffcc00'
      ),    

      array(
        'id' => 'shuoshuo_arrow',
        'type' => 'switcher',
        'title' => __('say template say tip arrow','csf'),
        'label' => __('After turning on the alert arrow will appear on the left side of the comment','csf'),
        'default' => false
      ),

      array(
        'id' => 'shuoshuo_font',
        'type' => 'text',
        'title' => __('talk about template talk about font','csf'),
        'desc' => __('Fill in the font name. For example: Ma Shan Zheng','csf'),
        'default' => 'Noto Serif SC'
      ),

      array(
        'id' => 'bilibili_id',
        'type' => 'text',
        'title' => __('bilibili catch-up template account ID','csf'),
        'desc' => __('Fill in your account ID, e.g. https://space.bilibili.com/13972644/, just the number "13972644" part','csf'),
        'default' => '13972644'
      ),

      array(
        'id' => 'bilibili_cookie',
        'type' => 'text',
        'title' => __('bilibili catch-up template account cookies','csf'),
        'desc' => __('Fill in your account cookies, F12 to open your browser web panel, go to your B site homepage to get cookies. if left empty, it will not show the progress of catching up','csf'),
        'default' => 'LIVE_BUVID='
      ),

      array(
        'id' => 'friend_link_align',
        'type' => 'image_select',
        'title' => __('friend_link_template_unit_alignment_direction','csf'),
        'options'     => array(
          'left'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/friend_link_left.png',
          'right'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/friend_link_right.png',
          'center'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/friend_link_center.png',
        ),
        'default'     => 'left'
      ),

      array(
        'id' => 'ex_register_open',
        'type' => 'switcher',
        'title' => __('Login template registration function','csf'),
        'label' => __('Login template will allow registration when enabled','csf'),
        'default' => false
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'page', 
    'title' => __('comment-related settings','csf'),
    'icon' => 'fa fa-comments-o',
    'fields' => array(

      array(
        'id' => 'comment_area',
        'type' => 'radio',
        'title' => __('page comment area display','csf'),
        'desc' => __('You can choose to expand or contract the content of the comments area','csf'),
        'options' => array(
          'unfold' => 'expand',
          'fold' => 'shrink',
        ),
        'default' => 'unfold'
      ),

      array(
        'id' => 'comment_smile_bilibili',
        'type' => 'switcher',
        'title' => __('Page comment area bilibili emoji pack','csf'),
        'label' => __('Default on, beeping emoticons are displayed below the comment box','csf'),
        'default' => true
      ),

      array(
        'id' => 'comment_area_image',
        'type' => 'upload',
        'title' => __('Page comment area bottom right background image','csf'),
        'desc' => __('If this option is blank, no image, no best recommendation','csf'),
        'library' => 'image',
      ),

      array(
        'id' => 'comment_useragent',
        'type' => 'switcher',
        'title' => __('Page comment area UA info','csf'),
        'label' => __('When enabled, the page comment area will display the user’s browser, operating system information','csf'),
        'default' => false
      ),

      array(
        'id' => 'comment_location',
        'type' => 'switcher',
        'title' => __('Page comment area location information','csf'),
        'label' => __('When turned on the page comment area will show the user’s location information','csf'),
        'default' => false
      ),

      array(
        'id' => 'comment_private_message',
        'type' => 'switcher',
        'title' => __('private comment function','csf'),
        'label' => __('Enabled will allow users to set their comments to be invisible to others','csf'),
        'default' => false
      ),

      array(
        'id' => 'not_robot',
        'type' => 'switcher',
        'title' => __('Page comment area bot verification','csf'),
        'label' => __('After opening user comments need to be verified before posting','csf'),
        'default' => false
      ),

      array(
        'id' => 'qq_avatar_link',
        'type' => 'select',
        'title' => __('QQ avatar link encryption','csf'),
        'options' => array(
          'off' => __('off','csf'),
          'type_1' => 'redirect (low security)',
          'type_2' => 'Backend get avatar data (medium security)',
          'type_3' => 'Backend parse avatar interface (high security, slow)',
        ),
        'default' => 'off'
      ),

      array(
        'id' => 'img_upload_api',
        'type' => 'select',
        'title' => __('Page comment area upload image interface','csf'),
        'options' => array(
          'off' => __('off','csf'),
          'imgur'  => 'Imgur (https://imgur.com)',
          'smms'  => 'SM.MS (https://sm.ms)',
          'chevereto'  => 'Chevereto (https://chevereto.com)',
        ),
        'default'     => 'off'
      ),

      array(
        'id' => 'imgur_client_id',
        'type' => 'text',
        'title' => __('Imgur Client ID','csf'),
        'dependency' => array( 'img_upload_api', '==', 'imgur' ),
        'desc' => __('Fill in Client ID here, to register please visit https://api.imgur.com/oauth2/addclient ','csf'),
      ),

      array(
        'id' => 'imgur_upload_image_proxy',
        'type' => 'text',
        'title' => __('Imgur upload proxy','csf'),
        'dependency' => array( 'img_upload_api', '==', 'imgur' ),
        'desc' => __('The proxy used by the backend when uploading images to Imgur. You can refer to the tutorial: https://2heng.xin/2018/06/06/javascript-upload-images-with-imgur-api/','csf'),
        'default' => 'https://api.imgur.com/3/image/'
      ),

      array(
        'id' => 'smms_client_id',
        'type' => 'text',
        'title' => __('SM.MS Secret Token','csf'),
        'dependency' => array( 'img_upload_api', '==', 'smms' ),
        'desc' => __('Fill in Key here, to get it please visit https://sm.ms/home/apitoken ','csf'),
      ),

      array(
        'id' => 'chevereto_api_key',
        'type' => 'text',
        'title' => __('Chevereto API v1 Key','csf'),
        'dependency' => array( 'img_upload_api', '==', 'chevereto' ),
        'desc' => __('Fill in Key here, to get please visit your Chevereto home page address/dashboard/settings/api ','csf'),
      ),

      array(
        'id' => 'cheverto_url',
        'type' => 'text',
        'title' => __('Chevereto address','csf'),
        'dependency' => array( 'img_upload_api', '==', 'chevereto' ),
        'desc' => __('Your Chevereto home page address, note the lack of / at the end, e.g. https://your.cherverto.com','csf'),
      ),

      array(
        'id' => 'comment_image_proxy',
        'type' => 'text',
        'title' => __('comment_image_proxy','csf'),
        'desc' => __('Proxy for the image displayed on the frontend','csf'),
        'default' => 'https://images.weserv.nl/?url='
      ),

      array(
        'id' => 'mail_img',
        'type' => 'upload',
        'title' => __('mail template featured image','csf'),
        'desc' => __('Set the background image above your reply email','csf'),
        'library' => 'image',
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/hyouryu/mail_head.jpg'
      ),

      array(
        'id' => 'mail_user_name',
        'type' => 'text',
        'title' => __('mail template outgoing address prefix','csf'),
        'desc' => __('Used to send system mailthe sender address displayed in the use’s mailbox, don’t use Chinese, the default system mail address is bibi@your domain','csf'),
        'default' => 'bibi'
      ),

      array(
        'id' => 'mail_notify',
        'type' => 'switcher',
        'title' => __('User mail reply notification','csf'),
        'label' => __('By default WordPress will use email notifications to notify users when their comments receive a reply, after turning it on allow users to set whether to use email notifications when their comments receive a reply','csf'),
        'default' => false
      ),

      array(
        'id' => 'admin_notify',
        'type' => 'switcher',
        'title' => __('admin email reply notification','csf'),
        'label' => __('Use email notifications when admin comments receive a reply after turning it on','csf'),
        'default' => false
      ),

    )
  ) );

  CSF::createSection( $prefix, array(
    'id' => 'others', 
    'title' => __('other settings','csf'),
    'icon' => 'fa fa-coffee',
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'others', 
    'title' => __('Login screen and dashboard related settings','csf'),
    'icon' => 'fa fa-sign-in',
    'fields' => array(

      array(
        'type' => 'subheading',
        'content' => __('login screen','csf'),
      ),

      array(
        'id' => 'login_background',
        'type' => 'upload',
        'title' => __('Login screen background image','csf'),
        'desc' => __('Set your login screen background image, leave this option blank to show default background','csf'),
        'library'      => 'image',
        'default'     => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/hyouryu/login_background.jpg'
      ),

      array(
        'id' => 'login_blur',
        'type' => 'switcher',
        'title' => __('Login screen background defocus','csf'),
        'label' => __('Login screen background image will be defocused when enabled','csf'),
        'default' => false
      ),

      array(
        'id' => 'login_logo_img',
        'type' => 'upload',
        'title' => __('Login Screen Logo','csf'),
        'desc' => __('Set your login screen Logo','csf'),
        'library' => 'image',
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/hyouryu/login_logo.png'
      ),

      array(
        'id' => 'login_urlskip',
        'type' => 'switcher',
        'title' => __('Jump after login','csf'),
        'label' => __('Jump to backend for admins and home for users after opening','csf'),
        'default' => false
      ),

      array(
        'type' => 'subheading',
        'content' => __('dashboard','csf'),
      ),

      array(
        'id' => 'admin_background',
        'type' => 'upload',
        'title' => __('dashboard background image','csf'),
        'desc' => __('Set your dashboard background image, leave this option blank to show white background','csf'),
        'library' => 'image',
        'default' => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/hyouryu/admin_background.jpg'
      ),

      array(
        'id' => 'admin_first_class_color',
        'type' => 'color',
        'title' => __('Dashboard first class menu color','csf'),
        'desc' => __('Custom colors','csf'),
        'default' => '#88cfef'
      ),  

      array(
        'id' => 'admin_second_class_color',
        'type' => 'color',
        'title' => __('Dashboard secondary menu color','csf'),
        'desc' => __('Custom colors','csf'),
        'default' => '#67c4ef'
      ),  

      array(
        'id' => 'admin_emphasize_color',
        'type' => 'color',
        'title' => __('dashboard emphasis color','csf'),
        'desc' => __('Custom colors','csf'),
        'default' => '#342a29'
      ),  

      array(
        'id' => 'admin_button_color',
        'type' => 'color',
        'title' => __('Dashboard button color','csf'),
        'desc' => __('Custom colors','csf'),
        'default' => '#d1976d'
      ),  

      array(
        'id' => 'admin_text_color',
        'type' => 'color',
        'title' => __('dashboard text color','csf'),
        'desc' => __('Custom colors','csf'),
      ),  

    )
  ) );

  CSF::createSection( $prefix, array(
    'parent' => 'others', 
    'title' => __('low use settings','csf'),
    'icon' => 'fa fa-low-vision',
    'fields' => array(

      array(
        'id' => 'statistics_api',
        'type' => 'radio',
        'title' => __('statistics_api','csf'),
        'desc' => __('You can choose WP-Statistics plugin statistics or theme built-in statistics as statistics','csf'),
        'options' => array(
          'theme_build_in' => 'theme_built_in_statistics',
          'wp_statistics' => 'WP-Statistics plugin statistics',
        ),
        'default' => 'theme_build_in'
      ),

      array(
        'id' => 'statistics_format',
        'type' => 'select',
        'title' => __('Statistics display format','csf'),
        'desc' => __('You can choose from four different data display formats','csf'),
        'options' => array(
          'type_1' => '23333 visits',
          'type_2' => '23,333 visits',
          'type_3' => '23,333 visits',
          'type_4' => '23K visits',
        ),
        'default' => 'type_1'
      ),

      array(
        'id' => 'live_search',
        'type' => 'switcher',
        'title' => __('live search','csf'),
        'label' => __('After turning on the live search in the foreground, call Rest API to update the cache once an hour, you can set the cache time manually in api.php','csf'),
        'default' => false
      ),

      array(
        'id' => 'live_search_comment',
        'type' => 'switcher',
        'title' => __('Live Search Comment Support','csf'),
        'label' => __('Enabled to search for comments in live search (not recommended if site has too many comments)','csf'),
        'default' => false
      ),

      array(
        'id' => 'gravatar_proxy',
        'type' => 'text',
        'title' => __('Gravatar avatar proxy','csf'),
        'desc' => __('Fill in the proxy address of the Gravatar avatar, use the Geek Nation proxy by default, leave it blank to not use the proxy','csf'),
        'default' => 'sdn.geekzu.org/avatar'
      ),

      array(
        'id' => 'google_analytics_id',
        'type' => 'text',
        'title' => __('Google stats code','csf'),
      ),

      array(
        'id' => 'site_custom_style',
        'type' => 'textarea',
        'title' => __('Custom CSS styles','csf'),
        'desc' => __('Fill CSS code without writing style tag','csf'),
      ),

      array(
        'id'=>'footer_addition',
        'type' => 'textarea',
        'title' => __('Footer Addition','csf'),
        'desc' => __('Add HTML code at the end of the page. Useful for adding customize JavaScript, or adding external CSS stylesheets.','csf'),
      ),
      
      array(
        'id' => 'time_zone_fix',
        'type' => 'slider',
        'title' => __('timezonefix','csf'),
        'desc' => __('slider, if the comment has a time difference problem adjust it here, fill in an integer, calculation: actual time = time of display error - the integer you entered (in hours)','csf'),
        'step' => '1',
        'max' => '24',
        'default' => '0'
      ),
      array(
        'type' => 'subheading',
        'content' => __('lightbox','csf'),
      ),
      array(
        'id' => 'baguetteBox',
        'type' => 'switcher',
        'title' => __('BaguetteBox lightbox effect','csf'),
        'label' => __('BaguetteBox will be used as the image lightbox effect when turned on','csf'),
        'default' => false
      ),
      array(
        'id' => 'fancybox',
        'type' => 'switcher',
        'title' => __('FancyBox lightbox effect','csf'),
        'label' => __('FancyBox will be used as an image lightbox effect after opening, additional JQ libraries will be loaded','csf'),
        'dependency' => array( 'baguetteBox', '==', 'false' ),
        'default' => false
      ), 
      array(
        'type' => 'subheading',
        'content' => __('code highlighting','csf'),
      ),array(
        'type'    => 'content',
        'content' => __('<p><strong>Highlight.js:</strong> Default, automatic language recognition. </p>'
        .' <p><strong>Prism.js:</strong> requires a language to be specified, see <a href="https://prismjs.com/#basic-usage">basic usage</a> and <a href="https://prismjs.com/ plugins/file-highlight/">How to code highlight dynamically loaded files</a>. </p>'
        .' <p><strong>Custom:</strong> for cases where another configuration is available. </p>','csf'),
      ),
      array(
        'id' => 'code_highlight_method',
        'type' => 'select',
        'title' => __('code highlighter','csf'),
        'options' => array(
          'hljs' => 'highlight.js',
          'prism' => 'Prism.js',
          'custom' => 'custom',
        ),
        "default" => "hljs"
      ),
      array(
        'id' => 'code_highlight_prism_line_number_all',
        'type' => 'switcher',
        'title' => __('Prism.js: add line number display for all code blocks','csf'),
        'dependency' => array(
          array( 'code_highlight_method', '==', 'prism' ),
        ),
        'desc' => __('See the <a href="https://prismjs.com/plugins/line-numbers/">plugin description documentation</a>','csf'),
      ),
      array(
        'id' => 'code_highlight_prism_autoload_path',
        'type' => 'text',
        'title' => __('Prism.js: autoload address','csf'),
        'dependency' => array(
          array( 'code_highlight_method', '==', 'prism' ),
        ),
        'desc' => "Leave blank to use default values",
        'default'=>'https://cdn.jsdelivr.net/npm/prismjs@1.23.0/'
      ),
      array(
        'id' => 'code_highlight_prism_theme_light',
        'type' => 'text',
        'title' => __('Prism.js: code highlight theme','csf'),
        'desc' => 'Relative to autoload address. Leave blank to use default values',
        'dependency' => array(
          array( 'code_highlight_method', '==', 'prism' ),
        ),
        'default' => 'themes/prism.min.css'
      ), array(
        'id' => 'code_highlight_prism_theme_dark',
        'type' => 'text',
        'title' => __('Prism.js: code highlight theme in dark mode','csf'),
        'desc' => 'Relative to autoload address. Leave blank to use default values',
        'dependency' => array(
          array( 'code_highlight_method', '==', 'prism' ),
        ),
        'default' => 'themes/prism-tomorrow.min.css'
      ),
      array(
        'type' => 'submessage',
        'style' => 'danger',
        'content' => __('The following settings are not recommended to be modified blindly, please use them under the guidance of others','csf'),
      ),

      array(
        'id' => 'image_cdn',
        'type' => 'text',
        'title' => __('image CDN','csf'),
        'desc' => __('Note: fill in the format http(s)://your CDN domain/. This means that images with original path http://your.domain/wp-content/uploads/2018/05/xx.png will be loaded from http://你的CDN域名/2018/05/xx.png','csf'),
        'default' => ''
      ),

      array(
        'id' => 'classify_display',
        'type' => 'text',
        'title' => __('Articles not displayed by category','csf'),
        'desc' => __('Fill in category ID, multiple in English' , 'separate','csf'),
      ),

      array(
        'id' => 'image_category',
        'type' => 'text',
        'title' => __('Image display category','csf'),
        'desc' => __('Fill in category ID, multiple in English" , "separate','csf'),
      ),

      array(
        'id' => 'exlogin_url',
        'type' => 'text',
        'title' => __('Specify login address','csf'),
        'desc' => __('Force not to use the WordPress login page address to login, fill in the new login page address, such as: http://www.xxx.com/login. Note that before filling in the new page you can test the normal opening, so as not to cause the inability to enter the background, etc.','csf'),
      ),

      array(
        'id' => 'exregister_url',
        'type' => 'text',
        'title' => __('Specify registered address','csf'),
        'desc' => __('This address is used as the registration entry on the login page, if you specify a login address, it is recommended to fill in ','csf'),
      ),

      array(
        'id' => 'cookie_version',
        'type' => 'text',
        'title' => __('version control','csf'),
        'desc' => __('Used to update front-end cookies and browser cache, can use any string','csf'),
      ),
    )
  ) );

  CSF::createSection($prefix, array(
    'title' => __('Backup Recovery','csf'),
    'icon'        => 'fa fa-shield',
    'description' => 'Back up or restore your theme settings',
    'fields'      => array(

        array(
            'type' => 'backup',
        ),

    )
  ) );

  CSF::createSection($prefix, array(
    'title' => __('About theme','csf'),
    'icon'        => 'fa fa-paperclip',
    'fields'      => array(

      array(
        'type'    => 'subheading',
        'content' => __('Version Info','csf'),
      ),

      array(
        'type'    => 'content',
        'content' => __('<img src="https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/hyouryulogo.gif"  alt="Theme Information" />','csf'),
      ),

      array(
        'type'    => 'submessage',
        'style'   => 'success',
        'content' => sprintf(__('Working with iro theme version %s | <a href="https://iro.tw">theme documentation</a> | <a href="https://github.com/mirai-mamori/Sakurairo">source address</a>', 'sakurairo', 'csf'), SAKURA_VERSION), 
      ),

      array(
        'type'    => 'subheading',
        'content' => __('Update Related','csf'),
      ),

      array(
        'id'          => 'iro_update_source',
        'type'        => 'image_select',
        'title' => __('Theme update source','csf'),
        'options'     => array(
          'github'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/update_source_github.png',
          'jsdelivr'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/update_source_jsd.png',
          'official_building'  => 'https://cdn.jsdelivr.net/gh/Fuukei/Public_Repository@latest/vision/options/update_source_iro.png',
        ),
        'desc' => __('If you are using a server set up in China, please use the jsDelivr source or the official theme source as your theme update source','csf'),
        'default'     => 'github'
      ),

      array(
        'id' => 'channel_validate_value',
        'type' => 'text',
        'title' => __('Theme update test channel disclaimer','csf'),
        'dependency' => array(
          array( 'local_global_library', '==', 'true' ),
          array( 'iro_update_source', '==', 'official_building' ),
        ),
        'desc' => __('If you want to use the test channel version of the theme, please copy the text in quotes after <strong>ensure that you have carefully understood the risks associated with participating in the test and are willing to assume all consequences at your own risk</strong> (including but not limited to possible data loss) into the options text box <strong "I understand the risks associated with testing and accept all consequences"</strong>','csf'),
      ),

      array(
        'id' => 'iro_update_channel',
        'type' => 'radio',
        'title' => __('theme update channel','csf'),
        'dependency' => array(
          array( 'channel_validate_value', '==', 'I understand the risks associated with testing and am willing to accept all consequences' ),
          array( 'local_global_library', '==', 'true' ),
          array( 'iro_update_source', '==', 'official_building' ), array( 'iro_update_source', '==', 'official_building' ),
        ),
        'desc' => __('You can toggle the update channel here to participate in the testing of the new version','csf'),
        'options' => array(
          'stable' => __('official channel','csf'),
          'beta' => __('public test channel','csf'),
          'preview' => __('preview test channel','csf'),
        ),
        'default' => 'stable'
      ),

      array(
        'type' => 'subheading',
        'content' => __('localization','csf'),
      ),

      array(
        'id' => 'local_global_library',
        'type' => 'switcher',
        'title' => __('localized frontend library','csf'),
        'label' => __('The frontend library will not go jsDelivr CDN after turning it on','csf'),
        'default' => false
      ),

      array(
        'id' => 'local_application_library',
        'type' => 'switcher',
        'title' => __('Localized JS/CSS files','csf'),
        'label' => __('Enabled by default, some JS files and CSS files do not go jsDelivr CDN','csf'),
        'default' => true
      ),

      array(
        'type' => 'subheading',
        'content' => __('citation information','csf'),
      ),

      array(
        'type'    => 'content',
        'content' => __('<p>The Fluent Design icon references the <a href="https://wwi.lanzous.com/ikyq5kgx0wb">Fluent icon pack designed by Paradox</a></p
        <p>MuHydro Icons cites the <a href="https://www.coolapk.com/apk/com.muh2.icon">MuHydro Icon Pack designed by Silence</a></p
        <p>Kanbanma references the <a href="https://github.com/stevenjoezhang/live2d-widget">Live2d-Widget</a> project opened by Stevenjoezhang</p>
        <p>White Cat Style Logo references the original theme author White Cat, as provided and referenced by <a href="https://hyacm.com/acai/ui/143/sakura-logo/">Hyacm</a></p>','csf'),
      ),

      array(
        'type'    => 'subheading',
        'content' => __('Dependency Information','csf'),
      ),

      array(
        'type'    => 'content',
        'content' => __('<p>Static resources rely on the <a href="https://github.com/Fuukei/Public_Repository">Public Repository</a> project created by the theme’s official Fuukei</p>
        <p>The setup framework relies on the Codestar open source <a href="https://github.com/Codestar/codestar-framework">Codestar-Framework</a> project</p>
        <p>Update functionality relies on YahnisElsts open source <a href="https://github.com/YahnisElsts/plugin-update-checker">Plugin-Update-Checker</a> project</p>','csf'),
      ),

      array(
        'type'    => 'content',
        'content' => __('<img src="https://img.shields.io/github/v/release/mirai-mamori/Sakurairo.svg?style=flat-square"  alt="Theme latest version" />  <img src="https://img.shields.io/github/release-date/mirai-mamori/Sakurairo?style=flat-square"  alt="Theme latest version release date" />  <img src="https://data.jsdelivr.com/v1/package/gh/Fuukei/Public_Repository/badge"  alt="Theme CDN resource access" />','csf'),
      ),

    )
  ) );

}

