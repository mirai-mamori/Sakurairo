<?php
/**
 * Creates and validates field parameters.
 *
 * @package     Kirki
 * @category    Core
 * @author      Themeum
 * @copyright   Copyright (c) 2023, Themeum
 * @license     https://opensource.org/licenses/MIT
 * @since       1.0
 */

namespace Kirki\Compatibility;

use Kirki\Compatibility\Kirki;

/**
 * Please do not use this class directly.
 * You should instead extend it per-field-type.
 */
class Field {

	/**
	 * An array of the field arguments.
	 *
	 * @access protected
	 * @var array
	 */
	public $label;
	public $row_label;
	public $button_label;
	public $description;
	public $help;

	protected $args = array();

	/**
	 * The ID of the kirki_config we're using.
	 *
	 * @see Kirki\Compatibility\Config
	 * @access protected
	 * @var string
	 */
	protected $kirki_config = 'global';

	/**
	 * The capability required so that users can edit this field.
	 *
	 * @access protected
	 * @var string
	 */
	protected $capability = 'edit_theme_options';

	/**
	 * If we're using options instead of theme_mods
	 * and we want them serialized, this is the option that
	 * will saved in the db.
	 *
	 * @access protected
	 * @var string
	 */
	protected $option_name = '';

	/**
	 * Custom input attributes (defined as an array).
	 *
	 * @access protected
	 * @var array
	 */
	protected $input_attrs = array();

	/**
	 * Preset choices.
	 *
	 * @access protected
	 * @var array
	 */
	protected $preset = array();

	/**
	 * CSS Variables.
	 *
	 * @access protected
	 * @var array
	 */
	protected $css_vars = array();

	/**
	 * Use "theme_mod" or "option".
	 *
	 * @access protected
	 * @var string
	 */
	protected $option_type = 'theme_mod';

	/**
	 * The name of this setting (id for the db).
	 *
	 * @access protected
	 * @var string|array
	 */
	protected $settings = '';

	/**
	 * Set to true if you want to disable all CSS output for this field.
	 *
	 * @access protected
	 * @var bool
	 */
	protected $disable_output = false;

	/**
	 * The field type.
	 *
	 * @access protected
	 * @var string
	 */
	protected $type = 'kirki-generic';

	/**
	 * Some fields require options to be set.
	 * We're whitelisting the property here
	 * and suggest you validate this in a child class.
	 *
	 * @access protected
	 * @var array
	 */
	protected $choices = array();

	/**
	 * Assign this field to a section.
	 * Fields not assigned to a section will not be displayed in the customizer.
	 *
	 * @access protected
	 * @var string
	 */
	protected $section = '';

	/**
	 * The default value for this field.
	 *
	 * @access protected
	 * @var string|array|bool
	 */
	protected $default = '';

	/**
	 * Priority determines the position of a control inside a section.
	 * Lower priority numbers move the control to the top.
	 *
	 * @access protected
	 * @var int
	 */
	protected $priority = 10;

	/**
	 * Unique ID for this field.
	 * This is auto-calculated from the $settings argument.
	 *
	 * @access protected
	 * @var string
	 */
	protected $id = '';

	/**
	 * Use if you want to automatically generate CSS from this field's value.
	 *
	 * @see https://docs.themeum.com/kirki/arguments/output/
	 * @access protected
	 * @var array
	 */
	protected $output = array();

	/**
	 * Use to automatically generate postMessage scripts.
	 * Not necessary to use if you use 'transport' => 'auto'
	 * and have already set an array for the 'output' argument.
	 *
	 * @see https://docs.themeum.com/kirki/arguments/js_vars/
	 * @access protected
	 * @var array
	 */
	protected $js_vars = array();

	/**
	 * If you want to use a CSS compiler, then use this to set the variable names.
	 *
	 * @see https://docs.themeum.com/kirki/arguments/output/
	 * @access protected
	 * @var array
	 */
	protected $variables = array();

