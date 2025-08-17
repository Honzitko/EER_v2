<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Event_Sale_User_Form {

        public function print_content($event_id) {
                $event_data = EER()->event->get_event_data($event_id);
                $form_message = stripcslashes(EER()->settings->eer_get_option('registration_form_message', ''));
                ?>
                <?php if (!empty($form_message)) { ?>
                        <div class="eer-form-custom-message"><?php echo $form_message; ?></div>
                <?php } ?>
                <form id="eer-ticket-shop-form" class="eer-ticket-shop-form"
                      data-no-tickets="<?php _e('At least one ticket is required to select.', 'easy-event-registration'); ?>">
                        <div class="eer-form-tickets-header eer-clearfix">
                                <div class="eer-column-header"><span><?php _e('Item', 'easy-event-registration'); ?></span></div>
				<div class="eer-column-header eer-amount"><span><?php _e('Amount', 'easy-event-registration'); ?></span>
				</div>
				<div class="eer-column-header"><span><?php _e('Price', 'easy-event-registration'); ?></span></div>
			</div>

			<div class="eer-form-tickets"
			     data-price-template="<?php echo EER()->currency->eer_prepare_price($event_id, '[price]', $event_data); ?>">
			</div>
			<div class="eer-final-price eer-row eer-clearfix">
				<div class="eer-final-price-label"><?php _e('Final price', 'easy-event-registration'); ?></div>
				<div class="eer-final-price-value eer-total-price-count" data-currency=""></div>
				<div class="eer-final-price-description">
				</div>
			</div>
			<?php do_action('eer_front_page_registration_under_selected_tickets', $event_id); ?>
			<div class="eer-user-form eer-clearfix">
				<div class="eer-row eer-clearfix">
					<div class="eer-form-group eer-required">
						<label><?php _e('Name', 'easy-event-registration'); ?></label>
						<input class="eer-form-control" type="text" required
						       name="name" <?php echo $this->get_user_default_value('first_name'); ?>
						       placeholder="<?php _e('name', 'easy-event-registration'); ?>">
					</div>
					<div class="eer-form-group eer-required">
						<label><?php _e('Surname', 'easy-event-registration'); ?></label>
						<input class="eer-form-control" type="text" required
						       name="surname" <?php echo $this->get_user_default_value('last_name'); ?>
						       placeholder="<?php _e('surname', 'easy-event-registration'); ?>">
					</div>
				</div>
				<div class="eer-row eer-full-width eer-clearfix">
					<div class="eer-form-group eer-required">
						<label><?php _e('E-mail', 'easy-event-registration'); ?></label>
						<input class="eer-form-control" type="email" required
						       name="email" <?php echo $this->get_user_default_value('user_email'); ?>
						       placeholder="<?php _e('e-mail address', 'easy-event-registration'); ?>">
					</div>
				</div>
				<div class="eer-row eer-clearfix">
					<?php if (intval(EER()->event->eer_get_event_option($event_data, 'phone_enabled', -1)) === 1) {
						$is_required = intval(EER()->event->eer_get_event_option($event_data, 'phone_required', -1)) === 1;
						?>
						<div class="eer-form-group <?php if ($is_required) {
							echo 'eer-required';
						} ?>">
							<label><?php _e('Phone number', 'easy-event-registration'); ?></label>
							<input class="eer-form-control" type="text"
								<?php if ($is_required) {
									echo 'required';
								} ?>
								   name="phone"
								   placeholder="<?php _e('phone number', 'easy-event-registration'); ?>">
						</div>
					<?php } ?>
					<?php if (intval(EER()->event->eer_get_event_option($event_data, 'country_enabled', -1)) === 1) {
						$is_required = intval(EER()->event->eer_get_event_option($event_data, 'country_required', -1)) === 1;
						?>
						<div class="eer-form-group <?php if ($is_required) {
							echo 'eer-required';
						} ?>">
							<label for="country"><?php _e('Country', 'easy-event-registration'); ?></label>
							<select class="eer-form-control"
								<?php if ($is_required) {
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
				<?php if (intval(EER()->event->eer_get_event_option($event_data, 'note_enabled', 1)) === 1) { ?>
					<?php $is_required = intval(EER()->event->eer_get_event_option($event_data, 'note_required', -1)) === 1; ?>
					<div class="eer-row eer-full-width eer-clearfix eer-note <?php if ($is_required) {
						echo 'eer-required';
					} ?>">
						<div class="eer-form-group">
							<label><?php echo EER()->event->eer_get_event_option($event_data, 'note_title', __('Note', 'easy-event-registration')); ?></label>
							<?php if (EER()->event->eer_get_event_option($event_data, 'note_description', '')) { ?>
								<div class="eer-event-note-description"><?php echo EER()->event->eer_get_event_option($event_data, 'note_description', ''); ?></div>
							<?php } ?>
							<textarea name="note" class="eer-form-control" placeholder="<?php _e('note', 'easy-event-registration') ?>"
								<?php if ($is_required) {
									echo 'required ';
								} ?>></textarea>
						</div>
					</div>
				<?php } ?>
				<?php if (intval(EER()->event->eer_get_event_option($event_data, 'tshirts_enabled', -1)) === 1) { ?>
					<div class="eer-row eer-full-width eer-clearfix eer-tshirts">
						<div class="eer-form-group">
							<label for="tshirt"><?php _e('T-Shirt', 'easy-event-registration'); ?></label>
							<div class="eer-event-description"><?php echo EER()->event->eer_get_event_option($event_data, 'tshirt_description', '') ?></div>
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
				<?php if (intval(EER()->event->eer_get_event_option($event_data, 'food_enabled', -1)) === 1) {
					$is_required = intval(EER()->event->eer_get_event_option($event_data, 'food_required', -1)) === 1;
					?>
					<div class="eer-row eer-full-width eer-clearfix eer-food <?php if ($is_required) {
						echo 'eer-required';
					} ?>">
						<div class="eer-form-group">
							<label for="food"><?php echo EER()->event->eer_get_event_option($event_data, 'food_title', 'Food'); ?></label>
							<div><?php echo EER()->event->eer_get_event_option($event_data, 'food_description', '') ?></div>
							<select class="eer-form-control eer-update-price" name="food"
								<?php if ($is_required) {
									echo 'required';
								} ?>>
								<option value=""
								        data-price="0"><?php _e('- select option -', 'easy-event-registration'); ?></option>
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
				<?php if (intval(EER()->event->eer_get_event_option($event_data, 'custom_area_enabled', -1)) === 1) {
					$is_required = intval(EER()->event->eer_get_event_option($event_data, 'custom_area_required', -1)) === 1;
					?>
					<div class="eer-row eer-full-width eer-clearfix eer-custom-textarea <?php if ($is_required) {
						echo 'eer-required';
					} ?>">
						<div class="eer-form-group">
							<label><?php echo EER()->event->eer_get_event_option($event_data, 'custom_area_title', ''); ?></label>
							<?php if (EER()->event->eer_get_event_option($event_data, 'custom_area_description', '')) { ?>
								<div class="eer-event-note-description"><?php echo EER()->event->eer_get_event_option($event_data, 'custom_area_description', ''); ?></div>
							<?php } ?>
							<textarea name="custom_area" class="eer-form-control"
								<?php if ($is_required) {
									echo 'required ';
								} ?>
							></textarea>
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
				<?php if (intval(EER()->event->eer_get_event_option($event_data, 'custom_checkbox_enabled', -1)) === 1) { ?>
					<div class="eer-row eer-full-width eer-clearfix eer-form-checkbox  eer-custom-checkbox">
						<div class="eer-form-group">
							<input class="eer-form-control" type="checkbox" name="custom_checkbox">
							<label><?php echo EER()->event->eer_get_event_option($event_data, 'custom_checkbox_description', ''); ?></label>
						</div>
					</div>
				<?php } ?>
				<?php if (intval(EER()->event->eer_get_event_option($event_data, 'terms_conditions_enabled', -1)) === 1) {
					$is_required = intval(EER()->event->eer_get_event_option($event_data, 'terms_conditions_required', -1)) === 1;
					?>
					<div class="eer-row eer-full-width eer-clearfix eer-form-checkbox <?php if ($is_required) {
						echo 'eer-required';
					} ?>">
						<div class="eer-form-group">
							<input class="eer-form-control" type="checkbox" name="terms"
								<?php if ($is_required) {
									echo 'required';
								} ?>
							>
							<label><?php echo stripcslashes(EER()->event->eer_get_event_option($event_data, 'terms_conditions_text', '')); ?></label>
						</div>
					</div>
				<?php }
					do_action('eer_print_after_event_user_registration_form', $event_data);
				?>
			</div>

			<div class="eer-final-price eer-row eer-clearfix">
				<div class="eer-final-price-label"><?php _e('Final price', 'easy-event-registration'); ?></div>
				<div class="eer-final-price-value" data-currency=""></div>
				<div class="eer-final-price-description"></div>
			</div>
			<div class="text-center">
				<input class="btn btn-default" type="submit"
				       name="eer-event-registration-submitted"
				       value="<?php _e(EER()->event->eer_get_event_option($event_data, 'registration_button', 'Submit'), 'easy-event-registration'); ?>"></div>
			<input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
		</form>
		<div class="eer-ticket-default-form-row">
			<?php $this->eer_print_ticket_row($event_data, EER()->currency->eer_get_currency_symbol('', $event_data)); ?>
		</div>
		<?php
	}


	private function get_user_default_value($key) {
		$current_user = wp_get_current_user();
		if ($current_user && isset($current_user->$key)) {
			return 'value="' . $current_user->$key . '"';
		}

		return '';
	}


	private function eer_print_ticket_row($event_data, $main_currency) {
		$ticket_id = '%eer-ticket-id%';
		?>
		<div id="eer-ticket-<?php echo $ticket_id; ?>"
		     class="eer-ticket-to-buy eer-clearfix"
		     data-id="<?php echo $ticket_id; ?>"
		     data-separately="%eer-separately%">
			<div class="eer-ticket-title eer-column">
				<button class="eer-ticket-remove" type="button"><span class="dashicons dashicons-minus"></span></button>
				%eer-ticket-title%
			</div>
			<div class="eer-number-of-tickets eer-column">
				<input type="number" min="1" max="%eer-max%"
				       name="number_of_tickets" class="eer-number eer-form-control"
				       value="1">
				<div class="eer-max-info"><?php echo __('max', 'easy-event-registration') . ' %eer-max%'; ?></div>
			</div>
			<div class="eer-column eer-price-column eer-show-mobile"></div>
			<div class="eer-column eer-price-column">
					<span class="eer-price" data-price="%eer-price%"
					      data-currency="<?php echo $main_currency; ?>">%eer-price-currency%</span>
			</div>
			<div class="eer-column eer-ticket-student-info">
				<div class="eer-info-row eer-level-row">
					<select class="eer-info-row-input eer-levels" required name="level_id">
						<option class="eer-default"
						        value=""><?php _e('Choose your level', 'easy-event-registration'); ?></option>
					</select>
				</div>
				<div class="eer-partner">
					<div class="eer-info-row eer-dancing-as">
						<span class="eer-info-row-label"><?php _e('Dancing as?', 'easy-event-registration'); ?></span>
						<div class="eer-info-row-input eer-dancing-as">
							<label>
								<input class="eer-leader eer-dancing-as-input" type="radio"
								       name="%eer-ticket-id%_dancing_as" required
								       value="<?php echo EER_Enum_Dancing_As::LEADER; ?>"><?php echo EER()->dancing_as->eer_get_title(EER_Enum_Dancing_As::LEADER); ?>
							</label>
							<label>
								<input class="eer-follower eer-dancing-as-input" type="radio"
								       name="%eer-ticket-id%_dancing_as" required
								       value="<?php echo EER_Enum_Dancing_As::FOLLOWER; ?>"><?php echo EER()->dancing_as->eer_get_title(EER_Enum_Dancing_As::FOLLOWER); ?>
							</label>
						</div>
					</div>
					<div class="eer-info-row eer-dancing-with">
						<span class="eer-info-row-label"><?php _e('Do you have partner?', 'easy-event-registration'); ?></span>
						<div class="eer-info-row-input">
							<label class="eer-choose-partner"><input type="radio"
							                                         class="choose_partner eer-choose-partner-input"
							                                         name="%eer-ticket-id%_choose_partner"
							                                         value="1"
							                                         required> <?php _e('Yes', 'easy-event-registration'); ?>
							</label>
							<label class="eer-choose-partner"><input type="radio"
							                                         class="choose_partner eer-choose-partner-input"
							                                         name="%eer-ticket-id%_choose_partner"
							                                         value="0"
							                                         required> <?php _e('No', 'easy-event-registration'); ?>
							</label>
						</div>
					</div>
				</div>
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
			</div>
		</div>
		<?php
	}
}

