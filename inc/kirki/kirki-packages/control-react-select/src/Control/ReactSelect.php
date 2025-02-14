<?php
/**
 * Customizer Control: kirki-select.
 *
 * @package   kirki-framework/control-select
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Control;

use Kirki\Control\Base;
use Kirki\URL;

/**
 * Select control.
 *
 * @since 1.0
 */
class ReactSelect extends Base {

	/**
	 * The control type.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-react-select';

	/**
	 * Placeholder text.
	 *
	 * @access public
	 * @since 1.0
	 * @var string|false
	 */
	public $placeholder = false;

	/**
	 * Whether the select should be clearable or not.
	 *
	 * @since 1.0
	 * @var bool
	 */
	public $clearable = false;

	/**
	 * Whether this is a multi-select or not.
	 *
	 * *Backwards compatibility note:
	 *
	 * Previously (when Kirki used Select2), $multiple is used to:
	 * - Determine whether the select is multiple or not.
	 * - Determine the maximum number of selection.
	 *
	 * Start from Kirki 4 (when Kirki uses react-select),
	 * $multiple is used to determine whether the select is multiple or not.
	 * The maximum selection number is now set in $max_selection.
	 *
	 * @access public
	 * @since 1.0
	 * @var bool
	 */
	public $multiple = false;

	/**
	 * The maximum selection length for multiple selection.
	 *
	 * @access public
	 * @since 1.1
	 * @var bool
	 */
	public $max_selection_number = 999;

	/**
	 * The version. Used in scripts & styles for cache-busting.
	 *
	 * @static
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public static $control_ver = '1.1.5';

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
		wp_enqueue_script(
			'kirki-control-select',
			URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.js' ),
			[
				'customize-controls',
				'customize-base',
				'wp-element',
				'wp-compose',
				'wp-components',
				'jquery',
				'wp-i18n',
				'kirki-control-base',
			],
			time(),
			false
		);

		// Enqueue the style.
		wp_enqueue_style( 'kirki-control-select-style', URL::get_from_path( dirname( dirname( __DIR__ ) ) . '/dist/control.css' ), [], self::$control_ver );

	}

	/**
	 * Get the URL for the control folder.
	 *
	 * This is a static method because there are more controls in the Kirki framework
	 * that use colorpickers, and they all need to enqueue the same assets.
	 *
	 * @static
	 * @access public
	 * @since 1.0.6
	 * @return string
	 */
	public static function get_control_path_url() {

		return URL::get_from_path( dirname( __DIR__ ) );

	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function to_json() {

		parent::to_json();

		if ( isset( $this->json['label'] ) ) {
			$this->json['label'] = html_entity_decode( $this->json['label'] );
		}

		if ( isset( $this->json['description'] ) ) {
			$this->json['description'] = html_entity_decode( $this->json['description'] );
		}

		// @link https://react-select.com/props
		$this->json['isClearable'] = $this->clearable;
		$this->json['isMulti']     = $this->multiple;
		$this->json['placeholder'] = ( $this->placeholder ) ? $this->placeholder : esc_html__( 'Select...', 'kirki' );

		// Will be a custom implementation, couldn't find an official prop to set this in react-select.
		$this->json['maxSelectionNumber'] = $this->max_selection_number;

		$this->json['messages'] = [
			// translators: %s is the limit of selection number.
			'maxLimitReached' => sprintf( esc_html__( 'You can only select %s items', 'kirki' ), $this->max_selection_number ),
		];

	}

}
