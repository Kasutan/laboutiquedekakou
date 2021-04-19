<?php 
function kasutan_bloc_visuels($block) {
	if(array_key_exists('className',$block)) {
		$className=esc_attr($block["className"]);
	} else $className='';
	$anchor = '';
	if( !empty( $block['anchor'] ) )
		$anchor = ' id="' . sanitize_title( $block['anchor'] ) . '"';

	$texte=esc_html( get_field('texte') );
	$sources=esc_html( get_field('sources') );

	if( have_rows('visuels') ):
		printf('<div class="acf-visuels %s"%s>', $className,$anchor);

		// loop through the rows of data
		while ( have_rows('visuels') ) : the_row();
			if($cible=esc_url(get_sub_field('cible'))) {
				//le visuel est cliquable
				printf('<a class="visuel" href="%s"><figure>%s<figcaption class="screen-reader-text">%s</figcaption></figure><span class="bouton">%s</span></a>',
					$cible,
					wp_get_attachment_image( esc_attr(get_sub_field('image')),'large' ),
					wp_get_attachment_caption(esc_attr(get_sub_field('image'))),
					$texte
				);
			} else {
				//le visuel n'est pas cliquable
				printf('<div class="visuel"><figure>%s<figcaption class="screen-reader-text">%s</figcaption></figure></div>',
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
}
	