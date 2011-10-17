<?php
/* Load the core theme file. */
require_once( trailingslashit( TEMPLATEPATH ) . 'library/bareskin.php' );
$theme = new Bareskin();

/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'bareskin_theme_setup' );



/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters.
 *
 * @since 0.1.0
 */
function bareskin_theme_setup() {
	
	add_theme_support( 'bareskin-theme-settings', array( 'about', 'footer', 'logo-favicon', 'archive-display', 'color-variations', 'style-minify' ) );
	add_theme_support( 'bareskin-custom-background' );	
	add_theme_support( 'bareskin-content-functions' );
	add_theme_support( 'bareskin-sidebars', array( 'primary', 'secondary', 'subsidiary', 'subsidiary2', 'subsidiary3', 'before-content', 'after-content' ) );
	add_theme_support( 'bareskin-menus', array( 'primary', 'secondary' ) );
	add_theme_support( 'bareskin-get-image' );	
	add_theme_support( 'bareskin-breadcrumb' );	
	add_theme_support( 'bareskin-shortcodes' );	
	add_theme_support( 'bareskin-widgets', array( 'recent-posts' ) );	
	
	add_theme_support( 'post-formats', array( 'aside', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video', 'audio' ) );
	
	
	
}






?>