<?php
/**
 *
 * Fastshop products
 *
 */
if ( !class_exists( 'Products_Widget' ) ) {
	class Products_Widget extends WP_Widget
	{
		function __construct()
		{
			$widget_ops = array(
				'classname'   => 'widget-products',
				'description' => 'Widget products.',
			);

			parent::__construct( 'widget_products', '1 - Fastshop Products', $widget_ops );
		}

		function widget( $args, $instance )
		{

			extract( $args );

			echo $before_widget;
			if ( !empty( $instance[ 'title' ] ) ) {
				echo $before_title . $instance[ 'title' ] . $after_title;
			}
			$product_item_class   = array( 'product-item' );
			$product_item_class[] = 'style-9';
			$product_item_class[] = 'rows-space-0';
			$product_item_class[] = 'col-bg-12';
			$product_item_class[] = 'col-lg-12';
			$product_item_class[] = 'col-md-12';
			$product_item_class[] = 'col-sm-12';
			$product_item_class[] = 'col-xs-12';
			$product_item_class[] = 'col-ts-12';

			add_filter( 'fastshop_shop_pruduct_thumb_width', create_function( '', 'return ' . 110 . ';' ) );
			add_filter( 'fastshop_shop_pruduct_thumb_height', create_function( '', 'return ' . 110 . ';' ) );
			$data_products     = new Fastshop_Shortcode;
			$instance[ 'ids' ] = $instance[ 'ids' ] ? implode( ',', $instance[ 'ids' ] ) : '';
			$products          = $data_products->getProducts( $instance );
			?>
            <div class="fastshop-products style-9">
				<?php if ( $products->have_posts() ): ?>
                    <ul class="product-list-grid row auto-clear equal-container better-height">
						<?php while ( $products->have_posts() ) : $products->the_post(); ?>
                            <li <?php post_class( $product_item_class ); ?>>
								<?php wc_get_template_part( 'product-styles/content-product-style', '9' ); ?>
                            </li>
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
                    </ul>
                    <div class="more-items">
                        <a href="<?php echo get_page_link( get_page_by_title( 'shop' )->ID ); ?>"
                           class=" cp-button button-block button-larger">
							<?php echo esc_html__( 'More items', 'fastshop-toolkit' ); ?>
                        </a>
                    </div>
				<?php else: ?>
                    <p>
                        <strong><?php esc_html_e( 'No Product', 'fastshop-toolkit' ); ?></strong>
                    </p>
				<?php endif; ?>
            </div>
			<?php
			echo $after_widget;

		}

		function update( $new_instance, $old_instance )
		{

			$instance               = $old_instance;
			$instance[ 'title' ]    = $new_instance[ 'title' ];
			$instance[ 'ids' ]      = $new_instance[ 'ids' ];
			$instance[ 'per_page' ] = $new_instance[ 'per_page' ];
			$instance[ 'order' ]    = $new_instance[ 'order' ];
			$instance[ 'orderby' ]  = $new_instance[ 'orderby' ];
			$instance[ 'target' ]   = $new_instance[ 'target' ];
			$instance[ 'taxonomy' ] = $new_instance[ 'taxonomy' ];

			return $instance;

		}

		function form( $instance )
		{
			//
			// set defaults
			// -------------------------------------------------
			$instance = wp_parse_args(
				$instance,
				array(
					'title'    => '',
					'ids'      => '',
					'per_page' => '4',
					'order'    => 'DESC',
					'orderby'  => 'date',
					'target'   => 'recent-product',
					'taxonomy' => '',
				)
			);

			$title_value = $instance[ 'title' ];
			$title_field = array(
				'id'    => $this->get_field_name( 'title' ),
				'name'  => $this->get_field_name( 'title' ),
				'type'  => 'text',
				'title' => esc_html__( 'Title', 'fastshop' ),
			);

			echo cs_add_element( $title_field, $title_value );

			$taxonomy_value = $instance[ 'taxonomy' ];
			$taxonomy_field = array(
				'id'         => $this->get_field_name( 'taxonomy' ),
				'name'       => $this->get_field_name( 'taxonomy' ),
				'type'       => 'select',
				'options'    => 'categories',
				'query_args' => array(
					'type'     => 'product',
					'taxonomy' => 'product_cat',
					'orderby'  => 'post_date',
					'order'    => 'DESC',
				),
				'default'    => '',
				'class'      => 'chosen',
				'attributes' => array(
					'multiple' => 'multiple',
					'style'    => 'width: 100%;',
				),
				'dependency' => array( 'target', '!=', 'products' ),
				'title'      => esc_html__( 'Choose Type Post', 'fastshop' ),
			);

			echo cs_add_element( $taxonomy_field, $taxonomy_value );

			$target_value = $instance[ 'target' ];
			$target_field = array(
				'id'         => $this->get_field_name( 'target' ),
				'name'       => $this->get_field_name( 'target' ),
				'type'       => 'select',
				'options'    => array(
					'best-selling'      => esc_html__( 'Best Selling Products', 'fastshop' ),
					'top-rated'         => esc_html__( 'Top Rated Products', 'fastshop' ),
					'recent-product'    => esc_html__( 'Recent Products', 'fastshop' ),
					'product-category'  => esc_html__( 'Product Category', 'fastshop' ),
					'products'          => esc_html__( 'Products', 'fastshop' ),
					'featured_products' => esc_html__( 'Featured Products', 'fastshop' ),
					'on_sale'           => esc_html__( 'On Sale', 'fastshop' ),
					'on_new'            => esc_html__( 'On New', 'fastshop' ),
				),
				'attributes' => array(
					'data-depend-id' => 'target',
					'style'          => 'width: 100%;',
				),
				'title'      => esc_html__( 'Choose Target', 'fastshop' ),
			);

			echo cs_add_element( $target_field, $target_value );

			$orderby_value = $instance[ 'orderby' ];
			$orderby_field = array(
				'id'         => $this->get_field_name( 'orderby' ),
				'name'       => $this->get_field_name( 'orderby' ),
				'type'       => 'select',
				'options'    => array(
					'date'          => esc_html__( 'Date', 'fastshop' ),
					'ID'            => esc_html__( 'ID', 'fastshop' ),
					'author'        => esc_html__( 'Author', 'fastshop' ),
					'title'         => esc_html__( 'Title', 'fastshop' ),
					'modified'      => esc_html__( 'Modified', 'fastshop' ),
					'rand'          => esc_html__( 'Random', 'fastshop' ),
					'comment_count' => esc_html__( 'Comment count', 'fastshop' ),
					'menu_order'    => esc_html__( 'Menu order', 'fastshop' ),
					'_sale_price'   => esc_html__( 'Sale price', 'fastshop' ),
				),
				'attributes' => array(
					'style' => 'width: 100%;',
				),
				'dependency' => array( 'target', '!=', 'products' ),
				'title'      => esc_html__( 'Order By', 'fastshop' ),
			);

			echo cs_add_element( $orderby_field, $orderby_value );

			$order_value = $instance[ 'order' ];
			$order_field = array(
				'id'         => $this->get_field_name( 'order' ),
				'name'       => $this->get_field_name( 'order' ),
				'type'       => 'select',
				'options'    => array(
					'ASC'  => esc_html__( 'ASC', 'fastshop' ),
					'DESC' => esc_html__( 'DESC', 'fastshop' ),
				),
				'attributes' => array(
					'style' => 'width: 100%;',
				),
				'dependency' => array( 'target', '!=', 'products' ),
				'title'      => esc_html__( 'Order', 'fastshop' ),
			);

			echo cs_add_element( $order_field, $order_value );

			$per_page_value = $instance[ 'per_page' ];
			$per_page_field = array(
				'id'         => $this->get_field_name( 'per_page' ),
				'name'       => $this->get_field_name( 'per_page' ),
				'type'       => 'number',
				'dependency' => array( 'target', '!=', 'products' ),
				'title'      => esc_html__( 'Product per page', 'fastshop' ),
			);

			echo cs_add_element( $per_page_field, $per_page_value );

			$ids_value = $instance[ 'ids' ];
			$ids_field = array(
				'id'         => $this->get_field_name( 'ids' ),
				'name'       => $this->get_field_name( 'ids' ),
				'type'       => 'select',
				'options'    => 'posts',
				'query_args' => array(
					'post_type'      => 'product',
					'orderby'        => 'post_date',
					'order'          => 'DESC',
					'posts_per_page' => -1,
				),
				'default'    => '',
				'class'      => 'chosen',
				'attributes' => array(
					'multiple' => 'multiple',
					'style'    => 'width: 100%;',
				),
				'dependency' => array( 'target', '==', 'products' ),
				'title'      => esc_html__( 'Choose Type Post', 'fastshop' ),
			);

			echo cs_add_element( $ids_field, $ids_value );
		}
	}
}

if ( !function_exists( 'Products_Widget_init' ) ) {
	function Products_Widget_init()
	{
		register_widget( 'Products_Widget' );
	}

	add_action( 'widgets_init', 'Products_Widget_init', 2 );
}