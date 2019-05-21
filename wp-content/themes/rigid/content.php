<?php
//  The default template for displaying content. Used for both single/archive/search/shortcode.

$rigid_custom_options = get_post_custom(get_the_ID());

$rigid_featured_slider = 'none';

if (isset($rigid_custom_options['rigid_rev_slider']) && trim($rigid_custom_options['rigid_rev_slider'][0]) != '' && function_exists('putRevSlider')) {
	$rigid_featured_slider = $rigid_custom_options['rigid_rev_slider'][0];
}
$rigid_rev_slider_before_header = 0;
if (isset($rigid_custom_options['rigid_rev_slider_before_header']) && trim($rigid_custom_options['rigid_rev_slider_before_header'][0]) != '') {
	$rigid_rev_slider_before_header = $rigid_custom_options['rigid_rev_slider_before_header'][0];
}

$rigid_featured_flex_slider_imgs = rigid_get_more_featured_images(get_the_ID());

// Blog style
$rigid_general_blog_style = rigid_get_option('general_blog_style');

// Featured image size
$rigid_featured_image_size = 'rigid-blog-category-thumb';

// If is latest posts
if (isset($rigid_is_latest_posts) && $rigid_is_latest_posts) { // If is latest post shortcode
    $rigid_featured_image_size = 'rigid-640x640';
}

$rigid_post_classes = array('blog-post');
if (!has_post_thumbnail()) {
	array_push($rigid_post_classes, 'rigid-post-no-image');
}

// Show or not the featured image in single post view
if(is_singular(array('post'))) {
	$rigid_show_feat_image_in_post = 'yes';
	if (isset($rigid_custom_options['rigid_show_feat_image_in_post']) && trim($rigid_custom_options['rigid_show_feat_image_in_post'][0]) != '') {
		$rigid_show_feat_image_in_post = $rigid_custom_options['rigid_show_feat_image_in_post'][0];
	}
}
?>

