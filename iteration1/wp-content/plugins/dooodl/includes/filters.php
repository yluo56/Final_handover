<?php

global $dooodl_filtername;

add_filter($dooodl_filtername, 'dooodl_add_settings_link');
add_filter('set-screen-option', 'dooodl_set_screen_options', 10, 3);
add_filter('query_vars', 'dooodl_add_query_vars' );
add_filter('post_updated_messages', 'dooodl_update_messages', 10);

function dooodl_update_messages($messages) {
	/*$bulk_messages['dooodl'] = array(
        'updated'   => _n( '%s Dooodl updated.', '%s Dooodls updated.', $bulk_counts['updated'] , 'dooodl'),
        'locked'    => _n( '%s Dooodl not updated, somebody is editing it.', '%s Dooodls not updated, somebody is editing them.', $bulk_counts['locked'] , 'dooodl'),
        'deleted'   => _n( '%s Dooodl permanently deleted.', '%s Dooodls permanently deleted.', $bulk_counts['deleted'] , 'dooodl'),
        'trashed'   => _n( '%s Dooodl moved to the Trash.', '%s Dooodls moved to the Trash.', $bulk_counts['trashed'] , 'dooodl'),
        'untrashed' => _n( '%s Dooodl restored from the Trash.', '%s Dooodls restored from the Trash.', $bulk_counts['untrashed'], 'dooodl'),
    );*/

	$messages['dooodl'] = array(
		__('Nothing', 'dooodl'),
       	__('Dooodl updated. ', 'dooodl'),
        __('Dooodl updated.', 'dooodl'),
        __('Dooodl deleted.', 'dooodl'),
        __('Dooodl updated.', 'dooodl'),
        __('Nothing', 'dooodl'),
        __('Dooodl published', 'dooodl'),
        __('Dooodl saved', 'dooodl'),
        __('Dooodl submitted', 'dooodl'),
        __('Dooodl scheduled', 'dooodl'),
        __('Dooodl draft updated. Preview page', 'dooodl'),

	);

    return $messages;
}


?>