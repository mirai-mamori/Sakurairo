<?php
/**
 * Plugin Name: Kirki Customizer Framework
 * Plugin URI: https://themeum.com
 * Description: The Ultimate WordPress Customizer Framework
 * Author: Themeum
 * Author URI: https://themeum.com
 * Version: 5.1.1
 * Text Domain: kirki
 * Requires at least: 5.2
 * Requires PHP: 7.4
 *
 * @package Kirki
 * @category Core
 * @author Themeum
 * @copyright Copyright (c) 2023, Themeum
 * @license https://opensource.org/licenses/MIT
 * @since 1.0
 */

use Kirki\L10n;
use Kirki\Compatibility\Modules;
use Kirki\Compatibility\Framework;
use Kirki\Compatibility\Kirki;
use Kirki\URL;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// No need to proceed if Kirki already exists.
if ( class_exists( 'Kirki' ) ) {
	return;
}

if ( ! defined( 'KIRKI_PLUGIN_FILE' ) ) {
	define( 'KIRKI_PLUGIN_FILE', __FILE__ );
}

require_once __DIR__ . '/lib/class-aricolor.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude
require_once __DIR__ . '/lib/class-kirki-color.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude
require_once __DIR__ . '/kirki-composer/autoload.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude
require_once __DIR__ . '/inc/bootstrap.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude

if ( ! defined( 'KIRKI_VERSION' ) ) {
	define( 'KIRKI_VERSION', '5.1.1' );
}

if ( ! defined( 'KIRKI_PLUGIN_DIR' ) ) {
	define( 'KIRKI_PLUGIN_DIR', __DIR__ );
}

if ( ! defined( 'KIRKI_PLUGIN_URL' ) ) {
	define( 'KIRKI_PLUGIN_URL', URL::get_from_path( __DIR__ ) );
}

if ( ! function_exists( 'Kirki' ) ) {
	/**
	 * Returns an instance of the Kirki object.
	 */
	function kirki() {
		$kirki = Framework::get_instance();
		return $kirki;
	}
}

// Start Kirki.
global $kirki;
$kirki = kirki();

// Instantiate the modules.
$kirki->modules = new Modules();

// Instantiate classes.
new Kirki();
new L10n( 'kirki', __DIR__ . '/languages' );
new \Kirki\Settings\SetupSettings();

// ? Bagus: Do we really need to-reinclude this file? It was included above.
// Include the ariColor library.
require_once wp_normalize_path( dirname( __FILE__ ) . '/lib/class-aricolor.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude

// Add an empty config for global fields.
Kirki::add_config( '' ); // ? Bagus: what is this for? Adding empty config.

// ? Bagus: Do we really need this line? custom-config.php here is supposed to inside this plugin. Or is this just in case we need it in the future?
$custom_config_path = dirname( __FILE__ ) . '/custom-config.php';
$custom_config_path = wp_normalize_path( $custom_config_path );
if ( file_exists( $custom_config_path ) ) {
	require_once $custom_config_path; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude
}

// Add upgrade notifications.
// require_once wp_normalize_path( dirname( __FILE__ ) . '/upgrade-notifications.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude

/**
 * To enable tests, add this line to your wp-config.php file (or anywhere else):
 * define( 'KIRKI_TEST', true );
 *
 * Please note that the example.php file is not included in the wordpress.org distribution
 * and will only be included in dev versions of the plugin in the github repository.
 */
// if ( defined( 'KIRKI_TEST' ) && true === KIRKI_TEST && file_exists( dirname( __FILE__ ) . '/example.php' ) ) {
// 	include_once dirname( __FILE__ ) . '/example.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude
// }

require_once __DIR__ . '/pro-src/pro-index.php';