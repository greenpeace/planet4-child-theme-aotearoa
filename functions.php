<?php

/**
 * Additional code for the child theme goes in here.
 */

add_action( 'wp_head', 'gpnz_enqueue_script', 11 );
function gpnz_enqueue_script() {
	global $post;
	if ( has_block('enform') ){
	    wp_enqueue_script( 'phone-format-js', get_stylesheet_directory_uri().'/phoneFormat.js', false );
		wp_enqueue_script( 'jquery-inputmask-js', get_stylesheet_directory_uri().'/jquery.inputmask.bundle.min.js', false );
	} else {
		add_action('wp_footer', 'console_log');
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

console.log(" FAILED: has_block('enform') ");

})(jQuery);

</script>

<?php
}