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

		/* Load the 'Advanced Text' widget if it is supported, and register it. */
		if ( in_array( 'advanced-text', $supports[0] ) ){
			require_once( trailingslashit( BARESKIN_WIDGETS ) . 'widget_advanced_text.php' );
			
			/* Register the advanced text widget. */
			register_widget( 'BareSkin_Advanced_Widget_Text' );
		}
		
		/* Load the 'Advanced Recent Posts' widget if it is supported, and register it. */
		if ( in_array( 'recent-posts', $supports[0] ) ){
			require_once( trailingslashit( BARESKIN_WIDGETS ) . 'widget_adv_recent_posts.php' );
			
			/* Register the advanced recent posts widget. */
			register_widget( 'BareSkin_Widget_Advanced_Recent_Posts' );
		}
		
	}	
}