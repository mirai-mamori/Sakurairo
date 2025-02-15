<?php
/**
 * Plugin Name: Kirki PRO - Headlines & Divider Control
 * Plugin URI:  https://themeum.com
 * Description: Headlines & divider control for Kirki Customizer Framework.
 * Version:     1.1
 * Author:      Themeum
 * Author URI:  https://themeum.com/
 * License:     GPL-3.0
 * License URI: https://oss.ninja/gpl-3.0?organization=Kirki%20Framework&project=control%20headline%20divider
 * Text Domain: kirki-pro-headline-divider
 * Domain Path: /languages
 *
 * @package kirki-pro-headline-divider
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'kirki_pro_load_headline_divider_control' ) ) {

	/**
	 * Load headline divider control.
	 */
	function kirki_pro_load_headline_divider_control() {

		// Stop, if Kirki is not installed.
		if ( ! defined( 'KIRKI_VERSION' ) ) {
			return;
		}

		// Stop, if Kirki PRO (bundle) is already installed.
		if ( defined( 'KIRKI_PRO_VERSION' ) ) {
			return;
		}

		// Stop, if Kirki Pro Headline Divider is already installed.
		if ( class_exists( '\Kirki\Pro\HeadlineDivider\Init' ) ) {
			return;
		}

		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_data = get_plugin_data( __FILE__ );

		define( 'KIRKI_PRO_HEADLINE_DIVIDER_VERSION', $plugin_data['Version'] );
		define( 'KIRKI_PRO_HEADLINE_DIVIDER_PLUGIN_FILE', __FILE__ );

		require_once __DIR__ . '/vendor/autoload.php';

		new \Kirki\Pro\HeadlineDivider\Init();

	}
	add_action( 'plugins_loaded', 'kirki_pro_load_headline_divider_control' );

}
