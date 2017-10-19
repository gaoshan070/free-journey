<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if( !class_exists('FPD_Admin_Plus_Product_Builder') ) {

	class FPD_Admin_Plus_Product_Builder {

		public function __construct() {

			add_action( 'fpd_product_builder_text_options_end', array( &$this, 'add_text_options' ));

		}

		public function add_text_options() {

			?>
			<tr class="fpd-text-plus-opts">
				<th>
					<?php _e('Text/Number', 'radykal'); ?>
					<i class="fpd-admin-icon-info-outline fpd-admin-tooltip" title="<?php _e('Enable the text element as a name or number placeholder to use it with the Names&Numbers module.', 'radykal'); ?>"></i>
				</th>
				<td>
					<select name="textNumberPlaceholder" data-toggle=".fpd-number-placeholder-opts=number">
						<option value="none"><?php _e('None', 'radykal'); ?></option>
						<option value="text"><?php _e('Text', 'radykal'); ?></option>
						<option value="number"><?php _e('Number', 'radykal'); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th>
					<?php _e('Number Range', 'radykal'); ?>
				</th>
				<td>
					<label style="margin-right: 10px;">
						<?php _e('Min:', 'radykal'); ?>
						<input type="number" step="1" name="numberPlaceholderMin" class="fpd-number-placeholder-opts fpd-only-numbers" />
					</label>
					<label>
						<?php _e('Max:', 'radykal'); ?>
						<input type="number" step="1" name="numberPlaceholderMax" class="fpd-number-placeholder-opts fpd-only-numbers" />
					</label>
				</td>
			</tr>
			<?php

		}

	}
}

return new FPD_Admin_Plus_Product_Builder();

?>