<?php

/**
 * Additional code for the child theme goes in here.
 */


add_action( 'wp_head', 'gpnz_enqueue_script', 11 );
function gpnz_enqueue_script() {
	if( has_shortcode( $post->post_content, 'shortcake_enblock') ){
      	wp_enqueue_script( 'phone-format-js', 'phoneFormat.js', false );
	  	wp_enqueue_script( 'jquery-inputmask-js', 'jquery.inputmask.bundle.min.js', false );
    } else {
	    // condition not met
    }
}

// test is_page condition
add_action( 'wp_head', 'gpnz_is_page', 12 );
function gpnz_is_page() {
	if( is_page( 'Validation Test' ) ){
      add_action('wp_footer', 'console_log_errors');
    }
}

add_action( 'wp_enqueue_scripts', 'enqueue_child_styles', 99);
function enqueue_child_styles() {
	$css_creation = filectime(get_stylesheet_directory() . '/style.css');
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [], $css_creation );
}

function console_log_errors() {
?>
<script type="text/javascript">
(function($) {

console.log("is_page( 'Validation Test' )");

})(jQuery);

</script>

<?php
}