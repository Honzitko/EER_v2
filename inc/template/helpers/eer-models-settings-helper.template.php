<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Models_Settings_Helper_Templater {

	public function eer_print_settings_tab($model, $fields, $data = []) {
		foreach ($fields as $key => $field) {
			$args     = wp_parse_args($field, [
				'model'         => $model,
				'id'            => null,
				'desc'          => '',
				'name'          => '',
				'size'          => null,
				'options'       => '',
				'std'           => '',
				'min'           => null,
				'max'           => null,
				'step'          => null,
				'chosen'        => null,
				'multiple'      => null,
				'placeholder'   => null,
				'readonly'      => false,
				'faux'          => false,
				'tooltip_title' => false,
				'tooltip_desc'  => false,
				'field_class'   => '',
				'template'      => 'EER_Models_Settings_Helper_Templater',
				'data'          => $data
			]);
			$callback = !isset($args['callback']) ? 'eer_' . $field['type'] . '_callback' : $args['callback'];
			?>
			<tr>
				<th><?php echo $field['name']; ?></th>
				<td><?php
					if (method_exists($args['template'], $callback)) {
						call_user_func([$args['template'], $callback], $args);
					}
					?></td>
			</tr>
			<?php
		}
	}


	public static function eer_email_callback($args) {
		$value = self::eer_check_default_value($args, $args['id']);

		$name = 'name="' . $args['model'] . '_settings[' . esc_attr(esc_attr($args['id'])) . ']"';

		$class = self::eer_sanitize_html_class($args['field_class']);

		$html = '<input type="email" class="' . $class . ' ' . 'eer-input regular-text" id="' . self::eer_sanitize_key($args['id']) . '" ' . $name . ' value="' . esc_attr(stripslashes($value)) . '" data-default="' . $value . '"/>';
		$html .= '<label for="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . ']"> ' . wp_kses_post($args['desc']) . '</label>';

		echo apply_filters('eer_after_setting_output', $html, $args);
	}


	public static function eer_text_callback($args) {
		$value = self::eer_check_default_value($args, $args['id']);

		$name = 'name="' . $args['model'] . '_settings[' . esc_attr($args['id']) . ']"';

		$class = self::eer_sanitize_html_class($args['field_class']);

		$html = '<input type="text" class="' . $class . ' ' . 'eer-input regular-text" id="' . self::eer_sanitize_key($args['id']) . '" ' . $name . ' value="' . esc_attr(stripslashes($value)) . '" data-default="' . $value . '"/>';
		$html .= '<label for="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . ']"> ' . wp_kses_post($args['desc']) . '</label>';

		echo apply_filters('eer_after_setting_output', $html, $args);
	}


	public static function eer_number_callback($args) {
		$value = self::eer_check_default_value($args, $args['id']);

		$name = 'name="' . $args['model'] . '_settings[' . esc_attr($args['id']) . ']"';

		$class = self::eer_sanitize_html_class($args['field_class']);

		$html = '<input type="number" min="0" class="' . $class . ' ' . 'eer-input regular-text" id="' . self::eer_sanitize_key($args['id']) . '" ' . $name . ' value="' . esc_attr(stripslashes($value)) . '" data-default="' . $value . '"/>';
		$html .= '<label for="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . ']"> ' . wp_kses_post($args['desc']) . '</label>';

		echo apply_filters('eer_after_setting_output', $html, $args);
	}


	public static function eer_color_picker_callback($args) {
		$value = self::eer_check_default_value($args, $args['id']);

		$name = 'name="' . $args['model'] . '_settings[' . esc_attr($args['id']) . ']"';

		$class = self::eer_sanitize_html_class($args['field_class']);

		$html = '<input type="text" class="' . $class . ' eer-input eer-color-picker" id="' . self::eer_sanitize_key($args['id']) . '" ' . $name . ' value="' . esc_attr(stripslashes($value)) . '"/>';
		$html .= '<label for="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . ']"> ' . wp_kses_post($args['desc']) . '</label>';

		echo apply_filters('eer_after_setting_output', $html, $args);
	}


	public static function eer_full_editor_callback($args) {
		$value = self::eer_check_default_value($args, $args['id']);

		$rows = isset($args['size']) ? $args['size'] : 20;

		$class = self::eer_sanitize_html_class($args['field_class']);

		ob_start();
		wp_editor(stripslashes($value), esc_attr($args['id']), ['textarea_name' => $args['model'] . '_settings[' . esc_attr($args['id']) . ']', 'textarea_rows' => absint($rows), 'editor_class' => $class]);
		$html = ob_get_clean();

		$html .= '<br/><label for="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . ']"> ' . wp_kses_post($args['desc']) . '</label>';

		if (isset($args['desc_tags']) && !empty($args['desc_tags'])) {
			$html .= '<br/>' . wp_kses_post($args['desc_tags']);
		}

		echo apply_filters('eer_after_setting_output', $html, $args);
	}


	public static function eer_checkbox_callback($args) {
		$name = 'name="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . ']"';

		$class = self::eer_sanitize_html_class($args['field_class']);

		$value = self::eer_check_default_value($args, $args['id']);

		$checked = intval($value) === 1 ? 'checked' : '';
		$html    = '<input type="hidden"' . $name . ' value="-1" />';
		$html    .= '<input type="checkbox" id="' . self::eer_sanitize_key($args['id']) . '"' . $name . ' value="1" ' . $checked . ' class="' . $class . ' eer-input"/>';
		$html    .= '<label class="checkbox-label" for="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . ']"> ' . wp_kses_post($args['desc']) . '</label>';

		echo apply_filters('eer_after_setting_output', $html, $args);
	}


	public static function eer_select_callback($args) {
		$value = self::eer_check_default_value($args, $args['id']);

		$class = self::eer_sanitize_html_class($args['field_class']);

		// If the Select Field allows Multiple values, save as an Array
		$name_attr = $args['model'] . '_settings[' . esc_attr($args['id']) . ']';

		$html = '<select id="' . self::eer_sanitize_key($args['id']) . '" name="' . $name_attr . '" class="' . $class . ' eer-input" data-default="' . $value . '">';

		foreach ($args['options'] as $option => $name) {
			$html .= '<option value="' . esc_attr($option) . '" ' . selected($option, $value, false) . '>' . esc_html($name) . '</option>';
		}

		$html .= '</select>';
		$html .= '<label for="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . ']"> ' . wp_kses_post($args['desc']) . '</label>';

		echo apply_filters('eer_after_setting_output', $html, $args);
	}


	public static function eer_related_tickets_callback($args) {
		$values = isset($args['data']->related_tickets) ? explode(',', $args['data']->related_tickets) : [];

		$class = self::eer_sanitize_html_class($args['field_class']);
		$name = 'name="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . '][]"';

		$html = '';
		foreach (EER()->ticket->get_tickets_by_event(intval($args['data']->event_id)) as $key => $ticket) {
			if (intval($args['data']->id) !== intval($ticket->id)) {
				$html .= '<label for="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . ']">';
				$html .= '<input type="checkbox" ' . (in_array($ticket->id, $values) ? 'checked' : '') . ' id="' . self::eer_sanitize_key($args['id']) . '"' . $name . ' value="' . $ticket->id . '" ' . ' class="' . $class . ' eer-input"/>';
				$html .= $ticket->title . '</label><br>';
			}
		}

		echo apply_filters('eer_after_setting_output', $html, $args);
	}


	public static function eer_add_list_levels_callback($args) {
		$values = self::eer_check_default_value($args, $args['id']);

		$html = '<table class="eer_list_items wp-list-table fixed posts">
				<thead>
				<tr>
					<th scope="col">' . __('Name', 'easy-event-registration') . '</th>
					<th scope="col" class="max_level_leaders">' . __('Leaders', 'easy-event-registration') . '</th>
					<th scope="col" class="max_level_followers">' . __('Followers', 'easy-event-registration') . '</th>
					<th scope="col" class="max_level_tickets">' . __('Tickets', 'easy-event-registration') . '</th>
				</tr>
				</thead>';
		if (empty($values)) {
			$html .= '<tr data-key="0">
					<td class="eer_list_item">
						<input type="text" data-name="name" name="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . '][0][name]" value=""/>
					</td>
					<td class="eer_list_item max_level_leaders">
						<input type="text" data-name="leaders" name="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . '][0][leaders]" value=""/>
					</td>
					<td class="eer_list_item max_level_followers">
						<input type="text" data-name="followers" name="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . '][0][followers]" value=""/>
					</td>
					<td class="eer_list_item max_level_tickets">
						<input type="text" data-name="tickets" name="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . '][0][tickets]" value=""/>
					</td>
					<td>
						<span class="eer_remove_list_item button-secondary">' . __('Remove', 'easy-event-registration') . ' ' . $args['singular'] . '</span>
					</td>
				</tr>';
		} else {
			foreach ($values as $key => $value) {
				$html .= '<tr data-key="' . $value['key'] . '">
					<td class="eer_list_item">
						<input type="text" data-name="name" name="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . '][' . $value['key'] . '][name]" value="' . $value['name'] . '"/>
					</td>
					<td class="eer_list_item max_level_leaders">
						<input type="text" data-name="leaders" name="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . '][' . $value['key'] . '][leaders]" value="' . $value['leaders'] . '"/>
					</td>
					<td class="eer_list_item max_level_followers">
						<input type="text" data-name="followers" name="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . '][' . $value['key'] . '][followers]" value="' . $value['followers'] . '"/>
					</td>
					<td class="eer_list_item max_level_tickets">
						<input type="text" data-name="tickets" name="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . '][' . $value['key'] . '][tickets]" value="' . $value['tickets'] . '"/>
					</td>
					<td>
						<span class="eer_remove_list_item button-secondary">' . __('Remove', 'easy-event-registration') . ' ' . $args['singular'] . '</span>
					</td>
				</tr>';
			}
		}
		$html .= '</table>
			<span class="button-secondary eer-add-list-item">' . __('Add', 'easy-event-registration') . ' ' . $args['singular'] . '</span>';

		echo apply_filters('eer_after_setting_output', $html, $args);
	}


	public static function eer_add_list_tshirts_callback($args) {
		$values = self::eer_check_default_value($args, $args['id'], []);

		$html = '<table class="eer_list_items wp-list-table fixed posts">
				<thead>
				<tr>
					<th scope="col">' . __('Name', 'easy-event-registration') . '</th>
					<th scope="col">' . __('Price', 'easy-event-registration') . '</th>
				</tr>
				</thead>';
		if (empty($values)) {
			$html .= '<tr data-key="0">
					<td class="eer_list_item">
						<input type="text" data-name="name" name="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . '][0][name]" value=""/>
					</td>
					<td class="eer_list_item">
						<input type="text" data-name="price" name="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . '][0][price]" value=""/>
					</td>
					<td>
						<span class="eer_remove_list_item button-secondary">' . __('Remove', 'easy-event-registration') . ' ' . $args['singular'] . '</span>
					</td>
				</tr>';
		} else {
			foreach ($values as $key => $value) {
				$html .= '<tr data-key="' . $value['key'] . '">
					<td class="eer_list_item">
						<input type="text" data-name="name" name="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . '][' . $value['key'] . '][name]" value="' . $value['name'] . '"/>
					</td>
					<td class="eer_list_item">
						<input type="text" data-name="price" name="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . '][' . $value['key'] . '][price]" value="' . $value['price'] . '"/>
					</td>
					<td>
						<span class="eer_remove_list_item button-secondary">' . __('Remove', 'easy-event-registration') . ' ' . $args['singular'] . '</span>
					</td>
				</tr>';
			}
		}
			$html .= '</table>
			<span class="button-secondary eer-add-list-item">' . __('Add', 'easy-event-registration') . ' ' . $args['singular'] . '</span>';

		echo apply_filters('eer_after_setting_output', $html, $args);
	}


	public static function eer_add_list_food_callback($args) {
		$values = self::eer_check_default_value($args, $args['id'], []);

		$html = '<table class="eer_list_items wp-list-table fixed posts">
				<thead>
				<tr>
					<th scope="col">' . __('Option', 'easy-event-registration') . '</th>
					<th scope="col">' . __('Price', 'easy-event-registration') . '</th>
				</tr>
				</thead>';
		if (empty($values)) {
			$html .= '<tr data-key="0">
					<td class="eer_list_item">
						<input type="text" data-name="option" name="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . '][0][option]" value=""/>
					</td>
					<td class="eer_list_item">
						<input type="text" data-name="price" name="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . '][0][price]" value=""/>
					</td>
					<td>
						<span class="eer_remove_list_item button-secondary">' . __('Remove', 'easy-event-registration') . ' ' . $args['singular'] . '</span>
					</td>
				</tr>';
		} else {
			foreach ($values as $key => $value) {
				$html .= '<tr data-key="' . $value['key'] . '">
					<td class="eer_list_item">
						<input type="text" data-name="option" name="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . '][' . $value['key'] . '][option]" value="' . $value['option'] . '"/>
					</td>
					<td class="eer_list_item">
						<input type="text" data-name="price" name="' . $args['model'] . '_settings[' . self::eer_sanitize_key($args['id']) . '][' . $value['key'] . '][price]" value="' . $value['price'] . '"/>
					</td>
					<td>
						<span class="eer_remove_list_item button-secondary">' . __('Remove', 'easy-event-registration') . ' ' . $args['singular'] . '</span>
					</td>
				</tr>';
			}
		}
		$html .= '</table>
			<span class="button-secondary eer-add-list-item">' . __('Add', 'easy-event-registration') . ' ' . $args['singular'] . '</span>';

		echo apply_filters('eer_after_setting_output', $html, $args);
	}


	public static function eer_submit_callback($args) {
		$html   = '';
		$status = get_option('eer_license_status');

		echo apply_filters('eer_after_setting_output', $html, $args);
	}


	public static function eer_sanitize_html_class($class = '') {

		if (is_string($class)) {
			$class = sanitize_html_class($class);
		} elseif (is_array($class)) {
			$class = array_values(array_map('sanitize_html_class', $class));
			$class = implode(' ', array_unique($class));
		}

		return $class;

	}


	public static function eer_sanitize_key($key) {
		$raw_key = $key;
		$key     = preg_replace('/[^a-zA-Z0-9_\-\.\:\/]/', '', $key);

		return apply_filters('eer_sanitize_key', $key, $raw_key);
	}


	public static function eer_check_default_value($args, $key, $default = '') {
		if (isset($args['data']) && !empty($args['data']) && isset($args['data']->$key)) {
			return $args['data']->$key;
		} elseif (isset($args['std'])) {
			return $args['std'];
		}

		return $default;
	}

}

add_action('eer_print_events_settings_tab', ['EER_Template_Event', 'eer_print_events_settings_tab'], 10, 2);

