<?php
/**
 * The Template for displaying products in a product category. Simply includes the archive template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/taxonomy-product-cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     4.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$queried_object=get_queried_object(  );

//Rediriger vers la page de la catégorie parente s'il y en a une (avec paramètre dans l'url pour activer le filtre)
$ancestors=array_reverse(get_ancestors($queried_object->term_id,'product_cat'));
if(!empty($ancestors)) {
	$lien=get_term_link( $ancestors[0],'product_cat' ).'?filtre_cat='.$queried_object->slug;
	wp_redirect( $lien );
	exit;
} 

get_header();

	echo '<div class="' . ea_class( 'content-area', 'wrap', apply_filters( 'ea_content_area_wrap', true ) ) . '">';
	echo '<main class="site-main" role="main">';
	if(function_exists('kasutan_fil_ariane')) {
		kasutan_fil_ariane();
	}
	echo'<div class="entry-content">';
	$queried_object=get_queried_object(  );
	$args=array( 
		'post_type' => 'product',
		'posts_per_page'=>-1,
		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $queried_object->slug,
			),
		),
	);
	$products=new WP_Query($args);
	if ( $products->have_posts() ) {
		echo '<section id="liste-filtrable" data-pagination="8">';

			if(function_exists('kasutan_fil_ariane')) {
				kasutan_affiche_filtre_taxonomy('product_cat',$queried_object->term_id);
			}
		
			echo '<ul class="list produits">';
			while ( $products->have_posts() ) {
				$products->the_post();
				wc_get_template_part( 'content', 'product' );

			}
			echo '</ul>';
			echo '<ul class="pagination"></ul>';
		echo '</section>';
	} else {
		echo '<p>Aucun produit dans cette catégorie.</p>';
	}
	/* Restore original Post Data */
	wp_reset_postdata();

	echo '</div></main>';
	echo '</div>';

get_footer();