<?php
// Woocommerce specific functions
// Add support for woocommerce
add_theme_support('woocommerce');
add_theme_support( 'wc-product-gallery-zoom' );
add_theme_support( 'wc-product-gallery-lightbox' );
add_theme_support( 'wc-product-gallery-slider' );

/** @var $product WC_Product */

// Disable WooCommerce styles
if (version_compare(WOOCOMMERCE_VERSION, "2.1") >= 0) {
	add_filter('woocommerce_enqueue_styles', '__return_false');
} else {
	define('WOOCOMMERCE_USE_CSS', false);
}

/**
 * Overright WooCommerce Breadcrumb
 *
 * @access public
 * @return void
 */
function woocommerce_breadcrumb($args = array()) {
// If the breadcrumb is enabled
	if (rigid_get_option('show_breadcrumb')) {
		$args = wp_parse_args($args, apply_filters('woocommerce_breadcrumb_defaults', array(
				'delimiter' => ' <span class="rigid-breadcrumb-delimiter">/</span> ',
				'wrap_before' => '<div class="breadcrumb">',
				'wrap_after' => '</div>',
				'before' => '',
				'after' => '',
				'home' => esc_html__('Home', 'rigid')
		)));

		$breadcrumbs = new WC_Breadcrumb();

		if ($args['home']) {
			$breadcrumbs->add_crumb($args['home'], rigid_wpml_get_home_url());
		}

		$args['breadcrumb'] = $breadcrumbs->generate();

		wc_get_template('global/breadcrumb.php', $args);
	}
}

// removed breadcrumb from hook and call explicitly in wrapper-start
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

/**
 * Display the image part of the product in loop
 *
 * Takes into account product_hover_onproduct theme option
 */
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);

remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_filter('woocommerce_before_shop_loop_item', 'rigid_shop_loop_image', 10);

if ( ! function_exists( 'rigid_shop_loop_image' ) ) {

	function rigid_shop_loop_image() {
		global $post;
		echo '<div class="image">';

		?>

        <a href="<?php the_permalink(); ?>">
			<?php woocommerce_template_loop_product_thumbnail(); ?>
			<?php
			$second_image = rigid_get_second_product_image_id( $post );
			// If we have swap image enabled and second image:
			if ( rigid_get_option( 'product_hover_onproduct' ) == 'rigid-prodhover-swap' && $second_image ):
				?>
				<?php
				$image_size = apply_filters( 'single_product_archive_thumbnail_size', 'shop_catalog' );

				$props = wc_get_product_attachment_props( $second_image, $post );
				echo wp_get_attachment_image( $second_image, $image_size, false, array(
					'title' => $props['title'],
					'alt'   => $props['alt']
				) );
				?>
			<?php endif; ?>
        </a>
        <!-- Small countdown -->
		<?php rigid_shop_sale_countdown() ?>
		<?php
		echo '</div>';
	}

}

if ( ! function_exists( 'rigid_get_second_product_image_id' ) ) {
	/**
	 * Returns the second product image ID (if any)
	 * Else returns false
	 *
	 * @param mixed $post Post object or post ID of the product.
	 *
	 * @return int|bool false if no second image OR the attachment ID of the image
	 */
	function rigid_get_second_product_image_id( $post ) {
		$product  = wc_get_product( $post );
		$imageIds = $product->get_gallery_image_ids();

		if ( array_key_exists( 0, $imageIds ) ) {
			return $imageIds[0];
		}

		return false;
	}
}

/**
 * Checks if the product is in the new period
 *
 * @param WC_Product $product
 * @return boolean
 */
if (!function_exists('rigid_is_product_new')) {

	function rigid_is_product_new($product) {
		/** @var $product WC_Product */

		$days_product_is_new = rigid_get_option('new_label_period', 45);
		$post_date_dt = date_create($product->get_date_created());
		$curr_date_dt = date_create('now');
		$post_date_ts = $post_date_dt->format('Y-m-d');
		$curr_date_ts = $curr_date_dt->format('Y-m-d');

		$diff = abs(strtotime($post_date_ts) - strtotime($curr_date_ts));
		$diff/= 3600 * 24;

		if ($diff < $days_product_is_new) {
			return true;
		}

		return false;
	}

}

