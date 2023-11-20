<?php
/* added child plugins in composer-local.json */

/* add stripe customer funtions */

add_action( 'wp_head', 'gpnz_enqueue_script', 11 );
function gpnz_enqueue_script() {
	global $post;
	
	wp_enqueue_script( 'phone-format-js', get_stylesheet_directory_uri().'/phoneFormat.js', false );
	wp_enqueue_script( 'jquery-inputmask-js', get_stylesheet_directory_uri().'/jquery.inputmask.bundle.min.js', false );
	
	if( $post->ID == 7669 || $post->ID == 684 || $post->ID == 7573){
		// add_action('wp_footer', 'console_log');
		$version_countUp = filectime(get_stylesheet_directory() . '/countUp.js');
		wp_enqueue_script( 'countUp', get_stylesheet_directory_uri().'/countUp.js', [], $version_countUp );
	} else {
		//add_action('wp_footer', 'console_log');
	}
	
}

add_action( 'wp_enqueue_scripts', 'enqueue_child_styles', 99);
function enqueue_child_styles() {
	$css_creation = filectime(get_stylesheet_directory() . '/style.css');
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [], $css_creation );
}

add_filter( 'gform_stripe_customer_id', function ( $customer_id, $feed, $entry, $form ) {
 
    $feed_name = rgars( $feed, 'meta/feedName' );
 
    gf_stripe()->log_debug( 'gform_stripe_customer_id: Running for Feed: ' . $feed_name );
 
    // Define the names of the feeds you want this code to run.
    $feed_names = array( 'feed name one', 'feed name two' );
 
    // Abort if the entry was processed by a different feed or it's not a product and services feed.
    if ( rgars( $feed, 'meta/transactionType' ) !== 'product' && ! in_array( $feed_name, $feed_names ) ) {
        return $customer_id;
    }
 
    if ( empty( $customer_id ) ) {
        $response = gf_stripe()->get_stripe_js_response();
        if ( ! empty( $response->id ) && substr( $response->id, 0, 3 ) === 'pi_' ) {
            try {
                $intent = \Stripe\PaymentIntent::retrieve( $response->id );
                if ( ! empty( $intent->customer ) ) {
                    gf_stripe()->log_debug( 'gform_stripe_customer_id: PaymentIntent already has customer; ' . print_r( $intent->customer, true ) );
 
                    return is_object( $intent->customer ) ? $intent->customer->id : $intent->customer;
                }
            } catch ( \Exception $e ) {
                gf_stripe()->log_debug( 'gform_stripe_customer_id: unable to get PaymentIntent; ' . $e->getMessage() );
            }
        }
 
        $customer_params = array();
 
        $email_field = rgars( $feed, 'meta/receipt_field' );
        if ( ! empty( $email_field ) && strtolower( $email_field ) !== 'do not send receipt' ) {
            $customer_params['email'] = gf_stripe()->get_field_value( $form, $entry, $email_field );
        }
 
        // Optional - Customer name using a Name field that has id 8. Update the id number or remove the following line.
        $customer_params['name'] = rgar( $entry, '8.3' ) . ' ' . rgar( $entry, '8.6' );
        gf_stripe()->log_debug( __METHOD__ . '(): Name: ' . $customer_params['name'] );
 
        // Optional - Customer phone using a Phone field that has id 5. Update the id number or remove the following line.
        $customer_params['phone'] = rgar( $entry, '5' );
        gf_stripe()->log_debug( __METHOD__ . '(): Phone: ' . $customer_params['phone'] );
 
        $customer    = gf_stripe()->create_customer( $customer_params, $feed, $entry, $form );
        $customer_id = $customer->id;
        gf_stripe()->log_debug( 'gform_stripe_customer_id: Returning Customer ID: ' . $customer_id );
    }
 
    return $customer_id;
 
}, 10, 4 );


add_filter( 'gform_confirmation_anchor', '__return_true' );
function console_log() {
?>
<script type="text/javascript">
(function($) {

console.log(" is $post->ID == 7669 || $post->ID == 684 || $post->ID == 7573 ");

})(jQuery);

</script>

<?php
}

function console_log_true() {
?>
<script type="text/javascript">
(function($) {

console.log(" match: 'planet4-blocks/enform' === $block['blockName'] ");

})(jQuery);

</script>

<?php
}

function console_log_false() {
?>
<script type="text/javascript">
(function($) {

console.log("parsed block did not match condition");

})(jQuery);

</script>

<?php
}

