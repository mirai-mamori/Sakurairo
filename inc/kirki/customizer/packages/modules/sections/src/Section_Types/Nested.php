<?php
/**
 * Nested section.
 *
 * @package kirki-framework/module-sections
 * @copyright Copyright (c) 2023, Themeum
 * @license https://opensource.org/licenses/MIT
 * @since 1.0.0
 */

namespace Kirki\Section_Types;

/**
 * Nested section.
 */
class Nested extends \WP_Customize_Section {

	/**
	 * The parent section.
	 *
	 * @access public
	 * @since 1.0.0
	 * @var string
	 */
	public $section;

	/**
	 * The section type.
	 *
	 * @access public
	 * @since 1.0.0
	 * @var string
	 */
	public $type = 'kirki-nested';

	/**
	 * Gather the parameters passed to client JavaScript via JSON.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return array The array to be exported to the client as JSON.
	 */
	public function json() {
		$array = wp_array_slice_assoc(
			(array) $this,
			[
				'id',
				'description',
				'priority',
				'panel',
				'type',
				'description_hidden',
				'section',
			]
		);

		$array['title']          = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
		$array['content']        = $this->get_content();
		$array['active']         = $this->active();
		$array['instanceNumber'] = $this->instance_number;

		$array['customizeAction'] = esc_html__( 'Customizing', 'kirki' );
		if ( $this->panel ) {
			/* translators: The title. */
			$array['customizeAction'] = sprintf( esc_html__( 'Customizing &#9656; %s', 'kirki' ), esc_html( $this->manager->get_panel( $this->panel )->title ) );
		}
		return $array;
	}
}
