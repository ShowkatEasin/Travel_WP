<?php
/**
 * Resa Customizer sections and panels.
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

if (!class_exists('Resa_Customizer_Sections')) :
	/**
	 * Resa Customizer sections and panels.
	 */
	class Resa_Customizer_Sections
	{

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct()
		{

			/**
			 * Registers our custom panels in Customizer.
			 */
			add_filter('resa_customizer_options', array($this, 'register_panel'));
		}

		/**
		 * Registers our custom options in Customizer.
		 *
		 * @param array $options Array of customizer options.
		 * @since 1.0.0
		 */
		public function register_panel($options)
		{

			// General panel.
			$options['section']['resa_section_main_header'] = array(
				'title' => esc_html__('Main Header', 'resa'),
				'panel' => 'resa_panel_header',
				'priority' => 20,
			);

			$options['section']['resa_section_footer_widgets'] = array(
				'title' => esc_html__('Footer Widgets', 'resa'),
				'panel' => 'resa_panel_footer',
				'priority' => 40,
			);
			$options['section']['resa_section_footer_copyright'] = array(
				'title' => esc_html__('Footer Copyright', 'resa'),
				'panel' => 'resa_panel_footer',
				'priority' => 60,
			);

			return $options;
		}
	}
endif;
new Resa_Customizer_Sections();
