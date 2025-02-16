<?php
/**
 * Creates a new Section.
 *
 * @package kirki-framework/module-sections
 * @copyright Copyright (c) 2023, Themeum
 * @license https://opensource.org/licenses/MIT
 * @since 1.0.0
 */

namespace Kirki;

/**
 * Section.
 */
class Section {

	/**
	 * The section ID.
	 *
	 * @access protected
	 * @since 1.0.0
	 * @var string
	 */
	protected $id;

	/**
	 * The section arguments.
	 *
	 * @access protected
	 * @since 1.0.0
	 * @var array
	 */
	protected $args;

	/**
	 * An array of our section types.
	 *
	 * @access private
	 * @var array
	 */
	private $section_types = [
		'kirki-expanded' => '\Kirki\Section_Types\Expanded',
		'kirki-nested'   => '\Kirki\Section_Types\Nested',
		'kirki-link'     => '\Kirki\Section_Types\Link',
		'kirki-outer'    => '\Kirki\Section_Types\Outer',
	];

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param string $id   The section ID.
	 * @param array  $args The section args.
	 */
	public function __construct( $id, $args = [] ) {
		$this->id   = $id;
		$this->args = $args;

		$this->section_types = apply_filters( 'kirki_section_types', $this->section_types );

		do_action( 'kirki_section_init', $id, $args );

		add_action( 'customize_register', [ $this, 'register_section_types' ] );

		if ( $this->args ) {
			add_action( 'customize_register', [ $this, 'add_section' ] );
		}
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'enqueue_scrips' ] );
		add_action( 'customize_controls_print_footer_scripts', [ $this, 'outer_sections_css' ] );
	}

	/**
	 * Register section types.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param object $wp_customize The customizer object.
	 * @return void
	 */
	public function register_section_types( $wp_customize ) {
		foreach ( $this->section_types as $section_type ) {
			$wp_customize->register_section_type( $section_type );
		}
	}

	/**
	 * Add the section using the Customizer API.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param object $wp_customize The customizer object.
	 */
	public function add_section( $wp_customize ) {

		// Figure out the type of this section.
		$this->args['type'] = isset( $this->args['type'] ) ? $this->args['type'] : 'default';
		if ( isset( $this->args['section'] ) && ! empty( $this->args['section'] ) ) {
			$this->args['type'] = 'kirki-nested';

			// We need to check if the parent section is nested inside a panel.
			$parent_section = $wp_customize->get_section( $this->args['section'] );
			if ( $parent_section && isset( $parent_section->panel ) ) {
				$this->args['panel'] = $parent_section->panel;
			}
		}
		$this->args['type'] = false === strpos( $this->args['type'], 'kirki-' ) ? 'kirki-' . $this->args['type'] : $this->args['type'];

		// Get the class we'll be using to create this section.
		$section_classname = '\WP_Customize_Section';
		if ( isset( $this->section_types[ $this->args['type'] ] ) ) {
			$section_classname = $this->section_types[ $this->args['type'] ];
		}

		if ( isset( $this->args['type'] ) && 'kirki-outer' === $this->args['type'] ) {
			$this->args['type'] = 'outer';
			$section_classname  = 'WP_Customize_Section'; // ? Bagus: we should be using `\` (backslash) right? Lookk at above.
		}

		// Add the section.
		$wp_customize->add_section(
			new $section_classname(
				$wp_customize,
				$this->id,
				apply_filters( 'kirki_section_args', $this->args, $this->id )
			)
		);

		// Run an action after the section has been added.
		do_action( 'kirki_section_added', $this->id, $this->args );
	}

	/**
	 * Removes the section.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function remove() {
		add_action( 'customize_register', [ $this, 'remove_section' ], 9999 );
	}

	/**
	 * Add the section using the Customizer API.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param object $wp_customize The customizer object.
	 */
	public function remove_section( $wp_customize ) {
		$wp_customize->remove_section( $this->id );
	}

	/**
	 * Enqueues any necessary scripts and styles.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function enqueue_scrips() {
		wp_enqueue_style( 'kirki-sections', URL::get_from_path( __DIR__ . '/styles.css' ), [], '1.0' );
		wp_enqueue_script( 'kirki-sections', URL::get_from_path( __DIR__ . '/script.js' ), [ 'jquery', 'customize-base', 'customize-controls' ], '1.0', false );
	}

	/**
	 * Generate CSS for the outer sections.
	 * These are by default hidden, we need to expose them.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function outer_sections_css() {
		if ( isset( $this->args['type'] ) && ( 'outer' === $this->args['type'] || 'kirki-outer' === $this->args['type'] ) ) {
			echo '<style>#customize-theme-controls li#accordion-section-' . esc_html( $this->id ) . ',li#sub-accordion-section-' . esc_html( $this->id ) . '{display:list-item!important;}</style>';
		}
	}
}
