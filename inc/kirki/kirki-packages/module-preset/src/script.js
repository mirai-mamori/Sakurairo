/* global kirkiPresetControls */
jQuery( document ).ready( function() {
	_.each( kirkiPresetControls, function( children, parentControl ) {
		wp.customize( parentControl, function( value ) {
			value.bind( function( to ) {
				_.each( children, function( preset, valueToListen ) {
					if ( valueToListen === to ) {
						_.each( preset.settings, function( controlValue, controlID ) {
							wp.customize( controlID ).set( controlValue );
						} );
					}
				} );
			} );
		} );
	} );
} );
