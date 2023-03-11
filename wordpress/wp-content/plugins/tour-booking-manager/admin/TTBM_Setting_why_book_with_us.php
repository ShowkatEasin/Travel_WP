<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die;
	} // Cannot access pages directly.
	if ( ! class_exists( 'TTBM_Setting_why_book_with_us' ) ) {
		class TTBM_Setting_why_book_with_us {
			public function __construct() {
				add_action('add_ttbm_settings_tab_name',[$this,'add_tab'],10);
				add_action('add_ttbm_settings_tab_content',[$this,'why_chose_us_settings'],10,1);
			}
			public function add_tab(){
				?>
				<li data-tabs-target="#ttbm_settings_why_chose_us">
					<span class="fas fa-info-circle"></span><?php esc_html_e( ' Why Book With Us ?', 'tour-booking-manager' ); ?>
				</li>
				<?php
			}
			public function why_chose_us_settings( $tour_id ) {
				$ttbm_label = TTBM_Function::get_name();
				$display    = TTBM_Function::get_post_info( $tour_id, 'ttbm_display_why_choose_us', 'on' );
				$active     = $display == 'off' ? '' : 'mActive';
				$checked=$display == 'off' ? '' : 'checked';
				?>
				<div class="tabsItem mp_settings_area ttbm_settings_why_chose_us" data-tabs="#ttbm_settings_why_chose_us">
					<h5 class="dFlex">
						<span class="mR"><?php esc_html_e( 'Why Chose Us' . $ttbm_label . ' Settings', 'tour-booking-manager' ); ?></span>
						<?php TTBM_Layout::switch_button( 'ttbm_display_why_choose_us', $checked ); ?>
					</h5>
					<?php TTBM_Settings::des_p( 'ttbm_display_why_choose_us' ); ?>
					<div class="divider"></div>
					<div data-collapse="#ttbm_display_why_choose_us" class="<?php echo esc_attr( $active ); ?>">
						<?php $this->why_chose_us( $tour_id ); ?>
					</div>
				</div>
				<?php
			}
			public function why_chose_us( $tour_id ) {
				$why_chooses = TTBM_Function::get_why_choose_us( $tour_id );
				?>
				<table class="layoutFixed">
					<tbody>
					<tr>
						<th>
							<?php esc_html_e( 'Why Book With Us?', 'tour-booking-manager' ); ?>
							<?php TTBM_Settings::des_p( 'why_chose_us' ); ?>
						</th>
						<td colspan="3">
							<table>
								<thead>
								<tr>
									<th><?php esc_html_e( 'Item List.', 'tour-booking-manager' ); ?></th>
									<th><?php esc_html_e( 'Action', 'tour-booking-manager' ); ?></th>
								</tr>
								</thead>
								<tbody class="mp_sortable_area mp_item_insert">
								<?php
									if ( sizeof( $why_chooses ) ) {
										foreach ( $why_chooses as $why_choose ) {
											$this->why_chose_us_item( $why_choose );
										}
									} else {
										$this->why_chose_us_item();
									}
								?>
								</tbody>
							</table>
							<?php TTBM_Layout::add_new_button( esc_html__( 'Add New Item', 'tour-booking-manager' ) ); ?>
						</td>
					</tr>
					</tbody>
				</table>
				<div class="mp_hidden_content">
					<table>
						<tbody class="mp_hidden_item">
						<?php $this->why_chose_us_item(); ?>
						</tbody>
					</table>
				</div>
				<?php
			}
			public function why_chose_us_item( $why_choose = '' ) {
				?>
				<tr class="mp_remove_area">
					<td>
						<label>
							<input class="formControl mp_name_validation" name="ttbm_why_choose_us_texts[]" value="<?php echo esc_attr( $why_choose ); ?>"/>
						</label>
					</td>
					<td><?php TTBM_Layout::move_remove_button(); ?></td>
				</tr>
				<?php
			}
		}
		new TTBM_Setting_why_book_with_us();
	}