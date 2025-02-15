jQuery( document ).ready( function() {

	wp.customize.section.each( function( section ) {

		// Get the pane element.
		var pane      = jQuery( '#sub-accordion-section-' + section.id ),
			sectionLi = jQuery( '#accordion-section-' + section.id );

		// Check if the section is expanded.
		if ( sectionLi.hasClass( 'control-section-kirki-expanded' ) ) {

			// Move element.
			pane.appendTo( sectionLi );

		}

	} );
} );

/**
 * See https://github.com/justintadlock/trt-customizer-pro
 */
( function() {
	wp.customize.sectionConstructor['kirki-link'] = wp.customize.Section.extend( {
		attachEvents: function() {}, // eslint-disable-line no-empty-function
		isContextuallyActive: function() {
			return true;
		}
	} );
}() );

/**
 * @see https://wordpress.stackexchange.com/a/256103/17078
 */
( function() {

	var _sectionEmbed,
		_sectionIsContextuallyActive,
		_sectionAttachEvents;

	wp.customize.bind( 'pane-contents-reflowed', function() {

		var sections = [];

		// Reflow Sections.
		wp.customize.section.each( function( section ) {

			if ( 'kirki-nested' !== section.params.type || _.isUndefined( section.params.section ) ) {
				return;
			}
			sections.push( section );
		} );

		sections.sort( wp.customize.utils.prioritySort ).reverse();

		jQuery.each( sections, function( i, section ) {
			var parentContainer = jQuery( '#sub-accordion-section-' + section.params.section );

			parentContainer.children( '.section-meta' ).after( section.headContainer );
		} );
	} );

	// Extend Section.
	_sectionEmbed = wp.customize.Section.prototype.embed;
	_sectionIsContextuallyActive = wp.customize.Section.prototype.isContextuallyActive;
	_sectionAttachEvents = wp.customize.Section.prototype.attachEvents;

	wp.customize.Section = wp.customize.Section.extend( {
		attachEvents: function() {

			var section = this;

			if ( 'kirki-nested' !== this.params.type || _.isUndefined( this.params.section ) ) {
				_sectionAttachEvents.call( section );
				return;
			}

			_sectionAttachEvents.call( section );

			section.expanded.bind( function( expanded ) {
				var parent = wp.customize.section( section.params.section );

				if ( expanded ) {
					parent.contentContainer.addClass( 'current-section-parent' );
				} else {
					parent.contentContainer.removeClass( 'current-section-parent' );
				}
			} );

			section.container.find( '.customize-section-back' ).off( 'click keydown' ).on( 'click keydown', function( event ) {
				if ( wp.customize.utils.isKeydownButNotEnterEvent( event ) ) {
					return;
				}
				event.preventDefault(); // Keep this AFTER the key filter above
				if ( section.expanded() ) {
					wp.customize.section( section.params.section ).expand();
				}
			} );
		},

		embed: function() {

			var section = this,
				parentContainer;

			if ( 'kirki-nested' !== this.params.type || _.isUndefined( this.params.section ) ) {
				_sectionEmbed.call( section );
				return;
			}

			_sectionEmbed.call( section );

			parentContainer = jQuery( '#sub-accordion-section-' + this.params.section );

			parentContainer.append( section.headContainer );
		},

		isContextuallyActive: function() {
			var section = this,
				children,
				activeCount = 0;
			if ( 'kirki-nested' !== this.params.type ) {
				return _sectionIsContextuallyActive.call( this );
			}

			children = this._children( 'section', 'control' );

			wp.customize.section.each( function( child ) {
				if ( ! child.params.section ) {
					return;
				}

				if ( child.params.section !== section.id ) {
					return;
				}
				children.push( child );
			} );

			children.sort( wp.customize.utils.prioritySort );

			_( children ).each( function( child ) {
				if ( 'undefined' !== typeof child.isContextuallyActive ) {
					if ( child.active() && child.isContextuallyActive() ) {
						activeCount += 1;
					}
				} else {
					if ( child.active() ) {
						activeCount += 1;
					}
				}
			} );

			return ( 0 !== activeCount );
		}
	} );
}( jQuery ) );
