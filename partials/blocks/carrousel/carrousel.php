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

	if(have_rows('slides')) : 
		printf('<section class="carrousel %s owl-carousel js-fade-in-on-visible">',$className);
		while ( have_rows('slides') ) : the_row();
			$image=esc_attr( get_sub_field('image') );
			$texte=wp_kses_post(get_sub_field('texte'));
			$label=wp_kses_post( get_sub_field('label') );
			$cible=esc_url( get_sub_field('cible') );
			echo '<div class="slide">';
				printf('<div class="image">%s</div>',wp_get_attachment_image( $image, 'banniere',false,array('decoding'=>'async')));
				printf('<div class="texte">%s',wpautop($texte));
				if(!empty($cible) && !empty($label)) {
					printf('<a class="button" href="%s">%s</a>',$cible,$label);
				}
			echo '</div></div>';
		endwhile;
		echo '</section>';
	endif;

endif;