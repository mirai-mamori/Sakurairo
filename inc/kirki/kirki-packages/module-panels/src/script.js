/**
 * @see https://wordpress.stackexchange.com/a/256103/17078
 */
( function() {

	var _panelEmbed,
		_panelIsContextuallyActive,
		_panelAttachEvents;

	wp.customize.bind( 'pane-contents-reflowed', function() {

		var panels = [];

		// Reflow Panels.
		wp.customize.panel.each( function( panel ) {
			if ( 'kirki-nested' !== panel.params.type || _.isUndefined( panel.params.panel ) ) {
				return;
			}
			panels.push( panel );
		} );

		panels.sort( wp.customize.utils.prioritySort ).reverse();

		jQuery.each( panels, function( i, panel ) {
			var parentContainer = jQuery( '#sub-accordion-panel-' + panel.params.panel );
			parentContainer.children( '.panel-meta' ).after( panel.headContainer );
		} );
	} );

	// Extend Panel.
	_panelEmbed                = wp.customize.Panel.prototype.embed;
	_panelIsContextuallyActive = wp.customize.Panel.prototype.isContextuallyActive;
	_panelAttachEvents         = wp.customize.Panel.prototype.attachEvents;

	wp.customize.Panel = wp.customize.Panel.extend( {
		attachEvents: function() {
			var panel = this;

			if ( 'kirki-nested' !== this.params.type || _.isUndefined( this.params.panel ) ) {
				_panelAttachEvents.call( this );
				return;
			}

			_panelAttachEvents.call( this );

			panel.expanded.bind( function( expanded ) {
				if ( expanded ) {
					wp.customize.panel( panel.params.panel ).contentContainer.addClass( 'current-panel-parent' );
				} else {
					wp.customize.panel( panel.params.panel ).contentContainer.removeClass( 'current-panel-parent' );
				}
			} );

			panel.container.find( '.customize-panel-back' ).off( 'click keydown' ).on( 'click keydown', function( event ) {
				if ( wp.customize.utils.isKeydownButNotEnterEvent( event ) ) {
					return;
				}
				event.preventDefault(); // Keep this AFTER the key filter above

				if ( panel.expanded() ) {
					wp.customize.panel( panel.params.panel ).expand();
				}
			} );
		},

		embed: function() {

			var panel = this,
				parentContainer;
			if ( 'kirki-nested' !== this.params.type || _.isUndefined( this.params.panel ) ) {
				_panelEmbed.call( this );
				return;
			}

			_panelEmbed.call( this );
			parentContainer = jQuery( '#sub-accordion-panel-' + this.params.panel );
			parentContainer.append( panel.headContainer );
		},

		isContextuallyActive: function() {

			var panel       = this,
				activeCount = 0,
				children;

			if ( 'kirki-nested' !== this.params.type ) {
				return _panelIsContextuallyActive.call( this );
			}

			children = this._children( 'panel', 'section' );

			wp.customize.panel.each( function( child ) {
				if ( ! child.params.panel || child.params.panel !== panel.id ) {
					return;
				}
				children.push( child );
			} );

			children.sort( wp.customize.utils.prioritySort );

			_( children ).each( function( child ) {
				if ( child.active() && child.isContextuallyActive() ) {
					activeCount += 1;
				}
			} );
			return ( 0 !== activeCount );
		}
	} );
}( jQuery ) );
