<?php
 
/*
 * register with hook 'wp_print_styles'
 */
add_action('wp_print_styles', 'bareskin_output_css_variation');

/*
 * Color Variation Output
 * 
 * Print style for color variation.
 *
 */
function bareskin_output_css_variation(){
	$cvar  = bareskin_get_setting( 'color_variation' );
	if( !empty( $cvar ) ){
		$css_src_theme = 'css/' .bareskin_get_setting( 'color_variation' ) .'.css';
		$css_src = trailingslashit( get_stylesheet_directory_uri() ) . $css_src_theme;
		 wp_enqueue_style( 'color_variation', $css_src, array() , bareskin_get_filemtime( $css_src_theme ), 'screen' );
	}
}
?>