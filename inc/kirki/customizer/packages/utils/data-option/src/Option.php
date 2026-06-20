<?php // phpcs:disable PHPCompatibility.FunctionDeclarations.NewClosure
/**
 * Option tweaks.
 *
 * @package   kirki-framework/data-option
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Data;

/**
 * Hooks and tweaks to allow Kirki saving Options instead of theme-mods.
 *
 * @since 1.0
 */
class Option {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function __construct() {
		add_filter( 'kirki_field_add_setting_args', [ $this, 'add_setting_args' ], 20, 2 );
		add_filter( 'kirki_field_add_control_args', [ $this, 'add_control_args' ], 20, 2 );
		add_filter( 'kirki_get_value', [ $this, 'kirki_get_value' ], 10, 4 );
	}

	/**
	 * Filters the value for an option.
	 *
	 * @access public
	 * @since 4.0
	 * @param mixed  $value   The value.
	 * @param string $option  The field-name.
	 * @param mixed  $default The default value.
	 * @param string $type    The option-type (theme_mod, option etc).
	 * @return mixed          Returns the field value.
	 */
	public function kirki_get_value( $value = '', $option = '', $default = '', $type = 'theme_mod' ) {

		if ( 'option' === $type ) {

			/**
			 * If the option doesn't contain a '[', then it's not a sub-item
			 * of another option. Get the option value and return it.
			 */
			if ( false === strpos( $option, '[' ) ) {
				return get_option( $option, $default );
			}

			/**
			 * If we got here then this is part of an option array.
			 * We need to get the 1st level, and then find the item inside that array.
			 */
			$parts = \explode( '[', $option );
			$value = get_option( $parts[0], [] );

			// If there's no value, return the default.
			if ( empty( $value ) ) {
				return $default;
			}

			foreach ( $parts as $key => $part ) {
				/**
				 * Skip the 1st item, it's already been dealt with
				 * when we got the value initially right before this loop.
				 */
				if ( 0 === $key ) {
					continue;
				}

				$part = str_replace( ']', '', $part );

				/**
				 * If the item exists in the value, then change $value to the item.
				 * This runs recursively for all parts until we get to the end.
				 */
				if ( is_array( $value ) && isset( $value[ $part ] ) ) {
					$value = $value[ $part ];
					continue;
				}

				/**
				 * If we got here, the item was not found in the value.
				 * We need to change the value accordingly depending on whether
				 * this is the last item in the loop or not.
				 */
				$value = ( isset( $parts[ $key + 1 ] ) ) ? [] : '';
			}
		}

		return $value;

	}

	/**
	 * Allow filtering the arguments.
	 *
	 * @since 0.1
	 * @param array                $args The arguments.
	 * @param WP_Customize_Manager $customizer The customizer instance.
	 * @return array                           Return the arguments.
	 */
	public function add_setting_args( $args, $customizer ) {

		// If this is not an option, early exit.
		if ( ! isset( $args['option_type'] ) || 'option' !== $args['option_type'] ) {
			return $args;
		}

		// Set "type" argument to option.
		$args['type'] = 'option';
		return $this->maybe_change_settings( $args );

	}

	/**
	 * Allow filtering the arguments.
	 *
	 * @since 0.1
	 * @param array                $args The arguments.
	 * @param WP_Customize_Manager $customizer The customizer instance.
	 * @return array                           Return the arguments.
	 */
	public function add_control_args( $args, $customizer ) {

		// If this is not an option, early exit.
		if ( ! isset( $args['option_type'] ) || 'option' !== $args['option_type'] ) {
			return $args;
		}

		return $this->maybe_change_settings( $args );

	}

	/**
	 * Change the settings argument.
	 *
	 * @access private
	 * @since 1.0
	 * @param array $args The arguments.
	 * @return array      Returns modified array with tweaks to the [settings] argument if needed.
	 */
	private function maybe_change_settings( $args ) {

		// Check if we have an option-name defined.
		if ( isset( $args['option_name'] ) ) {
			if ( empty( $args['option_name'] ) ) {
				return $args;
			}

			if ( isset( $args['settings'] ) && $args['settings'] && false !== strpos( $args['settings'], $args['option_name'] . '[' ) ) {
				return $args;
			}

			if ( false === strpos( $args['settings'], '[' ) ) {
				// ? Bagus: in line above, it's obvious that '[' is not found in $args['settings']. But why do we explode it using '[' here?
				$parts       = explode( '[', $args['settings'] );
				$final_parts = [ $args['option_name'] ];

				foreach ( $parts as $part ) {
					$final_parts[] = $part;
				}

				$args['settings'] = \implode( '][', $final_parts ) . ']';
				$args['settings'] = str_replace(
					$args['option_name'] . '][',
					$args['option_name'] . '[',
					$args['settings']
				);
			}
		}

		return $args;

	}
}
