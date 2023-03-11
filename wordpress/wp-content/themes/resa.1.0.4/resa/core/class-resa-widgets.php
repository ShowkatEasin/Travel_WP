<?php
/**
 * Resa_Widgets setup
 *
 * @package Resa_Widgets
 * @since 1.0.0
 */

/**
 * Main Resa_Widgets Class.
 *
 * @class Resa_Widgets
 */
class Resa_Widgets
{

	/**
	 * The single instance of the class.
	 *
	 * @var Resa_Widgets
	 * @since 1.0.0
	 */
	protected static $_instance = null;


	/**
	 * Main Resa_Widgets Instance.
	 *
	 * Ensures only one instance of Resa_Widgets is loaded or can be loaded.
	 *
	 * @return Resa_Widgets - Main instance.
	 * @since 1.0.0
	 * @static
	 */
	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		self::$_instance->includes();

		return self::$_instance;
	}

	public function includes()
	{

	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function __construct()
	{
		add_action('widgets_init', array($this, 'widgets_init'));

	}

	/**
	 * Register widget area.
	 *
	 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
	 */
	public function widgets_init()
	{
		$sidebar_args['sidebar'] = array(
			'name' => esc_html__('Sidebar Archive', 'resa'),
			'id' => 'sidebar-blog',
			'description' => '',
		);
		$sidebar_args['sidebar-single'] = array(
			'name' => esc_html__('Sidebar Single Post', 'resa'),
			'id' => 'sidebar-single',
			'description' => '',
		);

		$sidebar_args['footer-widget-1'] = array(
			'name' => esc_html__('Footer Widget Area 1', 'resa'),
			'id' => 'footer-widget-area-1',
			'description' => '',
		);
		$sidebar_args['footer-widget-2'] = array(
			'name' => esc_html__('Footer Widget Area 2', 'resa'),
			'id' => 'footer-widget-area-2',
			'description' => '',
		);
		$sidebar_args['footer-widget-3'] = array(
			'name' => esc_html__('Footer Widget Area 3', 'resa'),
			'id' => 'footer-widget-area-3',
			'description' => '',
		);
		$sidebar_args['footer-widget-4'] = array(
			'name' => esc_html__('Footer Widget Area 4', 'resa'),
			'id' => 'footer-widget-area-4',
			'description' => '',
		);


		$sidebar_args = apply_filters('resa_sidebar_args', $sidebar_args);

		foreach ($sidebar_args as $sidebar => $args) {
			$widget_tags = array(
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h2 class="widget-title">',
				'after_title' => '</h2>',
			);

			$filter_hook = sprintf('resa_%s_widget_tags', $sidebar);
			$widget_tags = apply_filters($filter_hook, $widget_tags);

			if (is_array($widget_tags)) {
				register_sidebar($args + $widget_tags);
			}
		}
	}


}

Resa_Widgets::instance();
