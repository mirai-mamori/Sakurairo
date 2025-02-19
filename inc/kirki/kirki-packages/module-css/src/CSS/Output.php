<?php
/**
 * Handles CSS output for fields.
 *
 * @package     Kirki
 * @subpackage  Controls
 * @copyright   Copyright (c) 2023, Themeum
 * @license    https://opensource.org/licenses/MIT
 * @since       2.2.0
 */

namespace Kirki\Module\CSS;

use Kirki\Compatibility\Values;
use Kirki\Compatibility\Kirki;

/**
 * Handles field CSS output.
 */
class Output {

	/**
	 * The field's `output` argument.
	 *
	 * @access protected
	 * @var array
	 */
	protected $output = [];

	/**
	 * An array of the generated styles.
	 *
	 * @access protected
	 * @var array
	 */
	protected $styles = [];

	/**
	 * The field.
	 *
	 * @access protected
	 * @var array
	 */
	protected $field = [];

	/**
	 * The value.
	 *
	 * @access protected
	 * @var string|array
	 */
	protected $value;

	/**
	 * The class constructor.
	 *
	 * @access public
	 * @param string       $config_id The config ID.
	 * @param array        $output    The output argument.
	 * @param string|array $value     The value.
	 * @param array        $field     The field.
	 */
	public function __construct( $config_id, $output, $value, $field ) {
		$this->value  = $value;
		$this->output = $output;
		$this->field  = $field;

		$this->parse_output();
	}

	/**
	 * If we have a sanitize_callback defined, apply it to the value.
	 *
	 * @param array        $output The output args.
	 * @param string|array $value  The value.
	 *
	 * @return string|array
	 */
	protected function apply_sanitize_callback( $output, $value ) {
		if ( isset( $output['sanitize_callback'] ) && null !== $output['sanitize_callback'] ) {

			// If the sanitize_callback is invalid, return the value.
			if ( ! is_callable( $output['sanitize_callback'] ) ) {
				return $value;
			}
			return call_user_func( $output['sanitize_callback'], $this->value );
		}
		return $value;
	}

	/**
	 * If we have a value_pattern defined, apply it to the value.
	 *
	 * @param array        $output The output args.
	 * @param string|array $value  The value.
	 * @return string|array
	 */
	protected function apply_value_pattern( $output, $value ) {
		if ( isset( $output['value_pattern'] ) && is_string( $output['value_pattern'] ) ) {
			if ( ! is_array( $value ) ) {
				$value = str_replace( '$', $value, $output['value_pattern'] );
			}
			if ( is_array( $value ) ) {
				foreach ( array_keys( $value ) as $value_k ) {
					if ( is_array( $value[ $value_k ] ) ) {
						continue;
					}
					if ( isset( $output['choice'] ) ) {
						if ( $output['choice'] === $value_k ) {
							$value[ $output['choice'] ] = str_replace( '$', $value[ $output['choice'] ], $output['value_pattern'] );
						}
						continue;
					}
					$value[ $value_k ] = str_replace( '$', $value[ $value_k ], $output['value_pattern'] );
				}
			}
			$value = $this->apply_pattern_replace( $output, $value );
		}
		return $value;
	}

