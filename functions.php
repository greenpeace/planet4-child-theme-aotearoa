<?php

/**
 * Additional code for the child theme goes in here.
 */

add_action( 'wp_enqueue_scripts', 'gpnz_enqueue_script' );
function gpnz_enqueue_script() {
	wp_enqueue_script( 'phone-format-js', 'phoneFormat.js', false );
	wp_enqueue_script( 'jquery-inputmask-js', 'jquery.inputmask.bundle.min.js', false );
}

add_action( 'wp_enqueue_scripts', 'enqueue_child_styles', 99);

function enqueue_child_styles() {
	$css_creation = filectime(get_stylesheet_directory() . '/style.css');

	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [], $css_creation );
}
