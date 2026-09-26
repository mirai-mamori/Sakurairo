<?php
/**
 * Handles sections created via the Kirki API.
 *
 * @package     Kirki
 * @category    Core
 * @author      Themeum
 * @copyright   Copyright (c) 2023, Themeum
 * @license    https://opensource.org/licenses/MIT
 * @since       1.0
 */

namespace Kirki\Compatibility;

/**
 * Each setting is a separate instance
 */
class Settings {

	/**
	 * The global $wp_customize object.
	 *
	 * @access protected
	 * @var WP_Customize_Manager
	 */
	protected $wp_customize;

	/**
	 * The setting-stypes we're using.
	 *
	 * @access protected
	 * @var array
	 */
	protected $setting_types = [];

	/**
	 * Creates a new Settings object.
	 * Class constructor.
	 *
	 * @access public
	 * @param array $args The field definition as sanitized in Kirki\Compatibility\Field.
	 */
	public function __construct( $args = [] ) {

		// Set the $wp_customize property.
		global $wp_customize;
		if ( ! $wp_customize ) {
			return;
		}
		$this->wp_customize = $wp_customize;

		// Set the setting_types.
		$this->set_setting_types();

		// Add the settings.
		$this->add_settings( $args );

	}

	/**
	 * Adds the settings for this field.
	 * If settings are defined as an array, then it goes through them
	 * and calls the add_setting method.
	 * If not an array, then it just calls add_setting
	 *
	 * @access private
	 * @param array $args The field definition as sanitized in Kirki\Compatibility\Field.
	 */
	final function add_settings( $args = [] ) {

		// Get the classname we'll be using to create our setting(s).
		$classname = false;
		if ( isset( $args['option_type'] ) && array_key_exists( $args['option_type'], $this->setting_types ) ) {
			$classname = $this->setting_types[ $args['option_type'] ];
		}
		if ( ! isset( $args['type'] ) || ! array_key_exists( $args['type'], $this->setting_types ) ) {
			$args['type'] = 'default';
		}
		$classname = ! $classname ? $this->setting_types[ $args['type'] ] : $classname;

		// If settings are defined as an array, then we need to go through them
		// and call add_setting for each one of them separately.
		if ( isset( $args['settings'] ) && is_array( $args['settings'] ) ) {

			// Make sure defaults have been defined.
			if ( ! isset( $args['default'] ) || ! is_array( $args['default'] ) ) {
				$args['default'] = [];
			}
			foreach ( $args['settings'] as $key => $value ) {
				// ? Bagus: this $defaults var is not defined anywhere inside this function, so is this a mistake?
				$default = ( isset( $defaults[ $key ] ) ) ? $defaults[ $key ] : '';
				$this->add_setting( $classname, $value, $default, $args['option_type'], $args['capability'], $args['transport'], $args['sanitize_callback'] );
			}
		}
		$this->add_setting( $classname, $args['settings'], $args['default'], $args['option_type'], $args['capability'], $args['transport'], $args['sanitize_callback'] );
	}

	/**
	 * This is where we're finally adding the setting to the Customizer.
	 *
	 * @access private
	 * @param string       $classname           The name of the class that will be used to create this setting.
	 *                                          We're getting this from $this->setting_types.
	 * @param string       $setting             The setting-name.
	 *                                          If settings is an array, then this method is called per-setting.
	 * @param string|array $default             Default value for this setting.
	 * @param string       $type                The data type we're using. Valid options: theme_mod|option.
	 * @param string       $capability          @see https://codex.wordpress.org/Roles_and_Capabilities.
	 * @param string       $transport           Use refresh|postMessage.
	 * @param string|array $sanitize_callback   A callable sanitization function or method.
	 */
	final function add_setting( $classname, $setting, $default, $type, $capability, $transport, $sanitize_callback ) {

		$this->wp_customize->add_setting(
			new $classname(
				$this->wp_customize,
				$setting,
				[
					'default'           => $default,
					'type'              => $type,
					'capability'        => $capability,
					'transport'         => $transport,
					'sanitize_callback' => $sanitize_callback,
				]
			)
		);

	}

	/**
	 * Sets the $this->setting_types property.
	 * Makes sure the kirki_setting_types filter is applied
	 * and that the defined classes actually exist.
	 * If a defined class does not exist, it is removed.
	 */
	final function set_setting_types() {

		// Apply the kirki_setting_types filter.
		$this->setting_types = apply_filters(
			'kirki_setting_types',
			[
				'default'     => 'WP_Customize_Setting',
				'repeater'    => '\Kirki_Settings_Repeater_Setting',
				'user_meta'   => '\Kirki\Util\Setting\User_Meta',
				'site_option' => '\Kirki\Util\Setting\Site_Option',
			]
		);

		// Make sure the defined classes actually exist.
		foreach ( $this->setting_types as $key => $classname ) {

			if ( ! class_exists( $classname ) ) {
				unset( $this->setting_types[ $key ] );
			}
		}
	}
}
