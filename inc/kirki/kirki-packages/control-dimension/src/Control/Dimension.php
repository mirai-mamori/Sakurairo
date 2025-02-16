<?php
/**
 * Customizer Control: dimension
 *
 * @package   kirki-framework/control-dimension
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Control;

use Kirki\Control\Base;
use Kirki\URL;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * A text control with validation for CSS units.
 *
 * @since 1.0
 */
class Dimension extends Base {

	/**
	 * The control type.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-dimension';

	/**
	 * The version. Used in scripts & styles for cache-busting.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public static $control_ver = '1.0';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function enqueue() {
		parent::enqueue();

		// Enqueue the script.
		wp_enqueue_script( 'kirki-control-dimension', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.js' ), [ 'jquery', 'customize-base', 'kirki-control-base' ], self::$control_ver, false );

		// Enqueue the style.
		wp_enqueue_style( 'kirki-control-dimension-style', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.css' ), [], self::$control_ver );

		wp_localize_script(
			'kirki-control-dimension',
			'dimensionkirkiL10n',
			[
				'invalid-value' => esc_html__( 'Invalid Value', 'kirki' ),
			]
		);
	}

	/**
	 * Get the URL for the control folder.
	 *
	 * This is a static method because there are more controls in the Kirki framework
	 * that use colorpickers, and they all need to enqueue the same assets.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @return string
	 */
	public static function get_control_path_url() {
		return URL::get_from_path( dirname( __DIR__ ) );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @access public
	 * @since 1.0
	 * @see WP_Customize_Control::to_json()
	 * @return void
	 */
	public function to_json() {

		$input_class = 'kirki-control-input';

		if ( isset( $this->input_attrs['class'] ) ) {
			$input_class .= ' ' . $this->input_attrs['class'];
			unset( $this->input_attrs['class'] );
		}

		// Get the basics from the parent class.
		parent::to_json();

		// Input class name.
		$this->json['inputClass'] = $input_class;

		// Label position.
		$this->json['labelPosition'] = 'top';

		if ( isset( $this->choices['label_position'] ) && 'bottom' === $this->choices['label_position'] ) {
			$this->json['labelPosition'] = 'bottom';
		}

		// Input id.
		$this->json['inputId'] = '_customize-input-' . $this->id;

	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @access protected
	 * @since 1.0
	 * @return void
	 */
	protected function content_template() {
		?>

		<div class="kirki-control-form <# if ('bottom' === data.labelPosition) { #>has-label-bottom<# } #>">
			<# if ( 'top' === data.labelPosition ) { #>
				<label class="kirki-control-label" for="{{ data.inputId }}">
					<# if ( data.label ) { #><span class="customize-control-title">{{{ data.label }}}</span><# } #>
					<# if ( data.description ) { #><span class="description customize-control-description">{{{ data.description }}}</span><# } #>
				</label>
			<# } #>

			<div class="kirki-input-control">
				<# var val = ( data.value && _.isString( data.value ) ) ? data.value.replace( '%%', '%' ) : ''; #>
				<input id="{{ data.inputId }}" {{{ data.inputAttrs }}} type="text" value="{{ val }}" class="{{ data.inputClass }}" />
			</div>

			<# if ( 'bottom' === data.labelPosition ) { #>
				<label class="kirki-control-label" for="{{ data.inputId }}">
					<# if ( data.label ) { #>{{{ data.label }}} <# } #>
				</label>
			<# } #>
		</div>

		<?php
	}
}
