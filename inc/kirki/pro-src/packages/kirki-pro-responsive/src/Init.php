<?php
/**
 * Init the Kirki PRO's responsive package.
 *
 * @package kirki-pro-responsive
 * @since 1.0.0
 */

namespace Kirki\Pro\Responsive;

use Kirki\Pro\Field\Responsive;

/**
 * Manage the responsive package.
 */
class Init {

	/**
	 * Control types with horizontal layout.
	 *
	 * @var array
	 */
	private $horizontal_types = [
		'kirki-checkbox',
		'kirki-toggle',
		'kirki-react-colorful',
	];

	/**
	 * The class constructor.
	 */
	public function __construct() {

		add_action( 'customize_register', [ $this, 'register_control_type' ] );
		add_filter( 'kirki_control_types', [ $this, 'control_type' ] );

		add_filter( 'kirki_field_exclude_init', array( $this, 'exclude_init' ), 99999, 4 );
		add_action( 'kirki_field_custom_init', [ $this, 'field_init' ], 99999, 3 );

		/**
		 * We use 8 as the priority because we want this to run before "kirki_get_value" method
		 * in wp-content/plugins/kirki-dev/packages/kirki-framework/data-option/src/Option.php file.
		 */
		add_filter( 'kirki_get_value', [ $this, 'kirki_get_value' ], 8, 4 );

	}

	/**
	 * Register control type.
	 *
	 * @param WP_Customize_Manager $wp_customize Instance of WP_Customize_Manager.
	 */
	public function register_control_type( $wp_customize ) {

		$wp_customize->register_control_type( '\Kirki\Pro\Control\Responsive' );

	}

	/**
	 * The control type.
	 *
	 * @param array $control_types The existing control types.
	 */
	public function control_type( $control_types ) {

		$control_types['kirki-responsive'] = 'Kirki\Pro\Control\Responsive';

		return $control_types;

	}

	/**
	 * Parse the "default" argument.
	 * This method will format the "default" argument to contains the devices that wraps the default value.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args The field arguments.
	 * @return array $args The modifiled field arguments.
	 */
	public function parse_default_arg( $args ) {

		$has_responsive_default = true;

		if ( isset( $args['default'] ) ) {
			if ( is_array( $args['default'] ) ) {
				if ( ! isset( $args['default']['desktop'] ) && ! isset( $args['default']['tablet'] ) && ! isset( $args['default']['mobile'] ) ) {
					$has_responsive_default = false;
				}
			} else {
				$has_responsive_default = false;
			}
		}

		if ( ! $has_responsive_default ) {
			if ( isset( $args['default'] ) ) {
				$args['default'] = array(
					'desktop' => $args['default'],
				);
			} else {
				$args['default'] = array(
					'desktop' => '',
				);
			}
		}

		return $args;

	}

	/**
	 * Parse the output argument.
	 * This method will format the "output" argument to modify the "media_query" based on the targetted device.
	 * This method will be called inside of "default" argument loop when the control is using responsive mode.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $args The field arguments.
	 * @param string $device The targetted device.
	 *
	 * @return array $args The modified field arguments.
	 */
	public function parse_output_arg( $args, $device ) {

		if ( isset( $args['output'] ) && ! empty( $args['output'] ) ) {
			foreach ( $args['output'] as $index => $output ) {
				if ( isset( $output['media_query'] ) ) {
					if ( isset( $output['media_query'][ $device ] ) ) {
						$args['output'][ $index ]['media_query'] = $output['media_query'][ $device ];
					} else {
						// If current device is not set in the "media_query", then the output won't work.
						unset( $args['output'][ $index ] );
					}
				} else {
					// If "media_query" is not provided in the "output" arg, then the output won't work.
					unset( $args['output'][ $index ] );
				}
			}
		}

		return $args;

	}

	/**
	 * Exclude responsive field (field here means group of controls) from default field's init call.
	 *
	 * @see wp-content/plugins/kirki-dev/packages/kirki-framework/field/src/Field.php
	 * @since 1.0.0
	 *
	 * @param bool   $condition The existing condition.
	 * @param Object $field The field object.
	 * @param array  $args The field args.
	 *
	 * @return bool
	 */
	public function exclude_init( $condition, $field, $args ) {

		if ( isset( $args['responsive'] ) && $args['responsive'] ) {
			return true;
		}

		return $condition;

	}

	/**
	 * Replace the default field init with custom init.
	 *
	 * @see wp-content/plugins/kirki-dev/packages/kirki-framework/field/src/Field.php
	 * @since 1.0.0
	 *
	 * @param Object $field The field object.
	 * @param array  $args The Kirki field args.
	 * @param string $control_class The control class name if it exists.
	 */
	public function field_init( $field, $args, $control_class ) {

		// Stop if this field doesn't have the "responsive" argument.
		if ( ! isset( $args['responsive'] ) || ! $args['responsive'] ) {
			return;
		}

		$this->register_real_controls( $field, $args, $control_class );

	}

