<?php
/**
 * Processes configurations.
 *
 * @package   Kirki
 * @category  Compatibility
 * @author    Themeum
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 */

namespace Kirki\Compatibility;

/**
 * The Config object
 */
final class Config {

	/**
	 * Each instance is stored separately in this array.
	 *
	 * @static
	 * @access private
	 * @var array
	 */
	private static $instances = [];

	/**
	 * The finalized configuration array.
	 *
	 * @access protected
	 * @var array
	 */
	protected $config_final = [];

	/**
	 * The configuration ID.
	 *
	 * @access public
	 * @var string
	 */
	public $id = 'global';

	/**
	 * Capability (fields will inherit this).
	 *
	 * @access protected
	 * @var string
	 */
	protected $capability = 'edit_theme_options';

	/**
	 * The data-type we'll be using.
	 *
	 * @access protected
	 * @var string
	 */
	protected $option_type = 'theme_mod';

	/**
	 * If we're using serialized options, then this is the global option name.
	 *
	 * @access protected
	 * @var string
	 */
	protected $option_name = '';

	/**
	 * The compiler.
	 *
	 * @access protected
	 * @var array
	 */
	protected $compiler = [];

	/**
	 * Set to true if you want to completely disable any Kirki-generated CSS.
	 *
	 * @access protected
	 * @var bool
	 */
	protected $disable_output = false;

	/**
	 * The class constructor.
	 * Use the get_instance() static method to get the instance you need.
	 *
	 * @access private
	 * @param string $config_id @see Kirki\Compatibility\Config::get_instance().
	 * @param array  $args      @see Kirki\Compatibility\Config::get_instance().
	 */
	private function __construct( $config_id = 'global', $args = [] ) {

		// Get defaults from the class.
		$defaults = get_class_vars( __CLASS__ );
		// Skip what we don't need in this context.
		unset( $defaults['config_final'] );
		unset( $defaults['instances'] );
		// Apply any kirki_config global filters.
		$defaults = apply_filters( 'kirki_config', $defaults );
		// Merge our args with the defaults.
		$args = wp_parse_args( $args, $defaults );

		// Modify default values with the defined ones.
		foreach ( $args as $key => $value ) {
			// Is this property whitelisted?
			if ( property_exists( $this, $key ) ) {
				$args[ $key ] = $value;
			}
		}
		$this->id = $config_id;

		$this->config_final = wp_parse_args(
			[
				'id' => $config_id,
			],
			$args
		);
	}

	/**
	 * Use this method to get an instance of your config.
	 * Each config has its own instance of this object.
	 *
	 * @static
	 * @access public
	 * @param string $id     Config ID.
	 * @param array  $args   {
	 * Optional. Arguments to override config defaults.
	 *
	 *    @type string      $capability       @see https://codex.wordpress.org/Roles_and_Capabilities
	 *    @type string      $option_type      theme_mod or option.
	 *    @type string      $option_name      If we want to used serialized options,
	 *                                        this is where we'll be adding the option name.
	 *                                        All fields using this config will be items in that array.
	 *    @type array       $compiler         Not yet fully implemented
	 *    @type bool        $disable_output   If set to true, no CSS will be generated
	 *                                        from fields using this configuration.
	 * }
	 *
	 * @return Kirki\Compatibility\Config
	 */
	public static function get_instance( $id = 'global', $args = [] ) {
		if ( empty( $id ) ) {
			$id = 'global';
		}
		$id_md5 = md5( $id );
		if ( ! isset( self::$instances[ $id_md5 ] ) ) {
			self::$instances[ $id_md5 ] = new self( $id, $args );
		}
		return self::$instances[ $id_md5 ];
	}

	/**
	 * Get the IDs of all current configs.
	 *
	 * @static
	 * @access public
	 * @since 3.0.22
	 * @return array
	 */
	public static function get_config_ids() {
		$configs = [];
		foreach ( self::$instances as $instance ) {
			$configs[] = $instance->id;
		}
		return array_unique( $configs );
	}

	/**
	 * Returns the $config_final property
	 *
	 * @access public
	 * @return array
	 */
	public function get_config() {
		return $this->config_final;
	}
}
