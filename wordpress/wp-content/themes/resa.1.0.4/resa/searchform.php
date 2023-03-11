<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
	<label>
		<span class="screen-reader-text"><?php echo esc_html__('Search for:', 'resa'); ?></span>
		<input type="search" class="search-field" placeholder="<?php esc_attr_e('Search &hellip;', 'resa'); ?>"
			   value="<?php echo get_search_query() ?>" name="s"/>
	</label>
	<button type="submit" class="search-submit" value="<?php echo esc_attr__('Search', 'resa'); ?>"><?php echo esc_html__('Search', 'resa'); ?></button>
</form>
