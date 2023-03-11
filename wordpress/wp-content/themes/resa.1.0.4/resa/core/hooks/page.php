<?php

class Resa_Hooks_Page

{

	public function __construct()
	{
		add_action('resa_page_content', array($this, 'header'), 10);
		add_action('resa_page_content', array($this, 'content'), 20);
		add_action('resa_page_after', array($this, 'display_comments'), 10);


	}

	public function header()
	{

		if (is_front_page()) {
			return;
		}

		?>
		<header class="entry-header">
			<?php
			if (has_post_thumbnail()) {
				resa_post_thumbnail('full');
			}
			the_title(
				sprintf(
					'<h1 %1$s>',
					Resa_Markup::attr(
						'title-content-page',
						array(
							'class' => 'entry-title',
						)
					)
				),
				'</h1>'
			);
			?>
		</header><!-- .entry-header -->
		<?php
	}

	public function content()
	{
		?>
		<div <?php
		echo Resa_Markup::attr(
			'article-entry-content-page',
			array(
				'class' => 'entry-content',
			)
		)
		?>>
			<?php the_content(); ?>

		</div><!-- .entry-content -->
		<?php
		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__('Pages:', 'resa'),
				'after' => '</div>',
			)
		);

	}

	public function display_comments()
	{
		// If comments are open or we have at least one comment, load up the comment template.
		if (comments_open() || 0 !== intval(get_comments_number())) :
			comments_template();
		endif;
	}

}

new Resa_Hooks_Page();
