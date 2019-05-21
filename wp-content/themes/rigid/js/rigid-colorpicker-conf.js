(function ($) {
	"use strict";
	$(document).ready(function () {
		$('INPUT.minicolors').minicolors({
			animationSpeed: 50,
			animationEasing: 'swing',
			change: null,
			changeDelay: 0,
			control: 'hue',
			dataUris: true,
			defaultValue: '',
			hide: null,
			hideSpeed: 100,
			inline: false,
			letterCase: 'lowercase',
			opacity: false,
			position: 'bottom left',
			show: null,
			showSpeed: 100,
			theme: 'default'
		});
	});
})(window.jQuery);
