(function ($) {
	"use strict";

	$(window).load(function () {

		/***************************************
		 * Used for displaying flex slides when
		 * there are featured images from 1-5
		 * "rigid-flex-slider"
		 ***************************************/
		$('.rigid_flexslider', '#content').flexslider({
			controlNav: false,
			directionNav: true,
			animation: 'fade',
			animationSpeed: 1500,
			smoothHeight: true,
			prevText: "", //String: Set the text for the "previous" directionNav item
			nextText: "",
			touch: true,
			pauseOnHover: true
		});

		/**************************
		 * "rigid-supersized-conf"
		 **************************/
		if (typeof rigid_supersized_conf !== 'undefined') {
			var imagesArr = new Array();
			for (var i = 0; i < rigid_supersized_conf.images.length; i++) {
				imagesArr[i] = {image: rigid_supersized_conf.images[i]};
			}

			$.supersized({
				// Functionality
				slideshow: 1, // Slideshow on/off
				autoplay: 1, // Slideshow starts playing automatically
				start_slide: 1, // Start slide (0 is random)
				stop_loop: 0, // Pauses slideshow on last slide
				random: 0, // Randomize slide order (Ignores start slide)
				slide_interval: 5000, // Length between transitions
				transition: 1, // 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
				transition_speed: 1300, // Speed of transition
				new_window: 0, // Image links open in new window/tab
				pause_hover: 0, // Pause slideshow on hover
				keyboard_nav: 0, // Keyboard navigation on/off
				performance: 1, // 0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed // (Only works for Firefox/IE, not Webkit)
				image_protect: 1, // Disables image dragging and right click with Javascript

				// Size and Position
				min_width: 0, // Min width allowed (in pixels)
				min_height: 0, // Min height allowed (in pixels)
				vertical_center: 1, // Vertically center background
				horizontal_center: 1, // Horizontally center background
				fit_always: 0, // Image will never exceed browser width or height (Ignores min. dimensions)
				fit_portrait: 0, // Portrait images will not exceed browser height
				fit_landscape: 0, // Landscape images will not exceed browser width

				// Components
				slide_links: false, // Individual links for each slide (Options: false, 'num', 'name', 'blank')
				thumb_links: 0, // Individual thumb links for each slide
				thumbnail_navigation: 0, // Thumbnail navigation
				slides: imagesArr,
				// Theme Options
				progress_bar: 0, // Timer for each slide
				mouse_scrub: 0

			});
		}
		/*******************************
		 * END "rigid-supersized-conf"
		 *******************************/
		/*****************************
		 * "rigid-masonry-settings"
		 *****************************/
		if (typeof rigid_masonry_settings !== 'undefined') {
			$('.rigid_blog_masonry', '#main').isotope({
				itemSelector: '#main div.blog-post'
			});
		}
	});

	$(document).ready(function () {

		/* *******************************
		 * creates ajax search if no touch
		 * "rigid-ajax-search"
		 *********************************/
		if (typeof rigid_ajax_search !== 'undefined') {
			var touch = $('html.touch');
			if (touch.length == 0) {
				new $.RigidAjaxSearch();
			}
		}

		/**************************
		 * "rigid-magnific-popup"
		 **************************/
		$('a.rigid-magnific-gallery-item').magnificPopup({
			mainClass: 'mfp-fade',
			type: 'image',
			gallery: {
				enabled: true
			}
		});

		/* for portfolio list */
		$('a.portfolio-lightbox-link').magnificPopup({
			mainClass: 'mfp-fade',
			type: 'image'
		});

		/*****************************
		 * "rigid-owl-carousel-cat"
		 *****************************/
		if (typeof rigid_owl_carousel_cat !== 'undefined') {
			var is_rtl = false;
			if (rigid_rtl.is_rtl === 'true') {
				is_rtl = true;
			}
			$(".rigid_woo_categories_shop.rigid-owl-carousel", "#main").owlCarousel({
				rtl: is_rtl,
				responsiveClass: true,
				responsive: {
					0: {
						items: 1,
					},
					600: {
						items: 2,
					},
					768: {
						items: 3,
					},
					1024: {
						items: 3,
					},
					1280: {
						items: rigid_owl_carousel_cat.columns,
					}
				},
				dots: false,
				loop: false,
				nav: true,
				navText: [
					"<i class='fa fa-angle-left'></i>",
					"<i class='fa fa-angle-right'></i>"
				]
			});
		}

		/*************************
		 * "rigid-owl-carousel"
		 *************************/
		if (typeof rigid_owl_carousel !== 'undefined') {
            var is_rtl = false;
            if (rigid_rtl.is_rtl === 'true') {
                is_rtl = true;
            }
            $(".rigid-related-blog-posts div.rigid-owl-carousel, .similar_projects div.rigid-owl-carousel, .related.products div.rigid-owl-carousel", "#content:not(.has-sidebar)").owlCarousel({
				rtl: is_rtl,
				responsiveClass: true,
				responsive: {
					0: {
						items: 1,
					},
					600: {
						items: 2,
					},
					768: {
						items: 3,
					},
					1024: {
						items: 3,
					},
					1280: {
						items: 4,
					}
				},
				dots: false,
				nav: true,
				navText: [
					"<i class='fa fa-angle-left'></i>",
					"<i class='fa fa-angle-right'></i>"
				]
			});
		}
		if (typeof rigid_owl_carousel !== 'undefined') {
			var is_rtl = false;
			if (rigid_rtl.is_rtl === 'true') {
				is_rtl = true;
			}
			$(".rigid-related-blog-posts div.rigid-owl-carousel, .similar_projects div.rigid-owl-carousel, .related.products div.rigid-owl-carousel", "#content.has-sidebar").owlCarousel({
				rtl: is_rtl,
				responsiveClass: true,
				responsive: {
					0: {
						items: 1,
					},
					600: {
						items: 2,
					},
					768: {
						items: 3,
					},
					1024: {
						items: 3,
					},
					1280: {
						items: 3,
					}
				},
				dots: false,
				nav: true,
				navText: [
					"<i class='fa fa-angle-left'></i>",
					"<i class='fa fa-angle-right'></i>"
				]
			});
		}

		/**********************
		 * "rigid-quickview"
		 *********************/
		if (typeof rigid_quickview !== 'undefined') {
			$(document).on('click', 'a.rigid-quick-view-link', function (e) {

				$(this).closest('div.prod_hold').addClass('loading');
				var product_id = $(this).attr('data-id');
				var data = {action: 'rigid_quickview', productid: product_id};

				$.post(
								rigid_quickview.rigid_ajax_url, data, function (response) {

									$.magnificPopup.open({
										mainClass: 'rigid-quick-view-lightbox mfp-fade',
										items: {
											src: '<div class="rigid-quickview-product-pop">' + response + '</div>',
											type: 'inline'
										},
										callbacks: {
											open: function () {
												$('.rigid-quickview-product-pop form').wc_variation_form();

											},
											change: function () {
												$('.rigid-quickview-product-pop form').wc_variation_form();
											}
										},
										removalDelay: 300
									});

									$('.prod_hold.loading').removeClass('loading');

								});
				e.preventDefault();
			});
		}

		/***********************************
		 * "rigid-variation-prod-cloudzoom"
		 ***********************************/
		if (typeof rigid_variation_prod_cloudzoom !== 'undefined') {
			if (jQuery('#zoom1').length) {
				jQuery(document).on('update_variation_values', function () {

					jQuery('a.reset_variations').on('click', jQuery(this), function (event) {

						var o_href = $('#zoom1').attr('data-o_href');

						$('#zoom1').attr('href', o_href);
						jQuery('#zoom1').CloudZoom();
					});

					jQuery('table.variations select option').on('click', jQuery(this), function (event) {
						// Destroy the previous zoom
						if (jQuery('#zoom1').data('zoom')) {
							jQuery('#zoom1').data('zoom').destroy();
							jQuery('#zoom1').CloudZoom();
							return false;
						}
					});
				});
			}
		}

		/***********************************
		 * "rigid-ytplayer-conf"
		 ***********************************/
		if (typeof rigid_ytplayer_conf !== 'undefined') {
			$("div.rigid_bckgr_player", "html.no-touch").YTPlayer();
		}
		/* End Ready */
	});

	/************************
	 *  "rigid-ajax-search"
	 ************************/
	$.RigidAjaxSearch = function (options) {
		var defaults = {
			delay: 200, //delay in ms until the user stops typing.
			minChars: 3, //dont start searching before we got at least that much characters
			scope: 'body > div#search'

		};

		this.options = $.extend({}, defaults, options);
		this.scope = $(this.options.scope);
		this.timer = false;
		this.lastVal = "";
		this.bind_keyup();
	};

	$.RigidAjaxSearch.prototype =
					{
						bind_keyup: function ()
						{
							this.scope.on('keyup', '#s', $.proxy(this.attempt_search, this));
						},
						attempt_search: function (e)
						{
							clearTimeout(this.timer);
							//if the field is empty - clear the results
							if (e.currentTarget.value.trim().length == '') {
								var result = $('.ajax_search_result');
								if (result)
									result.remove();
							}

							//only execute search if chars are at least "minChars" and search differs from last one
							if (e.currentTarget.value.length >= this.options.minChars && this.lastVal != $.trim(e.currentTarget.value))
							{
								//wait at least "delay" miliseconds to execute ajax. if user types again during that time dont execute
								this.timer = setTimeout($.proxy(this.execute_search, this, e), this.options.delay);
							}
						},
						execute_search: function (e)
						{
							var obj = this,
											currentField = $(e.currentTarget).attr("autocomplete", "off"),
											form = currentField.parents('form:eq(0)'),
											results = form.find('.ajax_search_result'),
											loading = $('<div class="ajax_loading"><span class="ajax_loading_inner"></span></div>'),
											action = form.attr('action'),
											values = form.serialize();
							values += '&action=rigid_ajax_search';
							//check if the form got get parameters applied and also apply them
							if (action.indexOf('?') != -1)
							{
								action = action.split('?');
								values += "&" + action[1];
							}

							if (!results.length)
								results = $('<div class="ajax_search_result"></div>').appendTo(form);
							//return if we already hit a no result and user is still typing
							if (results.find('.ajax_not_found').length && e.currentTarget.value.indexOf(this.lastVal) != -1) {
								return;
							}
							this.lastVal = e.currentTarget.value;
							$.ajax({
								url: rigid_main_js_params.admin_url,
								type: "POST",
								data: values,
								beforeSend: function ()
								{
									if (!currentField.next('div.ajax_loading').length) {
										loading.insertAfter(currentField);
									}
								},
								success: function (response)
								{
									if (response == 0)
										response = "";
									results.html(response);
								},
								complete: function ()
								{
									loading.remove();
								}
							});
						}
					}
	/***************************
	 * END "rigid-ajax-search"
	 ***************************/

	/***************************
	 * rigid-scroll
	 * "scrolltopcontrol"
	 ***************************/
	var scrolltotop = {
		//startline: Integer. Number of pixels from top of doc scrollbar is scrolled before showing control
		//scrollto: Keyword (Integer, or "Scroll_to_Element_ID"). How far to scroll document up when control is clicked on (0=top).
		setting: {startline: 500, scrollto: 0, scrollduration: 1000, fadeduration: [500, 100]},
		controlHTML: '<span class="scroltopcontrol"></span>', //HTML for control, which is auto wrapped in DIV w/ ID="topcontrol"
		controlattrs: {offsetx: 10, offsety: 10}, //offset of control relative to right/ bottom of window corner
		anchorkeyword: '#top', //Enter href value of HTML anchors on the page that should also act as "Scroll Up" links

		state: {isvisible: false, shouldvisible: false},
		scrollup: function () {
			if (!this.cssfixedsupport) //if control is positioned using JavaScript
				this.$control.css({opacity: 0}) //hide control immediately after clicking it
			var dest = isNaN(this.setting.scrollto) ? this.setting.scrollto : parseInt(this.setting.scrollto)
			if (typeof dest == "string" && jQuery('#' + dest).length == 1) //check element set by string exists
				dest = jQuery('#' + dest).offset().top
			else
				dest = 0
			this.$body.animate({scrollTop: dest}, this.setting.scrollduration);
		},
		keepfixed: function () {
			var $window = jQuery(window)
			var controlx = $window.scrollLeft() + $window.width() - this.$control.width() - this.controlattrs.offsetx
			var controly = $window.scrollTop() + $window.height() - this.$control.height() - this.controlattrs.offsety
			this.$control.css({left: controlx + 'px', top: controly + 'px'})
		},
		togglecontrol: function () {
			var scrolltop = jQuery(window).scrollTop()
			if (!this.cssfixedsupport)
				this.keepfixed()
			this.state.shouldvisible = (scrolltop >= this.setting.startline) ? true : false
			if (this.state.shouldvisible && !this.state.isvisible) {
				this.$control.stop().animate({opacity: 1}, this.setting.fadeduration[0])
				this.state.isvisible = true
			}
			else if (this.state.shouldvisible == false && this.state.isvisible) {
				this.$control.stop().animate({opacity: 0}, this.setting.fadeduration[1])
				this.state.isvisible = false
			}
		},
		init: function () {
			jQuery(document).ready(function ($) {
				var mainobj = scrolltotop
				var iebrws = document.all
				mainobj.cssfixedsupport = !iebrws || iebrws && document.compatMode == "CSS1Compat" && window.XMLHttpRequest //not IE or IE7+ browsers in standards mode
				mainobj.$body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body')
				mainobj.$control = $('<div id="topcontrol">' + mainobj.controlHTML + '</div>')
								.css({position: mainobj.cssfixedsupport ? 'fixed' : 'absolute', bottom: mainobj.controlattrs.offsety, right: mainobj.controlattrs.offsetx, opacity: 0, cursor: 'pointer'})
								.attr({title: 'Scroll Back to Top'})
								.click(function () {
									mainobj.scrollup();
									return false
								})
								.appendTo('body')
				if (document.all && !window.XMLHttpRequest && mainobj.$control.text() != '') //loose check for IE6 and below, plus whether control contains any text
					mainobj.$control.css({width: mainobj.$control.width()}) //IE6- seems to require an explicit width on a DIV containing text
				mainobj.togglecontrol()
				$('a[href="' + mainobj.anchorkeyword + '"]').click(function () {
					mainobj.scrollup()
					return false
				})
				$(window).bind('scroll resize', function (e) {
					mainobj.togglecontrol()
				})
			})
		}
	}

	scrolltotop.init();
	/***************************
	 * END rigid-scroll
	 * "scrolltopcontrol"
	 ***************************/

})(window.jQuery);

