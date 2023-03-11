<?php
if (!function_exists('resa_site_branding')) {

	function resa_site_branding()
	{
		?>
		<div
			<?php echo Resa_Markup::attr(
				'site-identity',
				array(
					'class' => 'site-branding'
				)
			); ?>>
			<?php
			the_custom_logo();
			?>
			<div class="site-branding-text">
				<?php
				resa_site_title();

				if (true === (boolean)resa()->options->get('resa_show_tagline')) {
					resa_site_description();
				}

				?>
			</div>
		</div>
		<?php
	}
}

if (!function_exists('resa_site_title')) {
	function resa_site_title()
	{
		$html_tag = is_front_page() ? 'h1' : 'p';

		?>
		<<?php echo esc_attr($html_tag); ?>
		<?php echo Resa_Markup::attr(
		'site-title',
		array(
			'class' => 'site-title'
		)
	); ?>>
		<a <?php echo Resa_Markup::attr(
			'site-title-link',
			array(
				'href' => esc_url(home_url('/')),
				'rel' => 'home'
			)
		); ?>><?php bloginfo('name'); ?></a>
		</<?php echo esc_attr($html_tag); ?>>
		<?php
	}
}
if (!function_exists('resa_site_description')) {

	function resa_site_description()
	{
		$description = get_bloginfo('description', 'display');

		if ($description) :
			?>
			<p
				<?php echo Resa_Markup::attr(
					'site-description',
					array(
						'class' => 'site-description',
					)
				); ?>
			><?php echo esc_html($description); ?></p>
		<?php endif;

	}
}
if (!function_exists('resa_navigation')) {

	function resa_navigation($location, $args)
	{
		$fallback_menu_args = array(
			'theme_location' => $location,
			'container' => 'div',
			'menu_class' => 'primary-navigation resa-page-navigation',
			'before' => '<ul class="menu">',
			'after' => '</ul>',
			'walker' => new Resa_Walker_Page(),
		);

		if (has_nav_menu($location)) {
			wp_nav_menu($args);
		} else {
			wp_page_menu($fallback_menu_args);
		}

	}
}
if (!function_exists('resa_primary_navigation')) {

	function resa_primary_navigation()
	{
		?>
		<nav <?php echo Resa_Markup::attr(
			'site-navigation',
			array(
				'id' => 'site-navigation',
				'class' => 'main-navigation site-navigation',
				'aria-label' => esc_html__('Primary Navigation', 'resa')
			)
		); ?>
		>
			<?php
			$args = apply_filters('resa_nav_menu_args', [
				'theme_location' => 'primary',
				'container_class' => 'primary-navigation',

			]);
			resa_navigation('primary', $args);
			?>
		</nav>
		<?php
	}
}

if (!function_exists('resa_hamburger_button')) {
	function resa_hamburger_button()
	{

		?>
		<button class="resa-panel-action-trigger-button" data-panel-trigger-id="resa-offcanvas">
				<span
					class="toggle-text screen-reader-text"><?php echo esc_html(apply_filters('resa_menu_toggle_text', esc_html__("Menu", 'resa'))); ?></span>
			<div class="resa-hamburger-icon">
				<span class="icon-1"></span>
				<span class="icon-2"></span>
				<span class="icon-3"></span>
			</div>
		</button>
		<?php

	}
}

if (!function_exists('resa_mobile_navigation')) {

	function resa_mobile_navigation()
	{
		if (!has_nav_menu('mobile')) {

			resa_primary_navigation();

			return;
		}
		?>
		<nav <?php echo Resa_Markup::attr(
			'site-navigation',
			array(
				'id' => 'mobile-navigation',
				'class' => 'mobile-navigation site-navigation',
				'aria-label' => esc_html__('Mobile Navigation', 'resa')
			)
		); ?>
		>
			<?php
			$args = [
				'theme_location' => 'mobile',
				'container_class' => 'mobile-navigation',
			];

			resa_navigation('mobile', $args);
			?>
		</nav>
		<?php
	}
}
