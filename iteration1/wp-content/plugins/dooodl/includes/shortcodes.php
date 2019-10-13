<?php




//creator shortcode
function shortcode_dooodl_creator($atts, $content = null) {
	$CREATOR_URL= dooodl_create_url('creator');

	extract(shortcode_atts(array("width" => '750', 'height' => '450', 'css'=>''), $atts));

	$html = '<div style="width:#WIDTH#px; height:#HEIGHT#px; #CSS#">
				<iframe frameborder="0" width="#WIDTH#" height="#HEIGHT#" src="#SRC#" scrolling="no" style="width:#WIDTH#px; height:#HEIGHT#px; position:absolute;"></iframe>
			</div> ';

	$html = str_replace('#WIDTH#',$width,$html);
	$html = str_replace('#HEIGHT#',$height,$html);
	$html = str_replace('#SRC#',$CREATOR_URL,$html);
	$html = str_replace('#CSS#',$css,$html);

    return $html;
}




//gallery shortcode
function shortcode_dooodl_gallery($atts, $content = null) {
	$GALLERY_URL = dooodl_create_url('gallery');
	extract(shortcode_atts(array("width" => '750', 'height' => '450', 'css'=>''), $atts));

	$html = '<div style="width:#WIDTH#px; height:#HEIGHT#px; #CSS#">
				<iframe frameborder="0" width="#WIDTH#" height="#HEIGHT#" src="#SRC##theViewer" scrolling="auto" style="width:#WIDTH#px; height:#HEIGHT#px; position:absolute;"></iframe>
			</div> ';

	$html = str_replace('#WIDTH#',$width,$html);
	$html = str_replace('#HEIGHT#',$height,$html);
	$html = str_replace('#SRC#',$GALLERY_URL,$html);
	$html = str_replace('#CSS#',$css,$html);

    return $html;
}






//widget shortcode
function shortcode_dooodl_widget($atts, $content = null) {

	extract(shortcode_atts(array("width" => '750', 'height' => '450', 'css'=>'','title'=>''), $atts));

	global $dooodl_options;

	$widget_title = $title;
	$show_widget_title = ($title == "") ? true : false;
	$widget_template = $dooodl_options['widget_template'];

	$CREATOR_URL = dooodl_create_url('creator');
	$GALLERY_URL = dooodl_create_url('gallery');

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

		$args['posts_per_page'] = -1;
		$count_query = new WP_Query($args);


		$query->the_post();

		$widget_html .= $widget_template;

		$TITLE 			= get_the_title();
		$AUTHOR 		= get_field('author_name', get_the_ID());
		$AUTHOR_URL 	= get_field('author_url', get_the_ID());
		$DESCRIPTION 	= get_field('description', get_the_ID());
		$DOOODL_URL  	= dooodl_get_image_url(get_the_id(), array(250,250));
		$TOTAL_COUNT 	= $count_query->found_posts;


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

	$html = $title_tag;
	$html .= $widget_html;

	wp_reset_postdata();

    return $html;
}




add_shortcode('dooodl_widget', 'shortcode_dooodl_widget');
add_shortcode('dooodl_creator', 'shortcode_dooodl_creator');
add_shortcode('dooodl_gallery', 'shortcode_dooodl_gallery');

?>