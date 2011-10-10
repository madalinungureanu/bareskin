## Adding new theme settings ## 

# Adding a box to the settings page:
 - you must use the "add_meta_boxes" action hook:
 
	add_action( 'add_meta_boxes', 'bareskin_meta_box_add_new_box' );
	
- either use the global $bareskin_settings_page or 'appearance_page_theme-settings'
- add_meta_box $context var can be 'normal', 'side' or 'advanced'

	function bareskin_meta_box_add_new_box() {		
		global $bareskin_settings_page;
		/* Get theme information. */
		$prefix = bareskin_get_prefix();
		$domain = bareskin_get_textdomain();
		
		add_meta_box( 'new-metabox-slug', __( 'New Meta Box', $domain ), 'bareskin_meta_box_new_box_display', $bareskin_settings_page, 'side', 'high' );	

	}
- the processing of the actual fields is done using the Settings API. No need to bother with nonce.
- you must use bareskin_settings_field_name('example-name') to generate a name attribute for the field, and  bareskin_get_setting('example-name') to retrive the value. 	
	function bareskin_meta_box_new_box_display(){
		$domain = bareskin_get_textdomain();
		?>
		<p>
			<span class="description"><?php _e( 'This is a new meta box .', $domain ); ?></span>
		</p>
		
		<p>
			<input type="text" id="<?php echo bareskin_settings_field_id( 'new_input_text' ); ?>" name="<?php echo bareskin_settings_field_name( 'new_input_text' ); ?>" value="<?php echo esc_attr( bareskin_get_setting( 'new_input_text' ) ); ?>" />
			<textarea id="<?php echo bareskin_settings_field_id( 'new_textarea' ); ?>" name="<?php echo bareskin_settings_field_name( 'new_textarea' ); ?>" cols="60" rows="5"><?php echo esc_textarea( bareskin_get_setting( 'new_textarea' ) ); ?></textarea>
		</p>
		<?php	
	}

# Optional: Sanitizing the settings or processing them in any unconventional way 

- use the '{$prefix}_add_to_validation' filter where prefix is the slug of your theme. You can retrieve it using bareskin_get_prefix();
 
	/* Sanitize the settings before adding them to the database. */
	add_filter( bareskin_get_prefix().'_add_to_validation', 'bareskin_new_meta_box_sanitize' );

- recieves $settings array and must return it
	function bareskin_new_meta_box_sanitize( $settings ){
	
		/* Make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( isset( $settings['new_textarea'] ) && !current_user_can( 'unfiltered_html' ) )
			$settings['new_textarea'] = stripslashes( wp_filter_post_kses( addslashes( $settings['new_textarea'] ) ) );
		
		return $settings;
	}

# Optional: add default settings

- use '{$prefix}_default_theme_settings' filter


## Adding menus 

- in the add_theme_support( 'bareskin-menus' ) call fom functions.php you must pass a second parameter like this:
	/*two menus will be registered: 'primary' and 'secondary' */
	add_theme_support( 'bareskin-menus', array( 'primary', 'secondary' ) );

## Adding sidebars

- use the 'bareskin_register_sidebars' filter like this

	add_filter('bareskin_register_sidebars', 'add_some_new_sidebars' );

	function add_some_new_sidebars($sidebars){
		$domain = bareskin_get_textdomain();
		
		$sidebars['new-sidebar'] = array(
				'name' =>	_x( 'New Sidebare', 'sidebar', $domain ),
				'description' =>	__( 'Loaded on singular post (page, attachment, etc.) views before the comments area.', $domain ));
		return $sidebars;
	}
- pass to the second argument of add_theme_support('bareskin-sidebars')' the sidebar id like so

	add_theme_support( 'bareskin-sidebars', array( 'new-sidebar' ) );
