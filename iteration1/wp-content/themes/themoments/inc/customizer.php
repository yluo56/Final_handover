<?php
/**
 * themoments Theme Customizer
 *
 * @package themoments
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */


function themoments_customize_register( $wp_customize ) {
  $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
  $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
  $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
  $wp_customize->get_section('static_front_page')->priority = 15;
}
add_action( 'customize_register', 'themoments_customize_register' );


function themoments_customizer_register( $wp_customize ) 
{
  // Do stuff with $wp_customize, the WP_Customize_Manager object.

  $wp_customize->get_section('header_image')->title = __( 'Header / Banner', 'themoments' );

  $wp_customize->add_panel( 'theme_option', array(
    'priority' => 10,
    'title' => __( 'TheMoments Options', 'themoments' ),
    'description' => __( 'TheMoments Options', 'themoments' )
  ) );

  $color_upgrade_info = 'More color options are available in PRO Version.';

  $wp_customize->add_setting( 'color_upgrade_info', array(
    'default' => '',
    'sanitize_callback' => 'wp_kses_post',
  ) );

  $wp_customize->add_control( new Themoments_Theme_Info( $wp_customize ,'color_upgrade_info',array(
    'section' => 'colors',
    'description' => $color_upgrade_info
  ) ) );
  

  /**********************************************/
  /************* BANNER TITLE AND DESCRIPTION SECTION *************/
  /**********************************************/

  $wp_customize->add_setting( 'themoments-home-banner-display-setting', array(
    'sanitize_callback' => 'themoments_sanitize_checkbox',
    'default' => true
  ) );

  $wp_customize->add_control( 'themoments-home-banner-display-control', array(
    'label' => __( 'Display?','themoments' ),
    'section' => 'header_image',
    'settings' => 'themoments-home-banner-display-setting',
    'type' => 'checkbox',
    'priority' => 1
  ) ); 

  $wp_customize->add_setting( 'themoments-home-banner-title-setting', array(
      'sanitize_callback' => 'sanitize_text_field',
      'default' => 'The Moments'
  ) );

  $wp_customize->add_control( 'themoments-home-banner-title-control', array(
    'label' => __( 'Add a Title','themoments' ),
    'section' => 'header_image',
    'settings' => 'themoments-home-banner-title-setting',
    'type' => 'text',
    'priority' => 1
  ) );

  $wp_customize->add_setting( 'themoments-home-banner-description-setting', array(
      'sanitize_callback' => 'sanitize_text_field',
      'default' => 'Theme for Photography'
  ) );

  $wp_customize->add_control( 'themoments-home-banner-description-control', array(
    'label' => __( 'Add a description','themoments' ),
    'section' => 'header_image',
    'settings' => 'themoments-home-banner-description-setting',
    'type' => 'text',
    'priority' => 2
  ) );

  $wp_customize->add_setting( 'themoments-home-banner-link-setting', array(
      'sanitize_callback' => 'esc_url_raw',
      'default' => ''
  ) );

  $wp_customize->add_control( 'themoments-home-banner-link-control', array(
    'label' => __( 'Add a Link','themoments' ),
    'section' => 'header_image',
    'settings' => 'themoments-home-banner-link-setting',
    'type' => 'text',
    'priority' => 3
  ) );


  /**********************************************/
  /************* ABOUT SECTION *************/
  /**********************************************/     

  $wp_customize->add_section( 'themoments-home-about', array(
      'capability' => 'edit_theme_options',
      'priority'       => 10,
      'title'          => __( 'About Section', 'themoments' ),
      'description'    => __( 'Select pages for About Section', 'themoments' ),
      'panel'  => 'theme_option'
  ) );

  $wp_customize->add_setting( 'themoments-home-about-display-setting', array(
      'sanitize_callback' => 'themoments_sanitize_checkbox',
      'default' => true
  ) );

  $wp_customize->add_control( 'themoments-home-about-display-control', array(
      'label' => __( 'Display?','themoments' ),
      'section' => 'themoments-home-about',
      'settings' => 'themoments-home-about-display-setting',
      'type' => 'checkbox',
      'priority'   => 1
  ) ); 

  $wp_customize->add_setting( 'themoments-home-about-page', array(
      'capability'    => 'edit_theme_options',
      'default'     => 0,
      'sanitize_callback' => 'themoments_sanitize_dropdown_pages'
  ) );

  $wp_customize->add_control( 'themoments-home-about-page', array(
      'label'                 =>  __( 'Select Page For About', 'themoments' ),
      'section'               => 'themoments-home-about',
      'type'                  => 'dropdown-pages',
      'priority'              => 30,
      'settings' => 'themoments-home-about-page'
  ) );
	
    

  /**********************************************/
  /************* MAIN SLIDER SECTION *************/
  /**********************************************/     

  $wp_customize->add_section( 'themoments-home-slider', array(
    'capability' => 'edit_theme_options',
    'priority'       => 10,
    'title'          => __( 'Slider Section', 'themoments' ),
    'description'    => __( 'Select pages for Slider Section', 'themoments' ),
    'panel'  => 'theme_option'
  ) );

  $wp_customize->add_setting( 'themoments-home-slider-display-setting', array(
      'sanitize_callback' => 'themoments_sanitize_checkbox',
      'default' => true
  ) );

  $wp_customize->add_control( 'themoments-home-slider-display-control', array(
      'label' => __( 'Display?','themoments' ),
      'section' => 'themoments-home-slider',
      'settings' => 'themoments-home-slider-display-setting',
      'type' => 'checkbox'
  ) ); 

  $wp_customize->add_setting( 'themoments-home-slider-page-1', array(
      'capability'    => 'edit_theme_options',
      'default'     => 0,
      'sanitize_callback' => 'themoments_sanitize_dropdown_pages'
  ) );

  $wp_customize->add_control( 'themoments-home-slider-page-1', array(
      'label'                 =>  __( 'Select Page For Slider 1', 'themoments' ),
      'section'               => 'themoments-home-slider',
      'type'                  => 'dropdown-pages',
      'priority'              => 30,
      'settings' => 'themoments-home-slider-page-1'
  ) );


  $wp_customize->add_setting( 'themoments-home-slider-page-2', array(
      'capability'    => 'edit_theme_options',
      'default'     => 0,
      'sanitize_callback' => 'themoments_sanitize_dropdown_pages'
  ) );

  $wp_customize->add_control( 'themoments-home-slider-page-2', array(
      'label'                 =>  __( 'Select Page For slider 2', 'themoments' ),
      'section'               => 'themoments-home-slider',
      'type'                  => 'dropdown-pages',
      'priority'              => 40,
      'settings' => 'themoments-home-slider-page-2'
  ) );


  $wp_customize->add_setting( 'themoments-home-slider-page-3', array(
      'capability'    => 'edit_theme_options',
      'default'     => 0,
      'sanitize_callback' => 'themoments_sanitize_dropdown_pages'
  ) );

  $wp_customize->add_control( 'themoments-home-slider-page-3', array(
      'label'                 =>  __( 'Select Page For slider 3', 'themoments' ),
      'section'               => 'themoments-home-slider',
      'type'                  => 'dropdown-pages',
      'priority'              => 60,
      'settings' => 'themoments-home-slider-page-3'
  ) );



  /**********************************************/
  /*************** FEATURES SECTION ****************/
  /**********************************************/

  $wp_customize->add_section( 'features_category', array(
    'priority' => 70,
    'title' => __( 'Features Categories','themoments' ),
    'description' => __( 'Select the Category for Features Section in Homepage','themoments' ),
    'panel' => 'theme_option'
  ) );

  $wp_customize->add_setting( 'feature-category-display-setting', array(
      'sanitize_callback' => 'themoments_sanitize_checkbox',
      'default' => true
  ) );

  $wp_customize->add_control( 'feature-category-display-control', array(
      'label' => __( 'Display?','themoments' ),
      'section' => 'features_category',
      'settings' => 'feature-category-display-setting',
      'type' => 'checkbox'
  ) );

  $wp_customize->add_setting( 'features_title', array(
      'sanitize_callback' => 'sanitize_text_field',
      'default' => ''          
      )
   );

  $wp_customize->add_control( 'features_title', array(
      'label' => __( 'Title','themoments' ),
      'section' => 'features_category',
      'settings' => 'features_title',
       'type' => 'text'
     )
  );

  $wp_customize->add_setting( 'features_display',array(
    'sanitize_callback' => 'themoments_sanitize_category',
    'default' => ''
  ) );

  $wp_customize->add_control( new Themoments_Customize_Dropdown_Taxonomies_Control( $wp_customize, 'features_display', array(
    'label' => __('Choose category','themoments'),
    'section' => 'features_category',
    'settings' => 'features_display',
    'type'=> 'dropdown-taxonomies'
    )  
  ) );


	/**********************************************/
  /*************** TESTIMONIAL SECTION ****************/
  /**********************************************/

  $wp_customize->add_section( 'testimonial_category',array(
    'priority' => 80,
    'title' => __( 'Testimonial Categories', 'themoments' ),
    'description' => __( 'Select the Category for Testimonial Section in Homepage', 'themoments' ),
    'panel' => 'theme_option'
  ) );

  $wp_customize->add_setting( 'testimonial-category-display-setting', array(
      'sanitize_callback' => 'themoments_sanitize_checkbox',
      'default' => true
  ) );

  $wp_customize->add_control( 'testimonial-category-display-control', array(
      'label' => __( 'Display?','themoments' ),
      'section' => 'testimonial_category',
      'settings' => 'testimonial-category-display-setting',
      'type' => 'checkbox'
  ) );

  $wp_customize->add_setting( 'testimonial_title', array(
      'sanitize_callback' => 'sanitize_text_field',
      'default' => ''      
      )
   );
  $wp_customize->add_control( 'testimonial_title', array(
      'label' => __( 'Title', 'themoments' ),
      'section' => 'testimonial_category',
      'settings' => 'testimonial_title',
       'type' => 'text'
     )
  );

  $wp_customize->add_setting( 'testimonial_display', array(
    'sanitize_callback' => 'themoments_sanitize_category',
    'default' => ''
  ) );

  $wp_customize->add_control( new Themoments_Customize_Dropdown_Taxonomies_Control( $wp_customize, 'testimonial_display', array(
    'label' => __( 'Choose category', 'themoments' ),
    'section' => 'testimonial_category',
    'settings' => 'testimonial_display',
    'type'=> 'dropdown-taxonomies',
    )  
  ) );
      
      
      

  /**********************************************/
  /*************** CREW MEMBERS SECTION ****************/
  /**********************************************/      

  $wp_customize->add_section( 'crew_category', array(
    'priority' => 80,
    'title' => __( 'Crew Members Categories', 'themoments' ),
    'description' => __( 'Select the Category for Crew Member Section in Homepage', 'themoments' ),
    'panel' => 'theme_option'
  ) );

  $wp_customize->add_setting( 'crew-category-display-setting', array(
      'sanitize_callback' => 'themoments_sanitize_checkbox',
      'default' => true
  ) );

  $wp_customize->add_control( 'crew-category-display-control', array(
      'label' => __( 'Display?','themoments' ),
      'section' => 'crew_category',
      'settings' => 'crew-category-display-setting',
      'type' => 'checkbox'
  ) );

  $wp_customize->add_setting( 'crew_title', array(
    'sanitize_callback' => 'sanitize_text_field',
    'default' => ''      
  ) );

  $wp_customize->add_control( 'crew_title', array(
    'label' => __( 'Title', 'themoments' ),
    'section' => 'crew_category',
    'settings' => 'crew_title',
    'type' => 'text'
  ) );

 $wp_customize->add_setting( 'crew_display', array(
    'sanitize_callback' => 'themoments_sanitize_category',
    'default' => ''
  ) );

  $wp_customize->add_control( new Themoments_Customize_Dropdown_Taxonomies_Control( $wp_customize, 'crew_display', array(
    'label' => __( 'Choose category', 'themoments' ),
    'section' => 'crew_category',
    'settings' => 'crew_display',
    'type'=> 'dropdown-taxonomies',
    )  
  ) );      
      
      
      
      
  /*******************************************/
  /*************** CLIENTS SECTION ****************/
  /**********************************************/      

  $wp_customize->add_section( 'client_category', array(
    'priority' => 80,
    'title' => __( 'Client Categories', 'themoments' ),
    'description' => __( 'Select the Category for Client Section in Homepage', 'themoments' ),
    'panel' => 'theme_option'
  ) );

  $wp_customize->add_setting( 'client-category-display-setting', array(
      'sanitize_callback' => 'themoments_sanitize_checkbox',
      'default' => true
  ) );

  $wp_customize->add_control( 'client-category-display-control', array(
      'label' => __( 'Display?','themoments' ),
      'section' => 'client_category',
      'settings' => 'client-category-display-setting',
      'type' => 'checkbox'
  ) );

  $wp_customize->add_setting( 'client_section_title', array(
      'sanitize_callback' => 'sanitize_text_field',
      'default' => ''      
      )
   );

  $wp_customize->add_control( 'client_section_title', array(
      'label' => __( 'Title', 'themoments' ),
      'section' => 'client_category',
      'settings' => 'client_section_title',
       'type' => 'text'
     )
  );

  $wp_customize->add_setting( 'client_display', array(
    'sanitize_callback' => 'themoments_sanitize_category',
    'default' => ''
  ));

  $wp_customize->add_control( new Themoments_Customize_Dropdown_Taxonomies_Control( $wp_customize, 'client_display',array(
    'label' => __( 'Choose category', 'themoments' ),
    'section' => 'client_category',
    'settings' => 'client_display',
    'type'=> 'dropdown-taxonomies'
    )  
  ) );
	
      

  /**********************************************/
  /*************** SOCIAL SECTION ***************/
  /**********************************************/

  $social = array( __('facebook', 'magazine-newspaper'), __('twitter', 'magazine-newspaper'), __('googleplus','magazine-newspaper'), __('youtube', 'magazine-newspaper'), __('linkedin', 'magazine-newspaper'), __('pinterest', 'magazine-newspaper'), __('instagram', 'magazine-newspaper') ); 

  $wp_customize->add_section( 'social_section', array(
    'priority' => 80,
    'title' => __( 'Social Info', 'magazine-newspaper' ),
    'description' => 'Customize your Social Info',
    'panel' => 'theme_option'
  ) );

  foreach ( $social as $key => $value ) {
    $wp_customize->add_setting( $value . '_textbox', array(
      'sanitize_callback' => 'esc_url_raw',
      'default' => ''
    ) );

    $wp_customize->add_control( $value . '_textbox', array(
      'label' => ucwords( $value ),
      'section' => 'social_section',
      'settings' => $value . '_textbox',
      'type' => 'text',
      'default' =>''
    ) );
  }

   /**********************************************/
    /*************** SORT SECTION ****************/
    /**********************************************/

    $wp_customize->add_section( 'themoments_home_sections_order', array(
        'capability' => 'edit_theme_options',
        'priority'       => 80,
        'title'          => __( 'Section Ordering', 'themoments' ),
        'panel' => 'theme_option'
    ) );

    $wp_customize->add_setting( 'section_order_upgrade_note', array(
      'default' => '',
      'sanitize_callback' => 'wp_kses_post',
    ) );

    $section_order_upgrade_note = 'This feature is available in PRO Version';

    $wp_customize->add_control( new Themoments_Theme_Info( $wp_customize ,'section_order_upgrade_note',array(
      'section' => 'themoments_home_sections_order',
      'description' => $section_order_upgrade_note
    ) ) );

     
}

add_action( 'customize_register', 'themoments_customizer_register' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function themoments_customize_preview_js() {
  wp_enqueue_script( 'themoments-customizer-preview', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'themoments_customize_preview_js' );


function themoments_sanitize_category( $input ){
  $output=intval( $input );
  return $output;

}

function themoments_sanitize_dropdown_pages( $page_id, $setting ) {
  // Ensure $input is an absolute integer.
  $page_id = absint( $page_id );
  
  // If $page_id is an ID of a published page, return it; otherwise, return the default.
  return ( 'publish' == get_post_status( $page_id ) ? $page_id : $setting->default );
}

//checkbox sanitization function
function themoments_sanitize_checkbox( $input ){       
    //returns true if checkbox is checked
    return ( ( isset( $input ) && true == $input ) ? true : false );
}