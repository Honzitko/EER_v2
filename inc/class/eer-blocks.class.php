<?php

if (!defined('ABSPATH')) {
        exit;
}

class EER_Blocks {

        public static function init() {
                add_action('init', [__CLASS__, 'register_blocks']);
        }

        public static function register_blocks() {
                if (!function_exists('register_block_type')) {
                        return;
                }

                $script_handle = 'eer-event-sale-block-editor';
                $script_path   = EER_PLUGIN_PATH . '/inc/assets/admin/js/eer-event-sale-block.js';
                $script_url    = EER_PLUGIN_URL . 'inc/assets/admin/js/eer-event-sale-block.js';

                $dependencies = [
                        'wp-blocks',
                        'wp-element',
                        'wp-components',
                        'wp-i18n',
                        'wp-editor',
                        'wp-block-editor',
                        'wp-data',
                ];

                $version = defined('EER_VERSION') ? EER_VERSION : '1.0.0';
                if (file_exists($script_path)) {
                        $version = filemtime($script_path);
                }

                wp_register_script(
                        $script_handle,
                        $script_url,
                        $dependencies,
                        $version,
                        true
                );

                wp_localize_script(
                        $script_handle,
                        'EEREventBlockData',
                        [
                                'events'        => self::get_events_for_block(),
                                'noEventsLabel' => __('No events found. Create an event in the Easy Event Registration admin to use this block.', 'easy-event-registration'),
                                'selectEvent'   => __('Select an event', 'easy-event-registration'),
                                'defaultIntro'  => __('Tell visitors more about this event using the block inspector controls.', 'easy-event-registration'),
                        ]
                );

                register_block_type(
                        'easy-event-registration/event-sale',
                        [
                                'editor_script'   => $script_handle,
                                'render_callback' => [__CLASS__, 'render_event_sale_block'],
                                'attributes'      => self::get_block_attributes(),
                                'supports'        => [
                                        'align'            => ['wide', 'full'],
                                        'customClassName'  => true,
                                        'html'             => false,
                                ],
                        ]
                );
        }

        private static function get_block_attributes() {
                return [
                        'eventId'       => [
                                'type'    => 'number',
                                'default' => 0,
                        ],
                        'align'         => [
                                'type'    => 'string',
                                'default' => '',
                        ],
                        'showTitle'     => [
                                'type'    => 'boolean',
                                'default' => true,
                        ],
                        'customTitle'   => [
                                'type'    => 'string',
                                'default' => '',
                        ],
                        'showEventId'   => [
                                'type'    => 'boolean',
                                'default' => false,
                        ],
                        'introText'     => [
                                'type'    => 'string',
                                'default' => '',
                        ],
                        'backgroundColor' => [
                                'type'    => 'string',
                                'default' => '',
                        ],
                        'textColor'     => [
                                'type'    => 'string',
                                'default' => '',
                        ],
                        'padding'       => [
                                'type'    => 'number',
                                'default' => 24,
                        ],
                        'borderRadius'  => [
                                'type'    => 'number',
                                'default' => 0,
                        ],
                        'showBorder'    => [
                                'type'    => 'boolean',
                                'default' => false,
                        ],
                        'borderColor'   => [
                                'type'    => 'string',
                                'default' => '#dcdcde',
                        ],
                        'borderWidth'   => [
                                'type'    => 'number',
                                'default' => 1,
                        ],
                        'shadow'        => [
                                'type'    => 'boolean',
                                'default' => false,
                        ],
                        'className'     => [
                                'type'    => 'string',
                                'default' => '',
                        ],
                ];
        }

