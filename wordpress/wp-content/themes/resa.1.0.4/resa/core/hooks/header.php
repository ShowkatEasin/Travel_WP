<?php

class Resa_Hooks_Header

{
	public function __construct()
	{
		add_action('wp_head', array($this, 'pingback_header'), 1);

	}


	public function pingback_header()
	{
		if (is_singular() && pings_open()) {

			echo '<link rel="pingback" href="', esc_url(get_bloginfo('pingback_url')), '">';
		}
	}


}

new Resa_Hooks_Header();
