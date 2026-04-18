<?php
/**
 * Customizer Control: cropped-image.
 *
 * @package   kirki-framework/control-cropped-image
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Control;

/**
 * Adds the image control.
 *
 * @since 1.0
 */
class Cropped_Image extends \WP_Customize_Cropped_Image_Control {

	/**
	 * The field type.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-cropped-image';
}
