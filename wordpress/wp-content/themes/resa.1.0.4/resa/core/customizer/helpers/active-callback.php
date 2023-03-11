<?php
function resa_is_tagline_active()
{
	if (true === (boolean)resa()->options->get('resa_show_tagline')) {
		return true;
	}
	return false;
}
