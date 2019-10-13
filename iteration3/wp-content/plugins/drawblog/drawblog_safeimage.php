<?php
	$img = urldecode($_GET['img']);
	$img_info = getimagesize($img);
	if (($img_info !== false) && (in_array($img_info[2], array(IMAGETYPE_JPG, IMAGETYPE_JPEG, IMAGETYPE_JPEG2000, IMAGETYPE_PNG, IMAGETYPE_GIF)))){
		header("Content-type: ".$img_info[2]);
		readfile($img);
	}