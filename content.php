<?php
/**
 * @package WordPress
 * @subpackage BareSkin
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', bareskin_get_textdomain() ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>	
		
		<?php 
		/* entry-meta */
		if( function_exists( 'bareskin_entry_meta' ) ) bareskin_entry_meta();
		/* entry-meta */		
		?>		
		
	</header><!-- .entry-header -->

	<?php if ( bareskin_get_setting( 'archive-display' ) == 'show-excerpt-thumb' ||  bareskin_get_setting( 'archive-display' ) == false ) : // By default or if show excerpt and thumb is chosen from the theme settings show excerpt ( and image thumbnail ) ?>
	<div class="entry-summary">
		<?php if( function_exists( 'get_the_image' ) ) get_the_image( array( 'image_class' => 'alignleft' ) ); ?>
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', bareskin_get_textdomain() ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', bareskin_get_textdomain() ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<?php 
	/* entry-utility */
	if( function_exists( 'bareskin_entry_utility' ) ) bareskin_entry_utility();
	/* entry-meta */		
	?>
	
</article><!-- #post-<?php the_ID(); ?> -->
