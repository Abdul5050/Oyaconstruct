jQuery( function( $ ) {
    "use strict";

    /**************************
     * "rigid-price-slider"
     **************************/

    if (typeof rigid_price_slider_params !== 'undefined') {
        // Get markup ready for slider
        $('#min_price').hide();
        $('#max_price').hide();

        $('div.price_slider', '#rigid-price-filter-form').show();
        $('div.price_label', '#rigid-price-filter-form').show();

        // Price slider uses jquery ui
        var min_price = $('#min_price').data('min');
        var max_price = $('#max_price').data('max');

        var current_min_price = parseInt(min_price, 10);
        var current_max_price = parseInt(max_price, 10);

        if (rigid_price_slider_params.min_price)
            current_min_price = parseInt(rigid_price_slider_params.min_price);
        if (rigid_price_slider_params.max_price)
            current_max_price = parseInt(rigid_price_slider_params.max_price);

        var body = $('body');

        body.bind('price_slider_create price_slider_slide', function (event, min, max) {
            if (rigid_price_slider_params.currency_pos == "left") {

                $("span.from", "#rigid_price_range").html(rigid_price_slider_params.currency_symbol + min);
                $("span.to", "#rigid_price_range").html(rigid_price_slider_params.currency_symbol + max);

            } else if (rigid_price_slider_params.currency_pos == "left_space") {

                $("span.from", "#rigid_price_range").html(rigid_price_slider_params.currency_symbol + " " + min);
                $("span.to", "#rigid_price_range").html(rigid_price_slider_params.currency_symbol + " " + max);

            } else if (rigid_price_slider_params.currency_pos == "right") {

                $("span.from", "#rigid_price_range").html(min + rigid_price_slider_params.currency_symbol);
                $("span.to", "#rigid_price_range").html(max + rigid_price_slider_params.currency_symbol);

            } else if (rigid_price_slider_params.currency_pos == "right_space") {

                $("span.from", "#rigid_price_range").html(min + " " + rigid_price_slider_params.currency_symbol);
                $("span.to", "#rigid_price_range").html(max + " " + rigid_price_slider_params.currency_symbol);

            }

            body.trigger('price_slider_updated', min, max);
        });

        $('div.price_slider', '#rigid-price-filter-form').slider({
            range: true,
            min: min_price,
            max: max_price,
            values: [current_min_price, current_max_price],
            create: function (event, ui) {

                $("#min_price").val(current_min_price);
                $("#max_price").val(current_max_price);

                body.trigger('price_slider_create', [current_min_price, current_max_price]);
            },
            slide: function (event, ui) {

                $("#min_price").val(ui.values[0]);
                $("#max_price").val(ui.values[1]);

                body.trigger('price_slider_slide', [ui.values[0], ui.values[1]]);
            },
            change: function (event, ui) {

                body.trigger('price_slider_change', [ui.values[0], ui.values[1]]);

            },
            stop: function (event, ui) {
            	$.rigid_show_loader();

            	setTimeout(function () {
            		$('#rigid-price-filter-form').submit();
            	}, 300);

            }
        });
    }
    /**************************
     * END "rigid-price-slider"
     **************************/
})