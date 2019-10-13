<?php
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function knight_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__('Sidebar', 'knight'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets here.', 'knight'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h5 class="widget-title bordered-widget-title">',
        'after_title' => '</h5>',
    ));


    $knight_footer_widgets_number = knight_get_option('number_of_footer_widget');

    if ($knight_footer_widgets_number > 0) {
        register_sidebar(array(
            'name' => esc_html__('Footer Column One', 'knight'),
            'id' => 'footer-col-one',
            'description' => esc_html__('Displays items on footer section.', 'knight'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h5 class="widget-title bordered-widget-title">',
            'after_title' => '</h5>',
        ));
        if ($knight_footer_widgets_number > 1) {
            register_sidebar(array(
                'name' => esc_html__('Footer Column Two', 'knight'),
                'id' => 'footer-col-two',
                'description' => esc_html__('Displays items on footer section.', 'knight'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h5 class="widget-title bordered-widget-title">',
                'after_title' => '</h5>',
            ));
        }
        if ($knight_footer_widgets_number > 2) {
            register_sidebar(array(
                'name' => esc_html__('Footer Column Three', 'knight'),
                'id' => 'footer-col-three',
                'description' => esc_html__('Displays items on footer section.', 'knight'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h5 class="widget-title bordered-widget-title">',
                'after_title' => '</h5>',
            ));
        }
    }
}

add_action('widgets_init', 'knight_widgets_init');
