<?php
/**
 * Advanced Recent Posts widget class
 *
 * @since 1.0.0
 */
class BareSkin_Widget_Advanced_Recent_Posts extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_adv_recent_entries', 'description' => __( "The most recent posts on your site with options") );
		parent::__construct('adv-recent-posts', __('Advanced Recent Posts'), $widget_ops);
		$this->alt_option_name = 'widget_adv_recent_entries';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
		add_action( 'init', array(&$this, 'setup_image_sizes') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('widget_adv_recent_posts', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Posts') : $instance['title'], $instance, $this->id_base);
		if ( ! $number = absint( $instance['number'] ) )
 			$number = 10;			
		if ( ! $category = absint( $instance['category'] ) )
 			$number = -1;
		$posttype = $instance['posttype'];
		$thumbnail = $instance['thumbnail'];
		if ( ! $thumbnail_width = absint( $instance['thumbnail_width'] ) )
 			$thumbnail_width = 100;
		if ( ! $thumbnail_height = absint( $instance['thumbnail_height'] ) )
 			$thumbnail_height = 80;
		
		$args = array('posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true);
		if( $category != -1 )
			$args['cat'] = $category;
		if( $posttype != 'post' )
			$args['post_type'] = $posttype;
			
		$r = new WP_Query( $args );
		if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<ul>
		<?php  while ($r->have_posts()) : $r->the_post(); ?>
		<li>
		<?php if( $thumbnail == 1 && get_theme_support( 'bareskin-get-image' ) != false ): ?>			
			<a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>" class="recent-thumbnail" ><?php get_the_image( array( 'size' => 'advanced-recent-posts-'.$this->number, 'width' => $thumbnail_width, 'height' => $thumbnail_height ) ) ?></a>
		<?php endif; ?>
		<a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a>
		</li>
		<?php endwhile; ?>
		</ul>
		<?php echo $after_widget; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_adv_recent_posts', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['category'] = (int) $new_instance['category'];
		$instance['posttype'] = $new_instance['posttype'];
		$instance['thumbnail'] = (int) $new_instance['thumbnail'];
		$instance['thumbnail_width'] = (int) $new_instance['thumbnail_width'];
		$instance['thumbnail_height'] = (int) $new_instance['thumbnail_height'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_adv_recent_entries']) )
			delete_option('widget_adv_recent_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_adv_recent_posts', 'widget');
	}
	
	function setup_image_sizes( ){
		
		$all_widget_settings = $this->get_settings('thumbnail_width');
		$widget_settings = $all_widget_settings[$this->number];
		
		if( get_theme_support( 'bareskin-get-image' ) != false && $widget_settings['thumbnail'] == 1 ){
			add_image_size('advanced-recent-posts-'.$this->number, $widget_settings['thumbnail_width'], $widget_settings['thumbnail_height'], true );
		}
		
	}

	function form( $instance ) {
		$posttypes = get_post_types('', 'objects');
		
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
		$category = isset($instance['category']) ? (int)$instance['category'] : -1;
		$posttype	= isset($instance['posttype']) ? esc_attr($instance['posttype']) : 'post';
		$thumbnail = esc_attr($instance['thumbnail']);
		$thumbnail_width = isset($instance['thumbnail_width']) ? absint($instance['thumbnail_width']) : 100;
		$thumbnail_height = isset($instance['thumbnail_height']) ? absint($instance['thumbnail_height']) : 80;
		
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
		
		<p><label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category:'); ?></label>
		<?php wp_dropdown_categories( array( 'selected' => $category, 'name' => $this->get_field_name('category'), 'show_option_none' => 'Select' ) ); ?> 
		</p>
		
		<p>	
			<label for="<?php echo $this->get_field_id('posttype'); ?>"><?php _e('Choose the Post Type to display'); ?></label> 
			<select name="<?php echo $this->get_field_name('posttype'); ?>" id="<?php echo $this->get_field_id('posttype'); ?>" class="widefat">
				<?php
				foreach ($posttypes as $option) {
					echo '<option value="' . $option->name . '" id="' . $option->name . '" '.selected( $posttype, $option->name ).'>', $option->name, '</option>';
				}
				?>
			</select>		
		</p>
		
		<?php if( get_theme_support( 'bareskin-get-image' ) != false ): ?>
		
			<p>
				<label for="<?php echo $this->get_field_id('thumbnail'); ?>"><?php _e('Display thumbnails?'); ?></label> 
				<input id="<?php echo $this->get_field_id('thumbnail'); ?>" name="<?php echo $this->get_field_name('thumbnail'); ?>" type="checkbox" value="1" <?php checked( '1', $thumbnail ); ?>/>         
			</p>
			
			<p>
			  <label for="<?php echo $this->get_field_id('thumbnail_width'); ?>"><?php _e('Width of the thumbnails'); ?></label> 
			  <input class="widefat" id="<?php echo $this->get_field_id('thumbnail_width'); ?>" name="<?php echo $this->get_field_name('thumbnail_width'); ?>" type="text" value="<?php echo $thumbnail_width; ?>" />
			</p>
			
			<p>
			  <label for="<?php echo $this->get_field_id('thumbnail_height'); ?>"><?php _e('Height of the thumbnails'); ?></label> 
			  <input class="widefat" id="<?php echo $this->get_field_id('thumbnail_height'); ?>" name="<?php echo $this->get_field_name('thumbnail_height'); ?>" type="text" value="<?php echo $thumbnail_height; ?>" />
			</p>
		<?php endif; ?>
<?php
	}
}