<?php
/**
 * Sakurairo Customizer.
 * Modified from Akina Theme
 * @package Sakurairo
 */
use Sakura\Custom\{Homepage_Component_Order_Control};

/**
 * 为站点标题、描述及颜色添加 postMessage 支持
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function akina_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'akina_customize_register' );

// 获取首页组件顺序
// @return array [组件ID => 显示名称]
function get_homepage_sortable_items() {
    $component_order = array();
    if ( function_exists('iro_opt') && iro_opt('bulletin_board') == '1' ) {
        $component_order['bulletin'] = __( '公告栏', 'sakura' );
    }
    if ( function_exists('iro_opt') && iro_opt('exhibition_area') == '1' ) {
        $component_order['exhibition'] = __( '展示区域', 'sakura' );
    }
    //if ( get_theme_mod( 'home_static_page', 0 ) ) { 默认显示，以支持选择后立即展示
        $component_order['static_page'] = __( '静态页面（选择后显示）', 'sakura' );
    //}
    $component_order['primary'] = __( '文章列表', 'sakura' );
    return $component_order;
}

/**
 * 输出可排序控件的 HTML 代码
 *
 * @param string $setting_id 控件关联的设置ID。
 * @param string $label      控件标题。
 */
function render_order_controllers( $setting_id, $label ) {
    // 获取当前设置值（默认值为 json 编码数组）
    $default_order = json_encode( array( 'bulletin', 'static_page', 'exhibition', 'primary' ) );
    $value         = get_theme_mod( $setting_id, $default_order );
    $order         = json_decode( $value, true );
    $sortable_items = get_homepage_sortable_items();
    ?>
    <div class="component-sort-container">
        <span class="customize-control-title"><?php echo esc_html( $label ); ?></span>
        <ul class="components-sortable">
            <?php 
            if ( is_array( $order ) ) {
                foreach ( $order as $component ) :
                    if ( isset( $sortable_items[ $component ] ) ) : ?>
                        <li class="component-item" 
                            data-component="<?php echo esc_attr( $component ); ?>"
                            style="padding: 10px; background: #fff; border: 1px solid #ddd; margin: 5px 0; cursor: move;"
                        >
                            <?php echo esc_html( $sortable_items[ $component ] ); ?>
                            <span class="dashicons dashicons-menu"></span>
                        </li>
                    <?php endif;
                endforeach;
            }
            ?>
        </ul>
        <!-- 注意这里输出一个隐藏的输入框，用于绑定 Customizer 设置 -->
        <input type="hidden" id="<?php echo esc_attr( $setting_id ); ?>" 
               name="<?php echo esc_attr( $setting_id ); ?>" 
               value="<?php echo esc_attr( $value ); ?>" 
               data-customize-setting-link="<?php echo esc_attr( $setting_id ); ?>">
    </div>
    
    <style>
    .components-sortable {
        list-style: none;
        padding-left: 0;
    }
    .component-item .dashicons-menu {
        float: right;
        color: #a0a5aa;
    }
    </style>

    <script>
    (function($) {
        wp.customize.bind('ready', function() {
            $('.components-sortable').sortable({
                update: function() {
                    var order = [];
                    $(this).find('.component-item').each(function() {
                        order.push( $(this).data('component') );
                    });
                    // 将排序后的数组以 JSON 格式保存到隐藏输入框中，并触发 change 事件
                    $('#<?php echo esc_js( $setting_id ); ?>').val( JSON.stringify( order ) ).trigger('change');
                }
            });
        });
    })(jQuery);
    </script>
    <?php
}

function set_homepage_controls($wp_customize) {
    // 移除不需要的控制项
	$wp_customize->remove_control( 'show_on_front' );
    $wp_customize->remove_control( 'page_on_front' );
    $wp_customize->remove_control( 'page_for_posts' );

	$section = $wp_customize->get_section( 'static_front_page' );
	if ( $section ) {
		$section->description = __('部分首页设置项，如果您安装了缓存插件，请在设置后清除缓存。',"sakura");
	}
    // 添加首页静态页面设置
	$wp_customize->add_setting('home_static_page', array(
        'default'           => 0, 
        'sanitize_callback' => 'absint', 
        'transport'         => 'refresh', 
    ));
    // 添加组件顺序设置
	$wp_customize->add_setting('component_order', array(
        'default'           => json_encode(['bulletin', 'static_page', 'exhibition', 'primary']),
        'transport'         => 'refresh',
        'sanitize_callback' => function($input) {
            $valid = array( 'bulletin', 'static_page', 'exhibition', 'primary' );
            $data = json_decode($input, true);
            return json_encode(array_filter($data, function($item) use ($valid) {
                return in_array($item, $valid);
            }));
        }
	));

    $wp_customize->add_control('mytheme_selected_page_control', array(
        'type'            => 'dropdown-pages',
        'label'           => __('页面',"sakura"),
        'description'     => __('显示一个静态页面',"sakura"),
        'section'         => 'static_front_page',
        'settings'        => 'home_static_page', 
        'allow_addition'  => true, 
	));

    // 捕获自定义控件的输出（包含排序列表和隐藏输入框）
    ob_start();
        render_order_controllers( 'component_order', __( '页面组件顺序', 'sakura' ) );
    $order_controllers = ob_get_clean();

    $wp_customize->add_control( 'component_order_control', array(
        'section'     => 'static_front_page',
        'settings'    => 'component_order',
        'type'        => 'hidden',
        'description' => $order_controllers,
    ) );
};
add_action('customize_register', 'set_homepage_controls');
