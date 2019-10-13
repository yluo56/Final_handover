<?php
/**
* college-education Customization options
*/

if ( ! function_exists( 'college_education_field_sanitize_checkbox' ) ) :
  function college_education_field_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true === $checked ) ? true : false );
}
endif;

function college_education_customize_register( $wp_customize ) {
  $wp_customize->add_panel(
    'general',
    array(
        'title' => __( 'General', 'college-education' ),
        'description' => __('styling options','college-education'),
        'priority' => 20, 
    )
  );
   //All our sections, settings, and controls will be added here
  $wp_customize->add_section(
    'CollegeEducationSocialLinks',
    array(
      'title' => __('Social Accounts', 'college-education'),
      'priority' => 120,
      'description' => __( 'In first input box, you need to add FONT AWESOME shortcode which you can find ' ,  'college-education').'<a target="_blank" href="'.esc_url('https://fortawesome.github.io/Font-Awesome/icons/').'">'.__('here' ,  'college-education').'</a>'.__(' and in second input box, you need to add your social media profile URL.', 'college-education').'<br />'.__(' Enter the URL of your social accounts. Leave it empty to hide the icon.' ,  'college-education'),
      'panel' => 'footer'
    )
  );
  $wp_customize->get_section('title_tagline')->panel = 'general';
  $wp_customize->get_section('header_image')->panel = 'general';
  $wp_customize->get_section('title_tagline')->title = __('Header & Logo','college-education');
  $wp_customize->get_section('static_front_page')->panel = 'general';


$CollegeEducationSocialIcon = array();
  for($i=1;$i <= 5;$i++):  
    $CollegeEducationSocialIcon[] =  array( 'slug'=>sprintf('CollegeEducationSocialIcon%d',$i),   
      'default' => '',   
      'label' => sprintf(esc_html__( 'Social Account %s', 'college-education' ),$i),   
      'priority' => sprintf('%d',$i) );  
  endfor;
  foreach($CollegeEducationSocialIcon as $CollegeEducationSocialIcons){
    $wp_customize->add_setting(
      $CollegeEducationSocialIcons['slug'],
      array(
        'default' => '',
        'capability'     => 'edit_theme_options',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
      )
    );
    $wp_customize->add_control(
      $CollegeEducationSocialIcons['slug'],
      array(
        'type'  => 'text',
        'section' => 'CollegeEducationSocialLinks',
        'input_attrs' => array( 'placeholder' => esc_attr__('Enter Icon','college-education') ),
        'label'      =>   $CollegeEducationSocialIcons['label'],
        'priority' => $CollegeEducationSocialIcons['priority']
      )
    );
  }
  $CollegeEducationSocialIconLink = array();
  for($i=1;$i <= 5;$i++):  
    $CollegeEducationSocialIconLink[] =  array( 'slug'=>sprintf('CollegeEducationSocialIconLink%d',$i),   
      'default' => '',   
      'label' => sprintf(esc_html__( 'Social Link %s', 'college-education' ),$i),   
      'priority' => sprintf('%d',$i) );  
  endfor;
  foreach($CollegeEducationSocialIconLink as $CollegeEducationSocialIconLinks){
    $wp_customize->add_setting(
      $CollegeEducationSocialIconLinks['slug'],
      array(
        'default' => '',
        'capability'     => 'edit_theme_options',
        'type' => 'theme_mod',
        'sanitize_callback' => 'esc_url_raw',
      )
    );
    $wp_customize->add_control(
      $CollegeEducationSocialIconLinks['slug'],
      array(
        'type'  => 'url',
        'section' => 'CollegeEducationSocialLinks',
        'priority' => $CollegeEducationSocialIconLinks['priority'],
        'input_attrs' => array( 'placeholder' => esc_html__('Enter URL','college-education')),
      )
    );
  }
$wp_customize->add_section(
  'headerNlogo',
  array(
    'title' => __('Header & Logo','college-education'),
    'panel' => 'general'
  )
);

/*
*Multiple logo upload code
*/
$wp_customize->add_setting(
    'CollegeEducationDarkLogo',
    array(
        'default' => '',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    )
);
$wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'CollegeEducationDarkLogo', array(
    'section'     => 'title_tagline',
    'label'       => __( 'Upload Dark Logo' ,'college-education'),
    'flex_width'  => true,
    'flex_height' => true,
    'width'       => 120,
    'height'      => 50,
    'priority'    => 48,
    'default-image' => '',
) ) );

