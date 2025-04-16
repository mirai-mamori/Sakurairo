<?php
/**
 * Generates the styles for the frontend.
 * Handles the 'output' argument of fields
 *
 * @package     Kirki
 * @category    Core
 * @author      Themeum
 * @copyright   Copyright (c) 2023, Themeum
 * @license    https://opensource.org/licenses/MIT
 * @since       1.0
 */

namespace Kirki\Module\CSS;

use Kirki\Module\Webfonts\Fonts;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles CSS output.
 */
final class Generator {

	/**
	 * The instance of this class (singleton pattern).
	 *
	 * @static
	 * @access public
	 * @var null|object
	 */
	public static $instance = null;

	/**
	 * Settings.
	 *
	 * @static
	 * @access public
	 * @var null|string|array
	 */
	public static $settings = null;

	/**
	 * Output.
	 *
	 * @static
	 * @access public
	 * @var array
	 */
	public static $output = [];

	/**
	 * Callback.
	 *
	 * @static
	 * @access public
	 * @var null|string|array
	 */
	public static $callback = null;

	/**
	 * Option Name.
	 *
	 * @static
	 * @access public
	 * @var null|string
	 */
	public static $option_name = null;

	/**
	 * Field Type.
	 *
	 * @static
	 * @access public
	 * @var string
	 */
	public static $field_type = null;

	/**
	 * Google Fonts
	 *
	 * @static
	 * @access public
	 * @var array
	 */
	public static $google_fonts = null;

	/**
	 * CSS
	 *
	 * @static
	 * @access public
	 * @var string
	 */
	public static $css;

	/**
	 * Value
	 *
	 * @static
	 * @access public
	 * @var mixed
	 */
	public static $value = null;

	/**
	 * The class constructor.
	 */
	private function __construct() {
		if ( is_null( self::$google_fonts ) ) {
			self::$google_fonts = Fonts::get_google_fonts();
		}
	}

	/**
	 * Get a single instance of this class
	 *
	 * @return object
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Get the CSS for a field.
	 *
	 * @static
	 * @access public
	 * @param array $field The field.
	 * @return array
	 */
	public static function css( $field ) {

		// Set class vars.
		self::$settings   = $field['settings'];
		self::$callback   = isset( $field['sanitize_callback'] ) ? $field['sanitize_callback'] : '';
		self::$field_type = $field['type'];
		self::$field_type = ( isset( $field['choices'] ) && isset( $field['choices']['parent_type'] ) ) ? $field['choices']['parent_type'] : self::$field_type;
		self::$output     = $field['output'];

		$field['kirki_config'] = isset( $field['kirki_config'] ) ? $field['kirki_config'] : 'global';

		if ( ! is_array( self::$output ) ) {
			self::$output = [
				[
					'element'           => self::$output,
					'sanitize_callback' => null,
				],
			];
		}

		// Get the value of this field.
		$option_type  = ( isset( $field['option_type'] ) ) ? $field['option_type'] : 'theme_mod';
		$default      = ( isset( $field['default'] ) ) ? $field['default'] : '';
		$setting_name = $field['settings'];

		if ( 'option' === $option_type ) {
			if ( ! empty( $field['option_name'] ) && 0 !== stripos( $setting_name, $field['option_name'] . '[' ) ) {
				$setting_name = $field['option_name'] . '[' . $field['settings'] . ']';
			}
		}

		self::$value = apply_filters( 'kirki_get_value', get_theme_mod( $field['settings'], $default ), $setting_name, $default, $option_type );

		// Find the class that will handle the outpout for this field.
		$classname            = '\Kirki\Module\CSS\Output';
		$field_output_classes = apply_filters( 'kirki_output_control_classnames', [] );
		$field_output_classes = apply_filters( "kirki_{$field['kirki_config']}_output_control_classnames", $field_output_classes );
		if ( array_key_exists( self::$field_type, $field_output_classes ) ) {
			$classname = $field_output_classes[ self::$field_type ];
		}
		$obj = new $classname( $field['kirki_config'], self::$output, self::$value, $field );
		return $obj->get_styles();
	}

	/**
	 * Gets the array of generated styles and creates the minimized, inline CSS.
	 *
	 * @static
	 * @access public
	 * @param array $css The CSS definitions array.
	 * @return string    The generated CSS.
	 */
	public static function styles_parse( $css = [] ) {

		// Pass our styles from the kirki_styles_array filter.
		$css = apply_filters( 'kirki_styles_array', $css );

		// Process the array of CSS properties and produce the final CSS.
		$final_css = '';

		if ( ! is_array( $css ) || empty( $css ) ) {
			return '';
		}

		foreach ( $css as $media_query => $styles ) {
			$final_css .= ( 'global' !== $media_query ) ? $media_query . '{' : '';
			foreach ( $styles as $style => $style_array ) {
				$css_for_style = '';

				foreach ( $style_array as $property => $value ) {
					if ( is_string( $value ) && '' !== $value ) {
						$css_for_style .= $property . ':' . $value . ';';
					} elseif ( is_array( $value ) ) {
						foreach ( $value as $subvalue ) {
							if ( is_string( $subvalue ) && '' !== $subvalue ) {
								$css_for_style .= $property . ':' . $subvalue . ';';
							}
						}
					}
					$value = ( is_string( $value ) ) ? $value : '';
				}

				if ( '' !== $css_for_style ) {
					$final_css .= $style . '{' . $css_for_style . '}';
				}
			}

			$final_css .= ( 'global' !== $media_query ) ? '}' : '';
		}

		return $final_css;
	}

	/**
	 * Add prefixes if necessary.
	 *
	 * @param  array $css The CSS definitions array.
	 * @return array
	 */
	public static function add_prefixes( $css ) {
		if ( is_array( $css ) ) {
			foreach ( $css as $media_query => $elements ) {
				foreach ( $elements as $element => $style_array ) {
					foreach ( $style_array as $property => $value ) {

						// Add -webkit-* and -moz-*.
						if ( is_string( $property ) && in_array(
							$property,
							[
								'border-radius',
								'box-shadow',
								'box-sizing',
								'text-shadow',
								'transform',
								'background-size',
								'transition',
								'transition-property',
							],
							true
						) ) {
							unset( $css[ $media_query ][ $element ][ $property ] );
							$css[ $media_query ][ $element ][ '-webkit-' . $property ] = $value;
							$css[ $media_query ][ $element ][ '-moz-' . $property ]    = $value;
							$css[ $media_query ][ $element ][ $property ]              = $value;
						}

						// Add -ms-* and -o-*.
						if ( is_string( $property ) && in_array(
							$property,
							[
								'transform',
								'background-size',
								'transition',
								'transition-property',
							],
							true
						) ) {
							unset( $css[ $media_query ][ $element ][ $property ] );
							$css[ $media_query ][ $element ][ '-ms-' . $property ] = $value;
							$css[ $media_query ][ $element ][ '-o-' . $property ]  = $value;
							$css[ $media_query ][ $element ][ $property ]          = $value;
						}
					}
				}
			}
		}
		return $css;
	}
}
