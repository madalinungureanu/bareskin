<?php
/**
 * Theme administration functions used with other components of the theme admin.  This file is for 
 * setting up any basic features and holding additional admin helper functions.
 *
 * @package BareSkin
 * @subpackage Admin
 */
 
 /**
 * Creates a settings field id attribute for use on the theme settings page.  This is a helper function for use
 * with the WordPress settings API.
 *
 * @since 1.0.0
 * @param string $setting. The name for the setting
 */
function bareskin_settings_field_id( $setting ) {
	return bareskin_get_prefix() . "_theme_settings-{$setting}";
}

/**
 * Creates a settings field name attribute for use on the theme settings page.  This is a helper function for 
 * use with the WordPress settings API.
 *
 * @since 1.0.0
 * @param string $setting. The name for the setting
 */
function bareskin_settings_field_name( $setting ) {
	return bareskin_get_prefix() . "_theme_settings[{$setting}]";
}
?>