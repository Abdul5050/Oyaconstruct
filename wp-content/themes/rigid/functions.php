<?php
/* Load core functions */
require_once (get_template_directory() . '/incl/system/core-functions.php');

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/*
 * Loads the Options Panel
 */
if (!function_exists('rigid_optionsframework_init')) {
	define('RIGID_OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/incl/rigid-options-framework/');
	// framework
	require_once get_template_directory() . '/incl/rigid-options-framework/rigid-options-framework.php';
	// custom functions
	require_once get_template_directory() . '/incl/rigid-options-framework/rigid-options-functions.php';
}

/* Load configuration */
require_once (get_template_directory() . '/incl/system/config.php');

/**
 * Echo the pagination
 */
if (!function_exists('rigid_pagination')) {

	function rigid_pagination($pages = '', $wp_query = '') {
		if (empty($wp_query)) {
			global $wp_query;
		}

		$range = get_query_var('posts_per_page');
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

		$html = '';

		if ($pages == '') {

			if (isset($wp_query->max_num_pages)) {
				$pages = $wp_query->max_num_pages;
			}

			if (!$pages) {
				$pages = 1;
			}
		}

		if (1 != $pages) {
			$html .= "<div class='pagination'><div class='links'>";
			if ($paged > 2) {
				$html .= "<a href='" . esc_url(get_pagenum_link(1)) . "'>&laquo;</a>";
			}
			if ($paged > 1) {
				$html .= "<a href='" . esc_url(get_pagenum_link($paged - 1)) . "'>&lsaquo;</a>";
			}

			for ($i = 1; $i <= $pages; $i++) {
				if (1 != $pages && (!( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) )) {
					$class = ( $paged == $i ) ? " class='selected'" : '';
					$html .= "<a href='" . esc_url(get_pagenum_link($i)) . "'$class >$i</a>";
				}
			}

			if ($paged < $pages) {
				$html .= "<a class='next_page' href='" . esc_url(get_pagenum_link($paged + 1)) . "'>&rsaquo;</a>";
			}
			if ($paged < $pages - 1) {
				$html .= "<a href='" . esc_url(get_pagenum_link($pages)) . "'>&raquo;</a>";
			}

			$first_article_on_page = ( $range * $paged ) - $range + 1;

			$last_article_on_page = min($wp_query->found_posts, $wp_query->get('posts_per_page') * $paged);

			$html .= "</div><div class='results'>";
			$html .= sprintf(esc_html__('Showing %1$s to %2$s of %3$s (%4$s Pages)', 'rigid'), $first_article_on_page, $last_article_on_page, $wp_query->found_posts, $pages);
			$html .= "</div></div>";
		}

		echo apply_filters('rigid_pagination', $html);
	}

}

/**
 * Return the page breadcrumbs
 *
 */
