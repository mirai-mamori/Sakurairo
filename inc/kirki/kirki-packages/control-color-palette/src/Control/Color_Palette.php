<?php
/**
 * The slider control.
 *
 * Creates a slider control.
 *
 * @package kirki-framework/control-slider
 * @license MIT (https://oss.ninja/mit?organization=Kirki%20Framework)
 * @since   1.0
 */

namespace Kirki\Control;

use Kirki\Control\Base;
use Kirki\URL;

/**
 * Color Palette control.
 *
 * @since 1.0
 */
class Color_Palette extends Base {

	/**
	 * The control type.
	 *
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $type = 'kirki-color-palette';

	/**
	 * The control version.
	 *
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public static $control_ver = '1.0';

	/**
	 * Enqueue control related styles/scripts.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function enqueue() {

		parent::enqueue();

		// Enqueue the style.
		wp_enqueue_style( 'kirki-color-palette-control', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.css' ), [], self::$control_ver );

		// Enqueue the script.
		wp_enqueue_script( 'kirki-color-palette-control', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.js' ), [ 'jquery', 'customize-controls', 'customize-base', 'react-dom' ], self::$control_ver, false );

	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 *
	 * @since 1.0
	 * @access public
	 */
	public function to_json() {

		parent::to_json();

		if ( isset( $this->json['label'] ) ) {
			$this->json['label'] = html_entity_decode( $this->json['label'] );
		}

		if ( isset( $this->json['description'] ) ) {
			$this->json['description'] = html_entity_decode( $this->json['description'] );
		}

		$this->json['value'] = strtolower( $this->json['value'] );

		$choices = $this->json['choices'];

		$this->json['choices'] = wp_parse_args(
			$choices,
			[
				'shape'  => 'square',
				'size'   => 28,
				'colors' => [],
			]
		);

		$this->json['choices']['colors'] = array_map( 'strtolower', $this->json['choices']['colors'] );

		if ( isset( $choices['style'] ) && ! empty( $choices['style'] ) ) {
			if ( ! isset( $choices['shape'] ) || empty( $choices['shape'] ) ) {
				$this->json['choices']['shape'] = $choices['style'];
			}

			unset( $this->json['choices']['style'] );
		}

		if ( ! is_numeric( $this->json['choices']['size'] ) ) {
			$this->json['choices']['size'] = 28;
		}

		$this->json['choices']['shape'] = 'circle' === $this->json['choices']['shape'] ? 'round' : $this->json['choices']['shape'];

	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding WP_Customize_Control::to_json().
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @since 1.0
	 */
	protected function content_template() {}

}
