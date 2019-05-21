<?php
defined('ABSPATH') || die();

// Include shortcodes classes
// If WCMp is active
if (is_plugin_active('dc-woocommerce-multi-vendor/dc_product_vendor.php') || class_exists('WCMp')) {
    require_once( plugin_dir_path(__FILE__) . 'incl/RigidShortcodeVendorList.php' );
    add_shortcode('rigid_wcmp_vendorslist', array('RigidShortcodeVendorList', 'output'));
}

if (defined('WPB_VC_VERSION')) {
	VcShortcodeAutoloader::getInstance()->includeClass('WPBakeryShortCode_VC_Tta_Tabs');

	class WPBakeryShortCode_Rigid_Content_Slider extends WPBakeryShortCode_VC_Tta_Tabs {

		public $layout = 'tabs';

		public function getTtaContainerClasses() {
			$classes = parent::getTtaContainerClasses();

			$classes .= ' vc_tta-o-non-responsive';

			return $classes;
		}

		public function getTtaGeneralClasses() {
			$classes = parent::getTtaGeneralClasses();

			$classes .= ' vc_tta-pageable';

			// tabs have pagination on opposite side of tabs. pageable should behave normally
			if (false !== strpos($classes, 'vc_tta-tabs-position-top')) {
				$classes = str_replace('vc_tta-tabs-position-top', 'vc_tta-tabs-position-bottom', $classes);
			} else {
				$classes = str_replace('vc_tta-tabs-position-bottom', 'vc_tta-tabs-position-top', $classes);
			}

			return $classes;
		}

		/**
		 * Disable all tabs
		 *
		 * @param $atts
		 * @param $content
		 *
		 * @return string
		 */
		public function getParamTabsList($atts, $content) {
			return '';
		}

		public function getFileName() {
			return 'vc_rigid_content_slider';
		}

	}

}


/**
 * Define rigid_counter shortcode
 */
if (!function_exists('rigid_counter_shortcode')) {

	function rigid_counter_shortcode($atts) {

		// Attributes
		extract(shortcode_atts(
										array(
				'txt_before_counter' => '',
				'count_number' => '10',
				'txt_after_counter' => '',
				'add_icon' => 'false',
				'counter_style' => 'h4',
				'counter_alignment' => 'rigid-counter-left',
				'text_color' => '',
				'i_type' => 'fontawesome',
				'i_icon_fontawesome' => 'fa fa-adjust',
				'i_icon_openiconic' => 'vc-oi vc-oi-dial',
				'i_icon_typicons' => 'typcn typcn-adjust-brightness',
				'i_icon_entypo' => 'entypo-icon entypo-icon-note',
				'i_icon_linecons' => 'vc_li vc_li-heart',
				'i_icon_etline' => 'icon-mobile',
				'i_custom_color' => '',
										), $atts)
		);

		$iconClass = '';

		if (!empty($add_icon) && 'true' === $add_icon) {
			if (isset(${'i_icon_' . $i_type})) {
				$iconClass = ${'i_icon_' . $i_type};
			}
			vc_icon_element_fonts_enqueue($i_type);
		}

		$icon_color = '';
		if ($i_custom_color) {
			$icon_color = $i_custom_color;
		}

		ob_start();
		?>
		<div class="rigid-counter-shortcode">
			<div class="rigid-counter-content  rigid-counter-<?php echo esc_attr($counter_style) ?> <?php echo sanitize_html_class($counter_alignment) ?>" <?php if ($text_color): ?> style="color:<?php echo esc_attr($text_color) ?>" <?php endif; ?> >
				<?php echo esc_html($txt_before_counter) ?>
				<?php if ($iconClass): ?>
					<i class="<?php echo esc_attr($iconClass) ?>" <?php if ($icon_color && $icon_color !== 'custom'): ?> style="color:<?php echo esc_attr($icon_color) ?>" <?php endif; ?>></i>
				<?php endif; ?>
				<?php if (is_numeric($count_number)): ?>
					<span class="rigid-counter"><?php echo esc_html($count_number) ?></span>
				<?php endif; ?>
				<?php echo esc_html($txt_after_counter) ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

}
add_shortcode('rigid_counter', 'rigid_counter_shortcode');

/**
 * Define rigid_typed shortcode
 */
if (!function_exists('rigid_typed_shortcode')) {

	function rigid_typed_shortcode($atts) {

		// Attributes
		extract(shortcode_atts(
										array(
				'txt_before_typed' => '',
				'rotating_strings' => 'One,Two,Tree',
				'txt_after_typed' => '',
				'typed_style' => 'h4',
				'typed_alignment' => 'rigid-typed-left',
				'static_text_color' => '',
				'typed_text_color' => '',
				'loop' => 'yes',
				'el_class' => '',
				'css' => '',
										), $atts)
		);

		$unique_id = uniqid('rigid_typed');

		// css from Design options
		$css_design_class = '';
		if(defined('VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG')) {
			$css_design_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '), 'rigid_typed', $atts);
		}

		$rotating_strings_arr = explode(',', $rotating_strings);
		ob_start();
		?>
		<div class="rigid-typed-shortcode">
		<div class="rigid-typed-content rigid-typed-<?php echo esc_attr($typed_style) ?> <?php echo sanitize_html_class($typed_alignment) ?><?php echo ($css_design_class ? ' ' . esc_attr($css_design_class) : '') ?>" <?php if ($static_text_color): ?> style="color:<?php echo esc_html($static_text_color) ?>" <?php endif; ?> >
			<?php echo esc_html($txt_before_typed) ?>
			<span id="<?php echo esc_attr($unique_id) ?>" class="rigid-typed"  <?php if ($typed_text_color): ?> style="color:<?php echo esc_html($typed_text_color) ?>" <?php endif; ?>></span>
			<?php echo esc_html($txt_after_typed) ?>
		</div>

		</div>
		<?php if (is_array($rotating_strings_arr) && count($rotating_strings_arr) > 1 && $rotating_strings_arr[0] != ''): ?>
			<script type="text/javascript">
				//<![CDATA[
				(function ($) {
					"use strict";
					$(document).ready(function () {
						$("#<?php echo esc_js($unique_id) ?>").typed({
							strings: [<?php
			foreach ($rotating_strings_arr as $str):
				echo '"' . esc_js($str) . '",';
			endforeach;
			?>],
							typeSpeed: 60,
							// time before typing starts
							startDelay: 1000,
							// backspacing speed
							backSpeed: 20,
							// delay before deleting last string
							backDelay: 1800,
							// MUST BE OPTIONAL TRUE/FALSE
							loop: <?php echo esc_js($loop == 'yes' ? 'true' : 'false') ?>,
							showCursor: true
						});
					})
				})(window.jQuery);
				//]]>
			</script>
		<?php endif; ?>
		<?php
		return ob_get_clean();
	}

}
add_shortcode('rigid_typed', 'rigid_typed_shortcode');


/**
 * Define rigidblogposts shortcode
 */
if (!function_exists('rigid_blogposts_shortcode')) {

	function rigid_blogposts_shortcode($atts) {

		// Attributes
		extract(shortcode_atts(
										array(
				'blog_style' => '',
				'date_sort' => 'default',
				'number_of_posts' => '',
				'offset' => ''
										), $atts), EXTR_PREFIX_ALL, 'rigid_blogposts_param'
		);

		if (is_front_page()) {
			$paged = (get_query_var('page')) ? get_query_var('page') : 1;
		} else {
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		}
		$query_args = array(
				'paged' => $paged,
				'post_type' => 'post'
		);

		// If defined sort order
		if ($rigid_blogposts_param_date_sort != 'default') {
			$query_args['order'] = $rigid_blogposts_param_date_sort;
		}
		// Posts per page
		if ($rigid_blogposts_param_number_of_posts != '') {
			$query_args['posts_per_page'] = $rigid_blogposts_param_number_of_posts;
		}
		// Offset
		if ($rigid_blogposts_param_offset != '') {
			$query_args['offset'] = $rigid_blogposts_param_offset;
		}

		// The query
		query_posts($query_args);

		switch ($rigid_blogposts_param_blog_style) {
			case 'rigid_blog_masonry':
				// load Isotope
				wp_enqueue_script('isotope');
				// Isotope settings
				ob_start();
				?>
				<script type="text/javascript">
					//<![CDATA[
					(function ($) {
						"use strict";
						$(window).load(function () {
							$('.rigid_blog_masonry', '#main').isotope({
								itemSelector: '#main div.blog-post'
							});
						});
					})(window.jQuery);
					//]]>
				</script>
				<?php
				echo ob_get_clean();
				break;
		}

		$output = '<div class="rigid_shortcode_blog ' . esc_attr($rigid_blogposts_param_blog_style) . '">';

		if (have_posts()) {
			while (have_posts()) {
				the_post();
				// Capture each post
				ob_start();

				include(locate_template('content.php'));

				$output .= ob_get_clean();
			}
		}

		$output .= '</div>';

		// Capture the pagination
		ob_start();
		?>

		<!-- PAGINATION -->
		<div class="box box-common">
			<?php
			if (function_exists('rigid_pagination')) :
				rigid_pagination();
			else :
				?>
				<div class="navigation group">
					<div class="alignleft"><?php next_posts_link(esc_html__('Next &raquo;', 'rigid-plugin')) ?></div>
					<div class="alignright"><?php previous_posts_link(esc_html__('&laquo; Back', 'rigid-plugin')) ?></div>
				</div>

			<?php endif; ?>
		</div>
		<!-- END OF PAGINATION -->

		<?php
		$output .= ob_get_clean();

		wp_reset_query();

		return $output;
	}

}
add_shortcode('rigidblogposts', 'rigid_blogposts_shortcode');

/**
 * Define rigid_latest_projects shortcode
 */
if (!function_exists('rigid_latest_projects_shortcode')) {

	function rigid_latest_projects_shortcode($atts) {
		global $wp;
		// Attributes
		extract(shortcode_atts(
										array(
				'display_style' => 'grid',
				'columns' => '4',
				'taxonomies' => '',
				'none_overlay' => '',
				'portfoio_cat_display' => '',
				'show_lightbox' => 'no',
				'enable_sortable' => 'no',
				'enable_masonry' => 'no',
				'limit' => '4',
				'use_pagination' => 'no',
				'use_load_more' => 'no',
				'offset' => '',
				'date_sort' => 'DESC',
				'css' => ''
										), $atts));

		// css from Design options
		$css_design_class = '';
		if(defined('VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG')) {
			$css_design_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '), 'rigid_latest_projects', $atts);
		}

		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$get_portfolio_args = array(
				'orderby' => 'date',
				'order' => $date_sort,
				'post_type' => 'rigid-portfolio',
				'post_status' => 'publish'
		);

		// Number of projects
		if ($limit) {
			$get_portfolio_args['posts_per_page'] = $limit;
		} elseif ($display_style !== 'carousel') {
			$get_portfolio_args['paged'] = $paged;
		} else {
			$get_portfolio_args['nopaging'] = true;
		}

		// Offset
		if ($offset) {
			$get_portfolio_args['offset'] = $offset;
		}

		// Filter by category
		if ($taxonomies) {
			$get_portfolio_args['tax_query'] = array(
					array(
							'taxonomy' => 'rigid_portfolio_category',
							'field' => 'term_id',
							'terms' => explode(',', $taxonomies),
					),
			);
		}

		if ($portfoio_cat_display == 'yes') {
			$portfoio_cat_display = 'rigid-10px-gap';
		}

		$projects = new WP_Query($get_portfolio_args);

		add_filter('excerpt_length', 'rigid_portfolio_custom_excerpt_length', 999);
		add_filter('excerpt_more', 'rigid_new_excerpt_more');

		$project_unit_class = 'grid-unit';

		$unique_id = uniqid('latest_projects');
		$thumb_size = 'rigid-portfolio-category-thumb';

		wp_enqueue_script('isotope');

		// If style is masonary no crop on images
		if ($display_style !== 'carousel') {
			if ($enable_masonry == 'yes') {
				$thumb_size = 'rigid-portfolio-category-thumb-real';
				$project_unit_class = 'masonry-unit';
			}
			?>
			<script type="text/javascript">
				//<![CDATA[
				(function ($) {
					"use strict";
					$(document).ready(function () {

						var $container = $('#<?php echo esc_attr($unique_id) ?> div.rigid-portfolio-shortcode-container');
						var $shortcode = $('#<?php echo esc_attr($unique_id) ?>');

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
						$container.prev('div.rigid-portfolio-categories').on('click', 'a', function () {
							var filterValue = $(this).attr('data-filter');
							// use filterFn if matches value
							$container.isotope({filter: filterValue});
						});

						// change is-checked class on buttons
						$container.prev('div.rigid-portfolio-categories').each(function (i, buttonGroup) {
							var $buttonGroup = $(buttonGroup);
							$buttonGroup.on('click', 'a', function () {
								$buttonGroup.find('.is-checked').removeClass('is-checked');
								$(this).addClass('is-checked');
							});
						});

						// Add magnific function
                        $isotopedGrid.isotope('on', 'layoutComplete',
                            function (isoInstance, laidOutItems) {
                                $isotopedGrid.find('a.portfolio-lightbox-link').magnificPopup({
									mainClass: 'mfp-fade',
									type: 'image'
								});
                            }
                        );

                        var $pagination = $shortcode.find('div.pagination');

						<?php if($use_pagination !== 'yes' && !$limit): ?>
                             // Infinite scroll, so hide the pagination
                             $pagination.hide();

                            <?php if($use_load_more == 'yes'): ?>
                                $shortcode.on('click', 'button.rigid-load-more', function (e) {
                                    $(this).hide();
                                    $pagination.find('a.next_page').click();
                                });
                            <?php else: ?>
                                // Track scrolling, hunting for infinite ajax load
                                $(window).on("scroll", function () {
                                    if ($shortcode.find('div.portfolio-nav.rigid-infinite').is(':in-viewport')) {
                                        $pagination.find('a.next_page').click();
                                    }
                                });
                            <?php endif; ?>

                             $shortcode.on('click', 'a.next_page', function (e) {
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
                                            $shortcode.find('button.rigid-load-more').hide();
                                        } else {
                                            $shortcode.find('button.rigid-load-more').show();
                                        }
                                    }
                                );
                            });
                        <?php endif; ?>

					});
				})(window.jQuery);
				//]]>
			</script>
			<?php
		}

		ob_start();
		?>
		<?php if ($projects->have_posts()): ?>
			<?php
			if ($display_style === 'carousel'):
				?>
				<script type="text/javascript">
					//<![CDATA[
					(function ($) {
						"use strict";
						$(window).load(function () {
							jQuery("#<?php echo esc_js($unique_id) ?> div.rigid-portfolio-shortcode-container").owlCarousel({
								rtl: <?php echo is_rtl() ? 'true' : 'false'; ?>,
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
										items: 4,
									},
									1280: {
										items:<?php echo esc_js($columns) ?>,
									}
								},
								dots: false,
								loop: false,
								nav: true,
								navText: [
									"<i class='fa fa-angle-left'></i>",
									"<i class='fa fa-angle-right'></i>"
								],
							});
						});
					})(window.jQuery);
					//]]>
				</script>
			<?php endif; ?>
			<div id="<?php echo esc_attr($unique_id) ?>" class="rigid-portfolio-shortcode<?php echo ($css_design_class ? ' ' . esc_attr($css_design_class) : '') ?>">
				<?php
				if ($display_style !== 'carousel' && $enable_sortable == 'yes'):

					$portfolio_categories = array();
					$portfolio_categories_unique = array();

					if ($taxonomies) {
						$taxonomies_arr = explode(',', $taxonomies);
						if (is_array($taxonomies_arr) && !empty($taxonomies_arr)) {
							foreach ($taxonomies_arr as $tax_id) {
								$tax = get_term((int) $tax_id, 'rigid_portfolio_category');
								if ($tax instanceof WP_Term) {
									$portfolio_categories_unique[$tax_id] = $tax;
								}
							}
						}
					} else {
						while ($projects->have_posts()) {
							$projects->the_post();

							$current_prtfl_terms = get_the_terms(get_the_ID(), 'rigid_portfolio_category');
							if(is_array($current_prtfl_terms))  {
							    $portfolio_categories = array_merge($portfolio_categories, $current_prtfl_terms);
							}
						}

						$projects->rewind_posts();
						foreach ($portfolio_categories as $cat) {
							$portfolio_categories_unique[$cat->term_id] = $cat;
						}
					}
					?>
					<div class="rigid-portfolio-categories">
						<ul>
							<li><a class="is-checked" data-filter="*" href="#"><?php esc_html_e('show all', 'rigid-plugin') ?></a></li>
							<?php foreach ($portfolio_categories_unique as $category): ?>
								<li><a data-filter=".<?php echo esc_attr($category->slug) ?>" href="#"><?php echo esc_html($category->name) ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
				<div class="rigid-portfolio-shortcode-container<?php if ($display_style === 'carousel'): ?> owl-carousel rigid-owl-carousel<?php endif; ?>">
				<?php endif; ?>

				<?php while ($projects->have_posts()): ?>
					<?php $projects->the_post(); ?>
					<?php
					// client name
				    $rigid_similar_client_name = get_post_meta(get_the_ID(), 'rigid_client', true);

					$current_terms = get_the_terms(get_the_ID(), 'rigid_portfolio_category');
					$current_terms_as_simple_array = array();
					$current_terms_as_classes = array();

					if ($current_terms) {
						foreach ($current_terms as $term) {
							$current_terms_as_simple_array[] = $term->name;
							$current_terms_as_classes[] = $term->slug;
						}
					}

					$featured_image_attr = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
					$featured_image_src = '';
					if ($featured_image_attr) {
						$featured_image_src = $featured_image_attr[0];
					}

					if (in_array($display_style, array('grid', 'carousel'))) {
						$grid_columns_class = 'portfolio-col-' . $columns;
						// None overlay
						if ($none_overlay == 'yes') {
							$grid_columns_class .= ' rigid-none-overlay';
						}
					} elseif ($display_style === 'list') {
						$project_unit_class = 'list-unit';
						$grid_columns_class = '';
					}
					?>
					<div class="portfolio-unit <?php echo esc_attr($grid_columns_class) ?> <?php echo esc_attr(implode(' ', $current_terms_as_classes)) ?> <?php echo esc_attr($portfoio_cat_display) ?> <?php echo esc_attr($project_unit_class) ?>">
						<div class="portfolio-unit-holder">
							<!-- LIST -->
							<?php if ($display_style === 'list'): ?>
								<div class="port-unit-image-holder">
									<a title="<?php esc_html_e('View project', 'rigid-plugin') ?>" href="<?php the_permalink(); ?>" class="portfolio-link">
										<?php if (has_post_thumbnail()): ?>
											<?php the_post_thumbnail($thumb_size); ?>
										<?php else: ?>
											<img src="<?php echo RIGID_PLUGIN_IMAGES_PATH . 'cat_not_found.png' ?>" />
										<?php endif; ?>
									</a>
								</div>
								<div class="portfolio-unit-info">
									<a title="<?php esc_html_e('View project', 'rigid-plugin') ?>" href="<?php the_permalink(); ?>" class="portfolio-link">
										<small>
                                            <?php if ( $rigid_similar_client_name ): ?>
                                                <span class=”rigid-client-name”><?php echo esc_html( $rigid_similar_client_name ) ?> </span>
                                            <?php endif; ?>
                                            <?php the_time( get_option( 'date_format' ) ); ?>
                                        </small>
										<h4><?php the_title(); ?></h4>
									</a>
									<?php if ($featured_image_src && $show_lightbox === 'yes'): ?>
										<a class="portfolio-lightbox-link" href="<?php echo esc_url($featured_image_src) ?>"><span></span></a>
									<?php endif; ?>
									<?php $short_description = get_post_meta(get_the_ID(), 'rigid_add_description', true); ?>
									<?php if ($short_description): // If has short description - show it, else the excerpt   ?>
										<p><?php echo wp_trim_words($short_description, 40, rigid_new_excerpt_more('no_hash')); ?></p>
									<?php elseif (get_the_content()): ?>
										<p><?php the_excerpt(); ?></p>
									<?php endif; ?>
									<?php if ($current_terms): ?>
										<h6><?php echo implode(' / ', $current_terms_as_simple_array) ?></h6>
									<?php endif; ?>
								</div>
								<!-- GRID and MASONRY -->
							<?php elseif (in_array($display_style, array('grid', 'carousel'))): ?>
								<?php if (has_post_thumbnail()): ?>
									<?php the_post_thumbnail($thumb_size); ?>
								<?php else: ?>
									<img src="<?php echo esc_url(RIGID_PLUGIN_IMAGES_PATH . 'cat_not_found.png') ?>" />
								<?php endif; ?>
								<div class="portfolio-unit-info">
									<a title="<?php esc_html_e('View project', 'rigid-plugin') ?>" href="<?php the_permalink(); ?>" class="portfolio-link">
										<small>
                                            <?php if ( $rigid_similar_client_name ): ?>
                                                <span class=”rigid-client-name”><?php echo esc_html( $rigid_similar_client_name ) ?> </span>
                                            <?php endif; ?>
                                            <?php the_time( get_option( 'date_format' ) ); ?>
                                        </small>
										<h4><?php the_title(); ?></h4>
										<?php if ($current_terms): ?>
											<h6><?php echo implode(' / ', $current_terms_as_simple_array) ?></h6>
										<?php endif; ?>
									</a>
									<?php if ($featured_image_src && $show_lightbox === 'yes'): ?>
										<a class="portfolio-lightbox-link" href="<?php echo esc_url($featured_image_src) ?>"><span></span></a>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endwhile; ?>
				<?php remove_filter('excerpt_length', 'rigid_portfolio_custom_excerpt_length') ?>
				<?php remove_filter('excerpt_more', 'rigid_new_excerpt_more'); ?>
				<?php wp_reset_postdata(); ?>

				<?php if ($projects->have_posts()): ?>
				</div>
				<div class="clear"></div>
				<?php if (!$limit && $display_style !== 'carousel'): ?>
					<!-- PAGINATION -->
					<div class="box box-common portfolio-nav rigid-enabled<?php if($use_pagination == 'no') echo ' rigid-infinite'; ?>">
					    <?php if ($use_pagination == 'no'): ?>
                            <div class="rigid-page-load-status">
                                <p class="infinite-scroll-request"><?php esc_html_e('Loading', 'rigid-plugin'); ?>...</p>
                                <p class="infinite-scroll-last"><?php esc_html_e('End of content', 'rigid-plugin'); ?></p>
                            </div>
                            <?php if($use_load_more == 'yes'): ?>
                                <div class="rigid-load-more-container">
                                    <button class="rigid-load-more button"><?php esc_html_e( 'Load More', 'rigid-plugin' ); ?></button>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
						<?php if (function_exists('rigid_pagination')) : rigid_pagination('', $projects); ?>
						<?php else : ?>

							<div class="navigation group">
								<div class="alignleft next-page-portfolio"><?php next_posts_link(esc_html__('Next &raquo;', 'rigid-plugin')) ?></div>
								<div class="alignright prev-page-portfolio"><?php previous_posts_link(esc_html__('&laquo; Back', 'rigid-plugin')) ?></div>
							</div>

						<?php endif; ?>
					</div>
					<!-- END OF PAGINATION -->
				<?php endif; ?>
			</div>
			<?php
		endif;

		$output = ob_get_clean();

		return $output;
	}

}
add_shortcode('rigid_latest_projects', 'rigid_latest_projects_shortcode');

