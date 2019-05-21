<?php
// The Archive template file for rigid-portfolio CPT.

get_header();
$rigid_category_layout = json_decode(rigid_get_option('portfoio_cat_layout'), true);

$rigid_portfolio_style_class = $rigid_category_layout['rigid_portfolio_style_class'];
$rigid_columns_class = $rigid_category_layout['rigid_columns_class'];

//If Masonry Fullwidth append fullwidth class to body
if ($rigid_columns_class == 'rigid_masonry_fullwidth') {

	$rigid_inline_js = '(function ($) {"use strict"; $(document).ready(function () { $("#content > .inner").addClass("rigid_masonry_fullwidth");});})(window.jQuery);';
	wp_add_inline_script('rigid-front', $rigid_inline_js);
	$rigid_columns_class = '';
}

set_query_var('rigid_portfolio_style_class', $rigid_portfolio_style_class);
set_query_var('rigid_columns_class', $rigid_columns_class);

// Load the partial
get_template_part('partials/content', 'rigid_portfolio_category');

get_footer();
