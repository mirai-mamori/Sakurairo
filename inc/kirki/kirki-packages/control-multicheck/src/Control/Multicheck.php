<?php
/**
 * Customizer Control: multicheck.
 *
 * Multiple checkbox customize control class.
 * Props @ Justin Tadlock: http://justintadlock.com/archives/2015/05/26/multiple-checkbox-customizer-control
 *
 * @package   kirki-framework/control-multicheck
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Control;

use Kirki\Control\Base;
use Kirki\URL;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds a multicheck control.
 *
 * @since 1.0
 */
class Multicheck extends Base {

	/**
	 * The control type.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-multicheck';

	/**
	 * The version. Used in scripts & styles for cache-busting.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public static $control_ver = '1.0';

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
		wp_enqueue_script( 'kirki-control-multicheck', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.js' ), [ 'jquery', 'customize-base', 'kirki-control-base' ], self::$control_ver, false );

		// Enqueue the style.
		wp_enqueue_style( 'kirki-control-multicheck-style', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.css' ), [], self::$control_ver );
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
		<# if ( ! data.choices ) { return; } #>

		<# if ( data.label ) { #><span class="customize-control-title">{{{ data.label }}}</span><# } #>
		<# if ( data.description ) { #><span class="description customize-control-description">{{{ data.description }}}</span><# } #>

		<ul>
			<# for ( key in data.choices ) { #>
				<li><label<# if ( _.contains( data.value, key ) ) { #> class="checked"<# } #>><input {{{ data.inputAttrs }}} type="checkbox" value="{{ key }}"<# if ( _.contains( data.value, key ) ) { #> checked<# } #> />{{ data.choices[ key ] }}</label></li>
			<# } #>
		</ul>
		<?php
	}
}
