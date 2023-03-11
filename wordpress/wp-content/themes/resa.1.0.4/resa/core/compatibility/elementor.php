<?php
/**
 * Elementor Compatibility File.
 *
 * @package Resa
 */

namespace Elementor;

// If plugin - 'Elementor' not exist then return.
if (!class_exists('\Elementor\Plugin')) {
	return;
}

/**
 * Resa Elementor Compatibility
 */
if (!class_exists('Resa_Elementor')) :

	/**
	 * Resa Elementor Compatibility
	 *
	 * @since 1.0.0
	 */
	class Resa_Elementor
	{

		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance()
		{
			if (!isset(self::$instance)) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct()
		{
			add_action('wp', array($this, 'elementor_default_setting'), 20);
			add_action('elementor/preview/init', array($this, 'elementor_default_setting'));
			add_action('elementor/preview/enqueue_styles', array($this, 'elementor_overlay_zindex'));
		}

		/**
		 * Elementor Content layout set as Page Builder
		 *
		 * @return void
		 * @since  1.0.0
		 */
		function elementor_default_setting()
		{

			if ('post' == get_post_type()) {
				return;
			}


			// don't modify post meta settings if we are not on Elementor's edit page.
			if (!$this->is_elementor_editor()) {
				return;
			}

			global $post;

			$id = get_the_ID();

			$page_builder_flag = '';//get_post_meta($id, 'yatri_pb_usage_flag', true);

		}


		function elementor_overlay_zindex()
		{

			// return if we are not on Elementor's edit page.
			if (!$this->is_elementor_editor()) {
				return;
			}

			?>
			<style type="text/css" id="resa-elementor-overlay-css">
				.elementor-editor-active .elementor-element > .elementor-element-overlay {
					z-index: 9999;
				}
			</style>

			<?php
		}


		function is_elementor_activated($id)
		{
			return Plugin::$instance->documents->get($id)->is_built_with_elementor($id);

		}

		/**
		 * Check if Elementor Editor is open.
		 *
		 * @return boolean True IF Elementor Editor is loaded, False If Elementor Editor is not loaded.
		 * @since  1.2.7
		 *
		 */
		private function is_elementor_editor()
		{
			if ((isset($_REQUEST['action']) && 'elementor' == $_REQUEST['action']) ||
				isset($_REQUEST['elementor-preview'])
			) {
				return true;
			}

			return false;
		}

	}

endif;

/**
 * Kicking this off by calling 'get_instance()' method
 */
Resa_Elementor::get_instance();
