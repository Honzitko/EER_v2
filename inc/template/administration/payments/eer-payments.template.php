<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Payments
{

	const MENU_SLUG = 'eer_admin_sub_page_payments';


	public static function print_content()
	{
		$template_payment_form = new EER_Template_Payment_Edit_Form();
		$template_payment_table = new EER_Subblock_Template_Payment_Table();

		$selected_event = apply_filters('eer_all_events_select_get', []);

		$user_can_edit = current_user_can('eer_payment_edit');
		?>
		<div class="wrap eer-settings">
			<?php do_action('eer_all_events_select_print', $selected_event); ?>
			<h1 class="wp-heading-inline"><?php _e('Payments', 'easy-event-registration'); ?></h1>

			<?php

			if ($user_can_edit) {
				$template_payment_form->print_form();
			}
			$template_payment_table->print_table($selected_event);
			?>
		</div>

		<?php
	}

}
