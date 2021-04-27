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

printf('<section class="acf-producteurs %s">', $className);
	if($filtre && function_exists('kasutan_affiche_filtre_taxonomy')) {
		kasutan_affiche_filtre_taxonomy('type_producteur');
	}

	if(function_exists('kasutan_affiche_bloc_deux_colonnes_alternees')) {
		printf('<ul class="list producteurs" data-pagination="%s">',$pagination);
		foreach($producteurs as $producteur) {
			$producteur_id=$producteur->ID;
			$type_producteur=ea_first_term(
				array(
					'taxonomy'	=> 'type_producteur',
					'field'		=> 'slug',
					'post_id'	=> $producteur_id,
				)
			);
			kasutan_affiche_bloc_deux_colonnes_alternees(array(
				'balise' => 'li',
				'titre' => get_the_title($producteur_id),
				'texte' => $producteur->post_content,
				'image' => get_post_thumbnail_id( $producteur_id),
				'type_producteur' => $type_producteur
			));
		}

		echo '<ul>';
	}

if( have_rows('producteurs') ):
	printf('<div class="acf-producteurs %s"%s>', $className,$anchor);

	// loop through the rows of data
	while ( have_rows('producteurs') ) : the_row();
		if($cible=esc_url(get_sub_field('cible'))) {
			//le producteur est cliquable
			printf('<a class="producteur" href="%s"><figure>%s<figcaption class="screen-reader-text">%s</figcaption></figure><span class="bouton">%s</span></a>',
				$cible,
				wp_get_attachment_image( esc_attr(get_sub_field('image')),'large' ),
				wp_get_attachment_caption(esc_attr(get_sub_field('image'))),
				$texte
			);
		} else {
			//le producteur n'est pas cliquable
			printf('<div class="producteur"><figure>%s<figcaption class="screen-reader-text">%s</figcaption></figure></div>',
				wp_get_attachment_image( esc_attr(get_sub_field('image')),'large' ),
				wp_get_attachment_caption(esc_attr(get_sub_field('image')))
			);
		}

	endwhile;
	if($sources) {
		printf('<a href="%s"><small>Sources</small></a>',$sources);
	}
	echo "</div>";
endif;

	