<?php

class Resa_Hooks_Template

{

	public function __construct()
	{
		add_filter('comment_form_default_fields', array($this, 'resa_update_comment_fields'));
		add_filter('wp_list_categories', array($this, 'resa_replace_categories_list'), 10, 2);
		add_filter('get_archives_link', array($this, 'resa_replace_archive_list'), 10, 7);

	}

	function resa_update_comment_fields($fields)
	{

		$commenter = wp_get_current_commenter();
		$req = get_option('require_name_email');
		$aria_req = $req ? "aria-required='true'" : '';

		$fields['author']
			= '<p class="comment-form-author">
			<input id="author" name="author" type="text" placeholder="' . esc_attr__('Your Name *', 'resa') . '" value="' . esc_attr($commenter['comment_author']) .
			'" size="30" ' . $aria_req . ' />
		</p>';

		$fields['email']
			= '<p class="comment-form-email">
			<input id="email" name="email" type="email" placeholder="' . esc_attr__('Email Address *', 'resa') . '" value="' . esc_attr($commenter['comment_author_email']) .
			'" size="30" ' . $aria_req . ' />
		</p>';

		$fields['url']
			= '<p class="comment-form-url">
			<input id="url" name="url" type="url"  placeholder="' . esc_attr__('Your Website', 'resa') . '" value="' . esc_attr($commenter['comment_author_url']) .
			'" size="30" />
			</p>';

		return $fields;
	}

	function resa_replace_categories_list($output, $args)
	{
		if ($args['show_count'] = 1) {
			$pattern = '#<li([^>]*)><a([^>]*)>(.*?)<\/a>\s*\(([0-9]*)\)\s*#i';  // removed ( and )
			$replacement = '<li$1><a$2><span class="cat-name">$3</span> <span class="cat-count">($4)</span></a>';
			return preg_replace($pattern, $replacement, $output);
		}
		return $output;
	}
	function resa_replace_archive_list($link_html, $url, $text, $format, $before, $after, $selected)
	{
		if ($format == 'html') {
			$pattern = '#<li><a([^>]*)>(.*?)<\/a>&nbsp;\s*\(([0-9]*)\)\s*#i';  // removed ( and )
			$replacement = '<li><a$1><span class="archive-name">$2</span> <span class="archive-count">($3)</span></a>';
			return preg_replace($pattern, $replacement, $link_html);
		}
		return $link_html;
	}
}

new Resa_Hooks_Template();
