<?php
function resa_footer_copyright()
{

	$theme_author = [
		'theme_name' => __('Resa WordPress Theme', 'resa'),
		'theme_author_url' => 'https://wpyatra.com/best-wordpress-travel-theme/',
	];

	$content = resa()->options->get('resa_bottom_footer_copyright_text');

	echo '<div class="resa-footer-copyright">';

	$content = str_replace('[copyright]', '&copy;', $content);

	$content = str_replace('[current_year]', gmdate('Y'), $content);

	$content = str_replace('[site_title]', get_bloginfo('name'), $content);

	$content = str_replace('[theme_author]', '<a href="' . esc_url($theme_author['theme_author_url']) . '" rel="nofollow noopener" target="_blank">' . $theme_author['theme_name'] . '</a>', $content);

	echo do_shortcode(wpautop($content));

	echo '</div>';


}
