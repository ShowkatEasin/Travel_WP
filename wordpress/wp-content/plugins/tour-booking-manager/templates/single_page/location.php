<?php
	if ( ! defined( 'ABSPATH' ) ) {
		die;
	} // Cannot access pages directly.
	get_header();
	do_action( 'ttbm_single_location_page_before_wrapper' );
	echo do_shortcode( "[travel-list style='modern' show='10'  pagination='yes' sidebar-filter='yes']" );
	do_action( 'ttbm_single_location_page_after_wrapper' );
	get_footer();
