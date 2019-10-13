<?php

function dooodl_version_check() {
	$current = get_option('dooodl_version', "none");

	if($current !== DOOODL_VERSION) {
		dooodl_runupdate();
		update_option('dooodl_version', DOOODL_VERSION);
	}
}

function dooodl_react_to_ajax_calls() {
	global  $wp_query;

    if(isset($wp_query->query_vars['dooodl'])){
    	$action = "dooodl/";
    	$action .= $wp_query->query_vars['dooodl'];
        $action = untrailingslashit($action);
	    do_action($action);
    }
}

function dooodl_fix_edit_menu(){
	$screen = get_current_screen();
	if($screen->post_type== "dooodl"){
		add_action('admin_footer','dooodl_fix_css_in_menu');
	}
}

function dooodl_add_metabox(){
	add_meta_box( "dooodl-image", "Dooodl", "dooodl_metabox_image", "dooodl", "side", "high", false );
}

function dooodl_metabox_image($post){
	$id = $post->ID;

	$attach_id = get_post_thumbnail_id($id);
	$img = wp_get_attachment_image_src($attach_id, false, false );
	$url = $img[0];
	?>

	<p class="hide-if-no-js">
		<img width="250" height="250" src="<?php echo $url; ?>" class="attachment-post-thumbnail" alt="Dooodl #'. <?php echo $id; ?> .'" />
	</p>
	<?php
}

function dooodl_fix_css_in_menu(){
	?>

	<style type="text/css">
		#favorite-actions, .add-new-h2, .tablenav { display: none; }
		#misc-publishing-actions,
        #minor-publishing-actions,
        #delete-action{
            display: none;
        }
	</style>

	<script type="text/javascript">
	(function($){
		$('#toplevel_page_dooodl-overview').addClass('wp-has-submenu wp-has-current-submenu wp-menu-open menu-top');
		$('#toplevel_page_dooodl-overview > a').addClass('wp-has-submenu wp-has-current-submenu wp-menu-open menu-top menu-icon-page').removeClass('wp-not-current-submenu');
	})(jQuery);
	</script>

	<?php
}

function dooodl_enqueue_admin_styles(){
	$screen = get_current_screen();
	$labels = array();

	// only include the migration manager in the migration screen.
	if($screen->id === 'admin_page_dooodl-perform-migration-v2') {
		$labels['confirmNavigation'] = __('Dooodl database migration is currently in progress. Navigation away from this page will cancel the progress. While this can still be picked up later on, it is recommended that you let this process finish now.', 'dooodl');

		wp_register_script("dooodl_admin_migration_manager", dooodl_get_plugin_uri('assets/migration_manager.js'), array('jquery'), DOOODL_VERSION, true );
		wp_localize_script("dooodl_admin_migration_manager", "DooodlAdminLabels", $labels );
		wp_enqueue_script('dooodl_admin_migration_manager');
	}

	wp_enqueue_style('dooodl_admin_css', dooodl_get_plugin_uri('assets/admin_style.css'), false, DOOODL_VERSION );	
}




function dooodl_show_gallery_xml(){
	$args = array();
	$args['post_type'] = 'dooodl';
	$args['posts_per_page'] = -1;
	$args['orderby'] = 'date';
	$args['order'] = 'desc';
	$args['meta_query'] = array(
		array('key'=>'approved', 'value'=> 'yes', 'compare'=> '=')
	);
	$query = new WP_Query($args);

	header("HTTP/1.1 200 OK");
	header('Content-type: text/xml');
	print('<' . '?xml version="1.0" encoding="UTF-8"?'. '>');

?> <tiltviewergallery>
	<photos>
		<?php
		if($query->have_posts()){
			while($query->have_posts()){
				$query->the_post();

				$image_url 		= dooodl_get_image_url(get_the_ID());
				$author_name 	= get_field("author_name", get_the_ID());
				$author_url 	= get_field("author_url", get_the_ID());
				$description 	= get_field("description", get_the_ID());
				$title 			= get_the_title();
				$date			= get_the_date();


				?>
					<photo imageurl="<?php print($image_url); ?>"<?php if($author_url != ""){ ?> linkurl="<?php print($author_url); ?>"<?php } ?> >
                            <title><![CDATA[<?php  print($title);  ?>]]></title>
                            <description><![CDATA[<?php
                             print($description);
                             print("\n");print("\n");
                             if($author_url != ""){
?><b><?php _e('Link', 'dooodl'); ?></b>: <a href="<?php echo($author_url); ?>" target="_blank"><?php print($author_url); ?></a><?php } ?>

<b><?php _e('Author', 'dooodl'); ?></b>: <?php print($author_name); print("\n"); ?>
<b><?php _e('Submitted', 'dooodl'); ?></b>: <?php print($date);

							?>]]></description>
                        </photo>


				<?php
			}
		}
?>
    </photos>
</tiltviewergallery><?php
exit();

}


