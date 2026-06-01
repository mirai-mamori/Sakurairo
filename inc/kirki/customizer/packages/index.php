<?php
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'kirki_init_controls' ) ) {

	/**
	 * Init Kirki controls.
	 */
	function kirki_init_controls() {

		$autoload_path = __DIR__ . '/autoload.php';

		if ( file_exists( $autoload_path ) ) {
			require_once $autoload_path;
		}

		// Load Pro namespace compatibility for backward compatibility.
		$compatibility_path = __DIR__ . '/compatibility/src/Pro_Namespace_Compatibility.php';
		if ( file_exists( $compatibility_path ) ) {
			require_once $compatibility_path;
		}

		$packages = array(
			__DIR__ . '/controls/headline-divider',
			__DIR__ . '/controls/input-slider',
			__DIR__ . '/controls/margin-padding',
			__DIR__ . '/controls/responsive',
			__DIR__ . '/controls/tabs',
		);

		foreach ( $packages as $package ) {
			$package_name = basename( $package );
			$init_class_name = str_ireplace( '-', ' ', $package_name );
			$init_class_name = ucwords( $init_class_name );
			$init_class_name = str_ireplace( ' ', '', $init_class_name );
			$init_class_name = '\\Kirki\\' . $init_class_name . '\\Init';

			$init_file_path = $package . '/' . basename( $package ) . '.php';

			if ( file_exists( $init_file_path ) ) {
				include_once $init_file_path;
			}

			if ( class_exists( $init_class_name ) ) {
				new $init_class_name();
			}
		}

	}
}

if ( ! function_exists( 'kirki_load_controls' ) ) {

	/**
	 * Load Kirki controls.
	 */
	function kirki_load_controls() {

		// Stop, if Kirki is not installed.
		if ( ! defined( 'KIRKI_VERSION' ) ) {
			return;
		}

		// Stop, if Kirki controls are already loaded.
		if ( defined( 'KIRKI_CONTROLS_VERSION' ) ) {
			return;
		}

		$plugin_file = defined( 'KIRKI_PLUGIN_FILE' ) ? KIRKI_PLUGIN_FILE : __FILE__;
		define( 'KIRKI_CONTROLS_VERSION', KIRKI_VERSION );
		define( 'KIRKI_CONTROLS_PLUGIN_FILE', $plugin_file );

		kirki_init_controls();

	}

	add_action( 'plugins_loaded', 'kirki_load_controls' );

}
