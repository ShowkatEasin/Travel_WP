<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die;
	}
	$tour_id        = $tour_id ?? get_the_id();
	$tour_date      = $tour_date ?? current( TTBM_Function::get_date( $tour_id ) );
	$ticket_lists   = TTBM_Function::get_post_info( $tour_id, 'ttbm_ticket_type', array() );
	$available_seat = TTBM_Function::get_total_available( $tour_id, $tour_date );
	if ( $available_seat > 0 && sizeof( $ticket_lists ) > 0 ) {
		do_action('ttbm_before_ticket_type_area',$tour_id,$tour_date);
		?>
		<div class="ttbm_ticket_area">
			<div class="ttbm_default_widget">
				<?php
					$option_name   = 'ttbm_string_availabe_ticket_list';
					$default_title = esc_html__( 'Available Ticket List ', 'tour-booking-manager' );
					include( TTBM_Function::template_path( 'layout/title_section.php' ) );
				?>
				<div class="ttbm_widget_content" data-placeholder>
					<table class="mp_tour_ticket_type">
						<thead>
						<tr>
							<th class="textL"><?php echo esc_html( TTBM_Function::ticket_name_text() ); ?></th>
							<th><?php echo esc_html( TTBM_Function::ticket_price_text() ); ?></th>
							<th><?php echo esc_html( TTBM_Function::ticket_qty_text() ); ?></th>
						</tr>
						</thead>
						<tbody>
						<?php foreach ( $ticket_lists as $ticket ) { ?>
							<?php
							$ticket_name      = array_key_exists( 'ticket_type_name', $ticket ) ? $ticket['ticket_type_name'] : '';
							$price            = TTBM_Function::get_price_by_name( $ticket_name, $tour_id, '', '', $tour_date );
							$regular_price    = TTBM_Function::check_discount_price_exit( $tour_id, $ticket_name, '', '', $tour_date );
							$ticket_price     = TTBM_Function::ttbm_wc_price( $tour_id, $price );
							$ticket_price_raw = TTBM_Function::price_convert_raw( $ticket_price );
							$ticket_qty       = array_key_exists( 'ticket_type_qty', $ticket ) && $ticket['ticket_type_qty'] > 0 ? $ticket['ticket_type_qty'] : 0;
							$reserve          = array_key_exists( 'ticket_type_resv_qty', $ticket ) && $ticket['ticket_type_resv_qty'] > 0 ? $ticket['ticket_type_resv_qty'] : 0;
							$ticket_qty_type  = array_key_exists( 'ticket_type_qty_type', $ticket ) ? $ticket['ticket_type_qty_type'] : 'inputbox';
							$default_qty      = array_key_exists( 'ticket_type_default_qty', $ticket ) && $ticket['ticket_type_default_qty'] > 0 ? $ticket['ticket_type_default_qty'] : 0;
							$min_qty          = apply_filters( 'ttbm_ticket_type_min_qty', 0 );
							$max_qty          = apply_filters( 'ttbm_ticket_type_max_qty', 0 );
							$sold_type        = TTBM_Function::get_total_sold( $tour_id, $tour_date, $ticket_name );
							$available        = (int) $ticket_qty - ( $sold_type + (int) $reserve );
							$ticket_type_icon = array_key_exists( 'ticket_type_icon', $ticket ) ? $ticket['ticket_type_icon'] : '';
							$description      = array_key_exists( 'ticket_type_description', $ticket ) ? $ticket['ticket_type_description'] : '';
							?>
							<tr>
								<th>
									<?php if ( $ticket_type_icon ) { ?>
										<span class="<?php echo esc_attr( $ticket_type_icon ); ?>"></span>
									<?php } ?>
									<?php echo mep_esc_html( $ticket_name ); ?>
									<?php
										if ( $description ) {
											$word_count = str_word_count( $description );
											if ( $word_count > 16 ) {
												$message      = implode( " ", array_slice( explode( " ", $description ), 0, 16 ) );
												$more_message = implode( " ", array_slice( explode( " ", $description ), 16, $word_count ) );
												$name_text    = preg_replace( "/[{}()<>+ ]/", '_', $ticket_name ) . '_' . $tour_id;
												?>
												<p style="margin-top: 5px;">
													<small>
														<?php echo esc_html( $message ); ?>
														<span data-collapse='#<?php echo esc_attr( $name_text ); ?>'><?php echo esc_html( $more_message ); ?></span>
														<span class="load_more_text" data-collapse-target="#<?php echo esc_attr( $name_text ); ?>">
															<?php esc_html_e( 'view more ', 'tour-booking-manager' ); ?>
														</span>
													</small>
												</p>
												<?php
											} else {
												?>
												<p style="margin-top: 5px;"><small><?php echo esc_html( $description ); ?></small></p>
												<?php
											}
										}
									?>
								</th>
								<td class="text-center">
									<?php if ( $regular_price ) { ?>
										<span class="strikeLine"><?php echo TTBM_Function::ttbm_wc_price( $tour_id, $regular_price ); ?></span>
									<?php } ?>
									<span><?php echo mep_esc_html( $ticket_price ); ?></span>
								</td>
								<td><?php TTBM_Layout::qty_input( $ticket_name, $available, $ticket_qty_type, $default_qty, $min_qty, $max_qty, $ticket_price_raw, 'ticket_qty[]' ); ?></td>
							</tr>
							<tr>
								<td colspan=3>
									<input type="hidden" name='tour_id[]' value='<?php echo esc_html( $tour_id ); ?>'>
									<input type="hidden" name='ticket_name[]' value='<?php echo esc_html( $ticket_name ); ?>'>
									<input type="hidden" name='ticket_max_qty[]' value='<?php echo esc_html( $max_qty ); ?>'>
								</td>
							</tr>
							<?php do_action( 'ttbm_after_ticket_type_item', $tour_id, $ticket ); ?>
						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<?php include( TTBM_Function::template_path( 'ticket/extra_service.php' ) ); ?>
		</div>
		<?php
		do_action( 'ttbm_load_seat_plan', $tour_id, $tour_date );
		do_action( 'ttbm_book_now_before', $tour_id );
		include( TTBM_Function::template_path( 'ticket/book_now.php' ) );
	} else {
		?>
		<div class="dLayout allCenter bgWarning">
			<h3 class="textWhite"><?php esc_html_e( 'No Ticket Available ! ', 'tour-booking-manager' ); ?></h3>
		</div>
		<?php
	}
?>