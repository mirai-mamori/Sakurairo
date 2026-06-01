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
class User_Meta extends \WP_Customize_Setting {

	/**
	 * Type of customize settings.
	 *
	 * @access public
	 * @since 3.0.0
	 * @var string
	 */
	public $type = 'user_meta';

	/**
	 * Get the root value for a setting, especially for multidimensional ones.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @param mixed $default Value to return if root does not exist.
	 * @return mixed
	 */
	protected function get_root_value( $default = null ) {
		$id_base = $this->id_data['base'];

		// Get all user-meta.
		// We'll use this to check if the value is set or not,
		// in order to figure out if we need to return the default value.
		$user_meta = get_user_meta( get_current_user_id() );

		// Get the single meta.
		$single_meta = get_user_meta( get_current_user_id(), $id_base, true );

		if ( isset( $user_meta[ $id_base ] ) ) {
			return $single_meta;
		}
		return $default;
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
		$id_base = $this->id_data['base'];

		// First delete the current user-meta.
		// We're doing this to avoid duplicate entries.
		delete_user_meta( get_current_user_id(), $id_base );

		// Update the user-meta.
		return update_user_meta( get_current_user_id(), $id_base, $value );
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
