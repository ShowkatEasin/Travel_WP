<?php

defined('ABSPATH') || exit;

function resa_103_site_identity_update()
{
	resa()->options->set('resa_show_tagline', true);
}
