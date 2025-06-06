<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Event_Sale_Thank_You_Page
{

	public function print_content($event_id, $data)
	{
		$content = '<div id="eer-thank-you" class="eer-thank-you">' . EER()->event->get_event_setting($event_id, 'thank_you', '') . '</div>';

		foreach (['registered', 'full'] as $tag) {
			if (isset($data[$tag])) {
				$content = str_replace('[list_' . $tag . ']', $this->prepare_tickets_list($data, $tag), $content);

				$content = str_replace('[' . $tag . '_exists]', '', $content);
				$content = str_replace('[/' . $tag . '_exists]', '', $content);
			} else {
				$content = preg_replace('/\[' . $tag . '_exists\].*\[\/' . $tag . '_exists\]/is', '', $content);
			}
		}

		return $content;
	}


	private function prepare_tickets_list($data, $key)
	{
		$content = '';
		if (isset($data[$key])) {
			$content .= '<p class="eer-tickets">';
			foreach ($data[$key] as $ticket_id => $ticket) {
				$ticket_data = EER()->ticket->get_ticket_data($ticket_id);
				$content .= '<span class="eer-name">' . $ticket_data->title . '</span><br>';
			}
			$content .= '</p>';
		}

		return $content;
	}

}