<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Settings
{

	/**
	 * Retrieve the array of plugin settings
	 *
	 * @since 3
	 * @return array
	 */
	public function eer_get_registered_settings()
	{
		$eer_settings = [
			/** General Settings */
			'general' => apply_filters('eer_settings_general', [
				'main' => [
					'license_key' => [
						'id' => 'license_key',
						'name' => __('License key', 'easy-event-registration'),
						'type' => 'text',
						'options' => 'small',
					],
					'eer_license_activate' => [
						'id' => 'eer_license_activate',
						'name' => __('Activate License', 'easy-event-registration'),
						'type' => 'submit',
					],
				],
			]),
                        'style'      => apply_filters( 'eer_settings_style', [
                                'main'            => [
                                ],
				'normal_ticket'             => [
					'normal_ticket_background'  => [
						'id'       => 'normal_ticket_background',
						'name'     => __( 'Ticket', 'easy-event-registration' ),
						'type'     => 'style_ticket_box',
						'std'      => [
							'background_color'   => '#182f49',
							'background_opacity' => 1,
							'border_width'       => 0,
							'border_color'       => '#000',
						],
						'selector' => '.eer-tickets-sale-wrapper .eer-tickets .eer-ticket .eer-ticket-body-wraper',
						'property' => 'background',
					],
					'normal_ticket_title'       => [
						'id'       => 'normal_ticket_title',
						'name'     => __( 'Ticket Title', 'easy-event-registration' ),
						'type'     => 'style_ticket_font',
						'std'      => [
							'color' => '#fff',
							'size'  => 16,
						],
						'selector' => '.eer-tickets-sale-wrapper .eer-tickets .eer-ticket .eer-ticket-body-wraper .eer-ticket-body .eer-ticket-title',
						'property' => 'font',
					],
					'normal_ticket_description' => [
						'id'       => 'normal_ticket_description',
						'name'     => __( 'Ticket Description', 'easy-event-registration' ),
						'type'     => 'style_ticket_font',
						'std'      => [
							'color' => '#fff',
							'size'  => 16,
						],
						'selector' => '.eer-tickets-sale-wrapper .eer-tickets .eer-ticket .eer-ticket-body-wraper .eer-ticket-body .eer-ticket-content',
						'property' => 'font',
					],
				],
				'sold_ticket'             => [
					'sold_ticket_background'  => [
						'id'       => 'sold_ticket_background',
						'name'     => __( 'Ticket', 'easy-event-registration' ),
						'type'     => 'style_ticket_box',
						'std'      => [
							'background_color'   => '#7a8d91',
							'background_opacity' => 1,
							'border_width'       => 0,
							'border_color'       => '#000',
						],
						'selector' => '.eer-tickets-sale-wrapper .eer-tickets .eer-ticket.eer-sold .eer-ticket-body-wraper',
						'property' => 'background',
					],
					'sold_ticket_title'       => [
						'id'       => 'sold_ticket_title',
						'name'     => __( 'Ticket Title', 'easy-event-registration' ),
						'type'     => 'style_ticket_font',
						'std'      => [
							'color' => '#fff',
							'size'  => 16,
						],
						'selector' => '.eer-tickets-sale-wrapper .eer-tickets .eer-ticket.eer-sold .eer-ticket-body-wraper .eer-ticket-body .eer-ticket-title',
						'property' => 'font',
					],
					'sold_ticket_description' => [
						'id'       => 'sold_ticket_description',
						'name'     => __( 'Ticket Description', 'easy-event-registration' ),
						'type'     => 'style_ticket_font',
						'std'      => [
							'color' => '#fff',
							'size'  => 16,
						],
						'selector' => '.eer-tickets-sale-wrapper .eer-tickets .eer-ticket.eer-sold .eer-ticket-body-wraper .eer-ticket-body .eer-ticket-content',
						'property' => 'font',
					],
				],
				'add_button'             => [
					'add_button'  => [
						'id'       => 'add_button',
						'name'     => __( 'Add Button Color', 'easy-event-registration' ),
						'type'     => 'style_color',
						'std'      => [
							'color' => '#e04038',
						],
						'selector' => '.eer-tickets-sale-wrapper .eer-tickets .eer-ticket .eer-ticket-body-wraper .eer-ticket-body button.eer-ticket-add svg g',
						'property' => 'fill',
					],
					'add_button_to_remove'       => [
						'id'       => 'add_button_to_remove',
						'name'     => __( 'Add Button 2. Color', 'easy-event-registration' ),
						'type'     => 'style_color',
						'std'      => [
							'color' => '#808080',
						],
						'selector' => '.eer-tickets-sale-wrapper .eer-tickets .eer-ticket.eer-ticket-remove .eer-ticket-body-wraper .eer-ticket-body button.eer-ticket-add svg g',
						'property' => 'fill',
					],
					'add_button_plus'  => [
						'id'       => 'add_button_plus',
						'name'     => __( 'Add Button + Color', 'easy-event-registration' ),
						'type'     => 'style_color',
						'std'      => [
							'color' => '#fff',
						],
						'selector' => '.eer-tickets-sale-wrapper .eer-tickets .eer-ticket .eer-ticket-body-wraper .eer-ticket-body button.eer-ticket-add .dashicons-plus',
						'property' => 'color',
					],
					'add_button_plus_to_remove'       => [
						'id'       => 'add_button_plus_to_remove',
						'name'     => __( 'Add Button + 2. Color', 'easy-event-registration' ),
						'type'     => 'style_color',
						'std'      => [
							'color' => '#fff',
						],
						'selector' => '.eer-tickets-sale-wrapper .eer-tickets .eer-ticket.eer-ticket-remove .eer-ticket-body-wraper .eer-ticket-body button.eer-ticket-add .dashicons-plus',
						'property' => 'color',
					],
				],
				'remove_button'             => [
					'remove_button'  => [
						'id'       => 'remove_button',
						'name'     => __( 'Remove Button Color', 'easy-event-registration' ),
						'type'     => 'style_color',
						'std'      => [
							'color' => '#e04038',
						],
						'selector' => '.eer-tickets-sale-wrapper button.eer-ticket-remove',
						'property' => 'background-color',
					],
					'remove_button_plus'  => [
						'id'       => 'remove_button_plus',
						'name'     => __( 'Remove Button - Color', 'easy-event-registration' ),
						'type'     => 'style_color',
						'std'      => [
							'color' => '#fff',
						],
						'selector' => '.eer-tickets-sale-wrapper button.eer-ticket-remove .dashicons-minus',
						'property' => 'color',
					],
				],
				'ticket_form'       => [
					'table_titles' => [
						'id'       => 'table_titles',
						'name'     => __( 'Table Title', 'easy-event-registration' ),
						'type'     => 'style_ticket_font',
						'std'      => [
							'color' => '#000',
							'size'  => 11,
						],
						'selector' => '.eer-tickets-sale-wrapper .eer-form-tickets-header .eer-column-header, .eer-ticket-shop-form .eer-user-form .eer-row .eer-form-group label',
						'property' => 'font',
					],
				],
				'user_form'       => [
					'element_title' => [
						'id'       => 'element_title',
						'name'     => __( 'Element And Table Title', 'easy-event-registration' ),
						'type'     => 'style_ticket_font',
						'std'      => [
							'color' => '#000',
							'size'  => 11,
						],
						'selector' => '.eer-tickets-sale-wrapper .eer-form-tickets-header .eer-column-header, .eer-ticket-shop-form .eer-user-form .eer-row .eer-form-group label',
						'property' => 'font',
					],
					'element_description' => [
						'id'       => 'element_description',
						'name'     => __( 'Element Description', 'easy-event-registration' ),
						'type'     => 'style_ticket_font',
						'std'      => [
							'color' => '#000',
							'size'  => 11,
						],
						'selector' => '.eer-ticket-shop-form .eer-user-form .eer-row .eer-form-group .eer-event-note-description, .eer-ticket-shop-form .eer-user-form .eer-row .eer-form-group .eer-event-input-description',
						'property' => 'font',
					],
					'price_style' => [
						'id'       => 'price_style',
						'name'     => __( 'Price', 'easy-event-registration' ),
						'type'     => 'style_ticket_font',
						'std'      => [
							'color' => '#343434',
							'size'  => 16,
						],
						'selector' => '.eer-final-price .eer-final-price-value, .eer-price',
						'property' => 'font',
					],
				],
				'confirm_button'       => [
					'confirm_button_bg'  => [
						'id'       => 'confirm_button_bg',
						'name'     => __( 'Confirm Button Background', 'easy-event-registration' ),
						'type'     => 'style_ticket_box',
						'std'      => [
							'background_color'   => '#182f49',
							'background_opacity' => 1,
							'border_width'       => 0,
							'border_color'       => '#000',
						],
						'selector' => '.eer-tickets-sale-wrapper .eer-ticket-shop-form .btn.btn-default',
						'property' => 'background',
					],
					'confirm_button_font'  => [
						'id'       => 'confirm_button_font',
						'name'     => __( 'Confirm Button Font', 'easy-event-registration' ),
						'type'     => 'style_ticket_font',
						'std'      => [
							'color' => '#fff',
							'size'  => 13,
						],
						'selector' => '.eer-tickets-sale-wrapper .eer-ticket-shop-form .btn.btn-default',
						'property' => 'font',
					],
					'confirm_button_hover_bg'  => [
						'id'       => 'confirm_button_hover_bg',
						'name'     => __( 'Confirm Button Background', 'easy-event-registration' ),
						'type'     => 'style_ticket_box',
						'std'      => [
							'background_color'   => '#182f49',
							'background_opacity' => 1,
							'border_width'       => 0,
							'border_color'       => '#000',
						],
						'selector' => '.eer-tickets-sale-wrapper .eer-ticket-shop-form .btn.btn-default:hover',
						'property' => 'background',
					],
					'confirm_button_hover_font'  => [
						'id'       => 'confirm_button_hover_font',
						'name'     => __( 'Confirm Button Font', 'easy-event-registration' ),
						'type'     => 'style_ticket_font',
						'std'      => [
							'color' => '#fff',
							'size'  => 13,
						],
						'selector' => '.eer-tickets-sale-wrapper .eer-ticket-shop-form .btn.btn-default:hover',
						'property' => 'font',
					],
                                ],
                        ] ),
                        'communication' => apply_filters('eer_settings_communication', [
                                'emails' => [
                                        'default_order_email_subject' => [
                                                'id'   => 'default_order_email_subject',
                                                'name' => __( 'Default Order Email Subject', 'easy-event-registration' ),
                                                'type' => 'text',
                                        ],
                                        'default_order_email_body'    => [
                                                'id'   => 'default_order_email_body',
                                                'name' => __( 'Default Order Email Body', 'easy-event-registration' ),
                                                'type' => 'full_editor',
                                        ],
                                ],
                                'form'   => [
                                        'registration_form_message' => [
                                                'id'   => 'registration_form_message',
                                                'name' => __( 'Registration Form Message', 'easy-event-registration' ),
                                                'type' => 'full_editor',
                                        ],
                                ],
                        ] ),
                        'licenses' => apply_filters('eer_settings_licenses', []),
                        'extensions' => apply_filters('eer_settings_extensions', []),
                ];

		return apply_filters('eer_registered_settings', $eer_settings);
	}


	public function eer_get_registered_settings_sections()
	{

		static $sections = false;

		if (false !== $sections) {
			return $sections;
		}

		$sections = [
			'general' => apply_filters('eer_settings_sections_general', [
				'main' => __('General', 'easy-event-registration'),
			]),
                        'style' => apply_filters('eer_settings_sections_style', [
                                'main' => __('General', 'easy-event-registration'),
                                'normal_ticket' => __('Ticket', 'easy-event-registration'),
                                'sold_ticket' => __('Sold Ticket', 'easy-event-registration'),
                                'add_button' => __('Add Button', 'easy-event-registration'),
                                'remove_button' => __('Remove Button', 'easy-event-registration'),
                                'ticket_form' => __('Ticket Form', 'easy-event-registration'),
                                'user_form' => __('Registration Form', 'easy-event-registration'),
                                'confirm_button' => __('Confirm Button', 'easy-event-registration'),
                        ]),
                        'communication' => apply_filters('eer_settings_sections_communication', [
                                'emails' => __( 'Emails', 'easy-event-registration' ),
                                'form'   => __( 'Form', 'easy-event-registration' ),
                        ]),
                        'licenses' => apply_filters('eer_settings_sections_licenses', []),
                        'extensions' => apply_filters('eer_settings_sections_extensions', []),
                ];

		$sections = apply_filters('eer_settings_sections', $sections);

		return $sections;
	}


	public function eer_get_settings_tabs()
	{

                $settings = $this->eer_get_registered_settings();

                $tabs = [];
                $tabs['general'] = __('General', 'easy-event-registration');
                $tabs['style'] = __('Style', 'easy-event-registration');
                $tabs['communication'] = __('Communication', 'easy-event-registration');

                if (!empty($settings['extensions'])) {
                        $tabs['extensions'] = __('Extensions', 'easy-event-registration');
                }
                if (!empty($settings['licenses'])) {
			$tabs['licenses'] = __('Licenses', 'easy-event-registration');
		}

		return apply_filters('eer_settings_tabs', $tabs);
	}


	public function eer_get_settings_tab_sections($tab = false)
	{

		$tabs = false;
		$sections = $this->eer_get_registered_settings_sections();

		if ($tab && !empty($sections[$tab])) {
			$tabs = $sections[$tab];
		} else if ($tab) {
			$tabs = false;
		}

		return $tabs;
	}


	public static function eer_register_settings()
	{
		if (false == get_option('eer_settings')) {
			update_option('eer_settings', []);
		}

		foreach (EER()->settings->eer_get_registered_settings() as $tab => $sections) {
			foreach ($sections as $section => $settings) {

				// Check for backwards compatibility
				$section_tabs = EER()->settings->eer_get_settings_tab_sections($tab);
				if (!is_array($section_tabs) || !array_key_exists($section, $section_tabs)) {
					$section = 'main';
					$settings = $sections;
				}

				add_settings_section('eer_settings_' . $tab . '_' . $section, null, '__return_false', 'eer_settings_' . $tab . '_' . $section);

				foreach ($settings as $option) {
					// For backwards compatibility
					if (empty($option['id'])) {
						continue;
					}

					$args = wp_parse_args($option, [
						'section' => $section,
						'id' => null,
						'desc' => '',
						'name' => '',
						'size' => null,
						'options' => '',
						'std' => '',
						'min' => null,
						'max' => null,
						'step' => null,
						'chosen' => null,
						'multiple' => null,
						'placeholder' => null,
						'allow_blank' => true,
						'readonly' => false,
						'faux' => false,
						'tooltip_title' => false,
						'tooltip_desc' => false,
						'field_class' => '',
						'prefix' => 'eer_',
						'template' => 'EER_Template_Settings_Helper'
					]);

					$callback = $args['prefix'] . $args['type'] . '_callback';
					add_settings_field('eer_settings[' . $args['id'] . ']', $args['name'], method_exists($args['template'], $callback) ? [$args['template'], $callback] : '', 'eer_settings_' . $tab . '_' . $section, 'eer_settings_' . $tab . '_' . $section, $args);
				}
			}
		}

		// Creates our settings in the options table
		register_setting('eer_settings', 'eer_settings', ['EER_Settings', 'eer_settings_sanitize']);
	}


	public function eer_get_option($key = '', $default = false)
	{
		global $eer_settings;
		$value = !empty($eer_settings[$key]) ? $eer_settings[$key] : $default;
		$value = apply_filters('eer_get_option', $value, $key, $default);

		return apply_filters('eer_get_option_' . $key, $value, $key, $default);
	}


	public function eer_get_settings()
	{

		$settings = get_option('eer_settings');

		if (empty($settings)) {
			update_option('eer_settings', []);
		}

		return apply_filters('eer_get_settings', $settings);
	}


	public static function eer_settings_sanitize($input = [])
	{
		global $eer_settings;

		$input = $input ? $input : [];

		// Merge our new settings with the existing
		$output = array_merge($eer_settings, $input);

		return $output;
	}
}

add_action('admin_init', ['EER_Settings', 'eer_register_settings']);