if ( ! function_exists( 'rigid_breadcrumb' ) ) {

	function rigid_breadcrumb( $delimiter = ' <span class="rigid-breadcrumb-delimiter">/</span> ' ) {

		if ( rigid_get_option( 'show_breadcrumb', 1 ) && ! is_404() ) {
			$home      = esc_html__( 'Home', 'rigid' ); // text for the 'Home' link
			$before    = '<span class="current-crumb">'; // tag before the current crumb
			$after     = '</span>'; // tag after the current crumb
			$brdcrmb   = '';

			global $post;
			global $wp_query;
			$homeLink = esc_url(rigid_wpml_get_home_url());

			if ( ! is_home() && ! is_front_page() ) {
				$brdcrmb .= '<a class="home" href="' . esc_url( $homeLink ) . '">' . $home . '</a> ' . $delimiter . ' ';
			}

			if ( is_category() ) {
				$cat_obj   = $wp_query->get_queried_object();
				$thisCat   = $cat_obj->term_id;
				$thisCat   = get_category( $thisCat );
				$parentCat = get_category( $thisCat->parent );

				if ( $thisCat->parent != 0 ) {
					$brdcrmb .= get_category_parents( $parentCat, true, ' ' . $delimiter . ' ' );
				}

				$brdcrmb .= $before . single_cat_title( '', false ) . $after;
				/* If is taxonomy or BBPress topic tag */
			} elseif ( is_tax() || get_query_var( 'bbp_topic_tag' ) ) {
				$cat_obj   = $wp_query->get_queried_object();
				$thisCat   = $cat_obj->term_id;
				$thisCat   = get_term( $thisCat, $cat_obj->taxonomy );
				$parentCat = get_term( $thisCat->parent, $cat_obj->taxonomy );
				$tax_obj   = get_taxonomy( $cat_obj->taxonomy );
				$brdcrmb .= $tax_obj->labels->name . ': ';

				if ( $thisCat->parent != 0 ) {
					$brdcrmb .= rigid_get_taxonomy_parents( $parentCat, $cat_obj->taxonomy, true, ' ' . $delimiter . ' ' );
				}
				$brdcrmb .= $before . $thisCat->name . $after;
			} elseif ( is_day() ) {
				$brdcrmb .= '<a class="no-link" href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '">' . get_the_time( 'Y' ) . '</a> ' . $delimiter . ' ';
				$brdcrmb .= '<a class="no-link" href="' . esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) ) . '">' . get_the_time( 'F' ) . '</a> ' . $delimiter . ' ';
				$brdcrmb .= $before . get_the_time( 'd' ) . $after;
			} elseif ( is_month() ) {
				$brdcrmb .= '<a class="no-link" href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '">' . get_the_time( 'Y' ) . '</a> ' . $delimiter . ' ';
				$brdcrmb .= $before . get_the_time( 'F' ) . $after;
			} elseif ( is_year() ) {
				$brdcrmb .= $before . get_the_time( 'Y' ) . $after;
			} elseif ( is_single() && ! is_attachment() ) {
				if ( get_post_type( $wp_query->post->ID ) == 'rigid-portfolio' ) {

					$post_type = get_post_type_object( 'rigid-portfolio' );
					$slug      = $post_type->rewrite;
					$brdcrmb .= '<a class="no-link" href="' . esc_url( $homeLink . '/' . $slug['slug'] ) . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';

					$terms = get_the_terms( $post->ID, 'rigid_portfolio_category' );

					if ( $terms ) {
						$first_cat       = reset( $terms );
						$parent_term_ids = rigid_get_rigid_portfolio_category_parents( $first_cat->term_id );

						$term_links = '';
						foreach ( $parent_term_ids as $term_id ) {
							$term = get_term( $term_id, 'rigid_portfolio_category' );
							$term_links .= '<a href="' . esc_url( get_term_link( $term_id ) ) . '">' . $term->name . '</a>' . $delimiter;
						}

						$brdcrmb .= $term_links;
					}

					$brdcrmb .= $before . get_the_title( $wp_query->post->ID ) . $after;
				} elseif ( get_post_type( $wp_query->post->ID ) != 'post' ) {
					$post_type = get_post_type_object( get_post_type( $wp_query->post->ID ) );
					$slug      = $post_type->rewrite;
					$real_slug = $slug['slug'];
					if ( $slug['slug'] == 'forums/forum' ) {
						$real_slug = 'forums';
					}
					if ( function_exists( 'bbp_is_single_topic' ) && bbp_is_single_topic() ) { // If is Topic
						if ( is_singular() ) {
							$ancestors = array_reverse( (array) get_post_ancestors( $wp_query->post->ID ) );
							// Ancestors exist
							if ( ! empty( $ancestors ) ) {
								// Loop through parents
								foreach ( (array) $ancestors as $parent_id ) {
									// Parents
									$parent = get_post( $parent_id );
									// Skip parent if empty or error
									if ( empty( $parent ) || is_wp_error( $parent ) ) {
										continue;
									}
									// Switch through post_type to ensure correct filters are applied
									switch ( $parent->post_type ) {
										// Forum
										case bbp_get_forum_post_type() :
											$crumbs[] = '<a href="' . esc_url( bbp_get_forum_permalink( $parent->ID ) ) . '" >' . bbp_get_forum_title( $parent->ID ) . '</a>';
											break;
										// Topic
										case bbp_get_topic_post_type() :
											$crumbs[] = '<a href="' . esc_url( bbp_get_topic_permalink( $parent->ID ) ) . '" >' . bbp_get_topic_title( $parent->ID ) . '</a>';
											break;
										// Reply (Note: not in most themes)
										case bbp_get_reply_post_type() :
											$crumbs[] = '<a href="' . esc_url( bbp_get_reply_permalink( $parent->ID ) ) . '" >' . bbp_get_reply_title( $parent->ID ) . '</a>';
											break;
										// WordPress Post/Page/Other
										default :
											$crumbs[] = '<a href="' . esc_url( get_permalink( $parent->ID ) ) . '" >' . get_the_title( $parent->ID ) . '</a>';
											break;
									}
								}

								// Edit topic tag
							}
						}

						$page = bbp_get_page_by_path( bbp_get_root_slug() );
						if ( ! empty( $page ) ) {
							$root_url = get_permalink( $page->ID );

							// Use the root slug
						} else {
							$root_url = get_post_type_archive_link( bbp_get_forum_post_type() );
						}

						$brdcrmb .= '<a class="no-link" href="' . esc_url( $root_url ) . '">' . esc_html__( 'Forums', 'rigid' ) . '</a> ' . $delimiter . ' ';
						foreach ( $crumbs as $crumb ) {
							$brdcrmb .= $crumb . ' ' . $delimiter;
						}

					} elseif ( ! in_array( $post_type->name, array( 'tribe_venue', 'tribe_organizer' ) ) ) {
						$brdcrmb .= '<a class="no-link" href="' . esc_url( $homeLink . '/' . $real_slug ) . '/">' . $post_type->labels->name . '</a> ' . $delimiter . ' ';
					} else {
						$brdcrmb .= '<span>' . $post_type->labels->name . '</span> ' . $delimiter . ' ';
					}

					$brdcrmb .= $before . get_the_title( $wp_query->post->ID ) . $after;
				} else {
					$cat = get_the_category();
					$cat = $cat[0];
					$brdcrmb .= get_category_parents( $cat, true, ' ' . $delimiter . ' ' );
					$brdcrmb .= $before . get_the_title( $wp_query->post->ID ) . $after;
				}
			} elseif ( ! is_single() && ! is_page() && ! is_404() && ! is_search() && get_post_type( $wp_query->post->ID ) != 'post') {
				$post_type = get_post_type_object( get_post_type( $wp_query->post->ID ) );
				if ( $post_type ) {
					$brdcrmb .= $before . $post_type->labels->singular_name . $after;
				}
			} elseif ( is_attachment() ) {
				$parent = get_post( $post->post_parent );
				$cat    = get_the_category( $parent->ID );
				if ( ! empty( $cat ) ) {
					$cat         = $cat[0];
					$cat_parents = get_category_parents( $cat, true, ' ' . $delimiter . ' ' );
					if ( ! is_wp_error( $cat_parents ) ) {
						$brdcrmb .= get_category_parents( $cat, true, ' ' . $delimiter . ' ' );
					}
				}
				$brdcrmb .= '<a class="no-link" href="' . esc_url( get_permalink( $parent ) ) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
				$brdcrmb .= $before . get_the_title( $wp_query->post->ID ) . $after;
			} elseif ( is_page() && ! $post->post_parent ) {
				$brdcrmb .= $before . ucfirst( strtolower( get_the_title( $wp_query->post->ID ) ) ) . $after;
			} elseif ( is_page() && $post->post_parent ) {
				$parent_id   = $post->post_parent;
				$breadcrumbs = array();

				while ( $parent_id ) {
					$page          = get_post( $parent_id );
					$breadcrumbs[] = '<a class="no-link" href="' . esc_url( get_permalink( $page->ID ) ) . '">' . get_the_title( $page->ID ) . '</a>';
					$parent_id     = $page->post_parent;
				}

				$breadcrumbs = array_reverse( $breadcrumbs );
				foreach ( $breadcrumbs as $crumb ) {
					$brdcrmb .= $crumb . ' ' . $delimiter . ' ';
				}

				$brdcrmb .= $before . get_the_title( $wp_query->post->ID ) . $after;
			} elseif ( is_search() ) {
				$brdcrmb .= $before . 'Search results for "' . get_search_query() . '"' . $after;
			} elseif ( is_tag() ) {
				$brdcrmb .= $before . 'Posts tagged "' . single_tag_title( '', false ) . '"' . $after;
			} elseif ( is_author() ) {
				global $author;
				$userdata = get_userdata( $author );
				$brdcrmb .= $before . 'Articles posted by ' . esc_attr( $userdata->display_name ) . $after;
			} elseif ( is_404() ) {
				$brdcrmb .= $before . 'Error 404' . $after;
			}

			if ( get_query_var( 'paged' ) ) {
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
					$brdcrmb .= ' (';
				}

				$brdcrmb .= $before . esc_html__( 'Page', 'rigid' ) . ' ' . get_query_var( 'paged' ) . $after;

				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
					$brdcrmb .= ')';
				}
			}

			if ( $brdcrmb ) {
				echo '<div class="breadcrumb">';
				echo wp_kses_post( $brdcrmb );
				echo '</div>';
			}
		} else {
			return false;
		}
	}

}

