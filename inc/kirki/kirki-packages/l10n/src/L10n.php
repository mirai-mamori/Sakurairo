<?php
/**
 * Internationalization helper.
 *
 * Inspired by Justin Tadlock and the work he did with hybrid-core.
 *
 * @package   kirki-framework/l10n
 * @author    Themeum
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki;

/**
 * Handles translations
 */
class L10n {

	/**
	 * The plugin textdomain
	 *
	 * @access private
	 * @since 1.0
	 * @var string
	 */
	private $textdomain;

	/**
	 * The folder path containing translation files.
	 *
	 * @access private
	 * @since 1.0
	 * @var string
	 */
	private $languages_path;

	/**
	 * The theme textdomain
	 *
	 * @access private
	 * @since 1.0
	 * @var string
	 */
	private $theme_textdomain = '';

	/**
	 * The class constructor.
	 * Adds actions & filters to handle the rest of the methods.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $textdomain     The textdomain we want to use. Defaults to "kirki".
	 * @param string $languages_path The path to languages files.
	 */
	public function __construct( $textdomain = 'kirki', $languages_path = '' ) {

		$this->textdomain     = $textdomain;
		$this->languages_path = $languages_path;
		// This will only work if we're inside a plugin.
		add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );

		// If we got this far, then Kirki is embedded in a plugin.
		// We want the theme's textdomain to handle translations.
		add_filter( 'override_load_textdomain', [ $this, 'override_load_textdomain' ], 5, 3 );
	}

	/**
	 * Load the plugin textdomain
	 *
	 * @access public
	 * @since 1.0
	 */
	public function load_textdomain() {
		if ( null !== $this->get_path() ) {
			load_textdomain( $this->textdomain, $this->get_path() );
		}
		load_plugin_textdomain( $this->textdomain, false, $this->languages_path );
	}

	/**
	 * Gets the path to a translation file.
	 *
	 * @access protected
	 * @since 1.0
	 * @return string Absolute path to the translation file.
	 */
	protected function get_path() {
		$path_found = false;
		$found_path = null;
		foreach ( $this->get_paths() as $path ) {
			if ( $path_found ) {
				continue;
			}
			$path = wp_normalize_path( $path );
			if ( file_exists( $path ) ) {
				$path_found = true;
				$found_path = $path;
			}
		}
		return $found_path;
	}

	/**
	 * Returns an array of paths where translation files may be located.
	 *
	 * @access protected
	 * @since 1.0
	 * @return array
	 */
	protected function get_paths() {
		return [
			WP_LANG_DIR . '/' . $this->textdomain . '-' . get_locale() . '.mo',
			trailingslashit( $this->languages_path ) . $this->textdomain . '-' . get_locale() . '.mo',
		];
	}

	/**
	 * Allows overriding the textdomain from a theme.
	 *
	 * @access public
	 * @since 1.0
	 * @param bool   $override Whether to override the .mo file loading. Default false.
	 * @param string $domain   Text domain. Unique identifier for retrieving translated strings.
	 * @param string $mofile   Path to the MO file.
	 * @return bool
	 */
	public function override_load_textdomain( $override, $domain, $mofile ) {
		global $l10n;
		if ( isset( $l10n[ $this->get_theme_textdomain() ] ) ) {
			$l10n[ $this->textdomain ] = $l10n[ $this->get_theme_textdomain() ]; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
		}

		// Check if the domain is the one we have defined.
		if ( $this->textdomain === $domain ) {
			return true;
		}
		return $override;
	}

	/**
	 * Get the theme's textdomain.
	 *
	 * @access private
	 * @since 1.0
	 * @return string
	 */
	private function get_theme_textdomain() {
		if ( '' === $this->theme_textdomain ) {

			// Get the textdomain.
			$theme                  = wp_get_theme();
			$this->theme_textdomain = $theme->get( 'TextDomain' );

			// If no texdomain was found, use the template folder name.
			if ( ! $this->theme_textdomain ) {
				$this->theme_textdomain = get_template();
			}
		}
		return $this->theme_textdomain;
	}
}
