<?php
/**
* Modifications vignette produit affichée dans les boucles 
* template woocommerce de référence : templates\content-product.php
*
**/

add_action( 'woocommerce_before_shop_loop_item_title','kasutan_before_shop_loop_item_title');
function kasutan_before_shop_loop_item_title(){
	if(!function_exists('kasutan_categories_produit')) {
		return;
	}

	$categories=kasutan_categories_produit();
	if(is_array($categories)) {
		$sous_categorie=$categories['sous_categorie'];
		printf('<span class="term screen-reader-text">%s</span>',$sous_categorie->slug);
	}
}