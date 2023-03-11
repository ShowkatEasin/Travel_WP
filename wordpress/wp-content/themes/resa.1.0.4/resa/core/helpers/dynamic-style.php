<?php
if (!function_exists('resa_minify_css')) {

	function resa_minify_css($css = '')
	{

		// Return if no CSS
		if (!$css) return;

		// Normalize whitespace
		$css = preg_replace('/\s+/', ' ', $css);

		// Remove ; before }
		$css = preg_replace('/;(?=\s*})/', '', $css);

		// Remove space after , : ; { } */ >
		$css = preg_replace('/(,|:|;|\{|}|\*\/|>) /', '$1', $css);

		// Remove space before , ; { }
		$css = preg_replace('/ (,|;|\{|})/', '$1', $css);

		// Strips leading 0 on decimal values (converts 0.5px into .5px)
		$css = preg_replace('/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css);

		// Strips units if value is 0 (converts 0px to 0)
		$css = preg_replace('/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css);

		// Trim
		// Return minified CSS
		return trim($css);

	}
}

if (!function_exists('resa_get_dynamic_css')) {

	function resa_get_dynamic_css()
	{
		$all_dynamic_css = apply_filters('resa_dynamic_css', '');

		return resa_minify_css($all_dynamic_css);
	}
}



