<?php
// The main template file.

get_header();

// show Blog title
$rigid_show_blog_title = rigid_get_option('show_blog_title');
// get Blog title
$rigid_blog_title = rigid_get_option('blog_title');
// get Blog subtitle
$rigid_blog_subtitle = rigid_get_option('blog_subtitle');
$rigid_title_background_image = rigid_get_option('blog_title_background_imgid');

if ($rigid_title_background_image) {
	$rigid_img = wp_get_attachment_image_src($rigid_title_background_image, 'full');
	$rigid_title_background_image = $rigid_img[0];
}

// Blog style
$rigid_general_blog_style = rigid_get_option('general_blog_style');
switch ($rigid_general_blog_style) {
	case 'rigid_blog_masonry':
		// load Isotope
		wp_enqueue_script('isotope');
		// Isotope settings
		wp_localize_script('rigid-libs-config', 'rigid_masonry_settings', array(
				'include' => 'true'
		));
		break;
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
	<?php if (rigid_is_blog() && $rigid_show_blog_title || rigid_breadcrumb()): ?>
		<div id="rigid_page_title" class="rigid_title_holder <?php echo esc_attr(rigid_get_option('blog_title_alignment')) ?> <?php if ($rigid_title_background_image): ?>title_has_image<?php endif; ?>">
			<?php if ($rigid_title_background_image): ?><div class="rigid-zoomable-background" style="background-image: url('<?php echo esc_url($rigid_title_background_image) ?>');"></div><?php endif; ?>
			<div class="inner fixed">
				<!-- BREADCRUMB -->
				<?php rigid_breadcrumb() ?>
				<!-- END OF BREADCRUMB -->
				<!-- TITLE -->
				<?php if (rigid_is_blog() && $rigid_show_blog_title): ?>
					<h1 class="heading-title"><?php echo esc_html($rigid_blog_title); ?></h1>
					<?php if ($rigid_blog_subtitle): ?>
						<h6><?php echo esc_html($rigid_blog_subtitle) ?></h6>
					<?php endif; ?>
				<?php endif; ?>
				<!-- END OF TITLE -->
			</div>
		</div>
	<?php endif; ?>
	<div class="inner">
		<!-- CONTENT WRAPPER -->
		<div id="main" class="fixed box box-common">
			<div class="content_holder<?php if($rigid_general_blog_style) echo ' '.esc_attr($rigid_general_blog_style); ?>">
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

						<!-- BLOG POST -->
						<?php get_template_part('content', get_post_format()); ?>
						<!-- END OF BLOG POST -->

						<?php
					endwhile;
				else:
					?>
					<?php get_template_part('content', 'none'); ?>
				<?php endif; ?>

			<!-- PAGINATION -->
			<div class="box box-common">
				<?php
				if (function_exists('rigid_pagination')) : rigid_pagination();
				else :
					?>

					<div class="navigation group">
						<div class="alignleft"><?php next_posts_link(esc_html__('Next &raquo;', 'rigid')) ?></div>
						<div class="alignright"><?php previous_posts_link(esc_html__('&laquo; Back', 'rigid')) ?></div>
					</div>

				<?php endif; ?>
			</div>
			<!-- END OF PAGINATION -->
            </div>

            <!-- SIDEBARS -->
			<?php if ($rigid_has_sidebar): ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>
			<?php if ($rigid_has_offcanvas_sidebar): ?>
                <a class="sidebar-trigger" href="#"><?php echo esc_html__('show', 'rigid') ?></a>
			<?php endif; ?>
            <!-- END OF SIDEBARS -->
            <div class="clear"></div>

		</div>
		<!-- END OF CONTENT WRAPPER -->
	</div>
</div>
<?php
get_footer();
