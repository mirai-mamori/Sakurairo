<?php
/**
 * Init Kirki PRO.
 *
 * @package kirki-pro
 * @since 1.0.0
 */

namespace Kirki\Pro;

/**
 * Class to init Kirki PRO.
 *
 * Only call this class when it's used as embedded package.
 * This class will not be called when it's used as a regular plugin.
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

		// Stop, if Kirki is not installed.
		if ( ! defined( 'KIRKI_VERSION' ) ) {
			return;
		}

		// Stop, if Kirki PRO is already installed.
		if ( defined( 'KIRKI_PRO_VERSION' ) ) {
			return;
		}

		kirki_pro_init_controls();
	}

}
