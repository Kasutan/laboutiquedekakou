<?php
/**
 * Template Tags
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/


/**
* Entry Title
*
*/
function ea_entry_title() {
	if(!is_front_page()) {
		echo '<h1 class="entry-title">' . get_the_title() . '</h1>';
	}
}
add_action( 'tha_entry_top', 'ea_entry_title' );

/**
 * Entry Category
 *
 */
function ea_entry_category($contexte='archive') {
	$term = ea_first_term();
	if( !empty( $term ) && ! is_wp_error( $term ) )
		if($contexte==='archive') {
			echo '<p class="entry-category"><a href="' . get_term_link( $term, 'category' ) . '">' . $term->name . '</a></p>';
		} else {
			//contexte single
			echo '<p class="entry-category h1 entry-title">' . $term->name . '</p>';
		}
		
}

/**
 * Post Summary Title
 *
 */
function ea_post_summary_title() {
	global $wp_query;
	$tag = ( is_singular() || -1 === $wp_query->current_post ) ? 'h3' : 'h2';
	echo '<' . $tag . ' class="post-summary__title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></' . $tag . '>';
}

/**
 * Post Summary Image
 *
 */
function ea_post_summary_image( $size = 'thumbnail_medium' ) {
	echo '<a class="post-summary__image" href="' . get_permalink() . '" tabindex="-1" aria-hidden="true">' . wp_get_attachment_image( ea_entry_image_id(), $size ) . '</a>';
}


/**
 * Entry Image ID
 *
 */
function ea_entry_image_id() {
	return has_post_thumbnail() ? get_post_thumbnail_id() : get_option( 'options_ea_default_image' );
}

/**
 * Entry Author
 *
 */
function ea_entry_author() {
	$id = get_the_author_meta( 'ID' );
	echo '<p class="entry-author"><a href="' . get_author_posts_url( $id ) . '" aria-hidden="true" tabindex="-1">' . get_avatar( $id, 40 ) . '</a><em>by</em> <a href="' . get_author_posts_url( $id ) . '">' . get_the_author() . '</a></p>';
}

/**
* Fil d'ariane
*
*/
if ( ! function_exists( 'kasutan_fil_ariane' ) ) :
	/**
	* Affiche le fil d'ariane.
	*/
	function kasutan_fil_ariane() {

		//On n'affiche pas le fil d'ariane sur la page d'accueil
		if(!is_front_page()) :
			echo '<p class="fil-ariane">';

			//Afficher le lien vers l'accueil
			$accueil=get_option('page_on_front');
			printf('<a href="%s">%s</a> > ',
				get_the_permalink( $accueil),
				strip_tags(get_the_title($accueil))
			);

			//Afficher la page des actualités pour les articles (single ou archive de catégorie ou archive des articles ou archive de tag)
			if ( (is_single() && 'post' === get_post_type()) || is_category() || is_tag() || is_home() ) :
				//l'ID de la page est stockée dans les options ACF
				$actus=kasutan_get_page_ID('page_actualites'); 
				if($actus) :
					printf('<a href="%s">%s</a> > ',
						get_the_permalink( $actus),
						strip_tags(get_the_title($actus))
					);
				endif;
			endif;


			//Afficher le titre de la page courante
			if(is_page()) : 
				//Afficher le titre de la page parente s'il y en a une
				$current=get_post(get_the_ID());
				$parent=$current->post_parent; 
				if($parent) :
					printf('<span class="current">%s : %s</span>',
						strip_tags(get_the_title($parent)),
						strip_tags(get_the_title())
					);
				else :
					printf('<span class="current">%s</span>',
						strip_tags(get_the_title())
					);
				endif;
			elseif(is_single()): //single articles ou ressources
				printf('<span class="current">%s</span>',
					strip_tags(get_the_title())
				);
			elseif (is_category()) :  //archives catégories d'articles
				echo '<span class="current">'.strip_tags(single_cat_title( '', false )).'</span>';
			elseif (is_tag()) :  //archives tags d'articles
				echo '<span class="current">'.strip_tags(single_tag_title( '', false )).'</span>';
			elseif (is_home()) :
				echo '<span class="current">Tous les articles</span>';
			elseif (is_search()) :
				echo '<span class="current">Recherche : '.get_search_query().'</span>';
			elseif (is_404()) :
				echo '<span class="current">Page introuvable</span>';

			endif;

			//Fermer la balise du fil d'ariane
			echo '</p>';

		endif;
	}
endif;


/**
* Volet de recherche
*
*/
function kasutan_affiche_recherche($contexte='desktop') {
	if(!function_exists('kasutan_picto') ) {
		return;
	}
	//volet avec formulaire de recherche
	printf('<div class="volet-recherche %s" id="volet-recherche-%s" role="form" aria-expanded="false" >',$contexte,$contexte);
		get_search_form();
	echo '</div>';
	//bouton ouvrir/fermer volet de recherche
	printf('<div class="centreur"><button id="ouvrir-recherche-%s" aria-expanded="false" class="recherche picto" aria-controls="#volet-recherche-%s" aria-label="Ouvrir le formulaire de recherche">%s<span class="screen-reader-text">Ouvrir le formulaire de recherche</span></button></div>',
		$contexte,
		$contexte,
		kasutan_picto(array('icon'=>'loupe','size'=>'33'))
	);
}



/**
* Image banniere
*
*/
function kasutan_page_banniere() {
	if(!function_exists('get_field')) {
		return;
	}
	$image_id=esc_attr(get_field('kakou_page_banniere'));
	if(!empty($image_id)) {
		printf('<div class="page-banniere">%s</div>',wp_get_attachment_image( $image_id, 'banniere'));
	}
}

/**
* Image mise en avant
*
*/
function kasutan_affiche_thumbnail_dans_contenu() {
	if(has_post_thumbnail()) {
		echo '<div class="thumbnail">';
			the_post_thumbnail( 'medium');
		echo '</div>';
	}
}