/**
 * Define rigid_latest_posts shortcode
 */
if (!function_exists('rigid_latest_posts_shortcode')) {

	function rigid_latest_posts_shortcode($atts) {

		// Attributes
		extract(shortcode_atts(
										array(
				'columns' => '4',
				'taxonomies' => '',
				'layout' => 'grid',
				'number_of_posts' => '4',
				'offset' => '',
				'date_sort' => 'default',
				'css' => ''
										), $atts), EXTR_PREFIX_ALL, 'rigid_blogposts_param'
		);

		// css from Design options
		$css_design_class = '';
		if(defined('VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG')) {
			$css_design_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($rigid_blogposts_param_css, ' '), 'rigid_latest_posts', $atts);
		}

		$query_args = array(
				'post_type' => 'post',
				'ignore_sticky_posts' => 1
		);

		// Filter by category
		if ($rigid_blogposts_param_taxonomies) {
			$query_args['tax_query'] = array(
					array(
							'taxonomy' => 'category',
							'field' => 'term_id',
							'terms' => explode(',', $rigid_blogposts_param_taxonomies),
					),
			);
		}

		// If defined sort order
		if ($rigid_blogposts_param_date_sort != 'default') {
			$query_args['order'] = $rigid_blogposts_param_date_sort;
		}
		// Posts per page
		if ($rigid_blogposts_param_number_of_posts != '') {
			$query_args['posts_per_page'] = $rigid_blogposts_param_number_of_posts;
		}
		// Offset
		if ($rigid_blogposts_param_offset != '') {
			$query_args['offset'] = $rigid_blogposts_param_offset;
		}

		$rigid_is_latest_posts = true;

		// The query
		query_posts($query_args);

		$js_config_output = '';

		$unique_id = uniqid('latest_posts');

		$layout_class = '';
		switch ($rigid_blogposts_param_layout) {
			case 'grid':
				$layout_class = 'rigid-latest-grid';
				break;
			case 'carousel':
				$layout_class = 'owl-carousel rigid-owl-carousel';
				break;
		}

		if ($rigid_blogposts_param_layout === 'carousel') {
			ob_start();
			?>
			<script type="text/javascript">
				//<![CDATA[
				(function ($) {
					"use strict";
					$(window).load(function () {
						jQuery("#<?php echo esc_js($unique_id) ?>").owlCarousel({
							rtl: <?php echo is_rtl() ? 'true' : 'false'; ?>,
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
									items: 4,
								},
								1280: {
									items: <?php echo esc_js($rigid_blogposts_param_columns) ?>,
								}
							},
							dots: false,
							nav: true,
							navText: [
								"<i class='fa fa-angle-left'></i>",
								"<i class='fa fa-angle-right'></i>"
							],
						});
					});
				})(window.jQuery);
				//]]>
			</script>
			<?php
			$js_config_output = ob_get_clean();
		}

		// Classes
		$shortcode_classes = array('rigid_shortcode_latest_posts', 'rigid_blog_masonry', 'rigid-latest-blog-col-' . $rigid_blogposts_param_columns, $layout_class, $css_design_class);

		$output = '<div id="' . esc_attr($unique_id) . '" class="' . esc_attr(implode(' ', $shortcode_classes)) . '">';

		if (have_posts()) {
			while (have_posts()) {
				the_post();
				// Capture each post
				ob_start();

				include(locate_template('content.php'));

				$output .= ob_get_clean();
			}
		}

		$output .= '</div>';

		wp_reset_query();

		return $js_config_output . $output;
	}

}
add_shortcode('rigid_latest_posts', 'rigid_latest_posts_shortcode');

/**
 * Define rigid_banner shortcode
 */
