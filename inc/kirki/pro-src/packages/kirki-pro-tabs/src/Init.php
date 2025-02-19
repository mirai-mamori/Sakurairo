<?php
/**
 * Init the Kirki PRO's tab package.
 *
 * @package kirki-pro-tabs
 * @since 1.0.0
 */

namespace Kirki\Pro\Tabs;

use Kirki\Pro\Field\Tabs;

/**
 * Manage the tabs package.
 */
class Init {

	/**
	 * The class constructor.
	 */
	public function __construct() {

		add_filter( 'kirki_control_types', [ $this, 'control_type' ] );
		add_filter( 'kirki_field_add_control_args', array( $this, 'filter_control_args' ), 10, 2 );
		add_action( 'kirki_section_init', [ $this, 'add_tab' ], 10, 2 );

	}

	/**
	 * The control type.
	 *
	 * @param array $control_types The existing control types.
	 */
	public function control_type( $control_types ) {

		$control_types['kirki-tab'] = 'Kirki\Pro\Control\Tabs';

		return $control_types;

	}

	/**
	 * Filter customizer's control arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param array                $args The field arguments.
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 *
	 * @return array
	 */
	public function filter_control_args( $args, $wp_customize ) {

		if ( isset( $args['tab'] ) ) {
			$tabs    = Tabs::$tabs;
			$section = $args['section'];

			if ( isset( $tabs[ $section ] ) ) {
				$tab_wrapper_attrs = array(
					'data-kirki-parent-tab-id'   => $section,
					'data-kirki-parent-tab-item' => isset( $args['tab'] ) ? $args['tab'] : '',
				);

				if ( isset( $args['wrapper_attrs'] ) ) {
					$args['wrapper_attrs'] = array_merge( $args['wrapper_attrs'], $tab_wrapper_attrs );
				} else {
					$args['wrapper_attrs'] = $tab_wrapper_attrs;
				}
			}
		}

		return $args;

	}

	/**
	 * Add the tab by creating custom control using Kirki API.
	 *
	 * @since 1.0.0
	 *
	 * @param string $section_id The The section id.
	 * @param array  $args The section args.
	 */
	public function add_tab( $section_id, $args ) {

		if ( ! isset( $args['tabs'] ) ) {
			return;
		}

		new \Kirki\Pro\Field\Tabs(
			[
				'settings' => 'kirki_tabs_' . $section_id,
				'section'  => $section_id,
				'priority' => 0,
				'choices'  => [
					'tabs' => $args['tabs'],
				],
			]
		);

	}

}
