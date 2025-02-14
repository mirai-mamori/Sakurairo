<?php
/**
 * Handle CSS output for margin control.
 *
 * @package Kirki
 * @subpackage Controls
 * @since 1.0.0
 */

namespace Kirki\Pro\Field\CSS;

use Kirki\Module\CSS\Output;

/**
 * Outpout overrides.
 */
class Margin extends Output {

	/**
	 * The field type.
	 *
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	protected $type = 'kirki-margin';

	/**
	 * Process a single item from the `output` array.
	 *
	 * @since 1.0
	 * @access protected
	 *
	 * @param array        $output The `output` item.
	 * @param array|string $value The field's value.
	 */
	protected function process_output( $output, $value ) {

		$property = str_ireplace( 'kirki-', '', $this->type );
		$unit     = isset( $this->field['choices'] ) && isset( $this->field['choices']['unit'] ) ? $this->field['choices']['unit'] : 'px';

		$output = wp_parse_args(
			$output,
			array(
				'media_query' => 'global',
				'element'     => '',
			)
		);

		// Stop if the value is not an array.
		if ( ! is_array( $value ) ) {
			return;
		}

		foreach ( $value as $position => $value ) {
			if ( '' !== $value ) {
				$value        = is_numeric( $value ) ? $value . $unit : $value;
				$css_property = $property . '-' . $position;

				$this->styles[ $output['media_query'] ][ $output['element'] ][ $css_property ] = $value;
			}
		}

		if ( 'kirki_pro_demo_responsive_margin[desktop]' === $this->field['settings'] ) {
			// error_log( print_r( get_theme_mod( 'kirki_pro_demo_responsive_margin[desktop]' ), true ) );
			// error_log( print_r( $this->styles, true ) );
			// error_log( print_r( $value, true ) );
		}

	}

}
