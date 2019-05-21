<?php

/*
  Plugin Name: Rigid Plugin
  Plugin URI: http://www.althemist.com/
  Description: Plugin containing the Rigid theme functionality
  Version: 1.1.0
  Author: theAlThemist
  Author URI: http://www.althemist.com/
  Author Email: visibleone@gmail.com
  WC requires at least: 3.0.0
  WC tested up to: 3.2.0
  License: Themeforest Split Licence
  License URI: -
 */
defined('ABSPATH') || die();

// Check if WooCommerce is active
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	define('RIGID_PLUGIN_IS_WOOCOMMERCE', TRUE);
} else {
	define('RIGID_PLUGIN_IS_WOOCOMMERCE', FALSE);
}

add_action('plugins_loaded', 'rigid_plugin_after_plugins_loaded' );
add_action( 'plugins_loaded', 'rigid_wc_variation_swatches_constructor' );

function rigid_plugin_after_plugins_loaded() {
	load_plugin_textdomain('rigid-plugin', FALSE, dirname(plugin_basename(__FILE__)) . '/languages/');

	/* independent widgets */
	foreach (array('RigidAboutWidget', 'RigidContactsWidget', 'RigidFacebookWidget', 'RigidPaymentOptionsWidget', 'RigidPopularPostsWidget', 'RigidLatestProjectsWidget') as $file) {
		require_once( plugin_dir_path(__FILE__) . 'widgets/' . $file . '.php' );
	}

	if(RIGID_PLUGIN_IS_WOOCOMMERCE) {
		/* WooCommerce dependent widgets */
		foreach ( array( 'RigidProductFilterWidget' ) as $file ) {
			require_once( plugin_dir_path( __FILE__ ) . 'widgets/wc_widgets/' . $file . '.php' );
		}
	}

	/* shortcodes */
	require_once( plugin_dir_path(__FILE__) . 'shortcodes/shortcodes.php' );

	/* Load variation product swatches */
	require_once( plugin_dir_path(__FILE__) . 'incl/swatches/variation-swatches.php' );
}

// Fix bbpress  Notice: bp_setup_current_user was called incorrectly
if (class_exists( 'bbPress' )) {
	remove_action('set_current_user', 'bbp_setup_current_user', 10);
	add_action('set_current_user', 'rigid_bbp_setup_current_user', 10);
}

if (!function_exists('rigid_bbp_setup_current_user')) {

	function rigid_bbp_setup_current_user() {
		do_action('bbp_setup_current_user');
	}

}
// include WP admin part functions so we can use 'is_plugin_active function'
if (!function_exists('is_plugin_active')) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if (!defined('RIGID_PLUGIN_IMAGES_PATH')) {
	define('RIGID_PLUGIN_IMAGES_PATH', plugins_url('/assets/image/', plugin_basename(__FILE__)));
}

/**
 * Generate excerpt by post Id
 *
 * @param type $post_id
 * @param type $excerpt_length
 * @param type $dots_to_link
 * @return string
 */
if (!function_exists('rigid_get_excerpt_by_id')) {

	function rigid_get_excerpt_by_id($post_id, $excerpt_length = 35, $dots_to_link = false) {

		$the_post = get_post($post_id); //Gets post
		$the_excerpt = strip_tags($the_post->post_excerpt);
		$the_excerpt = '<p>' . $the_excerpt . '</p>';

		return $the_excerpt;
	}

}

/**
 * Define Portfolio custom post type
 * 'rigid-portfolio'
 */