	/**
	 * Register the real controls.
	 *
	 * @since 1.0.0
	 *
	 * @param Object $field The field object.
	 * @param array  $args The Kirki field args.
	 * @param string $control_class The control class name if it exists.
	 */
	public function register_real_controls( $field, $args, $control_class = '' ) {

		$defaults = $this->parse_default_arg( $args );
		$defaults = $defaults['default'];
		$defaults = array_reverse( $defaults );
		$devices  = [];

		$inside_horizontal_layout = property_exists( $field, 'type' ) && in_array( $field->type, $this->horizontal_types, true ) ? true : false;

		foreach ( $defaults as $device => $value ) {
			array_push( $devices, $device );
		}

		$devices = array_reverse( $devices );

		$devices_control_id = 'kirki_responsive__' . $args['settings'];

		$loop_count = 0;

		foreach ( $defaults as $device => $value ) {
			$loop_count++;

			if ( ! $inside_horizontal_layout && 1 === $loop_count ) {
				$this->add_devices_control( $devices_control_id, $args, $devices, $inside_horizontal_layout );
			}

			$new_control_args = $args;

			$new_control_args['default']  = $value;
			$new_control_args['settings'] = $args['settings'] . '[' . $device . ']';
			$new_control_args['device']   = $device;

			if ( ! isset( $new_control_args['wrapper_attrs'] ) ) {
				$new_control_args['wrapper_attrs'] = [];
			}

			$new_control_args['wrapper_attrs']['data-kirki-parent-responsive-id'] = $devices_control_id;
			$new_control_args['wrapper_attrs']['data-kirki-device-preview']       = $device;

			if ( isset( $new_control_args['label'] ) ) {
				unset( $new_control_args['label'] );
			}

			if ( isset( $new_control_args['description'] ) ) {
				unset( $new_control_args['description'] );
			}

			if ( isset( $new_control_args['responsive'] ) ) {
				unset( $new_control_args['responsive'] );
			}

			$wrapper_class = '';

			/**
			 * If `$control_class` is empty, then we assume this is a "parent field" and not the "real controls".
			 * A "parent field" here means: a parent of group of controls such as field-dimensions, field-typography, etc.
			 */
			if ( ! $control_class ) {
				$wrapper_class .= ' customize-control-kirki-hidden-field';
			}

			if ( $inside_horizontal_layout ) {
				$wrapper_class .= ' customize-control-kirki-responsive-horizontal';
			}

			if ( ! empty( $wrapper_class ) ) {
				if ( isset( $new_control_args['wrapper_attrs']['class'] ) ) {
					$new_control_args['wrapper_attrs']['class'] .= $wrapper_class;
				} else {
					$new_control_args['wrapper_attrs']['class'] = '{default_class}' . $wrapper_class;
				}
			}

			$new_control_args = $this->parse_output_arg( $new_control_args, $device );
			$field_classname  = get_class( $field );

			new $field_classname( $new_control_args );

			if ( $inside_horizontal_layout && count( $devices ) === $loop_count ) {
				$this->add_devices_control( $devices_control_id, $args, $devices, $inside_horizontal_layout );
			}
		}

	}

	/**
	 * Add devices control via $wp_customize.
	 *
	 * @param string $id The control's ID.
	 * @param array  $args The control arguments.
	 * @param array  $devices The specified devices.
	 * @param bool   $inside_horizontal_layout Whether or not this control is inside a horizontal layout.
	 */
	public function add_devices_control( $id, $args, $devices, $inside_horizontal_layout = false ) {

		unset( $args['responsive'] );

		if ( isset( $args['default'] ) ) {
			unset( $args['default'] );
		}

		if ( isset( $args['transport'] ) ) {
			unset( $args['transport'] );
		}

		if ( isset( $args['output'] ) ) {
			unset( $args['output'] );
		}

		if ( isset( $args['wrapper_attrs'] ) ) {
			if ( isset( $args['wrapper_attrs']['data-kirki-parent-responsive-id'] ) ) {
				unset( $args['wrapper_attrs']['data-kirki-parent-responsive-id'] );
			}

			if ( isset( $args['wrapper_attrs']['data-kirki-device-preview'] ) ) {
				unset( $args['wrapper_attrs']['data-kirki-device-preview'] );
			}
		}

		$args['settings'] = $id;
		$args['type']     = 'kirki-responsive';

		if ( ! $inside_horizontal_layout ) {
			if ( ! isset( $args['wrapper_opts'] ) ) {
				$args['wrapper_opts'] = [];
			}

			$args['wrapper_opts']['gap'] = 'small';
		}

		$args['choices'] = [
			'devices' => $devices,
		];

		new Responsive( $args );

	}

	/**
	 * Filter the value for responsive fields.
	 *
	 * @see wp-content/plugins/kirki-dev/packages/kirki-framework/data-option/src/Option.php
	 *
	 * @param mixed  $value The field value.
	 * @param string $field_name The field name.
	 * @param mixed  $default The default value.
	 * @param string $type The option type (theme_mod or option).
	 *
	 * @return mixed The filtered value.
	 */
	public function kirki_get_value( $value = '', $field_name = '', $default = '', $type = 'theme_mod' ) {

		/**
		 * The "option" will be handled by "kirki_get_value" method in
		 * wp-content/plugins/kirki-dev/packages/kirki-framework/data-option/src/Option.php file.
		 */
		if ( 'option' === $type ) {
			return $value;
		}

		// If the field name doesn't contain a '[', then it's not a sub-item of another field.
		if ( false === strpos( $field_name, '[' ) ) {
			return $value;
		}

		// If this is not part of a responsive field, then return the value.
		if ( ! in_array( $field_name, Responsive::$sub_field_names, true ) ) {
			return $value;
		}

		/**
		 * If we got here then this is part of an option array.
		 * We need to get the 1st level, and then find the item inside that array.
		 */
		$parts = \explode( '[', $field_name );
		$value = get_theme_mod( $parts[0], [] );

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

		$value = empty( $value ) ? $default : $value;

		return $value;

	}

}
