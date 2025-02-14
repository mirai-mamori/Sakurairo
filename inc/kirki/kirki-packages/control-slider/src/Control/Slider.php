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
 * Slider control.
 *
 * @since 1.0
 */
class Slider extends Base {

	/**
	 * The control type.
	 *
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $type = 'kirki-slider';

	/**
	 * The control version.
	 *
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public static $control_ver = '1.0.4';

	/**
	 * Enqueue control related styles/scripts.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function enqueue() {

		parent::enqueue();

		// Enqueue the style.
		wp_enqueue_style( 'kirki-control-slider', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.css' ), [], self::$control_ver );

		// Enqueue the script.
		wp_enqueue_script( 'kirki-control-slider', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.js' ), [ 'jquery', 'customize-controls', 'customize-base', 'react-dom' ], self::$control_ver, false );

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

		$this->json['choices'] = wp_parse_args(
			$this->json['choices'],
			[
				'min'  => 0,
				'max'  => 100,
				'step' => 1,
			]
		);

		if ( isset( $this->json['label'] ) ) {
			$this->json['label'] = html_entity_decode( $this->json['label'] );
		}

		if ( isset( $this->json['description'] ) ) {
			$this->json['description'] = html_entity_decode( $this->json['description'] );
		}

		$this->json['choices']['min']  = (float) $this->json['choices']['min'];
		$this->json['choices']['max']  = (float) $this->json['choices']['max'];
		$this->json['choices']['step'] = (float) $this->json['choices']['step'];

		$this->json['value'] = $this->json['value'] < $this->json['choices']['min'] ? $this->json['choices']['min'] : $this->json['value'];
		$this->json['value'] = $this->json['value'] > $this->json['choices']['max'] ? $this->json['choices']['max'] : $this->json['value'];
		$this->json['value'] = (float) $this->json['value'];

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
