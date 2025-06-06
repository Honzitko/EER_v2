<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Order
{

	const MENU_SLUG = 'eer_admin_order';


	public static function print_content()
	{
		$template_order_form = new EER_Template_Order_Edit_Form();
		$subblock_orders_table = new EER_Subblock_Order_Table();

		$selected_event = apply_filters('eer_all_events_select_get', []);

		$user_can_edit = current_user_can('eer_order_edit');

		if ($user_can_edit && isset($_POST['eer_order_edit_submit'])) {
			$worker_order = new EER_Worker_Order();
			$worker_order->update_order($_POST);
		}

		ob_start();
		?>
		<div class="wrap tabbable boxed parentTabs eer-settings">
			<h1 class="wp-heading-inline"><?php _e('Orders', 'easy-event-registration'); ?></h1>
			<?php
			do_action('eer_all_events_select_print', $selected_event);

			if ($user_can_edit) {
				$template_order_form->print_form($selected_event);
			}

			$subblock_orders_table->print_block($selected_event);
			?>
		</div><!-- #tab_container-->
		<?php
		echo ob_get_clean();
	}

}
