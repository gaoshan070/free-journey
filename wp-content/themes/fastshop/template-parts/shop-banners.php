<?php
$enable_shop_banner = fastshop_get_option( 'enable_shop_banner' );
$woo_shop_banner    = fastshop_get_option( 'woo_shop_banner', '' );
$woo_shop_url       = fastshop_get_option( 'woo_shop_url', '#' );
$banner_class       = array( 'banner-shop slick-slider' );
$woo_shop_banner    = explode( ',', $woo_shop_banner );
?>
<?php if ( $enable_shop_banner == 1 ) : ?>
    <div class="<?php echo esc_attr( implode( ' ', $banner_class ) ); ?>"
         data-slick='{"slidesToShow": 1, "infinite": true, "autoplay": true, "arrows": false, "autoplaySpeed": 4000,"fade": true, "cssEase": "linear"}'>
		<?php foreach ( $woo_shop_banner as $value ) : ?>
			<?php $attachment = wp_get_attachment_image_src( $value, 'full' ); ?>
            <div class="item-slide">
                <a href="<?php echo esc_url( $woo_shop_url ); ?>" target="_blank">
                    <img style="width: 100%" src="<?php echo esc_url( $attachment[ 0 ] ); ?>"
                         width="<?php echo esc_attr( $attachment[ 1 ] ); ?>"
                         height="<?php echo esc_attr( $attachment[ 2 ] ); ?>" alt="<?php the_title(); ?>">
                </a>
            </div>
		<?php endforeach; ?>
    </div>
<?php endif; ?>
