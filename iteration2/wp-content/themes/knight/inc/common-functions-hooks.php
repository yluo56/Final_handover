<?php
if (!function_exists('knight_the_custom_logo')):
/**
 * Displays the optional custom logo.
 *
 * Does nothing if the custom logo is not available.
 *
 * @since Knight 1.0.0
 */
function knight_the_custom_logo() {
	if (function_exists('the_custom_logo')) {
		the_custom_logo();
	}
}
endif;

if (!function_exists('knight_body_class')):

/**
 * body class.
 *
 * @since 1.0.0
 */
function knight_body_class($knight_body_class) {
	global $post;
	$global_layout       = knight_get_option('global_layout');
	$input               = '';
	$home_content_status = knight_get_option('home_page_content_status');
	if (1 != $home_content_status) {
		$input = 'home-content-not-enabled';
	}
	// Check if single.
	if ($post && is_singular()) {
		$post_options = get_post_meta($post->ID, 'knight-meta-select-layout', true);
		if (empty($post_options)) {
			$global_layout = esc_attr(knight_get_option('global_layout'));
		} else {
			$global_layout = esc_attr($post_options);
		}
	}
	if ($global_layout == 'left-sidebar') {
		$knight_body_class[] = 'left-sidebar '.esc_attr($input);
	} elseif ($global_layout == 'no-sidebar') {
		$knight_body_class[] = 'no-sidebar '.esc_attr($input);
	} else {
		$knight_body_class[] = 'right-sidebar '.esc_attr($input);

	}
	return $knight_body_class;
}
endif;

add_action('body_class', 'knight_body_class');

add_action('knight_action_sidebar', 'knight_add_sidebar');

/**
 * Returns word count of the sentences.
 *
 * @since Knight 1.0.0
 */
if (!function_exists('knight_words_count')):
function knight_words_count($length = 25, $knight_content = null) {
	$length          = absint($length);
	$source_content  = preg_replace('`\[[^\]]*\]`', '', $knight_content);
	$trimmed_content = wp_trim_words($source_content, $length, '');
	return $trimmed_content;
}
endif;

if (!function_exists('knight_simple_breadcrumb')):

/**
 * Simple breadcrumb.
 *
 * @since 1.0.0
 */
function knight_simple_breadcrumb() {

	if (!function_exists('breadcrumb_trail')) {

		require_once get_template_directory().'/assets/libraries/breadcrumbs/breadcrumbs.php';
	}

	$breadcrumb_args = array(
		'container'   => 'div',
		'show_browse' => false,
	);
	breadcrumb_trail($breadcrumb_args);

}

endif;



if ( ! function_exists( 'knight_ajax_pagination' ) ) :
    /**
     * Outputs the required structure for ajax loading posts on scroll and click
     *
     * @since 1.0.0
     * @param $type string Ajax Load Type
     */
    function knight_ajax_pagination($type) {
        ?>
        <div class="load-more-posts" data-load-type="<?php echo esc_attr($type);?>">
            <a href="#" class="btn-link btn-link-load">
                <span class="ajax-loader"></span>
                <?php _e('Load More Posts', 'knight')?>
                <i class="ion-ios-arrow-right"></i>
            </a>
        </div>
        <?php
    }
endif;

if ( ! function_exists( 'knight_load_more' ) ) :
    /**
     * Ajax Load posts Callback.
     *
     * @since 1.0.0
     *
     */
    function knight_load_more() {

        check_ajax_referer( 'knight-load-more-nonce', 'nonce' );

        $output['more_post'] = false;
        $output['content'] = array();

        $args['post_type'] = ( isset( $_GET['post_type']) && !empty($_GET['post_type'] ) ) ? esc_attr( $_GET['post_type'] ) : 'post';
        $args['post_status'] = 'publish';
        $args['paged'] = (int) esc_attr( $_GET['page'] );

        if( isset( $_GET['cat'] ) && isset( $_GET['taxonomy'] ) ){
            $args['tax_query'] = array(
                array(
                    'taxonomy' => esc_attr($_GET['taxonomy']),
                    'field'    => 'slug',
                    'terms'    => array(esc_attr($_GET['cat'])),
                ),
            );
        }

        if( isset($_GET['search']) ){
            $args['s'] = esc_attr( $_GET['search'] );
        }

        if( isset($_GET['author']) ){
            $args['author_name'] = esc_attr( $_GET['author'] );
        }

        if( isset($_GET['year']) || isset($_GET['month']) || isset($_GET['day']) ){

            $date_arr = array();

            if( !empty($_GET['year']) ){
                $date_arr['year'] = (int) esc_attr($_GET['year']);
            }
            if( !empty($_GET['month']) ){
                $date_arr['month'] = (int) esc_attr($_GET['month']);
            }
            if( !empty($_GET['day']) ){
                $date_arr['day'] = (int) esc_attr($_GET['day']);
            }

            if( !empty($date_arr) ){
                $args['date_query'] = array($date_arr);
            }
        }

        $loop = new WP_Query( $args );
        if($loop->max_num_pages > $args['paged']){
            $output['more_post'] = true;
        }
        if ( $loop->have_posts() ):
            while ( $loop->have_posts() ): $loop->the_post();
                ob_start();
                get_template_part('template-parts/content', get_post_format());
                $output['content'][] = ob_get_clean();
            endwhile;wp_reset_postdata();
            wp_send_json_success($output);
        else:
            $output['more_post'] = false;
            wp_send_json_error($output);
        endif;
        wp_die();
    }
