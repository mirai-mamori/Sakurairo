<?php
/**
 * A simple object containing properties for fonts.
 *
 * @package kirki-framework/module-webfonts
 * @author Themeum
 * @copyright Copyright (c) 2023, Themeum
 * @license https://opensource.org/licenses/MIT
 * @since 1.0.0
 */

namespace Kirki\Module\Webfonts;

use Kirki\GoogleFonts;

/**
 * The Fonts object.
 */
final class Fonts {

	/**
	 * The mode we'll be using to add google fonts.
	 * This is a todo item, not yet functional.
	 *
	 * @static
	 * @todo
	 * @access public
	 * @var string
	 */
	public static $mode = 'link';

	/**
	 * Holds a single instance of this object.
	 *
	 * @static
	 * @access private
	 * @var null|object
	 */
	private static $instance = null;

	/**
	 * An array of our google fonts.
	 *
	 * @static
	 * @access public
	 * @var array
	 */
	public static $google_fonts = null;

	/**
	 * The class constructor.
	 */
	private function __construct() {}

	/**
	 * Get the one, true instance of this class.
	 * Prevents performance issues since this is only loaded once.
	 *
	 * @return object Fonts
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Compile font options from different sources.
	 *
	 * @return array    All available fonts.
	 */
	public static function get_all_fonts() {
		$standard_fonts = self::get_standard_fonts();
		$google_fonts   = self::get_google_fonts();
		return apply_filters( 'kirki_fonts_all', array_merge( $standard_fonts, $google_fonts ) );
	}

	/**
	 * Return an array of standard websafe fonts.
	 *
	 * @return array    Standard websafe fonts.
	 */
	public static function get_standard_fonts() {
		$standard_fonts = [
			'serif'      => [
				'label' => 'Serif',
				'stack' => 'Georgia,Times,"Times New Roman",serif',
			],
			'sans-serif' => [
				'label' => 'Sans Serif',
				'stack' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
			],
			'monospace'  => [
				'label' => 'Monospace',
				'stack' => 'Monaco,"Lucida Sans Typewriter","Lucida Typewriter","Courier New",Courier,monospace',
			],
		];

		return apply_filters( 'kirki_fonts_standard_fonts', $standard_fonts );
	}

	/**
	 * Return an array of all available Google Fonts.
	 *
	 * @return array    All Google Fonts.
	 */
	public static function get_google_fonts() {
		if ( ! self::$google_fonts ) {
			$googlefonts        = new GoogleFonts();
			self::$google_fonts = $googlefonts->get_google_fonts();
		}
		return self::$google_fonts;
	}

	/**
	 * Returns an array of all available subsets.
	 *
	 * @static
	 * @access public
	 * @return array
	 */
	public static function get_google_font_subsets() {
		return [
			'cyrillic'     => 'Cyrillic',
			'cyrillic-ext' => 'Cyrillic Extended',
			'devanagari'   => 'Devanagari',
			'greek'        => 'Greek',
			'greek-ext'    => 'Greek Extended',
			'khmer'        => 'Khmer',
			'latin'        => 'Latin',
			'latin-ext'    => 'Latin Extended',
			'vietnamese'   => 'Vietnamese',
			'hebrew'       => 'Hebrew',
			'arabic'       => 'Arabic',
			'bengali'      => 'Bengali',
			'gujarati'     => 'Gujarati',
			'tamil'        => 'Tamil',
			'telugu'       => 'Telugu',
			'thai'         => 'Thai',
		];
	}

	/**
	 * Dummy function to avoid issues with backwards-compatibility.
	 * This is not functional, but it will prevent PHP Fatal errors.
	 *
	 * @static
	 * @access public
	 */
	public static function get_google_font_uri() {}

	/**
	 * Returns an array of all available variants.
	 *
	 * @static
	 * @access public
	 * @return array
	 */
	public static function get_all_variants() {
		return [
			'100'       => esc_html__( 'Ultra-Light 100', 'kirki' ),
			'100light'  => esc_html__( 'Ultra-Light 100', 'kirki' ),
			'100italic' => esc_html__( 'Ultra-Light 100 Italic', 'kirki' ),
			'200'       => esc_html__( 'Light 200', 'kirki' ),
			'200italic' => esc_html__( 'Light 200 Italic', 'kirki' ),
			'300'       => esc_html__( 'Book 300', 'kirki' ),
			'300italic' => esc_html__( 'Book 300 Italic', 'kirki' ),
			'400'       => esc_html__( 'Normal 400', 'kirki' ),
			'regular'   => esc_html__( 'Normal 400', 'kirki' ),
			'italic'    => esc_html__( 'Normal 400 Italic', 'kirki' ),
			'500'       => esc_html__( 'Medium 500', 'kirki' ),
			'500italic' => esc_html__( 'Medium 500 Italic', 'kirki' ),
			'600'       => esc_html__( 'Semi-Bold 600', 'kirki' ),
			'600bold'   => esc_html__( 'Semi-Bold 600', 'kirki' ),
			'600italic' => esc_html__( 'Semi-Bold 600 Italic', 'kirki' ),
			'700'       => esc_html__( 'Bold 700', 'kirki' ),
			'700italic' => esc_html__( 'Bold 700 Italic', 'kirki' ),
			'800'       => esc_html__( 'Extra-Bold 800', 'kirki' ),
			'800bold'   => esc_html__( 'Extra-Bold 800', 'kirki' ),
			'800italic' => esc_html__( 'Extra-Bold 800 Italic', 'kirki' ),
			'900'       => esc_html__( 'Ultra-Bold 900', 'kirki' ),
			'900bold'   => esc_html__( 'Ultra-Bold 900', 'kirki' ),
			'900italic' => esc_html__( 'Ultra-Bold 900 Italic', 'kirki' ),
		];
	}

	/**
	 * Determine if a font-name is a valid google font or not.
	 *
	 * @static
	 * @access public
	 * @param string $fontname The name of the font we want to check.
	 * @return bool
	 */
	public static function is_google_font( $fontname ) {
		if ( is_string( $fontname ) ) {
			$fonts = self::get_google_fonts();
			return isset( $fonts[ $fontname ] );
		}
		return false;
	}

	/**
	 * Gets available options for a font.
	 *
	 * @static
	 * @access public
	 * @return array
	 */
	public static function get_font_choices() {

		$fonts = self::get_all_fonts();
		$keys  = array_keys( $fonts );


		return array_combine( $keys, $keys );
	}
}
