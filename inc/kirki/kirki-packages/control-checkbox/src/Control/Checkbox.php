<?php
/**
 * Customizer Control: checkbox.
 *
 * Creates a new custom control.
 * Custom controls contains all background-related options.
 *
 * @package    kirki-framework/control-checkbox
 * @copyright  Copyright (c) 2023, Themeum
 * @license    https://opensource.org/licenses/MIT
 * @since      1.0
 */

namespace Kirki\Control;

use Kirki\Control\Base;
use Kirki\URL;

/**
 * Adds a checkbox control.
 *
 * @since 1.0
 */
class Checkbox extends Base {

	/**
	 * The control type.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-checkbox';

	/**
	 * The control version.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public static $control_ver = '1.0.3';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function enqueue() {
		parent::enqueue();

		// Enqueue the script.
		wp_enqueue_script( 'kirki-control-checkbox', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.js' ), [ 'jquery', 'customize-base', 'kirki-control-base' ], self::$control_ver, false );

		// Enqueue the style.
		wp_enqueue_style( 'kirki-control-checkbox-style', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.css' ), [], self::$control_ver );
	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @access protected
	 * @since 1.0
	 * @return void
	 */
	protected function content_template() {
		?>
		<input
			id="_customize-input-{{ data.id }}"
			type="checkbox"
			value="{{ data.value }}"
			{{{ data.link }}}
			<# if ( data.description ) { #>aria-describedby="_customize-description-{{ data.id }}"<# } #>
			<# if ( data.value ) { #>checked="checked"<# } #>
		/>
		<label for="_customize-input-{{ data.id }}">{{{ data.label }}}</label>
		<# if ( data.description ) { #>
			<span id="_customize-description-{{ data.id }}" class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>
		<?php
	}
}