endif;
add_action( 'wp_ajax_knight_load_more', 'knight_load_more' );
add_action( 'wp_ajax_nopriv_knight_load_more', 'knight_load_more' );


if (!function_exists('knight_custom_posts_navigation')):
/**
 * Posts navigation.
 *
 * @since 1.0.0
 */
function knight_custom_posts_navigation() {

	$pagination_type = knight_get_option('pagination_type');

	switch ($pagination_type) {

		case 'default':
			the_posts_navigation();
			break;

		case 'numeric':
			the_posts_pagination();
			break;

        case 'infinite_scroll_load':
            knight_ajax_pagination('scroll');
            break;
        case 'button_click_load':
                knight_ajax_pagination('click');
                break;
		default:
			break;
	}

}
endif;

add_action('knight_action_posts_navigation', 'knight_custom_posts_navigation');

if (!function_exists('knight_excerpt_length') && !is_admin()):

/**
 * Excerpt length
 *
 * @since  Knight 1.0.0
 *
 * @param null
 * @return int
 */
function knight_excerpt_length($length) {
	$excerpt_length = knight_get_option('excerpt_length_global');
	if (empty($excerpt_length)) {
		$excerpt_length = $length;
	}
	return absint($excerpt_length);

}

add_filter('excerpt_length', 'knight_excerpt_length', 999);
endif;

if (!function_exists('knight_excerpt_more') && !is_admin()):

/**
 * Implement read more in excerpt.
 *
 * @since 1.0.0
 *
 * @param string $more The string shown within the more link.
 * @return string The excerpt.
 */
function knight_excerpt_more($more) {

	$flag_apply_excerpt_read_more = apply_filters('knight_filter_excerpt_read_more', true);
	if (true !== $flag_apply_excerpt_read_more) {
		return $more;
	}

	$output         = $more;
	$read_more_text = esc_html(knight_get_option('read_more_button_text'));
	if (!empty($read_more_text)) {
		$output = '<div class="continue-reading-wrapper"><a href="'.esc_url(get_permalink()).'" class="continue-reading">'.esc_html($read_more_text).'<i class="ion-ios-arrow-right"></i>'.'</a></div>';
		$output = apply_filters('knight_filter_read_more_link', $output);
	}
	return $output;

}

add_filter('excerpt_more', 'knight_excerpt_more');
endif;

if (!function_exists('knight_get_link_url')):

/**
 * Return the post URL.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @since 1.0.0
 *
 * @return string The Link format URL.
 */
function knight_get_link_url() {
	$content = get_the_content();
	$has_url = get_url_in_content($content);

	return ($has_url)?$has_url:apply_filters('the_permalink', get_permalink());
}

endif;

if (!function_exists('knight_fonts_url')):

/**
 * Return fonts URL.
 *
 * @since 1.0.0
 * @return string Fonts URL.
 */
function knight_fonts_url() {
	$fonts_url = '';
	$fonts     = array();

	$knight_primary_font   = knight_get_option('primary_font');
	$knight_secondary_font = knight_get_option('secondary_font');

	$knight_fonts   = array();
	$knight_fonts[] = $knight_primary_font;
	$knight_fonts[] = $knight_secondary_font;

	$knight_fonts_stylesheet = '//fonts.googleapis.com/css?family=';

	$i = 0;
	for ($i = 0; $i < count($knight_fonts); $i++) {

		if ('off' !== sprintf(_x('on', '%s font: on or off', 'knight'), $knight_fonts[$i])) {
			$fonts[] = $knight_fonts[$i];
		}

	}

	if ($fonts) {
		$fonts_url = add_query_arg(array(
				'family' => urldecode(implode('|', $fonts)),
			), 'https://fonts.googleapis.com/css');
	}

	return $fonts_url;
}

