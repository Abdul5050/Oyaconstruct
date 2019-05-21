<?php
// The Default Page template file.

get_header();
global $wp_query;

// Get the rigid custom options
$rigid_page_options = get_post_custom($wp_query->post->ID);
$rigid_current_post_type = get_post_type($wp_query->post->ID);

$rigid_show_title_page = 'yes';
$rigid_show_breadcrumb = 'yes';
$rigid_featured_slider = 'none';
$rigid_rev_slider_before_header = 0;
$rigid_subtitle = '';
$rigid_show_title_background = 0;
$rigid_title_background_image = '';
$rigid_title_alignment = 'left_title';
$rigid_featured_flex_slider_imgs = array();

if ( is_singular() && in_array($rigid_current_post_type, array('page', 'tribe_events')) ) {

	if ( isset( $rigid_page_options['rigid_show_title_page'] ) && trim( $rigid_page_options['rigid_show_title_page'][0] ) != '' ) {
		$rigid_show_title_page = $rigid_page_options['rigid_show_title_page'][0];
	}

	if ( isset( $rigid_page_options['rigid_show_breadcrumb'] ) && trim( $rigid_page_options['rigid_show_breadcrumb'][0] ) != '' ) {
		$rigid_show_breadcrumb = $rigid_page_options['rigid_show_breadcrumb'][0];
	}

	if ( isset( $rigid_page_options['rigid_rev_slider'] ) && trim( $rigid_page_options['rigid_rev_slider'][0] ) != '' ) {
		$rigid_featured_slider = $rigid_page_options['rigid_rev_slider'][0];
	}

	if ( isset( $rigid_page_options['rigid_rev_slider_before_header'] ) && trim( $rigid_page_options['rigid_rev_slider_before_header'][0] ) != '' ) {
		$rigid_rev_slider_before_header = $rigid_page_options['rigid_rev_slider_before_header'][0];
	}

	if ( isset( $rigid_page_options['rigid_page_subtitle'] ) && trim( $rigid_page_options['rigid_page_subtitle'][0] ) != '' ) {
		$rigid_subtitle = $rigid_page_options['rigid_page_subtitle'][0];
	}

	if ( isset( $rigid_page_options['rigid_title_background_imgid'] ) && trim( $rigid_page_options['rigid_title_background_imgid'][0] ) != '' ) {
		$rigid_img                    = wp_get_attachment_image_src( $rigid_page_options['rigid_title_background_imgid'][0], 'full' );
		$rigid_title_background_image = $rigid_img[0];
	}

	if ( isset( $rigid_page_options['rigid_title_alignment'] ) && trim( $rigid_page_options['rigid_title_alignment'][0] ) != '' ) {
		$rigid_title_alignment = $rigid_page_options['rigid_title_alignment'][0];
	}

	$rigid_featured_flex_slider_imgs = rigid_get_more_featured_images($wp_query->post->ID);
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

// Title and events
$rigid_events_mode_and_title = rigid_get_current_events_display_mode_and_title( $wp_query->post->ID );
$rigid_title                 = $rigid_events_mode_and_title['title'];
$rigid_events_mode           = $rigid_events_mode_and_title['display_mode'];

if ( RIGID_IS_EVENTS && in_array( $rigid_events_mode, array(
		'MAIN_CALENDAR',
		'CALENDAR_CATEGORY',
		'MAIN_EVENTS',
		'CATEGORY_EVENTS',
		'SINGLE_EVENT_DAYS'
	) )
) {
	$rigid_img                   = wp_get_attachment_image_src( rigid_get_option( 'events_title_background_imgid' ), 'full' );
	if ( $rigid_img ) {
		$rigid_title_background_image = $rigid_img[0];
	}
	$rigid_subtitle        = rigid_get_option( 'events_subtitle' );
	$rigid_title_alignment = rigid_get_option( 'events_title_alignment' );

}
// END title and events
?>
<?php if ($rigid_has_offcanvas_sidebar): ?>
	<?php get_sidebar('offcanvas'); ?>
<?php endif; ?>
<div id="content" <?php if (!empty($rigid_sidebar_classes)) echo 'class="' . esc_attr(implode(' ', $rigid_sidebar_classes)) . '"'; ?> >
	<?php if ($rigid_show_title_page == 'yes' || $rigid_show_breadcrumb == 'yes'): ?>
		<div id="rigid_page_title" class="rigid_title_holder <?php echo esc_attr($rigid_title_alignment) ?> <?php if ($rigid_title_background_image): ?>title_has_image<?php endif; ?>">
			<?php if ($rigid_title_background_image): ?>
				<div class="rigid-zoomable-background" style="background-image: url('<?php echo esc_url($rigid_title_background_image) ?>');"></div>
			<?php endif; ?>
			<div class="inner fixed">
				<!-- BREADCRUMB -->
				<?php if ($rigid_show_breadcrumb == 'yes'): ?>
					<?php rigid_breadcrumb() ?>
				<?php endif; ?>
				<!-- END OF BREADCRUMB -->
				<!-- TITLE -->
				<?php if ($rigid_show_title_page == 'yes'): ?>
					<h1 class="heading-title"><?php echo wp_filter_post_kses($rigid_title); ?></h1>
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
			<div class="inner">
				<?php putRevSlider($rigid_featured_slider) ?>
			</div>
		</div>
		<!-- END OF FEATURED REVOLUTION SLIDER -->
	<?php endif; ?>
	<div class="inner">
		<!-- CONTENT WRAPPER -->
		<div id="main" class="fixed box box-common">
			<div class="content_holder">
				<?php if ( is_singular() ): ?>
					<?php if ( ! empty( $rigid_featured_flex_slider_imgs ) ): ?>
						<div class="rigid_flexslider post_slide">
							<ul class="slides">
								<?php if ( has_post_thumbnail( $wp_query->post->ID ) ): ?>
									<li>
										<?php echo wp_get_attachment_image( get_post_thumbnail_id( $wp_query->post->ID ), 'rigid-blog-category-thumb' ); ?>
									</li>
								<?php endif; ?>

								<?php foreach ( $rigid_featured_flex_slider_imgs as $rigid_img_att_id ): ?>
									<li>
										<?php echo wp_get_attachment_image( $rigid_img_att_id, 'rigid-blog-category-thumb' ); ?>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php elseif ( has_post_thumbnail( $wp_query->post->ID ) ): ?>
						<?php echo get_the_post_thumbnail( $wp_query->post->ID, 'rigid-blog-category-thumb' ); ?>
					<?php endif; ?>
				<?php endif; ?>

				<?php while (have_posts()) : the_post(); ?>
					<?php get_template_part('content', 'page'); ?>
					<?php comments_template('', true); ?>
				<?php endwhile; // end of the loop.  ?>
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
<!-- END OF MAIN CONTENT -->

<?php
get_footer();
