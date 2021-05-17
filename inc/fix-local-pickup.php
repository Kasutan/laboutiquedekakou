<?php 
//https://wordpress.org/support/topic/hide-if-shipping-method-isnt-pickup/

// JavaScript pour masquer/afficher le sélecteur de créneau selon la méthode de livraison choisie
add_action( 'wp_footer', 'kasutan_conditionally_hidding_pickuptime' );
function kasutan_conditionally_hidding_pickuptime(){
	// Only on checkout page
	if( ! is_checkout() ) return;

	$chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
	$chosen_shipping = $chosen_methods[0];
	?>
	<script>
		jQuery(function($){
			// Choosen shipping method selectors slug
			var shipMethod = 'input[name^="shipping_method"]',
				shipMethodChecked = shipMethod+':checked';

			// Function that shows or hide imput select fields
			function showHide( actionToDo='show', selector='' ){
				if( actionToDo == 'show' )
					$(selector).show( 200, function(){
						$(this).addClass("validate-required");
					});
				else
					$(selector).hide( 200, function(){
						$(this).removeClass("validate-required");
					});
			}

			// Initialising: Hide if choosen shipping method is "Local Pickup"
			<?php if ( 0 === strpos( $chosen_shipping, 'local_pickup' ) ) { ?>
			showHide('show','#local-pickup-time-select' );
			<?php  
} 
			else{
			?>
			showHide('hide','#local-pickup-time-select' );	
			<?php
			}
			?>

			//Déplacer le champ pour le mettre juste en dessous du choix des méthodes de livraison
			$('.woocommerce-checkout-review-order-table').after($('#local-pickup-time-select'));

			//Masquer le titre
			$('#local-pickup-time-select h2').hide();
      

			// Live event (When shipping method is changed)
			$( 'form.checkout' ).on( 'change', shipMethod, function() {
				if( $(shipMethodChecked).val().indexOf('local_pickup') >=0){
					showHide('show','#local-pickup-time-select' );
				}
				else{
					showHide('hide','#local-pickup-time-select' );
				}
			});
		});
	</script>
	<?php
}


// Retirer la validation systématique prévue dans le plugin 
// add_action( 'woocommerce_checkout_process', array( $this, 'field_process' ) );
// dans le fichier public/class-local-pickup-time.php 
if ( class_exists( 'Local_Pickup_Time' ) ) {
	remove_action( 'woocommerce_checkout_process', array( Local_Pickup_Time::get_instance(), 'field_process') );
}

// Rétablir la validation PHP : choix du créneau obligatoire mais seulement si la méthode choisie contient local_pickup
add_action( 'woocommerce_after_checkout_validation', 'kasutan_validate_pickup', 10, 2);
function kasutan_validate_pickup( $fields, $errors ){
	$chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
	$chosen_shipping = $chosen_methods[0];
	if ( 0 === strpos( $chosen_shipping, 'local_pickup' ) ) {
		if ( empty($_POST[ 'local_pickup_time_select' ] ) ){
			$errors->add( 'validation', 'Merci de choisir un créneau de retrait à la boutique.' );
		}
	}
}


// Empêcher le pludgin d'ajouter systématiquement le créneau de retrait aux emails (en plus il écrasait toutes les autres métas)
// add_filter( 'woocommerce_email_order_meta_fields', array( $this, 'update_order_email_fields' ), 10, 3 );
// dans le fichier public/class-local-pickup-time.php 
if ( class_exists( 'Local_Pickup_Time' ) ) {
	remove_filter( 'woocommerce_email_order_meta_fields', array( Local_Pickup_Time::get_instance(), 'update_order_email_fields') );
}

// Ajouter le créneau de retrait seulement aux emails seulement s'il y en a un 
add_filter('woocommerce_email_order_meta_fields','kasutan_email_order_meta_fields',20,3);
function kasutan_email_order_meta_fields( $fields, $sent_to_admin, $order ) {
	$key='_local_pickup_time_select';
	$value = get_post_meta($order->get_id(),$key, true );
	error_log('creneau timestamp : '.$value);
	if($value!=="Aucun") {
		$creneau=date('d/m/Y à H:i',$value);
		error_log('creneau formaté : '.$creneau);
		$fields[$key] = array(
			'label' => 'Rendez-vous est pris pour le retrait à la date du ',
			'value' => $creneau,
		);
	}
	return $fields;
}