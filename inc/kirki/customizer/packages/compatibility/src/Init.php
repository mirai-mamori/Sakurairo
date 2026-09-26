<?php
/**
 * Initializes Kirki
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
 * Initialize Kirki
 */
class Init {

	/**
	 * Control types.
	 *
	 * @access private
	 * @since 3.0.0
	 * @var array
	 */
	private $control_types = [];

	/**
	 * Should we show a nag for the deprecated fontawesome field?
	 *
	 * @static
	 * @access private
	 * @since 3.0.42
	 * @var bool
	 */
	private static $show_fa_nag = false;

	/**
	 * The class constructor.
	 */
	public function __construct() {
		add_action( 'wp_loaded', [ $this, 'add_to_customizer' ], 1 );
		add_filter( 'kirki_control_types', [ $this, 'default_control_types' ] );

		add_action( 'customize_register', [ $this, 'remove_controls' ], 99999 );

		add_action( 'admin_notices', [ $this, 'admin_notices' ] );
		add_action( 'admin_init', [ $this, 'dismiss_nag' ] );

		// ? Bagus: is this necessary? The Values class doesn't have constructor, so this does nothing.
		new Values();
	}

	/**
	 * Add the default Kirki control types.
	 *
	 * @access public
	 * @since 3.0.0
	 * @param array $control_types The control types array.
	 * @return array
	 */
	public function default_control_types( $control_types = [] ) {
		$this->control_types = [
			'kirki-composite'       => '\Kirki\Control\Composite',
			'checkbox'              => '\Kirki\Control\Checkbox',
			'kirki-color'           => '\Kirki\Control\ReactColorful',
			'kirki-color-palette'   => '\Kirki\Control\Color_Palette',
			'kirki-custom'          => '\Kirki\Control\Custom',
			'kirki-date'            => '\Kirki\Control\Date',
			'kirki-dashicons'       => '\Kirki\Control\Dashicons',
			'kirki-dimension'       => '\Kirki\Control\Dimension',
			'kirki-dimensions'      => '\Kirki\Control\Dimensions',
			'kirki-editor'          => '\Kirki\Control\Editor',
			'kirki-image'           => '\Kirki\Control\Image',
			'kirki-multicolor'      => '\Kirki\Control\Multicolor',
			'kirki-multicheck'      => '\Kirki\Control\Multicheck',
			'kirki-number'          => '\Kirki\Control\Number',
			'kirki-radio'           => '\Kirki\Control\Radio',
			'kirki-radio-buttonset' => '\Kirki\Control\Radio_Buttonset',
			'kirki-radio-image'     => '\Kirki\Control\Radio_Image',
			'repeater'              => '\Kirki\Control\Repeater',
			'kirki-select'          => '\Kirki\Control\Select',
			'kirki-slider'          => '\Kirki\Control\Slider',
			'kirki-sortable'        => '\Kirki\Control\Sortable',
			'kirki-spacing'         => '\Kirki\Control\Dimensions',
			'kirki-switch'          => '\Kirki\Control\Checkbox_Switch',
			'kirki-generic'         => '\Kirki\Control\Generic',
			'kirki-toggle'          => '\Kirki\Control\Checkbox_Toggle',
			'image'                 => '\Kirki\Control\Image',
			'cropped_image'         => '\Kirki\Control\Cropped_Image',
			'upload'                => '\Kirki\Control\Upload',
		];
		return array_merge( $this->control_types, $control_types );
	}

	/**
	 * Helper function that adds the fields to the customizer.
	 */
	public function add_to_customizer() {
		$this->fields_from_filters();
		add_action( 'customize_register', [ $this, 'register_control_types' ] );
		add_action( 'customize_register', [ $this, 'add_fields' ], 99 );
	}

	/**
	 * Register control types
	 */
	public function register_control_types() {
		global $wp_customize;

		$this->control_types = $this->default_control_types();
		if ( ! class_exists( 'WP_Customize_Code_Editor_Control' ) ) {
			unset( $this->control_types['code_editor'] );
		}
		foreach ( $this->control_types as $key => $classname ) {
			if ( ! class_exists( $classname ) ) {
				unset( $this->control_types[ $key ] );
			}
		}

		$skip_control_types = apply_filters(
			'kirki_control_types_exclude',
			[
				'\Kirki\Control\Repeater',
				'\WP_Customize_Control',
			]
		);

		foreach ( $this->control_types as $control_type ) {
			if ( ! in_array( $control_type, $skip_control_types, true ) && class_exists( $control_type ) ) {
				$wp_customize->register_control_type( $control_type );
			}
		}
	}

	/**
	 * Create the settings and controls from the $fields array and register them.
	 *
	 * @var object The WordPress Customizer object.
	 */
	public function add_fields() {
		global $wp_customize;

		foreach ( Kirki::$fields as $args ) {

			// Create the settings.
			new \Kirki\Compatibility\Settings( $args );

			// Check if we're on the customizer.
			// If we are, then we will create the controls, add the scripts needed for the customizer
			// and any other tweaks that this field may require.
			if ( $wp_customize ) {

				// Create the control.
				new Control( $args );

			}
		}
	}