if (!function_exists('rigid_register_cpt_rigid_portfolio')) {
	add_action('init', 'rigid_register_cpt_rigid_portfolio', 5);

	function rigid_register_cpt_rigid_portfolio() {

		$labels = array(
				'name' => esc_html__('Portfolios', 'rigid-plugin'),
				'singular_name' => esc_html__('Portfolio', 'rigid-plugin'),
				'add_new' => esc_html__('Add New', 'rigid-plugin'),
				'add_new_item' => esc_html__('Add New Portfolio', 'rigid-plugin'),
				'edit_item' => esc_html__('Edit Portfolio', 'rigid-plugin'),
				'new_item' => esc_html__('New Portfolio', 'rigid-plugin'),
				'view_item' => esc_html__('View Portfolio', 'rigid-plugin'),
				'search_items' => esc_html__('Search Portfolios', 'rigid-plugin'),
				'not_found' => esc_html__('No portfolios found', 'rigid-plugin'),
				'not_found_in_trash' => esc_html__('No portfolios found in Trash', 'rigid-plugin'),
				'parent_item_colon' => esc_html__('Parent Portfolio:', 'rigid-plugin'),
				'menu_name' => esc_html__('Portfolios', 'rigid-plugin'),
		);

		$args = array(
				'labels' => $labels,
				'hierarchical' => false,
				'description' => 'Rigid portfolio post type',
				'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'trackbacks', 'revisions', 'page-attributes'),
				'taxonomies' => array('rigid_portfolio_category'),
				'public' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'show_in_nav_menus' => true,
				'publicly_queryable' => true,
				'exclude_from_search' => false,
				'has_archive' => true,
				'query_var' => true,
				'can_export' => true,
				'rewrite' => true,
				'capability_type' => 'page',
				'menu_icon' => 'dashicons-portfolio',
				'rewrite' => array(
						'slug' => esc_html__('portfolios', 'rigid-plugin')
				)
		);

		register_post_type('rigid-portfolio', $args);
	}

}

/**
 * Define rigid_portfolio_category taxonomy
 * used by rigid-portfolio post type
 */
if (!function_exists('rigid_register_taxonomy_rigid_portfolio_category')) {
	add_action('init', 'rigid_register_taxonomy_rigid_portfolio_category', 5);

	function rigid_register_taxonomy_rigid_portfolio_category() {

		$labels = array(
				'name' => esc_html__('Portfolio Category', 'rigid-plugin'),
				'singular_name' => esc_html__('Portfolio categories', 'rigid-plugin'),
				'search_items' => esc_html__('Search Portfolio Category', 'rigid-plugin'),
				'popular_items' => esc_html__('Popular Portfolio Category', 'rigid-plugin'),
				'all_items' => esc_html__('All Portfolio Category', 'rigid-plugin'),
				'parent_item' => esc_html__('Parent Portfolio categories', 'rigid-plugin'),
				'parent_item_colon' => esc_html__('Parent Portfolio categories:', 'rigid-plugin'),
				'edit_item' => esc_html__('Edit Portfolio categories', 'rigid-plugin'),
				'update_item' => esc_html__('Update Portfolio categories', 'rigid-plugin'),
				'add_new_item' => esc_html__('Add New Portfolio categories', 'rigid-plugin'),
				'new_item_name' => esc_html__('New Portfolio categories', 'rigid-plugin'),
				'separate_items_with_commas' => esc_html__('Separate portfolio category with commas', 'rigid-plugin'),
				'add_or_remove_items' => esc_html__('Add or remove portfolio category', 'rigid-plugin'),
				'choose_from_most_used' => esc_html__('Choose from the most used portfolio category', 'rigid-plugin'),
				'menu_name' => esc_html__('Portfolio Category', 'rigid-plugin'),
		);

		$args = array(
				'labels' => $labels,
				'public' => true,
				'show_in_nav_menus' => true,
				'show_ui' => true,
				'show_tagcloud' => true,
				'show_admin_column' => false,
				'hierarchical' => true,
				'rewrite' => true,
				'query_var' => true,
				'rewrite' => array(
						'slug' => 'portfolios-category'
				)
		);

		register_taxonomy('rigid_portfolio_category', array('rigid-portfolio'), $args);
	}

}

