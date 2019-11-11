<?php

/**
 * Additional code for the child theme goes in here.
 */

add_action( 'wp_head', 'gpnz_enqueue_script', 11 );
function gpnz_enqueue_script() {
	global $post;
		
	$blocks = parse_blocks( $post->post_content );
	
	if ( has_blocks( $post->post_content ) ) {
	    $blocks = parse_blocks( $post->post_content );
		
		foreach ( $blocks as $block ) {
			if ( 'planet4-blocks/enform' === $block['blockName'] ) {
				add_action('wp_footer', 'console_log_true');
			    wp_enqueue_script( 'phone-format-js', get_stylesheet_directory_uri().'/phoneFormat.js', false );
				wp_enqueue_script( 'jquery-inputmask-js', get_stylesheet_directory_uri().'/jquery.inputmask.bundle.min.js', false );
			} else {
				add_action('wp_footer', 'console_log_false');
			}
		}
	
	}

add_action( 'wp_enqueue_scripts', 'enqueue_child_styles', 99);
function enqueue_child_styles() {
	$css_creation = filectime(get_stylesheet_directory() . '/style.css');
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [], $css_creation );
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

console.log(" no match: 'planet4-blocks/enform' === $block['blockName'] ");

})(jQuery);

</script>

<?php
}