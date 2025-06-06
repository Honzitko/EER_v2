<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Model_Settings
{


	public function eer_get_event_option($event_id, $key = '', $default = false)
	{
		return $this->eer_find_option(EER()->event->get_event_data($event_id), $key, $default);
	}


	public function eer_find_option($data, $key = '', $default = false)
	{
		if (isset($data->$key) && $data->$key !== '' && $data->$key !== null) {
			return $data->$key;
		} else {
			return $default;
		}
	}


}
