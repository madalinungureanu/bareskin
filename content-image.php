<?php
/**
 * The template for displaying posts in the Image Post Format on index and archive pages
 *
 * Learn more: http://codex.wordpress.org/Post_Formats
 *
 * @package WordPress
 * @subpackage BareSkin
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', bareskin_get_textdomain() ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', bareskin_get_textdomain() ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', bareskin_get_textdomain() ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->

	<?php 
	/* entry-utility */
	if( function_exists( 'bareskin_entry_utility' ) ) bareskin_entry_utility();
	/* entry-utility */		
	?>
	
</article><!-- #post-<?php the_ID(); ?> -->
