<?php

if (!defined('RIGID_IMAGES_PATH')) {
	define('RIGID_IMAGES_PATH', get_template_directory_uri() . '/image/');
}

if (!defined('RIGID_BACKGROUNDS_PATH')) {
	define('RIGID_BACKGROUNDS_PATH', RIGID_IMAGES_PATH . 'backgrounds/');
}

if (class_exists('bbPress')) {
	define('RIGID_IS_BBPRESS', TRUE);
} else {
	define('RIGID_IS_BBPRESS', FALSE);
}

// Check if WooCommerce is active
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	define('RIGID_IS_WOOCOMMERCE', TRUE);
	require_once(get_template_directory() . '/incl/woocommerce-functions.php');
	require_once(get_template_directory() . '/incl/system/woocommerce-metaboxes.php');
} else {
	define('RIGID_IS_WOOCOMMERCE', FALSE);
}

if (class_exists('Tribe__Events__Main')) {
	define('RIGID_IS_EVENTS', TRUE);
} else {
	define('RIGID_IS_EVENTS', FALSE);
}

if (class_exists('YITH_WCWL')) {
	define('RIGID_IS_WISHLIST', TRUE);
} else {
	define('RIGID_IS_WISHLIST', FALSE);
}

if (class_exists('RevSliderBase')) {
	define('RIGID_IS_REVOLUTION', TRUE);
} else {
	define('RIGID_IS_REVOLUTION', FALSE);
}

// include metaboxes.php
require_once(get_template_directory() . '/incl/system/metaboxes.php');

// Is blank page template
global $rigid_is_blank;
$rigid_is_blank = false;

/**
 * Force Visual Composer to initialize as "built into the theme". This will hide certain tabs under the Settings->Visual Composer page
 */
if (!function_exists('rigid_set_vc_as_theme')) {
	add_action('vc_before_init', 'rigid_set_vc_as_theme');

	function rigid_set_vc_as_theme() {
		vc_set_as_theme(true);
	}

}

add_action('init', 'rigid_vc_set_cpt');
if (!function_exists('rigid_vc_set_cpt')) {

	/**
	 * Define the post types that will use VC
	 */
	function rigid_vc_set_cpt() {
		if (class_exists('WPBakeryVisualComposerAbstract')) {
			$list = array(
					'post',
					'page',
					'product',
					'product_variation',
					'rigid-portfolio'
			);
			vc_set_default_editor_post_types($list);
		}
	}

}

/**
 * Include TGM-Plugin-Activation
 */
require_once(get_template_directory() . '/incl/tgm-plugin-activation/class-tgm-plugin-activation.php');

/**
 * Include Rigid_Transfer_Content
 */
require_once(get_template_directory() . '/incl/RigidTransferContent.class.php');

/**
 * Include RigidWalker
 */
require_once(get_template_directory() . '/incl/RigidMegaMenu.php');

/*
 * Register theme text domain
 */
add_action('after_setup_theme', 'rigid_lang_setup');
if (!function_exists('rigid_lang_setup')) {

	function rigid_lang_setup() {
		load_theme_textdomain('rigid', get_template_directory() . '/languages');
	}

}

/**
 * Include the dynamic css
 */
require_once(get_template_directory() . '/styles/dynamic-css.php');
