<?php
/**
 * Customizer Control: kirki-headline.
 *
 * @package   kirki-pro-headline
 * @since     1.0
 */

namespace Kirki\Pro\Control;

use Kirki\Control\Base;
use Kirki\URL;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Headline control
 */
class Headline extends Base {

	/**
	 * The control type.
	 *
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-headline';

	/**
	 * The version. Used in scripts & styles for cache-busting.
	 *
	 * @since 1.0
	 * @var string
	 */
	public static $control_ver = '1.0.0.1';

	/**
	 * Enqueue control related styles/scripts.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function enqueue() {

		parent::enqueue();

		// Enqueue the style.
		wp_enqueue_style( 'kirki-control-headline', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.css' ), [], self::$control_ver );

	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 * @since 1.0
	 */
	protected function content_template() {
		?>

		<div class="kirki-control-label">
			<# if (data.label) { #>
				<h4 class="customize-control-title">
					{{{ data.label }}}
				</h4>
			<# } #>

			<# if (data.description) { #>
				<p class="customize-control-description">{{{ data.description }}}</p>
			<# } #>
		</div>

		<?php

	}

}
