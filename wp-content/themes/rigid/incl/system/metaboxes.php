<?php

/**
 * Register page layout metaboxes
 */
add_action('add_meta_boxes', 'rigid_add_layout_metabox');
add_action('save_post', 'rigid_save_layout_postdata');

/* Adds a box to the side column on the Page edit screens */
if (!function_exists('rigid_add_layout_metabox')) {

	function rigid_add_layout_metabox() {

		$posttypes = array('page', 'post', 'rigid-portfolio');
		if (RIGID_IS_WOOCOMMERCE) {
			$posttypes[] = 'product';
		}
		if (RIGID_IS_BBPRESS) {
			$posttypes[] = 'forum';
			$posttypes[] = 'topic';
		}
		if(post_type_exists('tribe_events')) {
			$posttypes[] = 'tribe_events';
		}

		foreach ($posttypes as $pt) {
			add_meta_box(
							'rigid_layout', esc_html__('Page Layout Options', 'rigid'), 'rigid_layout_callback', $pt, 'side'
			);
		}
	}

}

/* Prints the box content */
if (!function_exists('rigid_layout_callback')) {

	function rigid_layout_callback($post) {
		// If current page is set as Blog page - don't show the options
		if ($post->ID == get_option('page_for_posts')) {
			echo esc_html__("Page Layout Options is disabled for this page, because the page is set as Blog page from Settings->Reading.", 'rigid');
			return;
		}

		// If current page is set as Shop page - don't show the options
		if (RIGID_IS_WOOCOMMERCE && $post->ID == wc_get_page_id('shop')) {
			echo esc_html__("Page Layout Options is disabled for this page, because the page is set as Shop page.", 'rigid');
			return;
		}

		// Use nonce for verification
		wp_nonce_field('rigid_save_layout_postdata', 'layout_nonce');

		$custom = get_post_custom($post->ID);

		// Set default values
		$values = array(
				'rigid_layout' => 'default',
				'rigid_top_header' => 'default',
				'rigid_footer_style' => 'default',
				'rigid_footer_size' => 'default',
				'rigid_header_size' => 'default',
				'rigid_header_syle' => '',
				'rigid_page_subtitle' => '',
				'rigid_title_background_imgid' => '',
				'rigid_title_alignment' => 'left_title'
		);

		if (isset($custom['rigid_layout']) && $custom['rigid_layout'][0] != '') {
			$values['rigid_layout'] = esc_attr($custom['rigid_layout'][0]);
		}
		if (isset($custom['rigid_top_header']) && $custom['rigid_top_header'][0] != '') {
			$values['rigid_top_header'] = esc_attr($custom['rigid_top_header'][0]);
		}
		if (isset($custom['rigid_footer_style']) && $custom['rigid_footer_style'][0] != '') {
			$values['rigid_footer_style'] = esc_attr($custom['rigid_footer_style'][0]);
		}
		if (isset($custom['rigid_footer_size']) && $custom['rigid_footer_size'][0] != '') {
			$values['rigid_footer_size'] = esc_attr($custom['rigid_footer_size'][0]);
		}
		if (isset($custom['rigid_header_size']) && $custom['rigid_header_size'][0] != '') {
			$values['rigid_header_size'] = esc_attr($custom['rigid_header_size'][0]);
		}
		if (isset($custom['rigid_header_syle']) && $custom['rigid_header_syle'][0] != '') {
			$values['rigid_header_syle'] = esc_attr($custom['rigid_header_syle'][0]);
		}
		if (isset($custom['rigid_page_subtitle']) && $custom['rigid_page_subtitle'][0] != '') {
			$values['rigid_page_subtitle'] = esc_attr($custom['rigid_page_subtitle'][0]);
		}
		if (isset($custom['rigid_title_background_imgid']) && $custom['rigid_title_background_imgid'][0] != '') {
			$values['rigid_title_background_imgid'] = esc_attr($custom['rigid_title_background_imgid'][0]);
		}
		if (isset($custom['rigid_title_alignment']) && $custom['rigid_title_alignment'][0] != '') {
			$values['rigid_title_alignment'] = esc_attr($custom['rigid_title_alignment'][0]);
		}

		// description
		$output = '<p>' . esc_html__("You can define layout specific options here.", 'rigid') . '</p>';

		// Layout
		$output .= '<p><b>' . esc_html__("Choose Page Layout", 'rigid') . '</b></p>';
		$output .= '<input id="rigid_layout_default" ' . checked($values['rigid_layout'], 'default', false) . ' type="radio" value="default" name="rigid_layout">';
		$output .= '<label for="rigid_layout_default">' . esc_html__('Default', 'rigid') . '</label><br>';
		$output .= '<input id="rigid_layout_fullwidth" ' . checked($values['rigid_layout'], 'rigid_fullwidth', false) . ' type="radio" value="rigid_fullwidth" name="rigid_layout">';
		$output .= '<label for="rigid_layout_fullwidth">' . esc_html__('Full-Width', 'rigid') . '</label><br>';
		$output .= '<input id="rigid_layout_boxed" ' . checked($values['rigid_layout'], 'rigid_boxed', false) . ' type="radio" value="rigid_boxed" name="rigid_layout">';
		$output .= '<label for="rigid_layout_boxed">' . esc_html__('Boxed', 'rigid') . '</label><br>';
		$output .= '<input id="rigid_layout_left_header" ' . checked($values['rigid_layout'], 'rigid_header_left', false) . ' type="radio" value="rigid_header_left" name="rigid_layout">';
		$output .= '<label for="rigid_layout_left_header">' . esc_html__('Left Header', 'rigid') . '</label>';

		// Top Menu Bar
		$output .= '<p><b>' . esc_html__("Top Menu Bar", 'rigid') . '</b></p>';
		$output .= '<input id="rigid_top_header_default" ' . checked($values['rigid_top_header'], 'default', false) . ' type="radio" value="default" name="rigid_top_header">';
		$output .= '<label for="rigid_top_header_default">' . esc_html__('Default', 'rigid') . '</label>&nbsp;';
		$output .= '<input id="rigid_top_header_show" ' . checked($values['rigid_top_header'], 'show', false) . ' type="radio" value="show" name="rigid_top_header">';
		$output .= '<label for="rigid_top_header_show">' . esc_html__('Show', 'rigid') . '</label>&nbsp;';
		$output .= '<input id="rigid_top_header_hide" ' . checked($values['rigid_top_header'], 'hide', false) . ' type="radio" value="hide" name="rigid_top_header">';
		$output .= '<label for="rigid_top_header_hide">' . esc_html__('Hide', 'rigid') . '</label>';

		// Footer Size
		$output .= '<p><b>' . esc_html__("Footer size", 'rigid') . '</b></p>';
		$output .= '<input id="rigid_footer_size_default" ' . checked($values['rigid_footer_size'], 'default', false) . ' type="radio" value="default" name="rigid_footer_size">';
		$output .= '<label for="rigid_footer_size_default">' . esc_html__('Default', 'rigid') . '</label>&nbsp;';
		$output .= '<input id="rigid_footer_size_standard" ' . checked($values['rigid_footer_size'], 'standard', false) . ' type="radio" value="standard" name="rigid_footer_size">';
		$output .= '<label for="rigid_footer_size_standard">' . esc_html__('Standard', 'rigid') . '</label>&nbsp;';
		$output .= '<input id="rigid_footer_size_hide" ' . checked($values['rigid_footer_size'], 'rigid-stretched-footer', false) . ' type="radio" value="rigid-stretched-footer" name="rigid_footer_size">';
		$output .= '<label for="rigid_footer_size_hide">' . esc_html__('Fullwidth', 'rigid') . '</label>';

		// Footer Style
		$output .= '<p><b>' . esc_html__("Footer style", 'rigid') . '</b></p>';
		$output .= '<input id="rigid_footer_style_default" ' . checked($values['rigid_footer_style'], 'default', false) . ' type="radio" value="default" name="rigid_footer_style">';
		$output .= '<label for="rigid_footer_style_default">' . esc_html__('Default', 'rigid') . '</label>&nbsp;';
		$output .= '<input id="rigid_footer_style_show" ' . checked($values['rigid_footer_style'], 'standart', false) . ' type="radio" value="standart" name="rigid_footer_style">';
		$output .= '<label for="rigid_footer_style_show">' . esc_html__('Standard', 'rigid') . '</label>&nbsp;';
		$output .= '<input id="rigid_footer_style_hide" ' . checked($values['rigid_footer_style'], 'rigid-reveal-footer', false) . ' type="radio" value="rigid-reveal-footer" name="rigid_footer_style">';
		$output .= '<label for="rigid_footer_style_hide">' . esc_html__('Reveal', 'rigid') . '</label>';

		// Header Size
		$output .= '<p><b>' . esc_html__("Header size", 'rigid') . '</b></p>';
		$output .= '<input id="rigid_header_size_default" ' . checked($values['rigid_header_size'], 'default', false) . ' type="radio" value="default" name="rigid_header_size">';
		$output .= '<label for="rigid_header_size_default">' . esc_html__('Default', 'rigid') . '</label>&nbsp;';
		$output .= '<input id="rigid_header_size_standard" ' . checked($values['rigid_header_size'], 'standard', false) . ' type="radio" value="standard" name="rigid_header_size">';
		$output .= '<label for="rigid_header_size_standard">' . esc_html__('Standard', 'rigid') . '</label>&nbsp;';
		$output .= '<input id="rigid_header_size_hide" ' . checked($values['rigid_header_size'], 'rigid-stretched-header', false) . ' type="radio" value="rigid-stretched-header" name="rigid_header_size">';
		$output .= '<label for="rigid_header_size_hide">' . esc_html__('Fullwidth', 'rigid') . '</label>';

		// Transparent header and Title with Image Background (only on posts, pages, forum, portfolio and topic)
		$screen = get_current_screen();
		if ($screen && in_array($screen->post_type, array('post', 'page', 'forum', 'topic', 'rigid-portfolio', 'tribe_events', 'product'), true)) {
			// Header style header
			$output .= '<p><b>' . esc_html__("Header Style", 'rigid') . '</b></p>';
			$output .= '<p><label for="rigid_header_syle">';

			$output .= "<select name='rigid_header_syle'>";
			// Add a default option
			$output .= "<option";
			if ($values['rigid_header_syle'] === '') {
				$output .= " selected='selected'";
			}
			$output .= " value=''>" . esc_html__('Normal', 'rigid') . "</option>";

			// Fill the select element
			$header_style_values = array(
					'rigid_transparent_header' => esc_html__('Transparent - Light Scheme', 'rigid'),
					'rigid_transparent_header rigid-transparent-dark' => esc_html__('Transparent - Dark Scheme', 'rigid'),
					'rigid-overlay-header' => esc_html__('Overlay', 'rigid')
			);

			foreach ($header_style_values as $header_style_val => $header_style_option) {
				$output .= "<option";
				if ($header_style_val === $values['rigid_header_syle']) {
					$output .= " selected='selected'";
				}
				$output .= " value='" . esc_attr($header_style_val) . "'>" . esc_html($header_style_option) . "</option>";
			}

			$output .= "</select>";

			// The image
			$image_id = get_post_meta(
				$post->ID, 'rigid_title_background_imgid', true
			);

			$add_link_style = '';
			$del_link_style = '';

			$output .= '<p class="hide-if-no-js">';
			$output .= '<span id="rigid_title_background_imgid_images" class="rigid_featured_img_holder">';

			if ( $image_id ) {
				$add_link_style = 'style="display:none"';
				$output .= wp_get_attachment_image( $image_id, 'medium' );
			} else {
				$del_link_style = 'style="display:none"';
			}

			$output .= '</span>';
			$output .= '</p>';
			$output .= '<p class="hide-if-no-js">';
			$output .= '<input id="rigid_title_background_imgid" name="rigid_title_background_imgid" type="hidden" value="' . esc_attr( $image_id ) . '" />';
			$output .= '<input type="button" value="' . esc_attr__( 'Manage Images', 'rigid' ) . '" id="upload_rigid_title_background_imgid" class="rigid_upload_image_button" data-uploader_title="' . esc_attr__( 'Select Title Background Image', 'rigid' ) . '" data-uploader_button_text="' . esc_attr__( 'Select', 'rigid' ) . '">';
			$output .= '</p>';

			// Below is not for product
			if($screen->post_type != 'product') {

				$output .= '<p><label for="rigid_page_subtitle">' . esc_html__( "Page Subtitle", 'rigid' ) . '</label></p>';
				$output .= '<input type="text" id="rigid_page_subtitle" name="rigid_page_subtitle" value="' . esc_attr( $values['rigid_page_subtitle'] ) . '" class="large-text" />';
				$output .= '<p><label for="rigid_title_alignment">' . esc_html__( "Title alignment", 'rigid' ) . '</label></p>';
				$output .= '<select name="rigid_title_alignment">';
				$output .= '<option ' . ( $values['rigid_title_alignment'] == 'left_title' ? 'selected="selected"' : '' ) . ' value="left_title">Left</option>';
				$output .= '<option ' . ( $values['rigid_title_alignment'] == 'centered_title' ? 'selected="selected"' : '' ) . ' value="centered_title">Center</option>';
				$output .= '</select>';
			}
		}

		echo $output; // All dynamic data escaped
	}

}

