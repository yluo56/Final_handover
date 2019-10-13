<?php


function dooodl_register_post_type(){
	$labels = array(
		'name'                => _x( 'Dooodl', 'Post Type General Name', 'dooodl' ),
		'singular_name'       => _x( 'Dooodl', 'Post Type Singular Name', 'dooodl' ),
		'menu_name'           => __( 'Dooodl', 'dooodl' ),
		'parent_item_colon'   => __( 'Parent Dooodl:', 'dooodl' ),
		'all_items'           => __( 'Dooodls overview', 'dooodl' ),
		'view_item'           => __( 'View Dooodl', 'dooodl' ),
		'add_new_item'        => __( 'Add New Dooodl', 'dooodl' ),
		'add_new'             => __( 'Add New', 'dooodl' ),
		'edit_item'           => __( 'Edit a Dooodl', 'dooodl' ),
		'update_item'         => __( 'Update Dooodl', 'dooodl' ),
		'search_items'        => __( 'Search Dooodls', 'dooodl' ),
		'not_found'           => __( 'Not found', 'dooodl' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'dooodl' ),
	);

	$args = array(
		'label'               	=> __( 'Dooodls', 'dooodl' ),
		'description'         	=> __( 'Dooodls created and submitted by your visitors', 'dooodl' ),
		'labels'              	=> $labels,
		'supports'            	=> array('title'),
		'taxonomies'          	=> array(),
		'hierarchical'        	=> false,
		'public'              	=> false,
		'show_ui'             	=> true,
		'show_in_menu'        	=> false,
		'show_in_nav_menus'   	=> false,
		'show_in_admin_bar'   	=> false,
		'menu_position'       	=> 6,
		'menu_icon'           	=> '',
		'can_export'          	=> true,
		'has_archive'         	=> false,
		'exclude_from_search' 	=> true,
		'publicly_queryable'  	=> false,
		'capability_type'     	=> 'post',
		'rewrite'				=> array('slug'=>'dooodls')
	);

	register_post_type('dooodl', $args);
	dooodl_register_meta();
}

function dooodl_register_meta(){
	if(function_exists("register_field_group")){
		register_field_group(array (
			'id' => 'acf_dooodl',
			'title' => 'Dooodl',
			'fields' => array (
				array (
					'key' => 'field_53b6df5971ef5',
					'label' => 'Is this Dooodl approved?',
					'name' => 'approved',
					'type' => 'radio',
					'instructions' => 'If this Dooodl should not be publicly visible on the website, set this to no. This will prevent anybody from seeing it but it will not delete it.',
					'required' => 1,
					'choices' => array (
						'yes' => 'Yes',
						'no' => 'No',
					),
					'other_choice' => 0,
					'save_other_choice' => 0,
					'default_value' => 'yes',
					'layout' => 'vertical',
				),
				array (
					'key' => 'field_53b6e0a837724',
					'label' => 'Author Name',
					'name' => 'author_name',
					'type' => 'text',
					'required' => 1,
					'default_value' => 'Anonymous',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'formatting' => 'none',
					'maxlength' => '',
				),
				array (
					'key' => 'field_53b6e04f37722',
					'label' => 'Author URL',
					'name' => 'author_url',
					'type' => 'text',
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'formatting' => 'none',
					'maxlength' => '',
				),
				array (
					'key' => 'field_53b6e06637723',
					'label' => 'Description',
					'name' => 'description',
					'type' => 'textarea',
					'default_value' => '',
					'placeholder' => '',
					'maxlength' => '',
					'rows' => '',
					'formatting' => 'br',
				),
			),
			'location' => array (
				array (
					array (
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'dooodl',
						'order_no' => 0,
						'group_no' => 0,
					),
				),
			),
			'options' => array (
				'position' => 'normal',
				'layout' => 'default',
				'hide_on_screen' => array (
					0 => 'permalink',
					1 => 'the_content',
					2 => 'excerpt',
					3 => 'custom_fields',
					4 => 'discussion',
					5 => 'comments',
					6 => 'revisions',
					7 => 'slug',
					8 => 'author',
					9 => 'format',
					10 => 'categories',
					11 => 'tags',
					12 => 'send-trackbacks',
				),
			),
			'menu_order' => 0,
		));
	}
}
?>