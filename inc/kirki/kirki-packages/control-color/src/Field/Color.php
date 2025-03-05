<?php
/**
 * Object used by the Kirki framework to instantiate the control.
 *
 * This is a man-in-the-middle class, nothing but a proxy to set sanitization
 * callbacks and any usother properties we may need.
 *
 * @package   kirki-framework/control-color
 * @copyright Copyright (c) 2023, Themeum
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Field;

use Kirki\Field;

/**
 * Field overrides.
 *
 * @since 1.0
 */
class Color extends ReactColorful {}
