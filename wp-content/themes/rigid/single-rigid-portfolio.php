<?php
get_header();

// The rigid-portfolio CPT template file.
// Get the rigid custom options
$rigid_page_options = get_post_custom(get_the_ID());

$rigid_show_title_page = 'yes';
$rigid_show_breadcrumb = 'yes';
$rigid_featured_slider = 'none';
$rigid_rev_slider_before_header = 0;
$rigid_subtitle = '';
$rigid_show_title_background = 0;
$rigid_title_background_image = '';
$rigid_title_alignment = 'left_title';

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

$rigid_featured_flex_slider_imgs = rigid_get_more_featured_images(get_the_ID());

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
<?php while (have_posts()) : the_post(); ?>
	<?php if ($rigid_has_offcanvas_sidebar): ?>
		<?php get_sidebar('offcanvas'); ?>
	<?php endif; ?>
	<div id="content" <?php if (!empty($rigid_sidebar_classes)) echo 'class="' . esc_attr(implode(' ', $rigid_sidebar_classes)) . '"'; ?> >
		<?php if ($rigid_show_title_page == 'yes' || $rigid_show_breadcrumb == 'yes'): ?>

			<div id="rigid_page_title" class="rigid_title_holder <?php echo esc_attr($rigid_title_alignment) ?> <?php if ($rigid_title_background_image): ?>title_has_image<?php endif; ?>">
				<?php if ($rigid_title_background_image): ?><div class="rigid-zoomable-background" style="background-image: url('<?php echo esc_url($rigid_title_background_image) ?>');"></div><?php endif; ?>
				<div class="inner fixed">
					<!-- BREADCRUMB -->
					<?php if ($rigid_show_breadcrumb == 'yes'): ?>
						<?php rigid_breadcrumb() ?>
					<?php endif; ?>
					<!-- END OF BREADCRUMB -->
					<!-- TITLE -->
					<?php if ($rigid_show_title_page == 'yes'): ?>
						<h1 class="heading-title"><?php the_title(); ?></h1>
						<?php if ($rigid_subtitle): ?>
							<h6><?php echo esc_html($rigid_subtitle) ?></h6>
						<?php endif; ?>
					<?php endif; ?>
					<!-- END OF TITLE -->
				</div>
			</div>
		<?php endif; ?>
		<?php if ($rigid_featured_slider != 'none' && function_exists('putRevSlider') && !$rigid_rev_slider_before_header): ?>
			<!-- FEATURED REVOLUTION SLIDER -->
			<div class="slideshow">
				<?php putRevSlider($rigid_featured_slider) ?>
			</div>
			<!-- END OF FEATURED REVOLUTION SLIDER -->
		<?php endif; ?>
		<div class="inner">
			<!-- CONTENT WRAPPER -->
			<div id="main" class="fixed box box-common">
				<div class="content_holder">
					<?php $rigid_curr_portfolio_id = get_the_ID(); ?>
					<?php $rigid_portfolio_custom = get_post_custom(); ?>
					<?php
					$rigid_collection = isset($rigid_portfolio_custom['rigid_collection']) ? $rigid_portfolio_custom['rigid_collection'][0] : '';
					$rigid_materials = isset($rigid_portfolio_custom['rigid_materials']) ? $rigid_portfolio_custom['rigid_materials'][0] : '';
					$rigid_client = isset($rigid_portfolio_custom['rigid_client']) ? $rigid_portfolio_custom['rigid_client'][0] : '';
					$rigid_model = isset($rigid_portfolio_custom['rigid_model']) ? $rigid_portfolio_custom['rigid_model'][0] : '';
					$rigid_status = isset($rigid_portfolio_custom['rigid_status']) ? $rigid_portfolio_custom['rigid_status'][0] : '';
					$rigid_ext_link_button_title = isset($rigid_portfolio_custom['rigid_ext_link_button_title']) ? $rigid_portfolio_custom['rigid_ext_link_button_title'][0] : '';
					$rigid_ext_link_url = isset($rigid_portfolio_custom['rigid_ext_link_url']) ? $rigid_portfolio_custom['rigid_ext_link_url'][0] : '';

					// What gallery to be used
					$rigid_prtfl_gallery = isset($rigid_portfolio_custom['rigid_prtfl_gallery']) ? $rigid_portfolio_custom['rigid_prtfl_gallery'][0] : 'flex';
					// Custom content
					$rigid_use_custom_content = isset($rigid_portfolio_custom['rigid_prtfl_custom_content']) ? $rigid_portfolio_custom['rigid_prtfl_custom_content'][0] : 0;
					?>
					<?php if (!$rigid_use_custom_content): ?>
						<div class="portfolio_top<?php if ($rigid_prtfl_gallery == 'list'): ?> rigid_image_list_portfolio<?php endif; ?>" >
							<div class="two_third portfolio-main-image-holder">
								<?php if ($rigid_prtfl_gallery == 'cloud' && has_post_thumbnail()): ?>
									<!-- Cloud Zoom -->
									<?php
									$rigid_featured_image_id = get_post_thumbnail_id();

									if ($rigid_featured_image_id) {
										array_unshift($rigid_featured_flex_slider_imgs, $rigid_featured_image_id);
									}

									$rigid_image_title = esc_attr(get_the_title(get_post_thumbnail_id()));
									$rigid_image_link = wp_get_attachment_url(get_post_thumbnail_id());
									$rigid_image = get_the_post_thumbnail(null, 'rigid-portfolio-single-thumb');
									?>
									<?php echo sprintf('<a id="zoom1" href="%s" itemprop="image" class="cloud-zoom " title="%s"  rel="position: \'inside\' , showTitle: false, adjustX:-4, adjustY:-4">%s</a>', esc_url($rigid_image_link), esc_attr($rigid_image_title), $rigid_image); ?>

									<?php if (!empty($rigid_featured_flex_slider_imgs)): // If there are additional images show CloudZoom gallery  ?>
										<ul class="additional-images">
											<?php foreach ($rigid_featured_flex_slider_imgs as $rigid_img_id): ?>
												<?php
												$rigid_image_title = esc_attr(get_the_title($rigid_img_id));
												$rigid_image_link = wp_get_attachment_url($rigid_img_id);
												$rigid_small_image_link = wp_get_attachment_url($rigid_img_id, 'rigid-portfolio-single-thumb');
												$rigid_thumb_image = wp_get_attachment_image($rigid_img_id, 'rigid-widgets-thumb');
												?>
												<li>
													<?php echo sprintf('<a rel="useZoom: \'zoom1\', smallImage: \'%s\'" title="%s" class="cloud-zoom-gallery" href="%s">%s</a>', esc_url($rigid_small_image_link), esc_attr($rigid_image_title), esc_url($rigid_image_link), $rigid_thumb_image); ?>
												</li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								<?php elseif ($rigid_prtfl_gallery == 'flex' && !empty($rigid_featured_flex_slider_imgs)): ?>
									<!-- FEATURED SLIDER/IMAGE -->
									<div class="rigid_flexslider">
										<ul class="slides">
											<?php if (has_post_thumbnail()): ?>
												<li>
													<?php echo wp_get_attachment_image(get_post_thumbnail_id(), 'rigid-portfolio-single-thumb'); ?>
												</li>
											<?php endif; ?>

											<?php foreach ($rigid_featured_flex_slider_imgs as $rigid_img_att_id): ?>
												<li>
													<?php echo wp_get_attachment_image($rigid_img_att_id, 'rigid-portfolio-single-thumb'); ?>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								<?php elseif ($rigid_prtfl_gallery == 'list' && has_post_thumbnail()): ?>
									<!-- Image List -->
									<div class="rigid_image_list">
										<?php if (has_post_thumbnail()): ?>
											<?php $rigid_attach_url = wp_get_attachment_url(get_post_thumbnail_id()); ?>
											<?php $rigid_image_title = get_the_title(get_post_thumbnail_id()); ?>
											<?php $rigid_img_tag = wp_get_attachment_image(get_post_thumbnail_id(), 'rigid-portfolio-single-thumb'); ?>
											<?php echo sprintf('<a href="%s" class="rigid-magnific-gallery-item" title="%s" >%s</a>', esc_url($rigid_attach_url), esc_attr($rigid_image_title), $rigid_img_tag); ?>
										<?php endif; ?>
										<?php foreach ($rigid_featured_flex_slider_imgs as $rigid_img_att_id): ?>
											<?php $rigid_attach_url = wp_get_attachment_url($rigid_img_att_id); ?>
											<?php $rigid_image_title = get_the_title($rigid_img_att_id); ?>
											<?php $rigid_img_tag = wp_get_attachment_image($rigid_img_att_id, 'rigid-portfolio-single-thumb'); ?>
											<?php echo sprintf('<a href="%s" class="rigid-magnific-gallery-item" title="%s" >%s</a>', esc_url($rigid_attach_url), esc_attr($rigid_image_title), $rigid_img_tag); ?>
										<?php endforeach; ?>
									</div>
								<?php elseif (has_post_thumbnail()): ?>
									<?php the_post_thumbnail('rigid-portfolio-single-thumb'); ?>
								<?php endif; ?>
								<!-- END OF FEATURED SLIDER/IMAGE -->
							</div>
							<div class="one_third last project-data">
								<div class="project-data-holder">
									<?php if ($rigid_portfolio_custom['rigid_add_description'][0]): ?>
										<div class="more-details">
											<h4><?php esc_html_e('Short Description', 'rigid') ?></h4>
											<?php echo wp_kses_post($rigid_portfolio_custom['rigid_add_description'][0]) ?>
											<?php
											// Check if the portfolio has features
											$rigid_has_features = false;

											for ($i = 1; $i <= 10; $i++) {
												if ($rigid_portfolio_custom['rigid_feature_' . $i][0]) {
													$rigid_has_features = true;
												}
											}
											?>
											<?php if ($rigid_has_features): ?>
												<div class="main-features">
													<h4><?php esc_html_e('Main Features', 'rigid') ?></h4>
													<ul class="checklist">
														<?php for ($i = 1; $i <= 10; $i++): ?>
															<?php if ($rigid_portfolio_custom['rigid_feature_' . $i][0]): ?>
																<li><?php echo esc_html($rigid_portfolio_custom['rigid_feature_' . $i][0]) ?></li>
															<?php endif; ?>
														<?php endfor; ?>
													</ul>
												</div>
											<?php endif; ?>
										</div>
									<?php endif; ?>
									<?php
									if ($rigid_collection || $rigid_materials || $rigid_client ||
													$rigid_model || $rigid_status || $rigid_ext_link_button_title || $rigid_ext_link_url):
										?>
										<div class="project-details">
											<h4><?php esc_html_e('Details', 'rigid') ?></h4>
											<ul class="simple-list-underlined">
												<?php if ($rigid_collection): ?>
													<li><strong><?php esc_html_e('Collection', 'rigid') ?>:</strong> <?php echo esc_html($rigid_collection) ?></li>
												<?php endif; ?>
												<?php if ($rigid_materials): ?>
													<li><strong><?php esc_html_e('Materials', 'rigid') ?>:</strong> <?php echo esc_html($rigid_materials) ?></li>
												<?php endif; ?>
												<?php if ($rigid_client): ?>
                                                    <li><strong><?php esc_html_e('Client', 'rigid') ?>:</strong> <?php echo esc_html($rigid_client) ?></li>
												<?php endif; ?>
												<?php if ($rigid_model): ?>
													<li><strong><?php esc_html_e('Model', 'rigid') ?>:</strong> <?php echo esc_html($rigid_model) ?></li>
												<?php endif; ?>
												<?php if ($rigid_status): ?>
													<li><strong><?php esc_html_e('Status', 'rigid') ?>:</strong> <?php echo esc_html($rigid_status) ?></li>
												<?php endif; ?>
												<?php if ($rigid_ext_link_button_title && $rigid_ext_link_url): ?>
													<li><a class="button" target="_blank" href="<?php echo esc_url($rigid_ext_link_url) ?>" title="<?php echo esc_attr($rigid_ext_link_button_title) ?>"><?php echo esc_attr($rigid_ext_link_button_title) ?></a></li>
												<?php endif; ?>
											</ul>
										</div>
									<?php endif; ?>


								</div>
							</div>
							<div class="clear"></div>
						</div>
					<?php endif; ?>

					<?php if ($post->post_content != ""): ?>
						<div class="full_width rigid-project-description<?php echo ($rigid_use_custom_content) ? ' rigid-custom-content' : ''; ?>">
							<?php the_content(); ?>
						</div>
					<?php endif; ?>
					<?php
					// Get random portfolio projects from the same category as the current one
					$rigid_get_portfolio_args = array(
							'nopaging' => true,
							'post__not_in' => array($rigid_curr_portfolio_id),
							'orderby' => 'rand',
							'post_type' => 'rigid-portfolio',
							'post_status' => 'publish'
					);

					$rigid_get_terms_args = array(
							'orderby' => 'name',
							'order' => 'ASC',
							'fields' => 'slugs'
					);
					$rigid_portfolio_categories = wp_get_object_terms(get_the_ID(), 'rigid_portfolio_category', $rigid_get_terms_args);
					if (array_key_exists(0, $rigid_portfolio_categories)) {
						$rigid_get_portfolio_args['tax_query'] = array(array('taxonomy' => 'rigid_portfolio_category', 'field' => 'slug', 'terms' => $rigid_portfolio_categories));
					}

					wp_reset_postdata();

					$rigid_similar_portfolios = new WP_Query($rigid_get_portfolio_args);
					?>
					<?php add_filter('excerpt_length', 'rigid_portfolio_custom_excerpt_length', 999); ?>
					<?php add_filter('excerpt_more', 'rigid_new_excerpt_more'); ?>

					<?php if (rigid_get_option('show_related_projects')): ?>
						<?php if ($rigid_similar_portfolios->have_posts()): ?>
							<?php
							// owl carousel
							wp_localize_script('rigid-libs-config', 'rigid_owl_carousel', array(
									'include' => 'true'
							));
							?>
							<div class="similar_projects full_width">
								<h4><?php esc_html_e('Similar projects', 'rigid') ?></h4>
								<div <?php if (rigid_get_option('owl_carousel')): ?> class="owl-carousel rigid-owl-carousel" <?php endif; ?>>
								<?php endif; ?>

								<?php $rigid_counter = 0; ?>
								<?php while ($rigid_similar_portfolios->have_posts()): ?>
									<?php $rigid_similar_portfolios->the_post(); ?>
									<?php
                                    // client name
							        $rigid_similar_client_name = get_post_meta(get_the_ID(), 'rigid_client', true);

									$rigid_counter++;
									$rigid_current_terms = get_the_terms(get_the_ID(), 'rigid_portfolio_category');
									$rigid_current_terms_as_simple_array = array();

									if ($rigid_current_terms) {
										foreach ($rigid_current_terms as $rigid_term) {
											$rigid_current_terms_as_simple_array[] = $rigid_term->name;
										}
									}
									?>
									<div class="portfolio-unit grid-unit <?php //if (($rigid_counter % 3) == 0) echo 'last'  ?>">
										<div class="portfolio-unit-holder">

											<?php if (has_post_thumbnail()): ?>
												<?php the_post_thumbnail('rigid-portfolio-category-thumb'); ?>
											<?php else: ?>
												<img src="<?php echo esc_attr(RIGID_IMAGES_PATH . 'cat_not_found.png') ?>" />
											<?php endif; ?>
                                            <div class="portfolio-unit-info">
                                                <a title="<?php esc_html_e( 'View project', 'rigid' ) ?>"
                                                   href="<?php the_permalink(); ?>" class="portfolio-link">
                                                    <small><?php if ( $rigid_similar_client_name ): ?>
                                                            <span class="rigid-client-name"><?php echo esc_html( $rigid_similar_client_name ) ?> </span>
														<?php endif; ?>
														<?php the_time( get_option( 'date_format' ) ); ?></small>
                                                    <h4><?php the_title(); ?></h4>
													<?php if ( $rigid_current_terms ): ?>
                                                        <h6><?php echo wp_kses_post( implode( ' / ', $rigid_current_terms_as_simple_array ) ) ?></h6>
													<?php endif; ?>
                                                </a>
                                            </div>

										</div>
									</div>
								<?php endwhile; ?>
								<?php remove_filter('excerpt_length', 'rigid_portfolio_custom_excerpt_length') ?>
								<?php remove_filter('excerpt_more', 'rigid_new_excerpt_more'); ?>
								<?php wp_reset_postdata(); ?>

								<?php if ($rigid_similar_portfolios->have_posts()): ?>
								</div>
								<div class="clear"></div>
							</div>
						<?php endif; ?>
					<?php endif; ?>
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
				<?php if (function_exists('rigid_share_links')): ?>
					<?php rigid_share_links(get_the_title(), get_permalink()); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endwhile; ?>
<!-- END OF MAIN CONTENT -->

<?php
get_footer();
