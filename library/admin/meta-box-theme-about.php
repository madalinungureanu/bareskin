<?php
/**
 * Creates a meta box for the theme settings page, which displays information about the theme.
 * To use this feature, the theme must support the 'about' argument for 'bareskin-theme-settings' feature.
 *
 * @package BareSkin
 * @subpackage Admin
 */

/* Create the about theme meta box on the 'add_meta_boxes' hook. */
add_action( 'add_meta_boxes', 'bareskin_meta_box_theme_add_about' );

/**
 * Adds the core about theme meta box to the theme settings page.
 *
 * @since 1.0.0
 * @global string $bareskin_settings_page The global setting page (returned by add_theme_page in function
 * bareskin_settings_page_init ).
 */
function bareskin_meta_box_theme_add_about() {
	global $bareskin_settings_page;
	/* Get theme information. */
	$prefix = bareskin_get_prefix();
	$domain = bareskin_get_textdomain();
	$theme_data = get_theme_data( trailingslashit( STYLESHEETPATH ) . 'style.css' );

	/* Adds the About box for the theme. */
	if( $bareskin_settings_page != null )
		add_meta_box( 'bareskin-about-theme', sprintf( __( 'About %1$s', $domain ), $theme_data['Title'] ), 'bareskin_meta_box_theme_display_about', $bareskin_settings_page, 'side', 'high' );	

}

/**
 * Creates an information meta box with no settings about the theme. 
 *
 * @since 1.0.0
 * @param $object Variable passed through the do_meta_boxes() call.
 * @param array $box Specific information about the meta box being loaded.
 */
function bareskin_meta_box_theme_display_about( $object, $box ) {

	/* Get theme information. */
	$prefix = bareskin_get_prefix();
	$domain = bareskin_get_textdomain();
	$theme_data = get_theme_data( trailingslashit( STYLESHEETPATH ) . 'style.css' );
	
	?>

	<table class="form-table">
		<tr>
			<th>
				<?php _e( 'Theme:', $domain ); ?>
			</th>
			<td>
				<a href="<?php echo $theme_data['URI']; ?>" title="<?php echo $theme_data['Title']; ?>"><?php echo $theme_data['Title']; ?></a>
			</td>
		</tr>
		<tr>
			<th>
				<?php _e( 'Version:', $domain ); ?>
			</th>
			<td>
				<?php echo $theme_data['Version']; ?>
			</td>
		</tr>
		<tr>
			<th>
				<?php _e( 'Author:', $domain ); ?>
			</th>
			<td>
				<?php echo $theme_data['Author']; ?>
			</td>
		</tr>
		<tr>
			<th>
				<?php _e( 'Description:', $domain ); ?>
			</th>
			<td>
				<?php echo $theme_data['Description']; ?>
			</td>
		</tr>
	</table><!-- .form-table --><?php
}
?>