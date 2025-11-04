<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Shortcodes {

        // [eer_event_sale]
        public static function eer_event_sale_shortcode($raw_attr) {
                $defaults = [
                        'event'          => '',
                        'test'           => '',
                        'show_title'     => '',
                        'custom_title'   => '',
                        'show_event_id'  => '',
                        'intro_text'     => '',
                        'background'     => '',
                        'text_color'     => '',
                        'padding'        => '',
                        'border_radius'  => '',
                        'show_border'    => '',
                        'border_color'   => '',
                        'border_width'   => '',
                        'shadow'         => '',
                        'wrapper_class'  => '',
                ];

                $attr = shortcode_atts($defaults, $raw_attr, 'eer_event_sale');

                if (!isset($attr['event']) || !ctype_digit((string) $attr['event'])) {
                        return '';
                }

                $event_id = (int) $attr['event'];

                ob_start();
                $templater_event_sale = new EER_Template_Event_Sale();
                $templater_event_sale->print_content($attr);
                $content = ob_get_clean();

                if ($content === '') {
                        return '';
                }

                if (!self::has_presentation_options($attr)) {
                        return $content;
                }

                return self::wrap_with_presentation($attr, $event_id, $content);
        }

        private static function has_presentation_options($attr) {
                if (self::is_truthy(isset($attr['show_title']) ? $attr['show_title'] : false)) {
                        return true;
                }

                if (!empty($attr['custom_title'])) {
                        return true;
                }

                if (self::is_truthy(isset($attr['show_event_id']) ? $attr['show_event_id'] : false)) {
                        return true;
                }

                if (!empty($attr['intro_text'])) {
                        return true;
                }

                if (!empty($attr['background']) || !empty($attr['text_color'])) {
                        return true;
                }

                if (!empty($attr['padding']) || !empty($attr['border_radius'])) {
                        return true;
                }

                if (self::is_truthy(isset($attr['show_border']) ? $attr['show_border'] : false)) {
                        return true;
                }

                if (!empty($attr['border_color']) || !empty($attr['border_width'])) {
                        return true;
                }

                if (self::is_truthy(isset($attr['shadow']) ? $attr['shadow'] : false)) {
                        return true;
                }

                if (!empty($attr['wrapper_class'])) {
                        return true;
                }

                return false;
        }

        private static function wrap_with_presentation($attr, $event_id, $content) {
                $classes = ['eer-event-sale-shortcode'];
                $extra_classes = self::sanitize_class_list(isset($attr['wrapper_class']) ? $attr['wrapper_class'] : '');
                if (!empty($extra_classes)) {
                        $classes = array_merge($classes, $extra_classes);
                }

                $style_rules = [];

                $background = self::sanitize_color_value(isset($attr['background']) ? $attr['background'] : '');
                if ($background !== '') {
                        $style_rules[] = 'background-color:' . $background;
                }

                $text_color = self::sanitize_color_value(isset($attr['text_color']) ? $attr['text_color'] : '');
                if ($text_color !== '') {
                        $style_rules[] = 'color:' . $text_color;
                }

                if (isset($attr['padding']) && $attr['padding'] !== '') {
                        $style_rules[] = 'padding:' . intval($attr['padding']) . 'px';
                }

                if (isset($attr['border_radius']) && $attr['border_radius'] !== '') {
                        $style_rules[] = 'border-radius:' . intval($attr['border_radius']) . 'px';
                }

                $show_border = self::is_truthy(isset($attr['show_border']) ? $attr['show_border'] : false);
                if ($show_border) {
                        $border_color = self::sanitize_color_value(isset($attr['border_color']) ? $attr['border_color'] : '');
                        if ($border_color === '') {
                                $border_color = '#dcdcde';
                        }
                        $border_width = isset($attr['border_width']) && $attr['border_width'] !== '' ? max(0, intval($attr['border_width'])) : 1;
                        $style_rules[] = sprintf('border:%dpx solid %s', $border_width, $border_color);
                }

                if (self::is_truthy(isset($attr['shadow']) ? $attr['shadow'] : false)) {
                        $style_rules[] = 'box-shadow:0 20px 50px rgba(0,0,0,0.1)';
                }

                $output  = '<div class="' . esc_attr(implode(' ', $classes)) . '" data-eer-event-id="' . esc_attr($event_id) . '"';
                if (!empty($style_rules)) {
                        $output .= ' style="' . esc_attr(implode(';', $style_rules)) . '"';
                }
                $output .= '>';

                $show_title = self::is_truthy(isset($attr['show_title']) ? $attr['show_title'] : false);
                $custom_title = isset($attr['custom_title']) ? sanitize_text_field($attr['custom_title']) : '';
                $title = '';
                if ($show_title) {
                        if ($custom_title !== '') {
                                $title = $custom_title;
                        } else {
                                $title = EER()->event->get_event_title($event_id);
                        }
                }

                $show_event_id = self::is_truthy(isset($attr['show_event_id']) ? $attr['show_event_id'] : false);

                if ($title !== '' || $show_event_id) {
                        $output .= '<div class="eer-event-sale-block__header">';
                        if ($title !== '') {
                                $output .= '<h3 class="eer-event-sale-block__title">' . esc_html($title) . '</h3>';
                        }
                        if ($show_event_id) {
                                $output .= '<span class="eer-event-sale-block__event-id">' . sprintf(esc_html__('#%d', 'easy-event-registration'), $event_id) . '</span>';
                        }
                        $output .= '</div>';
                }

                if (!empty($attr['intro_text'])) {
                        $output .= '<div class="eer-event-sale-block__intro">' . wp_kses_post($attr['intro_text']) . '</div>';
                }

                $output .= '<div class="eer-event-sale-block__content">' . $content . '</div>';
                $output .= '</div>';

                return $output;
        }

        private static function sanitize_class_list($classes) {
                if (!is_string($classes) || trim($classes) === '') {
                        return [];
                }

                $parts = preg_split('/\s+/', $classes, -1, PREG_SPLIT_NO_EMPTY);
                if (empty($parts)) {
                        return [];
                }

                $sanitized = [];
                foreach ($parts as $class) {
                        $sanitized_class = sanitize_html_class($class);
                        if ($sanitized_class !== '') {
                                $sanitized[] = $sanitized_class;
                        }
                }

                return $sanitized;
        }

        private static function is_truthy($value) {
                if (is_bool($value)) {
                        return $value;
                }

                if (is_numeric($value)) {
                        return (int) $value !== 0;
                }

                if (is_string($value)) {
                        $value = strtolower(trim($value));
                        return in_array($value, ['1', 'true', 'yes', 'on'], true);
                }

                return false;
        }

        private static function sanitize_color_value($color) {
                $color = is_string($color) ? trim($color) : '';
                if ($color === '') {
                        return '';
                }

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

add_shortcode('eer_event_sale', ['EER_Shortcodes', 'eer_event_sale_shortcode']);
