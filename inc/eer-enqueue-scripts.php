<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Enqueue_Scripts {

	public static function add_web_scripts() {
		self::enqueue_web_style_scripts();
		self::enqueue_web_js_scripts();
	}


	public static function eer_register_theme_scripts_callback() {
		wp_register_style('eer_web_style', EER_PLUGIN_URL . 'inc/assets/web/css/eer-web.css', [], EER_VERSION);
		wp_register_script('eer_web_script', EER_PLUGIN_URL . 'inc/assets/web/js/eer-web.js', ['jquery'], EER_VERSION);
		wp_register_script('eer_spin_js_script', EER_PLUGIN_URL . 'libs/spin/js/spin.min.js', ['jquery'], EER_VERSION);
		wp_localize_script('eer_web_script', 'eer_ajax_object', [
                        'ajaxurl' => admin_url('admin-ajax.php'),
                        'nonce'   => wp_create_nonce('eer_ajax_nonce'),
                ]);
	}


	public static function eer_load_theme_scripts_callback() {
		wp_enqueue_style('eer_web_style');
		wp_enqueue_script('eer_web_script');
		wp_enqueue_script('eer_spin_js_script');
		wp_enqueue_style('dashicons');
	}


	private static function enqueue_web_style_scripts() {
		wp_enqueue_style('eer_web_style', EER_PLUGIN_URL . 'inc/assets/web/css/eer-web.css', [], EER_VERSION);
	}


	private static function enqueue_web_js_scripts() {
		wp_enqueue_script('eer_web_script', EER_PLUGIN_URL . 'inc/assets/web/js/eer-web.js', ['jquery'], EER_VERSION);
		wp_enqueue_script('eer_spin_js_script', EER_PLUGIN_URL . 'libs/spin/js/spin.min.js', ['jquery'], EER_VERSION);
		wp_localize_script('eer_web_script', 'eer_ajax_object', [
                        'ajaxurl' => admin_url('admin-ajax.php'),
                        'nonce'   => wp_create_nonce('eer_ajax_nonce'),
                ]);
	}


	public static function add_admin_scripts() {

		$eer_scripts = [
			EER_Template_Payments::MENU_SLUG => [
				'eer_scripts_default_admin',
				'eer_scripts_datatable',
			],
			EER_Admin::ADMIN_MENU_SLUG       => [
				'eer_scripts_default_admin',
			]
		];

		if (self::check_page_base(EER_Template_Event::MENU_SLUG) || self::check_page_base(EER_Template_Ticket::MENU_SLUG)) {
			wp_enqueue_script('eer_admin_events_script', EER_PLUGIN_URL . 'inc/assets/admin/js/eer-production.js', ['jquery', 'wp-color-picker']);
			self::eer_include_admin_scripts();
			self::eer_include_datatable_scripts();
			wp_enqueue_script('tinymce');
			wp_enqueue_style('wp-color-picker');
		} elseif (self::check_page_base(EER_Template_Order::MENU_SLUG) || self::check_page_base(EER_Template_Sold_Ticket::MENU_SLUG) || self::check_page_base(EER_Template_Payment_Emails::MENU_SLUG) || self::check_page_base(EER_Template_Tickets_In_Numbers::MENU_SLUG)) {
			wp_enqueue_script('eer_admin_events_script', EER_PLUGIN_URL . 'inc/assets/admin/js/eer-production.js', ['jquery']);
			self::eer_include_admin_scripts();
			self::eer_include_datatable_scripts();
		}

		if (self::check_page_base(EER_Template_Add_Over_Limit::MENU_SLUG)) {
			wp_enqueue_style('eer_web_style', EER_PLUGIN_URL . 'inc/assets/web/css/eer-web.css', [], EER_VERSION);
			wp_enqueue_script('eer_admin_events_script', EER_PLUGIN_URL . 'inc/assets/admin/js/eer-production.js', ['jquery'], EER_VERSION);
			wp_enqueue_script('eer_spin_js_script', EER_PLUGIN_URL . 'libs/spin/js/spin.min.js', ['jquery'], EER_VERSION);
			self::eer_include_admin_scripts();
		}

		if (self::check_page_base(EER_Template_Settings::MENU_SLUG)) {
			wp_enqueue_style('eer_admin_style', EER_PLUGIN_URL . 'inc/assets/admin/css/eer-admin-settings.css', [], EER_VERSION);
			wp_enqueue_script('eer_admin_events_script', EER_PLUGIN_URL . 'inc/assets/admin/js/eer-production.js', ['jquery'], EER_VERSION);
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'wp-color-picker' );
		}

		foreach ($eer_scripts as $key => $scripts) {
			if (self::check_page_base($key)) {
				foreach ($scripts as $script) {
					do_action($script);
				}
				continue;
			}
		}
	}


	private static function eer_include_admin_scripts() {
		wp_localize_script('eer_admin_events_script', 'eer_ajax_object', [
                        'ajaxurl' => admin_url('admin-ajax.php'),
                        'nonce'   => wp_create_nonce('eer_ajax_nonce'),
                ]);
		wp_enqueue_style('eer_admin_style', EER_PLUGIN_URL . 'inc/assets/admin/css/eer-admin-settings.css', [], EER_VERSION);
	}


	private static function eer_include_datatable_scripts() {
		wp_enqueue_script('eer_dataTables_script', EER_PLUGIN_URL . 'libs/datatable/datatables.min.js', ['jquery'], EER_VERSION);
		wp_enqueue_style('eer_dataTables_style', EER_PLUGIN_URL . 'libs/datatable/datatables.min.css', [], EER_VERSION);
		wp_enqueue_style('eer_admin_bootstrap_style', EER_PLUGIN_URL . 'libs/bootstrap/css/bootstrap-ofic.css', [], EER_VERSION);
		wp_enqueue_script('eer_bootstrap_script', EER_PLUGIN_URL . 'libs/bootstrap/js/bootstrap.min.js', ['jquery'], EER_VERSION);
	}


	public static function eer_scripts_default_admin_callback() {
		wp_enqueue_script('eer_admin_events_script', EER_PLUGIN_URL . 'inc/assets/admin/js/eer-production.js', ['jquery'], EER_VERSION);
		wp_localize_script('eer_admin_events_script', 'eer_ajax_object', [
                        'ajaxurl' => admin_url('admin-ajax.php'),
                        'nonce'   => wp_create_nonce('eer_ajax_nonce'),
                ]);
		wp_enqueue_style('eer_admin_style', EER_PLUGIN_URL . 'inc/assets/admin/css/eer-admin-settings.css', [], EER_VERSION);

		wp_enqueue_style('eer_admin_bootstrap_style', EER_PLUGIN_URL . 'libs/bootstrap/css/bootstrap-ofic.css', [], EER_VERSION);
		wp_enqueue_script('eer_bootstrap_script', EER_PLUGIN_URL . 'libs/bootstrap/js/bootstrap.min.js', ['jquery'], EER_VERSION);
	}


	public static function eer_scripts_datatable_callback() {
		wp_enqueue_script('eer_dataTables_script', EER_PLUGIN_URL . 'libs/datatable/datatables.min.js', ['jquery'], EER_VERSION);
		wp_enqueue_style('eer_dataTables_style', EER_PLUGIN_URL . 'libs/datatable/datatables.min.css', [], EER_VERSION);
	}


	private static function check_page_base($base_to_check) {
		return strpos(get_current_screen()->base, $base_to_check) !== false;
	}


	public static function eer_add_custom_styles_callback() {
		do_action( 'eer_print_styles' );
	}

}
add_action( 'wp_footer', [ 'EER_Enqueue_Scripts', 'eer_add_custom_styles_callback' ], 99 );

add_action('eer_load_theme_scripts', ['EER_Enqueue_Scripts', 'eer_load_theme_scripts_callback']);

add_action('wp_enqueue_scripts', ['EER_Enqueue_Scripts', 'eer_register_theme_scripts_callback']);

//add_action('wp_enqueue_scripts', ['EER_Enqueue_Scripts', 'add_web_scripts']);
add_action('admin_enqueue_scripts', ['EER_Enqueue_Scripts', 'add_admin_scripts']);

//Script calls
add_action('eer_scripts_default_admin', ['EER_Enqueue_Scripts', 'eer_scripts_default_admin_callback']);
add_action('eer_scripts_datatable', ['EER_Enqueue_Scripts', 'eer_scripts_datatable_callback']);