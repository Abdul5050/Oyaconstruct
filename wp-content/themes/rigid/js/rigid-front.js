(function ($) {
	"use strict";
    var rigid_ajaxXHR = null;
    var is_mailto_or_tel_link = false;

	/* If preloader is enabled */
	if (rigid_main_js_params.show_preloader) {
		$(window).load(function () {
			$("#loader").delay(100).fadeOut();
			$(".mask").delay(300).fadeOut();
		});

	}
	$(window).load(function () {
		checkRevealFooter();
		defineMegaMenuSizing();

		$("html.no-touch div.owl-item, html.no-touch .rigid_woo_categories_shop div.product-category.product, div.woocommerce > div.product-category.product").hover(
			function () {
				$(this).siblings().stop().addClass('rigid_bw_filter')
			},
			function () {
				$(this).siblings().stop().removeClass('rigid_bw_filter')
			}
		)
	});

	$(document).ready(function () {

		//
		// -------------------------------------------------------------------------------------------------------
		// Dropdown Menu
		// -------------------------------------------------------------------------------------------------------

		$('.box-sort-filter select, div.summary .variations_form td.value select, .widget_archive select, .widget_categories select').niceSelect();

		/*
		 * Animated page transition
		 */
		$(window).on('beforeunload', function () {
			if(!is_mailto_or_tel_link) {
                $("body").animate({opacity: 0}, "slow");
            }
		});

		/*
		 * Special Characters
		 */

		$('h1,h2,h3,h4,h5,h6').each(function() {
			$(this).html(
				$(this).html()
					.replace(/&nbsp;/gi,'')
			);
		});

		$('.rigid-pricing-heading h5, .rigid-iconbox h5, .vc_custom_heading').each(function() {
			$(this).html(
				$(this).html()
					.replace(".",'<span class="rigid-spec-dot">.</span>')
			);
		});

		/*
		 * Remove resposive images functionality for CloudZoom galleries
		*/
		$('#wrap a.cloud-zoom img').removeAttr('srcset');
		$("div.summary.entry-summary table.variations td:has(div.rigid-wcs-swatches) ").addClass("rigid-has-swatches-option");
		$("ul#topnav li:has(ul), ul#topnav2 li:has(ul), ul.menu li:has(ul) ").addClass("dropdown");
		$("#header:has(div#header_top) ").addClass("rigid-has-header-top");
		$("ul.menu li:has(div)").addClass("has-mega");
		$("#header_top .inner:has(div#menu)").addClass("has-top-menu");
		$("#header .main_menu_holder:has(div#main-menu)").addClass("has-main-menu");

		/*
		 * Manipulate the cart
		 */

		$('#header #cart-module div.widget.woocommerce.widget_shopping_cart').prependTo('body');
		$('body > div.widget.woocommerce.widget_shopping_cart').prepend('<span class="close-cart-button"></span>');
		$('body > #search').prepend('<span class="close-search-button"></span>');

		/* REMOVE PARENTHESIS ON WOO CATEGORIES */

			$('.count').text(function(_, text) {
				return text.replace(/\(|\)/g, '');
			});

		/**
		 * Sticky header (if on)
		 */
		if ((rigid_main_js_params.sticky_header) && ($('#container').has('#header').length)) {
			$("body:not(.rigid_header_left) #header").addClass('original_header').before($("#header").clone().addClass("animateIt").removeClass('original_header'));
			$('#header.animateIt').attr('id', 'header2');
			$('#header2.animateIt #header_top').remove();
			$(window).on("scroll", function () {
				var showStickyMenu = $('#header').offset().top;
				$("body").toggleClass("down", ($(window).scrollTop() > showStickyMenu + 200));
			});

			$('body.rigid_logo_center_menu_below #header .rigid-search-cart-holder, body.rigid_logo_left_menu_below #header .rigid-search-cart-holder').prependTo('#header #main-menu');
			$('body.rigid_logo_center_menu_below #header2 .rigid-search-cart-holder, body.rigid_logo_left_menu_below #header2 .rigid-search-cart-holder').prependTo('#header2 #main-menu');

		}

        defineCartIconClickBehaviour();

		var customTitleHeight = $('body.rigid_transparent_header #header').height();
		$('body.rigid_transparent_header:not(.rigid_header_left) .rigid_title_holder .inner').css("padding-top", customTitleHeight + 250);
		var customTitleHeight2 = $('body.rigid-overlay-header #header').height();
		$('body.rigid-overlay-header:not(.rigid_header_left) .rigid_title_holder .inner').css("padding-top", customTitleHeight2 + 250);


		$('#header .rigid-search-trigger a, #header2 .rigid-search-trigger a, .close-search-button').on('click', function (event) {
			event.stopPropagation();
			$("body > #search").toggleClass("active");
		});

		$('#main-menu .rigid-mega-menu').css("display", "");

		$('p.demo_store').prependTo('#content .inner #main');
		$('body.woocommerce-account #customer_login.col2-set, .woocommerce #customer_login.u-columns.col2-set').addClass('owl-carousel');
		var is_rtl = false;
		if (rigid_main_js_params.is_rtl === 'true') {
			is_rtl = true;
		}
		$("body.woocommerce-account #customer_login.col2-set, .woocommerce #customer_login.u-columns.col2-set").owlCarousel({
			rtl: is_rtl,
			items: 1,
			dots: false,
			mouseDrag: false,
			nav: true,
			navText: [
				rigid_main_js_params.login_label,
				rigid_main_js_params.register_label
			]
		});

		//
		// -------------------------------------------------------------------------------------------------------
		// Mobile Menu
		// -------------------------------------------------------------------------------------------------------
		$(".mob-menu-toggle, .mob-close-toggle, ul#mobile-menu.menu li:not(.menu-item-has-children) a").on('click', function (event) {
			event.stopPropagation();
			$("#menu_mobile").toggleClass("active");
		});
        $("ul#mobile-menu.menu .menu-item a").each(function() {
            if( $(this).html() == "–"){
                $(this).remove();
            }
        });

        $("ul#mobile-menu.menu > li.menu-item-has-children > a").prepend('<span class="drop-mob">+</span>');
        $("ul#mobile-menu.menu > li.menu-item-has-children > a .drop-mob").on('click', function (event) {
            event.preventDefault();
            $(this).closest('li').find('ul.sub-menu').toggleClass("active");
        });
		$(document).click( function(e){
			if (!$(e.target).closest('.widget_shopping_cart').hasClass('active_cart')) {
				$("body > div.widget.woocommerce.widget_shopping_cart").removeClass("active_cart");
			}
			if (!$(e.target).closest('#menu_mobile').hasClass('active')) {
				$("#menu_mobile").removeClass("active");
			}
			if (!$(e.target).closest('#search').hasClass('active')) {
				$("#search.active").removeClass("active");
			}
			if (!$(e.target).closest('.off-canvas-sidebar').hasClass('active_sidebar')) {
				$(".sidebar.off-canvas-sidebar").removeClass("active_sidebar");
			}
		});

		$(".video_controlls a#video-volume").click(function () {
			$(".video_controlls a#video-volume").toggleClass("disabled");
		});

		$(document.body).find('a[href="#"], a.cloud-zoom, #header2 a.cart-contents').on('click', function (event) {
			event.preventDefault();
		});
		

		$('a[href$=".mov"] , a[href$=".swf"], a[href$=".mp4"], a[href*="vimeo.com/"], a[href*="youtube.com/watch"]').magnificPopup({
			disableOn: 700,
			type: 'iframe',
			mainClass: 'mfp-fade is-rigid-video',
			removalDelay: 160,
			preloader: false,
			fixedContentPos: false
		});

		$(".prod_hold a.add_to_wishlist").attr("title", "Add to wishlist");

		// -------------------------------------------------------------------------------------------------------
		// SLIDING ELEMENTS
		// -------------------------------------------------------------------------------------------------------

		$('a#toggle_switch').toggle(function () {
			if ($(this).hasClass("swap")) {
				$(this).removeClass("swap");
            } else {
				$(this).addClass("swap");
			}
			$('#togglerone').slideToggle("slow");

			return false;
		}, function () {
			$('#togglerone').slideToggle("slow");

			if ($(this).hasClass("swap")) {
				$(this).removeClass("swap");
			} else {
				$(this).addClass("swap");
			}
			return false;
		});

		if (!document.getElementById("rigid_page_title")) {
			$('body').addClass('page-no-title');
		} else {
			$('body').addClass('page-has-title');
		}

		$('body.page-no-title .sidebar-trigger, body.rigid-accent-tearoff .sidebar-trigger').prependTo('#header .rigid-search-cart-holder');
		if  ($('div#rigid_page_title .inner').has('div.breadcrumb').length) {
			$('.video_controlls').appendTo('div.breadcrumb');
		} else {
			$('.video_controlls').prependTo('#header .rigid-search-cart-holder');
		}


		$('.sidebar-trigger, .close-off-canvas').click(function (event) {
			event.stopPropagation();
			$(".off-canvas-sidebar").toggleClass("active_sidebar");
		});

        $(document.body).on('click', 'a.rigid-filter-widgets-triger', function () {
            $("#rigid-filter-widgets").toggleClass("rigid_active_filter_area");
        });

		$('a.rigid-filter-widgets-triger').toggle(function () {
			$('#rigid-filter-widgets').slideToggle("slow");

			return false;
		}, function () {
			$('#rigid-filter-widgets').slideToggle("slow");
			return false;
		});

		$(".pull-item.left, .pull-item.right").hover(function () {
			$(this).addClass('active');
		}, function () {
			$(this).removeClass('active');
		});

		$('html.no-touch .rigid-from-bottom').each(function () {
			$(this).appear(function () {
				$(this).delay(300).animate({opacity: 1, bottom: "0px"}, 500);
			});
		});

		$('html.no-touch .rigid-from-left').each(function () {
			$(this).appear(function () {
				$(this).delay(300).animate({opacity: 1, left: "0px"}, 500);
			});
		});

		$('html.no-touch .rigid-from-right').each(function () {
			$(this).appear(function () {
				$(this).delay(300).animate({opacity: 1, right: "0px"}, 500);
			});
		});

		$('html.no-touch .rigid-fade').each(function () {
			$(this).appear(function () {
				$(this).delay(300).animate({opacity: 1}, 700);
			});
		});

		$('html.no-touch div.prod_hold, html.no-touch .wpb_rigid_banner:not(.rigid-from-bottom), html.no-touch .wpb_rigid_banner:not(.rigid-from-left), html.no-touch .wpb_rigid_banner:not(.rigid-from-right), html.no-touch .wpb_rigid_banner:not(.rigid-fade)').each(function () {
			$(this).appear(function () {
				$(this).addClass('prod_visible').delay(800);
			});
		});

		$('.rigid-counter:not(.already_seen)').each(function () {
			$(this).appear(function () {

				$(this).prop('Counter', 0).animate({
					Counter: $(this).text()
				}, {
					duration: 3000,
					decimals: 2,
					easing: 'swing',
					step: function (now) {
						$(this).text(Math.ceil(now).toLocaleString('en'));
					}
				});
				$(this).addClass('already_seen');

			});
		});

		// -------------------------------------------------------------------------------------------------------
		// FADING ELEMENTS
		// -------------------------------------------------------------------------------------------------------

		$(window).scroll(function () {
			$("html.no-touch .rigid_title_holder.title_has_image .inner").css("opacity", 1 - $(window).scrollTop() / 375);
		});

		// Put class .last on each 4th widget in the footer
		$('#slide_footer div.one_fourth').filter(function (index) {
			return index % 4 === 3;
		}).addClass('last').after('<div class="clear"></div>');
		$('#footer > div.inner div.one_fourth').filter(function (index) {
			return index % 4 === 3;
		}).addClass('last').after('<div class="clear"></div>');
		// Put class .last on each 4th widget in pre header
		$('#pre_header > div.inner div.one_fourth').filter(function (index) {
			return index % 4 === 3;
		}).addClass('last').after('<div class="clear"></div>');
        $('#rigid-filter-widgets > div.one_fourth').filter(function (index) {
            return index % 4 === 3;
        }).addClass('last').after('<div class="clear"></div>');

		// Put class .last on each 3th widget in the footer
		$('#slide_footer div.one_third').filter(function (index) {
			return index % 3 === 2;
		}).addClass('last').after('<div class="clear"></div>');
		$('#footer > div.inner div.one_third').filter(function (index) {
			return index % 3 === 2;
		}).addClass('last').after('<div class="clear"></div>');
		// Put class .last on each 3th widget in pre header
		$('#pre_header > div.inner div.one_third').filter(function (index) {
			return index % 3 === 2;
		}).addClass('last').after('<div class="clear"></div>');
        $('#rigid-filter-widgets > div.one_third').filter(function (index) {
            return index % 3 === 2;
        }).addClass('last').after('<div class="clear"></div>');

		// Put class .last on each 2nd widget in the footer
		$('#slide_footer div.one_half').filter(function (index) {
			return index % 2 === 1;
		}).addClass('last').after('<div class="clear"></div>');
		$('#footer > div.inner div.one_half').filter(function (index) {
			return index % 2 === 1;
		}).addClass('last').after('<div class="clear"></div>');
		// Put class .last on each 2nd widget in pre header
		$('#pre_header > div.inner div.one_half').filter(function (index) {
			return index % 2 === 1;
		}).addClass('last').after('<div class="clear"></div>');
        $('#rigid-filter-widgets > div.one_half').filter(function (index) {
            return index % 2 === 1;
        }).addClass('last').after('<div class="clear"></div>');

        // Woocommerce part columns
        $('.woocommerce.columns-2:not(.owl-carousel) div.prod_hold').filter(function (index) {
            return index % 2 === 1;
        }).addClass('last').after('<div class="clear"></div>');
        $('.woocommerce.columns-3:not(.owl-carousel) div.prod_hold').filter(function (index) {
            return index % 3 === 2;
        }).addClass('last').after('<div class="clear"></div>');
        $('.woocommerce.columns-4:not(.owl-carousel) div.prod_hold').filter(function (index) {
            return index % 4 === 3;
        }).addClass('last').after('<div class="clear"></div>');
        $('.woocommerce.columns-5:not(.owl-carousel) div.prod_hold').filter(function (index) {
			return index % 5 === 4;
		}).addClass('last').after('<div class="clear"></div>');
		$('.woocommerce.columns-6:not(.owl-carousel) div.prod_hold').filter(function (index) {
			return index % 6 === 5;
		}).addClass('last').after('<div class="clear"></div>');


		// Number of products to show in category
		// per_page and auto load
		$('select.per_page').change(function () {
			$('.woocommerce-ordering').submit();
		});

		function addQty() {
			var input = $(this).parent().find('input[type=number]');

			if (isNaN(input.val())) {
				input.val(0);
			}
			input.val(parseInt(input.val()) + 1);
		}

		function subtractQty() {
			var input = $(this).parent().find('input[type=number]');
			if (isNaN(input.val())) {
				input.val(1);
			}
			if (input.val() > 1) {
				input.val(parseInt(input.val()) - 1);
			}
		}

		$(".rigid-qty-plus").on('click', addQty);
		$(".rigid-qty-minus").on('click', subtractQty);

		if ($('#cart-module').length !== 0) {
			track_ajax_add_to_cart();
			$('body').bind('added_to_cart', update_cart_dropdown);
		}

		$(".rigid-latest-grid.rigid-latest-blog-col-3 div.post:nth-child(3n)").after("<div class='clear'></div>");
		$(".rigid-latest-grid.rigid-latest-blog-col-2 div.post:nth-child(2n)").after("<div class='clear'></div>");
		$(".rigid-latest-grid.rigid-latest-blog-col-4 div.post:nth-child(4n)").after("<div class='clear'></div>");
		$(".rigid-latest-grid.rigid-latest-blog-col-5 div.post:nth-child(5n)").after("<div class='clear'></div>");
		$(".rigid-latest-grid.rigid-latest-blog-col-6 div.post:nth-child(6n)").after("<div class='clear'></div>");

		// HIDE EMPTY COMMENTS DIV
		$('div#comments').each(function () {
			if ($(this).children().length == 0) {
				$(this).hide();
			}
		});

		// Smooth scroll
		var scrollDuration = 0;
		if (rigid_main_js_params.enable_smooth_scroll) {
			scrollDuration = 1500;
		}

		$("li.menu-item a[href*='#']:not([href='#']), .wpb_text_column a[href*='#']:not([href='#']), a.vc_btn3[href*='#']:not([href='#']), a.woocommerce-review-link, .vc_icon_element a[href*='#']:not([href='#'])").click(function () {
			if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
				var target = $(this.hash);
				target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
				if (target.length) {
					$('html,body').animate({
						scrollTop: target.offset().top - 75
					}, scrollDuration, 'swing');
				}
				return false;
			}
		});


		/**
		 * This part handles the menu highlighting functionality.
		 * When using anchors
		 */
		var aChildren = $("li.menu-item a[href*='#']:not([href='#'])"); // find the a children of the list items
		var aArray = []; // create the empty aArray
		for (var i = 0; i < aChildren.length; i++) {
			var aChild = aChildren[i];
			var ahref = $(aChild).attr('href');
			aArray.push(ahref);
		} // this for loop fills the aArray with attribute href values

		$(window).scroll(function () {
			var windowPos = $(window).scrollTop(); // get the offset of the window from the top of page
			var windowHeight = $(window).height(); // get the height of the window
			var docHeight = $(document).height();

			for (var i = 0; i < aArray.length; i++) {
				var theID = aArray[i];
				if ((theID).length && undefined !== $(theID).offset()) {
					var divPos = $(theID).offset().top - 145; // get the offset of the div from the top of page
					var divHeight = $(theID).height(); // get the height of the div in question
					if (windowPos >= divPos && windowPos < (divPos + divHeight)) {
						$("li.current-menu-item").removeClass("current-menu-item");
						$("li.menu-item a[href='" + theID + "']").parent().addClass("current-menu-item");
					}
				}
			}

			if (windowPos + windowHeight == docHeight) {
				if (!$("li.menu-item:last-child").hasClass("current-menu-item")) {
					var navActiveCurrent = $("li.current-menu-item a").attr("href");
					$("a[href='" + navActiveCurrent + "']").parent().removeClass("current-menu-item");
					$("li.menu-item:last-child a").addClass("current-menu-item");
				}
			}
		});

		// Add to cart Ajax if enable_ajax_add_to_cart is set in the WooCommerce settings and product is simple or variable
        var is_simple = $('div#products-wrapper>.product-type-simple').length;
        var is_variable = $('div#products-wrapper>.product-type-variable').length;

        if(rigid_main_js_params.enable_ajax_add_to_cart === 'yes' && (is_simple || is_variable)) {
            $(".single_add_to_cart_button").click(function (e) {

                // perfrom the html5 validation
                if($(this).parents('form.cart')[0].checkValidity()){
                    e.preventDefault();
                } else {
                    return true;
                }

                // If we've chosen unavailable variation don't execute
                if(!$( this ).is('.wc-variation-is-unavailable')) {

                    var $add_to_cart_form = $(this).parents('form');

                    var variation_id = $add_to_cart_form.find('input[name="variation_id"]').val();
                    var quantity = $add_to_cart_form.find('input[name="quantity"]').val();

                    if (is_variable) {
                        var product_id = $add_to_cart_form.find('input[name="add-to-cart"]').val();
                    } else {
                        var product_id = $add_to_cart_form.find('button[name="add-to-cart"]').val();
                    }

                    var data = {product_id: product_id, quantity: quantity, product_sku: ""};

                    // AJAX add to cart request.
                    var $thisbutton = $(this);

                    // Trigger event.
                    $(document.body).trigger('adding_to_cart', [$thisbutton, data]);

                    //AJAX call
                    if (is_variable && variation_id !== '') {
                        $thisbutton.addClass('loading');
                        $thisbutton.prop('disabled', true);

                        var add_to_cart_ajax_data = {};
                        add_to_cart_ajax_data.action = 'rigid_wc_add_cart';
                        add_to_cart_ajax_data.product_id = product_id;
                        add_to_cart_ajax_data.variation_id = variation_id;
                        add_to_cart_ajax_data.quantity = quantity;

                        // get the variation attributes
                        var $selected_attributes = $add_to_cart_form.find( "[name^='attribute_']" );
                        $selected_attributes.each(function(){
                            add_to_cart_ajax_data[$(this).attr('name')] = $(this).val();
                        });

                        $.ajax({
                            url: rigid_main_js_params.admin_url,
                            type: 'POST',
                            data: $.param(add_to_cart_ajax_data),

                            success: function (results) {
                                // Redirect to cart option
                                if (rigid_main_js_params.cart_redirect_after_add === 'yes') {
                                    window.location = rigid_main_js_params.cart_url;
                                    return;
                                } else {
                                    // Trigger event so themes can refresh other areas
                                    $(document.body).trigger('added_to_cart', [results.fragments, results.cart_hash, $thisbutton]);
                                }
                            },
                            complete: function (jqXHR, status) {
                                $thisbutton.removeClass('loading');
                                $thisbutton.prop('disabled', false);
                            }
                        });
                    } else if (!is_variable) {
                        $thisbutton.addClass('loading');
                        $thisbutton.prop('disabled', true);

                        $.ajax({
                            url: rigid_main_js_params.admin_url,
                            type: 'POST',
                            data: 'action=rigid_wc_add_cart&product_id=' + product_id + '&quantity=' + quantity,

                            success: function (results) {
                                // Redirect to cart option
                                if (rigid_main_js_params.cart_redirect_after_add === 'yes') {
                                    window.location = rigid_main_js_params.cart_url;
                                    return;
                                } else {
                                    // Trigger event so themes can refresh other areas
                                    $(document.body).trigger('added_to_cart', [results.fragments, results.cart_hash, $thisbutton]);
                                }
                            },
                            complete: function (jqXHR, status) {
                                $thisbutton.removeClass('loading');
                                $thisbutton.prop('disabled', false);
                            }
                        });
                    }
                }
            });
        }

        // Initialise the small countdowns on products list
        rigidInitSmallCountdowns($('div.prod_hold'));

        // if is set infinite load on shop - run it de..
        if(rigid_main_js_params.enable_infinite_on_shop === 'yes') {
        	// hide the pagination
            var $pagination = $('#products-wrapper').find('div.pagination');
            $pagination.hide();

            // If enabled load more button
			if(rigid_main_js_params.use_load_more_on_shop === 'yes') {
                $('body').on('click', 'div.rigid-shop-pager.rigid-infinite button.rigid-load-more', function (e) {
                	$(this).hide();
                    $('body').find('div.rigid-shop-pager.rigid-infinite a.next_page').click();
                });
			} else {
                // Track scrolling, hunting for infinite ajax load
                $(window).on("scroll", function () {
                    if ($('body').find('div.rigid-shop-pager.rigid-infinite').is(':in-viewport')) {
                        $('body').find('div.rigid-shop-pager.rigid-infinite a.next_page').click();
                    }
                });
            }

            // Shop Page
            $('body').on('click', 'div.rigid-shop-pager.rigid-infinite a.next_page', function (e) {
                e.preventDefault();

                if ($(this).data('requestRunning')) {
                    return;
                }

                $(this).data('requestRunning', true);

                var $products = $('#products-wrapper').find('div.box-products.woocommerce');
                var $pageStatus = $pagination.prevAll('.rigid-page-load-status');

                $pageStatus.children('.infinite-scroll-last').hide();
                $pageStatus.children('.infinite-scroll-request').show();
                $pageStatus.show();

                $.get(
                    $(this).attr('href'),
                    function (response) {

                        $.rigid_refresh_products_after_ajax(response, $products, $pagination, $pageStatus);

                        $(document.body).trigger('rigid_shop_ajax_loading_success');
                    }
                );
            });
        }

        if(typeof rigid_portfolio_js_params !== 'undefined') {

            var $container = $('div.portfolios', '#main');

            var $isotopedGrid = $container.isotope({
                itemSelector: 'div.portfolio-unit',
                layoutMode: 'masonry',
                transitionDuration: '0.5s'
            });

            // layout Isotope after each image loads
            $isotopedGrid.imagesLoaded().progress(function () {
                $isotopedGrid.isotope('layout');
            });

            // bind filter button click
            $('.rigid-portfolio-categories').on('click', 'a', function () {
                var filterValue = $(this).attr('data-filter');
                // use filterFn if matches value
                $isotopedGrid.isotope({filter: filterValue});
            });

            // change is-checked class on buttons
            $('div.rigid-portfolio-categories', '#main').each(function (i, buttonGroup) {
                var $buttonGroup = $(buttonGroup);
                $buttonGroup.on('click', 'a', function () {
                    $buttonGroup.find('.is-checked').removeClass('is-checked');
                    $(this).addClass('is-checked');
                });
            });

            // if is set infinite load on portfolio - run it de..
            if (rigid_portfolio_js_params.enable_portfolio_infinite === 'yes') {
                // hide the pagination
                var $pagination = $('div.portfolio-nav').find('div.pagination');
                $pagination.hide();

                // If enabled load more button
                if (rigid_portfolio_js_params.use_load_more_on_portfolio === 'yes') {
                    $('body').on('click', 'div.portfolio-nav.rigid-infinite button.rigid-load-more', function (e) {
                        $(this).hide();
                        $pagination.find('a.next_page').click();
                    });
                } else {
                    // Track scrolling, hunting for infinite ajax load
                    $(window).on("scroll", function () {
                        if ($('body').find('div.portfolio-nav.rigid-infinite').is(':in-viewport')) {
                            $pagination.find('a.next_page').click();
                        }
                    });
                }

                // Portfolio List Page
                $('body').on('click', 'div.portfolio-nav.rigid-infinite a.next_page', function (e) {
                    e.preventDefault();

                    if ($(this).data('requestRunning')) {
                        return;
                    }

                    $(this).data('requestRunning', true);

                    var $pageStatus = $pagination.prevAll('.rigid-page-load-status');

                    $pageStatus.children('.infinite-scroll-last').hide();
                    $pageStatus.children('.infinite-scroll-request').show();
                    $pageStatus.show();

                    $.get(
                        $(this).attr('href'),
                        function (response) {

                            var $newPortfolios = $(response).find('.content_holder').find('.portfolio-unit');
                            var $pagination_html = $(response).find('.portfolio-nav .pagination').html();

                            $pagination.html($pagination_html);

                            // Now add the new portfolios to the list
                            $newPortfolios.imagesLoaded(function () {
                                $isotopedGrid.isotope('insert', $newPortfolios);
                            });

                            // Add magnific function
                            $isotopedGrid.isotope('on', 'layoutComplete',
                                function (isoInstance, laidOutItems) {
                                    $('a.portfolio-lightbox-link').magnificPopup({
                                        mainClass: 'mfp-fade',
                                        type: 'image'
                                    });
                                }
                            );

                            $pagination.find('a.next_page').data('requestRunning', false);
                            // hide loading
                            $pageStatus.children('.infinite-scroll-request').hide();

                            $(document.body).trigger('rigid_portfolio_ajax_loading_success');

                            if (!$pagination.find('a.next_page').length) {
                                $pageStatus.children('.infinite-scroll-last').show();
                                $('button.rigid-load-more').hide();
                            } else {
                                $('button.rigid-load-more').show();
                            }
                        }
                    );
                });
            }
        }

        // AJAXIFY products listing filters, widgets, etc
        if(rigid_main_js_params.use_product_filter_ajax === 'yes') {
            // products ordering and per page
            var woocommerceOrderingForm = $(document.body).find('form.woocommerce-ordering');
            if (woocommerceOrderingForm.length) {
                woocommerceOrderingForm.on('submit', function (e) {
                    e.preventDefault();
                });

                $(document.body).on('change', 'form.woocommerce-ordering select.orderby, form.woocommerce-ordering select.per_page', function (e) {
                    e.preventDefault();

                    var currentUrlParams = window.location.search;
                    var url = window.location.href.replace(window.location.search, '') + rigidUpdateUrlParameters(currentUrlParams, woocommerceOrderingForm.serialize());

                    $(document.body).trigger('rigid_products_filter_ajax', [url, woocommerceOrderingForm]);
                });
            }

            // price slider
            $(document.body).find('#rigid-price-filter-form').on('submit', function (e) {
                e.preventDefault();
            });

            $(document.body).on('price_slider_change', function (event, ui) {
                var form = $('.price_slider').closest('form').get(0);
                var $form = $(form);

                var currentUrlParams = window.location.search;
                var url = $form.attr('action') + rigidUpdateUrlParameters(currentUrlParams, $form.serialize());

                $(document.body).trigger('rigid_products_filter_ajax', [url, $(this)]);
            });

            // rigid_product_filter
            $(document.body).on('click', 'div.rigid_product_filter a', function (e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $(document.body).trigger('rigid_products_filter_ajax', [url, $(this)]);
            });
        }

        // Set flag when mailto: and tel: links are clicked
		$(document.body).on('click', 'div.widget_rigid_contacts_widget a, div.rigid-top-bar-message a', function (e){
            is_mailto_or_tel_link = true;
		});

        // Share links
		$(document.body).on('click', 'div.rigid-share-links a', function(e){
            window.open(this.href,'targetWindow','toolbar=no,location=0,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=300');
            return false;
		});

        // End of document.ready()
	});

	// Handle the products filtering
    $(document.body).on('rigid_products_filter_ajax', function (e, url, element) {

        var $products = $('#products-wrapper').find('div.box-products.woocommerce');
        var $pagination = $('#products-wrapper').find('div.pagination');
        var $pageStatus = $pagination.prevAll('.rigid-page-load-status');

        $.rigid_show_loader();

        if ('?' == url.slice(-1)) {
            url = url.slice(0, -1);
        }

        url = url.replace(/%2C/g, ',');
        window.history.pushState({page: url}, "", url);

        if (rigid_ajaxXHR) {
            rigid_ajaxXHR.abort();
        }

        rigid_ajaxXHR = $.get(url, function (res) {

        	// Empty the products container
            $products.empty();

            $.rigid_refresh_product_filters_areas(res);
            $.rigid_refresh_products_after_ajax(res, $products, $pagination, $pageStatus);

            $.rigid_hide_loader();
            $(document.body).trigger('rigid_products_filter_ajax_success', [res, url]);
        }, 'html');

    });

    window.onresize = function () {
        checkRevealFooter();
		defineMegaMenuSizing();
    };

    /**
     * Initialise the small countdowns on products list
     * @param prodHoldElements
     */
    window.rigidInitSmallCountdowns = function (prodHoldElements) {
        $(prodHoldElements).each(function () {
                var data = $(this).find('.count_holder_small').data();
                if (typeof data !== 'undefined') {
                    $(data.countdownId).countdown({
                        until: new Date(data.countdownTo),
                        compact: false,
                        layout: '<span class="countdown_time_tiny">{dn} {dl} {hn}:{mnn}:{snn}</span>'
                    });
                }
            }
        )
    };


	/* Mega Menu */

	function defineMegaMenuSizing() {

		$('#header #main-menu .rigid-mega-menu').each(function () {
			var menu = $('#header .main_menu_holder').offset();
			var menuColumns = $(this).find('li.rigid_colum_title').length;
			$(this).addClass('menu-columns' + menuColumns);
			var dropdown = $(this).parent().offset();
			var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#header .main_menu_holder').outerWidth());
			if (i > 0) {
				$(this).css('margin-left', '-' + (i + 10) + 'px');
			}
		});

		$('#header2 #main-menu .rigid-mega-menu').each(function () {
			var menu = $('#header2 .main_menu_holder').offset();
			var menuColumns = $(this).find('li.rigid_colum_title').length;
			$(this).addClass('menu-columns' + menuColumns);
			var dropdown = $(this).parent().offset();
			var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#header2 .main_menu_holder').outerWidth());
			if (i > 0) {
				$(this).css('margin-left', '-' + (i + 10) + 'px');
			}
		});

	}

    /**
	 * Define behaviour for click on shopping cart icon
     */
    function defineCartIconClickBehaviour() {
        $(document).on("click", "#rigid_quick_cart_link", function(event) {
            event.preventDefault();
            event.stopPropagation();

            var shoppingCart = $("body > div.widget.woocommerce.widget_shopping_cart");

            shoppingCart.addClass("active_cart");

        });

        $(document).on("click", ".close-cart-button", function(event) {
            var $parent = $(this).parent();
            $parent.removeClass('active_cart');
        });
    }

	function checkRevealFooter() {
		var isReveal = $('#footer').height() - 1;
		if (isReveal < 550 && $('body').hasClass("rigid_fullwidth")) {
			$('html.no-touch body.rigid_fullwidth.rigid-reveal-footer #content').css("margin-bottom", isReveal + "px");
			$('body.rigid_fullwidth.rigid-reveal-footer #footer').addClass('rigid_do_reveal');
		} else {
			$('html.no-touch body.rigid_fullwidth.rigid-reveal-footer #content').css("margin-bottom", 0 + "px");
			$('body.rigid_fullwidth.rigid-reveal-footer #footer').removeClass('rigid_do_reveal');

		}
	}

	/**
	 * Override vc_rowBehaviour for stretch row
	 */

	window.vc_rowBehaviour = function () {
		function fullWidthRow() {
			var $elements = $('[data-vc-full-width="true"], #content:not(.has-sidebar) p.woocommerce-thankyou-order-received, #content:not(.has-sidebar) .rigid-author-info, #content:not(.has-sidebar) ul.woocommerce-order-overview.woocommerce-thankyou-order-details.order_details');
			$.each($elements, function (key, item) {
				var $el = $(this);
				$el.addClass("vc_hidden");
				var $el_full = $el.next(".vc_row-full-width");
				$el_full.length || ($el_full = $el.parent().next(".vc_row-full-width"));
				var el_margin_left = parseInt($el.css("margin-left"), 10),
					el_margin_right = parseInt($el.css("margin-right"), 10);

				// VC code
				// offset = 0 - $el_full.offset().left - el_margin_left,
				// width = $(window).width();
				// End VC code

				// Althemist edit
				var width = $('#content').width();
                var row_padding = 40;

				var offset = -($('#content').width() - $('#content > .inner ').css("width").replace("px", "")) / 2 - row_padding;
				// End Althemist edit

				// RTL support
				var right_offset = "auto";
				var left_offset = "auto";
				var is_rtl = false;
				if (rigid_main_js_params.is_rtl === 'true') {
					is_rtl = true;
				}

				if (is_rtl) {
					right_offset = offset + 15;
				} else {
					left_offset = offset + 15;
				}

				if ($el.css({
						position: "relative",
						left: left_offset,
						right: right_offset,
						"box-sizing": "border-box",
						// VC code: width: $(window).width()
						// Althemist
						'width': $('#content').width(),
						// End Althemist
					}), !$el.data("vcStretchContent")) {
					var padding = -1 * offset;
					0 > padding && (padding = 0);
					var paddingRight = width - padding - $el_full.width() + el_margin_left + el_margin_right;
					0 > paddingRight && (paddingRight = 0), $el.css({
						"padding-left": padding + "px",
						"padding-right": padding + "px"
					})
				}
				$el.attr("data-vc-full-width-init", "true"), $el.removeClass("vc_hidden")
			}), $(document).trigger("vc-full-width-row", $elements)
		}

		function parallaxRow() {
			var vcSkrollrOptions, callSkrollInit = !1;
			return window.vcParallaxSkroll && window.vcParallaxSkroll.destroy(), $(".vc_parallax-inner").remove(), $("[data-5p-top-bottom]").removeAttr("data-5p-top-bottom data-30p-top-bottom"), $("[data-vc-parallax]").each(function () {
				var skrollrSpeed, skrollrSize, skrollrStart, skrollrEnd, $parallaxElement, parallaxImage, youtubeId;
				callSkrollInit = !0, "on" === $(this).data("vcParallaxOFade") && $(this).children().attr("data-5p-top-bottom", "opacity:0;").attr("data-30p-top-bottom", "opacity:1;"), skrollrSize = 100 * $(this).data("vcParallax"), $parallaxElement = $("<div />").addClass("vc_parallax-inner").appendTo($(this)), $parallaxElement.height(skrollrSize + "%"), parallaxImage = $(this).data("vcParallaxImage"), youtubeId = vcExtractYoutubeId(parallaxImage), youtubeId ? insertYoutubeVideoAsBackground($parallaxElement, youtubeId) : "undefined" != typeof parallaxImage && $parallaxElement.css("background-image", "url(" + parallaxImage + ")"), skrollrSpeed = skrollrSize - 100, skrollrStart = -skrollrSpeed, skrollrEnd = 0, $parallaxElement.attr("data-bottom-top", "top: " + skrollrStart + "%;").attr("data-top-bottom", "top: " + skrollrEnd + "%;")
			}), callSkrollInit && window.skrollr ? (vcSkrollrOptions = {
				forceHeight: !1,
				smoothScrolling: !1,
				mobileCheck: function () {
					return !1
				}
			}, window.vcParallaxSkroll = skrollr.init(vcSkrollrOptions), window.vcParallaxSkroll) : !1
		}

		function fullHeightRow() {
			var $element = $(".vc_row-o-full-height:first");
			if ($element.length) {
				var $window, windowHeight, offsetTop, fullHeight;
				$window = $(window), windowHeight = $window.height(), offsetTop = $element.offset().top, windowHeight > offsetTop && (fullHeight = 100 - offsetTop / (windowHeight / 100), $element.css("min-height", fullHeight + "vh"))
			}
			$(document).trigger("vc-full-height-row", $element)
		}

		function fixIeFlexbox() {
			var ua = window.navigator.userAgent,
				msie = ua.indexOf("MSIE ");
			(msie > 0 || navigator.userAgent.match(/Trident.*rv\:11\./)) && $(".vc_row-o-full-height").each(function () {
				"flex" === $(this).css("display") && $(this).wrap('<div class="vc_ie-flexbox-fixer"></div>')
			})
		}

		var $ = window.jQuery;
		$(window).off("resize.vcRowBehaviour").on("resize.vcRowBehaviour", fullWidthRow).on("resize.vcRowBehaviour", fullHeightRow), fullWidthRow(), fullHeightRow(), fixIeFlexbox(), vc_initVideoBackgrounds(), parallaxRow()
	};

//updates the shopping cart in the sidebar, hooks into the added_to_cart event whcih is triggered by woocommerce
	function update_cart_dropdown(event)
	{
		var product = jQuery.extend({name: "Product", price: "", image: ""}, rigid_added_product);
		var notice = $("<div class='rigid_added_to_cart_notification'>" + product.image + "<div class='added-product-text'><strong>" + product.name + " " + rigid_main_js_params.added_to_cart_label + "</strong></div></div>");

		if (typeof event !== 'undefined')
		{
			//$("body").append(notice).fadeIn('slow');
			if ($('#cart_add_sound').length) {
				$('#cart_add_sound')[0].play && $('#cart_add_sound')[0].play();
				$("body > div.widget.woocommerce.widget_shopping_cart").addClass("active_cart");
			}

            defineCartIconClickBehaviour();

			notice.appendTo($("body")).hide().fadeIn('slow');
			setTimeout(function () {
				notice.fadeOut('slow');
			}, 2000);
			setTimeout(function () {
				$("body > div.widget.woocommerce.widget_shopping_cart").removeClass("active_cart");
			}, 8000);
		}
	}

	var rigid_added_product = {};
	function track_ajax_add_to_cart()
	{
		jQuery('body').on('click', '.add_to_cart_button', function ()
		{
			var productContainer = jQuery(this).parents('.product').eq(0), product = {};
			product.name = productContainer.find('span.name').text();
			product.image = productContainer.find('div.image img');
			product.price = productContainer.find('.price_hold .amount').last().text();

			/*fallbacks*/
			if (productContainer.length === 0)
			{
				return;
			}

			if (product.image.length)
			{
				product.image = "<img class='added-product-image' src='" + product.image.get(0).src + "' title='' alt='' />";
			}
			else
			{
				product.image = "";
			}

			rigid_added_product = product;
		});
	}

	// Showing loader
	jQuery.rigid_show_loader = function () {

		var overlay;
		if ($('.shopbypricefilter-overlay').length) {
			overlay = $('.shopbypricefilter-overlay');
		} else {
			overlay = $('<div class="ui-widget-overlay shopbypricefilter-overlay">&nbsp;</div>').prependTo('body');
		}

		$(overlay).css({
			'position': 'fixed',
			'top': 0,
			'left': 0,
			'width': '100%',
			'height': '100%',
			'z-index': 19999,
		});

		$('.shopbypricefilter-overlay').each(function () {
			var overlay = this;
			var img;

			if ($('img', overlay).length) {
				img = $('img', overlay);
			} else {
				img = $('<img id="price_fltr_loading_gif" src="' + rigid_main_js_params.img_path + 'loading3.gif" />').prependTo(overlay);
			}

			$(img).css({
				'max-height': $(overlay).height() * 0.8,
				'max-width': $(overlay).width() * 0.8
			});

			$(img).css({
				'position': 'fixed',
				'top': $(window).outerHeight() / 2,
				'left': ($(window).outerWidth() - $(img).width()) / 2
			});
		}).show();

	};

    // Hiding loader
    jQuery.rigid_hide_loader = function () {
        $('.shopbypricefilter-overlay').remove();
    };

    // Refresh product filters area
	jQuery.rigid_refresh_product_filters_areas = function(response){
    	// rigid_product_filter widget
		var $rigid_product_filters = $(document.body).find('div.rigid_product_filter');
        var $new_rigid_product_filters = $(response).find('div.rigid_product_filter');

        $rigid_product_filters.each(function(index) {
        	// if widget is not present in the returned content, remove it
        	if($new_rigid_product_filters.get(index) !== 'undefined') {
                $(this).html($($new_rigid_product_filters.get(index)).html());
            }
		})
    };

    // Refresh products list after ajax calls
    jQuery.rigid_refresh_products_after_ajax = function (response, $products, $pagination, $pageStatus) {

        var $newProducts = $(response).find('.content_holder').find('.prod_hold');
        var $pagination_html = $(response).find('.rigid-shop-pager .pagination').html();

        if(typeof $pagination_html === 'undefined') {
            $pagination.html('');
		} else {
            $pagination.html($pagination_html);
		}


        // Do the necessary for the appending products
        $newProducts.imagesLoaded(function () {
            $newProducts.each(function () {
                $(this).addClass('rigid-infinite-loaded');

                if($(document.documentElement).hasClass('no-touch')) {
                    $(this).appear(function () {
                        $(this).addClass('prod_visible').delay(800);
                    });
                }
            });
        });

        // Now add the new products to the list
        $products.append($newProducts);

        rigidInitSmallCountdowns($newProducts);

        // Woocommerce part columns
        $('.woocommerce.columns-2:not(.owl-carousel) div.prod_hold').filter(function (index) {
            if ($(this).next().hasClass('clear')) {
                return false;
            } else {
                return index % 2 === 1;
            }
        }).addClass('last').after('<div class="clear"></div>');
        $('.woocommerce.columns-3:not(.owl-carousel) div.prod_hold').filter(function (index) {
            if ($(this).next().hasClass('clear')) {
                return false;
            } else {
                return index % 3 === 2;
            }
        }).addClass('last').after('<div class="clear"></div>');
        $('.woocommerce.columns-4:not(.owl-carousel) div.prod_hold').filter(function (index) {
            if ($(this).next().hasClass('clear')) {
                return false;
            } else {
                return index % 4 === 3;
            }
        }).addClass('last').after('<div class="clear"></div>');
        $('.woocommerce.columns-5:not(.owl-carousel) div.prod_hold').filter(function (index) {
            if ($(this).next().hasClass('clear')) {
                return false;
            } else {
                return index % 5 === 4;
            }
        }).addClass('last').after('<div class="clear"></div>');
        $('.woocommerce.columns-6:not(.owl-carousel) div.prod_hold').filter(function (index) {
            if ($(this).next().hasClass('clear')) {
                return false;
            } else {
                return index % 6 === 5;
            }
        }).addClass('last').after('<div class="clear"></div>');

        $pagination.find('a.next_page').data('requestRunning', false);
        // hide loading
        $pageStatus.children('.infinite-scroll-request').hide();

        if (!$pagination.find('a.next_page').length) {
            $pageStatus.children('.infinite-scroll-last').show();
            $('button.rigid-load-more').hide();
        } else {
            $('button.rigid-load-more').show();
        }
    }

})(window.jQuery);

// non jQuery scripts below
"use strict";
// Add or Update a key-value pairs in the URL query parameters (with leading '?')
function rigidUpdateUrlParameters(currentParams, newParams) {

	if(currentParams.trim() === '') {
		return "?" + newParams;
	}

    var newParamsObj = {};
    newParams.split('&').forEach(function(x){
        var arr = x.split('=');
        arr[1] && (newParamsObj[arr[0]] = arr[1]);
    });

    for (var prop in newParamsObj) {
        // remove the hash part before operating on the uri
        var i = currentParams.indexOf('#');
        var hash = i === -1 ? '' : uri.substr(i);
        currentParams = i === -1 ? currentParams : currentParams.substr(0, i);

        var re = new RegExp("([?&])" + prop + "=.*?(&|$)", "i");
        var separator = "&";
        if (currentParams.match(re)) {
            currentParams = currentParams.replace(re, '$1' + prop + "=" + newParamsObj[prop] + '$2');
        } else {
            currentParams = currentParams + separator + prop + "=" + newParamsObj[prop];
        }
        currentParams + hash;  // finally append the hash as well
    }

    return currentParams;
}

