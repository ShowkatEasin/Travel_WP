<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

class Resa_Sidebar_Schema extends Resa_Schema
{

	public function setup_schema()
	{

		if (true !== $this->schema_enabled()) {
			return false;
		}

		add_filter('resa_markup_attr_sidebar', array($this, 'sidebar_markup_schema'));
	}

	public function sidebar_markup_schema($attr)
	{
		$attr['itemtype'] = 'https://schema.org/WPSideBar';
		$attr['itemscope'] = 'itemscope';

		return $attr;
	}

	protected function schema_enabled()
	{
		return apply_filters('resa_sidebar_schema_markup_enabled', parent::schema_enabled());
	}

}

new Resa_Sidebar_Schema();
