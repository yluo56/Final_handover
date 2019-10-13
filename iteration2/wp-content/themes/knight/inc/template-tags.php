<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Knight
 */

if (!function_exists('knight_posted_details')) :
    /**
     * Prints HTML with meta information for the current post-date/time and author.
     */
    function knight_posted_details()
    {
        global $post;
        $author_id = $post->post_author;
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf($time_string,
            esc_attr(get_the_date('c')),
            esc_html(get_the_date()),
            esc_attr(get_the_modified_date('c')),
            esc_html(get_the_modified_date())
        ); 
        $avatar = get_avatar( get_the_author_meta( 'ID' ), 100, '', '', array( 'class' => 'byline' ) );
        if( $avatar !== false )
        {
            $avatar_img = $avatar;
        }
        $byline = sprintf(
            esc_html__( 'By %s', 'knight' ),
            '<a class="url" href="' . esc_url(get_author_posts_url($author_id)) . '">' . esc_html(get_the_author_meta('display_name', $author_id)) . '</a>'
        );
        $archive_year  = get_the_time('Y'); 
        $archive_month = get_the_time('m'); 
        $archive_day   = get_the_time('d'); 
        $posted_on = sprintf(
            esc_html__( ' %s', 'knight' ),
            '<a href="' . esc_url(get_day_link( $archive_year, $archive_month, $archive_day)) . '" rel="bookmark">' . $time_string . '</a>'
        );


        echo '<span class="author primary-font"> ' .$avatar_img .' '.$byline . '</span><span class="posted-on primary-font">' . $posted_on . '</span>'; // WPCS: XSS OK.
    }
endif;

if (!function_exists('knight_entry_category')) :
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function knight_entry_category()
    {
        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list(esc_html__('/', 'knight'));
            if ($categories_list && knight_categorized_blog()) {
                printf(esc_html__(' %1$s', 'knight'), $categories_list);
            }
        }
    }
endif;

if (!function_exists('knight_entry_tags')) :
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function knight_entry_tags()
    {
        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list('Tagged: ', esc_html__('/', 'knight'));
            if ($tags_list) {
                printf('<span class="tags-links"> ' . esc_html__('Tagged: %1$s', 'knight') . '</span>', $tags_list); // WPCS: XSS OK.
            }
        }
    }
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function knight_categorized_blog()
{
    if (false === ($all_the_cool_cats = get_transient('knight_categories'))) {
        // Create an array of all the categories that are attached to posts.
        $all_the_cool_cats = get_categories(array(
            'fields' => 'ids',
            'hide_empty' => 1,
            // We only need to know if there is more than one category.
            'number' => 2,
        ));

        // Count the number of categories that are attached to the posts.
        $all_the_cool_cats = count($all_the_cool_cats);

        set_transient('knight_categories', $all_the_cool_cats);
    }

    if ($all_the_cool_cats > 1) {
        // This blog has more than 1 category so knight_categorized_blog should return true.
        return true;
    } else {
        // This blog has only 1 category so knight_categorized_blog should return false.
        return false;
    }
}

/**
 * Flush out the transients used in knight_categorized_blog.
 */
function knight_category_transient_flusher()
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    // Like, beat it. Dig?
    delete_transient('knight_categories');
}

add_action('edit_category', 'knight_category_transient_flusher');
add_action('save_post', 'knight_category_transient_flusher');
