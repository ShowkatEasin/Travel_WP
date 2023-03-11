<?php

class Resa_Compatibility
{
	public function __construct()
	{
		$this->includes();
	}

	public function includes()
	{
		require RESA_THEME_DIR . '/core/compatibility/elementor.php';

	}

}

new Resa_Compatibility();
