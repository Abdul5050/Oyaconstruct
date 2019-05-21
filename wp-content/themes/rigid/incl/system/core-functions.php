<?php
defined('ABSPATH') || die();
/* Register Theme Features */

/* Hook into the 'after_setup_theme' action */
add_action('after_setup_theme', 'rigid_register_theme_features');
if (!function_exists('rigid_register_theme_features')) {

	function rigid_register_theme_features() {

		// Add post-thumbnails support
		add_theme_support('post-thumbnails');

		// Add Content Width theme support
		if (!isset($content_width)) {
			$content_width = 1220;
		}

		// Add Feed Links theme support
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		// Add theme support for Custom Background
		$background_args = array(
				'default-color' => '',
				'default-image' => '',
				'wp-head-callback' => '_custom_background_cb',
				'admin-head-callback' => '',
				'admin-preview-callback' => '',
		);
		add_theme_support('custom-background', $background_args);

		//  Add theme suppport for aside, gallery, link, image, quote, status, video, audio, chat
		add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));
	}

}

// BC for title tag
if (!function_exists('_wp_render_title_tag')) {
	add_action('wp_head', 'rigid_render_title');
	if (!function_exists('rigid_render_title')) {

		function rigid_render_title() {
			?>
			<title><?php wp_title('|', true, 'right'); ?></title>
			<?php
		}

	}
}

// Register top navigation menu
register_nav_menu('primary', esc_html__('Main Menu', 'rigid'));

// Register mobile navigation menu
register_nav_menu('mobile', esc_html__('Mobile Menu', 'rigid'));

// Register side navigation menu
register_nav_menu('secondary', esc_html__('Top Menu', 'rigid'));

// Register footer navigation menu
register_nav_menu('tertiary', esc_html__('Footer Menu', 'rigid'));

add_action('widgets_init', 'rigid_register_sidebars');
if (!function_exists('rigid_register_sidebars')) {

	/**
	 * Register sidebars
	 */
	function rigid_register_sidebars() {
		if (function_exists('register_sidebar')) {

			// Define default sidebar
			register_sidebar(array(
					'name' => esc_html__('Default Sidebar', 'rigid'),
					'id' => 'right_sidebar',
					'description' => esc_html__('Default Blog widget area', 'rigid'),
					'before_widget' => '<div id="%1$s" class="widget box %2$s">',
					'after_widget' => '</div>',
					'before_title' => '<h3>',
					'after_title' => '</h3>',
			));

			// Define bottom footer widget area
			register_sidebar(array(
					'name' => esc_html__('Footer Sidebar', 'rigid'),
					'id' => 'bottom_footer_sidebar',
					'description' => esc_html__('Footer widget area', 'rigid'),
					'before_widget' => '<div id="%1$s" class="widget %2$s ">',
					'after_widget' => '</div>',
					'before_title' => '<h3>',
					'after_title' => '</h3>',
			));

			// Define Pre header widget area
			register_sidebar(array(
					'name' => esc_html__('Pre Header Sidebar', 'rigid'),
					'id' => 'pre_header_sidebar',
					'description' => esc_html__('Pre header widget area', 'rigid'),
					'before_widget' => '<div id="%1$s" class="widget %2$s ">',
					'after_widget' => '</div>',
					'before_title' => '<h3>',
					'after_title' => '</h3>',
			));

			if (RIGID_IS_WOOCOMMERCE) {
				// Define shop sidebar if woocommerce is active
				register_sidebar(array(
						'name' => esc_html__('Shop Sidebar', 'rigid'),
						'id' => 'shop',
						'description' => esc_html__('Default Shop sidebar', 'rigid'),
						'before_widget' => '<div id="%1$s" class="widget box %2$s">',
						'after_widget' => '</div>',
						'before_title' => '<h3>',
						'after_title' => '</h3>',
				));

				// Define widget area for product filters
				register_sidebar(array(
					'name' => esc_html__('Product Filters Sidebar', 'rigid'),
					'id' => 'rigid_product_filters_sidebar',
					'description' => esc_html__('Product filters widget area, shown on shop and product category pages', 'rigid'),
					'before_widget' => '<div id="%1$s" class="widget box %2$s">',
					'after_widget' => '</div>',
					'before_title' => '<h3>',
					'after_title' => '</h3>',
				));
			}

			if (RIGID_IS_BBPRESS) {
				// Define shop sidebar if BBpress is active
				register_sidebar(array(
						'name' => 'Forum Sidebar',
						'id' => 'rigid_forum',
						'description' => esc_html__('Default Forum sidebar', 'rigid'),
						'before_widget' => '<div id="%1$s" class="widget box %2$s">',
						'after_widget' => '</div>',
						'before_title' => '<h3>',
						'after_title' => '</h3>',
				));
			}

			// Register the custom sidbars
			$rigid_custom_sdbrs = substr(rigid_get_option('sidebar_ids'), 0, -1);

			if ($rigid_custom_sdbrs) {
				$sdbrsArr = explode(';', $rigid_custom_sdbrs);
				foreach ($sdbrsArr as $sdbr) {
					$sdbr_id = rigid_generate_slug($sdbr, 45);
					register_sidebar(array(
							'name' => $sdbr,
							'id' => $sdbr_id,
							'before_widget' => '<div id="%1$s" class="widget box %2$s">',
							'after_widget' => '</div>',
							'before_title' => '<h3>',
							'after_title' => '</h3>',
					));
				}
			}
		}
	}

}

add_action('tgmpa_register', 'rigid_register_required_plugins');

