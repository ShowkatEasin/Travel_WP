<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}


class Resa_CreativeWork_Schema extends Resa_Schema
{


	public function setup_schema()
	{

		if (true !== $this->schema_enabled()) {
			return false;
		}

		add_filter('resa_markup_attr_article-blog', array($this, 'creative_work_schema'));
		add_filter('resa_markup_attr_article-page', array($this, 'creative_work_schema'));
		add_filter('resa_markup_attr_article-single', array($this, 'creative_work_schema'));

		add_filter('resa_markup_attr_article-title-blog', array($this, 'article_title_blog_schema_prop'));
		add_filter('resa_markup_attr_article-title-blog-single', array($this, 'article_title_blog_single_schema_prop'));
		add_filter('resa_markup_attr_article-title-content-page', array($this, 'article_title_content_page_schema_prop'));

		add_filter('resa_markup_attr_article-entry-content-blog-layout', array($this, 'article_content_blog_layout_schema_prop'));
		add_filter('resa_markup_attr_article-entry-content-page', array($this, 'article_content_page_schema_prop'));

		add_filter('resa_markup_attr_article-image', array($this, 'article_image_schema_prop'));
	}


	public function creative_work_schema($attr)
	{
		$attr['itemtype'] = 'https://schema.org/CreativeWork';
		$attr['itemscope'] = 'itemscope';

		return $attr;
	}


	public function article_title_blog_schema_prop($attr)
	{
		$attr['itemprop'] = 'headline';

		return $attr;
	}


	public function article_title_blog_single_schema_prop($attr)
	{
		$attr['itemprop'] = 'headline';

		return $attr;
	}


	public function article_title_content_page_schema_prop($attr)
	{
		$attr['itemprop'] = 'headline';

		return $attr;
	}


	public function article_title_content_schema_prop($attr)
	{
		$attr['itemprop'] = 'headline';

		return $attr;
	}


	public function article_content_blog_layout_schema_prop($attr)
	{
		$attr['itemprop'] = 'text';

		return $attr;
	}


	public function article_content_page_schema_prop($attr)
	{
		$attr['itemprop'] = 'text';

		return $attr;
	}


	public function article_image_schema_prop($attr)
	{
		$attr['itemprop'] = 'image';

		return $attr;
	}

	
	protected function schema_enabled()
	{
		return apply_filters('resa_creativework_schema_markup_enabled', parent::schema_enabled());
	}

}

new Resa_CreativeWork_Schema();
