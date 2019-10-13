<?php

if (!class_exists('Dooodl_Redux_Config')) {

    class Dooodl_Redux_Config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs.  ;)
            if (  true == Redux_Helpers::isTheme(__FILE__) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

        }

        public function initSettings() {

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        public function setSections() {
        	// ACTUAL DECLARATION OF SECTIONS
            $this->sections[] = array(
                'title'     => __('Settings', 'dooodl'),
                'desc'      => __('Here you can set all options for the Dooodl plugin', 'dooodl'),
                'icon'      => 'el-icon-cogs',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(

                    array(
						'id'        => 'moderation_enabled',
						'type'      => 'checkbox',
						'title'     => __('Moderate new Dooodls?', 'dooodl'),
						'subtitle' 	=> __('Should newly submitted Dooodls be held in moderation before you display them on your website?', 'dooodl'),
                    ),

                    array(
						'id'        => 'email_notification',
						'type'      => 'checkbox',
						'title'     => __('Email upon new Dooodls?', 'dooodl'),
						'subtitle' 	=> __('Would you like to receive an email upon new Dooodl submissions?', 'dooodl'),
                    ),

                )



            );

			$this->sections[] = array(
                'title'     => __('Widget Layout', 'dooodl'),
                'desc'      => __('Here you can set any of the changes that apply to the Dooodl Widget', 'dooodl'),
                'icon'      => 'el-icon-website',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                	 array(
					    'id'       => 'widget_template',
					    'type'     => 'ace_editor',
					    'mode'	   => 'html',
					    'options'  => array(
					    	"highlightActiveLine"	=> true,
					    	"wrapBehavioursEnabled"	=> false,
					    	"displayIndentGuides" 	=> true,
					    	"minLines" => 20
					    	),
					    'default'  => dooodl_get_whtml_template(),
					    'title'    => __('Widget layout template', 'dooodl'),
					    'subtitle' => __('You can define a custom template in this textarea', 'dooodl'),
					    'desc'     => __('	You can use the following placeholders. <br/>
					    					%TITLE% - The title of the current Dooodl<br/>
											%DESCRIPTION% - The little message the author added when (s)he submitted this Dooodl<br/>
											%AUTHOR% - The name of the author who created current Dooodl<br/>
											%AUTHOR_URL% - The URL to the author of the the current Dooodl<br/>
											%DOOODL_URL% - The URL to the image of the current Dooodl<br/>
											%TOTAL_COUNT% - The number of Dooodls that have been submitted<br/>
											%CREATOR_URL% - The URL to the creator which enables people to draw Dooodls<br/>
											%GALLERY_URL% - The URL to the gallery which shows all Dooodls
										', 'dooodl'),
					),
                )
            );



			$this->sections[] = array(
                'title'     => __('HTML Gallery Layout', 'dooodl'),
                'desc'      => __('Here you can set the Dooodl gallery layout options like colors etc', 'dooodl'),
                'icon'      => 'el-icon-screen',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                	array(
					    'id'       => 'gallery_title_color',
					    'type'     => 'color',
					    'title'    => __('Gallery title color', 'dooodl'),
					    'validate' => 'color',
					    'default' => '#ffffff',
					  	'transparent' => false
					),
					array(
					    'id'       => 'gallery_title_bg_color',
					    'type'     => 'color',
					    'title'    => __('Gallery title background color', 'dooodl'),
					    'validate' => 'color',
					    'default' => '#ff0066',
					  	'transparent' => true
					),
					array(
					    'id'       => 'gallery_intro_color',
					    'type'     => 'color',
					    'title'    => __('Gallery intro color', 'dooodl'),
					    'validate' => 'color',
					    'default' => '#ffffff',
					  	'transparent' => false
					),
					array(
					    'id'       => 'gallery_intro_bg_color',
					    'type'     => 'color',
					    'title'    => __('Gallery intro background color', 'dooodl'),
					    'validate' => 'color',
					    'default' => '#333333',
					  	'transparent' => true
					),
					array(
					    'id'       => 'gallery_text_color',
					    'type'     => 'color',
					    'title'    => __('General text color', 'dooodl'),
					    'validate' => 'color',
					    'default' => '#ffffff',
					  	'transparent' => false
					),
                	 array(
					    'id'       => 'gallery_body_bg_color',
					    'type'     => 'color',
					    'title'    => __('Background color', 'dooodl'),
					    'validate' => 'color',
					    'default' => '#eeeeee',
					  	'transparent' => false
					),
                	array(
					    'id'       => 'gallery_dooodl_item_title_color',
					    'type'     => 'color',
					    'title'    => __('Dooodl item title color', 'dooodl'),
					    'validate' => 'color',
					    'default' => '#ffffff',
					  	'transparent' => true
					),
                	array(
					    'id'       => 'gallery_dooodl_item_bg_color',
					    'type'     => 'color',
					    'title'    => __('Dooodl item background color', 'dooodl'),
					    'validate' => 'color',
					    'default' => '#333333',
					  	'transparent' => true
					),
					array(
					    'id'       => 'gallery_link_color',
					    'type'     => 'color',
					    'title'    => __('Link color', 'dooodl'),
					    'validate' => 'color',
					    'default' => '#ffffff',
					  	'transparent' => false
					),
					array(
					    'id'       => 'gallery_link_bg_color',
					    'type'     => 'color',
					    'title'    => __('Link background color', 'dooodl'),
					    'validate' => 'color',
					    'default' => '#ff0066',
					  	'transparent' => true
					),

					array(
					    'id'       => 'gallery_custom_css',
					    'type'     => 'ace_editor',
					    'title'    => __('Custom CSS', 'dooodl'),
					    'desc'	   => __('Here you can add custom CSS to change the looks of the HTML gallery','dooodl'),
					    'mode'		=> 'css',
					    'default' => '',
					  	'transparent' => true
					),



                )
            );





			$this->sections[] = array(
                'title'     => __('Flash Viewer Layout', 'dooodl'),
                'desc'      => __('Here you can set the Flash Dooodl gallery layout options like colors etc', 'dooodl'),
                'icon'      => 'el-icon-photo',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                	array(
					    'id'       => 'use_flash_viewer',
					    'type'     => 'checkbox',
					    'title'    => __('Enable Flash Gallery', 'dooodl'),
					    'subtitle' => __('You can choose to use the Flash Gallery. The HTML fallback will be shown on devices with no Flash support', 'dooodl')
					),
					array(
					    'id'       => 'dooodl_flash_frame_color',
					    'type'     => 'color',
					    'title'    => __('Dooodl frame color', 'dooodl'),
					    'validate' => 'color',
					    'default' => '#ffffff',
					    'required' => array('use_flash_viewer','equals','1'),
					  	'transparent' => false
					),
					array(
					    'id'       => 'dooodl_flash_backColor',
					    'type'     => 'color',
					    'title'    => __('Background color', 'dooodl'),
					    'validate' => 'color',
					    'default' => '#ff0066',
					    'required' => array('use_flash_viewer','equals','1'),
					  	'transparent' => false
					),
					array(
					    'id'       => 'dooodl_flash_bgOuterColor',
					    'type'     => 'color',
					    'title'    => __('Background outer color', 'dooodl'),
					    'validate' => 'color',
					    'default' => '#111111',
					    'required' => array('use_flash_viewer','equals','1'),
					  	'transparent' => false
					),
					array(
					    'id'       => 'dooodl_flash_bgInnerColor',
					    'type'     => 'color',
					    'title'    => __('Inner background color', 'dooodl'),
					    'validate' => 'color',
					    'default' => '#333333',
					    'required' => array('use_flash_viewer','equals','1'),
					  	'transparent' => false
					),
                )
            );


		 $this->sections[] = array(
                'title'     => __('Shortcodes', 'dooodl'),
                'desc'      => dooodl_shortcode_explanation(),
                'icon'      => 'el-icon-bullhorn',
            );


			/*
            $this->sections[] = array(
                'title'     => __('Creator Layout', 'dooodl'),
                'desc'      => __('This is where you can set options like brushes, colors, size etc.', 'dooodl'),
                'icon'      => 'el-icon-bullhorn'
            );
			*/



        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            //$theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'          => 'dooodl_options',           	// This is where your data is stored in the database and also becomes your global variable name.
                'display_name'      => __('Dooodl', 'dooodl'),     	// Name that appears at the top of your panel
                'display_version'   => '2.0',  						// Version that appears at the top of your panel
                'menu_type'         => 'submenu',                  	//Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'    => false,                   	// Show the sections below the admin menu item or not
                'menu_title'        => __('Settings', 'dooodl'),
                'page_title'        => __('Dooodl Plugin Options', 'dooodl'),

                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => '', // Must be defined to add google fonts to the typography module

                'async_typography'  => false,                    // Use a asynchronous font on the front end or font string
                'admin_bar'         => false,                    // Show the panel pages on the admin bar
                //'global_variable'   => '',                      // Set a different name for your global variable other than the opt_name
                'dev_mode'          => false,                    // Show the time the page took to load, etc
                'customizer'        => true,                    // Enable basic customizer support

                // OPTIONAL -> Give you extra features
                'page_priority'     => null,                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent'       => 'dooodl-overview',       // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters

                'page_permissions'  => 'manage_options',        // Permissions needed to access the options panel.
                'menu_icon'         => 'el-icon-wordpress',     // Specify a custom URL to an icon
                'last_tab'          => '',                      // Force your panel to always open to a specific tab (by id)
                'page_icon'         => '',           // Icon displayed in the admin panel next to your menu_title
                'page_slug'         => "dooodl-options",     	// Page slug used to denote the panel
                'save_defaults'     => true,                    // On load save the defaults to DB before user clicks save or not
                'default_show'      => false,                   // If true, shows the default value next to each field that is not the default value.
                'default_mark'      => '',                      // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export'=> false,                   // Shows the Import/Export panel when not used as a field.
                'hide_reset' 		=> true,

                // CAREFUL -> These options are for advanced use only
                'transient_time'    => 60 * MINUTE_IN_SECONDS,
                'output'            => true,                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'        => true,                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                'footer_credit'     => " ",                      // Disable the footer credit of Redux. Please leave if you can help it.

                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'              => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info'           => false, // REMOVE

                // HINTS
                'hints' => array(
                    'icon'          => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',
                    'tip_style'     => array(
                        'color'         => 'light',
                        'shadow'        => true,
                        'rounded'       => false,
                        'style'         => '',
                    ),
                    'tip_position'  => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'    => array(
                        'show'          => array(
                            'effect'        => 'slide',
                            'duration'      => '500',
                            'event'         => 'mouseover',
                        ),
                        'hide'      => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'click mouseleave',
                        ),
                    ),
                )
            );


            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'http://ioartfactory.com',
                'title' => __('Check out my other work','dooodl'),
                'icon'  => 'el-icon-screen-alt'
                //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://nocreativity.com',
                'title' => __('Check out my personal blog and experiments','dooodl'),
                'icon'  => 'el-icon-home-alt'
                //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
            );
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/ioartfactory',
                'title' => __('Like my page on Facebook','dooodl'),
                'icon'  => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://twitter.com/nocreativity',
                'title' => __('Follow me on Twitter','dooodl'),
                'icon'  => 'el-icon-twitter'
            );

            $this->args['share_icons'][] = array(
                'url'   => 'https://wordpress.org/plugins/dooodl/',
                'title' => __('Keep up with Dooodl changes on the WordPress plugin site and leave a review','dooodl'),
                'icon'  => 'el-icon-wordpress'
            );


            //$this->args['intro_text'] = __('This is where you can set up everything about Dooodl', 'dooodl');
            //$this->args['footer_text'] = __('', 'dooodl');
        }

    }


    global $dooodlReduxConfig;
    $dooodlReduxConfig = new Dooodl_Redux_Config();

}