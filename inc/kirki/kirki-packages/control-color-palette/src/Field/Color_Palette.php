<?php
/**
 * Override field methods.
 *
 * @package kirki-framework/control-slider
 * @license MIT (https://oss.ninja/mit?organization=Kirki%20Framework)
 * @since   1.0
 */

namespace Kirki\Field;

use Kirki\Field;

/**
 * Field overrides.
 *
 * @since 1.0
 */
class Color_Palette extends Field {

	/**
	 * The field type.
	 *
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $type = 'kirki-color-palette';

	/**
	 * The control class-name.
	 *
	 * @since 1.0
	 * @access protected
	 * @var string
	 */
	protected $control_class = '\Kirki\Control\Color_Palette';

	/**
	 * Whether we should register the control class for JS-templating or not.
	 *
	 * @since 1.0
	 * @access protected
	 * @var bool
	 */
	protected $control_has_js_template = true;

	/**
	 * Filter arguments before creating the setting.
	 *
	 * @param array                 $args The field arguments.
	 * @param \WP_Customize_Manager $wp_customize The customizer instance.
	 *
	 * @return array $args The maybe-filtered arguments.
	 */
	public function filter_setting_args( $args, $wp_customize ) {

		if ( $args['settings'] === $this->args['settings'] ) {
			$args = parent::filter_setting_args( $args, $wp_customize );

			// Set the sanitize_callback if none is defined.
			if ( ! isset( $args['sanitize_callback'] ) || ! $args['sanitize_callback'] ) {
				$args['sanitize_callback'] = [ __CLASS__, 'sanitize' ];
			}
		}

		return $args;

	}

	/**
	 * Filter arguments before creating the control.
	 *
	 * @param array                 $args The field arguments.
	 * @param \WP_Customize_Manager $wp_customize The customizer instance.
	 *
	 * @return array $args The maybe-filtered arguments.
	 */
	public function filter_control_args( $args, $wp_customize ) {

		if ( $args['settings'] === $this->args['settings'] ) {
			$args         = parent::filter_control_args( $args, $wp_customize );
			$args['type'] = 'kirki-color-palette';
		}

		return $args;

	}

	/**
	 * Sanitize colors.
	 *
	 * @static
	 * @access public
	 * @since 1.0.2
	 * @param string $value The color.
	 * @return string
	 */
	public static function sanitize( $value ) {

		/**
		 * This pattern will check and match 3/6/8-character hex, rgb, rgba, hsl, hsla, hsv, and hsva colors.
		 *
		 * RGB regex:
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

		\preg_match( $pattern, $value, $matches );

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

}
