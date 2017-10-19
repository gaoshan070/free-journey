<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if( !class_exists('FPD_Plus_Settings') ) {

	class FPD_Plus_Settings {

		public function __construct() {

			//Global Settings
			add_filter( 'fpd_settings_tabs', array(&$this, 'add_plus_tab') );
			add_filter( 'fpd_settings_blocks', array(&$this, 'add_plus_tab_blocks') );
			add_action( 'fpd_block_options_end', array(&$this, 'add_block_options') );

			//IPS
			add_action( 'fpd_ips_tabs_end', array(&$this, 'add_ips_tab') );
			add_action( 'fpd_ips_tabs_content_end', array(&$this, 'add_ips_content_tab') );

			//Product Options
			add_action( 'fpd_product_options_form_end', array(&$this, 'add_product_options') );

		}

		public function add_plus_tab( $tabs ) {

			$tabs['plus'] = __('Plus', 'radykal');

			return $tabs;

		}

		public function add_plus_tab_blocks( $blocks ) {

			$blocks['plus'] = array(
				'plus-modules' => __('Modules', 'radykal'),
				'plus-tools' => __('Tools', 'radykal'),
			);

			return $blocks;

		}

		public function add_block_options() {

			$plus_options = self::get_options();
			FPD_Settings::$radykal_settings->add_block_options( 'plus-modules', $plus_options['modules']);
			FPD_Settings::$radykal_settings->add_block_options( 'plus-tools', $plus_options['tools']);

		}

		public function add_ips_tab() {
			?>
			<a href="plus-options"><?php _e('Plus', 'radykal'); ?></a>
			<?php
		}

		public function add_ips_content_tab() {

			?>

			<div data-id="plus-options">
				<h4><?php _e('Modules', 'radykal'); ?></h4>
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row"><label><?php _e('Names & Numbers Dropdown', 'radykal'); ?></label></th>
							<td><input type="text" name="plus_names_numbers_dropdown" placeholder="<?php echo fpd_get_option('fpd_plus_names_numbers_dropdown'); ?>" value=""></td>
						</tr>
						<tr valign="top">
							<th scope="row"><label><?php _e('Names & Numbers Entry Price', 'radykal'); ?></label></th>
							<td><input type="number" min="0" name="plus_names_numbers_entry_price" placeholder="<?php echo fpd_get_option('fpd_plus_names_numbers_entry_price'); ?>" value=""></td>
						</tr>
					</tbody>
				</table>
				<h4><?php _e('Tools', 'radykal'); ?></h4>
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row"><label><?php _e('Color Selection Placement', 'radykal'); ?></label></th>
							<td>
								<select name="plus_color_selection_placement" class="radykal-select2" style="width: 100%;">
								<option value=""><?php _e( 'Use Option From Main Settings', 'radykal' ); ?></option>
									<?php
										//get all created categories
										$ui_layouts = self::get_color_selection_placements();
										foreach($ui_layouts as $key => $value) {
											echo '<option value="'.$key.'">'.$value.'</option>';
										}
									?>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label><?php _e('Bulk-Add Form Placement', 'radykal'); ?></label></th>
							<td>
								<select name="plus_bulk_add_form_placement" class="radykal-select2" style="width: 100%;">
								<option value=""><?php _e( 'Use Option From Main Settings', 'radykal' ); ?></option>
									<?php
										//get all created categories
										$ui_layouts = self::get_bulk_add_form_placements();
										foreach($ui_layouts as $key => $value) {
											echo '<option value="'.$key.'">'.$value.'</option>';
										}
									?>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label><?php _e('Bulk-Add Variations', 'radykal'); ?></label></th>
							<td><input type="text" name="plus_bulk_add_variations_written" placeholder="<?php echo fpd_get_option('fpd_plus_bulk_add_variations_written'); ?>" value="" class="widefat"></td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php

		}

		public function add_product_options() {

			radykal_output_option_item( array(
					'id' => 'main_element',
					'title' => 'Main Element',
					'type' => 'text',
					'class' => 'large-text',
					'placeholder' => __('Enter title of the main element', 'radykal'),
					'description' => __('The defintion of the main element is required to use the Color Selection feature.', 'radykal')
				)
			);

		}

		public static function get_options() {

			return apply_filters('fpd_plus_settings', array(

				'modules' => array(

					array(
						'title' 		=> __( 'Names & Numbers Dropdown', 'radykal' ),
						'description' 	=> __( 'Enter some attributes by "|" separating values. These attributes will be used in the Names&Numbers module.', 'radykal' ),
						'id' 			=> 'fpd_plus_names_numbers_dropdown',
						'css' 			=> 'width:500px;',
						'default'		=> '',
						'type' 			=> 'text'
					),

					array(
						'title' 		=> __( 'Names & Numbers Entry Price', 'radykal' ),
						'description' 	=> __( 'The additional price for every entry in the Names&Numbers module.', 'radykal' ),
						'id' 			=> 'fpd_plus_names_numbers_entry_price',
						'css' 			=> 'width:70px;',
						'default'		=> 0,
						'type' 			=> 'number'
					),


				), //modules

				'tools' => array(

					array(
						'title' 		=> __( 'Color Selection Placement', 'radykal' ),
						'description' 	=> __( 'You need to define the main element in the options of a Fancy Product to use the Color Selection.', 'radykal' ),
						'id' 			=> 'fpd_plus_color_selection_placement',
						'class'			=> 'radykal-select2',
						'css' 			=> 'width: 300px;',
						'default'		=> '',
						'type' 			=> 'select',
						'options' 		=> self::get_color_selection_placements()
					),

					array(
						'title' 		=> __( 'Color Selection Tooltip', 'radykal' ),
						'description' 	=> __( 'Enables tooltips to display the custom hex name.', 'radykal' ),
						'id' 			=> 'fpd_plus_color_selection_tooltip',
						'default'		=> 'no',
						'type' 			=> 'radio',
						'options' 		=> array(
							'yes'	 => __( 'Yes', 'radykal' ),
							'no'	 => __( 'No', 'radykal' ),
						)
					),

					array(
						'title' 		=> __( 'Bulk-Add Form Placement', 'radykal' ),
						'id' 			=> 'fpd_plus_bulk_add_form_placement',
						'class'			=> 'radykal-select2',
						'css' 			=> 'width: 300px;',
						'default'		=> '',
						'type' 			=> 'select',
						'options' 		=> self::get_bulk_add_form_placements()
					),

					array(
						'title' 		=> __( 'Bulk-Add Variations', 'radykal' ),
						'description' 	=> __( 'You can define variations like that: Size=M|L;Colors=Blue|Red.', 'radykal' ),
						'id' 			=> 'fpd_plus_bulk_add_variations_written',
						'css' 			=> 'width: 100%;',
						'default'		=> '',
						'type' 			=> 'text',
					),

				),//tools

			));
		}

		public static function get_color_selection_placements() {

			$options = array(
				'none' => __( 'None', 'radykal' ),
				'inside-tl'	 => __( 'Inside Top/Left', 'radykal' ),
				'inside-tc'	 => __( 'Inside Top/Center', 'radykal' ),
				'inside-tr'	 => __( 'Inside Top/Right', 'radykal' ),
				'inside-bl'	 => __( 'Inside Bottom/Left', 'radykal' ),
				'inside-bc'	 => __( 'Inside Bottom/Center', 'radykal' ),
				'inside-br'	 => __( 'Inside Bottom/Right', 'radykal' ),
				'shortcode' => __( 'Via Shortcode [fpd_cs]', 'radykal' )
			);

			if( function_exists('get_woocommerce_currency') ) {
				$options['after-short-desc'] = __( 'After Short Description (WooCommerce)', 'radykal' );
			}

			return $options;

		}

		public static function get_bulk_add_form_placements() {

			$options = array(
				'none' => __( 'None', 'radykal' ),
				'after-short-desc'	 => __( 'After Short Description (WooCommerce)', 'radykal' ),
				'shortcode' => __( 'Via Shortcode [fpd_bulk_add_form]', 'radykal' ),
			);

			if( function_exists('get_woocommerce_currency') ) {
				$options['after-short-desc'] = __( 'After Short Description (WooCommerce)', 'radykal' );
			}

			return $options;

		}

		public static function get_all_product_attributes() {

			$attribute_taxonomies = wc_get_attribute_taxonomies();

		}
	}
}

new FPD_Plus_Settings();

?>