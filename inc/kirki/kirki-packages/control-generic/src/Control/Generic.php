<?php
/**
 * Customizer Control: kirki-generic.
 *
 * @package   kirki-framework/control-generic
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Control;

use Kirki\Control\Base;
use Kirki\URL;

/**
 * A generic and pretty abstract control.
 * Allows for great manipulation using the field's "choices" argumnent.
 *
 * @since 1.0
 */
class Generic extends Base {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'kirki-generic';

	/**
	 * The version. Used in scripts & styles for cache-busting.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public static $control_ver = '1.0.2';

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
		wp_enqueue_script( 'kirki-control-generic', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.js' ), [ 'jquery', 'customize-base', 'kirki-control-base' ], self::$control_ver, false );

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
	 * @since 1.1
	 * @return void
	 */
	protected function content_template() {
		?>
		<label class="customize-control-label" for="{{ ! data.choices.id ? 'customize-input-' + data.id : data.choices.id }}">
			<span class="customize-control-title">{{{ data.label }}}</span>
			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>
		</label>
		<div class="kirki-control-form">
			<# element = ( data.choices.element ) ? data.choices.element : 'input'; #>

			<# if ( 'textarea' === element ) { #>
				<textarea
					{{{ data.inputAttrs }}}
					{{ data.link.replace(/"/g, '') }}
					<# if ( ! data.choices.id ) { #>
						id="{{'customize-input-' + data.id}}"
					<# } #>
					<# _.each( data.choices, function( val, key ) { #>
						{{ key }}="{{ val }}"
					<# }); #>
				>{{{ data.value }}}</textarea>
			<# } else { #>
				<{{ element }}
					{{{ data.inputAttrs }}}
					value="{{ data.value }}"
					{{ data.link.replace(/"/g, '') }}

					<# if ( ! data.choices.id ) { #>
						id="{{'customize-input-' + data.id}}"
					<# } #>

					<# _.each( data.choices, function( val, key ) { #>
						{{ key }}="{{ val }}"
					<# } ); #>
				<# if ( data.choices.content ) { #>>{{{ data.choices.content }}}</{{ element }}><# } else { #>/><# } #>
			<# } #>
		</div>
		<?php
	}
}
