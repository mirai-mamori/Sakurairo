<?php
/**
 * Override field methods
 *
 * @package   kirki-framework/react-colorful
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Field;

use Kirki\URL;
use Kirki\Field;

/**
 * Field overrides.
 *
 * @since 1.0
 */
class ReactColorful extends Field {

	/**
	 * The field type.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-react-colorful';

	/**
	 * The control class-name.
	 *
	 * @access protected
	 * @since 0.1
	 * @var string
	 */
	protected $control_class = '\Kirki\Control\ReactColorful';

	/**
	 * Whether we should register the control class for JS-templating or not.
	 *
	 * @access protected
	 * @since 0.1
	 * @var bool
	 */
	protected $control_has_js_template = true;

	/**
	 * Additional logic for the field.
	 *
	 * @since 4.0.0
	 * @access protected
	 *
	 * @param array $args The field arguments.
	 */
	protected function init( $args ) {

		add_action( 'customize_preview_init', [ $this, 'enqueue_customize_preview_scripts' ] );
		add_filter( 'kirki_output_control_classnames', [ $this, 'output_control_classnames' ] );

	}

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
				$args['sanitize_callback'] = [ __CLASS__, 'sanitize' ];

				// If this is a hue control then its value should be an integer.
				if ( isset( $args['mode'] ) && 'hue' === $args['mode'] ) {
					$args['sanitize_callback'] = 'absint';
				}
			}

			// For postMessage/preview purpose, if property is not set, then set it to 'color'.
			if ( isset( $args['output'] ) && ! empty( $args['output'] ) && is_array( $args['output'] ) && ! isset( $args['output']['element'] ) ) {
				foreach ( $args['output'] as $index => $output ) {
					if ( ! isset( $output['property'] ) ) {
						if ( empty( $args['output'][ $index ] ) ) {
							$args['output'][ $index ] = [];
						}

						$args['output'][ $index ]['property'] = 'color';
					}
				}
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
			$args['type'] = 'kirki-react-colorful';
		}

		return $args;

	}

	/**
	 * Sanitize colors.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 *
	 * @param string|array $value The color.
	 * @return string|array
	 */
	public static function sanitize( $value ) {

		$sanitized_value = '';

		if ( is_string( $value ) ) {
			$sanitized_value = self::sanitize_color_string( $value );
		} elseif ( is_array( $value ) ) {
			if ( isset( $value['r'] ) || isset( $value['g'] ) || isset( $value['b'] ) ) {
				$sanitized_value = self::sanitize_color_array( $value, 'rgb' );
			} elseif ( isset( $value['h'] ) || isset( $value['s'] ) ) {
				if ( isset( $value['l'] ) ) {
					$sanitized_value = self::sanitize_color_array( $value, 'hsl' );
				} elseif ( isset( $value['v'] ) ) {
					$sanitized_value = self::sanitize_color_array( $value, 'hsv' );
				}
			}
		}

		return $sanitized_value;

	}

	/**
	 * Sanitize single color array.
	 *
	 * @param array  $color The provided color in array format.
	 * @param string $color_type The color type. Accepts: rgb, hsl, and hsv.
	 *
	 * @return array The sanitized color.
	 */
	public static function sanitize_color_array( $color, $color_type = 'rgb' ) {

		$keys = [ 'r', 'g', 'b' ];
		$mins = [ 0, 0, 0 ];
		$maxs = [ 255, 255, 255 ];

		if ( 'hsl' === $color_type || 'hsv' === $color_type ) {
			$keys    = [ 'h', 's', '' ];
			$keys[2] = isset( $color['v'] ) ? 'v' : 'l';

			$mins = [ 0, 0, 0 ];
			$maxs = [ 360, 100, 100 ];
		}

		$sanitized_color = [];

		$sanitized_color = [
			$keys[0] => isset( $color[ $keys[0] ] ) ? absint( $color[ $keys[0] ] ) : $mins[0],
			$keys[1] => isset( $color[ $keys[1] ] ) ? absint( $color[ $keys[1] ] ) : $mins[1],
			$keys[2] => isset( $color[ $keys[2] ] ) ? absint( $color[ $keys[2] ] ) : $mins[2],
		];

		$sanitized_color[ $keys[0] ] = $sanitized_color[ $keys[0] ] < $mins[0] ? $mins[0] : $sanitized_color[ $keys[0] ];
		$sanitized_color[ $keys[0] ] = $sanitized_color[ $keys[0] ] > $maxs[0] ? $maxs[0] : $sanitized_color[ $keys[0] ];

		$sanitized_color[ $keys[1] ] = $sanitized_color[ $keys[1] ] < $mins[1] ? $mins[1] : $sanitized_color[ $keys[1] ];
		$sanitized_color[ $keys[1] ] = $sanitized_color[ $keys[1] ] > $maxs[1] ? $maxs[1] : $sanitized_color[ $keys[1] ];

		$sanitized_color[ $keys[2] ] = $sanitized_color[ $keys[2] ] < $mins[2] ? $mins[2] : $sanitized_color[ $keys[2] ];
		$sanitized_color[ $keys[2] ] = $sanitized_color[ $keys[2] ] > $maxs[2] ? $maxs[2] : $sanitized_color[ $keys[2] ];

		if ( isset( $color['a'] ) ) {
			$sanitized_color['a'] = isset( $color['a'] ) ? filter_var( $color['a'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) : 1;
			$sanitized_color['a'] = $sanitized_color['a'] < 0 ? 0 : $sanitized_color['a'];
			$sanitized_color['a'] = $sanitized_color['a'] > 1 ? 1 : $sanitized_color['a'];
		}

		return $sanitized_color;

	}

	/**
	 * Sanitize color string.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 *
	 * @param string $value The color.
	 * @return string
	 */
	public static function sanitize_color_string( $value ) {

		$value = strtolower( $value );

		/**
		 * This pattern will check and match 3/6/8-character hex, rgb, rgba, hsl, hsla, hsv, and hsva colors.
		 *
		 * RGB regex:
		 *
		 * @link https://stackoverflow.com/questions/9585973/javascript-regular-expression-for-rgb-values#answer-9586045
		 *
		 * For testing it, you can use these links:
		 *
		 * @link https://regex101.com/
		 * @link https://regexr.com/
		 * @link https://www.regextester.com/
		 *
		 * How to test it?
		 *
		 * Paste the following code to the test field (of course without the asterisks and spaces in front of them):
		 * rgba(255, 255, 0, 0.9)
		 * rgb(255, 255, 0)
		 * #ff0
		 * #ffff00
		 * hsl(150, 25%, 25%)
		 * hsla(250, 25%, 25%, 0.7)
		 * hsv(125, 15%, 30%)
		 * hsva(125, 15%, 30%, 0.5)
		 *
		 * And then paste the regex `$pattern` below (without the single quote's start and end) to the regular expression box.
		 * Set the flag to use "global" and "multiline".
		 */
		$pattern = '/^(\#[\da-f]{3}|\#[\da-f]{6}|\#[\da-f]{8}|rgba\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)(,\s*(0\.\d+|1))\)|rgb\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)|hsla\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)(,\s*(0\.\d+|1))\)|hsl\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)\)|hsva\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)(,\s*(0\.\d+|1))\)|hsv\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)\))$/';

		preg_match( $pattern, $value, $matches );

		// Return the 1st match found.
		if ( isset( $matches[0] ) ) {
			if ( is_string( $matches[0] ) ) {
				return $matches[0];
			}

			if ( is_array( $matches[0] ) && isset( $matches[0][0] ) ) {
				return $matches[0][0];
			}
		}

		// If no match was found, return an empty string.
		return '';
	}

	/**
	 * Enqueue styles & scripts on 'customize_preview_init' action.
	 *
	 * @since 4.0.0
	 * @access public
	 */
	public function enqueue_customize_preview_scripts() {

		wp_enqueue_script( 'kirki-react-colorful', URL::get_from_path( dirname( dirname( __DIR__ ) ) ) . '/dist/preview.js', [ 'wp-hooks', 'customize-preview' ], $this->control_class::$control_ver, true );

	}

	/**
	 * Add output control class for react colorful control.
	 *
	 * @since 4.0.0
	 * @access public
	 *
	 * @param array $control_classes The existing control classes.
	 * @return array
	 */
	public function output_control_classnames( $control_classes ) {

		$control_classes['kirki-react-colorful'] = '\Kirki\Field\CSS\ReactColorful';

		return $control_classes;

	}

}
