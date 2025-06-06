<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Tags {

	public function get_tags($category = null, $event_data = []) {
		$tags = $this->set_tags();
		if (($category === 'order_confirmation_email') && (intval(EER()->event->eer_get_event_option($event_data, 'floating_price_enabled', -1)) !== -1)) {
			$tags[$category]['floating_price'] = [
				'id'          => 'floating_price',
				'tag'         => 'floating_price',
				'description' => __('Course price after considering all applicable discounts.', 'easy-event-registration'),
				'function'    => 'eer_tag_replace_price',
				'parameter'   => 'floating_price'
			];
		}

		if (($category != null) && (isset($tags[$category]))) {
			return $tags[$category];
		} else {
			return $category;
		}
	}


	private function set_tags() {
		$preset_tags = [
			'thank_you_page'             => [
				'registered_exists' => [
					'id'          => 'registered_exists',
					'tag'         => 'registered_exists',
					'type'        => 'double',
					'description' => __('List of registered tickets.', 'easy-event-registration'),
				],
				'list_registered'   => [
					'id'          => 'list_registered',
					'tag'         => 'list_registered',
					'description' => __('List of registered tickets.', 'easy-event-registration'),
				],
				'full_exists'       => [
					'id'          => 'full_exists',
					'tag'         => 'full_exists',
					'type'        => 'double',
					'description' => __('List of full tickets.', 'easy-event-registration'),
				],
				'list_full'         => [
					'id'          => 'list_full',
					'tag'         => 'list_full',
					'description' => __('List of full tickets.', 'easy-event-registration'),
				],
			],
			'order_email'                => [
				'event_title'          => [
					'id'          => 'title',
					'tag'         => 'event_title',
					'description' => __('Event title.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_string',
					'parameter'   => 'event_title'
				],
				'registered_exists'    => [
					'id'          => 'registered_exists',
					'tag'         => 'registered_exists',
					'type'        => 'double',
					'description' => __('List of registered tickets.', 'easy-event-registration'),
				],
				'list_registered'      => [
					'id'          => 'list_registered',
					'tag'         => 'list_registered',
					'description' => __('List of registered tickets.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_registration_ticket_list',
					'parameter'   => 'list_registered'
				],
				'name'                 => [
					'id'          => 'name',
					'tag'         => 'name',
					'description' => __('Name of user from registration.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_order_info',
					'parameter'   => 'order_info'
				],
				'surname'              => [
					'id'          => 'surname',
					'tag'         => 'surname',
					'description' => __('Surname of user from registration.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_order_info',
					'parameter'   => 'order_info'
				],
				'email'                => [
					'id'          => 'email',
					'tag'         => 'email',
					'description' => __('Email of user from registration.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_order_info',
					'parameter'   => 'order_info'
				],
				'phone'                => [
					'id'          => 'phone',
					'tag'         => 'phone',
					'description' => __('Phone of user from registration.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_order_info',
					'parameter'   => 'order_info'
				],
				'country'              => [
					'id'          => 'country',
					'tag'         => 'country',
					'description' => __('Country of user from registration.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_order_info',
					'parameter'   => 'order_info'
				],
				'note'                 => [
					'id'          => 'note',
					'tag'         => 'note',
					'description' => __('Note from registration.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_order_info',
					'parameter'   => 'order_info'
				],
				'hosting_option'       => [
					'id'          => 'hosting',
					'tag'         => 'hosting_option',
					'description' => __('Hosting option.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_string',
					'parameter'   => 'hosting_option'
				],
				'hosting_offer_option' => [
					'id'          => 'hosting_offer_option',
					'tag'         => 'hosting_offer_option',
					'description' => __('Hosting offer option.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_string',
					'parameter'   => 'hosting_offer_option'
				],
				'tshirt_option'        => [
					'id'          => 'tshirt',
					'tag'         => 'tshirt_option',
					'description' => __('T-shirt option.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_string',
					'parameter'   => 'tshirt_option'
				],
				'food_option'          => [
					'id'          => 'food',
					'tag'         => 'food_option',
					'description' => __('Food option.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_string',
					'parameter'   => 'food_option'
				],
				'custom_checkbox'      => [
					'id'          => 'custom_checkbox',
					'tag'         => 'custom_checkbox',
					'description' => __('Custom checkbox.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_string',
					'parameter'   => 'custom_checkbox'
				],
			],
			'order_confirmation_email'   => apply_filters('eer_tags_order_confirmation_email', [
				'event_title'              => [
					'id'          => 'title',
					'tag'         => 'event_title',
					'description' => __('Event title.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_string',
					'parameter'   => 'event_title'
				],
				'ticket_title'             => [
					'id'          => 'title',
					'tag'         => 'ticket_title',
					'description' => __('Ticket title.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_string',
					'parameter'   => 'ticket_title'
				],
				'price'                    => [
					'id'          => 'price',
					'tag'         => 'price',
					'description' => __('Ticket price.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_price',
					'parameter'   => 'price'
				],
				'different_currency_price' => [
					'id'          => 'different_currency_price',
					'tag'         => 'different_currency_price',
					'description' => __('Ticket different currency price.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_string',
					'parameter'   => 'different_currency_price'
				],
				'total_price'              => [
					'id'          => 'total_price',
					'tag'         => 'total_price',
					'description' => __('Total price of all tickets.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_price',
					'parameter'   => 'to_pay'
				],
				'order_code'               => [
					'id'          => 'order_code',
					'tag'         => 'order_code',
					'description' => __('Order code to identify which order is user paying.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_string',
					'parameter'   => 'order_code'
				],
				'ticket_code'               => [
					'id'          => 'ticket_code',
					'tag'         => 'ticket_code',
					'description' => __('Ticket to identify which order is user paying.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_string',
					'parameter'   => 'ticket_code'
				],
				'name'                     => [
					'id'          => 'name',
					'tag'         => 'name',
					'description' => __('Name of user from registration.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_order_info',
					'parameter'   => 'order_info'
				],
				'surname'                  => [
					'id'          => 'surname',
					'tag'         => 'surname',
					'description' => __('Surname of user from registration.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_order_info',
					'parameter'   => 'order_info'
				],
				'dancing_as'               => [
					'id'          => 'dancing_as',
					'tag'         => 'dancing_as',
					'description' => __('Dancing Role (leader/follower).', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_dancing_as',
					'parameter'   => 'dancing_as'
				],
				'has_level'                => [
					'id'          => 'has_level',
					'tag'         => 'has_level',
					'description' => __('Display text in between this tags if ticket has levels.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_has_level',
					'parameter'   => 'has_level',
					'type'        => 'double'
				],
				'level'                    => [
					'id'          => 'level',
					'tag'         => 'level',
					'description' => __('Selected level if ticket has some.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_string',
					'parameter'   => 'level'
				],
				'custom_checkbox'          => [
					'id'          => 'custom_checkbox',
					'tag'         => 'custom_checkbox',
					'description' => __('Custom checkbox.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_string',
					'parameter'   => 'custom_checkbox'
				],
			]),
			'payment_reminder_email'     => [
				'event_title'          => [
					'id'          => 'title',
					'tag'         => 'event_title',
					'description' => __('Event title.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_string',
					'parameter'   => 'event_title'
				],
				'tickets_list'         => [
					'id'          => 'tickets_list',
					'tag'         => 'tickets_list',
					'description' => __('List of tickets the student is confirmed to.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_tickets_list',
					'parameter'   => 'tickets_list'
				],
				'total_price'          => [
					'id'          => 'total_price',
					'tag'         => 'total_price',
					'description' => __('Total price of all tickets.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_price',
					'parameter'   => 'to_pay'
				],
				'order_code'           => [
					'id'          => 'order_code',
					'tag'         => 'order_code',
					'description' => __('Order code to identify which order is user paying.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_string',
					'parameter'   => 'order_code'
				],
				'name'                 => [
					'id'          => 'name',
					'tag'         => 'name',
					'description' => __('Name of user from registration.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_order_info',
					'parameter'   => 'order_info'
				],
				'surname'              => [
					'id'          => 'surname',
					'tag'         => 'surname',
					'description' => __('Surname of user from registration.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_order_info',
					'parameter'   => 'order_info'
				],
				'email'                => [
					'id'          => 'email',
					'tag'         => 'email',
					'description' => __('Email of user from registration.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_order_info',
					'parameter'   => 'order_info'
				],
				'phone'                => [
					'id'          => 'phone',
					'tag'         => 'phone',
					'description' => __('Phone of user from registration.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_order_info',
					'parameter'   => 'order_info'
				],
				'country'              => [
					'id'          => 'country',
					'tag'         => 'country',
					'description' => __('Country of user from registration.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_order_info',
					'parameter'   => 'order_info'
				],
				'note'                 => [
					'id'          => 'note',
					'tag'         => 'note',
					'description' => __('Note from registration.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_order_info',
					'parameter'   => 'order_info'
				],
				'hosting_option'       => [
					'id'          => 'hosting',
					'tag'         => 'hosting_option',
					'description' => __('Hosting option.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_string',
					'parameter'   => 'hosting_option'
				],
				'hosting_offer_option' => [
					'id'          => 'hosting_offer_option',
					'tag'         => 'hosting_offer_option',
					'description' => __('Hosting offer option.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_string',
					'parameter'   => 'hosting_offer_option'
				],
				'tshirt_option'        => [
					'id'          => 'tshirt',
					'tag'         => 'tshirt_option',
					'description' => __('T-shirt option.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_string',
					'parameter'   => 'tshirt_option'
				],
				'food_option'          => [
					'id'          => 'food',
					'tag'         => 'food_option',
					'description' => __('Food option.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_string',
					'parameter'   => 'food_option'
				],
			],
			'payment_confirmation_email' =>  apply_filters('eer_tags_email_payment_confirmation', [
				'event_title'  => [
					'id'          => 'title',
					'tag'         => 'event_title',
					'description' => __('Event title.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_string',
					'parameter'   => 'event_title'
				],
				'tickets_list' => [
					'id'          => 'tickets_list',
					'tag'         => 'tickets_list',
					'description' => __('List of tickets the student is confirmed to.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_tickets_numbers_list',
					'parameter'   => 'tickets_list'
				],
				'name'         => [
					'id'          => 'name',
					'tag'         => 'name',
					'description' => __('Name of user from registration.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_order_info',
					'parameter'   => 'order_info'
				],
				'surname'      => [
					'id'          => 'surname',
					'tag'         => 'surname',
					'description' => __('Surname of user from registration.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_order_info',
					'parameter'   => 'order_info'
				],
				'email'        => [
					'id'          => 'email',
					'tag'         => 'email',
					'description' => __('Email of user from registration.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_order_info',
					'parameter'   => 'order_info'
				],
				'phone'        => [
					'id'          => 'phone',
					'tag'         => 'phone',
					'description' => __('Phone of user from registration.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_order_info',
					'parameter'   => 'order_info'
				],
				'country'      => [
					'id'          => 'country',
					'tag'         => 'country',
					'description' => __('Country of user from registration.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_order_info',
					'parameter'   => 'order_info'
				],
				'note'         => [
					'id'          => 'note',
					'tag'         => 'note',
					'description' => __('Note from registration.', 'easy-event-registration'),
					'function'    => 'eer_tag_replace_order_info',
					'parameter'   => 'order_info'
				],
			]),
			'sale_not_opened'            => [
				'sale_start' => [
					'id'          => 'sale_start',
					'tag'         => 'sale_start',
					'description' => __('Date and time of event sale.', 'easy-event-registration'),
				]

			],
		];

		return apply_filters('eer_registered_tags', $preset_tags);
	}

}
