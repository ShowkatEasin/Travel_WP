<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<link rel="profile" href="//gmpg.org/xfn/11">
	<?php
	/**
	 * Functions hooked in to wp_head action
	 *
	 * @see resa_pingback_header - 1
	 */
	wp_head();

	?>
</head>
<body <?php body_class(); ?>>
<a class="skip-link screen-reader-text" href="<?php echo apply_filters('resa_skip_to_content_href', '#content') ?>"
   role="link"
   title="<?php esc_attr_e('Skip to content', 'resa'); ?>"
>
	<?php echo __('Skip to content', 'resa'); ?>
</a>
<?php
if (function_exists('wp_body_open')) {
	wp_body_open();
}
?>

<?php do_action('resa_before_site'); ?>

<div id="page" class="hfeed site">
	<?php
	/**
	 * Functions hooked in to resa_before_header action
	 *
	 */
	do_action('resa_before_header');

	?>
	<header
		<?php
		echo Resa_Markup::attr(
			'header',
			array(
				'id' => 'masthead',
				'class' => 'site-header resa-site-header',
				'role' => 'banner'
			)
		);
		?>
	>
		<div class="resa-main-header" data-device="desktop">
			<div class="resa-container">
				<div class="resa-row">

					<div class="column-3 resa-site-branding-wrap">
						<?php
						resa_site_branding();
						?>
					</div>
					<?php if (function_exists('yatra_mini_cart') && (boolean)resa()->options->get('resa_show_yatra_mini_cart')===true) { ?>
						<div class="column-8 resa-primary-navigation-wrap">
							<?php resa_primary_navigation(); ?>
						</div>
						<div class="column-1 resa-yatra-mini-cart">
							<?php
							yatra_mini_cart();
							?>
						</div>
					<?php } else {
						?>
						<div class="column-9 resa-primary-navigation-wrap">
							<?php resa_primary_navigation(); ?>
						</div>
						<?php
					} ?>

				</div>
			</div>
		</div>
		<div class="resa-main-header" data-device="mobile">
			<div class="resa-container">
				<div class="resa-row">

					<div class="column-9 resa-site-branding-wrap">
						<?php
						resa_site_branding();
						?>

					</div>
					<div class="column-3 resa-primary-navigation-wrap">
						<?php resa_hamburger_button(); ?>
					</div>

				</div>
			</div>
		</div>
	</header><!-- #masthead -->

	<?php


	/**
	 * Functions hooked in to resa_before_content action
	 *
	 */
	do_action('resa_before_content');
	?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="resa-container">
			<div class="resa-row">

<?php
/**
 * Functions hooked in to resa_content_top action
 *
 * @see resa_shop_messages - 10 - woo
 *
 */
do_action('resa_content_top');

