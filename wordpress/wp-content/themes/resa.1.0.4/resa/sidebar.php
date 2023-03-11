<?php
$sidebar = apply_filters('resa_theme_sidebar', '');
if (!$sidebar) {
	return;
}
?>

<div <?php
echo Resa_Markup::attr(
	'sidebar',
	array(
		'id' => 'secondary',
		'class' => 'resa-secondary-sidebar-area',
		'role' => 'complementary',
	)
); ?>>
	<?php dynamic_sidebar($sidebar); ?>
</div><!-- #secondary -->
