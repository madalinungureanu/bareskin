<?php
/**
 * Creates a meta box for the theme settings page, which holds css color variations for 
 * the theme.  To use this feature, the theme must support the 'color-variations' argument for the 
 * 'bareskin-theme-settings' feature.
 *
 * @package BareSkin
 * @subpackage Admin
 */

/* Create the color variations meta box on the 'add_meta_boxes' hook. */
add_action( 'add_meta_boxes', 'bareskin_meta_box_theme_add_color_variations' );

/* Hook function to remove the css */
add_filter( bareskin_get_prefix().'_add_to_validation', 'bareskin_meta_box_theme_remove_css' );


/**
 * Adds the color variations meta box to the theme settings page in the admin.
 *
 * @since 1.0.0
 */
function bareskin_meta_box_theme_add_color_variations() {
	global $bareskin_settings_page;
	
	if( $bareskin_settings_page != null )
		add_meta_box( 'bareskin-meta-box-color-variations', __( 'Color Variations', bareskin_get_textdomain() ), 'bareskin_meta_box_theme_display_color_variations', $bareskin_settings_page, 'normal', 'high' );
}

/**
 * Creates a settings box that allows users to select color variations for the theme. 
 *
 * @since 1.0.0
 */
function bareskin_meta_box_theme_display_color_variations() {
	$domain = bareskin_get_textdomain(); 
	
	/* Get all files in the css dir from the theme directory */
	
	
	$css_list = array();
	
	if( $files = list_files( trailingslashit( get_stylesheet_directory() ) . 'css' , 1 ) ){
		foreach( $files as $file ){
			if( is_file( $file ) ){
				$file_info = pathinfo( $file );
				if( $file_info[ 'extension' ] == 'css' ){				
					$css_info = array( 'file_path' => $file, 'filename' => $file_info[ 'filename' ] );
					
					if( file_exists( $file_info[ 'dirname' ] . '/' . $file_info[ 'filename' ] . '.png' ) )
						$css_info[ 'img_thumb' ]  = $file_info[ 'dirname' ] . '/' . $file_info[ 'filename' ] . '.png';
					
					$css_list[] = $css_info;					
					
				}
			}
		}
	}	
	
	?>
	<p>
		<span class="description"><?php _e( 'You can choose one of the following color variations below:', $domain ); ?></span>
	</p>
	
	<div style="overflow:hidden;">
		<?php if( !empty( $css_list ) ){
				foreach($css_list as $css){
		?>		
					<div style="float: left; margin-right: 14px; margin-bottom: 18px;">
						<input type="radio" name="<?php echo bareskin_settings_field_name( 'color_variation' ) ?>" value="<?php echo $css[ 'filename' ];?>" id="<?php echo $css[ 'filename' ];?>" <?php checked( bareskin_get_setting( 'color_variation' ), $css[ 'filename' ] ); ?>/>						
						<label for="<?php echo $css[ 'filename' ]; ?>" style="margin-top: 4px; float: left; clear: both;">
							<?php 			
							if( isset( $css[ 'img_thumb' ] ) ){
								$img_path = str_replace( ABSPATH, '', $css[ 'img_thumb' ] );
								$img_path = trailingslashit( home_url() ). $img_path;
								echo "<img src='".$img_path."' width='122' height='136' style='margin-bottom:5px;'/><br />";
							}
							?>
							<span style="margin-top: 8px; float: left;" class="description"><?php echo $css[ 'filename' ]; ?></span>
						</label>
					</div>	
		<?php
				}
			}
		?>
		
	</div>
	
	<p>
		<span class="description"><?php _e( 'You can add color variation css in the folder css from the theme dir. Optionally you can add a sceenshot for that css. The screenshot must have the same name as the css and the extension .png', $domain ); ?></span>
	</p>
	
	<p>
		<input type="submit" class="button" value="<?php _e( 'Remove Css', $domain) ?>" name="bareskin_remove_css" />
	</p>
	<?php
}

/**
 * Check if Remove Css was pressed and remove the option.
 *
 * @since 1.0.0
 * @param array $settings Array of theme settings passed by the Settings API for validation.
 * @return array $settings
 */
function bareskin_meta_box_theme_remove_css( $settings ){

	if ( isset( $_POST['bareskin_remove_css'] ) ) {
		$settings['color_variation'] = '';
	}
	
	/* Return the theme settings. */
	return $settings;

}
?>