/**
 * Register the required plugins for this theme.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
if (!function_exists('rigid_register_required_plugins')) {

	function rigid_register_required_plugins() {

		/**
		 * Array of plugin arrays. Required keys are name and slug.
		 * If the source is NOT from the .org repo, then source is also required.
		 */
		$plugins = array(
				array(
						'name' => esc_html__('Rigid Plugin', 'rigid'), // The plugin name
						'slug' => 'rigid-plugin', // The plugin slug (typically the folder name)
						'source' => get_template_directory() . '/plugins/rigid-plugin.zip', // The plugin source
						'required' => true, // If false, the plugin is only 'recommended' instead of required
						'force_activation' => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
						'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
						'version' => '1.1.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
				),
				array(
						'name' => esc_html__('WooCommerce - excelling eCommerce', 'rigid'), // The plugin name
						'slug' => 'woocommerce', // The plugin slug (typically the folder name)
						'required' => false, // If false, the plugin is only 'recommended' instead of required
						'version' => '3.2.3', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
				),
				array(
						'name' => esc_html__('YITH WooCommerce Wishlist', 'rigid'), // The plugin name
						'slug' => 'yith-woocommerce-wishlist', // The plugin slug (typically the folder name)
						'required' => false, // If false, the plugin is only 'recommended' instead of required
						'version' => '2.1.2', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
				),
				array(
						'name' => esc_html__('Revolution Slider', 'rigid'), // The plugin name
						'slug' => 'revslider', // The plugin slug (typically the folder name)
						'source' => get_template_directory() . '/plugins/revslider.zip', // The plugin source
						'required' => false, // If false, the plugin is only 'recommended' instead of required
						'version' => '5.4.6.2', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
						'force_activation' => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
						'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
				),
				array(
						'name' => esc_html__('WPBakery Page Builder', 'rigid'), // The plugin name
						'slug' => 'js_composer', // The plugin slug (typically the folder name)
						'source' => get_template_directory() . '/plugins/js_composer.zip', // The plugin source
						'required' => false, // If false, the plugin is only 'recommended' instead of required
						'version' => '5.4.2', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
						'force_activation' => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
						'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
				),
		);


		/**
		 * Array of configuration settings. Amend each line as needed.
		 * If you want the default strings to be available under your own theme domain,
		 * leave the strings uncommented.
		 * Some of the strings are added into a sprintf, so see the comments at the
		 * end of each line for what each argument will be.
		 */
		$config = array(
				'id' => 'rigid', // Unique ID for hashing notices for multiple instances of TGMPA.
				'default_path' => '', // Default absolute path to bundled plugins.
				'menu' => 'tgmpa-install-plugins', // Menu slug.
				'has_notices' => true, // Show admin notices or not.
				'is_automatic' => false, // Automatically activate plugins after installation or not.
				'dismissable' => true, // If false, a user cannot dismiss the nag message.
				'dismiss_msg' => '', // If 'dismissable' is false, this message will be output at top of nag.
				'message' => '', // Message to output right before the plugins table.
				'strings' => array(
						'page_title' => esc_html__('Install Required Plugins', 'rigid'),
						'menu_title' => esc_html__('Install Plugins', 'rigid'),
						/* translators: %s: plugin name. */
						'installing' => esc_html__('Installing Plugin: %s', 'rigid'),
						/* translators: %s: plugin name. */
						'updating' => esc_html__('Updating Plugin: %s', 'rigid'),
						'oops' => esc_html__('Something went wrong with the plugin API.', 'rigid'),
						'notice_can_install_required' => _n_noop(
										/* translators: 1: plugin name(s). */
										'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'rigid'
						),
						'notice_can_install_recommended' => _n_noop(
										/* translators: 1: plugin name(s). */
										'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'rigid'
						),
						'notice_ask_to_update' => _n_noop(
										/* translators: 1: plugin name(s). */
										'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'rigid'
						),
						'notice_ask_to_update_maybe' => _n_noop(
										/* translators: 1: plugin name(s). */
										'There is an update available for: %1$s. Prior update please make sure that the theme is compatible with the new version.', 'There are updates available for the following plugins: %1$s. Prior update please make sure that the theme is compatible with the new version.', 'rigid'
						),
						'notice_can_activate_required' => _n_noop(
										/* translators: 1: plugin name(s). */
										'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'rigid'
						),
						'notice_can_activate_recommended' => _n_noop(
										/* translators: 1: plugin name(s). */
										'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'rigid'
						),
						'install_link' => _n_noop(
										'Begin installing plugin', 'Begin installing plugins', 'rigid'
						),
						'update_link' => _n_noop(
										'Begin updating plugin', 'Begin updating plugins', 'rigid'
						),
						'activate_link' => _n_noop(
										'Begin activating plugin', 'Begin activating plugins', 'rigid'
						),
						'return' => esc_html__('Return to Required Plugins Installer', 'rigid'),
						'plugin_activated' => esc_html__('Plugin activated successfully.', 'rigid'),
						'activated_successfully' => esc_html__('The following plugin was activated successfully:', 'rigid'),
						/* translators: 1: plugin name. */
						'plugin_already_active' => esc_html__('No action taken. Plugin %1$s was already active.', 'rigid'),
						/* translators: 1: plugin name. */
						'plugin_needs_higher_version' => esc_html__('Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'rigid'),
						/* translators: 1: dashboard link. */
						'complete' => esc_html__('All plugins installed and activated successfully. %1$s', 'rigid'),
						'dismiss' => esc_html__('Dismiss this notice', 'rigid'),
						'notice_cannot_install_activate' => esc_html__('There are one or more required or recommended plugins to install, update or activate.', 'rigid'),
						'contact_admin' => esc_html__('Please contact the administrator of this site for help.', 'rigid'),
						'nag_type' => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
				),
		);

		tgmpa($plugins, $config);
	}

}

/**
 * Converts the stored id's of the images
 * to be friendly for supersized js plugin
 *
 * @param type $ids
 */
if (!function_exists('rigid_get_supersized_image_urls')) {

	function rigid_get_supersized_image_urls($ids) {
		$image_urls = array();

		$ids_arr = explode(';', $ids);

		if (is_array($ids_arr) && count($ids_arr)) {
			foreach ($ids_arr as $id) {
				$image_urls[] = esc_url(wp_get_attachment_url($id));
			}
		}

		return $image_urls;
	}

}

/**
 * Enqueues scripts and styles in the admin
 *
 * @param type $hook
 * @return type
 */
