<?php
/*
* The Header template for our theme
*/ ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <meta charset="<?php bloginfo( 'charset' ); ?>" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />   
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php wp_head(); ?>
</head>
<body <?php body_class();?>> 
    <?php if(get_theme_mod('preloader') != 2) :
        if(get_theme_mod('customPreloader') == '') { ?>
            <div class="preloader">
                <span class="preloader-gif">
                    <svg width='70px' height='70px' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="uil-ring">
                        <circle id="loader" cx="50" cy="50" r="40" stroke-dasharray="163.36281798666926 87.9645943005142" stroke="<?php echo esc_attr(get_theme_mod('themeColor','#c1331b')); ?>" fill="none" stroke-width="5"></circle>
                    </svg>
                </span>
            </div>
        <?php } else { ?>
            <div class="preloader"><span class="preloader-gif" style="background: url(<?php echo esc_url(get_theme_mod('customPreloader')); ?>) no-repeat;background-size: contain;animation: none;"></span></div>
    <?php } endif; 
     $header_menu=1; $header_style = get_theme_mod('menustyle');
    if($header_menu != 0 || is_front_page()) : ?>
    <header>
        <div class="header-top <?php if($header_style != 1){ echo 'transparent'; } else {echo 'no-transparent';} ?>">
            <div class="container">
                <div class="row">
                    <nav>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="logo">
                                <?php
                                if(has_custom_logo()){
                                    the_custom_logo();
                                } 
                                $college_education_dark_logo_id=get_theme_mod('CollegeEducationDarkLogo');
                                $college_education_dark_logo=wp_get_attachment_url($college_education_dark_logo_id);
                                if($college_education_dark_logo == ''){
                                    $custom_logo_id = get_theme_mod( 'custom_logo' );
                                    $college_education_dark_logo = wp_get_attachment_url( $custom_logo_id );
                                }
                                if($college_education_dark_logo != ''){ ?> 
                                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link" rel="home" itemprop="url">
                                        <img class="img-responsive logo-dark" src="<?php echo esc_url($college_education_dark_logo); ?>" alt="<?php esc_attr_e('Logo','college-education'); ?>">
                                    </a>
                                    <?php }
                                    if(get_theme_mod('header_text',true)):?>
                                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" id='brand' class="custom-logo-link"><span class="site-title"><h4><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h4><small class="site-description"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></small></span></a>   
                                    <?php endif; ?> 
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-8 col-xs-12 mob_nav">
                            <div class="main-menu">  
                                <div id='menu-style'>
                                    <?php 
                                        $college_education_defaults = array(
                                            'theme_location'    => 'primary',
                                            'container'         => 'ul',
                                            'menu_class'        => 'mobilemenu',
                                        );
                                        wp_nav_menu($college_education_defaults);
                                     ?>  
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <?php endif; ?>