// Register scripts
add_action('wp_enqueue_scripts', 'rigid_register_plugin_scripts');
if (!function_exists('rigid_register_plugin_scripts')) {

	function rigid_register_plugin_scripts() {

		// flexslider
		wp_enqueue_script('flexslider', get_template_directory_uri() . "/js/flex/jquery.flexslider-min.js", array('jquery'), '2.2.2', true);
		wp_enqueue_style('flexslider', get_template_directory_uri() . "/js/flex/flexslider.css", array(), '2.2.2');

		// owl-carousel
		wp_enqueue_script('owl-carousel', get_template_directory_uri() . "/js/owl-carousel2-dist/owl.carousel.min.js", array('jquery'), '2.0.0', true);
		wp_enqueue_style('owl-carousel', get_template_directory_uri() . "/js/owl-carousel2-dist/assets/owl.carousel.min.css", array(), '2.0.0');
		wp_enqueue_style('owl-carousel-theme-default', get_template_directory_uri() . "/js/owl-carousel2-dist/assets/owl.theme.default.min.css", array(), '2.0.0');
		wp_enqueue_style('owl-carousel-animate', get_template_directory_uri() . "/js/owl-carousel2-dist/assets/animate.css", array(), '2.0.0');

		// cloud-zoom
		wp_enqueue_script('cloud-zoom', get_template_directory_uri() . "/js/cloud-zoom.1.0.2.min.js", array('jquery'), '1.0.2', true);
		wp_enqueue_style('cloud-zoom', get_template_directory_uri() . "/js/cloud-zoom.css", array(), '1.0.2');

		// countdown
		wp_enqueue_script('countdown', get_template_directory_uri() . "/js/count/jquery.countdown.min.js", array('jquery'), '2.0.0', true);

		// magnific
		wp_enqueue_script('magnific', get_template_directory_uri() . "/js/magnific/jquery.magnific-popup.min.js", array('jquery'), '1.0.0', true);
		wp_enqueue_style('magnific', get_template_directory_uri() . "/js/magnific/magnific-popup.css", array(), '1.0.2');

		// appear
		wp_enqueue_script('appear', get_template_directory_uri() . "/js/jquery.appear.min.js", array('jquery'), '1.0.0', true);

		// appear
		wp_enqueue_script('typed', get_template_directory_uri() . "/js/typed.min.js", array('jquery'), '1.0.0', true);

		// nice-select
		wp_enqueue_script('nice-select', get_template_directory_uri() . "/js/jquery.nice-select.min.js", array('jquery'), '1.0.0', true);

		// is-in-viewport
		wp_enqueue_script('is-in-viewport', get_template_directory_uri() . "/js/isInViewport.min.js", array('jquery'), '1.0.0', true);

		// Isotope
		wp_register_script('isotope', get_template_directory_uri() . "/js/isotope/dist/isotope.pkgd.min.js", array('jquery', 'imagesloaded'), false, true);
		// google maps
        if(function_exists('rigid_get_option')) {
	        wp_register_script( 'rigid-google-map', 'https://maps.googleapis.com/maps/api/js?' . ( rigid_get_option( 'google_maps_api_key' ) ? 'key=' . rigid_get_option( 'google_maps_api_key' ) . '&' : '' ) . 'sensor=false', array( 'jquery' ), false, true );
        }
		// facebook
		wp_register_script('rigid-facebook-script', plugins_url('assets/js/rigid-facebook.js', __FILE__), array('jquery'), false, true);
	}

}

// Enqueue the script for proper positioning the custom added font in vc edit form
add_filter('vc_edit_form_enqueue_script', 'rigid_enqueue_edit_form_scripts');
if (!function_exists('rigid_enqueue_edit_form_scripts')) {

	function rigid_enqueue_edit_form_scripts($scripts) {
		$scripts[] = plugin_dir_url(__FILE__) . 'assets/js/rigid-vc-edit-form.js';
		return $scripts;
	}

}

add_filter('vc_iconpicker-type-etline', 'rigid_vc_iconpicker_type_etline');

/**
 * Elegant Icons Font icons
 *
 * @param $icons - taken from filter - vc_map param field settings['source'] provided icons (default empty array).
 * If array categorized it will auto-enable category dropdown
 *
 * @since 4.4
 * @return array - of icons for iconpicker, can be categorized, or not.
 */
