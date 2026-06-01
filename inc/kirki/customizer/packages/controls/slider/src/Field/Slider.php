<?php
/**
 * Override field methods.
 *
 * @package kirki-framework/control-slider
 * @license MIT (https://oss.ninja/mit?organization=Kirki%20Framework)
 * @since   1.0
 */

namespace Kirki\Field;

use Kirki\Field;

/**
 * Field overrides.
 *
 * @since 1.0
 */
class Slider extends Field {

	/**
	 * The field type.
	 *
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $type = 'kirki-slider';

	/**
	 * The control class-name.
	 *
	 * @since 1.0
	 * @access protected
	 * @var string
	 */
	protected $control_class = '\Kirki\Control\Slider';

	/**
	 * Whether we should register the control class for JS-templating or not.
	 *
	 * @since 1.0
	 * @access protected
	 * @var bool
	 */
	protected $control_has_js_template = true;

	/**
	 * Filter arguments before creating the setting.
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
				$args['sanitize_callback'] = function ( $value ) {
					return filter_var( $value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
				};
			}
		}

		return $args;

	}

	/**
	 * Filter arguments before creating the control.
	 *
	 * @param array                 $args The field arguments.
	 * @param \WP_Customize_Manager $wp_customize The customizer instance.
	 *
	 * @return array $args The maybe-filtered arguments.
	 */
	public function filter_control_args( $args, $wp_customize ) {

		if ( $args['settings'] === $this->args['settings'] ) {
			$args         = parent::filter_control_args( $args, $wp_customize );
			$args['type'] = 'kirki-slider';
		}

		return $args;

	}

}
