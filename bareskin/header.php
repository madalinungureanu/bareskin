<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage BareSkin
 *
 */
?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', bareskin_get_textdomain() ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<?php 
	/* Get theme-supported meta boxes for the settings page. */
	$supports = get_theme_support( 'bareskin-theme-settings' );
	
	if ( in_array( 'style-minify', $supports[0] ) ){
		$style_to_use = bareskin_get_setting( 'style_to_use' );
		if( $style_to_use ==  'style' || $style_to_use === false ){
?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_uri().'?'.bareskin_get_filemtime('stylesheet'); ?>" />
<?php 
		}
		else{
?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_directory_uri().'/style.dev.css?'.bareskin_get_filemtime('style.dev.css'); ?>" />
<?php 
		}
	}
	else{
?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_uri().'?'.bareskin_get_filemtime('stylesheet'); ?>" />
<?php 	
	}	
?>
<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="wrapper" class="hfeed">
	<header id="branding" role="banner">
		<div id="inner-header">
			<hgroup>
				<h1 id="site-title">
					<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
						<?php if( bareskin_get_setting( 'logo_source' ) ): ?>
							<img src="<?php echo bareskin_get_setting( 'logo_source' ) ?>" width="350" height="150"/>
						<?php else: ?>						
							<?php bloginfo( 'name' ); ?>
						<?php endif; ?>
					</a>
				</h1>
				<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
			</hgroup>
		
			<?php get_template_part( 'menu', 'primary' ); // Loads the menu-primary.php template. ?>
		</div><!-- #inner-header -->		
	</header><!-- #branding -->
	<div id="main-wrapper">
		<div id="main">