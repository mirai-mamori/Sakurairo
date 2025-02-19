<?php
/**
 * Override field methods.
 *
 * @package kirki-pro-margin
 * @since   1.0
 */

namespace Kirki\Pro\Field;

use Kirki\Field;
use Kirki\URL;

/**
 * Field overrides.
 *
 * @since 1.0
 */
class Margin extends Field {

	/**
	 * The field type.
	 *
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $type = 'kirki-margin';

	/**
	 * The control class-name.
	 *
	 * @since 1.0
	 * @access protected
	 * @var string
	 */
	protected $control_class = '\Kirki\Pro\Control\Margin';

	/**
	 * Whether we should register the control class for JS-templating or not.
	 *
	 * @since 1.0
	 * @access protected
	 * @var bool
	 */
	protected $control_has_js_template = false;

	/**
	 * Additional logic for the field.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param array $args The field arguments.
	 */
	protected function init( $args ) {

		add_action( 'customize_preview_init', [ $this, 'enqueue_customize_preview_scripts' ] );
		add_filter( 'kirki_output_control_classnames', [ $this, 'output_control_classnames' ] );

	}

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
				$args['sanitize_callback'] = [ __CLASS__, 'sanitize' ];
			}
		}

		return $args;

	}

	/**
	 * Sanitize the value.
	 *
	 * @param mixed $values The value to sanitize.
	 * @return mixed
	 */
	public static function sanitize( $values ) {

		foreach ( $values as $position => $value ) {
			if ( '' !== $value ) {
				if ( is_numeric( $value ) ) {
					$value = $value . 'px';
				}
			}

			$values[ $position ] = sanitize_text_field( $value );
		}

		return $values;

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
			$args['type'] = 'kirki-margin';
		}

		return $args;

	}

	/**
	 * Enqueue styles & scripts on 'customize_preview_init' action.
	 *
	 * @since 4.0.0
	 * @access public
	 */
	public function enqueue_customize_preview_scripts() {

		wp_enqueue_script( 'kirki-preview-margin-padding', URL::get_from_path( dirname( dirname( __DIR__ ) ) ) . '/dist/preview.js', [ 'wp-hooks', 'customize-preview' ], $this->control_class::$control_ver, true );

	}

	/**
	 * Add output control class for margin/padding control.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $control_classes The existing control classes.
	 * @return array
	 */
	public function output_control_classnames( $control_classes ) {

		$class_name = str_ireplace( 'kirki-', '', $this->type );
		$class_name = ucfirst( $class_name );

		$control_classes[ $this->type ] = '\Kirki\Pro\Field\CSS\\' . $class_name;

		return $control_classes;

	}

}