if (!function_exists('rigid_banner_shortcode')) {

	function rigid_banner_shortcode($atts) {

		// Attributes
		extract(shortcode_atts(
										array(
				'type' => 'fontawesome',
				'icon_fontawesome' => '',
				'icon_openiconic' => '',
				'icon_typicons' => '',
				'icon_entypoicons' => '',
				'icon_linecons' => '',
				'icon_entypo' => '',
				'icon_etline' => '',
				'alignment' => 'banner-center-center',
				'image_id' => '',
				'title' => '',
				'title_size' => '',
				'subtitle' => '',
				'link' => '',
				'link_target' => '_blank',
				'button_text' => '',
				'color_scheme' => '',
				'appear_animation' => '',
				'css' => ''
										), $atts)
		);

		// css from Design options
		$css_design_class = '';
		if(defined('VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG')) {
			$css_design_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '), 'rigid_banner', $atts);
		}

		// Enqueue needed icon font.
		rigid_icon_element_fonts_enqueue($type);

		$iconClass = isset(${"icon_" . $type}) ? esc_attr(${"icon_" . $type}) : 'fa fa-adjust';

		$image_src = '';

		if ($image_id) {
			$image_attachment = wp_get_attachment_image_src($image_id, 'full');
			if ($image_attachment && is_array($image_attachment)) {
				$image_src = $image_attachment[0];
			}
		}

		ob_start();
		?>
		<div class="wpb_rigid_banner wpb_content_element <?php echo esc_attr($alignment) ?> <?php echo esc_attr($title_size) ?><?php if ($appear_animation !== '') echo ' ' . sanitize_html_class($appear_animation); ?><?php if ($color_scheme !== '') echo ' ' . sanitize_html_class($color_scheme); ?> <?php echo ($css_design_class ? ' ' . esc_attr($css_design_class) : '') ?>">
			<div class="wpb_wrapper">
				<div class="rigid_whole_banner_wrapper">
					<a href="<?php echo esc_url($link) ? esc_url($link) : '#'; ?>" target="<?php echo esc_attr($link_target) ?>" <?php echo esc_attr($title) ? 'title="' . esc_attr($title) . '"' : ''; ?>>
						<?php if ($image_src): ?>
							<div class="rigid_banner_image">
								<img class="rigid_banner_bg" src="<?php echo esc_url($image_src) ?>" <?php echo esc_attr($title) ? 'alt="' . esc_attr($title) . '"' : ''; ?> />
							</div>
						<?php endif; ?>
						<div class="rigid_banner_text">
							<div class="rigid_banner_centering">
								<div class="rigid_banner_centered">
									<?php if($iconClass): ?>
										<span class="rigid_banner-icon <?php echo esc_attr($iconClass); ?>" ></span>
									<?php endif; ?>
									<?php if ($title): ?>
										<h4><?php echo esc_html($title) ?></h4>
									<?php endif; ?>
									<?php if ($subtitle): ?>
										<h6><?php echo esc_html($subtitle) ?></h6>
									<?php endif; ?>
									<?php if ($button_text): ?>
										<span class="rigid_banner_buton"><?php echo esc_html($button_text) ?></span>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

}
add_shortcode('rigid_banner', 'rigid_banner_shortcode');

/**
 * Define rigid_cloudzoom_gallery shortcode
 */
if (!function_exists('rigid_cloudzoom_gallery_shortcode')) {

	function rigid_cloudzoom_gallery_shortcode($atts) {

		// Attributes
		extract(shortcode_atts(
										array(
				'images' => '',
				'img_size' => '800x800'
										), $atts)
		);

		if ($images) {
			$images = explode(',', $images);
			$unique_id = uniqid('rigid_cloudzoom_gallery_');

			if (is_array($images) && !empty($images)) {
				ob_start();
				?>
				<div class="rigid-cloudzoom-gallery wpb_content_element">
					<div class="wpb_wrapper">
						<?php
						$first_image_attach_id = $images[0];
						$first_image = wp_get_attachment_image($first_image_attach_id, $img_size);
						$first_image_attach_url = wp_get_attachment_url($first_image_attach_id);
						echo sprintf('<a id="%s" href="%s" itemprop="image" class="cloud-zoom" rel="position: \'inside\' , showTitle: false, adjustX:-4, adjustY:-4">%s</a>', esc_attr($unique_id), esc_url($first_image_attach_url), $first_image);
						?>

						<ul class="additional-images">
							<?php foreach ($images as $attach_id): ?>
								<?php
								$thumb_image = wp_get_attachment_image($attach_id, 'rigid-widgets-thumb');
								$small_image = wp_get_attachment_image($attach_id, $img_size);
								$small_image_params = wp_get_attachment_image_src($attach_id, $img_size);

								$image_attach_url = wp_get_attachment_url($attach_id);
								?>
								<li>
									<?php echo sprintf('<a rel="useZoom: \'%s\', smallImage: \'%s\'" class="cloud-zoom-gallery" href="%s">%s</a>', esc_attr($unique_id), esc_url($small_image_params[0]), esc_url($image_attach_url), $thumb_image); ?>
								</li>
							<?php endforeach; ?>
						</ul>

					</div>
				</div>
				<script type="text/javascript">
					//<![CDATA[
					(function ($) {
						"use strict";
						$(document).ready(function () {
							jQuery('#<?php echo esc_attr($unique_id) ?>').CloudZoom();
						});
					})(window.jQuery);
					//]]>
				</script>
				<?php
				return ob_get_clean();
			}
		}
	}

}
add_shortcode('rigid_cloudzoom_gallery', 'rigid_cloudzoom_gallery_shortcode');

/**
 * Define rigid_icon shortcode
 */
if (!function_exists('rigid_icon_shortcode')) {

	function rigid_icon_shortcode($atts) {
		// Attributes
		extract(shortcode_atts(
										array(
				'type' => 'fontawesome',
				'icon_fontawesome' => 'fa fa-adjust',
				'icon_openiconic' => '',
				'icon_typicons' => '',
				'icon_entypoicons' => '',
				'icon_linecons' => '',
				'icon_entypo' => '',
				'icon_etline' => '',
				'text' => '',
				'tag' => 'h3',
				'color' => '',
				'custom_color' => '',
				'text_color' => ''
										), $atts)
		);

		// Enqueue needed icon font.
		rigid_icon_element_fonts_enqueue($type);

		$iconClass = isset(${"icon_" . $type}) ? esc_attr(${"icon_" . $type}) : 'fa fa-adjust';
		$iconClass .= ' rigid_icon_element-color-' . esc_attr($color);
		$custom_color_style = ($color === 'custom' ? 'color:' . esc_attr($custom_color) . ' !important' : '');

		$text_color_style = '';
		if (trim($text_color)) {
			$text_color_style = 'style="color:' . $text_color . '"';
		}

		ob_start();
		?>
		<div class="wpb_content_element rigid-vertical-section-title">
			<?php
			switch ($tag) {
				case 'h1':
					echo '<h1 ' . $text_color_style . '>';
					break;
				case 'h2':
					echo '<h2 ' . $text_color_style . '>';
					break;
				case 'h3':
					echo '<h3 ' . $text_color_style . '>';
					break;
				case 'h4':
					echo '<h4 ' . $text_color_style . '>';
					break;
				case 'h5':
					echo '<h5 ' . $text_color_style . '>';
					break;
				case 'h6':
					echo '<h6 ' . $text_color_style . '>';
					break;
				case 'p':
					echo '<p ' . $text_color_style . '>';
					break;
				default:
					echo '<h3 ' . $text_color_style . '>';
					break;
			};
			?>
			<span <?php if ($custom_color): ?> style="<?php echo esc_attr($custom_color_style) ?>" <?php endif; ?> class="rigid-icon-title <?php echo esc_attr($iconClass); ?>" ></span> <?php echo esc_html($text); ?>
			<?php
			switch ($tag) {
				case 'h1':
					echo '</h1>';
					break;
				case 'h2':
					echo '</h2>';
					break;
				case 'h3':
					echo '</h3>';
					break;
				case 'h4':
					echo '</h4>';
					break;
				case 'h5':
					echo '</h5>';
					break;
				case 'h6':
					echo '</h6>';
					break;
				case 'p':
					echo '</p>';
					break;
				default:
					echo '</h3>';
					break;
			};

			echo '</div>';

			$output = ob_get_clean();

			return $output;
		}

	}
	add_shortcode('rigid_icon', 'rigid_icon_shortcode');

	/**
	 * Define rigid_icon_teaser shortcode
	 */
	if (!function_exists('rigid_icon_teaser_shortcode')) {

		function rigid_icon_teaser_shortcode($atts, $content = '') {
			// Attributes
			extract(shortcode_atts(
											array(
					'title' => '',
					'subtitle' => '',
					'type' => 'fontawesome',
					'icon_fontawesome' => 'fa fa-adjust',
					'icon_etline' => 'icon-mobile',
					'color' => '',
					'align' => 'teaser-left',
					'appear_animation' => '',
					'titles_color' => ''
											), $atts)
			);

			// Enqueue font-awesome.
			wp_enqueue_style('font-awesome');

			$unique_id = uniqid('rigid_icon_teaser_');

			ob_start();
			?>
			<div class="rigid_icon_teaser wpb_content_element<?php if ($appear_animation !== '') echo ' ' . sanitize_html_class($appear_animation); ?>">
				<div class="icon_link_item <?php echo esc_attr($align) ?>">
					<a href="#<?php echo esc_attr($unique_id) ?>" class="rigid-icon-teaser-popup-link">
						<div  class="icon_holder">
							<?php if ($type === 'fontawesome'): ?>
								<i <?php echo( $color ? ' style="color:' . esc_attr($color) . ';"' : '' ); ?> class="<?php echo esc_attr($icon_fontawesome); ?>"></i>
							<?php elseif ($type === 'etline'): ?>
								<i <?php echo( $color ? ' style="color:' . esc_attr($color) . ';"' : '' ); ?> class="<?php echo esc_attr($icon_etline); ?>"></i>
							<?php endif; ?>
						</div>
						<?php if ($title): ?>
							<h5<?php echo( $titles_color ? ' style="color:' . esc_attr($titles_color) . ';"' : '' ); ?>><?php echo esc_html($title) ?></h5>
						<?php endif; ?>
						<?php if ($subtitle): ?>
							<small<?php echo( $titles_color ? ' style="color:' . esc_attr($titles_color) . ';"' : '' ); ?>><?php echo esc_html($subtitle) ?></small>
						<?php endif; ?>
					</a>
				</div>
			</div>
			<!-- The popup -->
			<div id="<?php echo esc_attr($unique_id) ?>" class="icon_teaser mfp-hide">
				<?php if ($title): ?>
					<h3><?php echo esc_html($title) ?></h3>
				<?php endif; ?>
				<?php if ($subtitle): ?>
					<h6><?php echo esc_html($subtitle) ?></h6>
				<?php endif; ?>
				<p><?php echo wp_kses_post(do_shortcode($content)) ?></p>
			</div>
			<!-- End The popup -->
			<script type="text/javascript">
				//<![CDATA[
				(function ($) {
					"use strict";
					$(document).ready(function () {
						$('.rigid-icon-teaser-popup-link').magnificPopup({
							mainClass: 'rigid-icon-teaser-lightbox mfp-fade',
							type: 'inline',
							midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
						});
					});
				})(window.jQuery);
				//]]>
			</script>
			<?php
			$output = ob_get_clean();

			return $output;
		}

	}
	add_shortcode('rigid_icon_teaser', 'rigid_icon_teaser_shortcode');


	/**
	 * Define rigid_icon_box shortcode
	 */
	if (!function_exists('rigid_icon_box_shortcode')) {

		function rigid_icon_box_shortcode($atts, $content = '') {
			// Attributes
			extract(shortcode_atts(
											array(
					'title' => '',
					'subtitle' => '',
					'type' => 'fontawesome',
					'icon_fontawesome' => 'fa fa-adjust',
					'icon_etline' => 'icon-mobile',
					'color' => '',
					'alignment' => '',
					'icon_style' => '',
					'appear_animation' => '',
					'titles_color' => ''
											), $atts)
			);

			// Enqueue font-awesome.
			wp_enqueue_style('font-awesome');

			$iconbox_styling_classes = implode(' ', array_filter(array('rigid-iconbox', $alignment, $icon_style)));

			$icon_style_inline = 'background-color';
			if ($icon_style == 'rigid-clean-icon') {
				$icon_style_inline = 'color';
			}

			ob_start();
			?>
			<div class="wpb_content_element<?php if ($appear_animation !== '') echo ' ' . sanitize_html_class($appear_animation); ?>">
				<div class="<?php echo esc_attr($iconbox_styling_classes) ?>">
					<div class="icon_wrapper">
						<span class="icon_inner"<?php echo( $color ? ' style="' . esc_attr($icon_style_inline) . ':' . esc_attr($color) . ';"' : '' ); ?>>
							<?php if ($type === 'fontawesome'): ?>
								<i class="<?php echo esc_attr($icon_fontawesome); ?>"></i>
							<?php elseif ($type === 'etline'): ?>
								<i class="<?php echo esc_attr($icon_etline); ?>"></i>
							<?php endif; ?>
						</span>
					</div>
					<div class="iconbox_content">
						<?php if ($title): ?>
							<h5<?php echo( $titles_color ? ' style="color:' . esc_attr($titles_color) . ';"' : '' ); ?>><?php echo esc_html($title) ?></h5>
						<?php endif; ?>
						<?php if ($subtitle): ?>
							<small<?php echo( $titles_color ? ' style="color:' . esc_attr($titles_color) . ';"' : '' ); ?>><?php echo esc_html($subtitle) ?></small>
						<?php endif; ?>
						<div class="iconbox_text_content">
							<?php echo wp_kses_post(do_shortcode($content)) ?>
						</div>
					</div>
				</div>
			</div>
			<?php
			$output = ob_get_clean();

			return $output;
		}

	}
	add_shortcode('rigid_icon_box', 'rigid_icon_box_shortcode');


	/**
	 * Define rigid_countdown shortcode
	 */
	if (!function_exists('rigid_countdown_shortcode')) {

		function rigid_countdown_shortcode($atts) {

			// Attributes
			extract(shortcode_atts(
											array(
					'date' => '',
					'counter_size' => '',
					'color' => '',
											), $atts)
			);

			$output = '';

			if ($date) {
				$unique_id = uniqid('rigid_count_');

				ob_start();
				?>
				<div id="<?php echo esc_attr($unique_id) ?>" class="rigid_shortcode_count_holder<?php if($counter_size !== '') echo ' ' . sanitize_html_class($counter_size); ?>" <?php if ($color) echo 'style="color: ' . esc_attr($color) . ';"'; ?>></div>
				<script type="text/javascript">
					//<![CDATA[
					jQuery(function () {
						jQuery('#<?php echo esc_js($unique_id) ?>').countdown({until: new Date("<?php echo esc_js($date) ?>"), compact: false});
					});
					//]]>
				</script>
				<?php
				$output = ob_get_clean();
			}

			return $output;
		}

	}

	add_shortcode('rigid_countdown', 'rigid_countdown_shortcode');

	/**
	 * Define rigid_map shortcode
	 */
	if (!function_exists('rigid_map_shortcode')) {

		function rigid_map_shortcode($atts) {
			// Attributes
			extract(shortcode_atts(
											array(
					'location_title' => '',
					'map_latitude' => '',
					'map_longitude' => '',
					'height' => '400'
											), $atts)
			);

			$output = '';

			if ($map_latitude && $map_longitude && !is_search()) {

				$map_canvas_unique_id = uniqid('map_canvas');
				$routeStart_unique_id = uniqid('routeStart');

				// Enqueue google maps script
				wp_enqueue_script('rigid-google-map');
				// Map config
				wp_enqueue_script('rigid-plugin-map-config-' . $map_canvas_unique_id, plugins_url("assets/js/rigid-plugin-map-config.min.js", dirname(__FILE__)), array('rigid-google-map'), false, true);
				wp_localize_script('rigid-plugin-map-config-' . $map_canvas_unique_id, 'rigid_map_short', array(
						'location_title' => esc_js($location_title ? $location_title : esc_html__('Our Location', 'rigid-plugin')),
						'lattitude' => esc_js($map_latitude),
						'longitude' => esc_js($map_longitude),
						'images' => esc_url(plugins_url('assets/image/google_maps/', dirname(__FILE__))),
						'map_canvas_unique_id' => esc_js($map_canvas_unique_id)
				));

				ob_start();
				?>
				<div class="rigid-google-maps rigid-map-shortcode">
					<div id="<?php echo esc_attr($map_canvas_unique_id) ?>" class="map_canvas" style="width:100%; height:<?php echo esc_attr($height) ?>px"></div>
					<div class="directions_holder">
						<h4><i class="fa fa-map-marker"></i> <?php esc_html_e('Get Directions', 'rigid-plugin') ?></h4>
						<p><?php esc_html_e('Fill in your address or zipcode to calculate the route', 'rigid-plugin') ?></p>
						<form action="" align="right" onSubmit="calcRoute('<?php echo esc_js($routeStart_unique_id) ?>', '<?php echo esc_js($map_latitude) ?>', '<?php echo esc_js($map_longitude) ?>', '<?php echo esc_js($map_canvas_unique_id) ?>');
											return false;">
							<input type="text" id="<?php echo esc_attr($routeStart_unique_id) ?>" value="" placeholder="<?php esc_html_e('Address or postcode', 'rigid-plugin') ?>" style="margin-top:3px"><br /><br />
							<input type="submit" value="<?php esc_html_e('Calculate route', 'rigid-plugin') ?>" class="button" onclick="calcRoute('<?php echo esc_js($routeStart_unique_id) ?>', '<?php echo esc_js($map_latitude) ?>', '<?php echo esc_js($map_longitude) ?>', '<?php echo esc_js($map_canvas_unique_id) ?>');
												return false;" />
						</form>
					</div>
				</div>

				<?php
				$output = ob_get_clean();
			}
			return $output;
		}

	}
	add_shortcode('rigid_map', 'rigid_map_shortcode');

	/**
	 * Define rigid_pricing_table shortcode
	 */
	if (!function_exists('rigid_pricing_table_shortcode')) {

		function rigid_pricing_table_shortcode($atts, $content = '') {

			// Attributes
			extract(shortcode_atts(
											array(
					'title' => '',
					'subtitle' => '',
					'styled_for_dark' => '',
					'price' => '',
					'price_coins' => '',
					'currency_symbol' => '',
					'period' => '',
					'appear_animation' => '',
					'button_text' => '',
					'link' => '',
					'accent_color' => '',
					'featured' => 'no'
											), $atts)
			);

			ob_start();
			?>

			<div class="rigid-pricing-table-shortcode<?php echo ($styled_for_dark === 'yes' ? ' pricing-table-light-titles' : '') ?><?php echo ($featured === 'yes' ? ' rigid-pricing-is-featured' : '') ?><?php if ($appear_animation !== '') echo ' ' . sanitize_html_class($appear_animation); ?>">
				<?php if ($price && $period): ?>
					<div class="rigid-pricing-table-price">
						<?php if ($price): ?>
						    <?php if($currency_symbol): ?><sub><?php echo esc_html($currency_symbol) ?></sub><?php endif; ?><?php echo esc_html($price); ?><?php if($price_coins): ?><?php echo "<sup>.".esc_html($price_coins)."</sup>" ?><?php endif; ?>
						<?php endif; ?>
						<?php if ($period): ?>
							<span class="rigid-pricing-table-period"><?php echo esc_html($period) ?></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<div class="rigid-pricing-heading" <?php echo( $accent_color ? ' style="background-color:' . esc_attr($accent_color) . ';"' : '' ); ?> >
					<?php if ($title): ?>
						<h5><?php echo esc_html($title) ?></h5>
					<?php endif; ?>
					<?php if ($subtitle): ?>
						<small><?php echo esc_html($subtitle) ?></small>
					<?php endif; ?>
				</div>
				<?php if ($content): ?>
					<div class="rigid-pricing-table-content"><?php echo wp_kses_post(do_shortcode($content)) ?></div>
				<?php endif; ?>
				<?php if ($link): ?>
					<div class="rigid-pricing-table-button">
						<a class="rigid_pricing_table-button" href="<?php echo esc_url($link); ?>" <?php echo( $accent_color ? ' style="background-color:' . esc_attr($accent_color) . ';"' : '' ); ?>>
							<?php echo esc_html($button_text) ?>
						</a>
					</div>
				<?php endif; ?>
			</div>

			<?php
			return ob_get_clean();
		}

	}
	add_shortcode('rigid_pricing_table', 'rigid_pricing_table_shortcode');

	/**
	 * Define rigid_contact_form shortcode
	 */
	if (!function_exists('rigid_contact_form_shortcode')) {

		function rigid_contact_form_shortcode($atts, $content = '') {

			$current_user_email = '';
			if (is_user_logged_in()) {
				$the_logged_user = wp_get_current_user();
				if ($the_logged_user instanceof WP_User) {
					$current_user_email = $the_logged_user->user_email;
				}
			}

			wp_enqueue_script('jquery-form');

			// Attributes
			$combined_atts = shortcode_atts(
							array(
					'title' => '',
					'contact_mail_to' => $current_user_email,
					'simple_captcha' => false,
					'contact_form_fields' => array()
							), $atts);

			//append rigid_ to each field
			foreach ($combined_atts as $key => $val) {
				$combined_atts['rigid_' . $key] = $val;
				unset($combined_atts[$key]);
			}
			extract($combined_atts);

			$unique_id = uniqid('rigid_contactform');
			$nonce = wp_create_nonce('rigid_contactform');

			$rigid_shortcode_params_for_tpl = json_encode($combined_atts);

			ob_start();
			?>
			<div id="holder_<?php echo esc_attr($unique_id) ?>" class="rigid-contacts-holder rigid-contacts-shortcode" >
				<?php
				$inline_js = '"use strict";
				jQuery(document).ready(function () {
					var submitButton = jQuery(\'#holder_' . esc_js($unique_id) . ' input:submit\');
					var loader = jQuery(\'<img id="' . esc_js($unique_id) . '_loading_gif" class="rigid-contacts-loading" src="' . esc_url(plugin_dir_url(__FILE__)) . '../assets/image/contacts_ajax_loading.png" />\').prependTo(\'#holder_' . esc_attr($unique_id) . ' div.buttons div.left\').hide();

					jQuery(\'#holder_' . esc_js($unique_id) . ' form\').ajaxForm({
						target: \'#holder_' . esc_js($unique_id) . '\',
						data: {
							// additional data to be included along with the form fields
							unique_id: \'' . esc_js($unique_id) . '\',
							action: \'rigid_submit_contact\',
							_ajax_nonce: \'' . esc_js($nonce) . '\'
						},
						beforeSubmit: function (formData, jqForm, options) {
							// optionally process data before submitting the form via AJAX
							submitButton.hide();
							loader.show();
						},
						success: function (responseText, statusText, xhr, $form) {
							// code that\'s executed when the request is processed successfully
							loader.remove();
							submitButton.show();
						}
					});
				});';

				wp_add_inline_script('flexslider', $inline_js);
				?>
				<?php require(plugin_dir_path( __FILE__ ) . 'partials/contact-form.php'); ?>
			</div>
			<?php
			return ob_get_clean();
		}

	}
	add_shortcode('rigid_contact_form', 'rigid_contact_form_shortcode');



	/**
	 * Define rigid_woo_top_rated_carousel shortcode
	 */
	if (!function_exists('rigid_woo_top_rated_carousel_shortcode')) {

		function rigid_woo_top_rated_carousel_shortcode($atts) {

			global $woocommerce_loop;

			$atts = shortcode_atts(array(
					'per_page' => '12',
					'columns' => '4',
					'orderby' => 'title',
					'order' => 'asc'
							), $atts);

			$meta_query = WC()->query->get_meta_query();

			$args = array(
					'post_type' => 'product',
					'post_status' => 'publish',
					'ignore_sticky_posts' => 1,
					'orderby' => $atts['orderby'],
					'order' => $atts['order'],
					'posts_per_page' => $atts['per_page'],
					'meta_query' => $meta_query
			);

			$unique_id = uniqid('woo_top_rated');

			ob_start();
			?>
			<script type="text/javascript">
				//<![CDATA[
				(function ($) {
					"use strict";
					$(window).load(function () {
						jQuery("#<?php echo esc_js($unique_id) ?>").owlCarousel({
							rtl: <?php echo is_rtl() ? 'true' : 'false'; ?>,
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
									items: 4,
								},
								1280: {
									items: <?php echo esc_js($atts['columns']) ?>,
								}
							},
							dots: false,
							nav: true,
							navText: [
								"<i class='fa fa-angle-left'></i>",
								"<i class='fa fa-angle-right'></i>"
							],
						});
					});
				})(window.jQuery);
				//]]>
			</script>
			<?php
			$js_config_output = ob_get_clean();

			ob_start();

			add_filter('posts_clauses', array('WC_Shortcodes', 'order_by_rating_post_clauses'));

			$products = new WP_Query(apply_filters('woocommerce_shortcode_products_query', $args, $atts));

			remove_filter('posts_clauses', array('WC_Shortcodes', 'order_by_rating_post_clauses'));

			$woocommerce_loop['columns'] = $atts['columns'];

			if ($products->have_posts()) :
				?>

				<?php woocommerce_product_loop_start(); ?>

				<?php while ($products->have_posts()) : $products->the_post(); ?>

					<?php wc_get_template_part('content', 'product'); ?>

				<?php endwhile; // end of the loop.                           ?>

				<?php woocommerce_product_loop_end(); ?>

				<?php
			endif;

			wp_reset_postdata();

			return $js_config_output . '<div id="' . esc_attr($unique_id) . '" class="owl-carousel woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
		}

	}

	/**
	 * Define rigid_woo_recent_carousel shortcode
	 */
	if (!function_exists('rigid_woo_recent_carousel_shortcode')) {

		function rigid_woo_recent_carousel_shortcode($atts) {
			global $woocommerce_loop;

			$atts = shortcode_atts(array(
					'per_page' => '12',
					'columns' => '4',
					'orderby' => 'date',
					'order' => 'desc'
							), $atts);

			$meta_query = WC()->query->get_meta_query();

			$args = array(
					'post_type' => 'product',
					'post_status' => 'publish',
					'ignore_sticky_posts' => 1,
					'posts_per_page' => $atts['per_page'],
					'orderby' => $atts['orderby'],
					'order' => $atts['order'],
					'meta_query' => $meta_query
			);

			$unique_id = uniqid('woo_recent_carousel');

			ob_start();
			?>
			<script type="text/javascript">
				//<![CDATA[
				(function ($) {
					"use strict";
					$(window).load(function () {
						jQuery("#<?php echo esc_js($unique_id) ?>").owlCarousel({
							rtl: <?php echo is_rtl() ? 'true' : 'false'; ?>,
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
									items: 4,
								},
								1280: {
									items: <?php echo esc_js($atts['columns']) ?>,
								}
							},
							dots: false,
							nav: true,
							navText: [
								"<i class='fa fa-angle-left'></i>",
								"<i class='fa fa-angle-right'></i>"
							],
						});
					});
				})(window.jQuery);
				//]]>
			</script>

			<?php
			$js_config_output = ob_get_clean();

			ob_start();

			$products = new WP_Query(apply_filters('woocommerce_shortcode_products_query', $args, $atts));

			$woocommerce_loop['columns'] = $atts['columns'];

			if ($products->have_posts()) :
				?>

				<?php woocommerce_product_loop_start(); ?>

				<?php while ($products->have_posts()) : $products->the_post(); ?>

					<?php wc_get_template_part('content', 'product'); ?>

				<?php endwhile; // end of the loop.                          ?>

				<?php woocommerce_product_loop_end(); ?>

				<?php
			endif;

			wp_reset_postdata();

			return $js_config_output . '<div id="' . esc_attr($unique_id) . '" class="owl-carousel woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
		}

	}

	/**
	 * Define rigid_woo_featured_carousel shortcode
	 */
	if (!function_exists('rigid_woo_featured_carousel_shortcode')) {

		function rigid_woo_featured_carousel_shortcode($atts) {
			global $woocommerce_loop;

			$atts = shortcode_atts(array(
					'per_page' => '12',
					'columns' => '4',
					'orderby' => 'date',
					'order' => 'desc'
							), $atts);

			$meta_query = WC()->query->get_meta_query();
			$meta_query[] = array(
					'key' => '_featured',
					'value' => 'yes'
			);

			$args = array(
					'post_type' => 'product',
					'post_status' => 'publish',
					'ignore_sticky_posts' => 1,
					'posts_per_page' => $atts['per_page'],
					'orderby' => $atts['orderby'],
					'order' => $atts['order'],
					'meta_query' => $meta_query
			);

			$unique_id = uniqid('woo_featured_carousel');

			ob_start();
			?>
			<script type="text/javascript">
				//<![CDATA[
				(function ($) {
					"use strict";
					$(window).load(function () {
						jQuery("#<?php echo esc_js($unique_id) ?>").owlCarousel({
							rtl: <?php echo is_rtl() ? 'true' : 'false'; ?>,
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
									items: 4,
								},
								1280: {
									items: <?php echo esc_js($atts['columns']) ?>,
								}
							},
							dots: false,
							nav: true,
							navText: [
								"<i class='fa fa-angle-left'></i>",
								"<i class='fa fa-angle-right'></i>"
							],
						});
					});
				})(window.jQuery);
				//]]>
			</script>

			<?php
			$js_config_output = ob_get_clean();

			ob_start();

			$products = new WP_Query(apply_filters('woocommerce_shortcode_products_query', $args, $atts));

			$woocommerce_loop['columns'] = $atts['columns'];

			if ($products->have_posts()) :
				?>

				<?php woocommerce_product_loop_start(); ?>

				<?php while ($products->have_posts()) : $products->the_post(); ?>

					<?php wc_get_template_part('content', 'product'); ?>

				<?php endwhile; // end of the loop.                           ?>

				<?php woocommerce_product_loop_end(); ?>

				<?php
			endif;

			wp_reset_postdata();

			return $js_config_output . '<div id="' . esc_attr($unique_id) . '" class="owl-carousel woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
		}

	}

	/**
	 * Define rigid_woo_sale_carousel shortcode
	 */
	if (!function_exists('rigid_woo_sale_carousel_shortcode')) {

		function rigid_woo_sale_carousel_shortcode($atts) {
			global $woocommerce_loop;

			$atts = shortcode_atts(array(
					'per_page' => '12',
					'columns' => '4',
					'orderby' => 'title',
					'order' => 'asc'
							), $atts);

			// Get products on sale
			$product_ids_on_sale = wc_get_product_ids_on_sale();

			$meta_query = WC()->query->get_meta_query();

			$args = array(
					'posts_per_page' => $atts['per_page'],
					'orderby' => $atts['orderby'],
					'order' => $atts['order'],
					'no_found_rows' => 1,
					'post_status' => 'publish',
					'post_type' => 'product',
					'meta_query' => $meta_query,
					'post__in' => array_merge(array(0), $product_ids_on_sale)
			);
			$unique_id = uniqid('woo_sale_carousel');

			ob_start();
			?>
			<script type="text/javascript">
				//<![CDATA[
				(function ($) {
					"use strict";
					$(window).load(function () {
						jQuery("#<?php echo esc_js($unique_id) ?>").owlCarousel({
							rtl: <?php echo is_rtl() ? 'true' : 'false'; ?>,
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
									items: 4,
								},
								1280: {
									items: <?php echo esc_js($atts['columns']) ?>,
								}
							},
							dots: false,
							nav: true,
							navText: [
								"<i class='fa fa-angle-left'></i>",
								"<i class='fa fa-angle-right'></i>"
							],
						});
					});
				})(window.jQuery);
				//]]>
			</script>

			<?php
			$js_config_output = ob_get_clean();

			ob_start();

			$products = new WP_Query(apply_filters('woocommerce_shortcode_products_query', $args, $atts));

			$woocommerce_loop['columns'] = $atts['columns'];

			if ($products->have_posts()) :
				?>

				<?php woocommerce_product_loop_start(); ?>

				<?php while ($products->have_posts()) : $products->the_post(); ?>

					<?php wc_get_template_part('content', 'product'); ?>

				<?php endwhile; // end of the loop.                           ?>

				<?php woocommerce_product_loop_end(); ?>

				<?php
			endif;

			wp_reset_postdata();

			return $js_config_output . '<div id="' . esc_attr($unique_id) . '" class="owl-carousel woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
		}

	}

	/**
	 * Define rigid_woo_best_selling_carousel shortcode
	 */
	if (!function_exists('rigid_woo_best_selling_carousel_shortcode')) {

		function rigid_woo_best_selling_carousel_shortcode($atts) {
			global $woocommerce_loop;

			$atts = shortcode_atts(array(
					'per_page' => '12',
					'columns' => '4'
							), $atts);

			$meta_query = WC()->query->get_meta_query();

			$args = array(
					'post_type' => 'product',
					'post_status' => 'publish',
					'ignore_sticky_posts' => 1,
					'posts_per_page' => $atts['per_page'],
					'meta_key' => 'total_sales',
					'orderby' => 'meta_value_num',
					'meta_query' => $meta_query
			);
			$unique_id = uniqid('woo_best_selling');

			ob_start();
			?>
			<script type="text/javascript">
				//<![CDATA[
				(function ($) {
					"use strict";
					$(window).load(function () {
						jQuery("#<?php echo esc_js($unique_id) ?>").owlCarousel({
							rtl: <?php echo is_rtl() ? 'true' : 'false'; ?>,
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
									items: 4,
								},
								1280: {
									items: <?php echo esc_js($atts['columns']) ?>,
								}
							},
							dots: false,
							nav: true,
							navText: [
								"<i class='fa fa-angle-left'></i>",
								"<i class='fa fa-angle-right'></i>"
							],
						});
					});
				})(window.jQuery);
				//]]>
			</script>

			<?php
			$js_config_output = ob_get_clean();

			ob_start();

			$products = new WP_Query(apply_filters('woocommerce_shortcode_products_query', $args, $atts));

			$woocommerce_loop['columns'] = $atts['columns'];

			if ($products->have_posts()) :
				?>

				<?php woocommerce_product_loop_start(); ?>

				<?php while ($products->have_posts()) : $products->the_post(); ?>

					<?php wc_get_template_part('content', 'product'); ?>

				<?php endwhile; // end of the loop.                      ?>

				<?php woocommerce_product_loop_end(); ?>

				<?php
			endif;

			wp_reset_postdata();

			return $js_config_output . '<div id="' . esc_attr($unique_id) . '" class="owl-carousel woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
		}

	}

	/**
	 * Define rigid_woo_best_selling_carousel shortcode
	 * List all (or limited) product categories
	 */
	if (!function_exists('rigid_woo_product_categories_carousel_shortcode')) {

		function rigid_woo_product_categories_carousel_shortcode($atts) {
			global $woocommerce_loop;

			$atts = shortcode_atts(array(
					'number' => null,
					'orderby' => 'name',
					'order' => 'ASC',
					'columns' => '4',
					'hide_empty' => 1,
					'parent' => '',
					'ids' => ''
							), $atts);

			if (isset($atts['ids'])) {
				$ids = explode(',', $atts['ids']);
				$ids = array_map('trim', $ids);
			} else {
				$ids = array();
			}

			$hide_empty = ( $atts['hide_empty'] == true || $atts['hide_empty'] == 1 ) ? 1 : 0;

// get terms and workaround WP bug with parents/pad counts
			$args = array(
					'orderby' => $atts['orderby'],
					'order' => $atts['order'],
					'hide_empty' => $hide_empty,
					'include' => $ids,
					'pad_counts' => true,
					'child_of' => $atts['parent']
			);

			$product_categories = get_terms('product_cat', $args);

			if ('' !== $atts['parent']) {
				$product_categories = wp_list_filter($product_categories, array('parent' => $atts['parent']));
			}

			if ($hide_empty) {
				foreach ($product_categories as $key => $category) {
					if ($category->count == 0) {
						unset($product_categories[$key]);
					}
				}
			}

			if ($atts['number']) {
				$product_categories = array_slice($product_categories, 0, $atts['number']);
			}

			$woocommerce_loop['columns'] = $atts['columns'];
			$unique_id = uniqid('woo_product_categories');

			ob_start();
			?>
			<script type="text/javascript">
				//<![CDATA[
				(function ($) {
					"use strict";
					$(window).load(function () {
						jQuery("#<?php echo esc_js($unique_id) ?>").owlCarousel({
							rtl: <?php echo is_rtl() ? 'true' : 'false'; ?>,
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
									items: 4,
								},
								1280: {
									items: <?php echo esc_js($atts['columns']) ?>,
								}
							},
							dots: false,
							nav: true,
							navText: [
								"<i class='fa fa-angle-left'></i>",
								"<i class='fa fa-angle-right'></i>"
							],
						});
					});
				})(window.jQuery);
				//]]>
			</script>

			<?php
			$js_config_output = ob_get_clean();
			ob_start();

