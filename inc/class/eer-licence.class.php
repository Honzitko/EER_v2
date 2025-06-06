<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

function eer_activate_license()
{
	// listen for our activate button to be clicked
	if (isset($_POST['eer_license_activate'])) {
		// run a quick security check
		if (!check_admin_referer('eer_nonce', 'eer_nonce')) {
			return;
		} // get out if we didn't click the Activate button
		// retrieve the license from the database
		$license = trim(EER()->settings->eer_get_option('license_key'));
		// data to send in our API request
		$api_params = [
			'edd_action' => 'activate_license',
			'license' => $license,
			'item_name' => EER_PLUGIN_NAME,
			//'item_id'    => EER_SL_ITEM_ID, // The ID of the item in EER
			'url' => home_url()
		];
		// Call the custom API.
		$response = wp_remote_post(EER_SL_STORE_URL, ['timeout' => 15, 'sslverify' => false, 'body' => $api_params]);

		// make sure the response came back okay
		if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
			$message = (is_wp_error($response) && !empty($response->get_error_message())) ? $response->get_error_message() : __('An error occurred, please try again.');
		} else {
			$license_data = json_decode(wp_remote_retrieve_body($response));
			if (false === $license_data->success) {
				switch ($license_data->error) {
					case 'expired' :
						$message = sprintf(__('Your license key expired on %s.'), date_i18n(get_option('date_format'), strtotime($license_data->expires, current_time('timestamp'))));
						break;
					case 'revoked' :
						$message = __('Your license key has been disabled.');
						break;
					case 'missing' :
						$message = __('Invalid license.');
						break;
					case 'invalid' :
					case 'site_inactive' :
						$message = __('Your license is not active for this URL.');
						break;
					case 'item_name_mismatch' :
						$message = __('This appears to be an invalid license key for.');
						break;
					case 'no_activations_left':
						$message = __('Your license key has reached its activation limit.');
						break;
					default :
						$message = __('An error occurred, please try again.');
						break;
				}
			}
		}
		// Check if anything passed on a message constituting a failure
		if (!empty($message)) {
			$base_url = admin_url('admin.php?page=eer_admin_sub_page_settings');
			$redirect = add_query_arg(['sl_activation' => 'false', 'message' => urlencode($message)], $base_url);
			wp_redirect($redirect);
			exit();
		}
		update_option('eer_license_status', $license_data->license);
		wp_redirect(admin_url('admin.php?page=eer_admin_sub_page_settings'));
		exit();
	}
}

add_action('admin_init', 'eer_activate_license');

function eer_sample_admin_notices()
{
	if (isset($_GET['sl_activation']) && !empty($_GET['message'])) {
		switch ($_GET['sl_activation']) {
			case 'false':
				$message = urldecode($_GET['message']);
				?>
				<div class="error">
					<p><?php echo $message; ?></p>
				</div>
				<?php
				break;
			case 'true':
			default:
				?>
				<div style="color:green;">
					<p><?php _e('Thank you, your license was activated.', 'easy-event-registration'); ?></p>
				</div>
				<?php
				break;
		}
	}
}

add_action('admin_notices', 'eer_sample_admin_notices');

function eer_version_in_header()
{
	echo '<meta name="generator" content="Easy Event Registration v' . EER_VERSION . '" />' . "\n";
}

add_action('wp_head', 'eer_version_in_header');