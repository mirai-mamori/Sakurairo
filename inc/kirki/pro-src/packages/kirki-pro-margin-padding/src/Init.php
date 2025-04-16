<?php
/**
 * Init the Kirki PRO's slider package.
 *
 * @package kirki-pro-margin-padding
 * @since 1.0.0
 */

namespace Kirki\Pro\MarginPadding;

/**
 * Manage the tabs package.
 */
class Init {

	/**
	 * The class constructor.
	 */
	public function __construct() {

		add_filter( 'kirki_control_types', [ $this, 'control_type' ] );

	}

	/**
	 * The control type.
	 *
	 * @param array $control_types The existing control types.
	 */
	public function control_type( $control_types ) {

		$control_types['kirki-margin']  = 'Kirki\Pro\Control\Margin';
		$control_types['kirki-padding'] = 'Kirki\Pro\Control\Padding';

		return $control_types;

	}

}
