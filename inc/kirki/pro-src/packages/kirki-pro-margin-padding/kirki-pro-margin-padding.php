<?php
/**
 * Plugin Name: Kirki PRO Margin & Padding
 * Plugin URI:  https://www.themeum.com/
 * Description: Margin & padding control for Kirki Customizer Framework.
 * Version:     1.1
 * Author:      Themeum
 * Author URI:  https://themeum.com/
 * License:     GPL-3.0
 * License URI: https://oss.ninja/gpl-3.0?organization=Kirki%20Framework&project=control%20margin%20padding
 * Text Domain: kirki-pro-margin-padding
 * Domain Path: /languages
 *
 * @package kirki-pro-margin-padding
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'kirki_pro_load_margin_padding_control' ) ) {

	/**
	 * Load margin & padding control.
	 */
	function kirki_pro_load_margin_padding_control() {

		// Stop, if Kirki is not installed.
		if ( ! defined( 'KIRKI_VERSION' ) ) {
			return;
		}

		// Stop, if Kirki PRO (bundle) is already installed.
		if ( defined( 'KIRKI_PRO_VERSION' ) ) {
			return;
		}

		// Stop, if Kirki Pro Margin & Padding is already installed.
		if ( class_exists( '\Kirki\Pro\MarginPadding\Init' ) ) {
			return;
		}

		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_data = get_plugin_data( __FILE__ );

		define( 'KIRKI_PRO_MARGIN_PADDING_VERSION', $plugin_data['Version'] );
		define( 'KIRKI_PRO_MARGIN_PADDING_PLUGIN_FILE', __FILE__ );

		require_once __DIR__ . '/vendor/autoload.php';

		new \Kirki\Pro\MarginPadding\Init();

	}
	add_action( 'plugins_loaded', 'kirki_pro_load_margin_padding_control' );

}
