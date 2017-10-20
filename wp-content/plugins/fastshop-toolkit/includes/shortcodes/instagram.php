<?php

if ( !class_exists( 'Fastshop_Shortcode_Instagram' ) ) {

	class Fastshop_Shortcode_Instagram extends Fastshop_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'instagram';

		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();


		public static function generate_css( $atts )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'fastshop_instagram', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );

			$css = '';
			$css .= '.' . $atts[ 'instagram_custom_id' ] . '.fastshop-instagram .item {
                width: ' . $atts[ 'number_col' ] . '%;
			}';

			return $css;
		}


		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'fastshop_instagram', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );

			$css_class   = array( 'fastshop-instagram' );
			$css_class[] = $atts[ 'el_class' ];
			$css_class[] = $atts[ 'instagram_custom_id' ];

			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), '', $atts );
			}

			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php
				if ( intval( $atts[ 'user_name' ] ) === 0 ) {
					esc_html_e( 'No user ID specified.', 'fastshop' );
				}
				$transient_var = $atts[ 'user_name' ] . '_' . $atts[ 'items_limit' ];

				if ( false === ( $items = get_transient( $transient_var ) ) && !empty( $user_name ) && !empty( $token ) ) {
					$response = wp_remote_get( 'https://api.instagram.com/v1/users/' . esc_attr( $user_name ) . '/media/recent/?access_token=' . esc_attr( $token ) . '&count=' . esc_attr( $items_limit ) );
					if ( !is_wp_error( $response ) ) {
						$response_body = json_decode( $response[ 'body' ] );

						if ( $response_body->meta->code !== 200 ) {
							echo '<p>' . esc_html__( 'User ID and access token do not match. Please check again.', 'fastshop' ) . '</p>';
						}

						$items_as_objects = $response_body->data;
						$items            = array();

						foreach ( $items_as_objects as $item_object ) {
							$item[ 'link' ] = $item_object->link;
							$item[ 'src' ]  = $item_object->images->low_resolution->url;
							$items[]        = $item;
						}

						set_transient( $transient_var, $items, 60 * 60 );
					}
				}
				?>
				<?php if ( isset( $items ) && $items ): ?>
					<?php if ( $atts[ 'title' ] ) : ?>
                        <h4 class="widget-title"><?php echo esc_html( $atts[ 'title' ] ); ?></h4>
					<?php endif; ?>
                    <div class="nav-center instagram">
						<?php foreach ( $items as $item ): ?>
							<?php
							$size_add       = 's150x150';
							$item[ 'src' ]  = str_replace( 's320x320', $size_add, $item[ 'src' ] );
							?>
                            <div class="item">
                                <a target="_blank" href="<?php echo esc_url( $item[ 'link' ] ) ?>">
                                    <img class="img-responsive" src="<?php echo esc_url( $item[ 'src' ] ); ?>"
                                         alt="Instagram"/>
                                </a>
                            </div>
						<?php endforeach; ?>
                    </div>
				<?php endif; ?>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Fastshop_Shortcode_instagram', force_balance_tags( $html ), $atts, $content );
		}
	}
}