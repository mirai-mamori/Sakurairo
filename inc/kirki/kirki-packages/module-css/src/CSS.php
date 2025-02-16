<?php
/**
 * Handles the CSS Output of fields.
 *
 * @package     Kirki
 * @category    Modules
 * @author      Themeum
 * @copyright   Copyright (c) 2023, Themeum
 * @license    https://opensource.org/licenses/MIT
 * @since       3.0.0
 */

namespace Kirki\Module;

use Kirki\Compatibility\Kirki;
use Kirki\Util\Helper;
use Kirki\Compatibility\Values;
use Kirki\Module\CSS\Generator;

/**
 * The Module object.
 */
class CSS {

	/**
	 * The CSS array
	 *
	 * @access public
	 * @var array
	 */
	public static $css_array = array();

	/**
	 * An array of fields to be processed.
	 *
	 * @static
	 * @access protected
	 * @since 1.0
	 * @var array
	 */
	protected static $fields = array();

	/**
	 * Field option types.
	 *
	 * @static
	 * @access protected
	 * @since 1.0
	 * @var array
	 */
	protected static $field_option_types = array();

	/**
	 * The default handle for kirki's styles enqueue.
	 *
	 * @since 4.0
	 * @access private
	 * @static
	 *
	 * @var string
	 */
	private static $css_handle = 'kirki-styles';

	/**
	 * The default id for kirki's inline style tag.
	 *
	 * @since 4.0.23
	 * @access private
	 * @static
	 *
	 * @var string
	 */
	private static $inline_styles_id = 'kirki-inline-styles';

	/**
	 * Constructor
	 *
	 * @access public
	 */
	public function __construct() {

		add_action( 'kirki_field_init', array( $this, 'field_init' ), 10, 2 );
		add_action( 'init', array( $this, 'init' ) );

	}

