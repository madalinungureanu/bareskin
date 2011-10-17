<?php
/**
 * The Sidebar containing the before-content widget area.
 *
 * @package WordPress
 * @subpackage BareSkin
 * 
 */
?>
		<?php if ( is_active_sidebar( 'before-content' ) ) : ?>					
				
			<div id="before-content" class="widget-area" role="complementary">
				<?php dynamic_sidebar( 'before-content' ); ?>
			</div><!-- #before-content .widget-area -->						
			
		<?php endif; ?>