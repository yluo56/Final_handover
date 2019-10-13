<?php
/*
Plugin Name: DrawBlog
Plugin URI: http://drawblog.com/
Description: A WordPress plugin that allows commenters to draw a picture.
Version: 0.90
Author: Randy Tayler
Author URI: http://randytayler.com
License: GPL

*/
global $drawblog_db_version;
global $drawblog_form_complete;
global $drawblog_post_form_complete;
$drawblog_db_version = "0.63";
/* Runs when plugin is activated */
register_activation_hook(__FILE__,'drawblog_install'); 

add_filter('comment_text', 'drawblog_add_image_to_comment');
add_action('comment_form', 'drawblog_comment_form');
add_action('comment_post', 'drawblog_save_image');
add_filter('the_content', 'drawblog_add_image_to_post');
add_action('save_post', 'drawblog_save_post_image' );
add_action('edit_form_advanced', 'drawblog_post_form' );

function drawblog_install() {
	global $drawblog_db_version;
	add_option("drawblog_db_version", $drawblog_db_version);
	if (!is_dir(WP_CONTENT_DIR . '/drawblog/images')){
		wp_mkdir_p(WP_CONTENT_DIR . '/drawblog/images', 755);
	}
	if (!get_option("drawblog_canvas_title")) add_option("drawblog_canvas_title", __('Click here to draw a picture to include in your comment.'));
	if (!get_option("drawblog_hint_text1")) add_option("drawblog_hint_text1",  __('Click on one of the images above to draw on it, or start from a blank canvas.'));
	if (!get_option("drawblog_hint_text2")) add_option("drawblog_hint_text2",  __('Include this picture with my comment.'));
	if (!get_option("drawblog_warning1")) add_option("drawblog_warning1",  __("This will copy over what you've already drawn. Are you sure?"));
	if (!get_option("drawblog_warning2")) add_option("drawblog_warning2",  __('Are you sure you want to clear your drawing?'));
	if (!get_option("drawblog_canvas_width")) add_option("drawblog_canvas_width", 400);
	if (!get_option("drawblog_canvas_height")) add_option("drawblog_canvas_height", 300);
	if (!get_option("drawblog_post_classname")) add_option("drawblog_post_classname", drawblog_determine_classname());	
	if (!get_option("drawblog_show_canvas")) add_option("drawblog_show_canvas", true);
	if (!get_option("drawblog_default_bg")) add_option("drawblog_default_bg", '');
}

function drawblog_add_image_to_comment($comment_text){
	global $comment; 
	$drawblog_image = drawblog_get_image($comment->comment_ID);
	if (($drawblog_image) && 
		(is_file( WP_CONTENT_DIR . '/drawblog/images/' . $drawblog_image)) && 
		(getimagesize(  WP_CONTENT_DIR . '/drawblog/images/' . $drawblog_image) !== false)){			
			$comment_text = "<img src=\"". content_url(). '/drawblog/images/'.$drawblog_image."\" class=\"drawblogimage\"><br>".$comment_text;
	}
	return $comment_text;
}

function drawblog_add_image_to_post($post_text){
	global $post; 
	$drawblog_image = drawblog_get_post_image($post->ID);
	if (($drawblog_image) && 
		(is_file( WP_CONTENT_DIR . '/drawblog/images/' . $drawblog_image)) && 
		(getimagesize(  WP_CONTENT_DIR . '/drawblog/images/' . $drawblog_image) !== false)){			
			$post_text = "<img src=\"". content_url(). '/drawblog/images/'.$drawblog_image."\" class=\"drawblogimage\"><br>".$post_text;
	} 
	return $post_text;
}

function drawblog_get_image($comment_id){
	global $wpdb;
	$meta = get_comment_meta($comment_id, 'drawblog_image');
	return $meta[0];
}

function drawblog_get_post_image($post_id){
	global $wpdb;
	$meta = get_post_meta($post_id, 'drawblog_image');
	return $meta[0];
}

function drawblog_comment_form(){
	global $drawblog_form_complete;
	if (!$drawblog_form_complete){
		echo drawblog_add_canvas();
		$drawblog_form_complete = true;
	}
}

function drawblog_post_form(){
	global $drawblog_post_form_complete;
	global $post;
	global $image_exists;
	$drawblog_image = drawblog_get_post_image($post->ID);
	if (($drawblog_image) && 
		(is_file( WP_CONTENT_DIR . '/drawblog/images/' . $drawblog_image)) && 
		(getimagesize(  WP_CONTENT_DIR . '/drawblog/images/' . $drawblog_image) !== false)){
			$image_exists = 'true';
		} else {
			$image_exists = 'false';
		}
	if (!$drawblog_post_form_complete){
		echo drawblog_add_post_canvas();
		$drawblog_post_form_complete = true;
	}
}

