<?php 
/**
 * Creates a meta box for the theme settings page, which holds options tu use a dev style 
 * or the working one and also you can minify the working style.  To use this feature, the theme must 
 * support the 'style-minify' argument for the 'bareskin-theme-settings' feature.
 *
 * @package BareSkin
 * @subpackage Admin
 */


/* Include the files that will handle the actual minification of the css. Using http://code.google.com/p/minify/ */ 
require_once( trailingslashit( BARESKIN_ADMIN ) . 'Minify/Compressor.php' );
require_once( trailingslashit( BARESKIN_ADMIN ) . 'Minify/CommentPreserver.php' );            

/* Create the style-minify meta box on the 'add_meta_boxes' hook. */
add_action( 'add_meta_boxes', 'bareskin_meta_box_add_style_minify' );

/* Hook function to perform minify operations */
add_filter( bareskin_get_prefix().'_add_to_validation', 'bareskin_meta_box_minify_css_actions' );

/**
 * Adds the style-minify meta box to the theme settings page in the admin.
 *
 * @since 1.0.0
 */
function bareskin_meta_box_add_style_minify() {
	global $bareskin_settings_page;
	add_meta_box( 'bareskin-meta-box-style-minify', __( 'Style Minify', bareskin_get_textdomain() ), 'bareskin_meta_box_display_style_minify', $bareskin_settings_page, 'normal', 'high' );
}


/**
 * Creates a settings box that allows users to perform style minification and choose 
 * between dev style and production style. 
 *
 * @since 1.0.0
 */
function bareskin_meta_box_display_style_minify() {
	$domain = bareskin_get_textdomain(); 
	
	if( ! file_exists( trailingslashit( get_stylesheet_directory() ) .'style.dev.css' ) ){
		?>	
			<p>
				<span class="description"><?php _e( 'It appears that you do not have a style.dev.css in your theme directory. You can either create one manualy by copying the style.css or click below and one will be created for you. Be sure that the theme info comments begin with /*! or else they will be striped when you minify.', $domain ); ?></span>
			</p>
			<input type="submit" name="bareskin_create_dev_style" value="<?php _e( 'Create dev style', $domain ) ?>" class="button">
		<?php 
	}
	
	else{
		?>
			<p>
				<span class="description"><?php _e( 'Select below which style you want to use: ', $domain ); ?></span>
			</p>
			<p>
				<input type="radio" name="<?php echo bareskin_settings_field_name( 'style_to_use' ) ?>" value="style" id="style" <?php checked( bareskin_get_setting( 'style_to_use' ), 'style' ); ?>/>				
				<label for="style"><?php _e( 'style.css', $domain )?></label>
				<input type="radio" name="<?php echo bareskin_settings_field_name( 'style_to_use' ) ?>" value="style_dev" id="style_dev" <?php checked( bareskin_get_setting( 'style_to_use' ), 'style_dev' ); ?>/>	
				<label for="style_dev"><?php _e( 'style.dev.css', $domain )?></label>		
			</p>
			<p>
				<span class="description"><?php _e( 'Click below if you want to minify style.dev.css to style.css: ', $domain ); ?></span>
			</p>
			<input type="submit" name="bareskin_minify_dev_style" value="<?php _e( 'Minify Now', $domain ) ?>" class="button">
		<?php	
	}	
}

/**
 * Creates a new style.dev.css from style.css if it doesn't exist. It also minifies style.dev.css to style.css
 *
 * @since 1.0.0
 * @param array $settings Array of theme settings passed by the Settings API for validation.
 * @return array $settings
 */
function bareskin_meta_box_minify_css_actions( $settings ){

	$form_fields = array ('bareskin_create_dev_style', 'bareskin_minify_dev_style' ); // this is a list of the form field contents I want passed along between page views
	$method = ''; // Normally you leave this an empty string and it figures it out by itself, but you can override the filesystem method here
		
	if ( isset( $_POST['bareskin_create_dev_style'] ) || isset( $_POST['bareskin_minify_dev_style'] ) ){
		$url = wp_nonce_url('themes.php?page=theme-settings');
		if (false === ($creds = request_filesystem_credentials($url, $method, false, false, $form_fields) ) ) {
		
			// if we get here, then we don't have credentials yet,
			// but have just produced a form for the user to fill in, 
			// so stop processing for now
			
			return true; // stop the normal page form from displaying
		}
			
		// now we have some credentials, try to get the wp_filesystem running
		if ( ! WP_Filesystem($creds) ) {
			// our credentials were no good, ask the user for them again
			request_filesystem_credentials($url, $method, true, false, $form_fields);
			return true;
		}
	}
	
	/* Create dev style from original style */
	if ( isset( $_POST['bareskin_create_dev_style'] ) ) {
				
		$filename_orig = trailingslashit( get_stylesheet_directory()  ).'style.css';
		$filename_dev = trailingslashit( get_stylesheet_directory()  ).'style.dev.css';

		// by this point, the $wp_filesystem global should be working, so let's use it to create a file
		global $wp_filesystem;
		
		$style_orig = $wp_filesystem->get_contents( $filename_orig );
		if ( ! $wp_filesystem->put_contents( $filename_dev, $style_orig, FS_CHMOD_FILE) ) {
			wp_die( __( 'Couldn\'t read data from style.css', bareskin_get_textdomain() ) );
		}
	}
	
	if ( isset( $_POST['bareskin_minify_dev_style'] ) ) {
	
		$filename_orig = trailingslashit( get_stylesheet_directory()  ).'style.css';
		$filename_dev = trailingslashit( get_stylesheet_directory()  ).'style.dev.css';

		// by this point, the $wp_filesystem global should be working, so let's use it to create a file
		global $wp_filesystem;
	
		/* Set option to preserve important comments ( comments that begin with /*! ) */
		$options = array( 'preserveComments' );
		
		/* Get contents of style.dev.css */
		if( $style = $wp_filesystem->get_contents( $filename_dev ) ){
			
			/* Minify the contents */
			$style = Minify_CommentPreserver::process( $style, array('Minify_CSS_Compressor', 'process') ,array($options) );
			
			/* Get theme header */	 
			$start = strpos($style, '/*'); 
            $end = strpos($style, '*/', $start + 2);
			 
			$theme_header = '/*' .substr($style, $start + 2, $end - $start) ;
			
			/* Make shure all new lines were removed. Apperantly minify doesn't strip all new lines */		
			$style = preg_replace('(\r|\n|\t)', ' ', $style);	
			/* Remove the theme header that was preserved initialy because now it is only on one line */
			$style = preg_replace('(/\*.*?\*/)', "\r\n", $style);
			/* Add the initial theme header that contains new lines  */
			$style = $theme_header.$style;
			
			/* Write the minified contents to style.css */			
			if ( ! $wp_filesystem->put_contents( $filename_orig, $style, FS_CHMOD_FILE) ) {
				wp_die( __( 'Couldn\'t write data to style.css', bareskin_get_textdomain() ) );
			}
		}
		else wp_die( __( 'Couldn\'t read data from style.dev.css', bareskin_get_textdomain() ) );
	}	
	
	/* Return the theme settings. */
	return $settings;

}