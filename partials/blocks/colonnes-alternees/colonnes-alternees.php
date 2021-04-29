<?php 
/**
* Template pour le bloc Colonnes alternées
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


	$image=esc_attr( get_field('image') );
	$titre=esc_html( get_field('titre') );
	$texte=wp_kses_post(get_field('texte'));
	$label_bouton=esc_html( get_field('label_bouton') );
	$cible_bouton=esc_url( get_field('cible_bouton') );
	$onglet_bouton=esc_attr( get_field('onglet_bouton') );

	$target_attr='';
	if($onglet_bouton==='blank') {
		$target_attr=' target="_blank" rel="noopener noreferrer" title="Ouvre un nouvel onglet"';
	}

	printf('<section class="colonnes-alternees acf %s"%s>',$className,$anchor);
		printf('<div class="col-image">%s</div>',wp_get_attachment_image( $image, 'large'));
		echo '<div class="col-texte">';
			printf('<h2 class="titre">%s</h2>',$titre);
			printf('<div class="texte">%s</div>',$texte);
			if($cible_bouton && $label_bouton) {
				printf('<a href="%s" class="button"%s>%s</a>',$cible_bouton,$target_attr,$label_bouton);
			}
		echo '</div>';
	echo '</section>';

endif;