if (!function_exists('rigid_vc_iconpicker_type_etline')) {

	function rigid_vc_iconpicker_type_etline($icons) {
		// Categorized icons ( you can also output simple array ( key=> value ), where key = icon class, value = icon readable name ).
		$etline_icons = array(
				array('icon-mobile' => 'Mobile'),
				array('icon-laptop' => 'Laptop'),
				array('icon-desktop' => 'Desktop'),
				array('icon-tablet' => 'Tablet'),
				array('icon-phone' => 'Phone'),
				array('icon-document' => 'Document'),
				array('icon-documents' => 'Documents'),
				array('icon-search' => 'Search'),
				array('icon-clipboard' => 'Clipboard'),
				array('icon-newspaper' => 'Newspaper'),
				array('icon-notebook' => 'Notebook'),
				array('icon-book-open' => 'Open'),
				array('icon-browser' => 'Browser'),
				array('icon-calendar' => 'Calendar'),
				array('icon-presentation' => 'Presentation'),
				array('icon-picture' => 'Picture'),
				array('icon-pictures' => 'Pictures'),
				array('icon-video' => 'Video'),
				array('icon-camera' => 'Camera'),
				array('icon-printer' => 'Printer'),
				array('icon-toolbox' => 'Toolbox'),
				array('icon-briefcase' => 'Briefcase'),
				array('icon-wallet' => 'Wallet'),
				array('icon-gift' => 'Gift'),
				array('icon-bargraph' => 'Bargraph'),
				array('icon-grid' => 'Grid'),
				array('icon-expand' => 'Expand'),
				array('icon-focus' => 'Focus'),
				array('icon-edit' => 'Edit'),
				array('icon-adjustments' => 'Adjustments'),
				array('icon-ribbon' => 'Ribbon'),
				array('icon-hourglass' => 'Hourglass'),
				array('icon-lock' => 'Lock'),
				array('icon-megaphone' => 'Megaphone'),
				array('icon-shield' => 'Shield'),
				array('icon-trophy' => 'Trophy'),
				array('icon-flag' => 'Flag'),
				array('icon-map' => 'Map'),
				array('icon-puzzle' => 'Puzzle'),
				array('icon-basket' => 'Basket'),
				array('icon-envelope' => 'Envelope'),
				array('icon-streetsign' => 'Streetsign'),
				array('icon-telescope' => 'Telescope'),
				array('icon-gears' => 'Gears'),
				array('icon-key' => 'Key'),
				array('icon-paperclip' => 'Paperclip'),
				array('icon-attachment' => 'Attachment'),
				array('icon-pricetags' => 'Pricetags'),
				array('icon-lightbulb' => 'Lightbulb'),
				array('icon-layers' => 'Layers'),
				array('icon-pencil' => 'Pencil'),
				array('icon-tools' => 'Tools'),
				array('icon-tools-2' => '2'),
				array('icon-scissors' => 'Scissors'),
				array('icon-paintbrush' => 'Paintbrush'),
				array('icon-magnifying-glass' => 'Glass'),
				array('icon-circle-compass' => 'Compass'),
				array('icon-linegraph' => 'Linegraph'),
				array('icon-mic' => 'Mic'),
				array('icon-strategy' => 'Strategy'),
				array('icon-beaker' => 'Beaker'),
				array('icon-caution' => 'Caution'),
				array('icon-recycle' => 'Recycle'),
				array('icon-anchor' => 'Anchor'),
				array('icon-profile-male' => 'Male'),
				array('icon-profile-female' => 'Female'),
				array('icon-bike' => 'Bike'),
				array('icon-wine' => 'Wine'),
				array('icon-hotairballoon' => 'Hotairballoon'),
				array('icon-globe' => 'Globe'),
				array('icon-genius' => 'Genius'),
				array('icon-map-pin' => 'Pin'),
				array('icon-dial' => 'Dial'),
				array('icon-chat' => 'Chat'),
				array('icon-heart' => 'Heart'),
				array('icon-cloud' => 'Cloud'),
				array('icon-upload' => 'Upload'),
				array('icon-download' => 'Download'),
				array('icon-target' => 'Target'),
				array('icon-hazardous' => 'Hazardous'),
				array('icon-piechart' => 'Piechart'),
				array('icon-speedometer' => 'Speedometer'),
				array('icon-global' => 'Global'),
				array('icon-compass' => 'Compass'),
				array('icon-lifesaver' => 'Lifesaver'),
				array('icon-clock' => 'Clock'),
				array('icon-aperture' => 'Aperture'),
				array('icon-quote' => 'Quote'),
				array('icon-scope' => 'Scope'),
				array('icon-alarmclock' => 'Alarmclock'),
				array('icon-refresh' => 'Refresh'),
				array('icon-happy' => 'Happy'),
				array('icon-sad' => 'Sad'),
				array('icon-facebook' => 'Facebook'),
				array('icon-twitter' => 'Twitter'),
				array('icon-googleplus' => 'Googleplus'),
				array('icon-rss' => 'Rss'),
				array('icon-tumblr' => 'Tumblr'),
				array('icon-linkedin' => 'Linkedin'),
				array('icon-dribbble' => 'Dribbble'),
		);

		return array_merge($icons, $etline_icons);
	}

}

