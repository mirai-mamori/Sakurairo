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
 *
 * @since 1.0
 */
class Number extends Generic {

	/**
	 * The field type.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-number';

	/**
	 * Filter arguments before creating the setting.
	 *
	 * @access public
	 * @since 0.1
	 * @param array                $args         The field arguments.
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 * @return array
	 */
	public function filter_setting_args( $args, $wp_customize ) {

		if ( $args['settings'] !== $this->args['settings'] ) {
			return $args;
		}

		// Set the sanitize-callback if none is defined.
		if ( ! isset( $args['sanitize_callback'] ) || ! $args['sanitize_callback'] ) {

			$args['sanitize_callback'] = function( $value ) use ( $args ) {
				$value = filter_var( $value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );

				if ( isset( $args['choices'] ) && isset( $args['choices']['min'] ) && isset( $args['choices']['max'] ) ) {
					// Make sure min & max are all numeric.
					$min = filter_var( $args['choices']['min'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
					$max = filter_var( $args['choices']['max'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );

					if ( $value < $min ) {
						$value = $min;
					} elseif ( $value > $max ) {
						$value = $max;
					}
				}

				return $value;
			};

		}

		return $args;
	}

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
			$args['choices']['element'] = 'input';
			$args['choices']['type']    = 'number';
			$args['choices']            = wp_parse_args(
				$args['choices'],
				[
					'min'  => -999999999,
					'max'  => 999999999,
					'step' => 1,
				]
			);

			// Make sure min, max & step are all numeric.
			$args['choices']['min']  = filter_var( $args['choices']['min'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
			$args['choices']['max']  = filter_var( $args['choices']['max'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
			$args['choices']['step'] = filter_var( $args['choices']['step'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		}

		return $args;

	}

}
