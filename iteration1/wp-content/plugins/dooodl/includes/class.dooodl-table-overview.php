<?php

if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if(!class_exists("Dooodl_Overview_Table")){
	class Dooodl_Overview_Table extends WP_List_Table {

		private $query_args = false;
		private $query = false;
		private $mode = false;
		private $current_view_label = false;

		private $count_total = 0;
		private $count_approved = 0;
		private $count_moderation = 0;
		private $count_trash = 0;

		function __construct(){
	        global $status, $page;
	        //Set parent defaults

	        $this->query_args = array(
				'posts_per_page' => $this->get_items_per_page('dooodls_per_page', 25),
				'order' => 'DESC',
				'orderby' => 'id',
				'post_type' => 'dooodl'
			);
	        parent::__construct();

	    }

	    public function get_view_label(){
	    	if(!$this->current_view_label){
	    		$this->current_view_label = ( !empty($_REQUEST['dooodl_view']) ? $_REQUEST['dooodl_view'] : 'all');
	    	}
	    	return $this->current_view_label;
	    }

	    function setQuery($args){
	    	$this->query_args = $args;
	    }

	    public function setMode($newMode){
	    	$all_modes = array();
	    	$all_modes[] = "approved";
	    	$all_modes[] = "moderation";
	    	$all_modes[] = "all";
	    	if(in_array($newMode, $all_modes)){
	    		$this->mode = $newMode;
	    		if($this->mode == "all"){
	    			$this->mode = false;
	    		}
	    	}
	    }

	    private function process_query(){
	    	$current = $this->get_view_label();

	    	//do we want approved/disapproved? If so: Use the meta query
	    	if($current !== "all" && $current !== "trash"){
	    		if($current == "approved"){
	    			$the_state = "yes";
	    		}
	    		else{
	    			$the_state = "no";
	    		}

	    		$meta_approved = array();
				$meta_approved["key"] = "approved";
				$meta_approved["value"] = $the_state;
				$meta_approved["compare"] = "=";

				$meta_query = array();
				$meta_query[] = $meta_approved;

				$this->query_args['meta_query'] = $meta_query;
	    	}elseif($current == "trash"){
	    		//do we want to see the trashed items?
	    		$this->query_args['post_status'] = "trash";
	    	}

	    	$this->query = new WP_Query($this->query_args);
	    }

	    function column_default($item, $column_name){

	    	$id = $item['ID'];

	    	switch($column_name){
				case "image":
					
					$url = dooodl_get_image_url($id, array(100,100));
					
					return '<img src="' .$url .'" width="100" height="100" />';
				break;

				case "dooodl_author":
					return get_field('author_name', $id);
				break;

				case "date":
					return $item['date'];
				break;

				case "approved":
					$img = "";
					if(get_field('approved', $id) == "yes"){
						$img = '<span class="dashicons dashicons-yes"></span>';
						$img .= __("Approved", 'dooodl');
					}
					else{
						$img = '<span class="dashicons dashicons-no"></span>';
						$img .= __("Not approved", 'dooodl');
					}
					return $img;
				break;

				case "description":
					return get_field('description', $id);
				break;
			}
	    }


	    function column_cb($item){
	        return sprintf(
	            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
	            /*$1%s*/ 'dooodl_selected',  //Let's simply repurpose the table's singular label ("movie")
	            /*$2%s*/ $item['ID']                //The value of the checkbox should be the record's id
	        );
	    }


	    function column_title($item){
	    	$id = $item['ID'];
	    	$editurl = get_edit_post_link($id);

	    	$current_view = $this->get_view_label();

	    	if('trash' == $current_view){
	    		$nonce_url = get_delete_post_link($id, '', true);
		    	$actions = array(
	            	'delete'    => '<a href="'. $nonce_url .'">'. __('Delete permanently', 'dooodl') .'</a>'
	        	);
	    	}
	    	else{
		    	$nonce_url = get_delete_post_link($id);
		    	$actions = array(
	            	'edit'      => sprintf('<a href="post.php?post=%s&action=%s">'. __('Edit', 'dooodl') .'</a>', $id,'edit'),
	            	'delete'    => '<a href="'. $nonce_url .'">'. __('Delete', 'dooodl') .'</a>'
	        	);
		    }

			return "<strong><a href='". $editurl ."' class='row-title'>" . $item['title'] . "</a></strong>" . $this->row_actions($actions) ;
	    }




		function get_columns(){
	        $new_columns = array();

		    $new_columns['cb'] = '<input type="checkbox" />';
		    $new_columns['image'] = __('Dooodl','dooodl');
		    $new_columns['title'] = _x('Title', 'column name','dooodl');
		    $new_columns['description'] = _x('Description', 'column name','dooodl');
		    $new_columns['approved'] = __('Status','dooodl');
		    $new_columns['dooodl_author'] = __('Author','dooodl');
		    $new_columns['date'] = _x('Date', 'column name','dooodl');

		    return $new_columns;
	    }

	    function get_sortable_columns() {
	        $sortable_columns = array(
	            'title'     => array('title',false),     //true means it's already sorted
	            'date'    => array('date',false),
	            'approved'    => array('approved',false),
				'dooodl_author' =>array('dooodl_author', false)
			);
	        return $sortable_columns;
	    }

	    function get_bulk_actions() {
	       $actions = array();
	       $current = $this->get_view_label();

	       if($current == "trash"){
				$actions['dooodl_restore'] = __("Restore",'dooodl');
				$actions['dooodl_permadelete'] = __("Delete Permanently",'dooodl');
			}

	        if($current == "moderation" || $current == "all"){
	        	$actions['dooodl_approve'] = __("Approve",'dooodl');
	        }

	        if($current == "all" || $current == "approved"){
				$actions['dooodl_unapprove'] = __("Disapprove / Move to moderation queue",'dooodl');
			}

			if($current !== "trash"){
	        	$actions['dooodl_delete'] = __("Delete",'dooodl');
	        }

	        return $actions;
	    }

	    public function process_bulk_action() {
	    	if( 'dooodl_delete'===$this->current_action() ) {
	          	$selected_items = $_REQUEST['dooodl_selected'];
	        	if($selected_items){
	        		$count = 0;
					foreach($selected_items as $item_id){
						wp_trash_post($item_id);
						$count++;
					}
		        }
	        }



	        if( 'dooodl_approve'===$this->current_action() ) {
	        	$selected_items = $_REQUEST['dooodl_selected'];

	        	if($selected_items){
	        		$count = 0;
					foreach($selected_items as $item_id){
						update_field('approved','yes', $item_id);
						$count++;
					}
		        }
	        }


	        if( 'dooodl_unapprove'===$this->current_action() ) {
	            $selected_items = $_REQUEST['dooodl_selected'];

	        	if($selected_items){
	        		$count = 0;
					foreach($selected_items as $item_id){
						update_field('approved','no', $item_id);
						$count++;
					}
		        }
	        }

	        if( 'dooodl_restore'===$this->current_action() ) {
	         	$selected_items = $_REQUEST['dooodl_selected'];

	        	if($selected_items){
	        		$count = 0;
					foreach($selected_items as $item_id){
						wp_untrash_post($item_id);
						$count++;
					}

		        }
	        }

	        if( 'dooodl_permadelete'===$this->current_action() ) {
	           $selected_items = $_REQUEST['dooodl_selected'];

	        	if($selected_items){
	        		$count = 0;
					foreach($selected_items as $item_id){
						wp_delete_post($item_id, true);
						$count++;
					}
		        }
	        }

	        return $count;

	    }


	    function get_views(){
			$views = array();
			$current = ( !empty($_REQUEST['dooodl_view']) ? $_REQUEST['dooodl_view'] : 'all');

			$obsolete_query_args = array();
			$obsolete_query_args[] = "dooodl_success";
			$obsolete_query_args[] = "dooodl_action";
			$obsolete_query_args[] = "dooodl_count";
			$obsolete_query_args[] = "paged";
			$url = remove_query_arg($obsolete_query_args);

			//All link
			$class = ($current == 'all' ? ' class="current"' :'');
			$url = remove_query_arg('dooodl_view', $url);
			$views['all'] = "<a href='{$url}' {$class} >" . __('All','dooodl') . " <span class='count'>({$this->count_total})</span></a>";

			$url = add_query_arg('dooodl_view','approved', $url);
			$class = ($current == 'approved' ? ' class="current"' :'');
			$views['approved'] = "<a href='{$url}' {$class} >" . __('Approved','dooodl') . " <span class='count'>({$this->count_approved})</span></a>";

			$url = add_query_arg('dooodl_view','moderation', $url);
			$class = ($current == 'moderation' ? ' class="current"' :'');
			$views['moderation'] = "<a href='{$url}' {$class} >" . __('Moderation Queue','dooodl') . " <span class='count'>({$this->count_moderation})</span></a>";

			$url = add_query_arg('dooodl_view','trash', $url);
			$class = ($current == 'trash' ? ' class="current"' :'');
			$views['trash'] = "<a href='{$url}' {$class} >" . __('Trash','dooodl') . " <span class='count'>({$this->count_trash})</span></a>";

			return $views;
		}



	    function query_to_array($query){
	    	$arr = array();

	    	if($query->have_posts()){
	    		while($query->have_posts()){
	    			$query->the_post();

	    			$item = array();
		    		$item['ID'] = get_the_ID();
		    		$item['title'] = get_the_title();
		    		$item['date'] = get_the_date("j M Y");
		    		$item['timestamp'] = get_the_time('U');
		    		$item['approved'] = get_field("approved");
		    		$item['dooodl_author'] = get_field("author_name");

		    		$arr[] = $item;
	    		}

	    	}
	    	wp_reset_postdata();
	    	return $arr;
	    }

	    function no_items(){
	    	$current = $this->get_view_label();

	     	if($current == "trash"){
				_e('No Dooodls in the trash! Yay!','dooodl');
			}

	        if($current == "moderation"){
	        	_e('There are currently no Dooodls in moderation.','dooodl');
	        }

	        if($current == "all"){
				_e("You haven't received any Dooodls yet. :-( ",'dooodl');
			}

			if($current == "approved"){
	        	_e("There aren't any approved Dooodls at this moment. :-(",'dooodl');
	        }

	        return $actions;

	    }

	    private function get_counts(){
			$this->query_args['meta_query'] = $meta_query;

	    	$args = array();
	    	$args['post_type'] = "dooodl";
	    	$args['posts_per_page'] = -1;
	    	$args['post_status'] = "publish";


	    	//all (published)
	    	$totalCounts = wp_count_posts('dooodl');
	    	$this->count_total = $totalCounts->publish;


	    	//trash
	    	$args['post_status'] = "trash";
	    	$query = new WP_Query($args);
	    	$this->count_trash = $query->post_count;

	    	//approved
	    	$meta_approved = array();
			$meta_approved["key"] = "approved";
			$meta_approved["value"] = "yes";
			$meta_approved["compare"] = "=";
			$meta_query = array();
			$meta_query[] = $meta_approved;
	    	$args['post_status'] = "publish";
	    	$args['meta_query'] = $meta_query;
	    	$query = new WP_Query($args);
	    	$this->count_approved = $query->post_count;

	    	//moderation queue
	    	$meta_approved = array();
			$meta_approved["key"] = "approved";
			$meta_approved["value"] = "no";
			$meta_approved["compare"] = "=";
			$meta_query = array();
			$meta_query[] = $meta_approved;
	    	$args['post_status'] = "publish";
	    	$args['meta_query'] = $meta_query;
	    	$query = new WP_Query($args);
	    	$this->count_moderation = $query->post_count;

	    }

	
	    function prepare_items() {
	        global $wpdb; 

	        $per_page = $this->get_items_per_page('dooodls_per_page', 25);
	        $this->query_args['posts_per_page'] = $per_page;

	        $current_page = $this->get_pagenum();
	        $this->query_args['paged'] = $current_page;

	        $columns = $this->get_columns();
	        $hidden = array();
	        $sortable = $this->get_sortable_columns();
	        $this->_column_headers = array($columns, $hidden, $sortable);

	        $this->get_counts();
	        $viewLabel = $this->get_view_label();
	        
	        switch($viewLabel) {
	        	case 'all':
	        		$total_items = $this->count_total;
	        	break;
	        	case 'approved':
	        		$total_items = $this->count_approved;
	        	break;
	        	case 'moderation':
	        		$total_items = $this->count_moderation;
	        	break;
	        	case 'trash':
	        		$total_items = $this->count_trash;
	        	break;
	        }
   	        

	        $this->process_query();
	        $data = $this->query_to_array($this->query);


	        function usort_reorder($a,$b){
	            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'ID'; //If no sort, default to title
	            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; //If no order, default to asc

	            switch($orderby){
	            	case 'menu_order':
	            	case 'ID':
	            		$result = $a[$orderby] - $b[$orderby];
	            	break;
	            	case 'date':
	            		$result = $a['timestamp'] - $b['timestamp'];
	            	break;

	            	default:
	            		$result = strcmp(strtolower($a[$orderby]), strtolower($b[$orderby])); //Determine sort order
	            	break;

	            }

	            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
	        }
	        usort($data, 'usort_reorder');

	        $this->items = $data;	

	        $this->set_pagination_args( array(
	            'total_items' => $total_items,                  //WE have to calculate the total number of items
	            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
	            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
	        ) );
	    }


	}


}
