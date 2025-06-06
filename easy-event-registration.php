<?php
/**
 * Plugin Name: Easy Event Registration
 * Plugin URI: https://easyschoolregistration.com/
 * Description: System for Easy Event Registration
 * Version: 1.3.3
 * Author: Zbyněk Nedoma
 * Author URI: https://easyschoolregistration.com/
 * License: GPL-3.0-or-later
 * Plugin Slug: eer
 */


// Exit if accessed directly.
if (!defined('ABSPATH')) {
        exit;
}

if (!class_exists('Easy_Event_Registration')) {

	/**
	 * Main Easy_Event_Registration Class.
	 *
	 */
	final class Easy_Event_Registration {

		/**
		 * @var Easy_Event_Registration
		 */
		private static $instance;

		/**
		 * @var EER_Currency
		 */
		public $currency;

		/**
		 * @var EER_Enum_Dancing_As
		 */
		public $dancing_as;

		/**
		 * @var EER_Email
		 */
		public $email;

		/**
		 * @var EER_Enum_Payment
		 */
		public $enum_payment;

		/**
		 * @var EER_Event
		 */
		public $event;

		/**
		 * @var EER_Event_Manager
		 */
		public $event_manager;

		/**
		 * @var EER_Fields
		 */
		public $fields;

		/**
		 * @var EER_Model_Settings
		 */
		public $model_settings;

		/**
		 * @var EER_Order
		 */
		public $order;

		/**
		 * @var EER_Enum_Order_Status
		 */
		public $order_status;

		/**
		 * @var EER_Enum_Pairing_Mode
		 */
		public $pairing_mode;

		/**
		 * @var EER_Payment
		 */
		public $payment;

		/**
		 * @var EER_Settings
		 */
		public $settings;

		/**
		 * @var EER_Sold_Ticket
		 */
		public $sold_ticket;

		/**
		 * @var EER_Enum_Sold_Ticket_Status
		 */
		public $sold_ticket_status;

		/**
		 * @var EER_Tags
		 */
		public $tags;

		/**
		 * @var EER_Ticket
		 */
		public $ticket;

		/**
		 * @var EER_Ticket_Summary
		 */
		public $ticket_summary;

		/**
		 * @var EER_User
		 */
		public $user;


		/**
		 * Main Easy_Event_Registration Instance.
		 *
		 * Insures that only one instance of Easy_Event_Registration exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @static
		 * @staticvar array $instance
		 * @see EER()
		 * @return object|Easy_Event_Registration
		 */
		public static function instance() {
			if (!isset(self::$instance) && !(self::$instance instanceof Easy_Event_Registration)) {
				self::$instance = new Easy_Event_Registration;
				self::$instance->setup_constants();

				self::$instance->includes();

				self::$instance->load_textdomain();

				self::$instance->currency           = new EER_Currency();
				self::$instance->dancing_as         = new EER_Enum_Dancing_As();
				self::$instance->event_manager           = new EER_Event_Manager();
				self::$instance->email              = new EER_Email();
				self::$instance->enum_payment       = new EER_Enum_Payment();
				self::$instance->event              = new EER_Event();
				self::$instance->fields             = new EER_Fields();
				self::$instance->model_settings     = new EER_Model_Settings();
				self::$instance->order              = new EER_Order();
				self::$instance->order_status       = new EER_Enum_Order_Status();
				self::$instance->payment            = new EER_Payment();
				self::$instance->pairing_mode       = new EER_Enum_Pairing_Mode();
				self::$instance->settings           = new EER_Settings();
				self::$instance->sold_ticket        = new EER_Sold_Ticket();
				self::$instance->sold_ticket_status = new EER_Enum_Sold_Ticket_Status();
				self::$instance->tags               = new EER_Tags();
				self::$instance->ticket             = new EER_Ticket();
				self::$instance->ticket_summary     = new EER_Ticket_Summary();
				self::$instance->user               = new EER_User();
			}

			return self::$instance;
		}


		/**
		 * Throw error on object clone.
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @access protected
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden.
			_doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'easy-event-registration'), '1.0');
		}


		/**
		 * Disable unserializing of the class.
		 *
		 * @access protected
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden.
			_doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'easy-event-registration'), '1.0');
		}


		private function setup_constants() {
			define('EER_SLUG', 'eer');
			define('EER_VERSION', '1.3.3');
			// Plugin Root File.
			if (!defined('EER_PLUGIN_FILE')) {
				define('EER_PLUGIN_FILE', __FILE__);
			}

			// Plugin Name
			if (!defined('EER_PLUGIN_NAME')) {
				define('EER_PLUGIN_NAME', 'Easy Event Registration');
			}

			define('EER_PLUGIN_PATH', dirname(__FILE__));
			define('EER_PLUGIN_URL', plugin_dir_url(__FILE__));
			define('EER_PLUGIN_DIR', plugin_dir_path(__FILE__));

			if (!defined('EER_SL_STORE_URL')) {
				define('EER_SL_STORE_URL', 'https://easyschoolregistration.com');
			}
			if (!defined('EER_SL_ITEM_ID')) {
				define('EER_SL_ITEM_ID', 1385);
			}
		}


		/**
		 * Include required files.
		 *
		 * @access private
		 * @return void
		 */
		private function includes() {
			global $eer_settings;

			require_once EER_PLUGIN_PATH . '/inc/eer-enqueue-scripts.php';

			require_once EER_PLUGIN_PATH . '/inc/class/eer-admin.class.php';
			require_once EER_PLUGIN_PATH . '/inc/class/eer-ajax.class.php';
			require_once EER_PLUGIN_PATH . '/inc/class/eer-currency.class.php';
			require_once EER_PLUGIN_PATH . '/inc/class/eer-email.class.php';
			require_once EER_PLUGIN_PATH . '/inc/class/eer-event-manager.php';
			require_once EER_PLUGIN_PATH . '/inc/class/eer-fields.class.php';
			require_once EER_PLUGIN_PATH . '/inc/class/eer-licence.class.php';
			require_once EER_PLUGIN_PATH . '/inc/class/eer-license-handler.class.php';
			require_once EER_PLUGIN_PATH . '/inc/class/eer-model-settings.class.php';
			require_once EER_PLUGIN_PATH . '/inc/class/eer-settings.class.php';
			require_once EER_PLUGIN_PATH . '/inc/class/eer-shortcodes.class.php';
			require_once EER_PLUGIN_PATH . '/inc/class/eer-sl-plugin-updater.class.php';
			require_once EER_PLUGIN_PATH . '/inc/class/eer-tags.class.php';
			require_once EER_PLUGIN_PATH . '/inc/class/eer-user.class.php';

			require_once EER_PLUGIN_PATH . '/inc/class/eer-tiny-mce.php';

			require_once EER_PLUGIN_PATH . '/inc/database/eer-database.php';

			require_once EER_PLUGIN_PATH . '/inc/enum/eer-countries.enum.php';
			require_once EER_PLUGIN_PATH . '/inc/enum/eer-dancing-as.enum.php';
			require_once EER_PLUGIN_PATH . '/inc/enum/eer-order-status.enum.php';
			require_once EER_PLUGIN_PATH . '/inc/enum/eer-pairing-mode.enum.php';
			require_once EER_PLUGIN_PATH . '/inc/enum/eer-payment.enum.php';
			require_once EER_PLUGIN_PATH . '/inc/enum/eer-role.enum.php';
			require_once EER_PLUGIN_PATH . '/inc/enum/eer-sold-ticket-status.enum.php';

			require_once EER_PLUGIN_PATH . '/inc/model/eer-event.php';
			require_once EER_PLUGIN_PATH . '/inc/model/eer-order.php';
			require_once EER_PLUGIN_PATH . '/inc/model/eer-payment.php';
			require_once EER_PLUGIN_PATH . '/inc/model/eer-sold-ticket.php';
			require_once EER_PLUGIN_PATH . '/inc/model/eer-ticket.php';
			require_once EER_PLUGIN_PATH . '/inc/model/eer-ticket-summary.php';

			require_once EER_PLUGIN_PATH . '/inc/template/administration/easy-event/eer-easy-event.templater.php';
			require_once EER_PLUGIN_PATH . '/inc/template/administration/add-over-limit/eer-add-over-limit.template.php';
			require_once EER_PLUGIN_PATH . '/inc/template/administration/add-over-limit/subblock/eer-add-over-limit-form.subblock.php';
			require_once EER_PLUGIN_PATH . '/inc/template/administration/event/eer-event.template.php';
			require_once EER_PLUGIN_PATH . '/inc/template/administration/event/subblock/eer-event-editor.subblock.php';
			require_once EER_PLUGIN_PATH . '/inc/template/administration/event/subblock/eer-event-table.subblock.php';
			require_once EER_PLUGIN_PATH . '/inc/template/administration/order/eer-order.template.php';
			require_once EER_PLUGIN_PATH . '/inc/template/administration/order/subblock/eer-order-form.subblock.php';
			require_once EER_PLUGIN_PATH . '/inc/template/administration/order/subblock/eer-order-table.subblock.php';
			require_once EER_PLUGIN_PATH . '/inc/template/administration/payment-emails/eer-payment-emails.template.php';
			require_once EER_PLUGIN_PATH . '/inc/template/administration/payments/eer-payments.template.php';
			require_once EER_PLUGIN_PATH . '/inc/template/administration/payments/subblock/eer-payment-edit-form.template.php';
			require_once EER_PLUGIN_PATH . '/inc/template/administration/payments/subblock/eer-payment-table.php';
			require_once EER_PLUGIN_PATH . '/inc/template/administration/settings/eer-settings.template.php';
			require_once EER_PLUGIN_PATH . '/inc/template/administration/settings/eer-settings-helper.template.php';
			require_once EER_PLUGIN_PATH . '/inc/template/administration/sold-ticket/eer-sold-ticket.template.php';
			require_once EER_PLUGIN_PATH . '/inc/template/administration/sold-ticket/subblock/eer-sold-ticket-table.subblock.php';
			require_once EER_PLUGIN_PATH . '/inc/template/administration/sold-ticket/subblock/eer-sold-ticket-form.subblock.php';
			require_once EER_PLUGIN_PATH . '/inc/template/administration/ticket/eer-ticket.template.php';
			require_once EER_PLUGIN_PATH . '/inc/template/administration/ticket/subblock/eer-ticket-editor.subblock.php';
			require_once EER_PLUGIN_PATH . '/inc/template/administration/ticket/subblock/eer-ticket-table.subblock.php';
			require_once EER_PLUGIN_PATH . '/inc/template/administration/tickets-in-number/eer-tickets-in-numbers.template.php';

			//Email templates
			require_once EER_PLUGIN_PATH . '/inc/template/email/eer-order-confirmation-email.template.php';
			require_once EER_PLUGIN_PATH . '/inc/template/email/eer-order-email.template.php';
			require_once EER_PLUGIN_PATH . '/inc/template/email/eer-payment-confirmation-email.template.php';
			require_once EER_PLUGIN_PATH . '/inc/template/email/eer-payment-email.template.php';
			require_once EER_PLUGIN_PATH . '/inc/template/email/eer-user-registration-email.template.php';

			require_once EER_PLUGIN_PATH . '/inc/template/event_sale/eer-event-sale.template.php';
			require_once EER_PLUGIN_PATH . '/inc/template/event_sale/eer-event-sale-not-opened.template.php';
			require_once EER_PLUGIN_PATH . '/inc/template/event_sale/eer-event-sale-thank-you-page.templater.php';
			require_once EER_PLUGIN_PATH . '/inc/template/event_sale/eer-event-sale-tickets.template.php';
			require_once EER_PLUGIN_PATH . '/inc/template/event_sale/eer-event-sale-user-form.template.php';

			require_once EER_PLUGIN_PATH . '/inc/template/helpers/eer-models-settings-helper.template.php';
			require_once EER_PLUGIN_PATH . '/inc/template/helpers/eer-all-events-select.templater.php';
			require_once EER_PLUGIN_PATH . '/inc/template/helpers/eer-settings-tag.template.php';
			require_once EER_PLUGIN_PATH . '/inc/template/helpers/filters/eer-event-tickets-filter.templater.php';
			require_once EER_PLUGIN_PATH . '/inc/template/helpers/eer-style.template.php';

			require_once EER_PLUGIN_PATH . '/inc/worker/eer-ajax.worker.php';
			require_once EER_PLUGIN_PATH . '/inc/worker/eer-email.worker.php';
			require_once EER_PLUGIN_PATH . '/inc/worker/eer-event-sale-couple.worker.php';
			require_once EER_PLUGIN_PATH . '/inc/worker/eer-event-sale-solo.worker.php';
			require_once EER_PLUGIN_PATH . '/inc/worker/eer-event-sale.worker.php';
			require_once EER_PLUGIN_PATH . '/inc/worker/eer-event.worker.php';
			require_once EER_PLUGIN_PATH . '/inc/worker/eer-order.worker.php';
			require_once EER_PLUGIN_PATH . '/inc/worker/eer-payment.worker.php';
			require_once EER_PLUGIN_PATH . '/inc/worker/eer-payment-email.worker.php';
			require_once EER_PLUGIN_PATH . '/inc/worker/eer-sold-ticket.worker.php';
			require_once EER_PLUGIN_PATH . '/inc/worker/eer-ticket.worker.php';
			require_once EER_PLUGIN_PATH . '/inc/worker/eer-tickets-in-numbers.worker.php';

			require_once EER_PLUGIN_PATH . '/inc/install.php';

			$settings_class = new EER_Settings();
			$eer_settings   = $settings_class->eer_get_settings();
		}




		public function load_textdomain() {
			// This filter is already documented in WordPress core
			$locale = apply_filters('plugin_locale', get_locale(), 'easy-event-registration');

			$mofile = sprintf('%1$s-%2$s.mo', 'easy-event-registration', $locale);

			$mofile_local  = trailingslashit(EER_PLUGIN_URL . 'languages') . $mofile;
			$mofile_global = WP_LANG_DIR . '/easy-event-registration/' . $mofile;

			if (file_exists($mofile_global)) {
				return load_textdomain('easy-event-registration', $mofile_global);
			} else if (file_exists($mofile_local)) {
				return load_textdomain('easy-event-registration', $mofile_local);
			} else {
				return load_plugin_textdomain('easy-event-registration', false, dirname(plugin_basename(__FILE__)) . '/languages/');
			}
		}

	}
}

$license_key = EER()->settings->eer_get_option('license_key');
$license_key = $license_key ? trim($license_key) : '';

// setup the updater
$edd_updater = new EER_SL_Plugin_Updater(EER_SL_STORE_URL, EER_PLUGIN_FILE, [
	'version'   => EER_VERSION,        // current version number
	'license'   => $license_key,    // license key (used get_option above to retrieve from DB)
	'item_name' => EER_PLUGIN_NAME,
	'author'    => 'zbynek',  // author of this plugin
	'url'       => home_url(),
	'beta'      => false
]);

function EER() {
	return Easy_Event_Registration::instance();
}

// Get EER Running.
EER();