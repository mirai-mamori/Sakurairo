<?php
/**
 * Override field methods
 *
 * @package   kirki-framework/control-code
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Field;

use Kirki\Field;

/**
 * Field overrides.
 */
class Code extends Field {

	/**
	 * The field type.
	 *
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $type = 'kirki-code';

	/**
	 * The control class-name.
	 *
	 * @access protected
	 * @since 0.1
	 * @var string
	 */
	protected $control_class = '\Kirki\Control\Code';

	/**
	 * Filter arguments before creating the setting.
	 *
	 * @access public
	 * @since 0.1
	 * @param array                $args         The field arguments.
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 * @return array
	 */
	public function filter_setting_args( $args, $wp_customize ) {
		if ( $args['settings'] === $this->args['settings'] ) {
			$args = parent::filter_setting_args( $args, $wp_customize );

			// Set the sanitize-callback if none is defined.
			if ( ! isset( $args['sanitize_callback'] ) || ! $args['sanitize_callback'] ) {
				$args['sanitize_callback'] = function( $value ) {
					/**
					 * Code fields should not be filtered by default.
					 * Their values usually contain CSS/JS and it it the responsibility
					 * of the theme/plugin that registers this field
					 * to properly apply any necessary sanitization.
					 */
					return $value;
				};
			}
		}
		return $args;
	}

	/**
	 * Filter arguments before creating the control.
	 *
	 * @access public
	 * @since 0.1
	 * @param array                $args         The field arguments.
	 * @param WP_Customize_Manager $wp_customize The customizer instance.
	 * @return array
	 */
	public function filter_control_args( $args, $wp_customize ) {
		if ( $args['settings'] === $this->args['settings'] ) {
			$args = parent::filter_control_args( $args, $wp_customize );

			$args['type'] = 'code_editor';

			$args['input_attrs'] = [
				'aria-describedby' => 'kirki-code editor-keyboard-trap-help-1 editor-keyboard-trap-help-2 editor-keyboard-trap-help-3 editor-keyboard-trap-help-4',
			];
			if ( ! isset( $args['choices']['language'] ) ) {
				return;
			}

			$language = $args['choices']['language'];
			switch ( $language ) {
				case 'json':
				case 'xml':
					$language = 'application/' . $language;
					break;
				case 'http':
					$language = 'message/' . $language;
					break;
				case 'js':
				case 'javascript':
					$language = 'text/javascript';
					break;
				case 'txt':
					$language = 'text/plain';
					break;
				case 'css':
				case 'jsx':
				case 'html':
					$language = 'text/' . $language;
					break;
				default:
					$language = ( 'js' === $language ) ? 'javascript' : $language;
					$language = ( 'htm' === $language ) ? 'html' : $language;
					$language = ( 'yml' === $language ) ? 'yaml' : $language;
					$language = 'text/x-' . $language;
					break;
			}
			if ( ! isset( $args['editor_settings'] ) ) {
				$args['editor_settings'] = [];
			}
			if ( ! isset( $args['editor_settings']['codemirror'] ) ) {
				$args['editor_settings']['codemirror'] = [];
			}
			if ( ! isset( $args['editor_settings']['codemirror']['mode'] ) ) {
				$args['editor_settings']['codemirror']['mode'] = $language;
			}

			if ( 'text/x-scss' === $args['editor_settings']['codemirror']['mode'] ) {
				$args['editor_settings']['codemirror'] = array_merge(
					$args['editor_settings']['codemirror'],
					[
						'lint'              => false,
						'autoCloseBrackets' => true,
						'matchBrackets'     => true,
					]
				);
			}
		}
		return $args;
	}
}
