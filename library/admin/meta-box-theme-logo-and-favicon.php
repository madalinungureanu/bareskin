<?php
/**
 * Creates a meta box for the theme settings page, which holds an upload for theme logo and a upload for theme 
 * favicon.  
 * To use this feature, the theme must support the 'logo-favicon' argument for the 'bareskin-theme-settings' feature.
 *
 * @package BareSkin
 * @subpackage Admin
 */
 
 /* Create the logo-and-favicon meta box on the 'add_meta_boxes' hook. */
add_action( 'add_meta_boxes', 'bareskin_meta_box_theme_add_logo_and_favicon' );

/* Sanitize the logo and favicon upload before adding them to the database. */
add_filter( bareskin_get_prefix().'_add_to_validation', 'bareskin_meta_box_theme_save_logo_and_favicon' );


/**
 * Adds the theme logo-and-favicon meta box to the theme settings page in the admin.
 *
 * @since 1.0.0
 */
function bareskin_meta_box_theme_add_logo_and_favicon() {
	global $bareskin_settings_page;
	
	if( $bareskin_settings_page != null )
		add_meta_box( 'bareskin-meta-box-logo-and-favicon', __( 'Logo and Favicon', bareskin_get_textdomain() ), 'bareskin_meta_box_theme_display_logo_and_favicon', $bareskin_settings_page, 'normal', 'high' );
}

/**
 * Creates a settings box that allows users to upload a custom logo and favicon.
 *
 * @since 1.0.0
 */
function bareskin_meta_box_theme_display_logo_and_favicon() {
	$domain = bareskin_get_textdomain(); ?>	
	
	<input type="hidden" name="<?php echo bareskin_settings_field_name( 'logo_source' ); ?>" value="<?php echo esc_url( bareskin_get_setting( 'logo_source' ) ); ?>" />
	<?php if ( bareskin_get_setting( 'logo_source' ) ): ?>
		<img src="<?php echo esc_url( bareskin_get_setting( 'logo_source' ) ); ?>" />
		<p>
			<input type="submit" class="button" value="<?php _e( 'Remove logo', $domain) ?>" name="bareskin_remove_logo" />
			<span class="description"><?php _e( 'The site name will be displayed instead.', $domain ); ?></span>
		</p>
	<?php endif; ?>

	<input type="file" id="bareskin_logo_upload" name="bareskin_logo_upload" />
	<label for="bareskin_resize_logo"><input type="checkbox" id="bareskin_resize_logo" name="bareskin_resize_logo" value='truethat' /> <?php _e( 'Resize', $domain ); ?></label>
	<input type="submit" class="button" value="<?php _e( 'Upload logo', $domain) ?>" name="bareskin_upload_logo" /><br />
	
	<p class="description"><?php _e( 'Use a PNG, JPEG or GIF image. We can also resize it for you.', $domain ); ?></p>
	
	<input type="hidden" name="<?php echo bareskin_settings_field_name( 'favicon_source' ); ?>" value="<?php echo esc_url( bareskin_get_setting( 'favicon_source' ) ); ?>" />
	<input type="hidden" name="<?php echo bareskin_settings_field_name( 'favicon_attachment_id' ); ?>" value="<?php echo esc_attr( bareskin_get_setting( 'favicon_attachment_id' ) ); ?>" />
	<?php if ( bareskin_get_setting( 'favicon_source' ) ): ?>
		<img src="<?php echo esc_url( bareskin_get_setting( 'favicon_source' ) ); ?>" />
		<p>
			<input type="submit" class="button" value="<?php _e( 'Remove favicon', $domain) ?>" name="bareskin_remove_favicon" />
		</p>
	<?php endif; ?>

	<input type="file" id="bareskin_favicon_upload" name="bareskin_favicon_upload" />	
	<input type="submit" class="button" value="<?php _e( 'Upload favicon', $domain) ?>" name="bareskin_upload_favicon" /><br />
	
	<p class="description"><?php _e( 'Use a 16x16 ICO.', $domain ); ?></p>
	
	<?php
}

/**
 * Saves the footer meta box settings by filtering the "{$prefix}_add_to_validation" hook.
 *
 * @since 1.0.0
 * @param array $settings Array of theme settings passed by the Settings API for validation.
 * @return array $settings
 */