// Reset loop/columns globals when starting a new loop
			$woocommerce_loop['loop'] = $woocommerce_loop['column'] = '';

			if ($product_categories) {

				woocommerce_product_loop_start();

				foreach ($product_categories as $category) {

					wc_get_template('content-product_cat.php', array(
							'category' => $category
					));
				}

				woocommerce_product_loop_end();
			}

			woocommerce_reset_loop();

			return $js_config_output . '<div id="' . esc_attr($unique_id) . '" class="owl-carousel woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
		}

	}

	/**
	 * Define rigid_woo_products_slider shortcode
	 */
	if (!function_exists('rigid_woo_products_slider_shortcode')) {

		function rigid_woo_products_slider_shortcode($atts) {
			global $post;

			if (empty($atts)) {
				return '';
			}

			$atts = shortcode_atts(array(
					'orderby' => 'title',
					'order' => 'asc',
					'ids' => '',
					'skus' => ''
							), $atts);

			$meta_query = WC()->query->get_meta_query();

			$args = array(
					'post_type' => 'product',
					'post_status' => 'publish',
					'ignore_sticky_posts' => 1,
					'orderby' => $atts['orderby'],
					'order' => $atts['order'],
					'posts_per_page' => -1,
					'meta_query' => $meta_query
			);

			if (!empty($atts['skus'])) {
				$skus = explode(',', $atts['skus']);
				$skus = array_map('trim', $skus);
				$args['meta_query'][] = array(
						'key' => '_sku',
						'value' => $skus,
						'compare' => 'IN'
				);
			}

			if (!empty($atts['ids'])) {
				$ids = explode(',', $atts['ids']);
				$ids = array_map('trim', $ids);
				$args['post__in'] = $ids;
			}

			$unique_id = uniqid('rigid_woo_products_slider');

			ob_start();
			?>
			<script type="text/javascript">
				//<![CDATA[
				(function ($) {
					"use strict";
					$(window).load(function () {
						jQuery("#<?php echo esc_js($unique_id) ?>").owlCarousel({
							rtl: <?php echo is_rtl() ? 'true' : 'false'; ?>,
							responsiveClass: true,
							items: 1,
							dots: false,
							nav: true,
							navText: [
								"<i class='fa fa-angle-left'></i>",
								"<i class='fa fa-angle-right'></i>"
							],
						});
					});
				})(window.jQuery);
				//]]>
			</script>

			<?php
			$js_config_output = ob_get_clean();

			ob_start();

			$products = new WP_Query(apply_filters('woocommerce_shortcode_products_query', $args, $atts));

			if ($products->have_posts()) :
				?>

				<?php while ($products->have_posts()) : $products->the_post(); ?>
					<?php $product = wc_get_product(get_the_ID()); ?>

					<div class="rigid-product-slide-holder">
						<div class="rigid-product-slide-image">
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" >
								<?php if (has_post_thumbnail()) : ?>
									<?php echo get_the_post_thumbnail(get_the_ID(), apply_filters('shop_single_image_size', 'shop_single'), array('title' => get_the_title())); ?>
								<?php else: ?>
									<?php echo sprintf('<img src="%s" alt="%s" />', wc_placeholder_img_src(), esc_html__('Placeholder', 'rigid-plugin')); ?>
								<?php endif; ?>
							</a>
						</div>
						<div class="rigid-product-slide-details">
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" ><h4><?php the_title(); ?></h4></a>
							<span class="rigid-product-slide-description">
								<?php if ($post->post_excerpt): ?>
									<?php echo wp_trim_words(apply_filters('woocommerce_short_description', $post->post_excerpt), 23, '...'); ?>
								<?php else: ?>
									<?php echo wp_trim_words(get_the_content(), 23, '...'); ?>
								<?php endif; ?>
							</span>
							<div class="rigid-product-slide-countdown"><?php rigid_product_sale_countdown(); ?></div>
							<span class="rigid-product-slide-price"><?php echo ($product->get_price_html()); ?></span>
							<span class="rigid-product-slide-cart">
								<?php echo apply_filters('woocommerce_loop_add_to_cart_link', sprintf('<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="button %s product_type_%s">%s</a>', esc_url($product->add_to_cart_url()), esc_attr($product->get_id()), esc_attr($product->get_sku()), esc_attr(isset($quantity) ? $quantity : 1 ), $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '', esc_attr($product->get_type()), esc_html($product->add_to_cart_text())), $product); ?>
							</span>
						</div>
					</div>

				<?php endwhile; // end of the loop.           ?>

				<?php
			endif;

			wp_reset_postdata();

			return $js_config_output . '<div id="' . esc_attr($unique_id) . '" class="rigid-product-slider owl-carousel">' . ob_get_clean() . '</div>';
		}

	}

