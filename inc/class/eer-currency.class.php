<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Currency
{

	public function eer_get_currencies()
	{
		$currencies = [
			'USD' => __('US Dollars (&#36;)', 'easy-event-registration'),
			'EUR' => __('Euros (&euro;)', 'easy-event-registration'),
			'GBP' => __('Pound Sterling (&pound;)', 'easy-event-registration'),
			'CZK' => __('Czech Crown (Kč)', 'easy-event-registration'),
			'DKK' => __('Danish Krone (kr)', 'easy-event-registration'),
			'HUF' => __('Hungarian Forint (Ft)', 'easy-event-registration'),
			'PLN' => __('Polish Zloty (Z&#x142;)', 'easy-event-registration'),
		];

		return apply_filters('eer_currencies', $currencies);
	}

	private function eer_get_currency($event_data)
	{
		return isset($event_data->currency) ? $event_data->currency : 'USD';
	}


	public function eer_get_currency_symbol($currency = '', $event_data = null)
	{
		if (empty($currency)) {
			$currency = $this->eer_get_currency($event_data);
		}

		switch ($currency) :
			case "CZK" :
				$symbol = 'Kč';
				break;
			case "GBP" :
				$symbol = '&pound;';
				break;
			case "EUR" :
				$symbol = '&euro;';
				break;
			case "USD" :
				$symbol = '&#36;';
				break;
			case "HUF" :
				$symbol = 'Ft';
				break;
			case "PLN" :
				$symbol = 'Z&#x142;';
				break;
			case "DKK" :
				$symbol = 'kr';
				break;
			default :
				$symbol = $currency;
				break;
		endswitch;

		return apply_filters('eer_currency_symbol', $symbol, $currency);
	}


	private function eer_currency_position($event_data)
	{
		return isset($event_data->currency_position) ? $event_data->currency_position : 'after_with_space';
	}


	public function eer_prepare_price($event_id, $price, $event_data = null)
	{
		if (!$event_data) {
			$event_data = EER()->event->get_event_data($event_id);
		}

		$currency_position = $this->eer_currency_position($event_data);

		switch ($currency_position) {
			case 'before':
				{
					$price_with_currency = $this->eer_get_currency_symbol(null, $event_data) . $price;
					break;
				}
			case 'before_with_space':
				{
					$price_with_currency = $this->eer_get_currency_symbol(null, $event_data) . ' ' . $price;
					break;
				}
			case 'after':
				{
					$price_with_currency = $price . $this->eer_get_currency_symbol(null, $event_data);
					break;
				}
			default :
				{
					$price_with_currency = $price . ' ' . $this->eer_get_currency_symbol(null, $event_data);
				}
		}

		return apply_filters('eer_prepare_price', $price_with_currency);
	}

}