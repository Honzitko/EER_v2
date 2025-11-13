<?php

if (!defined('ABSPATH')) {
        exit;
}

if (!class_exists('\\Elementor\\Widget_Base')) {
        return;
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;

class EER_Elementor_Event_Sale_Widget extends Widget_Base {

        public function get_name() {
                return 'eer-event-sale';
        }

        public function get_title() {
                return __('Easy Event Registration – Event Sale', 'easy-event-registration');
        }

        public function get_icon() {
                return 'eicon-calendar';
        }

        public function get_categories() {
                return ['easy-event-registration'];
        }

        public function get_keywords() {
                return ['event', 'registration', 'ticket', 'sale', 'eer'];
        }

        public function get_style_depends() {
                return ['eer_web_style'];
        }

        public function get_script_depends() {
                return ['eer_web_script', 'eer_spin_js_script'];
        }

        protected function register_controls() {
                $this->register_event_section();
                $this->register_content_section();
                $this->register_style_section();
        }

        private function register_event_section() {
                $this->start_controls_section(
                        'event_section',
                        [
                                'label' => __('Event', 'easy-event-registration'),
                        ]
                );

                $event_options = $this->get_event_options();

                $default_event = 0;
                if (!empty($event_options)) {
                        $option_keys   = array_keys($event_options);
                        $default_event = (int) reset($option_keys);
                }

                $this->add_control(
                        'eventId',
                        [
                                'label'   => __('Select Event', 'easy-event-registration'),
                                'type'    => Controls_Manager::SELECT,
                                'options' => $event_options,
                                'default' => $default_event,
                        ]
                );

                $this->add_control(
                        'showTitle',
                        [
                                'label'        => __('Show Title', 'easy-event-registration'),
                                'type'         => Controls_Manager::SWITCHER,
                                'label_on'     => __('Show', 'easy-event-registration'),
                                'label_off'    => __('Hide', 'easy-event-registration'),
                                'return_value' => 'yes',
                                'default'      => 'yes',
                        ]
                );

                $this->add_control(
                        'customTitle',
                        [
                                'label'       => __('Custom Title', 'easy-event-registration'),
                                'type'        => Controls_Manager::TEXT,
                                'placeholder' => __('Enter a custom heading…', 'easy-event-registration'),
                                'condition'   => [
                                        'showTitle' => 'yes',
                                ],
                        ]
                );

                $this->add_control(
                        'showEventId',
                        [
                                'label'        => __('Show Event ID Badge', 'easy-event-registration'),
                                'type'         => Controls_Manager::SWITCHER,
                                'label_on'     => __('Show', 'easy-event-registration'),
                                'label_off'    => __('Hide', 'easy-event-registration'),
                                'return_value' => 'yes',
                                'default'      => '',
                        ]
                );

                $this->add_control(
                        'introText',
                        [
                                'label'       => __('Intro Text', 'easy-event-registration'),
                                'type'        => Controls_Manager::WYSIWYG,
                                'placeholder' => __('Add a short introduction for visitors…', 'easy-event-registration'),
                        ]
                );

                $this->add_control(
                        'className',
                        [
                                'label'       => __('Additional CSS Classes', 'easy-event-registration'),
                                'type'        => Controls_Manager::TEXT,
                                'placeholder' => __('e.g. my-custom-class', 'easy-event-registration'),
                        ]
                );

                $this->end_controls_section();
        }

        private function register_content_section() {
                $this->start_controls_section(
                        'content_section',
                        [
                                'label' => __('Layout', 'easy-event-registration'),
                                'tab'   => Controls_Manager::TAB_STYLE,
                        ]
                );

                $this->add_control(
                        'backgroundColor',
                        [
                                'label' => __('Background Color', 'easy-event-registration'),
                                'type'  => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .eer-event-sale-block' => 'background-color: {{VALUE}};',
                                ],
                        ]
                );

                $this->add_control(
                        'textColor',
                        [
                                'label' => __('Text Color', 'easy-event-registration'),
                                'type'  => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .eer-event-sale-block' => 'color: {{VALUE}};',
                                ],
                        ]
                );

                $this->add_responsive_control(
                        'padding',
                        [
                                'label'      => __('Padding', 'easy-event-registration'),
                                'type'       => Controls_Manager::SLIDER,
                                'size_units' => ['px'],
                                'range'      => [
                                        'px' => [
                                                'min' => 0,
                                                'max' => 120,
                                        ],
                                ],
                                'default'    => [
                                        'size' => 24,
                                        'unit' => 'px',
                                ],
                                'selectors'  => [
                                        '{{WRAPPER}} .eer-event-sale-block' => 'padding: {{SIZE}}{{UNIT}};',
                                ],
                        ]
                );

                $this->add_responsive_control(
                        'borderRadius',
                        [
                                'label'      => __('Border Radius', 'easy-event-registration'),
                                'type'       => Controls_Manager::SLIDER,
                                'size_units' => ['px'],
                                'range'      => [
                                        'px' => [
                                                'min' => 0,
                                                'max' => 60,
                                        ],
                                ],
                                'default'    => [
                                        'size' => 0,
                                        'unit' => 'px',
                                ],
                                'selectors'  => [
                                        '{{WRAPPER}} .eer-event-sale-block' => 'border-radius: {{SIZE}}{{UNIT}};',
                                ],
                        ]
                );

                $this->add_control(
                        'showBorder',
                        [
                                'label'        => __('Add Border', 'easy-event-registration'),
                                'type'         => Controls_Manager::SWITCHER,
                                'label_on'     => __('Show', 'easy-event-registration'),
                                'label_off'    => __('Hide', 'easy-event-registration'),
                                'return_value' => 'yes',
                                'default'      => '',
                        ]
                );

                $this->add_control(
                        'borderColor',
                        [
                                'label'     => __('Border Color', 'easy-event-registration'),
                                'type'      => Controls_Manager::COLOR,
                                'condition' => [
                                        'showBorder' => 'yes',
                                ],
                                'selectors' => [
                                        '{{WRAPPER}} .eer-event-sale-block' => 'border-color: {{VALUE}};',
                                ],
                        ]
                );

                $this->add_responsive_control(
                        'borderWidth',
                        [
                                'label'      => __('Border Width', 'easy-event-registration'),
                                'type'       => Controls_Manager::SLIDER,
                                'size_units' => ['px'],
                                'range'      => [
                                        'px' => [
                                                'min' => 0,
                                                'max' => 10,
                                        ],
                                ],
                                'default'    => [
                                        'size' => 1,
                                        'unit' => 'px',
                                ],
                                'condition'  => [
                                        'showBorder' => 'yes',
                                ],
                                'selectors'  => [
                                        '{{WRAPPER}} .eer-event-sale-block' => 'border-width: {{SIZE}}{{UNIT}};',
                                ],
                        ]
                );

                $this->add_control(
                        'shadow',
                        [
                                'label'        => __('Drop Shadow', 'easy-event-registration'),
                                'type'         => Controls_Manager::SWITCHER,
                                'label_on'     => __('Show', 'easy-event-registration'),
                                'label_off'    => __('Hide', 'easy-event-registration'),
                                'return_value' => 'yes',
                                'default'      => '',
                        ]
                );

                $this->end_controls_section();
        }

        private function register_style_section() {
                $this->start_controls_section(
                        'typography_section',
                        [
                                'label' => __('Typography', 'easy-event-registration'),
                                'tab'   => Controls_Manager::TAB_STYLE,
                        ]
                );

                $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                'name'     => 'typography',
                                'label'    => __('Base Typography', 'easy-event-registration'),
                                'selector' => '{{WRAPPER}} .eer-event-sale-block',
                        ]
                );

                $this->add_control(
                        'title_color',
                        [
                                'label'     => __('Title Color', 'easy-event-registration'),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .eer-event-sale-block__title' => 'color: {{VALUE}};',
                                ],
                        ]
                );

                $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                'name'     => 'title_typography',
                                'label'    => __('Title Typography', 'easy-event-registration'),
                                'selector' => '{{WRAPPER}} .eer-event-sale-block__title',
                        ]
                );

                $this->add_control(
                        'badge_background',
                        [
                                'label'     => __('Badge Background', 'easy-event-registration'),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .eer-event-sale-block__event-id' => 'background-color: {{VALUE}};',
                                ],
                        ]
                );

                $this->add_control(
                        'badge_text_color',
                        [
                                'label'     => __('Badge Text Color', 'easy-event-registration'),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .eer-event-sale-block__event-id' => 'color: {{VALUE}};',
                                ],
                        ]
                );

                $this->add_control(
                        'intro_text_color',
                        [
                                'label'     => __('Intro Text Color', 'easy-event-registration'),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => [
                                        '{{WRAPPER}} .eer-event-sale-block__intro' => 'color: {{VALUE}};',
                                ],
                        ]
                );

                $this->end_controls_section();
        }

        protected function render() {
                $settings = $this->get_settings_for_display();

                $event_id = isset($settings['eventId']) ? intval($settings['eventId']) : 0;
                if ($event_id <= 0) {
                        echo '<div class="eer-event-sale-widget__notice">' . esc_html__('Select an event to display the registration form.', 'easy-event-registration') . '</div>';
                        return;
                }

                if (!class_exists('EER_Blocks')) {
                        echo '<div class="eer-event-sale-widget__notice">' . esc_html__('The Easy Event Registration block renderer is not available.', 'easy-event-registration') . '</div>';
                        return;
                }

                $attributes = [
                        'eventId'       => $event_id,
                        'showTitle'     => $this->to_boolean($settings, 'showTitle'),
                        'customTitle'   => isset($settings['customTitle']) ? sanitize_text_field($settings['customTitle']) : '',
                        'showEventId'   => $this->to_boolean($settings, 'showEventId'),
                        'introText'     => isset($settings['introText']) ? $settings['introText'] : '',
                        'backgroundColor' => isset($settings['backgroundColor']) ? sanitize_text_field($settings['backgroundColor']) : '',
                        'textColor'     => isset($settings['textColor']) ? sanitize_text_field($settings['textColor']) : '',
                        'padding'       => $this->extract_slider_value($settings, 'padding', 24),
                        'borderRadius'  => $this->extract_slider_value($settings, 'borderRadius', 0),
                        'showBorder'    => $this->to_boolean($settings, 'showBorder'),
                        'borderColor'   => isset($settings['borderColor']) ? sanitize_text_field($settings['borderColor']) : '',
                        'borderWidth'   => $this->extract_slider_value($settings, 'borderWidth', 1),
                        'shadow'        => $this->to_boolean($settings, 'shadow'),
                        'className'     => isset($settings['className']) ? sanitize_text_field($settings['className']) : '',
                ];

                echo EER_Blocks::render_event_sale_block($attributes, '');
        }

        private function get_event_options() {
                $events = [];
                if (isset(EER()->event)) {
                        $events = EER()->event->load_tinymce_events();
                }

                if (empty($events)) {
                        return [
                                0 => __('No events found', 'easy-event-registration'),
                        ];
                }

                $options = [];
                foreach ($events as $event) {
                        $options[intval($event->value)] = $event->text;
                }

                return $options;
        }

        private function to_boolean($settings, $key) {
                return isset($settings[$key]) && $settings[$key] === 'yes';
        }

        private function extract_slider_value($settings, $key, $default = 0) {
                if (!isset($settings[$key])) {
                        return $default;
                }

                $value = $settings[$key];
                if (is_array($value) && isset($value['size'])) {
                        return intval($value['size']);
                }

                return intval($value);
        }
}
