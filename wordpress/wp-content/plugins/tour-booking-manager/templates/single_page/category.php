<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die;
	} // Cannot access pages directly.
	get_header();
	the_post();
	$term_id = get_queried_object()->term_id;
	do_action( 'ttbm_single_category_page_before_wrapper' );
	echo do_shortcode( "[travel-list cat='" . $term_id . "' style='modern' show='10'  pagination='yes' sidebar-filter='yes']" );
	do_action( 'ttbm_single_category_page_after_wrapper' );
	get_footer();
