<div id="primary" class="resa-primary-content-area">

	<main id="main" class="site-main">

		<?php
		while (have_posts()) :
			the_post();

			do_action('resa_single_post_before');

			?>
			<article
				<?php echo Resa_Markup::attr(
					'article-single',
					array(
						'id' => 'post-' . get_the_ID(),
						'class' => join(' ', get_post_class()),
					)
				); ?>>
				<div class="single-content">
					<?php


					do_action('resa_single_post_content');


					?>

				</div>

			</article><!-- #post -->
			<?php

			do_action('resa_single_post_after');

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
do_action('resa_sidebar');
