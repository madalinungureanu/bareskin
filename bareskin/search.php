<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage BareSkin 
 */

get_header(); ?>

		<section id="container">
			<div id="content" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title"><?php printf( __( 'Search Results for: %s', bareskin_get_textdomain() ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				</header>
				
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'search' ); ?>

				<?php endwhile; ?>

				<?php get_template_part( 'loop-nav' ); ?>

			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', bareskin_get_textdomain() ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', bareskin_get_textdomain() ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

			</div><!-- #content -->
		</section><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>