endif;

/*Recomended plugin*/
if (!function_exists('knight_recommended_plugins')):

/**
 * Recommended plugins
 *
 */
function knight_recommended_plugins() {
	$knight_plugins = array(
		array(
			'name'     => __('One Click Demo Import', 'knight'),
			'slug'     => 'one-click-demo-import',
			'required' => false,
		),
        array(
            'name'     => __( 'Social Share With Floating Bar', 'knight' ),
            'slug'     => 'social-share-with-floating-bar',
            'required' => false,
        ),
        array(
            'name'     => __( 'MailOptin – Popups, Email Optin Forms & Newsletters for MailChimp, Aweber etc.', 'knight' ),
            'slug'     => 'mailoptin',
            'required' => false,
        ),
	);
	$knight_plugins_config = array(
		'dismissable' => true,
	);

	tgmpa($knight_plugins, $knight_plugins_config);
}
endif;
add_action('tgmpa_register', 'knight_recommended_plugins');


function knight_archive_title( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
        $title = single_term_title( '', false );
    }

    return $title;
}

add_filter( 'get_the_archive_title', 'knight_archive_title' );


function knight_check_other_plugin() {
    // check for plugin using plugin name
    if (is_plugin_active('one-click-demo-import/one-click-demo-import.php')) {
        // Disable PT branding.
        add_filter('pt-ocdi/disable_pt_branding', '__return_true');
        //plugin is activated
        function ocdi_after_import_setup() {
            // Assign menus to their locations.
            $main_menu   = get_term_by('name', 'Primary Menu', 'nav_menu');
            $social_menu = get_term_by('name', 'social', 'nav_menu');

            set_theme_mod('nav_menu_locations', array(
                    'primary' => $main_menu->term_id,
                    'social'  => $social_menu->term_id,
                )
            );

            // Assign front page and posts page (blog page).
            $front_page_id = get_page_by_title('');
            $blog_page_id  = get_page_by_title('Blog');

            update_option('show_on_front', 'page');
            update_option('page_on_front', $front_page_id->ID);
            update_option('page_for_posts', $blog_page_id->ID);

        }
        add_action('pt-ocdi/after_import', 'ocdi_after_import_setup');
    }
}
add_action('admin_init', 'knight_check_other_plugin');


