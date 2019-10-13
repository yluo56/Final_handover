<?php
/**
 * slider section
 *
 * @package Knight
 */

$default = knight_get_default_theme_options();

// Slider Main Section.
$wp_customize->add_section('slider_section_settings',
	array(
		'title'      => esc_html__('Slider Options', 'knight'),
		'priority'   => 100,
		'capability' => 'edit_theme_options',
		'panel'      => 'theme_option_panel',
	)
);

// Setting - show_slider_section.
$wp_customize->add_setting('show_slider_section',
	array(
		'default'           => $default['show_slider_section'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'knight_sanitize_checkbox',
	)
);
$wp_customize->add_control('show_slider_section',
	array(
		'label'    => esc_html__('Enable Slider', 'knight'),
		'section'  => 'slider_section_settings',
		'type'     => 'checkbox',
		'priority' => 100,
	)
);

/*No of Slider*/
$wp_customize->add_setting('number_of_home_slider',
	array(
		'default'           => $default['number_of_home_slider'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'knight_sanitize_select',
	)
);
$wp_customize->add_control('number_of_home_slider',
	array(
		'label'       => esc_html__('Select no of slider', 'knight'),
		'section'     => 'slider_section_settings',
		'choices'     => array(
			'1'          => esc_html__('1', 'knight'),
			'2'          => esc_html__('2', 'knight'),
			'3'          => esc_html__('3', 'knight'),
			'4'          => esc_html__('4', 'knight'),
			'5'          => esc_html__('5', 'knight'),
			'6'          => esc_html__('6', 'knight'),
		),
		'type'     => 'select',
		'priority' => 105,
	)
);

/*content excerpt in Slider*/
$wp_customize->add_setting('number_of_content_home_slider',
	array(
		'default'           => $default['number_of_content_home_slider'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'knight_sanitize_positive_integer',
	)
);
$wp_customize->add_control('number_of_content_home_slider',
	array(
		'label'       => esc_html__('Select no words of slider', 'knight'),
		'section'     => 'slider_section_settings',
		'type'        => 'number',
		'priority'    => 110,
		'input_attrs' => array('min' => 0, 'max' => 200, 'style' => 'width: 150px;'),

	)
);


// Setting - drop down category for slider.
$wp_customize->add_setting('select_category_for_slider',
	array(
		'default'           => $default['select_category_for_slider'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	)
);
$wp_customize->add_control(new Knight_Dropdown_Taxonomies_Control($wp_customize, 'select_category_for_slider',
		array(
			'label'           => esc_html__('Category for slider', 'knight'),
			'description'     => esc_html__('Select category to be shown on tab ', 'knight'),
			'section'         => 'slider_section_settings',
			'type'            => 'dropdown-taxonomies',
			'taxonomy'        => 'category',
			'priority'        => 130,

		)));

/*settings for Section property*/
/*Button Text*/
$wp_customize->add_setting('button_text_on_slider',
	array(
		'default'           => $default['button_text_on_slider'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control('button_text_on_slider',
	array(
		'label'       => esc_html__('Read More Text', 'knight'),
		'description' => esc_html__('Removing text will disable read more on the slider', 'knight'),
		'section'     => 'slider_section_settings',
		'type'        => 'text',
		'priority'    => 170,
	)
);

// Setting - slider_text_title_size.
$wp_customize->add_setting( 'slider_text_title_size',
    array(
        'default'           => $default['slider_text_title_size'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'knight_sanitize_positive_integer',
    )
);
$wp_customize->add_control( 'slider_text_title_size',
    array(
        'label'    => __( 'Text Size For Slider Title', 'knight' ),
        'section'  => 'slider_section_settings',
        'type'     => 'number',
        'priority' => 120,
        'input_attrs'     => array( 'min' => 1, 'max' => 100, 'style' => 'width: 150px;' ),
    )
);

// Intro Section.
$wp_customize->add_section('intro_section_settings',
    array(
        'title' => esc_html__('Intro Section Options', 'knight'),
        'priority' => 100,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);
// Setting - show_intro_section_section.
$wp_customize->add_setting('show_intro_section_section',
    array(
        'default' => $default['show_intro_section_section'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'knight_sanitize_checkbox',
    )
);
$wp_customize->add_control('show_intro_section_section',
    array(
        'label' => esc_html__('Enable Intro Section', 'knight'),
        'section' => 'intro_section_settings',
        'type' => 'checkbox',
        'priority' => 100,
    )
);


//intro section image and url
for ( $i=1; $i <= 3 ; $i++ ) {
        $wp_customize->add_setting( 'title_for_intro_'.$i, array(
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
        ) );

        $wp_customize->add_control( 'title_for_intro_'.$i, array(
            'label'             => esc_html__( 'Title For Intro Section ', 'knight' ) . ' - ' . $i ,
            'priority'          =>  '120' . $i,
            'section'           => 'intro_section_settings',
            'type'              => 'text',
            'priority'          => 120,
            )
        );
        $wp_customize->add_setting( 'intro_section_image_'.$i, array(
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'knight_sanitize_image',
        ) );
        $wp_customize->add_control(
            new WP_Customize_Image_Control( $wp_customize, 'intro_section_image_'.$i,
            array(
            'label'             => esc_html__( 'Upload Image For Intro Section ', 'knight' ) . ' - ' . $i ,
            'section'         => 'intro_section_settings',
            'priority'        => 120,
            )
        )
        );
        $wp_customize->add_setting( 'url_for_intro_'.$i, array(
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'esc_url_raw',
        ) );

        $wp_customize->add_control( 'url_for_intro_'.$i, array(
            'label'             => esc_html__( 'URL For Intro Section ', 'knight' ) . ' - ' . $i ,
            'priority'          =>  '120' . $i,
            'section'           => 'intro_section_settings',
            'type'              => 'text',
            'priority'          => 120,
            )
        );
    }



// Footer featured fix post Section.
$wp_customize->add_section('footer_pined_post_section_settings',
    array(
        'title' => esc_html__('You May Like Section Options', 'knight'),
        'priority' => 100,
        'capability' => 'edit_theme_options',
        'panel' => 'theme_option_panel',
    )
);
// Setting - show_footer_pinned_post_section_section.
$wp_customize->add_setting('show_footer_pinned_post_section_section',
    array(
        'default' => $default['show_footer_pinned_post_section_section'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'knight_sanitize_checkbox',
    )
);
$wp_customize->add_control('show_footer_pinned_post_section_section',
    array(
        'label' => esc_html__('Enable You May Like Post Section', 'knight'),
        'section' => 'footer_pined_post_section_settings',
        'type' => 'checkbox',
        'priority' => 100,
    )
);

/*No of Slider*/
$wp_customize->add_setting('title_footer_pinned_post',
	array(
		'default'           => $default['title_footer_pinned_post'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control('title_footer_pinned_post',
	array(
		'label'       => esc_html__('Section Title', 'knight'),
		'section'     => 'footer_pined_post_section_settings',
		'type'        => 'text',
		'priority'    => 110,
	)
);

$wp_customize->add_setting('select_category_for_footer_pinned_section',
	array(
		'default'           => $default['select_category_for_footer_pinned_section'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	)
);
$wp_customize->add_control(new Knight_Dropdown_Taxonomies_Control($wp_customize, 'select_category_for_footer_pinned_section',
		array(
			'label'           => esc_html__('Category for You May Like', 'knight'),
			'section'         => 'footer_pined_post_section_settings',
			'type'            => 'dropdown-taxonomies',
			'taxonomy'        => 'category',
			'priority'        => 130,

		)));



        // Footer Latest fix post Section.
        $wp_customize->add_section('latest_fix_post_section_settings',
            array(
                'title' => esc_html__('Footer Pinned Post Section Options', 'knight'),
                'priority' => 100,
                'capability' => 'edit_theme_options',
                'panel' => 'theme_option_panel',
            )
        );
        // Setting - show_latest_fixed_post_section_section.
        $wp_customize->add_setting('show_latest_fixed_post_section_section',
            array(
                'default' => $default['show_latest_fixed_post_section_section'],
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'knight_sanitize_checkbox',
            )
        );
        $wp_customize->add_control('show_latest_fixed_post_section_section',
            array(
                'label' => esc_html__('Enable Footer Pinned Post', 'knight'),
                'section' => 'latest_fix_post_section_settings',
                'type' => 'checkbox',
                'priority' => 100,
            )
        );

        /*No of Slider*/
        $wp_customize->add_setting('number_of_fixed_post',
            array(
                'default'           => $default['number_of_fixed_post'],
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'knight_sanitize_positive_integer',
            )
        );
        $wp_customize->add_control('number_of_fixed_post',
            array(
                'label'       => esc_html__('Select no of post to display (max-15)', 'knight'),
                'section'     => 'latest_fix_post_section_settings',
                'type'        => 'number',
                'priority'    => 110,
                'input_attrs' => array('min' => 1, 'max' => 15, 'style' => 'width: 150px;'),

            )
        );

        $wp_customize->add_setting('select_category_for_footer_fix_section',
            array(
                'default'           => $default['select_category_for_footer_fix_section'],
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'absint',
            )
        );
        $wp_customize->add_control(new Knight_Dropdown_Taxonomies_Control($wp_customize, 'select_category_for_footer_fix_section',
                array(
                    'label'           => esc_html__('Category for Footer Pinned Post', 'knight'),
                    'section'         => 'latest_fix_post_section_settings',
                    'type'            => 'dropdown-taxonomies',
                    'taxonomy'        => 'category',
                    'priority'        => 130,

                )));
