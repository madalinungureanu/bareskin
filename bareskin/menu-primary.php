<?php
/**
 * Primary Menu Template
 *
 * Displays the Primary Menu if it has active menu items.
 *
 * @package WordPress
 * @subpackage BareSkin
 */
?>

<nav id="access" role="navigation">		
	<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
	<div style="clear:both"></div>
</nav><!-- #access -->

