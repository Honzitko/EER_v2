<?php


// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}


function eer_install() {
	eer_run_install();
}

register_activation_hook(EER_PLUGIN_FILE, 'eer_install');


function eer_run_install() {
	global $eer_settings;

	$options = [];

	// Populate some default values
	foreach (EER()->settings->eer_get_registered_settings() as $tab => $sections) {
		foreach ($sections as $section => $settings) {
			// Check for backwards compatibility
			$tab_sections = EER()->settings->eer_get_settings_tab_sections($tab);
			if (!is_array($tab_sections) || !array_key_exists($section, $tab_sections)) {
				$settings = $sections;
			}

			foreach ($settings as $option) {
				if (!empty($option['type']) && 'checkbox' == $option['type'] && !empty($option['std'])) {
					$options[$option['id']] = '1';
				}
			}
		}

	}

	$merged_options = array_merge($eer_settings, $options);
	$eer_settings   = $merged_options;

	update_option('eer_settings', $merged_options);
	update_option('eer_version', EER_VERSION);
}
