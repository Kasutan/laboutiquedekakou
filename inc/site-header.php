<?php
add_action('tha_header_before','kasutan_header_before');
function kasutan_header_before() {
	 if(function_exists('kasutan_affiche_volet_recherche')) {
		kasutan_affiche_volet_recherche();
	}
	
}