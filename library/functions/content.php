<?php
/**
 * The content functions file for the BareSkin theme. Functions defined here are generally
 * used in the content template. 
 *
 * @package BareSkin
 * @subpackage Functions
 */
 
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since 1.0.0
 */
function bareskin_entry_meta() {
	if ( 'post' == get_post_type() ){
		?>
		<div class="entry-meta">
		<?php 
		printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="byline"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', bareskin_get_textdomain() ),
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			sprintf( esc_attr__( 'View all posts by %s', bareskin_get_textdomain() ), get_the_author() ),
			esc_html( get_the_author() )
		);
		?>
		</div><!-- .entry-meta -->
		<?php
	}
}


/**
 * Prints HTML with entry-utility information for the current post.
 *
 * @since 1.0.0
 */
function bareskin_entry_utility() {
	?>
	<footer class="entry-utility">
		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php				
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', bareskin_get_textdomain() ) );
				if ( $categories_list && bareskin_categorized_blog() ) :
			?>
			<span class="cat-links">
				<?php printf( __( 'Posted in %1$s', bareskin_get_textdomain() ), $categories_list ); ?>
			</span>
			<span class="sep"> | </span>			
			<?php endif; // End if categories ?>
			
			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', bareskin_get_textdomain() ) );
				if ( $tags_list ) :
			?>
			<span class="tag-links">
				<?php printf( __( 'Tagged %1$s', bareskin_get_textdomain() ), $tags_list ); ?>
			</span>
			<span class="sep"> | </span>
			<?php endif; // End if $tags_list ?>
		<?php endif; // End if 'post' == get_post_type() ?>
		
		<?php if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
		<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', bareskin_get_textdomain() ), __( '1 Comment', bareskin_get_textdomain() ), __( '% Comments', bareskin_get_textdomain() ) ); ?></span>
		<span class="sep"> | </span>
		<?php endif; ?>
		
		<?php edit_post_link( __( 'Edit', bareskin_get_textdomain() ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-utility -->
	<?php
}

/**
 * Returns true if a blog has more than 1 category
 *
 * @since BareSkin 1.0.0
 */
function bareskin_categorized_blog() {
	if ( false === ( $bareskin_blog_categories = get_transient( 'bareskin_blog_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$bareskin_blog_categories = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$bareskin_blog_categories = count( $bareskin_blog_categories );

		set_transient( 'bareskin_blog_categories', $bareskin_blog_categories );
	}

	if ( '1' != $bareskin_blog_categories ) {
		// This blog has more than 1 category so bareskin_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so bareskin_categorized_blog should return false
		return false;
	}
}
?>