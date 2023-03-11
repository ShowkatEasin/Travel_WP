<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

class Resa_Header_Schema extends Resa_Schema
{

	public function setup_schema()
	{

		if (true !== $this->schema_enabled()) {
			return false;
		}

		add_filter('resa_markup_attr_header', array($this, 'header_markup_schema'));
	}


	public function header_markup_schema($attr)
	{
		$attr['itemtype'] = 'https://schema.org/WPHeader';
		$attr['itemscope'] = 'itemscope';
		$attr['itemid'] = '#masthead';

		return $attr;
	}


	protected function schema_enabled()
	{
		return apply_filters('resa_header_schema_markup_enabled', parent::schema_enabled());
	}

}

new Resa_Header_Schema();
