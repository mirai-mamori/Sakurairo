/* global kirkiIcons */
jQuery( document ).ready( function() {

	'use strict';

	if ( ! _.isUndefined( kirkiIcons.section ) ) {

		// Parse sections and add icons.
		_.each( kirkiIcons.section, function( icon, sectionID ) {

			// Add icons in list.
			jQuery( '#accordion-section-' + sectionID + ' > h3' ).addClass( 'dashicons-before ' + icon );

			// Add icons on titles when a section is open.
			jQuery( '#sub-accordion-section-' + sectionID + ' .customize-section-title > h3' ).append( '<span class="dashicons ' + icon + '" style="float:left;padding-right:.1em;padding-top:2px;"></span>' );
		} );

	}

	if ( ! _.isUndefined( kirkiIcons.panel ) ) {

		_.each( kirkiIcons.panel, function( icon, panelID ) {

			// Add icons in lists & headers.
			jQuery( '#accordion-panel-' + panelID + ' > h3, #sub-accordion-panel-' + panelID + ' .panel-title' ).addClass( 'dashicons-before ' + icon );
		} );

	}

} );
