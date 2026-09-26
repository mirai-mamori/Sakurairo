<?php
/**
 * Backward compatibility for old Kirki Pro namespace.
 *
 * This file provides class aliases for the old \Kirki\Pro\* namespace
 * to maintain backward compatibility after removing the Pro namespace.
 *
 * @package Kirki
 * @since 5.1.1
 */

defined( 'ABSPATH' ) || exit;

// Prevent registering multiple times.
if ( defined( 'KIRKI_PRO_NAMESPACE_COMPATIBILITY_LOADED' ) ) {
	return;
}

define( 'KIRKI_PRO_NAMESPACE_COMPATIBILITY_LOADED', true );

/**
 * Autoloader for old Pro namespace classes.
 *
 * This autoloader intercepts attempts to load old Pro namespace classes
 * and loads the corresponding new namespace class instead.
 * It uses lazy loading to avoid loading classes before WordPress Customizer is available.
 */
spl_autoload_register(
	function ( $class ) {
		// Only handle \Kirki\Pro\* classes.
		if ( 0 !== strpos( $class, 'Kirki\\Pro\\' ) ) {
			return;
		}

		$new_class = str_replace( 'Kirki\\Pro\\', 'Kirki\\', $class );

		if ( class_exists( $new_class, false ) ) {
			class_alias( $new_class, $class );
			return;
		}

		// Control classes extend WP_Customize_Control, so WordPress Customizer must be loaded first.
		$is_control_class = ( 0 === strpos( $new_class, 'Kirki\\Control\\' ) );
		
		// For Control classes, WP_Customize_Control must be available.
		if ( $is_control_class && ! class_exists( 'WP_Customize_Control', false ) ) {
			// Defer loading until WordPress Customizer is ready.
			return;
		}
		
		// Try to trigger the main autoloader first.
		if ( class_exists( $new_class ) ) {
			class_alias( $new_class, $class );
			return;
		}

		// Try to load the new class file manually if autoloader didn't find it.
		$relative_class = str_replace( 'Kirki\\', '', $new_class );
		$relative_path  = str_replace( '\\', '/', $relative_class ) . '.php';

		// Check in Field and Control directories.
		$base_dir = dirname( dirname( __DIR__ ) ) . '/';
		$packages = array( 'controls/margin-padding', 'controls/headline-divider', 'controls/input-slider', 'controls/responsive', 'controls/tabs' );

		foreach ( $packages as $package ) {
			$file_name = basename( $relative_path );

			$field_file = $base_dir . $package . '/src/Field/' . $file_name;
			if ( file_exists( $field_file ) ) {
				require_once $field_file;
				if ( class_exists( $new_class, false ) ) {
					class_alias( $new_class, $class );
				}
				return;
			}

			// Check Control directory (requires WP_Customize_Control).
			$control_file = $base_dir . $package . '/src/Control/' . $file_name;
			if ( file_exists( $control_file ) && class_exists( 'WP_Customize_Control', false ) ) {
				require_once $control_file;
				if ( class_exists( $new_class, false ) ) {
					class_alias( $new_class, $class );
				}
				return;
			}
		}
	},
	true, // Throw exception if class not found.
	true  // Prepend to autoload stack (high priority).
);