	/**
	 * Text that will be used in a tooltip to provide extra info for this field.
	 *
	 * @access protected
	 * @var string
	 */
	protected $tooltip = '';

	/**
	 * A custom callback to determine if the field should be visible or not.
	 *
	 * @access protected
	 * @var string|array
	 */
	protected $active_callback = '__return_true';

	/**
	 * A custom sanitize callback that will be used to properly save the values.
	 *
	 * @access protected
	 * @var string|array
	 */
	protected $sanitize_callback = '';

	/**
	 * Use 'refresh', 'postMessage' or 'auto'.
	 * 'auto' will automatically geberate any 'js_vars' from the 'output' argument.
	 *
	 * @access protected
	 * @var string
	 */
	protected $transport = 'refresh';

	/**
	 * Define dependencies to show/hide this field based on the values of other fields.
	 *
	 * @access protected
	 * @var array
	 */
	protected $required = array();

	/**
	 * Partial Refreshes array.
	 *
	 * @access protected
	 * @var array
	 */
	protected $partial_refresh = array();

	/**
	 * The class constructor.
	 * Parses and sanitizes all field arguments.
	 * Then it adds the field to Kirki::$fields.
	 *
	 * @access public
	 * @param string $config_id    The ID of the config we want to use.
	 *                             Defaults to "global".
	 *                             Configs are handled by the Kirki\Compatibility\Config class.
	 * @param array  $args         The arguments of the field.
	 */
	public function __construct( $config_id = 'global', $args = array() ) {

		/**
		 * In case the user only provides 1 argument,
		 * assume that the provided argument is $args and set $config_id = 'global'.
		 */
		if ( is_array( $config_id ) && empty( $args ) ) {
			$args      = $config_id;
			$config_id = isset( $args['kirki_config'] ) ? $args['kirki_config'] : 'global';
		}

		if ( isset( $args['setting'] ) && ! empty( $args['setting'] ) && ( ! isset( $args['settings'] ) || empty( $args['settings'] ) ) ) {
			/* translators: %s represents the field ID where the error occurs. */
			_doing_it_wrong( __METHOD__, sprintf( esc_html__( 'Typo found in field %s - setting instead of settings.', 'kirki' ), esc_html( $args['settings'] ) ), '3.0.10' );
			$args['settings'] = $args['setting'];
			unset( $args['setting'] );
		}

		$args['kirki_config'] = $config_id;

		$this->kirki_config = $config_id;

		if ( '' === $config_id ) {
			/* translators: %1$s represents the field ID where the error occurs. %2$s is the URL in the documentation site. */
			_doing_it_wrong( __METHOD__, sprintf( esc_html__( 'Config not defined for field %1$s - See %2$s for details on how to properly add fields.', 'kirki' ), esc_html( $args['settings'] ), 'https://aristath.github.io/kirki/docs/getting-started/fields.html' ), '3.0.10' );
			$this->kirki_config = 'global';
		}

		// Get defaults from the class.
		$defaults = get_class_vars( __CLASS__ );

		// Get the config arguments, and merge them with the defaults.
		$config_defaults = ( isset( Kirki::$config['global'] ) ) ? Kirki::$config['global'] : array();

		if ( 'global' !== $this->kirki_config && isset( Kirki::$config[ $this->kirki_config ] ) ) {
			$config_defaults = Kirki::$config[ $this->kirki_config ];
		}

		$config_defaults = ( is_array( $config_defaults ) ) ? $config_defaults : array();

		foreach ( $config_defaults as $key => $value ) {
			if ( isset( $defaults[ $key ] ) && ! empty( $value ) && $value !== $defaults[ $key ] ) {
				$defaults[ $key ] = $value;
			}
		}

		// Merge our args with the defaults.
		$args = wp_parse_args( $args, $defaults );

		// Set the class properties using the parsed args.
		foreach ( $args as $key => $value ) {
			$this->{$key} = $value;
		}		

		$this->args = $args;

		$this->set_field();

		// Instantiate the \Kirki\Field to apply hooks.
		new \Kirki\Field\None( $this->args );

	}