if (!function_exists('rigid_enqueue_admin_js')) {

	function rigid_enqueue_admin_js($hook) {
		wp_enqueue_style('rigid-admin', get_template_directory_uri() . "/styles/rigid-admin.css");
		wp_register_script('rigid-medialibrary-uploader', RIGID_OPTIONS_FRAMEWORK_DIRECTORY . 'js/rigid-medialibrary-uploader.js', array('jquery-ui-accordion', 'media-upload'), false, true);
		wp_enqueue_script('rigid-medialibrary-uploader');

		// Color picker for menu label colors
		wp_enqueue_style('jquery-minicolors', get_template_directory_uri() . "/js/colorpicker/jquery.minicolors.css");
		wp_enqueue_script('jquery-minicolors', get_template_directory_uri() . "/js/colorpicker/jquery.minicolors.min.js", array('jquery'), false, true);
		wp_enqueue_script('jquery-minicolors-conf', get_template_directory_uri() . "/js/rigid-colorpicker-conf.js", array('jquery-minicolors'), false, true);

		// font-awesome
		wp_enqueue_style('font-awesome', get_template_directory_uri() . "/styles/font-awesome/css/font-awesome.min.css", array('rigid-fonticonpicker-css'), false, 'screen');
		// et-line-font
		wp_enqueue_style('et-line-font', get_template_directory_uri() . "/styles/et-line-font/style.css", false, false, 'screen');
		// Fonticonpicker
		wp_enqueue_script('rigid-fonticonpicker', get_template_directory_uri() . "/js/fonticonpicker/jquery.fonticonpicker.min.js", array('jquery'), false, true);
		wp_enqueue_style('rigid-fonticonpicker-css', get_template_directory_uri() . "/js/fonticonpicker/css/jquery.fonticonpicker.min.css");
		wp_enqueue_style('rigid-fonticonpicker-gray-theme', get_template_directory_uri() . "/js/fonticonpicker/themes/grey-theme/jquery.fonticonpicker.grey.min.css", array('rigid-fonticonpicker-css'));
		// Backend jS
		wp_enqueue_script('rigid-nice-select', get_template_directory_uri() . "/incl/rigid-options-framework/js/jquery.nice-select.min.js", array('jquery'), false, true);
		wp_enqueue_script('rigid-back', get_template_directory_uri() . "/js/rigid-back.js", array('jquery', 'rigid-nice-select'), false, true);
		// Mega Menu script/style
		wp_enqueue_style('rigid-mega-menu', get_template_directory_uri() . '/styles/rigid-admin-megamenu.css');
		wp_enqueue_script('rigid-mega-menu', get_template_directory_uri() . '/js/rigid-admin-mega-menu.js', array('jquery', 'jquery-ui-sortable'), false, true);
	}

}
add_action('admin_enqueue_scripts', 'rigid_enqueue_admin_js');

/**
 * Checks if post has 'rigid_super_slider_ids' meta
 * and return the supersized slider images ids.
 * If not - returns false
 *
 * @return boolean
 */
if (!function_exists('rigid_has_post_supersized')) {

	function rigid_has_post_supersized() {
		$image_urls = array();
		$custom = false;

		if (is_singular()) {
			$custom = get_post_custom();
		}

		if ($custom && array_key_exists('rigid_super_slider_ids', $custom) && $custom['rigid_super_slider_ids'][0]) {
			$ids_arr = explode(';', $custom['rigid_super_slider_ids'][0]);

			foreach ($ids_arr as $id) {
				$image_urls[] = wp_get_attachment_url($id);
			}

			return $image_urls;
		}

		return false;
	}

}

/**
 * Checks if post has 'rigid_video_bckgr_url' meta
 * and return the custom fields.
 * If not - returns false
 *
 * @return boolean
 */
if (!function_exists('rigid_has_post_video_bckgr')) {

	function rigid_has_post_video_bckgr() {

		$custom = false;

		if (is_singular()) {
			$custom = get_post_custom();
		}

		if ($custom && array_key_exists('rigid_video_bckgr_url', $custom) && $custom['rigid_video_bckgr_url'][0]) {
			return $custom;
		}

		return false;
	}

}

/**
 * Used to generate slugs
 * Used mainly for custom sidebars
 *
 * @param String $phrase
 * @param Integer $maxLength
 * @return String
 */
if (!function_exists('rigid_generate_slug')) {

	function rigid_generate_slug($phrase, $maxLength) {
		$result = strtolower($phrase);

		$result = preg_replace("/[^a-z0-9\s-]/", "", $result);
		$result = trim(preg_replace("/[\s-]+/", " ", $result));
		$result = trim(substr($result, 0, $maxLength));
		$result = preg_replace("/\s/", "-", $result);

		return $result;
	}

}

/**
 * Returns string with links to all parent taxonomies
 */
if (!function_exists('rigid_get_taxonomy_parents')) {

	function rigid_get_taxonomy_parents($id, $taxonomy, $link = false, $separator = '/', $nicename = false, $visited = array()) {
		$chain = '';
		$parent = get_term($id, $taxonomy);
		if (is_wp_error($parent))
			return $parent;

		if ($nicename)
			$name = $parent->slug;
		else
			$name = $parent->name;

		if ($parent->parent && ( $parent->parent != $parent->term_id ) && !in_array($parent->parent, $visited)) {
			$visited[] = $parent->parent;
			$chain .= rigid_get_taxonomy_parents($parent->parent, $taxonomy, $link, $separator, $nicename, $visited);
		}

		if ($link) {
			$term_link = get_term_link($parent, $taxonomy);
			$chain .= '<a href="' . esc_url($term_link) . '">' . $name . '</a>' . $separator;
		} else
			$chain .= $name . $separator;
		return $chain;
	}

}

if (!function_exists('rigid_get_more_featured_images')) {

	/**
	 * Get custom featured images by post_id
	 *
	 * @param int $post_id
	 * @return array of custom featured images. If not - empty array
	 */
	function rigid_get_more_featured_images($post_id) {
		$featured_imgs = array();
		$post_meta = get_post_meta($post_id);

		for ($i = 2; $i <= 6; $i++) {
			if (isset($post_meta['rigid_featured_imgid_' . $i][0]) && $post_meta['rigid_featured_imgid_' . $i][0]) {
				$featured_imgs['rigid_featured_imgid_' . $i] = $post_meta['rigid_featured_imgid_' . $i][0];
			}
		}

		return $featured_imgs;
	}

}

