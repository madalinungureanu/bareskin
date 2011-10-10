<?php
/**
 * The Sidebar containing the subsidiary widget areas.
 *
 * @package WordPress
 * @subpackage BareSkin
 * 
 */
?>
		<?php if ( is_active_sidebar( 'subsidiary' ) || is_active_sidebar( 'subsidiary2' ) || is_active_sidebar( 'subsidiary3' ) ) : ?>
			<div id="subsidiary-area">
				<div id="subsidiary-container">
				
					<div id="subsidiary" class="widget-area" role="complementary">
						<?php dynamic_sidebar( 'subsidiary' ); ?>
					</div><!-- #subsidiary .widget-area -->
					
					<div id="subsidiary-second" class="widget-area" role="complementary">
						<?php dynamic_sidebar( 'subsidiary2' ); ?>
					</div><!-- #subsidiary .widget-area -->
					
					<div id="subsidiary-third" class="widget-area" role="complementary">
						<?php dynamic_sidebar( 'subsidiary3' ); ?>
					</div><!-- #subsidiary .widget-area -->
					
					
				</div><!-- #subsidiary-container -->
			</div><!-- #subsidiary-area -->
		<?php endif; ?>