<?php

if (!function_exists('resa_get_theme_option')) {
	function resa_get_theme_option($option_name, $default = false)
	{

		if ($option = get_option('resa_options_' . $option_name)) {
			$default = $option;
		}

		return $default;
	}
}
function resa_load_google_fonts_locally()
{
	return false; // we will add option whether load font locally or directly from google font api
}

function resa_google_fonts()
{
	$google_fonts = apply_filters('resa_google_font_families',
		[
			'Heebo' => 'Heebo:400,500,700'
		]
	);

	if (count($google_fonts) < 1) {
		return false;
	}

	$query_args = array(
		'family' => implode('&family=', $google_fonts),
		'subset' => rawurlencode('latin,latin-ext'),
		'display' => 'swap',
	);

	return add_query_arg($query_args, 'https://fonts.googleapis.com/css');
}

function resa_get_prop( $array, $prop, $default = null ) {

	if ( ! is_array( $array ) && ! ( is_object( $array ) && $array instanceof ArrayAccess ) ) {
		return $default;
	}

	if ( isset( $array[ $prop ] ) ) {
		$value = $array[ $prop ];
	} else {
		$value = '';
	}

	return empty( $value ) && null !== $default ? $default : $value;
}
function resa_responsive_spacing( $option, $side = '', $device = 'desktop', $default = '', $prefix = '' ) {

	$unit = isset($option[$device]['unit']) ? $option[$device]['unit'] : '';

	$unit = $unit=='' && isset($option['unit']) ? $option['unit']: $unit;

	if ( isset( $option[ $device ][ $side ] ) && $unit!=='') {
		$spacing = resa_get_css_value( $option[ $device ][ $side ], $unit, $default );
	} elseif ( is_numeric( $option ) ) {
		$spacing = resa_get_css_value( $option );
	} else {
		$spacing = ( ! is_array( $option ) ) ? $option : '';
	}

	if ( '' !== $prefix && '' !== $spacing ) {
		return $prefix . $spacing;
	}
	return $spacing;
}
function resa_get_css_value( $value = '', $unit = 'px', $default = '' ) {

	if ( '' == $value && '' == $default ) {
		return $value;
	}

	$css_val = '';

	switch ( $unit ) {

		case 'px':
		case '%':
		case 'rem':
			if ( 'inherit' === strtolower( $value ) || 'inherit' === strtolower( $default ) ) {
				return $value;
			}

			$value   = ( '' != $value ) ? $value : $default;
			$css_val = esc_attr( $value ) . $unit;
			break;

		case 'url':
			$css_val = $unit . '(' . esc_url( $value ) . ')';
			break;

		default:
			$value = ( '' != $value ) ? $value : $default;
			if ( '' != $value ) {
				$css_val = esc_attr( $value ) . $unit;
			}
	}

	return $css_val;
}
