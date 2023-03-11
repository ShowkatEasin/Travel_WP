<?php

/**
 * Resa functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Mantrabrain
 * @subpackage Resa
 * @since 1.0.0
 */
$resa_theme = wp_get_theme('resa');

define('RESA_THEME_VERSION', $resa_theme->get('Version'));
define('RESA_THEME_SETTINGS', 'resa');
define('RESA_THEME_OPTION_PANEL', 'resa_theme_option_panel');
define('RESA_THEME_DIR', trailingslashit(get_template_directory()));
define('RESA_THEME_URI', trailingslashit(esc_url(get_template_directory_uri())));
// Theme Core file init

require_once RESA_THEME_DIR . 'core/class-resa.php';

/**
 * The function which returns the one Resa instance.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $resa = resa(); ?>
 *
 * @return Resa
 * @since 1.0.0
 */
function resa()
{
	return Resa::get_instance();

}

resa();