function dooodl_show_gallery_scroll(){
	header("HTTP/1.1 200 OK");
	$page = $_REQUEST['page'];

	if($page == ''){
		die('false');
	}
	dooodl_include('gallery/includes/contentdisplay.php');
	exit();
}

function dooodl_add_endpoints(){
	 add_rewrite_endpoint( 'dooodl', EP_ROOT );
	 flush_rewrite_rules();
}

function dooodl_show_gallery(){
	add_filter('template_include', 'dooodl_template_hijack_gallery', 1);
}

function dooodl_show_creator(){
	add_filter('template_include', 'dooodl_template_hijack_creator', 1);
}

function dooodl_template_hijack_creator($template){
	global $dooodl_plugin_path;
	$template = $dooodl_plugin_path . "creator/index.php";
	return $template;
}

function dooodl_template_hijack_gallery($template){
	global $dooodl_plugin_path;
	$template = $dooodl_plugin_path . "gallery/index.php";
	return $template;
}

function dooodl_add_query_vars($qv){
	$qv[] = "action";
	return $qv;
}

function dooodl_enqueue_scripts_creator(){
	add_action('wp_enqueue_scripts', 'dooodl_replace_enqueue_scripts_creator',99999999);
	add_action('wp_enqueue_scripts', 'dooodl_replace_enqueue_styles_creator',99999999);
}

function dooodl_enqueue_scripts_gallery(){
	add_action('wp_enqueue_scripts', 'dooodl_replace_enqueue_scripts_gallery',99999999);
	add_action('wp_enqueue_scripts', 'dooodl_replace_enqueue_styles_gallery',99999999);
}

function dooodl_replace_enqueue_styles_creator(){
	$plugin_uri = dooodl_get_plugin_uri();

    wp_enqueue_style( "dooodl_creator_screen", $plugin_uri . "creator/css/screen.css", false, DOOODL_VERSION, "screen");
}

function dooodl_replace_enqueue_scripts_creator(){
	$plugin_uri = dooodl_get_plugin_uri();

	wp_register_script( "dooodl_sketcher", $plugin_uri . "creator/js/libs/sketcher.js", false, DOOODL_VERSION, true );
	wp_register_script( "dooodl_js_app", $plugin_uri . "creator/js/script.js", array('jquery', 'dooodl_sketcher'), DOOODL_VERSION, true );

	$vars = dooodl_prepareJavascriptVars();

	wp_localize_script( "dooodl_js_app", "DooodlVars", $vars );
	wp_enqueue_script('dooodl_js_app');
}


function dooodl_replace_enqueue_styles_gallery(){
    $plugin_uri = dooodl_get_plugin_uri();

    wp_enqueue_style( "dooodl_gallery_style", $plugin_uri . "gallery/css/layout.css", false, DOOODL_VERSION, "all" );
}

function dooodl_replace_enqueue_scripts_gallery(){
	global $dooodl_options;

	$plugin_uri = dooodl_get_plugin_uri();

	wp_register_script( "dooodl_swfobject", $plugin_uri . "gallery/js/swfobject.js", false, DOOODL_VERSION, true );
	wp_register_script( "dooodl_script_js_gallery", $plugin_uri . "gallery/js/script.js", array("jquery","dooodl_swfobject"), DOOODL_VERSION, true );

	$vars = dooodl_prepareJavascriptVars();
	$vars['gallery_scroll'] = dooodl_create_url("gallery/scroll");

    $tvars = array();
    $tvars['enabled'] = (bool) $dooodl_options['use_flash_viewer'];
    $tvars['frame_color'] = str_replace("#", "0x", $dooodl_options['dooodl_flash_frame_color']);
    $tvars['back_color'] = str_replace("#", "0x", $dooodl_options['dooodl_flash_backColor']);
    $tvars['bg_outer_color'] = str_replace("#", "0x", $dooodl_options['dooodl_flash_bgOuterColor']);
    $tvars['bg_inner_color'] = str_replace("#", "0x", $dooodl_options['dooodl_flash_bgInnerColor']);
    $tvars['xml_url'] = dooodl_create_url('gallery/xml');

    $vars['tv'] = $tvars;

	wp_localize_script( "dooodl_script_js_gallery", "DooodlVars", $vars );
	wp_enqueue_script('dooodl_script_js_gallery');
}

