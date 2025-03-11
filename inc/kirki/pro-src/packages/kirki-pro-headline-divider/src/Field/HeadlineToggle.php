<?php // phpcs:disable PHPCompatibility.FunctionDeclarations.NewClosure
/**
 * Override field methods
 *
 * @package   kirki-pro-headline
 * @since     1.0
 */

namespace Kirki\Pro\Field;

use Kirki\Field;
use Kirki\Field\Checkbox_Toggle;

/**
 * Field overrides.
 *
 * @since 1.0
 */
class HeadlineToggle extends Checkbox_Toggle {

	/**
	 * The control class-name.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected $control_class = '\Kirki\Pro\Control\HeadlineToggle';

}
