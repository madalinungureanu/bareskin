<?php
/**
 * Handles the display and functionality of the theme settings page. This provides the needed hooks and
 * meta box calls for developers to create any number of theme settings needed. This file is only loaded
 * only if theme supports 'bareskin-theme-settings';
 *
 * Provides the ability for developers to add custom meta boxes to the theme settings page by using the 
 * add_meta_box() function.  Developers should hook their meta box registration function to 'admin_menu' 
 * and register the meta box for 'appearance_page-theme-settings'. If data needs to be saved, devs can 
 * use the '$prefix_add_to_validation' action hook to save their data.
 *
 * @package BareSkin
 * @subpackage Admin
 */
 
 
 /* Hook the settings page function to 'admin_menu'. */
add_action( 'admin_menu', 'bareskin_settings_page_init' );

/* Hook to amdin_init  bareskin_register_settings function */
add_action( 'admin_init', 'bareskin_register_settings' );

/* Hook to admin_init  bareskin_load_settings_page_meta_boxes function. This had to be moved here from  bareskin_settings_page_init because the validation didn't work otherwise */
add_action( "admin_init", 'bareskin_load_settings_page_meta_boxes' );

/**
 * Register theme settings. Uses register_setting( $option_group, $option_name, $sanitize_callback) 
 *
 * @since 1.0.0
 */
function bareskin_register_settings(){
	$prefix = bareskin_get_prefix();
	/* Register theme settings. */
	register_setting( "{$prefix}_theme_settings", "{$prefix}_theme_settings" , 'bareskin_save_theme_settings' );
}

/**
 * Initializes all the theme settings page functions. This function is used to create the theme settings 
 * page, then use that as a launchpad for specific actions that need to be tied to the settings page.
 *
 * Users or developers can set a custom capability (default is 'edit_theme_options') for access to the
 * settings page using the "$prefix_settings_capability" filter hook.
 *
 * @since 1.0.0
 * @global string $bareskin_settings_page The global setting page. It is initialized here
 */
function bareskin_settings_page_init(){
	global $bareskin_settings_page;
	/* Get theme information. */
	$theme_data = get_theme_data( trailingslashit( STYLESHEETPATH ) . 'style.css' );
	$prefix = bareskin_get_prefix();
	$domain = bareskin_get_textdomain();
	
	
	/* Create the theme settings page. */
	 $bareskin_settings_page = add_theme_page( sprintf( __( '%1$s Theme Settings', $domain ), $theme_data['Name'] ), sprintf( __( '%1$s Settings', $domain ), $theme_data['Name'] ), apply_filters( "{$prefix}_settings_capability", 'edit_theme_options' ), 'theme-settings', 'bareskin_settings_page' );
	 
	/* Load the theme settings meta boxes. */
	//add_action( "load-{$bareskin_settings_page}", 'bareskin_load_settings_page_meta_boxes' );
	
	/* Create a hook for adding meta boxes. */
	add_action( "load-{$bareskin_settings_page}", 'bareskin_settings_page_add_meta_boxes' );	
	
	/* Load the JavaScript and stylesheets needed for the theme settings screen. */
	add_action( 'admin_enqueue_scripts', 'bareskin_settings_page_enqueue_scripts' );
	add_action( 'admin_enqueue_scripts', 'bareskin_settings_page_enqueue_styles' );
	add_action( "admin_head-{$bareskin_settings_page}", 'bareskin_settings_page_load_scripts' );
	
}


/**
 * Displays the theme settings page and calls do_meta_boxes() to allow additional settings
 * meta boxes to be added to the page.
 *
 * @since 1.0.0
 * @global string $bareskin_settings_page The global setting page (returned by add_theme_page in function
 * bareskin_settings_page_init ).
 */

function bareskin_settings_page(){
	global $bareskin_settings_page;
	/* Get theme information. */
	$theme_data = get_theme_data( trailingslashit( STYLESHEETPATH ) . 'style.css' );
	$prefix = bareskin_get_prefix();
	$domain = bareskin_get_textdomain();
	?>
	<div class="wrap">

		<?php screen_icon(); ?>

		<h2><?php printf( __( '%1$s Theme Settings', $domain ), $theme_data['Name'] ); ?></h2>

		<?php if ( isset( $_GET['settings-updated'] ) && 'true' == esc_attr( $_GET['settings-updated'] ) ) echo '<div class="updated"><p><strong>' . __( 'Settings saved.', $domain ) . '</strong></p></div>'; ?>

		<div id="poststuff">

			<form method="post" action="options.php" enctype="multipart/form-data">

				<?php settings_fields( "{$prefix}_theme_settings" ); ?>
				<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
				<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>

				<div class="metabox-holder">
					<div class="post-box-container column-1 normal"><?php do_meta_boxes( $bareskin_settings_page, 'normal', null ); ?></div>
					<div class="post-box-container column-2 side"><?php do_meta_boxes( $bareskin_settings_page, 'side', null ); ?></div>
					<div class="post-box-container column-3 advanced"><?php do_meta_boxes( $bareskin_settings_page, 'advanced', null ); ?></div>
				</div>

				<?php submit_button( esc_attr__( 'Update Settings', $domain ) ); ?>

			</form>

		</div><!-- #poststuff -->

	</div><!-- .wrap -->
	<?php
}

