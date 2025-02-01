<?php
namespace Sakura\Customizer;

// 通用可排序控件父类
abstract class Generic_Sortable_Control extends \WP_Customize_Control {
    /**
     * @return array [组件ID => 显示名称]
     */
    abstract protected function get_sortable_items();

    public function render_content() {
        $components = $this->get_sortable_items();
        ?>
        <div class="component-sort-container">
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <ul class="components-sortable">
                <?php foreach (json_decode($this->value(), true) as $component) : ?>
                    <?php if (isset($components[$component])) : ?>
                        <li class="component-item" 
                            data-component="<?php echo esc_attr($component); ?>"
                            style="padding: 10px; background: #fff; border: 1px solid #ddd; margin: 5px 0; cursor: move;"
                        >
                            <?php echo esc_html($components[$component]); ?>
                            <span class="dashicons dashicons-menu"></span>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <input type="hidden" <?php $this->link(); ?>>
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
                            order.push($(this).data('component'));
                        });
                        wp.customize('<?php echo $this->id; ?>').set(JSON.stringify(order));
                    }
                });
            });
        })(jQuery);
        </script>
        <?php
    }
}

// 首页专用子类
class Homepage_Component_Order_Control extends Generic_Sortable_Control {
    protected function get_sortable_items() {
        return [
            'bulletin'    => __('公告栏', 'sakurairo'),
            'static_page' => __('静态页面', 'sakurairo'),
            'exhibition'  => __('展示区域', 'sakurairo'),
            'primary'     => __('文章列表', 'sakurairo')
        ];
    }
}