<?php
/**
 * The Sidebar containing the after-content widget area.
 *
 * @package WordPress
 * @subpackage BareSkin
 * 
 */
?>
		<?php if ( is_active_sidebar( 'after-content' ) ) : ?>					
				
			<div id="after-content" class="widget-area" role="complementary">
				<?php dynamic_sidebar( 'after-content' ); ?>
			</div><!-- #after-content .widget-area -->						
			
		<?php endif; ?>