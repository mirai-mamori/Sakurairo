<?php
/**
 * Customizer Control: kirki-divider.
 *
 * @package   kirki-pro-divider
 * @since     1.0
 */

namespace Kirki\Pro\Control;

use Kirki\Control\Base;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Divider control
 */
class Divider extends Base {

	/**
	 * The control type.
	 *
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-divider';

	/**
	 * The version. Used in scripts & styles for cache-busting.
	 *
	 * @since 1.0
	 * @var string
	 */
	public static $control_ver = '1.0';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @access public
	 * @since 1.0
	 * @see WP_Customize_Control::to_json()
	 * @return void
	 */
	public function to_json() {

		// Get the basics from the parent class.
		parent::to_json();

		$border_top_color    = isset( $this->choices ) && isset( $this->choices['color'] ) ? esc_attr( $this->choices['color'] ) : '#ccc';
		$border_bottom_color = '#f8f8f8';

		$this->json['choices']['borderTopColor']    = $border_top_color;
		$this->json['choices']['borderBottomColor'] = $border_bottom_color;

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

		<hr style="border-top: 1px solid {{ data.choices.borderTopColor }}; border-bottom: 1px solid {{ data.choices.borderBottomColor }}" />

		<?php

	}

}
