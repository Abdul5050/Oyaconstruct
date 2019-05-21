<!-- FOOTER -->
<?php
global $rigid_is_blank;
?>
<!-- If it is not a blank page template -->
<?php if (!$rigid_is_blank): ?>
	<div id="footer">
		<?php
		$rigid_show_footer_logo = false;
		$rigid_show_footer_menu = false;

		if ( has_nav_menu( 'tertiary' ) ) {
			$rigid_show_footer_menu = true;
		}
		if ( rigid_get_option( 'show_logo_in_footer' ) && ( rigid_get_option( 'theme_logo' ) || rigid_get_option( 'footer_logo' ) ) ) {
			$rigid_show_footer_logo = true;
		}
		?>
		<?php if ( $rigid_show_footer_logo || $rigid_show_footer_menu ): ?>
            <div class="inner">
				<?php if ( $rigid_show_footer_menu ): ?>
					<?php
					/* Tertiary menu */
					$rigid_footer_nav_args = array(
						'theme_location' => 'tertiary',
						'container'      => 'div',
						'container_id'   => 'rigid_footer_menu_container',
						'menu_class'     => '',
						'menu_id'        => 'rigid_footer_menu',
						'depth'          => 1,
						'fallback_cb'    => '',
					);
					wp_nav_menu( $rigid_footer_nav_args );
					?>
				<?php endif; ?>
				<?php if ( $rigid_show_footer_logo ): ?>
                    <div id="rigid_footer_logo">
                        <a href="<?php echo esc_url( rigid_wpml_get_home_url() ); ?>"
                           title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
							<?php
							$rigid_theme_logo_img  = rigid_get_option( 'theme_logo' );
							$rigid_footer_logo_img = rigid_get_option( 'footer_logo' );

							// If footer logo, show footer logo, else main logo
							if ( $rigid_footer_logo_img ) {
								echo wp_get_attachment_image( $rigid_footer_logo_img, 'full', false );
							} elseif ( $rigid_theme_logo_img ) {
								echo wp_get_attachment_image( $rigid_theme_logo_img, 'full', false );
							}
							?>
                        </a>
                    </div>
				<?php endif; ?>
            </div>
		<?php endif; ?>
		<?php
		$rigid_meta_options = array();
		if (is_single() || is_page()) {
			$rigid_meta_options = get_post_custom(get_queried_object_id());
		}

		$rigid_show_sidebar = 'yes';
		if (isset($rigid_meta_options['rigid_show_footer_sidebar']) && trim($rigid_meta_options['rigid_show_footer_sidebar'][0]) != '') {
			$rigid_show_sidebar = $rigid_meta_options['rigid_show_footer_sidebar'][0];
		}

		$rigid_footer_sidebar_choice = rigid_get_option('footer_sidebar');
		if (isset($rigid_meta_options['rigid_custom_footer_sidebar']) && $rigid_meta_options['rigid_custom_footer_sidebar'][0] !== 'default') {
			$rigid_footer_sidebar_choice = $rigid_meta_options['rigid_custom_footer_sidebar'][0];
		}

		if ( $rigid_show_sidebar === 'no' ) {
			$rigid_footer_sidebar_choice = 'none';
		}
		?>
		<?php if (function_exists('dynamic_sidebar') && $rigid_footer_sidebar_choice != 'none' && is_active_sidebar($rigid_footer_sidebar_choice)) : ?>
			<div class="inner">
				<?php dynamic_sidebar($rigid_footer_sidebar_choice) ?>
				<div class="clear"></div>
			</div>
		<?php endif; ?>
		<div id="powered">
			<div class="inner">
				<!--	Social profiles in footer -->
				<?php if (rigid_get_option('social_in_footer')): ?>
					<?php get_template_part('partials/social-profiles'); ?>
				<?php endif; ?>

				<div class="author_credits"><?php echo wp_kses_post(rigid_get_option('copyright_text')) ?></div>
			</div>
		</div>
	</div>
	<!-- END OF FOOTER -->
	<!-- Previous / Next links -->
	<?php if (rigid_get_option('show_prev_next')): ?>
		<?php echo rigid_post_nav(); ?>
	<?php endif; ?>
<?php endif; ?>
</div>
<!-- END OF MAIN WRAPPER -->
<?php
$rigid_is_compare = false;
if (isset($_GET['action']) && $_GET['action'] === 'yith-woocompare-view-table') {
	$rigid_is_compare = true;
}

