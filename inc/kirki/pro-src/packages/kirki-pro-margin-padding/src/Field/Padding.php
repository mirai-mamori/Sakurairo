<?php
/**
 * Override field methods.
 *
 * @package kirki-pro-padding
 * @since   1.0
 */

namespace Kirki\Pro\Field;

/**
 * Field overrides.
 *
 * @since 1.0
 */
class Padding extends Margin {

	/**
	 * The field type.
	 *
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $type = 'kirki-padding';

	/**
	 * The control class-name.
	 *
	 * @since 1.0
	 * @access protected
	 * @var string
	 */
	protected $control_class = '\Kirki\Pro\Control\Padding';

	/**
	 * Filter arguments before creating the control.
	 *
	 * @param array                 $args The field arguments.
	 * @param \WP_Customize_Manager $wp_customize The customizer instance.
	 *
	 * @return array $args The maybe-filtered arguments.
	 */
	public function filter_control_args( $args, $wp_customize ) {

		if ( $args['settings'] === $this->args['settings'] ) {
			$args         = parent::filter_control_args( $args, $wp_customize );
			$args['type'] = 'kirki-padding';
		}

		return $args;

	}

}