// If WooCommerce is active
	if (RIGID_PLUGIN_IS_WOOCOMMERCE) {
		add_shortcode('rigid_woo_top_rated_carousel', 'rigid_woo_top_rated_carousel_shortcode');
		add_shortcode('rigid_woo_recent_carousel', 'rigid_woo_recent_carousel_shortcode');
		add_shortcode('rigid_woo_featured_carousel', 'rigid_woo_featured_carousel_shortcode');
		add_shortcode('rigid_woo_sale_carousel', 'rigid_woo_sale_carousel_shortcode');
		add_shortcode('rigid_woo_best_selling_carousel', 'rigid_woo_best_selling_carousel_shortcode');
		add_shortcode('rigid_woo_product_categories_carousel', 'rigid_woo_product_categories_carousel_shortcode');
		add_shortcode('rigid_woo_products_slider', 'rigid_woo_products_slider_shortcode');
	}

	/**
	 * Map all Rigid shortcodes to VC
	 */
	add_action('vc_before_init', 'rigid_integrateWithVC');
	if (!function_exists('rigid_integrateWithVC')) {

		function rigid_integrateWithVC() {

			$current_user_email = '';
			if (is_user_logged_in()) {
				$the_logged_user = wp_get_current_user();
				if ($the_logged_user instanceof WP_User) {
					$current_user_email = $the_logged_user->user_email;
				}
			}

			$althem_icon = plugins_url('assets/image/VC_logo_alth.png', dirname(__FILE__));
			$latest_projects_columns_values = array(1, 2, 3, 4, 5, 6);
			$banner_alignment_styles = array(
					esc_html__('Center-Center', 'rigid-plugin') => 'banner-center-center',
					esc_html__('Top-Left', 'rigid-plugin') => 'banner-top-left',
					esc_html__('Top-Center', 'rigid-plugin') => 'banner-top-center',
					esc_html__('Top-Right', 'rigid-plugin') => 'banner-top-right',
					esc_html__('Center-Left', 'rigid-plugin') => 'banner-center-left',
					esc_html__('Center-Right', 'rigid-plugin') => 'banner-center-right',
					esc_html__('Bottom-Left', 'rigid-plugin') => 'banner-bottom-left',
					esc_html__('Bottom-Center', 'rigid-plugin') => 'banner-bottom-center',
					esc_html__('Bottom-Right', 'rigid-plugin') => 'banner-bottom-right',
			);

// Map rigid_counter
			if (defined('WPB_VC_VERSION')) {
				require_once vc_path_dir('CONFIG_DIR', 'content/vc-icon-element.php');

				$icon_params = vc_map_integrate_shortcode(vc_icon_element_params(), 'i_', '', array(
						// we need only type, icon_fontawesome, icon_.., NOT etc
						'include_only_regex' => '/^(type|icon_\w*)/',
								), array(
						'element' => 'add_icon',
						'value' => 'true',
				));

				$params = array_merge(array(
						array(
								'type' => 'textfield',
								'heading' => esc_html__('Text before counter', 'rigid-plugin'),
								'param_name' => 'txt_before_counter',
								'value' => '',
								'description' => esc_html__('Enter text to be shown before counter.', 'rigid-plugin'),
						),
						array(
								'type' => 'textfield',
								'heading' => esc_html__('Counting number', 'rigid-plugin'),
								'param_name' => 'count_number',
								'value' => '10',
								'description' => esc_html__('Enter the number to count to.', 'rigid-plugin'),
								'admin_label' => true
						),
						array(
								'type' => 'textfield',
								'heading' => esc_html__('Text after counter', 'rigid-plugin'),
								'param_name' => 'txt_after_counter',
								'value' => '',
								'description' => esc_html__('Enter text to be shown after counter.', 'rigid-plugin'),
						),
						array(
								'type' => 'checkbox',
								'param_name' => 'add_icon',
								'heading' => esc_html__('Add icon?', 'rigid-plugin'),
								'description' => esc_html__('Add icon to the counter.', 'rigid-plugin'),
						)), $icon_params, array(
						array(
								'type' => 'colorpicker',
								'heading' => esc_html__('Icon color', 'rigid-plugin'),
								'param_name' => 'i_custom_color',
								'value' => '',
								'description' => esc_html__('Select icon color.', 'rigid-plugin'),
								'dependency' => array(
										'element' => 'add_icon',
										'value' => 'true'
								)
						),
						array(
								'type' => 'dropdown',
								'param_name' => 'counter_style',
								'value' => array(
										'H1' => 'h1',
										'H2' => 'h2',
										'H3' => 'h3',
										'H4' => 'h4',
										'H5' => 'h5',
										'H6' => 'h6',
										'Paragraph' => 'paragraph'
								),
								'std' => 'h4',
								'heading' => esc_html__('Counter style', 'rigid-plugin'),
								'description' => esc_html__('Select counter style.', 'rigid-plugin'),
						),
						array(
								'type' => 'dropdown',
								'param_name' => 'counter_alignment',
								'value' => array(
										esc_html__('Left', 'rigid-plugin') => 'rigid-counter-left',
										esc_html__('Centered', 'rigid-plugin') => 'rigid-counter-centered',
										esc_html__('Right', 'rigid-plugin') => 'rigid-counter-right',
								),
								'std' => 'rigid-counter-left',
								'heading' => esc_html__('Counter alignment', 'rigid-plugin'),
								'description' => esc_html__('Select counter alignment style.', 'rigid-plugin'),
						),
						array(
								'type' => 'colorpicker',
								'heading' => esc_html__('Text color', 'rigid-plugin'),
								'param_name' => 'text_color',
								'value' => '',
								'description' => esc_html__('Choose color for the counter text.', 'rigid-plugin'),
						),
				));

				// Remove Icon library admin label
				unset($params[4]['admin_label']);

				vc_map(array(
						'name' => esc_html__('Counter', 'rigid-plugin'),
						'base' => 'rigid_counter',
						'icon' => $althem_icon,
						'description' => esc_html__('Configure counter', 'rigid-plugin'),
						'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
						'params' => $params,
								)
				);
			}

			// Map rigid_typed
			vc_map(array(
					'name' => esc_html__('Typed', 'rigid-plugin'),
					'base' => 'rigid_typed',
					'icon' => $althem_icon,
					'description' => esc_html__('Animated typing', 'rigid-plugin'),
					'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
					'params' => array(
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Text before typing rotator', 'rigid-plugin'),
									'param_name' => 'txt_before_typed',
									'value' => '',
									'description' => esc_html__('Enter text to be shown before typing rotator.', 'rigid-plugin'),
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Rotating strings', 'rigid-plugin'),
									'param_name' => 'rotating_strings',
									'value' => 'One,Two,Tree',
									'description' => esc_html__('Enter strings to be rotated, separated by comma, (e.g. One,Two,Tree). Please only use letters and numbers. No special characters. If it’s necessary to use special characters, please use HTML Entities instead (e.g. “&amp;amp;” instead of “&”)', 'rigid-plugin'),
									'admin_label' => true
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Text after typing rotator', 'rigid-plugin'),
									'param_name' => 'txt_after_typed',
									'value' => '',
									'description' => esc_html__('Enter text to be shown after typing rotator.', 'rigid-plugin'),
							),
							array(
									'type' => 'dropdown',
									'param_name' => 'typed_style',
									'value' => array(
											'H1' => 'h1',
											'H2' => 'h2',
											'H3' => 'h3',
											'H4' => 'h4',
											'H5' => 'h5',
											'H6' => 'h6',
											'Paragraph' => 'paragraph'
									),
									'std' => 'h4',
									'heading' => esc_html__('Typed style', 'rigid-plugin'),
									'description' => esc_html__('Select style.', 'rigid-plugin'),
							),
							array(
									'type' => 'dropdown',
									'param_name' => 'typed_alignment',
									'value' => array(
											esc_html__('Left', 'rigid-plugin') => 'rigid-typed-left',
											esc_html__('Centered', 'rigid-plugin') => 'rigid-typed-centered',
											esc_html__('Right', 'rigid-plugin') => 'rigid-typed-right',
									),
									'std' => 'rigid-typed-left',
									'heading' => esc_html__('Alignment', 'rigid-plugin'),
									'description' => esc_html__('Select alignment style.', 'rigid-plugin'),
							),
							array(
									'type' => 'colorpicker',
									'heading' => esc_html__('Static text color', 'rigid-plugin'),
									'param_name' => 'static_text_color',
									'value' => '',
									'description' => esc_html__('Choose color for the static text.', 'rigid-plugin'),
							),
							array(
									'type' => 'colorpicker',
									'heading' => esc_html__('Typed text color', 'rigid-plugin'),
									'param_name' => 'typed_text_color',
									'value' => '',
									'description' => esc_html__('Choose color for the typed text.', 'rigid-plugin'),
							),
							array(
									'type' => 'checkbox',
									'heading' => esc_html__('Loop', 'rigid-plugin'),
									'param_name' => 'loop',
									'value' => array(esc_html__('Start from beginning after the last string.', 'rigid-plugin') => 'yes'),
									'std' => 'yes'
							),
							array(
									'type' => 'css_editor',
									'heading' => esc_html__('CSS box', 'rigid-plugin'),
									'param_name' => 'css',
									'group' => esc_html__('Design Options', 'rigid-plugin'),
							),
					),)
			);

			// Map rigid_content_slider shortcode
			vc_map(array(
					'name' => esc_html__('Content Slider', 'rigid-plugin'),
					'base' => 'rigid_content_slider',
					'icon' => $althem_icon,
					'is_container' => true,
					'show_settings_on_create' => false,
					'as_parent' => array(
							'only' => 'vc_tta_section',
					),
					'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
					'description' => esc_html__('Slide any content', 'rigid-plugin'),
					'params' => array(
							array(
									'type' => 'textfield',
									'param_name' => 'title',
									'heading' => esc_html__('Widget title', 'rigid-plugin'),
									'description' => esc_html__('Enter text used as widget title (Note: located above content element).', 'rigid-plugin'),
							),
							array(
									'type' => 'hidden',
									'param_name' => 'no_fill_content_area',
									'std' => true,
							),
							array(
									'type' => 'dropdown',
									'param_name' => 'autoplay',
									'value' => array(
											esc_html__('None', 'rigid-plugin') => 'none',
											'1' => '1',
											'2' => '2',
											'3' => '3',
											'4' => '4',
											'5' => '5',
											'10' => '10',
											'20' => '20',
											'30' => '30',
											'40' => '40',
											'50' => '50',
											'60' => '60',
									),
									'std' => 'none',
									'heading' => esc_html__('Autoplay', 'rigid-plugin'),
									'description' => esc_html__('Select auto rotate for pageable in seconds (Note: disabled by default).', 'rigid-plugin'),
							),
							array(
									'type' => 'checkbox',
									'heading' => esc_html__('Full-Height slider', 'rigid-plugin'),
									'param_name' => 'full_height',
									'value' => array(esc_html__('Display slider in full height', 'rigid-plugin') => 'yes'),
							),
							array(
									'type' => 'checkbox',
									'heading' => esc_html__('Prev / Next navigation', 'rigid-plugin'),
									'param_name' => 'navigation',
									'value' => array(esc_html__('Enable Prev / Next navigation', 'rigid-plugin') => 'yes'),
									'std' => 'yes'
							),
							array(
									'type' => 'dropdown',
									'param_name' => 'navigation_color',
									'value' => array(
											esc_html__('Light', 'rigid-plugin') => 'rigid_content_slider_light_nav',
											esc_html__('Dark', 'rigid-plugin') => 'rigid_content_slider_dark_nav',
									),
									'std' => 'rigid_content_slider_light_nav',
									'heading' => esc_html__('Navigation Color', 'rigid-plugin'),
									'description' => esc_html__('Choose light or dark navigation, depending on the background.', 'rigid-plugin'),
							),
							array(
									'type' => 'checkbox',
									'heading' => esc_html__('Pause On Hover', 'rigid-plugin'),
									'param_name' => 'pause_on_hover',
									'value' => array(esc_html__('Should the slider pause on hover', 'rigid-plugin') => 'yes'),
									'std' => 'yes'
							),
							array(
									'type' => 'checkbox',
									'heading' => esc_html__('Pagination', 'rigid-plugin'),
									'param_name' => 'pagination',
									'value' => array(esc_html__('Enable pagination', 'rigid-plugin') => 'yes'),
							),
							array(
									'type' => 'dropdown',
									'param_name' => 'pagination_type',
									'value' => array(
											esc_html__('Bullets', 'rigid-plugin') => 'rigid-pagination-bullets',
											esc_html__('Numbers', 'rigid-plugin') => 'rigid-pagination-numbers'
									),
									'std' => 'rigid-pagination-bullets',
									'heading' => esc_html__('Pagination Type', 'rigid-plugin'),
									'description' => esc_html__('Select pagination type.', 'rigid-plugin'),
									'dependency' => array(
											'element' => 'pagination',
											'value' => 'yes'
									)
							),
							array(
									'type' => 'dropdown',
									'param_name' => 'transition',
									'value' => array(
											esc_html__('Fade', 'rigid-plugin') => 'fade',
											esc_html__('Slide', 'rigid-plugin') => 'slide',
											esc_html__('Slide-Flip', 'rigid-plugin') => 'slide-flip',
									),
									'std' => 'fade',
									'heading' => esc_html__('Transition', 'rigid-plugin'),
									'description' => esc_html__('Select transition effect.', 'rigid-plugin'),
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Extra class name', 'rigid-plugin'),
									'param_name' => 'el_class',
									'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'rigid-plugin') . '<br/><br/>' . esc_html__('NOTE: If video backgrounds are used in slides content, the loop slides option would be automatically disabled for compatibility reasons.', 'rigid-plugin'),
							),
							array(
									'type' => 'css_editor',
									'heading' => esc_html__('CSS box', 'rigid-plugin'),
									'param_name' => 'css',
									'group' => esc_html__('Design Options', 'rigid-plugin'),
							),
					),
					'js_view' => 'VcBackendTtaPageableView',
					'custom_markup' => '
<div class="vc_tta-container vc_tta-o-non-responsive" data-vc-action="collapse">
	<div class="vc_general vc_tta vc_tta-tabs vc_tta-pageable vc_tta-color-backend-tabs-white vc_tta-style-flat vc_tta-shape-rounded vc_tta-spacing-1 vc_tta-tabs-position-top vc_tta-controls-align-left">
		<div class="vc_tta-tabs-container">'
					. '<ul class="vc_tta-tabs-list">'
					. '<li class="vc_tta-tab" data-vc-tab data-vc-target-model-id="{{ model_id }}" data-element_type="vc_tta_section"><a href="javascript:;" data-vc-tabs data-vc-container=".vc_tta" data-vc-target="[data-model-id=\'{{ model_id }}\']" data-vc-target-model-id="{{ model_id }}"><span class="vc_tta-title-text">{{ section_title }}</span></a></li>'
					. '</ul>
		</div>
		<div class="vc_tta-panels vc_clearfix {{container-class}}">
		  {{ content }}
		</div>
	</div>
</div>',
					'default_content' => '
[vc_tta_section title="' . sprintf('%s %d', esc_html__('Section', 'rigid-plugin'), 1) . '"][/vc_tta_section]
[vc_tta_section title="' . sprintf('%s %d', esc_html__('Section', 'rigid-plugin'), 2) . '"][/vc_tta_section]
	',
					'admin_enqueue_js' => array(
							vc_asset_url('lib/vc_tabs/vc-tabs.min.js'),
					),
			));

// Map rigidblogposts shortcode
			vc_map(array(
					'name' => esc_html__('Blog Posts', 'rigid-plugin'),
					'base' => 'rigidblogposts',
					'icon' => $althem_icon,
					'description' => esc_html__('Output Blog posts with customizable Blog style', 'rigid-plugin'),
					'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
					'params' => array(
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Blog style', 'rigid-plugin'),
									'param_name' => 'blog_style',
									'value' => array(
											esc_html__('Standard', 'rigid-plugin') => '',
											esc_html__('Masonry', 'rigid-plugin') => 'rigid_blog_masonry',
									),
									'description' => esc_html__('Choose how the posts will appear.', 'rigid-plugin')
							),
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Sorting direction', 'rigid-plugin'),
									'param_name' => 'date_sort',
									'value' => array(
											esc_html__('WordPress Default', 'rigid-plugin') => 'default',
											esc_html__('Ascending', 'rigid-plugin') => 'ASC',
											esc_html__('Descending', 'rigid-plugin') => 'DESC'
									),
									'description' => esc_html__('Choose the date sorting direction.', 'rigid-plugin')
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Posts per page', 'rigid-plugin'),
									'param_name' => 'number_of_posts',
									'value' => '',
									'description' => esc_html__('Enter the number of posts displayed per page. Leave blank for default.', 'rigid-plugin'),
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Offset', 'rigid-plugin'),
									'param_name' => 'offset',
									'value' => '',
									'description' => esc_html__('Set number of posts to be skipped.', 'rigid-plugin'),
							)
					)
			));

			// Map rigid_latest_posts shortcode
			// Define filters to be able to use the Taxonomies search autocomplete field
			add_filter('vc_autocomplete_rigid_latest_posts_taxonomies_callback', 'rigid_latest_posts_category_field_search', 10, 1);
			add_filter('vc_autocomplete_rigid_latest_posts_taxonomies_render', 'vc_autocomplete_taxonomies_field_render', 10, 1);
			vc_map(array(
					'name' => esc_html__('Latest Posts', 'rigid-plugin'),
					'base' => 'rigid_latest_posts',
					'icon' => $althem_icon,
					'description' => esc_html__('Show Latest Posts', 'rigid-plugin'),
					'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
					'params' => array(
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Layout', 'rigid-plugin'),
									'value' => array(
											esc_html__('Grid', 'rigid-plugin') => 'grid',
											esc_html__('Carousel', 'rigid-plugin') => 'carousel',
									),
									'param_name' => 'layout',
									'admin_label' => true
							),
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Columns', 'rigid-plugin'),
									'value' => $latest_projects_columns_values,
									'param_name' => 'columns',
									'description' => esc_html__('Number of columns', 'rigid-plugin'),
									'admin_label' => true,
									'dependency' => array(
											'element' => 'layout',
											'value' => array('grid', 'carousel')
									)
							),
							array(
									'type' => 'autocomplete',
									'heading' => esc_html__('Filter By Category', 'rigid-plugin'),
									'param_name' => 'taxonomies',
									'settings' => array(
											'multiple' => true,
											'min_length' => 1,
											'groups' => false,
											// In UI show results grouped by groups, default false
											'unique_values' => true,
											// In UI show results except selected. NB! You should manually check values in backend, default false
											'display_inline' => true,
											// In UI show results inline view, default false (each value in own line)
											'delay' => 500,
											// delay for search. default 500
											'auto_focus' => true,
									// auto focus input, default true
									),
									'param_holder_class' => 'vc_not-for-custom',
									'description' => esc_html__('Enter category names.', 'rigid-plugin'),
									'admin_label' => true
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Number of posts', 'rigid-plugin'),
									'param_name' => 'number_of_posts',
									'value' => '4',
									'description' => esc_html__('Enter the number of posts to be displayed.', 'rigid-plugin'),
									'admin_label' => true
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Offset', 'rigid-plugin'),
									'param_name' => 'offset',
									'value' => '',
									'description' => esc_html__('Set number of posts to be skipped.', 'rigid-plugin'),
							),
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Sorting direction', 'rigid-plugin'),
									'param_name' => 'date_sort',
									'value' => array(
											esc_html__('WordPress Default', 'rigid-plugin') => 'default',
											esc_html__('Ascending', 'rigid-plugin') => 'ASC',
											esc_html__('Descending', 'rigid-plugin') => 'DESC'
									),
									'description' => esc_html__('Choose the date sorting direction.', 'rigid-plugin'),
									'admin_label' => true
							),
							array(
									'type' => 'css_editor',
									'heading' => esc_html__('CSS box', 'rigid-plugin'),
									'param_name' => 'css',
									'group' => esc_html__('Design Options', 'rigid-plugin'),
							),
					)
			));

