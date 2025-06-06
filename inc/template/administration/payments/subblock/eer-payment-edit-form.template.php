<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Payment_Edit_Form
{

	public function __construct()
	{
		//add_action('eer_payment_form_input', [get_called_class(), 'input_event_id']);
		add_action('eer_payment_form_input', [get_called_class(), 'input_payment_type']);
		add_action('eer_payment_form_input', [get_called_class(), 'input_payment']);
		add_action('eer_payment_form_input', [get_called_class(), 'input_note']);
		add_action('eer_payment_form_input', [get_called_class(), 'input_email_confirmation']);
		add_action('eer_payment_form_submit', [get_called_class(), 'input_submit']);
	}


	public function print_form()
	{
		?>
		<div id="eer-edit-box" class="eer-edit-box">
			<span class="close"><span class="dashicons dashicons-no-alt"></span></span>
			<form id="payments-form">
				<table>
					<?php
					do_action('eer_payment_form_input');
					do_action('eer_payment_form_submit');
					?>
				</table>
			</form>
		</div>
		<?php
	}

	public static function input_payment()
	{
		?>
		<tr class="payment">
			<th><?php _e('Payment', 'easy-event-registration') ?></th>
			<td>
				<input required type="number" name="payment">
			</td>
		</tr>
		<?php
	}


	public static function input_payment_type()
	{
		?>
		<tr>
			<th><?php _e('Payment type', 'easy-event-registration'); ?></th>
			<td>
				<select required name="payment_type">
					<option value=""><?php _e('- select -', 'easy-event-registration'); ?></option>
					<option value="paid"><?php _e('Payment', 'easy-event-registration') ?></option>
					<option value="not_paying"><?php _e('Not paying this wave', 'easy-event-registration') ?></option>
				</select>
			</td>
		</tr>
		<?php
	}


	public static function input_note()
	{
		?>
		<tr>
			<th><?php _e('Note', 'easy-event-registration') ?></th>
			<td><textarea name="note"></textarea></td>
		</tr>
		<?php
	}


	public static function input_email_confirmation()
	{
		?>
		<tr>
			<th><?php _e('Send email confirmation', 'easy-event-registration') ?></th>
			<td><input type="checkbox" name="eer_payment_email_confirmation" checked></td>
		</tr>
		<?php
	}


	public static function input_submit()
	{
		?>
		<tr>
			<th></th>
			<td>
				<input type="hidden" name="order_id">
				<input type="submit" name="eer_payment_submit" value="<?php _e('Save Payment', 'easy-event-registration'); ?>">
			</td>
		</tr>
		<?php
	}

}
