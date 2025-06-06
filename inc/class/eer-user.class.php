<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_User
{

	public function eer_process_user_registration($data)
	{
		$email = strtolower(trim($data->user_info->email));
		$user_id = null;

		if (email_exists($email)) {
			$user = get_user_by('email', $email);
			$user_id = $user->ID;
		} else {
			$user_id = EER()->user->eer_create_new_user($email, $data->user_info->name, $data->user_info->surname)->ID;
		}

		return $user_id;
	}


	public function eer_create_new_user($email, $name, $surname)
	{
		$random_password = wp_generate_password();
		$user_id = wp_create_user($email, $random_password, $email);
		wp_update_user([
			'ID' => $user_id,
			'first_name' => $name,
			'last_name' => $surname,
			'display_name' => $name . ' ' . $surname,
		]);
		$new_user = get_user_by('email', $email);

		$new_user->set_role('esr_student'); //set role to student

		//EER()->email->send_user_registration_email($new_user->user_login, $email, $random_password);

		return $new_user;
	}

}