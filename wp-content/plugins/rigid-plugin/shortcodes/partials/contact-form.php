<?php
wp_enqueue_script('jquery-form');

//response messages
$rigid_missing_content = esc_html__('Please enter %s.', 'rigid-plugin');
$rigid_missing_message = esc_html__('Please enter a message.', 'rigid-plugin');
$rigid_captcha_message = esc_html__('Calculation result was not correct.', 'rigid-plugin');
$rigid_email_invalid = esc_html__('Email Address Invalid.', 'rigid-plugin');
$rigid_message_unsent = esc_html__('Message was not sent. Try Again.', 'rigid-plugin');
$rigid_message_sent = esc_html__('Thanks! Your message has been sent.', 'rigid-plugin');

//user posted variables
$rigid_subject = array_key_exists('rigid_subject', $_POST) ? $_POST['rigid_subject'] : '';
$rigid_email = array_key_exists('rigid_email', $_POST) ? $_POST['rigid_email'] : '';
$rigid_name = array_key_exists('rigid_name', $_POST) ? $_POST['rigid_name'] : '';
$rigid_phone = array_key_exists('rigid_phone', $_POST) ? $_POST['rigid_phone'] : '';
$rigid_address = array_key_exists('rigid_address', $_POST) ? $_POST['rigid_address'] : '';
$rigid_message = array_key_exists('rigid_enquiry', $_POST) ? $_POST['rigid_enquiry'] : '';
$rigid_captcha_rand = array_key_exists('rigid_contact_submitted', $_POST) ? $_POST['rigid_contact_submitted'] : '';
$rigid_captcha_answer = array_key_exists('rigid_captcha_answer', $_POST) ? $_POST['rigid_captcha_answer'] : '';
// shortcode params
if (!isset($rigid_shortcode_params_for_tpl)) {
	$rigid_shortcode_params_for_tpl = array_key_exists('shortcode_params_for_tpl', $_POST) ? stripcslashes($_POST['shortcode_params_for_tpl']) : '';
}

if ($rigid_shortcode_params_for_tpl) {
	$rigid_shortcode_params_array = json_decode($rigid_shortcode_params_for_tpl, true);
	if ($rigid_shortcode_params_array) {
		extract($rigid_shortcode_params_array);
	}
}

$rigid_headers = '';
$rigid_contactform_response = '';
$rigid_rand_captcha = '';

/* Get choosen fields from Options */
$rigid_contacts_fields = array();

/* if is from shortcode */
if (isset($rigid_contact_form_fields)) {

	if(is_string($rigid_contact_form_fields)) {
		$rigid_contact_form_fields_arr = explode( ',', $rigid_contact_form_fields );
	} elseif (is_array($rigid_contact_form_fields)) {
		$rigid_contact_form_fields_arr = $rigid_contact_form_fields;
	}

	foreach($rigid_contact_form_fields_arr as $rigid_field)    {
		$rigid_contacts_fields[$rigid_field] = true;
	}
}

$rigid_has_error = false;
$rigid_name_error = $rigid_email_error = $rigid_phone_error = $rigid_address_error = $rigid_subject_error = $rigid_message_error = $rigid_captcha_error = false;

if (isset($_POST['rigid_contact_submitted'])) {

	/* Validate Email address */
	if ($rigid_email && $rigid_contacts_fields['email'] && !filter_var($rigid_email, FILTER_VALIDATE_EMAIL)) {
		$rigid_has_error = true;
		$rigid_email_error = rigid_contact_form_generate_response("error", $rigid_email_invalid);
	} else {
		$rigid_headers = 'From: ' . sanitize_email($rigid_email) . "\r\n" . 'Reply-To: ' . sanitize_email($rigid_email) . "\r\n";
	}

	/* Check if all fields are filled */
	foreach ($rigid_contacts_fields as $rigid_fieldname => $rigid_is_enabled) {
		if ($rigid_is_enabled && !${'rigid_' . $rigid_fieldname}) {
			$rigid_has_error = true;
			${'rigid_' . $rigid_fieldname . '_error'} = rigid_contact_form_generate_response("error", sprintf($rigid_missing_content, $rigid_fieldname));
		}
	}

	/* Check for a message */
	if (!trim($rigid_message)) {
		$rigid_has_error = true;
		$rigid_message_error = rigid_contact_form_generate_response("error", $rigid_missing_message);
	}

	/* captcha validation */
	if ($rigid_simple_captcha) {
		if ((int) $rigid_captcha_rand + 1 !== (int) $rigid_captcha_answer) {
			$rigid_has_error = true;
			$rigid_captcha_error = rigid_contact_form_generate_response("error", $rigid_captcha_message);
		}
	}

	if (!$rigid_has_error) {
		$rigid_sent = wp_mail(sanitize_email($rigid_contact_mail_to), ($rigid_subject ? sanitize_text_field($rigid_subject) : sprintf(esc_html__('Someone sent a message from %s', 'rigid-plugin'), sanitize_text_field(get_bloginfo('name')))), ($rigid_name ? "Name: " . sanitize_text_field($rigid_name) : "") . "\r\n" . ($rigid_email ? "E-Mail Address: " . sanitize_text_field($rigid_email) . "\r\n" : "") . ($rigid_phone ? "Phone: " . sanitize_text_field($rigid_phone) . "\r\n" : "") . ($rigid_address ? "Street Address: " . sanitize_text_field($rigid_address) . "\r\n" : "") . "\r\n" . wp_kses_post($rigid_message), $rigid_headers);
		if ($rigid_sent) {
			$rigid_contactform_response = rigid_contact_form_generate_response("success", $rigid_message_sent); //message sent!
			//clear values
			$rigid_subject = $rigid_email = $rigid_name = $rigid_phone = $rigid_address = $rigid_message = '';
		} else {
			$rigid_contactform_response = rigid_contact_form_generate_response("error", $rigid_message_unsent); //message wasn't sent
		}
	}
}

