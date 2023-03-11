<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die;
	} // Cannot access pages directly.
	if ( ! class_exists( 'TTBM_Query' ) ) {
		class TTBM_Query {
			public function __construct() {}
			public static function query_post_type( $post_type ): WP_Query {
				$args = array(
					'post_type'      => $post_type,
					'posts_per_page' => - 1,
				);
				return new WP_Query( $args );
			}
			public static function ttbm_query( $show, $sort = '', $cat = '', $org = '', $city = '', $country = '', $status = '' ,$tour_type=''): WP_Query {
				TTBM_Function::update_all_upcoming_date_month();
				if ( get_query_var( 'paged' ) ) {
					$paged = get_query_var( 'paged' );
				} elseif ( get_query_var( 'page' ) ) {
					$paged = get_query_var( 'page' );
				} else {
					$paged = 1;
				}
				$now     = current_time( 'Y-m-d' );
				$compare = '>=';
				if ( $status ) {
					$compare = $status == 'expired' ? '<' : '>=';
				} else {
					$expire_tour = TTBM_Function::get_general_settings( 'ttbm_expire', 'yes' );
					$compare     = $expire_tour == 'yes' ? '' : $compare;
				}
				$expire_filter  = $compare ? array(
					'key'     => 'ttbm_upcoming_date',
					'value'   => $now,
					'compare' => $compare
				) : '';
				$cat_filter     = ! empty( $cat ) ? array(
					'taxonomy' => 'ttbm_tour_cat',
					'field'    => 'term_id',
					'terms'    => $cat
				) : '';
				$org_filter     = ! empty( $org ) ? array(
					'taxonomy' => 'ttbm_tour_org',
					'field'    => 'term_id',
					'terms'    => $org
				) : '';
				$city_filter    = ! empty( $city ) ? array(
					'key'     => 'ttbm_location_name',
					'value'   => $city,
					'compare' => 'LIKE'
				) : '';
				$country_filter = ! empty( $country ) ? array(
					'key'     => 'ttbm_country_name',
					'value'   => $country,
					'compare' => 'LIKE'
				) : '';
				$tour_type_filter= ! empty( $tour_type ) ? array(
					'key'     => 'ttbm_type',
					'value'   => $tour_type,
					'compare' => 'LIKE'
				) : '';
				$args           = array(
					'post_type'      => array(TTBM_Function::get_cpt_name() ),
					'paged'          => $paged,
					'posts_per_page' => $show,
					'order'          => $sort,
					'orderby'        => 'meta_value',
					'meta_key'       => 'ttbm_upcoming_date',
					'meta_query'     => array(
						$expire_filter,
						$city_filter,
						$country_filter,
						$tour_type_filter
					),
					'tax_query'      => array(
						$cat_filter,
						$org_filter
					)
				);
				return new WP_Query( $args );
			}
			public static function get_all_tour_in_location( $location, $status = '' ): WP_Query {
				$compare = '>=';
				if ( $status ) {
					$compare = $status == 'expired' ? '<' : '>=';
				} else {
					$expire_tour = TTBM_Function::get_general_settings( 'ttbm_expire', 'yes' );
					$compare     = $expire_tour == 'yes' ? '' : $compare;
				}
				$location      = ! empty( $location ) ? array(
					'key'     => 'ttbm_location_name',
					'value'   => $location,
					'compare' => 'LIKE'
				) : '';
				$expire_filter =  ! empty( $compare ) ?array(
					'key'     => 'ttbm_upcoming_date',
					'value'   => current_time( 'Y-m-d' ),
					'compare' => $compare
				):'';
				$args          = array(
					'post_type'      => array(TTBM_Function::get_cpt_name() ),
					'posts_per_page' => - 1,
					'order'          => 'ASC',
					'orderby'        => 'meta_value',
					'meta_query'     => array( $location, $expire_filter )
				);
				return new WP_Query( $args );
			}
			public static function get_hotel_list(): WP_Query {
				$args = array(
					'post_type'      => 'ttbm_hotel',
					'posts_per_page' => - 1
				);
				return new WP_Query( $args );
			}
			public static function get_all_tour_id(): array {
				return get_posts( array(
					'fields'         => 'ids',
					'posts_per_page' => - 1,
					'post_type'      => TTBM_Function::get_cpt_name(),
					'post_status'    => 'publish'
				) );
			}
			public static function get_order_meta( $item_id, $key ): string {
				global $wpdb;
				$table_name = $wpdb->prefix . "woocommerce_order_itemmeta";
				$results    = $wpdb->get_results( $wpdb->prepare( "SELECT meta_value FROM $table_name WHERE order_item_id = %d AND meta_key = %s", $item_id, $key ) );
				foreach ( $results as $result ) {
					$value = $result->meta_value;
				}
				return $value ?? '';
			}
			public static function query_all_sold( $tour_id, $tour_date, $type = '', $hotel_id = '' ): WP_Query {
				$_seat_booked_status      = TTBM_Function::get_general_settings( 'ttbm_set_book_status', array( 'processing', 'completed' ) );
				$seat_booked_status       = ! empty( $_seat_booked_status ) ? $_seat_booked_status : [];
				$type_filter              = ! empty( $type ) ? array(
					'key'     => 'ttbm_ticket_name',
					'value'   => $type,
					'compare' => '='
				) : '';
				$date_filter              = ! empty( $tour_date ) ? array(
					'key'     => 'ttbm_date',
					'value'   => $tour_date,
					'compare' => 'LIKE'
				) : '';
				$hotel_filter             = ! empty( $hotel_id ) ? array(
					'key'     => 'ttbm_hotel_id',
					'value'   => $hotel_id,
					'compare' => '='
				) : '';
				$pending_status_filter    = in_array( 'pending', $seat_booked_status ) ? array(
					'key'     => 'ttbm_order_status',
					'value'   => 'pending',
					'compare' => '='
				) : '';
				$on_hold_status_filter    = in_array( 'on-hold', $seat_booked_status ) ? array(
					'key'     => 'ttbm_order_status',
					'value'   => 'on-hold',
					'compare' => '='
				) : '';
				$processing_status_filter = in_array( 'processing', $seat_booked_status ) ? array(
					'key'     => 'ttbm_order_status',
					'value'   => 'processing',
					'compare' => '='
				) : '';
				$completed_status_filter  = in_array( 'completed', $seat_booked_status ) ? array(
					'key'     => 'ttbm_order_status',
					'value'   => 'completed',
					'compare' => '='
				) : '';
				$args                     = array(
					'post_type'      => 'ttbm_booking',
					'posts_per_page' => - 1,
					'meta_query'     => array(
						'relation' => 'AND',
						array(
							'relation' => 'AND',
							array(
								'key'     => 'ttbm_id',
								'value'   => $tour_id,
								'compare' => '='
							),
							$type_filter,
							$hotel_filter,
							$date_filter
						),
						array(
							'relation' => 'OR',
							$pending_status_filter,
							$on_hold_status_filter,
							$processing_status_filter,
							$completed_status_filter
						)
					)
				);
				return new WP_Query( $args );
			}
			public static function query_all_service_sold( $tour_id, $tour_date, $type = '' ): WP_Query {
				$_seat_booked_status      = TTBM_Function::get_general_settings( 'ttbm_set_book_status', array( 'processing', 'completed' ) );
				$seat_booked_status       = ! empty( $_seat_booked_status ) ? $_seat_booked_status : [];
				$type_filter              = ! empty( $type ) ? array(
					'key'     => 'ttbm_service_name',
					'value'   => $type,
					'compare' => '='
				) : '';
				$date_filter              = ! empty( $tour_date ) ? array(
					'key'     => 'ttbm_date',
					'value'   => $tour_date,
					'compare' => 'LIKE'
				) : '';
				$pending_status_filter    = in_array( 'pending', $seat_booked_status ) ? array(
					'key'     => 'ttbm_order_status',
					'value'   => 'pending',
					'compare' => '='
				) : '';
				$on_hold_status_filter    = in_array( 'on-hold', $seat_booked_status ) ? array(
					'key'     => 'ttbm_order_status',
					'value'   => 'on-hold',
					'compare' => '='
				) : '';
				$processing_status_filter = array(
					'key'     => 'ttbm_order_status',
					'value'   => 'processing',
					'compare' => '='
				);
				$completed_status_filter  = array(
					'key'     => 'ttbm_order_status',
					'value'   => 'completed',
					'compare' => '='
				);
				$args                     = array(
					'post_type'      => 'ttbm_service_booking',
					'posts_per_page' => - 1,
					'meta_query'     => array(
						'relation' => 'AND',
						array(
							'relation' => 'AND',
							array(
								'key'     => 'ttbm_id',
								'value'   => $tour_id,
								'compare' => '='
							),
							$type_filter,
							$date_filter
						),
						array(
							'relation' => 'OR',
							$pending_status_filter,
							$on_hold_status_filter,
							$processing_status_filter,
							$completed_status_filter
						)
					)
				);
				return new WP_Query( $args );
			}
		}
		new TTBM_Query();
	}