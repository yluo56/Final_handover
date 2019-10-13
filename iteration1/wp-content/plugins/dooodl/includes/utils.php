<?php

if( !function_exists('dump')) {
	function dump($var, $die=true,$breakout=false){
		$enableDumping = true; // for developing purposes

		if($enableDumping === true) {
			if($breakout){
				$css = "position: fixed; width: 100%; top: 0; left: 0; min-height: 400px; z-index: 50000000;";
			}
			print('<pre style="'. $css .'border: #ff9900 2px dashed; color: #ffffff; background-color: #333333; padding: 20px; display: block; font-size: 12px; overflow: auto; z-index: 99999; position: relative;">');
			print_r($var);
			print('</pre>');

			if($die){
				die('---end----');
			}
		}
	}
}

function dooodl_create_url($action){
	global $wp_rewrite;

	$home_url = get_bloginfo('url');

	$url = "";

	if($wp_rewrite->using_permalinks()){
		$url = $home_url . "/dooodl/" . $action;
	}
	else{
		$url = add_query_arg(array('dooodl'=>$action), $home_url);
	}

	return $url;
}


function dooodl_get_image_url($id, $size=false){
	$attach_id = get_post_thumbnail_id($id);
	$img = wp_get_attachment_image_src($attach_id, $size, false );
	$url = $img[0];
	
	$url = str_replace('http://', '//', $url);
	$url = str_replace('https://', '//', $url);

	return $url;
}

?>