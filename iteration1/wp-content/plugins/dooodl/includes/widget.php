<?php


if(!class_exists("Dooodl_widget")){

	class Dooodl_widget extends WP_Widget {

		function __construct() {
			$args = array( 'description' => __( 'Show off your Dooodls in the sidebar', 'dooodl' ));
			parent::__construct('Dooodl_widget', __('Dooodl widget', 'dooodl'), $args);
		}


		public function widget( $args, $instance ) {
			global $dooodl_options;

			if($args != NULL){
				extract($args);
			}

			$widget_title 		= $instance['title'];
			$show_widget_title 	= $instance['show_widget_title'];
			$widget_template 	= $dooodl_options['widget_template'];

			$GALLERY_URL = dooodl_create_url('gallery');
			$CREATOR_URL = dooodl_create_url('creator');

			$widget_html = "";
			if($show_widget_title == true){
				$widget_html = $before_title . $widget_title . $after_title;
			}

			$args = array();
			$args['post_type'] = 'dooodl';
			$args['posts_per_page'] = 1;
			$args['orderby'] = 'date';
			$args['order'] = 'desc';
			$args['meta_query'] = array(
				array('key'=>'approved', 'value'=> 'yes', 'compare'=> '=')
			);

			$query = new WP_Query($args);


			if($query->have_posts()){

				$query->the_post();

				$widget_html .= $widget_template;

				$url = dooodl_get_image_url(get_the_ID(), array(250,250));

				$count_query = wp_count_posts('dooodl');

				$TOTAL_COUNT 	= $count_query->publish;
				$TITLE 			= get_the_title();
				$AUTHOR 		= get_field('author_name', get_the_ID());
				$AUTHOR_URL 	= get_field('author_url', get_the_ID());
				$DESCRIPTION 	= get_field('description', get_the_ID());
				$DOOODL_URL  	= $url;
				

				$widget_html = str_replace("%TITLE%",$TITLE, $widget_html);
				$widget_html = str_replace("%AUTHOR%",$AUTHOR, $widget_html);
				$widget_html = str_replace("%AUTHOR_URL%",$AUTHOR_URL, $widget_html);
				$widget_html = str_replace("%DESCRIPTION%",$DESCRIPTION, $widget_html);
				$widget_html = str_replace("%DOOODL_URL%",$DOOODL_URL, $widget_html);
				$widget_html = str_replace("%TOTAL_COUNT%",$TOTAL_COUNT, $widget_html);
				$widget_html = str_replace("%CREATOR_URL%",$CREATOR_URL, $widget_html);
				$widget_html = str_replace("%GALLERY_URL%",$GALLERY_URL, $widget_html);

			}
			else{
				$widget_html .= "<p>". __('No Dooodls yet! Time to get drawing!','dooodl') ." <br/> <a href='". $CREATOR_URL ."' target='_blank'>" . __("Draw the first Dooodl on this site!", "dooodl") . "</a></p>";
			}

			echo $before_widget;
			echo $widget_html;
			echo $after_widget;

			wp_reset_postdata();
		}

		// Widget Backend
		public function form( $instance ) {
			if ( isset( $instance[ 'title' ] ) ) {
				$title = $instance[ 'title' ];
			}
			else {
				$title = __( 'Dooodl', 'dooodl');
			}

			if ( isset( $instance[ 'show_widget_title' ] ) ) {
				$show_widget_title = $instance[ 'show_widget_title' ];
			}
			else {
				$show_widget_title = true;
			}

			?>



			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'dooodl' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>

			<p>
				<?php _e( "Should the widget's title be visible?" , 'dooodl'); ?> <br/>
				<input class="widefat" id="<?php echo $this->get_field_id( 'show_widget_title_yes' ); ?>" name="<?php echo $this->get_field_name( 'show_widget_title' ); ?>" type="radio" value="yes" <?php if($show_widget_title == true){ print('checked="checked"'); } ?>/>
				<label for="<?php echo $this->get_field_id( 'show_widget_title_yes' ); ?>"><?php _e( "Yes" , 'dooodl'); ?></label>

				<input class="widefat" id="<?php echo $this->get_field_id( 'show_widget_title_no' ); ?>" name="<?php echo $this->get_field_name( 'show_widget_title' ); ?>" type="radio" value="no" <?php if($show_widget_title == false){ print('checked="checked"'); } ?>>
				<label for="<?php echo $this->get_field_id( 'show_widget_title_no' ); ?>"><?php _e( "No" , 'dooodl'); ?></label>
			</p>
			<?php
		}


		// Updating widget replacing old instances with new
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : 'No title';

			if($new_instance['show_widget_title'] == "yes"){
				$instance['show_widget_title'] = true;
			}
			else{
				$instance['show_widget_title'] = false;
			}

			return $instance;
		}

	}

}

?>