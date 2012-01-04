<?php
/**
 * Creates a meta box for the theme settings page, which displays a selection for primary font and secondary font.
 * To use this feature, the theme must support the 'font' argument for 'bareskin-theme-settings' feature.
 *
 * @package BareSkin
 * @subpackage Admin
 */

/* Create the about theme meta box on the 'add_meta_boxes' hook. */
add_action( 'add_meta_boxes', 'bareskin_meta_box_theme_add_font' );

/**
 * Adds the meta box for font selection to the theme settings page.
 *
 * @since 1.0.0
 * @global string $bareskin_settings_page The global setting page (returned by add_theme_page in function
 * bareskin_settings_page_init ).
 */
function bareskin_meta_box_theme_add_font() {
	global $bareskin_settings_page;
	/* Get theme information. */
	$prefix = bareskin_get_prefix();
	$domain = bareskin_get_textdomain();	

	/* Adds the About box for the parent theme. */
	add_meta_box( 'bareskin-theme-font', __( 'Font Selection', $domain ), 'bareskin_meta_box_theme_display_font', $bareskin_settings_page, 'side', 'high' );	

}

/**
 * Creates a meta box with selects for the theme font. 
 *
 * @since 1.0.0
 * @param $object Variable passed through the do_meta_boxes() call.
 * @param array $box Specific information about the meta box being loaded.
 */
function bareskin_meta_box_theme_display_font( $object, $box ) {

	/* Get theme information. */
	$prefix = bareskin_get_prefix();
	$domain = bareskin_get_textdomain();
	
	$fonts = bareskin_get_available_fonts();	
	
	
	$pr_font = bareskin_get_setting( 'primary_font' );
	if ( !empty( $pr_font ) ){		
		$current_primary_font = bareskin_get_setting( 'primary_font' );
	}
	
	$se_font = bareskin_get_setting( 'secondary_font' );
	if ( !empty( $se_font ) ){		
		$current_secondary_font = bareskin_get_setting( 'secondary_font' );
	}	
	
	?>
	<select id="<?php echo bareskin_settings_field_id( 'primary_font' ); ?>" name="<?php echo bareskin_settings_field_name( 'primary_font' ); ?>">
	<?php foreach( $fonts as $font_key => $font ): ?>
		<option <?php if( $font_key == $current_primary_font ) echo "selected='selected'"; ?> value="<?php echo $font_key; ?>"><?php echo $font['name']; ?></option>
	<?php endforeach; ?>
	</select>
	<br class="clear" />
	<span class="description" style="margin:8px 0;display:block">
		<?php _e( 'Primary Font. The selected font will be used for most titles and buttons.', $domain ); ?>
	</span>
	
	<select id="<?php echo bareskin_settings_field_id( 'secondary_font' ); ?>" name="<?php echo bareskin_settings_field_name( 'secondary_font' ); ?>">
	<?php foreach( $fonts as $font_key => $font ): ?>
		<option <?php if( $font_key == $current_secondary_font ) echo "selected='selected'"; ?> value="<?php echo $font_key; ?>"><?php echo $font['name']; ?></option>
	<?php endforeach; ?>
	</select>
	<br class="clear" />
	<span class="description" style="margin:8px 0;display:block">
		<?php _e( 'Secondary Font. The selected font will be used in your content.', $domain ); ?>
	</span>
	<?php
}

?>