function bareskin_meta_box_theme_save_logo_and_favicon( $settings ) {
	// Logo Uploading
	if( isset( $_POST['bareskin_upload_logo'] ) ){	
		
		if ( ! empty( $_FILES ) && isset( $_FILES['bareskin_logo_upload']['error'], $_FILES['bareskin_logo_upload']['size'] ) && $_FILES['bareskin_logo_upload']['error'] == 0 && $_FILES['bareskin_logo_upload']['size'] > 0 ) {
		
			// Not checking for nonce, Settings API will do that
			$file = wp_handle_upload( $_FILES['bareskin_logo_upload'], array( 'test_form' => false ) );
			
			if ( isset( $file['error'] ) )
				wp_die( $file['error'] );
			
			$url = $file['url'];
			$type = $file['type'];
			$file = $file['file'];
			$filename = basename( $file );

			// Make sure it's an image
			if ( ! in_array( $type, array( 'image/png', 'image/gif', 'image/jpeg' ) ) )
				wp_die( __( 'Invalid image format. Expected jpeg, gif or png.', bareskin_get_textdomain() ) );

			// Create an attachment post
			$object = array(
				'post_title' => $filename,
				'post_content' => $url,
				'post_mime_type' => $type,
				'guid' => $url,
				'context' => 'custom-background'
			);

			// Save the data
			$id = wp_insert_attachment( $object, $file );

			// Add the meta-data (does the resizing too)
			wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );
			
			
			
			// Let's see if the user has asked to resize their logo
			if ( isset( $_POST['bareskin_resize_logo'] ) ) {
				$file_path = wp_get_attachment_url( $id );
				$path = $image_path = image_resize( $file, 350, 150, $file_path );	
				
				$path = str_replace( ABSPATH, '', $path );
				$url = trailingslashit( home_url() ). $path;
				
			}
			
			// Save the URL to the image.
			$settings['logo_source'] = $url;			
			
			unset( $_FILES );
			
		}		
	}
	
	if ( isset( $_POST['bareskin_remove_logo'] ) ) {
		$settings['logo_source'] = '';
	}
	
	/* Favicon upload */
	if( isset( $_POST['bareskin_upload_favicon'] ) ){	
		
		if ( ! empty( $_FILES ) && isset( $_FILES['bareskin_favicon_upload']['error'], $_FILES['bareskin_favicon_upload']['size'] ) && $_FILES['bareskin_favicon_upload']['error'] == 0 && $_FILES['bareskin_favicon_upload']['size'] > 0 ) {
			if( $_FILES['bareskin_favicon_upload']["name"] != 'favicon.ico')
				wp_die( __( 'Invalid favicon name. Expected favicon.ico', bareskin_get_textdomain() ) );
				
			if( !empty( $settings['favicon_attachment_id'] ) )
				wp_delete_attachment( $settings['favicon_attachment_id'], true );
			// Not checking for nonce, Settings API will do that
			$file = wp_handle_upload( $_FILES['bareskin_favicon_upload'], array( 'test_form' => false ) );
			
			if ( isset( $file['error'] ) )
				wp_die( $file['error'] );
			
			$url = $file['url'];
			$type = $file['type'];
			$file = $file['file'];
			$filename = basename( $file );

			// Make sure it's an image
			if ( ! in_array( $type, array( 'image/x-icon' ) ) )
				wp_die( __( 'Invalid image format. Expected ico.', bareskin_get_textdomain() ) );

			// Create an attachment post
			$object = array(
				'post_title' => $filename,
				'post_content' => $url,
				'post_mime_type' => $type,
				'guid' => $url,
				'context' => 'custom-background'
			);

			// Save the data
			$id = wp_insert_attachment( $object, $file );

			// Add the meta-data (does the resizing too)
			wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );
			
			// Save the URL to the image and the attachment id.
			$settings['favicon_source'] = $url;
			$settings['favicon_attachment_id'] = $id;
			unset( $_FILES );
			
		}		
	}
	
	if ( isset( $_POST['bareskin_remove_favicon'] ) ) {
		wp_delete_attachment( $settings['favicon_attachment_id'], true );
		$settings['favicon_source'] = '';
	}
	
	/* Return the theme settings. */
	return $settings;
} 
 ?>