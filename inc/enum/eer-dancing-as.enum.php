<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Enum_Dancing_As
{
	const
		LEADER = 0, FOLLOWER = 1, SOLO = 2;

	private $items = [];


	/**
	 * @codeCoverageIgnore
	 */
	public function __construct()
	{
		$this->items = [
			self::LEADER => [
				'title' => __('Leader', 'easy-event-registration'),
			],
			self::FOLLOWER => [
				'title' => __('Follower', 'easy-event-registration'),
			],
			self::SOLO => [
				'title' => __('Solo', 'easy-event-registration')
			]
		];
	}


	public function eer_get_items()
	{
		return $this->items;
	}


	public function eer_get_couple_items()
	{
		$items = $this->eer_get_items();
		unset($items[self::SOLO]);
		return $items;
	}


	public function eer_get_item($key)
	{
		return $this->eer_get_items()[$key];
	}


	public function eer_get_title($key)
	{
		return $this->eer_get_item($key)['title'];
	}


	/**
	 * @param int $key
	 *
	 * @return bool
	 */
	public function eer_is_leader($key)
	{
		return ($key !== null) && ($key == self::LEADER);
	}


	/**
	 * @param int $key
	 *
	 * @return bool
	 */
	public function eer_is_solo($key)
	{
		return ($key !== null) && ($key == self::SOLO);
	}


	/**
	 * @param int $key
	 *
	 * @return bool
	 */
	public function eer_is_follower($key)
	{
		return ($key !== null) && ($key == self::FOLLOWER);
	}


	/**
	 * @param int $ticket_id
	 * @param int $level_id
	 *
	 * @return bool
	 */
	public function eer_is_leader_registration_enabled($ticket_id, $level_id = NULL)
	{
		global $wpdb;

		if (is_null($level_id)) {
			return filter_var($wpdb->get_var($wpdb->prepare("SELECT SUM(max_leaders) > SUM(registered_leaders) FROM {$wpdb->prefix}eer_ticket_summary WHERE ticket_id = %d", [intval($ticket_id)])), FILTER_VALIDATE_BOOLEAN);
		} else {
			return filter_var($wpdb->get_var($wpdb->prepare("SELECT SUM(max_leaders) > SUM(registered_leaders) FROM {$wpdb->prefix}eer_ticket_summary WHERE ticket_id = %d AND level_id = %d", [$ticket_id, $level_id])), FILTER_VALIDATE_BOOLEAN);
		}
	}


	/**
	 * @param int $ticket_id
	 * @param int $level_id
	 *
	 * @return bool
	 */
	public function eer_is_followers_registration_enabled($ticket_id, $level_id = NULL)
	{
		global $wpdb;

		if (is_null($level_id)) {
			return filter_var($wpdb->get_var($wpdb->prepare("SELECT SUM(max_followers) > SUM(registered_followers) FROM {$wpdb->prefix}eer_ticket_summary WHERE ticket_id = %d", [intval($ticket_id)])), FILTER_VALIDATE_BOOLEAN);
		} else {
			return filter_var($wpdb->get_var($wpdb->prepare("SELECT SUM(max_followers) > SUM(registered_followers) FROM {$wpdb->prefix}eer_ticket_summary WHERE ticket_id = %d AND level_id = %d", [$ticket_id, $level_id])), FILTER_VALIDATE_BOOLEAN);
		}
	}


	/**
	 * @param int $ticket_id
	 * @param int $level_id
	 *
	 * @return bool
	 */
	public function eer_is_solo_registration_enabled($ticket_id, $level_id = NULL)
	{
		global $wpdb;

		if (is_null($level_id)) {
			return filter_var($wpdb->get_var($wpdb->prepare("SELECT max_tickets > registered_tickets FROM {$wpdb->prefix}eer_ticket_summary WHERE ticket_id = %d", [intval($ticket_id)])), FILTER_VALIDATE_BOOLEAN);
		} else {
			return filter_var($wpdb->get_var($wpdb->prepare("SELECT 1 FROM {$wpdb->prefix}eer_ticket_summary WHERE ticket_id = %d AND level_id = %d AND max_tickets > registered_tickets", [$ticket_id, $level_id])), FILTER_VALIDATE_BOOLEAN);
		}
	}
}