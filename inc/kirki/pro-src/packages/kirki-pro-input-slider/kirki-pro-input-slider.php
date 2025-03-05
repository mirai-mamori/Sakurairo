<?php
/**
 * Plugin Name: Kirki PRO Input Slider
 * Plugin URI:  https://www.themeum.com/
 * Description: Input slider control for Kirki Customizer Framework.
 * Version:     1.1
 * Author:      Themeum
 * Author URI:  https://themeum.com/
 * License:     GPL-3.0
 * License URI: https://oss.ninja/gpl-3.0?organization=Kirki%20Framework&project=control%20input%20slider
 * Text Domain: kirki-pro-input-slider
 * Domain Path: /languages
 *
 * @package kirki-pro-input-slider
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'kirki_pro_load_input_slider_control' ) ) {

	/**
	 * Load input slider control.
	 */
	function kirki_pro_load_input_slider_control() {

		// Stop, if Kirki is not installed.
		if ( ! defined( 'KIRKI_VERSION' ) ) {
			return;
		}

		// Stop, if Kirki PRO (bundle) is already installed.
		if ( defined( 'KIRKI_PRO_VERSION' ) ) {
			return;
		}

		// Stop, if Kirki Pro Input Slider is already installed.
		if ( class_exists( '\Kirki\Pro\InputSlider\Init' ) ) {
			return;
		}

		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_data = get_plugin_data( __FILE__ );

		define( 'KIRKI_PRO_INPUT_SLIDER_VERSION', $plugin_data['Version'] );
		define( 'KIRKI_PRO_INPUT_SLIDER_PLUGIN_FILE', __FILE__ );

		require_once __DIR__ . '/vendor/autoload.php';

		new \Kirki\Pro\InputSlider\Init();

	}
	add_action( 'plugins_loaded', 'kirki_pro_load_input_slider_control' );

}
