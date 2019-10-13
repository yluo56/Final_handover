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
            $raw_content = get_the_content();
            $content = apply_filters( 'the_content', $raw_content );

            $audio = false;
            // Only get audio from the content if a playlist isn't present.
            if ( false === strpos( $content, 'wp-playlist-script' ) ) {
                $audio = get_media_embedded_in_content( $content, array( 'audio' ) );
            }

            /*Get first word of content*/
            $first_word = substr($raw_content, 0, 1);
            /*only allow alphabets*/
            if(preg_match("/[A-Za-z]+/", $first_word) != TRUE){
                $first_word = '';
            }

        ?>
        <div class="entry-content twp-entry-content">
            <div class="twp-text-align">
                <?php 
                $read_more_text = esc_html(knight_get_option('read_more_button_text'));
                if ( ! empty( $audio ) ) {
                    foreach ( $audio as $audio_html ) {
                        echo '<div class="entry-audio">';
                        echo $audio_html;
                        echo '</div><!-- .entry-audio -->';
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
