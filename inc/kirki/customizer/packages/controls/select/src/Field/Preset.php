<?php
/**
 * Override field methods
 *
 * @package   kirki-framework/control-select
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Field;

/**
 * Field overrides.
 *
 * @since 1.0
 */
class Preset extends Select {

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

			$args['multiple'] = 1;
			$args['preset']   = $args['choices'];
			foreach ( $args['choices'] as $key => $args ) {
				$args['choices'][ $key ] = $args['label'];
			}
		}
		return $args;
	}
}