$rigid_to_include_backgr_video = rigid_has_to_include_backgr_video($rigid_is_compare);
?>
<?php if ($rigid_to_include_backgr_video): ?>
	<?php
	$rigid_video_bckgr_url = $rigid_video_bckgr_start = $rigid_video_bckgr_end = $rigid_video_bckgr_loop = $rigid_video_bckgr_mute = '';

	switch ($rigid_to_include_backgr_video) {
		case 'postmeta':
			$rigid_custom = rigid_has_post_video_bckgr();
			$rigid_video_bckgr_url = isset($rigid_custom['rigid_video_bckgr_url'][0]) ? $rigid_custom['rigid_video_bckgr_url'][0] : '';
			$rigid_video_bckgr_start = isset($rigid_custom['rigid_video_bckgr_start'][0]) ? $rigid_custom['rigid_video_bckgr_start'][0] : '';
			$rigid_video_bckgr_end = isset($rigid_custom['rigid_video_bckgr_end'][0]) ? $rigid_custom['rigid_video_bckgr_end'][0] : '';
			$rigid_video_bckgr_loop = isset($rigid_custom['rigid_video_bckgr_loop'][0]) ? $rigid_custom['rigid_video_bckgr_loop'][0] : '';
			$rigid_video_bckgr_mute = isset($rigid_custom['rigid_video_bckgr_mute'][0]) ? $rigid_custom['rigid_video_bckgr_mute'][0] : '';
			break;
		case 'blog':
			$rigid_video_bckgr_url = rigid_get_option('blog_video_bckgr_url');
			$rigid_video_bckgr_start = rigid_get_option('blog_video_bckgr_start');
			$rigid_video_bckgr_end = rigid_get_option('blog_video_bckgr_end');
			$rigid_video_bckgr_loop = rigid_get_option('blog_video_bckgr_loop');
			$rigid_video_bckgr_mute = rigid_get_option('blog_video_bckgr_mute');
			break;
		case 'shop':
		case 'shopwide':
			$rigid_video_bckgr_url = rigid_get_option('shop_video_bckgr_url');
			$rigid_video_bckgr_start = rigid_get_option('shop_video_bckgr_start');
			$rigid_video_bckgr_end = rigid_get_option('shop_video_bckgr_end');
			$rigid_video_bckgr_loop = rigid_get_option('shop_video_bckgr_loop');
			$rigid_video_bckgr_mute = rigid_get_option('shop_video_bckgr_mute');
			break;
		case 'global':
			$rigid_video_bckgr_url = rigid_get_option('video_bckgr_url');
			$rigid_video_bckgr_start = rigid_get_option('video_bckgr_start');
			$rigid_video_bckgr_end = rigid_get_option('video_bckgr_end');
			$rigid_video_bckgr_loop = rigid_get_option('video_bckgr_loop');
			$rigid_video_bckgr_mute = rigid_get_option('video_bckgr_mute');
			break;
		default:
			break;
	}
	?>
    <div id="bgndVideo" class="rigid_bckgr_player"
         data-property="{videoURL:'<?php echo esc_url($rigid_video_bckgr_url) ?>',containment:'body',autoPlay:true, loop:<?php echo esc_js($rigid_video_bckgr_loop) ? 'true' : 'false'; ?>, mute:<?php echo esc_js($rigid_video_bckgr_mute) ? 'true' : 'false'; ?>, startAt:<?php echo esc_js($rigid_video_bckgr_start) ? esc_js($rigid_video_bckgr_start) : 0; ?>, opacity:.9, showControls:false, addRaster:true, quality:'default'<?php if ($rigid_video_bckgr_end): ?>, stopAt:<?php echo esc_js($rigid_video_bckgr_end) ?><?php endif; ?>}">
    </div>
	<?php if (!$rigid_video_bckgr_mute): ?>
        <div class="video_controlls">
            <a id="video-volume" href="#" onclick="<?php echo esc_js('jQuery("#bgndVideo").toggleVolume()') ?>"><i class="fa fa-volume-up"></i></a>
            <a id="video-play" href="#" onclick="<?php echo esc_js('jQuery("#bgndVideo").playYTP()') ?>"><i class="fa fa-play"></i></a>
            <a id="video-pause" href="#" onclick="<?php echo esc_js('jQuery("#bgndVideo").pauseYTP()') ?>"><i class="fa fa-pause"></i></a>
        </div>
	<?php endif; ?>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>