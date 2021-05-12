<?php 
/**
* Template pour le bloc Catégories produits
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

	if(have_rows('categories')) : 
		$nb=count( get_field( 'categories' ));
		printf('<section class="categories-produits col-%s %s js-cascade-on-visible">',$nb, $className);
		while ( have_rows('categories') ) : the_row();
			$image=esc_attr( get_sub_field('image') );
			$term=get_sub_field('categorie');
			
			echo '<div class="categorie">';
				printf('<div class="image">%s</div>',wp_get_attachment_image( $image, 'medium_large'));
				printf('<a class="texte" href="%s">%s</a>',get_term_link($term,'product_cat'),$term->name);
			echo '</div>';
		endwhile;
		echo '</section>';
	endif;

endif;