if (!function_exists('rigid_wp_lang_to_valid_language_code')) {

	function rigid_wp_lang_to_valid_language_code($wp_lang) {
		$wp_lang = str_replace('_', '-', $wp_lang);
		switch (strtolower($wp_lang)) {
			// arabic
			case 'ar':
			case 'ar-ae':
			case 'ar-bh':
			case 'ar-dz':
			case 'ar-eg':
			case 'ar-iq':
			case 'ar-jo':
			case 'ar-kw':
			case 'ar-lb':
			case 'ar-ly':
			case 'ar-ma':
			case 'ar-om':
			case 'ar-qa':
			case 'ar-sa':
			case 'ar-sy':
			case 'ar-tn':
			case 'ar-ye': return 'ar';

			// bulgarian
			case 'bg':
			case 'bg-bg': return 'bg';

			// bosnian
			case 'bs':
			case 'bs-ba': return 'bs';

			// catalan
			case 'ca':
			case 'ca-es': return 'ca';

			// czech
			case 'cs':
			case 'cs-cz': return 'cs';

			case 'cy': return 'cy';

			// danish
			case 'da':
			case 'da-dk': return 'da';

			// german
			case 'de':
			case 'de-at':
			case 'de-ch':
			case 'de-de':
			case 'de-li':
			case 'de-lu': return 'de';

			// greek
			case 'el':
			case 'el-gr': return 'el';

			// spanish
			case 'es':
			case 'es-ar':
			case 'es-bo':
			case 'es-cl':
			case 'es-co':
			case 'es-cr':
			case 'es-do':
			case 'es-ec':
			case 'es-es':
			case 'es-es':
			case 'es-gt':
			case 'es-hn':
			case 'es-mx':
			case 'es-ni':
			case 'es-pa':
			case 'es-pe':
			case 'es-pr':
			case 'es-py':
			case 'es-sv':
			case 'es-uy':
			case 'es-ve': return 'es';

			// estonian
			case 'et':
			case 'et-ee': return 'et';

			// farsi/persian
			case 'fa':
			case 'fa fa-ir': return 'fa';

			// finnish
			case 'fi':
			case 'fi-fi': return 'fi';

			// french
			case 'fr':
			case 'fr-be':
			case 'fr-ca':
			case 'fr-ch':
			case 'fr-fr':
			case 'fr-lu':
			case 'fr-mc': return 'fr';

			// galician
			case 'gl':
			case 'gl-es': return 'gl';

			// gujarati
			case 'gu':
			case 'gu-in': return 'gu';

			// hebrew
			case 'he':
			case 'he-il': return 'he';

			// croatian
			case 'hr':
			case 'hr-ba':
			case 'hr-hr': return 'hr';

			// hungarian
			case 'hu':
			case 'hu-hu': return 'hu';

			// armenian
			case 'hy':
			case 'hy-am': return 'hy';

			// indonesian
			case 'id':
			case 'id-id': return 'id';

			// italian
			case 'it':
			case 'it-ch':
			case 'it-it': return 'it';

			// japanese
			case 'ja':
			case 'ja-jp': return 'ja';

			// kannada
			case 'kn':
			case 'kn-in': return 'kn';

			// korean
			case 'ko':
			case 'ko-kr': return 'ko';

			// lithuanian
			case 'lt':
			case 'lt-lt': return 'lt';

			// latvian
			case 'lv':
			case 'lv-lv': return 'lv';

			// malay
			case 'ms':
			case 'ms-bn':
			case 'ms-my': return 'ms';

			// burmese
			case 'my': return 'my';

			// norwegian
			case 'nb':
			case 'nb-no': return 'nb';

			// dutch
			case 'nl':
			case 'nl-be':
			case 'nl-nl': return 'nl';

			// polish
			case 'pl':
			case 'pl-pl': return 'pl';

			// portuguese
			case 'pt':
			case 'pt-br':
			case 'pt-pt': return 'pt-br';

			// romanian
			case 'ro':
			case 'ro-ro': return 'ro';

			// russian
			case 'ru':
			case 'ru-ru': return 'ru';

			// slovak
			case 'sk':
			case 'sk-sk': return 'sk';

			// slovenian
			case 'sl':
			case 'sl-si': return 'sl';

			// albanian
			case 'sq':
			case 'sq-al': return 'sq';

			// serbian
			case 'sr-ba':
			case 'sr-sp':
			case 'sr-sr': return 'sr-sr';

			// swedish
			case 'sv':
			case 'sv-fi':
			case 'sv-se': return 'sv';

			// thai
			case 'th':
			case 'th-th': return 'th';

			// turkish
			case 'tr':
			case 'tr-tr': return 'tr';

			// ukranian
			case 'uk':
			case 'uk-ua': return 'uk';

			// urdu
			case 'ur':
			case 'ur-pk': return 'ur';

			// uzbek
			case 'uz':
			case 'uz-uz': return 'uz';

			// vietnamese
			case 'vi':
			case 'vi-vn': return 'vi';

			// chinese/simplified
			case 'zh-cn': return 'zh-cn';

			// chinese/traditional
			case 'zh':
			case 'zh-hk':
			case 'zh-mo':
			case 'zh-sg':
			case 'zh-tw': return 'zh-tw';

			/* these don't exist and have no real language code? */

			// malaylam
			case 'ml': return 'ml';

			// assume english
			default: return '';
		}
	}

}

/**
 * Checks font options to see if a Google font is selected.
 * If so, builds an url to enqueue the styles
 */
