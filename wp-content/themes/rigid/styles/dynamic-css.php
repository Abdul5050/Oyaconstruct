<?php
/**
 * Insert the customized css from selected options on wp_head hook + the custom css
 */
add_action('wp_enqueue_scripts', 'rigid_add_custom_css', 99);

if (!function_exists('rigid_add_custom_css')) {

	function rigid_add_custom_css() {
		ob_start();
		?>
		<style media="all" type="text/css">
			/* Site main accent color */
			div.widget_categories ul li.current-cat > a:before, .wpb_rigid_banner:hover .rigid_banner_buton, a#cancel-comment-reply-link, .infinite-scroll-request:before, .widget_layered_nav_filters li a:before, .links a.button.add_to_cart_button:after, .links a.button.add_to_cart_button.ajax_add_to_cart:after, div.prod_hold .name sup, blockquote:before, q:before, #main-menu li ul.sub-menu li a sup, div.prod_hold .name sub, #content div.product div.summary h1.heading-title sup, #content div.product div.summary h1.heading-title sub, .rigid-spec-dot, .count_holder .count_info:before, .rigid-pricing-table-shortcode .title-icon-holder, .count_holder .count_info_left:before, .widget_layered_nav ul li:hover .count, .widget_layered_nav ul li.chosen a, .widget_product_categories ul li:hover > .count, .widget_product_categories ul li.current-cat > a, .widget_layered_nav ul li:hover a:before, .widget_product_categories ul li:hover a:before, #rigid_price_range, .wpb_rigid_banner a span.rigid_banner-icon, .rigid-event-countdown .is-countdown, .video_controlls a#video-volume:after, div.widget_categories ul li > a:hover:before, #main-menu ul.menu > li > a:hover, #main-menu ul.menu > li.current-menu-item > a, .otw-input-wrap:before, .summary.entry-summary .yith-wcwl-add-to-wishlist a:before, .summary.entry-summary .compare::before, .prod_hold .price_hold:before, a.bbp-forum-title:hover, .portfolio_top .project-data .main-features .checklist li:before, body.rigid_transparent_header #main-menu ul.menu > li.current_page_item > a:before, body.rigid_transparent_header #main-menu ul.menu > li.current-menu-item > a:before, body.rigid_transparent_header #main-menu ul.menu > li > a:hover:before {
				color:<?php echo esc_attr(rigid_get_option('accent_color')) ?>;
			}
			.tribe-mini-calendar-event .list-date, div:not(.rigid_blog_masonry) > .blog-post.sticky .rigid_post_data_holder:before, .wcmp_vendor_list .wcmp_sorted_vendors:before, #cart-module .cart-contents span.count, .rigid-wishlist-counter a .rigid-wish-number, .tribe-events-list div.type-tribe_events .tribe-events-event-cost, .tribe-events-schedule .tribe-events-cost, .woocommerce form.track_order input.button, #main-menu.rigid-strikethrough-accent li ul.sub-menu li:not(.rigid_colum_title) > a:before, #bbpress-forums li.bbp-body ul.forum:hover, #bbpress-forums li.bbp-body ul.topic:hover, .woocommerce-shipping-fields input[type="checkbox"]:checked + span:before, a.close-off-canvas:hover, span.close-cart-button:hover, #search.active > span.close-search-button:hover, .widget_product_categories ul li.current-cat > .count, .widget_layered_nav ul li.chosen .count, .bypostauthor > .comment-body img.avatar, div.product-category.product a h2:after, .rigid_added_to_cart_notification, #yith-wcwl-popup-message, .rigid-iconbox h5:after, .rigid-pricing-heading h5:after, .rigid_title_holder.title_has_image.centered_title .inner h1.heading-title:before, a.sidebar-trigger, .woocommerce #content table.wishlist_table.cart a.remove, td.tribe-events-present > div:first-of-type, a.mob-close-toggle:hover, .pagination .links a:hover, .dokan-pagination-container .dokan-pagination li a:hover, a.mob-menu-toggle i, .bbp-pagination-links a:hover, .rigid_content_slider .owl-dot.active span, #main-menu ul.menu > li > .rigid-custom-menu-label, .product-category.product h2 mark:after, #main-menu li ul.sub-menu li.rigid_colum_title > a:after, #main-menu li ul.sub-menu li.rigid_colum_title > a:before, body.rigid_transparent_header #header #main-menu.rigid-line-accent ul.menu > li:before, .blog-post-meta span.sticky_post, .rigid_image_list a.rigid-magnific-gallery-item:before, #bbpress-forums > #subscription-toggle a.subscription-toggle, .widget > h3:first-child:before, h2.widgettitle:before, .widget > h3:first-child:after, .rigid-portfolio-categories ul li a:hover:before, .rigid-portfolio-categories ul li a.is-checked:before, .rigid-portfolio-categories ul li a:hover:after, .rigid-portfolio-categories ul li a.is-checked:after, .flex-direction-nav a, ul.status-closed li.bbp-topic-title .bbp-topic-permalink:before, ul.sticky li.bbp-topic-title .bbp-topic-permalink:before, ul.super-sticky li.bbp-topic-title .bbp-topic-permalink:before {
				background-color:<?php echo esc_attr(rigid_get_option('accent_color')) ?>;
			}
			.bbp-topics-front ul.super-sticky:hover, .bbp-topics ul.super-sticky:hover, .bbp-topics ul.sticky:hover, .bbp-forum-content ul.sticky:hover {
				background-color:<?php echo esc_attr(rigid_get_option('accent_color')) ?> !important;
			}
			.rigid-product-slider .count_holder, div:not(.rigid_blog_masonry) > .blog-post.sticky .rigid_post_data_holder, #bbpress-forums li.bbp-body ul.forum:hover, #bbpress-forums li.bbp-body ul.topic:hover, div.product div.images ol.flex-control-nav li img.flex-active, div.product div.images ol.flex-control-nav li:hover img, .bbp-topics-front ul.super-sticky, .widget_layered_nav ul li:hover .count, .widget_layered_nav ul li.chosen .count, .widget_product_categories ul li.current-cat > .count, .widget_product_categories ul li:hover .count, #main-menu li ul.sub-menu li.rigid-highlight-menu-item:after, .error404 div.blog-post-excerpt, .rigid-none-overlay.rigid-10px-gap .portfolio-unit-holder:hover, .portfolio-unit-info a.portfolio-lightbox-link:hover, .rigid_banner_text:before, .rigid_banner_text:after, body table.booked-calendar td.today .date span, .vc_tta-color-white.vc_tta-style-modern .vc_tta-tab.vc_active > a, .bbp-topics ul.super-sticky, .bbp-topics ul.sticky, .bbp-forum-content ul.sticky, a.sidebar-trigger:hover:after, .rigid-pulsator-accent .wpb_wrapper:after {
				border-color:<?php echo esc_attr(rigid_get_option('accent_color')) ?> !Important;
			}
			::-moz-selection {
				background:<?php echo esc_attr(rigid_get_option('accent_color')) ?>;
			}
			::selection {
				background:<?php echo esc_attr(rigid_get_option('accent_color')) ?>;
			}
			.box-sort-filter .ui-slider-horizontal .ui-slider-handle, .topic .bbp-private-reply, .widget_price_filter .ui-slider-handle.ui-state-default.ui-corner-all {
				background:<?php echo esc_attr(rigid_get_option('accent_color')) ?> !Important;
			}
			.widget_shopping_cart_content a.remove:hover { background:<?php echo esc_attr(rigid_get_option('accent_color')) ?>; }
			.double-bounce2 { background-color:<?php echo esc_attr(rigid_get_option('accent_color')) ?>; }
			/* Links color */
			a, div.widget_categories ul li a:hover, div.widget_nav_menu ul li a:hover, div.widget_archive ul li a:hover, div.widget_recent_comments ul li a:hover, div.widget_pages ul li a:hover, div.widget_links ul li a:hover, div.widget_recent_entries ul a:hover, div.widget_meta ul li a:hover, div.widget_display_forums ul li a:hover, .widget_display_replies ul li a:hover, .widget_display_topics li > a.bbp-forum-title:hover, .widget_display_stats dt:hover, .widget_display_stats dd:hover, div.widget_display_views ul li a:hover, .widget_layered_nav ul li a:hover, .widget_product_categories ul li a:hover {color:<?php echo esc_attr(rigid_get_option('links_color')) ?>;}
			/* Links hover color */
			a:hover{color:<?php echo esc_attr(rigid_get_option('links_hover_color')) ?>;}
			/* Widgets Title Color */
			.sidebar .box h3, .wpb_widgetised_column .box h3, h2.widgettitle, h2.wpb_flickr_heading{color:<?php echo esc_attr(rigid_get_option('sidebar_titles_color')) ?>;}
			/* Buttons Default style */
			<?php if (rigid_get_option('all_buttons_style') === 'round'): ?>
			.rigid-wcs-swatches .swatch {
				border-radius: 50%;
				-webkit-border-radius: 50%;
				-moz-border-radius: 50%;
			}
			span.onsale {
				-webkit-border-radius: 3em;
				-moz-border-radius: 3em;
				border-radius: 3em;
			}
			.count_holder .count_info {
				-webkit-border-radius: 3px 3em 3em 3px;
				-moz-border-radius: 3px 3em 3em 3px;
				border-radius: 3px 3em 3em 3px;
			}
			.count_holder .count_info_left {
				-webkit-border-radius: 3em 3px 3px 3em;
				-moz-border-radius: 3em 3px 3px 3em;
				border-radius: 3em 3px 3px 3em;
			}
			.product-type-external .count_holder .count_info_left {
				border-radius: 3em 3em 3em 3em;
			}
			a.button, .wcv-navigation ul.menu.horizontal li a, form .vendor_sort select, .wcv-pro-dashboard input[type="submit"], .rigid-pricing-table-button a, div.widget_product_search input[type="text"], div.widget_search input[type="text"], .widget_display_search input#bbp_search, #bbpress-forums > #subscription-toggle a.subscription-toggle, .bbp-topic-title span.bbp-st-topic-support, div.quantity, .rigid-wcs-swatches .swatch.swatch-label, #main-menu.rigid-pills-accent ul.menu > li > a:before, .rigid_banner_buton, nav.woocommerce-MyAccount-navigation ul li a, .woocommerce .wishlist_table td.product-add-to-cart a.button, .widget_shopping_cart_content p.buttons .button, input.button, button.button, a.button-inline, #submit_btn, #submit, .wpcf7-submit, #bbpress-forums #bbp-search-form #bbp_search, input[type="submit"], form.mc4wp-form input[type=submit], form.mc4wp-form input[type=email] {
				border-radius: 2em;
			}
			<?php endif; ?>
			/* Wordpress Default Buttons Color */
			a.button, button.wcv-button, input.button, .wcv-navigation ul.menu.horizontal li a, input.button, nav.woocommerce-MyAccount-navigation ul li a, .woocommerce .wishlist_table td.product-add-to-cart a.button, button.button, a.button-inline, #submit_btn, #submit, .wpcf7-submit, input.otw-submit, form.mc4wp-form input[type=submit], .tribe-events-button, input[type="submit"] {background-color:<?php echo esc_attr(rigid_get_option('all_buttons_color')) ?>;}
			/* Wordpress Default Buttons Hover Color */
			a.button:hover, input.button:hover, .wcv-navigation ul.menu.horizontal li a:hover, .wcv-navigation ul.menu.horizontal li.active a, button.button:hover, nav.woocommerce-MyAccount-navigation ul li.is-active a, .woocommerce .wishlist_table td.product-add-to-cart a.button:hover, nav.woocommerce-MyAccount-navigation ul li a:hover, a.button-inline:hover, #submit_btn:hover, #submit:hover, .wpcf7-submit:hover, .r_more:hover, .r_more_right:hover, button.single_add_to_cart_button:hover, .rigid-product-slide-cart .button.add_to_cart_button:hover, input.otw-submit:hover, form.mc4wp-form input[type=submit]:hover, .wc-proceed-to-checkout a.checkout-button.button:hover {background-color:<?php echo esc_attr(rigid_get_option('all_buttons_hover_color')) ?> !important;}
			/* NEW label color */
			div.prod_hold .new_prod{background-color:<?php echo esc_attr(rigid_get_option('new_label_color')) ?>;}
			/* SALE label color */
			div.prod_hold .sale, span.onsale, div.prod_hold .count_holder_small {background-color:<?php echo esc_attr(rigid_get_option('sale_label_color')) ?>;}
			.count_holder .countdown_time_tiny {color:<?php echo esc_attr(rigid_get_option('sale_label_color')) ?>;}
            /* Standard page title color (no background image) */
			#rigid_page_title h1.heading-title, #rigid_page_title h1.heading-title a, .breadcrumb,.breadcrumb a, .rigid-dark-skin #rigid_page_title h1.heading-title a, body.single-post .rigid_title_holder .blog-post-meta a {color:<?php echo esc_attr(rigid_get_option('page_title_color')) ?>;}
            .breadcrumb {color: #999999;}
            /* Standard page subtitle color (no background image) */
			.rigid_title_holder h6 {color:<?php echo esc_attr(rigid_get_option('page_subtitle_color')) ?>;}
			/* Customized page title color (with background image) */
			#rigid_page_title.rigid_title_holder.title_has_image h1.heading-title, #rigid_page_title.rigid_title_holder.title_has_image .blog-post-meta *, #rigid_page_title.rigid_title_holder.title_has_image .blog-post-meta .post-meta-date:before, #rigid_page_title.rigid_title_holder.title_has_image h1.heading-title a, body.single-post #rigid_page_title.rigid_title_holder.title_has_image .blog-post-meta a, #rigid_page_title.rigid_title_holder.title_has_image h6, #rigid_page_title.rigid_title_holder.title_has_image .breadcrumb, #rigid_page_title.rigid_title_holder.title_has_image .breadcrumb a {
				color:<?php echo esc_attr(rigid_get_option('custom_page_title_color')) ?>;
			}
			/* Standard page title background color (no background image) */
			.rigid_title_holder, .rigid_title_holder .inner:before, body.rigid_header_left .rigid_title_holder:not(.title_has_image) .inner {background-color:<?php echo esc_attr(rigid_get_option('page_title_bckgr_color')) ?>;}
			/* Standard page title border color (no background image) */
			.rigid_title_holder, body.rigid_header_left .rigid_title_holder:not(.title_has_image) .inner { border-color:<?php echo esc_attr(rigid_get_option('page_title_border_color')) ?>; }
			.rigid_title_holder .inner:before { border-color: transparent <?php echo esc_attr(rigid_get_option('page_title_border_color')) ?> <?php echo esc_attr(rigid_get_option('page_title_border_color')) ?> transparent; }
			/* Post Overlay color */
			<?php if (rigid_get_option('blog_post_hover_overlay_color')): ?>
			.rigid_blog_masonry:not(.rigid-related-blog-posts) .rigid_post_data_holder:before {background:<?php echo esc_attr(rigid_get_option('blog_post_hover_overlay_color')) ?> !Important; }
			body.blog .rigid_blog_masonry .sticky .rigid_post_data_holder, .rigid_shortcode_blog.rigid_blog_masonry .sticky .rigid_post_data_holder {background-color:<?php echo esc_attr(rigid_get_option('blog_post_hover_overlay_color')) ?>; }
			<?php else: ?>
			.rigid_blog_masonry:not(.rigid-related-blog-posts) .rigid_post_data_holder:before {background:<?php echo esc_attr(rigid_get_option('accent_color')) ?> !Important; }
			body.blog .rigid_blog_masonry .sticky .rigid_post_data_holder, .rigid_shortcode_blog.rigid_blog_masonry .sticky .rigid_post_data_holder {background-color:<?php echo esc_attr(rigid_get_option('accent_color')) ?>; }
			<?php endif; ?>
			/* Portfolio overlay text color */
			.portfolio-unit:not(.rigid-none-overlay):not(.list-unit) a.portfolio-link, .portfolio-unit:not(.rigid-none-overlay):not(.list-unit)  small, .portfolio-unit:not(.rigid-none-overlay):not(.list-unit)  a.portfolio-link h4, .portfolio-unit:not(.rigid-none-overlay):not(.list-unit) p {
				color:<?php echo esc_attr(rigid_get_option('portfolio_overlay_text_color')) ?>;
			}
			.portfolio-unit-info a.portfolio-lightbox-link span {
				border-color:<?php echo esc_attr(rigid_get_option('portfolio_overlay_text_color')) ?>;
			}
			/* Top Menu Bar Visible on Mobile */
			<?php if (!rigid_get_option('header_top_mobile_visibility')): ?>
			<?php echo '@media only screen and (max-width: 1023px) {#header_top {display: none !Important}}'; ?>
			<?php endif; ?>
			/* Header top bar background color */
			#header_top { background-color:<?php echo esc_attr(rigid_get_option('header_top_bar_color')) ?>; border-color:<?php echo esc_attr(rigid_get_option('header_top_bar_border_color')) ?> !Important;}
			body.rigid-overlay-header #header_top .inner { background-color:<?php echo esc_attr(rigid_get_option('header_top_bar_color')) ?>; border-color:<?php echo esc_attr(rigid_get_option('header_top_bar_border_color')) ?> !Important;}
			/* Header middle section background color */
			#header_bottom, #header_bottom .inner:before {background-color:<?php echo esc_attr(rigid_get_option('header_bottom_bar_color')) ?>;}
			/* Header middle section bar border color */
			#header_bottom, #header_bottom .inner:before {border-color:<?php echo esc_attr(rigid_get_option('header_top_bar_border_color')) ?> !Important;}
			<?php if (rigid_get_option('header_middle_content_color')): ?>
			/* Header middle section content and links color */
			#header_bottom #welcome, #header_bottom #welcome a, #cart-module a.cart-contents, #cart-module a.cart-contents:before, .rigid-search-cart-holder .rigid-search-trigger > a {color:<?php echo esc_attr(rigid_get_option('header_middle_content_color')) ?>;}
			<?php endif; ?>
			/* Main menu links color and typography */
			<?php
			$main_menu_typography = rigid_get_option('main_menu_typography');
			$main_menu_typography_style = json_decode($main_menu_typography['style'], true);
			$main_menu_typography_css_style = '';
			if ($main_menu_typography_style) {
				$main_menu_typography_css_style = 'font-weight:' . esc_attr($main_menu_typography_style['font-weight'] . ';font-style:' . $main_menu_typography_style['font-style'] . ';');
			}
			?>
			#main-menu ul.menu > li > a, #main-menu li div.rigid-mega-menu > ul.sub-menu > li > a, .rigid-wishlist-counter a, .rigid-search-cart-holder a.sidebar-trigger:before, #header .rigid-search-cart-holder .video_controlls a {color:<?php echo esc_attr(rigid_get_option('main_menu_links_color')) ?>;font-size:<?php echo esc_attr($main_menu_typography['size']) ?>;<?php echo esc_attr($main_menu_typography_css_style) ?>}
			/* Main menu links hover color */
			ul#mobile-menu.menu li a {font-size:<?php echo esc_attr($main_menu_typography['size']) ?>;<?php echo esc_attr($main_menu_typography_css_style) ?>}
			/* Main menu links hover color */
			#main-menu ul.menu > li:hover > a, #main-menu ul.menu > li.current_page_item > a, #main-menu ul.menu > li.rigid-highlight-menu-item > a, body.rigid_transparent_header #header #main-menu ul.menu > li:hover > a, body.rigid_transparent_header #header #main-menu ul.menu > li.current-menu-item > a, #cart-module a.cart-contents, #main-menu li div.rigid-mega-menu > ul.sub-menu > li > a:hover {color:<?php echo esc_attr(rigid_get_option('main_menu_links_hover_color')) ?>;}
			#main-menu.rigid-strikethrough-accent ul.menu > li:hover > a:before, #main-menu.rigid-strikethrough-accent ul.menu > li.current-menu-item > a:before, #main-menu.rigid-strikethrough-accent ul.menu > li.current_page_item > a:before {background-color:<?php echo esc_attr(rigid_get_option('main_menu_links_hover_color')) ?>;}
			/* Main menu background hover color */
			<?php if (rigid_get_option('main_menu_links_bckgr_hover_color')): ?>
			body:not(.rigid_transparent_header) #main-menu ul.menu > li:hover > a, body:not(.rigid_transparent_header) #main-menu ul.menu > li.current-menu-item > a, body:not(.rigid_transparent_header) #main-menu ul.menu > li:hover > a, #header2 #main-menu ul.menu > li.current-menu-item > a, #header2 #main-menu ul.menu > li:hover > a { background-color: <?php echo esc_attr(rigid_get_option('main_menu_links_bckgr_hover_color')) ?>;}
			#main-menu ul.menu > li.rigid-highlight-menu-item > a, #main-menu ul.menu > li.rigid-highlight-menu-item:after { background-color: <?php echo esc_attr(rigid_get_option('main_menu_links_bckgr_hover_color')) ?>;}
			#main-menu ul.menu > li.rigid-highlight-menu-item:after { border-color: <?php echo esc_attr(rigid_get_option('main_menu_links_bckgr_hover_color')) ?>;}
			#main-menu.rigid-pills-accent ul.menu > li > a:before { background:none !important;}
			<?php endif; ?>
			body:not(.rigid_transparent_header) #main-menu.rigid-line-accent ul.menu > li:before, #header2 #main-menu.rigid-line-accent ul.menu > li:before {background-color:<?php echo esc_attr(rigid_get_option('main_menu_links_hover_color')) ?>;}
			<?php if (!rigid_get_option('main_menu_links_bckgr_hover_color')): ?>
			#main-menu ul.menu > li.rigid-highlight-menu-item > a, #main-menu ul.menu > li.rigid-highlight-menu-item:after { background-color: <?php echo esc_attr(rigid_get_option('accent_color')) ?>;}
			#main-menu ul.menu > li.rigid-highlight-menu-item:after { border-color: <?php echo esc_attr(rigid_get_option('accent_color')) ?>;}
			#main-menu.rigid-pills-accent ul.menu > li:hover > a:before, #main-menu.rigid-pills-accent ul.menu > li.current_page_item > a:before { background-color: <?php echo esc_attr(rigid_get_option('accent_color')) ?>;}
			<?php endif; ?>
			<?php if (rigid_get_option('main_menu_transf_to_uppercase')): ?>
			#main-menu ul.menu > li > a, #rigid_footer_menu > li a {text-transform: uppercase;}
			<?php endif; ?>
			/* Main menu icons color */
			<?php if (rigid_get_option('main_menu_icons_color')): ?>
			#main-menu ul.menu li a i {color: <?php echo esc_attr(rigid_get_option('main_menu_icons_color')) ?>;}
			<?php endif; ?>
			<?php if (rigid_get_option('products_reveal_effect_color')): ?>
			div.prod_hold::before, .wpb_rigid_banner:before {background-color: <?php echo esc_attr(rigid_get_option('products_reveal_effect_color')) ?>;}
			<?php else: ?>
			div.prod_hold::before, .wpb_rigid_banner:before {background-color: <?php echo esc_attr(rigid_get_option('accent_color')) ?>;}
			<?php endif; ?>
			/* Header top bar menu links color */
			ul#topnav2 > li a, .rigid-top-bar-message, .rigid-top-bar-message a, #header_top .rigid-social ul li a {color:<?php echo esc_attr(rigid_get_option('top_bar_menu_links_color')) ?>}
			/* Header top bar menu links hover color */
			ul#topnav2 li a:hover, body.rigid_transparent_header ul#topnav2 > li > a:hover {color:<?php echo esc_attr(rigid_get_option('top_bar_menu_links_hover_color')) ?> !important;}
			/* Header top bar menu links hover background color */
			ul#topnav2 li a:hover, ul#topnav2 ul.sub-menu li a:hover, ul#topnav2 li:hover ul.sub-menu a:hover {background-color:<?php echo esc_attr(rigid_get_option('top_bar_menu_links_bckgr_color')) ?>;}
			/* Collapsible Pre-Header background color */
			#pre_header, #pre_header:before {background-color:<?php echo esc_attr(rigid_get_option('collapsible_bckgr_color')) ?>;}
			/* Collapsible Pre-Header titles color */
			#pre_header .widget > h3:first-child {color:<?php echo esc_attr(rigid_get_option('collapsible_titles_color')) ?>;}
			/* Collapsible Pre-Header titles border color */
			#pre_header .widget > h3:first-child, #pre_header > .inner ul.product_list_widget li, #pre_header > .inner div.widget_nav_menu ul li a, #pre_header > .inner ul.products-list li {border-color:<?php echo esc_attr(rigid_get_option('collapsible_titles_border_color')) ?>;}
			#pre_header > .inner div.widget_categories ul li, #pre_header > .inner div.widget_archive ul li, #pre_header > .inner div.widget_recent_comments ul li, #pre_header > .inner div.widget_pages ul li,
			#pre_header > .inner div.widget_links ul li, #pre_header > .inner div.widget_recent_entries ul li, #pre_header > .inner div.widget_meta ul li, #pre_header > .inner div.widget_display_forums ul li,
			#pre_header > .inner .widget_display_replies ul li, #pre_header > .inner .widget_display_views ul li {border-color: <?php echo esc_attr(rigid_get_option('collapsible_titles_border_color')) ?>;}
			/* Collapsible Pre-Header links color */
			#pre_header a {color:<?php echo esc_attr(rigid_get_option('collapsible_links_color')) ?>;}
			/* Transparent Header menu color */
			@media only screen and (min-width: 1024px) {
				body.rigid_transparent_header #header #logo .rigid-logo-title, body.rigid_transparent_header .rigid-top-bar-message, body.rigid_transparent_header .rigid-top-bar-message a, body.rigid_transparent_header #header_top .rigid-social ul li a, body.rigid_transparent_header ul#topnav2 > li > a, body.rigid_transparent_header #rigid-account-holder a, body.rigid_transparent_header #header #rigid-account-holder a i, body.rigid_transparent_header #header .rigid-search-cart-holder .video_controlls a, body.rigid_transparent_header #header #logo .rigid-logo-subtitle, body.rigid_transparent_header #header #main-menu ul.menu > li > a, body.rigid_transparent_header #header .rigid-search-cart-holder .rigid-search-trigger > a, body.rigid_transparent_header .rigid-search-cart-holder a.sidebar-trigger:before, body.rigid_transparent_header #header #cart-module a.cart-contents, body.rigid_transparent_header #header #cart-module a.cart-contents:before, body.rigid_transparent_header #header .rigid-wishlist-counter a, body.rigid_transparent_header #header .rigid-wishlist-counter a i {
					color:<?php echo esc_attr(rigid_get_option('transparent_header_menu_color')) ?> !Important;
				}
				body.rigid_transparent_header.rigid-transparent-dark #header #logo .rigid-logo-title, body.rigid_transparent_header.rigid-transparent-dark .rigid-top-bar-message, body.rigid_transparent_header.rigid-transparent-dark .rigid-top-bar-message a, body.rigid_transparent_header.rigid-transparent-dark #header_top .rigid-social ul li a, body.rigid_transparent_header.rigid-transparent-dark ul#topnav2 > li > a, body.rigid_transparent_header.rigid-transparent-dark #rigid-account-holder a, body.rigid_transparent_header.rigid-transparent-dark #header #rigid-account-holder a i, body.rigid_transparent_header.rigid-transparent-dark #header .rigid-search-cart-holder .video_controlls a, body.rigid_transparent_header.rigid-transparent-dark #header #logo .rigid-logo-subtitle, body.rigid_transparent_header.rigid-transparent-dark #header #main-menu ul.menu > li > a, body.rigid_transparent_header.rigid-transparent-dark #header .rigid-search-cart-holder .rigid-search-trigger > a, body.rigid_transparent_header.rigid-transparent-dark .rigid-search-cart-holder a.sidebar-trigger:before, body.rigid_transparent_header.rigid-transparent-dark #header #cart-module a.cart-contents, body.rigid_transparent_header.rigid-transparent-dark #header #cart-module a.cart-contents:before, body.rigid_transparent_header.rigid-transparent-dark #header .rigid-wishlist-counter a, body.rigid_transparent_header.rigid-transparent-dark #header .rigid-wishlist-counter a i {
					color:<?php echo esc_attr(rigid_get_option('transparent_header_dark_menu_color')) ?> !Important;
				}
                body.rigid_transparent_header #header #main-menu.rigid-strikethrough-accent ul.menu > li > a:before {background-color:<?php echo esc_attr(rigid_get_option('transparent_header_menu_color')) ?> !Important;}
				body.rigid_transparent_header.rigid-transparent-dark #header #main-menu.rigid-strikethrough-accent ul.menu > li > a:before {background-color:<?php echo esc_attr(rigid_get_option('transparent_header_dark_menu_color')) ?> !Important;}
				/* Transparent menu hover color */
			<?php if (rigid_get_option('transparent_header_menu_hover_color')): ?>
				body.rigid_transparent_header #header #main-menu ul.menu > li > a:hover, body.rigid_transparent_header #header #main-menu ul.menu > li.current-menu-item > a { color: <?php echo esc_attr(rigid_get_option('transparent_header_menu_hover_color')) ?> !Important;}
                body.rigid_transparent_header #main-menu ul.menu > li > a:before { background-color: <?php echo esc_attr(rigid_get_option('transparent_header_menu_hover_color')) ?> !Important;}
            <?php endif; ?>
			<?php if (rigid_get_option('transparent_header_dark_menu_hover_color')): ?>
				body.rigid_transparent_header.rigid-transparent-dark #header #main-menu ul.menu > li > a:hover, body.rigid_transparent_header.rigid-transparent-dark #header #main-menu ul.menu > li.current-menu-item > a { color: <?php echo esc_attr(rigid_get_option('transparent_header_dark_menu_hover_color')) ?> !Important;}
				body.rigid_transparent_header.rigid-transparent-dark #main-menu ul.menu > li > a:before { background-color: <?php echo esc_attr(rigid_get_option('transparent_header_dark_menu_hover_color')) ?> !Important;}
			<?php endif; ?>
			}
			/* Header background */
			<?php $header_backgr = rigid_get_option('header_background'); ?>
			<?php if ($header_backgr['image']): ?>
			#header, #header2 {background: url("<?php echo esc_url(wp_get_attachment_image_url($header_backgr['image'], 'full')) ?>") <?php echo esc_attr($header_backgr['position']) ?> <?php echo esc_attr($header_backgr['repeat']) ?> <?php echo esc_attr($header_backgr['attachment']) ?>;}
			body.rigid-overlay-header #header .main_menu_holder {background: url("<?php echo esc_url(wp_get_attachment_image_url($header_backgr['image'], 'full')) ?>") <?php echo esc_attr($header_backgr['position']) ?> <?php echo esc_attr($header_backgr['repeat']) ?> <?php echo esc_attr($header_backgr['attachment']) ?>;}
			<?php endif; ?>

			#header, #header2 {background-color: <?php echo esc_attr($header_backgr['color']) ?>;}
			<?php if ($header_backgr['color'] != "#ffffff"): ?>
			.rigid-search-cart-holder .rigid-search-trigger > a, .rigid-search-cart-holder a.sidebar-trigger:before, .rigid-search-cart-holder #cart-module a.cart-contents, .rigid-search-cart-holder #cart-module a.cart-contents:before, .rigid-search-cart-holder .rigid-wishlist-counter a, .rigid-search-cart-holder .rigid-wishlist-counter a i, #rigid-account-holder i {
				color:<?php echo esc_attr(rigid_get_option('main_menu_links_color')) ?>;}
			#header, #header2, #header_top {border:none;}
			#main-menu ul.menu > li > a:before {background-color:<?php echo esc_attr(rigid_get_option('main_menu_links_hover_color')) ?>;}
			body.rigid_header_left #header, body.rigid_header_left.rigid_transparent_header #header {
				border-right: none;
			}
			<?php endif; ?>
			body.rigid-overlay-header #header .main_menu_holder {background-color: <?php echo esc_attr($header_backgr['color']) ?>;}
			/* footer_background */
			<?php $footer_backgr = rigid_get_option('footer_background'); ?>
			<?php if ($footer_backgr['image']): ?>
			#footer {background: url("<?php echo esc_url(wp_get_attachment_image_url($footer_backgr['image'], 'full')) ?>") <?php echo esc_attr($footer_backgr['position']) ?> <?php echo esc_attr($footer_backgr['repeat']) ?> <?php echo esc_attr($footer_backgr['attachment']) ?>;}
			<?php endif; ?>
			#footer {background-color: <?php echo esc_attr($footer_backgr['color']) ?>;}

			@media only screen and (min-width: 1024px) {
				body.rigid_header_left.rigid-overlay-header #footer, body.rigid_header_left.rigid-overlay-header #powered {background: none;}
				body.rigid_header_left.rigid-overlay-header #footer .inner {background-color: <?php echo esc_attr($footer_backgr['color']) ?>;}
				body.rigid_header_left.rigid-overlay-header #powered .inner {background-color: <?php echo esc_attr(rigid_get_option('footer_copyright_bar_bckgr_color')) ?>;}
			}


			/* footer_titles_color + footer_title_border_color */
			#footer .widget > h3:first-child {color:<?php echo esc_attr(rigid_get_option('footer_titles_color')) ?>; border-color: <?php echo esc_attr(rigid_get_option('footer_title_border_color')) ?>;}
			#footer {border-top: 1px solid  <?php echo esc_attr(rigid_get_option('footer_title_border_color')) ?>;}
			#footer > .inner ul.product_list_widget li, #footer > .inner div.widget_nav_menu ul li a, #footer > .inner ul.products-list li, #rigid_footer_menu > li {border-color: <?php echo esc_attr(rigid_get_option('footer_title_border_color')) ?>;}
			/* footer_menu_links_color */
			#rigid_footer_menu > li a, #powered a, #powered .rigid-social ul li a {color: <?php echo esc_attr(rigid_get_option('footer_menu_links_color')) ?>;}
			/* footer_links_color */
			#footer > .inner a {color: <?php echo esc_attr(rigid_get_option('footer_links_color')) ?>;}
			/* footer_text_color */
			#footer {color: <?php echo esc_attr(rigid_get_option('footer_text_color')) ?>;}
			#footer > .inner div.widget_categories ul li, #footer > .inner div.widget_archive ul li, #footer > .inner div.widget_recent_comments ul li, #footer > .inner div.widget_pages ul li,
			#footer > .inner div.widget_links ul li, #footer > .inner div.widget_recent_entries ul li, #footer > .inner div.widget_meta ul li, #footer > .inner div.widget_display_forums ul li,
			#footer > .inner .widget_display_replies ul li, #footer > .inner .widget_display_views ul li, #footer > .inner div.widget_nav_menu ul li {border-color: <?php echo esc_attr(rigid_get_option('footer_title_border_color')) ?>;}
			/* footer_copyright_bar_bckgr_color */
			#powered{background-color: <?php echo esc_attr(rigid_get_option('footer_copyright_bar_bckgr_color')) ?>; color: <?php echo esc_attr(rigid_get_option('footer_copyright_bar_text_color')) ?>;}
			/* Body font */
			<?php $body_font = rigid_get_option('body_font'); ?>
			body, #bbpress-forums .bbp-body div.bbp-reply-content {
				font-family:<?php echo esc_attr($body_font['face']) ?>;
				font-size:<?php echo esc_attr($body_font['size']) ?>;
				color:<?php echo esc_attr($body_font['color']) ?>;
			}
			#header #logo .rigid-logo-subtitle, #header2 #logo .rigid-logo-subtitle {
				color: <?php echo esc_attr($body_font['color']) ?>;
			}
			/* Text logo color and typography */
			<?php
			$text_logo_typography = rigid_get_option('text_logo_typography');
			$text_logo_typography_style = json_decode($text_logo_typography['style'], true);
			$text_logo_typography_color = $text_logo_typography['color'];
			$text_logo_typography_css_style = '';
			if ($text_logo_typography_style) {
				$text_logo_typography_css_style = 'font-weight:' . esc_attr($text_logo_typography_style['font-weight'] . ';font-style:' . $text_logo_typography_style['font-style'] . ';');
			}
			?>
			#header #logo .rigid-logo-title, #header2 #logo .rigid-logo-title {color: <?php echo esc_attr($text_logo_typography_color) ?>;font-size:<?php echo esc_attr($text_logo_typography['size']) ?>;<?php echo esc_attr($text_logo_typography_css_style) ?>}
			/* Heading fonts */
			<?php $headings_font = rigid_get_option('headings_font'); ?>
			h1, h2, h3, h4, h5, h6, p.woocommerce-thankyou-order-received, .vendor_description .vendor_img_add .vendor_address p.wcmp_vendor_name, .tribe-events-event-cost, .tribe-events-schedule .tribe-events-cost, .rigid-page-load-status, .widget_layered_nav_filters li a, section.woocommerce-order-details, ul.woocommerce-error, table.woocommerce-checkout-review-order-table, body.woocommerce-cart .cart-collaterals, .cart-info table.shop_table.cart, ul.woocommerce-order-overview.woocommerce-thankyou-order-details.order_details li, .countdown_time_tiny, blockquote, q, #rigid_footer_menu > li a, .rigid-pagination-numbers .owl-dot:before, .rigid-wcs-swatches .swatch.swatch-label, .portfolio-unit-info small, .widget .post-date, div.widget_nav_menu ul li a, .comment-body span, .comment-reply-link, span.edit-link a, #reviews .commentlist li .meta, div.widget_categories ul li a, div.widget_archive ul li a, div.widget_recent_entries ul li a, div.widget_recent_comments ul li a, .woocommerce p.cart-empty, div.woocommerce-MyAccount-content .myaccount_user, label, .rigid-pricing-table-content, p.product.woocommerce.add_to_cart_inline, .product-filter .limit b, .product-filter .sort b, .product-filter .price_label, .contact-form .content span, .tribe-countdown-text, .rigid-event-countdown .is-countdown, .rigid-portfolio-categories ul li a, div.prod_hold .name, .prod_hold .price_hold, #header #logo .rigid-logo-title, #header2 #logo .rigid-logo-title, .rigid-counter-h1, .rigid-typed-h1, .rigid-typed-h2, .rigid-typed-h3, .rigid-typed-h4, .rigid-typed-h5, .rigid-typed-h6, .rigid-counter-h2, body.woocommerce-account #customer_login.col2-set .owl-nav, .woocommerce #customer_login.u-columns.col2-set .owl-nav, .rigid-counter-h3, .error404 div.blog-post-excerpt:before, #yith-wcwl-popup-message #yith-wcwl-message, div.added-product-text strong, .vc_pie_chart .vc_pie_chart_value, .countdown-amount, .rigid-product-slide-price, .rigid-counter-h4, .rigid-counter-h5, .rigid-search-cart-holder #search input[type="text"], .rigid-counter-h6, .vc_tta-tabs:not(.vc_tta-style-modern) .vc_tta-tab, div.product .price span, a.bbp-forum-title, p.logged-in-as, .rigid-pricing-table-price, li.bbp-forum-info, li.bbp-topic-title .bbp-topic-permalink, .breadcrumb, .offer_title, ul.tabs a, .wpb_tabs .wpb_tabs_nav li a, .wpb_tour .wpb_tabs_nav a, .wpb_accordion .wpb_accordion_wrapper .wpb_accordion_header a, .post-date .num, .rigid-products-list-view div.prod_hold .name, .rigid_shortcode_count_holder .countdown-amount, .blog-post-meta a, .widget_shopping_cart_content p.total, #cart-module a.cart-contents, .rigid-wishlist-counter .rigid-wish-number, .portfolio_top .project-data .project-details .simple-list-underlined li, .portfolio_top .project-data .main-features .checklist li, .summary.entry-summary .yith-wcwl-add-to-wishlist a {
				font-family:<?php echo esc_attr($headings_font['face']) ?>;
			}
            .u-column1 h2, .u-column2 h3, .rigid_title_holder h1.heading-title {
                font-family:<?php echo esc_attr($headings_font['face']) ?> !important;
            }
			<?php $use_google_face_for = rigid_get_option('use_google_face_for'); ?>

			<?php if ($use_google_face_for['main_menu']): ?>
			#main-menu ul.menu li a, ul#mobile-menu.menu li a, #main-menu li div.rigid-mega-menu > ul.sub-menu > li.rigid_colum_title > a {
				font-family:<?php echo esc_attr($headings_font['face']) ?>;
			}
			<?php endif; ?>

			<?php if ($use_google_face_for['buttons']): ?>
			a.button, input.button, .wcv-navigation ul.menu.horizontal li a, .wcv-pro-dashboard input[type="submit"], button.button, input[type="submit"], a.button-inline, .rigid_banner_buton, #submit_btn, #submit, .wpcf7-submit, .col2-set.addresses header a.edit, div.product input.qty, .rigid-pricing-table-button a, .vc_btn3, nav.woocommerce-MyAccount-navigation ul li a {
				font-family:<?php echo esc_attr($headings_font['face']) ?>;
			}
			<?php endif; ?>
			/* H1 */
			<?php
			$h1_font = rigid_get_option('h1_font');
			$h1_style = json_decode($h1_font['style'], true);
			$h1_css_style = '';
			if ($h1_style) {
				$h1_css_style = 'font-weight:' . esc_attr($h1_style['font-weight'] . ';font-style:' . $h1_style['font-style'] . ';');
			}
			?>
			h1, .rigid-counter-h1, .rigid-typed-h1, .rigid-dropcap p:first-letter, .rigid-dropcap h1:first-letter, .rigid-dropcap h2:first-letter, .rigid-dropcap h3:first-letter, .rigid-dropcap h4:first-letter, .rigid-dropcap h5:first-letter, .rigid-dropcap h6:first-letter{color:<?php echo esc_attr($h1_font['color']) ?>;font-size:<?php echo esc_attr($h1_font['size']) ?>;<?php echo esc_attr($h1_css_style) ?>}
			/* H2 */
			<?php
			$h2_font = rigid_get_option('h2_font');
			$h2_style = json_decode($h2_font['style'], true);
			$h2_css_style = '';
			if ($h2_style) {
				$h2_css_style = 'font-weight:' . esc_attr($h2_style['font-weight'] . ';font-style:' . $h2_style['font-style'] . ';');
			}
			?>
			h2, .rigid-counter-h2, .rigid-typed-h2, .icon_teaser h3:first-child, body.woocommerce-account #customer_login.col2-set .owl-nav, .woocommerce #customer_login.u-columns.col2-set .owl-nav {color:<?php echo esc_attr($h2_font['color']) ?>;font-size:<?php echo esc_attr($h2_font['size']) ?>;<?php echo esc_attr($h2_css_style) ?>}
			/* H3 */
			<?php
			$h3_font = rigid_get_option('h3_font');
			$h3_style = json_decode($h3_font['style'], true);
			$h3_css_style = '';
			if ($h3_style) {
				$h3_css_style = 'font-weight:' . esc_attr($h3_style['font-weight'] . ';font-style:' . $h3_style['font-style'] . ';');
			}
			?>
			h3, .rigid-counter-h3, .rigid-typed-h3, .woocommerce p.cart-empty {color:<?php echo esc_attr($h3_font['color']) ?>;font-size:<?php echo esc_attr($h3_font['size']) ?>;<?php echo esc_attr($h3_css_style) ?>}
			/* H4 */
			<?php
			$h4_font = rigid_get_option('h4_font');
			$h4_style = json_decode($h4_font['style'], true);
			$h4_css_style = '';
			if ($h4_style) {
				$h4_css_style = 'font-weight:' . esc_attr($h4_style['font-weight'] . ';font-style:' . $h4_style['font-style'] . ';');
			}
			?>
			h4, .rigid-counter-h4, .rigid-typed-h4{color:<?php echo esc_attr($h4_font['color']) ?>;font-size:<?php echo esc_attr($h4_font['size']) ?>;<?php echo esc_attr($h4_css_style) ?>}
			/* H5 */
			<?php
			$h5_font = rigid_get_option('h5_font');
			$h5_style = json_decode($h5_font['style'], true);
			$h5_css_style = '';
			if ($h5_style) {
				$h5_css_style = 'font-weight:' . esc_attr($h5_style['font-weight'] . ';font-style:' . $h5_style['font-style'] . ';');
			}
			?>
			h5, .rigid-counter-h5, .rigid-typed-h5{color:<?php echo esc_attr($h5_font['color']) ?>;font-size:<?php echo esc_attr($h5_font['size']) ?>;<?php echo esc_attr($h5_css_style) ?>}
			/* H6 */
			<?php
			$h6_font = rigid_get_option('h6_font');
			$h6_style = json_decode($h6_font['style'], true);
			$h6_css_style = '';
			if ($h6_style) {
				$h6_css_style = 'font-weight:' . esc_attr($h6_style['font-weight'] . ';font-style:' . $h6_style['font-style'] . ';');
			}
			?>
			h6, .rigid-counter-h6, .rigid-typed-h6{color:<?php echo esc_attr($h6_font['color']) ?>;font-size:<?php echo esc_attr($h6_font['size']) ?>;<?php echo esc_attr($h6_css_style) ?>}
			<?php if (rigid_get_option('custom_css')): ?>
			<?php echo esc_attr(rigid_get_option('custom_css')); ?>
			<?php endif; ?>
			/* Add to Cart Color */
			button.single_add_to_cart_button, .wc-proceed-to-checkout a.checkout-button.button, .rigid-product-slide-cart .button.add_to_cart_button {background-color:<?php echo esc_attr(rigid_get_option('add_to_cart_color')); ?> !important;}
			div.prod_hold a.button.add_to_cart_button:hover, p.product.woocommerce.add_to_cart_inline + .links a.button.add_to_cart_button.ajax_add_to_cart:hover {color:<?php echo esc_attr(rigid_get_option('add_to_cart_color')); ?> !important;}
			/* Main menu background color */
			<?php if (rigid_get_option('main_menu_bckgr_color')): ?>
			body.rigid_logo_center_menu_below #main-menu {background-color:<?php echo esc_attr(rigid_get_option('main_menu_bckgr_color')) ?>; padding-left:12px; padding-right:12px;}
			<?php endif; ?>
			table.compare-list .add-to-cart td a.rigid-quick-view-link, table.compare-list .add-to-cart td a.compare.button {
				display:none !important;
			}</style>
		<?php
		$custom_css = ob_get_clean();
		$custom_css = trim(preg_replace('#<style[^>]*>(.*)</style>#is', '$1', $custom_css));

		wp_add_inline_style('rigid-style', $custom_css); // All dynamic data escaped
	}

}