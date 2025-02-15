<?php // phpcs:disable PHPCompatibility.FunctionDeclarations.NewClosure
/**
 * Override field methods
 *
 * @package   kirki-pro-responsive
 * @since     1.0
 */

namespace Kirki\Pro\Field;

use Kirki\Field;

/**
 * Field overrides.
 *
 * @since 1.0
 */
class Responsive extends Field {

	/**
	 * The field type.
	 *
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-responsive';

	/**
	 * The control class-name.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected $control_class = '\Kirki\Pro\Control\Responsive';

	/**
	 * Collection of the connected field names.
	 *
	 * @var array
	 */
	public static $sub_field_names = [];

	/**
	 * Whether we should register the control class for JS-templating or not.
	 *
	 * @access protected
	 * @since 1.0
	 * @var bool
	 */
	protected $control_has_js_template = true;

	/**
	 * Class constructor.
	 *
	 * @param array $args The field arguments.
	 */
	public function __construct( $args ) {

		$field_name = str_ireplace( 'kirki_responsive__', '', $args['settings'] );

		if ( isset( $args['choices'] ) && isset( $args['choices']['devices'] ) && ! empty( $args['choices']['devices'] ) ) {
			foreach ( $args['choices']['devices'] as $device ) {
				$sub_field_name = $field_name . '[' . $device . ']';

				if ( ! in_array( $sub_field_name, self::$sub_field_names, true ) ) {
					array_push( self::$sub_field_names, $sub_field_name );
				}
			}
		}

		parent::__construct( $args );

	}

	/**
	 * Filter arguments before creating the setting.
	 *
	 * @since 1.0
	 *
	 * @param array                $args         The field arguments.
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 *
	 * @return array
	 */
	public function filter_setting_args( $args, $wp_customize ) {

		if ( $args['settings'] === $this->args['settings'] ) {
			$args = parent::filter_setting_args( $args, $wp_customize );

			// Set the sanitize-callback if none is defined.
			if ( ! isset( $args['sanitize_callback'] ) || ! $args['sanitize_callback'] ) {
				$args['sanitize_callback'] = '__return_null';
			}
		}

		return $args;

	}

	/**
	 * Filter arguments before creating the control.
	 *
	 * @since 0.1
	 *
	 * @param array                $args         The field arguments.
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 *
	 * @return array
	 */
	public function filter_control_args( $args, $wp_customize ) {

		if ( $args['settings'] === $this->args['settings'] ) {
			$args         = parent::filter_control_args( $args, $wp_customize );
			$args['type'] = 'kirki-responsive';
		}

		return $args;

	}
}
