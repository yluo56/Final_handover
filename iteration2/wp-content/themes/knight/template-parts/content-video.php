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

    <div class="twp-article-wrapper content-no-image">
    <?php if (!is_single()) { ?>
        <?php         
            $content = apply_filters( 'the_content', get_the_content() );
            $video = false;

            // Only get video from the content if a playlist isn't present.
            if ( false === strpos( $content, 'wp-playlist-script' ) ) {
                $video = get_media_embedded_in_content( $content, array( 'video', 'object', 'embed', 'iframe' ) );
            }
        ?>
        <div class="entry-content twp-entry-content">
            <div class="twp-text-align">
                <?php 
                $background_color_single_post = get_post_meta($post->ID, 'knight_background_color', true);
                $text_color_single_post = get_post_meta($post->ID, 'knight_text_color', true);
                $read_more_text = esc_html(knight_get_option('read_more_button_text'));
                if ( ! empty( $video ) ) {
                    foreach ( $video as $video_html ) {
                        echo $video_html;
                    }
                };
                sprintf(
                /* translators: %s: Name of current post. */
                    wp_kses($read_more_text, __('%s <i class="ion-ios-arrow-right read-more-right"></i>', 'knight'), array('span' => array('class' => array()))),
                    the_title('<span class="screen-reader-text">"', '"</span>', false)
                );?>
            </div>
        </div><!-- .entry-content -->
        <div class="twp-knight-article" style="background-color: <?php echo $background_color_single_post; ?>; color: <?php  echo $text_color_single_post; ?>;">
            <header class="article-header">
                <div class="entry-meta">
                    <?php knight_posted_details(); ?>
                    <span class="post-category primary-font">
                    <?php knight_entry_category(); ?>
                    </span>
                </div>
                <h2 class="entry-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h2>
            </header>
        </div>

    <?php } else {?>
        <div class="entry-content twp-entry-content">
            <div class="twp-text-align">
                <?php 
                the_content(); ?>
            </div>
        </div><!-- .entry-content -->
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
