<?php
/**
 * Theme functions and definitions
 *
 * @package Busiway
 */

if ( ! function_exists( 'busiway_enqueue_styles' ) ) :

	/**
	 * Load assets.
	 *
	 * @since 1.0.0
	 */
	function busiway_enqueue_styles() {

		wp_enqueue_style( 'consultup-style-parent', get_template_directory_uri() . '/style.css' );
		wp_enqueue_style( 'busiway-style', get_stylesheet_directory_uri() . '/style.css', array( 'consultup-style-parent' ), '1.0' );
		wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css');
		wp_enqueue_style( 'busiway-default-css', get_stylesheet_directory_uri()."/css/colors/default.css" );
		wp_dequeue_style( 'default',get_template_directory_uri() .'/css/colors/default.css');
	}

endif;

add_action( 'wp_enqueue_scripts', 'busiway_enqueue_styles', 99 );


function busiway_customizer_rid_values($wp_customize) {

  $wp_customize->remove_section('header_widget_one');
  $wp_customize->remove_section('header_widget_two');
  $wp_customize->remove_section('header_widget_three');
}

add_action( 'customize_register', 'busiway_customizer_rid_values', 1000 );