/* When the post is saved, saves our custom data */
if (!function_exists('rigid_save_layout_postdata')) {

	function rigid_save_layout_postdata($post_id) {
		global $pagenow;

		// verify if this is an auto save routine.
		// If it is our form has not been submitted, so we dont want to do anything
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		// verify this came from our screen and with proper authorization,
		// because save_post can be triggered at other times
		if (isset($_POST['layout_nonce']) && !wp_verify_nonce($_POST['layout_nonce'], 'rigid_save_layout_postdata')) {
			return;
		}

		if (!current_user_can('edit_pages', $post_id)) {
			return;
		}

		if ('post-new.php' == $pagenow) {
			return;
		}

		if (isset($_POST['rigid_layout'])) {
			update_post_meta($post_id, "rigid_layout", sanitize_text_field($_POST['rigid_layout']));
		}

		if (isset($_POST['rigid_top_header'])) {
			update_post_meta($post_id, "rigid_top_header", sanitize_text_field($_POST['rigid_top_header']));
		}

		if (isset($_POST['rigid_footer_style'])) {
			update_post_meta($post_id, "rigid_footer_style", sanitize_text_field($_POST['rigid_footer_style']));
		}

		if (isset($_POST['rigid_footer_size'])) {
			update_post_meta($post_id, "rigid_footer_size", sanitize_text_field($_POST['rigid_footer_size']));
		}

		if (isset($_POST['rigid_header_size'])) {
			update_post_meta($post_id, "rigid_header_size", sanitize_text_field($_POST['rigid_header_size']));
		}

		if (isset($_POST['rigid_page_subtitle'])) {
			update_post_meta($post_id, "rigid_page_subtitle", sanitize_text_field($_POST['rigid_page_subtitle']));
		}

		if (isset($_POST['rigid_header_syle'])) {
			update_post_meta($post_id, "rigid_header_syle", sanitize_text_field($_POST['rigid_header_syle']));
		}

		if (isset($_POST['rigid_title_background_imgid'])) {
			update_post_meta($post_id, 'rigid_title_background_imgid', sanitize_text_field($_POST['rigid_title_background_imgid']));
		}

		if (isset($_POST['rigid_title_alignment'])) {
			update_post_meta($post_id, 'rigid_title_alignment', sanitize_text_field($_POST['rigid_title_alignment']));
		}
	}

}

/**
 * Register metaboxes
 */
add_action('add_meta_boxes', 'rigid_add_page_options_metabox');
add_action('save_post', 'rigid_save_page_options_postdata');

/* Adds a box to the side column on the Page edit screens */
if (!function_exists('rigid_add_page_options_metabox')) {

	function rigid_add_page_options_metabox() {

		$posttypes = array('page', 'post', 'rigid-portfolio', 'tribe_events');

		if (RIGID_IS_BBPRESS) {
			$posttypes[] = 'forum';
			$posttypes[] = 'topic';
		}
		if(post_type_exists('tribe_events')) {
			$posttypes[] = 'tribe_events';
		}

		foreach ($posttypes as $pt) {
			add_meta_box(
							'rigid_page_options', esc_html__('Page Structure Options', 'rigid'), 'rigid_page_options_callback', $pt, 'side'
			);
		}
	}

}

