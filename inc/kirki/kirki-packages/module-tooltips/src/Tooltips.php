<?php
/**
 * Injects tooltips to controls when the 'tooltip' argument is used.
 *
 * @package   Kirki
 * @category  Modules
 * @author    Themeum
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Module;

use Kirki\URL;

/**
 * Adds the tooltips.
 *
 * @since 1.0
 */
class Tooltips {

	/**
	 * An array containing field identifieds and their tooltips.
	 *
	 * @access private
	 * @since 1.0
	 * @var array
	 */
	private $tooltips_content = [];

	/**
	 * The class constructor
	 *
	 * @access public
	 * @since 1.0
	 */
	public function __construct() {

		add_action( 'customize_controls_print_footer_scripts', [ $this, 'customize_controls_print_footer_scripts' ] );
		add_filter( 'kirki_field_add_control_args', [ $this, 'filter_control_args' ], 10, 2 );

	}

	/**
	 * Enqueue scripts.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function customize_controls_print_footer_scripts() {

		wp_enqueue_style( 'kirki-tooltip', URL::get_from_path( dirname( __DIR__ ) . '/dist/control.css' ), [], '1.0.10' );

		wp_enqueue_script( 'kirki-tooltip', URL::get_from_path( dirname( __DIR__ ) . '/dist/control.js' ), [ 'jquery' ], '1.0.10', false );
		wp_localize_script( 'kirki-tooltip', 'kirkiTooltips', $this->tooltips_content );

	}

	/**
	 * Filter control args.
	 *
	 * @access public
	 * @since 1.0
	 * @param array                $args         The field arguments.
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 * @return array
	 */
	public function filter_control_args( $args, $wp_customize ) {
		if ( isset( $args['tooltip'] ) && $args['tooltip'] ) {
			$this->tooltips_content[ $args['settings'] ] = [
				'id'      => sanitize_key( $args['settings'] ),
				'content' => wp_kses_post( $args['tooltip'] ),
			];
		}
		return $args;
	}
}
