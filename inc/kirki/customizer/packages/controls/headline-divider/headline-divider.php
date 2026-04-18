<?php
/**
 * Plugin Name: Kirki - Headlines & Divider Control
 * Plugin URI:  https://www.themeum.com
 * Description: Headlines & divider control for Kirki Customizer Framework.
 * Version:     1.1
 * Author:      Themeum
 * Author URI:  https://www.themeum.com
 * License:     GPL-3.0
 * License URI: https://oss.ninja/gpl-3.0?organization=Kirki%20Framework&project=control%20headline%20divider
 * Text Domain: kirki-headline-divider
 * Domain Path: /languages
 *
 * @package kirki-headline-divider
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'kirki_load_headline_divider_control' ) ) {

	/**
	 * Load headline divider control.
	 */
	function kirki_load_headline_divider_control() {

		// Stop, if Kirki is not installed.
		if ( ! defined( 'KIRKI_VERSION' ) ) {
			return;
		}

		// Stop, if Kirki controls are already loaded.
		if ( defined( 'KIRKI_CONTROLS_VERSION' ) ) {
			return;
		}

		// Stop, if Kirki Headline Divider is already installed.
		if ( class_exists( '\Kirki\HeadlineDivider\Init' ) ) {
			return;
		}

		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_data = get_plugin_data( __FILE__ );

		define( 'KIRKI_HEADLINE_DIVIDER_VERSION', $plugin_data['Version'] );
		define( 'KIRKI_HEADLINE_DIVIDER_PLUGIN_FILE', __FILE__ );

		new \Kirki\HeadlineDivider\Init();

	}
	add_action( 'plugins_loaded', 'kirki_load_headline_divider_control' );

}
