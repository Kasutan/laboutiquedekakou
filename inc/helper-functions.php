<?php
/**
 * Helper Functions
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

// Duplicate 'the_content' filters
global $wp_embed;
add_filter( 'ea_the_content', array( $wp_embed, 'run_shortcode' ), 8 );
add_filter( 'ea_the_content', array( $wp_embed, 'autoembed'     ), 8 );
add_filter( 'ea_the_content', 'wptexturize'        );
add_filter( 'ea_the_content', 'convert_chars'      );
add_filter( 'ea_the_content', 'wpautop'            );
add_filter( 'ea_the_content', 'shortcode_unautop'  );
add_filter( 'ea_the_content', 'do_shortcode'       );

/**
 * Get the first term attached to post
 *
 * @param string $taxonomy
 * @param string/int $field, pass false to return object
 * @param int $post_id
 * @return string/object
 */
function ea_first_term( $args = [] ) {

	$defaults = [
		'taxonomy'	=> 'category',
		'field'		=> null,
		'post_id'	=> null,
	];

	$args = wp_parse_args( $args, $defaults );

	$post_id = !empty( $args['post_id'] ) ? intval( $args['post_id'] ) : get_the_ID();
	$field = !empty( $args['field'] ) ? esc_attr( $args['field'] ) : false;
	$term = false;

	// Use WP SEO Primary Term
	// from https://github.com/Yoast/wordpress-seo/issues/4038
	if( class_exists( 'WPSEO_Primary_Term' ) ) {
		$term = get_term( ( new WPSEO_Primary_Term( $args['taxonomy'],  $post_id ) )->get_primary_term(), $args['taxonomy'] );
	}

	// Fallback on term with highest post count
	if( ! $term || is_wp_error( $term ) ) {

		$terms = get_the_terms( $post_id, $args['taxonomy'] );

		if( empty( $terms ) || is_wp_error( $terms ) )
			return false;

		// If there's only one term, use that
		if( 1 == count( $terms ) ) {
			$term = array_shift( $terms );

		// If there's more than one...
		} else {

			// Sort by term order if available
			// @uses WP Term Order plugin
			if( isset( $terms[0]->order ) ) {
				$list = array();
				foreach( $terms as $term )
					$list[$term->order] = $term;
				ksort( $list, SORT_NUMERIC );

			// Or sort by post count
			} else {
				$list = array();
				foreach( $terms as $term )
					$list[$term->count] = $term;
				ksort( $list, SORT_NUMERIC );
				$list = array_reverse( $list );
			}

			$term = array_shift( $list );
		}
	}

	// Output
	if( !empty( $field ) && isset( $term->$field ) )
		return $term->$field;

	else
		return $term;
}

/**
 * Conditional CSS Classes
 *
 * @param string $base_classes, classes always applied
 * @param string $optional_class, additional class applied if $conditional is true
 * @param bool $conditional, whether to add $optional_class or not
 * @return string $classes
 */
function ea_class( $base_classes, $optional_class, $conditional ) {
	return $conditional ? $base_classes . ' ' . $optional_class : $base_classes;
}

/**
 *  Background Image Style
 *
 * @param int $image_id
 * @return string $output
 */
function ea_bg_image_style( $image_id = false, $image_size = 'full' ) {
	if( !empty( $image_id ) )
		return ' style="background-image: url(' . wp_get_attachment_image_url( $image_id, $image_size ) . ');"';
}

/**
 * Get Icon
 * This function is in charge of displaying SVG icons across the site.
 *
 * Place each <svg> source in the /assets/icons/ directory, without adding
 * both `width` and `height` attributes, since these are added dynamically,
 * before rendering the SVG code.
 *
 * All icons are assumed to have equal width and height, hence the option
 * to only specify a `$size` parameter in the svg methods.
 *
 */
