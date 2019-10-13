<?php
/*
* Set up the content width value based on the theme's design.
*/

if (!function_exists('college_education_setup')):
	function college_education_setup() {
		global $content_width;
		if (!isset($content_width)) {
			$content_width = 870;
		}
		// Make college-education theme available for translation.
		load_theme_textdomain('college-education', get_template_directory() . '/languages');

		// Add RSS feed links to <head> for posts and comments.
		add_theme_support('automatic-feed-links');

		// register menu 
		register_nav_menus(array(
		    'primary' => __('Top Header Menu', 'college-education'),
		));
		// Featured image support
		add_theme_support('post-thumbnails');
		add_theme_support('custom-logo', array(
			'height' => 120,
			'width' => 120,
			'flex-height' => true,
			'flex-width' => true,
			'priority' => 11,
			'header-text' => array('img-responsive', 'site-description'),
		));
		add_image_size('CollegeEducationThumbnailImage', 840, 560, true);

		$college_education_defaults = array(
			'default-image'          => get_template_directory_uri().'/assets/images/college-education.jpeg',
			'width'                  => 0,
			'height'                 => 0,
			'flex-height'            => 1400,
			'flex-width'             => 800,
			'uploads'                => true,
			'random-default'         => false,
			'header-text'            => false,
			'default-text-color'     => '',
			'wp-head-callback'       => '',
			'admin-head-callback'    => '',
			'admin-preview-callback' => '',
		);
		register_default_headers( array(
		    'default-image' => array(
		        'url'           => get_template_directory_uri().'/assets/images/college-education.jpeg',
		        'thumbnail_url' => get_template_directory_uri().'/assets/images/college-education.jpeg',
		        'description'   => __( 'Default Header Image', 'college-education' )
		    ),
		) );
		add_theme_support('custom-header',$college_education_defaults);
		
		// Switch default core markup for search form, comment form, and commen, to output valid HTML5.
		add_theme_support('html5', array(
			'comment-form', 'comment-list',
		));
		// Add support for featured content.
		add_theme_support('featured-content', array(
			'featured_content_filter' => 'college_education_get_featured_posts',
			'max_posts' => 6,
		));
		// This theme uses its own gallery styles.
		add_filter('use_default_gallery_style', '__return_false');
		/* slug setup */
		add_theme_support('title-tag');
	}
endif; // college_education_setup
add_action('after_setup_theme', 'college_education_setup');

/* Add a pingback url auto-discovery header for singularly identifiable articles.*/
function college_education_pingback_header() {
  	if ( is_singular() && pings_open() ) {
 		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
 	}
}  
add_action( 'wp_head', 'college_education_pingback_header' );

add_action( 'admin_menu', 'college_education_admin_menu');
function college_education_admin_menu( ) {
    add_theme_page( __('Pro Feature','college-education'), __('College Education Pro','college-education'), 'manage_options', 'college-education-pro-buynow', 'college_education_pro_buy_now', 300 ); 
  
}
function college_education_pro_buy_now(){ ?>  
  <div class="college_education_pro_version">
  <a href="<?php echo esc_url('https://sigmathemes.com/wordpress-themes/college-education-pro/'); ?>" target="_blank">
    <img src ="<?php echo esc_url('https://d3mprvxplsf1mr.cloudfront.net/main/uploads/college-education-pro-feature.png') ?>" width="100%" height="auto" />
  </a>
</div>
<?php }

require get_template_directory() . '/functions/theme-default-setup.php';