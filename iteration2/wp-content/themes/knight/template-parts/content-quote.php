<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Knight
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if (is_single()) {
        $archive_div_class = "single-post";
    } else {
        $archive_div_class = "twp-article-wrapper";
    } ?>
    <div class="<?php echo esc_attr($archive_div_class); ?> content-no-image">
            <?php
            if(is_singular()){ ?>
                <div class="entry-content">
                    <?php if (has_post_thumbnail()) { ?>
                        <div class="post-featured-image post-thumb">
                            <?php echo (get_the_post_thumbnail(get_the_ID())); ?> 
                        <?php $pic_caption = get_the_post_thumbnail_caption(); 
                        if ($pic_caption) { ?>
                            <div class="img-copyright-info">
                                <p><?php echo esc_html($pic_caption); ?></p>
                            </div>
                        <?php
                        } ?>
                        </div>
                    <?php }
                the_content( sprintf(
                    wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                        __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'knight' ),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    get_the_title()
                ) );
                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'knight' ),
                    'after'  => '</div>',
                ) );
                echo "</div>";
            }else{
                $background_color_single_post = get_post_meta($post->ID, 'knight_background_color', true);
                $text_color_single_post = get_post_meta($post->ID, 'knight_text_color', true);
             ?>
            <div class="twp-knight-article" style="background-color: <?php echo $background_color_single_post; ?>; color: <?php  echo $text_color_single_post; ?>;">
                <div class="entry-content">
                    <a href="<?php the_permalink(); ?>">
                    <?php 
                    echo '<h2 class="entry-title quote-entry-title">'.esc_html(get_the_title()).'</h2>';
                    wp_link_pages( array(
                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'knight' ),
                        'after'  => '</div>',
                    ) );
                    ?>
                    </a>
                </div><!-- .entry-content -->
            </div>
            <?php } ?>
    </div>
</article>