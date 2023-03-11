<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

class Resa_Schema
{


	public function __construct()
	{
		$this->include();

		add_action('wp', array($this, 'setup_schema'));
	}

	public function setup_schema()
	{
	}

	private function include()
	{

		require_once RESA_THEME_DIR . '/core/schema/header.php';
		require_once RESA_THEME_DIR . '/core/schema/site-navigation.php';
		require_once RESA_THEME_DIR . '/core/schema/breadcrumb.php';
		require_once RESA_THEME_DIR . '/core/schema/sidebar.php';
		require_once RESA_THEME_DIR . '/core/schema/creativework.php';
		require_once RESA_THEME_DIR . '/core/schema/person.php';
		require_once RESA_THEME_DIR . '/core/schema/organization.php';
		require_once RESA_THEME_DIR . '/core/schema/footer.php';


	}


	protected function schema_enabled()
	{
		return apply_filters('resa_schema_markup_enabled', true);
	}

}

new Resa_Schema();
