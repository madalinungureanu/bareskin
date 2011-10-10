<?php 
/*
 * Available Fonts
 *
 * This function returns available fonts for Theme Option typography
 * options. The function is used both for primary and secondary fonts
 * You can add your own fonts.
 *
 */
 
function bareskin_get_available_fonts() {
	$fonts = array(
		'pt-sans' => array(
			'name' => 'PT Sans narrow',
			'import' => '@import url(http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700);',
			'css' => "font-family: 'PT Sans narrow', sans-serif;%;",
			'font-size' => '13px'
		),
		'lato' => array(
			'name' => 'Lato',
			'import' => '@import url(http://fonts.googleapis.com/css?family=Lato);',
			'css' => "font-family: 'Lato', sans-serif;",
			'font-size' => '13px'
		),
		'oswald' => array(
			'name' => 'Oswald',
			'import' => '@import url(http://fonts.googleapis.com/css?family=Oswald);',
			'css' => "font-family: 'Oswald', sans-serif;",
			'font-size' => '13px'
		),
		'arial' => array(
			'name' => 'Arial',
			'import' => '',
			'css' => "font-family: Arial, sans-serif;",
			'font-size' => '13px'
		),
		'trebuchet' => array(
			'name' => 'Trebuchet MS',
			'import' => '',
			'css' => "font-family: 'Trebuchet MS', sans-serif;",
			'font-size' => '13px'
		)
	);

	return apply_filters( 'bareskin_available_fonts', $fonts );
}

/*
 * Custom Fonts Output
 * 
 * Print style for custom fonts. Outputs style for Primary and Secondary fonts.
 *
 */
function bareskin_custom_fonts() {

	// Getting an array of available fonts
	$fonts = bareskin_get_available_fonts();
	
	
	// Get primary and secondary font choosed in options
	$primary_font = bareskin_get_setting( 'primary_font' );
	$secondary_font = bareskin_get_setting( 'secondary_font' );
	if ( isset( $primary_font ) )
		$current_prime_font_key = $primary_font;
	
	if ( isset( $secondary_font ) )
		$current_sec_font_key = $secondary_font;
	
	// Check if primary and secondary fonts are the same and load font one time.
	if ( $current_sec_font_key == $current_prime_font_key ) {
		$current_font_key = $current_prime_font_key;
		if ( isset( $fonts[$current_font_key] ) ) {
			$current_font = $fonts[$current_font_key];

			echo '<style>';
			echo $current_font['import'];
			echo 'body { font-size: ' . $current_font['font-size'] . '; } ';
			echo 'body * { ' . $current_font['css'] . ' } ';
			echo 'code, pre, tt, kbd { font-family: monospace }';
			echo '</style>';
		}
	} else {
		
		// Print font styles for primary and secondary elements.
		if ( isset( $fonts[$current_prime_font_key] ) || isset( $fonts[$current_sec_font_key] ) ) {
			$current_prime_font = $fonts[$current_prime_font_key];
			$current_sec_font = $fonts[$current_sec_font_key];

			echo '<style>';
			echo $current_prime_font['import'];
			echo $current_sec_font['import'];
			echo 'body { font-size: ' . $current_sec_font['font-size'] . '; } ';
			echo 'body * {' . $current_sec_font['css'] . ' }';
			echo ' .site-title a, .page-title, .page-title span, .entry-title, .entry-title a, article .more-link, .comment-reply-link, .widget-title { ' . $current_prime_font['css'] . ' } ';
			echo 'code, pre, tt, kbd { font-family: monospace }';
			echo '</style>';
		}
	}
}	
?>