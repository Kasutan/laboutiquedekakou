<?php
if ( ! defined( 'ABSPATH' ) ) exit;


/***************************************************************
	Custom Taxonomy Type de ressource
/***************************************************************/

//add_action( 'init', 'create_type_ressource_tag', 0 );
function create_type_ressource_tag() {
// Labels part for the GUI
$labels = array(
	'name' => _x( 'Types de ressource', 'taxonomy general name' ),
	'singular_name' => _x( 'Type de ressource', 'taxonomy singular name' ),
	'menu_name' => __( 'Types de ressource' ),
); 
register_taxonomy('type_ressource','ressource',array(
	'hierarchical' => true,
	'labels' => $labels,
	'show_ui' => true, 
	'show_admin_column' => true,
	'query_var' => false,
	'public' => false,
	'show_in_rest' => false
));
}




/***************************************************************
	Custom Post Type : ressource
/***************************************************************/
//add_action( 'init', 'kasutan_ressource_post_type', 0 );
function kasutan_ressource_post_type() {

	$labels = array(
		'name'                  => _x( 'Ressources', 'Post Type General Name', 'kakou' ),
		'singular_name'         => _x( 'Ressource', 'Post Type Singular Name', 'kakou' ),
		'menu_name'             => __( 'Ressources', 'kakou' ),
		'name_admin_bar'        => __( 'Ressources', 'kakou' ),
		'archives'              => __( 'Archives des ressources', 'kakou' ),
		'attributes'            => __( 'Item Attributes', 'kakou' ),
		'parent_item_colon'     => __( 'Parent Item:', 'kakou' ),
		'all_items'             => __( 'Toutes les ressources', 'kakou' ),
		'add_new_item'          => __( 'Ajouter une ressource', 'kakou' ),
		'add_new'               => __( 'Ajouter', 'kakou' ),
		'new_item'              => __( 'Nouvelle ressource', 'kakou' ),
		'edit_item'             => __( 'Modifier la ressource', 'kakou' ),
		'update_item'           => __( 'Mettre à jour la ressource', 'kakou' ),
		'view_item'             => __( 'Voir la ressource', 'kakou' ),
		'view_items'            => __( 'Voir les ressources', 'kakou' ),
		'search_items'          => __( 'Rechercher une ressource', 'kakou' ),
		'not_found'             => __( 'Aucune ressource', 'kakou' ),
		'not_found_in_trash'    => __( 'Aucune ressource dans la corbeille', 'kakou' ),
	);
	$args = array(
		'label'                 => __( 'Ressource', 'kakou' ),
		'description'           => __( 'Ressources publiques ou réservées aux adhérents', 'kakou' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'revisions', 'editor', 'custom-fields' ),
		'taxonomies'            => array( 'type_ressource'),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-index-card',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'show_in_rest'          => false,
	);
	register_post_type( 'ressource', $args );

}

/***************************************************************
	Fonctions communes
/***************************************************************/
function kasutan_get_type_evenement($post_id) {
	$terms=get_the_terms($post_id,'type_evement');
	if($terms) {
		return $terms[0]; //renvoie l'objet Term
	}
}