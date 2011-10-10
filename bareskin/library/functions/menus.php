<?php
/**
 * The menus functions deal with registering nav menus within WordPress for the theme.
 *
 * @package BareSkin
 * @subpackage Functions
 */

/* Register nav menus. */
add_action( 'init', 'bareskin_register_menus' );

/**
 * Registers the the theme's menus based on the menus the theme has registered support for.
 *
 * @since 1.0.0
 * @uses register_nav_menu() Registers a nav menu with WordPress.
 * @link http://codex.wordpress.org/Function_Reference/register_nav_menu
 */
function bareskin_register_menus() {

	/* Get theme-supported menus. */
	$menus = get_theme_support( 'bareskin-menus' );

	/* If there is no array of menus IDs, return. */
	if ( !is_array( $menus[0] ) )
		return;

	/* Register the menus based on the second parameter of add_theme_support('bareskin-menus'). */
	foreach( $menus[0] as $menu ){
		register_nav_menu( $menu, _x( ucfirst( $menu ), 'nav menu location', bareskin_get_textdomain() ) );
	}

}

?>