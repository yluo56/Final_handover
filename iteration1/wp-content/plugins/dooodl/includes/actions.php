<?php

add_action('widgets_init', 'dooodl_load_widgets' );
add_action('init', 'dooodl_register_post_type', 0 );

if(is_admin()){
	//load only if there's an admin-screen to be shown
	add_action('plugins_loaded', 'dooodl_version_check');
	add_action('admin_menu', 'dooodl_add_menu');
	add_action('all_admin_notices', 'dooodl_show_feedback_notices');
	add_action('current_screen', 'dooodl_fix_edit_menu', 99999);
	add_action('add_meta_boxes', 'dooodl_add_metabox');
	add_action('wp_dashboard_setup', 'dooodl_add_dashboard_widget');
	add_action('admin_enqueue_scripts', 'dooodl_enqueue_admin_styles');

	add_action('admin_post_dooodl_delete', 'dooodl_handle_bulk_edits');
	add_action('admin_post_dooodl_approve', 'dooodl_handle_bulk_edits');
	add_action('admin_post_dooodl_unapprove', 'dooodl_handle_bulk_edits');
	add_action('admin_post_dooodl_restore', 'dooodl_handle_bulk_edits');
	add_action('admin_post_dooodl_permadelete', 'dooodl_handle_bulk_edits');

	//migration shit
	register_activation_hook('dooodl/Dooodl.php', 'dooodl_runupdate');
	add_action('init', 'dooodl_old_system_check');
	add_action('wp_ajax_dooodl-get-stats', 'dooodlv2_migration_ajax_stats');
	add_action('wp_ajax_dooodl-batch-update', 'dooodlv2_migration_bach_update');
	add_action('wp_ajax_dooodl-database-update', 'dooodlv2_migration_database_update');
} else {
	//load only if there's a frontend page to be shown
	add_action('init',  'dooodl_add_endpoints');
	add_action('template_redirect', 'dooodl_react_to_ajax_calls' );
	add_action('dooodl/creator/post/new', 'dooodl_add_new');
	add_action('dooodl/gallery', 'dooodl_show_gallery');
	add_action('dooodl/creator', 'dooodl_show_creator');
	add_action('dooodl_creator','dooodl_enqueue_scripts_creator');
	add_action('dooodl_gallery','dooodl_enqueue_scripts_gallery');
	add_action('dooodl/gallery/xml', 'dooodl_show_gallery_xml');
	add_action('dooodl/gallery/scroll', 'dooodl_show_gallery_scroll');
}

?>