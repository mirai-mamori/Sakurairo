<?php
/**
 * Init the Kirki headlines & divider package.
 *
 * @package kirki-headline-divider
 * @since 1.0.0
 */

namespace Kirki\HeadlineDivider;

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

		$control_types['kirki-headline']        = 'Kirki\Control\Headline';
		$control_types['kirki-headline-toggle'] = 'Kirki\Control\HeadlineToggle';
		$control_types['kirki-divider']         = 'Kirki\Control\Divider';

		return $control_types;

	}

}
