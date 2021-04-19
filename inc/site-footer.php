<?php

/**
* Afficher l'adresse de la boutique
*
*/
function kasutan_affiche_coordonnees_boutique() {
	$tel=$adresse_ligne_2='';

	echo '<div class="coordonnees-boutique">';
		echo '<div class="col-1">';
			echo '<h2>Contactez-nous</h2>';
			if (kasutan_is_woo_active()) {
				$adresse_ligne_2=WC()->countries->get_base_address_2();
				echo '<p class="adresse-boutique">';
					printf('<strong>%s</strong></br>',get_option('blogname'));
					echo WC()->countries->get_base_address().'</br>';
					if($adresse_ligne_2) {
						echo $adresse_ligne_2.'</br>';
					}
					echo WC()->countries->get_base_postcode().' ';
					echo WC()->countries->get_base_city();
				echo '</p>';
			} 
			
			if(function_exists('get_field')) {
				$tel=esc_attr(get_field('kakou_telephone','option'));
				if(!empty($tel)) {
					$tel_link='tel:+33'.str_replace(' ','',$tel);
					printf('<a href="%s" class="tel">%s</a>',$tel_link,$tel);
				}
			}
		echo '</div>';//fin .col-1 

		if(function_exists('kasutan_social_links')) {
			echo kasutan_social_links();
		}

	echo '</div>';
	
}
add_action('tha_footer_bottom','kasutan_affiche_coordonnees_boutique');

/**
 * Register footer widget areas
 *
 */
function ea_register_footer_widget_areas() {

	for( $i = 1; $i <=3; $i++ ) {

		register_sidebar( ea_widget_area_args( array(
			'name' => esc_html__( 'Footer ' . $i, 'ea-starter' ),
		) ) );
	}

}
add_action( 'widgets_init', 'ea_register_footer_widget_areas' );


/**
 * Footer Widget Areas
 *
 */
function ea_site_footer_widgets() {
	echo '<div class="footer-widgets"><div class="wrap">';
	for( $i = 1; $i < 4; $i++ ) {
		dynamic_sidebar( 'footer-' . $i );
	}
	echo '</div></div>';
}
add_action( 'tha_footer_before', 'ea_site_footer_widgets' );

/**
 * Site Footer
 *
 */
function ea_site_footer() {
	echo '<div class="footer-left">';
		echo '<p class="copyright">Copyright &copy; ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ) . '®. All Rights Reserved.</p>';
		echo '<p class="footer-links"><a href="' . home_url( 'privacy-policy' ) . '">Privacy Policy</a> <a href="' . home_url( 'terms' ) . '">Terms</a></p>';
		echo '<p class="cafemedia">An Elite Cafemedia Food Publisher</p>';
	echo '</div>';
	echo '<a class="backtotop" href="#main-content">Back to top' . kasutan_picto( array( 'icon' => 'arrow-up' ) ) . '</a>';
}
add_action( 'tha_footer_top', 'ea_site_footer' );