/* Prints the box content */
if (!function_exists('rigid_page_options_callback')) {

	function rigid_page_options_callback($post) {
		// If current page is set as Blog page - don't show the options
		if ($post->ID == get_option('page_for_posts')) {
			echo esc_html__("Page Structure Options are disabled for this page, because the page is set as Blog page from Settings->Reading.", 'rigid');
			return;
		}
		// If current page is set as Shop page - don't show the options
		if (RIGID_IS_WOOCOMMERCE && $post->ID == wc_get_page_id('shop')) {
			echo esc_html__("Page Structure Options are disabled for this page, because the page is set as Shop page.", 'rigid');
			return;
		}

		// Use nonce for verification
		wp_nonce_field('rigid_save_page_options_postdata', 'page_options_nonce');
		global $wp_registered_sidebars;

		$custom = get_post_custom($post->ID);

		// Set default values
		$values = array(
				'rigid_top_menu' => 'default',
				'rigid_show_title_page' => 'yes',
				'rigid_show_breadcrumb' => 'yes',
				'rigid_show_feat_image_in_post' => 'yes',
				'rigid_show_sidebar' => 'yes',
				'rigid_sidebar_position' => 'default',
				'rigid_show_footer_sidebar' => 'yes',
				'rigid_show_offcanvas_sidebar' => 'yes',
				'rigid_show_share' => 'default',
				'rigid_custom_sidebar' => 'default',
				'rigid_custom_footer_sidebar' => 'default',
				'rigid_custom_offcanvas_sidebar' => 'default'
		);

		if (isset($custom['rigid_top_menu']) && $custom['rigid_top_menu'][0] != '') {
			$values['rigid_top_menu'] = $custom['rigid_top_menu'][0];
		}
		if (isset($custom['rigid_show_title_page']) && $custom['rigid_show_title_page'][0] != '') {
			$values['rigid_show_title_page'] = $custom['rigid_show_title_page'][0];
		}
		if (isset($custom['rigid_show_breadcrumb']) && $custom['rigid_show_breadcrumb'][0] != '') {
			$values['rigid_show_breadcrumb'] = $custom['rigid_show_breadcrumb'][0];
		}
		if (isset($custom['rigid_show_feat_image_in_post']) && $custom['rigid_show_feat_image_in_post'][0] != '') {
			$values['rigid_show_feat_image_in_post'] = $custom['rigid_show_feat_image_in_post'][0];
		}
		if (isset($custom['rigid_show_sidebar']) && $custom['rigid_show_sidebar'][0] != '') {
			$values['rigid_show_sidebar'] = $custom['rigid_show_sidebar'][0];
		}
		if (isset($custom['rigid_sidebar_position']) && $custom['rigid_sidebar_position'][0] != '') {
			$values['rigid_sidebar_position'] = $custom['rigid_sidebar_position'][0];
		}
		if (isset($custom['rigid_show_footer_sidebar']) && $custom['rigid_show_footer_sidebar'][0] != '') {
			$values['rigid_show_footer_sidebar'] = $custom['rigid_show_footer_sidebar'][0];
		}
		if (isset($custom['rigid_show_offcanvas_sidebar']) && $custom['rigid_show_offcanvas_sidebar'][0] != '') {
			$values['rigid_show_offcanvas_sidebar'] = $custom['rigid_show_offcanvas_sidebar'][0];
		}
		if (isset($custom['rigid_show_share']) && $custom['rigid_show_share'][0] != '') {
			$values['rigid_show_share'] = $custom['rigid_show_share'][0];
		}
		if (isset($custom['rigid_custom_sidebar']) && $custom['rigid_custom_sidebar'][0] != '') {
			$values['rigid_custom_sidebar'] = $custom['rigid_custom_sidebar'][0];
		}
		if (isset($custom['rigid_custom_footer_sidebar']) && $custom['rigid_custom_footer_sidebar'][0] != '') {
			$values['rigid_custom_footer_sidebar'] = $custom['rigid_custom_footer_sidebar'][0];
		}
		if (isset($custom['rigid_custom_offcanvas_sidebar']) && $custom['rigid_custom_offcanvas_sidebar'][0] != '') {
			$values['rigid_custom_offcanvas_sidebar'] = $custom['rigid_custom_offcanvas_sidebar'][0];
		}

		// description
		$output = '<p>' . esc_html__("You can configure the page structure, using this options.", 'rigid') . '</p>';

		// Top Menu
		$choose_menu_options = rigid_get_choose_menu_options();
		$output .= '<p><label for="rigid_top_menu"><b>' . esc_html__("Choose Top Menu", 'rigid') . '</b></label></p>';
		$output .= "<select name='rigid_top_menu'>";
		// Add a default option
		foreach ($choose_menu_options as $key => $val) {
			$output .= "<option value='" . esc_attr($key) . "' " . esc_attr(selected($values['rigid_top_menu'], $key, false)) . " >" . esc_html($val) . "</option>";
		}
		$output .= "</select>";

		// Show title
		$output .= '<p><label for="rigid_show_title_page"><b>' . esc_html__("Show Title", 'rigid') . '</b></label></p>';
		$output .= '<input id="rigid_show_title_page_yes" ' . checked($values['rigid_show_title_page'], 'yes', false) . ' type="radio" value="yes" name="rigid_show_title_page">';
		$output .= '<label for="rigid_show_title_page_yes">Yes </label>&nbsp;';
		$output .= '<input id="rigid_show_title_page_no" ' . checked($values['rigid_show_title_page'], 'no', false) . ' type="radio" value="no" name="rigid_show_title_page">';
		$output .= '<label for="rigid_show_title_page_no">No</label>';

		// Show breadcrumb
		$output .= '<p><label for="rigid_show_breadcrumb"><b>' . esc_html__("Show Breadcrumb", 'rigid') . '</b></label></p>';
		$output .= "<input id='rigid_show_breadcrumb_yes' " . checked($values['rigid_show_breadcrumb'], 'yes', false) . " type='radio' value='yes' name='rigid_show_breadcrumb'>";
		$output .= '<label for="rigid_show_breadcrumb_yes">Yes </label>&nbsp;';
		$output .= '<input id="rigid_show_breadcrumb_no" ' . checked($values['rigid_show_breadcrumb'], 'no', false) . ' type="radio" value="no" name="rigid_show_breadcrumb">';
		$output .= '<label for="rigid_show_breadcrumb_no">No</label>';

		// Show featured image inside post in single post view
		$screen = get_current_screen();
		if ($screen && in_array($screen->post_type, array('post'), true)) {
			$output .= '<p><label for="rigid_show_feat_image_in_post"><b>' . esc_html__( "Featured Image in Single Post View", 'rigid' ) . '</b></label></p>';
			$output .= '<input id="rigid_show_feat_image_in_post_yes" ' . checked( $values['rigid_show_feat_image_in_post'], 'yes', false ) . ' type="radio" value="yes" name="rigid_show_feat_image_in_post">';
			$output .= '<label for="rigid_show_feat_image_in_post_yes">Yes </label>&nbsp;';
			$output .= '<input id="rigid_show_feat_image_in_post_no" ' . checked( $values['rigid_show_feat_image_in_post'], 'no', false ) . ' type="radio" value="no" name="rigid_show_feat_image_in_post">';
			$output .= '<label for="rigid_show_feat_image_in_post_no">No</label>';
		}

		// Show share
		$output .= '<p><label for="rigid_show_share"><b>' . esc_html__("Show Social Share Links", 'rigid') . '</b></label></p>';
		$output .= '<input id="rigid_show_share_default" ' . checked($values['rigid_show_share'], 'default', false) . ' type="radio" value="default" name="rigid_show_share">';
		$output .= '<label for="rigid_show_share_default">' . esc_html__('Default', 'rigid') . '</label>&nbsp;';
		$output .= '<input id="rigid_show_share_yes" ' . checked($values['rigid_show_share'], 'yes', false) . ' type="radio" value="yes" name="rigid_show_share">';
		$output .= '<label for="rigid_show_share_yes">Yes </label>&nbsp;';
		$output .= '<input id="rigid_show_share_no" ' . checked($values['rigid_show_share'], 'no', false) . ' type="radio" value="no" name="rigid_show_share">';
		$output .= '<label for="rigid_show_share_no">No</label>';

		// Show Main sidebar
		$output .= '<p><label for="rigid_show_sidebar"><b>' . esc_html__("Main Sidebar", 'rigid') . '</b></label></p>';
		$output .= '<input id="rigid_show_sidebar_yes" ' . checked($values['rigid_show_sidebar'], 'yes', false) . ' type="radio" value="yes" name="rigid_show_sidebar">';
		$output .= '<label for="rigid_show_sidebar_yes">Show </label>&nbsp;';
		$output .= '<input id="rigid_show_sidebar_no" ' . checked($values['rigid_show_sidebar'], 'no', false) . ' type="radio" value="no" name="rigid_show_sidebar">';
		$output .= '<label for="rigid_show_sidebar_no">Hide </label>';

		// Select Main sidebar
		$output .= "<select name='rigid_custom_sidebar'>";
		// Add a default option
		$output .= "<option";
		if ($values['rigid_custom_sidebar'] == "default") {
			$output .= " selected='selected'";
		}
		$output .= " value='default'>" . esc_html__('default', 'rigid') . "</option>";

		// Fill the select element with all registered sidebars
		foreach ($wp_registered_sidebars as $sidebar_id => $sidebar) {
			if ($sidebar_id != 'bottom_footer_sidebar' && $sidebar_id != 'pre_header_sidebar') {
				$output .= "<option";
				if ($sidebar_id == $values['rigid_custom_sidebar']) {
					$output .= " selected='selected'";
				}
				$output .= " value='" . esc_attr($sidebar_id) . "'>" . esc_html($sidebar['name']) . "</option>";
			}
		}

		$output .= "</select>";

		// Main Sidebar Position
		$output .= '<p><label for="rigid_sidebar_position"><b>' . esc_html__("Main Sidebar Position", 'rigid') . '</b></label></p>';
		$output .= '<select name="rigid_sidebar_position">';
		$output .= '<option value="default" '.esc_attr(selected($values['rigid_sidebar_position'], 'default', false)).' >' . esc_html__("default", 'rigid') . '</option>';
		$output .= '<option value="rigid-left-sidebar" '.esc_attr(selected($values['rigid_sidebar_position'], 'rigid-left-sidebar', false)).'>' . esc_html__("Left", 'rigid') . '</option>';
		$output .= '<option value="rigid-right-sidebar" '.esc_attr(selected($values['rigid_sidebar_position'], 'rigid-right-sidebar', false)).'>' . esc_html__("Right", 'rigid') . '</option>';
		$output .= '</select>';

		// Show offcanvas sidebar
		$output .= '<p><label for="rigid_show_offcanvas_sidebar"><b>' . esc_html__("Off Canvas Sidebar", 'rigid') . '</b></label></p>';
		$output .= '<input id="rigid_show_offcanvas_sidebar_yes" ' . checked($values['rigid_show_offcanvas_sidebar'], 'yes', false) . ' type="radio" value="yes" name="rigid_show_offcanvas_sidebar">';
		$output .= '<label for="rigid_show_offcanvas_sidebar_yes">Show </label>&nbsp;';
		$output .= '<input id="rigid_show_offcanvas_sidebar_no" ' . checked($values['rigid_show_offcanvas_sidebar'], 'no', false) . ' type="radio" value="no" name="rigid_show_offcanvas_sidebar">';
		$output .= '<label for="rigid_show_offcanvas_sidebar_no">Hide </label>';

		// Select offcanvas sidebar
		$output .= "<select name='rigid_custom_offcanvas_sidebar'>";

		// Add a default option
		$output .= "<option";
		if ($values['rigid_custom_offcanvas_sidebar'] == "default") {
			$output .= " selected='selected'";
		}
		$output .= " value='default'>" . esc_html__('default', 'rigid') . "</option>";

		// Fill the select element with all registered sidebars
		foreach ($wp_registered_sidebars as $sidebar_id => $sidebar) {
			if ($sidebar_id != 'pre_header_sidebar') {
				$output .= "<option";
				if ($sidebar_id == $values['rigid_custom_offcanvas_sidebar']) {
					$output .= " selected='selected'";
				}
				$output .= " value='" . esc_attr($sidebar_id) . "'>" . esc_html($sidebar['name']) . "</option>";
			}
		}

		$output .= "</select>";

		// Show footer sidebar
		$output .= '<p><label for="rigid_show_footer_sidebar"><b>' . esc_html__("Footer Sidebar", 'rigid') . '</b></label></p>';
		$output .= '<input id="rigid_show_footer_sidebar_yes" ' . checked($values['rigid_show_footer_sidebar'], 'yes', false) . ' type="radio" value="yes" name="rigid_show_footer_sidebar">';
		$output .= '<label for="rigid_show_footer_sidebar_yes">Show </label>&nbsp;';
		$output .= '<input id="rigid_show_footer_sidebar_no" ' . checked($values['rigid_show_footer_sidebar'], 'no', false) . ' type="radio" value="no" name="rigid_show_footer_sidebar">';
		$output .= '<label for="rigid_show_footer_sidebar_no">Hide </label>';

		// Select footer sidebar
		$output .= "<select name='rigid_custom_footer_sidebar'>";

		// Add a default option
		$output .= "<option";
		if ($values['rigid_custom_footer_sidebar'] == "default") {
			$output .= " selected='selected'";
		}
		$output .= " value='default'>" . esc_html__('default', 'rigid') . "</option>";

		// Fill the select element with all registered sidebars
		foreach ($wp_registered_sidebars as $sidebar_id => $sidebar) {
			if ($sidebar_id != 'pre_header_sidebar') {
				$output .= "<option";
				if ($sidebar_id == $values['rigid_custom_footer_sidebar']) {
					$output .= " selected='selected'";
				}
				$output .= " value='" . esc_attr($sidebar_id) . "'>" . esc_html($sidebar['name']) . "</option>";
			}
		}

		$output .= "</select>";

		echo $output; // All dynamic data escaped
	}

}