// Map rigid_banner shortcode
			vc_map(array(
					'name' => esc_html__('Banner', 'rigid-plugin'),
					'base' => 'rigid_banner',
					'icon' => $althem_icon,
					'description' => esc_html__('Output configurable banner', 'rigid-plugin'),
					'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
					'params' => array(
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Alignment', 'rigid-plugin'),
									'value' => $banner_alignment_styles,
									'param_name' => 'alignment',
									'description' => esc_html__('Choose alginment style for the banner.', 'rigid-plugin'),
							),
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Icon library', 'rigid-plugin'),
									'value' => array(
											esc_html__('Font Awesome', 'rigid-plugin') => 'fontawesome',
											esc_html__('Open Iconic', 'rigid-plugin') => 'openiconic',
											esc_html__('Typicons', 'rigid-plugin') => 'typicons',
											esc_html__('Entypo', 'rigid-plugin') => 'entypo',
											esc_html__('Linecons', 'rigid-plugin') => 'linecons',
											esc_html__('Elegant Icons Font', 'rigid-plugin') => 'etline',
									),
									'admin_label' => true,
									'param_name' => 'type',
									'description' => esc_html__('Select icon library.', 'rigid-plugin'),
							),
							array(
									'type' => 'iconpicker',
									'heading' => esc_html__('Icon', 'rigid-plugin'),
									'param_name' => 'icon_fontawesome',
									'value' => '', // default value to backend editor admin_label
									'settings' => array(
											'emptyIcon' => true, // default true, display an "EMPTY" icon?
											'type' => 'fontawesome',
											'iconsPerPage' => 4000, // default 100, how many icons per/page to display, we use (big number) to display all icons in single page
									),
									'dependency' => array(
											'element' => 'type',
											'value' => 'fontawesome',
									),
									'description' => esc_html__('Select icon from library.', 'rigid-plugin'),
							),
							array(
									'type' => 'iconpicker',
									'heading' => esc_html__('Icon', 'rigid-plugin'),
									'param_name' => 'icon_openiconic',
									'value' => 'vc-oi vc-oi-dial', // default value to backend editor admin_label
									'settings' => array(
											'emptyIcon' => true, // default true, display an "EMPTY" icon?
											'type' => 'openiconic',
											'iconsPerPage' => 4000, // default 100, how many icons per/page to display
									),
									'dependency' => array(
											'element' => 'type',
											'value' => 'openiconic',
									),
									'description' => esc_html__('Select icon from library.', 'rigid-plugin'),
							),
							array(
									'type' => 'iconpicker',
									'heading' => esc_html__('Icon', 'rigid-plugin'),
									'param_name' => 'icon_typicons',
									'value' => 'typcn typcn-adjust-brightness', // default value to backend editor admin_label
									'settings' => array(
											'emptyIcon' => true, // default true, display an "EMPTY" icon?
											'type' => 'typicons',
											'iconsPerPage' => 4000, // default 100, how many icons per/page to display
									),
									'dependency' => array(
											'element' => 'type',
											'value' => 'typicons',
									),
									'description' => esc_html__('Select icon from library.', 'rigid-plugin'),
							),
							array(
									'type' => 'iconpicker',
									'heading' => esc_html__('Icon', 'rigid-plugin'),
									'param_name' => 'icon_entypo',
									'value' => 'entypo-icon entypo-icon-note', // default value to backend editor admin_label
									'settings' => array(
											'emptyIcon' => true, // default true, display an "EMPTY" icon?
											'type' => 'entypo',
											'iconsPerPage' => 4000, // default 100, how many icons per/page to display
									),
									'dependency' => array(
											'element' => 'type',
											'value' => 'entypo',
									),
							),
							array(
									'type' => 'iconpicker',
									'heading' => esc_html__('Icon', 'rigid-plugin'),
									'param_name' => 'icon_linecons',
									'value' => 'vc_li vc_li-heart', // default value to backend editor admin_label
									'settings' => array(
											'emptyIcon' => true, // default true, display an "EMPTY" icon?
											'type' => 'linecons',
											'iconsPerPage' => 4000, // default 100, how many icons per/page to display
									),
									'dependency' => array(
											'element' => 'type',
											'value' => 'linecons',
									),
									'description' => esc_html__('Select icon from library.', 'rigid-plugin'),
							),
							array(
									'type' => 'iconpicker',
									'heading' => esc_html__('Icon', 'rigid-plugin'),
									'param_name' => 'icon_etline',
									'value' => 'icon-mobile', // default value to backend editor admin_label
									'settings' => array(
											'emptyIcon' => true,
											'type' => 'etline',
											'iconsPerPage' => 100,
									// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
									),
									'dependency' => array(
											'element' => 'type',
											'value' => 'etline',
									),
									'description' => esc_html__('Select icon from library.', 'rigid-plugin'),
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Title', 'rigid-plugin'),
									'param_name' => 'title',
									'value' => '',
									'description' => esc_html__('Enter the banner title.', 'rigid-plugin'),
									'admin_label' => true
							),
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Title Font Size', 'rigid-plugin'),
									'param_name' => 'title_size',
									'value' => array(
											esc_html__('Default', 'rigid-plugin') => '',
											esc_html__('Big', 'rigid-plugin') => 'rigid_banner_big',
									),
									'description' => esc_html__('Choose predefined title font size.', 'rigid-plugin'),
									'admin_label' => true
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Sub Title', 'rigid-plugin'),
									'param_name' => 'subtitle',
									'value' => '',
									'description' => esc_html__('Enter Sub Title.', 'rigid-plugin'),
							),
							array(
									'type' => 'attach_image',
									'heading' => esc_html__('Image', 'rigid-plugin'),
									'param_name' => 'image_id',
									'value' => '',
									'description' => esc_html__('Choose image for the banner. (Actual size will be used)', 'rigid-plugin')
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Link', 'rigid-plugin'),
									'param_name' => 'link',
									'value' => '',
									'description' => esc_html__('Enter the URL where the banner will lead to.', 'rigid-plugin'),
							),
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Open in', 'rigid-plugin'),
									'value' => array(
											esc_html__('New window', 'rigid-plugin') => '_blank',
											esc_html__('Same window', 'rigid-plugin') => '_self',
									),
									'param_name' => 'link_target',
									'description' => esc_html__('Open link in new window or current.', 'rigid-plugin'),
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Button Text', 'rigid-plugin'),
									'param_name' => 'button_text',
									'value' => '',
									'description' => esc_html__('Enter text for the button.', 'rigid-plugin'),
							),
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Color Scheme', 'rigid-plugin'),
									'param_name' => 'color_scheme',
									'value' => array(
											esc_html__('Light', 'rigid-plugin') => '',
											esc_html__('Dark', 'rigid-plugin') => 'rigid-banner-dark',
									),
									'description' => esc_html__('Choose the color scheme.', 'rigid-plugin')
							),
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Appear Animation', 'rigid-plugin'),
									'param_name' => 'appear_animation',
									'value' => array(
											esc_html__('none', 'rigid-plugin') => '',
											esc_html__('From Left', 'rigid-plugin') => 'rigid-from-left',
											esc_html__('From Right', 'rigid-plugin') => 'rigid-from-right',
											esc_html__('From Bottom', 'rigid-plugin') => 'rigid-from-bottom',
											esc_html__('Fade', 'rigid-plugin') => 'rigid-fade'
									),
									'description' => esc_html__('Choose how the element will appear.', 'rigid-plugin')
							),
							array(
									'type' => 'css_editor',
									'heading' => esc_html__('CSS box', 'rigid-plugin'),
									'param_name' => 'css',
									'group' => esc_html__('Design Options', 'rigid-plugin'),
							),
					),
					'js_view' => 'VcIconElementView_Backend',
			));

			// Map rigid_cloudzoom_gallery shortcode
			vc_map(array(
					'name' => esc_html__('CloudZoom gallery', 'rigid-plugin'),
					'base' => 'rigid_cloudzoom_gallery',
					'icon' => $althem_icon,
					'description' => esc_html__('Output CloudZoom gallery', 'rigid-plugin'),
					'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
					'params' => array(
							array(
									'type' => 'attach_images',
									'heading' => esc_html__('Images', 'rigid-plugin'),
									'param_name' => 'images',
									'value' => '',
									'description' => esc_html__('Choose images for the gallery.', 'rigid-plugin')
							),
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Main image size', 'rigid-plugin'),
									'param_name' => 'img_size',
									'value' => array(
											esc_html__('Big', 'rigid-plugin') => 'rigid-general-big-size',
											esc_html__('Middle', 'rigid-plugin') => 'rigid-portfolio-category-thumb',
											esc_html__('Small', 'rigid-plugin') => 'rigid-blog-small-image-size'
									),
									'description' => esc_html__('Choose from the predefined main image sizes.', 'rigid-plugin')
							),
					)
			));

			// Define filters to be able to use the Taxonomies search autocomplete field
			add_filter('vc_autocomplete_rigid_latest_projects_taxonomies_callback', 'rigid_portfolio_category_field_search', 10, 1);
			add_filter('vc_autocomplete_rigid_latest_projects_taxonomies_render', 'vc_autocomplete_taxonomies_field_render', 10, 1);

			// Map rigid_latest_projects shortcode
			vc_map(array(
					'name' => esc_html__('Projects', 'rigid-plugin'),
					'base' => 'rigid_latest_projects',
					'icon' => $althem_icon,
					'description' => esc_html__('Customisable Projects List', 'rigid-plugin'),
					'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
					'params' => array(
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('View Style', 'rigid-plugin'),
									'value' => array(
											esc_html__('Grid View', 'rigid-plugin') => 'grid',
											esc_html__('Carousel', 'rigid-plugin') => 'carousel',
											esc_html__('List View', 'rigid-plugin') => 'list',
									),
									'param_name' => 'display_style',
									'description' => esc_html__('Choose grid or list view', 'rigid-plugin'),
									'admin_label' => true
							),
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Columns', 'rigid-plugin'),
									'value' => $latest_projects_columns_values,
									'param_name' => 'columns',
									'description' => esc_html__('Number of columns', 'rigid-plugin'),
									'dependency' => array(
											'element' => 'display_style',
											'value' => array('grid', 'carousel')
									),
							),
							array(
									'type' => 'autocomplete',
									'heading' => esc_html__('Filter By Category', 'rigid-plugin'),
									'param_name' => 'taxonomies',
									'settings' => array(
											'multiple' => true,
											'min_length' => 1,
											'groups' => false,
											// In UI show results grouped by groups, default false
											'unique_values' => true,
											// In UI show results except selected. NB! You should manually check values in backend, default false
											'display_inline' => true,
											// In UI show results inline view, default false (each value in own line)
											'delay' => 500,
											// delay for search. default 500
											'auto_focus' => true,
									// auto focus input, default true
									),
									'param_holder_class' => 'vc_not-for-custom',
									'description' => esc_html__('Enter project category names.', 'rigid-plugin'),
									'admin_label' => true
							),
							array(
									'type' => 'checkbox',
									'heading' => esc_html__('None-Overlay', 'rigid-plugin'),
									'value' => array(esc_html__('Use none-overlay style', 'rigid-plugin') => 'yes'),
									'param_name' => 'none_overlay',
									'dependency' => array(
											'element' => 'display_style',
											'value' => array('grid', 'carousel'),
									),
							),
							array(
									'type' => 'checkbox',
									'heading' => esc_html__('Portfolio Gaps', 'rigid-plugin'),
									'value' => array(esc_html__('Show gap between projects', 'rigid-plugin') => 'yes'),
									'param_name' => 'portfoio_cat_display',
									'dependency' => array(
											'element' => 'display_style',
											'value' => array('grid', 'carousel'),
									),
							),
							array(
									'type' => 'checkbox',
									'heading' => esc_html__('Lightbox', 'rigid-plugin'),
									'param_name' => 'show_lightbox',
									'value' => array(esc_html__('Show link that opens the featured image in lightbox', 'rigid-plugin') => 'yes'),
							),
							array(
									'type' => 'checkbox',
									'heading' => esc_html__('Sortable', 'rigid-plugin'),
									'param_name' => 'enable_sortable',
									'value' => array(esc_html__('Enable Sortable', 'rigid-plugin') => 'yes'),
									'description' => esc_html__('Show the projects categories on top and show only projects from cpecific category, using sortable.', 'rigid-plugin'),
									'admin_label' => true,
									'dependency' => array(
											'element' => 'display_style',
											'value' => array('grid', 'list'),
									),
							),
							array(
									'type' => 'checkbox',
									'heading' => esc_html__('Masonry', 'rigid-plugin'),
									'param_name' => 'enable_masonry',
									'value' => array(esc_html__('Enable Masonry', 'rigid-plugin') => 'yes'),
									'description' => esc_html__('Projects will be listed with soft crop of their featured images, keeping the original ratio.', 'rigid-plugin'),
									'admin_label' => true,
									'dependency' => array(
											'element' => 'display_style',
											'value' => 'grid',
									),
							),
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Sorting direction', 'rigid-plugin'),
									'param_name' => 'date_sort',
									'value' => array(
											esc_html__('Descending', 'rigid-plugin') => 'DESC',
											esc_html__('Ascending', 'rigid-plugin') => 'ASC'
									),
									'description' => esc_html__('Choose the date sorting direction.', 'rigid-plugin')
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Number of Projects Listed', 'rigid-plugin'),
									'param_name' => 'limit',
									'value' => '4',
									'description' => esc_html__('Enter the number of projects to be displayed. NOTE: If leaved empty and carousel option is not selected, porjects will be listed with infinite scroll/pagination', 'rigid-plugin'),
									'admin_label' => true
							),
							array(
									'type' => 'checkbox',
									'heading' => esc_html__('Use Pagination', 'rigid-plugin'),
									'param_name' => 'use_pagination',
									'value' => array(esc_html__('Use pagination instead of infinite scroll', 'rigid-plugin') => 'yes'),
									'admin_label' => true,
									'dependency' => array(
											'element' => 'display_style',
											'value' => array('grid', 'list'),
									),
							),
							array(
									'type' => 'checkbox',
									'heading' => esc_html__('Use Load More Button', 'rigid-plugin'),
									'param_name' => 'use_load_more',
									'value' => array(esc_html__('Use Load More Button to load the additional content', 'rigid-plugin') => 'yes'),
									'admin_label' => true,
									'dependency' => array(
											'element' => 'use_pagination',
											'value_not_equal_to' => 'yes',
									),
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Offset', 'rigid-plugin'),
									'param_name' => 'offset',
									'value' => '',
									'description' => esc_html__('Set number of projects to be skipped.', 'rigid-plugin'),
							),
							array(
									'type' => 'css_editor',
									'heading' => esc_html__('CSS box', 'rigid-plugin'),
									'param_name' => 'css',
									'group' => esc_html__('Design Options', 'rigid-plugin'),
							),
					)
			));

			// Map rigid_icon shortcode
			vc_map(array(
					'name' => esc_html__('Icon Title', 'rigid-plugin'),
					'base' => 'rigid_icon',
					'icon' => $althem_icon,
					'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
					'description' => esc_html__('Icon with text', 'rigid-plugin'),
					'params' => array(
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Icon library', 'rigid-plugin'),
									'value' => array(
											esc_html__('Font Awesome', 'rigid-plugin') => 'fontawesome',
											esc_html__('Open Iconic', 'rigid-plugin') => 'openiconic',
											esc_html__('Typicons', 'rigid-plugin') => 'typicons',
											esc_html__('Entypo', 'rigid-plugin') => 'entypo',
											esc_html__('Linecons', 'rigid-plugin') => 'linecons',
											esc_html__('Elegant Icons Font', 'rigid-plugin') => 'etline',
									),
									'admin_label' => true,
									'param_name' => 'type',
									'description' => esc_html__('Select icon library.', 'rigid-plugin'),
							),
							array(
									'type' => 'iconpicker',
									'heading' => esc_html__('Icon', 'rigid-plugin'),
									'param_name' => 'icon_fontawesome',
									'value' => 'fa fa-adjust', // default value to backend editor admin_label
									'settings' => array(
											'emptyIcon' => false, // default true, display an "EMPTY" icon?
											'type' => 'fontawesome',
											'iconsPerPage' => 4000, // default 100, how many icons per/page to display, we use (big number) to display all icons in single page
									),
									'dependency' => array(
											'element' => 'type',
											'value' => 'fontawesome',
									),
									'description' => esc_html__('Select icon from library.', 'rigid-plugin'),
							),
							array(
									'type' => 'iconpicker',
									'heading' => esc_html__('Icon', 'rigid-plugin'),
									'param_name' => 'icon_openiconic',
									'value' => 'vc-oi vc-oi-dial', // default value to backend editor admin_label
									'settings' => array(
											'emptyIcon' => false, // default true, display an "EMPTY" icon?
											'type' => 'openiconic',
											'iconsPerPage' => 4000, // default 100, how many icons per/page to display
									),
									'dependency' => array(
											'element' => 'type',
											'value' => 'openiconic',
									),
									'description' => esc_html__('Select icon from library.', 'rigid-plugin'),
							),
							array(
									'type' => 'iconpicker',
									'heading' => esc_html__('Icon', 'rigid-plugin'),
									'param_name' => 'icon_typicons',
									'value' => 'typcn typcn-adjust-brightness', // default value to backend editor admin_label
									'settings' => array(
											'emptyIcon' => false, // default true, display an "EMPTY" icon?
											'type' => 'typicons',
											'iconsPerPage' => 4000, // default 100, how many icons per/page to display
									),
									'dependency' => array(
											'element' => 'type',
											'value' => 'typicons',
									),
									'description' => esc_html__('Select icon from library.', 'rigid-plugin'),
							),
							array(
									'type' => 'iconpicker',
									'heading' => esc_html__('Icon', 'rigid-plugin'),
									'param_name' => 'icon_entypo',
									'value' => 'entypo-icon entypo-icon-note', // default value to backend editor admin_label
									'settings' => array(
											'emptyIcon' => false, // default true, display an "EMPTY" icon?
											'type' => 'entypo',
											'iconsPerPage' => 4000, // default 100, how many icons per/page to display
									),
									'dependency' => array(
											'element' => 'type',
											'value' => 'entypo',
									),
							),
							array(
									'type' => 'iconpicker',
									'heading' => esc_html__('Icon', 'rigid-plugin'),
									'param_name' => 'icon_linecons',
									'value' => 'vc_li vc_li-heart', // default value to backend editor admin_label
									'settings' => array(
											'emptyIcon' => false, // default true, display an "EMPTY" icon?
											'type' => 'linecons',
											'iconsPerPage' => 4000, // default 100, how many icons per/page to display
									),
									'dependency' => array(
											'element' => 'type',
											'value' => 'linecons',
									),
									'description' => esc_html__('Select icon from library.', 'rigid-plugin'),
							),
							array(
									'type' => 'iconpicker',
									'heading' => esc_html__('Icon', 'rigid-plugin'),
									'param_name' => 'icon_etline',
									'value' => 'icon-mobile', // default value to backend editor admin_label
									'settings' => array(
											'emptyIcon' => false,
											'type' => 'etline',
											'iconsPerPage' => 100,
									// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
									),
									'dependency' => array(
											'element' => 'type',
											'value' => 'etline',
									),
									'description' => esc_html__('Select icon from library.', 'rigid-plugin'),
							),
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Color', 'rigid-plugin'),
									'param_name' => 'color',
									'value' => array_merge(array(
											'Blue' => 'blue',
											'Turquoise' => 'turquoise',
											'Pink' => 'pink',
											'Violet' => 'violet',
											'Peacoc' => 'peacoc',
											'Chino' => 'chino',
											'Mulled Wine' => 'mulled_wine',
											'Vista Blue' => 'vista_blue',
											'Black' => 'black',
											'Grey' => 'grey',
											'Orange' => 'orange',
											'Sky' => 'sky',
											'Green' => 'green',
											'Juicy pink' => 'juicy_pink',
											'Sandy brown' => 'sandy_brown',
											'Purple' => 'purple',
											'White' => 'white'
													), array(esc_html__('Custom color', 'rigid-plugin') => 'custom')),
									'description' => esc_html__('Icon color.', 'rigid-plugin'),
									'param_holder_class' => 'vc_colored-dropdown',
							),
							array(
									'type' => 'colorpicker',
									'heading' => esc_html__('Custom Icon Color', 'rigid-plugin'),
									'param_name' => 'custom_color',
									'description' => esc_html__('Select custom icon color.', 'rigid-plugin'),
									'dependency' => array(
											'element' => 'color',
											'value' => 'custom',
									),
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Icon Text', 'rigid-plugin'),
									'value' => '',
									'param_name' => 'text',
									'description' => esc_html__('Enter the text that will be used with the icon', 'rigid-plugin'),
									'admin_label' => true
							),
							array(
									'type' => 'colorpicker',
									'heading' => esc_html__('Text color', 'rigid-plugin'),
									'param_name' => 'text_color',
									'description' => esc_html__('Select text color.', 'rigid-plugin'),
							),
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Tag', 'rigid-plugin'),
									'param_name' => 'tag',
									'value' => array(
											'H3' => 'h3',
											'H1' => 'h1',
											'H2' => 'h2',
											'H4' => 'h4',
											'H5' => 'h5',
											'H6' => 'h6',
											'P' => 'p'
									),
									'description' => esc_html__('Tag to be used with the icon.', 'rigid-plugin'),
							),
					),
					'js_view' => 'VcIconElementView_Backend',
			));

			// Map rigid_icon_teaser shortcode
			vc_map(array(
					'name' => esc_html__('Icon Teaser', 'rigid-plugin'),
					'base' => 'rigid_icon_teaser',
					'icon' => $althem_icon,
					'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
					'description' => esc_html__('Icon teaser', 'rigid-plugin'),
					'params' => array(
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Title', 'rigid-plugin'),
									'value' => '',
									'param_name' => 'title',
									'description' => esc_html__('Enter title', 'rigid-plugin'),
									'admin_label' => true
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Subtitle', 'rigid-plugin'),
									'value' => '',
									'param_name' => 'subtitle',
									'description' => esc_html__('Enter subtitle', 'rigid-plugin'),
							),
							array(
									'type' => 'colorpicker',
									'heading' => esc_html__('Title/Subtitle Color', 'rigid-plugin'),
									'param_name' => 'titles_color',
									'value' => '',
									'description' => esc_html__('Choose Title/Subtitle color.', 'rigid-plugin'),
							),
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Align', 'rigid-plugin'),
									'value' => array(
											'Left' => 'teaser-left',
											'Right' => 'teaser-right'
									),
									'param_name' => 'align',
									'description' => esc_html__('Choose alignment', 'rigid-plugin'),
							),
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Appear Animation', 'rigid-plugin'),
									'param_name' => 'appear_animation',
									'value' => array(
											esc_html__('none', 'rigid-plugin') => '',
											esc_html__('From Left', 'rigid-plugin') => 'rigid-from-left',
											esc_html__('From Right', 'rigid-plugin') => 'rigid-from-right',
											esc_html__('From Bottom', 'rigid-plugin') => 'rigid-from-bottom',
											esc_html__('Fade', 'rigid-plugin') => 'rigid-fade'
									),
									'description' => esc_html__('Choose how the element will appear.', 'rigid-plugin')
							),
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Icon library', 'rigid-plugin'),
									'value' => array(
											esc_html__('Font Awesome', 'rigid-plugin') => 'fontawesome',
											esc_html__('Elegant Icons Font', 'rigid-plugin') => 'etline',
									),
									'param_name' => 'type',
									'admin_label' => true,
									'description' => esc_html__('Select icon library.', 'rigid-plugin'),
							),
							array(
									'type' => 'iconpicker',
									'heading' => esc_html__('Icon', 'rigid-plugin'),
									'param_name' => 'icon_fontawesome',
									'value' => 'fa fa-adjust', // default value to backend editor admin_label
									'settings' => array(
											'emptyIcon' => false, // default true, display an "EMPTY" icon?
											'type' => 'fontawesome',
											'iconsPerPage' => 4000, // default 100, how many icons per/page to display, we use (big number) to display all icons in single page
									),
									'dependency' => array(
											'element' => 'type',
											'value' => 'fontawesome',
									),
									'description' => esc_html__('Select icon from library.', 'rigid-plugin'),
							),
							array(
									'type' => 'iconpicker',
									'heading' => esc_html__('Icon', 'rigid-plugin'),
									'param_name' => 'icon_etline',
									'value' => 'icon-mobile', // default value to backend editor admin_label
									'settings' => array(
											'emptyIcon' => false,
											'type' => 'etline',
											'iconsPerPage' => 100,
									// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
									),
									'dependency' => array(
											'element' => 'type',
											'value' => 'etline',
									),
									'description' => esc_html__('Select icon from library.', 'rigid-plugin'),
							),
							array(
									'type' => 'colorpicker',
									'heading' => esc_html__('Icon color', 'rigid-plugin'),
									'param_name' => 'color',
									'description' => esc_html__('Select icon color.', 'rigid-plugin'),
							),
							array(
									'type' => 'textarea_html',
									'holder' => 'div',
									'class' => '',
									'heading' => esc_html__('Text', 'rigid-plugin'),
									'value' => '',
									'param_name' => 'content',
									'description' => esc_html__('Enter the text that will be used with the icon', 'rigid-plugin'),
							),
					),
					'js_view' => 'VcIconElementView_Backend',
			));

			// Map rigid_icon_box shortcode
			vc_map(array(
					'name' => esc_html__('Icon Box', 'rigid-plugin'),
					'base' => 'rigid_icon_box',
					'icon' => $althem_icon,
					'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
					'description' => esc_html__('Icon box', 'rigid-plugin'),
					'params' => array(
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Title', 'rigid-plugin'),
									'value' => '',
									'param_name' => 'title',
									'description' => esc_html__('Enter title', 'rigid-plugin'),
									'admin_label' => true
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Subtitle', 'rigid-plugin'),
									'value' => '',
									'param_name' => 'subtitle',
									'description' => esc_html__('Enter subtitle', 'rigid-plugin'),
							),
							array(
									'type' => 'colorpicker',
									'heading' => esc_html__('Title/Subtitle Color', 'rigid-plugin'),
									'param_name' => 'titles_color',
									'value' => '',
									'description' => esc_html__('Choose Title/Subtitle color.', 'rigid-plugin'),
							),
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Icon library', 'rigid-plugin'),
									'value' => array(
											esc_html__('Font Awesome', 'rigid-plugin') => 'fontawesome',
											esc_html__('Elegant Icons Font', 'rigid-plugin') => 'etline',
									),
									'param_name' => 'type',
									'admin_label' => true,
									'description' => esc_html__('Select icon library.', 'rigid-plugin'),
							),
							array(
									'type' => 'iconpicker',
									'heading' => esc_html__('Icon', 'rigid-plugin'),
									'param_name' => 'icon_fontawesome',
									'value' => 'fa fa-adjust', // default value to backend editor admin_label
									'settings' => array(
											'emptyIcon' => false, // default true, display an "EMPTY" icon?
											'type' => 'fontawesome',
											'iconsPerPage' => 4000, // default 100, how many icons per/page to display, we use (big number) to display all icons in single page
									),
									'dependency' => array(
											'element' => 'type',
											'value' => 'fontawesome',
									),
									'description' => esc_html__('Select icon from library.', 'rigid-plugin'),
							),
							array(
									'type' => 'iconpicker',
									'heading' => esc_html__('Icon', 'rigid-plugin'),
									'param_name' => 'icon_etline',
									'value' => 'icon-mobile', // default value to backend editor admin_label
									'settings' => array(
											'emptyIcon' => false,
											'type' => 'etline',
											'iconsPerPage' => 100,
									// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
									),
									'dependency' => array(
											'element' => 'type',
											'value' => 'etline',
									),
									'description' => esc_html__('Select icon from library.', 'rigid-plugin'),
							),
							array(
									'type' => 'colorpicker',
									'heading' => esc_html__('Icon color', 'rigid-plugin'),
									'param_name' => 'color',
									'description' => esc_html__('Select icon color.', 'rigid-plugin'),
							),
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Alignment', 'rigid-plugin'),
									'param_name' => 'alignment',
									'value' => array(
											esc_html__('Center', 'rigid-plugin') => '',
											esc_html__('Left', 'rigid-plugin') => 'rigid-icon-box-left',
											esc_html__('Right', 'rigid-plugin') => 'rigid-icon-box-right'
									),
									'description' => esc_html__('Choose icon alignment.', 'rigid-plugin')
							),
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Icon Style', 'rigid-plugin'),
									'param_name' => 'icon_style',
									'value' => array(
											esc_html__('Circle', 'rigid-plugin') => '',
											esc_html__('Square', 'rigid-plugin') => 'rigid-icon-box-square',
											esc_html__('Clean', 'rigid-plugin') => 'rigid-clean-icon'
									),
									'description' => esc_html__('Choose icon style.', 'rigid-plugin')
							),
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Appear Animation', 'rigid-plugin'),
									'param_name' => 'appear_animation',
									'value' => array(
											esc_html__('none', 'rigid-plugin') => '',
											esc_html__('From Left', 'rigid-plugin') => 'rigid-from-left',
											esc_html__('From Right', 'rigid-plugin') => 'rigid-from-right',
											esc_html__('From Bottom', 'rigid-plugin') => 'rigid-from-bottom',
											esc_html__('Fade', 'rigid-plugin') => 'rigid-fade'
									),
									'description' => esc_html__('Choose how the element will appear.', 'rigid-plugin')
							),
							array(
									'type' => 'textarea_html',
									'holder' => 'div',
									'class' => '',
									'heading' => esc_html__('Text', 'rigid-plugin'),
									'value' => '',
									'param_name' => 'content',
									'description' => esc_html__('Enter the text that will be used with the icon', 'rigid-plugin'),
							),
					),
					'js_view' => 'VcIconElementView_Backend',
			));

			// Map rigid_map shortcode
			vc_map(array(
					'name' => esc_html__('Map', 'rigid-plugin'),
					'base' => 'rigid_map',
					'icon' => $althem_icon,
					'description' => esc_html__('Map with location', 'rigid-plugin'),
					'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
					'params' => array(
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Location Title', 'rigid-plugin'),
									'param_name' => 'location_title',
									'value' => '',
									'description' => esc_html__('Will appear when hover over the location.', 'rigid-plugin'),
									'admin_label' => true
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Latitude', 'rigid-plugin'),
									'param_name' => 'map_latitude',
									'value' => '',
									'description' => esc_html__('Enter location latitude.', 'rigid-plugin'),
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Longitude', 'rigid-plugin'),
									'param_name' => 'map_longitude',
									'value' => '',
									'description' => esc_html__('Enter location longitude.', 'rigid-plugin') . '</br></br>' . sprintf(_x('Go to %s and get your location coordinates: </br>(e.g. Latitude: 40.588372 / Longitude: -74.240112)', 'theme-options', 'rigid-plugin'), '<a href="http://itouchmap.com/latlong.html" target="_blank">http://itouchmap.com/latlong.html</a>'),
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Map height', 'rigid-plugin'),
									'param_name' => 'height',
									'value' => '400',
									'description' => esc_html__('Map height in px.', 'rigid-plugin'),
							),
					)
			));
			// Map rigid_pricing_table shortcode
			vc_map(array(
					'name' => esc_html__('Pricing Table', 'rigid-plugin'),
					'base' => 'rigid_pricing_table',
					'icon' => $althem_icon,
					'description' => esc_html__('Create pricing tables', 'rigid-plugin'),
					'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
					'params' => array(
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Title', 'rigid-plugin'),
									'param_name' => 'title',
									'value' => '',
									'description' => esc_html__('Enter the table title.', 'rigid-plugin'),
									'admin_label' => true
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Sub Title', 'rigid-plugin'),
									'param_name' => 'subtitle',
									'value' => '',
									'description' => esc_html__('Enter sub title.', 'rigid-plugin'),
							),
							array(
									'type' => 'checkbox',
									'heading' => esc_html__('Styled for Dark Background', 'rigid-plugin'),
									'param_name' => 'styled_for_dark',
									'value' => array(esc_html__('Yes', 'rigid-plugin') => 'yes')
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Price Bills', 'rigid-plugin'),
									'param_name' => 'price',
									'value' => '',
									'description' => esc_html__('Enter the bills price for this package. e.g. 157.', 'rigid-plugin'),
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Price Coins', 'rigid-plugin'),
									'param_name' => 'price_coins',
									'value' => '',
									'description' => esc_html__('Enter the coins for the price. e.g. 49 , for a price of 157.49', 'rigid-plugin'),
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Currency Symbol', 'rigid-plugin'),
									'param_name' => 'currency_symbol',
									'value' => '',
									'description' => esc_html__('Enter the currency symbol for the price. e.g. $.', 'rigid-plugin'),
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Period', 'rigid-plugin'),
									'param_name' => 'period',
									'value' => '',
									'description' => esc_html__('e.g. per month.', 'rigid-plugin'),
							),
							array(
									'type' => 'dropdown',
									'heading' => esc_html__('Appear Animation', 'rigid-plugin'),
									'param_name' => 'appear_animation',
									'value' => array(
											esc_html__('none', 'rigid-plugin') => '',
											esc_html__('From Left', 'rigid-plugin') => 'rigid-from-left',
											esc_html__('From Right', 'rigid-plugin') => 'rigid-from-right',
											esc_html__('From Bottom', 'rigid-plugin') => 'rigid-from-bottom',
											esc_html__('Fade', 'rigid-plugin') => 'rigid-fade'
									),
									'description' => esc_html__('Choose how the element will appear.', 'rigid-plugin')
							),
							array(
									'type' => 'textarea_html',
									'holder' => 'div',
									'class' => '',
									'heading' => esc_html__('Content', 'rigid-plugin'),
									'value' => '',
									'param_name' => 'content',
									'description' => esc_html__('Enter the pricing table content', 'rigid-plugin'),
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Button Text', 'rigid-plugin'),
									'param_name' => 'button_text',
									'value' => '',
									'description' => esc_html__('Enter text for the button.', 'rigid-plugin'),
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Link', 'rigid-plugin'),
									'param_name' => 'link',
									'value' => '',
									'description' => esc_html__('Enter the URL for the button.', 'rigid-plugin'),
							),
							array(
									'type' => 'colorpicker',
									'heading' => esc_html__('Accent Color', 'rigid-plugin'),
									'param_name' => 'accent_color',
									'value' => '',
									'description' => esc_html__('Choose accent color of the pricing table.', 'rigid-plugin'),
							),
							array(
									'type' => 'checkbox',
									'heading' => esc_html__('Featured', 'rigid-plugin'),
									'param_name' => 'featured',
									'value' => array(esc_html__('Mark as featured', 'rigid-plugin') => 'yes')
							)
					),
					'js_view' => 'VcIconElementView_Backend',
			));
			// Map rigid_contact_form shortcode
			vc_map(array(
					'name' => esc_html__('Contact Form', 'rigid-plugin'),
					'base' => 'rigid_contact_form',
					'icon' => $althem_icon,
					'description' => esc_html__('Configurable Contact Form', 'rigid-plugin'),
					'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
					'params' => array(
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Title', 'rigid-plugin'),
									'param_name' => 'title',
									'value' => '',
									'description' => esc_html__('Enter contact form title.', 'rigid-plugin'),
									'admin_label' => true
							),
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Receiving Email Address', 'rigid-plugin'),
									'param_name' => 'contact_mail_to',
									'value' => $current_user_email,
									'description' => esc_html__('Email address for receing the contact form email.', 'rigid-plugin'),
									'admin_label' => true
							),
							array(
									'type' => 'checkbox',
									'heading' => esc_html__('Use Captcha', 'rigid-plugin'),
									'param_name' => 'simple_captcha',
									'value' => array(esc_html__('Use simple captcha for the contact form', 'rigid-plugin') => true),
							),
							array(
									'type' => 'checkbox',
									'heading' => esc_html__('Select Fields for the Contact Form', 'rigid-plugin'),
									'param_name' => 'contact_form_fields',
									'value' => array(
											esc_html__('Name', 'rigid-plugin') => 'name',
											esc_html__('E-Mail Address', 'rigid-plugin') => 'email',
											esc_html__('Phone', 'rigid-plugin') => 'phone',
											esc_html__('Street Address', 'rigid-plugin') => 'address',
											esc_html__('Subject', 'rigid-plugin') => 'subject',
									),
									'description' => esc_html__('Choose which fields to be displayed on the contact form. Selcted fields will also be required fields. The message textarea will be always displayed.', 'rigid-plugin')
							)
					)
			));
			// Map rigid_countdown shortcode
			vc_map(array(
					'name' => esc_html__('Countdown', 'rigid-plugin'),
					'base' => 'rigid_countdown',
					'icon' => $althem_icon,
					'description' => esc_html__('Customized Countdown', 'rigid-plugin'),
					'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
					'params' => array(
							array(
									'type' => 'textfield',
									'heading' => esc_html__('Expire Date', 'rigid-plugin'),
									'param_name' => 'date',
									'value' => '',
									'description' => esc_html__('Enter the end date for the counter.', 'rigid-plugin') . '<br/>' . esc_html__('Use following format YYYY/MM/DD HH:MM:SS, e.g. 2020/04/25 17:45:00', 'rigid-plugin'),
									'admin_label' => true
							),
							array(
									'type' => 'dropdown',
									'param_name' => 'counter_size',
									'value' => array(
											esc_html__('Normal', 'rigid-plugin') => '',
											esc_html__('Big', 'rigid-plugin') => 'rigid-counter-big',
									),
									'std' => '',
									'heading' => esc_html__('Size', 'rigid-plugin'),
									'description' => esc_html__('Select counter size.', 'rigid-plugin'),
							),
							array(
									'type' => 'colorpicker',
									'heading' => esc_html__('Color', 'rigid-plugin'),
									'param_name' => 'color',
									'value' => '',
									'description' => esc_html__('Choose counter color.', 'rigid-plugin'),
							)
					)
			));
