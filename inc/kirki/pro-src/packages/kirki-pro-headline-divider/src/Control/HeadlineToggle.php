<?php
/**
 * Customizer Control: kirki-headline-toggle.
 *
 * @package   kirki-pro-headline-toggle
 * @since     1.0
 */

namespace Kirki\Pro\Control;

use Kirki\Control\Checkbox_Switch;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Headline toggle control
 */
class HeadlineToggle extends Checkbox_Switch {

	/**
	 * The class constructor.
	 *
	 * @since 1.0
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      Control ID.
	 * @param array                $args    The control arguments.
	 */
	public function __construct( $manager, $id, $args = array() ) {

		parent::__construct( $manager, $id, $args );

		if ( isset( $args['wrapper_attrs'] ) && isset( $args['wrapper_attrs']['class'] ) ) {
			$this->wrapper_attrs['class'] = '{default_class} ' . $args['wrapper_attrs']['class'] . ' customize-control-kirki-headline-toggle';
		} else {
			$this->wrapper_attrs['class'] = '{default_class} customize-control-kirki-headline-toggle';
		}

	}

}
