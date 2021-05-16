<?php
/**
* Modifications vignette produit affichée dans les boucles 
* template woocommerce de référence : templates\content-product.php
*
**/

//Insérer overlay et pictos après image
add_action( 'woocommerce_before_shop_loop_item_title', 'kasutan_overlay_pictos_vignette_produit', 12 );
function kasutan_overlay_pictos_vignette_produit() {
	echo '<div class="overlay-vignette">Je découvre</div>';
	$etiquettes = get_the_terms( get_the_ID(), 'product_tag' );
	if(!empty($etiquettes) && function_exists('get_field')) {
		echo '<div class="pictos">';
		foreach($etiquettes as $etiquette) {
			$titre=$etiquette->name;
			$term_id=$etiquette->term_id;
			$picto_id=get_field('picto','product_tag_'.$term_id);
			echo wp_get_attachment_image( $picto_id, 'thumbnail', false, array('title'=>$titre,'class'=>'picto') );
		}
		echo '</div>';
	}
}
//On garde la balise <a> au début de la vignette mais on la referme après l'image, l'overlay et les pictos 
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
	$args['class']=substr_replace($args['class'],'',0,strlen('button')); //on ne remplace que la première occurence au début de la liste de classes
	return $args;
}


//Ajouter une classe au li.product si le produit se trouve dans la catégorie des produits avec alcool (identifiée dans les options du site)
add_filter('woocommerce_post_class','kasutan_ajoute_classe_produit_avec_alcool', 10,2);
function kasutan_ajoute_classe_produit_avec_alcool($classes, $product) {
	if( function_exists('get_field') && function_exists('kasutan_categories_produit') ) {
		$categories=kasutan_categories_produit();
		$categorie_avec_alcool=esc_attr(get_field('categorie_avec_alcool','option')); //renvoie un term_id
		if(is_array($categories) && !empty($categorie_avec_alcool)) {
			$sous_categorie=$categories['sous_categorie']->term_id;
			if($sous_categorie==$categorie_avec_alcool) {
				$classes[]='avec-alcool';
			}
		}
	}

	return $classes;
}