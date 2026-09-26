<?php
/**
 * Handles CSS output for multicolor fields.
 *
 * @package     Kirki
 * @subpackage  Controls
 * @copyright   Copyright (c) 2023, Themeum
 * @license    https://opensource.org/licenses/MIT
 * @since       2.2.0
 */

namespace Kirki\Field\CSS;

use Kirki\Module\CSS\Output;

/**
 * Output overrides.
 */
class Multicolor extends Output {

	/**
	 * Processes a single item from the `output` array.
	 *
	 * @access protected
	 * @param array $output The `output` item.
	 * @param array $value  The field's value.
	 */
	protected function process_output( $output, $value ) {

		foreach ( $value as $key => $sub_value ) {

			// If "element" is not defined, there's no reason to continue.
			if ( ! isset( $output['element'] ) ) {
				continue;
			}

			// If the "choice" is not the same as the $key in our loop, there's no reason to proceed.
			if ( isset( $output['choice'] ) && $key !== $output['choice'] ) {
				continue;
			}

			// If "property" is not defined, fallback to the $key.
			$property = ( ! isset( $output['property'] ) || empty( $output['property'] ) ) ? $key : $output['property'];

			// If "media_query" is not defined, use "global".
			if ( ! isset( $output['media_query'] ) || empty( $output['media_query'] ) ) {
				$output['media_query'] = 'global';
			}

			// If "suffix" is defined, add it to the value.
			$output['suffix'] = ( isset( $output['suffix'] ) ) ? $output['suffix'] : '';

			// Create the styles.
			$this->styles[ $output['media_query'] ][ $output['element'] ][ $property ] = $sub_value . $output['suffix'];

		}
	}
}