/**********************
 * "rigid-quickview"
 *********************/
if (typeof rigid_quickview !== 'undefined') {
	/*global rigid_quickview, wc_cart_fragments_params */
    ;(function ( $, window, document, undefined ) {
        /**
         * VariationForm class which handles variation forms and attributes.
         */
        var VariationForm = function( $form ) {
            this.$form                = $form;
            this.$attributeFields     = $form.find( '.variations select' );
            this.$singleVariation     = $form.find( '.single_variation' ),
                this.$singleVariationWrap = $form.find( '.single_variation_wrap' );
            this.$resetVariations     = $form.find( '.reset_variations' );
            this.$product             = $form.closest( '.product' );
            this.variationData        = $form.data( 'product_variations' );
            this.useAjax              = false === this.variationData;
            this.xhr                  = false;

            // Initial state.
            this.$singleVariationWrap.show();
            this.$form.unbind( 'check_variations update_variation_values found_variation' );
            this.$resetVariations.unbind( 'click' );
            this.$attributeFields.unbind( 'change ' );

            // Methods.
            this.getChosenAttributes    = this.getChosenAttributes.bind( this );
            this.findMatchingVariations = this.findMatchingVariations.bind( this );
            this.isMatch                = this.isMatch.bind( this );
            this.toggleResetLink        = this.toggleResetLink.bind( this );

            // Events.
            $form.on( 'click', '.reset_variations', { variationForm: this }, this.onReset );
            $form.on( 'reload_product_variations', { variationForm: this }, this.onReload );
            $form.on( 'hide_variation', { variationForm: this }, this.onHide );
            $form.on( 'show_variation', { variationForm: this }, this.onShow );
            $form.on( 'click', '.single_add_to_cart_button', { variationForm: this }, this.onAddToCart );
            $form.on( 'reset_data', { variationForm: this }, this.onResetDisplayedVariation );
            $form.on( 'reset_image', { variationForm: this }, this.onResetImage );
            $form.on( 'change', '.variations select', { variationForm: this }, this.onChange );
            $form.on( 'found_variation', { variationForm: this }, this.onFoundVariation );
            $form.on( 'check_variations', { variationForm: this }, this.onFindVariation );
            $form.on( 'update_variation_values', { variationForm: this }, this.onUpdateAttributes );

            // Check variations once init.
            $form.trigger( 'check_variations' );
            $form.trigger( 'wc_variation_form' );

            // Swatches
            $( '.variations' ).rigid_wcs_variation_swatches_form();
            $( document.body ).trigger( 'rigid-wcs_initialized' );
        };

        /**
         * Reset all fields.
         */
        VariationForm.prototype.onReset = function( event ) {
            event.preventDefault();
            event.data.variationForm.$attributeFields.val( '' ).change();
            event.data.variationForm.$form.trigger( 'reset_data' );
        };

        /**
         * Reload variation data from the DOM.
         */
        VariationForm.prototype.onReload = function( event ) {
            var form           = event.data.variationForm;
            form.variationData = form.$form.data( 'product_variations' );
            form.useAjax       = false === form.variationData;
            form.$form.trigger( 'check_variations' );
        };

        /**
         * When a variation is hidden.
         */
        VariationForm.prototype.onHide = function( event ) {
            event.preventDefault();
            event.data.variationForm.$form.find( '.single_add_to_cart_button' ).removeClass( 'wc-variation-is-unavailable' ).addClass( 'disabled wc-variation-selection-needed' );
            event.data.variationForm.$form.find( '.woocommerce-variation-add-to-cart' ).removeClass( 'woocommerce-variation-add-to-cart-enabled' ).addClass( 'woocommerce-variation-add-to-cart-disabled' );
        };

        /**
         * When a variation is shown.
         */
        VariationForm.prototype.onShow = function( event, variation, purchasable ) {
            event.preventDefault();
            if ( purchasable ) {
                event.data.variationForm.$form.find( '.single_add_to_cart_button' ).removeClass( 'disabled wc-variation-selection-needed wc-variation-is-unavailable' );
                event.data.variationForm.$form.find( '.woocommerce-variation-add-to-cart' ).removeClass( 'woocommerce-variation-add-to-cart-disabled' ).addClass( 'woocommerce-variation-add-to-cart-enabled' );
            } else {
                event.data.variationForm.$form.find( '.single_add_to_cart_button' ).removeClass( 'wc-variation-selection-needed' ).addClass( 'disabled wc-variation-is-unavailable' );
                event.data.variationForm.$form.find( '.woocommerce-variation-add-to-cart' ).removeClass( 'woocommerce-variation-add-to-cart-enabled' ).addClass( 'woocommerce-variation-add-to-cart-disabled' );
            }
        };

        /**
         * When the cart button is pressed.
         */
        VariationForm.prototype.onAddToCart = function( event ) {
            if ( $( this ).is('.disabled') ) {
                event.preventDefault();

                if ( $( this ).is('.wc-variation-is-unavailable') ) {
                    window.alert( rigid_quickview.i18n_unavailable_text );
                } else if ( $( this ).is('.wc-variation-selection-needed') ) {
                    window.alert( rigid_quickview.i18n_make_a_selection_text );
                }
            }
        };

        /**
         * When displayed variation data is reset.
         */
        VariationForm.prototype.onResetDisplayedVariation = function( event ) {
            var form = event.data.variationForm;
            form.$product.find( '.product_meta' ).find( '.sku' ).wc_reset_content();
            form.$product.find( '.product_weight' ).wc_reset_content();
            form.$product.find( '.product_dimensions' ).wc_reset_content();
            form.$form.trigger( 'reset_image' );
            form.$singleVariation.slideUp( 200 ).trigger( 'hide_variation' );
        };

        /**
         * When the product image is reset.
         */
        VariationForm.prototype.onResetImage = function( event ) {
            event.data.variationForm.$form.wc_variations_image_update( false );
        };

        /**
         * Looks for matching variations for current selected attributes.
         */
        VariationForm.prototype.onFindVariation = function( event ) {
            var form              = event.data.variationForm,
                attributes        = form.getChosenAttributes(),
                currentAttributes = attributes.data;

            if ( attributes.count === attributes.chosenCount ) {
                if ( form.useAjax ) {
                    if ( form.xhr ) {
                        form.xhr.abort();
                    }
                    form.$form.block( { message: null, overlayCSS: { background: '#fff', opacity: 0.6 } } );
                    currentAttributes.product_id  = parseInt( form.$form.data( 'product_id' ), 10 );
                    currentAttributes.custom_data = form.$form.data( 'custom_data' );
                    form.xhr                      = $.ajax( {
                        url: wc_cart_fragments_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'get_variation' ),
                        type: 'POST',
                        data: currentAttributes,
                        success: function( variation ) {
                            if ( variation ) {
                                form.$form.trigger( 'found_variation', [ variation ] );
                            } else {
                                form.$form.trigger( 'reset_data' );
                                form.$form.find( '.single_variation' ).after( '<p class="wc-no-matching-variations woocommerce-info">' + rigid_quickview.i18n_no_matching_variations_text + '</p>' );
                                form.$form.find( '.wc-no-matching-variations' ).slideDown( 200 );
                            }
                        },
                        complete: function() {
                            form.$form.unblock();
                        }
                    } );
                } else {
                    form.$form.trigger( 'update_variation_values' );

                    var matching_variations = form.findMatchingVariations( form.variationData, currentAttributes ),
                        variation           = matching_variations.shift();

                    if ( variation ) {
                        form.$form.trigger( 'found_variation', [ variation ] );
                    } else {
                        form.$form.trigger( 'reset_data' );
                        form.$form.find( '.single_variation' ).after( '<p class="wc-no-matching-variations woocommerce-info">' + rigid_quickview.i18n_no_matching_variations_text + '</p>' );
                        form.$form.find( '.wc-no-matching-variations' ).slideDown( 200 );
                    }
                }
            } else {
                form.$form.trigger( 'update_variation_values' );
                form.$form.trigger( 'reset_data' );
            }

            // Show reset link.
            form.toggleResetLink( attributes.chosenCount > 0 );
        };

        /**
         * Triggered when a variation has been found which matches all attributes.
         */
        VariationForm.prototype.onFoundVariation = function( event, variation ) {
            var form           = event.data.variationForm,
                $sku           = form.$product.find( '.product_meta' ).find( '.sku' ),
                $weight        = form.$product.find( '.product_weight' ),
                $dimensions    = form.$product.find( '.product_dimensions' ),
                $qty           = form.$singleVariationWrap.find( '.quantity' ),
                purchasable    = true,
                variation_id   = '',
                template       = false,
                $template_html = '';

            if ( variation.sku ) {
                $sku.wc_set_content( variation.sku );
            } else {
                $sku.wc_reset_content();
            }

            if ( variation.weight ) {
                $weight.wc_set_content( variation.weight );
            } else {
                $weight.wc_reset_content();
            }

            if ( variation.dimensions ) {
                $dimensions.wc_set_content( variation.dimensions );
            } else {
                $dimensions.wc_reset_content();
            }

            form.$form.wc_variations_image_update( variation );

            if ( ! variation.variation_is_visible ) {
                template = wp.template( 'unavailable-variation-template' );
            } else {
                template     = wp.template( 'variation-template' );
                variation_id = variation.variation_id;
            }

            $template_html = template( {
                variation: variation
            } );
            $template_html = $template_html.replace( '/*<![CDATA[*/', '' );
            $template_html = $template_html.replace( '/*]]>*/', '' );

            form.$singleVariation.html( $template_html );
            form.$form.find( 'input[name="variation_id"], input.variation_id' ).val( variation.variation_id ).change();

            // Hide or show qty input
            if ( variation.is_sold_individually === 'yes' ) {
                $qty.find( 'input.qty' ).val( '1' ).attr( 'min', '1' ).attr( 'max', '' );
                $qty.hide();
            } else {
                $qty.find( 'input.qty' ).attr( 'min', variation.min_qty ).attr( 'max', variation.max_qty );
                $qty.show();
            }

            // Enable or disable the add to cart button
            if ( ! variation.is_purchasable || ! variation.is_in_stock || ! variation.variation_is_visible ) {
                purchasable = false;
            }

            // Reveal
            if ( $.trim( form.$singleVariation.text() ) ) {
                form.$singleVariation.slideDown( 200 ).trigger( 'show_variation', [ variation, purchasable ] );
            } else {
                form.$singleVariation.show().trigger( 'show_variation', [ variation, purchasable ] );
            }
        };

        /**
         * Triggered when an attribute field changes.
         */
        VariationForm.prototype.onChange = function( event ) {
            var form = event.data.variationForm;

            form.$form.find( 'input[name="variation_id"], input.variation_id' ).val( '' ).change();
            form.$form.find( '.wc-no-matching-variations' ).remove();

            if ( form.useAjax ) {
                form.$form.trigger( 'check_variations' );
            } else {
                form.$form.trigger( 'woocommerce_variation_select_change' );
                form.$form.trigger( 'check_variations' );
                $( this ).blur();
            }

            // Custom event for when variation selection has been changed
            form.$form.trigger( 'woocommerce_variation_has_changed' );
        };

        /**
         * Escape quotes in a string.
         * @param {string} string
         * @return {string}
         */
        VariationForm.prototype.addSlashes = function( string ) {
            string = string.replace( /'/g, '\\\'' );
            string = string.replace( /"/g, '\\\"' );
            return string;
        };

        /**
         * Updates attributes in the DOM to show valid values.
         */
        VariationForm.prototype.onUpdateAttributes = function( event ) {
            var form              = event.data.variationForm,
                attributes        = form.getChosenAttributes(),
                currentAttributes = attributes.data;

            if ( form.useAjax ) {
                return;
            }

            // Loop through selects and disable/enable options based on selections.
            form.$attributeFields.each( function( index, el ) {
                var current_attr_select     = $( el ),
                    current_attr_name       = current_attr_select.data( 'attribute_name' ) || current_attr_select.attr( 'name' ),
                    show_option_none        = $( el ).data( 'show_option_none' ),
                    option_gt_filter        = ':gt(0)',
                    attached_options_count  = 0,
                    new_attr_select         = $( '<select/>' ),
                    selected_attr_val       = current_attr_select.val() || '',
                    selected_attr_val_valid = true;

                // Reference options set at first.
                if ( ! current_attr_select.data( 'attribute_html' ) ) {
                    var refSelect = current_attr_select.clone();

                    refSelect.find( 'option' ).removeAttr( 'disabled attached' ).removeAttr( 'selected' );

                    current_attr_select.data( 'attribute_options', refSelect.find( 'option' + option_gt_filter ).get() ); // Legacy data attribute.
                    current_attr_select.data( 'attribute_html', refSelect.html() );
                }

                new_attr_select.html( current_attr_select.data( 'attribute_html' ) );

                // The attribute of this select field should not be taken into account when calculating its matching variations:
                // The constraints of this attribute are shaped by the values of the other attributes.
                var checkAttributes = $.extend( true, {}, currentAttributes );

                checkAttributes[ current_attr_name ] = '';

                var variations = form.findMatchingVariations( form.variationData, checkAttributes );

                // Loop through variations.
                for ( var num in variations ) {
                    if ( typeof( variations[ num ] ) !== 'undefined' ) {
                        var variationAttributes = variations[ num ].attributes;

                        for ( var attr_name in variationAttributes ) {
                            if ( variationAttributes.hasOwnProperty( attr_name ) ) {
                                var attr_val         = variationAttributes[ attr_name ],
                                    variation_active = '';

                                if ( attr_name === current_attr_name ) {
                                    if ( variations[ num ].variation_is_active ) {
                                        variation_active = 'enabled';
                                    }

                                    if ( attr_val ) {
                                        // Decode entities and add slashes.
                                        attr_val = $( '<div/>' ).html( attr_val ).text();

                                        // Attach.
                                        new_attr_select.find( 'option[value="' + form.addSlashes( attr_val ) + '"]' ).addClass( 'attached ' + variation_active );
                                    } else {
                                        // Attach all apart from placeholder.
                                        new_attr_select.find( 'option:gt(0)' ).addClass( 'attached ' + variation_active );
                                    }
                                }
                            }
                        }
                    }
                }

                // Count available options.
                attached_options_count = new_attr_select.find( 'option.attached' ).length;

                // Check if current selection is in attached options.
                if ( selected_attr_val && ( attached_options_count === 0 || new_attr_select.find( 'option.attached.enabled[value="' + form.addSlashes( selected_attr_val ) + '"]' ).length === 0 ) ) {
                    selected_attr_val_valid = false;
                }

                // Detach the placeholder if:
                // - Valid options exist.
                // - The current selection is non-empty.
                // - The current selection is valid.
                // - Placeholders are not set to be permanently visible.
                if ( attached_options_count > 0 && selected_attr_val && selected_attr_val_valid && ( 'no' === show_option_none ) ) {
                    new_attr_select.find( 'option:first' ).remove();
                    option_gt_filter = '';
                }

                // Detach unattached.
                new_attr_select.find( 'option' + option_gt_filter + ':not(.attached)' ).remove();

                // Finally, copy to DOM and set value.
                current_attr_select.html( new_attr_select.html() );
                current_attr_select.find( 'option' + option_gt_filter + ':not(.enabled)' ).prop( 'disabled', true );

                // Choose selected value.
                if ( selected_attr_val ) {
                    // If the previously selected value is no longer available, fall back to the placeholder (it's going to be there).
                    if ( selected_attr_val_valid ) {
                        current_attr_select.val( selected_attr_val );
                    } else {
                        current_attr_select.val( '' ).change();
                    }
                } else {
                    current_attr_select.val( '' ); // No change event to prevent infinite loop.
                }
            });

            // Custom event for when variations have been updated.
            form.$form.trigger( 'woocommerce_update_variation_values' );
        };

        /**
         * Get chosen attributes from form.
         * @return array
         */
        VariationForm.prototype.getChosenAttributes = function() {
            var data   = {};
            var count  = 0;
            var chosen = 0;

            this.$attributeFields.each( function() {
                var attribute_name = $( this ).data( 'attribute_name' ) || $( this ).attr( 'name' );
                var value          = $( this ).val() || '';

                if ( value.length > 0 ) {
                    chosen ++;
                }

                count ++;
                data[ attribute_name ] = value;
            });

            return {
                'count'      : count,
                'chosenCount': chosen,
                'data'       : data
            };
        };

        /**
         * Find matching variations for attributes.
         */
        VariationForm.prototype.findMatchingVariations = function( variations, attributes ) {
            var matching = [];
            if (typeof variations != 'undefined') {
                for (var i = 0; i < variations.length; i++) {
                    var variation = variations[i];

                    if (this.isMatch(variation.attributes, attributes)) {
                        matching.push(variation);
                    }
                }
            }
            return matching;
        };

        /**
         * See if attributes match.
         * @return {Boolean}
         */
        VariationForm.prototype.isMatch = function( variation_attributes, attributes ) {
            var match = true;
            for ( var attr_name in variation_attributes ) {
                if ( variation_attributes.hasOwnProperty( attr_name ) ) {
                    var val1 = variation_attributes[ attr_name ];
                    var val2 = attributes[ attr_name ];
                    if ( val1 !== undefined && val2 !== undefined && val1.length !== 0 && val2.length !== 0 && val1 !== val2 ) {
                        match = false;
                    }
                }
            }
            return match;
        };

        /**
         * Show or hide the reset link.
         */
        VariationForm.prototype.toggleResetLink = function( on ) {
            if ( on ) {
                if ( this.$resetVariations.css( 'visibility' ) === 'hidden' ) {
                    this.$resetVariations.css( 'visibility', 'visible' ).hide().fadeIn();
                }
            } else {
                this.$resetVariations.css( 'visibility', 'hidden' );
            }
        };

        /**
         * Function to call wc_variation_form on jquery selector.
         */
        $.fn.wc_variation_form = function() {
            new VariationForm( this );
            return this;
        };

        /**
         * Stores the default text for an element so it can be reset later
         */
        $.fn.wc_set_content = function( content ) {
            if ( undefined === this.attr( 'data-o_content' ) ) {
                this.attr( 'data-o_content', this.text() );
            }
            this.text( content );
        };

        /**
         * Stores the default text for an element so it can be reset later
         */
        $.fn.wc_reset_content = function() {
            if ( undefined !== this.attr( 'data-o_content' ) ) {
                this.text( this.attr( 'data-o_content' ) );
            }
        };

        /**
         * Stores a default attribute for an element so it can be reset later
         */
        $.fn.wc_set_variation_attr = function( attr, value ) {
            if ( undefined === this.attr( 'data-o_' + attr ) ) {
                this.attr( 'data-o_' + attr, ( ! this.attr( attr ) ) ? '' : this.attr( attr ) );
            }
            if ( false === value ) {
                this.removeAttr( attr );
            } else {
                this.attr( attr, value );
            }
        };

        /**
         * Reset a default attribute for an element so it can be reset later
         */
        $.fn.wc_reset_variation_attr = function( attr ) {
            if ( undefined !== this.attr( 'data-o_' + attr ) ) {
                this.attr( attr, this.attr( 'data-o_' + attr ) );
            }
        };

        /**
         * Reset the slide position if the variation has a different image than the current one
         */
        $.fn.wc_maybe_trigger_slide_position_reset = function( variation ) {
            var $form                = $( this ),
                $product             = $form.closest( '.product' ),
                $product_gallery     = $product.find( '.images' ),
                reset_slide_position = false,
                new_image_id = ( variation && variation.image_id ) ? variation.image_id : '';

            if ( $form.attr( 'current-image' ) !== new_image_id ) {
                reset_slide_position = true;
            }

            $form.attr( 'current-image', new_image_id );

            if ( reset_slide_position ) {
                $product_gallery.trigger( 'woocommerce_gallery_reset_slide_position' );
            }
        };

        /**
         * Sets product images for the chosen variation
         */
        $.fn.wc_variations_image_update = function( variation ) {
            var $form             = this,
                $product          = $form.closest( '.product' ),
                $product_gallery  = $product.find( '.images' ),
                $gallery_img      = $product.find( '.flex-control-nav li:eq(0) img' ),
                $product_img_wrap = $product_gallery.find( '.woocommerce-product-gallery__image, .woocommerce-product-gallery__image--placeholder' ).eq( 0 ),
                $product_img      = $product_img_wrap.find( '.wp-post-image' ),
                $product_link     = $product_img_wrap.find( 'a' ).eq( 0 );

            if ( variation && variation.image && variation.image.src && variation.image.src.length > 1 ) {
                $product_img.wc_set_variation_attr( 'src', variation.image.src );
                $product_img.wc_set_variation_attr( 'height', variation.image.src_h );
                $product_img.wc_set_variation_attr( 'width', variation.image.src_w );
                $product_img.wc_set_variation_attr( 'srcset', variation.image.srcset );
                $product_img.wc_set_variation_attr( 'sizes', variation.image.sizes );
                $product_img.wc_set_variation_attr( 'title', variation.image.title );
                $product_img.wc_set_variation_attr( 'alt', variation.image.alt );
                $product_img.wc_set_variation_attr( 'data-src', variation.image.full_src );
                $product_img.wc_set_variation_attr( 'data-large_image', variation.image.full_src );
                $product_img.wc_set_variation_attr( 'data-large_image_width', variation.image.full_src_w );
                $product_img.wc_set_variation_attr( 'data-large_image_height', variation.image.full_src_h );
                $product_img_wrap.wc_set_variation_attr( 'data-thumb', variation.image.src );
                $gallery_img.wc_set_variation_attr( 'src', variation.image.thumb_src );
                $product_link.wc_set_variation_attr( 'href', variation.image.full_src );
            } else {
                $product_img.wc_reset_variation_attr( 'src' );
                $product_img.wc_reset_variation_attr( 'width' );
                $product_img.wc_reset_variation_attr( 'height' );
                $product_img.wc_reset_variation_attr( 'srcset' );
                $product_img.wc_reset_variation_attr( 'sizes' );
                $product_img.wc_reset_variation_attr( 'title' );
                $product_img.wc_reset_variation_attr( 'alt' );
                $product_img.wc_reset_variation_attr( 'data-src' );
                $product_img.wc_reset_variation_attr( 'data-large_image' );
                $product_img.wc_reset_variation_attr( 'data-large_image_width' );
                $product_img.wc_reset_variation_attr( 'data-large_image_height' );
                $product_img_wrap.wc_reset_variation_attr( 'data-thumb' );
                $gallery_img.wc_reset_variation_attr( 'src' );
                $product_link.wc_reset_variation_attr( 'href' );
            }

            window.setTimeout( function() {
                $product_gallery.trigger( 'woocommerce_gallery_init_zoom' );
                $form.wc_maybe_trigger_slide_position_reset( variation );
                $( window ).trigger( 'resize' );
            }, 10 );
        };

        $(function() {
            if ( typeof rigid_quickview !== 'undefined' ) {
                $( '.variations_form' ).each( function() {
                    $( this ).wc_variation_form();
                });
            }
        });

        /**
         * Matches inline variation objects to chosen attributes
         * @deprecated 2.6.9
         * @type {Object}
         */
        var wc_variation_form_matcher = {
            find_matching_variations: function( product_variations, settings ) {
                var matching = [];
                for ( var i = 0; i < product_variations.length; i++ ) {
                    var variation    = product_variations[i];

                    if ( wc_variation_form_matcher.variations_match( variation.attributes, settings ) ) {
                        matching.push( variation );
                    }
                }
                return matching;
            },
            variations_match: function( attrs1, attrs2 ) {
                var match = true;
                for ( var attr_name in attrs1 ) {
                    if ( attrs1.hasOwnProperty( attr_name ) ) {
                        var val1 = attrs1[ attr_name ];
                        var val2 = attrs2[ attr_name ];
                        if ( val1 !== undefined && val2 !== undefined && val1.length !== 0 && val2.length !== 0 && val1 !== val2 ) {
                            match = false;
                        }
                    }
                }
                return match;
            }
        };

    })( jQuery, window, document );
}
/**********************
 * END "rigid-quickview"
 *********************/

