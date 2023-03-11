<?php

get_header(); ?>

	<div id="primary" class="resa-primary-content-area">

		<main id="main" class="site-main">

			<?php if (have_posts()) {

				?>
				<header class="page-header">
					<h1 class="page-title">
						<?php
						/* translators: %s: search term */
						printf(esc_attr__('Search Results for: %s', 'resa'), '<span>' . get_search_query() . '</span>');
						?>
					</h1>
				</header><!-- .page-header -->

				<?php
				get_template_part('template-parts/loop');

			} else {
				get_template_part('template-parts/none');
			}
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action('resa_sidebar');
get_footer();
