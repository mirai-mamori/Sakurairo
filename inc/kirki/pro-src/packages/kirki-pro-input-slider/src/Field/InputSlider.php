<?php
/**
 * Override field methods.
 *
 * @package kirki-pro-input-slider
 */

namespace Kirki\Pro\Field;

use Kirki\Field;

/**
 * Field overrides.
 *
 * @since 1.0
 */
class InputSlider extends Field {

	/**
	 * The field type.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	public $type = 'kirki-input-slider';

	/**
	 * The control class-name.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	protected $control_class = '\Kirki\Pro\Control\InputSlider';

	/**
	 * Whether we should register the control class for JS-templating or not.
	 *
	 * @since 1.0
	 *
	 * @var bool
	 */
	protected $control_has_js_template = false;

	/**
	 * Filter arguments before creating the setting.
	 *
	 * @since 1.0.0
	 *
	 * @param array                 $args The field arguments.
	 * @param \WP_Customize_Manager $wp_customize The customizer instance.
	 *
	 * @return array $args The maybe-filtered arguments.
	 */
	public function filter_setting_args( $args, $wp_customize ) {

		if ( $args['settings'] === $this->args['settings'] ) {
			$args = parent::filter_setting_args( $args, $wp_customize );

			// Set the sanitize_callback if none is defined.
			if ( ! isset( $args['sanitize_callback'] ) || ! $args['sanitize_callback'] ) {
				$args['sanitize_callback'] = [ __CLASS__, 'sanitize' ];
			}
		}

		return $args;

	}

	/**
	 * Sanitize the value.
	 *
	 * @param mixed $value The value to sanitize.
	 * @return mixed
	 */
	public static function sanitize( $value ) {

		if ( is_numeric( $value ) ) {
			return filter_var( $value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		} else {
			return sanitize_text_field( $value );
		}

	}

	/**
	 * Filter arguments before creating the control.
	 *
	 * @since 1.0.0
	 *
	 * @param array                 $args The field arguments.
	 * @param \WP_Customize_Manager $wp_customize The customizer instance.
	 *
	 * @return array $args The maybe-filtered arguments.
	 */
	public function filter_control_args( $args, $wp_customize ) {

		if ( $args['settings'] === $this->args['settings'] ) {
			$args         = parent::filter_control_args( $args, $wp_customize );
			$args['type'] = 'kirki-input-slider';
		}

		return $args;

	}

}
