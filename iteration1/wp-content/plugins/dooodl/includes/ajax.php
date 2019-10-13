<?php

function dooodl_prepareJavascriptVars(){
	global $wp_rewrite;

	$path = trailingslashit(get_bloginfo('url'));

	$vars = array();
	$vars['plugin_root'] = dooodl_get_plugin_uri('');
	$vars['post_new'] = dooodl_create_url('creator/post/new');

	return $vars;
}

function dooodl_saveAsPNG($imagedata,$filename){
	$imagedata= str_replace('data:image/png;base64,','',$imagedata);
	$bytes = base64_decode($imagedata);

	file_put_contents(dooodl_getUploadsDir() . $filename, $bytes);
	return dooodl_getUploadsDir() . $filename;
}

function dooodl_saveImage($username,$title,$description,$url,$image){
	global $dooodl_options;

	if(!function_exists("wp_generate_attachment_metadata")){
		require_once(ABSPATH . "wp-admin/includes/image.php");
	}

	if($username == ""){
		$username = __("Anonymous", "dooodl");
	}
	if($title == ""){
		$title = __("Untitled", "dooodl");
	}

	$username 		= sanitize_text_field($username);
	$title 			= sanitize_text_field($title);
	$description 	= sanitize_text_field($description);
	$url 			= sanitize_text_field($url);

	$hasToBeModerated = $dooodl_options['moderation_enabled'];

	$is_live = "yes";

	if($hasToBeModerated == true){
		$is_live = "no";
	}

	$ext = "png";

	$dooodl = array();
	$dooodl['post_type'] = 'dooodl';
	$dooodl['post_title'] = $title;
	$dooodl['post_status'] = "publish";

	$dooodl_id = wp_insert_post($dooodl);

	update_field(DOOODL_APPROVED, $is_live, $dooodl_id);
	update_field(DOOODL_AUTHOR_NAME, $username, $dooodl_id);
	update_field(DOOODL_AUTHOR_URL, $url, $dooodl_id);
	update_field(DOOODL_DESCRIPTION, $description, $dooodl_id);

	$filename = dooodl_saveAsPNG($image,$dooodl_id . "." . $ext);

	/**/
	$wp_filetype = wp_check_filetype($filename, null );
	$attachment = array(
	 'post_mime_type' => $wp_filetype['type'],
	 'post_title' => "Dooodl #" . $dooodl_id,
	 'post_content' => '',
	 'post_status' => 'inherit'
	);

	$attach_id = wp_insert_attachment( $attachment, $filename, $dooodl_id);
	$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );

	wp_update_attachment_metadata( $attach_id,  $attach_data );
	set_post_thumbnail($dooodl_id, $attach_id);
	/**/

	$hasToBeMailed = $dooodl_options['email_notification'];
	
	if($hasToBeMailed){
		dooodl_sendMail($dooodl_id,$username,$url,$title,$description);
	}

	return true;
}

function dooodl_sendMail($id,$author,$url,$title,$desc){
	$blogname = get_option('blogname');
	$adminemail = get_option('admin_email');
	
	$attach_id = get_post_thumbnail_id($id);
	$path = get_attached_file($attach_id);

	$attachments = array($path);

	$headers = 'From: '. $blogname .' <'. $adminemail .'>' . "\r\n\\";

	/*$unapproveurl = get_option('siteurl') . "/wp-admin/admin.php?page=dooodl_settings&action=dooodl_unapprove&id=".$id;
	$approveurl = get_option('siteurl') . "/wp-admin/admin.php?page=dooodl_settings&action=dooodl_approve&id=".$id;
	$deleteeurl = get_option('siteurl') . "/wp-admin/admin.php?page=dooodl_manager&askToDelete=$id&dest=manager";*/

	$message="Hi there!

There's a new Dooodl on $blogname! How cool is that!?

The Dooodl was drawn by '$author' [$url] and is titled '$title'.
Description: $desc

";

	$hasToBeModerated = get_option('dooodl_moderate');
	/*
	if($hasToBeModerated == "on"){
		$message .= __("In order for it to show up on your site, you'll have to moderate the Dooodl!", 'dooodl');
		$message .= "\n";
		$message .= __("You can do this by using the following links:", 'dooodl');
		$message .= "\n\n";
		$message .= __("Approve it:", 'dooodl') . ' ' .  $approveurl;
		$message .= "\n";
		$message .= __("Delete it:", 'dooodl') . ' ' . $deleteeurl;
	} else{
		$message .= __("If you don't like it, you can just delete it by clicking here:", 'dooodl') . ' ' . $deleteeurl;
		$message .= "\n";
		$message .= __("or hide it from the by clicking here:", 'dooodl') . ' ' .  $unapproveurl;
	}*/

	//$message .= "\n\n\n";	
	$message .= __("Have a nice day!", 'dooodl');

	wp_mail($adminemail, __("New Dooodl on", 'dooodl') . ' ' . $blogname, $message, $headers,$attachments);
	return true;
}

function dooodl_add_new(){
	global $wp;
	$arr = array();
	$arr['success'] = false;
	$arr['message'] = __('No message', TEXTDOMAIN);

	if(isset($_POST['name'])){
		$name = 		sanitize_text_field($_POST['name']);
		$title = 		sanitize_text_field($_POST['title']);
		$description = 	sanitize_text_field($_POST['description']);
		$url = 			sanitize_text_field($_POST['url']);
		$imagedata = 	$_POST['imagedata'];
		$time = 		time();

		$return = dooodl_saveImage($name,$title,$description,$url,$imagedata);
		if($return){
			$arr['success'] = true;
			$arr['message'] = __("Your Dooodl has been saved! Thanks!",' dooodl');
		}
		else{
			$arr['success'] = false;
			$arr['message'] = __("The Dooodl could not be saved on the server. What's going on?!",' dooodl');
		}
	}
	else{
		$arr['success'] = false;
		$arr['message'] = __("No POST has been sent to the server.",' dooodl');
	}

	$json = json_encode($arr);
	header("HTTP/1.1 200 OK");
	header("Content-type: application/json");
	print($json);
	exit;
}

