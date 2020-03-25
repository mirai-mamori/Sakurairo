<?php
/*
Description: A framework for building theme options.
Author: Devin Price
Author URI: http://www.wptheming.com
License: GPLv2
Version: 1.6
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/* If the user can't edit theme options, no use running this plugin */

add_action( 'init', 'optionsframework_rolescheck' );

function optionsframework_rolescheck () {
	if ( current_user_can( 'edit_theme_options' ) ) {
		// If the user can edit theme options, let the fun begin!
		add_action( 'admin_menu', 'optionsframework_add_page');
		add_action( 'admin_init', 'optionsframework_init' );
		add_action( 'wp_before_admin_bar_render', 'optionsframework_adminbar' );
	}
}

/* Loads the file for option sanitization */

add_action( 'init', 'optionsframework_load_sanitization' );

function optionsframework_load_sanitization() {
	require_once dirname( __FILE__ ) . '/options-sanitize.php';
}

/*
 * Creates the settings in the database by looping through the array
 * we supplied in options.php.  This is a neat way to do it since
 * we won't have to save settings for headers, descriptions, or arguments.
 *
 * Read more about the Settings API in the WordPress codex:
 * http://codex.wordpress.org/Settings_API
 *
 */

function optionsframework_init() {

	// Include the required files
	require_once dirname( __FILE__ ) . '/options-interface.php';
	require_once dirname( __FILE__ ) . '/options-media-uploader.php';

	// Optionally Loads the options file from the theme
	$location = apply_filters( 'options_framework_location', array( 'options.php' ) );
	$optionsfile = locate_template( $location );

	// Load settings
	$optionsframework_settings = get_option('optionsframework' );

	// Updates the unique option id in the database if it has changed
	if ( function_exists( 'optionsframework_option_name' ) ) {
		optionsframework_option_name();
	}
	elseif ( has_action( 'optionsframework_option_name' ) ) {
		do_action( 'optionsframework_option_name' );
	}
	// If the developer hasn't explicitly set an option id, we'll use a default
	else {
		$default_themename = get_option( 'stylesheet' );
		$default_themename = preg_replace("/\W/", "_", strtolower($default_themename) );
		$default_themename = 'optionsframework_' . $default_themename;
		if ( isset( $optionsframework_settings['id'] ) ) {
			if ( $optionsframework_settings['id'] == $default_themename ) {
				// All good, using default theme id
			} else {
				$optionsframework_settings['id'] = $default_themename;
				update_option( 'optionsframework', $optionsframework_settings );
			}
		}
		else {
			$optionsframework_settings['id'] = $default_themename;
			update_option( 'optionsframework', $optionsframework_settings );
		}
	}

	// If the option has no saved data, load the defaults
	if ( ! get_option( $optionsframework_settings['id'] ) ) {
		optionsframework_setdefaults();
	}

	// Registers the settings fields and callback
	register_setting( 'optionsframework', $optionsframework_settings['id'], 'optionsframework_validate' );
	// Change the capability required to save the 'optionsframework' options group.
	add_filter( 'option_page_capability_optionsframework', 'optionsframework_page_capability' );
}

/**
 * Ensures that a user with the 'edit_theme_options' capability can actually set the options
 * See: http://core.trac.wordpress.org/ticket/14365
 *
 * @param string $capability The capability used for the page, which is manage_options by default.
 * @return string The capability to actually use.
 */

function optionsframework_page_capability( $capability ) {
	return 'edit_theme_options';
}

/*
 * Adds default options to the database if they aren't already present.
 * May update this later to load only on plugin activation, or theme
 * activation since most people won't be editing the options.php
 * on a regular basis.
 *
 * http://codex.wordpress.org/Function_Reference/add_option
 *
 */

function optionsframework_setdefaults() {

	$optionsframework_settings = get_option( 'optionsframework' );

	// Gets the unique option id
	$option_name = $optionsframework_settings['id'];

	/*
	 * Each theme will hopefully have a unique id, and all of its options saved
	 * as a separate option set.  We need to track all of these option sets so
	 * it can be easily deleted if someone wishes to remove the plugin and
	 * its associated data.  No need to clutter the database.
	 *
	 */

	if ( isset( $optionsframework_settings['knownoptions'] ) ) {
		$knownoptions =  $optionsframework_settings['knownoptions'];
		if ( !in_array( $option_name, $knownoptions ) ) {
			array_push( $knownoptions, $option_name );
			$optionsframework_settings['knownoptions'] = $knownoptions;
			update_option( 'optionsframework', $optionsframework_settings );
		}
	} else {
		$newoptionname = array( $option_name );
		$optionsframework_settings['knownoptions'] = $newoptionname;
		update_option( 'optionsframework', $optionsframework_settings );
	}

	// Gets the default options data from the array in options.php
	$options =& _optionsframework_options();

	// If the options haven't been added to the database yet, they are added now
	$values = of_get_default_values();

	if ( isset( $values ) ) {
		add_option( $option_name, $values ); // Add option with default settings
	}
}