if (!function_exists('rigid_portfolio_category_field_search')) {

	function rigid_portfolio_category_field_search($search_string) {
		$data = array();

		$vc_taxonomies_types = array('rigid_portfolio_category');
		$vc_taxonomies = get_terms($vc_taxonomies_types, array(
				'hide_empty' => false,
				'search' => $search_string,
		));
		if (is_array($vc_taxonomies) && !empty($vc_taxonomies)) {
			foreach ($vc_taxonomies as $t) {
				if (is_object($t)) {
					$data[] = vc_get_term_object($t);
				}
			}
		}

		return $data;
	}

}

if (!function_exists('rigid_latest_posts_category_field_search')) {

	function rigid_latest_posts_category_field_search($search_string) {
		$data = array();

		$vc_taxonomies_types = array('category');
		$vc_taxonomies = get_terms($vc_taxonomies_types, array(
				'hide_empty' => false,
				'search' => $search_string,
		));
		if (is_array($vc_taxonomies) && !empty($vc_taxonomies)) {
			foreach ($vc_taxonomies as $t) {
				if (is_object($t)) {
					$data[] = vc_get_term_object($t);
				}
			}
		}

		return $data;
	}

}
add_action('admin_init', 'rigid_load_incl_importer', 99);
if (!function_exists('rigid_load_incl_importer')) {

	function rigid_load_incl_importer() {
		/* load required files */

        // Load Importer API
		require_once ABSPATH . 'wp-admin/includes/import.php';

		if (!class_exists('WP_Importer')) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			if (file_exists($class_wp_importer)) {
				require_once $class_wp_importer;
			}
		}

		$class_rigid_importer = plugin_dir_path(__FILE__) . "importer/rigid-wordpress-importer.php";
		if (file_exists($class_rigid_importer)) {
			require_once $class_rigid_importer;
		}
	}

}

// Contact form ajax actions
if (!function_exists('rigid_submit_contact')) {

	function rigid_submit_contact() {

		check_ajax_referer('rigid_contactform', false, true);

		$unique_id = array_key_exists('unique_id', $_POST) ? sanitize_text_field($_POST['unique_id']) : '';
		$nonce = array_key_exists('_ajax_nonce', $_POST) ? sanitize_text_field($_POST['_ajax_nonce']) : '';

		ob_start();
		?>
		<script type="text/javascript">
            //<![CDATA[
            "use strict";
            jQuery(document).ready(function () {
                var submitButton = jQuery('#holder_<?php echo esc_js($unique_id) ?> input:submit');
                var loader = jQuery('<img id="<?php echo esc_js($unique_id) ?>_loading_gif" class="rigid-contacts-loading" src="<?php echo esc_url(plugin_dir_url(__FILE__)) ?>assets/image/contacts_ajax_loading.png" />').prependTo('#holder_<?php echo esc_attr($unique_id) ?> div.buttons div.left').hide();

                jQuery('#holder_<?php echo esc_js($unique_id) ?> form').ajaxForm({
                    target: '#holder_<?php echo esc_js($unique_id) ?>',
                    data: {
                        // additional data to be included along with the form fields
                        unique_id: '<?php echo esc_js($unique_id) ?>',
                        action: 'rigid_submit_contact',
                        _ajax_nonce: '<?php echo esc_js($nonce); ?>'
                    },
                    beforeSubmit: function (formData, jqForm, options) {
                        // optionally process data before submitting the form via AJAX
                        submitButton.hide();
                        loader.show();
                    },
                    success: function (responseText, statusText, xhr, $form) {
                        // code that's executed when the request is processed successfully
                        loader.remove();
                        submitButton.show();
                    }
                });
            });
            //]]>
		</script>
		<?php
		require(plugin_dir_path( __FILE__ ) . 'shortcodes/partials/contact-form.php');

		$output = ob_get_contents();
		ob_end_clean();

		echo $output; // All dynamic data escaped
		die();
	}

}

add_action('wp_ajax_rigid_submit_contact', 'rigid_submit_contact');
add_action('wp_ajax_nopriv_rigid_submit_contact', 'rigid_submit_contact');

//function to generate response
if (!function_exists('rigid_contact_form_generate_response')) {

	function rigid_contact_form_generate_response($type, $message) {

		$rigid_contactform_response = '';

		if ($type == "success") {
			$rigid_contactform_response = "<div class='success-message'>{$message}</div>";
		} else {
			$rigid_contactform_response .= "<div class='error-message'>{$message}</div>";
		}

		return $rigid_contactform_response;
	}

}

