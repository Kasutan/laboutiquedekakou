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

// Remplacer la fonction du plugin qui enregistre la meta avec le timestamp du créneau de livraison (pb de nonce quand le client n'est pas connecté)
// add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'update_order_meta' ) );
// dans le fichier public/class-local-pickup-time.php
if ( class_exists( 'Local_Pickup_Time' ) ) {
	remove_action( 'woocommerce_checkout_update_order_meta', array( Local_Pickup_Time::get_instance(), 'update_order_meta') );
}

//Nouvelle fonction pour enregistrer la meta avec le timestamp du créneau de livraison
add_action( 'woocommerce_checkout_update_order_meta', 'kasutan_update_order_meta' );
function kasutan_update_order_meta( $order_id ) {
	//propriétés protégées de la classe Local_Pickup_Time
	// dans le fichier public/class-local-pickup-time.php
	$order_post_key = 'local_pickup_time_select'; 
	$order_meta_key = '_local_pickup_time_select';

	// Update the order pickup time if set.
	$value=esc_attr(wc_get_post_data_by_key( $order_post_key ));
	if ( ! empty( $value ) && kasutan_isValidTimeStamp($value) ) {
		update_post_meta( $order_id, $order_meta_key, $value );
	} else {
		error_log('Créneau de livraison vide ou non valide : '.$value);
	}
}

//https://stackoverflow.com/questions/2524680/check-whether-the-string-is-a-unix-timestamp
function kasutan_isValidTimeStamp($timestamp)
{
	return ((string) (int) $timestamp === $timestamp) 
		&& ($timestamp <= PHP_INT_MAX)
		&& ($timestamp >= ~PHP_INT_MAX);
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


// Empêcher le plugin d'ajouter systématiquement le créneau de retrait aux emails (en plus il écrasait toutes les autres métas)
// add_filter( 'woocommerce_email_order_meta_fields', array( $this, 'update_order_email_fields' ), 10, 3 );
// dans le fichier public/class-local-pickup-time.php 
if ( class_exists( 'Local_Pickup_Time' ) ) {
	remove_filter( 'woocommerce_email_order_meta_fields', array( Local_Pickup_Time::get_instance(), 'update_order_email_fields') );
}

// Ajouter le créneau de retrait seulement aux emails seulement s'il y en a un 
add_filter('woocommerce_email_order_meta_fields','kasutan_email_order_meta_fields',20,3);
function kasutan_email_order_meta_fields( $fields, $sent_to_admin, $order ) {
	if ( !class_exists( 'Local_Pickup_Time' ) ) {
		return $fields;
	}

	$key='_local_pickup_time_select';
	$value = get_post_meta($order->get_id(),$key, true );
	if(!empty($value) && $value!=="Aucun") {

		// Get an instance of the Public plugin.
		$plugin = Local_Pickup_Time::get_instance();
		//Utiliser la méthode de formatage du plugin pour avoir le bon fuseau horaire
		$creneau=$plugin->pickup_time_select_translatable( $value );

		$fields[$key] = array(
			'label' => 'Rendez-vous est pris pour le retrait à la date du ',
			'value' => $creneau,
		);
	}
	return $fields;
}


// Empêcher le plugin d'ajouter systématiquement le créneau de retrait aux détails de la commande
// add_action( $admin_hooked_location, array( $this, 'show_metabox' ), 10, 1 );
// dans le fichier plugins\woocommerce-local-pickup-time-select\admin\class-local-pickup-time-admin.php
if ( class_exists( 'Local_Pickup_Time_Admin' ) ) {
	$admin_hooked_location = apply_filters( 'local_pickup_time_admin_location', 'woocommerce_admin_order_data_after_billing_address' );

	//Décrocher l'action du plugin
	remove_action($admin_hooked_location, array( Local_Pickup_Time_Admin::get_instance(), 'show_metabox'),10 );

	//Raccrocher mon action
	add_action($admin_hooked_location,'kasutan_affiche_creneau_commande_admin',10,1);
}

// Ajouter le créneau de retrait dans l'écran d'édition de la commande seulement s'il y en a un 
function kasutan_affiche_creneau_commande_admin($order) {
	if ( !class_exists( 'Local_Pickup_Time' ) ) {
		return;
	}

	$key='_local_pickup_time_select';
	$value = esc_html(get_post_meta($order->get_id(),$key, true )); //timestamp ou aucun
	
	if(!empty($value) && $value!=="Aucun") {
		// Get an instance of the Public plugin.
		$plugin = Local_Pickup_Time::get_instance();
		$creneau=$plugin->pickup_time_select_translatable( $value );
		echo '<p><strong>Créneau de retrait à la boutique</strong> ' . $creneau . '</p>';
	}
}
