<?php
/*
Plugin Name: Fancy Product Designer Plus
Plugin URI: http://fancyproductdesigner.com/
Description: The Plus Add-On extends the Fancy Product Designer with new modules and helpful tools.
Version: 1.1.2
Author: Rafael Dery | radykal.de
Author URI: http://fancyproductdesigner.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if( !class_exists('Fancy_Product_Designer_Plus') ) {

	class Fancy_Product_Designer_Plus {

		const VERSION = '1.1.2';
		const LOCAL = false;

		public function __construct() {

			add_action( 'plugins_loaded', array( &$this,'plugins_loaded' ) );

		}

		public function plugins_loaded() {

			define( 'FPD_CONSTRAINT', class_exists('Fancy_Product_Designer') && version_compare(Fancy_Product_Designer::VERSION, '3.3.0', '>=') );

			if(FPD_CONSTRAINT) { //FPD is enabled

				require_once(__DIR__.'/inc/class-scripts-styles.php');
				require_once(__DIR__.'/inc/class-frontend.php');
				require_once(__DIR__.'/inc/class-gravity-form.php');
				require_once(__DIR__.'/admin/class-admin.php');

			}
			else {
				add_action( 'admin_notices',  array( &$this, 'display_admin_notices' ) );
			}

		}

		public function display_admin_notices() {

			?>
			<div class="error">
		        <p><?php _e( 'Fancy Product Designer Plus requires <a href="https://codecanyon.net/item/fancy-product-designer-woocommerce-plugin/6318393?ref=radykal" target="_blank">Fancy Product Designer Plugin for WordPress/WooCommerce V3.3.0</a>. Please install the plugin, otherwise you can not use the Plus Add-On.', 'radykal' ); ?></p>
		    </div>
		    <?php

		}

	}
}

new Fancy_Product_Designer_Plus();

?>