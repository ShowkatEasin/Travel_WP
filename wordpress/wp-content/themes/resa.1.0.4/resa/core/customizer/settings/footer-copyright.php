<?php
/**
 * Resa Footer Copyright
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

if (!class_exists('Resa_Customizer_Footer_Copyright')) :
	/**
	 * Resa Main Header section in Customizer.
	 */
	class Resa_Customizer_Footer_Copyright extends Resa_Customizer_Setting_Base
	{

		public function register($options)
		{

 			// Header Background.
			$options['setting']['resa_bottom_footer_copyright_text'] = array(
				'sanitize_callback' => 'sanitize_text_field',
				'control' => array(
					'type' => 'textarea',
					'label' => esc_html__('Copyright text', 'resa'),
					'section' => 'resa_section_footer_copyright',
				),
			);

			$options['setting']['resa_bottom_footer_background_color'] = array(
				'sanitize_callback' => 'resa_sanitize_color',
				'control' => array(
					'type' => 'resa-color',
					'label' => esc_html__('Background color', 'resa'),
					'section' => 'resa_section_footer_copyright',
					'opacity' => true,
				),
			);
			return $options;
		}
	}
endif;
new Resa_Customizer_Footer_Copyright();
