<?php
/**
 * Registers scripts for WordPress Compatibility with versions prior to WP5.0
 *
 * @package   Kirki
 * @category  Core
 * @author    Themeum
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     0.1
 */

namespace Kirki\Compatibility;

use Kirki\URL;

/**
 * Adds scripts for backwards-compatibility
 *
 * @since 0.1
 */
class Scripts {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 0.1
	 */
	public function __construct() {
		global $wp_version;

		/**
		 * Check if the WordPress version is lower than 5.0
		 * If lower then we need to enqueue the backported scripts.
		 */
		if ( version_compare( $GLOBALS['wp_version'], '5.0', '<' ) ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ] );
			add_action( 'admin_register_scripts', [ $this, 'register_scripts' ] );
			add_action( 'customize_controls_enqueue_scripts', [ $this, 'register_scripts' ] );
		}
	}

	/**
	 * Enqueue missing WP scripts.
	 *
	 * @access public
	 * @since 0.1
	 * @return void
	 */
	public function register_scripts() {
		$folder_url = trailingslashit( URL::get_from_path( __DIR__ ) );
		wp_register_script( 'wp-polyfill', $folder_url . 'scripts/wp-polyfill.js', [], '7.0.0', false );
		wp_register_script( 'wp-hooks', $folder_url . 'scripts/hooks.js', [ 'wp-polyfill' ], '2.2.0', false );
		wp_register_script( 'wp-i18n', $folder_url . 'scripts/i18n.js', [ 'wp-polyfill' ], '3.3.0', false );
	}

}
