<?php
/**
 * Creates a meta box for the theme settings page, which displays radio button selection on how the archive page is 
 * displayed.  To use this feature, the theme must  support the 'archive-display' argument for 
 * 'bareskin-theme-settings' * feature.
 *
 * @package BareSkin
 * @subpackage Admin
 */
 
 /* Create the archive-display theme meta box on the 'add_meta_boxes' hook. */
add_action( 'add_meta_boxes', 'bareskin_meta_box_add_archive_display' );

/**
 * Adds the core archive display meta box to the theme settings page.
 *
 * @since 1.0.0
 * @global string $bareskin_settings_page The global setting page (returned by add_theme_page in function
 * bareskin_settings_page_init ).
 */
function bareskin_meta_box_add_archive_display() {
	global $bareskin_settings_page;
	/* Get theme information. */
	$prefix = bareskin_get_prefix();
	$domain = bareskin_get_textdomain();

	/* Adds the Archive Display metabox for the theme. */
	add_meta_box( 'bareskin-archive-display', __( 'Archives Display', $domain ), 'bareskin_meta_box_archive_display', $bareskin_settings_page, 'side', 'high' );	

}

/**
 * Creates a meta box with settings for archives display.  
 *
 * @since 1.0.0
 * @param $object Variable passed through the do_meta_boxes() call.
 * @param array $box Specific information about the meta box being loaded.
 */
function bareskin_meta_box_archive_display( $object, $box ) {

	/* Get theme information. */
	$prefix = bareskin_get_prefix();
	$domain = bareskin_get_textdomain();	
	?>
	<p>
		<span class="description"><?php _e( 'You can choose how the theme will display your posts on your blog homepage and archives.', $domain ); ?></span>
	</p>

	<p>
		<input type="radio" name="<?php echo bareskin_settings_field_name( 'archive-display' ) ?>" value="show-content" id="show-content" <?php checked( bareskin_get_setting( 'archive-display' ), 'show-content' ); ?>/>
		<label for="show-content"><?php _e( 'Show Content', $domain )?></label>
		<input type="radio" name="<?php echo bareskin_settings_field_name( 'archive-display' ) ?>" value="show-excerpt-thumb" id="show-excerpt-thumb" <?php checked( bareskin_get_setting( 'archive-display' ), 'show-excerpt-thumb' ); ?>/>	
		<label for="show-excerpt-thumb"><?php _e( 'Show Excerpt and Thumbnail', $domain )?></label>		
	</p>
	<?php
}
 ?>