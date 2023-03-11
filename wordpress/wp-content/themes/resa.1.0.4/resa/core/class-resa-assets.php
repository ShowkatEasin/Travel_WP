<?php

class Resa_Assets
{
	public function __construct()
	{
		add_action('wp_enqueue_scripts', array($this, 'scripts'));

		add_action('enqueue_block_assets', array($this, 'block_assets'));


	}

	public function scripts()
	{

		include_once RESA_THEME_DIR . '/core/third-party/font-loader/wptt-webfont-loader.php';

		wp_register_style('resa-font-awesome', get_template_directory_uri() . '/assets/lib/font-awesome/css/fontawesome.min.css', '', '6.2.0');

		wp_enqueue_style('resa-style', get_template_directory_uri() . '/assets/css/resa.css', array('resa-font-awesome'), RESA_THEME_VERSION);
		//wp_enqueue_style('resa-style', get_template_directory_uri() . '/style.css', '', RESA_THEME_VERSION);


		//Load resa fonts
		$google_fonts = function_exists('wptt_get_webfont_url') && resa_load_google_fonts_locally() ? wptt_get_webfont_url(resa_google_fonts()) : resa_google_fonts();

		wp_enqueue_style('resa-fonts', $google_fonts, array(), null);


		wp_enqueue_script('resa-theme', get_template_directory_uri() . '/assets/js/resa.js', array('jquery'), RESA_THEME_VERSION, true);
		wp_localize_script('resa-theme', 'resaAjax', array('ajaxurl' => admin_url('admin-ajax.php')));


		if (is_singular() && comments_open() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply');
		}

		$dynamic_css = resa_get_dynamic_css();


		wp_add_inline_style('resa-style', $dynamic_css);

	}

	public function block_assets()
	{

	}
}

new Resa_Assets();