/**
 * Returns the "not sale" price.
 * Used by rigid_get_product_saving()
 *
 * @param WC_Product $product
 * @return type
 */
if (!function_exists('rigid_get_product_not_sale_price')) {

	function rigid_get_product_not_sale_price($product) {
		/** @var $product WC_Product */
		if($product->is_type('variable')) {
			return $product->get_variation_regular_price('min');
		} else {
			return $product->get_regular_price();
		}
	}

}

/**
 * Gets product saving
 *
 * @param WC_Product $product
 * @return type
 */
if (!function_exists('rigid_get_product_saving')) {

	function rigid_get_product_saving($product) {
		/** @var $product WC_Product */
		if ($product->is_on_sale()) {
			$sale_price = $product->get_price();
			$not_sale_price = rigid_get_product_not_sale_price($product);

			$saving = 100 - $sale_price / $not_sale_price * 100;

			return round($saving);
		}
	}

}

// Unload PrettyPhoto init for Woocommerce only
add_action('wp_enqueue_scripts', 'rigid_remove_wc_prettyphoto');

if (!function_exists('rigid_remove_wc_prettyphoto')) {

	function rigid_remove_wc_prettyphoto() {
		wp_dequeue_script('prettyPhoto-init');
	}

}

// remove result count showing on top of category
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);

// Display content holder
add_action('woocommerce_before_shop_loop', 'rigid_add_content_holder', 5);
if (!function_exists('rigid_add_content_holder')) {

	function rigid_add_content_holder() {

		echo '<div class="content_holder">';

		$style_class = 'columns-' . rigid_get_option('category_columns_num');

		if (rigid_get_option('enable_shop_cat_carousel')) {
			// owl carousel
			wp_localize_script('rigid-libs-config', 'rigid_owl_carousel_cat', array(
					'columns' => esc_js(rigid_get_option('category_columns_num'))
			));

			$style_class = 'owl-carousel rigid-owl-carousel';
		}

		// subcategories
		woocommerce_product_subcategories(array('before' => '<div class="rigid_woo_categories_shop woocommerce ' . esc_attr($style_class) . '">', 'after' => '</div>'));

		if (rigid_get_option('show_refine_area') && woocommerce_products_will_display()) {
			echo '<div class="box-sort-filter'.(is_active_sidebar('rigid_product_filters_sidebar') ? ' rigid-product-filters-has-widgets' : '' ).'">';
			echo '<h2 class="heading-title">' . esc_html__('Refine Products', 'rigid') . '</h2>';
			echo '<div class="product-filter">';
		}
	}

}

// Price filter on category pages
if (rigid_get_option('show_pricefilter', 1) && rigid_get_option('show_refine_area')) {
	add_action('woocommerce_before_shop_loop', 'rigid_price_filter', 10);
}

