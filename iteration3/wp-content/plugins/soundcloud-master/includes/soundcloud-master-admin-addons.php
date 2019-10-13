<?php
function menu_single_soundcloud_admin_addons(){
	if ( is_admin() )
	add_submenu_page( 'soundcloud-master', 'Add-ons', 'Add-ons', 'manage_options', 'soundcloud-master-admin-addons', 'soundcloud_master_admin_addons' );
}

function soundcloud_master_admin_addons(){
$plugin_master_name = constant('SOUNDCLOUD_MASTER_NAME');
?>
<div class="wrap">
<h1><?php echo $plugin_master_name; ?> Add-ons</h1>
<?php
if(!class_exists('soundcloud_master_admin_addons_table')){
	require_once( WP_PLUGIN_DIR . '/soundcloud-master/includes/soundcloud-master-admin-addons-table.php');
}
//Prepare Table of elements
$wp_list_table = new soundcloud_master_admin_addons_table();
//Table of elements
$wp_list_table->display();

?>
</br>

<h2>IMPORTANT: Makes no use of Javascript or Ajax to keep your website fast and conflicts free</h2>

<div style="background: url(<?php echo plugins_url('images/techgasp-hr.png', dirname(__FILE__)); ?>) repeat-x; height: 10px"></div>

<br>

<p>
<a class="button-secondary" href="https://wordpress.techgasp.com" target="_blank" title="Visit Website">More TechGasp Plugins</a>
<a class="button-secondary" href="https://www.techgasp.com/support/" target="_blank" title="TechGasp Support">TechGasp Support</a>
<a class="button-primary" href="https://wordpress.techgasp.com/soundcloud-master/" target="_blank" title="Visit Website"><?php echo $plugin_master_name; ?> Info</a>
<a class="button-primary" href="https://wordpress.techgasp.com/soundcloud-master-documentation/" target="_blank" title="Visit Website"><?php echo $plugin_master_name; ?> Documentation</a>
</p>
<?php
}
if( is_multisite() ) {
add_action( 'admin_menu', 'menu_single_soundcloud_admin_addons' );
}
else {
add_action( 'admin_menu', 'menu_single_soundcloud_admin_addons' );
}
