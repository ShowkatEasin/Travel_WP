<?php
/**
 * Do not allow direct script access.
 */
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Resa_Customizer_Setting_Base')) :
	/**
	 * Resa Main Header section in Customizer.
	 */
	abstract class Resa_Customizer_Setting_Base
	{

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct()
		{

			/**
			 * Registers our custom options in Customizer.
			 */
			add_filter('resa_customizer_options', array($this, 'register'));

		}

		/**
		 *
		 *
		 * @param array
		 * @since 1.0.0
		 */
		public abstract function register($options);


	}
endif;
