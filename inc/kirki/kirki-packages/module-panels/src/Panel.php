<?php
/**
 * Creates a new panel.
 *
 * @package    Kirki
 * @subpackage Custom Sections Module
 * @copyright  Copyright (c) 2023, Themeum
 * @license    https://opensource.org/licenses/MIT
 * @since      1.0
 */

namespace Kirki;

/**
 * Panel.
 */
class Panel {

	/**
	 * The panel ID.
	 *
	 * @access protected
	 * @since 1.0
	 * @var string
	 */
	protected $id;

	/**
	 * The panel arguments.
	 *
	 * @access protected
	 * @since 1.0
	 * @var array
	 */
	protected $args;

	/**
	 * An array of our panel types.
	 *
	 * @access private
	 * @var array
	 */
	private $panel_types = [
		'default'      => 'WP_Customize_Panel',
		'kirki-nested' => '\Kirki\Panel_Types\Nested',
	];

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $id   The panel ID.
	 * @param array  $args The panel args.
	 */
	public function __construct( $id, $args = [] ) {
		$this->id   = $id;
		$this->args = $args;

		$this->panel_types = apply_filters( 'kirki_panel_types', $this->panel_types );

		if ( $this->args ) {
			add_action( 'customize_register', [ $this, 'add_panel' ] );
		}
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'enqueue_scrips' ] );
	}

	/**
	 * Add the panel using the Customizer API.
	 *
	 * @access public
	 * @since 1.0
	 * @param object $wp_customize The customizer object.
	 */
	public function add_panel( $wp_customize ) {

		// Figure out the type of this panel.
		$this->args['type'] = isset( $this->args['type'] ) ? $this->args['type'] : 'default';
		if ( isset( $this->args['panel'] ) && ! empty( $this->args['panel'] ) ) {
			$this->args['type'] = 'kirki-nested';
		}
		$this->args['type'] = false === strpos( $this->args['type'], 'kirki-' ) ? 'kirki-' . $this->args['type'] : $this->args['type'];
		$this->args['type'] = 'kirki-default' === $this->args['type'] ? 'default' : $this->args['type'];

		// Get the class we'll be using to create this panel.
		$panel_classname = $this->panel_types[ $this->args['type'] ];

		// Fallback to the default panel type if the custom class doesn't exist.
		$panel_classname = class_exists( $panel_classname ) ? $panel_classname : 'WP_Customize_Panel';

		// Add the panel.
		$wp_customize->add_panel(
			new $panel_classname(
				$wp_customize,
				$this->id,
				apply_filters( 'kirki_panel_args', $this->args, $this->id )
			)
		);

		// Run an action after the panel has been added.
		do_action( 'kirki_panel_added', $this->id, $this->args );
	}

	/**
	 * Removes the panel.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function remove() {
		add_action( 'customize_register', [ $this, 'remove_panel' ], 9999 );
	}

	/**
	 * Add the panel using the Customizer API.
	 *
	 * @access public
	 * @since 1.0
	 * @param object $wp_customize The customizer object.
	 */
	public function remove_panel( $wp_customize ) {
		$wp_customize->remove_panel( $this->id );
	}
	
	/**
	 * Enqueues any necessary scripts and styles.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function enqueue_scrips() {
		wp_enqueue_script( 'kirki-panels', URL::get_from_path( __DIR__ . '/script.js' ), [ 'jquery', 'customize-base', 'customize-controls' ], '1.0', false );
	}
}
