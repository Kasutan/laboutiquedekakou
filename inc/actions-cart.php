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