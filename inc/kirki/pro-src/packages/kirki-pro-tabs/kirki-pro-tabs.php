<?php
/**
 * Plugin Name: Kirki PRO Tabs
 * Plugin URI:  https://www.themeum.com/
 * Description: Tab control for Kirki Customizer Framework.
 * Version:     1.1
 * Author:      Themeum
 * Author URI:  https://themeum.com/
 * License:     GPL-3.0
 * License URI: https://oss.ninja/gpl-3.0?organization=Kirki%20Framework&project=control%20tab
 * Text Domain: kirki-pro-tabs
 * Domain Path: /languages
 *
 * @package kirki-pro-tabs
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'kirki_pro_load_tab_control' ) ) {

	/**
	 * Load tab control inside "plugins_loaded" hook.
	 * This is necessary to check if Kirki plugin is installed.
	 */
	function kirki_pro_load_tab_control() {

		// Stop, if Kirki is not installed.
		if ( ! defined( 'KIRKI_VERSION' ) ) {
			return;
		}

		// Stop, if Kirki PRO (bundle) is already installed.
		if ( defined( 'KIRKI_PRO_VERSION' ) ) {
			return;
		}

		// Stop, if Kirki Pro Tabs is already installed.
		if ( class_exists( '\Kirki\Pro\Tabs\Init' ) ) {
			return;
		}

		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_data = get_plugin_data( __FILE__ );

		define( 'KIRKI_PRO_TAB_VERSION', $plugin_data['Version'] );
		define( 'KIRKI_PRO_TAB_PLUGIN_FILE', __FILE__ );

		require_once __DIR__ . '/vendor/autoload.php';

		new \Kirki\Pro\Tabs\Init();

	}

	add_action( 'plugins_loaded', 'kirki_pro_load_tab_control' );

}
