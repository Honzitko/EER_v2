<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Fields
{

	private $fields;


	public function add_field($key, $type, $required)
	{
		$this->fields[$key] = [
			'type' => $type,
			'required' => $required
		];
	}


	/**
	 * @return object
	 */
	public function get_fields()
	{
		return $this->fields;
	}

	public function sanitize($type, $value)
	{
		switch ($type) {
			case 'int':
				return (int)$value;
			case 'boolean':
				return filter_var($value, FILTER_VALIDATE_BOOLEAN);
			case 'timestamp':
				return date('Y-m-d H:i', strtotime($value));
			case 'html':
				return addslashes(stripcslashes($value));
			case 'json':
				return $value;
			default:
				return sanitize_text_field($value);
		}
	}

	public function eer_sanitize_event_settings($values)
	{
		return $this->eer_sanitize_settings(EER()->event->eer_get_event_settings_fields(), $values);
	}

	public function eer_sanitize_ticket_settings($values)
	{
		return $this->eer_sanitize_settings(EER()->ticket->eer_get_ticket_settings_fields(), $values);
	}

	private function eer_sanitize_settings($fields, $values)
	{
		$ret = [];

		foreach ($fields as $section_key => $sections) {
			foreach ($sections as $sub_section_key => $sub_section) {
				foreach ($sub_section as $key => $ssub_values) {
					if (isset($values[$key])) {
						$sanitize = isset($ssub_values['type']) ? $ssub_values['type'] : null;
						switch ($sanitize) {
							case 'add_list_levels':
							case 'add_list_tshirts':
							case 'add_list_food':
								$ret[$key] = $values[$key];
								if (isset(current($ret[$key])->option)) {
									current($ret[$key])->option = stripcslashes(sanitize_text_field(current($ret[$key])->option));
								}
								break;
							case 'text':
							case 'select':
							case 'email':
								$ret[$key] = sanitize_text_field($values[$key]);
								break;
							case 'boolean':
								$ret[$key] = filter_var($values[$key], FILTER_VALIDATE_BOOLEAN);
								break;
							case 'timestamp':
								$ret[$key] = date('Y-m-d H:i', strtotime($values[$key]));
								break;
							case 'html':
							case 'full_editor':
							case 'color_picker':
								$ret[$key] = addslashes(stripcslashes($values[$key]));
								break;
							case 'related_tickets':
							case 'required_tickets':
								$ret[$key] = sanitize_text_field(implode(',', $values[$key]));
								break;
							default:
								$ret[$key] = sanitize_text_field($values[$key]);
								break;
						}
					}
				}
			}
		}

		return $ret;
	}

}