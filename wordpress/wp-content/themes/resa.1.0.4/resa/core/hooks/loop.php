<?php

class Resa_Hooks_Loop

{

	public function __construct()
	{
		add_action('resa_loop_post_content', array($this, 'header'), 15);
		add_action('resa_loop_post_content', array($this, 'content'), 30);


		add_action('resa_loop_after', array($this, 'pagination_navigation'), 10);

	}

	public function header()
	{
		?>
		<header class="entry-header">
			<?php
			$categories_list = get_the_category_list(' ');

			if ('post' == get_post_type()) {

				if ($categories_list && !has_post_thumbnail()) {
					// Make sure there's more than one category before displaying.
					echo '<div class="categories-link-box">' . $categories_list . '</div>';
				}

			}
			the_title(
				sprintf(
					'<h3 %2$s><a href="%1$s" rel="bookmark">',
					esc_url(get_permalink()),
					Resa_Markup::attr(
						'article-title-blog',
						array(
							'class' => 'entry-title',
						)
					)
				),
				'</a></h2>'
			);

			?>
			<div class="entry-meta">
				<?php resa_post_meta(); ?>
			</div>
		</header><!-- .entry-header -->
		<?php
	}

	public function content()
	{

		do_action('resa_loop_post_content_before');

		?>
		<div <?php
		echo Resa_Markup::attr(
			'article-entry-excerpt-blog-layout',
			array(
				'class' => 'entry-excerpt',
			)
		)
		?>><?php

			the_excerpt();
			?>
		</div><!-- .entry-excerpt -->
		<?php

		echo '<div class="more-link-wrap"><a class="more-link" href="' . get_permalink() . '">' . esc_html__('Read more', 'resa') . '<i class="fa-solid fa-angle-right"></i></a></div>';

		do_action('resa_loop_post_content_after');

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__('Pages:', 'resa'),
				'after' => '</div>',
			)
		);
	}

	public function pagination_navigation()
	{
		$args = array(
			//'type' => 'list',
			'next_text' => '<span class="screen-reader-text">' . esc_html__('Next', 'resa') . '</span><i class="resa-icon fa-solid fa-angle-right"></i>',
			'prev_text' => '<i class="resa-icon fa-solid fa-angle-left"></i><span class="screen-reader-text">' . esc_html__('Previous', 'resa') . '</span>',
		);

		the_posts_pagination($args);
	}


}

new Resa_Hooks_Loop();
