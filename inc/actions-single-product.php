<?php
/**
* Modifications contenu single product
*
**/
add_action('woocommerce_single_product_summary','kasutan_single_product_title',5);
function kasutan_single_product_title() {
	printf('<h1 class="titre-produit">%s</h1>',get_the_title());
}

add_action('woocommerce_before_add_to_cart_form','kasutan_before_add_to_cart_form');
function kasutan_before_add_to_cart_form() {
	if(is_single()) {
		echo '<div class="form-container">';
	}
}
add_action('woocommerce_after_add_to_cart_form','kasutan_after_add_to_cart_form');
function kasutan_after_add_to_cart_form() {
	if(is_single()) {
		global $product;
		echo wc_get_stock_html( $product ); //TODO picto checkmark quand le produit est en stock
		echo '</div>'; //on referme la balise div.form-container
		echo '<p>TODO Boutons de partage ici</p>';
	}
}


add_action('woocommerce_before_quantity_input_field','womoon_before_quantity_input_field');
function womoon_before_quantity_input_field() {
	if(is_single()) {
		echo '<span class="label-quantite">Quantité</span>';
	}
	echo '<div class="cadre-quantite"><button class="change-quantity" data-value="-" aria-label="Diminuer la quantité de 1">-</button>';
}

add_action('woocommerce_after_quantity_input_field','womoon_after_quantity_input_field');
function womoon_after_quantity_input_field() {
	echo '<button class="change-quantity" data-value="+" aria-label="Augmenter la quantité de 1">+</button>';
	echo '</div>';//on ferme la balise div.cadre-quantite
}

//Enlever les métas dans le résumé
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

//TODO onglets personnalisés


//TODO produits associés