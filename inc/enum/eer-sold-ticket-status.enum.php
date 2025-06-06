<?php

class EER_Enum_Sold_Ticket_Status
{

	const WAITING = 0,
		CONFIRMED = 1,
		DELETED = 2;

	private $items = [];


	/**
	 * @codeCoverageIgnore
	 */
	public function __construct()
	{
		$this->items = [
			self::WAITING => [
				'title' => __('Waiting', 'easy-event-registration')
			],
			self::CONFIRMED => [
				'title' => __('Confirmed', 'easy-event-registration')
			],
			self::DELETED => [
				'title' => __('Deleted', 'easy-event-registration')
			],
		];
	}


	public function get_title($key)
	{
		if (!isset($this->items[$key]['title'])) {
			return NULL;
		}

		return $this->items[$key]['title'];
	}

	public function is_waiting($key)
	{
		return ($key !== null) && (intval($key) === self::WAITING);
	}

}