if ( class_exists( 'Walker_Nav_Menu' ) ) {
    // For main menu to generate mage menu related elements
    class Knight_Walker_Nav_Menu extends Walker_Nav_Menu {
        public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
            if ( ! $element ) {
                return;
            }
            $id_field = $this->db_fields['id'];
            $id       = $element->$id_field;

            //display this element
            $this->has_children = ! empty( $children_elements[$id] );
            if ( isset( $args[0] ) && is_array( $args[0] ) ) {
                $args[0]['has_children'] = $this->has_children; // Back-compat.
            }

            $cb_args = array_merge( array( &$output, $element, $depth ), $args );
            call_user_func_array( array( $this, 'start_el' ), $cb_args );

            // descend only when the depth is right and there are childrens for this element
            if ( ( $max_depth == 0 || ( $max_depth > $depth + 1 ) ) && isset( $children_elements[$id] ) ) {
                if ( ! $this->is_mega_category( $element, $depth ) ) {
                    foreach ( $children_elements[$id] as $child ) {
                        if ( ! isset( $newlevel ) ) {
                            $newlevel = true;
                            //start the child delimiter
                            $cb_args = array_merge( array( &$output, $depth ), $args );
                            call_user_func_array( array( $this, 'start_lvl' ), $cb_args );
                        }
                        $this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
                    }
                }
                unset( $children_elements[$id] );
            }
            if ( isset( $newlevel ) && $newlevel ) {
                //end the child delimiter
                $cb_args = array_merge( array( &$output, $depth ), $args );
                call_user_func_array( array( $this, 'end_lvl' ), $cb_args );
            }

            //end this element
            $cb_args = array_merge( array( &$output, $element, $depth ), $args );
            call_user_func_array( array( $this, 'end_el' ), $cb_args );
        }
        public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

            $classes = empty( $item->classes ) ? array() : ( array )$item->classes;

            // $depth starts from 0, so 2 means third level
            if ( $depth > 1 ) {
                if ( in_array( 'menu-item-has-children', $classes ) ) {
                    $item->classes = array_diff( $classes, array( 'menu-item-has-children' ) );
                }
            }
            // Support mega menu for first level only
            if ( $depth > 0 ) {
                if ( in_array( 'mega-menu', $classes ) ) {
                    $item->classes = array_diff( $classes, array( 'mega-menu' ) );
                }
            }
            // If is category and has its child category, add class menu-item-has-children
            if ( $this->is_mega_category( $item, $depth ) ) {
                $term_id = $item->object_id;
                $terms = get_terms( array( 'taxonomy' => 'category', 'parent' => $term_id ) );
                if ( ! is_wp_error( $terms ) && ( count( $terms ) > 0 ) ) {
                    $item->classes = array_merge( $classes, array( 'menu-item-has-children' ) );
                };
            }
            parent::start_el( $output, $item, $depth, $args, $id );
        }
        public function end_el( &$output, $item, $depth = 0, $args = array() ) {

            if ( $this->is_mega_category( $item, $depth ) ) { 
                $term_id = $item->object_id;
                $terms = get_terms( array( 'taxonomy' => 'category', 'parent' => $term_id ) );
                $ppp = ( ! is_wp_error( $terms ) && ( count( $terms ) > 0 ) ) ? 3 : 4;
                $query = new WP_Query( array( 'posts_per_page' => $ppp, 'cat' => $term_id, 'offset' => 0 ) );
                if ( $query->have_posts() ) {
                    $output .= '<ul class="sub-menu">';
                    if ( ! is_wp_error( $terms ) && ( count( $terms ) > 0 ) ) {
                        $tmpl = '<li class="sub-cat-list"><ul>%s</ul></li><li class="sub-cat-posts">%s</li>';
                        $cat_list = sprintf(
                            '<li class="current" data-id="cat-%s"><a href="%s">%s</a></li>',
                            $term_id,
                            get_term_link( intval( $term_id ), 'category' ),
                            esc_html__( 'All', 'knight' )
                        );
                        $post_list = $this->post_list( $query, '<div class="sub-cat current cat-' . $term_id . '"><ul>', '</ul></div>' );
                        foreach ( $terms as $t ) {
                            $term_id = $t->term_id;
                            $query = new WP_Query( array( 'posts_per_page' => $ppp, 'cat' => $term_id, 'offset' => 0 ) );
                            if ( $query->have_posts() ) {
                                $term_id = $t->term_id;
                                $cat_list .= sprintf( 
                                    '<li data-id="cat-%s"><a href="%s">%s</a></li>',
                                    $term_id,
                                    get_term_link( $t, 'category' ),
                                    $t->name
                                );
                                $post_list .= $this->post_list( $query, '<div class="sub-cat cat-' . $term_id . '"><ul>', '</ul></div>' );
                            }
                        } 
                        $output .= sprintf( $tmpl, $cat_list, $post_list );
                    } else {
                        $output .= $this->post_list( $query );
                    }
                    $output .= '</ul>';
                }
            }
            parent::end_el( $output, $item, $depth, $args );
        }   
        public function start_lvl( &$output, $depth = 0, $args = array() ) {

            if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }
            $indent = str_repeat( $t, $depth );
            $output .= "{$n}{$indent}<ul class=\"sub-menu\">{$n}";
        }
        private function is_mega_category( $item, $depth ) {
            return in_array( 'mega-menu', ( array )$item->classes ) && ( $depth == 0 ) && ( $item->object == 'category' );
        }
        private function post_list( $query, $before = '', $after = '' ) {
            ob_start();
            print( $before );
            while ( $query->have_posts() ) : $query->the_post();
                $has_thumbnail = has_post_thumbnail();
                $link = get_permalink(); ?>
                <li>
                    <div class="post mega-menu-post<?php if ( $has_thumbnail ) { echo ' has-post-thumbnail'; } ?>">
                        <?php if ( $has_thumbnail ) : ?>
                        <figure class="featured-img bg-image bg-image-2">
                            <?php the_post_thumbnail('medium'); ?>
                        </figure>
                        <?php endif; ?>
                        <div class="post-content">
                            <div class="post-header">
                                <p class="post-title">
                                    <a href="<?php echo esc_url( $link ); ?>"><?php the_title(); ?></a>
                                </p>
                            </div>
                            <?php $this->show_meta(); ?>
                        </div>
                    </div>
                </li> <?php
            endwhile; 
            wp_reset_postdata();
            print( $after );
            return ob_get_clean();
        }
        private function show_meta() {
            return '';
        }
    }

    // Waler class for fullscreen site header
    class Knight_Walker_Fullscreen_Nav_Menu extends Walker_Nav_Menu {
        /*
         * @description add a wrapper div
         * @param string $output Passed by reference. Used to append additional content.
         * @param int    $depth  Depth of menu item. Used for padding.
         * @param array  $args   An array of wp_nav_menu() arguments.
         */
        public function start_lvl( &$output, $depth = 0, $args = array() ) {
            $indent = str_repeat( "\t", $depth );
            $wrap = ( $args->theme_location === 'primary' ) 
                ? sprintf( '<button class="dropdown-toggle" aria-expanded="false"><span class="screen-reader-text">%s</span></button>', esc_html__( 'expand child menu', 'knight' ) ) : '';
            $output .=  "\n$indent$wrap<ul class=\"sub-menu\">\n";
        }
    }
}


