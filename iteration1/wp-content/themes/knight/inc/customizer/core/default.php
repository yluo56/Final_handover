<?php
/**
 * Default theme options.
 *
 * @package Knight
 */

if (!function_exists('knight_get_default_theme_options')):

/**
 * Get default theme options
 *
 * @since 1.0.0
 *
 * @return array Default theme options.
 */
function knight_get_default_theme_options() {

	$defaults = array();

        // Slider Section.
        $defaults['show_slider_section']           = 1;
        $defaults['number_of_home_slider']         = 3;
        $defaults['number_of_content_home_slider'] = 20;
        $defaults['select_slider_from']            = 'from-category';
        $defaults['select-page-for-slider']        = 0;
        $defaults['select_category_for_slider']    = 1;
        $defaults['slider_section_layout']         = 'twp-slider';
        $defaults['button_text_on_slider']         = esc_html__('Read More', 'knight');
        $defaults['show_intro_section_section']    = 0;
            

        $defaults['show_latest_fixed_post_section_section'] = 1;
        $defaults['title_footer_pinned_post'] = esc_html__('You may also like', 'knight');
        $defaults['select_category_for_footer_pinned_section'] = 0;

        $defaults['show_footer_pinned_post_section_section'] = 1;
        $defaults['number_of_fixed_post'] = 8;
        $defaults['select_category_for_footer_fix_section'] = 0;

        /*layout*/
        $defaults['enable_overlay_option']    = 1;
        $defaults['homepage_layout_option']   = 'full-width';
        $defaults['read_more_button_text']    = esc_html__('Continue Reading', 'knight');
        $defaults['global_layout']            = 'right-sidebar';
        $defaults['excerpt_length_global']    = 50;
        $defaults['single_post_image_layout'] = 'full';
        $defaults['pagination_type']          = 'infinite_scroll_load';
        $defaults['copyright_text']           = esc_html__('Copyright All right reserved.', 'knight');
        $defaults['number_of_footer_widget']  = 3;
        $defaults['breadcrumb_type']          = 'simple';
        $defaults['enable_preloader']         = 0;
        $defaults['enable_copyright_credit']  = 1;

        /*instagram*/
        $defaults['enable_instagram']               = 0;
        $defaults['instagram_user_api']             = '';
        $defaults['instagram_user_name']            = '';
        $defaults['number_of_instagram']            = 9;

		/*font and color*/
        $defaults['slider_text_title_size']         = 58;
        $defaults['general_text_size']              = 18;
        $defaults['title_heading_size_1']           = 44;
		$defaults['title_heading_size_2']			= 22;
        $defaults['primary_color']                  = '#d72828';
        $defaults['secondary_color']                = '#161f30';
        $defaults['footer_color']                   = '#0f0f0f';
        $defaults['primary_font']                   = 'Roboto+Condensed:400,700';
        $defaults['secondary_font']                 = 'Roboto:100,300,400,500,700';
	// Pass through filter.
	$defaults = apply_filters('knight_filter_default_theme_options', $defaults);

	return $defaults;

}

endif;