if (!function_exists('rigid_price_filter')) {

	function rigid_price_filter() {
		global $wp, $wp_the_query;

		if (!is_post_type_archive('product') && !is_tax(get_object_taxonomies('product'))) {
			return;
		}

		if (!$wp_the_query->post_count) {
			return;
		}

		$min_price = isset($_GET['min_price']) ? esc_attr($_GET['min_price']) : '';
		$max_price = isset($_GET['max_price']) ? esc_attr($_GET['max_price']) : '';

		wp_enqueue_style('jquery-ui');
		wp_enqueue_script( 'rigid-price-slider', get_template_directory_uri() . '/js/rigid-price-slider.js', array('jquery-ui-slider', 'wc-jquery-ui-touchpunch', 'accounting' ), false, true );
		wp_localize_script('rigid-price-slider', 'rigid_price_slider_params', array(
				'currency_symbol' => esc_js(get_woocommerce_currency_symbol()),
				'currency_pos' => esc_js(get_option('woocommerce_currency_pos')),
				'min_price' => esc_js($min_price),
				'max_price' => esc_js($max_price),
				'ajaxurl' => esc_js(admin_url('admin-ajax.php'))
		));

		// Remember current filters/search
		$fields = '';

		if (get_search_query()) {
			$fields .= '<input type="hidden" name="s" value="' . get_search_query() . '" />';
		}

		if (!empty($_GET['post_type'])) {
			$fields .= '<input type="hidden" name="post_type" value="' . esc_attr($_GET['post_type']) . '" />';
		}

		if (!empty($_GET['product_cat'])) {
			$fields .= '<input type="hidden" name="product_cat" value="' . esc_attr($_GET['product_cat']) . '" />';
		}

		if (!empty($_GET['product_tag'])) {
			$fields .= '<input type="hidden" name="product_tag" value="' . esc_attr($_GET['product_tag']) . '" />';
		}

		if (!empty($_GET['orderby'])) {
			$fields .= '<input type="hidden" name="orderby" value="' . esc_attr($_GET['orderby']) . '" />';
		}

		if (!empty($_GET['min_rating'])) {
			$fields .= '<input type="hidden" name="min_rating" value="' . esc_attr($_GET['min_rating']) . '" />';
		}

		if ($_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes()) {
			foreach ($_chosen_attributes as $attribute => $data) {
				$taxonomy_filter = 'filter_' . str_replace('pa_', '', $attribute);

				$fields .= '<input type="hidden" name="' . esc_attr($taxonomy_filter) . '" value="' . esc_attr(implode(',', $data['terms'])) . '" />';

				if ('or' == $data['query_type']) {
					$fields .= '<input type="hidden" name="' . esc_attr(str_replace('pa_', 'query_type_', $attribute)) . '" value="or" />';
				}
			}
		}

		// Find min and max price in current result set
		$prices = rigid_get_filtered_price();

		$min = floor($prices->min_price);
		$max = ceil($prices->max_price);

		if ($min === $max) {
			return;
		}

		if ('' === get_option('permalink_structure')) {
			$form_action = remove_query_arg(array('page', 'paged'), add_query_arg($wp->query_string, '', home_url($wp->request)));
		} else {
			$form_action = preg_replace('%\/page/[0-9]+%', '', home_url(trailingslashit($wp->request)));
		}

		/**
		 * Adjust max if the store taxes are not displayed how they are stored.
		 * Min is left alone because the product may not be taxable.
		 * Kicks in when prices excluding tax are displayed including tax.
		 */
		if (wc_tax_enabled() && 'incl' === get_option('woocommerce_tax_display_shop') && !wc_prices_include_tax()) {
			$tax_classes = array_merge(array(''), WC_Tax::get_tax_classes());
			$class_max = $max;

			foreach ($tax_classes as $tax_class) {
				if ($tax_rates = WC_Tax::get_rates($tax_class)) {
					$class_max = $max + WC_Tax::get_tax_total(WC_Tax::calc_exclusive_tax($max, $tax_rates));
				}
			}

			$max = $class_max;
		}

		echo '<form id="rigid-price-filter-form" method="get" action="' . esc_url($form_action) . '">
									<div id="price-filter" class="price_slider_wrapper">
										<div class="price_slider_amount">
												<input type="text" id="min_price" name="min_price" value="' . esc_attr($min_price) . '" data-min="' . esc_attr($min) . '" placeholder="' . esc_attr__('Min price', 'rigid') . '" />
												<input type="text" id="max_price" name="max_price" value="' . esc_attr($max_price) . '" data-max="' . esc_attr($max) . '" placeholder="' . esc_attr__('Max price', 'rigid') . '" />
												<div class="price_label" style="display:none;">
														<p>
																' . esc_html__('Price range:', 'rigid') . ' <span id="rigid_price_range"><span class="from"></span> &mdash; <span class="to"></span></span>
														</p>
												</div>
												' . $fields . '
												<div class="clear"></div>
										</div>
										<div class="price_slider" style="display:none;"></div>
								</div>
						</form>';
	}

}

