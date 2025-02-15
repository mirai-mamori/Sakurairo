<?php
/**
 * Customizer Control: kirki-tab.
 *
 * @package   kirki-pro-tabs
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
 * Tab control
 */
class Tabs extends Base {

	/**
	 * The control type.
	 *
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-tab';

	/**
	 * The version. Used in scripts & styles for cache-busting.
	 *
	 * @since 1.0
	 * @var string
	 */
	public static $control_ver = '1.0.0.1';

	/**
	 * Enqueue control related styles/scripts.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function enqueue() {

		parent::enqueue();

		// Enqueue the style.
		wp_enqueue_style( 'kirki-control-tabs', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.css' ), [], self::$control_ver );

		// Enqueue the script.
		wp_enqueue_script( 'kirki-control-tabs', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.js' ), [ 'jquery', 'customize-base', 'customize-controls' ], self::$control_ver, true );

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

		$tabs = isset( $this->choices['tabs'] ) ? $this->choices['tabs'] : [];

		$tab_menu   = '';
		$loop_index = 0;

		foreach ( $tabs as $tab_id => $tab_args ) {
			$loop_index++;

			$tab_menu .= '
			<li class="kirki-tab-menu-item' . ( 1 === $loop_index ? ' is-active' : '' ) . '" data-kirki-tab-menu-id="' . esc_attr( $tab_id ) . '">
				<a href="#" class="kirki-tab-link">' . esc_html( $tab_args['label'] ) . '</a>
			</li>
			';
		}

		$this->json['choices']['section'] = $this->section;

		$this->json['tabMenu'] = $tab_menu;

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

		<div class="kirki-tab" data-kirki-tab-id="{{{ data.section }}}">
			<ul class="kirki-tab-menu">
				{{{ data.tabMenu }}}
			</ul>
		</div>

		<?php
	}

}
