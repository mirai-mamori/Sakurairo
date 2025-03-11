<?php // phpcs:disable PHPCompatibility.FunctionDeclarations.NewClosure
/**
 * Override field methods
 *
 * @package   kirki-pro-tabs
 * @since     1.0
 */

namespace Kirki\Pro\Field;

use Kirki\Field;

/**
 * Field overrides.
 *
 * @since 1.0
 */
class Tabs extends Field {

	/**
	 * The field type.
	 *
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-tab';

	/**
	 * The control class-name.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected $control_class = '\Kirki\Pro\Control\Tabs';

	/**
	 * Whether we should register the control class for JS-templating or not.
	 *
	 * @access protected
	 * @since 1.0
	 * @var bool
	 */
	protected $control_has_js_template = true;

	/**
	 * Array of tabs collection.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public static $tabs = [];

	/**
	 * Constructor.
	 * Registers any hooks we need to run.
	 *
	 * @since 1.0.0
	 * @param array $args The field arguments.
	 */
	public function __construct( $args ) {

		parent::__construct( $args );

		self::$tabs[ $args['section'] ] = $args['choices']['tabs'];

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
			$args['type'] = 'kirki-tab';
		}

		return $args;

	}
}