if (!function_exists('rigid_get_filtered_price')) {

	function rigid_get_filtered_price() {
		global $wpdb, $wp_the_query;

		$args = $wp_the_query->query_vars;
		$tax_query = isset($args['tax_query']) ? $args['tax_query'] : array();
		$meta_query = isset($args['meta_query']) ? $args['meta_query'] : array();

		if (!empty($args['taxonomy']) && !empty($args['term'])) {
			$tax_query[] = array(
					'taxonomy' => $args['taxonomy'],
					'terms' => array($args['term']),
					'field' => 'slug',
			);
		}

		foreach ($meta_query as $key => $query) {
			if (!empty($query['price_filter']) || !empty($query['rating_filter'])) {
				unset($meta_query[$key]);
			}
		}

		$meta_query = new WP_Meta_Query($meta_query);
		$tax_query = new WP_Tax_Query($tax_query);

		$meta_query_sql = $meta_query->get_sql('post', $wpdb->posts, 'ID');
		$tax_query_sql = $tax_query->get_sql($wpdb->posts, 'ID');

		$sql = "SELECT min( CAST( price_meta.meta_value AS DECIMAL ) ) as min_price, max( CAST( price_meta.meta_value AS DECIMAL ) ) as max_price FROM {$wpdb->posts} ";
		$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
		$sql .= " 	WHERE {$wpdb->posts}.post_type = 'product'
					AND {$wpdb->posts}.post_status = 'publish'
					AND price_meta.meta_key IN ('" . implode("','", array_map('esc_sql', apply_filters('woocommerce_price_filter_meta_keys', array('_price')))) . "')
					AND price_meta.meta_value > '' ";
		$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

		return $wpdb->get_row($sql);
	}

}

add_action('woocommerce_after_shop_loop', 'rigid_wrap_after_shop_loop', 5);
if (!function_exists('rigid_wrap_after_shop_loop')) {

	function rigid_wrap_after_shop_loop() {
		echo '</div>'; // closes box-products
		echo '</div>'; // closes box-products container
	}

}
add_action('woocommerce_after_shop_loop', 'rigid_shop_sidebar', 15);
if (!function_exists('rigid_shop_sidebar')) {

	function rigid_shop_sidebar() {
		echo '</div>'; // closes content_holder
		if (rigid_get_option('show_sidebar_shop')) {
			do_action('woocommerce_sidebar');
			echo '<div class="clear"></div>';
		}
	}

}

add_action('woocommerce_before_shop_loop', 'rigid_wrap_before_shop_loop_after', 60);
if (!function_exists('rigid_wrap_before_shop_loop_after')) {

	function rigid_wrap_before_shop_loop_after() {
		$shop_default_product_columns = rigid_get_option('shop_default_product_columns');

		if (rigid_get_option('show_refine_area') && woocommerce_products_will_display()) {
		    // Define widget area here for filters
            if(is_active_sidebar('rigid_product_filters_sidebar')) {
	            echo '<div class="rigid-filter-widgets-holder">';
                    echo '<a title="'.esc_html__('More Filters', 'rigid').'" class="rigid-filter-widgets-triger" href="#"></a>';
                    echo '<div id="rigid-filter-widgets">';
                    dynamic_sidebar('rigid_product_filters_sidebar');
                    echo '</div>';
	            echo '</div>';
            }

			echo '<div class="clear"></div>';
			echo '</div>';
			echo '</div>';
		}

		echo '<div class="box-product-list">';
		echo '<div class="box-products woocommerce ' . esc_attr($shop_default_product_columns) . '">';
	}

}