$wp_customize->add_setting('theme_header_fix', array(
        'default' => false,  
        'sanitize_callback' => 'college_education_field_sanitize_checkbox',
));
$wp_customize->add_control('theme_header_fix', array(
    'label'   => esc_html__('Header Fix','college-education'),
    'section' => 'title_tagline',
    'type'    => 'checkbox',
    'priority' => 49
));

$wp_customize->add_setting(
  'theme_logo_height',
  array(
    'default' => '',
    'capability'     => 'edit_theme_options',
    'sanitize_callback' => 'absint',
    )
  );
$wp_customize->add_control(
  'theme_logo_height',
  array(
    'section' => 'title_tagline',
    'label'      => __('Enter Logo Size', 'college-education'),
    'description' => __("Use if you want to increase or decrease logo size (optional) Don't enter `px` in the string. e.g. 20 (default: 10px)",'college-education'),
    'type'       => 'text',
    'priority'    => 50,
    )
  );

$wp_customize->add_setting(
    'preloader',
    array(
        'default' => '1',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'priority' => 20, 
    )
);
$wp_customize->add_section( 'preloaderSection' , array(
    'title'       => __( 'Preloader', 'college-education' ),
    'priority'    => 32,
    'capability'     => 'edit_theme_options',
    'panel' => 'general'
) );
$wp_customize->add_control(
    'preloader',
    array(
        'section' => 'preloaderSection',                
        'label'   => __('Preloader','college-education'),
        'type'    => 'radio',
        'choices'        => array(
            "1"   => esc_html__( "On ", 'college-education' ),
            "2"   => esc_html__( "Off", 'college-education' ),
        ),
    )
);

$wp_customize->add_setting( 'customPreloader', array(
    'sanitize_callback' => 'esc_url_raw',
    'capability'     => 'edit_theme_options',
    'priority' => 40,
));

$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'customPreloader', array(
    'label'    => __( 'Upload Custom Preloader', 'college-education' ),
    'section'  => 'preloaderSection',
    'settings' => 'customPreloader',
) ) );

$wp_customize->add_section( 'homepageSection' , array(
    'title'       => __( 'Menu Settings', 'college-education' ),
    'priority'    => 40,
    'capability'     => 'edit_theme_options',
    'panel' => 'general'
) );

$wp_customize->add_setting( 'menustyle', array(
    'capability'     => 'edit_theme_options',
    'priority' => 40,
     'default' => '0',
    'sanitize_callback' => 'sanitize_text_field',
));

$wp_customize->add_control( 'menustyle', array(
    'label'    => __( 'Menu Style', 'college-education' ),
    'section'  => 'homepageSection',
    'type'       => 'select',
    'choices' => array(
      "0"   => esc_html__( "Transparent", 'college-education' ),
      "1"   => esc_html__( "Non-Transparent", 'college-education' ),
    ),
));

$wp_customize->add_setting( 'pagetitle', array(
    'capability'     => 'edit_theme_options',
    'priority' => 40,
    'default' => '1',
    'sanitize_callback' => 'sanitize_text_field',
));

$wp_customize->add_control( 'pagetitle', array(
    'label'    => __( 'Front Page Title', 'college-education' ),
    'section'  => 'homepageSection',
    'type'       => 'select',    
    'choices' => array(
      "0"   => esc_html__( "Hide", 'college-education' ),
      "1"   => esc_html__( "Show", 'college-education' ),
    ),
));

$wp_customize->add_section( 'animationSection' , array(
    'title'       => __( 'Animation', 'college-education' ),
    'priority'    => 50,
    'description' => __( 'For more information Click' ,  'college-education').'<a target="_blank" href="'.esc_url('https://daneden.github.io/animate.css/').'">'.__('here' ,  'college-education').'</a> '.__('to find other animation effects.' , 'college-education'),
    'capability'     => 'edit_theme_options',
    'panel' => 'general'
) );

$wp_customize->add_setting( 'animation', array(
    'capability'     => 'edit_theme_options',
    'priority' => 40,
     'default' => '0',
    'sanitize_callback' => 'sanitize_text_field',
));

