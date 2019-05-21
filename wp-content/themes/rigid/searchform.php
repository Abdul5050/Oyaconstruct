<?php

$rigid_search_params = array(
	'placeholder'  	=> esc_html__('Search','rigid'),
	'search_id'	   	=> 's',
	'form_action'	=> rigid_wpml_get_home_url(),
	'ajax_disable'	=> false
);

?>

<form action="<?php echo esc_url($rigid_search_params['form_action']); ?>" id="searchform" method="get">
	<div>
		<input type="submit" id="searchsubmit"  value=""/>
		<input type="text" id="s" name="<?php echo esc_attr($rigid_search_params['search_id']); ?>" value="<?php if(!empty($rigid__GET['s'])) echo esc_attr(get_search_query()); ?>" placeholder='<?php echo esc_attr($rigid_search_params['placeholder']); ?>' />
	</div>
</form>