<?php

if (!defined('ABSPATH')) {
	exit;
}

final class Resa
{

	private static $instance = null;

	/** @var $options Resa_Options */

	public $options;

	public static function get_instance()
	{

		if (!isset(self::$instance) && !(self::$instance instanceof Resa)) {
			self::$instance = new Resa();
			self::$instance->includes();
			self::$instance->hooks();
			self::$instance->load();
			do_action('resa_loaded');
		}
		return self::$instance;
	}

	public function load()
	{
		resa()->options = new Resa_Options();

	}

	public function includes()
	{

		require RESA_THEME_DIR . '/core/helpers/functions.php';
		require RESA_THEME_DIR . '/core/helpers/html-header.php';
		require RESA_THEME_DIR . '/core/helpers/html-content.php';
		require RESA_THEME_DIR . '/core/helpers/html-sidebar.php';
		require RESA_THEME_DIR . '/core/helpers/html-footer.php';
		require RESA_THEME_DIR . '/core/helpers/dynamic-style.php';
		require RESA_THEME_DIR . '/core/class-resa-options.php';
		require RESA_THEME_DIR . '/core/class-resa-assets.php';
		require RESA_THEME_DIR . '/core/class-resa-hooks.php';
		require RESA_THEME_DIR . '/core/class-resa-markup.php';
		require RESA_THEME_DIR . '/core/class-resa-schema.php';
		require RESA_THEME_DIR . '/core/class-resa-walker-page.php';
		require RESA_THEME_DIR . '/core/customizer/customizer.php';
		require RESA_THEME_DIR . '/core/class-resa-widgets.php';
		require RESA_THEME_DIR . '/core/class-resa-compatibility.php';

		//Background Updater
		require RESA_THEME_DIR . '/core/update/background-updater.php';



	}

	public function hooks()
	{
		add_action('after_setup_theme', array($this, 'setup'));
		add_filter('resa_theme_sidebar', array($this, 'set_sidebar'), 20);
		add_filter('body_class', array($this, 'body_classes'));
		add_filter('wp_page_menu_args', array($this, 'page_menu_args'));
		add_filter('navigation_markup_template', array($this, 'navigation_markup_template'));
		add_filter('block_editor_settings_all', array($this, 'custom_editor_settings'), 10, 2);

		add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);

