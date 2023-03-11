<?php
get_header(); ?>
	<div id="primary" class="content">
		<main id="main" class="site-main">
			<div class="error-404 not-found">
				<div class="page-content">
					<div class="error-img404">
						<img src="<?php echo get_theme_file_uri('assets/images/404-image.jpg') ?>"
							 alt="<?php echo esc_attr__('404 Page', 'resa'); ?>">
					</div>
					<header class="page-header">
						<h2 class="sub-title"><?php esc_html_e('Oops! That Page Can’t Be Found.', 'resa'); ?></h2>
					</header><!-- .page-header -->
					<div class="error-text">
                        <span><?php esc_html_e('The Page you are looking for doesn\'t exitst. Go to ', 'resa') ?><a
								href="<?php echo esc_url(home_url('/')); ?>"
								class="return-home"><?php esc_html_e('Home page', 'resa'); ?></a></span>
					</div>
				</div><!-- .page-content -->
			</div><!-- .error-404 -->
		</main><!-- #main -->
	</div><!-- #primary -->
<?php

get_footer();
