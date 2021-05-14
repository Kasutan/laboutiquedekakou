<?php 
/**
* Template pour le bloc Blog
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

$articles=new WP_Query(array(
	'post_type' => 'post',
	'posts_per_page' => '2',
	'orderby' => 'date',
	'order' => 'DESC'
));



printf('<section class="acf blog %s">', $className);
	if(!$articles->have_posts(  )) {
		echo '<p>Aucun article</p>';
		return;
	}

	if($titre) printf('<h2 class="h1">%s</h2>',$titre);

	echo '<ul class="loop js-fade-in-on-visible">';
	while ( $articles->have_posts() ) {
		$articles->the_post();
		get_template_part( 'partials/archive');
	}
	echo '</ul>';
	wp_reset_postdata();

echo '</section>';
	