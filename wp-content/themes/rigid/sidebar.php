<?php
// Sidebar template
//wp_reset_postdata();
$rigid_sidebar_choice = apply_filters('rigid_has_sidebar', '');
?>

<?php if (function_exists('dynamic_sidebar') && $rigid_sidebar_choice != 'none' && is_active_sidebar($rigid_sidebar_choice) ) : ?>
	<div class="sidebar">
		<?php dynamic_sidebar($rigid_sidebar_choice); ?>
	</div>
<?php endif;