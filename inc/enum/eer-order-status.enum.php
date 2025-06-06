<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Enum_Order_Status
{

	const ORDERED = 0,
		DELETED = 1;

	public $items = [];


	/**
	 * @codeCoverageIgnore
	 */
	public function __construct()
	{
		$this->items = [
			self::ORDERED => [
				'title' => __('Ordered', 'easy-event-registration')
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


}