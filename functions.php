<?php

/**
 * Additional code for the child theme goes in here.
 */


add_action( 'wp_head', 'gpnz_enqueue_script', 11 );
function gpnz_enqueue_script() {
	if( has_shortcode( $post->post_content, 'shortcake_enblock') ){
      	wp_enqueue_script( 'phone-format-js', 'phoneFormat.js', false );
	  	wp_enqueue_script( 'jquery-inputmask-js', 'jquery.inputmask.bundle.min.js', false );
    }
}

add_action( 'wp_enqueue_scripts', 'enqueue_child_styles', 99);
function enqueue_child_styles() {
	$css_creation = filectime(get_stylesheet_directory() . '/style.css');
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [], $css_creation );
}

// test 
add_action('wp_footer', 'my_action_javascript');
function my_action_javascript() {
?>
<script type="text/javascript">
(function($) {

console.log("Child theme functions jQuery check - release v0.5.2");

})(jQuery);

</script>

<?php
}