// Changing products per page
add_filter('loop_shop_per_page', 'rigid_set_products_per_page', 20);

if (!function_exists('rigid_set_products_per_page')) {

	function rigid_set_products_per_page() {
		$per_page = rigid_get_option('products_per_page');
		if (array_key_exists('per_page', $_GET)) {
			$per_page = esc_attr($_GET['per_page']);
		}

		return $per_page;
	}

}

/**
 * Return the start and end sales dates for product on sale
 * If not on sale, return false
 *
 * @param type $post
 * @return boolean
 */
if (!function_exists('rigid_get_product_sales_dates')) {

	function rigid_get_product_sales_dates($post) {
		/** @var $product WC_Product */
		$start_sales_date = 9999999999;
		$end_sales_date = 0;

		$product = wc_get_product($post);
		if (!$product || !$product->is_on_sale())
			return false;

		$child_products = $product->get_children();
// If is variation product
		if (count($child_products)) {
			foreach ($child_products as $child_id) {
				$sale_price_dates_from = get_post_meta($child_id, '_sale_price_dates_from', true);
				$sale_price_dates_to = get_post_meta($child_id, '_sale_price_dates_to', true);

				if ($sale_price_dates_from && $sale_price_dates_from < $start_sales_date) {
					$start_sales_date = $sale_price_dates_from;
				}

				if ($sale_price_dates_to && $sale_price_dates_to > $end_sales_date) {
					$end_sales_date = $sale_price_dates_to;
				}
			}
		} else {
			$start_sales_date = get_post_meta($post->ID, '_sale_price_dates_from', true);
			$end_sales_date = get_post_meta($post->ID, '_sale_price_dates_to', true);
		}

		return array('from' => $start_sales_date, 'to' => $end_sales_date);
	}

}

// Show countdown for sales on product list
if (!function_exists('rigid_shop_sale_countdown')) {

	function rigid_shop_sale_countdown() {
		/**
		 * @var WC_Product $product
		 */
		global $post, $product;

		$sales_dates = rigid_get_product_sales_dates($post);
		$now = time();

		if (rigid_get_option('use_countdown', 'enabled') == 'enabled' && $product->is_on_sale() && $sales_dates['to'] && $now < $sales_dates['to']) {
			$random_num = uniqid();
			?>
			<div class="count_holder_small" data-countdown-id="<?php echo esc_js('#rigidCountSmallLatest'.$post->ID . $random_num)?>" data-countdown-to="<?php echo esc_js(date('F j, Y G:i:s', $sales_dates['to'])) ?>">
                <div class="count_info"><?php esc_html_e('Offer ends in', 'rigid') ?>:</div>
				<div id="rigidCountSmallLatest<?php echo esc_attr($post->ID . $random_num) ?>"></div>
				<div class="clear"></div>
			</div>
			<?php
		}
	}

}

// Show countdown for sales on the product page
add_filter('woocommerce_single_product_summary', 'rigid_product_sale_countdown', 7);

if (!function_exists('rigid_product_sale_countdown')) {

	function rigid_product_sale_countdown() {
		global $post, $product;

		$sales_dates = rigid_get_product_sales_dates($post);
		$now = time();

		if (rigid_get_option('use_countdown', 'enabled') == 'enabled' && $product->is_on_sale() && $sales_dates['to'] && $now < $sales_dates['to']) {
			$unique_id = uniqid('rigid_sale_countdown');
			?>
            <script type="text/javascript">
                <!--
                jQuery(function () { jQuery('#<?php echo esc_attr($unique_id)?>').countdown({until: new Date("<?php echo esc_js(date('F j, Y G:i:s', $sales_dates['to']))?>"), compact: false, layout: '<span class="countdown_time_tiny">{dn} {dl} {hn}:{mnn}:{snn}</span>'});});
                // -->
            </script>
			<div class="count_holder"> <span class="offer_title"><?php esc_html_e('Offer ends in', 'rigid') ?>:</span>
				<div id="<?php echo esc_attr($unique_id) ?>"></div>
                <div class="clear"></div>
				<?php if ($product->managing_stock()): ?>
					<div class="count_info"><?php echo wp_kses_post(sprintf(__('Hurry! Only <b>%u</b> left', 'rigid'), $product->get_stock_quantity())) ?></div>
				<?php endif; ?>
				<div class="count_info_left"><?php esc_html_e('Saving', 'rigid') ?>: <b><?php echo rigid_get_product_saving($product) ?>%</b></div>
				<div class="clear"></div>
			</div>
			<?php
		}
	}

}

