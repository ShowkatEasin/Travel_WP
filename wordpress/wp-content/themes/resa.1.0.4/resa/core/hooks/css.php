<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

class Resa_Hooks_CSS

{

	public function __construct()
	{
		add_filter('resa_dynamic_css', array($this, 'dynamic_css'), 20);

	}

	public function get_desktop_css()
	{
		$css = '';

		$main_header_spacing = resa()->options->get('resa_main_header_spacing');

		$top = resa_responsive_spacing($main_header_spacing, 'top', 'desktop');

		$bottom = resa_responsive_spacing($main_header_spacing, 'bottom', 'desktop');

		$css .= $top !== '' ? '.resa-main-header[data-device="desktop"]{padding-top:' . esc_attr($top) . ';}' : '';

		$css .= $bottom !== '' ? '.resa-main-header[data-device="desktop"]{padding-bottom:' . esc_attr($bottom) . ';}' : '';

		return $css;

	}

	public function get_tablet_css()
	{
		$css = '';

		$main_header_spacing = resa()->options->get('resa_main_header_spacing');

		$top = resa_responsive_spacing($main_header_spacing, 'top', 'tablet');

		$bottom = resa_responsive_spacing($main_header_spacing, 'bottom', 'tablet');

		$css .= $top !== '' ? '.resa-main-header[data-device="mobile"]{padding-top:' . esc_attr($top) . ';}' : '';

		$css .= $bottom !== '' ? '.resa-main-header[data-device="mobile"]{padding-bottom:' . esc_attr($bottom) . ';}' : '';

		return $css;
	}

	public function get_mobile_css()
	{
		$css = '';

		$main_header_spacing = resa()->options->get('resa_main_header_spacing');

		$top = resa_responsive_spacing($main_header_spacing, 'top', 'mobile');

		$bottom = resa_responsive_spacing($main_header_spacing, 'bottom', 'mobile');

		$css .= $top !== '' ? '.resa-main-header[data-device="mobile"]{padding-top:' . esc_attr($top) . ';}' : '';

		$css .= $bottom !== '' ? '.resa-main-header[data-device="mobile"]{padding-bottom:' . esc_attr($bottom) . ';}' : '';

		return $css;
	}

	public function get_root_css()
	{

		$primary_color = 'red';

		$secondary_color = 'green';

		$base_text_color = '#fff';

		$base_heading_color = '#000';

		$base_link_color = '#dfdfdf';

		$base_link_hover_color = '#f8f8f8';

		$button_text_color = '';

		$button_background_color = '#dddddd';

		$button_hover_text_color = '';

		$button_hover_background_color = '';

		$border_color = '#aaaaaa';

		$inputs_border_color = '#ccccc';

		$css = '';

		$css .= ':root {';


		//$css .= '--resa_primary_color: ' . esc_attr($primary_color) . ';';


		/*$css .= '--resa_secondary_color: ' . esc_attr($secondary_color['initial']) . ';';


		$css .= '--resa_base_text_color: ' . esc_attr($text_color['initial']) . ';';


		$css .= '--resa_base_heading_color: ' . esc_attr($headings_color['initial']) . ';';


		$css .= '--resa_base_link_color: ' . esc_attr($links_color['initial']) . ';';


		$css .= '--resa_base_link_hover_color: ' . esc_attr($links_color['hover']) . ';';


		$css .= '--resa_button_text_color: ' . esc_attr($button_label_color['initial']) . ';';


		$css .= '--resa_button_background_color: ' . esc_attr($button_background_color['initial']) . ';';


		$css .= '--resa_button_hover_text_color: ' . esc_attr($button_label_color['hover']) . ';';


		$css .= '--resa_button_hover_background_color: ' . esc_attr($button_background_color['hover']) . ';';


		$css .= '--resa_border_color: ' . esc_attr($global_border_color['initial']) . ';';


		$css .= '--resa_inputs_border_color: ' . esc_attr($global_input_field_border_color['initial']) . ';';*/


		$css .= '}';

		return $css;
	}

	public function get_other_dynamic_css()
	{
		$css = '';

		$copyright_background_color = resa()->options->get('resa_bottom_footer_background_color');

		$css .= '.resa-bottom-footer{--resa_bottom_footer_background_color: ' . esc_attr($copyright_background_color) . ';}';

		return $css;
	}

	public function dynamic_css($css)
	{

		$css .= $this->get_root_css();

		$desktop_css = $this->get_desktop_css();

		$tablet_css = $this->get_tablet_css();

		$mobile_css = $this->get_mobile_css();

		$tablet_max_width = '999.98px';

		$mobile_max_width = '689.98px';

		if (is_customize_preview()) {

			$tablet_max_width = '800px';

			$mobile_max_width = '370px';
		}

		$css .= $desktop_css;

		if ($tablet_css !== '') {
			$css .= '@media (max-width: ' . esc_attr($tablet_max_width) . ') {' . $tablet_css . '}';
		}

		if ($mobile_css !== '') {
			$css .= '@media (max-width: ' . esc_attr($mobile_max_width) . ') {' . $mobile_css . '}';
		}

		$css .= $this->get_other_dynamic_css();


		return $css;

	}

}

new Resa_Hooks_CSS();
