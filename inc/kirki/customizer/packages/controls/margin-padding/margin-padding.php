<?php
/**
 * Plugin Name: Kirki Margin & Padding
 * Plugin URI:  https://www.themeum.com
 * Description: Margin & padding control for Kirki Customizer Framework.
 * Version:     1.1
 * Author:      Themeum
 * Author URI:  https://www.themeum.com
 * License:     GPL-3.0
 * License URI: https://oss.ninja/gpl-3.0?organization=Kirki%20Framework&project=control%20margin%20padding
 * Text Domain: kirki-margin-padding
 * Domain Path: /languages
 *
 * @package kirki-margin-padding
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'kirki_load_margin_padding_control' ) ) {

	/**
	 * Load margin & padding control.
	 */
	function kirki_load_margin_padding_control() {

		// Stop, if Kirki is not installed.
		if ( ! defined( 'KIRKI_VERSION' ) ) {
			return;
		}

		// Stop, if Kirki controls are already loaded.
		if ( defined( 'KIRKI_CONTROLS_VERSION' ) ) {
			return;
		}

		// Stop, if Kirki Margin & Padding is already installed.
		if ( class_exists( '\Kirki\MarginPadding\Init' ) ) {
			return;
		}

		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_data = get_plugin_data( __FILE__ );

		define( 'KIRKI_MARGIN_PADDING_VERSION', $plugin_data['Version'] );
		define( 'KIRKI_MARGIN_PADDING_PLUGIN_FILE', __FILE__ );

		new \Kirki\MarginPadding\Init();

	}
	add_action( 'plugins_loaded', 'kirki_load_margin_padding_control' );

}
