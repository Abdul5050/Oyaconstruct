<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     2.2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wp_query;

if ( $wp_query->max_num_pages <= 1 ) {
	return;
}
?>
<div class="box box-common rigid-shop-pager<?php if(rigid_get_option('enable_shop_infinite')) echo ' rigid-infinite' ?>">

    <div class="rigid-page-load-status">
        <p class="infinite-scroll-request"><?php esc_html_e( 'Loading', 'rigid' ); ?>...</p>
        <p class="infinite-scroll-last"><?php esc_html_e( 'No more items available', 'rigid' ); ?></p>
    </div>

    <?php if(rigid_get_option('enable_shop_infinite') && rigid_get_option('use_load_more_on_shop')): ?>
        <div class="rigid-load-more-container">
            <button class="rigid-load-more button"><?php esc_html_e( 'Load More', 'rigid' ); ?></button>
        </div>
    <?php endif; ?>
    <?php rigid_pagination(); ?>
</div>