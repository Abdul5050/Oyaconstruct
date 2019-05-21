<?php
// Partial to use when displayng rigid_portfolio_category category, archive and page template
global $wp;
// Isotope
wp_enqueue_script('isotope');

$enable_portfolio_infinite = 'no';
if ( rigid_get_option( 'enable_portfolio_infinite' ) ) {
	$enable_portfolio_infinite = 'yes';
}
$use_load_more_on_portfolio = 'no';
if ( rigid_get_option( 'use_load_more_on_portfolio' ) ) {
	$use_load_more_on_portfolio = 'yes';
}
wp_localize_script('rigid-front', 'rigid_portfolio_js_params', array(
	'enable_portfolio_infinite' => $enable_portfolio_infinite,
	'use_load_more_on_portfolio' => $use_load_more_on_portfolio
));

if (!isset($rigid_portfolio_style_class)) {
	$rigid_portfolio_style_class = 'grid-unit';
}

if (!isset($rigid_columns_class)) {
	$rigid_columns_class = 'portfolio-col-3';
}
// If the style is different than grid, we dont need columns
if ($rigid_portfolio_style_class != 'grid-unit') {
	$rigid_columns_class = '';
}
// If style is masonary no crop on images
if ($rigid_portfolio_style_class == 'masonry-unit') {
	$rigid_thumb_size = 'rigid-portfolio-category-thumb-real';
} else {
	$rigid_thumb_size = 'rigid-portfolio-category-thumb';
}

// get the gaps style
if (rigid_get_option('portfoio_cat_display')) {
	$rigid_gaps_class = 'rigid-10px-gap';
} else {
	$rigid_gaps_class = '';
}

// none-overlay style
if (rigid_get_option('none_overlay') && $rigid_portfolio_style_class !== 'list-unit') {
	$rigid_none_overlay_class = 'rigid-none-overlay';
} else {
	$rigid_none_overlay_class = '';
}

$rigid_subtitle = '';
$rigid_title_background_image = '';
$rigid_title_alignment = 'left_title';

if (is_page()) {
// Get the rigid custom options
	$rigid_page_options = get_post_custom(get_the_ID());

	$rigid_show_title_page = 'yes';
	$rigid_show_breadcrumb = 'yes';
	$rigid_featured_slider = 'none';
	$rigid_rev_slider_before_header = 0;

	if (isset($rigid_page_options['rigid_show_title_page']) && trim($rigid_page_options['rigid_show_title_page'][0]) != '') {
		$rigid_show_title_page = $rigid_page_options['rigid_show_title_page'][0];
	}

	if (isset($rigid_page_options['rigid_show_breadcrumb']) && trim($rigid_page_options['rigid_show_breadcrumb'][0]) != '') {
		$rigid_show_breadcrumb = $rigid_page_options['rigid_show_breadcrumb'][0];
	}

	if (isset($rigid_page_options['rigid_rev_slider']) && trim($rigid_page_options['rigid_rev_slider'][0]) != '') {
		$rigid_featured_slider = $rigid_page_options['rigid_rev_slider'][0];
	}

	if (isset($rigid_page_options['rigid_rev_slider_before_header']) && trim($rigid_page_options['rigid_rev_slider_before_header'][0]) != '') {
		$rigid_rev_slider_before_header = $rigid_page_options['rigid_rev_slider_before_header'][0];
	}

	$rigid_featured_flex_slider_imgs = rigid_get_more_featured_images(get_the_ID());

	if (isset($rigid_page_options['rigid_page_subtitle']) && trim($rigid_page_options['rigid_page_subtitle'][0]) != '') {
		$rigid_subtitle = $rigid_page_options['rigid_page_subtitle'][0];
	}

	if (isset($rigid_page_options['rigid_title_background_imgid']) && trim($rigid_page_options['rigid_title_background_imgid'][0]) != '') {
		$rigid_img = wp_get_attachment_image_src($rigid_page_options['rigid_title_background_imgid'][0], 'full');
		$rigid_title_background_image = $rigid_img[0];
	}

	if (isset($rigid_page_options['rigid_title_alignment']) && trim($rigid_page_options['rigid_title_alignment'][0]) != '') {
		$rigid_title_alignment = $rigid_page_options['rigid_title_alignment'][0];
	}
}

$rigid_sidebar_choice = apply_filters('rigid_has_sidebar', '');

if ($rigid_sidebar_choice != 'none') {
	$rigid_has_sidebar = is_active_sidebar($rigid_sidebar_choice);
} else {
	$rigid_has_sidebar = false;
}
$rigid_offcanvas_sidebar_choice = apply_filters('rigid_has_offcanvas_sidebar', '');

