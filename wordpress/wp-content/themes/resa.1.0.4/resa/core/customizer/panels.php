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

if (!class_exists('Resa_Customizer_Panels')) :
	/**
	 * Resa Customizer sections and panels.
	 */
	class Resa_Customizer_Panels
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

		
			// Header panel.
			$options['panel']['resa_panel_header'] = array(
				'title' => esc_html__('Header', 'resa'),
				'priority' => 3,
			);

			// Footer panel.
			$options['panel']['resa_panel_footer'] = array(
				'title' => esc_html__('Footer', 'resa'),
				'priority' => 5,
			);


			return $options;
		}
	}
endif;
new Resa_Customizer_Panels();