function kasutan_picto( $atts = array() ) {

	$atts = shortcode_atts( array(
		'icon'	=> false,
		'size'	=> 16,
		'class'	=> false,
		'label'	=> false,
	), $atts );

	if( empty( $atts['icon'] ) )
		return;

	$icon_path = get_theme_file_path( '/icons/' . $atts['icon'] . '.svg' );
	if( ! file_exists( $icon_path ) )
		return;

		$icon = file_get_contents( $icon_path );

		$class = 'svg-icon';
		if( !empty( $atts['class'] ) )
			$class .= ' ' . esc_attr( $atts['class'] );

		if( false !== $atts['size'] ) {
			$repl = sprintf( '<svg class="' . $class . '" width="%d" height="%d" aria-hidden="true" role="img" focusable="false" ', $atts['size'], $atts['size'] );
			$svg  = preg_replace( '/^<svg /', $repl, trim( $icon ) ); // Add extra attributes to SVG code.
		} else {
			$svg = preg_replace( '/^<svg /', '<svg class="' . $class . '"', trim( $icon ) );
		}
		$svg  = preg_replace( "/([\n\t]+)/", ' ', $svg ); // Remove newlines & tabs.
		$svg  = preg_replace( '/>\s*</', '><', $svg ); // Remove white space between SVG tags.

		if( !empty( $atts['label'] ) ) {
			$svg = str_replace( '<svg class', '<svg aria-label="' . esc_attr( $atts['label'] ) . '" class', $svg );
			$svg = str_replace( 'aria-hidden="true"', '', $svg );
		}

		return $svg;
}

/**
 * Has Action
 *
 */
function ea_has_action( $hook ) {
	ob_start();
	do_action( $hook );
	$output = ob_get_clean();
	return !empty( $output );
}

/***************************************************************
			Vérifier si WooCommerce est activé
***************************************************************/
function kasutan_is_woo_active() {
	$active_plugins=apply_filters( 'active_plugins', get_option( 'active_plugins' ));
	return in_array('woocommerce/woocommerce.php', $active_plugins);
}


/***************************************************************
			Afficher l'adresse mail via un shortcode
***************************************************************/

function mc_adresse_email($atts) {
	extract( shortcode_atts( array(    
		'mail' => ' ',    
		), $atts) );
	
			return sprintf('<a href="mailto:%s">%s</a>',antispambot($mail),antispambot($mail));
		}
		
add_shortcode( 'adresse-email', 'mc_adresse_email' );



/***************************************************************
	Affiche l'image banniere d'un post ou d'une page
/***************************************************************/
if ( ! function_exists( 'kasutan_post_thumbnail' ) ) :

	function kasutan_post_thumbnail($taille='banniere') {
		ob_start();
		$defaut='';
		if(function_exists('get_field')) {
			$defaut=esc_attr(get_field('banniere_defaut','option'));
		}
		if(has_post_thumbnail(  )) {
			the_post_thumbnail( $taille);
		} else {
			if($defaut) {
				echo wp_get_attachment_image( $defaut, $taille);
			}
		}
		return ob_get_clean();
	}

endif;

/***************************************************************
	Affiche l'image banniere d'une archive
/***************************************************************/
if ( ! function_exists( 'kasutan_archive_thumbnail' ) ) :

	function kasutan_archive_thumbnail($taille='banniere',$term_id=null) {
		$image_id='';
		if(!function_exists('get_field')) {
			return '';
		}
		if(is_category() && $term_id) {
			$image_id=esc_attr(get_field('image','term_'.$term_id));
			return wp_get_attachment_image( $image_id, $taille);
		} else {
			$actus=kasutan_get_page_ID('page_actualites'); 
			if($actus) :
				return get_the_post_thumbnail($actus,$taille);
			endif;
		}

	}

endif;



/**
* Récupérer l'ID d'une page - stockée dans une option ACF.
*/

function kasutan_get_page_ID($nom) {
	if (!function_exists('get_field')) {
		return;
	}

	$page=get_field($nom,'option');

	return $page;
}

/***************************************************************
	Affiche le fil d'ariane
/***************************************************************/
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
* Afficher le volet de recherche dans l'en-tête
*/
function kasutan_affiche_volet_recherche() {
	if(!function_exists('kasutan_picto') ) {
		return;
	}
	echo '<div class="volet-header recherche" id="volet-recherche" role="form" aria-expanded="false" >';
		echo kasutan_picto(array('icon'=>'loupe'));
		echo '<p class="h2">Rechercher un contenu</p>';
		get_search_form();
		printf('<button id="fermer-recherche" class="fermer"><span>Fermer</span>%s',kasutan_picto(array('icon'=>'croix-blanc')));
	echo '</div>';
}