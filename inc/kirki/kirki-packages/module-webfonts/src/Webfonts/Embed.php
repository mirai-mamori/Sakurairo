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

use Kirki\Module\Webfonts\Helper;

/**
 * Manages the way Google Fonts are enqueued.
 */
final class Embed {

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

		add_action( 'wp', [ $this, 'init' ], 9 );
		// add_filter( 'wp_resource_hints', [ $this, 'resource_hints' ], 10, 2 );
	}

	/**
	 * Init.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function init() {
		$this->populate_fonts();
		add_action( 'kirki_dynamic_css', [ $this, 'the_css' ] );
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
	public function populate_fonts() {

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
			$this->fonts_to_load[] = [
				'family'  => $font,
				'weights' => $weights,
			];
		}
	}

	/**
	 * Webfont Loader script for Google Fonts.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function the_css() {
		foreach ( $this->fonts_to_load as $font ) {

			$family  = str_replace( ' ', '+', trim( $font['family'] ) );
			$weights = join( ',', $font['weights'] );
			$url     = "https://fonts.googleapis.com/css?family={$family}:{$weights}&subset=cyrillic,cyrillic-ext,devanagari,greek,greek-ext,khmer,latin,latin-ext,vietnamese,hebrew,arabic,bengali,gujarati,tamil,telugu,thai&display=swap";

			$downloader = new Downloader();
			$contents   = $downloader->get_styles( $url );

			if ( $contents ) {
				/**
				 * Note to code reviewers:
				 *
				 * Though all output should be run through an escaping function, this is pure CSS
				 * and it is added on a call that has a PHP `header( 'Content-type: text/css' );`.
				 * No code, script or anything else can be executed from inside a stylesheet.
				 * For extra security we're using the wp_strip_all_tags() function here
				 * just to make sure there's no <script> tags in there or anything else.
				 */
				echo wp_strip_all_tags( $contents ); // phpcs:ignore WordPress.Security.EscapeOutput
			}
		}
	}

	/**
	 * Downloads font-files locally and uses the local files instead of the ones from Google's servers.
	 * This addresses any and all GDPR concerns, as well as firewalls that exist in some parts of the world.
	 *
	 * @access private
	 * @since 1.0.0
	 * @param string $css The CSS with original URLs.
	 * @return string     The CSS with local URLs.
	 */
	private function use_local_files( $css ) {
		preg_match_all( '/https\:.*?\.woff/', $css, $matches );

		$matches = array_shift( $matches );

		foreach ( $matches as $match ) {
			if ( 0 === strpos( $match, 'https://fonts.gstatic.com' ) ) {
				$new_url = Helper::download_font_file( $match );
				if ( $new_url ) {
					$css = str_replace( $match, $new_url, $css );
				}
			}
		}
		return $css;
	}
}
