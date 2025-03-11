<?php
/**
 * Color Calculations class for Kirki
 * Initially built for the Shoestrap-3 theme and then tweaked for Kirki.
 *
 * @package Kirki
 * @category Core
 * @author Themeum
 * @copyright Copyright (c) 2023, Themeum
 * @license https://opensource.org/licenses/MIT
 * @since 1.0
 */

// phpcs:ignoreFile

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helper class for color manipulation.
 */
final class Kirki_Color extends ariColor {

	/**
	 * A proxy for the sanitize_color method.
	 *
	 * @param string|array $color The color.
	 * @param bool         $hash  Whether we want to include a hash (#) at the beginning or not.
	 * @return string             The sanitized hex color.
	 */
	 public static function sanitize_hex( $color = '#FFFFFF', $hash = true ) {
	 	if ( ! $hash ) {
	 		return ltrim( self::sanitize_color( $color, 'hex' ), '#' );
	 	}
	 	return self::sanitize_color( $color, 'hex' );
	}

	/**
	 * A proxy the sanitize_color method.
	 *
	 * @static
	 * @access public
	 * @param string $color The color.
	 * @return string
	 */
	public static function sanitize_rgba( $color ) {
		return self::sanitize_color( $color, 'rgba' );
	}

	/**
	 * Sanitize colors.
	 * Determine if the current value is a hex or an rgba color and call the appropriate method.
	 *
	 * @static
	 * @access public
	 * @since 0.8.5
	 * @param string|array $color The color.
	 * @param string       $mode  The mode to be used.
	 * @return string
	 */
	public static function sanitize_color( $color = '', $mode = 'auto' ) {
		if ( is_string( $color ) && 'transparent' == trim( $color ) ) {
			return 'transparent';
		}
		$obj = ariColor::newColor( $color );
		if ( 'auto' == $mode ) {
			$mode = $obj->mode;
		}
		return $obj->toCSS( $mode );
	}

	/**
	 * Gets the rgb value of a color.
	 *
	 * @static
	 * @access public
	 * @param string  $color   The color.
	 * @param boolean $implode Whether we want to implode the values or not.
	 * @return array|string
	 */
	public static function get_rgb( $color, $implode = false ) {
		$obj = ariColor::newColor( $color );
		if ( $implode ) {
			return $obj->toCSS( 'rgb' );
		}
		return array( $obj->red, $obj->green, $obj->blue );
	}

	/**
	 * A proxy for the sanitize_color method.
	 *
	 * @static
	 * @access public
	 * @param string|array $color The color to convert.
	 * @return string The hex value of the color.
	 */
	public static function rgba2hex( $color ) {
		return self::sanitize_color( $color, 'hex' );
	}

	/**
	 * Get the alpha channel from an rgba color.
	 *
	 * @static
	 * @access public
	 * @param string $color The rgba color formatted like rgba(r,g,b,a).
	 * @return int|float    The alpha value of the color.
	 */
	public static function get_alpha_from_rgba( $color ) {
		$obj = ariColor::newColor( $color );
		return $obj->alpha;
	}

	/**
	 * Gets the rgba value of the $color.
	 *
	 * @static
	 * @access public
	 * @param string    $color The hex value of a color.
	 * @param int|float $alpha Opacity level (0-1).
	 * @return string
	 */
	public static function get_rgba( $color = '#fff', $alpha = 1 ) {
		$obj = ariColor::newColor( $color );
		if ( 1 == $alpha ) {
			return $obj->toCSS( 'rgba' );
		}
		// Make sure that opacity is properly formatted.
		// Converts 1-100 values to 0-1.
		if ( $alpha > 1 || $alpha < -1 ) {
			// Divide by 100.
			$alpha /= 100;
		}
		// Get absolute value.
		$alpha = abs( $alpha );
		// Max 1.
		if ( 1 < $alpha ) {
			$alpha = 1;
		}
		$new_obj = $obj->getNew( 'alpha', $alpha );
		return $new_obj->toCSS( 'rgba' );
	}

	/**
	 * Strips the alpha value from an RGBA color string.
	 *
	 * @static
	 * @access public
	 * @param string $color The RGBA color string.
	 * @return string       The corresponding RGB string.
	 */
	public static function rgba_to_rgb( $color ) {
		$obj = ariColor::newColor( $color );
		return $obj->toCSS( 'rgb' );
	}

