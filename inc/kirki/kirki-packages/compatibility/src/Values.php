<?php
/**
 * Helpers to get the values of a field.
 *
 * ! WARNING: PLEASE DO NOT USE THESE.
 * we only have these for backwards-compatibility purposes.
 * please use get_option() & get_theme_mod() instead.
 *
 * @package     Kirki
 * @category    Core
 * @author      Themeum
 * @copyright   Copyright (c) 2023, Themeum
 * @license    https://opensource.org/licenses/MIT
 * @since       1.0
 */

namespace Kirki\Compatibility;

/**
 * Wrapper class for static methods.
 */
class Values {

	/**
	 * Get the value of a field.
	 *
	 * @static
	 * @access public
	 * @param string $config_id The configuration ID. @see Kirki\Compatibility\Config.
	 * @param string $field_id  The field ID.
	 * @return string|array
	 */
	public static function get_value( $config_id = '', $field_id = '' ) {

		// Make sure value is defined.
		$value = '';

		// This allows us to skip the $config_id argument.
		// If we skip adding a $config_id, use the 'global' configuration.
		if ( ( '' === $field_id ) && '' !== $config_id ) {
			$field_id  = $config_id;
			$config_id = 'global';
		}

		// If $config_id is empty, set it to 'global'.
		$config_id = ( '' === $config_id ) ? 'global' : $config_id;

		// Fallback to 'global' if $config_id is not found.
		if ( ! isset( Kirki::$config[ $config_id ] ) ) {
			$config_id = 'global';
		}

		if ( 'theme_mod' === Kirki::$config[ $config_id ]['option_type'] ) {

			// We're using theme_mods so just get the value using get_theme_mod.
			$default_value = null;

			if ( isset( Kirki::$all_fields[ $field_id ] ) && isset( Kirki::$all_fields[ $field_id ]['default'] ) ) {
				$default_value = Kirki::$all_fields[ $field_id ]['default'];
			}

			$value = get_theme_mod( $field_id, $default_value );

			return apply_filters( 'kirki_values_get_value', $value, $field_id );
		}

		if ( 'option' === Kirki::$config[ $config_id ]['option_type'] ) {

			// We're using options.
			if ( '' !== Kirki::$config[ $config_id ]['option_name'] ) {
				// Options are serialized as a single option in the db.
				// We'll have to get the option and then get the item from the array.
				$options = get_option( Kirki::$config[ $config_id ]['option_name'] );

				if ( ! isset( Kirki::$all_fields[ $field_id ] ) && isset( Kirki::$all_fields[ Kirki::$config[ $config_id ]['option_name'] . '[' . $field_id . ']' ] ) ) {
					$field_id = Kirki::$config[ $config_id ]['option_name'] . '[' . $field_id . ']';
				}

				$setting_modified = str_replace( ']', '', str_replace( Kirki::$config[ $config_id ]['option_name'] . '[', '', $field_id ) );

				$default_value = ( isset( Kirki::$all_fields[ $field_id ] ) && isset( Kirki::$all_fields[ $field_id ]['default'] ) ) ? Kirki::$all_fields[ $field_id ]['default'] : '';
				$value         = ( isset( $options[ $setting_modified ] ) ) ? $options[ $setting_modified ] : $default_value;
				$value         = maybe_unserialize( $value );

				return apply_filters( 'kirki_values_get_value', $value, $field_id );
			}

			// Each option separately saved in the db.
			$value = get_option( $field_id, Kirki::$all_fields[ $field_id ]['default'] );

			return apply_filters( 'kirki_values_get_value', $value, $field_id );

		}

		return apply_filters( 'kirki_values_get_value', $value, $field_id );

	}

	/**
	 * Gets the value or fallsback to default.
	 *
	 * @static
	 * @access public
	 * @param array $field The field aruments.
	 * @return string|array
	 */
	public static function get_sanitized_field_value( $field ) {
		$value = $field['default'];
		if ( ! isset( $field['option_type'] ) || 'theme_mod' === $field['option_type'] ) {
			$value = get_theme_mod( $field['settings'], $field['default'] );
		} elseif ( isset( $field['option_type'] ) && 'option' === $field['option_type'] ) {
			if ( isset( $field['option_name'] ) && '' !== $field['option_name'] ) {
				$all_values     = get_option( $field['option_name'], [] );
				$sub_setting_id = str_replace( [ ']', $field['option_name'] . '[' ], '', $field['settings'] );
				if ( isset( $all_values[ $sub_setting_id ] ) ) {
					$value = $all_values[ $sub_setting_id ];
				}
			} else {
				$value = get_option( $field['settings'], $field['default'] );
			}
		}
		return $value;
	}
}
