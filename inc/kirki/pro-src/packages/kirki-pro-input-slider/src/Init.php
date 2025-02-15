<?php
/**
 * Init the Kirki PRO's slider package.
 *
 * @package kirki-pro-input-slider
 * @since 1.0.0
 */

namespace Kirki\Pro\InputSlider;

/**
 * Manage the tabs package.
 *
 * @since 1.0.0
 */
class Init {

	/**
	 * The class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		add_filter( 'kirki_control_types', [ $this, 'control_type' ] );

	}

	/**
	 * The control type.
	 *
	 * @since 1.0.0
	 *
	 * @param array $control_types The existing control types.
	 */
	public function control_type( $control_types ) {

		$control_types['kirki-input-slider'] = 'Kirki\Pro\Control\InputSlider';

		return $control_types;

	}

}
