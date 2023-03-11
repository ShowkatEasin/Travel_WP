</div><!-- .resa-row -->
</div><!-- .resa-container -->
</div><!-- #content -->

<?php do_action('resa_before_footer');

?>

<footer <?php echo Resa_Markup::attr(
	'footer',
	array(
		'id' => 'colophon',
		'class' => 'site-footer resa-site-footer',
		'role' => 'contentinfo'
	)
); ?>
>
	<?php

	do_action('resa_footer');

	?>

</footer><!-- #colophon -->

<?php


/**
 * Functions hooked in to resa_after_footer action
 * @see resa_sticky_single_add_to_cart    - 999 - woo
 */
do_action('resa_after_footer');
?>

</div><!-- #page -->

<?php
do_action('resa_after_site');
wp_footer();
?>
</body>
</html>
