<?php
/**
 * Creates a meta box for the theme settings page, which holds a textarea for custom footer text within 
 * the theme.  To use this feature, the theme must support the 'footer' argument for the 
 * 'bareskin-theme-settings' feature.
 *
 * @package BareSkin
 * @subpackage Admin
 */

/* Create the footer meta box on the 'add_meta_boxes' hook. */
add_action( 'add_meta_boxes', 'bareskin_meta_box_theme_add_footer' );

/* Sanitize the footer settings before adding them to the database. */
add_filter( bareskin_get_prefix().'_add_to_validation', 'bareskin_meta_box_theme_save_footer' );

/**
 * Adds the core theme footer meta box to the theme settings page in the admin.
 *
 * @since 1.0.0
 */
function bareskin_meta_box_theme_add_footer() {
	global $bareskin_settings_page;
	add_meta_box( 'bareskin-core-meta-box-footer', __( 'Footer settings', bareskin_get_textdomain() ), 'bareskin_meta_box_theme_display_footer', $bareskin_settings_page, 'normal', 'high' );
}

/**
 * Creates a settings box that allows users to customize their footer. A basic textarea is given that
 * allows HTML and shortcodes to be input.
 *
 * @since 1.0.0
 */
function bareskin_meta_box_theme_display_footer() {
	$domain = bareskin_get_textdomain(); ?>

	<p>
		<span class="description"><?php _e( 'You can add custom <acronym title="Hypertext Markup Language">HTML</acronym> and/or shortcodes, which will be automatically inserted into your theme.', $domain ); ?></span>
	</p>

	<p>
		<textarea id="<?php echo bareskin_settings_field_id( 'footer_insert' ); ?>" name="<?php echo bareskin_settings_field_name( 'footer_insert' ); ?>" cols="60" rows="5"><?php echo esc_textarea( bareskin_get_setting( 'footer_insert' ) ); ?></textarea>
	</p>

	
	<?php if ( current_theme_supports( 'bareskin-shortcodes' ) ) { ?>
		<p>
			<?php printf( __( 'Shortcodes: %s', $domain ), '<code>[the-year]</code>, <code>[site-link]</code>, <code>[loginout-link]</code>, <code>[query-counter]</code>' ); ?>
		</p>
	<?php } 
	
}

/**
 * Saves the footer meta box settings by filtering the "{$prefix}_add_to_validation" hook.
 *
 * @since 1.0.0
 * @param array $settings Array of theme settings passed by the Settings API for validation.
 * @return array $settings
 */
function bareskin_meta_box_theme_save_footer( $settings ) {
	
	/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
	if ( isset( $settings['footer_insert'] ) && !current_user_can( 'unfiltered_html' ) )
		$settings['footer_insert'] = stripslashes( wp_filter_post_kses( addslashes( $settings['footer_insert'] ) ) );	
		
	/* Return the theme settings. */
	return $settings;
}
?>