	/**
	 * If we have a value_pattern defined, apply it to the value.
	 *
	 * @param array        $output The output args.
	 * @param string|array $value  The value.
	 * @return string|array
	 */
	protected function apply_pattern_replace( $output, $value ) {
		if ( isset( $output['pattern_replace'] ) && is_array( $output['pattern_replace'] ) ) {
			$option_type = ( isset( $this->field['option_type'] ) ) ? $this->field['option_type'] : 'theme_mod';
			$option_name = ( isset( $this->field['option_name'] ) ) ? $this->field['option_name'] : '';
			$options     = [];
			if ( $option_name ) {
				$options = ( 'site_option' === $option_type ) ? get_site_option( $option_name ) : get_option( $option_name );
			}
			foreach ( $output['pattern_replace'] as $search => $replace ) {
				$replacement = '';
				switch ( $option_type ) {
					case 'option':
						if ( is_array( $options ) ) {
							if ( $option_name ) {
								$subkey      = str_replace( [ $option_name, '[', ']' ], '', $replace );
								$replacement = ( isset( $options[ $subkey ] ) ) ? $options[ $subkey ] : '';
								break;
							}
							$replacement = ( isset( $options[ $replace ] ) ) ? $options[ $replace ] : '';
							break;
						}
						$replacement = get_option( $replace );
						break;
					case 'site_option':
						$replacement = ( is_array( $options ) && isset( $options[ $replace ] ) ) ? $options[ $replace ] : get_site_option( $replace );
						break;
					case 'user_meta':
						$user_id = get_current_user_id();
						if ( $user_id ) {
							$replacement = get_user_meta( $user_id, $replace, true );
						}
						break;
					default:
						$replacement = get_theme_mod( $replace );
						if ( ! $replacement ) {
							$replacement = Values::get_value( $this->field['kirki_config'], $replace );
						}
				}
				$replacement = ( false === $replacement ) ? '' : $replacement;
				if ( is_array( $value ) ) {
					foreach ( $value as $k => $v ) {
						$_val        = ( isset( $value[ $v ] ) ) ? $value[ $v ] : $v;
						$value[ $k ] = str_replace( $search, $replacement, $_val );
					}
					return $value;
				}
				$value = str_replace( $search, $replacement, $value );
			}
		}
		return $value;
	}

	/**
	 * Parses the output arguments.
	 * Calls the process_output method for each of them.
	 *
	 * @access protected
	 */
	protected function parse_output() {
		foreach ( $this->output as $output ) {
			$skip = false;

			// Apply any sanitization callbacks defined.
			$value = $this->apply_sanitize_callback( $output, $this->value );

			// Skip if value is empty.
			if ( '' === $this->value ) {
				$skip = true;
			}

			// No need to proceed this if the current value is the same as in the "exclude" value.
			if ( isset( $output['exclude'] ) && is_array( $output['exclude'] ) ) {
				foreach ( $output['exclude'] as $exclude ) {
					if ( is_array( $value ) ) {
						if ( is_array( $exclude ) ) {
							$diff1 = array_diff( $value, $exclude );
							$diff2 = array_diff( $exclude, $value );

							if ( empty( $diff1 ) && empty( $diff2 ) ) {
								$skip = true;
							}
						}
						// If 'choice' is defined check for sub-values too.
						// Fixes https://github.com/aristath/kirki/issues/1416.
						if ( isset( $output['choice'] ) && isset( $value[ $output['choice'] ] ) && $exclude == $value[ $output['choice'] ] ) { // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
							$skip = true;
						}
					}
					if ( $skip ) {
						continue;
					}

					// Skip if value is defined as excluded.
					if ( $exclude === $value || ( '' === $exclude && empty( $value ) ) ) {
						$skip = true;
					}
				}
			}
			if ( $skip ) {
				continue;
			}

			// Apply any value patterns defined.
			$value = $this->apply_value_pattern( $output, $value );

			if ( isset( $output['element'] ) && is_array( $output['element'] ) ) {
				$output['element'] = array_unique( $output['element'] );
				sort( $output['element'] );
				$output['element'] = implode( ',', $output['element'] );
			}

			$value = $this->process_value( $value, $output );

			if ( is_admin() ) {
				// In admin area, only output kirki-styles/kirki-inline-styles inside editing screen.
				if ( ! isset( $_GET['editor'] ) || 1 !== (int) $_GET['editor'] ) { // phpcs:ignore WordPress.Security.NonceVerification
					continue;
				}
			} else {
				// Check if this is a frontend style.
				if ( isset( $output['context'] ) && ! in_array( 'front', $output['context'], true ) ) {
					continue;
				}
			}

			/**
			 * Inside gutenberg editing screen, prepend `.editor-styles-wrapper` to the element
			 * so that it doesn't polute elements other than inside the editing content.
			 */
			if ( isset( $_GET['editor'] ) && 1 === (int) $_GET['editor'] ) {
				if ( isset( $output['element'] ) && ! empty( $output['element'] ) ) {

					if ( -1 < strpos( $output['element'], ',' ) ) {
						$elms = explode( ',', $output['element'] );

						foreach ( $elms as $index => $elm ) {
							if ( ! empty( $elm ) ) {
								$elms[ $index ] = '.editor-styles-wrapper ' . $elm;
								$elms[ $index ] = str_ireplace( '.editor-styles-wrapper :root', '.editor-styles-wrapper', $elms[ $index ] );
							}
						}

						$output['element'] = implode( ',', $elms );
					} else {
						$output['element'] = '.editor-styles-wrapper ' . $output['element'];
						$output['element'] = str_ireplace( '.editor-styles-wrapper :root', '.editor-styles-wrapper', $output['element'] );
					}
				}
			}

			$this->process_output( $output, $value );
		}
	}

