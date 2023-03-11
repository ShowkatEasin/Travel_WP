<?php

class Resa_Hooks_Single

{

	public function __construct()
	{

		add_action('resa_single_post_content', array($this, 'post_thumbnail'), 10);
		add_action('resa_single_post_content', array($this, 'post_header'), 20);
		add_action('resa_single_post_content', array($this, 'post_content'), 30);

		add_action('resa_single_post_content', array($this, 'post_taxonomy'), 40);
		add_action('resa_single_post_content', array($this, 'post_nav'), 50);
		add_action('resa_single_post_content', array($this, 'display_comments'), 60);


	}

	public function post_header()
	{
		?>
		<header class="entry-header">
			<?php
			$categories_list = get_the_category_list(' ');

			if ($categories_list && !has_post_thumbnail()) {
				// Make sure there's more than one category before displaying.
				echo '<div class="categories-link-box">' . $categories_list . '</div>';
			}
			the_title(
				sprintf(
					'<h1 %1$s>',
					Resa_Markup::attr(
						'article-title-blog-single',
						array(
							'class' => 'entry-title',
						)
					)
				),
				'</h1>'
			)
			?>
			<div class="entry-meta">
				<?php resa_post_meta(); ?>
			</div>

		</header><!-- .entry-header -->
		<?php
	}

	public function post_thumbnail()
	{
		resa_post_thumbnail('full', true);
	}

	public function post_content()
	{

		?>
		<div <?php
		echo Resa_Markup::attr(
			'article-entry-content-single',
			array(
				'class' => 'entry-content',
			)
		)
		?>><?php

			the_content();

			?>
		</div><!-- .entry-content -->
		<?php

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__('Pages:', 'resa'),
				'after' => '</div>',
			)
		);
	}


	public function post_taxonomy()
	{
		/* translators: used between list items, there is a space after the comma */

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list('', ' ');
		if (!$tags_list) {
			return;
		}
		?>
		<aside class="entry-taxonomy">
			<?php if ($tags_list) : ?>
				<div class="tags-links">
					<span
						class="screen-reader-text"><?php echo esc_html(_n('Tag:', 'Tags:', count(get_the_tags()), 'resa')); ?></span>
					<?php printf('%s', $tags_list); ?>
				</div>
			<?php endif;
			?>
		</aside>
		<?php
	}

	public function post_nav()
	{

		$prev_post = get_previous_post();
		$next_post = get_next_post();
		$args = [];
		$thumbnail_prev = '';
		$thumbnail_next = '';

		if ($prev_post) {
			$thumbnail_prev = get_the_post_thumbnail($prev_post->ID, array(108, 74));
		};

		if ($next_post) {
			$thumbnail_next = get_the_post_thumbnail($next_post->ID, array(108, 74));
		};
		if ($next_post) {
			$args['next_text'] = '<span class="nav-content"><span class="reader-text">' . esc_html__('Next', 'resa') . ' <i class="fa-solid fa-angle-right"></i></span><span class="title">%title</span></span>' . $thumbnail_next;
		}
		if ($prev_post) {
			$args['prev_text'] = $thumbnail_prev . '<span class="nav-content"><span class="reader-text"><i class="fa-solid fa-angle-left"></i>' . esc_html__('Prev', 'resa') . ' </span><span class="title">%title</span></span> ';
		}

		the_post_navigation($args);

	}

	public function display_comments()
	{
		// If comments are open or we have at least one comment, load up the comment template.
		if (comments_open() || 0 !== intval(get_comments_number())) :
			comments_template();
		endif;
	}


}

new Resa_Hooks_Single();
