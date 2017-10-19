<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if( !class_exists('FPD_Pricing_Settings') ) {

	class FPD_Pricing_Settings {

		public function __construct() {

			//Global Settings
			add_filter( 'fpd_general_settings', array( &$this,'register_settings' ) );
			add_action( 'radykal_save_options', array( &$this, 'options_saved') );

			//IPS
			add_action( 'fpd_ips_general_tbody_end', array( &$this,'ips_general_pricing_settings' ) );

		}

		public function register_settings( $settings ) {

			array_push($settings['general-product-designer'], array(
					'title' => __( 'Pricing Groups', 'radykal' ),
					'description' => __('Select pricing groups that will be used for all product designers.', 'radykal'),
					'id' 		=> 'fpd_pricing_rules',
					'css' 		=> 'width: 100%;',
					'default'	=> '',
					'type' 		=> 'multiselect',
					'class'		=> 'radykal-select2',
					'options'   => self::get_pricing_group_names()
				)
			);

			array_push($settings['purchase-codes'], array(
					'title' 	=> __( 'Pricing Add-on', 'radykal' ),
					'description' 		=> __( 'Enter here the purchase code of the Pricing add-on.', 'radykal' ),
					'id' 		=> 'fpd_envato_purchase_code_pricing_addon',
					'default'	=> '',
					'type' 		=> 'text',
					'css' 		=> 'width:500px;',
				)
			);

			return $settings;

		}

		public function options_saved( $tab ) {

			if( $tab == 'general') {

				if( is_multisite() && isset($_POST['fpd_envato_purchase_code_pricing_addon']) ) {

					if( empty($_POST['fpd_envato_purchase_code_pricing_addon']) )
						delete_site_option( 'fpd_envato_purchase_code_pricing_addon' );
					else
						update_site_option( 'fpd_envato_purchase_code_pricing_addon', $_POST['fpd_envato_purchase_code_pricing_addon']);
				}

			}

		}

		public static function get_pricing_group_names() {

			$names = array();
			$pr_groups = get_option( 'fpd_pr_groups', array() );

			if( !is_array($pr_groups) )
				$pr_groups = json_decode(fpd_strip_multi_slahes($pr_groups), true);

			foreach($pr_groups as $pr_group) {
				$names[sanitize_key($pr_group['name'])] = $pr_group['name'];
			}

			return $names;

		}

		public function ips_general_pricing_settings() {

			?>
			<tr valign="top">
				<th scope="row"><label><?php _e('Pricing Rules', 'radykal'); ?></label></th>
				<td>
					<select class="radykal-select2" name="pricing_rules[]" multiple data-placeholder="<?php _e('Global Pricing Rules', 'radykal'); ?>" style="width: 100%;">
						<?php
							//get all created categories
							$pr_groups = self::get_pricing_group_names();
							foreach($pr_groups as $key => $pr_group) {
								echo '<option value="'.$key.'">'.$pr_group.'</option>';
							}
						?>
					</select>
				</td>
			</tr>
			<?php
		}

	}
}

new FPD_Pricing_Settings();

?>