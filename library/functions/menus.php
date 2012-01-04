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

/* Add function to hook 'wp_get_nav_menu_items' */
add_filter( 'wp_get_nav_menu_items',  'bareskin_modify_menu_display', 10, 3 );

/**
 * Add class has-children to menu item if it has children 
 */
function bareskin_modify_menu_display($items, $menu, $args){
	
	/* Define an array that will hold all the ids of the menu items that have childen*/
	$menu_ids_that_have_childern = array();
	
	/* Populate the array by looping through all the menu items  */
	foreach ( $items as $item ){       
		
		if( $item->menu_item_parent != '0'){
			if( !in_array($item->menu_item_parent, $menu_ids_that_have_childern ) )
				$menu_ids_that_have_childern[] = $item->menu_item_parent;			
		}
        
    }
	
	/* Add class 'has-children' to the items that have tha id in the previously populated $menu_ids_that_have_childern array */
	foreach ( $items as $item ){
		if( in_array( $item->ID, $menu_ids_that_have_childern ) ){
			$item->classes[] = 'has-children';
		}
	}

	
	return $items;
}

?>