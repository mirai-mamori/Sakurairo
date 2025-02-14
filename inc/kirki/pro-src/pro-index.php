<?php
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'kirki_pro_init_controls' ) ) {

	/**
	 * Init Kirki PRO controls.
	 */
	function kirki_pro_init_controls() {

		$packages = array(
			__DIR__ . '/packages/kirki-pro-headline-divider',
			__DIR__ . '/packages/kirki-pro-input-slider',
			__DIR__ . '/packages/kirki-pro-margin-padding',
			__DIR__ . '/packages/kirki-pro-responsive',
			__DIR__ . '/packages/kirki-pro-tabs',
		);

		foreach ( $packages as $package ) {
			$init_class_name = str_ireplace( __DIR__ . '/packages/kirki-pro-', '', $package );
			$init_class_name = str_ireplace( '-', ' ', $init_class_name );
			$init_class_name = ucwords( $init_class_name );
			$init_class_name = str_ireplace( ' ', '', $init_class_name );
			$init_class_name = '\\Kirki\\Pro\\' . $init_class_name . '\\Init';

			$init_file_path = $package . '/' . basename( $package ) . '.php';
			$vendor_file    = $package . '/vendor/autoload.php';

			if ( file_exists( $init_file_path ) ) {
				include_once $init_file_path;
				include_once $vendor_file;
			}

			if ( class_exists( $init_class_name ) ) {
				new $init_class_name();
			}
		}

	}
}

if ( ! function_exists( 'kirki_pro_load_controls' ) ) {

	/**
	 * Load Kirki PRO controls.
	 */
	function kirki_pro_load_controls() {

		// Stop, if Kirki is not installed.
		if ( ! defined( 'KIRKI_VERSION' ) ) {
			return;
		}

		// Stop, if Kirki PRO is already installed.
		if ( defined( 'KIRKI_PRO_VERSION' ) ) {
			return;
		}

		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_data = get_plugin_data( __FILE__ );

		define( 'KIRKI_PRO_VERSION', $plugin_data['Version'] );
		define( 'KIRKI_PRO_PLUGIN_FILE', __FILE__ );

		kirki_pro_init_controls();

	}

	add_action( 'plugins_loaded', 'kirki_pro_load_controls' );

}
