<?php
/**
 * Resa Customizer class
 *
 * @package     Resa
 * @since       1.0.0
 */

/**
 * Do not allow direct script access.
 */
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Resa_Customizer')) :
	/**
	 * Resa Customizer class
	 */
	class Resa_Customizer
	{

		/**
		 * Singleton instance of the class.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private static $instance;

		/**
		 * Customizer options.
		 *
		 * @since 1.0.0
		 * @var Array
		 */
		private static $options;

		/**
		 * Main Resa_Customizer Instance.
		 *
		 * @return Resa_Customizer
		 * @since 1.0.0
		 */
		public static function instance()
		{

			if (!isset(self::$instance) && !(self::$instance instanceof Resa_Customizer)) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct()
		{

			// Loads our Customizer custom controls.
			add_action('customize_register', array($this, 'controls'));

			// Loads our Customizer helper functions.
			add_action('customize_register', array($this, 'functions'));

			// Tweak inbuilt sections.
			add_action('customize_register', array($this, 'modify_defaults'), 11);

			// Registers our Customizer options.
			add_action('after_setup_theme', array($this, 'register_options'));

			// Registers our Customizer options.
			add_action('customize_register', array($this, 'register_options_new'));

			// Loads our Customizer controls assets.
			add_action('customize_controls_enqueue_scripts', array($this, 'load_assets'), 10);

			// Enqueues our Customizer preview assets.
			add_action('customize_preview_init', array($this, 'load_preview_assets'));

			// Add available top bar widgets panel.
			add_action('customize_controls_print_footer_scripts', array('Resa_Customizer_Control', 'template_units'));
		}

		/**
		 * Loads our Customizer custom controls.
		 *
		 * @param WP_Customize_Manager $customizer Instance of WP_Customize_Manager class.
		 * @since 1.0.0
		 */
		public function controls($customizer)
		{


			// Directory where each custom control is located.
			$path = RESA_THEME_DIR . 'core/customizer/controls';

			// Require base control class.
			require $path . '/control.php'; // phpcs:ignore

			$controls = $this->get_custom_controls();

			// Load custom controls classes.
			foreach ($controls as $control => $class) {
				$control_path = $path . '/' . $control . '/customize.php';

				if (file_exists($control_path)) {
					require_once $control_path; // phpcs:ignore
					$customizer->register_control_type($class);
				}
			}
		}

		/**
		 * Loads Customizer helper functions and sanitization callbacks.
		 *
		 * @since 1.0.0
		 */
		public function functions()
		{
			require_once RESA_THEME_DIR . 'core/customizer/helpers/sanitize-callback.php'; // phpcs:ignore
			require_once RESA_THEME_DIR . 'core/customizer/helpers/active-callback.php'; // phpcs:ignore

		}


		/**
		 * Move inbuilt panels into our sections.
		 *
		 * @param WP_Customize_Manager $customizer Instance of WP_Customize_Manager class.
		 * @since 1.0.0
		 */
		public static function modify_defaults($customizer)
		{

			// Site Identity to Logo.
			$customizer->get_section('title_tagline')->priority = 2;

			// Custom logo.
			$customizer->get_control('site_icon')->description = '';
			$customizer->get_control('custom_logo')->description = '';
			$customizer->get_control('custom_logo')->priority = 10;
			$customizer->get_setting('custom_logo')->transport = 'postMessage';

			// Add selective refresh partial for Custom Logo.
			$customizer->selective_refresh->add_partial(
				'custom_logo',
				array(
					'selector' => '.resa-logo',
					'render_callback' => 'resa_logo',
					'container_inclusive' => false,
					'fallback_refresh' => true,
				)
			);

			// Site title.
			$customizer->get_setting('blogname')->transport = 'postMessage';
			$customizer->get_control('blogname')->description = '';
			$customizer->get_control('blogname')->priority = 60;

			// Site description.
			$customizer->get_setting('blogdescription')->transport = 'postMessage';
			$customizer->get_control('blogdescription')->description = '';
			$customizer->get_control('blogdescription')->priority = 70;
			$customizer->get_control('blogdescription')->active_callback = 'resa_is_tagline_active';

			// Site icon.
			$customizer->get_control('site_icon')->priority = 90;

			// Site Background.
			$background_fields = array(
				'background_color',
				'background_image',
				'background_preset',
				'background_position',
				'background_size',
				'background_repeat',
				'background_attachment',
				'background_image',
			);

			foreach ($background_fields as $field) {
				$customizer->get_control($field)->section = 'resa_section_colors';
				$customizer->get_control($field)->priority = 50;
			}

			// Load the custom section class.
			/*require RESA_THEME_PATH . 'core/customizer/class-resa-customizer-info-section.php'; // phpcs:ignore

			// Register custom section types.
			$customizer->register_section_type('Resa_Customizer_Info_Section');*/
		}

		/**
		 * Registers our Customizer options.
		 *
		 * @since 1.0.0
		 */
		public function register_options()
		{

			require_once RESA_THEME_DIR . 'core/customizer/panels.php';
			require_once RESA_THEME_DIR . 'core/customizer/sections.php';
			require_once RESA_THEME_DIR . 'core/customizer/settings/base.php';

			$path = RESA_THEME_DIR . 'core/customizer/settings/';
			/**
			 * Customizer sections.
			 */
			$sections = array(
				'header',
				'footer-widgets',
				'footer-copyright',
			);

			foreach ($sections as $section) {
				if (file_exists($path . $section . '.php')) {
					require_once $path . $section . '.php'; // phpcs:ignore
				}
			}
		}

		/**
		 * Registers our Customizer options.
		 *
		 * @param WP_Customize_Manager $customizer instance of WP_Customize_Manager.
		 *
		 * @return void
		 * @since 1.0.0
		 *
		 */
		public function register_options_new($customizer)
		{

			$options = $this->get_customizer_options();

			if (isset($options['panel']) && !empty($options['panel'])) {
				foreach ($options['panel'] as $id => $args) {
					$this->add_panel($id, $args, $customizer);
				}
			}

			if (isset($options['section']) && !empty($options['section'])) {
				foreach ($options['section'] as $id => $args) {
					$this->add_section($id, $args, $customizer);
				}
			}

			if (isset($options['setting']) && !empty($options['setting'])) {
				foreach ($options['setting'] as $id => $args) {
					$this->add_setting($id, $args, $customizer);
					$this->add_control($id, $args['control'], $customizer);
				}
			}
		}

		/**
		 * Filter and return Customizer options.
		 *
		 * @return Array Customizer options for registering Sections/Panels/Controls.
		 * @since 1.0.0
		 *
		 */
		public function get_customizer_options()
		{
			if (!is_null(self::$options)) {
				return self::$options;
			}

			return apply_filters('resa_customizer_options', array());
		}

		/**
		 * Register Customizer Panel.
		 *
		 * @param Array $panel Panel settings.
		 * @param WP_Customize_Manager $customizer instance of WP_Customize_Manager.
		 *
		 * @return void
		 * @since 1.0.0
		 *
		 */
		private function add_panel($id, $args, $customizer)
		{
			$class = resa_get_prop($args, 'class', 'WP_Customize_Panel');

			$customizer->add_panel(new $class($customizer, $id, $args));
		}

		/**
		 * Register Customizer Section.
		 *
		 * @param Array $section Section settings.
		 * @param WP_Customize_Manager $customizer instance of WP_Customize_Manager.
		 *
		 * @return void
		 * @since 1.0.0
		 *
		 */
		private function add_section($id, $args, $customizer)
		{
			$class = resa_get_prop($args, 'class', 'WP_Customize_Section');

			$customizer->add_section(new $class($customizer, $id, $args));
		}

		/**
		 * Register Customizer Control.
		 *
		 * @param Array $control Control settings.
		 * @param WP_Customize_Manager $customizer instance of WP_Customize_Manager.
		 *
		 * @return void
		 * @since 1.0.0
		 *
		 */
		private function add_control($id, $args, $customizer)
		{
			$class = $this->get_control_class(resa_get_prop($args, 'type'));

			$args['setting'] = $id;

			if (false !== $class) {
				$customizer->add_control(new $class($customizer, $id, $args));
			} else {
				$customizer->add_control($id, $args);
			}
		}

		/**
		 * Register Customizer Setting.
		 *
		 * @param Array $setting Settings.
		 * @param WP_Customize_Manager $customizer instance of WP_Customize_Manager.
		 *
		 * @return void
		 * @since 1.0.0
		 *
		 */
		private function add_setting($id, $setting, $customizer)
		{
			$setting = wp_parse_args($setting, $this->get_customizer_defaults('setting'));

			$customizer->add_setting(
				$id,
				array(
					'default' => resa()->options->get_default($id),
					'type' => resa_get_prop($setting, 'type'),
					'transport' => resa_get_prop($setting, 'transport'),
					'sanitize_callback' => resa_get_prop($setting, 'sanitize_callback', 'resa_no_sanitize'),
				)
			);

			$partial = resa_get_prop($setting, 'partial', false);

			if ($partial && isset($customizer->selective_refresh)) {

				$customizer->selective_refresh->add_partial(
					$id,
					array(
						'selector' => resa_get_prop($partial, 'selector'),
						'container_inclusive' => resa_get_prop($partial, 'container_inclusive'),
						'render_callback' => resa_get_prop($partial, 'render_callback'),
						'fallback_refresh' => resa_get_prop($partial, 'fallback_refresh'),
					)
				);
			}
		}

		/**
		 * Return custom controls.
		 *
		 * @return Array custom control slugs & classnames.
		 * @since 1.0.0
		 *
		 */
		private function get_custom_controls()
		{
			return apply_filters(
				'resa_custom_customizer_controls',
				array(
					'color' => 'Resa_Customizer_Control_Color',
					'toggle' => 'Resa_Customizer_Control_Toggle',
					'spacing' => 'Resa_Customizer_Control_Spacing',
				)
			);
		}

		/**
		 * Return default values for customizer parts.
		 *
		 * @return Array default values for the Customizer Configurations.
		 * @since 1.0.0
		 *
		 */
		private function get_customizer_defaults($type)
		{

			$defaults = array();

			switch ($type) {
				case 'setting':
					$defaults = array(
						'type' => 'theme_mod',
						'transport' => 'refresh',
					);
					break;

				case 'control':
					$defaults = array();
					break;

				default:
					break;
			}

			return apply_filters(
				'resa_customizer_configuration_defaults',
				$defaults,
				$type
			);
		}

		/**
		 * Get custom control classname.
		 *
		 * @param string $control Control ID.
		 *
		 * @return string Control classname.
		 * @since 1.0.0
		 *
		 */
		private function get_control_class($type)
		{

			if (false !== strpos($type, 'resa-')) {

				$controls = $this->get_custom_controls();
				$type = trim(str_replace('resa-', '', $type));

				if (isset($controls[$type])) {
					return $controls[$type];
				}
			}

			return false;
		}

		/**
		 * Loads our own Customizer assets.
		 *
		 * @since 1.0.0
		 */
		public function load_assets()
		{

			// Script debug.
			$resa_dir = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '';

			$resa_suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '';

			/**
			 * Enqueue our Customizer styles.
			 */
			wp_enqueue_style(
				'resa-customizer-styles',
				RESA_THEME_URI . 'core/customizer/assets/css/resa-customizer' . $resa_suffix . '.css',
				false,
				RESA_THEME_VERSION
			);

			/**
			 * Enqueue our Customizer controls script.
			 */
			wp_enqueue_script(
				'resa-customizer-js',
				RESA_THEME_URI . 'core/customizer/assets/js/' . $resa_dir . 'customize-controls' . $resa_suffix . '.js',
				array('wp-color-picker', 'jquery', 'customize-base'),
				RESA_THEME_VERSION,
				true
			);

			/**
			 * Enqueue Customizer controls dependency script.
			 */
			wp_enqueue_script(
				'resa-control-dependency-js',
				RESA_THEME_URI . 'core/customizer/assets/js/' . $resa_dir . 'customize-dependency' . $resa_suffix . '.js',
				array('jquery'),
				RESA_THEME_VERSION,
				true
			);


			/**
			 * Allow customizer localized vars to be filtered.
			 */
			$resa_customizer_localized = apply_filters('resa_customizer_localized', []);

			wp_localize_script(
				'resa-customizer-js',
				'resa_customizer_localized',
				$resa_customizer_localized
			);
		}

		/**
		 * Loads customizer preview assets
		 *
		 * @since 1.0.0
		 */
		public function load_preview_assets()
		{

			// Script debug.
			$resa_dir = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '';
			$resa_suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '';
			$version = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? time() : RESA_THEME_VERSION;

			wp_enqueue_script(
				'resa-customizer-preview-js',
				RESA_THEME_URI . 'core/customizer/assets/js/' . $resa_dir . 'customize-preview' . $resa_suffix . '.js',
				array('customize-preview', 'customize-selective-refresh', 'jquery'),
				$version,
				true
			);

			// Enqueue Customizer preview styles.
			wp_enqueue_style(
				'resa-customizer-preview-styles',
				RESA_THEME_URI . 'core/customizer/assets/css/resa-customizer-preview' . $resa_suffix . '.css',
				false,
				RESA_THEME_VERSION
			);


			/**
			 * Allow customizer localized vars to be filtered.
			 */
			$resa_customizer_localized = apply_filters('resa_customize_preview_localized', []);

			wp_localize_script(
				'resa-customizer-preview-js',
				'resa_customizer_preview',
				$resa_customizer_localized
			);
		}
	}
endif;
new Resa_Customizer();
