<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Knight
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="twp-article-wrapper clearfix">
    <?php if (!is_single()) { ?>
            <?php
            if (get_post_gallery()) {
                echo '<div class="entry-gallery">';
                echo get_post_gallery();
                echo '</div>';
            } ?>
    <?php } else { ?>
        <div class="entry-content twp-entry-content">
            <div class="twp-text-align">
                <?php 
                $read_more_text = esc_html(knight_get_option('read_more_button_text'));
                the_content(sprintf(
                /* translators: %s: Name of current post. */
                    wp_kses($read_more_text, __('%s <i class="ion-ios-arrow-right read-more-right"></i>', 'knight'), array('span' => array('class' => array()))),
                    the_title('<span class="screen-reader-text">"', '"</span>', false)
                )); ?>
            </div>
        </div><!-- .entry-content -->
    <?php } ?>
        <?php if (!is_single()) { 
        $background_color_single_post = get_post_meta($post->ID, 'knight_background_color', true);
        $text_color_single_post = get_post_meta($post->ID, 'knight_text_color', true);
            ?>
            <div class="twp-knight-article" style="background-color: <?php echo $background_color_single_post; ?>; color: <?php  echo $text_color_single_post; ?>;">
                <header class="article-header">
                    <div class="entry-meta">
                        <?php knight_posted_details(); ?>
                        <span class="post-category primary-font">
                        <?php knight_entry_category(); ?>
                        </span>
                    </div>

                    <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>

                </header>
            </div>
        <?php } ?>
        <?php if (is_single()) { 
            /**
             * Hook knight_related_posts
             *
             * @hooked knight_get_related_posts
             */
            do_action('knight_related_posts');?>
            <div class="single-meta">
            <?php if(has_tag()) { ?>
                <div class="post-tags primary-bgcolor">
                    <?php knight_entry_tags(); ?>
                </div>
            <?php } ?>
            </div>
        <?php } ?>
    </div>
</article><!-- #post-## -->