if ($rigid_offcanvas_sidebar_choice != 'none') {
	$rigid_has_offcanvas_sidebar = is_active_sidebar($rigid_offcanvas_sidebar_choice);
} else {
	$rigid_has_offcanvas_sidebar = false;
}

$rigid_sidebar_classes = array();
if ($rigid_has_sidebar) {
	$rigid_sidebar_classes[] = 'has-sidebar';
}
if ($rigid_has_offcanvas_sidebar) {
	$rigid_sidebar_classes[] = 'has-off-canvas-sidebar';
}

// Sidebar position
$rigid_sidebar_classes[] =  apply_filters('rigid_left_sidebar_position_class', '');
?>
<?php if ($rigid_has_offcanvas_sidebar): ?>
	<?php get_sidebar('offcanvas'); ?>
<?php endif; ?>
<div id="content" <?php if (!empty($rigid_sidebar_classes)) echo 'class="' . esc_attr(implode(' ', $rigid_sidebar_classes)) . '"'; ?> >

	<div id="rigid_page_title" class="rigid_title_holder <?php echo esc_attr($rigid_title_alignment) ?> <?php if ($rigid_title_background_image): ?>title_has_image<?php endif; ?>">
		<?php if ($rigid_title_background_image): ?><div class="rigid-zoomable-background" style="background-image: url('<?php echo esc_url($rigid_title_background_image) ?>');"></div><?php endif; ?>
		<div class="inner fixed">
			<!-- BREADCRUMB -->
			<?php if ((is_page() && $rigid_show_breadcrumb == 'yes') || !is_page()): ?>
				<?php rigid_breadcrumb() ?>
			<?php endif; ?>
			<!-- END OF BREADCRUMB -->
			<?php if (is_tax()): ?>
				<h1 class="heading-title"><?php single_term_title() ?></h1>
			<?php elseif (is_page() && $rigid_show_title_page == 'yes'): ?>
				<h1 class="heading-title"><?php the_title(); ?></h1>
				<?php if ($rigid_subtitle): ?>
					<h6><?php echo esc_html($rigid_subtitle) ?></h6>
				<?php endif; ?>
			<?php elseif (!is_page()): ?>
				<h1 class="heading-title"><?php esc_html_e('Portfolio', 'rigid') ?></h1>
			<?php endif; ?>
		</div>
	</div>
	<div class="inner">
		<!-- CONTENT WRAPPER -->
		<div id="main" class="fixed box box-common">
			<div class="content_holder">
				<?php if (is_page() && !empty($rigid_featured_flex_slider_imgs)): ?>
					<div class="rigid_flexslider  post_slide">
						<ul class="slides">
							<?php if (has_post_thumbnail()): ?>
								<li>
									<?php echo wp_get_attachment_image(get_post_thumbnail_id(), 'rigid-blog-category-thumb'); ?>
								</li>
							<?php endif; ?>

							<?php foreach ($rigid_featured_flex_slider_imgs as $rigid_img_att_id): ?>
								<li>
									<?php echo wp_get_attachment_image($rigid_img_att_id, 'rigid-blog-category-thumb'); ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php elseif (is_page() && $rigid_featured_slider != 'none' && function_exists('putRevSlider') && !$rigid_rev_slider_before_header): ?>
					<!-- FEATURED REVOLUTION SLIDER -->
					<div class="slideshow">
						<?php putRevSlider($rigid_featured_slider) ?>
					</div>
					<!-- END OF FEATURED REVOLUTION SLIDER -->
				<?php elseif (is_page() && has_post_thumbnail()): ?>
					<?php the_post_thumbnail('rigid-blog-category-thumb'); ?>
				<?php endif; ?>

				<?php if (is_tax()): ?>
					<?php if (term_description()): ?>
						<div class="portfolio-cat-desc">
							<?php echo wp_kses_post(term_description()); ?>
						</div>
					<?php endif; ?>
					<?php $rigid_curr_category = get_queried_object(); ?>
					<?php $rigid_portgolio_categories = array($rigid_curr_category) ?>
					<?php $rigid_portfolio_categories = array_merge($rigid_portgolio_categories, get_term_children($rigid_curr_category->term_id, 'rigid_portfolio_category')); ?>
				<?php else: ?>
					<?php $rigid_portfolio_categories = get_terms('rigid_portfolio_category'); ?>
				<?php endif; ?>

				<?php if (count($rigid_portfolio_categories) > 0): ?>
					<div class="rigid-portfolio-categories">
						<ul>
							<?php if (!is_tax()): ?>
								<li><a class="is-checked" data-filter="*" href="#"><?php esc_html_e('show all', 'rigid') ?></a></li>
							<?php endif; ?>
							<?php foreach ($rigid_portfolio_categories as $rigid_category): ?>
								<?php if (!is_object($rigid_category)) $rigid_category = get_term_by('id', $rigid_category, 'rigid_portfolio_category') ?>
								<li><a <?php if (is_tax() && get_queried_object()->term_id == $rigid_category->term_id) echo 'class="is-checked"' ?> data-filter=".<?php echo esc_attr($rigid_category->slug) ?>" href="#"><?php echo esc_html($rigid_category->name) ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>

				<?php add_filter('excerpt_length', 'rigid_portfolio_custom_excerpt_length', 999); ?>
				<?php add_filter('excerpt_more', 'rigid_portfolio_new_excerpt_more'); ?>
				<?php $rigid_counter = 0; ?>
				<?php
				global $query_string;

				if (is_page()) {
					//get all portfolios
					$rigid_portfolios = new WP_Query('post_type=rigid-portfolio&post_status=publish&posts_per_page=' . get_option("posts_per_page") . '&paged=' . get_query_var('paged'));
				} else {
					$rigid_portfolios = new WP_Query($query_string . '&post_type=rigid-portfolio');
				}
				?>
				<div class="portfolios">
					<?php while ($rigid_portfolios->have_posts()): ?>
						<?php $rigid_portfolios->the_post(); ?>
						<?php $rigid_portfolio = get_post(); ?>
						<?php $rigid_counter++; ?>
						<?php
						// client name
						$rigid_similar_client_name = get_post_meta(get_the_ID(), 'rigid_client', true);

						$rigid_terms_arr = array();
						$rigid_current_terms = get_the_terms($rigid_portfolio->ID, 'rigid_portfolio_category');
						$rigid_current_terms_as_simple_array = array();

						if ($rigid_current_terms) {
							foreach ($rigid_current_terms as $rigid_term) {
								$rigid_current_terms_as_simple_array[] = $rigid_term->name;

								$rigid_ancestors = rigid_get_rigid_portfolio_category_parents($rigid_term->term_id);
								foreach ($rigid_ancestors as $rigid_term_ancestor) {
									$rigid_terms_arr[] = $rigid_term_ancestor->slug;
								}
							}
							$rigid_terms_arr = array_unique($rigid_terms_arr);
						}

						$rigid_portfolio_featured_imgs = rigid_get_more_featured_images($rigid_portfolio->ID);

						$rigid_featured_image_attr = wp_get_attachment_image_src(get_post_thumbnail_id($rigid_portfolio->ID), 'full');
						$rigid_featured_image_src = '';
						if ($rigid_featured_image_attr) {
							$rigid_featured_image_src = $rigid_featured_image_attr[0];
						}
						?>
						<div class="portfolio-unit <?php echo esc_attr($rigid_none_overlay_class) ?> <?php echo esc_attr(implode(' ', $rigid_terms_arr)) ?> <?php echo esc_attr($rigid_portfolio_style_class) ?> <?php echo esc_attr($rigid_columns_class) ?> <?php echo esc_attr($rigid_gaps_class) ?>">
							<div class="portfolio-unit-holder">
								<!-- LIST -->
								<?php if ($rigid_portfolio_style_class == 'list-unit'): ?>
									<div class="port-unit-image-holder">
										<a title="<?php esc_html_e('View project', 'rigid') ?>" href="<?php echo esc_url(get_the_permalink($rigid_portfolio->ID)); ?>" class="portfolio-link">
											<?php if (has_post_thumbnail($rigid_portfolio->ID)): ?>
												<?php echo get_the_post_thumbnail($rigid_portfolio->ID, $rigid_thumb_size); ?>
											<?php else: ?>
												<img src="<?php echo esc_attr(RIGID_IMAGES_PATH . 'cat_not_found.png') ?>" />
											<?php endif; ?>
										</a>
									</div>
									<div class="portfolio-unit-info">
										<a title="<?php esc_html_e('View project', 'rigid') ?>" href="<?php echo esc_url(get_the_permalink($rigid_portfolio->ID)); ?>" class="portfolio-link">
                                            <small>
	                                            <?php if ( $rigid_similar_client_name ): ?>
                                                    <span class=”rigid-client-name”><?php echo esc_html( $rigid_similar_client_name ) ?> </span>
	                                            <?php endif; ?>
												<?php the_time( get_option( 'date_format' ) ); ?>
                                            </small>
											<h4><?php echo esc_html(get_the_title($rigid_portfolio->ID)); ?></h4>
										</a>
										<?php if ($rigid_featured_image_src && rigid_get_option('show_light_projects')): ?>
											<a class="portfolio-lightbox-link" href="<?php echo esc_url($rigid_featured_image_src) ?>"><span></span></a>
										<?php endif; ?>
										<?php $rigid_short_description = get_post_meta(get_the_ID(), 'rigid_add_description', true); ?>
										<?php if ($rigid_short_description): // If has short description - show it, else the excerpt  ?>
											<p><?php echo wp_trim_words($rigid_short_description, 40, rigid_new_excerpt_more('no_hash')); ?></p>
										<?php elseif (get_the_content()): ?>
											<p><?php the_excerpt(); ?></p>
										<?php endif; ?>
										<?php if ($rigid_current_terms): ?>
											<h6><?php echo wp_kses_post(implode(' / ', $rigid_current_terms_as_simple_array)) ?></h6>
										<?php endif; ?>
									</div>
									<!-- GRID and MASONRY -->
								<?php else: ?>
									<?php if (has_post_thumbnail($rigid_portfolio->ID)): ?>
										<?php echo get_the_post_thumbnail($rigid_portfolio->ID, $rigid_thumb_size); ?>
									<?php else: ?>
										<img src="<?php echo esc_attr(RIGID_IMAGES_PATH . 'cat_not_found.png') ?>" />
									<?php endif; ?>
									<div class="portfolio-unit-info">
										<a title="<?php esc_html_e('View project', 'rigid') ?>" href="<?php echo esc_url(get_the_permalink($rigid_portfolio->ID)); ?>" class="portfolio-link">
											<small>
												<?php if ( $rigid_similar_client_name ): ?>
                                                    <span class="rigid-client-name"><?php echo esc_html( $rigid_similar_client_name ) ?> </span>
												<?php endif; ?>
                                                <?php the_time(get_option('date_format')); ?>
                                            </small>
											<h4><?php echo esc_html(get_the_title($rigid_portfolio->ID)); ?></h4>
											<?php if ($rigid_current_terms): ?>
												<h6><?php echo wp_kses_post(implode(' / ', $rigid_current_terms_as_simple_array)) ?></h6>
											<?php endif; ?>
										</a>
										<?php if ($rigid_featured_image_src && rigid_get_option('show_light_projects')): ?>
											<a class="portfolio-lightbox-link" href="<?php echo esc_url($rigid_featured_image_src) ?>"><span></span></a>
										<?php endif; ?>
									</div>
								<?php endif; ?>

							</div>
						</div>
					<?php endwhile; ?>
				</div>
                <?php if (!$rigid_portfolios->have_posts()): ?>
					<p><?php esc_html_e('No Portfolio found. Sorry!', 'rigid'); ?></p>
				<?php endif; ?>
				<?php remove_filter('excerpt_length', 'rigid_portfolio_custom_excerpt_length') ?>
				<?php remove_filter('excerpt_more', 'rigid_portfolio_new_excerpt_more'); ?>
			</div>
			<!-- SIDEBARS -->
			<?php if ($rigid_has_sidebar): ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>
			<?php if ($rigid_has_offcanvas_sidebar): ?>
				<a class="sidebar-trigger" href="#"><?php echo esc_html__('show', 'rigid') ?></a>
			<?php endif; ?>
			<!-- END OF IDEBARS -->
			<div class="clear"></div>

			<!-- PAGINATION -->
			<div class="box box-common portfolio-nav rigid-enabled<?php if(rigid_get_option('enable_portfolio_infinite')) echo ' rigid-infinite'; ?>">

                <div class="rigid-page-load-status">
                    <p class="infinite-scroll-request"><?php esc_html_e( 'Loading', 'rigid' ); ?>...</p>
                    <p class="infinite-scroll-last"><?php esc_html_e( 'No more items available', 'rigid' ); ?></p>
                </div>

				<?php if(rigid_get_option('enable_portfolio_infinite') && rigid_get_option('use_load_more_on_portfolio')): ?>
                    <div class="rigid-load-more-container">
                        <button class="rigid-load-more button"><?php esc_html_e( 'Load More', 'rigid' ); ?></button>
                    </div>
				<?php endif; ?>

				<?php if (function_exists('rigid_pagination')) : rigid_pagination('', $rigid_portfolios); ?>
				<?php else : ?>

					<div class="navigation group">
						<div class="alignleft next-page-portfolio"><?php next_posts_link(esc_html__('Next &raquo;', 'rigid')) ?></div>
						<div class="alignright prev-page-portfolio"><?php previous_posts_link(esc_html__('&laquo; Back', 'rigid')) ?></div>
					</div>

				<?php endif; ?>
			</div>
			<!-- END OF PAGINATION -->

		</div>
		<!-- END OF CONTENT WRAPPER -->
	</div>
</div>