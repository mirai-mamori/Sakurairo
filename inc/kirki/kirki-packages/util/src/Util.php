<?php
/**
 * A utility class for Kirki.
 *
 * @package     Kirki
 * @category    Core
 * @author      Themeum
 * @copyright   Copyright (c) 2023, Themeum
 * @license    https://opensource.org/licenses/MIT
 * @since       3.0.9
 */

namespace Kirki\Util;

/**
 * Utility class.
 */
class Util {

	/**
	 * Fields containing variables.
	 *
	 * @static
	 * @access private
	 * @since 4.0
	 * @var array
	 */
	private $variables_fields = [];

	/**
	 * Constructor.
	 *
	 * @since 3.0.9
	 * @access public
	 */
	public function __construct() {
		add_filter( 'http_request_args', [ $this, 'http_request' ], 10, 2 );
		add_action( 'kirki_field_init', [ $this, 'field_init_variables' ], 10, 2 );
	}

	/**
	 * Determine if Kirki is installed as a plugin.
	 *
	 * @static
	 * @access public
	 * @since 3.0.0
	 * @return bool
	 */
	public static function is_plugin() {
		$is_plugin = false;
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude
		}

		// Get all plugins.
		$plugins = get_plugins();
		$_plugin = '';
		foreach ( $plugins as $plugin => $args ) {
			if ( ! $is_plugin && isset( $args['Name'] ) && ( 'Kirki' === $args['Name'] || 'Kirki Toolkit' === $args['Name'] ) ) {
				$is_plugin = true;
				$_plugin   = $plugin;
			}
		}

		// No need to proceed any further if Kirki wasn't found in the list of plugins.
		if ( ! $is_plugin ) {
			return false;
		}

		// Make sure the is_plugins_loaded function is loaded.
		include_once ABSPATH . 'wp-admin/includes/plugin.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude

		// Extra logic in case the plugin is installed but not activated.
		if ( $_plugin && is_plugin_inactive( $_plugin ) ) {
			return false;
		}
		return $is_plugin;
	}

	/**
	 * Add fields with variables to self::$variables_fields.
	 *
	 * @access public
	 * @since 4.0
	 * @param array  $args   The field args.
	 * @param Object $object The field object.
	 * @return void
	 */
	public function field_init_variables( $args, $object ) {
		if ( isset( $args['variables'] ) ) {
			self::$variables_fields[] = $args;
		}
	}

	/**
	 * Build the variables.
	 *
	 * @static
	 * @access public
	 * @since 3.0.9
	 * @return array Formatted as array( 'variable-name' => value ).
	 */
	public static function get_variables() {

		$variables = [];
		$fields    = self::$variables_fields;

		/**
		 * Compatibility with Kirki v3.x API.
		 * If the Kirki class exists, check for fields inside it
		 * and add them to our fields array.
		 */
		if ( class_exists( '\Kirki\Compatibility\Kirki' ) ) {
			$fields = array_merge( \Kirki\Compatibility\Kirki::$fields, $fields );
		}

		// Loop through all fields.
		foreach ( $fields as $field ) {

			// Skip if this field doesn't have variables.
			if ( ! isset( $field['variables'] ) || ! $field['variables'] || empty( $field['variables'] ) ) {
				continue;
			}

			$option_type = ( isset( $field['option_type'] ) ) ? $field['option_type'] : 'theme_mod';
			$default     = ( isset( $field['default'] ) ) ? $field['default'] : '';
			$value       = apply_filters( 'kirki_get_value', get_theme_mod( $field['settings'], $default ), $field['settings'], $default, $option_type );

			// Loop through the array of variables.
			foreach ( $field['variables'] as $field_variable ) {

				// Is the variable ['name'] defined? If yes, then we can proceed.
				if ( isset( $field_variable['name'] ) ) {

					// Do we have a callback function defined? If not then set $variable_callback to false.
					$variable_callback = ( isset( $field_variable['callback'] ) && is_callable( $field_variable['callback'] ) ) ? $field_variable['callback'] : false;

					/**
					 * If we have a variable_callback defined then get the value of the option
					 * and run it through the callback function.
					 * If no callback is defined (false) then just get the value.
					 */
					$variables[ $field_variable['name'] ] = $value;
					if ( $variable_callback ) {
						$variables[ $field_variable['name'] ] = call_user_func( $field_variable['callback'], $value );
					}
				}
			}
		}

		// Pass the variables through a filter ('kirki_variable') and return the array of variables.
		return apply_filters( 'kirki_variable', $variables );
	}

	/**
	 * HTTP Request injection.
	 *
	 * @access public
	 * @since 3.0.0
	 * @param array  $request The request params.
	 * @param string $url     The request URL.
	 * @return array
	 */
	public function http_request( $request = [], $url = '' ) {

		// Early exit if installed as a plugin or not a request to wordpress.org,
		// or finally if we don't have everything we need.
		if (
			self::is_plugin() ||
			false === strpos( $url, 'wordpress.org' ) || (
				! isset( $request['body'] ) ||
				! isset( $request['body']['plugins'] ) ||
				! isset( $request['body']['translations'] ) ||
				! isset( $request['body']['locale'] ) ||
				! isset( $request['body']['all'] )
			)
		) {
			return $request;
		}

		$plugins = json_decode( $request['body']['plugins'], true );
		if ( ! isset( $plugins['plugins'] ) ) {
			return $request;
		}
		$exists = false;
		foreach ( $plugins['plugins'] as $plugin ) {
			if ( isset( $plugin['Name'] ) && 'Kirki Toolkit' === $plugin['Name'] ) {
				$exists = true;
			}
		}
		// Inject data.
		if ( ! $exists && defined( 'KIRKI_PLUGIN_FILE' ) ) {
			$plugins['plugins']['kirki/kirki.php'] = get_plugin_data( KIRKI_PLUGIN_FILE );
		}
		$request['body']['plugins'] = wp_json_encode( $plugins );
		return $request;
	}

	/**
	 * Returns the $wp_version.
	 *
	 * @static
	 * @access public
	 * @since 3.0.12
	 * @param string $context Use 'minor' or 'major'.
	 * @return int|string      Returns integer when getting the 'major' version.
	 *                         Returns string when getting the 'minor' version.
	 */
	public static function get_wp_version( $context = 'minor' ) {
		global $wp_version;

		// We only need the major version.
		if ( 'major' === $context ) {
			$version_parts = explode( '.', $wp_version );
			return $version_parts[0];
		}

		return $wp_version;
	}
}