function drawblog_save_image($comment_id){
	global $wpdb;
	if ($_POST['drawblog_include_pic'] == true){
		$data = $_POST['drawblog_picture'];
		$raw_data = str_replace(' ','+',$data);
		$filtered_data=substr($raw_data, strpos($raw_data, ",")+1);
		$data = base64_decode($filtered_data);
		$new_image = uniqid($comment_id.'_').'.png';
		$fp = fopen(WP_CONTENT_DIR . '/drawblog/images/'.$new_image, 'wb' );
		fwrite( $fp, $data);
		fclose( $fp );
		if (is_file(WP_PLUGIN_DIR . "/drawblog/icons/dbwm.png")){
			$im = imagecreatefrompng(WP_CONTENT_DIR . '/drawblog/images/'.$new_image);
			$src = imagecreatefrompng(WP_PLUGIN_DIR . "/drawblog/icons/dbwm.png");
			list($wm_width, $wm_height) = getimagesize(WP_PLUGIN_DIR . "/drawblog/icons/dbwm.png");
			imagecopy($im, $src, get_option('drawblog_canvas_width')-$wm_width, get_option('drawblog_canvas_height') - $wm_height, 0, 0, $wm_width, $wm_height);
			imagepng($im, WP_CONTENT_DIR . '/drawblog/images/'.$new_image);
		} 
		add_comment_meta($comment_id, 'drawblog_image', $new_image);
	}
}
function drawblog_save_post_image($post_id){
	global $wpdb;
	if ($_POST['drawblog_include_pic'] == true){
		$data = $_POST['drawblog_picture'];
		$raw_data = str_replace(' ','+',$data);
		$filtered_data=substr($raw_data, strpos($raw_data, ",")+1);
		$data = base64_decode($filtered_data);
		$new_image = uniqid('p'.$post_id.'_').'.png';
		$fp = fopen(WP_CONTENT_DIR . '/drawblog/images/'.$new_image, 'wb' );
		fwrite( $fp, $data);
		fclose( $fp );
		if (is_file(WP_PLUGIN_DIR . "/drawblog/icons/dbwm.png")){
			$im = imagecreatefrompng(WP_CONTENT_DIR . '/drawblog/images/'.$new_image);
			$src = imagecreatefrompng(WP_PLUGIN_DIR . "/drawblog/icons/dbwm.png");
			list($wm_width, $wm_height) = getimagesize(WP_PLUGIN_DIR . "/drawblog/icons/dbwm.png");
			imagecopy($im, $src, get_option('drawblog_canvas_width')-$wm_width, get_option('drawblog_canvas_height') - $wm_height, 0, 0, $wm_width, $wm_height);
			imagepng($im, WP_CONTENT_DIR . '/drawblog/images/'.$new_image);
		} 
		delete_post_meta($post_id, 'drawblog_image');
		add_post_meta($post_id, 'drawblog_image', $new_image);
	} else {
		delete_post_meta($post_id, 'drawblog_image');
	}
}

function drawblog_check_options(){	
	//new options pose a little trouble on upgrade. This'll force them to update if the installation trick didn't work 
	if (!get_option("drawblog_hint_text1")) add_option("drawblog_hint_text1",  __('Click on one of the images above to draw on it, or start from a blank canvas.'));
	if (!get_option("drawblog_hint_text2")) add_option("drawblog_hint_text2",  __('Include this picture with my comment.'));
	if (!get_option("drawblog_warning1")) add_option("drawblog_warning1",  __("This will copy over what you've already drawn. Are you sure?"));
	if (!get_option("drawblog_warning2")) add_option("drawblog_warning2",  __('Are you sure you want to clear your drawing?'));

	if (get_option("drawblog_hint_text1")=='') update_option("drawblog_hint_text1",  __('Click on one of the images above to draw on it, or start from a blank canvas.'));
	if (get_option("drawblog_hint_text2")=='') update_option("drawblog_hint_text2",  __('Include this picture with my comment.'));
	if (get_option("drawblog_warning1")=='') update_option("drawblog_warning1",  __("This will copy over what you've already drawn. Are you sure?"));
	if (get_option("drawblog_warning2")=='') update_option("drawblog_warning2",  __('Are you sure you want to clear your drawing?'));
	
}

function drawblog_add_canvas(){
	drawblog_check_options();
	global $post;
	if (get_option('drawblog_api_key')!=''){
		$ch = curl_init('http://drawblog.com/auth.php');
		curl_setopt($ch, CURLOPT_POST, true);
		$postfields = 'apikey='.get_option('drawblog_api_key').'&domain='.$_SERVER['HTTP_HOST'];
		$postfields .='&theme='.get_stylesheet(); // if you're using the api, I need your theme
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		echo curl_exec($ch);
		curl_close($ch);
	}
	$width = get_option('drawblog_canvas_width');
	$height = get_option('drawblog_canvas_height');
	include "drawblog_canvas.php";
}

