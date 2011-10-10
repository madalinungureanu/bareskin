<?php
/**
 * @package WordPress
 * @subpackage BareSkin
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>

		<?php 
		/* entry-meta */
		if( function_exists( 'bareskin_entry_meta' ) ) bareskin_entry_meta();
		/* entry-meta */		
		?>
		
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'toolbox' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->

	<?php 
	/* entry-utility */
	if( function_exists( 'bareskin_entry_utility' ) ) bareskin_entry_utility();
	/* entry-utility */		
	?>
	
</article><!-- #post-<?php the_ID(); ?> -->
