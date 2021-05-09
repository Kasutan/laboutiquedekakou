<?php 
/**
* Template pour le bloc Réassurance
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

	if(have_rows('arguments')) : 
		printf('<section class="reassurance %s">',$className);
		while ( have_rows('arguments') ) : the_row();
			$picto=esc_attr( get_sub_field('picto') );
			$texte=wp_kses_post(get_sub_field('texte'));
			
			echo '<div class="argument">';
				printf('<div class="picto">%s</div>',wp_get_attachment_image( $picto, 'thumbnail'));
				printf('<div class="texte">%s</div>',$texte);
			echo '</div>';
		endwhile;
		echo '</section>';
	endif;

endif;