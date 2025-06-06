<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Settings_Tag {

	public function print_content($tags) {
		$tags_list = '';

		if ($tags) {
			$tags_list .= '<table class="eer-tags-table">';
			foreach ($tags as $tag) {
				if (isset($tag['type']) && ($tag['type'] === 'double')) {
					$tags_list .= '<tr><td class="eer-copy-on-click" title="' . __('Click to copy', 'easy-event-registration') . '">[' . $tag['tag'] . '][/' . $tag['tag'] . ']</td><td>' . $tag['description'] . '</td></tr>';
				} else {
					$tags_list .= '<tr><td class="eer-copy-on-click" title="' . __('Click to copy', 'easy-event-registration') . '">[' . $tag['tag'] . ']</td><td>' . $tag['description'] . '</td></tr>';
				}
			}
			$tags_list .= '</table>';
		}

		return $tags_list;
	}


	public static function eer_tag_replace_string($tag, $body, $replacement) {
		return str_replace('[' . $tag['tag'] . ']', $replacement, $body);
	}


	public static function eer_tag_replace_has_level($tag, $body, $replacement) {
		if ($replacement) {
			return str_replace('[/' . $tag['tag'] . ']', '', str_replace('[' . $tag['tag'] . ']', '', $body));
		} else {
			return preg_replace('/\[' . $tag['tag'] . '\].*\[\/' . $tag['tag'] . '\]/is', '', $body);
		}
	}


	public static function eer_tag_replace_registration_ticket_list($tag, $body, $order_id) {
		$replacement = "";
		$tag_code    = str_replace('list_', '', $tag['tag']);

		if ($order_id) {
			$replacement .= "<ul>";
			foreach (EER()->sold_ticket->eer_get_sold_tickets_by_order($order_id) as $ticket_id => $sold_ticket) {
				$ticket_data = EER()->ticket->get_ticket_data($sold_ticket->ticket_id);
				$levels      = isset($ticket_data->levels) ? $ticket_data->levels : [];
				$replacement .= "<li>" . $ticket_data->title . "<br><ul style='margin-left: 20px'>";
				if (intval(EER()->ticket->eer_get_ticket_option($ticket_data, 'levels_enabled', -1)) !== -1) {
					$replacement .= "<li>" . __('Level', 'easy-event-registration') . ": " . (($levels) ? $levels[$sold_ticket->level_id]['name'] : '') . "</li>";
				}
				if (!$ticket_data->is_solo) {
					$replacement .= "<li>" . __('Role', 'easy-event-registration') . ": " . (EER()->dancing_as->eer_get_title($sold_ticket->dancing_as)) . "</li>";
				}
				if ($sold_ticket->dancing_with) {
					$replacement .= "<li>" . __('Partner', 'easy-event-registration') . ": " . (($sold_ticket->dancing_with_name) ? $sold_ticket->dancing_with_name : '') . ' ' . (($sold_ticket->dancing_with) ? $sold_ticket->dancing_with : '') . "</li>";
				}
				$replacement .= "</ul></li> ";
			}
			$replacement .= "</ul> ";

			$body = str_replace('[' . $tag_code . '_exists]', '', $body);
			$body = str_replace('[/' . $tag_code . '_exists]', '', $body);
		} else {
			$body = preg_replace('/\[' . $tag_code . '_exists\].*\[\/' . $tag_code . '_exists\]/is', '', $body);
		}

		return str_replace("[" . $tag['tag'] . "]", $replacement, $body);
	}


	public static function eer_tag_replace_order_data($tag, $body, $order) {
		$replacement = "";

		if ($order->{$tag['id']}) {
			$replacement = $order->{$tag['id']};
		}

		return str_replace("[" . $tag['tag'] . "]", $replacement, $body);
	}


	public static function eer_tag_replace_dancing_as($tag, $body, $dancing_as) {
		return str_replace("[" . $tag['tag'] . "]", EER()->dancing_as->eer_get_title($dancing_as), $body);
	}


	public static function eer_tag_replace_order_info($tag, $body, $order_info) {
		$replacement = "";

		if (property_exists($order_info, $tag['id']) && $order_info->{$tag['id']}) {
			$replacement = $order_info->{$tag['id']};
		}

		return str_replace("[" . $tag['tag'] . "]", $replacement, $body);
	}


	public static function eer_tag_replace_tickets_list($tag, $body, $tickets) {
		$replacement = "";

		if ($tickets) {
			$replacement .= " <ul>";
			foreach ($tickets as $id => $ticket) {
				$replacement .= "<li>" . $ticket->title . "<br><ul style='margin-left: 20px'>";
				if (intval(EER()->ticket->eer_get_ticket_option($ticket, 'levels_enabled', -1)) !== -1) {
					$levels      = isset($ticket->levels) ? $ticket->levels : [];
					$replacement .= "<li>" . __('Level', 'easy-event-registration') . ": " . (($levels) ? $levels[$ticket->level_id]['name'] : '') . "</li>";
				}
				if (!$ticket->is_solo) {
					$replacement .= "<li>" . __('Role', 'easy-event-registration') . ": " . (EER()->dancing_as->eer_get_title($ticket->dancing_as)) . "</li>";
				}
				if ($ticket->dancing_with) {
					$replacement .= "<li>" . __('Partner', 'easy-event-registration') . ": " . (($ticket->dancing_with_name) ? $ticket->dancing_with_name : '') . ' ' . (($ticket->dancing_with) ? $ticket->dancing_with : '') . "</li>";
				}
				$replacement .= "</ul> </li> ";
			}
			$replacement .= "</ul> ";
		}

		return str_replace("[" . $tag['tag'] . "]", $replacement, $body);
	}


	public static function eer_tag_replace_tickets_numbers_list($tag, $body, $tickets) {
		$replacement = "";

		if ($tickets) {
			$replacement .= " <ul>";
			foreach ($tickets as $id => $ticket) {
				$replacement .= "<li>" . $ticket->title . "<br><ul style='margin-left: 20px'>";
				$replacement .= "<li>" . __('Ticket Number', 'easy-event-registration') . ": " . $ticket->unique_key . "</li>";
				$replacement .= "</ul> </li> ";
			}
			$replacement .= "</ul> ";
		}

		return str_replace("[" . $tag['tag'] . "]", $replacement, $body);
	}


	public static function eer_tag_replace_price($tag, $body, $data) {
		return str_replace("[" . $tag['tag'] . "]", EER()->currency->eer_prepare_price($data['event']->id, $data['price'], $data['event']), $body);
	}
}