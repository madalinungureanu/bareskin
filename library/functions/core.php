<?php
/**
 * The core functions file for the BareSkin theme. Functions defined here are generally
 * used across the entire theme to make various tasks faster. This file should be loaded
 * prior to any other files because its functions are needed to run the framework.
 *
 * @package BareSkin
 * @subpackage Functions
 */

 
 /**
 * Defines the theme prefix. 
 *
 * @since 1.0.0
 * @uses get_template() Defines the theme prefix, which is generally 'bareskin'. 
 * @return string $prefix The prefix of the theme.
 */
function bareskin_get_prefix() {
	
	$prefix = apply_filters( 'bareskin_prefix', get_stylesheet() );

	return $prefix;
}

/**
 * Defines the theme textdomain. This sets the textdomain as the directory name of the theme.
 *  
 * @since 1.0.0
 * @uses get_template() Defines the theme textdomain, which is generally 'bareskin'.
 * @return string $textdomain The textdomain of the theme.
 */
function bareskin_get_textdomain() {

	$textdomain = apply_filters( 'bareskin_textdomain', get_stylesheet() );

	return $textdomain;
}

/**
 * Returns the time when the content of the file was changed.
 *  
 * @since 1.0.0
 * @param string $path Path to the desired file in the theme directory relative to the theme directory. Don't include * the first '/'. If $path = 'stylesheet' then it returns the time the style.css of the theme was changed.
 * @return int $filemtime The time the file was last modified, or FALSE on failure. The time is returned as a Unix 
 * timestamp, which is suitable for the date() function.
 */
function bareskin_get_filemtime($path){
	if($path == 'stylesheet'){
		$filemtime = filemtime(get_stylesheet_directory().'/style.css');
	}
	else $filemtime = filemtime(get_stylesheet_directory().'/'.$path);
	return $filemtime;	
}

/**
 * Loads the BareSkin theme settings once and allows the input of the specific field the user would 
 * like to show.  Hybrid theme settings are added with 'autoload' set to 'yes', so the settings are 
 * only loaded once on each page load.
 *
 * @since 1.0.0
 * @uses get_option() Gets an option from the database.
 * @uses bareskin_get_prefix() Gets the prefix of the theme.
 * @param string $option The specific theme setting the user wants.
 * @return string|int|array $settings[$option] Specific setting asked for.
 */
function bareskin_get_setting( $option = '' ) {

	if ( !$option )
		return false;

	$bareskin_settings = get_option( bareskin_get_prefix() . '_theme_settings', bareskin_get_default_theme_settings() );

	if ( !is_array( $bareskin_settings ) || empty( $bareskin_settings[$option] ) )
		return false;

	if ( is_array( $bareskin_settings[$option] ) )
		return $bareskin_settings[$option];
	else
		return wp_kses_stripslashes( $bareskin_settings[$option] );
}

/**
 * Sets up a default array of theme settings for use with the theme.  Theme developers should filter the 
 * "{$prefix}_default_theme_settings" hook to define any default theme settings.  WordPress does not 
 * provide a hook for default settings at this time.
 *
 * @since 1.0.0
 */
function bareskin_get_default_theme_settings() {

	/* Set up some default variables. */
	$settings = array();
	$domain = bareskin_get_textdomain();
	$prefix = bareskin_get_prefix();

	/* Get theme-supported meta boxes for the settings page. */
	$supports = get_theme_support( 'bareskin-theme-settings' );

	
	if ( is_array( $supports[0] ) ) {
	
		/* If the current theme supports the footer meta box, add default footer settings. */
		if ( in_array( 'archive-display', $supports[0] ) )
			$settings['archive-display'] = 'show-excerpt-thumb';
			
		/* If the current theme supports the font meta box, add default font settings. */
		if ( in_array( 'font', $supports[0] ) ){
			$settings['primary_font'] = 'pt-sans';
			$settings['secondary_font'] = 'trebuchet';			
		}
			
		/* provide a hook for overwriting the default settings. */
		$settings = apply_filters( "{$prefix}_default_theme_settings", $settings );
	}

	/* Return the $settings array */
	return $settings;
}
?>