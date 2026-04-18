<?php
/**
 * Handles CSS output for font-family.
 *
 * @package     Kirki
 * @subpackage  Controls
 * @copyright   Copyright (c) 2023, Themeum
 * @license    https://opensource.org/licenses/MIT
 * @since       2.2.0
 */

namespace Kirki\Module\CSS\Property;

use Kirki\Module\CSS\Property;
use Kirki\Module\Webfonts\Fonts;

/**
 * Output overrides.
 */
class Font_Family extends Property {

	/**
	 * Modifies the value.
	 *
	 * @access protected
	 */
	protected function process_value() {
		$google_fonts_array = Fonts::get_google_fonts();

		$family = $this->value;

		// Make sure the value is a string.
		// If not, then early exit.
		if ( ! is_string( $family ) ) {
			return;
		}

		// Hack for standard fonts.
		$family = str_replace( '&quot;', '"', $family );

		// Add double quotes if needed.
		if ( false !== strpos( $family, ' ' ) && false === strpos( $family, '"' ) ) {
			$this->value = '"' . $family . '"';
		}
		$this->value = html_entity_decode( $family, ENT_QUOTES );
	}
}
