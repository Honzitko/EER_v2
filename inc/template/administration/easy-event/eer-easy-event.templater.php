<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Easy_Event
{

	public static function print_page()
	{
		?>
		<div class="wrap eer-settings">
			<h1 class="wp-heading-inline"><?php echo __('Easy Event Registration', 'easy-event-registration') . ' ' . __('(EER)', 'easy-event-registration'); ?></h1>
                        <h2><?php _e('All-in-One Registration Management Tool', 'easy-event-registration'); ?></h2>

                        <p><?php _e('EER is the <strong>free</strong> to-go tool to run your school events with ease.', 'easy-event-registration'); ?></p>

                        <p><?php _e('Use the plugin to create events, sell tickets and track registrations right from your WordPress dashboard.', 'easy-event-registration'); ?></p>

                        <h3><?php _e('Quick instructions', 'easy-event-registration'); ?></h3>
                        <ol>
                                <li><?php _e('Create a new event and configure its details.', 'easy-event-registration'); ?></li>
                                <li><?php _e('Add tickets for the event with prices and limits.', 'easy-event-registration'); ?></li>
                                <li><?php _e('Publish the registration form and share it with participants.', 'easy-event-registration'); ?></li>
                                <li><?php _e('Manage orders and payments from the dashboard.', 'easy-event-registration'); ?></li>
                        </ol>

                        <p><?php echo sprintf(__('How to get started? Follow the <strong><a href="%s" target="_blank">Quick-Start Guide</a></strong> to get up and running in no time!', 'easy-event-registration'), 'https://easyschoolregistration.com/docs/general/quick-start-guide-eer/'); ?></p>

                        <p><?php _e('Need discounts, promo codes or potentially a custom feature?', 'easy-event-registration'); ?><br/>
                                <?php echo sprintf(__('Visit our <strong><a href="%s" target="_blank">website</a></strong> to check the available modules or get in touch with our team!', 'easy-event-registration'), 'https://easyschoolregistration.com/'); ?></p>
                        <br>
                        <p><?php _e('Questions or ideas? Need help with the setup?', 'easy-event-registration'); ?></p>

                        <p><?php echo sprintf(__('Check the <strong><a href="%s" target="_blank">Documentation</a></strong> or let us know via <strong><a href="%s" target="_blank">Contact form</a></strong>.', 'easy-event-registration'), 'https://easyschoolregistration.com/docs', 'https://easyschoolregistration.com/contact/'); ?></p>

			<p class="eer-socials">
				<a href="https://www.facebook.com/easyschoolregistration/" target="_blank"><i class="fab fa-facebook-square"></i></a>
				<a href="https://twitter.com/eerwp" target="_blank"><i class="fab fa-twitter"></i></a>
				<a href="https://www.youtube.com/channel/UC1Z1iogssQy7FXzlCUmCqpA" target="_blank"><i class="fab fa-youtube"></i></a>
			</p>
		</div>
		<?php
	}
}