/**
 * Provides a hook for adding meta boxes as seen on the post screen in the WordPress admin.  This addition 
 * is needed because normal plugin/theme pages don't have this hook by default.  The other goal of this 
 * function is to provide a way for themes to load and execute meta box code only on the theme settings 
 * page in the admin.  This way, they're not needlessly loading extra files.
 *
 * @global string $bareskin_settings_page. The global setting page (returned by add_theme_page in function
 * bareskin_settings_page_init ).
 * @since 1.0.0
 */
function bareskin_settings_page_add_meta_boxes() {
	global $bareskin_settings_page;
	$theme_data = get_theme_data( trailingslashit( STYLESHEETPATH ) . 'style.css' );
	
	do_action( 'add_meta_boxes', $bareskin_settings_page, $theme_data );
}


/**
 * Loads the meta boxes packaged with the framework on the theme settings page.  These meta boxes are 
 * merely loaded with this function.  Meta boxes are only loaded if the feature is supported by the theme.
 *
 * @since 1.0.0
 */
function bareskin_load_settings_page_meta_boxes() {

	/* Get theme-supported meta boxes for the settings page. */
	$supports = get_theme_support( 'bareskin-theme-settings' );

	/* If there are any supported meta boxes, load them. */
	if ( is_array( $supports[0] ) ) {

		/* Load the 'About' meta box if it is supported. */
		if ( in_array( 'about', $supports[0] ) )
			require_once( trailingslashit( BARESKIN_ADMIN ) . 'meta-box-theme-about.php' );

		/* Load the 'Footer' meta box if it is supported. */
		if ( in_array( 'footer', $supports[0] ) )
			require_once( trailingslashit( BARESKIN_ADMIN ) . 'meta-box-theme-footer.php' );
		
		/* Load the 'Logo and Favicon' meta box if it is supported. */
		if ( in_array( 'logo-favicon', $supports[0] ) )
			require_once( trailingslashit( BARESKIN_ADMIN ) . 'meta-box-theme-logo-and-favicon.php' );
		
		/* Load the 'Archive Display' meta box if it is supported. */
		if ( in_array( 'archive-display', $supports[0] ) ) {
			require_once( trailingslashit( BARESKIN_ADMIN ) . 'meta-box-archive-display.php' );
			require_once( trailingslashit( BARESKIN_FUNCTIONS ) . 'get-the-image.php' );
		}
		
		/* Load the 'Font Selection' meta box if it is supported. */
		if ( in_array( 'font', $supports[0] ) )
			require_once( trailingslashit( BARESKIN_ADMIN ) . 'meta-box-theme-font.php' );
		
		/* Load the 'Color variation' meta box if it is supported. */
		if ( in_array( 'color-variations', $supports[0] ) )
			require_once( trailingslashit( BARESKIN_ADMIN ) . 'meta-box-theme-css-variations.php' );
			
		/* Load the 'Style Minify' meta box if it is supported. */
		if ( in_array( 'style-minify', $supports[0] ) )
			require_once( trailingslashit( BARESKIN_ADMIN ) . 'meta-box-style-css-compression.php' );
	}
}


/**
 * Validation/Sanitization callback function for theme settings.  This just returns the data passed to it. 
 * To add validation to any additions you should use the "{$prefix}_add_to_validation" filter.
 *
 * @since 1.0.0
 */
function bareskin_save_theme_settings( $settings ) {
	$prefix = bareskin_get_prefix();
	$settings = apply_filters( "{$prefix}_add_to_validation", $settings );	
	/* Return the theme settings. */
	return $settings;
}

/**
 * Loads the JavaScript files required for managing the meta boxes on the theme settings
 * page, which allows users to arrange the boxes to their liking.
 *
 * @global string $bareskin_settings_page. The global setting page (returned by add_theme_page in function
 * bareskin_settings_page_init ).
 * @since 1.0.0
 * @param string $hook_suffix The current page being viewed.
 */
function bareskin_settings_page_enqueue_scripts( $hook_suffix ) {
	global $bareskin_settings_page;
	if ( $hook_suffix == $bareskin_settings_page ) {
		wp_enqueue_script( 'common' );
		wp_enqueue_script( 'wp-lists' );
		wp_enqueue_script( 'postbox' );
	}
}

/**
 * Loads the required stylesheets for displaying the theme settings page in the WordPress admin.
 *
 * @global string $bareskin_settings_page. The global setting page (returned by add_theme_page in function
 * bareskin_settings_page_init ).
 * @since 1.0.0
 */
function bareskin_settings_page_enqueue_styles( $hook_suffix ) {
	global $bareskin_settings_page;
	/* Load admin stylesheet if on the theme settings screen. */
	if ( $hook_suffix == $bareskin_settings_page ){
		wp_register_style( 'bareskin-admin', trailingslashit( BARESKIN_CSS ) . 'admin.css', false, '20110512', 'screen' );
		wp_enqueue_style( 'bareskin-admin' );
	}
}

/**
 * Loads the JavaScript required for toggling the meta boxes on the theme settings page.
 *
 * @global string $bareskin_settings_page. The global setting page (returned by add_theme_page in function
 * bareskin_settings_page_init ).
 * @since 1.0.0
 */
function bareskin_settings_page_load_scripts() { 
	global $bareskin_settings_page;
	?>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			postboxes.add_postbox_toggles( '<?php echo $bareskin_settings_page; ?>' );
		});
		//]]>
	</script><?php
}
?>