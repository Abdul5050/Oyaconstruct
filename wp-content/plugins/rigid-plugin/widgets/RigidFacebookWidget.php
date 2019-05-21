<?php
defined('ABSPATH') || die();

/**
 * Widget to display Facebook page details
 *
 * @author aatanasov
 */
class RigidFacebookWidget extends WP_Widget {

	public function __construct() {
		$widget_ops = array('description' => esc_html__('Shows Facebook page details', 'rigid-plugin'));
		parent::__construct('rigid_facebook_widget', 'Rigid Facebook', $widget_ops);
	}

	public function widget($args, $instance) {
		// enqueue facebook js
		wp_enqueue_script('rigid-facebook-script');

		extract($args);
		$title = apply_filters('widget_title', $instance['title']);

		echo wp_kses_post($before_widget);
		if (!empty($title))
			echo wp_kses_post($before_title . $title . $after_title);
		?>
		<div id="fb-root"></div>
		<iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo esc_attr($instance['fb_url']) ?>&amp;width=245&amp;height=240&amp;show_faces=true&amp;connections=8&amp;colorscheme=<?php echo esc_attr($instance['skin']) ?>&amp;stream=false&amp;show_border=false&amp;header=false&amp;appId=<?php echo esc_attr($instance['app_id']) ?>" style="border:none; overflow:hidden; width:245px; height:240px;"></iframe>
		<?php
		echo wp_kses_post($after_widget);
	}

	public function form($instance) {
		// Defaults
		$defaults = array(
				'title' => esc_html__('Join us on Facebook', 'rigid-plugin'),
				'skin' => 'dark'
		);

		$instance = wp_parse_args((array) $instance, $defaults);
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'rigid-plugin'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php if (isset($instance['title'])) echo esc_attr($instance['title']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('fb_url')); ?>"><?php esc_html_e('Facebook Profile ID:', 'rigid-plugin'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('fb_url')); ?>" name="<?php echo esc_attr($this->get_field_name('fb_url')); ?>" type="text" value="<?php if (isset($instance['fb_url'])) echo esc_attr($instance['fb_url']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('app_id')); ?>"><?php esc_html_e('Application ID:', 'rigid-plugin'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('app_id')); ?>" name="<?php echo esc_attr($this->get_field_name('app_id')); ?>" type="text" value="<?php if (isset($instance['app_id'])) echo esc_attr($instance['app_id']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('skin')); ?>"><?php esc_html_e('Choose skin:', 'rigid-plugin'); ?></label>
			<select class="widefat" id="<?php echo esc_attr($this->get_field_id('skin')); ?>" name="<?php echo esc_attr($this->get_field_name('skin')); ?>">
				<option value="light" <?php selected($instance['skin'], 'light') ?> ><?php esc_html_e('Light', 'rigid-plugin') ?></option>
				<option value="dark" <?php selected($instance['skin'], 'dark') ?> ><?php esc_html_e('Dark', 'rigid-plugin') ?></option>
			</select>
		</p>
		<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['fb_url'] = $new_instance['fb_url'];
		$instance['app_id'] = $new_instance['app_id'];
		$instance['skin'] = $new_instance['skin'];

		return $instance;
	}

}

add_action('widgets_init', 'rigid_register_rigid_facebook_widget');
if (!function_exists('rigid_register_rigid_facebook_widget')) {

	function rigid_register_rigid_facebook_widget() {
		register_widget('RigidFacebookWidget');
	}

}
?>