<div id="post-<?php the_ID(); ?>" <?php post_class($rigid_post_classes); ?>>
	<!-- Featured content for post list -->
	<!--	Hide image if is latest posts shortcode and hide_image is selected-->
	<?php if (isset($rigid_is_latest_posts) && isset($rigid_blogposts_param_hide_image) && $rigid_blogposts_param_hide_image === 'no' || !isset($rigid_blogposts_param_hide_image)): ?>
		<?php if (!empty($rigid_featured_flex_slider_imgs) && is_singular()): // if there is slider or featured image attached and it is single post view, display it  ?>
			<div class="rigid_flexslider post_slide">
				<ul class="slides">
					<?php if (has_post_thumbnail()): ?>
						<li>
							<?php echo wp_get_attachment_image(get_post_thumbnail_id(), $rigid_featured_image_size); ?>
						</li>
					<?php endif; ?>

					<?php foreach ($rigid_featured_flex_slider_imgs as $rigid_img_att_id): ?>
						<li>
							<?php echo wp_get_attachment_image($rigid_img_att_id, $rigid_featured_image_size); ?>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php if (!is_single()): ?>
					<div class="portfolio-unit-info">
						<a class="go_to_page go_to_page_blog" title="<?php esc_html_e('View', 'rigid') ?>" href="<?php echo esc_url(get_permalink()) ?>"><?php the_title() ?></a>
					</div>
				<?php endif; ?>
			</div>
		<?php elseif (!$rigid_rev_slider_before_header && $rigid_featured_slider != 'none' && function_exists('putRevSlider')): ?>
			<div class="slideshow">
				<?php putRevSlider($rigid_featured_slider) ?>
			</div>
		<?php elseif (has_post_thumbnail() && (!is_single() || is_singular(array('post')) && $rigid_show_feat_image_in_post == 'yes')): ?>
			<div class="post-unit-holder">
				<?php the_post_thumbnail($rigid_featured_image_size); ?>
				<?php if (!is_single()): ?>
					<div class="portfolio-unit-info">
						<a class="go_to_page go_to_page_blog" title="<?php esc_html_e('View', 'rigid') ?>" href="<?php echo esc_url(get_permalink()) ?>"><?php the_title() ?></a>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	<!-- End Featured content for post list -->

	<div class="rigid_post_data_holder">
		<?php if ( ! is_singular() ): ?>
			<?php get_template_part( 'partials/blog-post-meta-top' ); ?>
		<?php endif; ?>
		<?php if (!is_single()): ?>
			<h2	class="heading-title">
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
			</h2>
		<?php endif; ?>

		<?php if ( ! is_singular() ): ?>
			<?php get_template_part( 'partials/blog-post-meta-bottom' ); ?>
		<?php endif; ?>

		<!-- SINGLE POST CONTENT -->
		<?php if (is_single()): ?>
			<?php the_content(); ?>
			<div class="clear"></div>
			<?php //the_tags();      ?>
			<?php if (rigid_get_option('show_author_info') && (trim(get_the_author_meta('description')))): ?>
				<div class="rigid-author-info">
					<div class="title">
						<h2><?php echo esc_html__('About the Author:', 'rigid'); ?> <?php the_author_posts_link(); ?></h2>
					</div>
					<div class="rigid-author-content">
						<div class="avatar">
							<?php echo get_avatar(get_the_author_meta('email'), 72); ?>
						</div>
						<div class="description">
							<?php the_author_meta("description"); ?>
						</div>
						<div class="clear"></div>
					</div>
				</div>
			<?php endif; ?>
			<?php wp_link_pages(array('before' => '<div class="page-links">' . esc_html__('Pages:', 'rigid'), 'after' => '</div>')); ?>
			<!-- BLOG / ARCHIVE / CATEGORY / TAG / SHORTCODE POST CONTENT -->
		<?php elseif (!is_search()): ?>
			<!--	Hide the excerpt if is latest posts shortcode and hide_excerpt is selected-->
			<?php if (isset($rigid_is_latest_posts) && isset($rigid_blogposts_param_hide_excerpt) && $rigid_blogposts_param_hide_excerpt === 'no' || !isset($rigid_blogposts_param_hide_excerpt)): ?>
				<div class="blog-post-excerpt">
					<?php
					// For latest post we set up excerpt of 20 words
					if (isset($rigid_is_latest_posts) && $rigid_is_latest_posts) {
						add_filter('excerpt_more', 'rigid_portfolio_new_excerpt_more');
						echo wp_trim_words(get_the_excerpt(), 20, ' ...');
						remove_filter('excerpt_more', 'rigid_new_excerpt_more');
					} else {
						if (has_excerpt()) {
						    // This is defined excerpt
                            echo '<div class="rigid-defined-excerpt">';
                                add_filter('excerpt_length', 'rigid_post_custom_excerpt_length', 999);
                                add_filter('excerpt_more', 'rigid_new_excerpt_more');
                                the_excerpt();
                                remove_filter('excerpt_length', 'rigid_post_custom_excerpt_length');
                                remove_filter('excerpt_more', 'rigid_new_excerpt_more');
							echo '</div>';
						} else {
							the_content();
						}
					}
					?>
				</div>
			<?php endif; ?>
			<!-- SEARCH POST CONTENT -->
		<?php else: ?>
            <div class="blog-post-excerpt">
				<?php if ( has_excerpt() ): // This is defined excerpt  ?>
                <div class="rigid-defined-excerpt">
					<?php endif; ?>

					<?php add_filter( 'excerpt_length', 'rigid_post_custom_excerpt_length', 999 ); ?>
					<?php add_filter( 'excerpt_more', 'rigid_new_excerpt_more' ); ?>
					<?php the_excerpt(); ?>
					<?php remove_filter( 'excerpt_length', 'rigid_post_custom_excerpt_length' ) ?>
					<?php remove_filter( 'excerpt_more', 'rigid_new_excerpt_more' ); ?>
                    
					<?php if ( has_excerpt() ): // This is defined excerpt  ?>
                </div>
			<?php endif; ?>
            </div>
		<?php endif; ?>
	</div>
</div>