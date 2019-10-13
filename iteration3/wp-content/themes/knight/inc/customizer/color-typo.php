<?php 
$default = knight_get_default_theme_options();

//$wp_customize->get_section('colors')->title = __( 'General settings' );

// Add Theme Options Panel.
$wp_customize->add_panel( 'theme_color_typo',
	array(
		'title'      => __( 'General settings', 'knight' ),
		'priority'   => 40,
		'capability' => 'edit_theme_options',
	)
);

// font Section.
$wp_customize->add_section( 'font_typo_section',
	array(
		'title'      => __( 'Fonts & Typography', 'knight' ),
		'priority'   => 100,
		'capability' => 'edit_theme_options',
		'panel'      => 'theme_color_typo',
	)
);

// font Section.
$wp_customize->add_section( 'colors',
	array(
		'title'      => __( 'Color Options', 'knight' ),
		'priority'   => 100,
		'capability' => 'edit_theme_options',
		'panel'      => 'theme_color_typo',
	)
);

// Setting - primary_color.
$wp_customize->add_setting( 'primary_color',
	array(
		'default'           => $default['primary_color'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
	)
);
$wp_customize->add_control( 'primary_color',
	array(
		'label'    => __( 'Primary Color', 'knight' ),
		'section'  => 'colors',
		'type'     => 'color',
		'priority' => 100,
	)
);


// Setting - secondary_color.
$wp_customize->add_setting( 'secondary_color',
	array(
		'default'           => $default['secondary_color'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
	)
);
$wp_customize->add_control( 'secondary_color',
	array(
		'label'    => __( 'Secondary Color', 'knight' ),
		'section'  => 'colors',
		'type'     => 'color',
		'priority' => 100,
	)
);

global $knight_google_fonts;

// Setting - primary_font.
$wp_customize->add_setting( 'primary_font',
	array(
		'default'           => $default['primary_font'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'knight_sanitize_select',
	)
);
$wp_customize->add_control( 'primary_font',
	array(
		'label'    => __( 'Primary Font', 'knight' ),
		'section'  => 'font_typo_section',
		'type'     => 'select',
		'choices'     => $knight_google_fonts,
		'priority' => 100,
	)
);

// Setting - secondary_font.
$wp_customize->add_setting( 'secondary_font',
	array(
		'default'           => $default['secondary_font'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'knight_sanitize_select',
	)
);
$wp_customize->add_control( 'secondary_font',
	array(
		'label'    => __( 'Secondary Font', 'knight' ),
		'section'  => 'font_typo_section',
		'type'     => 'select',
		'choices'     => $knight_google_fonts,
		'priority' => 110,
	)
);


// Setting - general_text_size.
$wp_customize->add_setting( 'general_text_size',
	array(
		'default'           => $default['general_text_size'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'knight_sanitize_positive_integer',
	)
);
$wp_customize->add_control( 'general_text_size',
	array(
		'label'    => __( 'General Text Size', 'knight' ),
		'section'  => 'font_typo_section',
		'type'     => 'number',
		'priority' => 120,
		'input_attrs'     => array( 'min' => 1, 'max' => 100, 'style' => 'width: 150px;' ),
	)
);


// Setting - title_heading_size_1.
$wp_customize->add_setting( 'title_heading_size_1',
	array(
		'default'           => $default['title_heading_size_1'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'knight_sanitize_positive_integer',
	)
);
$wp_customize->add_control( 'title_heading_size_1',
	array(
		'label'    => __( 'Title Heading Size 1', 'knight' ),
		'section'  => 'font_typo_section',
		'type'     => 'number',
		'priority' => 120,
		'input_attrs'     => array( 'min' => 1, 'max' => 100, 'style' => 'width: 150px;' ),
	)
);


// Setting - title_heading_size_2.
$wp_customize->add_setting( 'title_heading_size_2',
	array(
		'default'           => $default['title_heading_size_2'],
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'knight_sanitize_positive_integer',
	)
);
$wp_customize->add_control( 'title_heading_size_2',
	array(
		'label'    => __( 'Title Heading Size 2', 'knight' ),
		'section'  => 'font_typo_section',
		'type'     => 'number',
		'priority' => 120,
		'input_attrs'     => array( 'min' => 1, 'max' => 100, 'style' => 'width: 150px;' ),
	)
);
