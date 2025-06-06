<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('EER_License_Handler')) {

	class EER_License_Handler
	{
		private $file;
		private $license;
		private $item_name;
		private $item_id;
		private $item_shortname;
		private $version;
		private $author;
		private $api_url = 'https://easyschoolregistration.com';


		/**
		 * Class constructor
		 *
		 * @param string $_file
		 * @param string $_item_name
		 * @param string $_version
		 * @param string $_author
		 * @param string $_optname
		 * @param string $_api_url
		 * @param int $_item_id
		 */
		function __construct($_file, $_item_name, $_version, $_author, $_optname = null, $_api_url = null, $_item_id = null)
		{
			$this->file = $_file;
			$this->item_name = $_item_name;

			if (is_numeric($_item_id)) {
				$this->item_id = absint($_item_id);
			}

			$this->item_shortname = 'eer_' . preg_replace('/[^a-zA-Z0-9_\s]/', '', str_replace(' ', '_', strtolower($this->item_name)));
			$this->version = $_version;
			$this->license = trim(EER()->settings->eer_get_option($this->item_shortname . '_license_key', ''));
			$this->author = $_author;
			$this->api_url = is_null($_api_url) ? $this->api_url : $_api_url;

			/**
			 * Allows for backwards compatibility with old license options,
			 * i.e. if the plugins had license key fields previously, the license
			 * handler will automatically pick these up and use those in lieu of the
			 * user having to reactive their license.
			 */
			if (!empty($_optname)) {
				$opt = edd_get_option($_optname, false);

				if (isset($opt) && empty($this->license)) {
					$this->license = trim($opt);
				}
			}

			// Setup hooks
			$this->includes();
			$this->hooks();

		}


		/**
		 * Include the updater class
		 *
		 * @access  private
		 * @return  void
		 */
		private function includes()
		{
			if (!class_exists('EER_SL_Plugin_Updater')) {
				require_once EER_PLUGIN_PATH . '/inc/class/eer-sl-plugin-updater.class.php';
			}
		}


		/**
		 * Setup hooks
		 *
		 * @access  private
		 * @return  void
		 */
		private function hooks()
		{
			add_filter('eer_settings_licenses', [$this, 'settings'], 1);
			add_action('admin_init', [$this, 'eer_activate_module_license']);
			add_action('admin_init', [$this, 'eer_updater'], 0);
		}


		/**
		 * Add license field to settings
		 *
		 * @access  public
		 *
		 * @param array $settings
		 *
		 * @return  array
		 */
		public function settings($settings)
		{
			$eer_license_settings = [
				[
					'id' => $this->item_shortname . '_license_key',
					'name' => sprintf(__('%1$s', 'easy-event-registration'), $this->item_name),
					'desc' => '',
					'type' => 'license_key',
					'options' => ['is_valid_license_option' => $this->item_shortname . '_license_active'],
					'size' => 'regular'
				]
			];

			return array_merge($settings, $eer_license_settings);
		}


		public function eer_updater()
		{
			$args = [
				'version' => $this->version,
				'license' => $this->license,
				'author' => $this->author,
			];

			if (!empty($this->item_id)) {
				$args['item_id'] = $this->item_id;
			} else {
				$args['item_name'] = $this->item_name;
			}

			// Setup the updater
			$edd_updater = new EER_SL_Plugin_Updater($this->api_url, $this->file, $args);
		}


		public function eer_activate_module_license()
		{
			if (!isset($_POST['eer_settings'])) {
				return;
			}

			if (!isset($_REQUEST[$this->item_shortname . '_license_key-nonce']) || !wp_verify_nonce($_REQUEST[$this->item_shortname . '_license_key-nonce'], $this->item_shortname . '_license_key-nonce')) {
				return;
			}

			if (!current_user_can('eer_settings')) {
				return;
			}

			if (empty($_POST['eer_settings'][$this->item_shortname . '_license_key'])) {
				delete_option($this->item_shortname . '_license_active');
				return;
			}


			foreach ($_POST as $key => $value) {
				if (false !== strpos($key, 'license_key_deactivate')) {
					// Don't activate a key when deactivating a different key
					return;
				}
			}

			$details = get_option($this->item_shortname . '_license_active');

			if (is_object($details) && 'valid' === $details->license) {
				return;
			}

			$license = sanitize_text_field($_POST['eer_settings'][$this->item_shortname . '_license_key']);

			if (empty($license)) {
				return;
			}

			// Data to send to the API
			$api_params = [
				'edd_action' => 'activate_license',
				'license' => $license,
				'item_name' => urlencode($this->item_name),
				'url' => home_url()
			];

			// Call the API
			$response = wp_remote_post($this->api_url, [
				'timeout' => 15,
				'sslverify' => false,
				'body' => $api_params
			]);

			// Make sure there are no errors
			if (is_wp_error($response)) {
				return;
			}

			// Tell WordPress to look for updates
			set_site_transient('update_plugins', null);

			// Decode license data
			$license_data = json_decode(wp_remote_retrieve_body($response));

			update_option($this->item_shortname . '_license_active', $license_data);

		}
	}

}
