<?php
/**
 * Sets up the themes widgets. A theme must register support for the 
 * 'bareskin-widgets' feature to use the themes widgets.
 *
 * @package BareSkin
 * @subpackage Functions
 */


/* Register BareSkin widgets. */
add_action( 'widgets_init', 'bareskin_register_widgets' );

/**
 * Registers the themes widgets. 
 *
 * @since 1.0.0
 * @uses register_widget() Registers individual widgets with WordPress
 * @link http://codex.wordpress.org/Function_Reference/register_widget
 */
function bareskin_register_widgets() {

	/* Get themes supported widgets. */
	$supports = get_theme_support( 'bareskin-widgets' );

	/* If there are any supported widgets, load them. */
	if ( is_array( $supports[0] ) ) {

		/* Load the 'Advertisment' widget if it is supported, and register it. */
		if ( in_array( 'advertisment', $supports[0] ) ){
			require_once( trailingslashit( BARESKIN_WIDGETS ) . 'widget_advertisment.php' );
			
			/* Register the advertisment widget. */
			register_widget( 'BareSkin_Widget_Advertisment' );
		}
		
	}	
}