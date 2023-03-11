<?php

class Resa_Hooks_Navigation

{
	private $navigations = ['primary', 'mobile'];

	public function __construct()
	{
		add_filter('nav_menu_item_title', array($this, 'dropdown_icon_markup'), 10, 4);

		add_filter('walker_nav_menu_start_el', array($this, 'toggle_button'), 20, 4);

	}

	public function toggle_button($item_output, $item, $depth, $args)
	{


		if (true === is_object($args)) {
			if (isset($args->theme_location) && in_array($args->theme_location, $this->navigations)) {
				if (isset($item->classes) && in_array('menu-item-has-children', $item->classes)) {
					$item_output = $this->dropdown_icon_button_markup($item_output, $item, $args);
				}
			}
		} else {
			if (isset($item->post_parent) && 0 === $item->post_parent) {
				$item_output = $this->dropdown_icon_button_markup($item_output, $item, $args);
			}
		}

		return $item_output;
	}

	public function dropdown_icon_button_markup($item_output, $item, $args)
	{
		$item_output = apply_filters('resa_toggle_button_markup', $item_output, $item);

		$is_expanded = in_array('current-menu-ancestor', $item->classes);

		if ($is_expanded) {

			$item->classes[] = "resa-menu-active-test";

		}

		$toggle_element = '<button ' . Resa_Markup::attr(
				'resa-menu-toggle',
				array(
					'aria-expanded' => 'false',
					'class' => 'resa-mobile-menu-toggle'
				),
				$item
			) . '><span class="screen-reader-text">' . __('Menu Toggle', 'resa') . '</span><span class="dropdown-menu-toggle fa-solid fa-angle-down"></span></button>';


		return '<span class="resa-sub-menu-parent">' . $item_output . $toggle_element . '</span>';


		return $item_output . $toggle_element;
	}


	public function dropdown_icon_markup($title, $item, $args, $depth)
	{
		$role = 'presentation';

		$load_icon = false;

		if (in_array($args->theme_location, $this->navigations)) {

			$load_icon = true;

		}

		if ($load_icon) {

			foreach ($item->classes as $value) {

				if ('menu-item-has-children' === $value) {

					$title = $title . '<span role="' . esc_attr($role) . '" class="resa-desktop-menu-toggle dropdown-menu-toggle fa-solid fa-angle-down" ></span>';


				}
			}
		}

		return $title;
	}


}

new Resa_Hooks_Navigation();