/* When the post is saved, saves our custom data */
if (!function_exists('rigid_save_page_options_postdata')) {

	function rigid_save_page_options_postdata($post_id) {
		// verify if this is an auto save routine.
		// If it is our form has not been submitted, so we dont want to do anything
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		// verify this came from our screen and with proper authorization,
		// because save_post can be triggered at other times
		if (isset($_POST['page_options_nonce']) && !wp_verify_nonce($_POST['page_options_nonce'], 'rigid_save_page_options_postdata')) {
			return;
		}

		if (!current_user_can('edit_pages', $post_id)) {
			return;
		}

		if (isset($_POST['rigid_top_menu'])) {
			update_post_meta($post_id, "rigid_top_menu", sanitize_text_field($_POST['rigid_top_menu']));
		}
		if (isset($_POST['rigid_show_title_page'])) {
			update_post_meta($post_id, "rigid_show_title_page", sanitize_text_field($_POST['rigid_show_title_page']));
		}
		if (isset($_POST['rigid_show_breadcrumb'])) {
			update_post_meta($post_id, "rigid_show_breadcrumb", sanitize_text_field($_POST['rigid_show_breadcrumb']));
		}
		if (isset($_POST['rigid_show_feat_image_in_post'])) {
			update_post_meta($post_id, "rigid_show_feat_image_in_post", sanitize_text_field($_POST['rigid_show_feat_image_in_post']));
		}
		if (isset($_POST['rigid_show_sidebar'])) {
			update_post_meta($post_id, "rigid_show_sidebar", sanitize_text_field($_POST['rigid_show_sidebar']));
		}
		if (isset($_POST['rigid_sidebar_position'])) {
			update_post_meta($post_id, "rigid_sidebar_position", sanitize_text_field($_POST['rigid_sidebar_position']));
		}
		if (isset($_POST['rigid_show_footer_sidebar'])) {
			update_post_meta($post_id, "rigid_show_footer_sidebar", sanitize_text_field($_POST['rigid_show_footer_sidebar']));
		}
		if (isset($_POST['rigid_show_offcanvas_sidebar'])) {
			update_post_meta($post_id, "rigid_show_offcanvas_sidebar", sanitize_text_field($_POST['rigid_show_offcanvas_sidebar']));
		}
		if (isset($_POST['rigid_show_share'])) {
			update_post_meta($post_id, "rigid_show_share", sanitize_text_field($_POST['rigid_show_share']));
		}
		if (isset($_POST['rigid_custom_sidebar'])) {
			update_post_meta($post_id, "rigid_custom_sidebar", sanitize_text_field($_POST['rigid_custom_sidebar']));
		}
		if (isset($_POST['rigid_custom_footer_sidebar'])) {
			update_post_meta($post_id, "rigid_custom_footer_sidebar", sanitize_text_field($_POST['rigid_custom_footer_sidebar']));
		}
		if (isset($_POST['rigid_custom_offcanvas_sidebar'])) {
			update_post_meta($post_id, "rigid_custom_offcanvas_sidebar", sanitize_text_field($_POST['rigid_custom_offcanvas_sidebar']));
		}
	}

}

// If Revolution slider is active add the meta box
if (RIGID_IS_REVOLUTION) {
	add_action('add_meta_boxes', 'rigid_add_revolution_slider_metabox');
	add_action('save_post', 'rigid_save_revolution_slider_postdata');
}

/* Adds a box to the side column on the Post, Page and Portfolio edit screens */
if (!function_exists('rigid_add_revolution_slider_metabox')) {

	function rigid_add_revolution_slider_metabox() {
		add_meta_box(
						'rigid_revolution_slider', esc_html__('Revolution Slider', 'rigid'), 'rigid_revolution_slider_callback', 'page', 'side'
		);

		add_meta_box(
						'rigid_revolution_slider', esc_html__('Revolution Slider', 'rigid'), 'rigid_revolution_slider_callback', 'post', 'side'
		);

		add_meta_box(
						'rigid_revolution_slider', esc_html__('Revolution Slider', 'rigid'), 'rigid_revolution_slider_callback', 'rigid-portfolio', 'side'
		);

		add_meta_box(
						'rigid_revolution_slider', esc_html__('Revolution Slider', 'rigid'), 'rigid_revolution_slider_callback', 'tribe_events', 'side'
		);
	}

}

