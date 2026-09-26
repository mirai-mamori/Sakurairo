<?php
/**
 * Override field methods
 *
 * @package   kirki-framework/control-select
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Field;

/**
 * Field overrides.
 *
 * @since 1.0
 */
class Dropdown_Pages extends Select {

	/**
	 * Filter arguments before creating the control.
	 *
	 * @access public
	 * @since 0.1
	 * @param array                $args         The field arguments.
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 * @return array
	 */
	public function filter_control_args( $args, $wp_customize ) {
		if ( $args['settings'] === $this->args['settings'] ) {
			$args = parent::filter_control_args( $args, $wp_customize );

			$all_pages = get_pages();
			foreach ( $all_pages as $page ) {
				$args['choices'][ $page->ID ] = $page->post_title;
			}
		}
		return $args;
	}
}