	/**
	 * Init.
	 *
	 * @access public
	 */
	public function init() {

		new \Kirki\Module\Webfonts();

		add_action( 'wp', array( $this, 'print_styles_action' ) );

		if ( ! apply_filters( 'kirki_output_inline_styles', true ) ) {
			$config   = apply_filters( 'kirki_config', array() );
			$priority = 999;

			if ( isset( $config['styles_priority'] ) ) {
				$priority = absint( $config['styles_priority'] );
			}

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), $priority );
		} else {
			add_action( 'wp_head', array( $this, 'print_styles_inline' ), 999 );
		}

	}

	/**
	 * Runs when a field gets added.
	 * Adds fields to this object so their styles can later be generated.
	 *
	 * @access public
	 * @since 1.0
	 * @param array  $args   The field args.
	 * @param Object $object The field object.
	 * @return void
	 */
	public function field_init( $args, $object ) {

		if ( ! isset( $args['output'] ) ) {
			$args['output'] = array();
		}

		self::$field_option_types[ $args['settings'] ] = isset( $args['option_type'] ) ? $args['option_type'] : 'theme_mod';

		if ( ! is_array( $args['output'] ) ) {
			/* translators: The field ID where the error occurs. */
			_doing_it_wrong( __METHOD__, sprintf( esc_html__( '"output" invalid format in field %s. The "output" argument should be defined as an array of arrays.', 'kirki' ), esc_html( $args['settings'] ) ), '3.0.10' );
			$args['output'] = array(
				array(
					'element' => $args['output'],
				),
			);
		}

		// Convert to array of arrays if needed.
		if ( isset( $args['output']['element'] ) ) {
			/* translators: The field ID where the error occurs. */
			_doing_it_wrong( __METHOD__, sprintf( esc_html__( '"output" invalid format in field %s. The "output" argument should be defined as an array of arrays.', 'kirki' ), esc_html( $args['settings'] ) ), '3.0.10' );
			$args['output'] = array( $args['output'] );
		}

		if ( empty( $args['output'] ) ) {
			return;
		}

		foreach ( $args['output'] as $key => $output ) {
			if ( empty( $output ) || ! isset( $output['element'] ) ) {
				unset( $args['output'][ $key ] );
				continue;
			}
			if ( ! isset( $output['sanitize_callback'] ) && isset( $output['callback'] ) ) {
				$args['output'][ $key ]['sanitize_callback'] = $output['callback'];
			}

			// Convert element arrays to strings.
			if ( isset( $output['element'] ) && is_array( $output['element'] ) ) {
				$args['output'][ $key ]['element'] = array_unique( $args['output'][ $key ]['element'] );
				sort( $args['output'][ $key ]['element'] );

				// Trim each element in the array.
				foreach ( $args['output'][ $key ]['element'] as $index => $element ) {
					$args['output'][ $key ]['element'][ $index ] = trim( $element );
				}
				$args['output'][ $key ]['element'] = implode( ',', $args['output'][ $key ]['element'] );
			}

			// Fix for https://github.com/aristath/kirki/issues/1659#issuecomment-346229751.
			$args['output'][ $key ]['element'] = str_replace( array( "\t", "\n", "\r", "\0", "\x0B" ), ' ', $args['output'][ $key ]['element'] );
			$args['output'][ $key ]['element'] = trim( preg_replace( '/\s+/', ' ', $args['output'][ $key ]['element'] ) );
		}

		if ( ! isset( $args['type'] ) && isset( $object->type ) ) {
			$args['type'] = $object->type;
		}

		self::$fields[] = $args;

	}

	/**
	 * Print styles inline.
	 *
	 * @access public
	 * @since 3.0.36
	 * @return void
	 */
	public function print_styles_inline() {

		$should_print = true;

		if ( defined( 'KIRKI_NO_OUTPUT' ) && true === KIRKI_NO_OUTPUT ) {
			$should_print = false;
		}

		ob_start();
		$this->print_styles();
		$inline_styles = ob_get_clean();

		/**
		 * If KIRKI_NO_OUTPUT constant is defined (and is true), but typography field is defined, then print it.
		 * Otherwise, the typography field might be broken (missing font-family) if the font-face is not outputted.
		 */
		if ( ! $should_print && false !== stripos($inline_styles, '@font-face') ) {
			$should_print = true;
		}

		if ( ! $should_print ) {
			return;
		}

		$inline_styles_id = apply_filters( 'kirki_inline_styles_id', self::$inline_styles_id );

		echo '<style id="' . esc_attr( $inline_styles_id ) . '">';
		echo $inline_styles;
		echo '</style>';

	}

	/**
	 * Enqueue the styles.
	 *
	 * @access public
	 * @since 3.0.36
	 * @return void
	 */
	public function enqueue_styles() {

		$args = array(
			'action' => apply_filters( 'kirki_styles_action_handle', self::$css_handle ),
		);

		if ( is_admin() ) {
			global $current_screen;

			/**
			 * This `enqueue_styles` method is also hooked into `enqueue_block_editor_assets`.
			 * It needs to be excluded from customize control page.
			 *
			 * Why not simply excluding all admin area except gutenberg editing interface?
			 * Because it would be nice to let the possibility open
			 * if a 3rd party plugin will output gutenberg syles somewhere in admin area.
			 *
			 * Example of possibility:
			 * In the future, Ultimate Dashboard Pro's admin page feature might supports Gutenberg.
			 */
			if ( is_object( $current_screen ) && property_exists( $current_screen, 'id' ) && 'customize' === $current_screen->id ) {
				return;
			}

			if ( property_exists( $current_screen, 'is_block_editor' ) && 1 === (int) $current_screen->is_block_editor ) {
				$args['editor'] = '1';
			}
		}

		// Enqueue the dynamic stylesheet.
		wp_enqueue_style(
			self::$css_handle,
			add_query_arg( $args, home_url() ),
			array(),
			'4.0'
		);

	}

	/**
	 * Prints the styles as an enqueued file.
	 *
	 * @access public
	 * @since 3.0.36
	 * @return void
	 */
	public function print_styles_action() {

		/**
		 * Note to code reviewers:
		 * There is no need for a nonce check here, we're only checking if this is a valid request or not.
		 */

		// phpcs:ignore WordPress.Security.NonceVerification
		if ( empty( $_GET['action'] ) || apply_filters( 'kirki_styles_action_handle', self::$css_handle ) !== $_GET['action'] ) {
			return;
		}

		// This is a stylesheet.
		header( 'Content-type: text/css' );
		$this->print_styles();
		exit;

	}

	/**
	 * Prints the styles.
	 *
	 * @access public
	 */
	public function print_styles() {

		// Go through all configs.
		$configs = Kirki::$config;

		foreach ( $configs as $config_id => $args ) {
			if ( defined( 'KIRKI_NO_OUTPUT' ) && true === KIRKI_NO_OUTPUT ) {
				continue;
			}

			if ( isset( $args['disable_output'] ) && true === $args['disable_output'] ) {
				continue;
			}

			$styles = self::loop_controls( $config_id );
			$styles = apply_filters( "kirki_{$config_id}_dynamic_css", $styles );

			if ( ! empty( $styles ) ) {
				/**
				 * Note to code reviewers:
				 *
				 * Though all output should be run through an escaping function, this is pure CSS.
				 *
				 * When used in the print_styles_action() method the PHP header() call makes the browser interpret it as such.
				 * No code, script or anything else can be executed from inside a stylesheet.
				 *
				 * When using in the print_styles_inline() method the wp_strip_all_tags call we use below
				 * strips anything that has the possibility to be malicious, and since this is inslide a <style> tag
				 * it can only be interpreted by the browser as such.
				 * wp_strip_all_tags() excludes the possibility of someone closing the <style> tag and then opening something else.
				 */
				echo wp_strip_all_tags( $styles ); // phpcs:ignore WordPress.Security.EscapeOutput
			}
		}

		do_action( 'kirki_dynamic_css' );

	}

	/**
	 * Loop through all fields and create an array of style definitions.
	 *
	 * @static
	 * @access public
	 * @param string $config_id The configuration ID.
	 */
	public static function loop_controls( $config_id ) {

		// Get an instance of the Generator class.
		// This will make sure google fonts and backup fonts are loaded.
		Generator::get_instance();

		$fields = self::get_fields_by_config( $config_id );

		// Compatibility with v3 API.
		if ( class_exists( '\Kirki\Compatibility\Kirki' ) ) {
			$fields = array_merge( \Kirki\Compatibility\Kirki::$fields, $fields );
		}

		$css = array();

		// Early exit if no fields are found.
		if ( empty( $fields ) ) {
			return;
		}

		foreach ( $fields as $field ) {

			// Only process fields that belong to $config_id.
			if ( isset( $field['kirki_config'] ) && $config_id !== $field['kirki_config'] ) {
				continue;
			}

			if ( true === apply_filters( "kirki_{$config_id}_css_skip_hidden", true ) ) {

				// Only continue if field dependencies are met.
				if ( ( isset( $field['required'] ) && ! empty( $field['required'] ) ) || ( isset( $field['active_callback'] ) && ! empty( $field['active_callback'] ) ) ) {
					$valid = true;

					// If $field is using active_callback instead of required.
					if ( ! isset( $field['required'] ) || empty( $field['required'] ) ) {
						if ( isset( $field['active_callback'] ) && ! empty( $field['active_callback'] ) ) {
							// The "active_callback" or "required" accepts array or callable as the value.
							if ( is_array( $field['active_callback'] ) || is_callable( $field['active_callback'] ) ) {
								$field['required'] = $field['active_callback'];
							}
						}
					}

					// At this point, we know that the "required" is set and is not empty.
					if ( is_array( $field['required'] ) ) {
						foreach ( $field['required'] as $requirement ) {
							if ( isset( $requirement['setting'] ) && isset( $requirement['value'] ) && isset( $requirement['operator'] ) && isset( self::$field_option_types[ $requirement['setting'] ] ) ) {
								$controller_value = Values::get_value( $config_id, $requirement['setting'] );

								if ( ! Helper::compare_values( $controller_value, $requirement['value'], $requirement['operator'] ) ) {
									$valid = false;
								}
							}
						}
					} elseif ( is_string( $field['required'] ) ) {
						$valid = '__return_true' === $field['required'] ? true : false;
					} elseif ( is_callable( $field['required'] ) ) {
						$valid = call_user_func( $field['required'] );
					}

					if ( ! $valid ) {
						continue;
					}
				}
			}

			// Only continue if $field['output'] is set.
			if ( isset( $field['output'] ) && ! empty( $field['output'] ) ) {
				$css = Helper::array_replace_recursive( $css, Generator::css( $field ) );

				// Add the globals.
				if ( isset( self::$css_array[ $config_id ] ) && ! empty( self::$css_array[ $config_id ] ) ) {
					Helper::array_replace_recursive( $css, self::$css_array[ $config_id ] );
				}
			}
		}

		$css = apply_filters( "kirki_{$config_id}_styles", $css );

		if ( is_array( $css ) ) {
			return Generator::styles_parse( Generator::add_prefixes( $css ) );
		}

	}

	/**
	 * Gets fields from self::$fields by config-id.
	 *
	 * @static
	 * @access private
	 * @since 1.0
	 * @param string $config_id The config-ID.
	 * @return array
	 */
	private static function get_fields_by_config( $config_id ) {

		$fields = array();

		foreach ( self::$fields as $field ) {
			if (
				( isset( $field['kirki_config'] ) && $config_id === $field['kirki_config'] ) ||
				(
					( 'global' === $config_id || ! $config_id ) &&
					( ! isset( $field['kirki_config'] ) || 'global' === $field['kirki_config'] || ! $field['kirki_config'] )
				)
			) {
				$fields[] = $field;
			}
		}

		return $fields;

	}

}
