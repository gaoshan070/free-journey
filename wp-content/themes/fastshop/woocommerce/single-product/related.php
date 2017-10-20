<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
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
 * @version     4.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}
$classes                    = array();
$fastshop_woo_product_style = fastshop_get_option( 'fastshop_shop_product_style', 1 );

$classes[]      = 'product-item style-' . $fastshop_woo_product_style;
$template_style = 'style-' . $fastshop_woo_product_style;

$woo_related_ls_items = fastshop_get_option( 'fastshop_woo_related_ls_items', 3 );
$woo_related_lg_items = fastshop_get_option( 'fastshop_woo_related_lg_items', 3 );
$woo_related_md_items = fastshop_get_option( 'fastshop_woo_related_md_items', 3 );
$woo_related_sm_items = fastshop_get_option( 'fastshop_woo_related_sm_items', 2 );
$woo_related_xs_items = fastshop_get_option( 'fastshop_woo_related_xs_items', 1 );
$woo_related_ts_items = fastshop_get_option( 'fastshop_woo_related_ts_items', 1 );

$data_reponsive = array(
	'0'    => array(
		'items' => $woo_related_ts_items,
	),
	'480'  => array(
		'items' => $woo_related_xs_items,
	),
	'768'  => array(
		'items' => $woo_related_sm_items,
	),
	'992'  => array(
		'items' => $woo_related_md_items,
	),
	'1200' => array(
		'items' => $woo_related_lg_items,
	),
	'1500' => array(
		'items' => $woo_related_ls_items,
	),
);

$data_reponsive = json_encode( $data_reponsive );

$woo_related_title = fastshop_get_option( 'fastshop_related_products_title', 'Related Products' );

if ( $related_products ) : ?>
    <section class="related products product-grid">

        <h2 class="product-grid-title"><?php echo esc_html( $woo_related_title ) ?></h2>
        <div class="owl-carousel owl-products nav2 top-right equal-container better-height" data-margin="30"
             data-nav="true" data-dots="false"
             data-loop="false" data-responsive='<?php echo esc_attr( $data_reponsive ); ?>'>
			<?php foreach ( $related_products as $related_product ) : ?>
                <div <?php post_class( $classes ) ?>>
					<?php
					$post_object = get_post( $related_product->get_id() );

					setup_postdata( $GLOBALS[ 'post' ] =& $post_object );

					wc_get_template_part( 'product-styles/content-product', $template_style ); ?>
                </div>
			<?php endforeach; ?>
        </div>

    </section>

<?php endif;

wp_reset_postdata();
