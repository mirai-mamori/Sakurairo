<?php
/**
 * Gutenberg integration for Kirki.
 *
 * This class contains methods for integrating Kirki with
 * the new WordPress core editor, Gutenberg.  It provides
 * fonts and styles to be output by the theme.
 *
 * @package     Kirki
 * @category    Core
 * @author      Tim Elsass
 * @copyright   Copyright (c) 2023, Themeum
 * @license     https://opensource.org/licenses/MIT
 * @since       3.0.35
 */

namespace Kirki\Module;

use Kirki\Compatibility\Kirki;

/**
 * Wrapper class for static methods.
 *
 * @since 3.0.35
 */
class Editor_Styles {

	/**
	 * Configuration reference.
	 *
	 * @access public
	 * @since 3.0.35
	 * @var object $configs
	 */
	private $configs;

	/**
	 * Whether feature is enabled.
	 *
	 * @access public
	 * @since 3.0.35
	 * @var bool $enabled
	 */
	public $enabled;

	/**
	 * CSS Module reference.
	 *
	 * @access public
	 * @since 3.0.35
	 * @var object $modules_css
	 */
	public $modules_css;

	/**
	 * Webfonts Module reference.
	 *
	 * @access public
	 * @since 3.0.35
	 * @var object $modules_webfonts
	 */
	private $modules_webfonts;

	/**
	 * Google Fonts reference.
	 *
	 * @access public
	 * @since 3.0.35
	 * @var object $google_fonts
	 */
	private $google_fonts;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 3.0.0
	 */
	public function __construct() {
		add_action( 'admin_init', [ $this, 'init' ] );
	}

	/**
	 * Initialize Module.
	 *
	 * Sets class properties and add necessary hooks.
	 *
	 * @since 3.0.35
	 */
	public function init() {
		$this->set_configs();
		$this->set_enabled();
		$this->set_modules_css();
		$this->set_google_fonts();
		$this->set_modules_webfonts();
		$this->add_hooks();
	}

	/**
	 * Add hooks for Gutenberg editor integration.
	 *
	 * @access protected
	 * @since 3.0.35
	 */
	protected function add_hooks() {
		if ( ! $this->is_disabled() ) {
			add_action( 'enqueue_block_editor_assets', [ $this->modules_css, 'enqueue_styles' ], 999 );
			add_action( 'after_setup_theme', [ $this, 'add_theme_support' ], 999 );
		}
	}

	/**
	 * Add theme support for editor styles.
	 *
	 * This checks if theme has declared editor-styles support
	 * already, and if not present, declares it. Hooked late.
	 *
	 * @access public
	 * @since 3.0.35
	 */
	public function add_theme_support() {
		if ( true !== get_theme_support( 'editor-styles' ) ) {
			add_theme_support( 'editor-styles' );
		}
	}

	/**
	 * Helper method to check if feature is disabled.
	 *
	 * Feature can be disabled by KIRKI_NO_OUTPUT constant,
	 * gutenbeg_support argument, and disabled output argument.
	 *
	 * @access public
	 * @param array $args An array of arguments.
	 * @since 3.0.35
	 *
	 * @return bool $disabled Is gutenberg integration feature disabled?
	 */
	private function is_disabled( $args = [] ) {
		if ( defined( 'KIRKI_NO_OUTPUT' ) && true === KIRKI_NO_OUTPUT ) {
			return true;
		}

		/**
		 * We would prefer to use "KIRKI_GUTENBERG_OUTPUT" instead.
		 * For consistency however, we will use "KIRKI_NO_GUTENBERG_OUTPUT".
		 */
		if ( defined( 'KIRKI_NO_GUTENBERG_OUTPUT' ) && true === KIRKI_NO_GUTENBERG_OUTPUT ) {
			return true;
		}

		return false;
	}

	/**
	 * Set class property for $configs.
	 *
	 * @access private
	 * @since 3.0.35
	 */
	private function set_configs() {
		$this->configs = Kirki::$config;
		return $this->configs;
	}

	/**
	 * Set class property for $enabled.
	 *
	 * @access private
	 * @since 3.0.35
	 */
	private function set_enabled() {
		$this->enabled = ! $this->is_disabled();
	}

	/**
	 * Set class property for $modules_css.
	 *
	 * @access private
	 * @since 3.0.35
	 */
	private function set_modules_css() {
		$this->modules_css = new \Kirki\Module\CSS();
	}

	/**
	 * Set class property for $google_fonts.
	 *
	 * @access private
	 * @since 3.0.35
	 */
	private function set_google_fonts() {
		$this->google_fonts = \Kirki\Module\Webfonts\Google::get_instance();
	}

	/**
	 * Set class property for $modules_webfonts.
	 *
	 * @access private
	 * @since 3.0.35
	 */
	private function set_modules_webfonts() {
		$this->modules_webfonts = new \Kirki\Module\Webfonts();
	}
}
