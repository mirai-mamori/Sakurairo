<?php
/**
 * Controls handler
 *
 * @package     Kirki
 * @category    Core
 * @author      Themeum
 * @copyright   Copyright (c) 2023, Themeum
 * @license    https://opensource.org/licenses/MIT
 */

namespace Kirki\Compatibility;

/**
 * Our main Kirki\Control object
 */
class Control {

	/**
	 * The $wp_customize WordPress global.
	 *
	 * @access protected
	 * @var WP_Customize_Manager
	 */
	protected $wp_customize;

	/**
	 * An array of all available control types.
	 *
	 * @access protected
	 * @var array
	 */
	protected static $control_types = [];

	/**
	 * The class constructor.
	 * Creates the actual controls in the customizer.
	 *
	 * @access public
	 * @param array $args The field definition as sanitized in Kirki\Field.
	 */
	public function __construct( $args ) {

		// Set the $wp_customize property.
		global $wp_customize;
		if ( ! $wp_customize ) {
			return;
		}
		$this->wp_customize = $wp_customize;

		// Set the control types.
		$this->set_control_types();

		// Add the control.
		$this->add_control( $args );

	}

	/**
	 * Get the class name of the class needed to create tis control.
	 *
	 * @access private
	 * @param array $args The field definition as sanitized in Kirki\Field.
	 *
	 * @return         string   the name of the class that will be used to create this control.
	 */
	final function get_control_class_name( $args ) {

		// Set a default class name.
		$class_name = 'WP_Customize_Control';

		// Get the classname from the array of control classnames.
		if ( array_key_exists( $args['type'], self::$control_types ) ) {
			$class_name = self::$control_types[ $args['type'] ];
		}
		return $class_name;
	}

	/**
	 * Adds the control.
	 *
	 * @access protected
	 * @param array $args The field definition as sanitized in Kirki\Field.
	 */
	final protected function add_control( $args ) {

		// Get the name of the class we're going to use.
		$class_name = $this->get_control_class_name( $args );

		/**
		 * Allow filtering the arguments.
		 *
		 * @since 0.1
		 * @param array                $args   The arguments.
		 * @param WP_Customize_Manager $wp_customize The customizer instance.
		 * @return array                             Return the arguments.
		 */
		$args = apply_filters( 'kirki_field_add_control_args', $args, $this->wp_customize );

		// Add the control.
		$this->wp_customize->add_control( new $class_name( $this->wp_customize, $args['settings'], $args ) );

	}

	/**
	 * Sets the $control_types property.
	 * Makes sure the kirki_control_types filter is applied
	 * and that the defined classes actually exist.
	 * If a defined class does not exist, it is removed.
	 *
	 * @access private
	 */
	final function set_control_types() {

		// Early exit if this has already run.
		if ( ! empty( self::$control_types ) ) {
			return;
		}

		self::$control_types = apply_filters( 'kirki_control_types', [] );

		// Make sure the defined classes actually exist.
		foreach ( self::$control_types as $key => $classname ) {
			if ( ! class_exists( $classname ) ) {
				unset( self::$control_types[ $key ] );
			}
		}
	}
}
