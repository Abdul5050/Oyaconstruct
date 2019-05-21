<?php

if (!defined('ABSPATH')) {
	die('-1');
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * @var $this WPBakeryShortCode_Rigid_Content_Slider|WPBakeryShortCode_VC_Tta_Tabs|WPBakeryShortCode_VC_Tta_Tour|WPBakeryShortCode_VC_Tta_Pageable
 *
 * Copied from vc-tta-global.php
 */
//$el_class = $css = '';
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
$this->resetVariables($atts, $content);
extract($atts);

$this->setGlobalTtaInfo();

//$this->enqueueTtaScript();
// It is required to be before tabs-list-top/left/bottom/right for tabs/tours
$prepareContent = $this->getTemplateVariable('content');

$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$custom_css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$css_classes = array();
// Full Height option
if($full_height === 'yes') {
	$css_classes[] = 'rigid-fullheight-content-slider';
}
// Navigation color option
$css_classes[] = $navigation_color;
// Pagination type option
if($pagination === 'yes') {
	$css_classes[] = $pagination_type;
}

$unique_id = uniqid('rigid_content_slider');

$output = '<div id="' . esc_attr($unique_id) . '" class="rigid_content_slider' . ($custom_css_class ? ' ' . esc_attr($custom_css_class) : '') . (empty($css_classes) ? '' : ' ' . esc_attr(implode(' ', $css_classes))) . '">';
$output .= $this->getTemplateVariable('title');
$output .= '<div class="vc_tta-panels owl-carousel">';
$output .= $prepareContent;
$output .= '</div>';
$output .= $this->getTemplateVariable('tabs-list-bottom');
$output .= $this->getTemplateVariable('tabs-list-right');
$output .= '</div>';

$autoplay_owl_option = 'false';
$autoplayTimeout_owl_option = '5000';
if ($autoplay !== 'none') {
	$autoplay_owl_option = 'true';
	$autoplayTimeout_owl_option = $autoplay . '000';
}

$navigation_owl_option = 'false';
if ($navigation === 'yes') {
	$navigation_owl_option = 'true';
}

$pause_on_hover_owl_option = 'false';
if ($pause_on_hover === 'yes') {
	$pause_on_hover_owl_option = 'true';
}

$pagination_owl_option = 'false';
if ($pagination === 'yes') {
	$pagination_owl_option = 'true';
}

$animateOut = 'false';
$animateIn = 'false';
if ($transition === 'fade') {
	$animateOut = 'fadeOut';
	$animateIn = 'fadeIn';
} elseif ($transition === 'slide-flip') {
	$animateOut = 'slideOutDown';
	$animateIn = 'flipInX';
}

$inline_js = '(function ($) {
		"use strict";
		var is_rigid_video_background = jQuery("#' . esc_js($unique_id) . '").find("div.rigid_bckgr_player").length;
		var rigid_loop = true;
		if(is_rigid_video_background) {
			rigid_loop = false;
		}
		
		var rigid_owl_args = {
				rtl: '.( is_rtl() ? 'true' : 'false' ).',
				items: 1,
				autoplayHoverPause: ' . esc_js($pause_on_hover_owl_option) . ',
				autoplay: ' . esc_js($autoplay_owl_option) . ',
				autoplayTimeout: ' . esc_js($autoplayTimeout_owl_option) . ',
				autoplaySpeed: 800,
				dots: ' . esc_js($pagination_owl_option) . ',
				nav: ' . esc_js($navigation_owl_option) . ',
				navText: [
					"<i class=\'fa fa-angle-left\'></i>",
					"<i class=\'fa fa-angle-right\'></i>"
				],
				animateOut: ' . ($animateOut == 'false' ? 'false' : '"' . esc_js($animateOut) . '"') . ',
				animateIn: ' . ($animateIn == 'false' ? 'false' : '"' . esc_js($animateIn) . '"') . ', ' . ($transition === 'slide-flip' ? 'smartSpeed:450,' : '') . '
		};
		rigid_owl_args["loop"] = rigid_loop;
		
		$(window).load(function () {
			jQuery("#' . esc_js($unique_id) . ' > .vc_tta-panels").owlCarousel(rigid_owl_args);
		});
	})(window.jQuery);';
wp_add_inline_script('owl-carousel', $inline_js);

echo $output; // All dynamic data escaped