// Wrap cart with div before
add_filter('woocommerce_before_cart_table', 'rigid_wrap_cart_before', 10);

if (!function_exists('rigid_wrap_cart_before')) {

	function rigid_wrap_cart_before() {
		echo '<div class="cart-info">';
	}

}

// Wrap cart with div after
add_filter('woocommerce_after_cart_table', 'rigid_wrap_cart_after', 10);

if (!function_exists('rigid_wrap_cart_after')) {

	function rigid_wrap_cart_after() {
		echo '</div>';
	}

}

// Ensure cart contents update when products are added to the cart via AJAX
add_filter('woocommerce_add_to_cart_fragments', 'rigid_header_add_to_cart_fragment');
if (!function_exists('rigid_header_add_to_cart_fragment')) {

	function rigid_header_add_to_cart_fragment($fragments) {
		ob_start();

		rigid_cart_link();

		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}

}

// Adding content holder (closed in content-single-product)
add_action('woocommerce_before_single_product_summary', 'rigid_insert_content_holder', 5);
if (!function_exists('rigid_insert_content_holder')) {

	function rigid_insert_content_holder() {
		echo '<div class="content_holder">';
	}

}

/**
 * Override woocommerce_taxonomy_archive_description
 * Show an archive description on taxonomy archives
 *
 * @return void
 */
function woocommerce_taxonomy_archive_description() {
	if (is_tax(array('product_cat', 'product_tag')) && get_query_var('paged') == 0) {
		$description = wpautop(do_shortcode(term_description()));

		$thumbnail_id = get_metadata( 'woocommerce_term', get_queried_object()->term_id, 'thumbnail_id', true );
		$image = wp_get_attachment_url($thumbnail_id);

		if ($description || $image) {
			if ($image) {
				$output = '<img class="pic-cat-main" src="' . esc_url($image) . '" alt="' . esc_attr(single_term_title('', false)) . '" />' . $description;
			} else {
				$output = $description;
			}

			echo '<div class="term-description fixed">' . $output . '</div>';
		}
	}
}

/**
 * Override the woocommerce function
 * Show a shop page description on product archives
 *
 * @subpackage	Archives
 */
function woocommerce_product_archive_description() {
	if (is_post_type_archive('product') && get_query_var('paged') == 0) {
		$shop_page = get_post(wc_get_page_id('shop'));
		if ($shop_page) {
			$description = wc_format_content($shop_page->post_content);
			if ($description) {
				echo '<div class="page-description fixed">' . $description . '</div>';
			}
		}
	}
}

// Cutting of the product title if exceeds 36 chars
if (!function_exists('rigid_short_product_title')) {

	function rigid_short_product_title($title, $short_title_length = 52) {

		if (mb_strlen($title) > $short_title_length) {
			return mb_substr($title, 0, $short_title_length - 3) . '...';
		}

		return $title;
	}

}

