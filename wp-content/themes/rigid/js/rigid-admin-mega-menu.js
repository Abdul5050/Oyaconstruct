/**
 * Used for mega menu set up in admin
 */
(function ($) {
	"use strict";

	var rigid_mega_menu = {
		recalcTimeout: false,

		bind_click: function ()
		{
			var megmenuActivator = '.rigid-menu-item-is_megamenu';

			$(document).on('click', megmenuActivator, function ()
			{
				var checkbox = $(this),	container = checkbox.parents('.menu-item:eq(0)');

				if (checkbox.is(':checked'))
				{
					container.addClass('rigid_is_mega');
				}
				else
				{
					container.removeClass('rigid_is_mega');
				}

				//check if anything in the dom needs to be changed to reflect the (de)activation of the mega menu
				rigid_mega_menu.recalc();

			});
		},
		recalcInit: function ()
		{
			$(document).on('mouseup', '.menu-item-bar', function (event, ui)
			{
				if (!$(event.target).is('a'))
				{
					clearTimeout(rigid_mega_menu.recalcTimeout);
					rigid_mega_menu.recalcTimeout = setTimeout(rigid_mega_menu.recalc, 500);
				}
			});
		},
		recalc: function ()
		{
			var menuItems = $('.menu-item');

			menuItems.each(function (i)
			{
				var item = $(this),
								megaMenuCheckbox = $('.rigid-menu-item-is_megamenu', this);

				if (!item.is('.menu-item-depth-0'))
				{
					var checkItem = menuItems.filter(':eq(' + (i - 1) + ')');
					if (checkItem.is('.rigid_is_mega'))
					{
						item.addClass('rigid_is_mega');
						megaMenuCheckbox.attr('checked', 'checked');
					}
					else
					{
						item.removeClass('rigid_is_mega');
						megaMenuCheckbox.attr('checked', '');
					}
				}
			});

		}
	};

	$(function ()
	{
		rigid_mega_menu.bind_click();
		rigid_mega_menu.recalcInit();
		rigid_mega_menu.recalc();
	});

})(jQuery);