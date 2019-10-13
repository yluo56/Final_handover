<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Knight
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <?php
            if (is_front_page() || is_home()) {
            } else {
                do_action('knight_action_breadcrumb');
            }
            ?>
            <?php while (have_posts()) : the_post(); ?>

                <?php
                $format = get_post_format();
                $format = (false === $format) ? 'single' : $format;
                ?>
                <?php get_template_part('template-parts/content', $format); ?>

                <?php
                // Previous/next post navigation.
                the_post_navigation(array(
                    'next_text' => '<span class="screen-reader-text">' . esc_html__('Next post:', 'knight') . '</span> ' .
                        '<span class="post-title">%title</span>',
                    'prev_text' => '<span class="screen-reader-text">' . esc_html__('Previous post:', 'knight') . '</span> ' .
                        '<span class="post-title">%title</span>',
                ));
                ?>

                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>

            <?php endwhile; // End of the loop. ?>

        </main><!-- #main -->
    </div><!-- #primary -->
<?php
get_sidebar();
get_footer();
