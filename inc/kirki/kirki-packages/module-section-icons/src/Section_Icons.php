<?php
/**
 * Try to automatically generate the script necessary for adding icons to panels & section
 *
 * @package     Kirki
 * @category    Core
 * @author      Themeum
 * @copyright   Copyright (c) 2023, Themeum
 * @license    https://opensource.org/licenses/MIT
 * @since       3.0.0
 */

namespace Kirki\Module;

use Kirki\URL;

/**
 * Adds scripts for icons in sections & panels.
 */
class Section_Icons {

	/**
	 * An array of panels and sections with icons.
	 *
	 * @static
	 * @access private
	 * @var array
	 */
	private static $icons = [];

	/**
	 * An array of panels.
	 *
	 * @access private
	 * @since 1.0
	 * @var array
	 */
	private $panels = [];

	/**
	 * An array of sections.
	 *
	 * @access private
	 * @since 1.0
	 * @var array
	 */
	private $sections = [];

	/**
	 * The class constructor.
	 *
	 * @access public
	 */
	public function __construct() {

		add_action( 'customize_controls_enqueue_scripts', [ $this, 'customize_controls_enqueue_scripts' ], 99 );
		add_action( 'kirki_panel_added', [ $this, 'panel_added' ], 10, 2 );
		add_action( 'kirki_section_added', [ $this, 'section_added' ], 10, 2 );

	}

	/**
	 * Adds icon for a section/panel.
	 *
	 * @access public
	 * @since 3.0.0
	 * @param string $id      The panel or section ID.
	 * @param string $icon    The icon to add.
	 * @param string $context Lowercase 'section' or 'panel'.
	 */
	public function add_icon( $id, $icon, $context = 'section' ) {

		self::$icons[ $context ][ $id ] = trim( $icon );

	}

	/**
	 * Hooks in kirki_panel_added to populate $this->panels.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $id   The panel ID.
	 * @param array  $args The panel arguments.
	 */
	public function panel_added( $id, $args ) {

		if ( isset( $args['icon'] ) ) {
			$args['id']     = $id;
			$this->panels[] = $args;
		}

	}

	/**
	 * Hooks in kirki_section_added to populate $this->sections.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $id   The section ID.
	 * @param array  $args The section arguments.
	 */
	public function section_added( $id, $args ) {

		if ( isset( $args['icon'] ) ) {
			$args['id']       = $id;
			$this->sections[] = $args;
		}

	}

	/**
	 * Format the script in a way that will be compatible with WordPress.
	 */
	public function customize_controls_enqueue_scripts() {

		// Parse panels and find ones with icons.
		foreach ( $this->panels as $panel ) {
			$this->add_icon( $panel['id'], $panel['icon'], 'panel' );
		}

		// Parse sections and find ones with icons.
		foreach ( $this->sections as $section ) {
			$this->add_icon( $section['id'], $section['icon'], 'section' );
		}

		wp_enqueue_script( 'kirki_panel_and_section_icons', URL::get_from_path( __DIR__ . '/icons.js' ), [ 'jquery', 'customize-base', 'customize-controls' ], '1.0', true );
		wp_localize_script( 'kirki_panel_and_section_icons', 'kirkiIcons', self::$icons );

	}

}
