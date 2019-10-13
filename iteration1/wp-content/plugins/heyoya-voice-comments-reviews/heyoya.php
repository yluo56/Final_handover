<?php
/*
Plugin Name: Voice Comments - Heyoya
Plugin URI: https://www.heyoya.com/
Description: Heyoya is a revolutionary voice comments platform that is transforming the way people interact with content online! To get started: 1) Click the "Activate" link to the left of this description, and 2) Go to your Heyoya configuration page, and log in / sign up

Version: 2.0.6
Author: Heyoya <support@heyoya.com>
Author URI: https://www.heyoya.com/
*/

require_once(dirname(__FILE__) . '/admin/admin.php');
require_once(dirname(__FILE__) . '/plugin/plugin.php');

/*
 * Creating one of 2 classes:
 * 	if we're in the admin panel - creating an instance of the AdminOptionsPage class
 * 	if we're in the frontend - creating an instance of the PluginContainer class 
 */
if( is_admin() )
	$admin_options_page = new AdminOptionsPage();
else {	
	$plugin_container = new PluginContainer();	
}


/*
 * This function will check if the user is logged in to the Heyoya admin panel
 */
function is_heyoya_installed() {	
	$options = get_option('heyoya_options', null);	
	
	return  $options != null && isset($options["apikey"]) && strlen($options["apikey"]) > 0;	
}


?>
