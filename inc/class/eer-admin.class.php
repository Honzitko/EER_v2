<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Admin
{

	const ADMIN_MENU_SLUG = 'eer_event_admin';

	public static function eer_add_admin_menu()
	{
		add_menu_page(__('Easy Events', 'easy-event-registration'), __('Easy Events', 'easy-event-registration'), 'eer_school', self::ADMIN_MENU_SLUG, ['EER_Template_Easy_Event', 'print_page'], 'dashicons-welcome-learn-more', 2);

		add_submenu_page(EER_Admin::ADMIN_MENU_SLUG, __('Events', 'easy-event-registration'), __('Events', 'easy-event-registration'), 'eer_event_view', EER_Template_Event::MENU_SLUG, ['EER_Template_Event', 'print_content']);
		add_submenu_page(EER_Admin::ADMIN_MENU_SLUG, __('Tickets', 'easy-event-registration'), __('Tickets', 'easy-event-registration'), 'eer_ticket_view', EER_Template_Ticket::MENU_SLUG, ['EER_Template_Ticket', 'print_content']);
		add_submenu_page(EER_Admin::ADMIN_MENU_SLUG, __('Orders', 'easy-event-registration'), __('Orders', 'easy-event-registration'), 'eer_order_view', EER_Template_Order::MENU_SLUG, ['EER_Template_Order', 'print_content']);
		add_submenu_page(EER_Admin::ADMIN_MENU_SLUG, __('Sold tickets', 'easy-event-registration'), __('Sold tickets', 'easy-event-registration'), 'eer_sold_ticket_view', EER_Template_Sold_Ticket::MENU_SLUG, ['EER_Template_Sold_Ticket', 'print_content']);
		add_submenu_page(EER_Admin::ADMIN_MENU_SLUG, __('Tickets in numbers', 'easy-event-registration'), __('Tickets in numbers', 'easy-event-registration'), 'eer_tickets_in_numbers_view', EER_Template_Tickets_In_Numbers::MENU_SLUG, ['EER_Template_Tickets_In_Numbers', 'print_content']);
		add_submenu_page(EER_Admin::ADMIN_MENU_SLUG, __('Payments', 'easy-event-registration'), __('Payments', 'easy-event-registration'), 'eer_payment_view', EER_Template_Payments::MENU_SLUG, ['EER_Template_Payments', 'print_content']);
		add_submenu_page(EER_Admin::ADMIN_MENU_SLUG, __('Add Over Limit', 'easy-event-registration'), __('Add Over Limit', 'easy-event-registration'), 'eer_add_over_limit_view', EER_Template_Add_Over_Limit::MENU_SLUG, ['EER_Template_Add_Over_Limit', 'print_content']);
		add_submenu_page(EER_Admin::ADMIN_MENU_SLUG, __('Payment emails', 'easy-event-registration'), __('Payment emails', 'easy-event-registration'), 'eer_payment_emails_view', EER_Template_Payment_Emails::MENU_SLUG, ['EER_Template_Payment_Emails', 'print_content']);

		add_submenu_page(EER_Admin::ADMIN_MENU_SLUG, __('Settings', 'easy-event-registration'), __('Settings', 'easy-event-registration'), 'eer_settings', EER_Template_Settings::MENU_SLUG, ['EER_Template_Settings', 'print_content']);

		do_action('eer_add_admin_menu');
	}

}

add_action('admin_menu', ['EER_Admin', 'eer_add_admin_menu'], 10);
