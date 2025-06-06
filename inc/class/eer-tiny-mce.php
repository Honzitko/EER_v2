<?php

function eer_add_tinymce_plugin($registered_plugins) {
	/*if (get_user_option('eer_mce_button') != 'true') {
		return $registered_buttons;
	}*/

	$registered_plugins['eer_mce_button'] = EER_PLUGIN_URL . 'inc/assets/admin/js/eer-mce-plugin.js';

	return $registered_plugins;
}

add_filter('mce_external_plugins', 'eer_add_tinymce_plugin');


function eer_register_tinymce_button($registered_buttons) {
	/*if (get_user_option('eer_mce_button') != 'true') {
		return $registered_buttons;
	}*/

	array_push($registered_buttons, "eer_mce_button");
	array_push($registered_buttons, "eer_mce_button_preset1");

	return $registered_buttons;
}

add_filter('mce_buttons', 'eer_register_tinymce_button');


function eer_add_mce_scripts() {
	wp_enqueue_style('eer_mce_button_style', EER_PLUGIN_URL . 'inc/assets/admin/css/eer-tinymce.css', [], EER_VERSION);
	wp_localize_script('eer_tinymce_script', 'eer_tinymce_ajax_object', ['ajaxurl' => admin_url('admin-ajax.php')]);
}

add_action('admin_enqueue_scripts', 'eer_add_mce_scripts');
