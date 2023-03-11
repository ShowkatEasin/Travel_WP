<?php
/**
 * Resa Header
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


if (!class_exists('Resa_Customizer_Header')) :
	/**
	 * Resa Main Header section in Customizer.
	 */
	class Resa_Customizer_Header extends Resa_Customizer_Setting_Base
	{
		public function register($options)
		{

			$options['setting']['resa_show_tagline'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'resa_sanitize_toggle',
				'control' => array(
					'type' => 'resa-toggle',
					'section' => 'title_tagline',
					'label' => esc_html__('Show Tagline', 'resa'),
					'priority' => 69,
				),
			);

			$options['setting']['resa_main_header_spacing'] = array(
				//'transport' => 'postMessage',
				'sanitize_callback' => 'resa_sanitize_responsive',
				'control' => array(
					'type' => 'resa-spacing',
					'label' => esc_html__('Header Padding', 'resa'),
					'description' => esc_html__('Specify spacing around logo. Negative values are allowed.', 'resa'),
					'section' => 'resa_section_main_header',
					'settings' => 'resa_main_header_spacing',
					'priority' => 40,
					'choices' => array(
						'top' => esc_html__('Top', 'resa'),
						'bottom' => esc_html__('Bottom', 'resa'),

					),
					'responsive' => true,
					'unit' => array(
						'px',
						'%',
						'em',
						'rem',
					),
				),
			);

			if (function_exists('yatra_mini_cart')) {
				$options['setting']['resa_show_yatra_mini_cart'] = array(
					'transport' => 'refresh',
					'sanitize_callback' => 'resa_sanitize_toggle',
					'control' => array(
						'type' => 'resa-toggle',
						'section' => 'resa_section_main_header',
						'label' => esc_html__('Show Yatra mini cart', 'resa'),
						'priority' => 69,
					),
				);
			}
			return $options;
		}


	}
endif;
new Resa_Customizer_Header();
