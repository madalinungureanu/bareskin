<?php
class Bareskin {

	/**
	 * Constructor method for the Bareskin class.  This method adds other methods of the class to 
	 * specific hooks within WordPress.  It controls the load order of the required files for running 
	 * the theme.
	 *
	 * @since 1.0.0
	 */
	function __construct() {
	
		/* Define theme constants. */
		add_action( 'after_setup_theme', array( &$this, 'constants' ), 1 );
	
		/* Load the core functions required by the rest of the framework. */		
		add_action( 'after_setup_theme', array( &$this, 'core' ), 2 );

		/* Language functions and translations setup. */
		add_action( 'after_setup_theme', array( &$this, 'locale' ), 3 );
		
		/* Load functionality required by wordpress for the theme. */
		add_action( 'after_setup_theme', array( &$this, 'theme' ), 4 );
		
		/* Load admin files. */
		add_action( 'wp_loaded', array( &$this, 'admin' ) );
		
		/* Load the framework functions. */
		add_action( 'after_setup_theme', array( &$this, 'functions' ), 14 );
	}

	/**
	 * Defines the constant paths for use within the theme. 
	 *
	 * @since 1.0.0
	 */
	function constants() {

		/* Sets the path to the core framework directory. */
		define( 'BARESKIN_DIR', trailingslashit( get_template_directory() ) . basename( dirname( __FILE__ ) ) );
			
		/* Sets the path to the core framework directory URI. */
		define( 'BARESKIN_URI', trailingslashit( get_template_directory_uri() ) . basename( dirname( __FILE__ ) ) );

		/* Sets the path to the core framework admin directory. */
		define( 'BARESKIN_ADMIN', trailingslashit( BARESKIN_DIR ) . 'admin' );

		/* Sets the path to the core framework classes directory. */
		define( 'BARESKIN_WIDGETS', trailingslashit( BARESKIN_DIR ) . 'widgets' );

		/* Sets the path to the core framework extensions directory. */
		define( 'BARESKIN_EXTENSIONS', trailingslashit( BARESKIN_DIR ) . 'extensions' );

		/* Sets the path to the core framework functions directory. */
		define( 'BARESKIN_FUNCTIONS', trailingslashit( BARESKIN_DIR ) . 'functions' );

		/* Sets the path to the core framework CSS directory URI. */
		define( 'BARESKIN_CSS', trailingslashit( BARESKIN_URI ) . 'css' );

		/* Sets the path to the core framework JavaScript directory URI. */
		define( 'BARESKIN_JS', trailingslashit( BARESKIN_URI ) . 'js' );
	}
	
	/**
	 * Loads the core framework functions.  These files are needed before loading anything else in the 
	 * framework because they have required functions for use.
	 *
	 * @since 1.0.0
	 */
	function core() {
	
		/* Load the core framework functions. */		
		require_once( trailingslashit( BARESKIN_FUNCTIONS ) . 'core.php' );

	}
	
	
	/**
	 * Handles the locale functions file and translations.
	 *
	 * @since 1.0.0
	 */
	 
	function locale() {

		/* Load theme textdomain. */
		load_theme_textdomain( bareskin_get_textdomain() );

		/* Get the user's locale. */
		$locale = get_locale();

		/* Locate a locale-specific functions file. */
		$locale_functions = locate_template( array( "languages/{$locale}.php", "{$locale}.php" ) );

		/* If the locale file exists and is readable, load it. */
		if ( !empty( $locale_functions ) && is_readable( $locale_functions ) )
			require_once( $locale_functions );
	}
	
	/**
	 * Set up theme requirements
	 *
	 * @since 1.0.0
	 */
	function theme() {
		/* setup global $content_width */
		global $content_width;		
		$content_width = 640;
		
		/* add support for feed links (required in theme development) */
		add_theme_support( 'automatic-feed-links' );
	}
	
	/**
	 * Load admin files for the framework.
	 *
	 * @since 1.0.0
	 */
	function admin() {
		/* Add custom background if theme supports */
		if( get_theme_support('bareskin-custom-background') )
			add_custom_background();
			
		/* Add fonts if theme supports */
		$supports = get_theme_support( 'bareskin-theme-settings' );
		if ( in_array( 'font', $supports[0] ) ){
			require_once( trailingslashit( BARESKIN_FUNCTIONS ) . 'fonts.php' );
			add_action( 'wp_head', 'bareskin_custom_fonts' );
		}
		
		/* Check if in the WordPress admin. */
		if ( is_admin() ) {

			/* Load the main admin file. */
			require_once( trailingslashit( BARESKIN_ADMIN ) . 'admin.php' );

			/* Load the theme settings feature if supported. */
			require_if_theme_supports( 'bareskin-theme-settings', trailingslashit( BARESKIN_ADMIN ) . 'theme-settings.php' );
			
		}
	}
	
	/**
	 * Load functions for the framework.
	 *
	 * @since 1.0.0
	 */
	
	function functions() {		 
	
		/* Add comments functions */
		require_once( trailingslashit( BARESKIN_FUNCTIONS ) . 'comments.php' );
	
		/* Add color variations if theme supports */
		$supports = get_theme_support( 'bareskin-theme-settings' );
		if ( in_array( 'color-variations', $supports[0] ) ){
			require_once( trailingslashit( BARESKIN_FUNCTIONS ) . 'color_variation.php' );			
		}
		
		/* Add content functions if theme supports */
		require_if_theme_supports( 'bareskin-content-functions', trailingslashit( BARESKIN_FUNCTIONS ) . 'content.php' );
		
		/* Load the sidebars if supported. */
		require_if_theme_supports( 'bareskin-sidebars', trailingslashit( BARESKIN_FUNCTIONS ) . 'sidebars.php' );
		
		/* Load the menus functions if supported. */
		require_if_theme_supports( 'bareskin-menus', trailingslashit( BARESKIN_FUNCTIONS ) . 'menus.php' );
		
		/* Load the shortcodes if supported. */
		require_if_theme_supports( 'bareskin-shortcodes', trailingslashit( BARESKIN_FUNCTIONS ) . 'shortcodes.php' );
		
		/* Load get_image functions if supported. */
		require_if_theme_supports( 'bareskin-get-image', trailingslashit( BARESKIN_FUNCTIONS ) . 'get-the-image.php' );
		
		/* Load breadcrumb trail functions if supported. */
		require_if_theme_supports( 'bareskin-breadcrumb', trailingslashit( BARESKIN_FUNCTIONS ) . 'breadcrumb-trail.php' );
		
		/* Load widgets functions if supported. */
		require_if_theme_supports( 'bareskin-widgets', trailingslashit( BARESKIN_FUNCTIONS ) . 'widgets.php' );
	}

}
?>