<?php
/**
 * Additional sanitization methods for controls.
 * These are used in the field's 'sanitize_callback' argument.
 *
 * @package     Kirki
 * @category    Core
 * @author      Themeum
 * @copyright   Copyright (c) 2023, Themeum
 * @license    https://opensource.org/licenses/MIT
 * @since       1.0
 */

namespace Kirki\Compatibility;

use Kirki\Field\Checkbox;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * A simple wrapper class for static methods.
 */
class Sanitize_Values {

	/**
	 * Checkbox sanitization callback.
	 *
	 * Sanitization callback for 'checkbox' type controls.
	 * This callback sanitizes `$value` as a boolean value, either TRUE or FALSE.
	 *
	 * Deprecated. Use \Kirki\Field\Checkbox::sanitize() instead.
	 *
	 * @static
	 * @access public
	 * @see \Kirki\Field\Checkbox::sanitize()
	 * @param bool|string $value Whether the checkbox is checked.
	 * @return bool Whether the checkbox is checked.
	 */
	public static function checkbox( $value ) {
		$obj = new Checkbox();

		// ! This sanitize function doesn't exist. A method exists check should be used before actually calling it.
		return (bool) $obj->sanitize( $value );
	}

	/**
	 * Sanitize number options.
	 *
	 * @static
	 * @access public
	 * @since 0.5
	 * @param int|float|double|string $value The value to be sanitized.
	 * @return integer|double|string
	 */
	public static function number( $value ) {
		return ( is_numeric( $value ) ) ? $value : intval( $value );
	}

	/**
	 * Drop-down Pages sanitization callback.
	 *
	 * - Sanitization: dropdown-pages
	 * - Control: dropdown-pages
	 *
	 * Sanitization callback for 'dropdown-pages' type controls. This callback sanitizes `$page_id`
	 * as an absolute integer, and then validates that $input is the ID of a published page.
	 *
	 * @see absint() https://developer.wordpress.org/reference/functions/absint/
	 * @see get_post_status() https://developer.wordpress.org/reference/functions/get_post_status/
	 *
	 * @param int                  $page_id    Page ID.
	 * @param WP_Customize_Setting $setting Setting instance.
	 * @return int|string Page ID if the page is published; otherwise, the setting default.
	 */
	public static function dropdown_pages( $page_id, $setting ) {

		// Ensure $input is an absolute integer.
		$page_id = absint( $page_id );

		// If $page_id is an ID of a published page, return it; otherwise, return the default.
		return ( 'publish' === get_post_status( $page_id ) ? $page_id : $setting->default );
	}

	/**
	 * Sanitizes css dimensions.
	 *
	 * @static
	 * @access public
	 * @since 2.2.0
	 * @param string $value The value to be sanitized.
	 * @return string
	 */
	public static function css_dimension( $value ) {

		// Trim it.
		$value = trim( $value );

		// If the value is round, then return 50%.
		if ( 'round' === $value ) {
			$value = '50%';
		}

		// If the value is empty, return empty.
		if ( '' === $value ) {
			return '';
		}

		// If auto, inherit or initial, return the value.
		if ( 'auto' === $value || 'initial' === $value || 'inherit' === $value || 'normal' === $value ) {
			return $value;
		}

		// Return empty if there are no numbers in the value.
		if ( ! preg_match( '#[0-9]#', $value ) ) {
			return '';
		}

		// If we're using calc() then return the value.
		if ( false !== strpos( $value, 'calc(' ) ) {
			return $value;
		}

		// The raw value without the units.
		$raw_value = self::filter_number( $value );
		$unit_used = '';

		// An array of all valid CSS units. Their order was carefully chosen for this evaluation, don't mix it up!!!
		$units = [ 'fr', 'rem', 'em', 'ex', '%', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ch', 'vh', 'vw', 'vmin', 'vmax' ];
		foreach ( $units as $unit ) {
			if ( false !== strpos( $value, $unit ) ) {
				$unit_used = $unit;
			}
		}

		// Hack for rem values.
		if ( 'em' === $unit_used && false !== strpos( $value, 'rem' ) ) {
			$unit_used = 'rem';
		}

		return $raw_value . $unit_used;
	}

	/**
	 * Filters numeric values.
	 *
	 * @static
	 * @access public
	 * @param string $value The value to be sanitized.
	 * @return int|float
	 */
	public static function filter_number( $value ) {
		return filter_var( $value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
	}

	/**
	 * Sanitize RGBA colors
	 *
	 * @static
	 * @since 0.8.5
	 * @param string $value The value to be sanitized.
	 * @return string
	 */
	public static function rgba( $value ) {
		$color = \ariColor::newColor( $value );
		return $color->toCSS( 'rgba' );
	}

	/**
	 * Sanitize colors.
	 *
	 * @static
	 * @since 0.8.5
	 * @param string $value The value to be sanitized.
	 * @return string
	 */
	public static function color( $value ) {

		// If the value is empty, then return empty.
		if ( '' === $value ) {
			return '';
		}

		// If transparent, then return 'transparent'.
		if ( is_string( $value ) && 'transparent' === trim( $value ) ) {
			return 'transparent';
		}

		// Instantiate the object.
		$color = \ariColor::newColor( $value );

		// Return a CSS value, using the auto-detected mode.
		return $color->toCSS( $color->mode );
	}

	/**
	 * DOES NOT SANITIZE ANYTHING.
	 *
	 * @static
	 * @since 0.5
	 * @param int|string|array $value The value to be sanitized.
	 * @return int|string|array
	 */
	public static function unfiltered( $value ) {
		return $value;
	}
}
