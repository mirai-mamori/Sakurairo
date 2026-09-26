<?php
/**
 * Plugin Name: Kirki Customizer Framework
 * Plugin URI: https://www.themeum.com
 * Description: The Ultimate WordPress Customizer Framework
 * Author: Themeum
 * Author URI: https://www.themeum.com
 * Version: 5.2.3
 * Text Domain: kirki
 * Requires at least: 5.3
 * Requires PHP: 7.4
 *
 * @package Kirki
 * @category Core
 * @author Themeum
 * @copyright Copyright (c) 2023, Themeum
 * @license https://opensource.org/licenses/MIT
 * @since 1.0
 */

use Kirki\Customizer;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

// No need to proceed if Kirki already exists.
if (class_exists('Kirki')) {
	return;
}

require_once __DIR__ . '/customizer/class-customizer.php';

Customizer::init();