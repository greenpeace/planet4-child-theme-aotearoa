<?php
/* added child plugins in composer-local.json */


// add_action( 'wp_head', 'gpnz_enqueue_script', 11 );
// function gpnz_enqueue_script() {
// 	global $post;
	
// 	wp_enqueue_script( 'phone-format-js', get_stylesheet_directory_uri().'/phoneFormat.js', false );
// 	wp_enqueue_script( 'jquery-inputmask-js', get_stylesheet_directory_uri().'/jquery.inputmask.bundle.min.js', false );
	
	
// }

add_action( 'wp_enqueue_scripts', 'enqueue_child_styles', 99);
function enqueue_child_styles() {
	$css_creation = filectime(get_stylesheet_directory() . '/style.css');
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [], $css_creation );
}

add_action( 'gform_post_payment_completed', function ( $entry, $action ) {
     
    // Do something here.

 	echo "gform_post_payment_completed entry";
	print_r($entry);



	echo "========================== action";
	print_r($action);

 
}, 10, 2 );