		add_filter('gutenberg_use_widgets_block_editor', '__return_false');
		add_filter('use_widgets_block_editor', '__return_false');
	}


	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	public function setup()
	{

		// Loads wp-content/themes/child-theme-name/languages/resa.mo.
		load_theme_textdomain('resa', get_stylesheet_directory() . '/languages');

		// Loads wp-content/themes/resa/languages/resa.mo.
		load_theme_textdomain('resa', get_template_directory() . '/languages');

		/**
		 * Add default posts and comments RSS feed links to head.
		 */
		add_theme_support('automatic-feed-links');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#Post_Thumbnails
		 */
		add_theme_support('post-thumbnails');
		set_post_thumbnail_size(1070, 510, true);
		add_image_size('resa-post-defautl', 780, 500, true);
		add_image_size('resa-post-grid', 820, 500, true);


		/**
		 * Register menu locations.
		 */
		register_nav_menus(
			apply_filters(
				'resa_register_nav_menus', array(
					'primary' => esc_html__('Primary Menu', 'resa'),
					'mobile' => esc_html__('Mobile Menu', 'resa'),
				)
			)
		);

		// Add theme support for Custom Logo.
		add_theme_support('custom-logo', array(
			'width' => 300,
			'height' => 200,
			'flex-width' => true,
			'flex-height' => true,
		));

		/*
		 * Switch default core markup for search form, comment form, comments, galleries, captions and widgets
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5', apply_filters(
				'resa_html5_args', array(
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'widgets',
					'script',
					'style',
				)
			)
		);

		/**
		 * Declare support for title theme feature.
		 */
		add_theme_support('title-tag');

		/**
		 * Declare support for selective refreshing of widgets.
		 */
		add_theme_support('customize-selective-refresh-widgets');

		/**
		 * Add support for Block Styles.
		 */
		add_theme_support('wp-block-styles');

		/**
		 * Add support for full and wide align images.
		 */
		add_theme_support('align-wide');

		/**
		 * Add support for editor styles.
		 */
		add_theme_support('editor-styles');

		/**
		 * Add support for editor font sizes.
		 */
		add_theme_support('editor-font-sizes', array(
			array(
				'name' => esc_html__('Small', 'resa'),
				'size' => 14,
				'slug' => 'small',
			),
			array(
				'name' => esc_html__('Normal', 'resa'),
				'size' => 16,
				'slug' => 'normal',
			),
			array(
				'name' => esc_html__('Medium', 'resa'),
				'size' => 23,
				'slug' => 'medium',
			),
			array(
				'name' => esc_html__('Large', 'resa'),
				'size' => 26,
				'slug' => 'large',
			),
			array(
				'name' => esc_html__('Huge', 'resa'),
				'size' => 37,
				'slug' => 'huge',
			),
		));

		/**
		 * Enqueue editor styles.
		 */
		add_editor_style(array('assets/css/base/gutenberg-editor.css', resa_google_fonts()));

		/**
		 * Add support for responsive embedded content.
		 */
		add_theme_support('responsive-embeds');
	}

	public function admin_scripts()
	{
		//wp_enqueue_style('resa-admin-style', get_theme_file_uri('assets/css/admin/admin.css'));
	}

	public function page_menu_args($args)
	{
		$args['show_home'] = true;

		return $args;
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 *
	 * @return array
	 */
	public function body_classes($classes)
	{
		global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
		if ($is_lynx) {
			$classes[] = 'lynx';
		} elseif ($is_gecko) {
			$classes[] = 'gecko';
		} elseif ($is_opera) {
			$classes[] = 'opera';
		} elseif ($is_NS4) {
			$classes[] = 'ns4';
		} elseif ($is_safari) {
			$classes[] = 'safari';
		} elseif ($is_chrome) {
			$classes[] = 'chrome';
		} elseif ($is_IE) {
			$classes[] = 'ie';
		}

		if ($is_iphone) {
			$classes[] = 'iphone';
		}


		/**
		 * Adds a class when WooCommerce is not active.
		 *
		 * @todo Refactor child themes to remove dependency on this class.
		 */
		$classes[] = 'no-wc-breadcrumb';
		$sidebar = resa_get_theme_option('blog_archive_sidebar', 'left');
		if (is_singular('post')) {
			if (!is_active_sidebar('sidebar-single')) {
				$classes[] = 'resa-full-width-content';
			} else {
				if ($sidebar == 'left') {
					$classes[] = 'resa-sidebar-left';
				}
			}
		} else {
			if (is_archive() || is_home() || is_category() || is_tag() || is_author() || is_search()) {
				if (!is_active_sidebar('sidebar-blog')) {
					$classes[] = 'resa-full-width-content';
				} else {
					if ($sidebar == 'left') {
						$classes[] = 'resa-sidebar-left';
					}
				}
			}
		}
		if (is_singular('resa_portfolio')) {
			$classes[] = 'resa-full-width-content';
		}

		// Add class when using homepage template + featured image.
		if (has_post_thumbnail()) {
			$classes[] = 'has-post-thumbnail';
		}

		return $classes;
	}

	public function set_sidebar($name)
	{
		if (is_singular('post')) {
			if (is_active_sidebar('sidebar-single')) {
				$name = 'sidebar-single';
			}
		} else {
			if (is_archive() || is_home() || is_category() || is_tag() || is_author() || is_search()) {
				if (is_active_sidebar('sidebar-blog') && (!is_post_type_archive('resa_portfolio') && !is_tax('resa_portfolio_cat'))) {
					$name = 'sidebar-blog';
				}
			}
		}

		return $name;
	}

	public function custom_editor_settings($settings, $post)
	{
		$settings['mainSidebarActive'] = false;

		if (is_active_sidebar('sidebar-blog')) {
			$settings['mainSidebarActive'] = true;
		}

		return $settings;
	}

	/**
	 * Custom navigation markup template hooked into `navigation_markup_template` filter hook.
	 */
	public function navigation_markup_template()
	{
		$template = '<nav id="post-navigation" class="navigation %1$s" role="navigation" aria-label="' . esc_attr__('Post Navigation', 'resa') . '">';
		$template .= '<h2 class="screen-reader-text">%2$s</h2>';
		$template .= '<div class="nav-links">%3$s</div>';
		$template .= '</nav>';

		return apply_filters('resa_navigation_markup_template', $template);
	}
}

