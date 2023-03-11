<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die;
	}
	$tour_id     = $tour_id ?? get_the_id();
	$travel_type = $travel_type ?? TTBM_Function::get_travel_type( $tour_id );
	$tour_type   = $tour_type ?? TTBM_Function::get_tour_type( $tour_id );
	$all_dates   = $all_dates ?? TTBM_Function::get_date( $tour_id );
	//echo '<pre>';print_r($all_dates);echo '</pre>';
	if ( sizeof( $all_dates ) > 0 && $tour_type == 'hotel' ) {
		$ttbm_hotels = TTBM_Function::get_hotel_list( $tour_id );
		if ( sizeof( $ttbm_hotels ) > 0 ) {
			//include( TTBM_Function::template_path( 'ticket/date_selection.php' ) );
			?>
			<div class="ttbm_hotel_area <?php echo esc_attr($travel_type == 'fixed' ?'':'dNone'); ?>">
				<?php foreach ( $ttbm_hotels as $hotel_id ) { ?>
					<div class="ttbm_registration_area">
						<div class="ttbm_hotel_item fdColumn">
							<input type="hidden" name="ttbm_id" value="<?php echo esc_attr( $tour_id ); ?>"/>
							<input type="hidden" name="ttbm_hotel_id" value="<?php echo esc_attr( $hotel_id ); ?>"/>
							<div class="ttbm_hotel_details_item">
								<div class="bg_image_area">
									<div data-bg-image="<?php echo esc_attr( TTBM_Function::get_image_url( $hotel_id ) ); ?>"></div>
								</div>
								<div class="ttbm_hotel_list_details">
									<div class="hotel_list_top_area justifyBetween">
										<div class="hotel_list_top_left fdColumn">
											<h4 class="flexWrap alignCenter">
												<?php echo get_the_title( $hotel_id ); ?>
												<div class="dFlex hotel_rating">
													<?php
														$ttbm_hotel_rating = TTBM_Function::get_post_info( $hotel_id, 'ttbm_hotel_rating' );
														if ( $ttbm_hotel_rating > 0 ) {
															for ( $i = 0; $i < $ttbm_hotel_rating; $i ++ ) {
																?>
																<span class="fas fa-star"></span>
																<?php
															}
														}
													?>
												</div>
											</h4>
											<ul class="flexWrap">
												<?php
													$hotel_location = TTBM_Function::get_post_info( $hotel_id, 'ttbm_hotel_location' );
													if ( $hotel_location ) {
														?>
														<li><a href="#"><?php echo esc_html( $hotel_location ); ?></a></li>
													<?php } ?>
												<?php
													$hotel_distance_text = TTBM_Function::get_post_info( $hotel_id, 'ttbm_hotel_distance_des' );
													if ( $hotel_distance_text ) {
														?>
														<li><?php echo esc_html( $hotel_distance_text ); ?></li>
													<?php } ?>
											</ul>
										</div>
										<div class="hotel_list_top_right dFlex">
											<div class="hotel_list_top_right_left fdColumn textRight">
												<h6><?php esc_html_e( 'Hotel score', 'tour-booking-manager' ); ?></h6>
											</div>
											<?php
												$ttbm_hotel_rating = TTBM_Function::get_post_info( $hotel_id, 'ttbm_hotel_rating' );
												if ( $ttbm_hotel_rating > 0 ) { ?>
													<div class="hotel_list_top_right_right">
														<?php echo esc_html( $ttbm_hotel_rating ); ?>
													</div>
												<?php } ?>
										</div>
									</div>
									<div class="hotel_list_middle_area justifyBetween">
										<div class="hotel_list_middle_left">
											<?php
												$ttbm_hotel_des = get_post_field( 'post_content', $tour_id );
												//$ttbm_hotel_des = TTBM_Function::get_post_info( $hotel_id, 'ttbm_hotel_short_des' );
												if ( $ttbm_hotel_des ) {
													?>
													<div class="mp_wp_editor">
														<?php echo mep_esc_html( $ttbm_hotel_des ); ?>
													</div>
												<?php } ?>
										</div>
										<div class="hotel_list_middle_right fdColumn textRight">
											<h4><?php echo wc_price( TTBM_Function::get_hotel_room_min_price( $hotel_id ) ); ?></h4>
											<button class="fRight mt_xs  ttbm_hotel_open_room_list" type="button">
												<?php esc_html_e( 'See availability', 'tour-booking-manager' ); ?>
												<span class="fas fa-angle-right ml"></span>
											</button>
										</div>
									</div>
								</div>
							</div>
							<div class="ttbm_booking_panel placeholder_area">
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
			<?php
		}
	}
?>