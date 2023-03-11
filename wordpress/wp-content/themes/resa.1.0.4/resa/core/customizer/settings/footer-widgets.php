<?php
/**
 * Resa Footer Widgets
 *
 * @package     Resa
 * @author      MantraBrain Team <mantrabrain@gmail.com>
 * @since       1.0.0
 */

/**
 * Do not allow direct script access.
 */
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Resa_Customizer_Footer_Widgets')) :
	/**
	 * Resa Main Header section in Customizer.
	 */
	class Resa_Customizer_Footer_Widgets extends Resa_Customizer_Setting_Base
	{

		public function register($options)
		{

			$options['setting']['resa_top_footer_show_widgets'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'resa_sanitize_toggle',
				'control' => array(
					'type' => 'resa-toggle',
					'section' => 'resa_section_footer_widgets',
					'label' => esc_html__('Show Widgets', 'resa'),
					'priority' => 69,
				),
			);
			return $options;
		}
	}
endif;
new Resa_Customizer_Footer_Widgets();
