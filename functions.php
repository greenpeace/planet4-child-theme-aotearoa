<?php
/* added child plugins in composer-local.json */

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