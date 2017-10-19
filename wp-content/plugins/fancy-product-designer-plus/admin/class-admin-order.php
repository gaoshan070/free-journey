<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if(!class_exists('FPD_Plus_Admin_Order')) {

	class FPD_Plus_Admin_Order {

		public function __construct() {

			add_action( 'fpd_order_viewer_tabs_end', array(&$this, 'add_order_viewer_tab') );
			add_action( 'fpd_order_viewer_tabs_content_end', array(&$this, 'add_order_viewer_tab_content') );

			//wc order
			add_filter( 'fpd_ajax_load_order_data', array(&$this, 'ajax_load_order_data'), 20, 4);

			//shortcode order
			add_filter( 'fpd_shortcode_order_data', array(&$this, 'shortcode_order_data') );

		}

		public function add_order_viewer_tab() {

			?>
			<a href="bulk-add-variations"><?php _e('Bulk-Add Variations', 'radykal'); ?></a>
			<?php

		}

		public function add_order_viewer_tab_content() {

			?>
			<div data-id="bulk-add-variations">
				<div>
					<p class="description"><?php _e('The customer ordered following variations:', 'radykal'); ?></p>
					<table class="form-table" id="bulk-variations-table">
						<thead>
							<tr>
								<th><?php _e('Variation', 'radykal'); ?></th>
								<th style="width: 100px;"><?php _e('Quantity', 'radykal'); ?></th>
							</tr>
						</thead>
						<tbody id="bulk-variations-tbody">
						</tbody>
					</table>
				</div>
			</div>
			<?php

		}

		public function ajax_load_order_data( $data, $fpd_data, $order_id, $item_id) {

			if( isset($fpd_data['fpd_bulk_variations_order']) && !empty($fpd_data['fpd_bulk_variations_order']) )
				$data['bulk_variations'] = $fpd_data['fpd_bulk_variations_order'];

			return $data;

		}

		public function shortcode_order_data( $data ) {

			if(isset($_POST['bulkVariations']) ) {

				global $wpdb;
				$bulk_variations_exists = $wpdb->get_var( "SHOW COLUMNS FROM ".FPD_ORDERS_TABLE." LIKE 'bulk_variations'" );
				if( empty($bulk_variations_exists) ) {
					$wpdb->query( "ALTER TABLE ".FPD_ORDERS_TABLE." ADD COLUMN bulk_variations LONGTEXT NOT NULL" );
				}

				$data['data']['bulk_Variations'] = $_POST['bulkVariations'];
				array_push($data['format'], '%s');

			}

			return $data;

		}

	}

}

new FPD_Plus_Admin_Order();

?>