/* Prints the box content */
if (!function_exists('rigid_revolution_slider_callback')) {

	function rigid_revolution_slider_callback($post) {

		// If current page is set as Blog page - don't show the options
		if ($post->ID == get_option('page_for_posts')) {
			echo esc_html__("Revolution slider is disabled for this page, because the page is set as Blog page from Settings->Reading.", 'rigid');
			return;
		}

		// If current page is set as Shop page - don't show the options
		if (RIGID_IS_WOOCOMMERCE && $post->ID == wc_get_page_id('shop')) {
			echo esc_html__("Revolution slider is disabled for this page, because the page is set as Shop page.", 'rigid');
			return;
		}

		// Use nonce for verification
		wp_nonce_field('rigid_save_revolution_slider_postdata', 'rigid_revolution_slider');

		$custom = get_post_custom($post->ID);

		if (isset($custom['rigid_rev_slider'])) {
			$val = $custom['rigid_rev_slider'][0];
		} else {
			$val = "none";
		}

		if (isset($custom['rigid_rev_slider_before_header']) && $custom['rigid_rev_slider_before_header'][0] != '') {
			$val_before_header = esc_attr($custom['rigid_rev_slider_before_header'][0]);
		} else {
			$val_before_header = 0;
		}

		// description
		$output = '<p>' . esc_html__("You can choose a Revolution slider to be attached. It will show up on the top of this page/post.", 'rigid') . '</p>';

		// select
		$output .= '<p><label for="rigid_rev_slider"><b>' . esc_html__("Select slider", 'rigid') . '</b></label></p>';
		$output .= "<select name='rigid_rev_slider'>";

		// Add a default option
		$output .= "<option";
		if ($val == "none") {
			$output .= " selected='selected'";
		}
		$output .= " value='none'>" . esc_html__('none', 'rigid') . "</option>";

		// Get defined revolution slides
		$slider = new RevSlider();
		$arrSliders = $slider->getArrSlidersShort();

		// Fill the select element with all registered slides
		foreach ($arrSliders as $id => $title) {
			$output .= "<option";
			if ($id == $val)
				$output .= " selected='selected'";
			$output .= " value='" . esc_attr($id) . "'>" . esc_html($title) . "</option>";
		}

		$output .= "</select>";
		$screen = get_current_screen();
		// only for pages
		if ($screen && in_array($screen->post_type, array('page'), true)) {
			// place before header
			$output .= '<p><label for="rigid_rev_slider_before_header">';
			$output .= "<input type='checkbox' id='rigid_rev_slider_before_header' name='rigid_rev_slider_before_header' value='1' " . checked(esc_attr($val_before_header), 1, false) . "><b>" . esc_html__("Place before header", 'rigid') . "</b></label></p>";
		}
		echo $output; // All dynamic data escaped
	}

}

/* When the post is saved, saves our custom data */
if (!function_exists('rigid_save_revolution_slider_postdata')) {

	function rigid_save_revolution_slider_postdata($post_id) {
		// verify if this is an auto save routine.
		// If it is our form has not been submitted, so we dont want to do anything
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		// verify this came from our screen and with proper authorization,
		// because save_post can be triggered at other times
		if (isset($_POST['rigid_revolution_slider']) && !wp_verify_nonce($_POST['rigid_revolution_slider'], 'rigid_save_revolution_slider_postdata')) {
			return;
		}

		if (!current_user_can('edit_pages', $post_id)) {
			return;
		}

		if (isset($_POST['rigid_rev_slider'])) {
			update_post_meta($post_id, "rigid_rev_slider", sanitize_text_field($_POST['rigid_rev_slider']));
		}

		if (isset($_POST['rigid_rev_slider_before_header']) && $_POST['rigid_rev_slider_before_header']) {
			update_post_meta($post_id, "rigid_rev_slider_before_header", 1);
		} else {
			update_post_meta($post_id, "rigid_rev_slider_before_header", 0);
		}
	}

}

/**
 * Register video background metaboxes
 */
add_action('add_meta_boxes', 'rigid_add_video_bckgr_metabox');
add_action('save_post', 'rigid_save_video_bckgr_postdata');

/* Adds a box to the side column on the Page edit screens */
if (!function_exists('rigid_add_video_bckgr_metabox')) {

	function rigid_add_video_bckgr_metabox() {

		$posttypes = array('page', 'post', 'rigid-portfolio', 'tribe_events');
		if (RIGID_IS_WOOCOMMERCE) {
			$posttypes[] = 'product';
		}
		if (RIGID_IS_BBPRESS) {
			$posttypes[] = 'forum';
			$posttypes[] = 'topic';
		}

		foreach ($posttypes as $pt) {
			add_meta_box(
							'rigid_video_bckgr', esc_html__('Video Background', 'rigid'), 'rigid_video_bckgr_callback', $pt, 'side'
			);
		}
	}

}

/* Prints the box content */
if (!function_exists('rigid_video_bckgr_callback')) {

	function rigid_video_bckgr_callback($post) {
		// If current page is set as Blog page - don't show the options
		if ($post->ID == get_option('page_for_posts')) {
			echo esc_html__("Video Background options are disabled for this page, because the page is set as Blog page from Settings->Reading.", 'rigid');
			return;
		}

		// If current page is set as Shop page - don't show the options
		if (RIGID_IS_WOOCOMMERCE && $post->ID == wc_get_page_id('shop')) {
			echo esc_html__("Video Background options are disabled for this page, because the page is set as Shop page.", 'rigid');
			return;
		}


		// Use nonce for verification
		wp_nonce_field('rigid_save_video_bckgr_postdata', 'video_bckgr_nonce');

		$custom = get_post_custom($post->ID);

		// Set default values
		$values = array(
				'rigid_video_bckgr_url' => '',
				'rigid_video_bckgr_start' => '',
				'rigid_video_bckgr_end' => '',
				'rigid_video_bckgr_loop' => 1,
				'rigid_video_bckgr_mute' => 1
		);

		if (isset($custom['rigid_video_bckgr_url']) && $custom['rigid_video_bckgr_url'][0] != '') {
			$values['rigid_video_bckgr_url'] = esc_attr($custom['rigid_video_bckgr_url'][0]);
		}
		if (isset($custom['rigid_video_bckgr_start']) && $custom['rigid_video_bckgr_start'][0] != '') {
			$values['rigid_video_bckgr_start'] = esc_attr($custom['rigid_video_bckgr_start'][0]);
		}
		if (isset($custom['rigid_video_bckgr_end']) && $custom['rigid_video_bckgr_end'][0] != '') {
			$values['rigid_video_bckgr_end'] = esc_attr($custom['rigid_video_bckgr_end'][0]);
		}
		if (isset($custom['rigid_video_bckgr_loop']) && $custom['rigid_video_bckgr_loop'][0] != '') {
			$values['rigid_video_bckgr_loop'] = esc_attr($custom['rigid_video_bckgr_loop'][0]);
		}
		if (isset($custom['rigid_video_bckgr_mute']) && $custom['rigid_video_bckgr_mute'][0] != '') {
			$values['rigid_video_bckgr_mute'] = esc_attr($custom['rigid_video_bckgr_mute'][0]);
		}

		// description
		$output = '<p>' . esc_html__("Define the video background options for this page/post.", 'rigid') . '</p>';

		// Video URL
		$output .= '<p><label for="rigid_video_bckgr_url"><b>' . esc_html__("YouTube video URL", 'rigid') . '</b></label></p>';
		$output .= '<input type="text" id="rigid_video_bckgr_url" name="rigid_video_bckgr_url" value="' . esc_attr($values['rigid_video_bckgr_url']) . '" class="large-text" />';

		// Start time
		$output .= '<p><label for="rigid_video_bckgr_start"><b>' . esc_html__("Start time in seconds", 'rigid') . '</b></label></p>';
		$output .= '<input type="text" id="rigid_video_bckgr_start" name="rigid_video_bckgr_start" value="' . esc_attr($values['rigid_video_bckgr_start']) . '" size="8" />';

		// End time
		$output .= '<p><label for="rigid_video_bckgr_end"><b>' . esc_html__("End time in seconds", 'rigid') . '</b></label></p>';
		$output .= '<input type="text" id="rigid_video_bckgr_end" name="rigid_video_bckgr_end" value="' . esc_attr($values['rigid_video_bckgr_end']) . '" size="8" />';

		// Loop
		$output .= '<p><label for="rigid_video_bckgr_loop">';
		$output .= "<input type='checkbox' id='rigid_video_bckgr_loop' name='rigid_video_bckgr_loop' value='1' " . checked(esc_attr($values['rigid_video_bckgr_loop']), 1, false) . "><b>" . esc_html__("Loop", 'rigid') . "</b></label></p>";

		// Mute
		$output .= '<p><label for="rigid_video_bckgr_mute">';
		$output .= "<input type='checkbox' id='rigid_video_bckgr_mute' name='rigid_video_bckgr_mute' value='1' " . checked(esc_attr($values['rigid_video_bckgr_mute']), 1, false) . "><b>" . esc_html__("Mute", 'rigid') . "</b></label></p>";


		echo $output; // All dynamic data escaped
	}

}

