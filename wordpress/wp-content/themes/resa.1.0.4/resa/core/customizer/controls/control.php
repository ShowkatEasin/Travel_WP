<?php
/**
 * Resa Customizer custom control class. To be extended in other controls.
 *
 * @package     Resa
 * @author      Resa Team <hello@resawp.com>
 * @since       1.0.0
 */

/**
 * Do not allow direct script access.
 */
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Resa_Customizer_Control')) :
	/**
	 * Resa Customizer custom control class. To be extended in other controls.
	 */
	class Resa_Customizer_Control extends WP_Customize_Control
	{

		/**
		 * Whitelisting the "required" argument.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		public $required = array();

		/**
		 * Whitelisting the "responsive" argument.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		public $responsive = array();

		/**
		 * Set the default options.
		 *
		 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
		 * @param string $id Control ID.
		 * @param array $args Default parent's arguments.
		 * @since 1.0.0
		 */
		public function __construct($manager, $id, $args = array())
		{

			parent::__construct($manager, $id, $args);

			$all_breakpoints = array(
				'desktop' => array(
					'title' => esc_html__('Desktop', 'resa'),
					'icon' => 'dashicons dashicons-desktop',
				),
				'tablet' => array(
					'title' => esc_html__('Tablet', 'resa'),
					'icon' => 'dashicons dashicons-tablet',
				),
				'mobile' => array(
					'title' => esc_html__('Mobile', 'resa'),
					'icon' => 'dashicons dashicons-smartphone',
				),
			);

			if (isset($args['responsive'])) {

				$breakpoints = isset($args['breakpoints']) ? $args['breakpoints'] : [];

				$responsive_breakpoints = [];

				foreach ($breakpoints as $breakpoint) {

					if (isset($all_breakpoints[$breakpoint])) {

						$responsive_breakpoints[$breakpoint] = $all_breakpoints[$breakpoint];
					}
				}
				if (count($responsive_breakpoints) < 1) {

					$responsive_breakpoints = $all_breakpoints;
				}
				$this->responsive = $responsive_breakpoints;
			}
		}

		/**
		 * Enqueue control related scripts/styles.
		 *
		 * @access public
		 */
		public function enqueue()
		{

			// Script debug.
			$resa_suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '';

			// Control type.
			$resa_type = str_replace('resa-', '', $this->type);

			/**
			 * Enqueue control stylesheet
			 */
			wp_enqueue_style(
				'resa-' . $resa_type . '-control-style',
				RESA_THEME_URI . 'core/customizer/controls/' . $resa_type . '/style' . $resa_suffix . '.css',
				false,
				RESA_THEME_VERSION,
				'all'
			);

			/**
			 * Enqueue our control script.
			 */
			wp_enqueue_script(
				'resa-' . $resa_type . '-js',
				RESA_THEME_URI . 'core/customizer/controls/' . $resa_type . '/script' . $resa_suffix . '.js',
				array('jquery', 'customize-base'),
				RESA_THEME_VERSION,
				true
			);
		}

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @see WP_Customize_Control::to_json()
		 * @since  1.0.0
		 */
		public function to_json()
		{

			$this->json['settings'] = array();
			foreach ($this->settings as $key => $setting) {
				$this->json['settings'][$key] = $setting->id;
			}

			$this->json['type'] = $this->type;
			$this->json['priority'] = $this->priority;
			$this->json['active'] = $this->active();
			$this->json['section'] = $this->section;
			$this->json['label'] = $this->label;
			$this->json['description'] = $this->description;
			$this->json['instanceNumber'] = $this->instance_number;

			if ('dropdown-pages' === $this->type) {
				$this->json['allow_addition'] = $this->allow_addition;
			}

			if (isset($this->default)) {
				$this->json['default'] = $this->default;
			} elseif (isset($this->setting->default)) {
				$this->json['default'] = $this->setting->default;
			}

			$this->json['value'] = $this->value();
			$this->json['link'] = $this->get_link();
			$this->json['id'] = $this->id;
			$this->json['required'] = $this->required;
			$this->json['responsive'] = $this->responsive;
			$this->json['inputAttrs'] = '';

			foreach ($this->input_attrs as $attr => $value) {
				$this->json['inputAttrs'] .= $attr . '="' . esc_attr($value) . '" ';
			}
		}

		/**
		 * An Underscore (JS) template for this control's responsive devices buttons.
		 *
		 * @since 1.0.0
		 */
		protected function responsive_devices()
		{
			?>
			<ul class="resa-responsive-switchers">

				<# _.each( data.responsive, function( settings, device ) { #>

				<li class="resa-{{ device }}" data-device="{{ device }}" title="{{ settings.title }}">

				</li>

				<# }); #>
			</ul>
			<?php
		}

		protected function description()
		{
			?>
			<# if ( data.description ) { #>
			<div class="resa-info-icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke-width="2"
					 stroke-linecap="round" stroke-linejoin="round" class="feather feather-help-circle">
					<circle cx="12" cy="12" r="10"></circle>
					<path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
					<line x1="12" y1="17" x2="12" y2="17"></line>
				</svg>
				<span class="resa-tooltip">{{{ data.description }}}</span>
			</div>
			<# } #>
			<?php

		}

		protected function refresh()
		{
			?>
			<button class="dashicons dashicons-image-rotate reset-defaults" type="button">
			</button>
			<?php
		}

		/**
		 * Print the JavaScript templates used throughout custom controls.
		 *
		 * Templates are imported into the JS use wp.template.
		 *
		 * @since 1.0.0
		 */
		public static function template_units()
		{
			?>
			<script type="text/template" id="tmpl-resa-control-unit">

				<# if ( _.isObject( data.unit ) ) { #>

				<div class="resa-control-unit">

					<# _.each( data.unit, function( unit ){ #>
					<input
						type="radio"
						id="{{ data.id }}-{{ unit.id }}-unit"
					<# if ( false !== data.option ) { #>data-option="{{ data.option }}-unit"<# } #>
					name="{{ data.id }}-unit"
					data-min="{{ unit.min }}"
					data-max="{{ unit.max }}"
					data-step="{{ unit.step }}"
					value="{{ unit.id }}"
					<# if ( unit.id === data.selected ) { #> checked="checked"<# } #> />

					<label for="{{ data.id }}-{{ unit.id }}-unit">{{{ unit.name }}}</label>

					<# }); #>
				</div>
				<# } #>
			</script>
			<?php
		}


	}
endif;