/**
 * Template for comments and pingbacks.
 */
if (!function_exists('rigid_comment')) {

	function rigid_comment($comment, $args, $depth) {

		$GLOBALS['comment'] = $comment;
		switch ($comment->comment_type) {
			case 'pingback' :
			case 'trackback' :
				?>
				<li class="post pingback">
					<p><?php esc_html_e('Pingback:', 'rigid'); ?> <?php comment_author_link(); ?><?php edit_comment_link(esc_html__('Edit', 'rigid'), '<span class="edit-link">', '</span>'); ?></p>
					<?php
					break;
				default :
					?>
				<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
					<div id="comment-<?php comment_ID(); ?>" class="comment-body">
						<?php
						$avatar_size = 70;
						echo get_avatar($comment, $avatar_size);
						echo sprintf('<span class="tuser">%s</span>', get_comment_author_link());
						echo sprintf('<span>%1$s</span>',
										/* translators: 1: date, 2: time */ sprintf(esc_html__('%1$s at %2$s', 'rigid'), get_comment_date(), get_comment_time())
						);
						?>
						<?php edit_comment_link(esc_html__('Edit', 'rigid'), '<span class="edit-link">', '</span>'); ?>
						<?php if ($comment->comment_approved == '0') : ?>
							<em class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'rigid'); ?></em>
							<br />
						<?php endif; ?>

						<p><?php comment_text(); ?></p>

						<?php comment_reply_link(array_merge($args, array('reply_text' => esc_html__('Reply', 'rigid'), 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>

					</div><!-- #comment-## -->

					<?php
					break;
			}
		}

	}

	/**
	 * Hook in on activation
	 */
	/**
	 * Define woocommerce image sizes
	 */
	if (!function_exists('rigid_woocommerce_image_dimensions')) {

		function rigid_woocommerce_image_dimensions() {
			global $pagenow;
			if (!isset($_GET['activated']) || $pagenow != 'themes.php') {
				return;
			}

			$single = array(
					'width' => '600', // px
					'height' => '600', // px
					'crop' => 0 // false
			);

			$thumbnail = array(
					'width' => '60', // px
					'height' => '60', // px
					'crop' => 1 // true
			);

			// Image sizes
			update_option('shop_single_image_size', $single); // Single product image
			update_option('shop_thumbnail_image_size', $thumbnail); // Image gallery thumbs
		}

	}
	add_action('after_switch_theme', 'rigid_woocommerce_image_dimensions', 1);

	/*
	 * Add custom image sizes for the rigid theme blog part
	 */
	if (function_exists('add_image_size')) {
		add_image_size('rigid-blog-category-thumb', 1130); //1130 pixels wide (and unlimited height)
		add_image_size('rigid-portfolio-single-thumb', 1220);
		add_image_size('rigid-general-big-size', 800, 800, true); //(cropped)
		add_image_size('rigid-640x640', 640, 640, true); //(cropped)
		add_image_size('rigid-portfolio-category-thumb', 600, 600, true); //(cropped)
		add_image_size('rigid-portfolio-category-thumb-real', 550);
		add_image_size('rigid-blog-small-image-size', 400, 400, true); //(cropped)
		add_image_size('rigid-general-medium-size', 200, 150);
		add_image_size('rigid-general-small-size', 100, 100, true); //(cropped)
		add_image_size('rigid-widgets-thumb', 60, 60, true); //(cropped)
		add_image_size('rigid-icon', 50, 50);
		add_image_size('rigid-related-posts', 300, 225, true); //(cropped)
	}

	add_filter('wp_prepare_attachment_for_js', 'rigid_append_image_sizes_js', 10, 3);
	if (!function_exists('rigid_append_image_sizes_js')) {

		/**
		 * Append the 'rigid-general-medium-size', 'rigid-general-small-size' custom
		 * sizes to the attachment elements returned by the wp.media
		 *
		 * @param type $response
		 * @param type $attachment
		 * @param type $meta
		 * @return string
		 */
		function rigid_append_image_sizes_js($response, $attachment, $meta) {

			$size_array = array('rigid-general-medium-size', 'rigid-general-small-size');

			foreach ($size_array as $size):

				if (isset($meta['sizes'][$size])) {
					$attachment_url = wp_get_attachment_url($attachment->ID);
					$base_url = str_replace(wp_basename($attachment_url), '', $attachment_url);
					$size_meta = $meta['sizes'][$size];

					$response['sizes'][str_replace('-', '_', $size)] = array(
							'height' => $size_meta['height'],
							'width' => $size_meta['width'],
							'url' => $base_url . $size_meta['file'],
							'orientation' => $size_meta['height'] > $size_meta['width'] ? 'portrait' : 'landscape',
					);
				}

			endforeach;

			return $response;
		}

	}

	add_action('init', 'rigid_enable_page_attributes');

	/**
	 * Add page attributes to page post type
	 * - Gives option to select template
	 * Adds excerpt support for pages - mainly used by the About widget
	 */
	if (!function_exists('rigid_enable_page_attributes')) {

		function rigid_enable_page_attributes() {
			add_post_type_support('page', 'page-attributes');
			add_post_type_support('page', 'excerpt');
		}

	}

	add_filter('template_include', 'rigid_post_templater');

	/**
	 * Loading custom templates
	 *
	 * @global type $wp_query
	 * @param type $template
	 * @return String
	 */
	if (!function_exists('rigid_post_templater')) {

		function rigid_post_templater($template) {
			if (!is_single()) {
				return $template;
			}
			global $wp_query;
			$c_template = get_post_meta($wp_query->post->ID, '_wp_page_template', true);
			return empty($c_template) ? $template : $c_template;
		}

	}

	/**
	 * Display language switcher
	 *
	 * @return String
	 */
	if (!function_exists('rigid_language_selector_flags')) {

		function rigid_language_selector_flags() {
			$languages = icl_get_languages('skip_missing=0&orderby=code');

			if (!empty($languages)) {
				foreach ($languages as $l) {
					if (!$l['active']) {
						echo '<a title="' . esc_attr($l['native_name']) . '" href="' . esc_url($l['url']) . '">';
					}

					echo '<img src="' . esc_url($l['country_flag_url']) . '" height="12" alt="' . esc_attr($l['language_code']) . '" width="18" />';

					if (!$l['active']) {
						echo '</a>';
					}
				}
			}
		}

	}

	/**
	 * Set custom excerpt_length for portfolio category
	 *
	 * @return Integer
	 */
	if (!function_exists('rigid_portfolio_custom_excerpt_length')) {

		function rigid_portfolio_custom_excerpt_length($length) {
			return 40;
		}

	}

	/**
	 * Set custom excerpt_length for post category
	 *
	 * @return Integer
	 */
	if (!function_exists('rigid_post_custom_excerpt_length')) {

		function rigid_post_custom_excerpt_length($length) {
			return 50;
		}

	}

	if (!function_exists('rigid_new_excerpt_more')) {

		/**
		 * Set custom excerpt more
		 *
		 * @param type $more If is set as 'no_hash' #more keyword is not appended in the url
		 * @return string
		 */
		function rigid_new_excerpt_more($more) {

			$more_html = ' ...<a class="r_more_blog" href="';
			if ('no_hash' === $more) {
				$more_html .= esc_url(get_the_permalink());
			} else {
				$more_html .= esc_url(get_the_permalink() . '#more-' . esc_attr(get_the_ID()));
			}

			$more_html .= '"><i class="fa fa-sign-in"></i> ' . esc_html__('read more', 'rigid') . '</a>';

			return $more_html;
		}

	}

	/**
	 * Set custom excerpt more for portfolio category
	 *
	 * @return String
	 */
	if (!function_exists('rigid_portfolio_new_excerpt_more')) {

		function rigid_portfolio_new_excerpt_more($more) {

			return '...';
		}

	}

	/**
	 * Set custom content more link
	 *
	 * @return String
	 */
	add_filter('the_content_more_link', 'rigid_content_more_link');

	if (!function_exists('rigid_content_more_link')) {

		function rigid_content_more_link() {
			return '<a class="r_more_blog" href="' . esc_url(get_permalink() . '#more-' . esc_attr(get_the_ID())) . '"><i class="fa fa-sign-in"></i> ' . esc_html__('read more', 'rigid') . '</a>';
		}

	}

	/**
	 * Adds one-half one-third one-forth class to footer widgets
	 */
	if (!function_exists('rigid_widget_class_append')) {

		function rigid_widget_class_append($params) {

			$sidebar_id = $params[0]['id']; // Get the id for the current sidebar we're processing

			if ($sidebar_id != 'bottom_footer_sidebar' && $sidebar_id != 'pre_header_sidebar' && $sidebar_id != 'rigid_product_filters_sidebar') {
				return $params;
			}

			$arr_registered_widgets = wp_get_sidebars_widgets(); // Get an array of ALL registered widgets
			$num_widgets_sidebar = count($arr_registered_widgets[$sidebar_id]);
			$class = 'class="';

			switch ($num_widgets_sidebar) {
				case 0:
				case 1:
					break;
				case 2:
					$class .= 'one_half ';
					break;
				case 3:
					$class .= 'one_third ';
					break;
				default:
					$class .= 'one_fourth ';
			}

			if (!isset($arr_registered_widgets[$sidebar_id]) || !is_array($arr_registered_widgets[$sidebar_id])) { // Check if the current sidebar has no widgets
				return $params; // No widgets in this sidebar.
			}

			$params[0]['before_widget'] = str_replace('class="', $class, $params[0]['before_widget']); // Insert our new classes into "before widget"

			return $params;
		}

	}
	add_filter('dynamic_sidebar_params', 'rigid_widget_class_append');

	if (!function_exists('rigid_get_rigid_portfolio_category_parents')) {

		/**
		 * Get list of all parent rigid_portfolio_category-s
		 *
		 * @param int $term_id
		 * @return Array with term ids
		 */
		function rigid_get_rigid_portfolio_category_parents($term_id) {
			$parents = array();
			// start from the current term
			$parent = get_term_by('id', $term_id, 'rigid_portfolio_category');
			$parents[] = $parent;
			// climb up the hierarchy until we reach a term with parent = '0'
			while ($parent->parent != '0') {
				$term_id = $parent->parent;

				$parent = get_term_by('id', $term_id, 'rigid_portfolio_category');
				$parents[] = $parent;
			}
			return $parents;
		}

	}

	add_action('wp_ajax_rigid_ajax_search', 'rigid_ajax_search');
	add_action('wp_ajax_nopriv_rigid_ajax_search', 'rigid_ajax_search');

	if (!function_exists('rigid_ajax_search')) {

		function rigid_ajax_search() {

			unset($_REQUEST['action']);
			if (empty($_REQUEST['s'])) {
				$_REQUEST['s'] = array_shift(array_values($_REQUEST));
			}
			if (empty($_REQUEST['s'])) {
				die();
			}


			$defaults = array('numberposts' => 5, 'post_type' => 'any', 'post_status' => 'publish', 'post_password' => '', 'suppress_filters' => false);
			$_REQUEST['s'] = apply_filters('get_search_query', $_REQUEST['s']);

			$parameters = array_merge($defaults, $_REQUEST);
			$query = http_build_query($parameters);
			$result = get_posts($query);

			$search_messages = array(
					'no_criteria_matched' => esc_html__("Sorry, no posts matched your criteria", 'rigid'),
					'another_search_term' => esc_html__("Please try another search term", 'rigid'),
					'time_format' => esc_attr(get_option('date_format')),
					'all_results_query' => http_build_query($_REQUEST),
					'all_results_link' => esc_url(home_url('?' . http_build_query($_REQUEST))),
					'view_all_results' => esc_html__('View all results', 'rigid')
			);

			if (empty($result)) {
				$output = "<ul>";
				$output .= "<li>";
				$output .= "<span class='ajax_search_unit ajax_not_found'>";
				$output .= "<span class='ajax_search_content'>";
				$output .= "    <span class='ajax_search_title'>";
				$output .= $search_messages['no_criteria_matched'];
				$output .= "    </span>";
				$output .= "    <span class='ajax_search_excerpt'>";
				$output .= $search_messages['another_search_term'];
				$output .= "    </span>";
				$output .= "</span>";
				$output .= "</span>";
				$output .= "</li>";
				$output .= "</ul>";
				echo wp_kses_post($output);
				die();
			}

			// reorder posts by post type
			$output = "";
			$sorted = array();
			$post_type_obj = array();
			foreach ($result as $post) {
				$sorted[$post->post_type][] = $post;
				if (empty($post_type_obj[$post->post_type])) {
					$post_type_obj[$post->post_type] = get_post_type_object($post->post_type);
				}
			}

			//preapre the output
			foreach ($sorted as $key => $post_type) {
				if (isset($post_type_obj[$key]->labels->name)) {
					$label = $post_type_obj[$key]->labels->name;
					$output .= "<h4>" . esc_html($label) . "</h4>";
				} else {
					$output .= "<hr />";
				}

				$output .= "<ul>";

				foreach ($post_type as $post) {
					$image = get_the_post_thumbnail($post->ID, 'rigid-widgets-thumb');

					$excerpt = "";

					if (!empty($post->post_excerpt)) {
						$excerpt = rigid_generate_excerpt($post->post_excerpt, 70, " ", "...", true, '', true);
					} else {
						$excerpt = get_the_time($search_messages['time_format'], $post->ID);
					}

					$link = get_permalink($post->ID);

					$output .= "<li>";
					$output .= "<a class ='ajax_search_unit' href='" . esc_url($link) . "'>";
					if ($image) {
						$output .= "<span class='ajax_search_image'>";
						$output .= $image;
						$output .= "</span>";
					}
					$output .= "<span class='ajax_search_content'>";
					$output .= "    <span class='ajax_search_title'>";
					$output .= get_the_title($post->ID);
					$output .= "    </span>";
					$output .= "    <span class='ajax_search_excerpt'>";
					$output .= $excerpt;
					$output .= "    </span>";
					$output .= "</span>";
					$output .= "</a>";
					$output .= "</li>";
				}

				$output .= "</ul>";
			}

			$output .= "<a class='ajax_search_unit ajax_search_unit_view_all' href='" . esc_url($search_messages['all_results_link']) . "'>" . esc_html($search_messages['view_all_results']) . "</a>";

			echo wp_kses_post($output);
			die();
		}

	}

	add_filter('wp_import_post_data_processed', 'rigid_preserve_post_ids', 10, 2);

	if (!function_exists('rigid_preserve_post_ids')) {

		/**
		 * WP Import.
		 * Add post id if the record exists
		 *
		 * @param type $postdata
		 * @param type $post
		 * @return Array
		 */
		function rigid_preserve_post_ids($postdata, $post) {

			if (is_array($post) && isset($post['post_id']) && get_post($post['post_id'])) {
				$postdata['ID'] = $post['post_id'];
			}

			return $postdata;
		}

	}

	/* Define ajax calls for each import */
	for ($i = 0; $i <= 1; $i++) {
		add_action('wp_ajax_rigid_import_rigid' . $i, 'rigid_import_rigid' . $i . '_callback');
	}

	if (!function_exists('rigid_import_rigid0_callback')) {

		/**
		 * Import rigid0 demo
		 */
		function rigid_import_rigid0_callback() {
			@set_time_limit(1200);
			$transfer = Rigid_Transfer_Content::getInstance();
			$result = $transfer->doImportDemo('rigid0');

			if ($result) {
				echo 'rigid_import_done';
			}
		}

	}

    if (!function_exists('rigid_import_rigid1_callback')) {

        /**
         * Import rigid1 demo
         */
        function rigid_import_rigid1_callback() {
            @set_time_limit(1200);
            $transfer = Rigid_Transfer_Content::getInstance();
            $result = $transfer->doImportDemo('rigid1');

            if ($result) {
                echo 'rigid_import_done';
            }
        }

    }

	// Replace OF textarea sanitization with rigid one - in admin_init, because we will allow <script> tag
	add_action('admin_init', 'rigid_add_script_to_allowed');
	if (!function_exists('rigid_add_script_to_allowed')) {

		function rigid_add_script_to_allowed() {
			// Add script to allowed tags only for the logged users - to be able to add tracking code
			global $allowedposttags;
			$allowedposttags['script'] = array('type' => TRUE);
		}

	}

	/**
	 * Returns selected subsets from options to pass to google
	 */
	if (!function_exists('rigid_get_google_subsets')) {

		function rigid_get_google_subsets() {
			$selected_subsets = rigid_get_option('google_subsets');
			$choosen = array();

			foreach ($selected_subsets as $subset => $is_selected) {
				if ($is_selected != '0') {
					$choosen[] = $subset;
				}
			}

			return implode(',', $choosen);
		}

	}

	/**
	 * WPML HOME URL
	 */
	if (!function_exists('rigid_wpml_get_home_url')) {

		function rigid_wpml_get_home_url() {
			if (function_exists('icl_get_home_url')) {
				return icl_get_home_url();
			} else {
				return home_url('/');
			}
		}

	}

	// Add classes to body
	add_filter('body_class', 'rigid_append_body_classes');
	if (!function_exists('rigid_append_body_classes')) {

		function rigid_append_body_classes($classes) {
			global $wp_query;

			// the layout class
			$general_layout = rigid_get_option('general_layout');

			// check is singular and not Blog/Shop/Forum so we get the real post_meta
			if (!(RIGID_IS_WOOCOMMERCE && is_shop()) && !rigid_is_blog() && !(RIGID_IS_BBPRESS && bbp_is_forum_archive()) && is_singular()) {
				$specific_header_size = get_post_meta($wp_query->post->ID, 'rigid_header_size', true);
				$specific_footer_size = get_post_meta($wp_query->post->ID, 'rigid_footer_size', true);
				$specific_footer_style = get_post_meta($wp_query->post->ID, 'rigid_footer_style', true);
				$specific_layout = get_post_meta( $wp_query->post->ID, 'rigid_layout', true );
			} else {
				$specific_header_size = 'default';
				$specific_footer_size = 'default';
				$specific_footer_style = 'default';
				$specific_layout = 'default';
			}

			if ($specific_layout !== 'default') {
				$classes[] = sanitize_html_class($specific_layout);
			} else {
				$classes[] = sanitize_html_class($general_layout);
			}

			// logo and menu postions class
			$logo_menu_position = rigid_get_option('logo_menu_position');

			if ( $logo_menu_position != 'rigid_logo_left_menu_right' && ! in_array( 'rigid_header_left', $classes ) ) {
				$classes[] = sanitize_html_class( $logo_menu_position );
			}
			if ( $logo_menu_position == 'rigid_logo_left_menu_right' ) {
				$classes[] = sanitize_html_class( rigid_get_option( 'main_menu_alignment' ) );
			}

			// add left-header-scrollable if Scrollable is selected
			if (in_array('rigid_header_left', $classes)) {
				$classes[] = sanitize_html_class(rigid_get_option('left_header_setting'));
			}

			// header style
			if(isset($wp_query->post->ID)) {
				$is_header_style_meta = get_post_meta( $wp_query->post->ID, 'rigid_header_syle', true );
			} else {
				$is_header_style_meta = '';
			}
			$is_header_style_blog = rigid_get_option('blog_header_style');
			$is_header_style_shop = rigid_get_option('shop_header_style');
			$is_header_style_forum = rigid_get_option('forum_header_style');
			$is_header_style_events = rigid_get_option('events_header_style');

			if(RIGID_IS_WOOCOMMERCE && function_exists('get_woocommerce_term_meta') && is_product_category()) {
				$is_header_style_shop_category = get_woocommerce_term_meta($wp_query->queried_object_id , 'rigid_term_header_style', true );
            }

			$header_style_class = '';
			if ($is_header_style_blog && (rigid_is_blog() || is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())) {
				$header_style_class = $is_header_style_blog;
			} else if (RIGID_IS_WOOCOMMERCE && is_shop() && $is_header_style_shop) {
				$header_style_class = $is_header_style_shop;
			} else if (RIGID_IS_WOOCOMMERCE && is_product_category() && $is_header_style_shop_category) {
				$header_style_class = $is_header_style_shop_category;
			} else if (RIGID_IS_BBPRESS && bbp_is_forum_archive() && $is_header_style_forum) {
				$header_style_class = $is_header_style_forum;
			} else if (RIGID_IS_EVENTS && rigid_is_events_part() && !is_singular( 'tribe_events' )) {
				$header_style_class = $is_header_style_events;
			} else if (is_singular()) {
				$header_style_class = $is_header_style_meta;
			}

			if ($header_style_class) {
				// If more than one class stored
				$header_style_class_array = explode(' ', $header_style_class);

				foreach ($header_style_class_array as $class) {
					$classes[] = sanitize_html_class( $class );
				}
			}

			// if no header-top
			if (!rigid_get_option('enable_top_header')) {
				$classes[] = sanitize_html_class('rigid-no-top-header');
			}

			// footer reveal
			if (rigid_get_option('footer_style') && $specific_footer_style === 'default') {
				$classes[] = sanitize_html_class(rigid_get_option('footer_style'));
			} elseif ($specific_footer_style !== 'standard' && $specific_footer_style !== 'default') {
				$classes[] = sanitize_html_class($specific_footer_style);
			}

			// Header size
			if (rigid_get_option('header_width') && $specific_header_size === 'default') {
				$classes[] = sanitize_html_class(rigid_get_option('header_width'));
			} else if ($specific_header_size !== 'standard' && $specific_header_size !== 'default') {
				$classes[] = sanitize_html_class($specific_header_size);
			}

			// Footer size
			if (rigid_get_option('footer_width') && $specific_footer_size === 'default') {
				$classes[] = sanitize_html_class(rigid_get_option('footer_width'));
			} else if ($specific_footer_size !== 'standard' && $specific_footer_size !== 'default') {
				$classes[] = sanitize_html_class($specific_footer_size);
			}

			// Sub-menu color Scheme
			if (rigid_get_option('submenu_color_scheme')) {
				$classes[] = sanitize_html_class(rigid_get_option('submenu_color_scheme'));
			}

			// If using video background
			if (rigid_has_to_include_backgr_video()) {
				$classes[] = 'rigid-page-has-video-background';
			}

			// Shop and Category Pages Width
            if(rigid_get_option('shop_pages_width')) {
			    $classes[] = rigid_get_option('shop_pages_width');
            }

			// Blog and Category Pages Width
			if(rigid_get_option('blog_pages_width')) {
				$classes[] = rigid_get_option('blog_pages_width');
			}

			// return the $classes array
			return $classes;
		}

	}

	// Allow HTML descriptions in WordPress Menu (related to Mega menu)
	remove_filter('nav_menu_description', 'strip_tags');
	add_filter('wp_setup_nav_menu_item', 'rigid_setup_nav_menu_item');

	if (!function_exists('rigid_setup_nav_menu_item')) {

		function rigid_setup_nav_menu_item($menu_item) {
			if ($menu_item->db_id != 0) {
				$menu_item->description = apply_filters('nav_menu_description', $menu_item->post_content);
			}

			return $menu_item;
		}

	}

	if (!function_exists('rigid_post_nav')) {

		/**
		 * Returns output for the prev / next links on posts and portfolios
		 *
		 * @param bool|type $same_category
		 * @param string|type $taxonomy
		 * @return string
		 * @global type $wp_version
		 */
		function rigid_post_nav($same_category = false, $taxonomy = 'category') {
			global $wp_version;
			$excluded_terms = '';

			$type = get_post_type(get_queried_object_id());

			if (!is_singular() || is_post_type_hierarchical($type)) {
				$is_hierarchical = true;
			}

			if (!empty($is_hierarchical)) {
				return;
			}

			$entries = array();

			if (version_compare($wp_version, '3.8', '>=')) {
				$entries['prev'] = get_previous_post($same_category, $excluded_terms, $taxonomy);
				$entries['next'] = get_next_post($same_category, $excluded_terms, $taxonomy);
			} else {
				$entries['prev'] = get_previous_post($same_category);
				$entries['next'] = get_next_post($same_category);
			}

			$output = "";

			foreach ($entries as $key => $entry) {
				if (empty($entry)) {
					continue;
				}

				$the_title = rigid_generate_excerpt(get_the_title($entry->ID), 75, " ", " ", true, '', true);
				$link = get_permalink($entry->ID);
				$image = get_the_post_thumbnail($entry->ID, 'rigid-general-small-size');

				$tc1 = $tc2 = "";

				$output .= "<a class='rigid-post-nav rigid-post-{$key} ' href='" . esc_url($link) . "' >";
				$output .= "    <i class='fa fa-angle-" . ($key == 'prev' ? 'left' : 'right') . "'></i>";
				$output .= "    <span class='entry-info-wrap'>";
				$output .= "        <span class='entry-info'>";
				$tc1 = "            <span class='entry-title'>{$the_title}</span>";
				if ($image) {
					$tc2 = "            <span class='entry-image'>{$image}</span>";
				}
				$output .= $key == 'prev' ? $tc1 . $tc2 : $tc2 . $tc1;
				$output .= "        </span>";
				$output .= "    </span>";
				$output .= "</a>";
			}
			return $output;
		}

	}

	// Disable autoptimize for bbPress pages
	add_filter('autoptimize_filter_noptimize', 'rigid_bbpress_noptimize', 10, 0);
	if (!function_exists('rigid_bbpress_noptimize')) {

		function rigid_bbpress_noptimize() {
			global $post;
			if (function_exists('is_bbpress') && is_bbpress() || (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'bbp-forum-index'))) {
				return true;
			} else {
				return false;
			}
		}

	}

add_action('activate_the-events-calendar/the-events-calendar.php', 'rigid_set_skeleton_styles_events');

if (!function_exists('rigid_set_skeleton_styles_events')) {

	/**
	 * Set skeleton styles option upon The Events Calendar plugin activation
	 */
	function rigid_set_skeleton_styles_events() {
		$events_options = get_option('tribe_events_calendar_options');
		if(is_array($events_options)) {
			$events_options['stylesheetOption'] = 'skeleton';

			update_option('tribe_events_calendar_options', $events_options);
		}
	}
}

// Check for Rigid updates
add_action('admin_init', 'rigid_check_theme_update');
if (!function_exists('rigid_check_theme_update')) {

	function rigid_check_theme_update() {
		load_template(trailingslashit(get_template_directory()) . 'incl/auto-update-libs/envato-wp-theme-updater.php');

		if (rigid_get_option('envato_username') && rigid_get_option('envato_api_key')) {
			Envato_WP_Theme_Updater::init(rigid_get_option('envato_username'), rigid_get_option('envato_api_key'), 'theAlThemist');
		}
	}
}

// Remove &nbsp from titles
add_filter( 'the_title', 'rigid_remove_nbsp_from_titles', 10, 2 );
if ( ! function_exists( 'rigid_remove_nbsp_from_titles' ) ) {
	function rigid_remove_nbsp_from_titles( $title, $id ) {
		return str_replace( '&nbsp;', ' ', $title );
	}
}

//override date display with the time - ago
add_filter( 'the_time', 'rigid_convert_to_timeago_date_format', 10, 1 );

if ( ! function_exists( 'rigid_convert_to_timeago_date_format' ) ) {
	/**
     * Convert to time ago format
     *
	 * @param $orig_time
	 *
	 * @return string
	 */
	function rigid_convert_to_timeago_date_format( $orig_time ) {
		global $post;
		$post_unix_time = strtotime( $post->post_date );

		if (rigid_get_option('date_format') == 'rigid_format' && !rigid_is_time_more_than_x_months_ago(6, $post_unix_time)) {
			return human_time_diff( $post_unix_time, current_time( 'timestamp' ) ) . ' ' . __( 'ago', 'rigid' );
		}

		return $orig_time;
	}
}

if ( ! function_exists( 'rigid_is_time_x_months_ago' ) ) {
	/**
     * Return true if $unix_time is more than $months months ago than current time
     *
	 * @param $months
	 * @param $unix_time
	 *
	 * @return bool
	 */
	function rigid_is_time_more_than_x_months_ago( $months, $unix_time ) {

		$x_months_ago = strtotime("-".$months." months");

		if ( $unix_time >= $x_months_ago ) {
			return false;
		}

		return true;
	}
}