<?php
/**
 * The margin control.
 *
 * Creates a margin control.
 *
 * @package kirki-pro-margin
 * @since   1.0
 */

namespace Kirki\Pro\Control;

use Kirki\Control\Base;
use Kirki\URL;

/**
 * Slider control.
 *
 * @since 1.0
 */
class Margin extends Base {

	/**
	 * The control type.
	 *
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $type = 'kirki-margin';

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
	public $value_unit = 'px';

	/**
	 * The default of control's default.
	 * This will be parsed with $args['default'] and without the unit.
	 *
	 * @var array
	 */
	public $default_array = [
		'top'    => '',
		'right'  => '',
		'bottom' => '',
		'left'   => '',
	];

	/**
	 * The default of control's value.
	 * This will be parsed with $this->value() and without the unit.
	 *
	 * @var array
	 */
	public $value_array = [
		'top'    => '',
		'right'  => '',
		'bottom' => '',
		'left'   => '',
	];

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

		$this->value_unit = strtolower( $this->value_unit );

		// Parse $args['default'] with $this->default_array.
		if ( ! empty( $args['default'] ) && is_array( $args['default'] ) ) {
			$this->default_array = wp_parse_args( $args['default'], $this->default_array );
		}

		$this->default_array = $this->remove_unit( $this->default_array );

		// Parse $this->value() with $this->value_array.
		if ( ! empty( $this->value() ) && is_array( $this->value() ) ) {
			$this->value_array = wp_parse_args( $this->value(), $this->value_array );
		}

		$this->value_array = $this->remove_unit( $this->value_array );

	}

	/**
	 * Remove unit from values.
	 *
	 * @param array $values The provided values.
	 * @return array
	 */
	public function remove_unit( $values ) {

		foreach ( $values as $position => $value ) {
			if ( '' !== $value ) {
				// Force $value to not using unit.
				if ( ! is_numeric( $value ) ) {
					$unit  = preg_replace( '/\d+/', '', $value );
					$value = str_ireplace( $unit, '', $value );
					$value = (float) $value;
				} else {
					$value = (float) $value;
				}
			}

			$values[ $position ] = $value;
		}

		return $values;

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
		wp_enqueue_style( 'kirki-control-margin-padding', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.css' ), [], self::$control_ver );

		// Enqueue the script.
		wp_enqueue_script( 'kirki-control-margin-padding', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.js' ), [ 'jquery', 'customize-controls', 'customize-base', 'react-dom' ], self::$control_ver, false );

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

		$this->json['valueArray']   = $this->value_array;
		$this->json['defaultArray'] = $this->value_array;
		$this->json['valueUnit']    = $this->value_unit;

		$this->json['value'] = [];

		foreach ( $this->json['valueArray'] as $position => $value ) {
			$this->json['value'][ $position ] = $value . $this->value_unit;
		}

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
