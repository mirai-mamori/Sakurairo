<?php
// phpcs:ignoreFile

add_filter( 'kirki_config', function( $args ) {
	return apply_filters( 'kirki/config', $args );
}, 99 );

add_filter( 'kirki_control_types', function( $args ) {
	return apply_filters( 'kirki/control_types', $args );
}, 99 );

add_filter( 'kirki_section_types', function( $args ) {
	return apply_filters( 'kirki/section_types', $args );
}, 99 );

add_filter( 'kirki_section_types_exclude', function( $args ) {
	return apply_filters( 'kirki/section_types/exclude', $args );
}, 99 );

add_filter( 'kirki_control_types_exclude', function( $args ) {
	return apply_filters( 'kirki/control_types/exclude', $args );
}, 99 );

add_filter( 'kirki_controls', function( $args ) {
	return apply_filters( 'kirki/controls', $args );
}, 99 );

add_filter( 'kirki_fields', function( $args ) {
	return apply_filters( 'kirki/fields', $args );
}, 99 );

add_filter( 'kirki_modules', function( $args ) {
	return apply_filters( 'kirki/modules', $args );
}, 99 );

add_filter( 'kirki_panel_types', function( $args ) {
	return apply_filters( 'kirki/panel_types', $args );
}, 99 );

add_filter( 'kirki_setting_types', function( $args ) {
	return apply_filters( 'kirki/setting_types', $args );
}, 99 );

add_filter( 'kirki_variable', function( $args ) {
	return apply_filters( 'kirki/variable', $args );
}, 99 );

add_filter( 'kirki_values_get_value', function( $arg1, $arg2 ) {
	return apply_filters( 'kirki/values/get_value', $arg1, $arg2 );
}, 99, 2 );

add_action( 'init', function() {
	$config_ids = \Kirki\Compatibility\Config::get_config_ids();
	global $kirki_deprecated_filters_iteration;
	foreach ( $config_ids as $config_id ) {
		foreach( array(
			'/dynamic_css',
			'/output/control-classnames',
			'/css/skip_hidden',
			'/styles',
			'/output/property-classnames',
			'/webfonts/skip_hidden',
		) as $filter_suffix ) {
			$kirki_deprecated_filters_iteration = array( $config_id, $filter_suffix );
			add_filter( "kirki_{$config_id}_{$filter_suffix}", function( $args ) {
				global $kirki_deprecated_filters_iteration;
				$kirki_deprecated_filters_iteration[1] = str_replace( '-', '_', $kirki_deprecated_filters_iteration[1] );
				return apply_filters( "kirki/{$kirki_deprecated_filters_iteration[0]}/{$kirki_deprecated_filters_iteration[1]}", $args );
			}, 99 );
			if ( false !== strpos( $kirki_deprecated_filters_iteration[1], '-' ) ) {
				$kirki_deprecated_filters_iteration[1] = str_replace( '-', '_', $kirki_deprecated_filters_iteration[1] );
				add_filter( "kirki_{$config_id}_{$filter_suffix}", function( $args ) {
					global $kirki_deprecated_filters_iteration;
					$kirki_deprecated_filters_iteration[1] = str_replace( '-', '_', $kirki_deprecated_filters_iteration[1] );
					return apply_filters( "kirki/{$kirki_deprecated_filters_iteration[0]}/{$kirki_deprecated_filters_iteration[1]}", $args );
				}, 99 );
			}
		}
	}
}, 99 );

add_filter( 'kirki_enqueue_google_fonts', function( $args ) {
	return apply_filters( 'kirki/enqueue_google_fonts', $args );
}, 99 );

add_filter( 'kirki_styles_array', function( $args ) {
	return apply_filters( 'kirki/styles_array', $args );
}, 99 );

add_filter( 'kirki_dynamic_css_method', function( $args ) {
	return apply_filters( 'kirki/dynamic_css/method', $args );
}, 99 );

add_filter( 'kirki_postmessage_script', function( $args ) {
	return apply_filters( 'kirki/postmessage/script', $args );
}, 99 );

add_filter( 'kirki_fonts_all', function( $args ) {
	return apply_filters( 'kirki/fonts/all', $args );
}, 99 );

add_filter( 'kirki_fonts_standard_fonts', function( $args ) {
	return apply_filters( 'kirki/fonts/standard_fonts', $args );
}, 99 );

add_filter( 'kirki_fonts_google_fonts', function( $args ) {
	return apply_filters( 'kirki/fonts/google_fonts', $args );
}, 99 );

add_filter( 'kirki_googlefonts_load_method', function( $args ) {
	return apply_filters( 'kirki/googlefonts_load_method', $args );
}, 99 );
