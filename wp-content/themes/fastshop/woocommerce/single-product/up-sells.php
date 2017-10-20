<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
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

$woo_upsell_ls_items = fastshop_get_option( 'fastshop_woo_upsell_ls_items', 3 );
$woo_upsell_lg_items = fastshop_get_option( 'fastshop_woo_upsell_lg_items', 3 );
$woo_upsell_md_items = fastshop_get_option( 'fastshop_woo_upsell_md_items', 3 );
$woo_upsell_sm_items = fastshop_get_option( 'fastshop_woo_upsell_sm_items', 2 );
$woo_upsell_xs_items = fastshop_get_option( 'fastshop_woo_upsell_xs_items', 1 );
$woo_upsell_ts_items = fastshop_get_option( 'fastshop_woo_upsell_ts_items', 1 );

$data_reponsive = array(
	'0'    => array(
		'items' => $woo_upsell_ts_items,
	),
	'480'  => array(
		'items' => $woo_upsell_xs_items,
	),
	'768'  => array(
		'items' => $woo_upsell_sm_items,
	),
	'992'  => array(
		'items' => $woo_upsell_md_items,
	),
	'1200' => array(
		'items' => $woo_upsell_lg_items,
	),
	'1500' => array(
		'items' => $woo_upsell_ls_items,
	),
);

$data_reponsive = json_encode( $data_reponsive );

$woo_upsell_sell_title = fastshop_get_option( 'fastshop_upsell_products_title', 'You may also like&hellip;' );

if ( $upsells ) : ?>
    <section class="up-sells upsells products product-grid">

        <h2 class="product-grid-title"><?php echo esc_html( $woo_upsell_sell_title ) ?></h2>
        <div class="owl-carousel owl-products nav2 top-right equal-container better-height" data-margin="30"
             data-nav="true" data-dots="false" data-loop="false"
             data-responsive='<?php echo esc_attr( $data_reponsive ); ?>'>
			<?php foreach ( $upsells as $upsell ) : ?>
                <div <?php post_class( $classes ) ?>>
					<?php
					$post_object = get_post( $upsell->get_id() );

					setup_postdata( $GLOBALS[ 'post' ] =& $post_object );

					wc_get_template_part( 'product-styles/content-product', $template_style ); ?>
                </div>
			<?php endforeach; ?>
        </div>
    </section>

<?php endif;

wp_reset_postdata();
