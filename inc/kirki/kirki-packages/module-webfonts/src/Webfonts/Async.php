<?php
/**
 * Adds the Webfont Loader to load fonts asyncronously.
 *
 * @package kirki-framework/module-webfonts
 * @author Themeum
 * @copyright Copyright (c) 2023, Themeum
 * @license https://opensource.org/licenses/MIT
 * @since 1.0.0
 */

namespace Kirki\Module\Webfonts;

use Kirki\URL;

/**3.
 * Manages the way Google Fonts are enqueued.
 */
final class Async {

	/**
	 * Only load the webfont script if this is true.
	 *
	 * @static
	 * @access public
	 * @since 1.0.0
	 * @var bool
	 */
	public static $load = false;

	/**
	 * The config ID.
	 *
	 * @access protected
	 * @since 1.0.0
	 * @var string
	 */
	protected $config_id;

	/**
	 * The \Kirki\Module\Webfonts object.
	 *
	 * @access protected
	 * @since 1.0.0
	 * @var object
	 */
	protected $webfonts;

	/**
	 * The \Kirki\Module\Webfonts\Google object.
	 *
	 * @access protected
	 * @since 1.0.0
	 * @var object
	 */
	protected $googlefonts;

	/**
	 * Fonts to load.
	 *
	 * @access protected
	 * @since 1.0.0
	 * @var array
	 */
	protected $fonts_to_load = [];

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param string $config_id   The config-ID.
	 * @param object $webfonts    The \Kirki\Module\Webfonts object.
	 * @param object $googlefonts The \Kirki\Module\Webfonts\Google object.
	 * @param array  $args        Extra args we want to pass.
	 */
	public function __construct( $config_id, $webfonts, $googlefonts, $args = [] ) {
		$this->config_id   = $config_id;
		$this->webfonts    = $webfonts;
		$this->googlefonts = $googlefonts;

		add_action( 'wp_head', [ $this, 'webfont_loader' ] );
		add_action( 'wp_head', [ $this, 'webfont_loader_script' ], 30 );

		// Add these in the dashboard to support editor-styles.
		add_action( 'admin_enqueue_scripts', [ $this, 'webfont_loader' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'webfont_loader_script' ], 30 );

		// add_filter( 'wp_resource_hints', [ $this, 'resource_hints' ], 10, 2 );
	}

	/**
	 * Add preconnect for Google Fonts.
	 *
	 * @access public
	 * @param array  $urls           URLs to print for resource hints.
	 * @param string $relation_type  The relation type the URLs are printed.
	 * @return array $urls           URLs to print for resource hints.
	 */
	public function resource_hints( $urls, $relation_type ) {
		$fonts_to_load = $this->googlefonts->fonts;

		if ( ! empty( $fonts_to_load ) && 'preconnect' === $relation_type ) {
			$urls[] = [
				'href' => 'https://fonts.gstatic.com',
				'crossorigin',
			];
		}
		return $urls;
	}

	/**
	 * Webfont Loader for Google Fonts.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function webfont_loader() {

		// Go through our fields and populate $this->fonts.
		$this->webfonts->loop_fields( $this->config_id );

		$this->googlefonts->fonts = apply_filters( 'kirki_enqueue_google_fonts', $this->googlefonts->fonts );

		// Goes through $this->fonts and adds or removes things as needed.
		$this->googlefonts->process_fonts();

		foreach ( $this->googlefonts->fonts as $font => $weights ) {
			foreach ( $weights as $key => $value ) {
				if ( 'italic' === $value ) {
					$weights[ $key ] = '400i';
				} else {
					$weights[ $key ] = str_replace( [ 'regular', 'bold', 'italic' ], [ '400', '', 'i' ], $value );
				}
			}
			$this->fonts_to_load[] = $font . ':' . join( ',', $weights ) . ':cyrillic,cyrillic-ext,devanagari,greek,greek-ext,khmer,latin,latin-ext,vietnamese,hebrew,arabic,bengali,gujarati,tamil,telugu,thai';
		}
		if ( ! empty( $this->fonts_to_load ) ) {
			self::$load = true;
		}

		global $wp_customize;
		if ( self::$load || $wp_customize || is_customize_preview() ) {
			wp_enqueue_script( 'webfont-loader', URL::get_from_path( dirname( __DIR__ ) . '/assets/scripts/vendor-typekit/webfontloader.js' ), [], '3.0.28', true );
		}
	}

	/**
	 * Webfont Loader script for Google Fonts.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function webfont_loader_script() {
		if ( ! empty( $this->fonts_to_load ) ) {
			wp_add_inline_script(
				'webfont-loader',
				'WebFont.load({google:{families:[\'' . join( '\', \'', $this->fonts_to_load ) . '\']}});',
				'after'
			);
		}
	}

	/**
	 * Set the $load property of this object.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param bool $load Set to false to disable loading.
	 * @return void
	 */
	public function set_load( $load ) {
		self::$load = $load;
	}
}
