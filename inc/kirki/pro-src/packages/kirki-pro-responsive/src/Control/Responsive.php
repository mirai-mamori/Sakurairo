<?php
/**
 * Customizer Control: kirki-responsive.
 *
 * @package   kirki-pro-responsive
 * @since     1.0
 */

namespace Kirki\Pro\Control;

use Kirki\Control\Base;
use Kirki\URL;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Responsive control
 */
class Responsive extends Base {

	/**
	 * The control type.
	 *
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-responsive';

	/**
	 * The version. Used in scripts & styles for cache-busting.
	 *
	 * @since 1.0
	 * @var string
	 */
	public static $control_ver = '1.0.0';

	/**
	 * Enqueue control related styles/scripts.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function enqueue() {

		parent::enqueue();

		// Enqueue the style.
		wp_enqueue_style( 'kirki-control-responsive', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.css' ), [], self::$control_ver );

		// Enqueue the script.
		wp_enqueue_script( 'kirki-control-responsive', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.js' ), [ 'jquery', 'customize-base', 'customize-controls' ], self::$control_ver, true );

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

		// Get the basics from the parent class.
		parent::to_json();

		$device_icons = [
			'desktop' => 'dashicons-desktop',
			'tablet'  => 'dashicons-tablet',
			'mobile'  => 'dashicons-smartphone',
		];

		$devices = isset( $this->choices['devices'] ) ? $this->choices['devices'] : [];

		$device_menu = '';
		$loop_index  = 0;

		foreach ( $devices as $device ) {
			$loop_index++;

			$device_menu .= '
			<li class="kirki-device-button kirki-device-button-' . $device . ( 1 === $loop_index ? ' is-active' : '' ) . '" data-kirki-device="' . esc_attr( $device ) . '">
				<i class="dashicons ' . esc_html( $device_icons[ $device ] ) . '"></i>
			</li>
			';
		}

		$this->json['deviceMenu'] = $device_menu;

	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 * @since 1.0
	 */
	protected function content_template() {
		?>

		<div class="kirki-responsive" data-kirki-responsive-id="{{{ data.settings.default }}}">
			<div class="kirki-control-label">
				<div class="customize-control-title">
					<span>{{{ data.label }}}</span>
				</div>
				<# if (data.description) { #>
					<div class="customize-control-description">{{{ data.description }}}</div>
				<# } #>
			</div>
			<ul class="kirki-device-buttons">
				{{{ data.deviceMenu }}}
			</ul>
		</div>

		<?php
	}

}
