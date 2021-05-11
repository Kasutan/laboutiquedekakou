<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function kasutan_retourne_message_absence() {
	if(!function_exists('get_field')) {
		return '';
	}
	return wp_kses_post(get_field('kakou_message_absence','options'));
}
function kasutan_affiche_message_absence() {
	$message=kasutan_retourne_message_absence();
	if(!empty($message)) {
		wc_print_notice($message,'notice');
	}
}

add_action('woocommerce_before_cart','kasutan_affiche_message_absence');
add_action('woocommerce_before_checkout_form','kasutan_affiche_message_absence');
add_action('woocommerce_before_single_product','kasutan_affiche_message_absence');
add_action('kakou_avant_archive_produit','kasutan_affiche_message_absence');