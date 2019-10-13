<?php
/*
 * college-education Main Sidebar
 */
function college_education_widgets_init() {
    register_sidebar(array(
        'name' => __('Main Sidebar', 'college-education'),
        'id' => 'sidebar-1',
        'description' => __('Main sidebar that appears on the right.', 'college-education'),
        'before_widget' => '<aside id="%1$s" class="menu-left widget widget_recent_entries %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h6 class="widget-title-sidebar">',
        'after_title' => '</h6>',
    ));
    register_sidebar(array(
        'name' => __('Footer 1', 'college-education'),
        'id' => 'footer-1',
        'description' => __('Footer that appears on the down.', 'college-education'),
        'before_widget' => '<aside id="%1$s" class="footer-widget widget widget_recent_entries %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h6>',
        'after_title' => '</h6>',
    ));
    register_sidebar(array(
        'name' => __('Footer 2', 'college-education'),
        'id' => 'footer-2',
        'description' => __('Footer that appears on the down.', 'college-education'),
        'before_widget' => '<aside id="%1$s" class="footer-widget widget widget_recent_entries %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h6>',
        'after_title' => '</h6>',
    ));
    register_sidebar(array(
        'name' => __('Footer 3', 'college-education'),
        'id' => 'footer-3',
        'description' => __('Footer that appears on the down.', 'college-education'),
        'before_widget' => '<aside id="%1$s" class="footer-widget widget widget_recent_entries %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h6>',
        'after_title' => '</h6>',
    ));
    register_sidebar(array(
        'name' => __('Footer 4', 'college-education'),
        'id' => 'footer-4',
        'description' => __('Footer that appears on the down.', 'college-education'),
        'before_widget' => '<aside id="%1$s" class="footer-widget widget widget_recent_entries %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h6>',
        'after_title' => '</h6>',
    ));
}
add_action('widgets_init', 'college_education_widgets_init');
 
function college_education_excerpt( $limit) {
        if ( is_admin() ) {
                return $limit;
        }
        return get_theme_mod('blogPostExcerptTextLimit',20);
}
add_filter( 'excerpt_length', 'college_education_excerpt',999);

function college_education_font_families_filter($font_families) {
    $font_families['Poppins'] = 'Poppins';
    return $font_families;
}
add_filter('siteorigin_widgets_font_families', 'college_education_font_families_filter');

/*
* Function For Tag Meta List
*/
function college_education_tag_meta() {  
	$CollegeEducationTagList = get_the_tag_list('', esc_html__( ', #', 'college-education' ));
	if(!empty($CollegeEducationTagList)) { ?>
	<div class="single-blog-tag"><span ><?php esc_html_e('Tag :','college-education'); ?></span><?php echo '#'.get_the_tag_list('', esc_html__( ', #', 'college-education' )); ?></div>  
	<?php } ?>  
  <div class="single-blog-author"><i class="fa fa-user"></i>&nbsp;&nbsp;<?php echo esc_html(get_the_author()); ?></div>
  <div class="single-blog-date"><i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo esc_html(get_the_date()); ?></div>  
  <?php 
}

require_once get_template_directory() . '/functions/class-tgm-plugin-activation.php';
/*
* TGM plugin activation register hook 
*/
add_action( 'tgmpa_register', 'college_education_register_required_plugins' );
function college_education_register_required_plugins() {
    $plugins = array(
      array(
         'name'      => __('Page Builder by SiteOrigin','college-education'),
         'slug'      => 'siteorigin-panels',
         'required'  => false,
      ),
      array(
         'name'      => __('SiteOrigin Widgets Bundle','college-education'),
         'slug'      => 'so-widgets-bundle',
         'required'  => false,
      ), 
      array(
         'name'      => __('Contact Form 7','college-education'),
         'slug'      => 'contact-form-7',
         'required'  => false,
      ),   
    );
    $config = array(
      'id'           => 'college-education',
      'default_path' => '',
      'menu'         => 'tgmpa-install-plugins',
      'has_notices'  => true,
      'dismissable'  => true,
      'dismiss_msg'  => '',
      'is_automatic' => false,
      'message'      => '',
      'strings'      => array(
         'page_title'                      => __( 'Install Recommended Plugins', 'college-education' ),
         'menu_title'                      => __( 'Install Plugins', 'college-education' ),
         'installing'                      => __( 'Installing Plugin: %s', 'college-education' ), 
         'oops'                            => __( 'Something went wrong with the plugin API.', 'college-education' ),
         'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.','college-education' ), 
         'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.','college-education' ), 
         'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.','college-education' ), 
         'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.','college-education' ), 
         'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.','college-education' ), 
         'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.','college-education' ), 
         'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.','college-education' ), 
         'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.','college-education' ), 
         'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins','college-education' ),
         'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins','college-education' ),
         'return'                          => __( 'Return to Required Plugins Installer', 'college-education' ),
         'plugin_activated'                => __( 'Plugin activated successfully.', 'college-education' ),
         'complete'                        => __( 'All plugins installed and activated successfully. %s', 'college-education' ), 
         'nag_type'                        => 'updated'
      )
    );
    tgmpa( $plugins, $config );
}
require get_template_directory() . '/functions/enqueue-files.php';
require get_template_directory() . '/functions/theme-customization.php';