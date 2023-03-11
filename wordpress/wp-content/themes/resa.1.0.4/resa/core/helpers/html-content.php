<?php
if (!function_exists('resa_post_thumbnail')) {
	function resa_post_thumbnail($size = 'post-thumbnail', $show_category = false)
	{
		if (has_post_thumbnail()) {
			echo '<div class="resa-post-thumbnail">';
			if ($show_category) {
				$categories_list = get_the_category_list(' ');
				if ($categories_list) {
					// Make sure there's more than one category before displaying.
					echo '<div class="categories-link-box">' . $categories_list . '</div>';
				}
			}
			$thumb_attributes = Resa_Markup::attr(
				'article-image',
				array(),
				array(),
				'array'
			);

			unset($thumb_attributes['class']);

			$size = $size ? $size : 'post-thumbnail';

			if (is_singular()) {

				the_post_thumbnail($size, $thumb_attributes);

			} else {

				echo '<a href="' . get_the_permalink() . '">';

				the_post_thumbnail($size, $thumb_attributes);

				echo '</a>';
			}

			echo '</div>';
		}
	}

}

if (!function_exists('resa_comment')) {

	function resa_comment($comment, $args, $depth)
	{
		if ('div' === $args['style']) {
			$tag = 'div';
			$add_below = 'comment';
		} else {
			$tag = 'li';
			$add_below = 'div-comment';
		}
		?>
		<<?php echo esc_attr($tag) . ' '; ?><?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?> id="comment-<?php comment_ID(); ?>">

		<div class="comment-body">
			<div class="comment-author vcard">
				<?php echo get_avatar($comment, 50); ?>
			</div>
			<?php if ('div' !== $args['style']) : ?>
			<div id="div-comment-<?php comment_ID(); ?>" class="comment-content">
				<?php endif; ?>
				<div class="comment-head">
					<div class="comment-meta commentmetadata">
						<?php printf('<cite class="fn">%s</cite>', get_comment_author_link()); ?>
						<?php if ('0' === $comment->comment_approved) : ?>
							<em class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'resa'); ?></em>
							<br/>
						<?php endif; ?>

						<a href="<?php echo esc_url(htmlspecialchars(get_comment_link($comment->comment_ID))); ?>"
						   class="comment-date">
							<?php echo '<time datetime="' . get_comment_date('c') . '">' . get_comment_date() . '</time>'; ?>
						</a>
					</div>
					<div class="reply">
						<?php
						comment_reply_link(
							array_merge(
								$args, array(
									'add_below' => $add_below,
									'depth' => $depth,
									'max_depth' => $args['max_depth'],
								)
							)
						);
						?>
						<?php edit_comment_link(esc_html__('Edit', 'resa'), '  ', ''); ?>
					</div>
				</div>
				<div class="comment-text">
					<?php comment_text(); ?>
				</div>
				<?php if ('div' !== $args['style']) : ?>
			</div>
		<?php endif; ?>
		</div>
		<?php
	}
}

if (!function_exists('resa_post_author')) {

	function resa_post_author($output_filter = '')
	{

		ob_start();

		echo '<span ';

		echo Resa_Markup::attr(
			'post-meta-author',
			array(
				'class' => 'posted-by vcard author',
			)
		);
		echo '>';
		// Translators: Author Name.
		?>
		<a title="<?php printf(esc_attr__('View all posts by %1$s', 'resa'), get_the_author()); ?>"
		   href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" rel="author"
			<?php
			echo Resa_Markup::attr(
				'author-url',
				array(
					'class' => 'url fn n',
				)
			);
			?>
		>
				<span
				<?php
				echo Resa_Markup::attr(
					'author-name',
					array(
						'class' => 'author-name',
					)
				);
				?>
				><?php echo get_the_author(); ?></span>
		</a>
		</span>

		<?php

		$output = ob_get_clean();

		return apply_filters('resa_post_author', $output, $output_filter);
	}
}

if (!function_exists('resa_post_meta')) {

	function resa_post_meta($atts = array())
	{
		global $post;
		if ('post' !== get_post_type()) {
			return;
		}

		extract(
			shortcode_atts(
				array(
					'show_date' => true,
					'show_cat' => false,
					'show_author' => true,
					'show_comment' => false,
				),
				$atts
			)
		);

		$posted_on = '';
		// Posted on.
		if ($show_date) {
			$posted_on = '<div class="posted-on">' . sprintf('<a href="%1$s" rel="bookmark">%2$s %3$s</a>', esc_url(get_permalink()), '<i class="fa-regular fa-calendar"></i>', get_the_modified_date()) . '</div>';
		}

		$categories_list = get_the_category_list(', ');
		$categories = '';
		if ($show_cat && $categories_list) {
			// Make sure there's more than one category before displaying.
			$categories = '<div class="categories-link"><span class="screen-reader-text">' . esc_html__('Categories', 'resa') . '</span>' . $categories_list . '</div>';
		}
		$author = '';
		// Author.
		if ($show_author == 1) {
			$author_id = $post->post_author;
			$author = sprintf(
				'<div class="post-author"><i class="fa-regular fa-user"></i><span>%1$s</span>%2$s</div>',
				esc_html__('By ', 'resa'),
				resa_post_author()
			);
		}

		echo wp_kses(
			sprintf('%1$s %2$s %3$s', $author, $posted_on, $categories), array(
				'div' => array(
					'class' => array(),
				),
				'span' => array(
					'class' => array(),
					'itemprop' => array(),
					'itemtype' => array(),
					'itemscope' => array()
				),
				'i' => array(
					'class' => array(),
				),
				'a' => array(
					'href' => array(),
					'rel' => array(),
					'class' => array(),
					'itemprop' => array(),
					'itemtype' => array(),
					'itemscope' => array()
				),
				'time' => array(
					'datetime' => array(),
					'class' => array(),
				),

			)
		);

		if ($show_comment) { ?>
			<div class="meta-reply">
				<?php
				comments_popup_link(esc_html__('0 comments', 'resa'), esc_html__('1 comment', 'resa'), esc_html__('% comments', 'resa'));
				?>
			</div>
			<?php
		}

	}
}

if (!function_exists('resa_edit_post_link')) {

	function resa_edit_post_link()
	{
		edit_post_link(
			sprintf(
				wp_kses(__('Edit <span class="screen-reader-text">%s</span>', 'resa'),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<div class="edit-link">',
			'</div>'
		);
	}
}

if (!function_exists('resa_categories_link')) {

	function resa_categories_link()
	{

		// Get Categories for posts.
		$categories_list = get_the_category_list('');

		if ('post' === get_post_type() && $categories_list) {
			// Make sure there's more than one category before displaying.
			echo '<div class="categories-link"><span class="screen-reader-text">' . esc_html__('Categories', 'resa') . '</span>' . $categories_list . '</div>';
		}
	}
}
