<?php
/*
Plugin Name: Dooodl
Plugin URI: http://nocreativity.com/blog/
Description: Enables your blog's visitors to draw a little Doodle and save it to your site. Powered by TiltViewer!
Version: 2.0.10
Author: Ronny Welter
Author URI: http://nocreativity.com
*/

/*  Copyright 2014  Ronny Welter  (email : senderhas@nocreativity.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//Only set ACF_LITE if the user hasn't installed it himself...
if(!function_exists("register_field_group")){
	define('ACF_LITE' , true );
}

$dooodl_filtername = "plugin_action_links_" . plugin_basename(__FILE__);
$dooodl_update_message = false;
$dooodl_plugin_path = plugin_dir_path(__FILE__);


function dooodl_get_plugin_uri($path = false){
	$url = trailingslashit(plugins_url("", __FILE__));
	if($path){
		$url .= $path;
	}
	return $url;
}

function dooodl_getUploadsDir(){
	$wp_upload_dir = wp_upload_dir(); 
    
    $output_dir = $wp_upload_dir['basedir'] . '/doodls/';
    if( ! file_exists( $output_dir ) ){
        wp_mkdir_p( $output_dir );
    }

    return $output_dir;
}

function dooodl_include($file){
	$dir = trailingslashit(dirname(__FILE__));
	require_once($dir . $file);
}



//Load core
dooodl_include('includes/utils.php');
dooodl_include('includes/globals.php');
dooodl_include('includes/handlers.php');
dooodl_include('includes/posttype.php');
dooodl_include('includes/ajax.php');
dooodl_include('includes/migration.php');

//load libs
dooodl_include('includes/libs/advanced-custom-fields/acf.php');
dooodl_include('includes/libs/redux-framework/framework.php');
dooodl_include('includes/class.dooodl-table-overview.php');

//build options panel
dooodl_include('includes/redux_config.php');

//build overview page
dooodl_include('includes/page.dooodl-overview.php');

//load features
dooodl_include('includes/widget.php');
dooodl_include('includes/dashboard-widget.php');
dooodl_include('includes/shortcodes.php');

//start actions & filters
dooodl_include('includes/actions.php');
dooodl_include('includes/filters.php');


?>