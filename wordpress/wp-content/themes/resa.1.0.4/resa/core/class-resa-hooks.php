<?php
/**
 * Resa_Hooks setup
 *
 * @package Resa_Hooks
 * @since 1.0.0
 */

/**
 * Main Resa_Hooks Class.
 *
 * @class Resa_Hooks
 */
class Resa_Hooks
{

	/**
	 * The single instance of the class.
	 *
	 * @var Resa_Hooks
	 * @since 1.0.0
	 */
	protected static $_instance = null;


	/**
	 * Main Resa_Hooks Instance.
	 *
	 * Ensures only one instance of Resa_Hooks is loaded or can be loaded.
	 *
	 * @return Resa_Hooks - Main instance.
	 * @since 1.0.0
	 * @static
	 */
	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		self::$_instance->includes();

		return self::$_instance;
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes()
	{
		include_once RESA_THEME_DIR . 'core/hooks/header.php';
		include_once RESA_THEME_DIR . 'core/hooks/footer.php';
		include_once RESA_THEME_DIR . 'core/hooks/loop.php';
		include_once RESA_THEME_DIR . 'core/hooks/page.php';
		include_once RESA_THEME_DIR . 'core/hooks/single.php';
		include_once RESA_THEME_DIR . 'core/hooks/sidebar.php';
		include_once RESA_THEME_DIR . 'core/hooks/template.php';
		include_once RESA_THEME_DIR . 'core/hooks/css.php';
		include_once RESA_THEME_DIR . 'core/hooks/navigation.php';


	}


}

Resa_Hooks::instance();
