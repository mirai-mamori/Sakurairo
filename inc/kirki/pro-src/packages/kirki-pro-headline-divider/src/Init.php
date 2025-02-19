<?php
/**
 * Init the Kirki PRO's headlines & divider package.
 *
 * @package kirki-pro-headline-divider
 * @since 1.0.0
 */

namespace Kirki\Pro\HeadlineDivider;

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

		$control_types['kirki-headline']        = 'Kirki\Pro\Control\Headline';
		$control_types['kirki-headline-toggle'] = 'Kirki\Pro\Control\HeadlineToggle';
		$control_types['kirki-divider']         = 'Kirki\Pro\Control\Divider';

		return $control_types;

	}

}
