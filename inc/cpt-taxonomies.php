<?php
if ( ! defined( 'ABSPATH' ) ) exit;


/***************************************************************
	Custom Taxonomy Type de producteur
/***************************************************************/

add_action( 'init', 'create_type_producteur_tag', 0 );
function create_type_producteur_tag() {
// Labels part for the GUI
$labels = array(
	'name' => _x( 'Types de producteurs', 'taxonomy general name' ),
	'singular_name' => _x( 'Type de producteur', 'taxonomy singular name' ),
	'menu_name' => __( 'Types de producteurs' ),
); 
register_taxonomy('type_producteur','producteur',array(
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
	Custom Post Type : producteur
/***************************************************************/
add_action( 'init', 'kasutan_producteur_post_type', 0 );
function kasutan_producteur_post_type() {

	$labels = array(
		'name'                  => _x( 'Producteurs', 'Post Type General Name', 'kakou' ),
		'singular_name'         => _x( 'Producteur', 'Post Type Singular Name', 'kakou' ),
		'menu_name'             => __( 'Producteurs', 'kakou' ),
		'name_admin_bar'        => __( 'Producteurs', 'kakou' ),
		'archives'              => __( 'Archives des producteurs', 'kakou' ),
		'attributes'            => __( 'Item Attributes', 'kakou' ),
		'parent_item_colon'     => __( 'Parent Item:', 'kakou' ),
		'all_items'             => __( 'Tous les producteurs', 'kakou' ),
		'add_new_item'          => __( 'Ajouter un producteur', 'kakou' ),
		'add_new'               => __( 'Ajouter un producteur', 'kakou' ),
		'new_item'              => __( 'Nouveau producteur', 'kakou' ),
		'edit_item'             => __( 'Modifier le producteur', 'kakou' ),
		'update_item'           => __( 'Mettre à jour le producteur', 'kakou' ),
		'view_item'             => __( 'Voir le producteur', 'kakou' ),
		'view_items'            => __( 'Voir les producteurs', 'kakou' ),
		'search_items'          => __( 'Rechercher un producteur', 'kakou' ),
		'not_found'             => __( 'Aucun producteur', 'kakou' ),
		'not_found_in_trash'    => __( 'Aucun producteur dans la corbeille', 'kakou' ),
		'featured_image'        => __( 'Image mise en avant sur la page Producteurs', 'kakou' ),
	);
	$args = array(
		'label'                 => __( 'Producteur', 'kakou' ),
		'description'           => __( 'Producteurs', 'kakou' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'revisions', 'editor', 'custom-fields', 'thumbnail' ),
		'taxonomies'            => array( 'type_producteur'),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 56,
		'menu_icon'             => 'dashicons-buddicons-replies',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'show_in_rest'          => false,
	);
	register_post_type( 'producteur', $args );

}

/***************************************************************
	Fonctions communes
/***************************************************************/
function kasutan_get_type_producteur($post_id) {
	$terms=get_the_terms($post_id,'type_producteur');
	if($terms) {
		return $terms[0]; //renvoie l'objet Term
	}
}