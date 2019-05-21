<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/** @var WC_Product $product */
global $post, $product;
?>
<div class="tagcloud product_meta">
    <?php do_action( 'woocommerce_product_meta_start' ); ?>
    <?php
        $categories = wc_get_product_category_list( $product->get_id() );
        $size_categories = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
        $tags = wc_get_product_tag_list( $product->get_id());
        $size_tags = sizeof( get_the_terms( $post->ID, 'product_tag' ) );

        if($categories) echo '<span class="posted_in">'._n('Category:', 'Categories:', $size_categories, 'rigid').'</span>'.$categories;

        if($tags) echo '<span class="tagged_as">'._n('Tag:', 'Tags:', $size_tags, 'rigid').'</span>'.$tags;
    ?>
	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'rigid' ); ?> <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'rigid' ); ?></span></span>

	<?php endif; ?>
    <?php do_action( 'woocommerce_product_meta_end' ); ?>
</div>