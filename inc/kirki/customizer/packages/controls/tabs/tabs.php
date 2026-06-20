<?php
/**
 * Plugin Name: Kirki Tabs
 * Plugin URI:  https://www.themeum.com
 * Description: Tab control for Kirki Customizer Framework.
 * Version:     1.1
 * Author:      Themeum
 * Author URI:  https://www.themeum.com
 * License:     GPL-3.0
 * License URI: https://oss.ninja/gpl-3.0?organization=Kirki%20Framework&project=control%20tab
 * Text Domain: kirki-tabs
 * Domain Path: /languages
 *
 * @package kirki-tabs
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'kirki_load_tab_control' ) ) {

	/**
	 * Load tab control inside "plugins_loaded" hook.
	 * This is necessary to check if Kirki plugin is installed.
	 */
	function kirki_load_tab_control() {

		// Stop, if Kirki is not installed.
		if ( ! defined( 'KIRKI_VERSION' ) ) {
			return;
		}

		// Stop, if Kirki controls are already loaded.
		if ( defined( 'KIRKI_CONTROLS_VERSION' ) ) {
			return;
		}

		// Stop, if Kirki Tabs is already installed.
		if ( class_exists( '\Kirki\Tabs\Init' ) ) {
			return;
		}

		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_data = get_plugin_data( __FILE__ );

		define( 'KIRKI_TAB_VERSION', $plugin_data['Version'] );
		define( 'KIRKI_TAB_PLUGIN_FILE', __FILE__ );

		new \Kirki\Tabs\Init();

	}

	add_action( 'plugins_loaded', 'kirki_load_tab_control' );

}
