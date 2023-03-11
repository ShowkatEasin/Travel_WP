<?php
/**
 * Resa Customizer custom color control class.
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

if (!class_exists('Resa_Customizer_Control_Color')) :
	/**
	 * Resa Customizer custom color control class.
	 */
	class Resa_Customizer_Control_Color extends Resa_Customizer_Control
	{

		/**
		 * The control type.
		 *
		 * @var string
		 */
		public $type = 'resa-color';

		/**
		 * Add support for showing the opacity value on the slider handle.
		 *
		 * @var boolean
		 */
		public $opacity;

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

			// Enqueue WordPress color picker styles.
			wp_enqueue_style('wp-color-picker');

			// Enqueue control stylesheet.
			wp_enqueue_style(
				'resa-' . $resa_type . '-control-style',
				RESA_THEME_URI . 'core/customizer/controls/' . $resa_type . '/style' . $resa_suffix . '.css',
				false,
				RESA_THEME_VERSION,
				'all'
			);

			// Enqueue our control script.
			wp_enqueue_script(
				'resa-' . $resa_type . '-js',
				RESA_THEME_URI . 'core/customizer/controls/' . $resa_type . '/script' . $resa_suffix . '.js',
				array('jquery', 'customize-base', 'wp-color-picker'),
				RESA_THEME_VERSION,
				true
			);
		}

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @see WP_Customize_Control::to_json()
		 */
		public function to_json()
		{
			parent::to_json();

			$this->json['opacity'] = (false === $this->opacity || 'false' === $this->opacity) ? 'false' : 'true';
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
			<div class="resa-color-wrapper resa-control-wrapper">

				<# if ( data.label ) { #>
				<div class="customize-control-title">
					<span>{{{ data.label }}}</span>

					<# if ( data.description ) { #>
					<i class="resa-info-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
							 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
							 class="feather feather-help-circle">
							<circle cx="12" cy="12" r="10"></circle>
							<path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
							<line x1="12" y1="17" x2="12" y2="17"></line>
						</svg>
						<span class="resa-tooltip">{{{ data.description }}}</span>
					</i>
					<# } #>

				</div>
				<# } #>

				<div>
					<input class="resa-color-control" type="text" value="{{ data.value }}"
						   data-show-opacity="{{ data.opacity }}" data-default-color="{{ data.default }}"/>
				</div>

			</div><!-- END .resa-toggle-wrapper -->
			<?php
		}

	}
endif;
