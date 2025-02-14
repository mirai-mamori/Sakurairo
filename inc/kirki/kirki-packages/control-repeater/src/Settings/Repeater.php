<?php
/**
 * Repeater Customizer Setting.
 *
 * @package   kirki-framework/control-repeater
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Settings;

/**
 * Repeater Settings.
 */
class Repeater extends \WP_Customize_Setting {

	/**
	 * Constructor.
	 *
	 * Any supplied $args override class property defaults.
	 *
	 * @access public
	 * @since 1.0
	 * @param WP_Customize_Manager $manager The WordPress WP_Customize_Manager object.
	 * @param string               $id       A specific ID of the setting. Can be a theme mod or option name.
	 * @param array                $args     Setting arguments.
	 */
	public function __construct( $manager, $id, $args = [] ) {
		parent::__construct( $manager, $id, $args );

		// Will convert the setting from JSON to array. Must be triggered very soon.
		add_filter( "customize_sanitize_{$this->id}", [ $this, 'sanitize_repeater_setting' ], 10, 1 );
	}

	/**
	 * Fetch the value of the setting.
	 *
	 * @access public
	 * @since 1.0
	 * @return mixed The value.
	 */
	public function value() {
		return (array) parent::value();
	}

	/**
	 * Convert the JSON encoded setting coming from Customizer to an Array.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $value URL Encoded JSON Value.
	 * @return array
	 */
	public function sanitize_repeater_setting( $value ) {
		if ( ! is_array( $value ) ) {
			$value = json_decode( urldecode( $value ) );
		}

		if ( empty( $value ) || ! is_array( $value ) ) {
			$value = [];
		}

		// Make sure that every row is an array, not an object.
		foreach ( $value as $key => $val ) {
			$value[ $key ] = (array) $val;
			if ( empty( $val ) ) {
				unset( $value[ $key ] );
			}
		}

		// Reindex array.
		if ( is_array( $value ) ) {
			$value = array_values( $value );
		}

		return $value;
	}
}
