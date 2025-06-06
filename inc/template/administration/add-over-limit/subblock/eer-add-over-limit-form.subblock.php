<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Subblock_Add_Over_Limit_Form {

	public static function eer_print_block($event_id, $ticket_id) {
		$ticket_data = EER()->ticket->get_ticket_data($ticket_id);
		$event_data  = EER()->event->get_event_data($event_id);
		if ($ticket_data) {
			?>
			<form id="eer-ticket-shop-form" class="eer-ticket-shop-form"
			      data-no-tickets="<?php _e('At least one ticket is required to select.', 'easy-event-registration'); ?>">
				<div id="eer-ticket-<?php echo $ticket_id; ?>"
				     class="eer-ticket-to-buy eer-clearfix"
				     data-id="<?php echo $ticket_id; ?>">
					<div class="eer-partner eer-column">
						<span class="eer-info-row-label"><?php _e('Number tickets', 'easy-event-registration'); ?></span>
						<div class="eer-number-of-tickets eer-column">
							<input type="number" min="1" max="<?php echo $ticket_data->max_per_order; ?>"
							       name="number_of_tickets" class="eer-number eer-form-control"
							       value="1">
						</div>
						<?php if (intval($ticket_data->levels_enabled) !== -1) { ?>
							<div class="eer-info-row">
								<select class="eer-info-row-input eer-levels" required name="level_id">
									<option class="eer-default"
									        value=""><?php _e('Choose your level', 'easy-event-registration'); ?></option>
									<?php foreach ($ticket_data->levels as $k => $level) { ?>
										<option value="<?php echo $level['key'] ?>"><?php echo $level['name'] ?></option>
									<?php } ?>
								</select>
							</div>
						<?php } ?>
						<?php if (intval($ticket_data->is_solo) < 1) { ?>
							<div class="eer-info-row">
								<span class="eer-info-row-label"><?php _e('Dancing as?', 'easy-event-registration'); ?></span>
								<div class="eer-info-row-input eer-dancing-as">
									<label>
										<input class="eer-leader eer-dancing-as-input" type="radio"
										       name="dancing_as" required
										       value="<?php echo EER_Enum_Dancing_As::LEADER; ?>"><?php echo EER()->dancing_as->eer_get_title(EER_Enum_Dancing_As::LEADER); ?>
									</label>
									<label>
										<input class="eer-follower eer-dancing-as-input" type="radio"
										       name="dancing_as" required
										       value="<?php echo EER_Enum_Dancing_As::FOLLOWER; ?>"><?php echo EER()->dancing_as->eer_get_title(EER_Enum_Dancing_As::FOLLOWER); ?>
									</label>
								</div>
							</div>
							<div class="eer-info-row">
								<span class="eer-info-row-label"><?php _e('Do you have partner?', 'easy-event-registration'); ?></span>
								<div class="eer-info-row-input">
									<label class="eer-choose-partner"><input type="radio"
									                                         class="choose_partner eer-choose-partner-input"
									                                         name="choose_partner"
									                                         value="1"
									                                         required> <?php _e('Yes', 'easy-event-registration'); ?>
									</label>
									<label class="eer-choose-partner"><input type="radio"
									                                         class="choose_partner eer-choose-partner-input"
									                                         name="choose_partner"
									                                         value="0"
									                                         required> <?php _e('No', 'easy-event-registration'); ?>
									</label>
								</div>
							</div>
						<?php } ?>
						<?php if (intval(EER()->event->eer_get_event_option($event_data, 'partner_name_enabled', -1)) === 1) { ?>
							<div class="eer-info-row dancing-with-name" style="display: none;"
							     data-required="<?php echo intval(EER()->event->eer_get_event_option($event_data, 'partner_name_required', -1)) ?>">
								<span class="eer-info-row-label"><?php _e('Partner name', 'easy-event-registration'); ?>:</span>
								<input class="eer-info-row-input dancing-with-name" type="text" name="dancing_with_name">
							</div>
						<?php } ?>
						<div class="eer-info-row dancing-with-email" style="display: none;">
							<span class="eer-info-row-label"><?php _e('Partner email', 'easy-event-registration'); ?>:</span>
							<input class="eer-info-row-input dancing-with" type="email" name="dancing_with">
						</div>
						<div class="eer-user-form eer-clearfix">
							<div class="eer-row eer-clearfix">
								<div class="eer-form-group">
									<label><?php _e('Name', 'easy-event-registration'); ?></label>
									<input class="eer-form-control" type="text" required
									       name="name"
									       placeholder="<?php _e('name', 'easy-event-registration'); ?>">
								</div>
								<div class="eer-form-group">
									<label><?php _e('Surname', 'easy-event-registration'); ?></label>
									<input class="eer-form-control" type="text" required
									       name="surname"
									       placeholder="<?php _e('surname', 'easy-event-registration'); ?>">
								</div>
							</div>
							<div class="eer-row eer-full-width eer-clearfix">
								<div class="eer-form-group">
									<label><?php _e('E-mail', 'easy-event-registration'); ?></label>
									<input class="eer-form-control" type="email" required
									       name="email"
									       placeholder="<?php _e('e-mail address', 'easy-event-registration'); ?>">
								</div>
							</div>
							<div class="eer-row eer-clearfix">
								<?php if (intval(EER()->event->eer_get_event_option($event_data, 'phone_enabled', -1)) === 1) { ?>
									<div class="eer-form-group">
										<label><?php _e('Phone number', 'easy-event-registration'); ?></label>
										<input class="eer-form-control" type="text"
											<?php if (intval(EER()->event->eer_get_event_option($event_data, 'phone_required', -1)) === 1) {
												echo 'required';
											} ?>
											   name="phone"
											   placeholder="<?php _e('phone number', 'easy-event-registration'); ?>">
									</div>
								<?php } ?>
								<?php if (intval(EER()->event->eer_get_event_option($event_data, 'country_enabled', -1)) === 1) { ?>
									<div class="eer-form-group">
										<label for="country"><?php _e('Country', 'easy-event-registration'); ?></label>
										<select class="eer-form-control"
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
									</div>
								<?php } ?>
							</div>
							<div class="eer-row eer-full-width eer-clearfix">
								<div class="eer-form-group">
									<label><?php _e('Note', 'easy-event-registration'); ?></label>
									<textarea name="note" class="eer-form-control" placeholder="note"></textarea>
								</div>
							</div>
							<?php if (intval(EER()->event->eer_get_event_option($event_data, 'tshirts_enabled', -1)) === 1) { ?>
								<div class="eer-row eer-full-width eer-clearfix">
									<div class="eer-form-group">
										<label for="tshirt"><?php _e('T-Shirt', 'easy-event-registration'); ?></label>
										<div><?php echo EER()->event->eer_get_event_option($event_data, 'tshirt_description', '') ?></div>
										<select class="eer-form-control eer-update-price" name="tshirt">
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
									</div>
								</div>
							<?php } ?>
							<?php if (intval(EER()->event->eer_get_event_option($event_data, 'food_enabled', -1)) === 1) { ?>
								<div class="eer-row eer-full-width eer-clearfix">
									<div class="eer-form-group">
										<label for="food"><?php _e('Food', 'easy-event-registration'); ?></label>
										<div><?php echo EER()->event->eer_get_event_option($event_data, 'food_description', '') ?></div>
										<select class="eer-form-control eer-update-price" name="food">
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
									</div>
								</div>
							<?php } ?>
							<?php if (intval(EER()->event->eer_get_event_option($event_data, 'hosting_enabled', -1)) === 1) { ?>
								<div class="eer-row eer-full-width eer-clearfix eer-form-checkbox">
									<div class="eer-form-group">
										<input class="eer-form-control" type="checkbox" name="hosting">
										<label><?php echo EER()->event->eer_get_event_option($event_data, 'hosting_text', ''); ?></label>
									</div>
								</div>
							<?php } ?>

							<?php if (intval(EER()->event->eer_get_event_option($event_data, 'offer_hosting_enabled', -1)) === 1) { ?>
								<div class="eer-row eer-full-width eer-clearfix eer-form-checkbox">
									<div class="eer-form-group">
										<input class="eer-form-control" type="checkbox" name="offer_hosting">
										<label><?php echo EER()->event->eer_get_event_option($event_data, 'offer_hosting_text', ''); ?></label>
									</div>
								</div>
							<?php } ?>
							<?php if (intval(EER()->event->eer_get_event_option($event_data, 'terms_conditions_enabled', -1)) === 1) { ?>
								<div class="eer-row eer-full-width eer-clearfix eer-form-checkbox">
									<div class="eer-form-group">
										<input class="eer-form-control" type="checkbox" name="terms"
											<?php if (intval(EER()->event->eer_get_event_option($event_data, 'terms_conditions_required', -1)) === 1) {
												echo 'required';
											} ?>
										>
										<label><?php echo stripcslashes(EER()->event->eer_get_event_option($event_data, 'terms_conditions_text', '')); ?></label>
									</div>
								</div>
							<?php } ?>
							<div class="eer-row eer-full-width eer-clearfix eer-form-checkbox">
								<div class="eer-form-group">
									<input class="eer-form-control" type="checkbox" name="eer_disable_confirmation_email" value="1">
									<label><?php _e('Disable Confirmation Email', 'easy-event-registration') ?></label>
								</div>
							</div>
						</div>
						<input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
						<input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
						<input type="button" name="eer_add_over_limit_submit" value="<?php _e('Submit', 'easy-event-registration'); ?>">
					</div>
				</div>
			</form>
			<?php
		}
	}

}

add_action('eer_print_add_over_limit_form', ['EER_Subblock_Add_Over_Limit_Form', 'eer_print_block'], 10, 2);