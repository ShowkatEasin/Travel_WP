<div id="primary" class="resa-primary-content-area">
	<main id="main" class="site-main">

		<?php

		if (have_posts()) : ?>

			<header class="page-header">
				<?php
				the_archive_description('<div class="taxonomy-description">', '</div>');
				?>
			</header><!-- .page-header -->

			<?php
			get_template_part('template-parts/loop');

		else :

			get_template_part('template-parts/none');

		endif;
		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
do_action('resa_sidebar');
