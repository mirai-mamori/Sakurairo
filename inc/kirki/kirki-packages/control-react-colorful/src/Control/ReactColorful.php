<?php
/**
 * Customizer Control: kirki-react-colorful.
 *
 * @package   kirki-framework/control-react-colorful
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Control;

use Kirki\Control\Base;
use Kirki\URL;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The react-colorful control.
 *
 * @since 1.0
 */
class ReactColorful extends Base {

	/**
	 * The control type.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-react-colorful';

	/**
	 * The control version.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public static $control_ver = '1.0.15';

	/**
	 * The color mode.
	 *
	 * Used by 'mode' => 'alpha' argument.
	 *
	 * @access public
	 * @var string
	 */
	public $mode = '';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function enqueue() {

		parent::enqueue();

		// Enqueue the script.
		wp_enqueue_script( 'kirki-control-react-colorful', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.js' ), [ 'customize-controls', 'wp-element', 'jquery', 'customize-base', 'kirki-control-base' ], self::$control_ver, false );

		// Enqueue the style.
		wp_enqueue_style( 'kirki-control-react-colorful', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.css' ), [], self::$control_ver );

	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @access public
	 * @since 1.0
	 * @see WP_Customize_Control::to_json()
	 * @return void
	 */
	public function to_json() {

		// Get the basics from the parent class.
		parent::to_json();

		if ( isset( $this->json['label'] ) ) {
			$this->json['label'] = html_entity_decode( $this->json['label'] );
		}

		if ( isset( $this->json['description'] ) ) {
			$this->json['description'] = html_entity_decode( $this->json['description'] );
		}

		// Value.
		$this->json['value'] = empty( $this->value() ) ? '' : ( 'hue' === $this->mode ? absint( $this->value() ) : $this->value() );

		if ( is_string( $this->json['value'] ) ) {
			$this->json['value'] = strtolower( $this->json['value'] );
		}

		// Mode.
		$this->json['mode'] = $this->mode;

		// The label_style.
		$this->json['choices']['labelStyle'] = isset( $this->choices['label_style'] ) ? $this->choices['label_style'] : 'default';

		// Color swatches.
		$this->json['choices']['swatches'] = $this->color_swatches();

		// Form component (the value is bsaed on react-colorful's components).
		if ( isset( $this->choices['form_component'] ) ) {
			$this->json['choices']['formComponent'] = $this->choices['form_component'];
		}

		$this->remove_unused_json_props();

	}

	/**
	 * Remove un-used json properties.
	 *
	 * For consistency in JS, we converted some choices to use camelCase.
	 * To reduce the returned json size, we remove the original properties (which is using snake_case) from the JSON.
	 * But we keep them to stay in the choices array, so that they're still accessible.
	 *
	 * @return void
	 */
	public function remove_unused_json_props() {

		if ( isset( $this->json['choices']['label_style'] ) ) {
			unset( $this->json['choices']['label_style'] );
		}

		if ( isset( $this->choices['form_component'] ) ) {
			unset( $this->json['choices']['form_component'] );
		}

		if ( isset( $this->json['choices']['trigger_style'] ) ) {
			unset( $this->json['choices']['trigger_style'] );
		}

		if ( isset( $this->json['choices']['button_text'] ) ) {
			unset( $this->json['choices']['button_text'] );
		}

	}

	/**
	 * Get color swatches values.
	 *
	 * @return array The color swatches values.
	 */
	public function color_swatches() {

		$default_swatches = [
			'#000000',
			'#ffffff',
			'#dd3333',
			'#dd9933',
			'#eeee22',
			'#81d742',
			'#1e73be',
			'#8224e3',
		];

		$default_swatches = apply_filters( 'kirki_default_color_swatches', $default_swatches );

		$defined_swatches = isset( $this->choices['swatches'] ) && ! empty( $this->choices['swatches'] ) ? $this->choices['swatches'] : [];

		if ( empty( $defined_swatches ) ) {
			$defined_swatches = isset( $this->choices['palettes'] ) && ! empty( $this->choices['palettes'] ) ? $this->choices['palettes'] : [];
		}

		if ( ! empty( $defined_swatches ) ) {
			$swatches       = $defined_swatches;
			$total_swatches = count( $swatches );

			if ( $total_swatches < 8 ) {
				for ( $i = $total_swatches; $i <= 8; $i++ ) {
					$swatches[] = $total_swatches[ $i ];
				}
			}
		} else {
			$swatches = $default_swatches;
		}

		$swatches = apply_filters( 'kirki_color_swatches', $swatches );

		return $swatches;

	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @access protected
	 * @since 1.0
	 * @return void
	 */
	protected function content_template() {}
}
