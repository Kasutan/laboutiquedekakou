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
		//Ajouter contenance poids ou volume net
		if(function_exists('get_field')) {
			$contenance=get_field('kakou_poids_net');
			if(!empty($contenance)) {
				printf('<p><strong>%s</strong></p>',$contenance);
			}
		}
		//Ouvrir un conteneur pour affichage en 2 colonnes avec le stock à droite
		echo '<div class="form-container">';
	}
}
add_action('woocommerce_after_add_to_cart_form','kasutan_after_add_to_cart_form');
function kasutan_after_add_to_cart_form() {
	if(is_single()) {
		global $product;
		echo wc_get_stock_html( $product );
		echo '</div>'; //on referme la balise div.form-container

		/**************Boutons de partage**********/
		if(function_exists('kasutan_picto')) :
			$link=str_replace(":", "%3A", get_the_permalink());?>
			<div class="boutons-partage">
				<nav aria-label="Partager sur les réseaux sociaux">
					<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $link;?>"  class="icone-partage"
					title="Cliquez pour partager sur Facebook" rel="noopener noreferrer" target="blank"   >
					<?php echo kasutan_picto(array('icon'=>'facebook-simple', 'size'=>'36'));?>
					<span class="screen-reader-text">Cliquez pour partager sur Facebook (ouvre dans une nouvelle fenêtre)</span></a>

					<a href="https://twitter.com/home?status=<?php echo $link;?>"  class="icone-partage twitter"
					title="Cliquez pour partager sur Twitter" rel="noopener noreferrer" target="blank"   >
					<?php echo kasutan_picto(array('icon'=>'twitter', 'size'=>'36'));?>
					<span class="screen-reader-text">Cliquez pour partager sur Twitter (ouvre dans une nouvelle fenêtre)</span></a>
				</nav>
			</div>
		<?php endif;
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


/**
* Onglets personnalisés
* https://docs.woocommerce.com/document/editing-product-data-tabs/#
*/
add_filter( 'woocommerce_product_tabs', 'kasutan_product_tabs', 98 );
function kasutan_product_tabs( $tabs ) {
	unset( $tabs['reviews'] );             // Remove the reviews tab
	unset( $tabs['additional_information'] );     // Remove the additional information tab

	if(!function_exists('get_field')) {
		return $tabs; //on affiche l'onglet description, même vide, pour ne pas casser la mise en page
	}

	$ingredients=wp_kses_post(get_field('kakou_ingredients'));//zone de texte
	$allergenes=wp_kses_post(get_field('kakou_allergenes_2'));//zone de texte
	$conseil=wp_kses_post(get_field('kakou_conseil'));//zone de texte
	$producteur=get_field('kakou_producteur'); //objet producteur

	if(!empty($ingredients) || !empty($allergenes)) {
		unset( $tabs['description'] );          // Remove the description tab
		$tabs['composition'] = array(
			'title' 	=>'Composition',
			'priority' 	=> 10,
			'callback' 	=> 'kasutan_new_product_tab_composition'
		);
	}

	if(!empty($producteur)) {
		$tabs['producteur'] = array(
			'title' 	=>'Producteur',
			'priority' 	=> 20,
			'callback' 	=> 'kasutan_new_product_tab_producteur'
		);
	}

	if(!empty($conseil)) {
		$tabs['conseil'] = array(
			'title' 	=>'Conseil de Kakou',
			'priority' 	=> 30,
			'callback' 	=> 'kasutan_new_product_tab_conseil'
		);
	}

	if(have_rows('kakou_avis')) {
		$tabs['avis'] = array(
			'title' 	=>'Avis',
			'priority' 	=> 40,
			'callback' 	=> 'kasutan_new_product_tab_avis'
		);
	}


	return $tabs;
}

function kasutan_new_product_tab_composition() {
	$ingredients=wp_kses_post(get_field('kakou_ingredients'));//zone de texte
	$allergenes=wp_kses_post(get_field('kakou_allergenes_2'));//zone de texte

	echo '<h2 class="screen-reader-text">Composition</h2>';

	if(!empty($ingredients)) {
		echo '<h3 class="titre-interne-onglet">Ingrédients</h3>';
		echo $ingredients;
	}

	if(!empty($allergenes)) {
		echo '<h3 class="titre-interne-onglet">Allergènes</h3>';
		echo $allergenes;
	}
	
}

function kasutan_new_product_tab_conseil() {
	$conseil=wp_kses_post(get_field('kakou_conseil'));//zone de texte

	echo '<h2 class="screen-reader-text">Conseil</h2>';
	echo $conseil;
}

function kasutan_new_product_tab_producteur() {
	$producteur=get_field('kakou_producteur'); //objet producteur
	echo '<h2 class="screen-reader-text">Producteur</h2>';
	printf('<h3 class="titre-interne-onglet">%s</h3>',$producteur->post_title);
	echo $producteur->post_content;
}

function kasutan_new_product_tab_avis() {
	while ( have_rows('kakou_avis') ) : the_row();
		echo '<blockquote>';
			echo wp_kses_post(get_sub_field('texte'));
			printf('<cite>%s</cite>',wp_kses_post(get_sub_field('auteur')));
		echo '</blockquote>';
	endwhile;
}
