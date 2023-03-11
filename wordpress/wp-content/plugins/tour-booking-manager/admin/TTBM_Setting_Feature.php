<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die;
	} // Cannot access pages directly.
	if ( ! class_exists( 'TTBM_Setting_Feature' ) ) {
		class TTBM_Setting_Feature {
			public function __construct() {
				add_action('add_ttbm_settings_tab_name',[$this,'add_tab'],10);
				add_action('add_ttbm_settings_tab_content',[$this,'ttbm_settings_feature'],10,1);
				add_action('add_ttbm_settings_feature_content',[$this,'ttbm_settings_feature'],10,1);
				//********Add New Feature************//
				add_action( 'wp_ajax_load_ttbm_feature_form', [ $this, 'load_ttbm_feature_form' ] );
				add_action( 'wp_ajax_nopriv_load_ttbm_feature_form', [ $this, 'load_ttbm_feature_form' ] );
				add_action( 'wp_ajax_ttbm_reload_feature_list', [ $this, 'ttbm_reload_feature_list' ] );
				add_action( 'wp_ajax_nopriv_ttbm_reload_feature_list', [ $this, 'ttbm_reload_feature_list' ] );
			}
			public function add_tab(){
				?>
				<li data-tabs-target="#ttbm_settings_feature">
					<span class="fas fa-clipboard-list"></span><?php esc_html_e( ' Features', 'tour-booking-manager' ); ?>
				</li>
				<?php
			}
			public function ttbm_settings_feature( $tour_id ) {
				?>
				<div class="tabsItem ttbm_settings_feature" data-tabs="#ttbm_settings_feature">
					<div class="mtb ttbm_features_table">
						<?php $this->feature( $tour_id ); ?>
					</div>
					<?php TTBM_Layout::popup_button( 'add_new_feature_popup', esc_html__( 'Create New Feature', 'tour-booking-manager' ) ); ?>
					<?php TTBM_Settings::des_p( 'add_new_feature_popup' ); ?>
					<?php $this->add_new_feature_popup(); ?>
				</div>
				<?php
			}
			public function feature( $tour_id ) {
				$features        = TTBM_Function::get_taxonomy( 'ttbm_tour_features_list' );
				$include_display = TTBM_Function::get_post_info( $tour_id, 'ttbm_display_include_service', 'on' );
				$include_active  = $include_display == 'off' ? '' : 'mActive';
				$exclude_display = TTBM_Function::get_post_info( $tour_id, 'ttbm_display_exclude_service', 'on' );
				$exclude_active  = $exclude_display == 'off' ? '' : 'mActive';
				$in_checked      = $include_display == 'off' ? '' : 'checked';
				$ex_checked      = $exclude_display == 'off' ? '' : 'checked';
				if ( sizeof( $features ) > 0 ) { ?>
					<table>
						<thead>
						<tr>
							<td>
								<h4 class="dFlex">
									<?php TTBM_Layout::switch_button( 'ttbm_display_include_service', $in_checked ); ?>
									<?php esc_html_e( 'Price Included Feature', 'tour-booking-manager' ); ?>
								</h4>
								<?php TTBM_Settings::des_p( 'ttbm_display_include_service' ); ?>
							</td>
							<td>
								<h4 class="dFlex">
									<?php TTBM_Layout::switch_button( 'ttbm_display_exclude_service', $ex_checked ); ?>
									<?php esc_html_e( 'Price Excluded Feature', 'tour-booking-manager' ); ?>
								</h4>
								<?php TTBM_Settings::des_p( 'ttbm_display_exclude_service' ); ?>
							</td>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td>
								<div data-collapse="#ttbm_display_include_service" class="<?php echo esc_attr( $include_active ); ?>">
									<?php $this->feature_list( $tour_id, 'ttbm_service_included_in_price' ); ?>
								</div>
							</td>
							<td>
								<div data-collapse="#ttbm_display_exclude_service" class="<?php echo esc_attr( $exclude_active ); ?>">
									<?php $this->feature_list( $tour_id, 'ttbm_service_excluded_in_price' ); ?>
								</div>
							</td>
						</tr>
						</tbody>
					</table>
					<?php
				}
			}
			public function feature_list( $tour_id, $feature_name ) {
				$all_features = TTBM_Function::get_taxonomy( 'ttbm_tour_features_list' );
				$features     = TTBM_Function::get_feature_list( $tour_id, $feature_name );
				$feature_ids  = TTBM_Function::feature_array_to_string( $features );
				if ( sizeof( $all_features ) > 0 ) {
					?>
					<div class="groupCheckBox">
						<label class="dNone">
							<input type="text" name="<?php echo esc_attr( $feature_name ); ?>" value="<?php echo esc_attr( $feature_ids ); ?>"/>
						</label>
						<?php foreach ( $all_features as $feature_list ) { ?>
							<?php $icon = get_term_meta( $feature_list->term_id, 'ttbm_feature_icon', true ) ? get_term_meta( $feature_list->term_id, 'ttbm_feature_icon', true ) : 'fas fa-forward'; ?>
							<label class="customCheckboxLabel">
								<input type="checkbox" <?php echo TTBM_Function::check_exit_feature( $features, $feature_list->name ) ? 'checked' : ''; ?> data-checked="<?php echo esc_attr( $feature_list->term_id ); ?>"/>
								<span class="customCheckbox"><span class="mR_xs <?php echo esc_attr( $icon ); ?>"></span><?php esc_html_e( $feature_list->name ); ?></span>
							</label>
						<?php } ?>
					</div>
					<?php
				}
			}
			public function add_new_feature_popup() {
				?>
				<div class="mpPopup" data-popup="add_new_feature_popup">
					<div class="popupMainArea">
						<div class="popupHeader">
							<h4>
								<?php esc_html_e( 'Add New Feature', 'tour-booking-manager' ); ?>
								<p class="_textSuccess_ml_dNone ttbm_success_info"><span class="fas fa-check-circle mR_xs"></span><?php esc_html_e( 'Feature is added successfully.', 'tour-booking-manager' ) ?></p>
							</h4>
							<span class="fas fa-times popupClose"></span>
						</div>
						<div class="popupBody ttbm_feature_form_area">
						</div>
						<div class="popupFooter">
							<div class="buttonGroup">
								<button class="_themeButton ttbm_new_feature_save" type="button"><?php esc_html_e( 'Save', 'tour-booking-manager' ); ?></button>
								<button class="_warningButton ttbm_new_feature_save_close" type="button"><?php esc_html_e( 'Save & Close', 'tour-booking-manager' ); ?></button>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
			public function load_ttbm_feature_form() {
				?>
				<label class="flexEqual">
					<span><?php esc_html_e( 'Feature Name : ', 'tour-booking-manager' ); ?><sup class="textRequired">*</sup></span>
					<input type="text" name="ttbm_feature_name" class="formControl" required>
				</label>
				<p class="textRequired" data-required="ttbm_feature_name">
					<span class="fas fa-info-circle"></span>
					<?php esc_html_e( 'Feature name is required!', 'tour-booking-manager' ); ?>
				</p>
				<?php TTBM_Settings::des_p( 'ttbm_feature_name' ); ?>
				<div class="divider"></div>
				<label class="flexEqual">
					<span><?php esc_html_e( 'Feature Description : ', 'tour-booking-manager' ); ?></span>
					<textarea name="ttbm_feature_description" class="formControl" rows="3"></textarea>
				</label>
				<?php TTBM_Settings::des_p( 'ttbm_feature_description' ); ?>
				<div class="divider"></div>
				<div class="flexEqual">
					<span><?php esc_html_e( 'Feature Icon : ', 'tour-booking-manager' ); ?><sup class="textRequired">*</sup></span>
					<?php do_action( 'mp_input_add_icon', 'ttbm_feature_icon' ); ?>
				</div>
				<p class="textRequired" data-required="ttbm_feature_icon">
					<span class="fas fa-info-circle"></span>
					<?php esc_html_e( 'Feature icon is required!', 'tour-booking-manager' ); ?>
				</p>
				<?php
				die();
			}
			public function ttbm_reload_feature_list() {
				$ttbm_id = TTBM_Function::data_sanitize( $_POST['ttbm_id'] );
				$this->feature( $ttbm_id );
				die();
			}
		}
		new TTBM_Setting_Feature();
	}