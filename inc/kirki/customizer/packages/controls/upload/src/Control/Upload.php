<?php
/**
 * Kirki upload control.
 *
 * @package kirki-framework/control-upload
 * @since   1.0.1
 */

namespace Kirki\Control;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Upload control
 */
class Upload extends \WP_Customize_Media_Control {

	/**
	 * Control type.
	 *
	 * @since 1.0.1
	 * @var string
	 */
	public $type = 'upload';

	/**
	 * Media control mime type.
	 *
	 * @since 1.0.1
	 * @var string
	 */
	public $mime_type = '';

	/**
	 * Button labels.
	 *
	 * @since 1.0.1
	 * @var array
	 */
	public $button_labels = array();

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @since 1.0.1
	 *
	 * @uses WP_Customize_Media_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$value = $this->value();

		if ( ! empty( $value ) ) {
			if ( is_array( $value ) && isset( $value['id'] ) ) {
				$attachment_id = $value['id'];
			} elseif ( is_numeric( $value ) ) {
				$attachment_id = absint( $value );
			} elseif ( is_string( $value ) && ! is_numeric( $value ) ) {
				$attachment_id = attachment_url_to_postid( $value );
			}

			if ( ! empty( $attachment_id ) ) {
				$this->json['attachment'] = wp_prepare_attachment_for_js( $attachment_id );
			}
		}
	}

}