	/**
	 * Gets the brightness of the $hex color.
	 *
	 * @static
	 * @access public
	 * @param string $hex The hex value of a color.
	 * @return int        Value between 0 and 255.
	 */
	public static function get_brightness( $hex ) {
		$hex = self::sanitize_hex( $hex, false );

		// Returns brightness value from 0 to 255.
		return intval( ( ( hexdec( substr( $hex, 0, 2 ) ) * 299 ) + ( hexdec( substr( $hex, 2, 2 ) ) * 587 ) + ( hexdec( substr( $hex, 4, 2 ) ) * 114 ) ) / 1000 );
	}

	/**
	 * Adjusts brightness of the $hex color.
	 *
	 * @static
	 * @access public
	 * @param   string  $hex    The hex value of a color.
	 * @param   integer $steps  Should be between -255 and 255. Negative = darker, positive = lighter.
	 * @return  string          Returns hex color.
	 */
	public static function adjust_brightness( $hex, $steps ) {
		$hex = self::sanitize_hex( $hex, false );
		$steps = max( -255, min( 255, $steps ) );

		// Adjust number of steps and keep it inside 0 to 255.
		$red   = max( 0, min( 255, hexdec( substr( $hex, 0, 2 ) ) + $steps ) );
		$green = max( 0, min( 255, hexdec( substr( $hex, 2, 2 ) ) + $steps ) );
		$blue  = max( 0, min( 255, hexdec( substr( $hex, 4, 2 ) ) + $steps ) );

		$red_hex   = str_pad( dechex( $red ), 2, '0', STR_PAD_LEFT );
		$green_hex = str_pad( dechex( $green ), 2, '0', STR_PAD_LEFT );
		$blue_hex  = str_pad( dechex( $blue ), 2, '0', STR_PAD_LEFT );
		return self::sanitize_hex( $red_hex . $green_hex . $blue_hex );
	}

	/**
	 * Mixes 2 hex colors.
	 * The "percentage" variable is the percent of the first color.
	 * to be used it the mix. default is 50 (equal mix).
	 *
	 * @static
	 * @access public
	 * @param string|false $hex1       Color.
	 * @param string|false $hex2       Color.
	 * @param int          $percentage A value between 0 and 100.
	 * @return string                  Returns hex color.
	 */
	public static function mix_colors( $hex1, $hex2, $percentage ) {
		$hex1 = self::sanitize_hex( $hex1, false );
		$hex2 = self::sanitize_hex( $hex2, false );
		$red   = ( $percentage * hexdec( substr( $hex1, 0, 2 ) ) + ( 100 - $percentage ) * hexdec( substr( $hex2, 0, 2 ) ) ) / 100;
		$green = ( $percentage * hexdec( substr( $hex1, 2, 2 ) ) + ( 100 - $percentage ) * hexdec( substr( $hex2, 2, 2 ) ) ) / 100;
		$blue  = ( $percentage * hexdec( substr( $hex1, 4, 2 ) ) + ( 100 - $percentage ) * hexdec( substr( $hex2, 4, 2 ) ) ) / 100;
		$red_hex   = str_pad( dechex( $red ), 2, '0', STR_PAD_LEFT );
		$green_hex = str_pad( dechex( $green ), 2, '0', STR_PAD_LEFT );
		$blue_hex  = str_pad( dechex( $blue ), 2, '0', STR_PAD_LEFT );
		return self::sanitize_hex( $red_hex . $green_hex . $blue_hex );
	}

	/**
	 * Convert hex color to hsv.
	 *
	 * @static
	 * @access public
	 * @param string $hex The hex value of color 1.
	 * @return array Returns array( 'h', 's', 'v' ).
	 */
	public static function hex_to_hsv( $hex ) {
		$rgb = (array) (array) self::get_rgb( self::sanitize_hex( $hex, false ) );
		return self::rgb_to_hsv( $rgb );
	}

	/**
	 * Convert hex color to hsv.
	 *
	 * @static
	 * @access public
	 * @param string $color The rgb color to convert array( 'r', 'g', 'b' ).
	 * @return array Returns array( 'h', 's', 'v' ).
	 */
	public static function rgb_to_hsv( $color = array() ) {
		$var_r = ( $color[0] / 255 );
		$var_g = ( $color[1] / 255 );
		$var_b = ( $color[2] / 255 );
		$var_min = min( $var_r, $var_g, $var_b );
		$var_max = max( $var_r, $var_g, $var_b );
		$del_max = $var_max - $var_min;
		$h = 0;
		$s = 0;
		$v = $var_max;
		if ( 0 != $del_max ) {
			$s = $del_max / $var_max;
			$del_r = ( ( ( $var_max - $var_r ) / 6 ) + ( $del_max / 2 ) ) / $del_max;
			$del_g = ( ( ( $var_max - $var_g ) / 6 ) + ( $del_max / 2 ) ) / $del_max;
			$del_b = ( ( ( $var_max - $var_b ) / 6 ) + ( $del_max / 2 ) ) / $del_max;
			if ( $var_r == $var_max ) {
				$h = $del_b - $del_g;
			} elseif ( $var_g == $var_max ) {
				$h = ( 1 / 3 ) + $del_r - $del_b;
			} elseif ( $var_b == $var_max ) {
				$h = ( 2 / 3 ) + $del_g - $del_r;
			}
			if ( $h < 0 ) {
				$h++;
			}
			if ( $h > 1 ) {
				$h--;
			}
		}
		return array(
			'h' => round( $h, 2 ),
			's' => round( $s, 2 ),
			'v' => round( $v, 2 ),
		);
	}

