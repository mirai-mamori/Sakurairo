<?php
/**
 * Customizer Control: image.
 *
 * @package   kirki-framework/control-image
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Control;

use Kirki\Control\Base;
use Kirki\URL;

/**
 * Adds the image control.
 *
 * @since 1.0
 */
class Image extends Base {

	/**
	 * The control type.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-image';

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
	 */
	public function enqueue() {
		parent::enqueue();

		// Enqueue the script.
		wp_enqueue_script( 'kirki-control-image', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.js' ), [ 'jquery', 'customize-base', 'kirki-control-base', 'wp-mediaelement', 'media-upload', 'wp-i18n' ], self::$control_ver, false );
		wp_set_script_translations( 'kirki-control-image', 'kirki' );
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
		<label>
			<span class="customize-control-title">{{{ data.label }}}</span>
			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>
		</label>
		<div class="image-wrapper attachment-media-view image-upload">
			<# url = ( _.isObject( data.value ) && ! _.isUndefined( data.value.url ) ) ? data.value.url : data.value; #>
			<# if ( data.value.url || '' !== url ) { #>
				<div class="thumbnail thumbnail-image">
					<img src="{{ url }}"/>
				</div>
			<# } else { #>
				<div class="placeholder"><?php esc_html_e( 'No image selected', 'kirki' ); ?></div>
			<# } #>
			<div class="actions">
				<button class="button image-upload-remove-button<# if ( '' === url ) { #> hidden <# } #>"><?php esc_html_e( 'Remove', 'kirki' ); ?></button>
				<# if ( data.default && '' !== data.default ) { #>
					<button type="button" class="button image-default-button"<# if ( data.default === data.value || ( ! _.isUndefined( data.value.url ) && data.default === data.value.url ) ) { #> style="display:none;"<# } #>><?php esc_html_e( 'Default', 'kirki' ); ?></button>
				<# } #>
				<button type="button" class="button image-upload-button"><?php esc_html_e( 'Select image', 'kirki' ); ?></button>
			</div>
		</div>
		<?php
	}
}