        public static function render_event_sale_block($attributes, $content) {
                $event_id = isset($attributes['eventId']) ? intval($attributes['eventId']) : 0;
                if ($event_id <= 0) {
                        return '';
                }

                $classes = ['wp-block-easy-event-registration-event-sale', 'eer-event-sale-block'];

                if (!empty($attributes['className'])) {
                        $custom_classes = preg_split('/\s+/', $attributes['className']);
                        foreach ($custom_classes as $custom_class) {
                                if (!empty($custom_class)) {
                                        $classes[] = sanitize_html_class($custom_class);
                                }
                        }
                }

                if (!empty($attributes['align'])) {
                        $classes[] = 'align' . sanitize_html_class($attributes['align']);
                }

                if (!empty($attributes['shadow'])) {
                        $classes[] = 'eer-event-sale-block--shadow';
                }

                $style_rules = [];
                $background_color = self::sanitize_color_value(isset($attributes['backgroundColor']) ? $attributes['backgroundColor'] : '');
                if (!empty($background_color)) {
                        $style_rules[] = 'background-color:' . $background_color;
                }

                $text_color = self::sanitize_color_value(isset($attributes['textColor']) ? $attributes['textColor'] : '');
                if (!empty($text_color)) {
                        $style_rules[] = 'color:' . $text_color;
                }

                if (isset($attributes['padding'])) {
                        $style_rules[] = 'padding:' . intval($attributes['padding']) . 'px';
                }

                if (isset($attributes['borderRadius'])) {
                        $style_rules[] = 'border-radius:' . intval($attributes['borderRadius']) . 'px';
                }

                if (!empty($attributes['showBorder'])) {
                        $border_color = self::sanitize_color_value(isset($attributes['borderColor']) ? $attributes['borderColor'] : '');
                        if (empty($border_color)) {
                                $border_color = '#dcdcde';
                        }
                        $border_width = isset($attributes['borderWidth']) ? max(0, intval($attributes['borderWidth'])) : 1;
                        $style_rules[] = sprintf('border:%dpx solid %s', $border_width, $border_color);
                }

                if (!empty($attributes['shadow'])) {
                        $style_rules[] = 'box-shadow:0 20px 50px rgba(0,0,0,0.1)';
                }

                $style_attribute = !empty($style_rules) ? ' style="' . esc_attr(implode(';', $style_rules)) . '"' : '';

                $title = '';
                $show_title = !isset($attributes['showTitle']) || (bool) $attributes['showTitle'];
                if ($show_title) {
                        if (!empty($attributes['customTitle'])) {
                                $title = sanitize_text_field($attributes['customTitle']);
                        } else {
                                $title = EER()->event->get_event_title($event_id);
                        }
                }

                $show_event_id = !empty($attributes['showEventId']);

                $intro_text = '';
                if (!empty($attributes['introText'])) {
                        $intro_text = wp_kses_post($attributes['introText']);
                }

                $wrapper_classes = implode(' ', array_map('sanitize_html_class', $classes));

                $output  = '<div class="' . esc_attr($wrapper_classes) . '" data-eer-event-id="' . esc_attr($event_id) . '"' . $style_attribute . '>';

                if ($show_title && !empty($title)) {
                        $output .= '<div class="eer-event-sale-block__header">';
                        $output .= '<h3 class="eer-event-sale-block__title">' . esc_html($title) . '</h3>';
                        if ($show_event_id) {
                                $output .= '<span class="eer-event-sale-block__event-id">' . sprintf(esc_html__('#%d', 'easy-event-registration'), $event_id) . '</span>';
                        }
                        $output .= '</div>';
                } elseif ($show_event_id) {
                        $output .= '<div class="eer-event-sale-block__header">';
                        $output .= '<span class="eer-event-sale-block__event-id">' . sprintf(esc_html__('#%d', 'easy-event-registration'), $event_id) . '</span>';
                        $output .= '</div>';
                }

                if (!empty($intro_text)) {
                        $output .= '<div class="eer-event-sale-block__intro">' . $intro_text . '</div>';
                }

                $output .= '<div class="eer-event-sale-block__content">' . do_shortcode('[eer_event_sale event="' . $event_id . '"]') . '</div>';

                $output .= '</div>';

                return $output;
        }

        private static function get_events_for_block() {
                $events = [];
                if (isset(EER()->event)) {
                        $events = EER()->event->load_tinymce_events();
                }

                if (empty($events)) {
                        return [];
                }

                $prepared = [];
                foreach ($events as $event) {
                        $prepared[] = [
                                'value' => intval($event->value),
                                'label' => $event->text,
                        ];
                }

                return $prepared;
        }

        private static function sanitize_color_value($color) {
                $color = is_string($color) ? trim($color) : '';
                if ($color === '') {
                        return '';
                }

                // Allow CSS variables for theme compatibility.
                if (preg_match('/^var\(\s*--[A-Za-z0-9_-]+\s*\)$/', $color)) {
                        return $color;
                }

                $sanitized_hex = sanitize_hex_color($color);
                if ($sanitized_hex) {
                        return $sanitized_hex;
                }

                return '';
        }
}

EER_Blocks::init();
