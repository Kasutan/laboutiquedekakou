<?php
/**
* Modifications vignette produit affichée dans les boucles 
* template woocommerce de référence : templates\content-product.php
*
**/

//On garde la balise <a> au début de la vignette mais on la referme juste après l'image 
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 15 );

//Insérer la catégorie entre l'image et le titre
add_action( 'woocommerce_before_shop_loop_item_title','kasutan_before_shop_loop_item_title',20);
function kasutan_before_shop_loop_item_title(){
	if(!function_exists('kasutan_categories_produit')) {
		return;
	}

	$categories=kasutan_categories_produit();
	if(is_array($categories)) {
		$sous_categorie=$categories['sous_categorie'];
		printf('<p class="categorie">%s</p><span class="term screen-reader-text">%s</span>',$sous_categorie->name,$sous_categorie->slug);
	}
}

//Placer des balises <a> et </a> juste avant et juste après le titre
add_action('woocommerce_shop_loop_item_title','woocommerce_template_loop_product_link_open',5);
add_action('woocommerce_shop_loop_item_title','woocommerce_template_loop_product_link_close',15);

//Enlever la balise </a> à la fin de la vignette
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

//Entourer le bouton d'ajout au panier d'un div et insérer un lien vers la fiche produit
add_action( 'woocommerce_after_shop_loop_item', 'kasutan_after_shop_loop_item', 5 );
function kasutan_after_shop_loop_item() {
	echo '<div class="actions-vignette">';
	printf('<a href="%s" class="oeil" title="Afficher la fiche produit"><span class="screen-reader-text">%s</span></a>', 
		get_the_permalink(),
		get_the_title()
	);
}
add_action( 'woocommerce_after_shop_loop_item', 'kasutan_after_shop_loop_item_fermer', 15 );
function kasutan_after_shop_loop_item_fermer() {
	echo '</div>'; //fin .actions-vignettes

	global $product;
	if(!$product->is_in_stock()) {
		echo '<div class="vignette-hors-stock">Bientôt de retour</div>';
	}
}


//Ajouter un attribut title au bouton d'ajout au panier et lui enlever la classe button
add_filter( 'woocommerce_loop_add_to_cart_args', 'kasutan_loop_add_to_cart_args',10,2);
function kasutan_loop_add_to_cart_args($args, $product ) {
	$args['attributes']['title']="Ajouter le produit au panier";
	$args['class']=str_replace('button','',$args['class']);
	return $args;
}
