<?php

/**
 * Bannière Newsletter
 *
 */
add_action( 'tha_footer_before', 'kasutan_affiche_banniere_newsletter' );
function kasutan_affiche_banniere_newsletter() {
	echo '<div class="banniere-newsletter" style="background:grey; padding:20px;height:100px">';
	echo '<p>Formulaire inscription newlsetter ici</p>';
	echo '</div>';
}

/**
 * Logo + menus Footer
 *
 */
add_action( 'tha_footer_top', 'kasutan_menus_footer' );
function kasutan_menus_footer() {
	echo '<div class="menus-footer">';
		printf('<div class="logo-footer">%s</div>',get_custom_logo());
		for($i=1;$i<=3;$i++) {
			if( has_nav_menu( 'footer-'.$i ) ) {
				wp_nav_menu( array( 'theme_location' => 'footer-'.$i, 'menu_id' => 'footer-'.$i, 'container_class' => 'nav-footer' ) );
			}
		}
		
	echo '</div>';
	echo '<a class="backtotop" href="#main-content"><span class="screen-reader-text">Retour en haut</span>' . kasutan_picto( array( 'icon' => 'arrow-up' ) ) . '</a>';
}


/**
* Afficher l'adresse de la boutique
*
*/
add_action('tha_footer_bottom','kasutan_affiche_coordonnees_boutique');
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


/**
* Copyright et liens légaux
*
*/
add_action( 'tha_footer_bottom', 'kasutan_copyright' );
function kasutan_copyright() {
	echo '<div class="copyright">';
		printf('<span>%s</span>',date('Y'));
		printf('<span>%s</span>',get_option('blogname'));
		echo ('<span><a href="https://banquise.com/" rel="noopener noreferrer" target="_blank">Site réalisé par 40 degrés sur la banquise</a></span>');
		if( has_nav_menu( 'footer-legal' ) ) {
			wp_nav_menu( array( 'theme_location' => 'footer-legal', 'menu_id' => 'footer-legal', 'container_class' => 'nav-footer-legal' ) );
		}
	echo '</div>';
}