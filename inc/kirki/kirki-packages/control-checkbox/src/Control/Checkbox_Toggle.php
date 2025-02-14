<?php
/**
 * Customizer Control: toggle.
 *
 * @package   kirki-framework/checkbox
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Control;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Switch control (modified checkbox).
 *
 * @since 1.0
 */
class Checkbox_Toggle extends Checkbox_Switch {

	/**
	 * The control type.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-toggle';

}
