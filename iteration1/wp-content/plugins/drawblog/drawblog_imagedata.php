<?php
$img = $_POST['imgsrc'];
$wpdir = $_POST['wpdir'];
$domain_bits = parse_url($img);
$domain = $domain_bits['host'];
$filename = $domain_bits['path'];
if ($domain == $_SERVER['SERVER_NAME']) {		
	$file = $img;
} else {
	$file = $wpdir.'/plugins/drawblog/drawblog_safeimage.php?img='.urlencode($img);
}
$img_info = getimagesize($img);
if ($img_info !== false){
	echo json_encode(array($file,$img_info[0], $img_info[1]));
} else echo '';

