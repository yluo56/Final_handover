<?php
/*
*   College Education Enqueue css and js files
*/

function college_education_enqueue() {
    
     wp_enqueue_style('college-education-font-Playfair+Display', '//fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i,900,900i', array(),null);        
    wp_enqueue_style('college-education-font-Oxygen', '//fonts.googleapis.com/css?family=Oxygen:300,400,700', array(),null);
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css', array(),null);
    wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/css/fontawesome.css', array(),null);

    wp_enqueue_style('college_education_default', get_template_directory_uri() . '/assets/css/default.css', array(),null);
    wp_enqueue_style('animation', get_template_directory_uri() . '/assets/css/animate.css', array(),null);
    wp_enqueue_style('college_education_menu', get_template_directory_uri() . '/assets/css/college-education.css', array(),null);

    wp_enqueue_script("comment-reply");
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-form');
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.js', array('jquery'), false, true);
    wp_enqueue_script('wow', get_template_directory_uri() . '/assets/js/wow.js', array('jquery'), false, true);
    wp_enqueue_script('college_education_custom_script', get_template_directory_uri() . '/assets/js/college-education-custom.js', array('jquery'), false, true);
    
    college_education_custom_css();
}

add_action('wp_enqueue_scripts', 'college_education_enqueue');

/*
*   Admin Script for Page and post metabox design
*/

function college_education_page_options_load_scripts($hook) {
    $currentScreen = get_current_screen();
    if ($currentScreen->id != "customize") {
        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-sortable');
    }    
}
add_action('admin_enqueue_scripts', 'college_education_page_options_load_scripts');