/* When the post is saved, saves our custom data */
if (!function_exists('rigid_save_video_bckgr_postdata')) {

	function rigid_save_video_bckgr_postdata($post_id) {
		global $pagenow;

		// verify if this is an auto save routine.
		// If it is our form has not been submitted, so we dont want to do anything
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		// verify this came from our screen and with proper authorization,
		// because save_post can be triggered at other times

		if (isset($_POST['video_bckgr_nonce']) && !wp_verify_nonce($_POST['video_bckgr_nonce'], 'rigid_save_video_bckgr_postdata')) {
			return;
		}

		if (!current_user_can('edit_pages', $post_id)) {
			return;
		}

		if ('post-new.php' == $pagenow) {
			return;
		}

		if (isset($_POST['rigid_video_bckgr_url'])) {
			update_post_meta($post_id, "rigid_video_bckgr_url", esc_url($_POST['rigid_video_bckgr_url']));
		}
		if (isset($_POST['rigid_video_bckgr_start'])) {
			update_post_meta($post_id, "rigid_video_bckgr_start", sanitize_text_field($_POST['rigid_video_bckgr_start']));
		}
		if (isset($_POST['rigid_video_bckgr_end'])) {
			update_post_meta($post_id, "rigid_video_bckgr_end", sanitize_text_field($_POST['rigid_video_bckgr_end']));
		}
		if (isset($_POST['rigid_video_bckgr_loop']) && $_POST['rigid_video_bckgr_loop']) {
			update_post_meta($post_id, "rigid_video_bckgr_loop", 1);
		} else {
			update_post_meta($post_id, "rigid_video_bckgr_loop", 0);
		}
		if (isset($_POST['rigid_video_bckgr_mute']) && $_POST['rigid_video_bckgr_mute']) {
			update_post_meta($post_id, "rigid_video_bckgr_mute", 1);
		} else {
			update_post_meta($post_id, "rigid_video_bckgr_mute", 0);
		}
	}

}

/**
 * Supersized slider
 */
add_action('add_meta_boxes', 'rigid_add_supersized_slider_metabox');
add_action('save_post', 'rigid_save_supersized_slider_postdata');

/* Adds a box to the side column on the Post and Page edit screens */
if (!function_exists('rigid_add_supersized_slider_metabox')) {

	function rigid_add_supersized_slider_metabox() {

		$posttypes = array('page', 'post', 'rigid-portfolio', 'tribe_events');
		if (RIGID_IS_WOOCOMMERCE) {
			$posttypes[] = 'product';
		}
		if (RIGID_IS_BBPRESS) {
			$posttypes[] = 'forum';
			$posttypes[] = 'topic';
		}

		foreach ($posttypes as $pt) {
			add_meta_box(
							'rigid_supersized_slider', esc_html__('Supersized Slider', 'rigid'), 'rigid_supersized_slider_callback', $pt, 'side'
			);
		}
	}

}

/* Prints the box content */
if (!function_exists('rigid_supersized_slider_callback')) {

	function rigid_supersized_slider_callback($post) {

		// If current page is set as Blog page - don't show the options
		if ($post->ID == get_option('page_for_posts')) {
			echo esc_html__("Supersized slider is disabled for this page, because the page is set as Blog page from Settings->Reading.", 'rigid');
			return;
		}

		// If current page is set as Shop page - don't show the options
		if (RIGID_IS_WOOCOMMERCE && $post->ID == wc_get_page_id('shop')) {
			echo esc_html__("Supersized slider is disabled for this page, because the page is set as Shop page.", 'rigid');
			return;
		}

		// Use nonce for verification
		wp_nonce_field('rigid_save_supersized_slider_postdata', 'rigid_supersized_slider');

		$custom = get_post_custom($post->ID);

		// get stored ids
		$image_ids = '';
		if (array_key_exists('rigid_super_slider_ids', $custom)) {
			$image_ids = $custom['rigid_super_slider_ids'][0];
		}
		$ids_arr = array();
		if ($image_ids) {
			$ids_arr = explode(';', $image_ids);
		}

		// description
		$output = '<p>' . esc_html__("Select images for the Supersized slider which will be used for this page/post.", 'rigid') . '</p>';

		$output .= '<input id="rigid_super_slider_ids" name="rigid_super_slider_ids" type="hidden" value="' . esc_attr($image_ids) . '" />';
		$output .= '<input type="button" value="' . esc_html__('Manage images', 'rigid') . '" id="upload_rigid_super_slider_ids" class="rigid_upload_image_button is_multiple" data-uploader_title="' . esc_attr__('Choose Supersized Images', 'rigid') . '" data-uploader_button_text="' . esc_attr__('Insert', 'rigid') . '">';

		$output .= '<div id="rigid_super_slider_ids_images">';

		foreach ($ids_arr as $id) {
			$image_arr = wp_get_attachment_image_src($id, 'rigid-general-small-size');
			$output .= '<img src="' . esc_url($image_arr[0]) . '">';
		}

		$output .= '</div>';

		echo $output; // All dynamic data escaped
	}

}

/* When the post is saved, saves our custom data */
if (!function_exists('rigid_save_supersized_slider_postdata')) {

	function rigid_save_supersized_slider_postdata($post_id) {
		// verify if this is an auto save routine.
		// If it is our form has not been submitted, so we dont want to do anything
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		// verify this came from our screen and with proper authorization,
		// because save_post can be triggered at other times

		if (isset($_POST['rigid_supersized_slider']) && !wp_verify_nonce($_POST['rigid_supersized_slider'], 'rigid_save_supersized_slider_postdata')) {
			return;
		}

		if (!current_user_can('edit_pages', $post_id)) {
			return;
		}

		if (isset($_POST['rigid_super_slider_ids'])) {
			update_post_meta($post_id, "rigid_super_slider_ids", sanitize_text_field($_POST['rigid_super_slider_ids']));
		}
	}

}

/**
 * Portfolio CPT metaboxes
 */
add_action('add_meta_boxes', 'rigid_add_portfolio_metabox');
add_action('save_post', 'rigid_save_portfolio_postdata');

/* Adds the custom fields for rigid-portfolio CPT */
if (!function_exists('rigid_add_portfolio_metabox')) {

	function rigid_add_portfolio_metabox() {
		add_meta_box(
						'rigid_portfolio_details', esc_html__('Portfolio details', 'rigid'), 'rigid_portfolio_callback', 'rigid-portfolio', 'normal', 'high'
		);
	}

}

