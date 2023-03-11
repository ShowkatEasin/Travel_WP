<?php
/**
 * Resa Customizer custom spacing control class.
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

if (!class_exists('Resa_Customizer_Control_Spacing')) :
	/**
	 * Resa Customizer custom select control class.
	 */
	class Resa_Customizer_Control_Spacing extends Resa_Customizer_Control
	{

		/**
		 * The control type.
		 *
		 * @var string
		 */
		public $type = 'resa-spacing';

		/**
		 * The unit.
		 *
		 * @var string
		 */
		public $unit = array();

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @see WP_Customize_Control::to_json()
		 */
		public function to_json()
		{
			parent::to_json();

			$this->json['title'] = esc_html__('Link values', 'resa');
			$this->json['choices'] = $this->choices;
			$this->json['unit'] = $this->unit;
		}

		/**
		 * An Underscore (JS) template for this control's content (but not its container).
		 *
		 * Class variables for this control class are available in the `data` JS object;
		 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
		 *
		 * @see WP_Customize_Control::print_template()
		 */
		protected function content_template()
		{
			?>
			<div
				class="resa-control-wrapper resa-spacing-wrapper<# if ( data.responsive ) { #> resa-control-responsive <# } #>">

				<# if ( data.label ) { #>
				<div class="customize-control-title">
					<?php $this->refresh(); ?>
					<span>{{{ data.label }}}</span>
					<?php $this->description(); ?>
					<# if ( ! _.isEmpty( data.responsive ) ) { #>
					<?php $this->responsive_devices(); ?>
					<# } #>
				</div>
				<# } #>


				<div class="resa-control-wrap">

					<# if ( ! _.isEmpty( data.responsive ) ) { #>

					<# _.each( data.responsive, function( settings, device ){ #>
					<div class="{{ device }} control-responsive" data-device="{{ device }}">
						<?php $this->spacing_field(); ?>
						<# if ( _.size( data.choices ) > 1 ) { #>
						<?php $this->spacing_link() ?>
						<# } #>
						<?php $this->units() ?>

					</div>
					<# }); #>
					<# } else { #>
					<div class="control-responsive active">
						<?php $this->spacing_field(); ?>
						<# if ( _.size( data.choices ) > 1 ) { #>
						<?php $this->spacing_link() ?>
						<# } #>
						<?php $this->units() ?>
						<# } #>
					</div>

				</div><!-- END .resa-control-wrap -->

			</div><!-- END .resa-control-wrapper -->
			<?php
		}

		public function spacing_link()
		{
			?>
			<span class="spacing-control-wrap">
							<div class="spacing-link-values">
								<span class="dashicons dashicons-admin-links resa-spacing-linked"
									  data-element="{{ data.id }}" title="{{ data.title }}"></span>
								<span class="dashicons dashicons-editor-unlink resa-spacing-unlinked"
									  data-element="{{ data.id }}" title="{{ data.title }}"></span>
							</div>

			</span>
			<?php
		}

		public function spacing_field()
		{
			?>
			<#
			_.each( data.choices, function( title, id ){
			var spacing_field_name = "spacing-control-" + id;
			var spacing_field_default_value = data.default[ id ];
			var spacing_field_data_value = data.value[id];

			if ( ! _.isEmpty( data.responsive ) ) {

			spacing_field_name = "spacing-control-" + device + "-" + id;
			spacing_field_default_value = data.default[ device ][ id ];
			spacing_field_data_value = data.value[device][id];
			}

			#>
			<span class="spacing-control-wrap spacing-input">
							<input {{{ data.inputAttrs }}} name="{{{spacing_field_name}}}" type="number"
								   data-spacing-choice="{{{ id }}}" value="{{{ spacing_field_data_value }}}"
								   data-default="{{{ spacing_field_default_value }}}"/>
							<span class="resa-spacing-label">{{{ title }}}</span>
			</span>
			<# }); #>
			<?php
		}

		public function units()
		{

			?>

			<#
			var spacing_field_unit_name = data.id + "-unit"
			var spacing_field_unit_default_value = data.default[ "unit" ];
			var spacing_field_unit_value = data.value["unit"];

			if ( ! _.isEmpty( data.responsive ) ) {

			spacing_field_unit_name = data.id + "-" + device + "-unit";
			spacing_field_unit_default_value = data.default[ device ][ "unit" ]!==undefined ? data.default[ device ][ "unit" ]: data.default["unit" ];
			spacing_field_unit_value = data.value[device]["unit"];
			}

			spacing_field_unit_value  =  spacing_field_unit_value=='' || undefined==spacing_field_unit_value? spacing_field_unit_default_value: spacing_field_unit_value;
			if ( ! _.isEmpty( data.unit ) ) { #>
			<span class="spacing-control-wrap unit-control-wrap">
			<select class="resa-spacing-unit resa-control-unit" name="{{{spacing_field_unit_name}}}" data-default="{{spacing_field_unit_default_value}}">
				<# _.each( data.unit, function( unit ){ #>
				<option class="spacing-unit-{{ spacing_field_unit_name }}-{{ unit }}"
						id="spacing-unit-{{ spacing_field_unit_name }}-{{ unit }}"
						value="{{ unit }}"
				<# if ( unit === spacing_field_unit_value ) { #> selected="selected"<# } #>
				>{{{ unit }}}</option>

				<# }); #>
			</select>
			</span>
			<# } #>
			<?php
		}
	}
endif;
