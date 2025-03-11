<?php
/**
 * Customizer Controls Base.
 *
 * Extend this in other controls.
 *
 * @package   kirki-framework/control-base
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Control;

use Kirki\URL;

/**
 * A base for controls.
 *
 * @since 1.0
 */
class Base extends \WP_Customize_Control {

	/**
	 * Used to automatically generate all CSS output.
	 *
	 * Whitelisting property for use in Kirki modules.
	 *
	 * @access public
	 * @since 1.0
	 * @var array
	 */
	public $output = [];

	/**
	 * Data type
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $option_type = 'theme_mod';

	/**
	 * Option name (if using options).
	 *
	 * Whitelisting property for use in Kirki modules.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $option_name = false;

	/**
	 * The kirki_config we're using for this control
	 *
	 * Whitelisting property for use in Kirki modules.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $kirki_config = 'global';

	/**
	 * Whitelisting the "preset" argument for use in Kirki modules.
	 *
	 * @access public
	 * @since 1.0
	 * @var array
	 */
	public $preset = [];

	/**
	 * Whitelisting the "css_vars" argument for use in Kirki modules.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $css_vars = '';

	/**
	 * The version. Used in scripts & styles for cache-busting.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public static $control_ver = '1.0.4';

	/**
	 * Parent setting.
	 *
	 * Used for composite controls to denote the setting that should be saved.
	 *
	 * @access public
	 * @since 1.1
	 * @var string
	 */
	public $parent_setting;

	/**
	 * Wrapper attributes.
	 *
	 * The value of this property will be rendered to the wrapper element.
	 * Can be 'class', 'id', 'data-*', and other attributes.
	 *
	 * @access public
	 * @since 1.1
	 * @var array
	 */
	public $wrapper_attrs = [];

	/**
	 * Backwards compatibility support for `$wrapper_attrs`.
	 *
	 * Kirki v3 already has this `$wrapper_atts` property.
	 * It was not published in the documentation, and more for internal use.
	 *
	 * The `WP_Customize_Control` is using `input_attrs` not `input_atts` (see, attrs vs atts).
	 * So Kirki uses `$wrapper_attrs` for consistency and keep the old `$wrapper_atts` backwards compatibility.
	 *
	 * This property could be removed in the future.
	 * Please use `$wrapper_attrs` instead.
	 *
	 * @since 1.1
	 * @deprecated 1.0.1 This variable could be removed in the future. Please use `$wrapper_attrs` instead.
	 * @var array
	 */
	public $wrapper_atts = [];

	/**
	 * Wrapper options.
	 *
	 * This won't be rendered automatically to the wrapper element.
	 * The purpose is to allow us to have custom options so we can manage it when needed.
	 *
	 * @access public
	 * @since 1.1
	 * @var array
	 */
	public $wrapper_opts = [];

