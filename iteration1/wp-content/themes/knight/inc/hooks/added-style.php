<?php
/**
 * CSS related hooks.
 *
 * This file contains hook functions which are related to CSS.
 *
 * @package Knight
 */

if (!function_exists('knight_trigger_custom_css_action')):

    /**
     * Do action theme custom CSS.
     *
     * @since 1.0.0
     */
    function knight_trigger_custom_css_action()
    {
        global $knight_google_fonts;
        $knight_enable_banner_overlay = knight_get_option('enable_overlay_option');
        $knight_primary_color = knight_get_option('primary_color');
        $knight_secondary_color = knight_get_option('secondary_color');
        $knight_footer_color = knight_get_option('footer_color');
        $knight_mailchimp_bg_color = knight_get_option('mailchimp_bg_color');
        $knight_mailchimp_text_color = knight_get_option('mailchimp_text_color');

        $knight_primary_font = $knight_google_fonts[knight_get_option('primary_font')];
        $knight_secondary_font = $knight_google_fonts[knight_get_option('secondary_font')];

        $knight_font_slider_text_title_size = knight_get_option('slider_text_title_size');
        $knight_font_general_text_size = knight_get_option('general_text_size');
        $knight_font_title_heading_size_1 = knight_get_option('title_heading_size_1');
        $knight_font_title_heading_size_2 = knight_get_option('title_heading_size_2');

        ?>
        <style type="text/css">
            <?php if ($knight_enable_banner_overlay == 1) {
                ?>
            body .inner-header-overlay {
                filter: alpha(opacity=42);
                opacity: .42;
            }

            .site .section-overlay{
                filter: alpha(opacity=74);
                opacity: .74;
            }

            body .owl-carousel .owl-item.active .single-slide:after {
                content: "";
            }

            <?php } ?>

            <?php if (!empty($knight_primary_color) ){ ?>
            body button,
            body input[type="button"],
            body input[type="reset"],
            body input[type="submit"],
            body .scroll-up:hover,
            body .scroll-up:focus,
            body .btn-link-primary,
            body .wp-block-quote,
            body .menu-description,
            .site .twp-content-details .continue-reading,
            .site .mc4wp-form-fields input[type="submit"],
            .cover-stories-featured .twp-content-details .continue-reading:before,
            .cover-stories-featured .twp-content-details .continue-reading:after{
                background: <?php echo esc_html($knight_primary_color); ?>;
            }

            body .btn-link:link:hover,
            body .btn-link:visited:hover,
            .site .widget:not(.knight_social_widget) ul li a:hover,
            .site .widget:not(.knight_social_widget) ul li a:focus{
                color: <?php echo esc_html($knight_primary_color); ?>;
            }

            body button,
            body input[type="button"],
            body input[type="reset"],
            body input[type="submit"],
            body .btn-link,
            body .site-footer .author-info .profile-image,
            .site .twp-content-details .continue-reading-wrapper:before{
                border-color: <?php echo esc_html($knight_primary_color); ?>;
            }

            .site .twp-content-details .continue-reading:after{
                border-left-color: <?php echo esc_html($knight_primary_color); ?>;
            }

            @media only screen and (min-width: 992px) {
                body .main-navigation .menu > ul > li:hover > a,
                body .main-navigation .menu > ul > li:focus > a,
                body .main-navigation .menu > ul > li.current-menu-item > a {
                    color: <?php echo esc_html($knight_primary_color); ?>;
                }
            }

            <?php } ?>

            <?php if (!empty($knight_secondary_color) ){ ?>
            body .primary-bgcolor,
            body button:hover,
            body button:focus,
            body input[type="button"]:hover,
            body input[type="button"]:focus,
            body input[type="reset"]:hover,
            body input[type="reset"]:focus,
            body input[type="submit"]:hover,
            body input[type="submit"]:focus,
            body .scroll-up {
                background-color: <?php echo esc_html($knight_secondary_color); ?>;
            }

            body .primary-textcolor {
                color: <?php echo esc_html($knight_secondary_color); ?>;;
            }

            body button:hover,
            body button:focus,
            body input[type="button"]:hover,
            body input[type="button"]:focus,
            body input[type="reset"]:hover,
            body input[type="reset"]:focus,
            body input[type="submit"]:hover,
            body input[type="submit"]:focus {
                border-color: <?php echo esc_html($knight_secondary_color); ?>;
            }

            <?php } ?>

            <?php if (!empty($knight_footer_color) ){ ?>
            body .site-footer .footer-bottom {
                background: <?php echo esc_html($knight_footer_color); ?>;
            }

            <?php } ?>

            <?php if (!empty($knight_mailchimp_bg_color) ){ ?>
            body .mailchimp-bgcolor {
                background: <?php echo esc_html($knight_mailchimp_bg_color); ?>;
            }

            <?php } ?>

            <?php if (!empty($knight_mailchimp_text_color) ){ ?>
            body .mailchimp-bgcolor,
            body .section-mailchimp .section-details .section-title,
            body .section-mailchimp .mc4wp-form-fields input[type="text"],
            body .section-mailchimp .mc4wp-form-fields input[type="email"]{
                color: <?php echo esc_html($knight_mailchimp_text_color); ?>;
            }

            body .mailchimp-bgcolor .section-title:after{
                border-color: <?php echo esc_html($knight_mailchimp_text_color); ?>;
            }

            <?php } ?>

            <?php if (!empty($knight_primary_font) ){ ?>
            body h1,
            body h2,
            body h3,
            body h4,
            body h5,
            body h6,
            body .main-navigation a,
            body .primary-font {
                font-family: <?php echo esc_html($knight_primary_font); ?> !important;
            }

            <?php } ?>

            <?php if (!empty($knight_secondary_font) ){ ?>
            body,
            body button,
            body input,
            body select,
            body textarea,
            body .secondary-font {
                font-family: <?php echo esc_html($knight_secondary_font); ?> !important;
            }

            <?php } ?>

            <?php if (!empty($knight_font_general_text_size) ){ ?>
            body .site, body .site button, body .site input, body .site select, body .site textarea {
                font-size: <?php echo esc_html($knight_font_general_text_size); ?>px;
            }

            <?php } ?>


            <?php if (!empty($knight_font_slider_text_title_size) ){ ?>
            @media only screen and (min-width: 768px){
                body .site .slide-title {
                    font-size: <?php echo esc_html($knight_font_slider_text_title_size); ?>px !important;
                }
            }
            <?php } ?>

            <?php if (!empty($knight_font_title_heading_size_1) ){ ?>
            @media only screen and (min-width: 768px){
                body .site .entry-title {
                    font-size: <?php echo esc_html($knight_font_title_heading_size_1); ?>px !important;
                }
            }
            <?php } ?>

            <?php if (!empty($knight_font_title_heading_size_2) ){ ?>
            @media only screen and (min-width: 768px){
                body .site .entry-title.entry-title-small {
                    font-size: <?php echo esc_html($knight_font_title_heading_size_2); ?>px !important;
                }
            }
            <?php } ?>



        </style>

    <?php }

endif;