	/**
	 * This is a very simple algorithm that works by summing up the differences between the three color components red, green and blue.
	 * A value higher than 500 is recommended for good readability.
	 *
	 * @static
	 * @access public
	 * @param string $color_1 The 1st color.
	 * @param string $color_2 The 2nd color.
	 * @return string
	 */
	public static function color_difference( $color_1 = '#ffffff', $color_2 = '#000000' ) {
		$color_1 = self::sanitize_hex( $color_1, false );
		$color_2 = self::sanitize_hex( $color_2, false );
		$color_1_rgb = self::get_rgb( $color_1 );
		$color_2_rgb = self::get_rgb( $color_2 );
		$r_diff = max( $color_1_rgb[0], $color_2_rgb[0] ) - min( $color_1_rgb[0], $color_2_rgb[0] );
		$g_diff = max( $color_1_rgb[1], $color_2_rgb[1] ) - min( $color_1_rgb[1], $color_2_rgb[1] );
		$b_diff = max( $color_1_rgb[2], $color_2_rgb[2] ) - min( $color_1_rgb[2], $color_2_rgb[2] );
		$color_diff = $r_diff + $g_diff + $b_diff;
		return $color_diff;
	}

	/**
	 * This function tries to compare the brightness of the colors.
	 * A return value of more than 125 is recommended.
	 * Combining it with the color_difference function above might make sense.
	 *
	 * @static
	 * @access public
	 * @param string $color_1 The 1st color.
	 * @param string $color_2 The 2nd color.
	 * @return string
	 */
	public static function brightness_difference( $color_1 = '#ffffff', $color_2 = '#000000' ) {
		$color_1 = self::sanitize_hex( $color_1, false );
		$color_2 = self::sanitize_hex( $color_2, false );
		$color_1_rgb = self::get_rgb( $color_1 );
		$color_2_rgb = self::get_rgb( $color_2 );
		$br_1 = ( 299 * $color_1_rgb[0] + 587 * $color_1_rgb[1] + 114 * $color_1_rgb[2] ) / 1000;
		$br_2 = ( 299 * $color_2_rgb[0] + 587 * $color_2_rgb[1] + 114 * $color_2_rgb[2] ) / 1000;
		return intval( abs( $br_1 - $br_2 ) );
	}

	/**
	 * Uses the luminosity to calculate the difference between the given colors.
	 * The returned value should be bigger than 5 for best readability.
	 *
	 * @static
	 * @access public
	 * @param string $color_1 The 1st color.
	 * @param string $color_2 The 2nd color.
	 * @return string
	 */
	public static function lumosity_difference( $color_1 = '#ffffff', $color_2 = '#000000' ) {
		$color_1 = self::sanitize_hex( $color_1, false );
		$color_2 = self::sanitize_hex( $color_2, false );
		$color_1_rgb = self::get_rgb( $color_1 );
		$color_2_rgb = self::get_rgb( $color_2 );
		$l1 = 0.2126 * pow( $color_1_rgb[0] / 255, 2.2 ) + 0.7152 * pow( $color_1_rgb[1] / 255, 2.2 ) + 0.0722 * pow( $color_1_rgb[2] / 255, 2.2 );
		$l2 = 0.2126 * pow( $color_2_rgb[0] / 255, 2.2 ) + 0.7152 * pow( $color_2_rgb[1] / 255, 2.2 ) + 0.0722 * pow( $color_2_rgb[2] / 255, 2.2 );
		$lum_diff = ( $l1 > $l2 ) ? ( $l1 + 0.05 ) / ( $l2 + 0.05 ) : ( $l2 + 0.05 ) / ( $l1 + 0.05 );
		return round( $lum_diff, 2 );
	}
}
