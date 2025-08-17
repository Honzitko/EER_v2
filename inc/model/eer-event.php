<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EER_Event {

	private $fields;


	public function __construct() {
		$this->fields = new EER_Fields();

		$this->fields->add_field( 'title', 'string', true );
		$this->fields->add_field( 'sale_start', 'timestamp', false );
		$this->fields->add_field( 'sale_end', 'timestamp', false );
		$this->fields->add_field( 'event_settings', 'json', false );
	}


	/**
	 * Retrieve the array of plugin settings
	 *
	 * @return array
	 */
	public function eer_get_event_settings_fields( $event_data = [] ) {
		$template_settings_tag = new EER_Template_Settings_Tag();

		$eer_event_settings = [
			/** General Settings */
			'event_general'     => apply_filters( 'eer_event_settings_general', [
				'general_currency'        => [
					'currency'          => [
						'id'      => 'currency',
						'name'    => __( 'Currency', 'easy-event-registration' ),
						'desc'    => __( 'Choose your currency.', 'easy-event-registration' ),
						'type'    => 'select',
						'options' => EER()->currency->eer_get_currencies(),
						'chosen'  => true,
						'std'     => 'USD',
					],
					'currency_position' => [
						'id'      => 'currency_position',
						'name'    => __( 'Currency Position', 'easy-event-registration' ),
						'desc'    => __( 'Choose the position of the currency symbol.', 'easy-event-registration' ),
						'type'    => 'select',
						'options' => [
							'before'            => __( 'Before - $10', 'easy-event-registration' ),
							'before_with_space' => __( 'Before with space - $ 10', 'easy-event-registration' ),
							'after'             => __( 'After - 10$', 'easy-event-registration' ),
							'after_with_space'  => __( 'After with space - 10 $', 'easy-event-registration' ),
						],
						'std'     => 'after_with_space',
					]
				],
				'general_sale_not_opened' => [
					'show_tickets'    => [
						'id'          => 'show_tickets',
						'name'        => __( 'Show Tickets', 'easy-event-registration' ),
						'desc'        => 'If selected, the tickets will be visible but still not open for sale.',
						'type'        => 'checkbox',
						'std'         => true,
						'field_class' => 'eer-input'
					],
					'sale_not_opened' => [
						'id'          => 'sale_not_opened',
						'name'        => __( 'Sale Not Opened Yet', 'easy-event-registration' ),
						'desc'        => __( 'Available template tags:', 'easy-event-registration' ),
						'desc_tags'   => $template_settings_tag->print_content( EER()->tags->get_tags( 'sale_not_opened' ) ),
						'type'        => 'full_editor',
						'field_class' => 'eer-input'
					],
				],
				'general_sale_closed'     => [
					'sale_closed' => [
						'id'          => 'sale_closed',
						'name'        => __( 'Sale Closed', 'easy-event-registration' ),
						'type'        => 'full_editor',
						'field_class' => 'eer-input'
					],
				],
			] ),
			'registration_form' => apply_filters( 'eer_event_settings_emails', [
				'rfmain'           => [
					'partner_name_enabled'  => [
						'id'   => 'partner_name_enabled',
						'name' => __( 'Enable Partner Name', 'easy-event-registration' ),
						'type' => 'checkbox',
					],
					'partner_name_required' => [
						'id'   => 'partner_name_required',
						'name' => __( 'Partner Name Required', 'easy-event-registration' ),
						'type' => 'checkbox',
					],
					'phone_enabled'         => [
						'id'   => 'phone_enabled',
						'name' => __( 'Enable Phone Number', 'easy-event-registration' ),
						'type' => 'checkbox',
					],
					'phone_required'        => [
						'id'   => 'phone_required',
						'name' => __( 'Phone Number Required', 'easy-event-registration' ),
						'type' => 'checkbox',
					],
					'country_enabled'       => [
						'id'   => 'country_enabled',
						'name' => __( 'Enable Country Selector', 'easy-event-registration' ),
						'type' => 'checkbox',
					],
					'country_required'      => [
						'id'   => 'country_required',
						'name' => __( 'Country Required', 'easy-event-registration' ),
						'type' => 'checkbox',
					],
                    'note_enabled'       => [
                        'id'   => 'note_enabled',
                        'name' => __( 'Is Note field enabled?', 'easy-event-registration' ),
                        'type' => 'checkbox',
                        'std'  => true
                    ],
				],
				'form_texts'       => [
					'note_title'          => [
						'id'   => 'note_title',
						'name' => __( 'Note Title', 'easy-event-registration' ),
						'type' => 'text',
						'std'  => __( 'Note', 'easy-event-registration' ),
					],
					'note_required'       => [
						'id'   => 'note_required',
						'name' => __( 'Is note required?', 'easy-event-registration' ),
						'desc' => __( 'If enabled, Note will be required to finish registration.', 'easy-event-registration' ),
						'type' => 'checkbox',
					],
					'note_description'    => [
						'id'   => 'note_description',
						'name' => __( 'Note Description', 'easy-event-registration' ),
						'type' => 'full_editor',
					],
					'registration_button' => [
						'id'   => 'registration_button',
						'name' => __( 'Registration Button', 'easy-event-registration' ),
						'type' => 'text',
						'std'  => 'Submit',
					],
				],
				'terms_conditions' => [
					'terms_conditions_enabled'  => [
						'id'   => 'terms_conditions_enabled',
						'name' => __( 'Enable Terms & Conditions', 'easy-event-registration' ),
						'desc' => __( 'If enabled, Terms & Conditions checkbox will be displayed during registration.', 'easy-event-registration' ),
						'type' => 'checkbox',
					],
					'terms_conditions_required' => [
						'id'   => 'terms_conditions_required',
						'name' => __( 'Require Terms & Conditions', 'easy-event-registration' ),
						'desc' => __( 'If enabled, Terms & Conditions confirmation will be required to finish registration.', 'easy-event-registration' ),
						'type' => 'checkbox',
					],
					'terms_conditions_text'     => [
						'id'   => 'terms_conditions_text',
						'name' => __( 'Terms & Conditions Text', 'easy-event-registration' ),
						'type' => 'full_editor',
					],
				],
				'thank_you_text'   => [
					'thank_you' => [
						'id'          => 'thank_you',
						'name'        => __( 'Thank you text', 'easy-event-registration' ),
						'desc'        => __( 'Available template tags:', 'easy-event-registration' ),
						'desc_tags'   => $template_settings_tag->print_content( EER()->tags->get_tags( 'thank_you_page' ) ),
						'type'        => 'full_editor',
						'field_class' => 'eer-input'
					],
				],
				'hosting'          => [
					'hosting_enabled'       => [
						'id'   => 'hosting_enabled',
						'name' => __( 'Interested In Hosting', 'easy-event-registration' ),
						'desc' => __( 'If enabled, a checkbox will appear in the Registration Form allowing users to indicate interest in getting hosting.', 'easy-event-registration' ),
						'type' => 'checkbox',
					],
					'hosting_text'          => [
						'id'   => 'hosting_text',
						'name' => __( 'Interested In Hosting Text', 'easy-event-registration' ),
						'type' => 'full_editor',
					],
					'offer_hosting_enabled' => [
						'id'   => 'offer_hosting_enabled',
						'name' => __( 'Offering Hosting', 'easy-event-registration' ),
						'desc' => __( 'If enabled, a checkbox will appear in the Registration Form allowing local students to offer hosting.', 'easy-event-registration' ),
						'type' => 'checkbox',
					],
					'offer_hosting_text'    => [
						'id'   => 'offer_hosting_text',
						'name' => __( 'Offering Hosting Text', 'easy-event-registration' ),
						'type' => 'full_editor',
					],
				],
				'rf_tshirts'       => [
					'tshirts_enabled'    => [
						'id'   => 'tshirts_enabled',
						'name' => __( 'Enable T-Shirts Selection', 'easy-event-registration' ),
						'desc' => __( 'If enabled, a selection of predefined T-Shirt options will appear in the Registration Form.', 'easy-event-registration' ),
						'type' => 'checkbox',
					],
					'tshirt_description' => [
						'id'          => 'tshirt_description',
						'name'        => __( 'T-shirt Description', 'easy-event-registration' ),
						'type'        => 'full_editor',
						'field_class' => 'eer-input'
					],
					'tshirt_options'     => [
						'id'       => 'tshirt_options',
						'name'     => __( 'T-shirt Options', 'easy-event-registration' ),
						'type'     => 'add_list_tshirts',
						'singular' => __( 'T-shirt', 'easy-event-registration' ),
					],
				],
				'food'             => [
					'food_enabled'     => [
						'id'   => 'food_enabled',
						'name' => __( 'Enable Food Selection', 'easy-event-registration' ),
						'desc' => __( 'If enabled, a selection of predefined food options will appear in the Registration Form.', 'easy-event-registration' ),
						'type' => 'checkbox',
					],
					'food_required'    => [
						'id'   => 'food_required',
						'name' => __( 'Is Required?', 'easy-event-registration' ),
						'type' => 'checkbox',
					],
					'food_title'       => [
						'id'   => 'food_title',
						'name' => __( 'Title', 'easy-event-registration' ),
						'type' => 'text',
					],
					'food_table_title' => [
						'id'   => 'food_table_title',
						'name' => __( 'Table Title', 'easy-event-registration' ),
						'type' => 'text',
					],
					'food_description' => [
						'id'          => 'food_description',
						'name'        => __( 'Food Description', 'easy-event-registration' ),
						'type'        => 'full_editor',
						'field_class' => 'eer-input'
					],
					'food_options'     => [
						'id'       => 'food_options',
						'name'     => __( 'Food Options', 'easy-event-registration' ),
						'type'     => 'add_list_food',
						'singular' => __( 'Food', 'easy-event-registration' ),
					],
				],
				'custom_checkbox'  => [
					'custom_checkbox_enabled'     => [
						'id'   => 'custom_checkbox_enabled',
						'name' => __( 'Enable Custom Checkbox', 'easy-event-registration' ),
						'type' => 'checkbox',
					],
					'custom_checkbox_table_title' => [
						'id'   => 'custom_checkbox_table_title',
						'name' => __( 'Table Title', 'easy-event-registration' ),
						'type' => 'text',
					],
					'custom_checkbox_description' => [
						'id'          => 'custom_checkbox_description',
						'name'        => __( 'Description', 'easy-event-registration' ),
						'type'        => 'full_editor',
						'field_class' => 'eer-input'
					],
				],
				'custom_area'      => [
					'custom_area_enabled'     => [
						'id'   => 'custom_area_enabled',
						'name' => __( 'Enable Custom TextArea', 'easy-event-registration' ),
						'type' => 'checkbox',
					],
					'custom_area_required'    => [
						'id'   => 'custom_area_required',
						'name' => __( 'Is Required?', 'easy-event-registration' ),
						'type' => 'checkbox',
					],
					'custom_area_title'       => [
						'id'   => 'custom_area_title',
						'name' => __( 'Title', 'easy-event-registration' ),
						'type' => 'text',
					],
					'custom_area_table_title' => [
						'id'   => 'custom_area_table_title',
						'name' => __( 'Table Title', 'easy-event-registration' ),
						'type' => 'text',
					],
					'custom_area_description' => [
						'id'          => 'custom_area_description',
						'name'        => __( 'Description', 'easy-event-registration' ),
						'type'        => 'full_editor',
						'field_class' => 'eer-input'
					],
				],
			] ),
			/** Emails Settings */
			'emails'            => apply_filters( 'eer_event_settings_emails', [
				'emain'                      => [
					'from_name'              => [
						'id'          => 'from_name',
						'name'        => __( 'From Name', 'easy-event-registration' ),
						'desc'        => __( 'Sender name for automated emails. This should ideally be your site name.', 'easy-event-registration' ),
						'type'        => 'text',
						'std'         => get_bloginfo( 'name' ),
						'allow_blank' => false,
					],
					'from_email'             => [
						'id'          => 'from_email',
						'name'        => __( 'From Email', 'easy-event-registration' ),
						'desc'        => __( 'Email address for automated emails. It will also act as "from" and "reply-to" address.', 'easy-event-registration' ),
						'type'        => 'email',
						'std'         => get_bloginfo( 'admin_email' ),
						'allow_blank' => false,
					],
					'bcc_email'              => [
						'id'          => 'bcc_email',
						'name'        => __( 'Bcc Email Address', 'easy-event-registration' ),
						'desc'        => __( 'Email address to receive a secret copy of every email sent by the system. Allows you to back up and re-send any email.', 'easy-event-registration' ),
						'type'        => 'email',
						'allow_blank' => true
					],
					'floating_price_enabled' => [
						'id'   => 'floating_price_enabled',
						'name' => __( 'Enable Floating Price', 'easy-event-registration' ),
						'desc' => __( 'If enabled, floting_price tag will be available for Event Confirmation Emails.', 'easy-event-registration' ),
						'type' => 'checkbox',
						'std'  => false,
					],
					'use_wp_mail'            => [
						'id'   => 'use_wp_mail',
						'name' => __( 'Use wp_mail', 'easy-event-registration' ),
						'desc' => __( 'This plugin is using PHP mail function to sending emails. If you want you can change it to Wordpress wp_mail function.', 'easy-event-registration' ),
						'type' => 'checkbox',
						'std'  => false,
					],
				],
				'order_email'                => [
					'order_email_enabled' => [
						'id'          => 'order_email_enabled',
						'name'        => __( 'Enable Registration Overview Email', 'easy-event-registration' ),
						'desc'        => 'If enabled, an overview email of registered courses will be sent.',
						'type'        => 'checkbox',
						'std'         => true,
						'field_class' => 'eer-input'
					],
					'order_email_subject' => [
						'id'   => 'order_email_subject',
						'name' => __( 'Email Subject', 'easy-event-registration' ),
						'type' => 'text',
					],
					'order_email_body'    => [
						'id'          => 'order_email_body',
						'name'        => __( 'Email Body', 'easy-event-registration' ),
						'desc'        => __( 'Available template tags:', 'easy-event-registration' ),
						'desc_tags'   => $template_settings_tag->print_content( EER()->tags->get_tags( 'order_email', $event_data ) ),
						'type'        => 'full_editor',
						'field_class' => 'eer-input'
					],
				],
				'order_confirmation_email'   => [
					'order_confirmation_email_enabled' => [
						'id'          => 'order_confirmation_email_enabled',
						'name'        => __( 'Enable Confirmation Emails', 'easy-event-registration' ),
						'desc'        => 'If enabled, a confirmation email for confirmed tickets will be sent.',
						'type'        => 'checkbox',
						'std'         => true,
						'field_class' => 'eer-input'
					],
					'order_confirmation_email_subject' => [
						'id'   => 'order_confirmation_email_subject',
						'name' => __( 'Email Subject', 'easy-event-registration' ),
						'type' => 'text',
					],
					'order_confirmation_email_body'    => [
						'id'          => 'order_confirmation_email_body',
						'name'        => __( 'Email Body', 'easy-event-registration' ),
						'desc'        => __( 'Available template tags:', 'easy-event-registration' ),
						'desc_tags'   => $template_settings_tag->print_content( EER()->tags->get_tags( 'order_confirmation_email', $event_data ) ),
						'type'        => 'full_editor',
						'field_class' => 'eer-input'
					],
				],
				'payment_reminder_email'     => [
					'payment_reminder_email_enabled' => [
						'id'          => 'payment_reminder_email_enabled',
						'name'        => __( 'Enable Payment Reminder Emails', 'easy-event-registration' ),
						'desc'        => 'If enabled, Payment Reminder Emails can be sent via Payment section.',
						'type'        => 'checkbox',
						'std'         => true,
						'field_class' => 'eer-input'
					],
					'payment_reminder_email_subject' => [
						'id'   => 'payment_reminder_email_subject',
						'name' => __( 'Email Subject', 'easy-event-registration' ),
						'type' => 'text',
					],
					'payment_reminder_email_body'    => [
						'id'          => 'payment_reminder_email_body',
						'name'        => __( 'Email Body', 'easy-event-registration' ),
						'desc'        => __( 'Available template tags:', 'easy-event-registration' ),
						'desc_tags'   => $template_settings_tag->print_content( EER()->tags->get_tags( 'payment_reminder_email', $event_data ) ),
						'type'        => 'full_editor',
						'field_class' => 'eer-input'
					],
				],
				'payment_confirmation_email' => [
					'payment_confirmation_email_enabled' => [
						'id'          => 'payment_confirmation_email_enabled',
						'name'        => __( 'Enable Payment Confirmation Emails', 'easy-event-registration' ),
						'desc'        => 'If enabled, Payment Confirmation Emails can be sent via Payment section.',
						'type'        => 'checkbox',
						'std'         => true,
						'field_class' => 'eer-input'
					],
					'payment_confirmation_email_subject' => [
						'id'   => 'payment_confirmation_email_subject',
						'name' => __( 'Email Subject', 'easy-event-registration' ),
						'type' => 'text',
					],
					'payment_confirmation_email_body'    => [
						'id'          => 'payment_confirmation_email_body',
						'name'        => __( 'Email Body', 'easy-event-registration' ),
						'desc'        => __( 'Available template tags:', 'easy-event-registration' ),
						'desc_tags'   => $template_settings_tag->print_content( EER()->tags->get_tags( 'payment_confirmation_email', $event_data ) ),
						'type'        => 'full_editor',
						'field_class' => 'eer-input'
					],
				],
			] ),
		];

		return apply_filters( 'eer_registered_events_settings', $eer_event_settings );
	}


	public function eer_get_event_settings_fields_to_print( $section_id, $sub_section_id, $event_data ) {
		$sections = $this->eer_get_event_settings_fields( $event_data );

		if ( isset( $sections[ $section_id ][ $sub_section_id ] ) ) {
			return $sections[ $section_id ][ $sub_section_id ];
		}

		return [];
	}


	public function eer_get_event_settings_tabs() {
		$tabs                      = [];
		$tabs['event_general']     = __( 'General', 'easy-event-registration' );
		$tabs['registration_form'] = __( 'Registration Form', 'easy-event-registration' );
		$tabs['emails']            = __( 'Emails', 'easy-event-registration' );

		return apply_filters( 'eer_event_settings_tabs', $tabs );
	}


	public function eer_get_event_settings_tab( $tab ) {
		$tabs = $this->eer_get_event_settings_tabs();

		return isset( $tabs[ $tab ] ) ? $tabs[ $tab ] : $tab;
	}


	public function eer_get_event_settings_tab_sections( $tab = false ) {

		$tabs     = false;
		$sections = $this->eer_get_event_settings_sections();

		if ( $tab && ! empty( $sections[ $tab ] ) ) {
			$tabs = $sections[ $tab ];
		} elseif ( $tab ) {
			$tabs = false;
		}

		return $tabs;
	}


	public function eer_get_event_settings_sections() {

		static $sections = false;

		if ( false !== $sections ) {
			return $sections;
		}

		$sections = [
			'event_general'     => apply_filters( 'eer_event_settings_sections_general', [
				'general_currency'        => __( 'Currency', 'easy-event-registration' ),
				'general_sale_not_opened' => __( 'Sale Not Opened', 'easy-event-registration' ),
				'general_sale_closed'     => __( 'Sale Closed', 'easy-event-registration' ),
			] ),
			'registration_form' => apply_filters( 'eer_event_settings_sections_registration_form', [
				'rfmain'           => __( 'Main', 'easy-event-registration' ),
				'form_texts'       => __( 'Form Texts', 'easy-event-registration' ),
				'thank_you_text'   => __( 'Thank You Text', 'easy-event-registration' ),
				'terms_conditions' => __( 'Terms & Conditions', 'easy-event-registration' ),
				'hosting'          => __( 'Hosting', 'easy-event-registration' ),
				'rf_tshirts'       => __( 'T-Shirts', 'easy-event-registration' ),
				'food'             => __( 'Food', 'easy-event-registration' ),
				'custom_checkbox'  => __( 'Custom Checkbox', 'easy-event-registration' ),
				'custom_area'      => __( 'Custom TextArea', 'easy-event-registration' ),
			] ),
			'emails'            => apply_filters( 'eer_event_settings_sections_emails', [
				'emain'                      => __( 'General', 'easy-event-registration' ),
				'order_email'                => __( 'Registration Overview Email', 'easy-event-registration' ),
				'order_confirmation_email'   => __( 'Confirmation Email', 'easy-event-registration' ),
				'payment_reminder_email'     => __( 'Payment Reminder Email', 'easy-event-registration' ),
				'payment_confirmation_email' => __( 'Payment Confirmation Email', 'easy-event-registration' ),
			] ),
		];

		$sections = apply_filters( 'eer_event_settings_sections', $sections );

		return $sections;
	}


	/**
	 * Loads all events
	 * @return array|null|object
	 */
	public function load_events() {
		global $wpdb;
		$return = [];

		//if ( current_user_can( 'eer_partial_events_view' ) ) {
			//$events = $wpdb->get_results( $wpdb->prepare( "SELECT e.* FROM {$wpdb->prefix}eer_events AS e JOIN {$wpdb->prefix}eer_event_managers AS em ON em.event_id = e.id WHERE em.user_id = %d ORDER BY e.id DESC", get_current_user_id() ), OBJECT_K );
		//} else {
			$events = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}eer_events ORDER BY id DESC", OBJECT_K );
		//}

		foreach ( $events as $id => $event ) {
			$settings = $event->event_settings;
			unset( $event->event_settings );
			$event                = (object) array_merge( (array) $event, (array) json_decode( $settings, true ) );
			$return[ $event->id ] = $event;
		}

		return $return;
	}


	/**
	 * Loads all events
	 * @return array|null|object
	 */
	public function load_events_without_data() {
		global $wpdb;

		//if ( current_user_can( 'eer_partial_events_view' ) ) {
		//	return $wpdb->get_results( $wpdb->prepare( "SELECT e.* FROM {$wpdb->prefix}eer_events AS e JOIN {$wpdb->prefix}eer_event_managers AS em ON em.event_id = e.id WHERE em.user_id = %d ORDER BY e.id DESC", get_current_user_id() ), OBJECT_K );
		//}

		return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}eer_events ORDER BY id DESC", OBJECT_K );
	}


	public function eer_get_event( $event_id ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}eer_events WHERE id = %d", [ $event_id ] ) );
	}


	public function get_event_data( $event_id ) {
		global $wpdb;
		$event    = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}eer_events WHERE id = %d", [ $event_id ] ), OBJECT );
		$settings = "";
		if ( $event ) {
			$settings = $event->event_settings;
			unset( $event->event_settings );
		}

		return (object) array_merge( (array) $event, (array) json_decode( $settings, true ) );
	}


	public function get_event_setting( $event_id, $setting_key, $default = null ) {
		$event_data = $this->get_event_data( $event_id );

		return isset( $event_data->$setting_key ) ? $event_data->$setting_key : $default;
	}


	public function get_event_title( $event_id ) {
		return $this->get_event_data( $event_id )->title;
	}


	/**
	 * @return object
	 */
	public function get_fields() {
		return $this->fields->get_fields();
	}


	public function is_event_sale_active( $event_id ) {
		$data         = $this->get_event_data( $event_id );
		$current_time = current_time( 'Y-m-d H:i:s' );

		return ! $data->is_passed && ( $data->sale_start <= $current_time ) && ( $data->sale_end >= $current_time );
	}


	/**
	 * @param int $event_id
	 *
	 * @return bool
	 */
	public function is_event_sale_closed( $event_id ) {
		$data         = $this->get_event_data( $event_id );
		$current_time = current_time( 'Y-m-d H:i:s' );

		return $data->is_passed || ( $data->sale_end < $current_time );
	}


	/**
	 * @param int $event_id
	 *
	 * @return bool
	 */
	public function is_event_sale_not_opened_yet( $event_id ) {
		$data         = $this->get_event_data( $event_id );
		$current_time = current_time( 'Y-m-d H:i:s' );

		return ! $data->is_passed && ( $data->sale_start > $current_time );
	}


	public function eer_get_event_option( $event_data, $key = '', $default = false ) {
		$value = ! empty( $event_data->$key ) ? $event_data->$key : $default;

		return apply_filters( 'eer_get_event_option_' . $key, $value, $key, $default );
	}


	/**
	 * Loads all events
	 * @return array|null|object
	 */
	public function load_tinymce_events() {
		global $wpdb;

		return $wpdb->get_results( "SELECT title AS text, id AS value FROM {$wpdb->prefix}eer_events ORDER BY id DESC" );
	}
}