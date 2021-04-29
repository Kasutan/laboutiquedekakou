<?php

add_filter( 'gettext', function($translated, $original, $domain) {

	if($domain == 'woocommerce') {
		switch ($translated) {
			case 'Description courte du produit' :
				return 'Présentation commerciale';
				break;
			default:
				break;
		}
	} else if($domain == 'woofc') {
		switch ($original) {
			case 'You may be interested in&hellip;' :
				return 'Vous aimerez aussi&hellip;';
				break;
			default:
				break;
		}
	}

	return $translated;
}, 10, 3);