$wp_customize->add_control( 'animation', array(
    'label'    => __( 'Animation', 'college-education' ),
    'section'  => 'animationSection',
    'type'    => 'radio',
    'choices'        => array(
        "0"   => esc_html__( 'No Animation', 'college-education' ),
        "1"   => esc_html__( 'Animation', 'college-education' ),
    ),
));
/* colors  section */
//Colors section
$wp_customize->add_setting(
    'themeColor',
    array(
        'default' => '#c1331b',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control(
  new WP_Customize_Color_Control(
    $wp_customize,
    'themeColor',
    array(
        'label'      => __('Theme Color ', 'college-education'),
        'section' => 'colors',
        'priority' => 10
    )
  )
);
$wp_customize->add_setting(
  'secondaryColor',
  array(
      'default' => '#071414',
      'capability'     => 'edit_theme_options',
      'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control(
  new WP_Customize_Color_Control(
    $wp_customize,
    'secondaryColor',
    array(
        'label'      => __('Secondary Color', 'college-education'),
        'section' => 'colors',
        'priority' => 11
    )
  )
);
//Menu Background Color
$wp_customize->add_setting(
  'menuBackgroundColor',
  array(
      'default' => '',
      'capability'     => 'edit_theme_options',
      'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control(
  new WP_Customize_Color_Control(
    $wp_customize,
    'menuBackgroundColor',
    array(
        'label'      => __('Menu Background Color', 'college-education'),
        'section' => 'colors',
        'priority' => 11
    )
  )
);
//Menu Background Color (Scroll)
$wp_customize->add_setting(
  'menuBackgroundColorScroll',
  array(
      'default' => '#fff',
      'capability'     => 'edit_theme_options',
      'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control(
  new WP_Customize_Color_Control(
    $wp_customize,
    'menuBackgroundColorScroll',
    array(
        'label'      => __('Menu Background Color (after scroll)', 'college-education'),
        'section' => 'colors',
        'priority' => 11
    )
  )
);
//Menu Text Color
$wp_customize->add_setting(
  'menuTextColor',
  array(
      'default' => '#ffffff',
      'capability'     => 'edit_theme_options',
      'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control(
  new WP_Customize_Color_Control(
    $wp_customize,
    'menuTextColor',
    array(
        'label'      => __('Menu Text Color', 'college-education'),
        'section' => 'colors',
        'priority' => 11
    )
  )
);
//Menu Text Color
$wp_customize->add_setting(
  'menuTextColorScroll',
  array(
      'default' => '#000',
      'capability'     => 'edit_theme_options',
      'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control(
  new WP_Customize_Color_Control(
    $wp_customize,
    'menuTextColorScroll',
    array(
        'label'      => __('Menu Text Color(after scroll)', 'college-education'),
        'section' => 'colors',
        'priority' => 11
    )
  )
);
//Body Background Color
$wp_customize->add_setting(
  'bodyBackgroundColor',
  array(
      'default' => '#ffffff',
      'capability'     => 'edit_theme_options',
      'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control(
  new WP_Customize_Color_Control(
    $wp_customize,
    'bodyBackgroundColor',
    array(
        'label'      => __('Body Background Color', 'college-education'),
        'section' => 'colors',
        'priority' => 11
    )
  )
);
//Body Text Color
$wp_customize->add_setting(
  'bodyTextColor',
  array(
      'default' => '#071414',
      'capability'     => 'edit_theme_options',
      'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control(
  new WP_Customize_Color_Control(
    $wp_customize,
    'bodyTextColor',
    array(
        'label'      => __('Body Text Color', 'college-education'),
        'section' => 'colors',
        'priority' => 11
    )
  )
);
//Footer Background Color
$wp_customize->add_setting(
  'footerBackgroundColor',
  array(
      'default' => '#f9f5f1',
      'capability'     => 'edit_theme_options',
      'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control(
  new WP_Customize_Color_Control(
    $wp_customize,
    'footerBackgroundColor',
    array(
        'label'      => __('Footer Background Color', 'college-education'),
        'section' => 'colors',
        'priority' => 11
    )
  )
);
//Footer Text Color
$wp_customize->add_setting(
  'footerTextColor',
  array(
      'default' => '#ffffff',
      'capability'     => 'edit_theme_options',
      'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control(
  new WP_Customize_Color_Control(
    $wp_customize,
    'footerTextColor',
    array(
        'label'      => __('Footer Text Color', 'college-education'),
        'section' => 'colors',
        'priority' => 11
    )
  )
);
//Footer Link Color
$wp_customize->add_setting(
  'footerLinkColor',
  array(
      'default' => '#ffffff',
      'capability'     => 'edit_theme_options',
      'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control(
  new WP_Customize_Color_Control(
    $wp_customize,
    'footerLinkColor',
    array(
        'label'      => __('Footer Link Color', 'college-education'),
        'section' => 'colors',
        'priority' => 11
    )
  )
);
//Footer Link Hover Color
$wp_customize->add_setting(
  'footerLinkHoverColor',
  array(
      'default' => '#c1331b',
      'capability'     => 'edit_theme_options',
      'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control(
  new WP_Customize_Color_Control(
    $wp_customize,
    'footerLinkHoverColor',
    array(
        'label'      => __('Footer Link Hover Color', 'college-education'),
        'section' => 'colors',
        'priority' => 11
    )
  )
);
/* Color Section Ends */
/*-------------------- BLog Page Option --------------------------*/
$wp_customize->add_section(
    'blogThemeOption',
    array(
        'title' => __( 'Blog Page Options', 'college-education' ),
        'description' => __('Blog page option settings. ','college-education'),
        'priority' => 124,
       
    )
);
$wp_customize->add_setting(
    'blogsidebar',
    array(
        'default' => '3',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control(
    'blogsidebar',
    array(
        'section' => 'blogThemeOption',
        'label'      => __('Select Blog Sidebar Option', 'college-education'),
        'type'       => 'select',
        'choices' => array(
          "1"   => esc_html__( "Left Sidebar", 'college-education' ),
          "2"   => esc_html__( "Right Sidebar", 'college-education' ),
          "3"   => esc_html__( "Full Sidebar", 'college-education' ),
        ),
    )
);
$wp_customize->add_setting(
    'blogsinglesidebar',
    array(
        'default' => '3',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control(
    'blogsinglesidebar',
    array(
        'section' => 'blogThemeOption',
        'label'      => __('Select Single Post Sidebar Option', 'college-education'),
        'type'       => 'select',
        'choices' => array(
          "1"   => esc_html__( "Left Sidebar", 'college-education' ),
          "2"   => esc_html__( "Right Sidebar", 'college-education' ),
          "3"   => esc_html__( "Full Sidebar", 'college-education' ),
        ),
    )
);
$wp_customize->add_setting(
    'blogMetaTag',
    array(
        'default' => '1',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control(
    'blogMetaTag',
    array(
        'section' => 'blogThemeOption',
        'label'      => __('Select Blog Post Meta Tag Option', 'college-education'),
        'type'       => 'select',
        'choices' => array(
          "1"   => esc_html__( "Show", 'college-education' ),
          "2"   => esc_html__( "Hide", 'college-education' ),      
        ),
    )
);
$wp_customize->add_setting(
    'blogSingleMetaTag',
    array(
        'default' => '1',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control(
    'blogSingleMetaTag',
    array(
        'section' => 'blogThemeOption',
        'label'      => __('Select Single Post Meta Tag Option', 'college-education'),
        'type'       => 'select',
        'choices' => array(
          "1"   => esc_html__( "Show", 'college-education' ),
          "2"   => esc_html__( "Hide", 'college-education' ),      
        ),
    )
);

$wp_customize->add_setting(
    'blogPostExcerpt',
    array(
        'default' => '1',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control(
    'blogPostExcerpt',
    array(
        'section' => 'blogThemeOption',
        'label'      => __('Select Blog Post Excerpt Option', 'college-education'),
        'type'       => 'select',
        'choices' => array(
          "1"   => esc_html__( "Show", 'college-education' ),
          "2"   => esc_html__( "Hide", 'college-education' ),      
        ),
    )
);
$wp_customize->add_setting(
    'blogPostExcerptTextLimit',
    array(
        'default' => '150',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control(
    'blogPostExcerptTextLimit',
    array(
        'section' => 'blogThemeOption',
        'label'      => __('Blog Post Excerpt String Limit Option', 'college-education'),
        'type'       => 'text',        
    )
);
$wp_customize->add_setting(
    'blogPostReadMore',
    array(
        'default' => '1',
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control(
    'blogPostReadMore',
    array(
        'section' => 'blogThemeOption',
        'label'      => __('Select Blog Post Read More Button Option', 'college-education'),
        'type'       => 'select',
        'choices' => array(
          "1"   => esc_html__( "Show", 'college-education' ),
          "2"   => esc_html__( "Hide", 'college-education' ),      
        ),
    )
);

/*------------------------ Blog  Page option End ----------------------------*/

//Footer Section
$wp_customize->add_panel(
    'footer',
    array(
        'title' => __( 'Footer', 'college-education' ),
        'description' => __('Footer options','college-education'),
        'priority' => 105, 
    )
);
$wp_customize->add_section( 'footerWidgetArea' , array(
    'title'       => __( 'Footer Widget Area', 'college-education' ),
    'priority'    => 135,
    'capability'     => 'edit_theme_options',
    'panel' => 'footer'
) );

$wp_customize->add_section( 'footerSocialSection' , array(
    'title'       => __( 'Social Settings', 'college-education' ),
    'description' => __( 'In first input box, you need to add FONT AWESOME shortcode which you can find ' , 'college-education').'<a target="_blank" href="'.esc_url('https://fortawesome.github.io/Font-Awesome/icons/').'">'.__('here' , 'college-education').'</a>'.__('and in second input box, you need to add your social media profile URL.' , 'college-education'),
    'priority'    => 135,
    'capability'     => 'edit_theme_options',
    'panel' => 'footer'
) );
$wp_customize->add_section( 'footerCopyright' , array(
    'title'       => __( 'Footer Copyright Area', 'college-education' ),
    'priority'    => 135,
    'capability'     => 'edit_theme_options',
    'panel' => 'footer'
) );
$wp_customize->add_setting(
  'hideFooterWidgetBar',
  array(
      'default' => '1',
      'capability'     => 'edit_theme_options',
      'sanitize_callback' => 'sanitize_text_field',
      'priority' => 20, 
  )
);
$wp_customize->add_control(
  'hideFooterWidgetBar',
  array(
    'section' => 'footerWidgetArea',                
    'label'   => __('Hide Widget Area','college-education'),
    'type'    => 'select',
    'choices' => array(
        "1"   => esc_html__( "Show", 'college-education' ),
        "2"   => esc_html__( "Hide", 'college-education' ),
    ),
  )
);
$wp_customize->add_setting(
  'footerWidgetStyle',
  array(
      'default' => '3',
      'capability'     => 'edit_theme_options',
      'sanitize_callback' => 'sanitize_text_field',
      'priority' => 20, 
  )
);
$wp_customize->add_control(
  'footerWidgetStyle',
  array(
      'section' => 'footerWidgetArea',                
      'label'   => __('Select Widget Area','college-education'),
      'type'    => 'select',
      'choices'        => array(
          "1"   => esc_html__( "2 column", 'college-education' ),
          "2"   => esc_html__( "3 column", 'college-education' ),
          "3"   => esc_html__( "4 column", 'college-education' )
      ),
  )
);
$wp_customize->add_setting(
    'CopyrightAreaText',
    array(
        'capability'     => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',
        'priority' => 20, 
    )
);
$wp_customize->add_control(
    'CopyrightAreaText',
    array(
        'section' => 'footerCopyright',                
        'label'   => __('Enter Copyright Text','college-education'),
        'type'    => 'textarea',
    )
);
// Text Panel Starts Here 


}
add_action( 'customize_register', 'college_education_customize_register' );

function college_education_custom_css(){  

  wp_enqueue_style( 'college_education_style', get_stylesheet_uri() );
  $custom_css='';

  $custom_css.="body{
      background: ".esc_attr(get_theme_mod('bodyBackgroundColor', '#ffffff')).";
    }
    .navbar {
      background: ".esc_attr(get_theme_mod('menuBackgroundColor', 'transparent')).";
    }
    .college-education-fixed-top,.college-education-fixed-top #cssmenu ul.sub-menu{
      background-color: ".esc_attr(get_theme_mod('menuBackgroundColor','transparent')).";
    }
    #top-menu ul ul li a{
      background-color: ".esc_attr(get_theme_mod('themeColor','#c1331b')).";
    }
    .fixed-header #top-menu ul ul li a{
      background-color: ".esc_attr(get_theme_mod('menuBackgroundColorScroll','#fff')).";
    }
    .fixed-header,.fixed-header #cssmenu ul.sub-menu,.fixed-header #top-menu ul{
      background-color: ".esc_attr(get_theme_mod('menuBackgroundColorScroll','#fff')).";
    }
    .header-top.no-transparent{
      position:relative; 
      background-color:".esc_attr(get_theme_mod('menuBackgroundColor','transparent')).";
    }";

    /*Main logo height*/
    $theme_logo_height = (get_theme_mod('theme_logo_height'))?(get_theme_mod('theme_logo_height')):45;
    $custom_css.= "#top-menu .logo img ,.header-top .logo img , #college_education_navigation .main-logo img{ max-height: ".esc_attr($theme_logo_height)."px;   }";

    if(get_theme_mod('theme_header_fix',0)){
      $custom_css.= ".header-top.fixed-header { position :fixed; } ";

    }
      if(has_header_image()){
         
          $url = get_header_image();
          $custom_css .= ".blog-heading-wrap {background-image:url(".esc_url_raw($url).");}";
      }
    

    $custom_css.= "#top-menu, #top-menu ul, #top-menu ul li,#top-menu ul li a, #top-menu #menu-button,
    #cssmenu, #cssmenu ul, #cssmenu ul li,#cssmenu ul li a,
    #cssmenu #menu-button,.logo-light, .logo-light a {
      color: ".esc_attr(get_theme_mod('menuTextColor','#fff')).";
    }    
    .fixed-header  #top-menu > ul > li > a,.fixed-header #top-menu  ul ul li  a,
    .fixed-header #cssmenu > ul > li > a,.fixed-header #cssmenu ul ul li a,
    .logo-dark a, .logo-dark{
      color: ".esc_attr(get_theme_mod('menuTextColorScroll','#000'))."; 
    }
    #top-menu .menu-global{border-top: 2px solid ".esc_attr(get_theme_mod('menuTextColor','#fff'))."; }
    #top-menu > ul > li:hover > a, #top-menu ul li.active a{border-top: 2px solid ".esc_attr(get_theme_mod('menuTextColor','#fff')).";}
    .fixed-header #top-menu > ul > li:hover > a, .fixed-header #top-menu ul li.active a
    {
      color: ".esc_attr(get_theme_mod('menuTextColorScroll','#000')).";
    }
    .fixed-header  #top-menu .menu-global{border-top: 2px solid ".esc_attr(get_theme_mod('menuTextColorScroll','#000'))."; }

    *::selection,.silver-package-bg,#menu-line,.menu-left li:hover:before{
      background-color: ".esc_attr(get_theme_mod('themeColor','#c1331b')).";
    }
    .title-data h2 a,.btn-light:focus,.btn-light:hover,a:hover, a:focus,.package-feature h6,.menu-left h6,.sow-slide-nav a:hover .sow-sld-icon-themeDefault-left,.sow-slide-nav a:hover .sow-sld-icon-themeDefault-right, .menu-left ul li a:hover, .menu-left ul li.active a, .recentcomments:hover,.blog-carousel .blog-carousel-title h4,
    .gallery-item .ovelay .content .lightbox:hover, .gallery-item:hover .ovelay .content .imag-alt a:hover{
      color: ".esc_attr(get_theme_mod('themeColor','#c1331b')).";
    }
       
    .btn-blank{
      box-shadow: inset 0 0 0 1px ".esc_attr(get_theme_mod('themeColor','#c1331b')).";
    }
    .btn-blank:hover:before, .btn-blank:focus:before, .btn-blank:active:before{
      box-shadow: inset 10px 0 0 0px ".esc_attr(get_theme_mod('themeColor','#c1331b')).";
    }
    .contact-me.darkForm input[type=submit],.contact-me.lightForm input[type=submit]:hover {
      background: ".esc_attr(get_theme_mod('secondaryColor','#071414')).";
      box-shadow: inset 10px 0 0 0px ".esc_attr(get_theme_mod('themeColor','#c1331b')).";
    }
    .btn-nav:focus, .btn-nav:hover,.menu-left li a:hover:before, .menu-left li.active:before, .services-tabs-left li:hover:before, .services-tabs-left li.active:before, ul#recentcomments li:hover:before,.btn-speechblue:before,.search-submit:hover, .search-submit:focus {
      background: ".esc_attr(get_theme_mod('themeColor','#c1331b')).";
    }
    .contact-me.lightForm input[type=submit],.contact-me.darkForm input[type=submit]:hover {
      background: ".esc_attr(get_theme_mod('themeColor','#c1331b')).";
      box-shadow: inset 10px 0 0 0px ".esc_attr(get_theme_mod('secondaryColor','#071414')).";
    }
    .menu-left ul li,.menu-left ul li span, body,.comment-form input, .comment-form textarea,input::-webkit-input-placeholder,textarea::-webkit-input-placeholder,time,.menu-left ul li a, .services-tabs-left li a, .menu-left ul li .comment-author-link a, .menu-left ul li.recentcomments a,caption{
      color: ".esc_attr(get_theme_mod('bodyTextColor','#071414')).";
    }
    input:-moz-placeholder{
      color: ".esc_attr(get_theme_mod('bodyTextColor','#071414')).";
    }
    input::-moz-placeholder{
      color: ".esc_attr(get_theme_mod('bodyTextColor','#071414')).";
    }
    input:-ms-input-placeholder{
      color: ".esc_attr(get_theme_mod('bodyTextColor','#071414')).";
    }
    a,.comment .comment-reply-link,.services-tabs-left li a:hover, .services-tabs-left li.active a{
      color: ".esc_attr(get_theme_mod('secondaryColor','#071414')).";
    }
    .menu-left li:before,.menu-left h6::after,.btn-blank:hover:before, .btn-blank:focus:before, .btn-blank:active:before,.package-feature h6::after,.counter-box p:before,.menu-left li:before, .services-tabs-left li:before,.btn-blank:before,.search-submit{
      background: ".esc_attr(get_theme_mod('secondaryColor','#071414')).";
    }
    .comment-form p.form-submit,.btn-speechblue{
      background: ".esc_attr(get_theme_mod('secondaryColor','#071414')).";
      box-shadow: inset 10px 0 0 0px ".esc_attr(get_theme_mod('themeColor','#c1331b')).";
    }
    .comment-form .form-submit:hover::before,.btn-speechblue:hover:before, .btn-speechblue:focus:before, .btn-speechblue:active:before{
      box-shadow: inset 10px 0 0 0px ".esc_attr(get_theme_mod('secondaryColor','#071414')).";
      background: ".esc_attr(get_theme_mod('themeColor','#c1331b')).";
    }
    .contact-me.darkForm input:focus, .contact-me.lightForm input:focus, .contact-me.darkForm textarea:focus, .contact-me.lightForm textarea:focus,
    blockquote,
    .comment-form input:focus, .comment-form textarea:focus{
      border-color: ".esc_attr(get_theme_mod('themeColor','#c1331b')).";
    }   
    .footer-box{
      background:".esc_attr(get_theme_mod('footerBackgroundColor','#f9f5f1')).";
    }
    .footer-box div,.footer-box .widget-title,.footer-box p,.footer-box .textwidget p,.footer-box div,.footer-box h1,.footer-box h2,.footer-box h3,.footer-box h4,.footer-box h5,.footer-box h6 {
      color: ".esc_attr(get_theme_mod('footerTextColor','#ffffff')).";
    }
    .footer-box .footer-widget ul li a,.footer-widget .tagcloud a{
      color:".esc_attr(get_theme_mod('footerLinkColor','#ffffff')).";
    }
    .footer-box .footer-widget ul li a:hover, .footer-widget .tagcloud a:hover{
      color:".esc_attr(get_theme_mod('footerLinkHoverColor','#c1331b')).";
    }
    .footer-box .tagcloud > a:hover{
      background:".esc_attr(get_theme_mod('footerLinkHoverColor','#c1331b')).";
    }
    .footer-wrap .copyright p{
      color: ".esc_attr(get_theme_mod('footerTextColor', '#ffffff')).";
    }
    .footer-wrap a,.footer-wrap.style2 .footer-nav ul li a{
      color: ".esc_attr(get_theme_mod('footerLinkColor', '#ffffff')).";
    }
    .footer-wrap .copyright a:hover,.footer-wrap a:hover,.footer-wrap.style2 .footer-nav ul li a:hover,.footer-wrap.style2 .copyright a:hover,.footer-wrap.style1 .copyright a:hover{
      color: ".esc_attr(get_theme_mod('footerLinkHoverColor', '#c1331b')).";
    }
    
    /* Menu Css Cutomization */
    
      /*main top menu text color*/

      #menu-style > ul > li > a {
        color: ".esc_attr(get_theme_mod('menuTextColor','#ffffff')).";
      }

      /*sub menu text color*/

      #menu-style ul ul li a{
        color: ".esc_attr(get_theme_mod('menuTextColor','#ffffff')).";
      }

      /*main top menu text Scroll color*/

      .fixed-header #menu-style > ul > li > a{
        color: ".esc_attr(get_theme_mod('menuTextColorScroll','#000')).";
      }

      /*sub menu text Scroll color*/

      .fixed-header #menu-style ul ul li a{
        color: ".esc_attr(get_theme_mod('menuTextColorScroll','#000')).";
      }

      /*sub menu background color*/

      #menu-style ul ul li a{
        background-color: ".esc_attr(get_theme_mod('secondaryColor','#071414')).";
      }

      /*sub menu Scroll background color*/

      .fixed-header #menu-style ul ul li a {
        background-color: ".esc_attr(get_theme_mod('menuBackgroundColorScroll','#ffffff')).";
      } 

      /*sub menu background hover color*/

      #menu-style ul ul li a:hover {
        background-color: ".esc_attr(get_theme_mod('themeColor','#c1331b')).";
      }      

      /*all top menu hover effect border color*/

      #menu-style > ul > li::before, #menu-style > ul > li::after{
           border-color: ".esc_attr(get_theme_mod('themeColor','#c1331b')).";
        }

      
      /*all menu arrow border color*/

      #menu-style > ul > li.has-sub > a::after, #menu-style ul ul li.has-sub > a::after {
           border-color: ".esc_attr(get_theme_mod('menuTextColor','#ffffff')).";
        }

        /*all menu scroll arrow border color*/

      .fixed-header #menu-style > ul > li.has-sub > a::after, .fixed-header #menu-style ul ul li.has-sub > a::after{
           border-color: ".esc_attr(get_theme_mod('menuTextColorScroll','#000')).";
        }

      @media only screen and (max-width: 1024px){
        
      /*all menu arrow border color*/
      #menu-style #menu-button::before,#menu-style .menu-opened::after
        {
           border-color: ".esc_attr(get_theme_mod('menuTextColor','#ffffff')).";
        }

      /*all menu scroll arrow border color*/
      .fixed-header #menu-style #menu-button::before, .fixed-header #menu-style .menu-opened::after{
           border-color: ".esc_attr(get_theme_mod('menuTextColorScroll','#000'))." ;
        }

      /*all menu arrow background border color*/      
      #menu-style #menu-button::after{
          background-color: ".esc_attr(get_theme_mod('menuTextColor','#ffffff')).";
        }

      /*all menu scroll arrow background border color*/      
      .fixed-header #menu-style #menu-button::after {
          background-color: ".esc_attr(get_theme_mod('menuTextColorScroll','#000')).";
        } 

      /*mobile menu background color*/
      #menu-style .mobilemenu li a
        {
           background-color: ".esc_attr(get_theme_mod('menuBackgroundColorScroll','#ffffff')).";
           color: ".esc_attr(get_theme_mod('menuTextColorScroll','#000')).";
        }

        #menu-style .mobilemenu li a:hover{
           background-color: ".esc_attr(get_theme_mod('themeColor','#c1331b')).";
        }

        #menu-style .mobilemenu li:hover > a{
           background-color: ".esc_attr(get_theme_mod('themeColor','#c1331b')).";
        }       

        #menu-style .submenu-button::before, #menu-style .submenu-button::after {
           background-color: ".esc_attr(get_theme_mod('menuTextColorScroll','#000')).";
        }    

      }   
      /*  Menu Css Cutomization */
    ";
    $custom_css .= wp_kses_post(get_theme_mod('customCss'));
  wp_add_inline_style( 'college_education_style', $custom_css ); 

  $script_js = '';  
  
  if(get_theme_mod('theme_header_fix',0))
  {
    $script_js .="
      jQuery(window).scroll(function () {
        if (jQuery(window).scrollTop() > 200) {
            jQuery('.header-top').addClass('fixed-header');             
        } else {           
            jQuery('.header-top').removeClass('fixed-header');
        }
      });
      ";
  }
  $animate = get_theme_mod('animation',1);
  if($animate == '1'){ 
    $script_js .="
      jQuery(window).load(function(){
        new WOW().init();
      });
    ";
  }
  wp_add_inline_script( 'college_education_custom_script', $script_js );
 }