if (!function_exists('rigid_share_links')) {

	/**
	 * Displays social networks share links
	 *
	 * @param $title
	 * @param $link
	 */
    function rigid_share_links($title, $link) {

        $has_to_show_share = rigid_has_to_show_share();

        if ( $has_to_show_share ) {
	        global $post;

	        $media = get_the_post_thumbnail_url( $post->ID, 'large' );
            $share_links_html = '';

            $share_links_html .= sprintf(
                '<a class="rigid-share-facebook" title="%s" href="http://www.facebook.com/sharer.php?u=%s&t=%s" target="_blank" ></a>',
                esc_attr__( 'Share on Facebook', 'rigid-plugin' ),
                urlencode( $link ),
	            urlencode( html_entity_decode($title) )
            );
	        $share_links_html .= sprintf(
		        '<a class="rigid-share-twitter"  title="%s" href="http://twitter.com/share?text=%s&url=%s" target="_blank"></a>',
		        esc_attr__( 'Share on Twitter', 'rigid-plugin' ),
		        urlencode( html_entity_decode($title) ),
                urlencode( $link )
	        );
	        $share_links_html .= sprintf(
		        '<a class="rigid-share-pinterest" title="%s"  href="http://pinterest.com/pin/create/button?media=%s&url=%s&description=%s" target="_blank"></a>',
		        esc_attr__( 'Share on Pinterest', 'rigid-plugin' ),
		        urlencode( $media ),
		        urlencode( $link ),
		        urlencode( html_entity_decode($title) )
	        );
	        $share_links_html .= sprintf(
		        '<a class="rigid-share-google-plus" title="%s" href="https://plus.google.com/share?url=%s&text=%s" target="_blank"></a>',
		        esc_attr__( 'Share on Google+', 'rigid-plugin' ),
		        urlencode( $link ),
		        urlencode( html_entity_decode($title) )
	        );
	        $share_links_html .= sprintf(
		        '<a class="rigid-share-linkedin" title="%s" href="http://www.linkedin.com/shareArticle?url=%s&title=%s" target="_blank"></a>',
		        esc_attr__( 'Share on LinkedIn', 'rigid-plugin' ),
		        urlencode( $link ),
		        urlencode( html_entity_decode($title) )
	        );
	        $share_links_html .= sprintf(
		        '<a class="rigid-share-vkontakte" title="%s"  href="http://vk.com/share.php?url=%s&title=%s&image=%s" target="_blank"></a>',
		        esc_attr__( 'Share on VK', 'rigid-plugin' ),
		        urlencode( $link ),
		        urlencode( html_entity_decode($title) ),
		        urlencode( $media )
	        );

            printf( '<div class="rigid-share-links">%s<div class="clear"></div></div>', $share_links_html );
        }

    }
}

add_action('wp_head', 'rigid_insert_og_tags');
if (!function_exists('rigid_insert_og_tags')) {
	/**
	 * Insert og tags sharers
	 */
    function rigid_insert_og_tags() {
        global $post;

        if(is_singular() && rigid_has_to_show_share()) {
            $large_size_width = get_option( "large_size_w" );
	        $large_size_height = get_option( "large_size_h" );

	        printf( '<meta property="og:image" content="%s">', get_the_post_thumbnail_url( $post->ID, 'large' ) );
	        printf( '<meta property="og:image:width" content="%d">', $large_size_width );
	        printf( '<meta property="og:image:height" content="%d">', $large_size_height );
        }
    }
}

if (!function_exists('rigid_has_to_show_share')) {
	function rigid_has_to_show_share() {

		if(function_exists('rigid_get_option')) {
			$general_option         = rigid_get_option( 'show_share_general' );
			$general_option_product = rigid_get_option( 'show_share_shop' );
			$single_meta            = get_post_meta( get_the_ID(), 'rigid_show_share', true );

			$target = 'single';
			if (function_exists('is_product') && is_product()) {
			    $target = 'product';
			}

			$has_to_show_share = false;

			if ( $target === 'single' && $single_meta === 'yes' ) {
				$has_to_show_share = true;
			} elseif ( $target === 'single' && $general_option && $single_meta !== 'no' ) {
				$has_to_show_share = true;
			} elseif ( $target === 'product' && $general_option_product ) {
				$has_to_show_share = true;
			}

			return $has_to_show_share;
		}

		return false;
	}
}