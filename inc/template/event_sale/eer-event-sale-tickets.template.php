<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Event_Sale_Tickets {

	public function print_content($event_id, $for_sale = true, $always_enabled = false) {
		$tickets = EER()->ticket->get_tickets_by_event($event_id);

		if ($tickets) {
			$ticket_width = 100 / count($tickets) - 2;
			?>
			<div class="eer-tickets eer-clearfix">
				<?php
				foreach ($tickets as $ticket_id => $ticket) {
					$ticket_buy_enabled = $always_enabled ? true : EER()->ticket->is_ticket_buy_enabled($ticket->id, $ticket);

					$classes = [
						'eer-ticket'
					];
					$levels  = null;

					if (!$ticket_buy_enabled) {
						$classes[] = 'eer-sold';
					}

					if ($ticket->has_levels) {
						$classes[] = 'eer-has-levels';
						$levels    = EER()->ticket_summary->eer_get_ticket_availability_by_levels($ticket->id);
						foreach ($levels as $level_id => $level) {
							$level->name = $ticket->levels[$level_id]['name'];
						}
					}
					?>
					<div class="<?php echo implode(' ', $classes); ?>" style="width: <?php echo $ticket_width ?>%"
					     data-id="<?php echo $ticket->id; ?>"
					     data-title="<?php echo $ticket->title; ?>"
					     data-price="<?php echo $ticket->price; ?>"
					     data-solo="<?php echo $ticket->is_solo; ?>"
					     data-max="<?php echo $ticket->max_per_order; ?>"
					     data-sold_separately="<?php echo $ticket->sold_separately; ?>"
						<?php if (isset($ticket->disable_partner_check)) { ?>
							data-disable_partner_check="<?php echo $ticket->disable_partner_check; ?>"
						<?php } ?>
						 data-levels="<?php echo htmlspecialchars(json_encode($levels)); ?>"
						<?php if (isset($ticket->related_tickets) && ($ticket->related_tickets !== null)) { ?>
							data-related-tickets="<?php echo htmlspecialchars(json_encode(explode(',', $ticket->related_tickets))); ?>"
						<?php } ?>
						<?php if (!$ticket->is_solo) { ?>
							data-leader-enabled="<?php echo EER()->dancing_as->eer_is_leader_registration_enabled($ticket->id); ?>"
							data-follower-enabled="<?php echo EER()->dancing_as->eer_is_followers_registration_enabled($ticket->id); ?>"
						<?php } do_action('eer_form_ticket_data', $ticket); ?>>
						<div class="eer-ticket-body-wraper">
							<div class="eer-ticket-body">
								<h3 class="eer-ticket-title"><?php echo $ticket->title; ?></h3>
								<div class="eer-ticket-content"><?php echo nl2br(stripslashes($ticket->content)); ?></div>
								<?php if ($for_sale) {
									if ($ticket_buy_enabled) { ?>
										<button class="eer-ticket-add"><svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="150px" height="150px" viewBox="0 0 1500 1500" preserveAspectRatio="xMidYMid meet">
												<g id="layer101" fill="#e04038" stroke="none">
													<path d="M618 1475 c-31 -21 -48 -25 -93 -23 -54 2 -58 1 -89 -37 -28 -33 -40 -40 -82 -44 -46 -6 -50 -8 -71 -53 -19 -39 -29 -49 -63 -59 -45 -14 -54 -25 -64 -81 -4 -24 -17 -45 -34 -57 -15 -12 -33 -26 -39 -31 -7 -6 -13 -33 -13 -61 0 -41 -6 -57 -32 -90 -31 -39 -31 -39 -18 -92 11 -46 10 -58 -4 -85 -21 -41 -20 -68 5 -103 18 -25 20 -38 14 -87 -6 -57 -6 -58 29 -90 27 -24 36 -41 36 -65 0 -55 10 -72 55 -99 34 -21 46 -36 55 -66 12 -48 24 -58 73 -67 29 -6 43 -17 65 -49 26 -38 31 -41 78 -44 42 -2 57 -9 87 -37 40 -38 56 -41 106 -24 28 10 38 9 71 -10 47 -26 70 -26 114 0 30 18 41 19 67 10 50 -17 71 -13 106 21 28 27 39 31 78 30 39 -2 50 2 68 24 48 59 67 74 89 74 36 0 65 26 72 64 7 37 19 50 69 75 29 14 33 22 37 69 5 45 11 58 43 87 40 37 42 43 27 94 -8 29 -6 41 18 82 25 45 26 51 13 81 -16 36 -18 75 -7 128 7 30 3 41 -23 74 -28 35 -31 44 -25 83 6 42 5 45 -40 85 -29 26 -46 50 -46 64 0 47 -18 73 -60 85 -34 10 -46 21 -65 60 -26 50 -39 59 -87 59 -23 0 -40 10 -68 42 -37 40 -39 41 -88 35 -46 -5 -54 -3 -89 24 -39 31 -50 32 -110 8 -28 -11 -37 -11 -68 4 -47 22 -54 22 -97 -8z"/>
												</g>
											</svg>
											<span class="dashicons dashicons-plus"></span></button>
									<?php } else { ?>
										<div class="eer-ticket-sold"><?php _e('Sold Out', 'easy-event-registration'); ?></div>
									<?php }
								} ?>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
			<?php
		}
	}
}

