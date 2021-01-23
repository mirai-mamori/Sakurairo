<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Sanitize
 * Replace letter a to letter b
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'csf_sanitize_replace_a_to_b' ) ) {
  function csf_sanitize_replace_a_to_b( $value ) {
    return str_replace( 'a', 'b', $value );
  }
}

/**
 *
 * Sanitize title
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'csf_sanitize_title' ) ) {
  function csf_sanitize_title( $value ) {
    return sanitize_title( $value );
  }
}
