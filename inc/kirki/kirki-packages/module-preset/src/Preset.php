<?php
/**
 * Automatic preset scripts calculation for Kirki controls.
 *
 * @package kirki-framework/module-preset
 * @author Themeum
 * @copyright Copyright (c) 2023, Themeum
 * @license https://opensource.org/licenses/MIT
 * @since 1.0.0
 */

namespace Kirki\Module;

use Kirki\URL;

/**
 * Adds styles to the customizer.
 */
class Preset {

	/**
	 * An array of preset controls and their arguments.
	 *
	 * @static
	 * @access private
	 * @since 1.0.0
	 * @var array
	 */
	private static $preset_controls = [];

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'customize_controls_print_footer_scripts', [ $this, 'customize_controls_print_footer_scripts' ] );
		add_filter( 'kirki_field_add_control_args', [ $this, 'field_add_control_args' ] );
	}

	/**
	 * Filter control arguments.
	 *
	 * @access public
	 * @since 1.0
	 * @param array $args The field arguments.
	 * @return array
	 */
	public function field_add_control_args( $args ) {
		if ( isset( $args['preset'] ) && is_array( $args['preset'] ) ) {
			self::$preset_controls[ $args['settings'] ] = $args['preset'];
		}
		return $args;
	}

	/**
	 * Enqueue scripts.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function customize_controls_print_footer_scripts() {
		wp_enqueue_script( 'kirki-preset', URL::get_from_path( __DIR__ . '/script.js' ), [ 'jquery' ], '1.0.0', false );
		wp_localize_script( 'kirki-preset', 'kirkiPresetControls', self::$preset_controls );
	}
}
