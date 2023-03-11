<?php

function resa_sanitize_toggle($input, $setting)
{

	$input = sanitize_key($input);

	return true === (boolean)$input;
}

function resa_sanitize_responsive($values, $setting)
{

	$control = $setting->manager->get_control($setting->id);
	$control_type = str_replace('resa-', '', $control->type);
	$control_units = isset($control->unit) ? $control->unit : false;

	if (is_array($control->responsive) && !empty($control->responsive)) {

		// Ensure all responsive devices are in value.
		foreach ($control->responsive as $device => $settings) {

			if (!isset($values[$device])) {
				$values[$device] = isset($setting->default[$device]) ? $setting->default[$device] : '';
			}
		}

		// Ensure all devices in value are allowed and sanitize value.
		foreach ($values as $device => $value) {

			if ('unit' === $device) {
				continue;
			}

			if (!isset($control->responsive[$device])) {
				unset($values[$device]);
				continue;
			}

			// Sanitize value.
			$values[$device] = call_user_func_array(
				'resa_sanitize_' . $control_type,
				array(
					$values[$device],
					$setting,
					isset($setting->default[$device]) ? $setting->default[$device] : '',
				)
			);
		}
	}

	return $values;
}

function resa_sanitize_spacing($values, $setting, $default = array())
{

	$control = $setting->manager->get_control($setting->id);
	$control_choices = $control->choices;
	$control_units = $control->unit;

	foreach ($control_choices as $key => $value) {
		if (!isset($values[$key])) {
			$values[$key] = isset($default[$key]) ? $default[$key]: 0;
		}
	}

	foreach ($values as $key => $value) {

		if ('unit' === $key) {
			continue;
		}

		if (!isset($control_choices[$key])) {
			unset($values[$key]);
			continue;
		}

		$values[$key] = is_numeric($value) ? $value : '';
	}

	if (isset($values['unit']) && !in_array($values['unit'], $control_units, true)) {
		if (isset($default['unit'])) {
			$values['unit'] = $default['unit'];
		} elseif (!empty($control_units)) {
			$values['unit'] = $control_units[0];
		} else {
			$values['unit'] = '';
		}
	}

	return $values;
}

function resa_sanitize_color($color)
{

	if (empty($color) || is_array($color)) {
		return '';
	}

	if (false === strpos($color, 'rgba')) {
		return resa_sanitize_hex_color($color);
	}

	return resa_sanitize_alpha_color($color);
}

function resa_sanitize_hex_color($color)
{

	if ('' === $color) {
		return '';
	}

	// 3 or 6 hex digits, or the empty string.
	if (preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color)) {
		return $color;
	}

	return '';
}

function resa_sanitize_alpha_color($color)
{

	if ('' === $color) {
		return '';
	}

	if (false === strpos($color, 'rgba')) {
		/* Hex sanitize */
		return resa_sanitize_hex_color($color);
	}

	/* rgba sanitize */
	$color = str_replace(' ', '', $color);
	sscanf($color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha);
	return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
}
