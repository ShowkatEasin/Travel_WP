<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die;
	} // Cannot access pages directly.
	if ( ! class_exists( 'TTBM_Function' ) ) {
		class TTBM_Function {
			public function __construct() {
				add_action( 'ttbm_date_picker_js', array( $this, 'date_picker_js' ), 10, 2 );
			}
			public static function get_post_info( $tour_id, $key, $default = '' ) {
				$data = get_post_meta( $tour_id, $key, true ) ?: $default;
				return self::data_sanitize( $data );
			}
			public static function data_sanitize( $data ) {
				$data = maybe_unserialize( $data );
				if ( is_string( $data ) ) {
					$data = maybe_unserialize( $data );
					if ( is_array( $data ) ) {
						$data = self::data_sanitize( $data );
					} else {
						$data = sanitize_text_field( $data );
					}
				} elseif ( is_array( $data ) ) {
					foreach ( $data as &$value ) {
						if ( is_array( $value ) ) {
							$value = self::data_sanitize( $value );
						} else {
							$value = sanitize_text_field( $value );
						}
					}
				}
				return $data;
			}
			public static function submit_sanitize( $key, $default = '' ) {
				$data = $_POST[ $key ] ?? $default;
				$data = stripslashes( strip_tags( $data ) );
				return self::data_sanitize( $data );
			}
			public static function get_submit_info( $key, $default = '' ) {
				$data = $_POST[ $key ] ?? $default;
				return self::data_sanitize( $data );
			}
			//***********Template********************//
			public static function all_details_template() {
				$template_path = get_stylesheet_directory() . '/ttbm_templates/themes/';
				$default_path  = TTBM_PLUGIN_DIR . '/templates/themes/';
				$dir           = is_dir( $template_path ) ? glob( $template_path . "*" ) : glob( $default_path . "*" );
				$names         = array();
				foreach ( $dir as $filename ) {
					if ( is_file( $filename ) ) {
						$file           = basename( $filename );
						$name           = str_replace( "?>", "", strip_tags( file_get_contents( $filename, false, null, 24, 16 ) ) );
						$names[ $file ] = $name;
					}
				}
				$name = [];
				foreach ( $names as $key => $value ) {
					$name[ $key ] = $value;
				}
				return apply_filters( 'ttbm_template_list_arr', $name );
			}
			public static function details_template_path(): string {
				$tour_id       = get_the_id();
				$template_name = self::get_post_info( $tour_id, 'ttbm_theme_file', 'default.php' );
				$file_name     = 'themes/' . $template_name;
				$dir           = TTBM_PLUGIN_DIR . '/templates/' . $file_name;
				if ( ! file_exists( $dir ) ) {
					$file_name = 'themes/default.php';
				}
				return self::template_path( $file_name );
			}
			public static function template_path( $file_name ): string {
				$template_path = get_stylesheet_directory() . '/ttbm_templates/';
				$default_dir   = TTBM_PLUGIN_DIR . '/templates/';
				$dir           = is_dir( $template_path ) ? $template_path : $default_dir;
				$file_path     = $dir . $file_name;
				return locate_template( array( 'ttbm_templates/' . $file_name ) ) ? $file_path : $default_dir . $file_name;
			}
			//*********Date and Time**********************//
			public static function get_date( $tour_id, $expire = '' ) {
				$tour_date   = [];
				$travel_type = TTBM_Function::get_travel_type( $tour_id );
				$now         = strtotime( current_time( 'Y-m-d H:i:s' ) );
				if ( $travel_type == 'particular' ) {
					$particular_dates = TTBM_Function::get_post_info( $tour_id, 'ttbm_particular_dates', array() );
					if ( sizeof( $particular_dates ) > 0 ) {
						foreach ( $particular_dates as $date ) {
							$time      = $date['ttbm_particular_start_time'] ?: '23.59.59';
							$full_date = TTBM_Function::reduce_stop_sale_hours( $date['ttbm_particular_start_date'] . ' ' . $time );
							if ( $expire || $now <= strtotime( $full_date ) ) {
								$tour_date[] = $date['ttbm_particular_start_date'];
							}
						}
					}
				} else if ( $travel_type == 'repeated' ) {
					$now_date         = strtotime( current_time( 'Y-m-d' ) );
					$start_date    = TTBM_Function::get_post_info( $tour_id, 'ttbm_travel_repeated_start_date' );
					$start_date    = $start_date ? date( 'Y-m-d', strtotime( $start_date ) ) : '';
					$end_date      = TTBM_Function::get_post_info( $tour_id, 'ttbm_travel_repeated_end_date' );
					$end_date      = $end_date ? date( 'Y-m-d', strtotime( $end_date ) ) : '';
					$off_days      = TTBM_Function::get_post_info( $tour_id, 'mep_ticket_offdays', array() );
					$all_off_dates = TTBM_Function::get_post_info( $tour_id, 'mep_ticket_off_dates', array() );
					$off_dates     = array();
					foreach ( $all_off_dates as $off_date ) {
						$off_dates[] = $off_date['mep_ticket_off_date'];
					}
					$tour_date = array();
					if ( $start_date == $end_date ) {
						$date = $start_date;
						$day  = strtolower( date( 'D', strtotime( $date ) ) );
						if ( ! in_array( $day, $off_days ) && ! in_array( $date, $off_dates ) ) {
							$current_date = self::get_date_by_time_check( $tour_id, $date, $expire );
							if ( $current_date ) {
								$tour_date[] = $current_date;
							}
						}
					} else {
						$interval  = TTBM_Function::get_post_info( $tour_id, 'ttbm_travel_repeated_after', 1 );
						$all_dates = self::date_separate_period( $start_date, $end_date, $interval );
						foreach ( $all_dates as $date ) {
							$date = $date->format( 'Y-m-d' );
							if ( $expire || $now_date <= strtotime( $date ) ) {
								$day = strtolower( date( 'D', strtotime( $date ) ) );
								if ( ! in_array( $day, $off_days ) && ! in_array( $date, $off_dates ) ) {
									$current_date = self::get_date_by_time_check( $tour_id, $date, $expire );
									if ( $current_date ) {
										$tour_date[] = $current_date;
									}
								}
							}
						}
					}
				} else {
					$date = self::get_post_info( $tour_id, 'ttbm_travel_start_date' );
					if ( $date ) {
						$time          = self::get_post_info( $tour_id, 'ttbm_travel_start_date_time' );
						$full_date     = $time ? $date . ' ' . $time : $date . ' ' . '23.59.59';
						$tour_status   = self::get_tour_status( $tour_id );
						$end_date      = self::get_post_info( $tour_id, 'ttbm_travel_reg_end_date' );
						$end_date_time = $end_date . ' ' . '23.59.59';
						$full_date     = self::reduce_stop_sale_hours( $end_date ? $end_date_time : $full_date );
						if ( $expire || ( $now <= strtotime( $full_date ) && $tour_status == 'active' ) ) {
							$tour_date['date']          = $date;
							$tour_date['expire']        = $expire;
							$tour_date['now']           = $now;
							$tour_date['fulldate']      = $full_date;
							$tour_date['end_date']      = $end_date;
							$tour_date['checkout_date'] = TTBM_Function::get_post_info( $tour_id, 'ttbm_travel_end_date' );
						}
					}
				}
				return apply_filters( 'ttbm_get_date', $tour_date, $tour_id, $expire );
			}
			public static function get_date_by_time_check( $tour_id, $date, $expire ) {
				$tour_date = '';
				$now       = strtotime( current_time( 'Y-m-d H:i:s' ) );
				$times     = TTBM_Function::get_time( $tour_id, $date, $expire );
				if ( is_array( $times ) && sizeof( $times ) > 0 ) {
					foreach ( $times as $time ) {
						$full_date = $time['time'] ? $date . ' ' . $time['time'] : $date . ' ' . '23.59.59';
						$full_date = TTBM_Function::reduce_stop_sale_hours( $full_date );
						if ( $expire || $now <= strtotime( $full_date ) ) {
							$tour_date = $date;
						}
					}
				} else {
					$full_date = TTBM_Function::reduce_stop_sale_hours( $date . ' ' . '23.59.59' );
					if ( $expire || $now <= strtotime( $full_date ) ) {
						$tour_date = $date;
					}
				}
				return $tour_date;
			}
			public static function date_separate_period( $start_date, $end_date, $repeat ): DatePeriod {
				$repeat    = $repeat ?: 1;
				$_interval = "P" . $repeat . "D";
				$end_date  = date( 'Y-m-d', strtotime( $end_date . ' +1 day' ) );
				return new DatePeriod( new DateTime( $start_date ), new DateInterval( $_interval ), new DateTime( $end_date ) );
			}
			public static function reduce_stop_sale_hours( $date ): string {
				$stop_hours = (int) self::get_general_settings( 'ttbm_ticket_expire_time' ) * 60 * 60;
				return date( 'Y-m-d H:i:s', strtotime( $date ) - $stop_hours );
			}
			public static function get_time( $tour_id, $date = '', $expire = '' ) {
				$date = $date ? date( 'Y-m-d', strtotime( $date ) ) : '';
				if ( $date ) {
					$time = self::get_post_info( $tour_id, 'ttbm_travel_start_date_time' );
					return apply_filters( 'ttbm_get_time', $time, $tour_id, $date, $expire );
				}
				return false;
			}
			public static function get_upcoming_date( $tour_id ) {
				$all_date = self::get_date( $tour_id );
				if ( sizeof( $all_date ) > 0 ) {
					return current( $all_date );
				}
				return false;
			}
			public static function update_upcoming_date_month( $tour_id, $update = '', $all_date = array() ): void {
				$now        = strtotime( current_time( 'Y-m-d' ) );
				$db_date    = self::get_post_info( $tour_id, 'ttbm_upcoming_date' );
				$db_date    = date( 'Y-m-d', strtotime( $db_date ) );
				$month_list = self::get_post_info( $tour_id, 'ttbm_month_list' );
				if ( ! $month_list || ! $db_date || $update || strtotime( $db_date ) < $now ) {
					$date     = '';
					$all_date = sizeof( $all_date ) > 0 ? $all_date : self::get_date( $tour_id );
					if ( sizeof( $all_date ) > 0 ) {
						$date = current( $all_date );
					}
					update_post_meta( $tour_id, 'ttbm_upcoming_date', $date );
					self::update_month_list( $tour_id, $all_date );
				}
			}
			public static function update_all_upcoming_date_month(): void {
				$tour_ids = TTBM_Query::get_all_tour_id();
				foreach ( $tour_ids as $tour_id ) {
					self::update_upcoming_date_month( $tour_id );
				}
			}
			public static function update_month_list( $tour_id, $dates ): void {
				$month = '';
				if ( is_array( $dates ) ) {
					$all_months = array();
					foreach ( $dates as $date ) {
						$all_months[] = date( 'n', strtotime( $date ) );
					}
					$all_months = array_unique( $all_months );
					foreach ( $all_months as $all_month ) {
						$month = $month ? $month . ',' . $all_month : $all_month;
					}
				} else {
					$month = date( 'n', strtotime( $dates ) );
				}
				update_post_meta( $tour_id, 'ttbm_month_list', $month );
			}
			public static function get_start_time( $tour_id ) {
				$start_time = self::get_post_info( $tour_id, 'ttbm_travel_start_date_time' );
				return apply_filters( 'ttbm_tour_start_time', $start_time, $tour_id );
			}
			public static function get_reg_end_date( $tour_id ) {
				$end_date = self::get_post_info( $tour_id, 'ttbm_travel_reg_end_date' );
				return apply_filters( 'ttbm_tour_reg_end_date', $end_date, $tour_id );
			}
			public static function datetime_format( $date, $type = 'date-time-text' ) {
				$date_format = get_option( 'date_format' );
				$time_format = get_option( 'time_format' );
				$wp_settings = $date_format . '  ' . $time_format;
				$timezone    = wp_timezone_string();
				$timestamp   = strtotime( $date . ' ' . $timezone );
				if ( $type == 'date-time' ) {
					$date = wp_date( $wp_settings, $timestamp );
				} elseif ( $type == 'date-text' ) {
					$date = wp_date( $date_format, $timestamp );
				} elseif ( $type == 'date' ) {
					$date = wp_date( $date_format, $timestamp );
				} elseif ( $type == 'time' ) {
					$date = wp_date( $time_format, $timestamp, wp_timezone() );
				} elseif ( $type == 'day' ) {
					$date = wp_date( 'd', $timestamp );
				} elseif ( $type == 'month' ) {
					$date = wp_date( 'M', $timestamp );
				} elseif ( $type == 'date-time-text' ) {
					$date = wp_date( $wp_settings, $timestamp, wp_timezone() );
				} else {
					$date = wp_date( $type, $timestamp );
				}
				return $date;
			}
			public static function check_time( $tour_id, $date ): bool {
				$time_slots = self::get_time( $tour_id, $date );
				if ( $time_slots ) {
					if ( is_array( $time_slots ) ) {
						if ( sizeof( $time_slots ) > 0 ) {
							return true;
						} else {
							return false;
						}
					} else {
						return true;
					}
				}
				return false;
			}
			public static function date_format(): string {
				$format      = self::get_general_settings( 'ttbm_date_format', 'D d M , yy' );
				$date_format = 'Y-m-d';
				$date_format = $format == 'yy/mm/dd' ? 'Y/m/d' : $date_format;
				$date_format = $format == 'yy-dd-mm' ? 'Y-d-m' : $date_format;
				$date_format = $format == 'yy/dd/mm' ? 'Y/d/m' : $date_format;
				$date_format = $format == 'dd-mm-yy' ? 'd-m-Y' : $date_format;
				$date_format = $format == 'dd/mm/yy' ? 'd/m/Y' : $date_format;
				$date_format = $format == 'mm-dd-yy' ? 'm-d-Y' : $date_format;
				$date_format = $format == 'mm/dd/yy' ? 'm/d/Y' : $date_format;
				$date_format = $format == 'd M , yy' ? 'j M , Y' : $date_format;
				$date_format = $format == 'D d M , yy' ? 'D j M , Y' : $date_format;
				$date_format = $format == 'M d , yy' ? 'M  j, Y' : $date_format;
				return $format == 'D M d , yy' ? 'D M  j, Y' : $date_format;
			}
			//*************Price*********************************//
			public static function get_tour_start_price( $tour_id, $start_date = '' ): string {
				$start_price  = self::get_post_info( $tour_id, 'ttbm_travel_start_price' );
				$ticket_list  = self::get_ticket_type( $tour_id );
				$ticket_price = [];
				$start_date   = $start_date ?: TTBM_Function::get_date( $tour_id )[0];
				if ( ! $start_price && sizeof( $ticket_list ) > 0 ) {
					foreach ( $ticket_list as $ticket ) {
						$ticket_name    = $ticket['ticket_type_name'];
						$price          = $ticket['ticket_type_price'];
						$price          = array_key_exists( 'sale_price', $ticket ) && $ticket['sale_price'] ? $ticket['sale_price'] : $price;
						$price          = apply_filters( 'ttbm_filter_ticket_price', $price, $tour_id, $start_date, $ticket_name );
						$price          = apply_filters( 'ttbm_price_by_name_filter', $price, $tour_id, 1, $start_date );
						$ticket_price[] = $price;
					}
					$start_price = min( $ticket_price );
				}
				return $start_price;
			}
			public static function get_wc_tour_start_price( $tour_id ): string {
				$price = self::get_tour_start_price( $tour_id );
				return self::ttbm_wc_price( $tour_id, $price );
			}
			public static function get_hotel_room_min_price( $hotel_id ) {
				$room_lists = self::get_room_list( $hotel_id );
				$price      = array();
				foreach ( $room_lists as $room_list ) {
					$price[] = $room_list['ttbm_hotel_room_price'];
				}
				return min( $price );
			}
			public static function get_price_by_name( $ticket_name, $tour_id, $hotel_id = '', $qty = '', $start_date = '' ) {
				$ttbm_type = self::get_tour_type( $tour_id );
				$price     = '';
				if ( $ttbm_type == 'general' ) {
					$ticket_types = self::get_ticket_type( $tour_id );
					foreach ( $ticket_types as $ticket_type ) {
						if ( $ticket_type['ticket_type_name'] == $ticket_name ) {
							$price = $ticket_type['ticket_type_price'];
							$price = array_key_exists( 'sale_price', $ticket_type ) && $ticket_type['sale_price'] ? $ticket_type['sale_price'] : $price;
							$price = apply_filters( 'ttbm_filter_ticket_price', $price, $tour_id, $start_date, $ticket_name );
							$price = apply_filters( 'ttbm_price_by_name_filter', $price, $tour_id, $qty, $start_date );
						}
					}
				}
				if ( $ttbm_type == 'hotel' ) {
					$room_lists = self::get_room_list( $hotel_id );
					foreach ( $room_lists as $room_list ) {
						if ( $room_list['ttbm_hotel_room_name'] == $ticket_name ) {
							$price = $room_list['ttbm_hotel_room_price'];
						}
					}
				}
				return $price;
			}
			public static function check_discount_price_exit( $tour_id, $ticket_name = '', $hotel_id = '', $qty = '', $start_date = '' ) {
				$ttbm_type = self::get_tour_type( $tour_id );
				$price     = '';
				if ( $ttbm_type == 'general' ) {
					$ticket_types = self::get_ticket_type( $tour_id );
					foreach ( $ticket_types as $ticket_type ) {
						if ( ! $ticket_name || $ticket_type['ticket_type_name'] == $ticket_name ) {
							$regular_price = $ticket_type['ticket_type_price'];
							$sale_price    = array_key_exists( 'sale_price', $ticket_type ) && $ticket_type['sale_price'] ? $ticket_type['sale_price'] : '';
							$price         = $regular_price && $sale_price ? $regular_price : '';
							return apply_filters( 'ttbm_filter_ticket_discount_price_check', $price, $tour_id, $start_date, $ticket_name );
							//$price = apply_filters( 'ttbm_price_by_name_filter', $price, $tour_id, $qty );
						}
					}
				}
				return $price;
			}
			public static function get_extra_service_price_by_name( $tour_id, $service_name ) {
				$extra_services = self::get_post_info( $tour_id, 'ttbm_extra_service_data', array() );
				$price          = '';
				if ( sizeof( $extra_services ) > 0 ) {
					foreach ( $extra_services as $service ) {
						if ( $service['service_name'] == $service_name ) {
							return $service['service_price'];
						}
					}
				}
				return $price;
			}
			public static function price_convert_raw( $price ) {
				$price = wp_strip_all_tags( $price );
				$price = str_replace( get_woocommerce_currency_symbol(), '', $price );
				$price = str_replace( wc_get_price_thousand_separator(), '', $price );
				$price = str_replace( wc_get_price_decimal_separator(), '.', $price );
				return max( $price, 0 );
			}
			public static function ttbm_wc_price( $post_id, $price, $args = array() ): string {
				$num_of_decimal = get_option( 'woocommerce_price_num_decimals', 2 );
				$args           = wp_parse_args( $args, array(
					'qty'   => '',
					'price' => '',
				) );
				$_product       = self::get_post_info( $post_id, 'link_wc_product', $post_id );
				$product        = wc_get_product( $_product );
				$qty            = '' !== $args['qty'] ? max( 0.0, (float) $args['qty'] ) : 1;
				$tax_with_price = get_option( 'woocommerce_tax_display_shop' );
				if ( '' === $price ) {
					return '';
				} elseif ( empty( $qty ) ) {
					return 0.0;
				}
				$line_price   = (float) $price * (int) $qty;
				$return_price = $line_price;
				if ( $product->is_taxable() ) {
					if ( ! wc_prices_include_tax() ) {
						$tax_rates = WC_Tax::get_rates( $product->get_tax_class() );
						$taxes     = WC_Tax::calc_tax( $line_price, $tax_rates );
						if ( 'yes' === get_option( 'woocommerce_tax_round_at_subtotal' ) ) {
							$taxes_total = array_sum( $taxes );
						} else {
							$taxes_total = array_sum( array_map( 'wc_round_tax_total', $taxes ) );
						}
						$return_price = $tax_with_price == 'excl' ? round( $line_price, $num_of_decimal ) : round( $line_price + $taxes_total, $num_of_decimal );
					} else {
						$tax_rates      = WC_Tax::get_rates( $product->get_tax_class() );
						$base_tax_rates = WC_Tax::get_base_tax_rates( $product->get_tax_class( 'unfiltered' ) );
						/**
						 * If the customer is excempt from VAT, remove the taxes here.
						 * Either remove the base or the user taxes depending on woocommerce_adjust_non_base_location_prices setting.
						 */
						if ( ! empty( WC()->customer ) && WC()->customer->get_is_vat_exempt() ) { // @codingStandardsIgnoreLine.
							$remove_taxes = apply_filters( 'woocommerce_adjust_non_base_location_prices', true ) ? WC_Tax::calc_tax( $line_price, $base_tax_rates, true ) : WC_Tax::calc_tax( $line_price, $tax_rates, true );
							if ( 'yes' === get_option( 'woocommerce_tax_round_at_subtotal' ) ) {
								$remove_taxes_total = array_sum( $remove_taxes );
							} else {
								$remove_taxes_total = array_sum( array_map( 'wc_round_tax_total', $remove_taxes ) );
							}
							// $return_price = round( $line_price, $num_of_decimal);
							$return_price = round( $line_price - $remove_taxes_total, $num_of_decimal );
							/**
							 * The woocommerce_adjust_non_base_location_prices filter can stop base taxes being taken off when dealing with out of base locations.
							 * e.g. If a product costs 10 including tax, all users will pay 10 regardless of location and taxes.
							 * This feature is experimental @since 2.4.7 and may change in the future. Use at your risk.
							 */
						} else {
							$base_taxes   = WC_Tax::calc_tax( $line_price, $base_tax_rates, true );
							$modded_taxes = WC_Tax::calc_tax( $line_price - array_sum( $base_taxes ), $tax_rates );
							if ( 'yes' === get_option( 'woocommerce_tax_round_at_subtotal' ) ) {
								$base_taxes_total   = array_sum( $base_taxes );
								$modded_taxes_total = array_sum( $modded_taxes );
							} else {
								$base_taxes_total   = array_sum( array_map( 'wc_round_tax_total', $base_taxes ) );
								$modded_taxes_total = array_sum( array_map( 'wc_round_tax_total', $modded_taxes ) );
							}
							$return_price = $tax_with_price == 'excl' ? round( $line_price - $base_taxes_total, $num_of_decimal ) : round( $line_price - $base_taxes_total + $modded_taxes_total, $num_of_decimal );
						}
					}
				}
				$return_price   = apply_filters( 'woocommerce_get_price_including_tax', $return_price, $qty, $product );
				$display_suffix = get_option( 'woocommerce_price_display_suffix' ) ? get_option( 'woocommerce_price_display_suffix' ) : '';
				return wc_price( $return_price ) . ' ' . $display_suffix;
			}
			//***********Duration*************************//
			public static function get_duration( $tour_id ) {
				$duration = self::get_post_info( $tour_id, 'ttbm_travel_duration', 0 );
				return apply_filters( 'ttbm_tour_duration', $duration, $tour_id );
			}
			public static function get_all_duration(): array {
				$tour_ids = TTBM_Query::get_all_tour_id();
				$duration = array();
				foreach ( $tour_ids as $tour_id ) {
					$duration[] = self::get_duration( $tour_id );
				}
				$duration = array_unique( $duration );
				natsort( $duration );
				return $duration;
			}
			//************Seat***********************//
			public static function get_total_seat( $tour_id ) {
				$total_seat = 0;
				$tour_type  = self::get_tour_type( $tour_id );
				if ( $tour_type == 'general' ) {
					$ticket_list = self::get_ticket_type( $tour_id );
					if ( sizeof( $ticket_list ) > 0 ) {
						foreach ( $ticket_list as $_ticket_list ) {
							$total_seat = $_ticket_list['ticket_type_qty'] + $total_seat;
						}
					}
				}
				return apply_filters( 'ttbm_get_total_seat_filter', $total_seat, $tour_id );
			}
			public static function get_total_reserve( $tour_id ) {
				$reserve   = 0;
				$tour_type = self::get_tour_type( $tour_id );
				if ( $tour_type == 'general' ) {
					$ticket_list = self::get_ticket_type( $tour_id );
					if ( sizeof( $ticket_list ) > 0 ) {
						foreach ( $ticket_list as $_ticket_list ) {
							if ( array_key_exists( 'ticket_type_resv_qty', $_ticket_list ) && $_ticket_list['ticket_type_resv_qty'] > 0 ) {
								$reserve = $_ticket_list['ticket_type_resv_qty'] + $reserve;
							}
						}
					}
				}
				return apply_filters( 'ttbm_get_total_reserve_filter', $reserve, $tour_id );
			}
			public static function get_total_sold( $tour_id, $tour_date = '', $type = '', $hotel_id = '' ): int {
				$tour_date  = $tour_date ?: TTBM_Function::get_post_info( $tour_id, 'ttbm_upcoming_date' );
				$type       = add_filter( 'ttbm_type_filter', $type, $tour_id );
				$sold_query = TTBM_Query::query_all_sold( $tour_id, $tour_date, '', $hotel_id );
				return $sold_query->post_count;
			}
			public static function get_total_service_sold( $tour_id, $tour_date, $type = '' ): int {
				$sold_query = TTBM_Query::query_all_service_sold( $tour_id, $tour_date, $type );
				return $sold_query->post_count;
			}
			public static function get_total_available( $tour_id, $tour_date = '' ) {
				$total     = self::get_total_seat( $tour_id );
				$reserve   = self::get_total_reserve( $tour_id );
				$sold      = self::get_total_sold( $tour_id, $tour_date );
				$available = $total - ( $reserve + $sold );
				return max( 0, $available );
			}
			//*********************************//
			public static function get_ticket_type( $tour_id ) {
				$ttbm_type = self::get_tour_type( $tour_id );
				$tickets   = array();
				if ( $ttbm_type == 'general' ) {
					$tickets = self::get_post_info( $tour_id, 'ttbm_ticket_type', array() );
					$tickets = apply_filters( 'ttbm_ticket_type_filter', $tickets, $tour_id );
				}
				return $tickets;
			}
			public static function get_room_list( $hotel_id ) {
				return self::get_post_info( $hotel_id, 'ttbm_room_details', array() );
			}
			//*********************************//
			public static function tour_type() {
				$type = array(
					'general' => __( 'General Tour', 'tour-booking-manager' ),
					'hotel'   => __( 'Hotel Base Tour', 'tour-booking-manager' )
				);
				return apply_filters( 'add_ttbm_tour_type', $type );
			}
			public static function get_tour_type( $tour_id ) {
				$tour_type = self::get_post_info( $tour_id, 'ttbm_type', 'general' );
				if ( $tour_type == 'hiphop' ) {
					update_post_meta( $tour_id, 'ttbm_type', 'general' );
					$tour_type = 'general';
				}
				return $tour_type;
			}
			public static function get_travel_type( $tour_id ) {
				$type = self::get_post_info( $tour_id, 'ttbm_travel_type', 'fixed' );
				return apply_filters( 'ttbm_tour_type', $type, $tour_id );
			}
			public static function travel_type_array(): array {
				return array(
					'fixed'      => __( 'Fixed Dates', 'tour-booking-manager' ),
					'particular' => __( 'Particular Dates', 'tour-booking-manager' ),
					'repeated'   => __( 'Repeated Dates', 'tour-booking-manager' )
				);
			}
			public static function get_tour_status( $tour_id, $status = 'active' ) {
				$tour_type = self::get_tour_type( $tour_id );
				$date_type = TTBM_Function::get_travel_type( $tour_id );
				if ( $tour_type == 'general' && $date_type == 'fixed' ) {
					$now          = current_time( 'Y-m-d H:i:s' );
					$reg_end_date = self::get_reg_end_date( $tour_id );
					$end_time     = date( 'Y-m-d H:i:s', strtotime( $reg_end_date ) );
					$_status      = strtotime( $now ) < strtotime( $end_time ) ? 'active' : 'expired';
					$status       = ! empty( $reg_end_date ) ? $_status : 'active';
				}
				return $status;
			}
			//***********Location & Place*************************//
			public static function get_start_place( $tour_id ) {
				return self::get_post_info( $tour_id, 'ttbm_travel_start_place' );
			}
			public static function get_location( $tour_id ): string {
				return self::get_post_info( $tour_id, 'ttbm_location_name' );
			}
			public static function get_all_location(): array {
				$locations = self::get_taxonomy( 'ttbm_tour_location' );
				$arr       = array(
					'' => esc_html__( 'Please Select a Location', 'tour-booking-manager' )
				);
				foreach ( $locations as $_terms ) {
					$arr[ $_terms->name ] = $_terms->name;
				}
				return $arr;
			}
			public static function get_full_location( $tour_id ): string {
				$city          = self::get_location( $tour_id );
				$country       = self::get_country( $tour_id );
				$full_location = $city && $country ? $city . ' , ' . $country : '';
				$full_location = $city && ! $country ? $city : $full_location;
				return ! $city && $country ? $country : $full_location;
			}
			public static function get_country( $tour_id ) {
				$location = self::get_location( $tour_id );
				$country  = '';
				if ( $location ) {
					$term = get_term_by( 'name', $location, 'ttbm_tour_location' );
					$name = $term && $term->term_id ? get_term_meta( $term->term_id, 'ttbm_country_location' ) : array();
					if ( is_array( $name ) && sizeof( $name ) > 0 ) {
						$country = $name[0];
					}
				}
				return $country;
			}
			public static function get_all_country(): array {
				$locations = self::get_taxonomy( 'ttbm_tour_location' );
				$country   = [];
				if ( sizeof( $locations ) > 0 ) {
					foreach ( $locations as $location ) {
						$name = get_term_meta( $location->term_id, 'ttbm_country_location' );
						if ( is_array( $name ) && sizeof( $name ) > 0 ) {
							$country[] = $name[0];
						}
					}
				}
				return $country;
			}
			//*******************************//
			public static function get_contact_text( $tour_id ) {
				return self::get_post_info( $tour_id, 'ttbm_contact_text' );
			}
			public static function get_contact_phone( $tour_id ) {
				return self::get_post_info( $tour_id, 'ttbm_contact_phone' );
			}
			public static function get_contact_email( $tour_id ) {
				return self::get_post_info( $tour_id, 'ttbm_contact_email' );
			}
			//*******************************//
			public static function get_thumbnail( $post_id = '', $image_id = '', $size = 'full' ) {
				return self::get_image_url( $post_id, $image_id, $size );
			}
			public static function get_image_url( $post_id = '', $image_id = '', $size = 'full' ) {
				if ( $post_id ) {
					$image_id = self::get_post_info( $post_id, 'ttbm_list_thumbnail' );
					$image_id = $image_id ?: get_post_thumbnail_id( $post_id );
				}
				return wp_get_attachment_image_url( $image_id, $size );
			}
			//*******************************//
			public static function get_max_people_allow( $tour_id ) {
				return self::get_post_info( $tour_id, 'ttbm_travel_max_people_allow' );
			}
			public static function get_min_age_allow( $tour_id ) {
				return self::get_post_info( $tour_id, 'ttbm_travel_min_age' );
			}
			//***************************//
			public static function get_hiphop_place( $tour_id ) {
				return self::get_post_info( $tour_id, 'ttbm_hiphop_places', array() );
			}
			public static function get_day_wise_details( $tour_id ) {
				return self::get_post_info( $tour_id, 'ttbm_daywise_details', array() );
			}
			public static function get_faq( $tour_id ) {
				return self::get_post_info( $tour_id, 'mep_event_faq', array() );
			}
			public static function get_why_choose_us( $tour_id ) {
				return self::get_post_info( $tour_id, 'ttbm_why_choose_us_texts', array() );
			}
			public static function get_hotel_list( $tour_id ) {
				$type        = self::get_tour_type( $tour_id );
				$hotel_lists = array();
				if ( $type == 'hotel' ) {
					$hotel_lists = self::get_post_info( $tour_id, 'ttbm_hotels', $hotel_lists );
				}
				return $hotel_lists;
			}
			public static function get_related_tour( $tour_id ) {
				return self::get_post_info( $tour_id, 'ttbm_related_tour', array() );
			}
			//**********************//
			public static function get_feature_list( $tour_id, $name ): array {
				$services = self::get_post_info( $tour_id, $name );
				if ( is_array( $services ) && sizeof( $services ) > 0 ) {
					$terms = array();
					foreach ( $services as $service ) {
						if ( is_array( $service ) && array_key_exists( 'name', $service ) ) {
							$terms[] = $service['name'];
						} else {
							if ( is_array( $service ) ) {
								$terms[] = $service['ttbm_feature_item'];
							} else {
								$terms[] = $service;
							}
						}
					}
					$services = $terms;
				} else {
					$services = self::feature_id_to_array( $services );
				}
				return $services;
			}
			public static function feature_id_to_array( $ids ): array {
				$ids  = $ids ? explode( ',', $ids ) : array();
				$data = array();
				foreach ( $ids as $id ) {
					if ( $id ) {
						$term = get_term_by( 'id', $id, 'ttbm_tour_features_list' );
						if ( $term ) {
							$data[] = $term->name;
						}
					}
				}
				return $data;
			}
			public static function feature_array_to_string( $features ): string {
				$ids = '';
				if ( sizeof( $features ) > 0 ) {
					foreach ( $features as $feature ) {
						$term = get_term_by( 'name', $feature, 'ttbm_tour_features_list' );
						if ( $term ) {
							$ids = $ids ? $ids . ',' . $term->term_id : $term->term_id;
						}
					}
				}
				return $ids;
			}
			public static function check_exit_feature( $features, $features_name ): bool {
				if ( sizeof( $features ) > 0 ) {
					foreach ( $features as $feature ) {
						if ( $feature == $features_name ) {
							return true;
						}
					}
				}
				return false;
			}
			/********************/
			public static function get_tag_id( $tags ) {
				if ( is_array( $tags ) ) {
					$term_id = '';
					foreach ( $tags as $tag ) {
						$term_id = $term_id ? $term_id . ',' . $tag->term_id : $tag->term_id;
					}
					$tags = $term_id;
				}
				return $tags;
			}
			//*******************************//
			public static function array_to_string( $array ) {
				$ids = '';
				if ( sizeof( $array ) > 0 ) {
					foreach ( $array as $data ) {
						if ( $data ) {
							$ids = $ids ? $ids . ',' . $data : $data;
						}
					}
				}
				return $ids;
			}
			//*******************************//
			public static function get_taxonomy( $name ) {
				return get_terms( array( 'taxonomy' => $name, 'hide_empty' => false ) );
			}
			public static function get_taxonomy_name_to_id_string( $tour_id, $key, $taxonomy ) {
				$infos = self::get_post_info( $tour_id, $key, array() );
				$id    = '';
				if ( $infos && sizeof( $infos ) > 0 ) {
					foreach ( $infos as $info ) {
						$term = get_term_by( 'name', $info, $taxonomy );
						if ( $term && $term->term_id ) {
							$id = $id ? $id . ',' . $term->term_id : $term->term_id;
						}
					}
				}
				return $id;
			}
			public static function get_taxonomy_id_string( $tour_id, $taxonomy ) {
				$infos = get_the_terms( $tour_id, $taxonomy );
				$id    = '';
				if ( is_array( $infos ) && sizeof( $infos ) > 0 ) {
					foreach ( $infos as $info ) {
						$id = $id ? $id . ',' . $info->term_id : $info->term_id;
					}
				}
				return $id;
			}
			//************************//
			public static function all_tax_list(): array {
				global $wpdb;
				$table_name = $wpdb->prefix . 'wc_tax_rate_classes';
				$result     = $wpdb->get_results( "SELECT * FROM $table_name" );
				$tax_list   = [];
				foreach ( $result as $tax ) {
					$tax_list[ $tax->slug ] = $tax->name;
				}
				return $tax_list;
			}
			//************************//
			public static function get_settings( $key, $option_name, $default = '' ) {
				$options = get_option( $option_name );
				return self::get_ttbm_settings( $options, $key, $default );
			}
			public static function get_ttbm_settings( $options, $key, $default = '' ) {
				if ( isset( $options[ $key ] ) && $options[ $key ] ) {
					$default = $options[ $key ];
				}
				return $default;
			}
			public static function get_general_settings( $key, $default = '' ) {
				$options = get_option( 'ttbm_basic_gen_settings' );
				return self::get_ttbm_settings( $options, $key, $default );
			}
			public static function get_style_settings( $key, $default = '' ) {
				$options = get_option( 'ttbm_basic_style_settings' );
				return self::get_ttbm_settings( $options, $key, $default );
			}
			public static function get_translation_settings( $key, $default = '' ) {
				$options = get_option( 'ttbm_basic_translation_settings' );
				return self::get_ttbm_settings( $options, $key, $default );
			}
			public static function translation_settings( $key, $default = '' ) {
				$options = get_option( 'ttbm_basic_translation_settings' );
				echo mep_esc_html( self::get_ttbm_settings( $options, $key, $default ) );
			}
			//***************************//
			public static function get_map_api() {
				$options = get_option( 'ttbm_basic_gen_settings' );
				$default = '';
				if ( isset( $options['ttbm_gmap_api_key'] ) && $options['ttbm_gmap_api_key'] ) {
					$default = $options['ttbm_gmap_api_key'];
				}
				return $default;
			}
			public static function ticket_name_text() {
				return self::get_translation_settings( 'ttbm_string_ticket_name', esc_html__( 'Name', 'tour-booking-manager' ) );
			}
			public static function ticket_price_text() {
				return self::get_translation_settings( 'ttbm_string_ticket_price', esc_html__( 'Price', 'tour-booking-manager' ) );
			}
			public static function ticket_qty_text() {
				return self::get_translation_settings( 'ttbm_string_ticket_qty', esc_html__( 'Qty', 'tour-booking-manager' ) );
			}
			public static function service_name_text() {
				return self::get_translation_settings( 'ttbm_string_service_name', esc_html__( 'Name', 'tour-booking-manager' ) );
			}
			public static function service_price_text() {
				return self::get_translation_settings( 'ttbm_string_service_price', esc_html__( 'Price', 'tour-booking-manager' ) );
			}
			public static function service_qty_text() {
				return self::get_translation_settings( 'ttbm_string_service_qty', esc_html__( 'Qty', 'tour-booking-manager' ) );
			}
			//*****************//
			public static function get_cpt_name(): string {
				return 'ttbm_tour';
			}
			public static function get_name() {
				return self::get_general_settings( 'ttbm_travel_label', 'Tour' );
			}
			public static function get_slug() {
				return self::get_general_settings( 'ttbm_travel_slug', 'tour' );
			}
			public static function get_icon() {
				return self::get_general_settings( 'ttbm_travel_icon', 'dashicons-admin-site-alt2' );
			}
			public static function get_category_label() {
				return self::get_general_settings( 'ttbm_travel_cat_label', 'Category' );
			}
			public static function get_category_slug() {
				return self::get_general_settings( 'ttbm_travel_cat_slug', 'travel-category' );
			}
			public static function get_organizer_label() {
				return self::get_general_settings( 'ttbm_travel_org_label', 'Organizer' );
			}
			public static function get_organizer_slug() {
				return self::get_general_settings( 'ttbm_travel_org_slug', 'travel-organizer' );
			}
			//***********************//
			public static function recurring_check( $tour_id ) {
				$travel_type = self::get_travel_type( $tour_id );
				$tour_type   = self::get_tour_type( $tour_id );
				if ( $tour_type == 'general' && ( $travel_type == 'particular' || $travel_type == 'repeated' ) ) {
					return true;
				}
				return '';
			}
			public function date_picker_js( $selector, $dates ) {
				$start_date  = $dates[0];
				$start_year  = date( 'Y', strtotime( $start_date ) );
				$start_month = ( date( 'n', strtotime( $start_date ) ) - 1 );
				$start_day   = date( 'j', strtotime( $start_date ) );
				$end_date    = end( $dates );
				$end_year    = date( 'Y', strtotime( $end_date ) );
				$end_month   = ( date( 'n', strtotime( $end_date ) ) - 1 );
				$end_day     = date( 'j', strtotime( $end_date ) );
				$all_date    = [];
				foreach ( $dates as $date ) {
					$all_date[] = '"' . date( 'j-n-Y', strtotime( $date ) ) . '"';
				}
				?>
				<script>
							jQuery(document).ready(function () {
								jQuery("<?php echo esc_attr( $selector ); ?>").datepicker({
									dateFormat: ttbm_date_format,
									minDate: new Date(<?php echo $start_year; ?>, <?php echo $start_month; ?>, <?php echo $start_day; ?>),
									maxDate: new Date(<?php echo $end_year; ?>, <?php echo $end_month; ?>, <?php echo $end_day; ?>),
									autoSize: true,
									beforeShowDay: WorkingDates,
									onSelect: onCloseForDatePicker
								});

								function WorkingDates(date) {
									let availableDates = [<?php echo implode( ',', $all_date ); ?>];
									let dmy = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
									if (jQuery.inArray(dmy, availableDates) !== -1) {
										return [true, "", "Available"];
									} else {
										return [false, "", "unAvailable"];
									}
								}

								function onCloseForDatePicker(dateString, data) {
									let tour_date = data.selectedYear + '-' + (parseInt(data.selectedMonth) + 1) + '-' + data.selectedDay;
									jQuery('input[type="hidden"][name="ttbm_date"]').val(tour_date).trigger('change');
								}
							});
				</script>
				<?php
			}
		}
		new TTBM_Function();
	}