/* NON jQuery */

/**********************
 * "rigid-map-config"
 *********************/
if (typeof rigid_map_config !== 'undefined') {
	var directionsDisplayCnt;
	var directionsServiceCnt = new google.maps.DirectionsService();

// Here you can customize the direction line color, weigth and opacity.
	var polylineOptionsActualCnt = new google.maps.Polyline({strokeColor: '#585858', strokeOpacity: 0.7, strokeWeight: 4});

	function initializeCnt() { // Place the coordinates of your store here.
		var latlng = new google.maps.LatLng(rigid_map_config.lattitude, rigid_map_config.longitude);
		directionsDisplayCnt = new google.maps.DirectionsRenderer();
		directionsDisplayCnt = new google.maps.DirectionsRenderer({suppressMarkers: true, polylineOptions: polylineOptionsActualCnt});

		var myOptionsCnt = {
			// By changing this number you can define the resolution of the current view.
			// Zoom level between 0 (the lowest zoom level, in which the entire world can be seen on one map) to 21+
			// (down to individual buildings)
			zoom: 17,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControl: true,
			scrollwheel: false
		};

		var mapCnt = new google.maps.Map(document.getElementById("rigid_map_canvas"), myOptionsCnt);

		directionsDisplayCnt.setMap(mapCnt);
		//directionsDisplayCnt.setPanel(document.getElementById("directionsPanel"));

		// Here you can change the path, size and pivot point of the marker on the map.
		var image = new google.maps.MarkerImage(rigid_map_config.images + 'marker.png', new google.maps.Size(45, 48), new google.maps.Point(0, 0), new google.maps.Point(25, 40));

		// Here you can change the path, size and pivot point of the marker's shadow on the map.
		var shadow = new google.maps.MarkerImage(rigid_map_config.images + 'shadow.png', new google.maps.Size(26, 10), new google.maps.Point(0, 0), new google.maps.Point(10, 4));

		// Change the title of your store. People see this when they hover over your marker.
		var marker = new google.maps.Marker({position: latlng, map: mapCnt, shadow: shadow, title: rigid_map_config.location_title, icon: image});

		// This function will make your marker bounce. When you click on it, it will toggle between bouncing and static.
		// You can comment out if you don't whant your marker to bounce.
		toggleBounce();

		google.maps.event.addListener(marker, 'click', toggleBounce);

		function toggleBounce() {
			if (marker.getAnimation() != null) {
				marker.setAnimation(null);
			} else {
				marker.setAnimation(google.maps.Animation.BOUNCE);
			}
		}
	}

// Change the coordinates below to those of your store. (should be the same as the coordinates above.
	function calcRouteOnContacts() {
		var start = document.getElementById("routeStart").value;
// Fill in the cordinates of your store. See readme file for help.
		var end = rigid_map_config.lattitude + "," + rigid_map_config.longitude;
		var request = {origin: start, destination: end, travelMode: google.maps.DirectionsTravelMode.DRIVING};

		directionsServiceCnt.route(request, function (response, status) {
			if (status == google.maps.DirectionsStatus.OK) {
				directionsDisplayCnt.setDirections(response);
			}
		});
	}

	google.maps.event.addDomListener(window, 'load', initializeCnt);
}
/*************************
 * END "rigid-map-config"
 *************************/
