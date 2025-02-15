<?php
/**
 * Override field methods.
 *
 * @package   kirki-framework/control-fontawesome
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Field;

use Kirki\Field\Select;

/**
 * Field overrides.
 */
class FontAwesome extends Select {

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

			ob_start();
			include 'fontawesome.json'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude
			$font_awesome_json = ob_get_clean();

			$fa_array        = (array) json_decode( $font_awesome_json, true );
			$args['choices'] = [];
			foreach ( $fa_array['icons'] as $icon ) {
				if ( ! isset( $icon['id'] ) || ! isset( $icon['name'] ) ) {
					continue;
				}
				$args['choices'][ $icon['id'] ] = $icon['name'];
			}
		}
		return $args;
	}
}
