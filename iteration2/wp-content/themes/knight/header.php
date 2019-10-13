<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Knight
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<?php if ((knight_get_option('enable_preloader')) == 1) { ?>
    <div class="preloader">
        <div class="preloader-wrapper">
            <div class="loader">
            </div>
        </div>
    </div>
<?php } ?>
<!-- full-screen-layout/boxed-layout -->
<?php if (knight_get_option('homepage_layout_option') == 'full-width') {
    $knight_homepage_layout = 'full-screen-layout';
} elseif (knight_get_option('homepage_layout_option') == 'boxed') {
    $knight_homepage_layout = 'boxed-layout';
} ?>
<div id="page" class="site site-bg <?php echo $knight_homepage_layout; ?>">
    <a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Skip to content', 'knight'); ?></a>

    <header id="masthead" class="site-header" role="banner">
        <div class="top-bar">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-xs-12">
                        <div class="tm-social-share">
                            <div class="social-icons">
                                <?php
                                wp_nav_menu(
                                    array('theme_location' => 'social',
                                        'link_before' => '<span>',
                                        'link_after' => '</span>',
                                        'menu_id' => 'social-menu',
                                        'fallback_cb' => false,
                                        'menu_class' => false
                                    )); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12 pull-right icon-search">
                        <?php get_search_form(); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="top-branding">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="site-branding">
                            <?php
                            knight_the_custom_logo();
                            if (is_front_page() && is_home()) : ?>
                                <span class="site-title primary-font">
                                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                    <?php bloginfo('name'); ?>
                                </a>
                            </span>
                            <?php else : ?>
                                <span class="site-title primary-font">
                                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                    <?php bloginfo('name'); ?>
                                </a>
                            </span>
                            <?php
                            endif;
                            $description = get_bloginfo('description', 'display');
                            if ($description || is_customize_preview()) : ?>
                                <p class="site-description">
                                   <span><?php echo esc_html($description); ?></span>
                                </p>
                            <?php
                            endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="top-header">
            <nav class="main-navigation" role="navigation">
                <span class="toggle-menu" aria-controls="primary-menu" aria-expanded="false">
                     <span class="screen-reader-text">
                        <?php esc_html_e('Primary Menu', 'knight'); ?>
                    </span>
                    <i class="ham"></i>
                </span>
                <?php
                if (has_nav_menu( 'menu-1' )) {
                    wp_nav_menu(array(
                        'theme_location' => 'menu-1',
                        'menu_id' => 'primary-menu',
                        'container' => 'div',
                        'container_class' => 'menu',
                        'depth'             => 3,
                        'walker'       => new Knight_Walker_Nav_Menu()
                    ));
                } else {
                wp_nav_menu(array(
                    'menu_id' => 'primary-menu',
                    'container' => 'div',
                    'container_class' => 'menu',
                    'depth'             => 3,
                ));
                } ?>
            </nav>
        </div>

    </header>
    <!-- #masthead -->

    <!-- Innerpage Header Begins Here -->
    <?php
    if (is_front_page() || is_home()) {
        do_action('knight_action_slider_post');
        do_action('knight_action_intro_post');
    } else {
        do_action('knight-page-inner-title');
    }
    ?>
    <!-- Innerpage Header Ends Here -->
    <div id="content" class="site-content">