	/**
	 * Extra script dependencies.
	 *
	 * @access public
	 * @since 1.0
	 * @return array
	 */
	public function kirki_script_dependencies() {
		return [];
	}

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function enqueue() {

		// Enqueue the styles.
		wp_enqueue_style( 'kirki-control-base', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.css' ), [], self::$control_ver );

		// Enqueue the scripts.
		wp_enqueue_script( 'kirki-control-base', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.js' ), [ 'customize-controls' ], self::$control_ver, false );

	}

	/**
	 * Renders the control wrapper and calls $this->render_content() for the internals.
	 *
	 * @since 1.0
	 */
	protected function render() {

		$id    = 'customize-control-' . str_replace( [ '[', ']' ], [ '-', '' ], $this->id );
		$class = 'customize-control customize-control-kirki customize-control-' . $this->type;
		$gap   = isset( $this->wrapper_opts['gap'] ) ? $this->wrapper_opts['gap'] : 'default';
		$tag   = isset( $this->wrapper_opts['tag'] ) ? $this->wrapper_opts['tag'] : 'li';

		switch ( $gap ) {
			case 'small':
				$class .= ' customize-control-has-small-gap';
				break;

			case 'none':
				$class .= ' customize-control-is-gapless';
				break;

			default:
				break;
		}

		if ( empty( $this->wrapper_attrs ) && ! empty( $this->wrapper_atts ) ) {
			$this->wrapper_attrs = $this->wrapper_atts;
		}

		if ( isset( $this->wrapper_attrs['id'] ) ) {
			$id = $this->wrapper_attrs['id'];
		}

		if ( ! isset( $this->wrapper_attrs['data-kirki-setting'] ) ) {
			$this->wrapper_attrs['data-kirki-setting'] = $this->id;
		}

		if ( ! isset( $this->wrapper_attrs['data-kirki-setting-link'] ) ) {
			if ( isset( $this->settings['default'] ) ) {
				$this->wrapper_attrs['data-kirki-setting-link'] = $this->settings['default']->id;
			}
		}

		$data_attrs = '';

		foreach ( $this->wrapper_attrs as $attr_key => $attr_value ) {
			if ( 0 === strpos( $attr_key, 'data-' ) ) {
				$data_attrs .= ' ' . esc_attr( $attr_key ) . '="' . esc_attr( $attr_value ) . '"';
			}
		}

		if ( isset( $this->wrapper_attrs['class'] ) ) {
			$class = str_ireplace( '{default_class}', $class, $this->wrapper_attrs['class'] );
		}

		// ! Consider to esc $data_attrs.
		// ? What function we can use to escape string like data-xx="yy"?
		printf( '<' . esc_attr( $tag ) . ' id="%s" class="%s"%s>', esc_attr( $id ), esc_attr( $class ), $data_attrs );
		$this->render_content();
		echo '</' . esc_attr( $tag ) . '>';

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

		// Default value.
		$this->json['default'] = $this->setting->default;

		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		}

		// Output.
		$this->json['output'] = $this->output;

		// Value.
		$this->json['value'] = $this->value();

		// Choices.
		$this->json['choices'] = $this->choices;

		// The link.
		$this->json['link'] = $this->get_link();

		// The ID.
		$this->json['id'] = $this->id;

		// Translation strings.
		$this->json['l10n'] = $this->l10n();

		// The ajaxurl in case we need it.
		$this->json['ajaxurl'] = admin_url( 'admin-ajax.php' );

		// Input attributes.
		$this->json['inputAttrs'] = '';

		if ( is_array( $this->input_attrs ) ) {
			foreach ( $this->input_attrs as $attr => $value ) {
				$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
			}
		}

		// The kirki-config.
		$this->json['kirkiConfig'] = $this->kirki_config;

		// The option-type.
		$this->json['kirkiOptionType'] = $this->option_type;

		// The option-name.
		$this->json['kirkiOptionName'] = $this->option_name;

		// The preset.
		$this->json['preset'] = $this->preset;

		// The CSS-Variables.
		$this->json['css-var'] = $this->css_vars;

		// Parent setting.
		$this->json['parent_setting'] = $this->parent_setting;

		// Wrapper Attributes.
		$this->json['wrapper_attrs'] = $this->wrapper_attrs;
		$this->json['wrapper_atts']  = $this->wrapper_attrs; // For backward compatibility - Could be removed in the future.

	}

	/**
	 * Render the control's content.
	 *
	 * Allows the content to be overridden without having to rewrite the wrapper in `$this::render()`.
	 * Control content can alternately be rendered in JS. See WP_Customize_Control::print_template().
	 *
	 * @access protected
	 * @since 1.0
	 * @return void
	 */
	protected function render_content() {}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @access protected
	 * @since 1.0
	 * @see WP_Customize_Control::print_template()
	 * @return void
	 */
	protected function content_template() {}

	/**
	 * Returns an array of translation strings.
	 *
	 * @access protected
	 * @since 3.0.0
	 * @return array
	 */
	protected function l10n() {
		return [];
	}
}
