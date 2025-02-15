<?php
/**
 * Override field methods
 *
 * @package   kirki-framework/control-repeater
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Field;

use Kirki\Compatibility\Field;
use Kirki\Field\Upload;

/**
 * Field overrides.
 *
 * @since 1.0
 */
class Repeater extends Field {

	/**
	 * The field type.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-repeater';

	/**
	 * Used only on repeaters.
	 * Contains an array of the fields.
	 *
	 * @access protected
	 * @since 1.0
	 * @var array
	 */
	protected $fields = [];

	/**
	 * Sets the control type.
	 *
	 * @access protected
	 * @since 1.0
	 * @return void
	 */
	protected function set_type() {

		$this->type = 'repeater';

	}

	/**
	 * Sets the $transport
	 *
	 * @access protected
	 * @since 1.0
	 * @return void
	 */
	protected function set_transport() {

		// Force using refresh mode.
		// Currently the repeater control does not support postMessage.
		$this->transport = 'refresh';

	}


	/**
	 * Sets the $sanitize_callback
	 *
	 * @access protected
	 * @since 1.0
	 * @return void
	 */
	protected function set_sanitize_callback() {

		if ( empty( $this->sanitize_callback ) ) {
			$this->sanitize_callback = [ $this, 'sanitize' ];
		}

	}

	/**
	 * The sanitize method that will be used as a falback
	 *
	 * @access public
	 * @since 1.0
	 * @param string|array $value The control's value.
	 */
	public function sanitize( $value ) {

		// is the value formatted as a string?
		if ( is_string( $value ) ) {
			$value = rawurldecode( $value );
			$value = json_decode( $value, true );
		}

		// Nothing to sanitize if we don't have fields.
		if ( empty( $this->fields ) ) {
			return $value;
		}

		foreach ( $value as $row_id => $row_value ) {

			// Make sure the row is formatted as an array.
			if ( ! is_array( $row_value ) ) {
				$value[ $row_id ] = [];
				continue;
			}

			// Start parsing sub-fields in rows.
			foreach ( $row_value as $subfield_id => $subfield_value ) {

				// Make sure this is a valid subfield.
				// If it's not, then unset it.
				if ( ! isset( $this->fields[ $subfield_id ] ) ) {
					unset( $value[ $row_id ][ $subfield_id ] );
				}

				// Get the subfield-type.
				if ( ! isset( $this->fields[ $subfield_id ]['type'] ) ) {
					continue;
				}

				$subfield      = $this->fields[ $subfield_id ];
				$subfield_type = $subfield['type'];

				// Allow using a sanitize-callback on a per-field basis.
				if ( isset( $this->fields[ $subfield_id ]['sanitize_callback'] ) ) {
					$subfield_value = call_user_func( $this->fields[ $subfield_id ]['sanitize_callback'], $subfield_value );
				} else {

					switch ( $subfield_type ) {
						case 'image':
						case 'cropped_image':
						case 'upload':
							$save_as        = isset( $subfield['choices'] ) && isset( $subfield['choices']['save_as'] ) ? $subfield['choices']['save_as'] : 'url';
							$subfield_value = Upload::sanitize( $subfield_value, $save_as );

							break;
						case 'dropdown-pages':
							$subfield_value = (int) $subfield_value;
							break;
						case 'color':
							if ( $subfield_value ) {
								$subfield_value = \Kirki\Field\ReactColorful::sanitize( $subfield_value );
							}
							break;
						case 'text':
							$subfield_value = sanitize_text_field( $subfield_value );
							break;
						case 'url':
						case 'link':
							$subfield_value = esc_url_raw( $subfield_value );
							break;
						case 'email':
							$subfield_value = filter_var( $subfield_value, FILTER_SANITIZE_EMAIL );
							break;
						case 'tel':
							$subfield_value = sanitize_text_field( $subfield_value );
							break;
						case 'checkbox':
							$subfield_value = (bool) $subfield_value;
							break;
						case 'select':
							if ( isset( $this->fields[ $subfield_id ]['multiple'] ) ) {
								if ( true === $this->fields[ $subfield_id ]['multiple'] ) {
									$multiple = 2;
								}
								$multiple = (int) $this->fields[ $subfield_id ]['multiple'];
								if ( 1 < $multiple ) {
									$subfield_value = (array) $subfield_value;
									foreach ( $subfield_value as $sub_subfield_key => $sub_subfield_value ) {
										$subfield_value[ $sub_subfield_key ] = sanitize_text_field( $sub_subfield_value );
									}
								} else {
									$subfield_value = sanitize_text_field( $subfield_value );
								}
							}
							break;
						case 'radio':
						case 'radio-image':
							$subfield_value = sanitize_text_field( $subfield_value );
							break;
						case 'textarea':
							$subfield_value = html_entity_decode( wp_kses_post( $subfield_value ) );

					}
				}

				$value[ $row_id ][ $subfield_id ] = $subfield_value;
			}
		}

		return $value;

	}

}
