<?php // phpcs:disable PHPCompatibility.FunctionDeclarations.NewClosure
/**
 * WordPress Customizer API abstraction.
 *
 * @package   kirki-framework/field
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     0.1
 */

namespace Kirki;

/**
 * Make it easier to create customizer settings & controls with a single call,
 * register the control type if needed, run extra actions the the customizer.
 * This is a simple abstraction which makes adding simple controls to the Customizer.
 *
 * This class is not meant to be used as-is, you'll need to extend it from a child class.
 *
 * @since 0.1
 */
abstract class Field {

	/**
	 * The field arguments.
	 *
	 * @access protected
	 * @since 0.1
	 * @var array
	 */
	protected $args;

	/**
	 * The control class-name.
	 *
	 * Use the full classname, with namespace included.
	 * Example: '\Kirki\Control\Color'.
	 *
	 * @access protected
	 * @since 0.1
	 * @var string
	 */
	protected $control_class;

	/**
	 * The setting class-name.
	 *
	 * @access protected
	 * @since 0.1
	 * @var string|null
	 */
	protected $settings_class;

	/**
	 * Whether we should register the control class for JS-templating or not.
	 *
	 * @access protected
	 * @since 0.1
	 * @var bool
	 */
	protected $control_has_js_template = false;

	/**
	 * Constructor.
	 * Registers any hooks we need to run.
	 *
	 * @access public
	 * @since 0.1
	 * @param array $args The field arguments.
	 */
	public function __construct( $args ) {

		$control_class = property_exists( $this, 'control_class' ) && ! empty( $this->control_class ) ? $this->control_class : '';

		// Allow 3rd parties to do their custom "init" work.
		do_action( 'kirki_field_custom_init', $this, $args, $control_class );

		// Allow 3rd parties to early stop the field from being registered.
		if ( apply_filters( 'kirki_field_exclude_init', false, $this, $args ) ) {
			return;
		}

		// Set the arguments in this object.
		$this->args = $args;

		if ( ! isset( $this->args['settings'] ) ) {
			$this->args['settings'] = md5( wp_json_encode( $this->args ) );
		}

		add_action(
			'wp_loaded',
			function() {
				do_action( 'kirki_field_init', $this->args, $this );
			}
		);

		add_action(
			'wp',
			function() {
				do_action( 'kirki_field_wp', $this->args, $this );
			}
		);

		$this->init( $this->args );

		// Register control-type for JS-templating in the customizer.
		add_action( 'customize_register', [ $this, 'register_control_type' ] );

		// Add customizer setting.
		add_action( 'customize_register', [ $this, 'add_setting' ] );

		// Add customizer control.
		add_action( 'customize_register', [ $this, 'add_control' ] );

		// Add default filters. Can be overriden in child classes.
		add_filter( 'kirki_field_add_setting_args', [ $this, 'filter_setting_args' ], 10, 2 );
		add_filter( 'kirki_field_add_control_args', [ $this, 'filter_control_args' ], 10, 2 );

		// Copy $this->args to a variable to be added to Kirki::$all_fields global.
		$field_args = $this->args;

		/**
		 * Kirki::$fields contains only fields which are not extending the new base Field.
		 * So we collect all fields and add them to Kirki::$all_fields.
		 *
		 * ! This patch is used by Kirki::get_option which calls Values::get_value method.
		 * Even though this is a patch, this is fine and still a good solution to handle backwards compatibility.
		 */
		\Kirki\Compatibility\Kirki::$all_fields[ $field_args['settings'] ] = $field_args;

	}

	/**
	 * Runs in the constructor. Can be used by child-classes to define extra logic.
	 *
	 * @access protected
	 * @since 0.1
	 * @param array $args The field arguments.
	 * @return void
	 */
	protected function init( $args ) {}

	/**
	 * Register the control-type.
	 *
	 * @access public
	 * @since 0.1
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 * @return void
	 */
	public function register_control_type( $wp_customize ) {

		if ( $this->control_class ) {
			$wp_customize->register_control_type( $this->control_class );
		}

	}

	/**
	 * Filter setting args.
	 *
	 * @access public
	 * @since 0.1
	 * @param array                $args         The field arguments.
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 * @return array
	 */
	public function filter_setting_args( $args, $wp_customize ) {

		return $args;

	}

	/**
	 * Filter control args.
	 *
	 * @access public
	 * @since 0.1
	 * @param array                $args         The field arguments.
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 * @return array
	 */
	public function filter_control_args( $args, $wp_customize ) {

		return $args;

	}

	/**
	 * Registers the setting.
	 *
	 * @access public
	 * @since 0.1
	 * @param WP_Customize_Manager $customizer The customizer instance.
	 * @return void
	 */
	public function add_setting( $customizer ) {

		$args = $this->args;

		// This is for postMessage purpose.
		// @see wp-content/plugins/kirki/kirki-packages/module-postmessage/src/Postmessage.php inside 'field_add_setting_args' method.
		$args['type'] = isset( $this->type ) ? $this->type : '';

		/**
		 * Allow filtering the arguments.
		 *
		 * @since 0.1
		 * @param array                $this->args The arguments.
		 * @param WP_Customize_Manager $customizer The customizer instance.
		 * @return array                           Return the arguments.
		 */
		$args = apply_filters( 'kirki_field_add_setting_args', $args, $customizer );

		if ( ! isset( $args['settings'] ) || empty( $args['settings'] ) ) {
			return;
		}

		$setting_id = $args['settings'];

		$args = [
			'type'                 => isset( $args['option_type'] ) ? $args['option_type'] : 'theme_mod', // 'type' here doesn't use the $args['type'] but instead checking the $args['option_type'].
			'capability'           => isset( $args['capability'] ) ? $args['capability'] : 'edit_theme_options',
			'theme_supports'       => isset( $args['theme_supports'] ) ? $args['theme_supports'] : '',
			'default'              => isset( $args['default'] ) ? $args['default'] : '',
			'transport'            => isset( $args['transport'] ) ? $args['transport'] : 'refresh',
			'sanitize_callback'    => isset( $args['sanitize_callback'] ) ? $args['sanitize_callback'] : '',
			'sanitize_js_callback' => isset( $args['sanitize_js_callback'] ) ? $args['sanitize_js_callback'] : '',
		];

		$settings_class = $this->settings_class ? $this->settings_class : null;

		if ( $settings_class ) {
			$customizer->add_setting( new $settings_class( $customizer, $setting_id, $args ) );
		} else {
			$customizer->add_setting( $setting_id, $args );
		}

	}

	/**
	 * Registers the control.
	 *
	 * @access public
	 * @since 0.1
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 * @return void
	 */
	public function add_control( $wp_customize ) {

		$control_class = $this->control_class;

		// If no class-name is defined, early exit.
		if ( ! $control_class ) {
			return;
		}

		/**
		 * Allow filtering the arguments.
		 *
		 * @since 0.1
		 * @param array                $this->args   The arguments.
		 * @param WP_Customize_Manager $wp_customize The customizer instance.
		 * @return array                             Return the arguments.
		 */
		$args = apply_filters( 'kirki_field_add_control_args', $this->args, $wp_customize );

		$wp_customize->add_control( new $control_class( $wp_customize, $this->args['settings'], $args ) );

	}

}
