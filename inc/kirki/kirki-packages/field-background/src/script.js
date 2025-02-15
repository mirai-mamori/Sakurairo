/* global kirkiPostMessage */

/**
 * Hook in the kirkiPostMessageStylesOutput filter.
 *
 * Handles postMessage styles for typography controls.
 */
jQuery( document ).ready( function() {
	wp.hooks.addFilter(
		'kirkiPostMessageStylesOutput',
		'kirki',

		/**
		 * Append styles for this control.
		 *
		 * @param {string} styles      - The styles.
		 * @param {Object} value       - The control value.
		 * @param {Object} output      - The control's "output" argument.
		 * @param {string} controlType - The control type.
		 * @returns {string} - Returns CSS styles as a string.
		 */
		function( styles, value, output, controlType ) {
			var processedValue;
			if ( 'kirki-background' === controlType ) {
				styles += output.element + '{';
				_.each( value, function( val, key ) {
					if ( output.choice && key !== output.choice ) {
						return;
					}
					if ( 'background-image' === key ) {
						val = -1 === val.indexOf( 'url(' ) ? 'url(' + val + ')' : val;
					}

					processedValue = kirkiPostMessage.util.processValue( output, val );

					if ( '' === processedValue ) {
						if ( 'background-color' === output.property ) {
							processedValue = 'unset';
						} else if ( 'background-image' === output.property ) {
							processedValue = 'none';
						}
					}

					if ( false !== processedValue ) {
						styles += output.property ? output.property + ':' + processedValue + ';' : key + ':' + processedValue + ';';
					}
				} );
				styles += '}';
			}
			return styles;
		}
	);
} );
