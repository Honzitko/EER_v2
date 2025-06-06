<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Order_Edit_Form
{

	public function __construct()
	{
		add_action('eer_order_form_input', [get_called_class(), 'input_phone'], 10);
		add_action('eer_order_form_input', [get_called_class(), 'input_country'], 10);
		add_action('eer_order_form_input', [get_called_class(), 'input_tshirt'], 10);
		add_action('eer_order_form_input', [get_called_class(), 'input_food'], 10);
		add_action('eer_order_form_input', [get_called_class(), 'input_hosting'], 10);
		add_action('eer_order_form_input', [get_called_class(), 'input_offer_hosting'], 10);
		add_action('eer_order_form_input', [get_called_class(), 'input_custom_checkbox'], 11);
		add_action('eer_order_form_input', [get_called_class(), 'input_custom_area'], 11);

		add_action('eer_order_form_submit', [get_called_class(), 'input_submit']);
	}


	public function print_form($event_id)
	{
		$event_data = EER()->event->get_event_data($event_id);
		?>
		<div class="eer-edit-box-wrapper"></div>
		<div id="eer-edit-box" class="eer-edit-box">
			<span class="close"><span class="dashicons dashicons-no-alt"></span></span>
			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
				<table>
					<?php
					do_action('eer_order_form_input', $event_data);
					do_action('eer_order_form_submit');
					?>
				</table>
			</form>
		</div>
		<?php
	}


	public static function input_phone($event_data)
	{
		if (intval(EER()->event->eer_get_event_option($event_data, 'phone_enabled', -1)) === 1) { ?>
			<tr>
				<th><?php _e('Phone', 'easy-event-registration'); ?></th>
				<td>
					<input id="phone" class="eer-form-control eer-input" type="text"
						<?php if (intval(EER()->event->eer_get_event_option($event_data, 'phone_required', -1)) === 1) {
							echo 'required';
						} ?>
						   name="phone"
						   placeholder="<?php _e('phone number', 'easy-event-registration'); ?>">
				</td>
			</tr>
			<?php
		}
	}


	public static function input_country($event_data)
	{
		if (intval(EER()->event->eer_get_event_option($event_data, 'country_enabled', -1)) === 1) { ?>
			<tr>
				<th><?php _e('Country', 'easy-event-registration'); ?></th>
				<td>
					<select id="country" class="eer-form-control eer-input"
						<?php if (intval(EER()->event->eer_get_event_option($event_data, 'country_required', -1)) === 1) {
							echo 'required';
						} ?>
						    name="country">
						<option value=""><?php _e('- select country -', 'easy-event-registration'); ?></option>
						<?php
						$enum_countries = new EER_Enum_Countries();
						foreach ($enum_countries->eer_get_items() as $key => $name) {
							?>
							<option value="<?php echo $name; ?>"><?php echo $name; ?></option><?php
						}
						?>
					</select>
				</td>
			</tr>
		<?php }
	}


	public static function input_tshirt($event_data)
	{
		if (intval(EER()->event->eer_get_event_option($event_data, 'tshirts_enabled', -1)) === 1) {
			?>
			<tr>
				<th><?php _e('T-shirts', 'easy-event-registration'); ?></th>
				<td>
					<select id="tshirt" class="eer-form-control eer-input eer-update-price" name="tshirt">
						<option value=""
						        data-price="0"><?php _e('- select t-shirt -', 'easy-event-registration'); ?></option>
						<?php
						foreach (EER()->event->eer_get_event_option($event_data, 'tshirt_options', []) as $key => $tshirt) {
							?>
							<option value="<?php echo $tshirt['key']; ?>"
							        data-price="<?php echo $tshirt['price']; ?>"><?php echo $tshirt['name']; ?></option><?php
						}
						?>
					</select>
				</td>
			</tr>
			<?php
		}
	}


	public static function input_food($event_data)
	{
		if (intval(EER()->event->eer_get_event_option($event_data, 'food_enabled', -1)) === 1) {
			?>
			<tr>
				<th><?php _e('Food', 'easy-event-registration'); ?></th>
				<td>
					<select id="food" class="eer-form-control eer-input eer-update-price" name="food">
						<option value=""
						        data-price="0"><?php _e('- select food -', 'easy-event-registration'); ?></option>
						<?php
						foreach (EER()->event->eer_get_event_option($event_data, 'food_options', []) as $key => $tshirt) {
							?>
							<option value="<?php echo $tshirt['key']; ?>"
							        data-price="<?php echo $tshirt['price']; ?>"><?php echo $tshirt['option']; ?></option><?php
						}
						?>
					</select>
				</td>
			</tr>
			<?php
		}
	}


	public static function input_hosting($event_data)
	{
		if (intval(EER()->event->eer_get_event_option($event_data, 'offer_hosting_enabled', -1)) === 1) {
			?>
			<tr>
				<th><?php _e('Offer hosting', 'easy-event-registration'); ?></th>
				<td>
					<input id="hosting" class="eer-form-control eer-input" type="checkbox" name="hosting">
					<label><?php echo EER()->event->eer_get_event_option($event_data, 'hosting_text', ''); ?></label>
				</td>
			</tr>
			<?php
		}
	}


	public static function input_offer_hosting($event_data)
	{
		if (intval(EER()->event->eer_get_event_option($event_data, 'offer_hosting_enabled', -1)) === 1) {
			?>
			<tr>
				<th><?php _e('Offer hosting', 'easy-event-registration'); ?></th>
				<td>
					<input id="offer_hosting" class="eer-form-control eer-input" type="checkbox" name="offer_hosting">
					<label><?php echo EER()->event->eer_get_event_option($event_data, 'offer_hosting_text', ''); ?></label>
				</td>
			</tr>
			<?php
		}
	}


	public static function input_custom_checkbox($event_data)
	{
		if (intval(EER()->event->eer_get_event_option($event_data, 'custom_checkbox_enabled', -1)) === 1) { ?>
			<tr>
				<th><?php echo EER()->event->eer_get_event_option($event_data, 'custom_checkbox_description', ''); ?></th>
				<td>
					<input id="custom_checkbox" class="eer-form-control eer-input" type="checkbox" name="custom_checkbox">
				</td>
			</tr>
		<?php }
	}


	public static function input_custom_area($event_data)
	{
		if (intval(EER()->event->eer_get_event_option($event_data, 'custom_area_enabled', -1)) === 1) { ?>
			<tr>
				<th><?php echo EER()->event->eer_get_event_option($event_data, 'custom_area_title', ''); ?></th>
				<td>
					<textarea id="custom_area" name="custom_area" class="eer-form-control eer-input"
						<?php if (intval(EER()->event->eer_get_event_option($event_data, 'custom_area_required', -1)) === 1) {
							echo 'required ';
						} ?>
					></textarea>
				</td>
			</tr>
		<?php }
	}


	public static function input_submit()
	{
		?>
		<tr>
			<th></th>
			<td>
				<input type="hidden" name="order_id">
				<input type="submit" name="eer_order_edit_submit" value="<?php _e('Save', 'easy-event-registration'); ?>">
			</td>
		</tr>
		<?php
	}
}