/*related post*/
if (!function_exists('knight_get_related_posts')) :
    /*
     * Function to get related posts
     */
    function knight_get_related_posts()
    {
        global $post;

        //$options = knight_get_theme_options(); // get theme options

        $post_categories = get_the_category($post->ID); // get category object
        $category_ids = array(); // set an empty array

        foreach ($post_categories as $post_category) {
            $category_ids[] = $post_category->term_id;
        }

        if (empty($category_ids)) return;

        $qargs = array(
            'posts_per_page' => 5,
            'category__in' => $category_ids,
            'post__not_in' => array($post->ID),
            'order' => 'ASC',
            'orderby' => 'rand'
        );

        $related_posts = get_posts($qargs); // custom posts
        ?>
        <div class="related-articles">
            <header class="related-header">
                <h3 class="related-title widget-title bordered-widget-title">
                    <?php esc_html_e('You May Also Like', 'knight'); ?>
                </h3>
            </header>

            <div class="entry-content">
                <div class="row">
                    <?php foreach ($related_posts as $related_post) {
                        $post_title = get_the_title($related_post->ID);
                        $post_url = get_permalink($related_post->ID);
                        $post_date = get_the_date('', $related_post->ID);
                        $post_author = get_the_author_meta( 'display_name', $related_post->ID );
                        $posts_categories = get_the_category($related_post->ID);
                        ?>
                        <div class="col-sm-12">
                            <div class="suggested-article">
                                <?php if (has_post_thumbnail($related_post->ID)) {
                                    $img_array = wp_get_attachment_image_src(get_post_thumbnail_id($related_post->ID), 'medium'); ?>
                                  <div class="post-image">
                                      <a href="<?php echo esc_url($post_url); ?>" class="bg-image bg-image-1">
                                          <img src="<?php echo esc_url($img_array[0]); ?>" alt="<?php echo esc_attr($post_title); ?>">
                                      </a>
                                  </div>
                                <?php } ?>
                                <div class="related-content">
                                    <div class="related-article-title">
                                        <h4 class="entry-title entry-title-small">
                                            <a href="<?php echo esc_url($post_url); ?>"><?php echo wp_kses_post($post_title); ?></a>
                                        </h4>
                                    </div>
                                    <div class="entry-meta small-font primary-font">
                                        <?php echo esc_html('Posted On : ','knight').$post_date; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
    }
endif;
add_action('knight_related_posts', 'knight_get_related_posts');


/**
 * A get_post_gallery() polyfill for Gutenberg
 *
 * @param string $gallery The current gallery html that may have already been found (through shortcodes).
 * @param int $post The post id.
 * @return string The gallery html.
 */
function knight_get_post_gallery( $gallery, $post ) {
    // Already found a gallery so lets quit.
    if ( $gallery ) {
        return $gallery;
    }
    // Check the post exists.
    $post = get_post( $post );
    if ( ! $post ) {
        return $gallery;
    }
    // Not using Gutenberg so let's quit.
    if ( ! function_exists( 'has_blocks' ) ) {
        return $gallery;
    }
    // Not using blocks so let's quit.
    if ( ! has_blocks( $post->post_content ) ) {
        return $gallery;
    }
    /**
     * Search for gallery blocks and then, if found, return the html from the
     * first gallery block.
     *
     * Thanks to Gabor for help with the regex:
     * https://twitter.com/javorszky/status/1043785500564381696.
     */
    $pattern = "/<!--\ wp:gallery.*-->([\s\S]*?)<!--\ \/wp:gallery -->/i";
    preg_match_all( $pattern, $post->post_content, $the_galleries );
    // Check a gallery was found and if so change the gallery html.
    if ( ! empty( $the_galleries[1] ) ) {
        $gallery = reset( $the_galleries[1] );
    }
    return $gallery;
}
add_filter( 'get_post_gallery', 'knight_get_post_gallery', 10, 2 );