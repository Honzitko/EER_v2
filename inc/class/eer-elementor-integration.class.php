<?php

if (!defined('ABSPATH')) {
        exit;
}

class EER_Elementor_Integration {

        public static function init() {
                add_action('plugins_loaded', [__CLASS__, 'maybe_setup']);
        }

        public static function maybe_setup() {
                if (did_action('elementor/loaded')) {
                        self::bootstrap();
                        return;
                }

                add_action('elementor/loaded', [__CLASS__, 'bootstrap']);
        }

        public static function bootstrap() {
                require_once EER_PLUGIN_PATH . '/inc/class/eer-elementor-widget.class.php';

                add_action('elementor/widgets/register', [__CLASS__, 'register_widget']);
                add_action('elementor/elements/categories_registered', [__CLASS__, 'register_category']);
        }

        public static function register_widget($widgets_manager) {
                if (class_exists('EER_Elementor_Event_Sale_Widget')) {
                        $widgets_manager->register(new \EER_Elementor_Event_Sale_Widget());
                }
        }

        public static function register_category($elements_manager) {
                if (method_exists($elements_manager, 'add_category')) {
                        $elements_manager->add_category(
                                'easy-event-registration',
                                [
                                        'title' => __('Easy Event Registration', 'easy-event-registration'),
                                        'icon'  => 'eicon-calendar',
                                ]
                        );
                }
        }
}

EER_Elementor_Integration::init();