	/**
	 * Process fields added using the 'kirki_fields' and 'kirki_controls' filter.
	 * These filters are no longer used, this is simply for backwards-compatibility.
	 *
	 * @access private
	 * @since 2.0.0
	 */
	private function fields_from_filters() {
		$fields = apply_filters( 'kirki_controls', [] );
		$fields = apply_filters( 'kirki_fields', $fields );

		if ( ! empty( $fields ) ) {
			foreach ( $fields as $field ) {
				$field['kirki_config'] = 'global';
				Kirki::add_field( 'global', $field );
			}
		}
	}

	/**
	 * Alias for the is_plugin static method in the Kirki\Util\Util class.
	 * This is here for backwards-compatibility purposes.
	 *
	 * @static
	 * @access public
	 * @since 3.0.0
	 * @return bool
	 */
	public static function is_plugin() {
		return Util::is_plugin();
	}

	/**
	 * Alias for the get_variables static method in the Kirki\Util\Util class.
	 * This is here for backwards-compatibility purposes.
	 *
	 * @static
	 * @access public
	 * @since 2.0.0
	 * @return array Formatted as array( 'variable-name' => value ).
	 */
	public static function get_variables() {

		// Log error for developers.
		_doing_it_wrong( __METHOD__, esc_html__( 'We detected you\'re using Kirki\Compatibility\Init::get_variables(). Please use \Kirki\Util\Util::get_variables() instead.', 'kirki' ), '3.0.10' );

		// ! This will be failed, because Util class is under Kirki\Util namespace.
		return Util::get_variables();
	}

	/**
	 * Remove controls.
	 *
	 * @since 3.0.17
	 * @param object $wp_customize The customizer object.
	 * @return void
	 */
	public function remove_controls( $wp_customize ) {
		foreach ( Kirki::$controls_to_remove as $control ) {
			$wp_customize->remove_control( $control );
		}
	}

	/**
	 * Shows an admin notice.
	 *
	 * @access public
	 * @since 3.0.42
	 * @return void
	 */
	public function admin_notices() {

		// No need for a nag if we don't need to recommend installing the FA plugin.
		if ( ! self::$show_fa_nag ) {
			return;
		}

		// No need for a nag if FA plugin is already installed.
		if ( defined( 'FONTAWESOME_DIR_PATH' ) ) {
			return;
		}

		// No need for a nag if current user can't install plugins.
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		// No need for a nag if user has dismissed it.
		$dismissed = get_user_meta( get_current_user_id(), 'kirki_fa_nag_dismissed', true );
		if ( true === $dismissed || 1 === $dismissed || '1' === $dismissed ) {
			return;
		}
		?>
		<div class="notice notice-info is-dismissible">
			<p>
				<?php esc_html_e( 'Your theme uses a Font Awesome field for icons. To avoid issues with missing icons on your frontend we recommend you install the official Font Awesome plugin.', 'kirki' ); ?>
			</p>
			<p>
				<a class="button button-primary" href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=font-awesome&TB_iframe=true&width=600&height=550' ) ); ?>"><?php esc_html_e( 'Install Plugin', 'kirki' ); ?></a>
				<a class="button button-secondary" href="<?php echo esc_url( wp_nonce_url( admin_url( '?dismiss-nag=font-awesome-kirki' ), 'kirki-dismiss-nag', 'nonce' ) ); ?>"><?php esc_html_e( 'Don\'t show this again', 'kirki' ); ?></a>
			</p>
		</div>
		<?php
	}

	/**
	 * Dismisses the nag.
	 *
	 * @access public
	 * @since 3.0.42
	 * @return void
	 */
	public function dismiss_nag() {
		if ( isset( $_GET['nonce'] ) && wp_verify_nonce( $_GET['nonce'], 'kirki-dismiss-nag' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			if ( get_current_user_id() && isset( $_GET['dismiss-nag'] ) && 'font-awesome-kirki' === $_GET['dismiss-nag'] ) {
				update_user_meta( get_current_user_id(), 'kirki_fa_nag_dismissed', true );
			}
		}
	}

	/**
	 * Handles showing a nag if the theme is using the deprecated fontawesome field
	 *
	 * @static
	 * @access protected
	 * @since 3.0.42
	 * @param array $args The field arguments.
	 * @return void
	 */
	protected static function maybe_show_fontawesome_nag( $args ) {

		// If we already know we want it, skip check.
		if ( self::$show_fa_nag ) {
			return;
		}

		// Check if the field is fontawesome.
		if ( isset( $args['type'] ) && in_array( $args['type'], [ 'fontawesome', 'kirki-fontawesome' ], true ) ) {

			// Skip check if theme has disabled FA enqueueing via a filter.
			if ( ! apply_filters( 'kirki_load_fontawesome', true ) ) {
				return;
			}

			// If we got this far, we need to show the nag.
			self::$show_fa_nag = true;
		}
	}
}