$rigid_contact_title = isset($rigid_title) ? $rigid_title : esc_html__('Send us a message', 'rigid-plugin');
?>
<?php if ($rigid_contact_title): ?>
	<h2 class="contact-form-title"><?php echo esc_html($rigid_contact_title) ?></h2>
<?php endif; ?>
<form action="<?php echo esc_url(admin_url('admin-ajax.php')) ?>" method="post" class="contact-form">
	<?php if (isset($rigid_contacts_fields['name'])): ?>
		<div class="content rigid_name"> <span><?php esc_html_e('Your Name', 'rigid-plugin'); ?>:</span>
			<input type="text" value="<?php echo esc_attr($rigid_name); ?>" name="rigid_name" />
			<?php if ($rigid_name_error) echo wp_kses_post($rigid_name_error); ?>
		</div>

	<?php endif; ?>
	<?php if (isset($rigid_contacts_fields['email'])): ?>
		<div class="content rigid_email"> <span><?php esc_html_e('E-Mail Address', 'rigid-plugin'); ?>:</span>
			<input type="text" value="<?php echo esc_attr($rigid_email); ?>" name="rigid_email" />
			<?php if ($rigid_email_error) echo wp_kses_post($rigid_email_error); ?>
		</div>
	<?php endif; ?>
	<?php if (isset($rigid_contacts_fields['phone'])): ?>
		<div class="content rigid_phone"> <span><?php esc_html_e('Phone', 'rigid-plugin'); ?>:</span>
			<input type="text" value="<?php echo esc_attr($rigid_phone); ?>" name="rigid_phone" />
			<?php if ($rigid_phone_error) echo wp_kses_post($rigid_phone_error); ?>
		</div>
	<?php endif; ?>
	<?php if (isset($rigid_contacts_fields['address'])): ?>
		<div class="content rigid_address"> <span><?php esc_html_e('Street Address', 'rigid-plugin'); ?>:</span>
			<input type="text" value="<?php echo esc_attr($rigid_address); ?>" name="rigid_address" />
			<?php if ($rigid_address_error) echo wp_kses_post($rigid_address_error); ?>
		</div>
	<?php endif; ?>
	<?php if (isset($rigid_contacts_fields['subject'])): ?>
		<div class="content rigid_subject"> <span><?php esc_html_e('Subject', 'rigid-plugin'); ?>:</span>
			<input type="text" value="<?php echo esc_attr($rigid_subject); ?>" name="rigid_subject" />
			<?php if ($rigid_subject_error) echo wp_kses_post($rigid_subject_error); ?>
		</div>
	<?php endif; ?>
	<div class="content rigid_enquiry"> <span><?php esc_html_e('Message Text', 'rigid-plugin'); ?>:</span>
		<textarea style="width: 99%;" rows="10" cols="40" name="rigid_enquiry"><?php echo esc_textarea($rigid_message); ?></textarea>
		<?php if ($rigid_message_error) echo wp_kses_post($rigid_message_error); ?>
	</div>
	<?php if ($rigid_simple_captcha): ?>
		<?php $rigid_rand_captcha = mt_rand(0, 8); ?>
		<div class="content rigid_form_test">
			<?php echo esc_html__("Prove you're a human", 'rigid-plugin') ?>: <span class=constant>1</span> + <span class=random><?php echo esc_html($rigid_rand_captcha) ?></span> = ? <input type="text" value="" name="rigid_captcha_answer" />
		</div>
		<?php if ($rigid_captcha_error) echo wp_kses_post($rigid_captcha_error); ?>
	<?php endif; ?>
	<?php echo wp_kses_post($rigid_contactform_response); ?>
	<div class="buttons">
		<input type="hidden" name="rigid_contact_submitted" value="<?php echo esc_attr($rigid_rand_captcha) ?>">
		<input type="hidden" name="shortcode_params_for_tpl" value="<?php echo esc_attr($rigid_shortcode_params_for_tpl) ?>">
		<div class="left"><input class="button button-orange" value="<?php esc_html_e('Send message', 'rigid-plugin') ?>" type="submit"></div>
	</div>
</form>
