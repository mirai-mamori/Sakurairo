<?php
namespace Sakura\Customizer;

class Component_Order_Control extends \WP_Customize_Control {
	// 控制页面顺序的专用类，可扩展，比如以后可支持多个公告栏
	public function render_content() {
		$components = [
			'bulletin' => '公告栏',
			'static_page' => '静态页面',
			'exhibition' => '展示区域',
			'primary' => '文章列表', 
		];
		?>
		<div class="component-sort-container">
			<span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
			<ul class="components-sortable">
				<?php foreach (json_decode($this->value(), true) as $component) : ?>
					<li class="component-item" 
						data-component="<?php echo esc_attr($component); ?>"
						style="padding: 10px; background: #fff; border: 1px solid #ddd; margin: 5px 0; cursor: move;"
					>
						<?php echo esc_html($components[$component] ?? $component); ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<input type="hidden" <?php $this->link(); ?>>
		</div>
		<style>
		.components-sortable {
			list-style: none;
			padding-left: 0;
		}
		.component-item::after {
			content: "\f333";
			font-family: dashicons;
			float: right;
			color: #a0a5aa;
		}
		</style>

		<?php // 内联脚本，这里是后台，写点js问题不大 ?>
		<script>
		(function($) {
			wp.customize.bind('ready', function() {
				$('.components-sortable').sortable({
					update: function() {
						var order = [];
						$(this).find('.component-item').each(function() {
							order.push($(this).data('component'));
						});
						wp.customize('component_order').set(JSON.stringify(order));
					}
				});
			});
		})(jQuery);
		</script>
		<?php
	}
}