<?php // phpcs:disable PHPCompatibility.FunctionDeclarations.NewClosure
/**
 * Override field methods
 *
 * @package   kirki-framework/control-radio
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
class Radio extends Field {

	/**
	 * The field type.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-radio';

	/**
	 * The control class-name.
	 *
	 * @access protected
	 * @since 0.1
	 * @var string
	 */
	protected $control_class = '\Kirki\Control\Radio';

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

			// Set the sanitize-callback if none is defined.
			if ( ! isset( $args['sanitize_callback'] ) || ! $args['sanitize_callback'] ) {
				$args['sanitize_callback'] = function( $value ) {
					if ( ! isset( $this->args['choices'][ $value ] ) ) {
						return ( isset( $this->args['default'] ) ) ? $this->args['default'] : '';
					}
					return $value;
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
			$args         = parent::filter_control_args( $args, $wp_customize );
			$args['type'] = 'kirki-radio';
		}
		return $args;
	}
}
