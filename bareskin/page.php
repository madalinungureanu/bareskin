<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage BareSkin
 */

get_header(); ?>

		<div id="container">
			<div id="content" role="main">
				
				<?php get_sidebar( 'before-content' );?>
				
				<?php the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>
				
				<?php get_sidebar( 'after-content' );?>

				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>