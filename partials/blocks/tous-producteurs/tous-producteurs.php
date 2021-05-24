<?php 
/**
* Template pour le bloc Tous producteurs
*
* @param   array $block The block settings and attributes.
* @param   string $content The block inner HTML (empty).
* @param   bool $is_preview True during AJAX preview.
* @param   (int|string) $post_id The post ID this block is saved to.
*/


if(array_key_exists('className',$block)) {
	$className=esc_attr($block["className"]);
} else $className='';


$filtre=esc_attr( get_field('afficher_filtre') );
$pagination=esc_attr( get_field('pagination') );

$producteurs=get_posts(array(
	'post_type' => 'producteur',
	'numberposts' => '-1',
	'orderby' => 'menu_order',
	'order' => 'ASC'
));

if(empty($producteurs)) {
	echo '<p>Aucun producteur</p>';
	return;
}

printf('<section id="liste-filtrable" class="acf-producteurs %s" data-pagination="%s">', $className,$pagination);
	if($filtre && function_exists('kasutan_affiche_filtre_taxonomy')) {
		kasutan_affiche_filtre_taxonomy('type_producteur');
	}

	if(function_exists('kasutan_affiche_bloc_deux_colonnes_alternees') 
		&& function_exists('kasutan_types_producteurs_string')) {
		printf('<ul class="list producteurs">');
		foreach($producteurs as $producteur) {
			$producteur_id=$producteur->ID;

			$type_producteur_string=kasutan_types_producteurs_string($producteur_id);

			kasutan_affiche_bloc_deux_colonnes_alternees(array(
				'balise' => 'li',
				'titre' => get_the_title($producteur_id),
				'texte' => $producteur->post_content,
				'image' => get_post_thumbnail_id( $producteur_id),
				'type_producteur' => $type_producteur_string
			));
		}

		echo '</ul>';
		echo '<ul class="pagination"></ul>';
	}
echo '</section>';
	