	/**
	 * Processes the field arguments
	 *
	 * @access protected
	 */
	protected function set_field() {

		$properties = get_class_vars( __CLASS__ );

		// Some things must run before the others.
		$this->set_option_type();
		$this->set_settings();

		// Sanitize the properties, skipping the ones that have already run above.
		foreach ( array_keys( $properties ) as $property ) {
			if ( in_array( $property, array( 'option_name', 'option_type', 'settings' ), true ) ) {
				continue;
			}
			if ( method_exists( $this, 'set_' . $property ) ) {
				$method_name = 'set_' . $property;
				$this->$method_name();
			}
		}

		// Get all arguments with their values.
		$args = get_object_vars( $this );

		foreach ( array_keys( $args ) as $key ) {
			$args[ $key ] = $this->$key;
		}

		// Add the field to the static $fields variable properly indexed.
		Kirki::$fields[ $this->settings ] = $args;

	}

	/**
	 * Escape the $section.
	 *
	 * @access protected
	 */
	protected function set_input_attrs() {
		$this->input_attrs = (array) $this->input_attrs;
	}

	/**
	 * Make sure we're using the correct option_type
	 *
	 * @access protected
	 */
	protected function set_option_type() {

		// Take care of common typos.
		if ( 'options' === $this->option_type ) {
			$this->option_type = 'option';
		}

		// Take care of common typos.
		if ( 'theme_mods' === $this->option_type ) {
			/* translators: %1$s represents the field ID where the error occurs. */
			_doing_it_wrong( __METHOD__, sprintf( esc_html( 'Typo found in field %s - "theme_mods" vs "theme_mod"', 'kirki' ), esc_html( $this->settings ) ), '3.0.10' );
			$this->option_type = 'theme_mod';
		}
	}

	/**
	 * Modifications for partial refreshes.
	 *
	 * @access protected
	 */
	protected function set_partial_refresh() {
		if ( ! is_array( $this->partial_refresh ) ) {
			$this->partial_refresh = array();
		}
		foreach ( $this->partial_refresh as $id => $args ) {
			if ( ! is_array( $args ) || ! isset( $args['selector'] ) || ! isset( $args['render_callback'] ) || ! is_callable( $args['render_callback'] ) ) {
				/* translators: %1$s represents the field ID where the error occurs. */
				_doing_it_wrong( __METHOD__, sprintf( esc_html__( '"partial_refresh" invalid entry in field %s', 'kirki' ), esc_html( $this->settings ) ), '3.0.10' );
				unset( $this->partial_refresh[ $id ] );
				continue;
			}
		}
		if ( ! empty( $this->partial_refresh ) ) {
			$this->transport = 'postMessage';
		}
	}

	/**
	 * Sets the settings.
	 * If we're using serialized options it makes sure that settings are properly formatted.
	 * We'll also be escaping all setting names here for consistency.
	 *
	 * @access protected
	 */
	protected function set_settings() {

		// If settings is not an array, temporarily convert it to an array.
		// This is just to allow us to process everything the same way and avoid code duplication.
		// if settings is not an array then it will not be set as an array in the end.
		if ( ! is_array( $this->settings ) ) {
			$this->settings = array(
				'kirki_placeholder_setting' => $this->settings,
			);
		}
		$settings = array();
		foreach ( $this->settings as $setting_key => $setting_value ) {
			$settings[ $setting_key ] = $setting_value;

			// If we're using serialized options then we need to spice this up.
			if ( 'option' === $this->option_type && '' !== $this->option_name && ( false === strpos( $setting_key, '[' ) ) ) {
				$settings[ $setting_key ] = "{$this->option_name}[{$setting_value}]";
			}
		}
		$this->settings = $settings;
		if ( isset( $this->settings['kirki_placeholder_setting'] ) ) {
			$this->settings = $this->settings['kirki_placeholder_setting'];
		}
	}

