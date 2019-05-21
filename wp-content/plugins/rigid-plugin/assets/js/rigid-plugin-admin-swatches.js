var frame,
	rigid_wcs = rigid_wcs || {};

jQuery( document ).ready( function ( $ ) {
	'use strict';
	var wp = window.wp,
		$body = $( 'body' );

	$( '#term-color' ).wpColorPicker();

	// Update attribute image
	$body.on( 'click', '.rigid-wcs-upload-image-button', function ( event ) {
		event.preventDefault();

		var $button = $( this );

		// If the media frame already exists, reopen it.
		if ( frame ) {
			frame.open();
			return;
		}

		// Create the media frame.
		frame = wp.media.frames.downloadable_file = wp.media( {
			title   : rigid_wcs.i18n.mediaTitle,
			button  : {
				text: rigid_wcs.i18n.mediaButton
			},
			multiple: false
		} );

		// When an image is selected, run a callback.
		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON();

			$button.siblings( 'input.rigid-wcs-term-image' ).val( attachment.id );
			$button.siblings( '.rigid-wcs-remove-image-button' ).show();
			$button.parent().prev( '.rigid-wcs-term-image-thumbnail' ).find( 'img' ).attr( 'src', attachment.sizes.thumbnail.url );
		} );

		// Finally, open the modal.
		frame.open();

	} ).on( 'click', '.rigid-wcs-remove-image-button', function () {
		var $button = $( this );

		$button.siblings( 'input.rigid-wcs-term-image' ).val( '' );
		$button.siblings( '.rigid-wcs-remove-image-button' ).show();
		$button.parent().prev( '.rigid-wcs-term-image-thumbnail' ).find( 'img' ).attr( 'src', rigid_wcs.placeholder );

		return false;
	} );

	// Toggle add new attribute term modal
	var $modal = $( '#rigid-wcs-modal-container' ),
		$spinner = $modal.find( '.spinner' ),
		$msg = $modal.find( '.message' ),
		$metabox = null;

	$body.on( 'click', '.rigid-wcs_add_new_attribute', function ( e ) {
		e.preventDefault();
		var $button = $( this ),
			taxInputTemplate = wp.template( 'rigid-wcs-input-tax' ),
			data = {
				type: $button.data( 'type' ),
				tax : $button.closest( '.woocommerce_attribute' ).data( 'taxonomy' )
			};

		// Insert input
		$modal.find( '.rigid-wcs-term-swatch' ).html( $( '#tmpl-rigid-wcs-input-' + data.type ).html() );
		$modal.find( '.rigid-wcs-term-tax' ).html( taxInputTemplate( data ) );

		if ( 'color' == data.type ) {
			$modal.find( 'input.rigid-wcs-input-color' ).wpColorPicker();
		}

		$metabox = $button.closest( '.woocommerce_attribute.wc-metabox' );
		$modal.show();
	} ).on( 'click', '.rigid-wcs-modal-close, .rigid-wcs-modal-backdrop', function ( e ) {
		e.preventDefault();

		closeModal();
	} );

	// Send ajax request to add new attribute term
	$body.on( 'click', '.rigid-wcs-new-attribute-submit', function ( e ) {
		e.preventDefault();

		var $button = $( this ),
			type = $button.data( 'type' ),
			error = false,
			data = {};

		// Validate
		$modal.find( '.rigid-wcs-input' ).each( function () {
			var $this = $( this );

			if ( $this.attr( 'name' ) != 'slug' && !$this.val() ) {
				$this.addClass( 'error' );
				error = true;
			} else {
				$this.removeClass( 'error' );
			}

			data[$this.attr( 'name' )] = $this.val();
		} );

		if ( error ) {
			return;
		}

		// Send ajax request
		$spinner.addClass( 'is-active' );
		$msg.hide();
		wp.ajax.send( 'rigid-wcs_add_new_attribute', {
			data   : data,
			error  : function ( res ) {
				$spinner.removeClass( 'is-active' );
				$msg.addClass( 'error' ).text( res ).show();
			},
			success: function ( res ) {
				$spinner.removeClass( 'is-active' );
				$msg.addClass( 'success' ).text( res.msg ).show();

				$metabox.find( 'select.attribute_values' ).append( '<option value="' + res.slug + '" selected="selected">' + res.name + '</option>' );
				$metabox.find( 'select.attribute_values' ).change();

				closeModal();
			}
		} );
	} );

	/**
	 * Close modal
	 */
	function closeModal() {
		$modal.find( '.rigid-wcs-term-name input, .rigid-wcs-term-slug input' ).val( '' );
		$spinner.removeClass( 'is-active' );
		$msg.removeClass( 'error success' ).hide();
		$modal.hide();
	}
} );

