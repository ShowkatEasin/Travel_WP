<?php


if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

class Resa_Organization_Schema extends Resa_Schema
{


	public function setup_schema()
	{

		if (true !== $this->schema_enabled()) {
			return false;
		}

		add_filter('resa_markup_attr_site-identity', array($this, 'organization_Schema'));
		add_filter('resa_markup_attr_site-title', array($this, 'site_title_attr'));
		add_filter('resa_markup_attr_site-title-link', array($this, 'site_title_link_attr'));
		add_filter('resa_markup_attr_site-description', array($this, 'site_description_attr'));
		
	}


	public function organization_Schema($attr)
	{
		$attr['itemtype'] = 'https://schema.org/Organization';
		$attr['itemscope'] = 'itemscope';

		return $attr;
	}

	public function site_title_attr($attr)
	{
		$attr['itemprop'] = 'name';

		return $attr;
	}


	public function site_title_link_attr($attr)
	{
		$attr['itemprop'] = 'url';

		return $attr;
	}


	public function site_description_attr($attr)
	{
		$attr['itemprop'] = 'description';

		return $attr;
	}


	protected function schema_enabled()
	{
		return apply_filters('resa_organization_schema_markup_enabled', parent::schema_enabled());
	}

}

new Resa_Organization_Schema();
