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

add_action( 'gform_post_payment_completed', function ( $entry, $action ) {
     
    // Do something here.
	echo '<pre>';
 	echo 'gform_post_payment_completed';
	echo '</pre>';

	echo '<pre>';
	print_r($entry);
	echo '</pre>';
	
	echo '<pre>';
	print_r($action);
 	echo '</pre>';

    // If data from the Stripe webhook event is needed (and this hook was initiated via a Stripe webhook request), you can access event data with the following line:
    $event = gf_stripe()->get_webhook_event();

	echo '<pre>';
	print_r($event);
	echo '</pre>';

    if ( $event ){
        print_r($event);
       // Access webhook event data. For event object documentation, see: https://stripe.com/docs/api/events/object
    }    
 
}, 10, 2 );
