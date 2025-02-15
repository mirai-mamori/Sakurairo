<?php
/**
 * The code editor control.
 *
 * Creates a code editor control.
 *
 * @package kirki-framework/control-code
 * @license MIT (https://oss.ninja/mit?organization=Kirki%20Framework)
 * @since   1.0.2
 */

namespace Kirki\Control;

use Kirki\Control\Base;

/**
 * Slider control.
 *
 * @since 1.0.2
 */
class Code extends Base {

	/**
	 * The control version.
	 *
	 * @since 1.0.2
	 * @access public
	 * @var string
	 */
	public static $control_ver = '1.0.2';

	/**
	 * Customize control type.
	 *
	 * @since 1.0.2
	 * @var string
	 */
	public $type = 'code_editor';

	/**
	 * Type of code that is being edited.
	 *
	 * @since 1.0.2
	 * @var string
	 */
	public $code_type = '';

	/**
	 * Code editor settings.
	 *
	 * @see wp_enqueue_code_editor()
	 * @since 1.0.2
	 * @var array|false
	 */
	public $editor_settings = array();

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @since 1.0.2
	 */
	public function enqueue() {
		$this->editor_settings = wp_enqueue_code_editor(
			array_merge(
				array(
					'type'       => $this->code_type,
					'codemirror' => array(
						'indentUnit' => 2,
						'tabSize'    => 2,
					),
				),
				$this->editor_settings
			)
		);
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.2
	 *
	 * @see WP_Customize_Control::json()
	 *
	 * @return array Array of parameters passed to the JavaScript.
	 */
	public function json() {
		$json                    = parent::json();
		$json['editor_settings'] = $this->editor_settings;
		$json['input_attrs']     = $this->input_attrs;
		return $json;
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 *
	 * @since 1.0.2
	 */
	public function render_content() {}

	/**
	 * Render a JS template for control display.
	 *
	 * @since 1.0.2
	 */
	public function content_template() {
		?>
		<# var elementIdPrefix = 'el' + String( Math.random() ); #>
		<# if ( data.label ) { #>
			<label for="{{ elementIdPrefix }}_editor" class="customize-control-title">
				{{ data.label }}
			</label>
		<# } #>
		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>
		<div class="customize-control-notifications-container"></div>
		<textarea id="{{ elementIdPrefix }}_editor"
			<# _.each( _.extend( { 'class': 'code' }, data.input_attrs ), function( value, key ) { #>
				{{{ key }}}="{{ value }}"
			<# }); #>
			></textarea>
		<?php
	}

}
