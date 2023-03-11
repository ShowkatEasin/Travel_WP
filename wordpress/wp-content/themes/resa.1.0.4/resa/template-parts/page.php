<div id="primary" class="resa-primary-content-area">

	<main id="main" class="site-main">

		<?php
		while (have_posts()) :
			the_post();

			do_action('resa_page_before');

			?>
			<article
				<?php echo Resa_Markup::attr(
					'article-page',
					array(
						'id' => 'post-' . get_the_ID(),
						'class' => join(' ', get_post_class()),
					)
				); ?>>
				<?php
				do_action('resa_page_content');
				?>
			</article><!-- #post-## -->

			<?php

			do_action('resa_page_after');

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->
</div><!-- #primary -->
