<?php
/**
 * WordPress Customize Setting classes
 *
 * @package Kirki
 * @subpackage Modules
 * @since 3.0.0
 */

namespace Kirki\Util\Setting;

/**
 * Handles saving and sanitizing of user-meta.
 *
 * @since 3.0.0
 * @see WP_Customize_Setting
 */
class Site_Option extends \WP_Customize_Setting {

	/**
	 * Type of customize settings.
	 *
	 * @access public
	 * @since 3.0.0
	 * @var string
	 */
	public $type = 'site_option';

	/**
	 * Get the root value for a setting, especially for multidimensional ones.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @param mixed $default Value to return if root does not exist.
	 * @return mixed
	 */
	protected function get_root_value( $default = null ) {
		return get_site_option( $this->id_data['base'], $default );
	}

	/**
	 * Set the root value for a setting, especially for multidimensional ones.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @param mixed $value Value to set as root of multidimensional setting.
	 * @return bool Whether the multidimensional root was updated successfully.
	 */
	protected function set_root_value( $value ) {
		return update_site_option( $this->id_data['base'], $value );
	}

	/**
	 * Save the value of the setting, using the related API.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @param mixed $value The value to update.
	 * @return bool The result of saving the value.
	 */
	protected function update( $value ) {
		return $this->set_root_value( $value );
	}

	/**
	 * Fetch the value of the setting.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @return mixed The value.
	 */
	public function value() {
		return $this->get_root_value( $this->default );
	}
}
