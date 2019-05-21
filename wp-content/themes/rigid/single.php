<?php
get_header();

// Default to single post
// Get the rigid custom options
$rigid_page_options = get_post_custom(get_the_ID());

$rigid_show_title_page = 'yes';
$rigid_show_breadcrumb = 'yes';
$rigid_featured_slider = 'none';
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
$rigid_sidebar_classes[] = apply_filters('rigid_left_sidebar_position_class', '');
?>
<?php if ($rigid_has_offcanvas_sidebar): ?>
	<?php get_sidebar('offcanvas'); ?>
<?php endif; ?>
<div id="content" <?php if (!empty($rigid_sidebar_classes)) echo 'class="' . esc_attr(implode(' ', $rigid_sidebar_classes)) . '"'; ?> >
	<?php while (have_posts()) : the_post(); ?>
		<?php if ($rigid_show_title_page == 'yes' || $rigid_show_breadcrumb == 'yes'): ?>
			<div id="rigid_page_title" class="rigid_title_holder <?php echo esc_attr($rigid_title_alignment) ?> <?php if ($rigid_title_background_image): ?>title_has_image<?php endif; ?>">
				<?php if ($rigid_title_background_image): ?><div class="rigid-zoomable-background" style="background-image: url('<?php echo esc_url($rigid_title_background_image) ?>');"></div><?php endif; ?>
				<div class="inner fixed">
					<!-- BREADCRUMB -->
					<?php if ($rigid_show_breadcrumb == 'yes'): ?>
						<?php rigid_breadcrumb() ?>
					<?php endif; ?>
					<!-- END OF BREADCRUMB -->
					<?php if ($rigid_show_title_page == 'yes'): ?>
						<h1	class="heading-title">
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
						</h1>
						<?php if ($rigid_subtitle): ?>
                            <h6><?php echo esc_html($rigid_subtitle) ?></h6>
						<?php endif; ?>
					<?php endif; ?>
                    <?php get_template_part( 'partials/blog-post-meta-bottom' ); ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="inner">
			<!-- CONTENT WRAPPER -->
			<div id="main" class="fixed box box-common">
				<div class="content_holder">
					<?php get_template_part('content', get_post_format()); ?>
					<?php
					if (comments_open() || get_comments_number()) :
						comments_template('', true);
					endif;
					?>
					<?php if (rigid_get_option('show_related_posts')): ?>
						<?php
						// Get random post from the same category as the current one
						$rigid_related_posts_args = array(
								'nopaging' => true,
								'post__not_in' => array($post->ID),
								'orderby' => 'rand',
								'post_type' => 'post',
								'post_status' => 'publish'
						);
						$rigid_get_terms_args = array(
								'orderby' => 'name',
								'order' => 'ASC',
								'fields' => 'slugs'
						);
						$rigid_categories = wp_get_post_terms($post->ID, 'category', $rigid_get_terms_args);
						if (!$rigid_categories instanceof WP_Error && !empty($rigid_categories)) {
							$rigid_related_posts_args['tax_query'] = array(array('taxonomy' => 'category', 'field' => 'slug', 'terms' => $rigid_categories));
						}

						wp_reset_postdata();

						//$rigid_related_posts = new WP_Query($rigid_related_posts_args);
						$rigid_is_latest_posts = true;
						// going off on my own here
						$rigid_temp_query = clone $wp_query;
						query_posts($rigid_related_posts_args);
						?>
						<?php if (have_posts()) : ?>
							<?php
							// owl carousel
							wp_localize_script('rigid-libs-config', 'rigid_owl_carousel', array(
									'include' => 'true'
							));
							?>
							<div class="rigid-related-blog-posts rigid_shortcode_latest_posts rigid_blog_masonry full_width">
								<h4><?php esc_html_e('Related posts', 'rigid') ?></h4>
								<div <?php if (rigid_get_option('owl_carousel')): ?> class="owl-carousel rigid-owl-carousel" <?php endif; ?>>
								<?php endif; ?>

								<?php while (have_posts()) : ?>
									<?php the_post(); ?>
							        <?php get_template_part('content', 'related-posts'); ?>
								<?php endwhile; ?>

								<?php if (have_posts()): ?>
								</div>
								<div class="clear"></div>
							</div>
						<?php endif; ?>
						<?php
						// going back to the main query
						$wp_query = clone $rigid_temp_query;
						?>
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
		<!-- END OF CONTENT WRAPPER -->
	<?php endwhile; // end of the loop.    ?>
</div>
<?php
get_footer();