	/**
	 * Sets the active_callback
	 * If we're using the $required argument,
	 * Then this is where the switch is made to our evaluation method.
	 *
	 * @access protected
	 */
	protected function set_active_callback() {

		if ( is_array( $this->active_callback ) ) {

			if ( ! is_callable( $this->active_callback ) ) {

				// Bugfix for https://github.com/aristath/kirki/issues/1961.
				foreach ( $this->active_callback as $key => $val ) {
					if ( is_callable( $val ) ) {
						unset( $this->active_callback[ $key ] );
					}
				}
				if ( isset( $this->active_callback[0] ) ) {
					$this->required = $this->active_callback;
				}
			}
		}

		if ( ! empty( $this->required ) ) {
			$this->active_callback = '__return_true';
			return;
		}
		// No need to proceed any further if we're using the default value.
		if ( '__return_true' === $this->active_callback ) {
			return;
		}

		// Make sure the function is callable, otherwise fallback to __return_true.
		if ( ! is_callable( $this->active_callback ) ) {
			$this->active_callback = '__return_true';
		}

	}

	/**
	 * Sets the $id.
	 * Setting the ID should happen after the 'settings' sanitization.
	 * This way we can also properly handle cases where the option_type is set to 'option'
	 * and we're using an array instead of individual options.
	 *
	 * @access protected
	 */
	protected function set_id() {
		$this->id = sanitize_key( str_replace( '[', '-', str_replace( ']', '', $this->settings ) ) );
	}

	/**
	 * Sets the $choices.
	 *
	 * @access protected
	 */
	protected function set_choices() {
		if ( ! is_array( $this->choices ) ) {
			$this->choices = array();
		}
	}

	/**
	 * Escapes the $disable_output.
	 *
	 * @access protected
	 */
	protected function set_disable_output() {
		$this->disable_output = (bool) $this->disable_output;
	}

	/**
	 * Sets the $sanitize_callback
	 *
	 * @access protected
	 */
	protected function set_output() {
		if ( empty( $this->output ) ) {
			return;
		}
		if ( ! is_array( $this->output ) ) {
			/* translators: The field ID where the error occurs. */
			_doing_it_wrong( __METHOD__, sprintf( esc_html__( '"output" invalid format in field %s. The "output" argument should be defined as an array of arrays.', 'kirki' ), esc_html( $this->settings ) ), '3.0.10' );
			$this->output = array(
				array(
					'element' => $this->output,
				),
			);
		}

		// Convert to array of arrays if needed.
		if ( isset( $this->output['element'] ) ) {
			/* translators: The field ID where the error occurs. */
			_doing_it_wrong( __METHOD__, sprintf( esc_html__( '"output" invalid format in field %s. The "output" argument should be defined as an array of arrays.', 'kirki' ), esc_html( $this->settings ) ), '3.0.10' );
			$this->output = array( $this->output );
		}

		foreach ( $this->output as $key => $output ) {
			if ( empty( $output ) || ! isset( $output['element'] ) ) {
				unset( $this->output[ $key ] );
				continue;
			}
			if ( ! isset( $output['sanitize_callback'] ) && isset( $output['callback'] ) ) {
				$this->output[ $key ]['sanitize_callback'] = $output['callback'];
			}

			// Convert element arrays to strings.
			if ( isset( $output['element'] ) && is_array( $output['element'] ) ) {
				$this->output[ $key ]['element'] = array_unique( $this->output[ $key ]['element'] );
				sort( $this->output[ $key ]['element'] );

				// Trim each element in the array.
				foreach ( $this->output[ $key ]['element'] as $index => $element ) {
					$this->output[ $key ]['element'][ $index ] = trim( $element );
				}
				$this->output[ $key ]['element'] = implode( ',', $this->output[ $key ]['element'] );
			}

			// Fix for https://github.com/aristath/kirki/issues/1659#issuecomment-346229751.
			$this->output[ $key ]['element'] = str_replace( array( "\t", "\n", "\r", "\0", "\x0B" ), ' ', $this->output[ $key ]['element'] );
			$this->output[ $key ]['element'] = trim( preg_replace( '/\s+/', ' ', $this->output[ $key ]['element'] ) );
		}
	}