/* Define menu options (still limited to appearance section)
 *
 * Examples usage:
 *
 * add_filter( 'optionsframework_menu', function($menu) {
 *     $menu['page_title'] = 'Hello Options';
 *	   $menu['menu_title'] = 'Hello Options';
 *     return $menu;
 * });
 */

function optionsframework_menu_settings() {

	$menu = array(
		'page_title' => __('Sakura Options', 'sakura'),
		'menu_title' => __('Sakura Options', 'sakura'),
		'capability' => 'edit_theme_options',
		'menu_slug' => 'options-framework',
		'callback' => 'optionsframework_page'
	);

	return apply_filters( 'optionsframework_menu', $menu );
}

/* Add a subpage called "Theme Options" to the appearance menu. */

function optionsframework_add_page() {

	$menu = optionsframework_menu_settings();
	$of_page = add_theme_page( $menu['page_title'], $menu['menu_title'], $menu['capability'], $menu['menu_slug'], $menu['callback'] );

	// Load the required CSS and javscript
	add_action( 'admin_enqueue_scripts', 'optionsframework_load_scripts' );
	add_action( 'admin_print_styles-' . $of_page, 'optionsframework_load_styles' );
}

/* Loads the CSS */

function optionsframework_load_styles() {
	wp_enqueue_style( 'optionsframework', OPTIONS_FRAMEWORK_DIRECTORY.'css/optionsframework.css' );
	if ( !wp_style_is( 'wp-color-picker','registered' ) ) {
		wp_register_style( 'wp-color-picker', OPTIONS_FRAMEWORK_DIRECTORY.'css/color-picker.min.css' );
	}
	wp_enqueue_style( 'wp-color-picker' );
}

/* Loads the javascript */

function optionsframework_load_scripts( $hook ) {

	$menu = optionsframework_menu_settings();

	if ( 'appearance_page_' . $menu['menu_slug'] != $hook )
        return;

	// Enqueue colorpicker scripts for versions below 3.5 for compatibility
	if ( !wp_script_is( 'wp-color-picker', 'registered' ) ) {
		wp_register_script( 'iris', OPTIONS_FRAMEWORK_DIRECTORY . 'js/iris.min.js', array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), false, 1 );
		wp_register_script( 'wp-color-picker', OPTIONS_FRAMEWORK_DIRECTORY . 'js/color-picker.min.js', array( 'jquery', 'iris' ) );
		$colorpicker_l10n = array(
			'clear' => __( 'Clear','options_framework_theme' ),
			'defaultString' => __( 'Default', 'options_framework_theme' ),
			'pick' => __( 'Select Color', 'options_framework_theme' )
		);
		wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n );
	}

	// Enqueue custom option panel JS
	wp_enqueue_script( 'options-custom', OPTIONS_FRAMEWORK_DIRECTORY . 'js/options-custom.js', array( 'jquery','wp-color-picker' ) );

	// Inline scripts from options-interface.php
	add_action( 'admin_head', 'of_admin_head' );
}

function of_admin_head() {
	// Hook to add custom scripts
	do_action( 'optionsframework_custom_scripts' );
}

/*
 * Builds out the options panel.
 *
 * If we were using the Settings API as it was likely intended we would use
 * do_settings_sections here.  But as we don't want the settings wrapped in a table,
 * we'll call our own custom optionsframework_fields.  See options-interface.php
 * for specifics on how each individual field is generated.
 *
 * Nonces are provided using the settings_fields()
 *
 */

if ( !function_exists( 'optionsframework_page' ) ) :
function optionsframework_page() { ?>

	<div id="optionsframework-wrap" class="wrap">
    <h2 class="nav-tab-wrapper">
        <?php echo optionsframework_tabs(); ?>
    </h2>

    <?php settings_errors( 'options-framework' ); ?>

    <div id="optionsframework-metabox" class="metabox-holder">
	    <div id="optionsframework" class="postbox">
			<form action="options.php" method="post">
			<?php settings_fields( 'optionsframework' ); ?>
			<?php optionsframework_fields(); /* Settings */ ?>
			<div id="optionsframework-submit">
				<input type="submit" class="button-primary" name="update" value="<?php _e( 'Save', 'sakura' ); ?>" />
				<input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e( 'Restore default', 'sakura' ); ?>" onclick="return confirm( '<?php print esc_js( __( 'All setting will be lost, sure?', 'sakura' ) ); ?>' );" />
				<div class="clear"></div>
			</div>
			</form>
		</div> <!-- / #container -->
	</div>
	<?php do_action( 'optionsframework_after' ); ?>
	</div> <!-- / .wrap -->

<?php
}
endif;

/**
 * Validate Options.
 *
 * This runs after the submit/reset button has been clicked and
 * validates the inputs.
 *
 * @uses $_POST['reset'] to restore default options
 */
