<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EER_Worker_Event {

        /**
         * Tracks the last event ID processed.
         *
         * @var int|null
         */
        private static $last_event_id = null;

        public static function eer_process_event_callback( $data ) {
                self::$last_event_id = null;

                if ( isset( $data['event_id'] ) && ! empty( $data['event_id'] ) ) {
                        do_action( 'eer_event_update', $data['event_id'], self::prepare_data( $data, isset( $data['event_id'] ) ), $data );
                } else {
                        do_action( 'eer_event_add', self::prepare_data( $data ), $data );
                }
	}


	public static function add_event_action( $data, $raw_data ) {
		global $wpdb;
		$result = $wpdb->insert( $wpdb->prefix . 'eer_events', $data );

                if ( $result !== false ) {
                        $data['event_id'] = $wpdb->insert_id;
                        self::$last_event_id = intval( $data['event_id'] );

                        if ( isset( $raw_data['event_manager'] ) ) {
                                foreach ( $raw_data['event_manager'] as $key => $user_id ) {
					/*if ( user_can( intval( $user_id ), 'eer_partial_events_view' ) && empty( EER()->event_manager->eer_get_event_manager( $data['event_id'], $user_id ) ) {
						$wpdb->insert( $wpdb->prefix . 'eer_event_managers', [
							'user_id'  => intval( $user_id ),
							'event_id' => $data['event_id']
						] );
					}*/
				}
			}

			/*$user_id = get_current_user_id();
			if ( user_can( $user_id, 'eer_partial_events_view' ) ) {
				if ( empty( EER()->event_manager->eer_get_event_manager( $data['event_id'], $user_id ) ) ) {
					$wpdb->insert( $wpdb->prefix . 'eer_event_managers', [
						'user_id'  => $user_id,
						'event_id' => $data['event_id']
					] );
				}
			}*/

                        do_action( 'eer_module_event_add', $raw_data );
                        do_action( 'eer_event_added', $data['event_id'], $data, $raw_data );
                }
        }


        public static function update_event_action( $event_id, $data, $raw_data ) {
		global $wpdb;

                $wpdb->update( $wpdb->prefix . 'eer_events', $data, [
                        'id' => $event_id,
                ] );

                self::$last_event_id = intval( $event_id );

                $managers = [];
                if ( isset( $raw_data['event_manager'] ) ) {
                        foreach ( $raw_data['event_manager'] as $key => $user_id ) {
				$user_id = intval( $user_id );
				/*if ( user_can( intval( $user_id ), 'eer_partial_events_view' ) ) {
					if ( empty( EER()->event_manager->eer_get_event_manager( $event_id, $user_id ) ) ) {
						$wpdb->insert( $wpdb->prefix . 'eer_event_managers', [
							'user_id'  => intval( $user_id ),
							'event_id' => $event_id
						] );
					}

					$managers[] = $user_id;
				}*/
			}
		}

                do_action( 'eer_module_event_update', $event_id, $raw_data );
        }


        /**
         * Returns the ID of the last created or updated event.
         *
         * @return int|null
         */
        public static function get_last_event_id() {
                return self::$last_event_id;
        }


	private static function prepare_data( $data, $is_update = false ) {
		$return_data = [];

		foreach ( EER()->event->get_fields() as $key => $field ) {
			if ( $field['type'] === 'json' ) {
				if ( isset( $data[ $key ]['tshirt_options'] ) ) {
					$data[ $key ]['tshirt_options'] = self::array_to_objects( $data[ $key ]['tshirt_options'] );
				}
				if ( isset( $data[ $key ]['food_options'] ) ) {
					$data[ $key ]['food_options'] = self::array_to_objects( $data[ $key ]['food_options'] );
				}

				$return_data[ $key ] = json_encode( EER()->fields->eer_sanitize_event_settings( $data[ $key ] ) );
			} elseif ( ( $field['required'] && ! $is_update ) || isset( $data[ $key ] ) ) {
				$return_data[ $key ] = EER()->fields->sanitize( $field['type'], $data[ $key ] );
			}
		}

		return $return_data;
	}


	private static function array_to_objects( $data ) {
		$options = [];
		foreach ( $data as $option_key => $option ) {
			$option['key']          = $option_key;
			$options[ $option_key ] = (object) $option;
		}

		return (object) $options;
	}
}

add_action( 'eer_process_event', [ 'EER_Worker_Event', 'eer_process_event_callback' ] );
add_action( 'eer_event_add', [ 'EER_Worker_Event', 'add_event_action' ], 10, 2 );
add_action( 'eer_event_update', [ 'EER_Worker_Event', 'update_event_action' ], 10, 3 );