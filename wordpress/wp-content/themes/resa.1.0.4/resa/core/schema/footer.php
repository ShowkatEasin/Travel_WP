<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}


class Resa_Footer_Schema extends Resa_Schema
{

	public function setup_schema()
	{

		if (true !== $this->schema_enabled()) {
			return false;
		}

		add_filter('resa_markup_attr_footer', array($this, 'footer_Schema'));
	}


	public function footer_Schema($attr)
	{
		$attr['itemtype'] = 'https://schema.org/WPFooter';
		$attr['itemscope'] = 'itemscope';
		$attr['itemid'] = '#colophon';
		return $attr;
	}


	protected function schema_enabled()
	{
		return apply_filters('resa_footer_schema_markup_enabled', parent::schema_enabled());
	}

}

new Resa_Footer_Schema();
