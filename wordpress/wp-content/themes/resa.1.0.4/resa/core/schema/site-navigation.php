<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

class Resa_Site_Navigation_Schema extends Resa_Schema
{


	public function setup_schema()
	{

		if (true !== $this->schema_enabled()) {
			return false;
		}

		add_filter('resa_markup_attr_site-navigation', array($this, 'site_navigation_schema'));
	}
	
	public function site_navigation_schema($attr)
	{
		$attr['itemtype'] = 'https://schema.org/SiteNavigationElement';
		$attr['itemscope'] = 'itemscope';

		return $attr;
	}

	protected function schema_enabled()
	{
		return apply_filters('resa_site_navigation_schema_markup_enabled', parent::schema_enabled());
	}

}

new Resa_Site_Navigation_Schema();
