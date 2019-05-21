<?php
/**
 * Register second image field for WooCommerce categories
 * to be used in the category header
 */

add_action( 'product_cat_add_form_fields', 'rigid_woocommerce_custom_cat_fields_add', 11, 2 );
add_action( 'product_cat_edit_form_fields', 'rigid_woocommerce_custom_cat_fields_edit', 11, 2 );
add_action( 'created_term', 'rigid_woocommerce_custom_cat_fields_save', 10, 4 );
add_action( 'edit_term', 'rigid_woocommerce_custom_cat_fields_save', 10, 4 );

if ( ! function_exists( 'rigid_woocommerce_custom_cat_fields_add' ) ) {
	function rigid_woocommerce_custom_cat_fields_add() {
		?>
        <div class="form-field rigid-term-header-img-wrap">
            <label><?php echo esc_html__( 'Title background image', 'rigid' ); ?></label>
            <div id="rigid_term_header_img" style="float: left; margin-right: 10px;"><img
                        src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px"/></div>
            <div style="line-height: 60px;">
                <input type="hidden" id="rigid_term_header_img_id" name="rigid_term_header_img_id"/>
                <button type="button"
                        class="rigid_term_header_img_upload_image_button button"><?php echo esc_html__( 'Upload/Add image', 'rigid' ); ?></button>
                <button type="button"
                        class="rigid_term_header_img_remove_image_button button"><?php echo esc_html__( 'Remove image', 'rigid' ); ?></button>
            </div>
			<?php ob_start(); ?>
            <script type="text/javascript">
                // Only show the "remove image" button when needed
                if (!jQuery('#rigid_term_header_img_id').val()) {
                    jQuery('.rigid_term_header_img_remove_image_button').hide();
                }

                // Uploading files
                var rigid_term_header_img_file_frame;

                jQuery(document).on('click', '.rigid_term_header_img_upload_image_button', function (event) {

                    event.preventDefault();

                    // If the media frame already exists, reopen it.
                    if (rigid_term_header_img_file_frame) {
                        rigid_term_header_img_file_frame.open();
                        return;
                    }

                    // Create the media frame.
                    rigid_term_header_img_file_frame = wp.media.frames.downloadable_file = wp.media({
                        title: '<?php echo esc_html__( "Choose an image", "rigid" ); ?>',
                        button: {
                            text: '<?php echo esc_html__( "Use image", "rigid" ); ?>'
                        },
                        multiple: false
                    });

                    // When an image is selected, run a callback.
                    rigid_term_header_img_file_frame.on('select', function () {
                        var attachment = rigid_term_header_img_file_frame.state().get('selection').first().toJSON();

                        jQuery('#rigid_term_header_img_id').val(attachment.id);
                        jQuery('#rigid_term_header_img').find('img').attr('src', attachment.sizes.thumbnail.url);
                        jQuery('.rigid_term_header_img_remove_image_button').show();
                    });

                    // Finally, open the modal.
                    rigid_term_header_img_file_frame.open();
                });

                jQuery(document).on('click', '.rigid_term_header_img_remove_image_button', function () {
                    jQuery('#rigid_term_header_img').find('img').attr('src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>');
                    jQuery('#rigid_term_header_img_id').val('');
                    jQuery('.rigid_term_header_img_remove_image_button').hide();
                    return false;
                });

                jQuery(document).ajaxComplete(function (event, request, options) {
                    if (request && 4 === request.readyState && 200 === request.status
                        && options.data && 0 <= options.data.indexOf('action=add-tag')) {

                        var res = wpAjax.parseAjaxResponse(request.responseXML, 'ajax-response');
                        if (!res || res.errors) {
                            return;
                        }
                        // Clear Thumbnail fields on submit
                        jQuery('#rigid_term_header_img').find('img').attr('src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>');
                        jQuery('#rigid_term_header_img_id').val('');
                        jQuery('.rigid_term_header_img_remove_image_button').hide();
                        return;
                    }
                });

            </script>
			<?php $js_handle_header_img_on_cat_add = ob_get_clean(); ?>
			<?php wp_add_inline_script( 'rigid-back', rigid_strip_script_tag_from_js_block( $js_handle_header_img_on_cat_add ) ); ?>
            <div class="clear"></div>
        </div>

        <div class="form-field rigid-term-header-style-wrap">
            <label for="rigid_term_header_style"><?php esc_html_e( 'Header Style', 'rigid' ); ?></label>
            <select id="rigid_term_header_style" name="rigid_term_header_style">
                <option value="" selected="selected"><?php esc_html_e( "Normal", "rigid" ) ?></option>
                <option value="rigid_transparent_header"><?php esc_html_e( "Transparent - Light Scheme", "rigid" ) ?></option>
                <option value="rigid_transparent_header rigid-transparent-dark"><?php esc_html_e( "Transparent - Dark Scheme", "rigid" ) ?></option>
                <option value="rigid-overlay-header"><?php esc_html_e( "Overlay", "rigid" ) ?></option>
            </select>
        </div>

        <div class="form-field rigid-term-header-subtitle-wrap">
            <label for="rigid_term_header_subtitle"><?php esc_html_e( 'Category Subtitle', 'rigid' ); ?></label>
            <input type="text" class="large-text" value="" name="rigid_term_header_subtitle"
                   id="rigid_term_header_subtitle">
        </div>

        <div class="form-field rigid-term-header-alignment-wrap">
            <label for="rigid_term_header_alignment"><?php esc_html_e( 'Title alignment', 'rigid' ); ?></label>
            <select name="rigid_term_header_alignment">
                <option value="left_title" selected="selected"><?php esc_html_e( 'Left', 'rigid' ); ?></option>
                <option value="centered_title"><?php esc_html_e( 'Center', 'rigid' ); ?></option>
            </select>
        </div>
		<?php
	}
}

