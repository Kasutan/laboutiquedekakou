<?php
/**
 * Page
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

// Breadcrumbs above page title
add_action( 'tha_entry_top', 'kasutan_fil_ariane', 8 );

// Image bannière au-dessus du titre de la page (titre inséré par la même action en priorité 10)
add_action( 'tha_entry_top', 'kasutan_page_banniere', 9 );

// Ajouter l'image si mise en page deux colonnes
add_action('tha_entry_content_before', 'kasutan_page_entry_content_before');
function kasutan_page_entry_content_before() {
	$layout=ea_page_layout();
	if($layout==='deux-colonnes' && function_exists('kasutan_affiche_thumbnail_dans_contenu')) {
		kasutan_affiche_thumbnail_dans_contenu();
	}
}

// Build the page
require get_template_directory() . '/index.php';


