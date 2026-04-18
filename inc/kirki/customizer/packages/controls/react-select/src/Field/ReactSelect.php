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

use Kirki\Field;

/**
 * Field overrides.
 *
 * @since 1.0
 */
class ReactSelect extends Field {

	/**
	 * The field type.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-select';

	/**
	 * Whether this is a multi-select or not.
	 *
	 * *Backwards compatibility note:
	 *
	 * Previously (when Kirki used Select2), $multiple is used to:
	 * - Determine whether the select is multiple or not.
	 * - Determine the maximum number of selection.
	 *
	 * Start from Kirki 4 (when Kirki uses react-select),
	 * $multiple is used to determine whether the select is multiple or not.
	 * The maximum selection number is now set in $max_selection.
	 *
	 * @since 1.0
	 * @var bool
	 */
	protected $multiple = false;

	/**
	 * The maximum selection length for multiple selection.
	 *
	 * @since 1.1
	 * @var bool
	 */
	protected $max_selection_number = 999;

	/**
	 * Placeholder text.
	 *
	 * @access protected
	 * @since 1.0
	 * @var string|false
	 */
	protected $placeholder = false;

	/**
	 * The control class-name.
	 *
	 * @access protected
	 * @since 0.1
	 * @var string
	 */
	protected $control_class = '\Kirki\Control\ReactSelect';

	/**
	 * Whether we should register the control class for JS-templating or not.
	 *
	 * @access protected
	 * @since 0.1
	 * @var bool
	 */
	protected $control_has_js_template = true;

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

		if ( $args['settings'] === $this->args['settings'] ) {
			$args = parent::filter_setting_args( $args, $wp_customize );

			if ( isset( $args['multiple'] ) ) {
				$multiple_and_max             = self::get_multiple_and_max( $args['multiple'] );
				$args['multiple']             = $multiple_and_max['multiple'];
				$args['max_selection_number'] = $multiple_and_max['max_selection_number'];
			} else {
				$args['multiple'] = false;
			}

			// Set the sanitize-callback if none is defined.
			if ( ! isset( $args['sanitize_callback'] ) || ! $args['sanitize_callback'] ) {
				$args['sanitize_callback'] = ! $args['multiple'] ? 'sanitize_text_field' : function( $values ) use ( $args ) {
					$values           = (array) $values;
					$sanitized_values = [];

					// If total selected values > max_selection_number, then we need to remove the excess.
					if ( count( $values ) > $args['max_selection_number'] ) {
						for ( $i = 0; $i < $args['max_selection_number']; $i++ ) {
							$sanitized_values[ $i ] = isset( $values[ $i ] ) ? sanitize_text_field( $values[ $i ] ) : '';
						}
					} else {
						foreach ( $values as $index => $subvalue ) {
							$sanitized_values[ $index ] = sanitize_text_field( $subvalue );
						}
					}

					return $sanitized_values;
				};
			}
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

			if ( isset( $args['multiple'] ) ) {
				$multiple_and_max             = self::get_multiple_and_max( $args['multiple'] );
				$args['multiple']             = $multiple_and_max['multiple'];
				$args['max_selection_number'] = $multiple_and_max['max_selection_number'];
			}

			$args['type'] = 'kirki-react-select';
		}

		return $args;

	}

	/**
	 * Get the value of "multiple" and "max_selection_number"
	 * from the provided $multiple parameter.
	 *
	 * @since 1.1
	 *
	 * @param bool|int $multiple The provided $multiple value.
	 * @return array
	 */
	public static function get_multiple_and_max( $multiple ) {

		$max_selection_number = 999;

		if ( is_numeric( $multiple ) ) {
			$multiple = (int) $multiple;

			/**
			 * Treat -1 as unlimited just like in WordPress's get_posts (well, in this Kirki case, it's 999 :).
			 * Also treat 0 as "unlimited" because 1 it self will disable the multiple selection.
			 */
			if ( 0 >= $multiple ) {
				$max_selection_number = 999;
				$multiple             = true;
			} else {
				// If $multiple is > 1.
				if ( 1 < $multiple ) {
					$max_selection_number = $multiple;
					$multiple             = true;
				} else {
					// Here $multiple === 1, that means, it's single mode select.
					$multiple = false;
				}
			}
		}

		return [
			'multiple'             => $multiple,
			'max_selection_number' => $max_selection_number,
		];

	}

}
