<?php
/*
 * Partial for showing social site profiles
 */

/* Array holding the available social profiles: name => array( title => fa class name) */
$rigid_social_profiles = array(
		'facebook' => array('title' => esc_html__('Follow on Facebook', 'rigid'), 'class' => 'fa fa-facebook'),
		'twitter' => array('title' => esc_html__('Follow on Twitter', 'rigid'), 'class' => 'fa fa-twitter'),
		'google' => array('title' => esc_html__('Follow on Google+', 'rigid'), 'class' => 'fa fa-google-plus'),
		'youtube' => array('title' => esc_html__('Follow on YouTube', 'rigid'), 'class' => 'fa fa-youtube-play'),
		'vimeo' => array('title' => esc_html__('Follow on Vimeo', 'rigid'), 'class' => 'fa fa-vimeo-square'),
		'dribbble' => array('title' => esc_html__('Follow on Dribbble', 'rigid'), 'class' => 'fa fa-dribbble'),
		'linkedin' => array('title' => esc_html__('Follow on LinkedIn', 'rigid'), 'class' => 'fa fa-linkedin'),
		'stumbleupon' => array('title' => esc_html__('Follow on StumbleUpon', 'rigid'), 'class' => 'fa fa-stumbleupon'),
		'flicker' => array('title' => esc_html__('Follow on Flickr', 'rigid'), 'class' => 'fa fa-flickr'),
		'instegram' => array('title' => esc_html__('Follow on Instagram', 'rigid'), 'class' => 'fa fa-instagram'),
		'pinterest' => array('title' => esc_html__('Follow on Pinterest', 'rigid'), 'class' => 'fa fa-pinterest'),
		'vkontakte' => array('title' => esc_html__('Follow on VKontakte', 'rigid'), 'class' => 'fa fa-vk'),
		'behance' => array('title' => esc_html__('Follow on Behance', 'rigid'), 'class' => 'fa fa-behance')
);
?>
<div class="rigid-social">
	<ul>
		<?php foreach ($rigid_social_profiles as $rigid_social_name => $rigid_details): ?>
			<?php if (rigid_get_option($rigid_social_name . '_profile')): ?>
				<li><a title="<?php echo esc_attr($rigid_details['title']) ?>" class="<?php echo esc_attr($rigid_social_name) ?>" target="_blank"  href="<?php echo esc_url(rigid_get_option($rigid_social_name . '_profile')) ?>"><i class="<?php echo esc_attr($rigid_details['class']) ?>"></i></a></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>