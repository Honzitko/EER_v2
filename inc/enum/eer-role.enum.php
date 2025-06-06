<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EER_Role {
	const
		EVENT_MANAGER = 1;

	public static function eer_get_items_callback() {
		return [
			self::EVENT_MANAGER => [
				'key'          => 'eer_event_manager',
				'title'        => 'Event Manager',
				'capabilities' => [
					'eer_school'                  => true,
					'eer_partial_events_view'     => true,
					'eer_add_over_limit_edit'     => true,
					'eer_add_over_limit_view'     => true,
					'eer_event_edit'              => true,
					'eer_event_view'              => true,
					'eer_event_add'               => true,
					'read'                        => true,
					'eer_ticket_edit'             => true,
					'eer_ticket_view'             => true,
					'eer_order_edit'              => true,
					'eer_order_view'              => true,
					'eer_payment_edit'            => true,
					'eer_payment_view'            => true,
					'eer_payment_emails_view'     => true,
					'eer_sold_ticket_edit'        => true,
					'eer_sold_ticket_view'        => true,
					'eer_tickets_in_numbers_view' => true,
				]
			],
		];
	}

	/**
	 * @codeCoverageIgnore
	 */
	public static function init() {
		foreach ( apply_filters( 'eer_get_roles', [] ) as $key => $role ) {
			if ( ! get_role( $role['key'] ) ) {
				add_role( $role['key'], $role['title'], $role['capabilities'] );
			} else {
				$existing_role = get_role( $role['key'] );
				foreach ( $role['capabilities'] as $role_key => $cap ) {
					$existing_role->add_cap( $role_key, $cap );
				}
			}
		}


		//add capabilities for admin
		$admin        = get_role( 'administrator' );
		$capabilities = [
			'eer_all_events_view'         => true,
			'eer_add_over_limit_edit'     => true,
			'eer_add_over_limit_view'     => true,
			'eer_event_edit'              => true,
			'eer_event_view'              => true,
			'eer_event_add'               => true,
			'eer_ticket_edit'             => true,
			'eer_ticket_view'             => true,
			'eer_order_edit'              => true,
			'eer_order_view'              => true,
			'eer_payment_edit'            => true,
			'eer_payment_view'            => true,
			'eer_payment_emails_view'     => true,
			'eer_sold_ticket_edit'        => true,
			'eer_sold_ticket_view'        => true,
			'eer_tickets_in_numbers_view' => true,
			'eer_school'                  => true,
			'eer_settings'                => true,
		];

		foreach ( $capabilities as $key => $cap ) {
			$admin->add_cap( $key, $cap );
		}
	}
}

add_action( 'init', [ 'EER_Role', 'init' ] );

add_filter( 'eer_get_roles', [ 'EER_Role', 'eer_get_items_callback' ] );