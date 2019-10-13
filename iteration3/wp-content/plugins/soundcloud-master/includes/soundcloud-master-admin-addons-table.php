<?php
if(!class_exists('WP_List_Table')){
	require_once( get_home_path() . 'wp-admin/includes/class-wp-list-table.php' );
}
class soundcloud_master_admin_addons_table extends WP_List_Table {
	/**
	 * Display the rows of records in the table
	 * @return string, echo the markup of the rows
	 */
	function display() {
	$plugin_master_name = constant('SOUNDCLOUD_MASTER_NAME');
	$path = WP_CONTENT_DIR . '/plugins/soundcloud-master-addons/';
	if ( is_plugin_active( 'soundcloud-master-addons/soundcloud-master-addons.php' ) && file_exists($path) ) {
		$soundcloud_master_addon = "yes";
		$soundcloud_master_addon_get = '<b>All add-ons Installed</b>';
	}
	else{
		$soundcloud_master_addon = "no";
		$soundcloud_master_addon_get = '<a class="button-primary" href="https://wordpress.techgasp.com/soundcloud-master/" target="_blank" title="Get all Add-ons" style="float:left;">Get all Add-ons pack for peanuts</a>';
	}
?>
<table class="widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3"><?php echo $soundcloud_master_addon_get; ?></th>
		</tr>
		<tr>
			<th><h2><img src="<?php echo plugins_url('images/techgasp-minilogo-16.png', dirname(__FILE__)); ?>" style="float:left; height:18px; vertical-align:middle;" /><?php _e('&nbsp;Screenshot', 'soundcloud_master'); ?></h2></th>
			<th><h2><img src="<?php echo plugins_url('images/techgasp-minilogo-16.png', dirname(__FILE__)); ?>" style="float:left; height:18px; vertical-align:middle;" /><?php _e('&nbsp;Description', 'soundcloud_master'); ?></h2></th>
			<th><h2><img src="<?php echo plugins_url('images/techgasp-minilogo-16.png', dirname(__FILE__)); ?>" style="float:left; height:18px; vertical-align:middle;" /><?php _e('&nbsp;Installed', 'soundcloud_master'); ?></h2></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th><?php echo $soundcloud_master_addon_get; ?></th>
			<th></th>
			<th></th>
		</tr>
	</tfoot>

	<tbody>
		<tr class="alternate">
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-admin-addons-widgets.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="300px" height="139px" style="padding:5px;"/></td>
			<td style="vertical-align:middle"><h3>Buttons Widget</h3><p>The perfect widget if you only want to display the Soundcloud Connect Button or the Lyrics Button. A great way to connect people to your Soundcloud profile or to display your cool lyrics page.</p><p>This widget works great when published under any of the below players. You can activate both buttons or a single one, navigate to your wordpress widgets page and start using it. <a href="https://wordpress.techgasp.com/soundcloud-master/" target="_blank"><strong>Demo Link</strong></a>.</p></td>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-check-yes.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="200px" height="121px" style="padding:5px;"/></td>
		</tr>
		<tr>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-admin-addons-widgets.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="300px" height="139px" style="padding:5px;"/></td>
			<td style="vertical-align:middle"><h3>Administrator Dashboard Widget</h3><p>Cool player widget to listen to your favourite Soundcloud Tracks while working on your Wordpress Administrator. Go to your Wordpress Dashboard page and start using it!!! <a href="https://wordpress.techgasp.com/soundcloud-master/" target="_blank"><strong>Demo Link</strong></a>.</p></td>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-check-'.$soundcloud_master_addon.'.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="200px" height="121px" style="padding:5px;"/></td>
		</tr>
		<tr class="alternate">
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-admin-addons-widgets.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="300px" height="139px" style="padding:5px;"/></td>
			<td style="vertical-align:middle"><h3>Soundcloud Profile Widget</h3><p>Fast loading widget to display the Soundcloud User Profiles. A great way to connect people to your Soundcloud Profile, show off your work, sell it and gather followers and likes.</p><p>Navigate to your wordpress widgets page and start using it. <a href="https://wordpress.techgasp.com/soundcloud-master/" target="_blank"><strong>Demo Link</strong></a>.</p></td>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-check-'.$soundcloud_master_addon.'.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="200px" height="121px" style="padding:5px;"/></td>
		</tr>
		<tr>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-admin-addons-widgets.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="300px" height="139px" style="padding:5px;"/></td>
			<td style="vertical-align:middle"><h3>Basic Player Fast Loading Widget</h3><p>The Basic Soundcloud Player Widget was specially designed for fast loading times and is perfect to display a single track. All player options are on automatic settings so it's easy and fast to deploy by any wordpress administrator.</p><p>This widget is fully <b>Mobile Responsive</b>, navigate to your wordpress widgets page and start using it. <a href="https://wordpress.techgasp.com/soundcloud-master/" target="_blank"><strong>Demo Link</strong></a>.</p></td>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-check-'.$soundcloud_master_addon.'.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="200px" height="121px" style="padding:5px;"/></td>
		</tr>
		<tr class="alternate">
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-admin-addons-widgets.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="300px" height="139px" style="padding:5px;"/></td>
			<td style="vertical-align:middle"><h3>Advanced Responsive Player Widget</h3><p>The "top of the line" Advanced Soundcloud Player Widget was specially designed to grant you all Soundcloud Player Options, you have full control over Size, Color, Auto-Play, Display Username, Display Artwork, Display Comments, Display Playcount, Display Share, Display Like, Display Download and Display Buy. All player options are customizable, still extremely easy to use.</p><p>This widget is fully <b>Mobile Responsive</b>, navigate to your wordpress widgets page and start using it. <a href="https://wordpress.techgasp.com/soundcloud-master/" target="_blank"><strong>Demo Link</strong></a>.</p></td>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-check-'.$soundcloud_master_addon.'.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="200px" height="121px" style="padding:5px;"/></td>
		</tr>
		
		<tr>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-admin-addons-widgets.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="300px" height="139px" style="padding:5px;"/></td>
			<td style="vertical-align:middle"><h3>Private Tracks Player Widget</h3><p>Allows your to play your soundcloud private tracks with full control over: Player Color, Auto-Play, Display User, Display Artwork, Display Comments, Display Related, Display Re-P.osts</p><p>This widget is fully <b>Mobile Responsive</b>, navigate to your wordpress widgets page and start using it. <a href="https://wordpress.techgasp.com/soundcloud-master/" target="_blank"><strong>Demo Link</strong></a>.</p></td>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-check-'.$soundcloud_master_addon.'.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="200px" height="121px" style="padding:5px;"/></td>
		</tr>
		<tr class="alternate">
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-admin-addons-shortcodes.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="300px" height="139px" style="padding:5px;"/></td>
			<td style="vertical-align:middle"><h3>Individual Shortcode</h3><p>Soundcloud Master uses TechGasp Wordpress Framework. The <b>Individual Shortcode</b> allows you to have a different customized Soundcloud Player in each page or post. Easy to use it can be found in the plugin <b>Individual Shortcodes menu</b> or when you edit a page or a post under the wordpress text editor. Once you have created your shortcode, Just insert the shortcode <b>[soundcloud-master]</b> anywhere inside that text. <a href="https://wordpress.techgasp.com/soundcloud-master/" target="_blank"><strong>Demo Link</strong></a>.</p></td>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-check-'.$soundcloud_master_addon.'.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="200px" height="121px" style="padding:5px;"/></td>
		</tr>
		<tr>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-admin-addons-shortcodes.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="300px" height="139px" style="padding:5px;"/></td>
			<td style="vertical-align:middle"><h3>Universal Shortcode</h3><p>Soundcloud Master uses TechGasp Wordpress Framework. The <b>Universal Shortcode</b> allows you to have the same Soundcloud Player across different pages or posts. Easy to use it can be found right here under the <b>Universal Shortcodes menu</b>. Once you have created your shortcode, Just insert the shortcode <b>[soundcloud-master-un]</b> anywhere inside the text of your pages or posts. <a href="https://wordpress.techgasp.com/soundcloud-master/" target="_blank"><strong>Demo Link</strong></a>.</p></td>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-check-'.$soundcloud_master_addon.'.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="200px" height="121px" style="padding:5px;"/></td>
		</tr>
		<tr class="alternate">
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-admin-addons-updater.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="300px" height="139px" style="padding:5px;"/></td>
			<td style="vertical-align:middle"><h3>Advanced Updater</h3><p>The Advanced Updater allows you to easily Update / Upgrade your Advanced Plugin. You can instantly update your plugin after we release a new version with more goodies without having to wait for the nightly native wordpress updater that runs every 24/48 hours. Get it fresh, get it fast.</p></td>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-check-'.$soundcloud_master_addon.'.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="200px" height="121px" style="padding:5px;"/></td>
		</tr>
		<tr>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-admin-addons-support.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="300px" height="139px" style="padding:5px;"/></td>
			<td style="vertical-align:middle"><h3>Award Winning Professional Support</h3><p>Need professional help deploying this plugin? TechGasp provides award winning professional wordpress support for all advanced version costumers and wordpress professionals. Support Us and we will Support You.</p></td>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-check-'.$soundcloud_master_addon.'.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="200px" height="121px" style="padding:5px;"/></td>
		</tr>
	</tbody>
</table>
<?php
		}
}
