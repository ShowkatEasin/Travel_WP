<?php

class Resa_Hooks_Sidebar

{

	public function __construct()
	{
		add_action('resa_sidebar', array($this, 'get_sidebar'), 10);

	}

	public function get_sidebar()
	{
		get_sidebar();
	}

}

new Resa_Hooks_Sidebar();