/* Prints the portfolio content */
if (!function_exists('rigid_portfolio_callback')) {

	function rigid_portfolio_callback($post) {
		// Use nonce for verification
		wp_nonce_field('rigid_save_portfolio_postdata', 'rigid_portfolio_nonce');

		echo '<h4>' . esc_html__('Fill any of the following fields. If you leave some of them empty, they won\'t show on the page.', 'rigid') . '</h4>';

		echo '<label for="rigid_collection">';
		_e('Collection', 'rigid');
		echo '</label> ';
		echo '<div><input type="text" id="rigid_collection" name="rigid_collection" value="' . esc_attr(get_post_meta($post->ID, 'rigid_collection', true)) . '" class="regular-text" /></div>';

		echo '<label for="rigid_materials">';
		_e('Materials', 'rigid');
		echo '</label> ';
		echo '<div><input type="text" id="rigid_materials" name="rigid_materials" value="' . esc_attr(get_post_meta($post->ID, 'rigid_materials', true)) . '" class="regular-text" /></div>';

		echo '<label for="rigid_client">';
		_e('Client name', 'rigid');
		echo '</label> ';
		echo '<div><input type="text" id="rigid_client" name="rigid_client" value="' . esc_attr(get_post_meta($post->ID, 'rigid_client', true)) . '" class="regular-text" /></div>';

		echo '<label for="rigid_model">';
		_e('Model', 'rigid');
		echo '</label> ';
		echo '<div><input type="text" id="rigid_model" name="rigid_model" value="' . esc_attr(get_post_meta($post->ID, 'rigid_model', true)) . '" class="regular-text" /></div>';

		echo '<label for="rigid_status">';
		_e('Current status of project', 'rigid');
		echo '</label> ';
		echo '<div><input type="text" id="rigid_status" name="rigid_status" value="' . esc_attr(get_post_meta($post->ID, 'rigid_status', true)) . '" class="regular-text" /></div>';


		echo '<h4>' . esc_html__('Project External Link:', 'rigid') . '</h4>';
		echo '<label for="rigid_ext_link_button_title">';
		_e('Button Title', 'rigid');
		echo '</label> ';
		echo '<div><input type="text" id="rigid_ext_link_button_title" name="rigid_ext_link_button_title" value="' . esc_attr(get_post_meta($post->ID, 'rigid_ext_link_button_title', true)) . '" class="regular-text" /></div>';

		echo '<label for="rigid_ext_link_url">';
		_e('Url', 'rigid');
		echo '</label> ';
		echo '<div><input type="text" id="rigid_ext_link_url" name="rigid_ext_link_url" value="' . esc_attr(get_post_meta($post->ID, 'rigid_ext_link_url', true)) . '" class="regular-text" /></div>';

		echo '<h4>' . esc_html__('Project features list:', 'rigid') . '</h4>';

		echo '<div><input type="text" id="rigid_feature_1" name="rigid_feature_1" value="' . esc_attr(get_post_meta($post->ID, 'rigid_feature_1', true)) . '" class="regular-text" /></div>';
		echo '<div><input type="text" id="rigid_feature_2" name="rigid_feature_2" value="' . esc_attr(get_post_meta($post->ID, 'rigid_feature_2', true)) . '" class="regular-text" /></div>';
		echo '<div><input type="text" id="rigid_feature_3" name="rigid_feature_3" value="' . esc_attr(get_post_meta($post->ID, 'rigid_feature_3', true)) . '" class="regular-text" /></div>';
		echo '<div><input type="text" id="rigid_feature_4" name="rigid_feature_4" value="' . esc_attr(get_post_meta($post->ID, 'rigid_feature_4', true)) . '" class="regular-text" /></div>';
		echo '<div><input type="text" id="rigid_feature_5" name="rigid_feature_5" value="' . esc_attr(get_post_meta($post->ID, 'rigid_feature_5', true)) . '" class="regular-text" /></div>';
		echo '<div><input type="text" id="rigid_feature_6" name="rigid_feature_6" value="' . esc_attr(get_post_meta($post->ID, 'rigid_feature_6', true)) . '" class="regular-text" /></div>';
		echo '<div><input type="text" id="rigid_feature_7" name="rigid_feature_7" value="' . esc_attr(get_post_meta($post->ID, 'rigid_feature_7', true)) . '" class="regular-text" /></div>';
		echo '<div><input type="text" id="rigid_feature_8" name="rigid_feature_8" value="' . esc_attr(get_post_meta($post->ID, 'rigid_feature_8', true)) . '" class="regular-text" /></div>';
		echo '<div><input type="text" id="rigid_feature_9" name="rigid_feature_9" value="' . esc_attr(get_post_meta($post->ID, 'rigid_feature_9', true)) . '" class="regular-text" /></div>';
		echo '<div><input type="text" id="rigid_feature_10" name="rigid_feature_10" value="' . esc_attr(get_post_meta($post->ID, 'rigid_feature_10', true)) . '" class="regular-text" /></div>';

		echo '<h4>' . esc_html__('Short Description', 'rigid') . '</h4>';
		wp_editor(esc_attr(get_post_meta($post->ID, 'rigid_add_description', true)), 'rigidadddescription', $settings = array('textarea_name' => 'rigid_add_description', 'textarea_rows' => 5));
	}

}

/* When the portfolio is saved, saves our custom data */
if (!function_exists('rigid_save_portfolio_postdata')) {

	function rigid_save_portfolio_postdata($post_id) {

		// Check if our nonce is set.
		if (!isset($_POST['rigid_portfolio_nonce'])) {
			return;
		}

		// Verify that the nonce is valid.
		if (!wp_verify_nonce($_POST['rigid_portfolio_nonce'], 'rigid_save_portfolio_postdata')) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		// Check the user's permissions.
		if (isset($_POST['post_type']) && 'page' === $_POST['post_type']) {

			if (!current_user_can('edit_pages', $post_id)) {
				return;
			}
		} else {

			if (!current_user_can('edit_posts', $post_id)) {
				return;
			}
		}

		/* OK, it's safe for us to save the data now. */
		// Make sure that it is set.
		if (!isset($_POST['rigid_collection'], $_POST['rigid_materials'], $_POST['rigid_client'], $_POST['rigid_model'], $_POST['rigid_status'], $_POST['rigid_ext_link_button_title'], $_POST['rigid_ext_link_url'], $_POST['rigid_feature_1'], $_POST['rigid_feature_2'], $_POST['rigid_feature_3'], $_POST['rigid_feature_4'], $_POST['rigid_feature_5'], $_POST['rigid_feature_6'], $_POST['rigid_feature_7'], $_POST['rigid_feature_8'], $_POST['rigid_feature_9'], $_POST['rigid_feature_10'], $_POST['rigid_add_description'])) {
			return;
		}

		update_post_meta($post_id, 'rigid_collection', sanitize_text_field($_POST['rigid_collection']));
		update_post_meta($post_id, 'rigid_materials', sanitize_text_field($_POST['rigid_materials']));
		update_post_meta($post_id, 'rigid_client', sanitize_text_field($_POST['rigid_client']));
		update_post_meta($post_id, 'rigid_model', sanitize_text_field($_POST['rigid_model']));
		update_post_meta($post_id, 'rigid_status', sanitize_text_field($_POST['rigid_status']));
		update_post_meta($post_id, 'rigid_ext_link_button_title', sanitize_text_field($_POST['rigid_ext_link_button_title']));
		update_post_meta($post_id, 'rigid_ext_link_url', esc_url($_POST['rigid_ext_link_url']));
		update_post_meta($post_id, 'rigid_feature_1', sanitize_text_field($_POST['rigid_feature_1']));
		update_post_meta($post_id, 'rigid_feature_2', sanitize_text_field($_POST['rigid_feature_2']));
		update_post_meta($post_id, 'rigid_feature_3', sanitize_text_field($_POST['rigid_feature_3']));
		update_post_meta($post_id, 'rigid_feature_4', sanitize_text_field($_POST['rigid_feature_4']));
		update_post_meta($post_id, 'rigid_feature_5', sanitize_text_field($_POST['rigid_feature_5']));
		update_post_meta($post_id, 'rigid_feature_6', sanitize_text_field($_POST['rigid_feature_6']));
		update_post_meta($post_id, 'rigid_feature_7', sanitize_text_field($_POST['rigid_feature_7']));
		update_post_meta($post_id, 'rigid_feature_8', sanitize_text_field($_POST['rigid_feature_8']));
		update_post_meta($post_id, 'rigid_feature_9', sanitize_text_field($_POST['rigid_feature_9']));
		update_post_meta($post_id, 'rigid_feature_10', sanitize_text_field($_POST['rigid_feature_10']));
		update_post_meta($post_id, 'rigid_add_description', sanitize_text_field($_POST['rigid_add_description']));
	}

}

/**
 * Register additional featured images metaboxes (5)
 */
add_action('add_meta_boxes', 'rigid_add_additonal_featured_meta');
add_action('save_post', 'rigid_save_additonal_featured_meta_postdata');

/* Adds a box to the side column on the Page/Post/Portfolio edit screens */
if (!function_exists('rigid_add_additonal_featured_meta')) {

	function rigid_add_additonal_featured_meta() {
		$post_types_array = array('page', 'post', 'rigid-portfolio', 'tribe_events');

		for ($i = 2; $i <= 6; $i++) {
			foreach ($post_types_array as $post_type) {
				add_meta_box(
								'rigid_featured_' . $i, esc_html__('Featured Image', 'rigid') . ' ' . $i, 'rigid_additonal_featured_meta_callback', $post_type, 'side', 'default', array('num' => $i)
				);
			}
		}
	}

}

/* Prints the box content */
if (!function_exists('rigid_additonal_featured_meta_callback')) {

	function rigid_additonal_featured_meta_callback($post, $args) {
		// Use nonce for verification
		wp_nonce_field('rigid_save_additonal_featured_meta_postdata', 'rigid_featuredmeta');

		$num = esc_attr($args['args']['num']);

		$image_id = get_post_meta(
						$post->ID, 'rigid_featured_imgid_' . $num, true
		);

		$add_link_style = '';
		$del_link_style = '';

		$output = '<p class="hide-if-no-js">';
		$output .= '<span id="rigid_featured_imgid_' . esc_attr($num) . '_images" class="rigid_featured_img_holder">';

		if ($image_id) {
			$add_link_style = 'style="display:none"';
			$output .= wp_get_attachment_image($image_id, 'medium');
		} else {
			$del_link_style = 'style="display:none"';
		}

		$output .= '</span>';
		$output .= '</p>';

		$output .= '<p class="hide-if-no-js">';
		$output .= '<input id="rigid_featured_imgid_' . esc_attr($num) . '" name="rigid_featured_imgid_' . esc_attr($num) . '" type="hidden" value="' . esc_attr($image_id) . '" />';

		// delete link
		$output .= '<a id="delete_rigid_featured_imgid_' . esc_attr($num) . '" ' . wp_kses_data($del_link_style) . ' class="rigid_delete_image_button" href="#" title="' . esc_attr__('Remove featured image', 'rigid') . ' ' . esc_attr($num) . '">' . esc_html__('Remove featured image', 'rigid') . ' ' . esc_attr($num) . '</a>';

		// add link
		$output .= '<a id="upload_rigid_featured_imgid_' . esc_attr($num) . '" ' . wp_kses_data($add_link_style) . ' data-uploader_title="' . esc_attr__('Select Featured Image', 'rigid') . ' ' . esc_attr($num) . '" data-uploader_button_text="' . esc_attr__('Set Featured Image', 'rigid') . ' ' . esc_attr($num) . '" class="rigid_upload_image_button is_upload_link" href="#" title="' . esc_attr__('Set featured image', 'rigid') . ' ' . esc_attr($num) . '">' . esc_html__('Set featured image', 'rigid') . ' ' . esc_attr($num) . '</a>';


		$output .= '</p>';

		echo $output; // All dynamic data escaped
	}

}

