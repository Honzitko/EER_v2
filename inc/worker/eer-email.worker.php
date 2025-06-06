<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Worker_Email {

	/**
	 * @param string $email
	 * @param string $subject
	 * @param string $body
	 * @param array $event_data
	 *
	 * @return bool
	 * @codeCoverageIgnore
	 */
	public static function eer_send_email_callback($email, $subject, $body, $event_data) {
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-type: text/html; charset=utf-8";
		$headers[] = "Content-Transfer-Encoding: base64";
		$headers[] = "From: " . EER()->event->eer_get_event_option($event_data, 'from_name') . " <" . EER()->event->eer_get_event_option($event_data, 'from_email') . ">";

		if (EER()->event->eer_get_event_option($event_data, 'bcc_email')) {
			$headers[] = "Bcc: " . EER()->event->eer_get_event_option($event_data, 'bcc_email');
		}

		$body = chunk_split(base64_encode(wpautop($body)));

		$subject = "=?utf-8?B?" . base64_encode($subject) . "?=";

		if (intval(EER()->event->eer_get_event_option($event_data, 'use_wp_mail', -1)) === -1) {
			$status = mail($email, $subject, $body, implode("\r\n", $headers), '');
		} else {
			$status = wp_mail($email, $subject, $body, implode("\r\n", $headers), '');
		}

		return $status;
	}


	public function send_ticket_email($email, $subject, $body, $event_data, $attachments = []) {
		add_filter('wp_mail_content_type', [&$this, 'set_html_content_type']);

		$headers = [
			'From:' . EER()->event->eer_get_event_option($event_data, 'from_name') . ' <' . EER()->event->eer_get_event_option($event_data, 'from_email') . '>',
			'Content-Type' => 'text/html',
			'charset=UTF-8',
		];

		if (EER()->event->eer_get_event_option($event_data, 'bcc_email')) {
			$headers = array_merge($headers, ["Bcc:" . EER()->event->eer_get_event_option($event_data, 'from_name') . "<" . EER()->event->eer_get_event_option($event_data, 'bcc_email') . ">"]);
		}

		$send = wp_mail($email, $subject, $body, $headers, $attachments);

		remove_filter('wp_mail_content_type', [&$this, 'set_html_content_type']);

		return $send;
	}


	public function set_html_content_type() {
		return 'text/html';
	}

}

add_filter('eer_send_email', ['EER_Worker_Email', 'eer_send_email_callback'], 10, 4);