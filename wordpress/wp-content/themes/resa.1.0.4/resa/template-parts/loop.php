<?php

do_action('resa_loop_before');


while (have_posts()) :
	the_post();

	?>
	<article <?php echo Resa_Markup::attr(
		'article-blog',
		array(
			'id' => 'post-' . get_the_ID(),
			'class' => join(' ', get_post_class('article-default')),
		)
	); ?>>
		<div class="post-inner">
			<?php resa_post_thumbnail('post-thumbnail', true); ?>
			<div class="post-content">
				<?php
				do_action('resa_loop_post_content');
				?>
			</div>
		</div>
	</article><!-- #post-## -->

<?php

endwhile;


do_action('resa_loop_after');