/* When the post is saved, saves our custom data */
if (!function_exists('rigid_save_additonal_featured_meta_postdata')) {

	function rigid_save_additonal_featured_meta_postdata($post_id) {
		// verify if this is an auto save routine.
		// If it is our form has not been submitted, so we dont want to do anything
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		// verify this came from our screen and with proper authorization,
		// because save_post can be triggered at other times

		if (isset($_POST['rigid_featuredmeta']) && !wp_verify_nonce($_POST['rigid_featuredmeta'], 'rigid_save_additonal_featured_meta_postdata')) {
			return;
		}

		if (!current_user_can('edit_pages', $post_id)) {
			return;
		}

		foreach ($_POST as $key => $value) {
			if (strstr($key, 'rigid_featured_imgid_')) {
				update_post_meta($post_id, sanitize_key($key), sanitize_text_field($value));
			}
		}
	}

}

/**
 * Register Portfolio enable Cloud Zoom metabox
 */
add_action('add_meta_boxes', 'rigid_add_portfolio_cz_metabox');
add_action('save_post', 'rigid_save_portfolio_cz_postdata');

if (!function_exists('rigid_add_portfolio_cz_metabox')) {

	function rigid_add_portfolio_cz_metabox() {
		add_meta_box(
						'rigid_portfolio_cz', esc_html__('Portfolio Options', 'rigid'), 'rigid_portfolio_cz_callback', 'rigid-portfolio', 'side', 'low'
		);
	}

}

/* Prints the box content */
if (!function_exists('rigid_portfolio_cz_callback')) {

	function rigid_portfolio_cz_callback($post) {

		// Use nonce for verification
		wp_nonce_field('rigid_save_portfolio_cz_postdata', 'portfolio_cz_nonce');

		$custom = get_post_custom($post->ID);

		// Set default
		$rigid_prtfl_custom_content = 0;
		$rigid_prtfl_gallery = 'flex';

		if (isset($custom['rigid_prtfl_custom_content']) && $custom['rigid_prtfl_custom_content'][0]) {
			$rigid_prtfl_custom_content = $custom['rigid_prtfl_custom_content'][0];
		}
		if (isset($custom['rigid_prtfl_gallery']) && $custom['rigid_prtfl_gallery'][0]) {
			$rigid_prtfl_gallery = $custom['rigid_prtfl_gallery'][0];
		}

		$output = '<p><b>' . esc_html__('Custom Content:', 'rigid') . '</b></p>';

		$output .= '<p><label for="rigid_prtfl_custom_content">';
		$output .= "<input type='checkbox' id='rigid_prtfl_custom_content' name='rigid_prtfl_custom_content' value='1' " .
						checked(esc_attr($rigid_prtfl_custom_content), 1, false) . ">" .
						esc_html__("Don't use the portfolio gallery and all portfolio related fields. Use only the content.", 'rigid') . "</label></p>";

		$output .= '<p><b>' . esc_html__('Portfolio gallery will appear as:', 'rigid') . '</b></p>';

		$output .= '<div><input id="rigid_prtfl_gallery_flex" ' . checked($rigid_prtfl_gallery, 'flex', false) . ' type="radio" value="flex" name="rigid_prtfl_gallery">';
		$output .= '<label for="rigid_prtfl_gallery_flex">' . esc_html__('Flex Slider', 'rigid') . '</label></div>';
		$output .= '<div><input id="rigid_prtfl_gallery_cloud" ' . checked($rigid_prtfl_gallery, 'cloud', false) . ' type="radio" value="cloud" name="rigid_prtfl_gallery">';
		$output .= '<label for="rigid_prtfl_gallery_cloud">' . esc_html__('Cloud Zoom', 'rigid') . '</label></div>';
		$output .= '<div><input id="rigid_prtfl_gallery_list" ' . checked($rigid_prtfl_gallery, 'list', false) . ' type="radio" value="list" name="rigid_prtfl_gallery">';
		$output .= '<label for="rigid_prtfl_gallery_list">' . esc_html__('Image List', 'rigid') . '</label></div>';

		echo $output; // All dynamic data escaped
	}

}

/* When the post is saved, saves our custom data */
if (!function_exists('rigid_save_portfolio_cz_postdata')) {

	function rigid_save_portfolio_cz_postdata($post_id) {
		// verify if this is an auto save routine.
		// If it is our form has not been submitted, so we dont want to do anything
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		// verify this came from our screen and with proper authorization,
		// because save_post can be triggered at other times

		if (isset($_POST['portfolio_cz_nonce']) && !wp_verify_nonce($_POST['portfolio_cz_nonce'], 'rigid_save_portfolio_cz_postdata')) {
			return;
		}

		// Check the user's permissions.
		if (isset($_POST['post_type']) && 'page' === $_POST['post_type']) {

			if (!current_user_can('edit_pages', $post_id)) {
				return;
			}
		} else {

			if (!current_user_can('edit_posts', $post_id)) {
				return;
			}
		}

		if (isset($_POST['rigid_prtfl_custom_content']) && $_POST['rigid_prtfl_custom_content']) {
			update_post_meta($post_id, "rigid_prtfl_custom_content", 1);
		} else {
			update_post_meta($post_id, "rigid_prtfl_custom_content", 0);
		}

		// It is checkbox - if is in the post - is set, if not - is not set
		if (isset($_POST['rigid_prtfl_gallery'])) {
			update_post_meta($post_id, "rigid_prtfl_gallery", sanitize_text_field($_POST['rigid_prtfl_gallery']));
		}
	}

}

/**
 * Register product video option for products
 */
add_action('add_meta_boxes', 'rigid_add_product_video_metabox');
add_action('save_post', 'rigid_save_product_video_postdata');

/* Adds a box to the side column on the Page edit screens */
if (!function_exists('rigid_add_product_video_metabox')) {

	function rigid_add_product_video_metabox() {
		add_meta_box(
			'rigid_product_video', esc_html__('Product Video', 'rigid'), 'rigid_product_video_callback', 'product', 'side'
		);
	}

}

/* Prints the box content */
if (!function_exists('rigid_product_video_callback')) {

	function rigid_product_video_callback($post) {

		// Use nonce for verification
		wp_nonce_field('rigid_save_product_video_postdata', 'product_video_nonce');

		$custom = get_post_custom($post->ID);

		// Set default values
		$values = array(
			'rigid_product_video_url' => ''
		);

		if (isset($custom['rigid_product_video_url']) && $custom['rigid_product_video_url'][0] != '') {
			$values['rigid_product_video_url'] = esc_attr($custom['rigid_product_video_url'][0]);
		}

		// description
		$output = '<p>' . esc_html__("Product Video to be displayed on the product page (YouTube, Vimeo, Self-hosted).", 'rigid') . '</p>';

		// Video URL
		$output .= '<p><label for="rigid_product_video_url"><b>' . esc_html__("Video URL", 'rigid') . '</b></label></p>';
		$output .= '<input type="text" id="rigid_product_video_url" name="rigid_product_video_url" value="' . esc_attr($values['rigid_product_video_url']) . '" class="large-text" />';

		echo $output; // All dynamic data escaped
	}

}

/* When the post is saved, saves our custom data */
if (!function_exists('rigid_save_product_video_postdata')) {

	function rigid_save_product_video_postdata($post_id) {
		global $pagenow;

		// verify if this is an auto save routine.
		// If it is our form has not been submitted, so we dont want to do anything
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		// verify this came from our screen and with proper authorization,
		// because save_post can be triggered at other times

		if (isset($_POST['product_video_nonce']) && !wp_verify_nonce($_POST['product_video_nonce'], 'rigid_save_product_video_postdata')) {
			return;
		}

		if (!current_user_can('edit_pages', $post_id)) {
			return;
		}

		if ('post-new.php' == $pagenow) {
			return;
		}

		if (isset($_POST['rigid_product_video_url'])) {
			update_post_meta($post_id, "rigid_product_video_url", esc_url($_POST['rigid_product_video_url']));
		}
	}

}