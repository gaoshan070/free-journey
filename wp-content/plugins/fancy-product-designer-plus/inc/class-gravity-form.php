<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if(!class_exists('FPD_Plus_Gravity_Form')) {

	class FPD_Plus_Gravity_Form {

		private $show_order_viewer = false;

		public function __construct() {

			add_shortcode( 'fpd_gf', array( &$this, 'gf_shortcode') );

			//Entry Detail
			add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_styles_scripts' ) );
			add_filter( 'gform_field_content', array(&$this, 'gf_hide_fpd_order'), 10, 3 );
			add_action( 'gform_entry_detail', array(&$this, 'gf_add_order_viewer'), 10, 2 );

		}

		public function gf_shortcode( ) {

			ob_start();
			echo  do_shortcode( '[fpd]' );
			?>
			<script type="text/javascript">

				jQuery(document).ready(function() {

					var $gfForm = jQuery('.gfield.fpd-order').parents('form:first');

					if($gfForm.length > 0) {

						$gfForm.on('click', ':submit', function(evt) {

							evt.preventDefault();

							if(!fpdProductCreated) { return false; }

							var order = fancyProductDesigner.getOrder({
									customizationRequired: <?php echo fpd_get_option('fpd_customization_required'); ?>
								});

							if(order.product != false) {

								$gfForm.find('.fpd-order input').val(JSON.stringify(order));
								$gfForm.submit();
							}

						});

					}

				});

			</script>
			<?php
			$output = ob_get_contents();
			ob_end_clean();

			return $output;

		}

		public function enqueue_styles_scripts( $hook ) {

			//add necessary styles and scripts into entry detail
			if( isset($_GET['page']) && $_GET['page'] === 'gf_entries' && isset($_GET['view']) ) {

				wp_enqueue_style( 'jquery-fpd' );
				wp_enqueue_style( 'fpd-admin-icon-font' );
				wp_enqueue_style( 'radykal-admin' );
				wp_enqueue_style( 'fpd-admin' );

				wp_enqueue_script( 'fpd-order-viewer');

			}

		}

		public function gf_hide_fpd_order( $content, $field, $value ) {

			//hide fpd-order row and create JS variable with fpd order data
			if( isset($_GET['page']) && $_GET['page'] == 'gf_entries' && strpos($field->cssClass, 'fpd-order') !== false && !empty($value) ) {
				$content = '<tr class="fpd-hidden"><td><script> var fpdOrder = '.$value.'</script></td></tr>';
				$this->show_order_viewer = true;
			}

			return $content;

		}

		public function gf_add_order_viewer( $form, $lead ) {

			if( $this->show_order_viewer ):
			?>
			<div class="fpd-panel">
				<h3><?php _e('FPD - Order Viewer', 'radykal'); ?></h3>
				<?php include( FPD_PLUGIN_ADMIN_DIR.'/views/html-order-viewer.php' ); ?>
			</div>
			<script type="text/javascript">

				jQuery(document).ready(function() {

					if(typeof fpdOrder !== 'undefined') {
						fpdLoadOrder(fpdOrder.product, fpdOrder.bulkVariations);
					}

				});

			</script>
			<?php
			endif;

		}

	}

}

new FPD_Plus_Gravity_Form();

?>