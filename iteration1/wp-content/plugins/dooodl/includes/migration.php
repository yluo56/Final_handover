<?php


function dooodl_runupdate(){
	global $wpdb;
	$table_name = dooodlv1_get_table_name();

	$result = $wpdb->get_var("SHOW TABLES LIKE '$table_name'");
	$old_system_active = get_option('dooodl_old_system_active', "NOVALUE");

	if($result == $table_name && $old_system_active == "NOVALUE") { // only trigger these option updates if their not in the database (because they trigger redirects... we wanna be carfeull with that stuff)
		update_option('dooodl_old_system_active', true);
		update_option('dooodl_old_system_force_redirect', true);
	}
}

function dooodlv1_get_table_name(){
	global $wpdb;
	return $wpdb->prefix . 'dooodl';
}

function dooodlv1_getDooodls($count = 10){
	global $wpdb;
	$doodls = $wpdb->get_results("SELECT id, username, title, timestamp, description, url, moderation, ext FROM ". dooodlv1_get_table_name() ." ORDER BY id ASC LIMIT 0," . $count);
	return  $doodls;
}

function dooodl_old_system_check(){
	if(true == get_option('dooodl_old_system_active', false)){
		if(true == get_option('dooodl_old_system_force_redirect', false)){
			//only force it on the user once...
			update_option('dooodl_old_system_force_redirect', false);
			$url = admin_url('admin.php?page=dooodl-migration');

			wp_redirect($url);
		}
		else{
			$qv = $_GET['page'];

			$hideUrls = array();
			$hideUrls[] = "dooodl-perform-migration-v2";
			$hideUrls[] = "dooodl-migration";

			if(!in_array($qv, $hideUrls)){
				add_action( 'admin_notices', 'dooodlv2_nag_migration' );
			}
		}
	}
}

function dooodlv2_nag_migration(){
	$url = admin_url('admin.php?page=dooodl-migration');
	?>
		<div class="updated">
	        <p><?php echo sprintf(__( 'The Dooodl plugin has updated and requires some database upgrades. Please <a href="%s">click here</a> to perform the upgrade before using the new version of Dooodl on your site.', 'dooodl' ), $url); ?></p>
	    </div>
	<?php
}

function dooodlv2_perform_migration_part_dooodl_table($incomingDooodls){
	global $wpdb;

	$newDooodls = array();
	$oldDooodls = $incomingDooodls;

	foreach($oldDooodls as $dooodl) {
		$username 		= sanitize_text_field($dooodl->username);
		$title 			= sanitize_text_field($dooodl->title);
		$description 	= sanitize_text_field($dooodl->description);
		$url 			= sanitize_text_field($dooodl->url);
		$isApproved 	= $dooodl->moderation == "live" ? "yes" : "no";
		$originalId		= $dooodl->id;
		$extension		= $dooodl->ext;
		$imageName 		= $originalId . "." . $extension;
		$datetime 		= $dooodl->timestamp;

		$newDooodl = array();
		$newDooodl['post_type'] = 'dooodl';
		$newDooodl['post_title'] = $title;
		$newDooodl['post_status'] = "publish";
		$newDooodl['post_date'] = $datetime;

		$dooodl_id = wp_insert_post($newDooodl);
		$newDooodls[] = $dooodl_id;

		update_field(DOOODL_APPROVED, $isApproved, $dooodl_id);
		update_field(DOOODL_AUTHOR_NAME, $username, $dooodl_id);
		update_field(DOOODL_AUTHOR_URL, $url, $dooodl_id);
		update_field(DOOODL_DESCRIPTION, $description, $dooodl_id);

		$filename = dooodl_getUploadsDir() . $imageName; //TODO: get doodlsuploaddir

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

		$wpdb->delete(dooodlv1_get_table_name(), array( 'ID' => $originalId ), array( '%d' ));
	}

	return $newDooodls;
}

function dooodlv2_migrate_option($newName, $oldName, $defaultValue, $isBoolean = false){
	global $dooodlReduxConfig;

	$value = get_option($oldName, false);

	if(false === $value){
		$value = $defaultValue;
	}

	if(true === $isBoolean){
		if("on" === $value){
			$value == true;
		}
		if("off" === $value){
			$value == false;
		}
	}

	$dooodlReduxConfig->ReduxFramework->set($newName, $value);

	delete_option($oldName);
}

function dooodlv2_perform_migration_part_redux_options(){
	global $dooodlReduxConfig;

	dooodlv2_migrate_option( "moderation_enabled", 					"dooodl_moderate", false, true);
	dooodlv2_migrate_option( "email_notification", 					"dooodl_notify_on_new", true);
	dooodlv2_migrate_option( "widget_template", 					"dooodl_template", dooodl_get_whtml_template());

	dooodlv2_migrate_option( "gallery_title_color", 				"dooodl_title_textcolor", "#ffffff");
	dooodlv2_migrate_option( "gallery_title_bg_color", 				"dooodl_title_backgroundcolor", "#ff0066");
	dooodlv2_migrate_option( "gallery_intro_color", 				"dooodl_intro_textcolor", "#ffffff");
	dooodlv2_migrate_option( "gallery_intro_bg_color", 				"dooodl_intro_backgroundcolor", "#333333");
	dooodlv2_migrate_option( "gallery_text_color", 					"dooodl_body_textcolor", "#ffffff");
	dooodlv2_migrate_option( "gallery_body_bg_color", 				"dooodl_body_backgroundcolor", "#eeeeee");
	dooodlv2_migrate_option( "gallery_dooodl_item_title_color", 	"", "#ffffff");
	dooodlv2_migrate_option( "gallery_dooodl_item_bg_color", 		"dooodl_td_backgroundcolor", "#333333");
	dooodlv2_migrate_option( "gallery_link_color", 					"dooodl_link_textcolor", "#ffffff");
	dooodlv2_migrate_option( "gallery_link_bg_color", 				"dooodl_link_backgroundcolor", "#ff0066");
	dooodlv2_migrate_option( "gallery_custom_css", 					"", ""); //TODO

	dooodlv2_migrate_option( "use_flash_viewer", 					"dooodl_flash_gallery", false, true);

	dooodlv2_migrate_option( "dooodl_flash_frameColor", 			"dooodl_link_backgroundcolor", "#ffffff");
	dooodlv2_migrate_option( "dooodl_flash_backColor", 				"dooodl_link_backgroundcolor", "#ffff00");
	dooodlv2_migrate_option( "dooodl_flash_bgOuterColor", 			"dooodl_link_backgroundcolor", "#000000");
	dooodlv2_migrate_option( "dooodl_flash_bgInnerColor", 			"dooodl_link_backgroundcolor", "#777777");
}

