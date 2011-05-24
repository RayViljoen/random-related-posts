<?php
/*
Plugin Name: Simple Related Posts
Description: Displays a selected number of posts from the same current category.
Author: <a href="http://www.fubra.com">Ray Viljoen</a>
Version: 1.0
Plugin URI: http://catn.com/community/plugins/random-related-posts/
Usage: widget.
*/
// � 2009-2010 Fubra Limited, all rights reserved.

class RPWidget extends WP_Widget {
	/** constructor */
	function RPWidget() {
		parent::WP_Widget(false, $name = 'Related Posts');
	}

	function widget($args, $instance) {

		$title = apply_filters('widget_title', $instance['title']);
		$num_posts = $instance['number'];
		global $post;

		$cat = get_the_category( $post->ID );
		if(array_key_exists(0, $cat))
			{$cat = $cat[0]->cat_ID;}else{$cat = '';}

		$rand_posts = get_posts( 'numberposts='.$num_posts.'&orderby=rand&category='.$cat );

		echo '<span class="related-title">'.$title.'</span>';
		echo '<ul class="random-posts">';
		foreach ($rand_posts as $rel_post)
		{

			$date = date_create($rel_post->post_date);

			echo '<li><h5><a href="';
			echo get_permalink($rel_post->ID);
			echo '" >'.$rel_post->post_title;
			echo '</a></h5><span><span class="by">By </span>';
			echo get_the_author_meta('nickname', $rel_post->post_author);
			echo '&nbsp;<span class="date">';
			echo date_format($date, 'D, jS M y');
			echo '</span></span>';
		}
		echo '</li></ul>';

	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = strip_tags($new_instance['number']);
		return $instance;
	}

	function form($instance) {
		if(array_key_exists('title', $instance)){
			$title = esc_attr($instance['title']);
		}else{$title='';}

		if(array_key_exists('number', $instance)){
			$number = esc_attr($instance['number']);
		}else{$number=3;}
?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
            <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number:'); ?>
              <select id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo $number; ?>" >
             <?php
		for ( $i = 1; $i <= 20; ++$i )
			echo "<option value='$i' " . ( $number == $i ? "selected='selected'" : '' ) . ">$i</option>";
?>
              </select></label></p>
        <?php
	}
}
add_action('widgets_init', create_function('', 'return register_widget("RPWidget");'));