// Override Woocommerce Compare add link
// if Woocompare is activated
if (defined('YITH_WOOCOMPARE')) {
	global $yith_woocompare;

	$woocompareFrontEnd = $yith_woocompare->obj;
	remove_action('woocommerce_after_shop_loop_item', array($woocompareFrontEnd, 'add_compare_link'), 20);

	if (!function_exists('rigid_add_compare_link')) {

		function rigid_add_compare_link($product_id = false, $args = array()) {
			extract($args);

			global $yith_woocompare;
			$woocompareFrontEnd = $yith_woocompare->obj;

			if (!method_exists($woocompareFrontEnd, 'add_product_url')) {
				return false;
			}

			if (!$product_id) {
				global $product;
				$product_id = ($product->get_id()) && $product->exists() ? $product->get_id() : 0;
			}

			// return if product doesn't exist
			if (empty($product_id)) {
				return;
			}

			$is_button = !isset($button_or_link) || !$button_or_link ? get_option('yith_woocompare_is_button') : $button_or_link;

			if (!isset($button_text) || $button_text == 'default') {
				$button_text = get_option('yith_woocompare_button_text', esc_html__('Compare', 'rigid'));
				$button_text = function_exists('icl_translate') ? icl_translate('Plugins', 'plugin_yit_compare_button_text', $button_text) : $button_text;
			}

			printf('<a href="%s" class="%s" data-product_id="%d" title="%s"><i class="fa fa-tasks"></i></a>', esc_url($woocompareFrontEnd->add_product_url($product_id)), 'compare' . ( $is_button == 'button' ? ' button' : '' ), esc_attr($product_id), esc_attr($button_text));
		}

	}
}

// Move woocommerce_template_loop_price to be below the title
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);

// If related products are set to zero hide them
if(rigid_get_option('number_related_products') == 0) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
}

add_filter('woocommerce_output_related_products_args', 'rigid_related_products_args');
if (!function_exists('rigid_related_products_args')) {

	/**
	 * WooCommerce Extra Feature
	 * --------------------------
	 *
	 * Change number of related products on product page
	 * Set your own value for 'posts_per_page'
	 *
	 */
	function rigid_related_products_args($args) {

		$args['posts_per_page'] = rigid_get_option('number_related_products'); // number_related_products theme option
		$args['columns'] = 1; // arranged in 1 columns

		return $args;
	}

}

add_action('woocommerce_single_product_summary', 'rigid_add_this_share', 99);
if (!function_exists('rigid_add_this_share')) {

	/**
	 * Display share links on product pages
	 */
	function rigid_add_this_share() {
		if (function_exists('rigid_share_links')) {
			rigid_share_links( get_the_title(), get_permalink());
		}
	}

}

/**
 * Cart Link
 * Displayed a link to the cart including the number of items present and the cart total
 * @param  array $settings Settings
 * @return array           Settings
 */
if (!function_exists('rigid_cart_link')) {

	function rigid_cart_link() {
		if (is_cart()) {
			$class = 'current-menu-item';
		} else {
			$class = '';
		}
		?>
		<li class="<?php echo sanitize_html_class($class); ?>">
			<a id="rigid_quick_cart_link" class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_html_e('View your shopping cart', 'rigid'); ?>">
				<span class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
			</a>
		</li>
		<?php
	}

}

// Quickview ajax actions
if (!function_exists('rigid_quickview')) {

	function rigid_quickview() {
		global $post, $product;
		$prod_id = esc_attr($_POST["productid"]);
		$post = get_post($prod_id);
		$product = wc_get_product($prod_id);

		Rigid_WCVS();
		Rigid_WC_Variation_Swatches_Frontend::instance();

		ob_start();
		// We also need the wp.template for this script :)
		wc_get_template( 'single-product/add-to-cart/variation.php' );
		wc_get_template('content-single-product-rigid-quickview.php');

		$output = ob_get_contents();
		ob_end_clean();

		echo $output; // All dynamic data escaped

		die();
	}

}

add_action('wp_ajax_rigid_quickview', 'rigid_quickview');
add_action('wp_ajax_nopriv_rigid_quickview', 'rigid_quickview');

// Move description before title
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 6 );