function dooodlv2_perform_migration(){
	?>
		<div class='wrap dooodl-migration-manager-perform-migration'>
			<h2><?php _e('Dooodl Migration Manager', 'dooodl'); ?></h2>
			<div class="fjs-dooodl-success dooodl-hidden updated">
				<h2>Upgrade complete</h2>
				<p>
					<?php _e("Migration completed successfully.","dooodl"); ?> <br/>
				</p>
				<p>
					<span class="fjs-dooodl-total-count"></span> <?php _e("Dooodls have been migrated.","dooodl"); ?> <br/>
				</p>
				<p>
					<?php _e("You can now use Dooodl as you usually do.","dooodl"); ?> <br/>
					<?php _e("Enjoy!","dooodl"); ?>
				</p>
			</div>

			<div class="fjs-dooodl-progress updated dooodl-panel--progress">
				<p>
					<h2><img src="<?php echo admin_url('/images/spinner.gif'); ?>" class="dooodl-spinner"/> Upgrade in progress</h2>
					<?php _e("The migration of your Dooodls is currently in progress...","dooodl"); ?> <br/>
					<?php _e("Please don't leave this page until this is finished.","dooodl"); ?> <br/>
				</p>

				<div class="fjs-dooodl-progress-holder dooodl-progress-holder">
					<div class="fjs-dooodl-progress-bar dooodl-progress-bar"> &nbsp;
					</div>
				</div>
				<p class="dooodl-progress-information"><?php _e("Current progress:","dooodl"); ?> <span class="fjs-dooodl-progress-label dooodl-label">0</span><span class="dooodl-label">%</span> 
					<span class="dooodl-progress-details">
						(<?php _e("Total:","dooodl"); ?> <span class="fjs-dooodl-total-count"></span> - <?php _e("Remaining:","dooodl"); ?> <span class="fjs-dooodl-remaining-count"></span>)
					</span>
				</p>
				<p class="fjs-dooodl-database-upgrade-step dooodl-hidden"><?php _e("Finalizing upgrade... Just a few more seconds now...","dooodl"); ?></p>
			</div>

		</div>

	<?php
}

function dooodlv2_migration_bach_update() {
	$oldDooodls = dooodlv1_getDooodls(1);
	dooodlv2_perform_migration_part_dooodl_table($oldDooodls);

	//send updated stats to the frontend
	dooodlv2_migration_ajax_stats();
}

function dooodlv2_migration_ajax_stats() {
	global $wpdb;

	$count = $wpdb->get_var("SELECT count(id) FROM ". dooodlv1_get_table_name() ." ORDER BY id ASC");
	$count = intval($count);

	$data = array();
	$data['count'] = $count;

	if($count == 0) {
		$data['migration_complete'] = true;
	}

	wp_send_json($data);
}

function dooodlv2_migration_database_update() {
	dooodlv2_perform_migration_part_redux_options();
	dooodlv2_migration_remove_table();
	update_option('dooodl_old_system_active', false);

	$data = array();
	$data['success'] = true;

	wp_send_json($data);
}

function dooodlv2_migration_remove_table() {
	global $wpdb;

	$table_name = dooodlv1_get_table_name();
	$sql = "DROP TABLE IF EXISTS $table_name;";
	$result = $wpdb->query($sql);
}

function dooodlv2_migration_manager(){
	?>
		<div class='wrap'>
			<h2><?php _e('Dooodl Migration Manager', 'dooodl'); ?></h2>
			<p>
				<?php _e("Your WordPress install currently has an old Dooodl version in the database.","dooodl"); ?> <br/>
				<?php _e("To upgrade to the newest version, please run the migration system by clicking the button below.","dooodl"); ?> <br/>
			</p>
			<p>
				<?php _e("While this migration system has been written and tested vigirously by a passionate developer, it won't harm you to back up your WordPress database first.","dooodl"); ?> <br/>
				<?php _e("Once you are sure your WordPress database is safely backed up, feel free to move ahead.","dooodl"); ?> <br/>
			</p>
			<hr />
			<p>
				<?php _e("This migration will perform the following actions.","dooodl"); ?>
			</p>
			<ul class="dooodl-list">
				<li><?php _e("Migrate every 'old' Dooodl to a new version.","dooodl"); ?> </li>
				<li><?php _e("After that, the (obsolete) Dooodl WordPress table will be removed.","dooodl"); ?> </li>
				<li><?php _e("Move the options to the new Dooodl Settings panel.","dooodl"); ?> </li>
			</ul>
			<p>
				<a href="<?php echo admin_url('admin.php?page=dooodl-perform-migration-v2'); ?>" class="button-primary"><?php _e('Perform migration', 'dooodl'); ?></a>
			</p>
		</div>
	<?php
}

?>