function drawblog_add_post_canvas(){
	drawblog_check_options();
	if (get_option('drawblog_api_key')!=''){
		$ch = curl_init('http://drawblog.com/auth.php');
		curl_setopt($ch, CURLOPT_POST, true);
		$postfields = 'apikey='.get_option('drawblog_api_key').'&domain='.$_SERVER['HTTP_HOST'];
		$postfields .='&theme='.get_stylesheet(); // if you're using the api, I need your theme
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		echo curl_exec($ch);
		curl_close($ch);
	}
	$width = get_option('drawblog_canvas_width');
	$height = get_option('drawblog_canvas_height');
	include "drawblog_post_canvas.php";
}

function drawblog_determine_classname(){
	$themedir = get_stylesheet_directory();
	$theme = get_stylesheet(); // I wish this function was called get_theme(), but whatever
	switch ($theme){		
		case 'custom-community':
			$postclass = 'post-content';
			break;
		case 'inferno-mf':
			$postclass = 'post';
			break;
		case 'easel':
		case 'eclipse':
		case 'ifeature':
			$postclass = 'entry';
			break;
		case 'responsive':
			$postclass = "post-entry";
			break;
		case 'mantra':
		case 'pagelines':
		case 'pinboard':
		case 'twentyeleven':
		case 'twentyten':
		case 'twentytwelve':
		default:
			$postclass = "entry-content";
			break;
	}
	return $postclass;
}

function get_image_data($img){	
	$domain_bits = parse_url($img);
	$domain = $domain_bits['host'];
	$filename = $domain_bits['path'];
	if ($domain == $_SERVER['SERVER_NAME']) {		
		$file = $img;
	} else {
		$file = plugins_url().'/drawblog/drawblog_safeimage.php?img='.urlencode($img);
	}
	$img_info = getimagesize($img);
	if ($img_info !== false){
		echo json_encode(array($file,$img_info[0], $img_info[1]));
	} else echo '';
}

if ( is_admin() ){
	add_action('admin_menu', 'drawblog_admin_menu');
	
	function drawblog_admin_menu() {
		add_options_page('DrawBlog', 'DrawBlog', 'administrator', 'drawblog', 'drawblog_settings_page');
	}
	
	function drawblog_check_api_key($apikey){
		$ch = curl_init('http://drawblog.com/validate.php');
		curl_setopt($ch, CURLOPT_POST, true);
		$postfields = 'apikey='.$apikey.'&domain='.$_SERVER['HTTP_HOST'];
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$retval = curl_exec($ch);
		curl_close($ch);
		return $retval;
	}
		
	function drawblog_update_options($values){
		$title = $values['drawblog_canvas_title'];	
		$hint1 = $values['drawblog_hint_text1'];	
		$hint2 = $values['drawblog_hint_text2'];
		$warning1 = $values['drawblog_warning1'];	
		$warning2 = $values['drawblog_warning2'];					
		$width = intval($values['drawblog_canvas_width']);
		$height = intval($values['drawblog_canvas_height']);
		$show_canvas = $values['drawblog_show_canvas'];
		if ($values['drawblog_default_bg']) $default_bg = $values['drawblog_default_bg'];
		if ($width<=0) return __('Canvas width'); //returns other than 'success', below, indicate a field with an error. Canvases cannot be negative or zero values.
		if ($height<=0) return __('Canvas height');
		if (($values['drawblog_api_key']!='') && (!(drawblog_check_api_key($values['drawblog_api_key']) == 1))) return __('API key is invalid or expired. Visit <a href="http://drawblog.com/purchase.php">DrawBlog.com</a> to purchase or renew an API key.');
		update_option('drawblog_api_key', $values['drawblog_api_key']);	
		update_option('drawblog_canvas_width', $width);		
		update_option('drawblog_canvas_height', $height);
		update_option('drawblog_canvas_title', $title);
		update_option('drawblog_hint_text1', $hint1);
		update_option('drawblog_hint_text2', $hint2);
		update_option('drawblog_warning1', $warning1);
		update_option('drawblog_warning2', $warning2);
		update_option('drawblog_post_classname', $values['post_class_name']);
		update_option('drawblog_show_canvas', $show_canvas);
		update_option('drawblog_default_bg', $default_bg);
		return 'success';
	}
	function drawblog_settings_page() {
		include "drawblog_admin.php";
	}
}
?>