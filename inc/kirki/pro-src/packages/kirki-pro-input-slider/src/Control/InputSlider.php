<?php
/**
 * The input slider control.
 *
 * Creates an input slider control.
 *
 * @package kirki-pro-input-slider
 */

namespace Kirki\Pro\Control;

use Kirki\Control\Base;
use Kirki\URL;

/**
 * Input slider control.
 *
 * @since 1.0
 */
class InputSlider extends Base {

	/**
	 * The control type.
	 *
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $type = 'kirki-input-slider';

	/**
	 * The control version.
	 *
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public static $control_ver = '0.1.0';

	/**
	 * Control's value unit.
	 *
	 * @var string
	 */
	public $value_unit = '';

	/**
	 * Control's value number.
	 *
	 * @var string
	 */
	public $value_number = 0;

	/**
	 * Control's constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 * @param string               $id      The control's ID.
	 * @param array                $args    The control's arguments.
	 */
	public function __construct( $wp_customize, $id, $args = array() ) {

		parent::__construct( $wp_customize, $id, $args );

		// If `unit` choice is defined.
		if ( ! empty( $this->choices['unit'] ) ) {
			$this->value_unit = $this->choices['unit'];
		}

		// If the value includes the unit, then replace the `value_unit` (set from choice) with unit from value.
		if ( ! is_numeric( $this->value() ) ) {
			$this->value_unit   = preg_replace( '/\d+/', '', $this->value() );
			$this->value_number = str_ireplace( $this->value_unit, '', $this->value() );
			$this->value_number = (float) $this->value_number;
		} else {
			$this->value_number = (float) $this->value();
		}

		// Set default choices.
		$this->choices = wp_parse_args(
			$this->choices,
			[
				'min'  => 0,
				'max'  => 100,
				'step' => 1,
			]
		);

		$this->choices['min']  = (float) $this->choices['min'];
		$this->choices['max']  = (float) $this->choices['max'];
		$this->choices['step'] = (float) $this->choices['step'];

		// Value number must not be less than min and must not be greater than max.
		$this->value_number = $this->value_number < $this->choices['min'] ? $this->choices['min'] : $this->value_number;
		$this->value_number = $this->value_number > $this->choices['max'] ? $this->choices['max'] : $this->value_number;

	}

	/**
	 * Enqueue control related styles/scripts.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function enqueue() {

		parent::enqueue();

		// Enqueue the style.
		wp_enqueue_style( 'kirki-control-input-slider', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.css' ), [], self::$control_ver );

		// Enqueue the script.
		wp_enqueue_script( 'kirki-control-input-slider', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.js' ), [ 'jquery', 'customize-controls', 'customize-base', 'react-dom' ], self::$control_ver, false );

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

		$this->json['value_number'] = $this->value_number;
		$this->json['value_unit']   = $this->value_unit;
		$this->json['value']        = $this->value_number . $this->value_unit;

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