	/**
	 * Sets the $js_vars
	 *
	 * @access protected
	 */
	protected function set_js_vars() {
		if ( ! is_array( $this->js_vars ) ) {
			$this->js_vars = array();
		}

		// Check if transport is set to auto.
		// If not, then skip the auto-calculations and exit early.
		if ( 'auto' !== $this->transport ) {
			return;
		}

		// Set transport to refresh initially.
		// Serves as a fallback in case we failt to auto-calculate js_vars.
		$this->transport = 'refresh';

		$js_vars = array();

		// Try to auto-generate js_vars.
		// First we need to check if js_vars are empty, and that output is not empty.
		if ( empty( $this->js_vars ) && ! empty( $this->output ) ) {

			// Start going through each item in the $output array.
			foreach ( $this->output as $output ) {
				$output['function'] = ( isset( $output['function'] ) ) ? $output['function'] : 'style';

				// If 'element' is not defined, skip this.
				if ( ! isset( $output['element'] ) ) {
					continue;
				}
				if ( is_array( $output['element'] ) ) {
					$output['element'] = implode( ',', $output['element'] );
				}

				// If there's a sanitize_callback defined skip this, unless we also have a js_callback defined.
				if ( isset( $output['sanitize_callback'] ) && ! empty( $output['sanitize_callback'] ) && ! isset( $output['js_callback'] ) ) {
					continue;
				}

				// If we got this far, it's safe to add this.
				$js_vars[] = $output;
			}

			// Did we manage to get all the items from 'output'?
			// If not, then we're missing something so don't add this.
			if ( count( $js_vars ) !== count( $this->output ) ) {
				return;
			}
			$this->js_vars   = $js_vars;
			$this->transport = 'postMessage';
		}
	}

	/**
	 * Sets the $variables
	 *
	 * @access protected
	 */
	protected function set_variables() {
		if ( ! is_array( $this->variables ) ) {
			$variable        = ( is_string( $this->variables ) && ! empty( $this->variables ) ) ? $this->variables : false;
			$this->variables = array();
			if ( $variable && empty( $this->variables ) ) {
				$this->variables[0]['name'] = $variable;
			}
		}
	}

	/**
	 * Sets the $transport
	 *
	 * @access protected
	 */
	protected function set_transport() {
		if ( 'postmessage' === trim( strtolower( $this->transport ) ) ) {
			$this->transport = 'postMessage';
		}
	}

	/**
	 * Sets the $required
	 *
	 * @access protected
	 */
	protected function set_required() {
		if ( ! is_array( $this->required ) ) {
			$this->required = array();
		}
	}

	/**
	 * Sets the $priority
	 *
	 * @access protected
	 */
	protected function set_priority() {
		$this->priority = absint( $this->priority );
	}

	/**
	 * Sets the $css_vars
	 *
	 * @access protected
	 */
	protected function set_css_vars() {
		if ( is_string( $this->css_vars ) ) {
			$this->css_vars = array( $this->css_vars );
		}
		if ( isset( $this->css_vars[0] ) && is_string( $this->css_vars[0] ) ) {
			$this->css_vars = array( $this->css_vars );
		}
		foreach ( $this->css_vars as $key => $val ) {
			if ( ! isset( $val[1] ) ) {
				$this->css_vars[ $key ][1] = '$';
			}
		}
	}
}