function dooodl_handle_bulk_edits(){
	$manager = new Dooodl_Overview_Table();
	$count = $manager->process_bulk_action();
	$action = $manager->current_action();

	$target = $_REQUEST['_wp_http_referer'];

	$args = array();
	$args['dooodl_success'] = true;
	$args['dooodl_action'] = $action;
	$args['dooodl_count'] = $count;

	$target = add_query_arg($args, $target);
	wp_redirect($target);
}


function dooodl_show_feedback_notices(){
	$has_valid_url_arg = ( !empty($_REQUEST['dooodl_success']) ? $_REQUEST['dooodl_success'] : false);
	$has_valid_url_arg = false;
	if(!empty($_REQUEST['dooodl_success'])){
		$has_valid_url_arg = $_REQUEST['dooodl_success'];
	}
	elseif(get_current_screen()->post_type == "dooodl" && !empty($_REQUEST['trashed'])){
		$has_valid_url_arg = true;
		$action = "dooodl_delete";
		$ids = $_REQUEST['ids'];
		$ids = explode(',', $ids);
		$count = count($ids); //pretty impossible to get more than one...
	}
	if($has_valid_url_arg){
		if(!isset($action)){
			$action = ( !empty($_REQUEST['dooodl_action']) ? $_REQUEST['dooodl_action'] : false);
			$count = ( !empty($_REQUEST['dooodl_count']) ? $_REQUEST['dooodl_count'] : 0);
		}

		if($action){
			$dooodl_update_message = false;
			switch($action){
				case "dooodl_delete":
					$dooodl_update_message = sprintf(_n('1 Dooodl has been deleted', '%s Dooodls have been deleted' ,$count, 'dooodl'), $count);
				break;
				case "dooodl_approve":
					$dooodl_update_message = sprintf(_n('1 Dooodl has been approved', '%s Dooodls have been approved' ,$count, 'dooodl'), $count);
				break;
				case "dooodl_unapprove":
					$dooodl_update_message = sprintf(_n('1 Dooodl has been moved back to the moderation queue', '%s Dooodls have been moved back to the moderation queue' ,$count, 'dooodl'), $count);
				break;
				case "dooodl_restore":
					$dooodl_update_message = sprintf(_n('1 Dooodl has been restored', '%s Dooodls have been restored' ,$count, 'dooodl'), $count);
				break;
				case "dooodl_permadelete":
					$dooodl_update_message = sprintf(_n('1 Dooodl has been deleted permanently', '%s Dooodls have been deleted permanently' ,$count, 'dooodl'), $count);
				break;
				default:
					$action = false;
				break;
			}

			if($dooodl_update_message){
				?>
					 <div class="updated">
				        <p><?php echo $dooodl_update_message; ?></p>
				    </div>
			    <?php
			}
		}
	}
}

function dooodl_add_settings_link( $links ) {
	$settings_link = '<a href="admin.php?page=dooodl-options&tab=main">Settings</a>';
	array_unshift( $links, $settings_link );
	return $links;
}

function dooodl_load_widgets() {
	register_widget('Dooodl_widget');
}

function dooodl_add_menu(){
	$page_overview 	= add_menu_page(__('Dooodl Overview', 'dooodl'), __('Dooodl', 'dooodl'), 'edit_posts', 'dooodl-overview', 'dooodl_show_overview_page',"dashicons-art", 50);
  	$page_dummy 	= add_submenu_page( "dooodl-overview", "You shouldn't see this", "You shouldn't see this", 'manage_options', "dooodl-dummy-item", '__return_null');
  	if(get_option('dooodl_old_system_active', false)){
  		$page_migration = add_submenu_page( "dooodl-overview", "Dooodl Migration", "Migration", 'manage_options', "dooodl-migration", 'dooodlv2_migration_manager');
  		$page_migration_v2_handler = add_submenu_page( null, "Dooodl Migration Manager", "Migration", 'manage_options', "dooodl-perform-migration-v2", 'dooodlv2_perform_migration');
  	}
    add_action( "load-$page_overview", 'dooodl_add_screen_options' );
}

function dooodl_add_screen_options() {

  $option = 'per_page';
  $args = array(
         'label' => __('Dooodls per page', 'dooodl'),
         'default' => 25,
         'option' => 'dooodls_per_page'
         );


  add_screen_option( $option, $args );

  new Dooodl_Overview_Table();
}

function dooodl_set_screen_options($status, $option, $value) {
	return $value;
}

?>