// If WooCommerce is active
			if (is_plugin_active('woocommerce/woocommerce.php') || class_exists('WooCommerce')) {
				$order_by_values = array(
						'',
						esc_html__('Date', 'rigid-plugin') => 'date',
						esc_html__('ID', 'rigid-plugin') => 'ID',
						esc_html__('Author', 'rigid-plugin') => 'author',
						esc_html__('Title', 'rigid-plugin') => 'title',
						esc_html__('Modified', 'rigid-plugin') => 'modified',
						esc_html__('Random', 'rigid-plugin') => 'rand',
						esc_html__('Comment count', 'rigid-plugin') => 'comment_count',
						esc_html__('Menu order', 'rigid-plugin') => 'menu_order',
				);

				$order_way_values = array(
						'',
						esc_html__('Descending', 'rigid-plugin') => 'DESC',
						esc_html__('Ascending', 'rigid-plugin') => 'ASC',
				);

				$columns_values = array(2, 3, 4, 5, 6);

				// Map rigid_woo_top_rated_carousel shortcode
				vc_map(array(
						'name' => esc_html__('Top Rated Products Carousel', 'rigid-plugin'),
						'base' => 'rigid_woo_top_rated_carousel',
						'icon' => $althem_icon,
						'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
						'description' => esc_html__('List all products on sale in carousel', 'rigid-plugin'),
						'params' => array(
								array(
										'type' => 'textfield',
										'heading' => esc_html__('Per page', 'rigid-plugin'),
										'value' => 12,
										'param_name' => 'per_page',
										'description' => esc_html__('How much items per page to show', 'rigid-plugin'),
								),
								array(
										'type' => 'dropdown',
										'heading' => esc_html__('Columns', 'rigid-plugin'),
										'value' => $columns_values,
										'param_name' => 'columns',
										'description' => esc_html__('How much columns grid', 'rigid-plugin'),
								),
								array(
										'type' => 'dropdown',
										'heading' => esc_html__('Order by', 'rigid-plugin'),
										'param_name' => 'orderby',
										'value' => $order_by_values,
										'description' => sprintf(esc_html__('Select how to sort retrieved products. More at %s.', 'rigid-plugin'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>')
								),
								array(
										'type' => 'dropdown',
										'heading' => esc_html__('Order way', 'rigid-plugin'),
										'param_name' => 'order',
										'value' => $order_way_values,
										'description' => sprintf(esc_html__('Designates the ascending or descending order. More at %s.', 'rigid-plugin'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>')
								),
						)
				));

				// Map rigid_woo_recent_carousel shortcode
				vc_map(array(
						'name' => esc_html__('Recent Products Carousel', 'rigid-plugin'),
						'base' => 'rigid_woo_recent_carousel',
						'icon' => $althem_icon,
						'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
						'description' => esc_html__('Lists recent products in carousel', 'rigid-plugin'),
						'params' => array(
								array(
										'type' => 'textfield',
										'heading' => esc_html__('Per page', 'rigid-plugin'),
										'value' => 12,
										'param_name' => 'per_page',
										'description' => esc_html__('The "per_page" shortcode determines how many products to show on the page', 'rigid-plugin'),
								),
								array(
										'type' => 'dropdown',
										'heading' => esc_html__('Columns', 'rigid-plugin'),
										'value' => $columns_values,
										'param_name' => 'columns',
										'description' => esc_html__('The columns attribute controls how many columns wide the products should be before wrapping.', 'rigid-plugin'),
								),
								array(
										'type' => 'dropdown',
										'heading' => esc_html__('Order by', 'rigid-plugin'),
										'param_name' => 'orderby',
										'value' => $order_by_values,
										'description' => sprintf(esc_html__('Select how to sort retrieved products. More at %s.', 'rigid-plugin'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>')
								),
								array(
										'type' => 'dropdown',
										'heading' => esc_html__('Order way', 'rigid-plugin'),
										'param_name' => 'order',
										'value' => $order_way_values,
										'description' => sprintf(esc_html__('Designates the ascending or descending order. More at %s.', 'rigid-plugin'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>')
								),
						)
				));

				// Map rigid_woo_featured_carousel shortcode
				vc_map(array(
						'name' => esc_html__('Featured Products Carousel', 'rigid-plugin'),
						'base' => 'rigid_woo_featured_carousel',
						'icon' => $althem_icon,
						'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
						'description' => esc_html__('Display products set as featured in carousel', 'rigid-plugin'),
						'params' => array(
								array(
										'type' => 'textfield',
										'heading' => esc_html__('Per page', 'rigid-plugin'),
										'value' => 12,
										'param_name' => 'per_page',
										'description' => esc_html__('The "per_page" shortcode determines how many products to show on the page', 'rigid-plugin'),
								),
								array(
										'type' => 'dropdown',
										'heading' => esc_html__('Columns', 'rigid-plugin'),
										'value' => $columns_values,
										'param_name' => 'columns',
										'description' => esc_html__('The columns attribute controls how many columns wide the products should be before wrapping.', 'rigid-plugin'),
								),
								array(
										'type' => 'dropdown',
										'heading' => esc_html__('Order by', 'rigid-plugin'),
										'param_name' => 'orderby',
										'value' => $order_by_values,
										'description' => sprintf(esc_html__('Select how to sort retrieved products. More at %s.', 'rigid-plugin'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>')
								),
								array(
										'type' => 'dropdown',
										'heading' => esc_html__('Order way', 'rigid-plugin'),
										'param_name' => 'order',
										'value' => $order_way_values,
										'description' => sprintf(esc_html__('Designates the ascending or descending order. More at %s.', 'rigid-plugin'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>')
								),
						)
				));

				// Map rigid_woo_sale_carousel shortcode
				vc_map(array(
						'name' => esc_html__('Sale Products Carousel', 'rigid-plugin'),
						'base' => 'rigid_woo_sale_carousel',
						'icon' => $althem_icon,
						'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
						'description' => esc_html__('List all products on sale in carousel', 'rigid-plugin'),
						'params' => array(
								array(
										'type' => 'textfield',
										'heading' => esc_html__('Per page', 'rigid-plugin'),
										'value' => 12,
										'param_name' => 'per_page',
										'description' => esc_html__('How much items per page to show', 'rigid-plugin'),
								),
								array(
										'type' => 'dropdown',
										'heading' => esc_html__('Columns', 'rigid-plugin'),
										'value' => $columns_values,
										'param_name' => 'columns',
										'description' => esc_html__('How much columns grid', 'rigid-plugin'),
								),
								array(
										'type' => 'dropdown',
										'heading' => esc_html__('Order by', 'rigid-plugin'),
										'param_name' => 'orderby',
										'value' => $order_by_values,
										'description' => sprintf(esc_html__('Select how to sort retrieved products. More at %s.', 'rigid-plugin'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>')
								),
								array(
										'type' => 'dropdown',
										'heading' => esc_html__('Order way', 'rigid-plugin'),
										'param_name' => 'order',
										'value' => $order_way_values,
										'description' => sprintf(esc_html__('Designates the ascending or descending order. More at %s.', 'rigid-plugin'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>')
								),
						)
				));

				// Map rigid_woo_best_selling_carousel shortcode
				vc_map(array(
						'name' => esc_html__('Best Selling Products Carousel', 'rigid-plugin'),
						'base' => 'rigid_woo_best_selling_carousel',
						'icon' => $althem_icon,
						'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
						'description' => esc_html__('List best selling products in carousel', 'rigid-plugin'),
						'params' => array(
								array(
										'type' => 'textfield',
										'heading' => esc_html__('Per page', 'rigid-plugin'),
										'value' => 12,
										'param_name' => 'per_page',
										'description' => esc_html__('How much items per page to show', 'rigid-plugin'),
								),
								array(
										'type' => 'dropdown',
										'heading' => esc_html__('Columns', 'rigid-plugin'),
										'value' => $columns_values,
										'param_name' => 'columns',
										'description' => esc_html__('How much columns grid', 'rigid-plugin'),
								),
						)
				));

				// Map rigid_woo_product_categories_carousel shortcode
				vc_map(array(
						'name' => esc_html__('Product Categories Carousel', 'rigid-plugin'),
						'base' => 'rigid_woo_product_categories_carousel',
						'icon' => $althem_icon,
						'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
						'description' => esc_html__('Display categories in carousel', 'rigid-plugin'),
						'params' => array(
								array(
										'type' => 'textfield',
										'heading' => esc_html__('Number', 'rigid-plugin'),
										'param_name' => 'number',
										'description' => esc_html__('The `number` field is used to display the number of products.', 'rigid-plugin'),
								),
								array(
										'type' => 'dropdown',
										'heading' => esc_html__('Order by', 'rigid-plugin'),
										'param_name' => 'orderby',
										'value' => $order_by_values,
										'description' => sprintf(esc_html__('Select how to sort retrieved products. More at %s.', 'rigid-plugin'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>')
								),
								array(
										'type' => 'dropdown',
										'heading' => esc_html__('Order way', 'rigid-plugin'),
										'param_name' => 'order',
										'value' => $order_way_values,
										'description' => sprintf(esc_html__('Designates the ascending or descending order. More at %s.', 'rigid-plugin'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>')
								),
								array(
										'type' => 'textfield',
										'heading' => esc_html__('Columns', 'rigid-plugin'),
										'value' => 4,
										'param_name' => 'columns',
										'description' => esc_html__('How much columns grid', 'rigid-plugin'),
								),
								array(
										'type' => 'textfield',
										'heading' => esc_html__('Number', 'rigid-plugin'),
										'param_name' => 'hide_empty',
										'description' => esc_html__('Hide empty', 'rigid-plugin'),
								),
								array(
										'type' => 'autocomplete',
										'heading' => esc_html__('Categories', 'rigid-plugin'),
										'param_name' => 'ids',
										'settings' => array(
												'multiple' => true,
												'sortable' => true,
										),
										'description' => esc_html__('List of product categories', 'rigid-plugin'),
								),
						)
				));
				//Filters For autocomplete param:
				//For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback

				add_filter('vc_autocomplete_rigid_woo_product_categories_carousel_ids_callback', 'rigid_productCategoryCategoryAutocompleteSuggester', 10, 1); // Get suggestion(find). Must return an array
				add_filter('vc_autocomplete_rigid_woo_product_categories_carousel_ids_render', 'rigid_productCategoryCategoryRenderByIdExact', 10, 1); // Render exact category by id. Must return an array (label,value)
				// Map rigid_woo_products_slider shortcode
				vc_map(array(
						'name' => esc_html__('Products Slider', 'rigid-plugin'),
						'base' => 'rigid_woo_products_slider',
						'icon' => $althem_icon,
						'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
						'description' => esc_html__('Display Products in slider', 'rigid-plugin'),
						'params' => array(
								array(
										'type' => 'dropdown',
										'heading' => esc_html__('Order by', 'rigid-plugin'),
										'param_name' => 'orderby',
										'value' => $order_by_values,
										'std' => 'title',
										'description' => sprintf(__('Select how to sort retrieved products. More at %s. Default by Title', 'rigid-plugin'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>')
								),
								array(
										'type' => 'dropdown',
										'heading' => esc_html__('Order way', 'rigid-plugin'),
										'param_name' => 'order',
										'value' => $order_way_values,
										'description' => sprintf(__('Designates the ascending or descending order. More at %s. Default by ASC', 'rigid-plugin'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>')
								),
								array(
										'type' => 'autocomplete',
										'heading' => esc_html__('Products', 'rigid-plugin'),
										'param_name' => 'ids',
										'admin_label' => true,
										'settings' => array(
												'multiple' => true,
												'sortable' => true,
												'unique_values' => true,
										// In UI show results except selected. NB! You should manually check values in backend
										),
										'description' => esc_html__('Enter List of Products', 'rigid-plugin'),
								),
								array(
										'type' => 'hidden',
										'param_name' => 'skus',
								),
						)
				));

				// Add Elegant Icons Font
				$attributes_icon = array(
						'type' => 'iconpicker',
						'heading' => esc_html__('Icon', 'rigid-plugin'),
						'param_name' => 'icon_etline',
						'value' => 'icon-mobile', // default value to backend editor admin_label
						'settings' => array(
								'emptyIcon' => false,
								'type' => 'etline',
								'iconsPerPage' => 100,
						// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
						),
						'dependency' => array(
								'element' => 'type',
								'value' => 'etline',
						),
						'description' => esc_html__('Select icon from library.', 'rigid-plugin'),
				);

				$attributes_rest = array(
						'type' => 'iconpicker',
						'heading' => esc_html__('Icon', 'rigid-plugin'),
						'param_name' => 'icon_etline',
						'value' => 'icon-mobile', // default value to backend editor admin_label
						'settings' => array(
								'emptyIcon' => false,
								'type' => 'etline',
								'iconsPerPage' => 100,
						// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
						),
						'dependency' => array(
								'element' => 'icon_type',
								'value' => 'etline',
						),
						'description' => esc_html__('Select icon from library.', 'rigid-plugin'),
				);

				$attributes_i = array(
						'type' => 'iconpicker',
						'heading' => esc_html__('Icon', 'rigid-plugin'),
						'param_name' => 'i_icon_etline',
						'value' => 'icon-mobile', // default value to backend editor admin_label
						'settings' => array(
								'emptyIcon' => false,
								'type' => 'etline',
								'iconsPerPage' => 100,
						// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
						),
						'dependency' => array(
								'element' => 'i_type',
								'value' => 'etline',
						),
						'description' => esc_html__('Select icon from library.', 'rigid-plugin'),
				);

				vc_add_param('vc_icon', $attributes_icon);
				vc_add_param('vc_message', $attributes_rest);
				vc_add_param('rigid_counter', $attributes_i);

				//Filters For autocomplete param:
				//For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback
				$WCvendor = new Vc_Vendor_Woocommerce();

				add_filter('vc_autocomplete_rigid_woo_products_slider_ids_callback', array(&$WCvendor, 'productIdAutocompleteSuggester'), 10, 1); // Get suggestion(find). Must return an array
				add_filter('vc_autocomplete_rigid_woo_products_slider_ids_render', array(&$WCvendor, 'productIdAutocompleteRender'), 10, 1); // Render exact product. Must return an array (label,value)
				//For param: ID default value filter
				add_filter('vc_form_fields_render_field_rigid_woo_products_slider_ids_param_value', array(&$WCvendor, 'productsIdsDefaultValue'), 10, 4); // Defines default value for param if not provided. Takes from other param value.
			}

			// If WCMp is active
			if (is_plugin_active('dc-woocommerce-multi-vendor/dc_product_vendor.php') || class_exists('WCMp')) {

                // Map rigid_woo_top_rated_carousel shortcode
				vc_map(array(
						'name' => esc_html__('WCMp Vendors List', 'rigid-plugin'),
						'base' => 'rigid_wcmp_vendorslist',
						'icon' => $althem_icon,
						'category' => esc_html__('Rigid Shortcodes', 'rigid-plugin'),
						'description' => esc_html__('Displays registered vendors', 'rigid-plugin'),
						'params' => array(
								array(
										'type' => 'dropdown',
										'heading' => esc_html__('Order By', 'rigid-plugin'),
										'value' => array(
                                                esc_html__('Date Registered', 'rigid-plugin') => 'registered',
                                                esc_html__('Vendor Name', 'rigid-plugin') => 'name',
                                                esc_html__('Product Category', 'rigid-plugin') => 'category'
                                        ),
										'param_name' => 'orderby',
										'description' => esc_html__('Sort vendors by chosen order parameter.', 'rigid-plugin'),
										'admin_label' => true
								),
								array(
										'type' => 'dropdown',
										'heading' => esc_html__('Order Way', 'rigid-plugin'),
										'param_name' => 'order',
										'value' => array(
						                        esc_html__('Ascending', 'rigid-plugin') => 'ASC',
										        esc_html__('Descending', 'rigid-plugin') => 'DESC',
										),
										'description' => esc_html__('Designates the ascending or descending order.', 'rigid-plugin'),
										'admin_label' => true
								),
								array(
                                        'type' => 'textfield',
                                        'heading' => esc_html__('Number of Vendors Listed', 'rigid-plugin'),
                                        'param_name' => 'limit',
                                        'value' => '',
                                        'description' => esc_html__('Enter the number of vendors to be displayed. NOTE: If leaved empty, all vendors will be listed.', 'rigid-plugin'),
                                        'admin_label' => true
							    ),
							    array(
                                        'type' => 'checkbox',
                                        'heading' => esc_html__('Hide Sorting Options', 'rigid-plugin'),
                                        'param_name' => 'hide_order_by',
                                        'value' => array(esc_html__('Hide "Sort" dropdown', 'rigid-plugin') => 'yes'),
                                        'admin_label' => true
                                ),
						)
				));
			}

		}

	}

	add_action('vc_after_init', 'rigid_add_etline_type'); /* Note: here we are using vc_after_init because WPBMap::GetParam and mutateParame are available only when default content elements are "mapped" into the system */
	if (!function_exists('rigid_add_etline_type')) {

		/**
		 * Add Elegant Icons Font option to the
		 * shortcode type parameters
		 */
		function rigid_add_etline_type() {

			//Get current values stored in the type param in "Call to Action" element
			$param = WPBMap::getParam('vc_icon', 'type');
			//Append new value to the 'value' array
			$param['value'][esc_html__('Elegant Icons Font', 'rigid-plugin')] = 'etline';
			//Finally "mutate" param with new values
			vc_update_shortcode_param('vc_icon', $param);

			$param = WPBMap::getParam('vc_message', 'icon_type');
			$param['value'][esc_html__('Elegant Icons Font', 'rigid-plugin')] = 'etline';
			vc_update_shortcode_param('vc_message', $param);

			$param = WPBMap::getParam('rigid_counter', 'i_type');
			$param['value'][esc_html__('Elegant Icons Font', 'rigid-plugin')] = 'etline';
			vc_update_shortcode_param('rigid_counter', $param);
		}

	}

// Add aditional parameters on VC shortcodes
	add_action('vc_before_init', 'rigid_add_atts_vc_shortcodes');
	if (!function_exists('rigid_add_atts_vc_shortcodes')) {

		function rigid_add_atts_vc_shortcodes() {

			$video_opacity_values = array('1' => '1');
			for ($j = 9; $j >= 1; $j--) {
				$video_opacity_values['0.' . $j] = '0.' . $j;
			}

			$video_background_attributes = array(
					array(
							'type' => 'textfield',
							'heading' => esc_html__('YouTube video URL', 'rigid-plugin'),
							'param_name' => 'video_bckgr_url',
							'value' => '',
							'description' => esc_html__('Paste the YouTube URL.', 'rigid-plugin'),
							'group' => esc_html__('Rigid Video Background', 'rigid-plugin')
					),
					array(
							'type' => 'dropdown',
							'heading' => esc_html__('Video Opacity', 'rigid-plugin'),
							'param_name' => 'video_opacity',
							'value' => $video_opacity_values,
							'description' => esc_html__('Set opacity fot the video.', 'rigid-plugin'),
							'group' => esc_html__('Rigid Video Background', 'rigid-plugin')
					),
					array(
							'type' => 'checkbox',
							'heading' => esc_html__('Raster', 'rigid-plugin'),
							'param_name' => 'video_raster',
							'value' => array(esc_html__('Enable Raster effect', 'rigid-plugin') => 'yes'),
							'group' => esc_html__('Rigid Video Background', 'rigid-plugin')
					),
					array(
							'type' => 'textfield',
							'heading' => esc_html__('Start time', 'rigid-plugin'),
							'param_name' => 'video_bckgr_start',
							'value' => '',
							'description' => esc_html__('Set the seconds the video should start at.', 'rigid-plugin'),
							'group' => esc_html__('Rigid Video Background', 'rigid-plugin')
					),
					array(
							'type' => 'textfield',
							'heading' => esc_html__('End time', 'rigid-plugin'),
							'param_name' => 'video_bckgr_end',
							'value' => '',
							'description' => esc_html__('Set the seconds the video should stop at.', 'rigid-plugin'),
							'group' => esc_html__('Rigid Video Background', 'rigid-plugin')
					),
			);

			// Aditional attributes for vc_row shortcode
			$attributes = array_merge($video_background_attributes, array(
					array(
							'type' => 'dropdown',
							'heading' => esc_html__('General row alignment', 'rigid-plugin'),
							'param_name' => 'general_row_align',
							'value' => array(
									esc_html__('Left', 'rigid-plugin') => '',
									esc_html__('Right', 'rigid-plugin') => 'rigid-align-right',
									esc_html__('Center', 'rigid-plugin') => 'rigid-align-center'
							),
							'group' => esc_html__('Design Options', 'rigid-plugin')
					),
					array(
							'type' => 'checkbox',
							'heading' => esc_html__('Allow content overflow', 'rigid-plugin'),
							'param_name' => 'allow_overflow',
							'value' => array(esc_html__('Allow content overflow', 'rigid-plugin') => 'yes'),
							'group' => esc_html__('Design Options', 'rigid-plugin')
					),
					array(
							'type' => 'checkbox',
							'heading' => esc_html__('Fixed Background', 'rigid-plugin'),
							'param_name' => 'fixed_background',
							'value' => array(esc_html__('Fixed Background', 'rigid-plugin') => 'yes'),
							'group' => esc_html__('Design Options', 'rigid-plugin')
					),
			));
			// Add params to Row shortcode
			vc_add_params('vc_row', $attributes);
			// Add params to Inner Row shortcode
			vc_add_params('vc_row_inner', $video_background_attributes);

			// Aditional attributes for vc_progress_bar shortcode
			$attributes = array(
					array(
							'type' => 'dropdown',
							'heading' => esc_html__('Dysplay Style', 'rigid-plugin'),
							'param_name' => 'display_style',
							'value' => array(
									esc_html__('Classic Style', 'rigid-plugin') => '',
									esc_html__('Rigid Style', 'rigid-plugin') => 'rigid-progress-bar'
							),
							'weight' => 1,
							'description' => esc_html__('Choose between the standart VC style and Rigid style.', 'rigid-plugin')
					)
			);
			vc_add_params('vc_progress_bar', $attributes);
		}

	}

// Autocomplete suggestor for rigid_woo_product_categories_carousel
	if (!function_exists('rigid_productCategoryCategoryAutocompleteSuggester')) {

		function rigid_productCategoryCategoryAutocompleteSuggester($query, $slug = false) {
			global $wpdb;

			$cat_id = (int) $query;
			$query = trim($query);
			$post_meta_infos = $wpdb->get_results(
							$wpdb->prepare("SELECT a.term_id AS id, b.name as name, b.slug AS slug
						FROM {$wpdb->term_taxonomy} AS a
						INNER JOIN {$wpdb->terms} AS b ON b.term_id = a.term_id
						WHERE a.taxonomy = 'product_cat' AND (a.term_id = '%d' OR b.slug LIKE '%%%s%%' OR b.name LIKE '%%%s%%' )", $cat_id > 0 ? $cat_id : - 1, stripslashes($query), stripslashes($query)), ARRAY_A);

			$result = array();
			if (is_array($post_meta_infos) && !empty($post_meta_infos)) {
				foreach ($post_meta_infos as $value) {
					$data = array();
					$data['value'] = $slug ? $value['slug'] : $value['id'];
					$data['label'] = esc_html__('Id', 'rigid-plugin') . ': ' .
									$value['id'] .
									( ( strlen($value['name']) > 0 ) ? ' - ' . esc_html__('Name', 'rigid-plugin') . ': ' .
													$value['name'] : '' ) .
									( ( strlen($value['slug']) > 0 ) ? ' - ' . esc_html__('Slug', 'rigid-plugin') . ': ' .
													$value['slug'] : '' );
					$result[] = $data;
				}
			}

			return $result;
		}

	}

// Render by ID for rigid_woo_product_categories_carousel
	if (!function_exists('rigid_productCategoryCategoryRenderByIdExact')) {

		function rigid_productCategoryCategoryRenderByIdExact($query) {
			global $wpdb;
			$query = $query['value'];
			$cat_id = (int) $query;
			$term = get_term($cat_id, 'product_cat');

			$term_slug = $term->slug;
			$term_title = $term->name;
			$term_id = $term->term_id;

			$term_slug_display = '';
			if (!empty($term_sku)) {
				$term_slug_display = ' - ' . esc_html__('Sku', 'rigid-plugin') . ': ' . $term_slug;
			}

			$term_title_display = '';
			if (!empty($product_title)) {
				$term_title_display = ' - ' . esc_html__('Title', 'rigid-plugin') . ': ' . $term_title;
			}

			$term_id_display = esc_html__('Id', 'rigid-plugin') . ': ' . $term_id;

			$data = array();
			$data['value'] = $term_id;
			$data['label'] = $term_id_display . $term_title_display . $term_slug_display;

			return !empty($data) ? $data : false;
		}

	}

	if (!function_exists('rigid_icon_element_fonts_enqueue')) {

		/**
		 * Enqueue icon element font
		 * @param $font
		 */
		function rigid_icon_element_fonts_enqueue($font) {
			switch ($font) {
				case 'fontawesome':
					wp_enqueue_style('font-awesome');
					break;
				case 'openiconic':
					wp_enqueue_style('vc_openiconic');
					break;
				case 'typicons':
					wp_enqueue_style('vc_typicons');
					break;
				case 'entypo':
					wp_enqueue_style('vc_entypo');
					break;
				case 'linecons':
					wp_enqueue_style('vc_linecons');
					break;
				default:
					do_action('vc_enqueue_font_icon_element', $font); // hook to custom do enqueue style
			}
		}

	}
