<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die;
	} // Cannot access pages directly.
	if ( ! class_exists( 'TTBM_Dependencies' ) ) {
		class TTBM_Dependencies {
			public function __construct() {
				add_action( 'init', array( $this, 'language_load' ) );
				$this->load_file();
				add_action( 'wp_enqueue_scripts', array( $this, 'ttbm_frontend_script' ), 90 );
				add_action( 'admin_enqueue_scripts', array( $this, 'ttbm_admin_script' ), 90 );
				add_action( 'admin_head', array( $this, 'ttbm_js_constant' ), 5 );
				add_action( 'wp_head', array( $this, 'ttbm_js_constant' ), 5 );
				add_action( 'wp_head', array( $this, 'apply_custom_css' ), 90 );
				add_action( 'admin_head', array( $this, 'apply_custom_css' ), 90 );
				add_action( 'ttbm_registration_enqueue', array( $this, 'registration_enqueue' ), 90 );
			}
			public function language_load() {
				$plugin_dir = basename( dirname( __DIR__ ) ) . "/languages/";
				load_plugin_textdomain( 'tour-booking-manager', false, $plugin_dir );
			}
			private function load_file() {
				require_once TTBM_PLUGIN_DIR . '/inc/TTBM_Function.php';
				require_once TTBM_PLUGIN_DIR . '/inc/TTBM_Layout.php';
				require_once TTBM_PLUGIN_DIR . '/support/elementor/elementor-support.php';
				require_once TTBM_PLUGIN_DIR . '/admin/TTBM_Admin.php';
				require_once TTBM_PLUGIN_DIR . '/inc/TTBM_Frontend.php';
				require_once TTBM_PLUGIN_DIR . '/inc/TTBM_Query.php';
				require_once TTBM_PLUGIN_DIR . '/inc/TTBM_Shortcodes.php';
				require_once TTBM_PLUGIN_DIR . '/inc/TTBM_Filter_Pagination.php';
				require_once TTBM_PLUGIN_DIR . '/inc/TTBM_Tour_List.php';
				require_once TTBM_PLUGIN_DIR . '/inc/TTBM_Style.php';
				require_once TTBM_PLUGIN_DIR . '/inc/TTBM_Details_Layout.php';
				require_once TTBM_PLUGIN_DIR . '/inc/TTBM_Booking.php';
				require_once TTBM_PLUGIN_DIR . '/inc/TTBM_Woocommerce.php';
				require_once TTBM_PLUGIN_DIR . '/super_slider/MP_Super_Slider.php';
			}
			public function ttbm_common_script() {
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-datepicker' );
				wp_localize_script( 'jquery', 'ttbm_ajax', array( 'ttbm_ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
				wp_enqueue_style( 'mp_font_awesome', '//cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css', array(), '5.15.4' );
				wp_enqueue_style( 'mp_jquery_ui', TTBM_PLUGIN_URL . '/assets/helper/css/jquery-ui.css' );
				wp_enqueue_style( 'mp_plugin_global', TTBM_PLUGIN_URL . '/assets/helper/mp_style/mp_style.css', array(), time() );
				wp_enqueue_script( 'mp_plugin_global', TTBM_PLUGIN_URL . '/assets/helper/mp_style/mp_script.js', array( 'jquery' ), time(), true );
				do_action( 'ttbm_common_script' );
			}
			public function ttbm_frontend_script() {
				$this->ttbm_common_script();
				wp_enqueue_script( 'jquery-ui-accordion' );
				wp_enqueue_style( 'ttbm_owl_style', TTBM_PLUGIN_URL . '/assets/owl/owl.carousel.min.css' );
				wp_enqueue_style( 'ttbm_owl_style_default', TTBM_PLUGIN_URL . '/assets/owl/owl.theme.default.min.css' );
				wp_enqueue_script( 'ttbm_owl_script', TTBM_PLUGIN_URL . '/assets/owl/owl.carousel.min.js', array( 'jquery' ), '', true );
				wp_enqueue_style( 'ttbm_filter_pagination_style', TTBM_PLUGIN_URL . '/assets/frontend/filter_pagination.css', array(), time() );
				wp_enqueue_script( 'ttbm_filter_pagination_script', TTBM_PLUGIN_URL . '/assets/frontend/filter_pagination.js', array( 'jquery' ), time(), true );
				wp_enqueue_style( 'ttbm_style', TTBM_PLUGIN_URL . '/assets/frontend/ttbm_style.css', array(), time() );
				wp_enqueue_script( 'ttbm_script', TTBM_PLUGIN_URL . '/assets/frontend/ttbm_script.js', array( 'jquery' ), time(), true );
				do_action( 'ttbm_registration_enqueue' );
				do_action( 'ttbm_frontend_script' );
			}
			public function ttbm_admin_script() {
				$this->ttbm_common_script();
				wp_enqueue_editor();
				//admin script
				wp_enqueue_script( 'jquery-ui-sortable' );
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'wp-color-picker' );
				wp_enqueue_script( 'magepeople-options-framework', TTBM_PLUGIN_URL . '/assets/helper/js/mage-options-framework.js', array( 'jquery' ), null );
				wp_localize_script( 'PickpluginsOptionsFramework', 'PickpluginsOptionsFramework_ajax', array( 'PickpluginsOptionsFramework_ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
				wp_enqueue_script( 'select2.min', TTBM_PLUGIN_URL . '/assets/helper/js/select2.min.js', array( 'jquery' ) );
				wp_enqueue_script( 'codemirror', TTBM_PLUGIN_URL . '/assets/helper/js/codemirror.min.js', array( 'jquery' ), null );
				wp_enqueue_script( 'form-field-dependency', TTBM_PLUGIN_URL . '/assets/helper/js/form-field-dependency.js', array( 'jquery' ), null );
				wp_enqueue_style( 'mage-options-framework', TTBM_PLUGIN_URL . '/assets/helper/css/mage-options-framework.css' );
				wp_enqueue_style( 'select2.min', TTBM_PLUGIN_URL . '/assets/helper/css/select2.min.css' );
				wp_enqueue_style( 'codemirror', TTBM_PLUGIN_URL . '/assets/helper/css/codemirror.css' );
				// custom
				wp_enqueue_script( 'mp_admin_settings', TTBM_PLUGIN_URL . '/assets/admin/mp_admin_settings.js', array( 'jquery' ), time(), true );
				wp_enqueue_style( 'mp_admin_settings', TTBM_PLUGIN_URL . '/assets/admin/mp_admin_settings.css', array(), time() );
				wp_enqueue_script( 'ttbm_admin_script', TTBM_PLUGIN_URL . '/assets/admin/ttbm_admin_script.js', array( 'jquery' ), time(), true );
				wp_enqueue_style( 'ttbm_admin_style', TTBM_PLUGIN_URL . '/assets/admin/ttbm_admin_style.css', array(), time() );
				do_action( 'ttbm_admin_script' );
			}
			public function registration_enqueue() {
				wp_enqueue_style( 'ttbm_date_range_picker', TTBM_PLUGIN_URL . '/assets/date_range_picker/date_range_picker.min.css', array(), '1' );
				wp_enqueue_script( 'ttbm_date_range_picker_js', TTBM_PLUGIN_URL . '/assets/date_range_picker/date_range_picker.js', array( 'jquery', 'moment' ), '1', true );
				wp_enqueue_style( 'ttbm_registration_style', TTBM_PLUGIN_URL . '/assets/frontend/ttbm_registration.css', array(), time() );
				wp_enqueue_script( 'ttbm_registration_script', TTBM_PLUGIN_URL . '/assets/frontend/ttbm_registration.js', array( 'jquery' ), time(), true );
				wp_enqueue_script( 'ttbm_price_calculation', TTBM_PLUGIN_URL . '/assets/frontend/ttbm_price_calculation.js', array( 'jquery' ), time(), true );
				do_action( 'add_ttbm_registration_enqueue' );
			}
			public function ttbm_js_constant() {
				?>
				<script type="text/javascript">
							let ttbm_ajax_url = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
							let ttbm_currency_symbol = "<?php echo get_woocommerce_currency_symbol(); ?>";
							let ttbm_currency_position = "<?php echo get_option( 'woocommerce_currency_pos' ); ?>";
							let ttbm_currency_decimal = "<?php echo wc_get_price_decimal_separator(); ?>";
							let ttbm_currency_thousands_separator = "<?php echo wc_get_price_thousand_separator(); ?>";
							let ttbm_num_of_decimal = "<?php echo get_option( 'woocommerce_price_num_decimals', 2 ); ?>";
							let ttbm_empty_image_url = "<?php echo esc_attr( TTBM_PLUGIN_URL . '/assets/helper/images/no_image.png' ); ?>";
							let ttbm_date_format = "<?php echo esc_attr( TTBM_Function::get_general_settings( 'ttbm_date_format', 'D d M , yy' ) ); ?>";
				</script>
				<?php
			}
			public function apply_custom_css() {
				$options    = get_option( 'ttbm_custom_css' );
				$custom_css = TTBM_Function::get_ttbm_settings( $options, 'custom_css' );
				ob_start();
				?>
				<style>
					<?php echo $custom_css; ?>
				</style>
				<?php
				echo ob_get_clean();
			}
		}
		new TTBM_Dependencies();
	}