// Ajax add to cart on product single
if (!function_exists('rigid_wc_add_cart_ajax')) {

	function rigid_wc_add_cart_ajax() {
		$product_id = $_POST['product_id'];
		$variation_id = isset($_POST['variation_id']) ? $_POST['variation_id'] : '';
		$quantity = $_POST['quantity'];

		if ($variation_id) {
			rigid_add_to_cart_handler_variable($product_id);
		} else {
			WC()->cart->add_to_cart( $product_id, $quantity);
		}

		return WC_AJAX::get_refreshed_fragments();
	}
}

add_action('wp_ajax_rigid_wc_add_cart', 'rigid_wc_add_cart_ajax');
add_action('wp_ajax_nopriv_rigid_wc_add_cart', 'rigid_wc_add_cart_ajax');

// Force variable attributes to show below the product
add_filter( 'woocommerce_product_variation_title_include_attributes', '__return_false' );

if (!function_exists('rigid_add_to_cart_handler_variable')) {
	/**
	 * Modified original WooCommerce add to cart for variables:
	 * add_to_cart_handler_variable
	 *
	 * @param $product_id
	 *
	 * @return bool
	 */
	function rigid_add_to_cart_handler_variable( $product_id ) {
		$adding_to_cart     = wc_get_product( $product_id );
		$variation_id       = empty( $_REQUEST['variation_id'] ) ? '' : absint( $_REQUEST['variation_id'] );
		$quantity           = empty( $_REQUEST['quantity'] ) ? 1 : wc_stock_amount( $_REQUEST['quantity'] );
		$missing_attributes = array();
		$variations         = array();
		$attributes         = $adding_to_cart->get_attributes();

		// If no variation ID is set, attempt to get a variation ID from posted attributes.
		if ( empty( $variation_id ) ) {
			$data_store   = WC_Data_Store::load( 'product' );
			$variation_id = $data_store->find_matching_product_variation( $adding_to_cart, wp_unslash( $_POST ) );
		}

		// Validate the attributes.
		try {
			if ( empty( $variation_id ) ) {
				throw new Exception( __( 'Please choose product options&hellip;', 'rigid' ) );
			}

			$variation_data = wc_get_product_variation_attributes( $variation_id );

			foreach ( $attributes as $attribute ) {
				if ( ! $attribute['is_variation'] ) {
					continue;
				}

				$taxonomy = 'attribute_' . sanitize_title( $attribute['name'] );

				if ( isset( $_REQUEST[ $taxonomy ] ) ) {
					// Get value from post data
					if ( $attribute['is_taxonomy'] ) {
						// Don't use wc_clean as it destroys sanitized characters
						$value = sanitize_title( stripslashes( $_REQUEST[ $taxonomy ] ) );
					} else {
						$value = wc_clean( stripslashes( $_REQUEST[ $taxonomy ] ) );
					}

					// Get valid value from variation
					$valid_value = isset( $variation_data[ $taxonomy ] ) ? $variation_data[ $taxonomy ] : '';

					// Allow if valid or show error.
					if ( $valid_value === $value ) {
						$variations[ $taxonomy ] = $value;
						// If valid values are empty, this is an 'any' variation so get all possible values.
					} elseif ( '' === $valid_value && in_array( $value, $attribute->get_slugs() ) ) {
						$variations[ $taxonomy ] = $value;
					} else {
						throw new Exception( sprintf( __( 'Invalid value posted for %s', 'rigid' ), wc_attribute_label( $attribute['name'] ) ) );
					}
				} else {
					$missing_attributes[] = wc_attribute_label( $attribute['name'] );
				}
			}
			if ( ! empty( $missing_attributes ) ) {
				throw new Exception( sprintf( _n( '%s is a required field', '%s are required fields', sizeof( $missing_attributes ), 'rigid' ), wc_format_list_of_items( $missing_attributes ) ) );
			}
		} catch ( Exception $e ) {
			wc_add_notice( $e->getMessage(), 'error' );

			return false;
		}

		// Add to cart validation
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations );

		if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations ) !== false ) {
			return true;
		}

		return false;
	}
}