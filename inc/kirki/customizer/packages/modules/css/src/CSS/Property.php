<?php
/**
 * Handles CSS properties.
 * Extend this class in order to handle exceptions.
 *
 * @package     Kirki
 * @subpackage  Controls
 * @copyright   Copyright (c) 2023, Themeum
 * @license    https://opensource.org/licenses/MIT
 * @since       2.2.0
 */

namespace Kirki\Module\CSS;

/**
 * Output for CSS properties.
 */
class Property {

	/**
	 * The property we're modifying.
	 *
	 * @access protected
	 * @var string
	 */
	protected $property;

	/**
	 * The value
	 *
	 * @access protected
	 * @var string|array
	 */
	protected $value;

	/**
	 * Constructor.
	 *
	 * @access public
	 * @param string $property The CSS property we're modifying.
	 * @param mixed  $value    The value.
	 */
	public function __construct( $property, $value ) {
		$this->property = $property;
		$this->value    = $value;
		$this->process_value();
	}

	/**
	 * Modifies the value.
	 *
	 * @access protected
	 */
	protected function process_value() {

	}

	/**
	 * Gets the value.
	 *
	 * @access protected
	 */
	public function get_value() {
		return $this->value;
	}
}
