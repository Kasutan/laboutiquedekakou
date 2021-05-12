<?php 
/**
* Template pour le bloc Section avec décors animés
*
* @param   array $block The block settings and attributes.
* @param   string $content The block inner HTML (empty).
* @param   bool $is_preview True during AJAX preview.
* @param   (int|string) $post_id The post ID this block is saved to.
*/

if(function_exists('get_field')) : 

	if(array_key_exists('className',$block)) {
		$className=esc_attr($block["className"]);
	} else $className='';

	$anchor = '';
	if( !empty( $block['anchor'] ) )
		$anchor = ' id="' . sanitize_title( $block['anchor'] ) . '"';

	$image_carree=esc_attr( get_field('image_carree') );
	$image_portrait=esc_attr( get_field('image_portrait') );
	$titre_2=esc_html( get_field('titre_2') );
	$titre_3=esc_html( get_field('titre_3') );
	$texte=wp_kses_post(get_field('texte'));
	$label_bouton=esc_html( get_field('label_bouton') );
	$cible_bouton=esc_url( get_field('cible_bouton') );
	$onglet_bouton=esc_attr( get_field('onglet_bouton') );
	$target_attr='';
	if($onglet_bouton==='blank') {
		$target_attr=' target="_blank" rel="noopener noreferrer" title="Ouvre un nouvel onglet"';
	}

	printf('<section class="section-animee acf %s"%s>',$className,$anchor);
		
		if($titre_2) printf('<h2 class="titre-section h1">%s</h2>',$titre_2);

		echo '<div class="decors-wrap">';
			
			if($image_carree || $image_portrait) {
				printf('<div class="col-images">%s%s</div>',
					wp_get_attachment_image( $image_portrait, 'medium',false,array('class'=>'portrait')),
					wp_get_attachment_image( $image_carree, 'woocommerce_thumbnail',false,array('class'=>'carre')),
				);
			}

			//Les décors sont affichés au-dessus des images mais sous le texte
			for($i=1;$i<=6;$i++) {
				printf('<img class="decor decor-%s" src="%s/icons/decor-%s.png" alt="" />',
					$i,
					get_stylesheet_directory_uri(  ),
					$i
				);	
			}

			echo '<div class="col-texte">';
				if ($titre_3) printf('<h3 class="titre">%s</h3>',$titre_3);
				if($texte) printf('<div class="texte">%s</div>',$texte);
				if($cible_bouton && $label_bouton) {
					printf('<a href="%s" class="button"%s>%s</a>',$cible_bouton,$target_attr,$label_bouton);
				}
			echo '</div>'; //fin .texte

		echo '</div>'; //fin .decors

	echo '</section>';


endif;