<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}


class Resa_Breadcrumb_Schema extends Resa_Schema
{


	public function setup_schema()
	{
		add_action('wp', array($this, 'disable_schema_before_title'), 20);
	}


	public function disable_schema_before_title()
	{

	}


	public function breadcrumb_schema($args)
	{
		$args['schema'] = false;

		return $args;
	}

	protected function schema_enabled()
	{
		return apply_filters('resa_breadcrumb_schema_markup_enabled', parent::schema_enabled());
	}

}

new Resa_Breadcrumb_Schema();
