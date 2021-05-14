<?php 
/**
* Template pour le bloc Nouveautés produits
*
* @param   array $block The block settings and attributes.
* @param   string $content The block inner HTML (empty).
* @param   bool $is_preview True during AJAX preview.
* @param   (int|string) $post_id The post ID this block is saved to.
*/


if(array_key_exists('className',$block)) {
	$className=esc_attr($block["className"]);
} else $className='';


$titre=wp_kses_post( get_field('titre') );

$produits=new WP_Query(array(
	'post_type' => 'product',
	'posts_per_page' => '4',
	'orderby' => 'date',
	'order' => 'DESC'
));



printf('<section class="acf nouveautes-produits %s">', $className);
	if(!$produits->have_posts(  )) {
		echo '<p>Aucun produit</p>';
		return;
	}

	if($titre) printf('<h2 class="h1">%s</h2>',$titre);

	echo '<ul class="list produits">';
	while ( $produits->have_posts() ) {
		$produits->the_post();
		wc_get_template_part( 'content', 'product' );
	}
	echo '</ul>';
	wp_reset_postdata();

echo '</section>';
	