<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Enum_Pairing_Mode
{
	const
		AUTOMATIC = 1, MANUAL = 2, CONFIRM_ALL = 3;

	private $items = [];


	/**
	 * @codeCoverageIgnore
	 */
	public function __construct()
	{
		$this->items = [
			self::AUTOMATIC => [
				'title' => __('Automatic', 'easy-event-registration'),
				'tooltip' => __('Students will be automatically paired after registration until the course it\'s full.', 'easy-event-registration'),
				'is_default' => true,
			],
			self::MANUAL => [
				'title' => __('Manual', 'easy-event-registration'),
				'tooltip' => __('Students will be on a Waiting List until they are manually confirmed. Pairing is disabled.', 'easy-event-registration'),
				'is_default' => false,
			],
			self::CONFIRM_ALL => [
				'title' => __('Confirm All', 'easy-event-registration'),
				'tooltip' => __('Students will be automatically confirmed after registration until the course it\'s full. Pairing is disabled.', 'easy-event-registration'),
				'is_default' => false,
			],
		];
	}


	public function get_items()
	{
		return $this->items;
	}


	public function get_items_for_settings()
	{
		$return_items = [];
		foreach ($this->get_items() as $key => $item) {
			$return_items[$key] = $item['title'];
		}

		return $return_items;
	}


	public function get_item($key)
	{
		$items = $this->get_items();
		return isset($items[$key]) ? $items[$key] : [];
	}


	public function get_title($key)
	{
		$item = $this->get_item($key);
		return isset($item['title']) ? $item['title'] : null;
	}

	public function is_pairing_enabled($key)
	{
		return intval($key) === self::AUTOMATIC;
	}

	public function is_auto_confirmation_enabled($key)
	{
		return intval($key) === self::CONFIRM_ALL;
	}

	public function get_solo_ticket_default_status($key)
	{
		return intval($key) === self::MANUAL ? EER_Enum_Sold_Ticket_Status::WAITING : EER_Enum_Sold_Ticket_Status::CONFIRMED;
	}
}