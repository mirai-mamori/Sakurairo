<?php
/**
 * Handles selective refreshes for Kirki fields.
 *
 * @package kirki-framework/module-selective-refresh
 * @author Themeum
 * @copyright Copyright (c) 2023, Themeum
 * @license https://opensource.org/licenses/MIT
 * @since 1.0.0
 */

namespace Kirki\Module;

/**
 * Handle selective refreshes introduced in WordPress 4.5.
 */
class Selective_Refresh {

	/**
	 * An array of fields with selective refreshes.
	 *
	 * @static
	 * @access private
	 * @since 1.0.0
	 * @var array
	 */
	private static $fields = [];

	/**
	 * Adds any necessary actions & filters.
	 *
	 * @access public
	 */
	public function __construct() {
		add_filter( 'kirki_field_add_setting_args', [ $this, 'filter_setting_args' ], 10, 2 );
	}

	/**
	 * Filter setting args.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param array                $field        The field arguments.
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 * @return array
	 */
	public function filter_setting_args( $field, $wp_customize ) {

		// Abort if selective refresh is not available.
		if ( ! isset( $wp_customize->selective_refresh ) ) {
			return $field;
		}

		if ( isset( $field['partial_refresh'] ) && ! empty( $field['partial_refresh'] ) ) {

			// Start going through each item in the array of partial refreshes.
			foreach ( $field['partial_refresh'] as $partial_refresh => $partial_refresh_args ) {

				// If we have all we need, create the selective refresh call.
				if ( isset( $partial_refresh_args['render_callback'] ) && isset( $partial_refresh_args['selector'] ) ) {
					$partial_refresh_args = wp_parse_args(
						$partial_refresh_args,
						[
							'settings' => $field['settings'],
						]
					);
					$wp_customize->selective_refresh->add_partial( $partial_refresh, $partial_refresh_args );

					// If partial refresh is set, change the transport to auto.
					$field['transport'] = 'postMessage';
				}
			}
		}

		return $field;
	}
}
