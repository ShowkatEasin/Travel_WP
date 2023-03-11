<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!class_exists('Resa_Markup')) :


	class Resa_Markup
	{

		public static function attr($context, $attributes = array(), $args = array(), $return_string_or_array = 'string')
		{

			$attributes = self::parse_attr($context, $attributes, $args);

			$output = '';

			$output_array = array();

			// Cycle through attributes, build tag attribute string.
			foreach ($attributes as $key => $value) {

				if (!$value) {
					continue;
				}

				if (true === $value) {
					$output .= esc_html($key) . ' ';
				} else {
					$output .= sprintf('%s="%s" ', esc_html($key), esc_attr($value));
				}
				$output_array[esc_attr(trim($key))] = esc_attr(trim($value));
			}

			$output = apply_filters("resa_markup_attr_{$context}_output", $output, $attributes, $context, $args);

			return $return_string_or_array === 'array' ? $output_array : trim($output);
		}

		private static function parse_attr($context, $attributes = array(), $args = array())
		{

			$append_class = "resa-markup-attr-" . sanitize_html_class($context);

			$attributes['class'] = isset($attributes['class']) ? $attributes['class'] . ' ' . $append_class : $append_class;

			// Contextual filter.
			return apply_filters("resa_markup_attr_{$context}", $attributes, $context, $args);
		}

	}

endif;