	/**
	 * Parses an output and creates the styles array for it.
	 *
	 * @access protected
	 * @param array        $output The field output.
	 * @param string|array $value  The value.
	 *
	 * @return null
	 */
	protected function process_output( $output, $value ) {
		$output = apply_filters( 'kirki_output_item_args', $output, $value, $this->output, $this->field );

		if ( ! isset( $output['element'] ) || ! isset( $output['property'] ) ) {
			return;
		}

		$output['media_query'] = ( isset( $output['media_query'] ) ) ? $output['media_query'] : 'global';
		$output['prefix']      = ( isset( $output['prefix'] ) ) ? $output['prefix'] : '';
		$output['units']       = ( isset( $output['units'] ) ) ? $output['units'] : '';
		$output['suffix']      = ( isset( $output['suffix'] ) ) ? $output['suffix'] : '';

		// Properties that can accept multiple values.
		// Useful for example for gradients where all browsers use the "background-image" property
		// and the browser prefixes go in the value_pattern arg.
		$accepts_multiple = [
			'background-image',
			'background',
		];

		if ( in_array( $output['property'], $accepts_multiple, true ) ) {
			if ( isset( $this->styles[ $output['media_query'] ][ $output['element'] ][ $output['property'] ] ) && ! is_array( $this->styles[ $output['media_query'] ][ $output['element'] ][ $output['property'] ] ) ) {
				$this->styles[ $output['media_query'] ][ $output['element'] ][ $output['property'] ] = (array) $this->styles[ $output['media_query'] ][ $output['element'] ][ $output['property'] ];
			}

			$this->styles[ $output['media_query'] ][ $output['element'] ][ $output['property'] ][] = $output['prefix'] . $value . $output['units'] . $output['suffix'];
			return;
		}

		if ( is_string( $value ) || is_numeric( $value ) ) {
			$this->styles[ $output['media_query'] ][ $output['element'] ][ $output['property'] ] = $output['prefix'] . $this->process_property_value( $output['property'], $value ) . $output['units'] . $output['suffix'];
		}
	}

	/**
	 * Some CSS properties are unique.
	 * We need to tweak the value to make everything works as expected.
	 *
	 * @access protected
	 * @param string       $property The CSS property.
	 * @param string|array $value    The value.
	 *
	 * @return array
	 */
	protected function process_property_value( $property, $value ) {
		$properties = apply_filters(
			'kirki_output_property_classnames',
			[
				'font-family'         => '\Kirki\Module\CSS\Property\Font_Family',
				'background-image'    => '\Kirki\Module\CSS\Property\Background_Image',
				'background-position' => '\Kirki\Module\CSS\Property\Background_Position',
			]
		);
		if ( array_key_exists( $property, $properties ) ) {
			$classname = $properties[ $property ];
			$obj       = new $classname( $property, $value );
			return $obj->get_value();
		}
		return $value;
	}

	/**
	 * Returns the value.
	 *
	 * @access protected
	 * @param string|array $value The value.
	 * @param array        $output The field "output".
	 * @return string|array
	 */
	protected function process_value( $value, $output ) {
		if ( isset( $output['property'] ) ) {
			return $this->process_property_value( $output['property'], $value );
		}
		return $value;
	}

	/**
	 * Exploses the private $styles property to the world
	 *
	 * @access protected
	 * @return array
	 */
	public function get_styles() {
		return $this->styles;
	}
}
