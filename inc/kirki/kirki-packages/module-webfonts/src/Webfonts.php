<?php
/**
 * Handles webfonts.
 *
 * @package kirki-framework/module-webfonts
 * @author Themeum
 * @copyright Copyright (c) 2023, Themeum
 * @license https://opensource.org/licenses/MIT
 * @since 1.0.0
 */

namespace Kirki\Module;

use Kirki\Compatibility\Values;
use Kirki\Compatibility\Kirki;
use Kirki\Module\Webfonts\Google;
use Kirki\Module\Webfonts\Embed;
use Kirki\Module\Webfonts\Async;

/**
 * The Webfonts object.
 */
class Webfonts {

	/**
	 * The Google object.
	 *
	 * @access protected
	 * @since 1.0.0
	 * @var \Kirki\Module\Webfonts\Google
	 */
	protected $fonts_google;

	/**
	 * An array of fields to be processed.
	 *
	 * @static
	 * @access public
	 * @since 1.0.0
	 * @var array
	 */
	public static $fields = [];

	/**
	 * The class constructor
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __construct() {
        add_action( 'kirki_field_init', [ $this, 'field_init' ], 10, 2 );
		add_action( 'wp_loaded', [ $this, 'run' ] );
	}

	/**
	 * Run on after_setup_theme.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function run() {
		$this->fonts_google = Google::get_instance();
		$this->init();
	}

	/**
	 * Init other objects depending on the method we'll be using.
	 *
	 * @access protected
	 * @since 1.0.0
	 */
	protected function init() {
		foreach ( array_keys( Kirki::$config ) as $config_id ) {
			if ( 'async' === $this->get_method() ) {
				new Async( $config_id, $this, $this->fonts_google );
			}
			new Embed( $config_id, $this, $this->fonts_google );
		}
	}

	/**
	 * Get the method we're going to use.
	 *
	 * @access public
	 * @since 1.0.0
	 * @deprecated in 3.0.36.
	 * @return string
	 */
	public function get_method() {
		return ( is_customize_preview() || is_admin() ) ? 'async' : 'embed';
	}

	/**
	 * Runs when a field gets added.
	 * Adds fields to this object so we can loop through them.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param array  $args   The field args.
	 * @param Object $object The field object.
	 * @return void
	 */
	public function field_init( $args, $object ) {
		if ( ! isset( $args['type'] ) && isset( $object->type ) ) {
			$args['type'] = $object->type;
		}

		if ( ! isset( $args['type'] ) || $args['type'] !== 'kirki-typography' ) {
			return;
		}

		// Use the settings ID as key:
		self::$fields[ $args['settings'] ] = $args;
	}


	/**
	 * Goes through all our fields and then populates the $this->fonts property.
	 *
	 * @access public
	 * @param string $config_id The config-ID.
	 */
	public function loop_fields( $config_id ) {
		foreach ( self::$fields as $field ) {
			if ( isset( $field['kirki_config'] ) && $config_id !== $field['kirki_config'] ) {
				continue;
			}

			$this->fonts_google->generate_google_font( $field );
		}
	}
}
