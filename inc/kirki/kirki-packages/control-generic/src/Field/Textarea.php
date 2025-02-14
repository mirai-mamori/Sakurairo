<?php
/**
 * Override field methods
 *
 * @package   kirki-framework/control-generic
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Field;

/**
 * Field overrides.
 */
class Textarea extends Generic {

	/**
	 * The field type.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-textarea';

	/**
	 * Filter arguments before creating the control.
	 *
	 * @access public
	 * @since 0.1
	 * @param array                $args         The field arguments.
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 * @return array
	 */
	public function filter_control_args( $args, $wp_customize ) {
		if ( $args['settings'] === $this->args['settings'] ) {
			$args = parent::filter_control_args( $args, $wp_customize );

			// Set the control-type.
			$args['type'] = 'kirki-generic';

			// Choices.
			$args['choices']            = isset( $args['choices'] ) ? $args['choices'] : [];
			$args['choices']['element'] = 'textarea';
			$args['choices']['rows']    = '5';
		}
		return $args;
	}
}
