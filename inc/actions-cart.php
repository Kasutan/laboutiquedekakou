<?php
/**
* Modifications page panier
*
**/

//Ajouter un wrapper autour de cart callaterals et des éléments insérés via des plugins sur le hook before_cart_collaterals
add_action('woocommerce_before_cart_collaterals','kasutan_before_cart_collaterals',1);
function kasutan_before_cart_collaterals() {
	echo '<div class="collaterals-wrap">';
}
add_action('woocommerce_after_cart','kasutan_after_cart');
function kasutan_after_cart() {
	echo '</div>';
}

//Ajouter une phrase sur la livraison offerte
add_action('woocommerce_after_shipping_calculator','kasutan_after_shipping_calculator');
function kasutan_after_shipping_calculator(){
	if(function_exists('get_field') 
		&& !empty( $message=wp_kses_post(get_field('kakou_message_livraison_offerte','options'))) ) {

			printf('<p><em><small>%s</small></em></p>',$message);
	}
}