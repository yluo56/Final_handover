<?php
/*
Version: 1.1.0
Author: Heyoya <support@heyoya.com>
Author URI: https://www.heyoya.com/
*/
$options = get_option('heyoya_options', null); 
if ($options == null || !isset($options["affiliate_id"]) || !isset($options["published"]) || $options["published"] == "0"){ 
	$content = "";
	if ($options != null && !isset($options["affiliate_id"]) && isset($options["published"]) && $options["published"] == "1"){		            	 	             
			$content .= "<iframe style=\"width:1px;height:1px;position:absolute;top:-10000px:left:-10000px;\" src=\"https://commerce-static.heyoya.com/b2b/b2b_gr.jsp?action=wp-plugin-load-failed-missing-affiliate-id&pageUrl=http://" . "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" . "\"></iframe>"; 
	}

	echo $content;
	
	return; 
} 	

 
$heyoyaScript = "<div id=\"heyoyaDiv\"></div><script type=\"text/javascript\" src=\"https://commerce-static.heyoya.com/b2b/b2b_settings.hey?affId=#AFFILIATE_ID#\"></script>"; 
$heyoyaScript = str_replace("#AFFILIATE_ID#", $options["affiliate_id"], $heyoyaScript); 


echo $heyoyaScript;   		

?>
