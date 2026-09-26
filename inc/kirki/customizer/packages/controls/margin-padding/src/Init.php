<?php
/**
 * Init the Kirki margin & padding package.
 *
 * @package kirki-margin-padding
 * @since 1.0.0
 */

namespace Kirki\MarginPadding;

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

		$control_types['kirki-margin']  = 'Kirki\Control\Margin';
		$control_types['kirki-padding'] = 'Kirki\Control\Padding';

		return $control_types;

	}

}
