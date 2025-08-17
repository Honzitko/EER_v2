<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Ticket {

	private $fields;


	public function __construct() {
		$this->fields = new EER_Fields();

		$this->fields->add_field('event_id', 'int', true);
		$this->fields->add_field('title', 'string', true);
		$this->fields->add_field('price', 'int', true);
		$this->fields->add_field('max_per_order', 'int', true);
		$this->fields->add_field('sold_separately', 'boolean', false);
		$this->fields->add_field('once_per_user', 'boolean', false);
		$this->fields->add_field('position', 'int', true);
		$this->fields->add_field('is_solo', 'boolean', false);
		$this->fields->add_field('max_leaders', 'int', false);
		$this->fields->add_field('max_followers', 'int', false);
		$this->fields->add_field('max_tickets', 'int', false);
		$this->fields->add_field('ticket_settings', 'json', false);
		$this->fields->add_field('pairing_mode', 'int', false);
	}


	/**
	 * Retrieve the array of plugin settings
	 *
	 * @return array
	 */
	public function eer_get_ticket_settings_fields() {
		$eer_ticket_settings = [
			/** General Settings */
			'general'      => apply_filters('eer_ticket_settings_general', [
				'gmain'  => [
					'content' => [
						'id'          => 'content',
						'name'        => __('Content', 'easy-event-registration'),
						'type'        => 'full_editor',
						'field_class' => 'eer-input'
					],
					'disable_partner_check' => [
						'id'          => 'disable_partner_check',
						'name'        => __('Disable Partner Check', 'easy-event-registration'),
						'desc'        => '',
						'type'        => 'checkbox',
						'std'         => false,
						'field_class' => 'eer-input'
					],
					'different_price'          => [
						'id'   => 'different_price',
						'name' => __('Different currency price', 'easy-event-registration'),
						'type' => 'text',
					],
				],
				'levels' => [
					'levels_enabled' => [
						'id'          => 'levels_enabled',
						'name'        => __('Enable levels', 'easy-event-registration'),
						'desc'        => '',
						'type'        => 'checkbox',
						'std'         => false,
						'field_class' => 'eer-input'
					],
					'levels'         => [
						'id'       => 'levels',
						'name'     => __('Levels', 'easy-event-registration'),
						'type'     => 'add_list_levels',
						'singular' => __('Level', 'easy-event-registration'),
					],
				],
			]),
			'waiting_list' => apply_filters('eer_ticket_settings_waiting_list', [
				'wlmain' => [
					'waiting_list_enabled' => [
						'id'          => 'waiting_list_enabled',
						'name'        => __('Enable waiting list', 'easy-event-registration'),
						'desc'        => '',
						'type'        => 'checkbox',
						'std'         => true,
						'field_class' => 'eer-input'
					],
					'waiting_list_limit'   => [
						'id'   => 'waiting_list_limit',
						'name' => __('Limit', 'easy-event-registration'),
						'type' => 'number',
					],
				],
			])
		];

		return apply_filters('eer_registered_ticket_settings', $eer_ticket_settings);
	}


	public function eer_get_ticket_settings_fields_to_print($section_id, $sub_section_id) {
		$sections = $this->eer_get_ticket_settings_fields();

		if (isset($sections[$section_id][$sub_section_id])) {
			return $sections[$section_id][$sub_section_id];
		}

		return [];
	}


	public function eer_get_ticket_settings_tabs() {
		$tabs            = [];
		$tabs['general'] = __('General', 'easy-event-registration');

		return apply_filters('eer_ticket_settings_tabs', $tabs);
	}


	public function eer_get_ticket_settings_tab($tab) {
		$tabs = $this->eer_get_ticket_settings_tabs();

		return isset($tabs[$tab]) ? $tabs[$tab] : $tab;
	}


	public function eer_get_ticket_settings_tab_sections($tab = false) {

		$tabs     = false;
		$sections = $this->eer_get_ticket_settings_sections();

		if ($tab && !empty($sections[$tab])) {
			$tabs = $sections[$tab];
		} elseif ($tab) {
			$tabs = false;
		}

		return $tabs;
	}


	public function eer_get_ticket_settings_sections() {

		static $sections = false;

		if (false !== $sections) {
			return $sections;
		}

		$sections = [
			'general'   => apply_filters('eer_settings_sections_general', [
				'gmain'  => __('General', 'easy-event-registration'),
				'levels' => __('Levels', 'easy-event-registration'),
			]),
		];

		$sections = apply_filters('eer_ticket_settings_sections', $sections);

		return $sections;
	}


	/**
	 * @return object
	 */
	public function get_fields() {
		return $this->fields->get_fields();
	}


	/**
	 * Loads all events
	 * @return array|null|object
	 */
	public function load_tickets() {
		global $wpdb;
		$return = [];

		//if ( current_user_can( 'eer_partial_events_view' ) ) {
		//	$tickets = $wpdb->get_results( $wpdb->prepare( "SELECT t.* FROM {$wpdb->prefix}eer_tickets AS t JOIN {$wpdb->prefix}eer_event_managers AS em ON em.event_id = t.event_id WHERE em.user_id = %d ORDER BY t.id DESC", get_current_user_id() ), OBJECT_K );
		//} else {
			$tickets = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}eer_tickets ORDER BY id DESC", OBJECT_K);
		//}

		foreach ($tickets as $id => $ticket) {
			$settings = $ticket->ticket_settings;
			unset($ticket->ticket_settings);
			$ticket              = (object) array_merge((array) $ticket, (array) json_decode($settings, true));
			$return[$ticket->id] = $ticket;
		}

		return $return;
	}


	/**
	 * Loads all events
	 *
	 * @param int $event_id
	 *
	 * @return array|null|object
	 */
	public function get_tickets_by_event($event_id) {
		global $wpdb;
		$return = [];

		$tickets = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}eer_tickets WHERE event_id = %d ORDER BY position ASC, id", [$event_id]), OBJECT_K);

		foreach ($tickets as $id => $ticket) {
			$settings = $ticket->ticket_settings;
			unset($ticket->ticket_settings);
			$ticket              = (object) array_merge((array) $ticket, (array) json_decode($settings, true));
			$return[$ticket->id] = $ticket;
		}

		return $return;
	}


	/**
	 * Check if ticket exists
	 *
	 * @param int $ticket_id
	 *
	 * @return boolean
	 */
	public function eer_check_ticket_exists($ticket_id) {
		global $wpdb;

		return intval($wpdb->get_var($wpdb->prepare("SELECT EXISTS(SELECT * FROM {$wpdb->prefix}eer_tickets WHERE id = %d)", [$ticket_id]))) === 1;
	}


       /**
        * Check if ticket is available for purchase.
        *
        * @param int        $ticket_id
        * @param object|null $ticket_data Optional ticket data to avoid extra DB call.
        * @param int|null    $level_id    Optional level ID.
        *
        * @return bool
        */
       public function is_ticket_buy_enabled($ticket_id, $ticket_data = null, $level_id = null) {
               if (!$ticket_data) {
                       $ticket_data = $this->get_ticket_data($ticket_id);
               }

               if (intval($ticket_data->is_solo) === 1) {
                       return EER()->dancing_as->eer_is_solo_registration_enabled($ticket_id, $level_id);
               } else {
                       return EER()->dancing_as->eer_is_leader_registration_enabled($ticket_id, $level_id) ||
                               EER()->dancing_as->eer_is_followers_registration_enabled($ticket_id, $level_id);
               }
       }


	public function get_ticket_data($ticket_id) {
		if ($ticket_id !== null) {
			global $wpdb;
			$ticket = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}eer_tickets WHERE id = %d", [(int) $ticket_id]), OBJECT);

			if ($ticket) {
				$settings = $ticket->ticket_settings;
				unset($ticket->ticket_settings);

				return (object) array_merge((array) $ticket, (array) json_decode($settings, true));
			}

			return $ticket;
		}

		return null;
	}


	public function eer_is_solo($ticket_id, $ticket_data = null) {
		if (!$ticket_data) {
			$ticket_data = $this->get_ticket_data($ticket_id);
		}

		return $ticket_data->is_solo;
	}


	public function eer_get_ticket_option($ticket_data, $key = '', $default = false) {
		$value = !empty($ticket_data->$key) ? $ticket_data->$key : $default;

		return apply_filters('eer_get_ticket_option_' . $key, $value, $key, $default);
	}
}