function optionsframework_validate( $input ) {

	/*
	 * Restore Defaults.
	 *
	 * In the event that the user clicked the "Restore Defaults"
	 * button, the options defined in the theme's options.php
	 * file will be added to the option for the active theme.
	 */

	if ( isset( $_POST['reset'] ) ) {
		add_settings_error( 'options-framework', 'restore_defaults', __( 'Default options restored.', 'options_framework_theme' ), 'updated fade' );
		return of_get_default_values();
	}

	/*
	 * Update Settings
	 *
	 * This used to check for $_POST['update'], but has been updated
	 * to be compatible with the theme customizer introduced in WordPress 3.4
	 */

	$clean = array();
	$options =& _optionsframework_options();
	foreach ( $options as $option ) {

		if ( ! isset( $option['id'] ) ) {
			continue;
		}

		if ( ! isset( $option['type'] ) ) {
			continue;
		}

		$id = preg_replace( '/[^a-zA-Z0-9._\-]/', '', strtolower( $option['id'] ) );

		// Set checkbox to false if it wasn't sent in the $_POST
		if ( 'checkbox' == $option['type'] && ! isset( $input[$id] ) ) {
			$input[$id] = false;
		}

		// Set each item in the multicheck to false if it wasn't sent in the $_POST
		if ( 'multicheck' == $option['type'] && ! isset( $input[$id] ) ) {
			foreach ( $option['options'] as $key => $value ) {
				$input[$id][$key] = false;
			}
		}

		// For a value to be submitted to database it must pass through a sanitization filter
		if ( has_filter( 'of_sanitize_' . $option['type'] ) ) {
			$clean[$id] = apply_filters( 'of_sanitize_' . $option['type'], $input[$id], $option );
		}
	}

	// Hook to run after validation
	do_action( 'optionsframework_after_validate', $clean );

	return $clean;
}

/**
 * Display message when options have been saved
 */

function optionsframework_save_options_notice() {
	add_settings_error( 'options-framework', 'save_options', __( 'Options saved.', 'options_framework_theme' ), 'updated fade' );
}

add_action( 'optionsframework_after_validate', 'optionsframework_save_options_notice' );

/**
 * Format Configuration Array.
 *
 * Get an array of all default values as set in
 * options.php. The 'id','std' and 'type' keys need
 * to be defined in the configuration array. In the
 * event that these keys are not present the option
 * will not be included in this function's output.
 *
 * @return    array     Rey-keyed options configuration array.
 *
 * @access    private
 */

function of_get_default_values() {
	$output = array();
	$config =& _optionsframework_options();
	foreach ( (array) $config as $option ) {
		if ( ! isset( $option['id'] ) ) {
			continue;
		}
		if ( ! isset( $option['std'] ) ) {
			continue;
		}
		if ( ! isset( $option['type'] ) ) {
			continue;
		}
		if ( has_filter( 'of_sanitize_' . $option['type'] ) ) {
			$output[$option['id']] = apply_filters( 'of_sanitize_' . $option['type'], $option['std'], $option );
		}
	}
	return $output;
}

/**
 * Add Theme Options menu item to Admin Bar.
 */

function optionsframework_adminbar() {

	global $wp_admin_bar;

	$wp_admin_bar->add_menu( array(
			'parent' => 'appearance',
			'id' => 'of_theme_options',
			'title' => __('Sakura Options', 'sakura'),
			'href' => admin_url( 'themes.php?page=options-framework' )
		));
}

/**
 * Wrapper for optionsframework_options()
 *
 * Allows for manipulating or setting options via 'of_options' filter
 * For example:
 *
 * <code>
 * add_filter('of_options', function($options) {
 *     $options[] = array(
 *         'name' => 'Input Text Mini',
 *         'desc' => 'A mini text input field.',
 *         'id' => 'example_text_mini',
 *         'std' => 'Default',
 *         'class' => 'mini',
 *         'type' => 'text'
 *     );
 *
 *     return $options;
 * });
 * </code>
 *
 * Also allows for setting options via a return statement in the
 * options.php file.  For example (in options.php):
 *
 * <code>
 * return array(...);
 * </code>
 *
 * @return array (by reference)
 */
function &_optionsframework_options() {
	static $options = null;

	if ( !$options ) {
		// Load options from options.php file (if it exists)
		$location = apply_filters( 'options_framework_location', array('options.php') );
		if ( $optionsfile = locate_template( $location ) ) {
			$maybe_options = require_once $optionsfile;
			if ( is_array($maybe_options) ) {
				$options = $maybe_options;
			} else if ( function_exists( 'optionsframework_options' ) ) {
				$options = optionsframework_options();
			}
		}

		// Allow setting/manipulating options via filters
		$options = apply_filters('of_options', $options);
	}

	return $options;
}

/**
 * Get Option.
 *
 * Helper function to return the theme option value.
 * If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 */

if ( ! function_exists( 'akina_option' ) ) {

	function akina_option( $name, $default = false ) {
		$config = get_option( 'optionsframework' );

		if ( ! isset( $config['id'] ) ) {
			return $default;
		}

		$options = get_option( $config['id'] );

		if ( isset( $options[$name] ) ) {
			return $options[$name];
		}

		return $default;
	}
}
