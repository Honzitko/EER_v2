<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Subblock_Ticket_Editor {

	public function __construct() {
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_title']);
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_event']);
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_price']);
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_is_solo']);
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_max_leaders']);
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_max_followers']);
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_max_tickets']);
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_sold_separately']);
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_once_per_user']);
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_max_per_order']);
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_position']);
		add_action('eer_ticket_edit_form_submit', [get_called_class(), 'input_submit']);
	}


	public function print_block($ticket_id) {
		$settings_tabs = EER()->ticket->eer_get_ticket_settings_tabs();
		$settings_tabs = empty($settings_tabs) ? [] : $settings_tabs;
		$active_tab    = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'currency';
		$active_tab    = array_key_exists($active_tab, $settings_tabs) ? $active_tab : 'currency';
		$sections      = EER()->ticket->eer_get_ticket_settings_sections();

		$ticket = EER()->ticket->get_ticket_data($ticket_id);
		?>
		<div>
			<h1 class="wp-heading-inline"><?php _e('Edit Ticket', 'easy-event-registration'); ?></h1>
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" class="tab-content eer-edit-form" data-id="<?php echo $ticket_id; ?>">
				<div class="eer-form-column">
					<h3><?php _e('Main Info', 'easy-event-registration'); ?></h3>
					<table>
						<?php
						do_action('eer_ticket_edit_form_input', $ticket);
						?>
					</table>
				</div>
				<div class="eer-form-column">
					<h3><?php _e('Additional Info', 'easy-event-registration'); ?></h3>
					<table>
						<?php
						do_action('eer_ticket_edit_additional_form_input', $ticket);
						?>
					</table>
				</div>
				<h3><?php _e('Settings', 'easy-event-registration'); ?></h3>
				<ul class="nav nav-tabs">
					<?php
					$number = 0;
					foreach (EER()->ticket->eer_get_ticket_settings_tabs() as $tab_id => $tab_name) {
						echo '<li class="' . ($number === 0 ? 'active' : '') . '">';
						echo '<a href="#' . $tab_id . '" class="nav-tab" data-toggle="tab">';
						echo esc_html($tab_name);
						echo '</a>';
						echo '</li>';
						$number++;
					}
					?>
				</ul>
				<?php
				$section_number = 0;
				foreach ($sections as $section_id => $subsections) {
					$number_of_sections = count($subsections);
					$number             = 0;
					if ($number_of_sections > 0) {
						?>
					<div id="<?php echo $section_id; ?>" class="tab-pane<?php echo($section_number === 0 ? ' active' : '') ?>">
						<div class="tabbable">
							<ul class="nav nav-tabs subsubsub eer-sub-tabs">
								<?php
								foreach ($subsections as $sub_section_id => $section_name) {
									echo '<li>';
									echo '<a class="nav-tab" href="#' . $sub_section_id . '" data-toggle="tab">' . $section_name . '</a>';
									$number++;
									if ($number != $number_of_sections) {
										echo ' | ';
									}
									echo '</li>';
								}
								?></ul><?php
							?>
							<div class="tab-content"><?php
								$number = 0;
								foreach ($subsections as $sub_section_id => $section_name) {
									?>
									<table id="<?php echo $sub_section_id; ?>" class="tab-pane form-table<?php echo($number === 0 ? ' active' : '') ?>">
										<?php
										$this->eer_print_tickets_settings_tab($section_id, $sub_section_id, $ticket);
										?>
									</table>
									<?php
									$number++;
								}
								?></div>
						</div>
						</div><?php
					}
					$section_number++;
				}
				?>
				<?php
				do_action('eer_ticket_edit_form_submit', $ticket_id);
				?>
			</form>
		</div>
		<?php
	}


	public static function input_title($ticket) {
		?>
		<tr>
			<th><?php _e('Ticket Title', 'easy-event-registration'); ?></th>
			<td><input id="title" required type="text" name="title" class="eer-input" value="<?php echo !empty((array) $ticket) ? $ticket->title : ''; ?>"></td>
		</tr>
		<?php
	}


	public static function input_event($ticket) {
		?>
		<tr>
			<th><?php _e('Event', 'easy-event-registration'); ?></th>
			<td>
				<select id="event_id" name="event_id" class="eer-input">
					<option value=""><?php _e('- select -', 'easy-event-registration'); ?></option>
					<?php foreach (EER()->event->load_events_without_data() as $key => $event) { ?>
						<option
								value="<?php echo $event->id; ?>"
							<?php echo(!empty((array) $ticket) && ($ticket->event_id == $event->id) ? 'selected' : '') ?>><?php echo $event->title; ?></option>
						<?php
					}
					?>
				</select>
			</td>
		</tr>
		<?php
	}


	public static function input_is_solo($ticket) {
		?>
		<tr>
			<th><?php _e('Solo Class', 'easy-event-registration'); ?></th>
			<td><input id="is_solo" type="checkbox" name="is_solo" <?php echo(!empty((array) $ticket) && $ticket->is_solo ? 'checked' : '') ?> class="eer-input" data-show=".max_tickets, .max_level_tickets" data-hide=".max_leaders, .max_followers, .max_level_followers, .max_level_leaders" value="1"></td>
		</tr>
		<?php
	}


	private static function add_number($name, $key, $class = '', $default = 0, $hidden = false) {
		?>
		<tr class="<?php echo $class; ?>" <?php echo($hidden ? 'style="display:none;"' : ''); ?>>
			<th><?php _e($name, 'easy-event-registration'); ?></th>
			<td><input id="<?php echo $key; ?>" type="number" name="<?php echo $key; ?>" value="<?php echo $default; ?>" class="eer-input"></td>
		</tr>
		<?php
	}


	public static function input_max_leaders($ticket) {
		self::add_number('Max Leaders', 'max_leaders', 'max_leaders', !empty((array) $ticket) ? $ticket->max_leaders : 0, !empty((array) $ticket) && $ticket->is_solo);
	}


	public static function input_max_followers($ticket) {
		self::add_number('Max Followers', 'max_followers', 'max_followers', !empty((array) $ticket) ? $ticket->max_followers : 0, !empty((array) $ticket) && $ticket->is_solo);
	}


	public static function input_max_tickets($ticket) {
		self::add_number('Max Tickets', 'max_tickets', 'max_tickets', !empty((array) $ticket) ? $ticket->max_tickets : 0, empty((array) $ticket) || (!empty((array) $ticket) && !$ticket->is_solo));
	}


	public static function input_price($ticket) {
		?>
		<tr>
			<th><?php _e('Price', 'easy-event-registration'); ?></th>
			<td><input id="price" required type="number" name="price" value="<?php echo !empty((array) $ticket) ? $ticket->price : ''; ?>" class="eer-input"></td>
		</tr>
		<?php
	}


	public static function input_max_per_order($ticket) {
		?>
		<tr class="max_per_order">
			<th><?php _e('Max Per Order', 'easy-event-registration'); ?></th>
			<td><input id="max_per_order" type="number" name="max_per_order" value="<?php echo !empty((array) $ticket) ? $ticket->max_per_order : ''; ?>" class="eer-input"></td>
		</tr>
		<?php
	}


	public static function input_sold_separately($ticket) {
		?>
		<tr>
			<th><?php _e('Sold Separately', 'easy-event-registration'); ?></th>
			<td><input id="sold_separately" type="checkbox" <?php echo(!empty((array) $ticket) && $ticket->sold_separately ? 'checked' : '') ?> name="sold_separately" class="eer-input" value="1"></td>
		</tr>
		<?php
	}


	public static function input_once_per_user($ticket) {
		?>
		<tr>
			<th><?php _e('Once Per User', 'easy-event-registration'); ?></th>
			<td><input id="once_per_user" type="checkbox" <?php echo(!empty((array) $ticket) && $ticket->once_per_user ? 'checked' : '') ?> name="once_per_user" class="eer-input" value="1"></td>
		</tr>
		<?php
	}


	public static function input_position($ticket) {
		?>
		<tr>
			<th><?php _e('Ticket Position', 'easy-event-registration'); ?></th>
			<td><input id="position" type="number" name="position" value="<?php echo !empty((array) $ticket) ? $ticket->position : ''; ?>" class="eer-input"></td>
		</tr>
		<?php
	}


	public static function input_submit($ticket_id) {
		?>
		<tr>
			<th></th>
			<td>
				<?php if (intval($ticket_id) !== -1) { ?>
					<input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
				<?php } ?>
				<input type="submit" name="eer_ticket_submit" value="<?php _e('Save', 'easy-event-registration'); ?>">
			</td>
		</tr>
		<?php
	}


	public static function eer_input_pairing_mode($ticket) {
		$default_key = ($ticket && isset($ticket->pairing_mode)) ? $ticket->pairing_mode : EER_Enum_Pairing_Mode::AUTOMATIC;
		?>
		<tr>
			<th><?php _e('Pairing Mode', 'easy-event-registration'); ?></th>
			<td>
				<?php foreach (EER()->pairing_mode->get_items() as $key => $mode) {
					?>
					<label>
						<input type="radio" name="pairing_mode" <?php if ($key == $default_key) {echo 'checked';} ?> value="<?php echo $key; ?>" class="<?php if ($mode['is_default']) {echo 'eer-default';} ?>"> <?php echo $mode['title']; ?>
					</label><br>
				<?php } ?>
			</td>
		</tr>
		<?php
	}



	public static function eer_print_tickets_settings_tab($section_id, $sub_section_id, $data) {
		$model_settings = new EER_Models_Settings_Helper_Templater();
		$model_settings->eer_print_settings_tab('ticket', EER()->ticket->eer_get_ticket_settings_fields_to_print($section_id, $sub_section_id), $data);
	}
}

add_action('eer_ticket_edit_additional_form_input', ['EER_Subblock_Ticket_Editor', 'eer_input_pairing_mode']);
