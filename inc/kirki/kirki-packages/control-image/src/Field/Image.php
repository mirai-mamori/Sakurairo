<?php // phpcs:disable PHPCompatibility.FunctionDeclarations.NewClosure
/**
 * Override field methods
 *
 * @package   kirki-framework/control-image
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Field;

use Kirki\Field;

/**
 * Field overrides.
 */
class Image extends Field {

	/**
	 * The field type.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-image';

	/**
	 * The control class-name.
	 *
	 * @access protected
	 * @since 0.1
	 * @var string
	 */
	protected $control_class = '\Kirki\Control\Image';

	/**
	 * Whether we should register the control class for JS-templating or not.
	 *
	 * @access protected
	 * @since 0.1
	 * @var bool
	 */
	protected $control_has_js_template = true;

	/**
	 * Additional logic for this field.
	 *
	 * @access protected
	 * @since 0.1
	 * @param array $args The field arguments.
	 * @return void
	 */
	protected function init( $args ) {
		add_filter( 'kirki_output_item_args', [ $this, 'output_item_args' ], 10, 4 );
		add_filter( 'kirki_output_control_classnames', [ $this, 'output_control_classnames' ] );
	}

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
					if ( isset( $this->args['choices']['save_as'] ) && 'array' === $this->args['choices']['save_as'] ) {
						return [
							'id'     => ( isset( $value['id'] ) && '' !== $value['id'] ) ? (int) $value['id'] : '',
							'url'    => ( isset( $value['url'] ) && '' !== $value['url'] ) ? esc_url_raw( $value['url'] ) : '',
							'width'  => ( isset( $value['width'] ) && '' !== $value['width'] ) ? (int) $value['width'] : '',
							'height' => ( isset( $value['height'] ) && '' !== $value['height'] ) ? (int) $value['height'] : '',
						];
					}
					if ( isset( $this->args['choices']['save_as'] ) && 'id' === $this->args['choices']['save_as'] ) {
						return absint( $value );
					}
					return ( is_string( $value ) ) ? esc_url_raw( $value ) : $value;
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

			$args['button_labels'] = isset( $args['button_labels'] ) ? $args['button_labels'] : [];
			$args['button_labels'] = wp_parse_args(
				$args['button_labels'],
				[
					'select'       => esc_html__( 'Select image', 'kirki' ),
					'change'       => esc_html__( 'Change image', 'kirki' ),
					'default'      => esc_html__( 'Default', 'kirki' ),
					'remove'       => esc_html__( 'Remove', 'kirki' ),
					'placeholder'  => esc_html__( 'No image selected', 'kirki' ),
					'frame_title'  => esc_html__( 'Select image', 'kirki' ),
					'frame_button' => esc_html__( 'Choose image', 'kirki' ),
				]
			);

			$args['choices']            = isset( $args['choices'] ) ? (array) $args['choices'] : [];
			$args['choices']['save_as'] = isset( $args['choices']['save_as'] ) ? $args['choices']['save_as'] : 'url';
			$args['choices']['labels']  = isset( $args['choices']['labels'] ) ? $args['choices']['labels'] : [];
			$args['choices']['labels']  = wp_parse_args( $args['choices']['labels'], $args['button_labels'] );

			// Set the control-type.
			$args['type'] = 'kirki-image';
		}
		return $args;
	}

	/**
	 * Filter for output argument used by the kirki-framework/module-css module.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $output      A single output item.
	 * @param mixed $value       The value.
	 * @param array $all_outputs All field output args.
	 * @param array $field       The field arguments.
	 * @return array
	 */
	public function output_item_args( $output, $value, $all_outputs, $field ) {
		if ( $field['settings'] === $this->args['settings'] ) {
			if ( isset( $output['property'] ) && in_array( [ 'background', 'background-image' ], $output['property'], true ) ) {
				if ( ! isset( $output['value_pattern'] ) || empty( $output['value_pattern'] ) || '$' === $output['value_pattern'] ) {
					$output['value_pattern'] = 'url("$")';
				}
			}
		}
		return $output;
	}

	/**
	 * Adds a custom output class for typography fields.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $classnames The array of classnames.
	 * @return array
	 */
	public function output_control_classnames( $classnames ) {
		$classnames['kirki-image'] = '\Kirki\Field\CSS\Image';
		return $classnames;
	}
}
