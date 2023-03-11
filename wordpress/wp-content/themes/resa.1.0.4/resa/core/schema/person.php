<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}


class Resa_Person_Schema extends Resa_Schema
{

	public function setup_schema()
	{

		if (true !== $this->schema_enabled()) {
			return false;
		}

		add_filter('resa_markup_attr_post-meta-author', array($this, 'person_Schema'));
		add_filter('resa_markup_attr_author-name', array($this, 'author_name_schema_itemprop'));
		add_filter('resa_markup_attr_author-url', array($this, 'author_url_schema_itemprop'));
		add_filter('resa_markup_attr_author-name-info', array($this, 'author_name_info_schema_itemprop'));
		add_filter('resa_markup_attr_author-url-info', array($this, 'author_info_url_schema_itemprop'));
		add_filter('resa_markup_attr_author-item-info', array($this, 'author_item_schema_itemprop'));
		add_filter('resa_markup_attr_author-desc-info', array($this, 'author_desc_schema_itemprop'));
	}

	public function person_Schema($attr)
	{
		$attr['itemtype'] = 'https://schema.org/Person';
		$attr['itemscope'] = 'itemscope';
		$attr['itemprop'] = 'author';
		$attr['class'] = 'posted-by vcard author';

		return $attr;
	}

	public function author_name_schema_itemprop($attr)
	{
		$attr['itemprop'] = 'name';

		return $attr;
	}

	public function author_name_info_schema_itemprop($attr)
	{
		$attr['itemprop'] = 'name';

		return $attr;
	}

	public function author_url_schema_itemprop($attr)
	{
		$attr['itemprop'] = 'url';

		return $attr;
	}

	public function author_info_url_schema_itemprop($attr)
	{
		$attr['itemprop'] = 'url';

		return $attr;
	}

	public function author_desc_schema_itemprop($attr)
	{
		$attr['itemprop'] = 'description';

		return $attr;
	}

	public function author_item_schema_itemprop($attr)
	{
		$attr['itemprop'] = 'author';

		return $attr;
	}

	protected function schema_enabled()
	{
		return apply_filters('resa_person_schema_markup_enabled', parent::schema_enabled());
	}

}

new Resa_Person_Schema();
