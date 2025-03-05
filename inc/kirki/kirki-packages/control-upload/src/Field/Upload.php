<?php
/**
 * Override field methods
 *
 * @package   kirki-framework/control-typography
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
class Upload extends Field {

	/**
	 * The field type.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-upload';

	/**
	 * The control class-name.
	 *
	 * @access protected
	 * @since 0.1
	 * @var string
	 */
	protected $control_class = '\Kirki\Control\Upload';

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
					$save_as = isset( $this->args['choices']['save_as'] ) ? $this->args['choices']['save_as'] : 'url';

					return self::sanitize( $value, $save_as );
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
			$args['type'] = 'upload';
		}
		return $args;
	}

	/**
	 * Sanitizes the field value.
	 *
	 * @since 1.0.2
	 *
	 * @param mixed  $value The field value.
	 * @param string $save_as The expected saving format.
	 *
	 * @return mixed
	 */
	public static function sanitize( $value, $save_as = 'url' ) {

		if ( 'array' === $save_as ) {

			if ( is_array( $value ) ) {
				return [
					'id'       => ( isset( $value['id'] ) && '' !== $value['id'] ) ? (int) $value['id'] : '',
					'url'      => ( isset( $value['url'] ) && '' !== $value['url'] ) ? esc_url_raw( $value['url'] ) : '',
					'filename' => ( isset( $value['filename'] ) && '' !== $value['filename'] ) ? sanitize_text_field( $value['filename'] ) : '',
				];
			} elseif ( is_string( $value ) && ! is_numeric( $value ) ) {
				// Here, we assume that the value is the URL.
				$attachment_id = attachment_url_to_postid( $value );

				return [
					'id'       => $attachment_id,
					'url'      => $value,
					'filename' => basename( get_attached_file( $attachment_id ) ),
				];
			} else {
				// Here, we assume that the value is int or numeric (the attachment ID).
				$value = absint( $value );

				return [
					'id'       => $value,
					'url'      => wp_get_attachment_url( $value ),
					'filename' => basename( get_attached_file( $value ) ),
				];
			}
		} elseif ( 'id' === $save_as ) {

			if ( is_string( $value ) && ! is_numeric( $value ) ) {
				// Here, we assume that the value is the URL.
				return attachment_url_to_postid( $value );
			} elseif ( is_array( $value ) && isset( $value['id'] ) ) {
				return absint( $value['id'] );
			}

			// Here, we assume that the value is int or numeric (the attachment ID).
			return absint( $value );

		}

		// If we're reaching this point, then we're saving the URL.
		if ( is_array( $value ) && isset( $value['url'] ) ) {
			$value = $value['url'];
		} elseif ( is_numeric( $value ) ) {
			$value = absint( $value );
			$value = wp_get_attachment_url( $value );
		} else {
			$value = esc_url_raw( $value );
		}

		return $value;

	}

}
