<?php
/**
 * Shortcodes bundled for use with themes.  These shortcodes can be used in any shortcode-ready area, 
 * which includes the post content area.  
 *
 * @package BareSkin
 * @subpackage Functions
 */

/* Register shortcodes. */
add_action( 'init', 'bareskin_add_shortcodes' );

/**
 * Creates new shortcodes for use in any shortcode-ready area.  This function uses the add_shortcode() 
 * function to register new shortcodes with WordPress.
 *
 * @since 1.0.0
 * @uses add_shortcode() to create new shortcodes.
 * @link http://codex.wordpress.org/Shortcode_API
 */
function bareskin_add_shortcodes() {

	/* Add theme-specific shortcodes. */
	add_shortcode( 'the-year', 'bareskin_the_year_shortcode' );
	add_shortcode( 'site-link', 'bareskin_site_link_shortcode' );	
	add_shortcode( 'loginout-link', 'bareskin_loginout_link_shortcode' );
	add_shortcode( 'query-counter', 'bareskin_query_counter_shortcode' );
	add_shortcode( 'nav-menu', 'bareskin_nav_menu_shortcode' );
	
}

/**
 * Shortcode to display the current year.
 *
 * @since 1.0.0
 * @uses date() Gets the current year.
 */
function bareskin_the_year_shortcode() {
	return date( __( 'Y', bareskin_get_textdomain() ) );
}

/**
 * Shortcode to display a link back to the site.
 *
 * @since 1.0.0
 * @uses get_bloginfo() Gets information about the install.
 */
function bareskin_site_link_shortcode() {
	return '<a class="site-link" href="' . home_url() . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . '" rel="home"><span>' . get_bloginfo( 'name' ) . '</span></a>';
}



/**
 * Shortcode to display a login link or logout link.
 *
 * @since 1.0.0
 * @uses is_user_logged_in() Checks if the current user is logged into the site.
 * @uses wp_logout_url() Creates a logout URL.
 * @uses wp_login_url() Creates a login URL.
 */
function bareskin_loginout_link_shortcode() {
	$domain = bareskin_get_textdomain();
	if ( is_user_logged_in() )
		$out = '<a class="logout-link" href="' . esc_url( wp_logout_url( site_url( $_SERVER['REQUEST_URI'] ) ) ) . '" title="' . esc_attr__( 'Log out of this account', $domain ) . '">' . __( 'Log out', $domain ) . '</a>';
	else
		$out = '<a class="login-link" href="' . esc_url( wp_login_url( site_url( $_SERVER['REQUEST_URI'] ) ) ) . '" title="' . esc_attr__( 'Log into this account', $domain ) . '">' . __( 'Log in', $domain ) . '</a>';

	return $out;
}

/**
 * Displays query count and load time if the current user can edit themes.
 *
 * @since 1.0.0
 * @uses current_user_can() Checks if the current user can edit themes.
 */
function bareskin_query_counter_shortcode() {
	if ( current_user_can( 'edit_theme_options' ) )
		return sprintf( __( 'This page loaded in %1$s seconds with %2$s database queries.', bareskin_get_textdomain() ), timer_stop( 0, 3 ), get_num_queries() );
	return '';
}

/**
 * Displays a nav menu that has been created from the Menus screen in the admin.
 *
 * @since 1.0.0
 * @uses wp_nav_menu() Displays the nav menu.
 */
function bareskin_nav_menu_shortcode( $attr ) {

	$attr = shortcode_atts(
		array(
			'menu' => '',
			'container' => 'div',
			'container_id' => '',
			'container_class' => 'nav-menu',
			'menu_id' => '',
			'menu_class' => '',
			'link_before' => '',
			'link_after' => '',
			'before' => '',
			'after' => '',
			'fallback_cb' => 'wp_page_menu',
			'walker' => ''
		),
		$attr
	);
	$attr['echo'] = false;

	return wp_nav_menu( $attr );
}

?>