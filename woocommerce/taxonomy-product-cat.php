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

get_header();

	echo '<div class="' . ea_class( 'content-area', 'wrap', apply_filters( 'ea_content_area_wrap', true ) ) . '">';
	echo '<main class="site-main" role="main">';
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
			echo '<p>filtre produits ici</p>';
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

	echo '</main>';
	echo '</div>';

get_footer();