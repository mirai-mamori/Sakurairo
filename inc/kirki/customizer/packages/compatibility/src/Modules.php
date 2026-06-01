<?php
/**
 * Handles modules loading.
 *
 * @package    Kirki
 * @category   Modules
 * @author     Themeum
 * @copyright  Copyright (c) 2023, Themeum
 * @license    https://opensource.org/licenses/MIT
 * @since      3.0.0
 */

namespace Kirki\Compatibility;

/**
 * The Modules class.
 */
class Modules {

	
	/**
	 * An array of available modules.
	 *
	 * @static
	 * @access private
	 * @since 3.0.0
	 * @var array
	 */
	private static $modules = [];

	/**
	 * An array of active modules (objects).
	 *
	 * @static
	 * @access private
	 * @since 3.0.0
	 * @var array
	 */
	private static $active_modules = [];

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 3.0.0
	 */
	public function __construct() {

		add_action( 'after_setup_theme', [ $this, 'setup_default_modules' ], 10 );
		add_action( 'after_setup_theme', [ $this, 'init' ], 11 );

	}

	/**
	 * Set the default modules and apply the 'kirki_modules' filter.
	 * In v3.0.35 this method was renamed from default_modules to setup_default_modules,
	 * and its visibility changed from private to public to fix https://github.com/aristath/kirki/issues/2023
	 *
	 * @access public
	 * @since 3.0.0
	 */
	public function setup_default_modules() {

		self::$modules = apply_filters(
			'kirki_modules',
			[
				'css'                => '\Kirki\Module\CSS',
				'tooltips'           => '\Kirki\Module\Tooltips',
				'postMessage'        => '\Kirki\Module\Postmessage',
				'selective-refresh'  => '\Kirki\Module\Selective_Refresh',
				'field-dependencies' => '\Kirki\Module\Field_Dependencies',
				'webfonts'           => '\Kirki\Module\Webfonts',
				'preset'             => '\Kirki\Module\Preset',
				'gutenberg'          => '\Kirki\Module\Editor_Styles',
				'section-icons'      => '\Kirki\Module\Section_Icons',
			]
		);

	}

	/**
	 * Instantiates the modules.
	 * In v3.0.35 the visibility for this method was changed
	 * from private to public to fix https://github.com/aristath/kirki/issues/2023
	 *
	 * @access public
	 * @since 3.0.0
	 */
	public function init() {

		foreach ( self::$modules as $module_class ) {
			if ( class_exists( $module_class ) ) {
				new $module_class();
			}
		}

	}

	/**
	 * Add a module.
	 *
	 * @static
	 * @access public
	 * @param string $module The classname of the module to add.
	 * @since 3.0.0
	 */
	public static function add_module( $module ) {

		if ( ! in_array( $module, self::$modules, true ) ) {
			self::$modules[] = $module;
		}

	}

	/**
	 * Remove a module.
	 *
	 * @static
	 * @access public
	 * @param string $module The classname of the module to add.
	 * @since 3.0.0
	 */
	public static function remove_module( $module ) {

		$key = array_search( $module, self::$modules, true );

		if ( false !== $key ) {
			unset( self::$modules[ $key ] );
		}

	}

	/**
	 * Get the modules array.
	 *
	 * @static
	 * @access public
	 * @since 3.0.0
	 * @return array
	 */
	public static function get_modules() {

		return self::$modules;

	}

	/**
	 * Get the array of active modules (objects).
	 *
	 * @static
	 * @access public
	 * @since 3.0.0
	 * @return array
	 */
	public static function get_active_modules() {

		return self::$active_modules;

	}

}