if (!function_exists('rigid_typography_google_fonts_url')) {

	function rigid_typography_google_fonts_url() {

		$font_families = array();

		/* Translators: If there are characters in your language that are not
		 * supported by that font, translate this to 'off'. Do not translate
		 * into your own language.
		 */
		if ('off' !== _x('on', 'Google fonts: on or off', 'rigid')) {
			$all_google_fonts = array_keys(rigid_typography_get_google_fonts());

			// Define all the options that possibly have a unique Google font
			$body_font = rigid_get_option('body_font');
			$headings_font = rigid_get_option('headings_font');

			// Get the font face for each option and put it in an array
			$selected_fonts = array(
					$body_font['face'],
					$headings_font['face']);

			// Remove any duplicates in the list
			$selected_fonts = array_unique($selected_fonts);

			// Check each of the unique fonts against the defined Google fonts
			// If it is a Google font, go ahead and call the function to enqueue it
			foreach ($selected_fonts as $font) {
				if (in_array($font, $all_google_fonts)) {
					$font_families[] = $font;
				}
			}
		}

		$font_url = '';

		if (!empty($font_families)) {
			$font_families_string_to_encode = implode('|', $font_families);
			$font_url = add_query_arg('family', urlencode($font_families_string_to_encode . ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&subset=' . rigid_get_google_subsets()), "//fonts.googleapis.com/css");
		}

		return $font_url;
	}

}
add_action('wp_enqueue_scripts', 'rigid_typography_enqueue_google_font');
add_action('admin_enqueue_scripts', 'rigid_typography_enqueue_google_font');

/**
 * Enqueues the Google $font that is passed
 */
function rigid_typography_enqueue_google_font() {
	wp_enqueue_style('rigid-fonts', rigid_typography_google_fonts_url(), array(), '1.0.0');
}

/**
 * Register / Enqueue theme scripts
 */
add_action('wp_enqueue_scripts', 'rigid_enqueue_scripts_and_styles');
if (!function_exists('rigid_enqueue_scripts_and_styles')) {

	function rigid_enqueue_scripts_and_styles() {

		// Preloader style
		if (rigid_get_option('show_preloader')) {
			wp_enqueue_style('rigid-preloader', get_template_directory_uri() . "/styles/rigid-preloader.css");
		}

		// Load the main stylesheet.
		wp_enqueue_style('rigid-style', get_stylesheet_uri());

		// Load the responsive stylesheet if enabled
		if (rigid_get_option('is_responsive')) {
			wp_enqueue_style('rigid-responsive', get_template_directory_uri() . "/styles/rigid-responsive.css", array('rigid-style'));
		}

		wp_enqueue_style('font-awesome', get_template_directory_uri() . "/styles/font-awesome/css/font-awesome.min.css");
		wp_enqueue_style('et-line-font', get_template_directory_uri() . "/styles/et-line-font/style.css");

		// Modernizr
		wp_enqueue_script('modernizr', get_template_directory_uri() . "/js/modernizr.custom.js", array('jquery'));

		/* loading jquery-ui-slider only for price filter */
		if (RIGID_IS_WOOCOMMERCE && rigid_get_option('show_pricefilter') && is_woocommerce() && !is_product()) {
			wp_enqueue_script('jquery-ui-slider');
		}

		$cart_redirect_after_add = 'no';
		$cart_url                = '';
		if ( RIGID_IS_WOOCOMMERCE && get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
			$cart_redirect_after_add = 'yes';
			$cart_url                = apply_filters( 'woocommerce_add_to_cart_redirect', wc_get_cart_url() );
		}

		$enable_ajax_add_to_cart = 'no';
		if ( RIGID_IS_WOOCOMMERCE && rigid_get_option('ajax_to_cart_single') ) {
			$enable_ajax_add_to_cart = 'yes';
		}

		$enable_infinite_on_shop = 'no';
		if ( RIGID_IS_WOOCOMMERCE && rigid_get_option( 'enable_shop_infinite' ) ) {
			$enable_infinite_on_shop = 'yes';
		}

		$use_load_more_on_shop = 'no';
		if ( RIGID_IS_WOOCOMMERCE && rigid_get_option( 'use_load_more_on_shop' ) ) {
			$use_load_more_on_shop = 'yes';
		}

		$use_product_filter_ajax = 'no';
		if ( RIGID_IS_WOOCOMMERCE && rigid_get_option( 'use_product_filter_ajax' ) ) {
			$use_product_filter_ajax = 'yes';
		}

		wp_enqueue_script('rigid-front', get_template_directory_uri() . "/js/rigid-front.js", array('jquery'), false, true);
		wp_localize_script('rigid-front', 'rigid_main_js_params', array(
				'img_path' => esc_js(RIGID_IMAGES_PATH),
				'admin_url' => esc_js(admin_url('admin-ajax.php')),
				'added_to_cart_label' => esc_js(__('was added to the cart', 'rigid')),
				'show_preloader' => esc_js(rigid_get_option('show_preloader')),
				'sticky_header' => esc_js(rigid_get_option('sticky_header')),
				'enable_smooth_scroll' => esc_js(rigid_get_option('enable_smooth_scroll')),
				'login_label' => esc_js(__('Login', 'rigid')),
				'register_label' => esc_js(__('Register', 'rigid')),
				'cart_redirect_after_add' => $cart_redirect_after_add,
				'cart_url' => $cart_url,
				'enable_ajax_add_to_cart' => $enable_ajax_add_to_cart,
				'enable_infinite_on_shop' => $enable_infinite_on_shop,
				'use_load_more_on_shop' => $use_load_more_on_shop,
				'use_product_filter_ajax' => $use_product_filter_ajax,
				'is_rtl' => (is_rtl() ? 'true' : 'false')
		));

		/* imagesloaded */
		wp_enqueue_script('imagesloaded', '',array('jquery'), false, true);

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

		// Mega Menu script/style
		wp_register_style('rigid-mega-menu', get_template_directory_uri() . '/styles/rigid-admin-megamenu.css');
		wp_register_script('rigid-mega-menu', get_template_directory_uri() . '/js/rigid-admin-mega-menu.js', array('jquery', 'jquery-ui-sortable'), false, true);

		// register Isotope
		wp_register_script('isotope', get_template_directory_uri() . "/js/isotope/dist/isotope.pkgd.min.js", array('jquery', 'imagesloaded'), false, true);

		// enqueue google map api
		wp_register_script('rigid-google-map', 'https://maps.googleapis.com/maps/api/js?'.( rigid_get_option('google_maps_api_key') ? 'key='.rigid_get_option('google_maps_api_key').'&' : '' ).'sensor=false', array('jquery'), false, true);

		$rigid_local = rigid_wp_lang_to_valid_language_code(get_locale());
		if ($rigid_local) {
			wp_enqueue_script('jquery-countdown-local', get_template_directory_uri() . "/js/count/jquery.countdown-$rigid_local.js", array('jquery', 'countdown'), false, true);
		}

		$is_compare = false;
		if (isset($_GET['action']) && $_GET['action'] === 'yith-woocompare-view-table') {
			$is_compare = true;
		}

		$to_include_supersized = rigid_has_to_include_supersized($is_compare);
		$to_include_backgr_video = rigid_has_to_include_backgr_video($is_compare);

		/* JavaScript to pages with the comment form
		 * to support sites with threaded comments (when in use).
		 */
		if (is_singular() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply');
		}

		/* Include js configs */
		wp_enqueue_script('rigid-libs-config', get_template_directory_uri() . "/js/rigid-libs-config.min.js", array('jquery', 'wp-util', 'flexslider', 'owl-carousel', 'cloud-zoom', 'countdown', 'magnific', 'appear', 'typed', 'nice-select', 'is-in-viewport' ), false, true);

		// send is_rtl to js for owl carousel
		wp_localize_script( 'rigid-libs-config', 'rigid_rtl',
			array(
				'is_rtl' => ( is_rtl() ? 'true' : 'false' )
			) );

		if ( RIGID_IS_WOOCOMMERCE && rigid_get_option( 'use_quickview' ) ) {
			wp_localize_script( 'rigid-libs-config', 'rigid_quickview',
				array(
					'rigid_ajax_url' => esc_js( admin_url( 'admin-ajax.php' ) ),
					'wc_ajax_url'                      => WC_AJAX::get_endpoint( "%%endpoint%%" ),
					'i18n_no_matching_variations_text' => esc_attr__( 'Sorry, no products matched your selection. Please choose a different combination.', 'rigid' ),
					'i18n_make_a_selection_text'       => esc_attr__( 'Please select some product options before adding this product to your cart.', 'rigid' ),
					'i18n_unavailable_text'            => esc_attr__( 'Sorry, this product is unavailable. Please choose a different combination.', 'rigid' ),
				) );
		}

		$search_options = rigid_get_option('search_options');
		if (rigid_get_option('show_searchform') && $search_options['use_ajax']) {
			wp_localize_script('rigid-libs-config', 'rigid_ajax_search', array(
					'include' => 'true'
			));
		}

		if (RIGID_IS_WOOCOMMERCE && is_product()) {
			wp_localize_script('rigid-libs-config', 'rigid_variation_prod_cloudzoom', array(
					'include' => 'true',
			));
		}

		// Register video background plugin
		wp_register_style('ytplayer', get_template_directory_uri() . "/js/jquery.mb.YTPlayer-3.0.20/css/jquery.mb.YTPlayer.min.css");
		wp_register_script('ytplayer', get_template_directory_uri() . "/js/jquery.mb.YTPlayer-3.0.20/jquery.mb.YTPlayer.min.js", array('jquery'), '3.0.20', true);

		// Load video background plugin
		if ($to_include_backgr_video) {
			wp_enqueue_style('ytplayer');
			wp_enqueue_script('ytplayer');
			wp_localize_script('rigid-libs-config', 'rigid_ytplayer_conf', array(
					'include' => 'true',
			));
			// Load supersized plugin if a slider is set up
		} elseif ($to_include_supersized) {
			wp_enqueue_style('supersized', get_template_directory_uri() . "/js/supersized/css/supersized.css");
			wp_enqueue_script('supersized-min', get_template_directory_uri() . "/js/supersized/js/supersized.3.2.7.min.js", array('jquery'), '3.2.7', true);
			if ($to_include_supersized == 'postmeta') {
				wp_localize_script('rigid-libs-config', 'rigid_supersized_conf', array(
						'images' => rigid_has_post_supersized(),
				));
			} elseif ($to_include_supersized == 'global') {
				wp_localize_script('rigid-libs-config', 'rigid_supersized_conf', array(
						'images' => rigid_get_supersized_image_urls(esc_attr(rigid_get_option('supersized_images'))),
				));
			} elseif ($to_include_supersized == 'blog') {
				wp_localize_script('rigid-libs-config', 'rigid_supersized_conf', array(
						'images' => rigid_get_supersized_image_urls(esc_attr(rigid_get_option('blog_supersized_images'))),
				));
			} elseif (in_array($to_include_supersized, array('shop', 'shopwide'))) {
				wp_localize_script('rigid-libs-config', 'rigid_supersized_conf', array(
						'images' => rigid_get_supersized_image_urls(esc_attr(rigid_get_option('shop_supersized_images'))),
				));
			}
		}
	}

}

if (!function_exists('rigid_generate_excerpt')) {

	/**
	 * Return excerpt
	 *
	 * @param string $input	input to truncate
	 * @param number $limit	 number of chars to reach to tuncate
	 * @param string $break
	 * @param string $more more string
	 * @param boolean $strip_it strip tags
	 * @param string $exclude exclude tags
	 * @param boolean $safe_truncate use mb_strimwidth()
	 * @return string the generated excerpt
	 */
	function rigid_generate_excerpt($input, $limit, $break = ".", $more = "...", $strip_it = false, $exclude = '<strong><em><span>', $safe_truncate = false) {
		if ($strip_it) {
			$input = strip_shortcodes(strip_tags($input, $exclude));
		}

		if (strlen($input) <= $limit) {
			return $input;
		}

		$breakpoint = strpos($input, $break, $limit);

		if ($breakpoint != false) {
			if ($breakpoint < strlen($input) - 1) {
				if ($safe_truncate || is_rtl()) {
					$input = mb_strimwidth($input, 0, $breakpoint) . $more;
				} else {
					$input = substr($input, 0, $breakpoint) . $more;
				}
			}
		}

		// prevent accidental word break
		if (!$breakpoint && strlen(strip_tags($input)) == strlen($input)) {
			if ($safe_truncate || is_rtl()) {
				$input = mb_strimwidth($input, 0, $limit) . $more;
			} else {
				$input = substr($input, 0, $limit) . $more;
			}
		}

		return $input;
	}

}

if (!function_exists('rigid_get_option')) {

	/**
	 * Get Option.
	 *
	 * The function is in use
	 * This should be starting point when implementing skins
	 */
	function rigid_get_option($name, $default = false) {

		$option_name = 'rigid';

		// In case the option is in url return that value
		if ( array_key_exists( $name, $_GET ) ) {
			return esc_attr( $_GET[ $name ] );
		}

        // else get it from stored options
		if (get_option($option_name)) {
			$options = get_option($option_name);
		}

		if (isset($options[$name])) {
			return $options[$name];
		} else {
			$all_options_def = rigid_get_default_values();
			if (is_array($all_options_def) && isset($all_options_def[$name])) {
				return $all_options_def[$name];
			} else {
				return $default;
			}
		}
	}

}

if (!function_exists('rigid_has_to_include_supersized')) {

	/**
	 * Checks if supersized js plugin has to be included
	 * and returns the places that is should appear, or
	 * false if not.
	 *
	 * @global type $post
	 * @param type $is_compare
	 * @return string|boolean
	 */
	function rigid_has_to_include_supersized($is_compare = false) {

		if (!$is_compare) {
			// The post has supersized
			if (rigid_has_post_supersized()) {
				return 'postmeta';
				// If is blog page and supersized is set
			} elseif (rigid_is_blog() && rigid_get_option('show_blog_super_gallery') && rigid_get_option('blog_supersized_images')) {
				return 'blog';
				// If is shopwide
			} elseif (RIGID_IS_WOOCOMMERCE && is_woocommerce() && rigid_get_option('show_shop_super_gallery') && rigid_get_option('shopwide_super_gallery') && rigid_get_option('shop_supersized_images')) {
				return 'shopwide';
				// If is shop page and supersized is set
			} elseif (RIGID_IS_WOOCOMMERCE && is_shop() && rigid_get_option('show_shop_super_gallery') && rigid_get_option('shop_supersized_images')) {
				return 'shop';
				// If Global supersized is set
			} elseif (rigid_get_option('show_super_gallery') && rigid_get_option('supersized_images')) {
				return 'global';
			}
		}

		return false;
	}

}

if (!function_exists('rigid_has_to_include_backgr_video')) {

	/**
	 * Checks if video background js plugin has to be included
	 * and returns the places that is should appear, or
	 * false if not.
	 *
	 * @global type $post
	 * @param type $is_compare
	 * @return string|boolean
	 */
	function rigid_has_to_include_backgr_video($is_compare = false) {

		// The post has video background
		if (rigid_has_post_video_bckgr()) {
			return 'postmeta';
			// If is blog page and video background is set
		} elseif (rigid_is_blog() && rigid_get_option('show_blog_video_bckgr') && rigid_get_option('blog_video_bckgr_url')) {
			return 'blog';
			// If is shopwide
		} elseif (!$is_compare && RIGID_IS_WOOCOMMERCE && is_woocommerce() && rigid_get_option('show_shop_video_bckgr') && rigid_get_option('shopwide_video_bckgr') && rigid_get_option('shop_video_bckgr_url')) {
			return 'shopwide';
			// If is shop page and video background is set
		} elseif (!$is_compare && RIGID_IS_WOOCOMMERCE && is_shop() && rigid_get_option('show_shop_video_bckgr') && rigid_get_option('shop_video_bckgr_url')) {
			return 'shop';
			// If Global video background is set
		} elseif (!$is_compare && rigid_get_option('show_video_bckgr') && rigid_get_option('video_bckgr_url')) {
			return 'global';
		}

		return false;
	}

}

if (!function_exists('rigid_is_blog')) {

	/**
	 * Return true if this is the Blog page
	 * @return boolean
	 */
	function rigid_is_blog() {

		if (is_front_page() && is_home()) {
			return false;
		} elseif (is_front_page()) {
			return false;
		} elseif (is_home()) {
			// BLOG - return true
			return true;
		} else {
			return false;
		}
	}

}

add_action('after_switch_theme', 'rigid_redirect_to_options', 99);
if (!function_exists('rigid_redirect_to_options')) {

	// Redirect to theme options on theme activation
	function rigid_redirect_to_options() {
		wp_redirect(admin_url("themes.php?page=rigid-optionsframework"));
	}

}

add_action('init', 'rigid_mega_menu_init');
if (!function_exists('rigid_mega_menu_init')) {

	// Initialise RigidMegaMenu class
	function rigid_mega_menu_init() {
		$init_mega_menu = new RigidMegaMenu();
	}

}

add_filter('wp_nav_menu_args', 'rigid_set_menu_on_primary');
if (!function_exists('rigid_set_menu_on_primary')) {

	/**
	 * Set selected menu for 'top_menu' location
	 *
	 * @param Array $args
	 * @return Array
	 */
	function rigid_set_menu_on_primary($args) {
		if ($args['theme_location'] === 'primary') {
			if (rigid_is_blog()) {
				return rigid_set_menu_on_primary_helper($args, rigid_get_option('blog_top_menu'));
			}
			if (RIGID_IS_WOOCOMMERCE && is_shop()) {
				return rigid_set_menu_on_primary_helper($args, rigid_get_option('shop_top_menu'));
			}
			if (RIGID_IS_BBPRESS && bbp_is_forum_archive()) {
				return rigid_set_menu_on_primary_helper($args, rigid_get_option('forum_top_menu'));
			}
			if (RIGID_IS_EVENTS) {
				$mode_and_title = rigid_get_current_events_display_mode_and_title();
				$events_mode = $mode_and_title['display_mode'];
				if(in_array($events_mode, array('MAIN_CALENDAR', 'CALENDAR_CATEGORY', 'MAIN_EVENTS', 'CATEGORY_EVENTS', 'SINGLE_EVENT_DAYS'))) {
					return rigid_set_menu_on_primary_helper( $args, rigid_get_option( 'events_top_menu' ) );
				}
			}

			$chosen_menu = get_post_meta(get_the_ID(), 'rigid_top_menu', true);
			return rigid_set_menu_on_primary_helper($args, $chosen_menu);
		} else {
			return $args;
		}
	}

}

if (!function_exists('rigid_set_menu_on_primary_helper')) {

	/**
	 * Helper
	 *
	 * @param array $args
	 * @param string $chosen_menu
	 * @return string
	 */
	function rigid_set_menu_on_primary_helper($args, $chosen_menu) {
		if ('default' === $chosen_menu) {
			return $args;
		} else if ('none' === $chosen_menu) {
			$args['theme_location'] = 'rigid_none_existing_location';
			return $args;
		} else {
			$args['menu'] = (int) $chosen_menu;
			return $args;
		}
	}

}
/*
 * Check for sidebar
 */
add_filter('rigid_has_sidebar', 'rigid_check_for_sidebar');
if (!function_exists('rigid_check_for_sidebar')) {

	function rigid_check_for_sidebar() {

		$options = array();
		$is_cat_tag_tax_archive = false;

		if (is_category() || is_tag() || is_tax() || is_archive() || is_search() || (is_home())) {
			$is_cat_tag_tax_archive = true;
		}

		$blog_categoty_sidebar = rigid_get_option('blog_categoty_sidebar');
		$portfolio_categoty_sidebar = rigid_get_option('portfolio_categoty_sidebar');

		if (RIGID_IS_WOOCOMMERCE) {
			$woocommerce_sidebar = rigid_get_option('woocommerce_sidebar');
		}

		if (RIGID_IS_BBPRESS) {
			$bbpress_sidebar = rigid_get_option('bbpress_sidebar');
		}

		if(RIGID_IS_EVENTS) {
			$events_sidebar = rigid_get_option('events_sidebar');
		}

		if (is_single() || is_page()) {
			$options = get_post_custom(get_queried_object_id());
		}

		$show_sidebar_from_meta = 'yes';
		if (isset($options['rigid_show_sidebar']) && trim($options['rigid_show_sidebar'][0]) != '') {
			$show_sidebar_from_meta = $options['rigid_show_sidebar'][0];
		}

		$sidebar_choice = 'none';

		if (RIGID_IS_WOOCOMMERCE && is_woocommerce() && isset($woocommerce_sidebar)) {
			$sidebar_choice = $woocommerce_sidebar;
		} elseif (RIGID_IS_BBPRESS && is_bbpress() && isset($bbpress_sidebar) && (empty($options) || (isset($options['rigid_custom_sidebar']) && $options['rigid_custom_sidebar'][0] == 'default' && $show_sidebar_from_meta == 'yes'))) {
			$sidebar_choice = $bbpress_sidebar;
		} elseif ( RIGID_IS_EVENTS && rigid_is_events_part() && isset($events_sidebar) && ( empty($options) || ( isset($options['rigid_custom_sidebar']) && $options['rigid_custom_sidebar'][0] == 'default' && $show_sidebar_from_meta == 'yes'))) {
			$sidebar_choice = $events_sidebar;
		} elseif (is_tax('rigid_portfolio_category') || is_post_type_archive('rigid-portfolio')) {
			$sidebar_choice = $portfolio_categoty_sidebar;
		} elseif ($is_cat_tag_tax_archive) {
			$sidebar_choice = $blog_categoty_sidebar;
		} elseif (isset($options['rigid_custom_sidebar']) && $show_sidebar_from_meta == 'yes') {
			if ($options['rigid_custom_sidebar'][0] == 'default') {
				$sidebar_choice = 'right_sidebar';
			} else {
				$sidebar_choice = $options['rigid_custom_sidebar'][0];
			}
		} else {
			$sidebar_choice = 'none';
		}

		return $sidebar_choice;
	}

}

/*
 * Check for sidebar
 */
add_filter('rigid_has_offcanvas_sidebar', 'rigid_check_for_offcanvas_sidebar');
if (!function_exists('rigid_check_for_offcanvas_sidebar')) {

	function rigid_check_for_offcanvas_sidebar() {
		$meta_options = array();
		if (is_single() || is_page()) {
			$meta_options = get_post_custom(get_queried_object_id());
		}

		if (isset($meta_options['rigid_show_offcanvas_sidebar']) && trim($meta_options['rigid_show_offcanvas_sidebar'][0]) === 'no') {
			return 'none';
		}

		$offcanvas_sidebar_choice = rigid_get_option('offcanvas_sidebar');
		if (isset($meta_options['rigid_custom_offcanvas_sidebar']) && $meta_options['rigid_custom_offcanvas_sidebar'][0] !== 'default') {
			$offcanvas_sidebar_choice = $meta_options['rigid_custom_offcanvas_sidebar'][0];
		}

		return $offcanvas_sidebar_choice;
	}

}

add_filter('rigid_left_sidebar_position_class', 'rigid_check_for_sidebar_position');
if (!function_exists('rigid_check_for_sidebar_position')) {

	/**
	 * Check position of sidebar
	 *
	 * @return string - Empty string for left and the class name for right
	 */
	function rigid_check_for_sidebar_position() {
		$meta_options = array();
		if (is_single() || is_page()) {
			$meta_options = get_post_custom(get_queried_object_id());
		}

		$sidebar_position = rigid_get_option('sidebar_position');
		if (isset($meta_options['rigid_sidebar_position']) && $meta_options['rigid_sidebar_position'][0] !== 'default') {
			$sidebar_position = $meta_options['rigid_sidebar_position'][0];
		}

		return $sidebar_position;
	}

}


if (!function_exists('rigid_get_choose_menu_options')) {

	/**
	 * Get options to use for choose menu select
	 *
	 * @return Array
	 */
	function rigid_get_choose_menu_options() {
		$registered_menus = wp_get_nav_menus();
		$choose_menu_options = array(
				'none' => esc_html__('- No menu -', 'rigid'),
				'default' => esc_html__('- Use global set top menu -', 'rigid')
		);

		foreach ($registered_menus as $menu) {
			$choose_menu_options[$menu->term_id] = $menu->name;
		}

		return $choose_menu_options;
	}

}

// Disable BBPress breadcrumb
add_filter('bbp_no_breadcrumb', '__return_true');

if ( ! function_exists( 'rigid_get_current_events_display_mode_and_title' ) ) {

	/**
	 * Returns current events display mode and page title specific for the Events Calendar Plugin
	 *
	 * @param $id int  post/page id
	 *
	 * @return array Array[display_mode, title]
	 */
	function rigid_get_current_events_display_mode_and_title( $id = 0 ) {

		if ( $id == 0 ) {
			global $wp_query;

			if ( isset($wp_query->post) ) {
				$id = $wp_query->post->ID;
			}
		}

		$return_arr = array(
			'display_mode' => '',
			'title'        => ''
		);

		// If Event calendar is active follow the procedure to display the title
		if ( function_exists( 'tribe_is_month' ) ) {
			if ( tribe_is_month() && ! is_tax('', $id) ) { // The Main Calendar Page
				if ( rigid_get_option( 'events_title' ) ) {
					$title = rigid_get_option( 'events_title' );
				} else {
					$title = esc_html__( 'The Main Calendar', 'rigid' );
				}
				$mode = 'MAIN_CALENDAR';
			} elseif ( tribe_is_month() && is_tax('', $id) ) { // Calendar Category Pages
				$title = esc_html__( 'Calendar Category', 'rigid' ) . ': ' . tribe_meta_event_category_name();
				$mode  = 'CALENDAR_CATEGORY';
			} elseif ( tribe_is_event( $id ) && ! tribe_is_day() && ! is_singular() && ! is_tax('', $id) ) { // The Main Events List
				if ( rigid_get_option( 'events_title' ) ) {
					$title = rigid_get_option( 'events_title' );
				} else {
					$title = esc_html__( 'Events List', 'rigid' );
				}
				$mode = 'MAIN_EVENTS';
			} elseif ( tribe_is_event( $id ) && ! tribe_is_day() && ! is_singular() && is_tax('', $id) ) { // Category Events List
				$title = esc_html__( 'Events List', 'rigid' ) . ': ' . tribe_meta_event_category_name();
				$mode  = 'CATEGORY_EVENTS';
			} elseif ( tribe_is_event( $id ) && is_singular() ) { // Single Events
				$title = get_the_title( $id );
				$mode  = 'SINGLE_EVENTS';
			} elseif ( tribe_is_day() ) { // Single Event Days
				$title = esc_html__( 'Events on', 'rigid' ) . ': ' . date( 'F j, Y', strtotime( get_query_var( 'eventDate' ) ) );
				$mode  = 'SINGLE_EVENT_DAYS';
			} elseif ( tribe_is_venue( $id ) ) { // Single Venues
				$title = get_the_title( $id );
				$mode  = 'VENUE';
			} else {
				$title = get_the_title( $id );
				$mode  = '';
			}
		} else {
			$title = get_the_title( $id );
			$mode  = '';
		}

		$return_arr['title']        = $title;
		$return_arr['display_mode'] = $mode;

		return $return_arr;
	}
}

if ( ! function_exists( 'rigid_is_events_part' ) ) {

	/**
	 * Detect if we are on an Events Calendar page
	 *
	 * @return bool
	 */
	function rigid_is_events_part() {

		if ( RIGID_IS_EVENTS && function_exists( 'tribe_is_event' ) && ( tribe_is_month() || tribe_is_event() || tribe_is_event_category() || tribe_is_in_main_loop() || tribe_is_view() || 'tribe_events' == get_post_type() || is_singular( 'tribe_events' ) ) ) {
			return true;
		}

		return false;
	}
}


/**
 * Strip the "script" tag from given string
 * Used for inline js code given to wp_add_inline_script
 *
 * @param string $source JS source code
 *
 * @return string The string without "script" tag
 */
function rigid_strip_script_tag_from_js_block( $source ) {
	return trim( preg_replace( '#<script[^>]*>(.*)</script>#is', '$1', $source ) );
}

if ( ! function_exists('rigid_write_log')) {
	function rigid_write_log ( $log )  {
		if ( is_array( $log ) || is_object( $log ) ) {
			error_log( print_r( $log, true ) );
		} else {
			error_log( $log );
		}
	}
}