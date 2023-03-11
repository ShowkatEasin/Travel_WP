<?php
/**
 * Resa Options Class.
 *
 * @package  Resa
 * @author   Resa Team <hello@resawp.com>
 * @since    1.0.0
 */

/**
 * Do not allow direct script access.
 */
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Resa_Options')) :

	/**
	 * Resa Options Class.
	 */
	class Resa_Options
	{

		/**
		 * Singleton instance of the class.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private static $instance;

		/**
		 * Options variable.
		 *
		 * @since 1.0.0
		 * @var mixed $options
		 */
		private static $options;

		/**
		 * Main Resa_Options Instance.
		 *
		 * @return Resa_Options
		 * @since 1.0.0
		 */
		public static function instance()
		{

			if (!isset(self::$instance) && !(self::$instance instanceof Resa_Options)) {
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

			// Refresh options.
			add_action('after_setup_theme', array($this, 'refresh'));
		}

		/**
		 * Set default option values.
		 *
		 * @return array Default values.
		 * @since  1.0.0
		 */
		public function get_defaults()
		{

			$defaults = array(
				'resa_show_tagline' => false,
				'resa_main_header_spacing' => array(
					'desktop' => array(
						'top' => 10,
						'bottom' => 10,
					),
					'tablet' => array(
						'top' => '',
						'bottom' => '',
					),
					'mobile' => array(
						'top' => '',
						'bottom' => '',
					),
					'unit' => 'px',
				),
				'resa_show_yatra_mini_cart' => true,
				'resa_top_footer_show_widgets' => false,
				'resa_bottom_footer_copyright_text' => 'Copyright [copyright] [current_year] [site_title] | Powered by [theme_author]',
				'resa_bottom_footer_background_color' => '#eeeeee',

			);

			return apply_filters('resa_default_option_values', $defaults);
		}

		/**
		 * Get the options from static array()
		 *
		 * @return array    Return array of theme options.
		 * @since  1.0.0
		 */
		public function get_options()
		{
			return self::$options;
		}

		/**
		 * Get the options from static array()
		 *
		 * @return mixed    Return array of theme options.
		 * @since  1.0.0
		 */
		public function get($id, $default = 'NOTHING')
		{
			$default = $default === 'NOTHING' ? self::get_default($id) : $default;
			$value = isset(self::$options[$id]) ? self::$options[$id] : $default;
			// phpcs:ignore
			return apply_filters("theme_mod_{$id}", $value);
		}

		/**
		 * Set option.
		 *
		 * @since  1.0.0
		 */
		public function set($id, $value)
		{
			set_theme_mod($id, $value);
			self::$options[$id] = $value;
		}

		/**
		 * Delete option.
		 *
		 * @since  1.0.0
		 */
		public function delete($id)
		{
			remove_theme_mod($id);
			if (isset(self::$options[$id])) {
				unset(self::$options[$id]);
			}
		}

		/**
		 * Refresh options.
		 *
		 * @return void
		 * @since  1.0.0
		 */
		public function refresh()
		{
			self::$options = wp_parse_args(
				get_theme_mods(),
				self::get_defaults()
			);
		}

		/**
		 * Returns the default value for option.
		 *
		 * @param string $id Option ID.
		 * @return mixed      Default option value.
		 * @since  1.0.0
		 */
		public function get_default($id)
		{
			$defaults = self::get_defaults();
			return isset($defaults[$id]) ? $defaults[$id] : false;
		}
	}

endif;