if ( ! function_exists( 'rigid_woocommerce_custom_cat_fields_edit' ) ) {
	function rigid_woocommerce_custom_cat_fields_edit( $term ) {

		$thumbnail_id     = absint( get_woocommerce_term_meta( $term->term_id, 'rigid_term_header_img_id', true ) );
		$header_style     = get_woocommerce_term_meta( $term->term_id, 'rigid_term_header_style', true );
		$subtitle         = get_woocommerce_term_meta( $term->term_id, 'rigid_term_header_subtitle', true );
		$header_alignment = get_woocommerce_term_meta( $term->term_id, 'rigid_term_header_alignment', true );

		$header_style_values = array(
			''                         => __( 'Normal', 'rigid' ),
			'rigid_transparent_header' => __( 'Transparent - Light Scheme', 'rigid' ),
			'rigid_transparent_header rigid-transparent-dark' => __('Transparent - Dark Scheme', 'rigid'),
			'rigid-overlay-header' => __( 'Overlay', 'rigid' )
		);

		$header_alignment_values = array(
			'left_title'     => __( 'Left', 'rigid' ),
			'centered_title' => __( 'Center', 'rigid' )
		);

		if ( $thumbnail_id ) {
			$image = wp_get_attachment_thumb_url( $thumbnail_id );
		} else {
			$image = wc_placeholder_img_src();
		}
		?>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php echo esc_html__( 'Title background image', 'rigid' ); ?></label>
            </th>
            <td>
                <div id="rigid_term_header_img" style="float: left; margin-right: 10px;"><img
                            src="<?php echo esc_url( $image ); ?>" width="60px" height="60px"/></div>
                <div style="line-height: 60px;">
                    <input type="hidden" id="rigid_term_header_img_id" name="rigid_term_header_img_id"
                           value="<?php echo esc_attr( $thumbnail_id ); ?>"/>
                    <button type="button"
                            class="rigid_term_header_img_upload_image_button button"><?php echo esc_html__( 'Upload/Add image', 'rigid' ); ?></button>
                    <button type="button"
                            class="rigid_term_header_img_remove_image_button button"><?php echo esc_html__( 'Remove image', 'rigid' ); ?></button>
                </div>
				<?php ob_start(); ?>
                <script type="text/javascript">

                    // Only show the "remove image" button when needed
                    if ('0' === jQuery('#rigid_term_header_img_id').val()) {
                        jQuery('.rigid_term_header_img_remove_image_button').hide();
                    }

                    // Uploading files
                    var rigid_term_header_img_file_frame;

                    jQuery(document).on('click', '.rigid_term_header_img_upload_image_button', function (event) {

                        event.preventDefault();

                        // If the media frame already exists, reopen it.
                        if (rigid_term_header_img_file_frame) {
                            rigid_term_header_img_file_frame.open();
                            return;
                        }

                        // Create the media frame.
                        rigid_term_header_img_file_frame = wp.media.frames.downloadable_file = wp.media({
                            title: '<?php echo esc_html__( "Choose an image", "rigid" ); ?>',
                            button: {
                                text: '<?php echo esc_html__( "Use image", "rigid" ); ?>'
                            },
                            multiple: false
                        });

                        // When an image is selected, run a callback.
                        rigid_term_header_img_file_frame.on('select', function () {
                            var attachment = rigid_term_header_img_file_frame.state().get('selection').first().toJSON();

                            jQuery('#rigid_term_header_img_id').val(attachment.id);
                            jQuery('#rigid_term_header_img').find('img').attr('src', attachment.sizes.thumbnail.url);
                            jQuery('.rigid_term_header_img_remove_image_button').show();
                        });

                        // Finally, open the modal.
                        rigid_term_header_img_file_frame.open();
                    });

                    jQuery(document).on('click', '.rigid_term_header_img_remove_image_button', function () {
                        jQuery('#rigid_term_header_img').find('img').attr('src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>');
                        jQuery('#rigid_term_header_img_id').val('');
                        jQuery('.rigid_term_header_img_remove_image_button').hide();
                        return false;
                    });

                </script>
				<?php $js_handle_header_img_on_cat_edit = ob_get_clean(); ?>
				<?php wp_add_inline_script( 'rigid-back', rigid_strip_script_tag_from_js_block( $js_handle_header_img_on_cat_edit ) ); ?>
                <div class="clear"></div>
            </td>
        </tr>

        <tr class="form-field">
            <th scope="row" valign="top"><label
                        for="rigid_term_header_style"><?php esc_html_e( 'Header Style', 'rigid' ); ?></label></th>
            <td>
                <div class="form-field rigid-term-header-style-wrap">
                    <select id="rigid_term_header_style" name="rigid_term_header_style">
						<?php foreach ( $header_style_values as $key => $value ): ?>
                            <option value="<?php echo esc_attr( $key ) ?>" <?php echo( $key == $header_style ? 'selected="selected"' : '' ) ?> ><?php echo esc_html( $value ) ?></option>
						<?php endforeach; ?>
                    </select>
                </div>
            </td>
        </tr>

        <tr class="form-field">
            <th scope="row" valign="top"><label
                        for="rigid_term_header_subtitle"><?php esc_html_e( 'Category Subtitle', 'rigid' ); ?></label>
            </th>
            <td>
                <div class="form-field rigid-term-header-subtitle-wrap">
                    <input type="text" class="large-text"
                           value="<?php echo( $subtitle ? esc_html( $subtitle ) : '' ) ?>"
                           name="rigid_term_header_subtitle"
                           id="rigid_term_header_subtitle">
                </div>
            </td>
        </tr>

        <tr class="form-field">
            <th scope="row" valign="top"><label
                        for="rigid_term_header_alignment"><?php esc_html_e( 'Title alignment', 'rigid' ); ?></label>
            </th>
            <td>
                <div class="form-field rigid-term-header-alignment-wrap">
                    <select name="rigid_term_header_alignment">
	                    <?php foreach ( $header_alignment_values as $key => $value ): ?>
                            <option value="<?php echo esc_attr( $key ) ?>" <?php echo( $key == $header_alignment ? 'selected="selected"' : '' ) ?> ><?php echo esc_html( $value ) ?></option>
	                    <?php endforeach; ?>
                    </select>
                </div>
            </td>
        </tr>
		<?php
	}
}

if ( ! function_exists( 'rigid_woocommerce_custom_cat_fields_save' ) ) {
	function rigid_woocommerce_custom_cat_fields_save( $term_id, $tt_id = '', $taxonomy = '' ) {


		if ( isset( $_POST['rigid_term_header_img_id'] ) && 'product_cat' === $taxonomy ) {
			update_woocommerce_term_meta( $term_id, 'rigid_term_header_img_id', absint( $_POST['rigid_term_header_img_id'] ) );
		}

		if ( isset( $_POST['rigid_term_header_style'] ) && 'product_cat' === $taxonomy ) {
			update_woocommerce_term_meta( $term_id, 'rigid_term_header_style', $_POST['rigid_term_header_style'] );
		}

		if ( isset( $_POST['rigid_term_header_subtitle'] ) && 'product_cat' === $taxonomy ) {
			update_woocommerce_term_meta( $term_id, 'rigid_term_header_subtitle', $_POST['rigid_term_header_subtitle'] );
		}

		if ( isset( $_POST['rigid_term_header_alignment'] ) && 'product_cat' === $taxonomy ) {
			update_woocommerce_term_meta( $term_id, 'rigid_term_header_alignment', $_POST['rigid_term_header_alignment'] );
		}
	}
}