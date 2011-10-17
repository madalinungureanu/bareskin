<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage BareSkin
 *
 */
?>

		</div><!-- #main -->
	</div><!-- #main-wrapper -->		
	
	<?php get_sidebar( 'subsidiary' );?>
	
	<footer id="colophon" role="contentinfo">
		<div id="inner-footer">
			<div id="site-generator">
				<?php echo do_shortcode( bareskin_get_setting( 'footer_insert' ) ); ?>
			</div>
		</div><!-- #inner-footer -->
	</footer><!-- #colophon -->
</div><!-- #wrapper -->

<?php wp_footer(); ?>

</body>
</html>