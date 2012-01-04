<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage BareSkin
 * 
 */

get_header(); ?>
		
		<?php if( function_exists( 'breadcrumb_trail' ) ) breadcrumb_trail(); ?>
		
		<div id="container">
			
			<div id="content" role="main">

			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>				

				<?php get_sidebar( 'before-content' );?>
				
				<?php get_template_part( 'content', 'single' ); ?>

				<?php get_template_part( 'loop-nav' ); ?>
				
				<?php get_sidebar( 'after-content' );?>

				<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>