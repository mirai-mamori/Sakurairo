<?php
// phpcs:ignoreFile

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'kirki_get_option' ) ) {
	/**
	 * Get the value of a field.
	 * This is a deprecated function that we used when there was no API.
	 * Please use get_theme_mod() or get_option() instead.
	 * @see https://developer.wordpress.org/reference/functions/get_theme_mod/
	 * @see https://developer.wordpress.org/reference/functions/get_option/
	 *
	 * @return mixed
	 */
	function kirki_get_option( $option = '' ) {
		_deprecated_function( __FUNCTION__, '1.0.0', sprintf( esc_html__( '%1$s or %2$s', 'kirki' ), 'get_theme_mod', 'get_option' ) );
		return Kirki::get_option( '', $option );
	}
}

if ( ! function_exists( 'kirki_sanitize_hex' ) ) {
	function kirki_sanitize_hex( $color ) {
		_deprecated_function( __FUNCTION__, '1.0.0', 'ariColor::newColor( $color )->toCSS( \'hex\' )' );
		return Kirki_Color::sanitize_hex( $color );
	}
}

if ( ! function_exists( 'kirki_get_rgb' ) ) {
	function kirki_get_rgb( $hex, $implode = false ) {
		_deprecated_function( __FUNCTION__, '1.0.0', 'ariColor::newColor( $color )->toCSS( \'rgb\' )' );
		return Kirki_Color::get_rgb( $hex, $implode );
	}
}

if ( ! function_exists( 'kirki_get_rgba' ) ) {
	function kirki_get_rgba( $hex = '#fff', $opacity = 100 ) {
		_deprecated_function( __FUNCTION__, '1.0.0', 'ariColor::newColor( $color )->toCSS( \'rgba\' )' );
		return Kirki_Color::get_rgba( $hex, $opacity );
	}
}

if ( ! function_exists( 'kirki_get_brightness' ) ) {
	function kirki_get_brightness( $hex ) {
		_deprecated_function( __FUNCTION__, '1.0.0', 'ariColor::newColor( $color )->lightness' );
		return Kirki_Color::get_brightness( $hex );
	}
}

if ( ! function_exists( 'Kirki' ) ) {
	function Kirki() {
		return \Kirki\Compatibility\Framework::get_instance();
	}
}
