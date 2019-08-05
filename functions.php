<?php

/**
 * Additional code for the child theme goes in here.
 */

global $post;


echo '<pre>';
echo $post->post_content;
echo '</pre>';

echo '<pre>';
echo '================================================================';
echo '</pre>';

echo '<pre>';
echo $post;
echo '</pre>';

add_action( 'wp_head', 'gpnz_enqueue_script', 11 );
function gpnz_enqueue_script() {
	if( has_shortcode( $post->post_content, 'shortcake_enblock') ){
      	wp_enqueue_script( 'phone-format-js', 'phoneFormat.js', false );
	  	wp_enqueue_script( 'jquery-inputmask-js', 'jquery.inputmask.bundle.min.js', false );
    } else {
	    add_action('wp_footer', 'console_log_errors');
    }
}

add_action( 'wp_enqueue_scripts', 'enqueue_child_styles', 99);
function enqueue_child_styles() {
	$css_creation = filectime(get_stylesheet_directory() . '/style.css');
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [], $css_creation );
}

// test 

function console_log_errors() {
?>
<script type="text/javascript">
(function($) {

console.log("condition not found : has_shortcode( $post->post_content, 'shortcake_enblock' ");

})(jQuery);

</script>

<?php
}