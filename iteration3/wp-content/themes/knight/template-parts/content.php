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
    <?php
    $div_class = '';
    if (has_post_thumbnail()){
        $div_class = '';
    } else {
        $div_class = 'content-no-image';
    }?>
    <div class="twp-article-wrapper <?php echo esc_attr($div_class); ?>">
        <?php if (!is_single()) {
        $background_color_single_post = get_post_meta($post->ID, 'knight_background_color', true);
        $text_color_single_post = get_post_meta($post->ID, 'knight_text_color', true);
        ?>
             <?php if (has_post_thumbnail()) { ?>
                 <div class='twp-content-image'>
                     <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                         <?php the_post_thumbnail('knight-archive-post'); ?>
                     </a>
                 </div>
             <?php } ?>
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
                <div class="entry-content">
                    <div class="twp-content-details">
                        <?php the_excerpt(); ?>
                    </div>
                </div><!-- .entry-content -->
            </div>

        <?php } else { ?>
            <div class="entry-content">
                <?php
                $image_values = get_post_meta($post->ID, 'knight-meta-image-layout', true);
                if (empty($image_values)) {
                    $values = esc_attr(knight_get_option('single_post_image_layout'));
                } else {
                    $values = esc_attr($image_values);
                }
                if ('no-image' != $values) {
                if ('left' == $values) {
                echo "<div class='image-left'>"; ?>
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                    <?php the_post_thumbnail('full');
                    } elseif ('right' == $values) {
                    echo "<div class='image-right'>"; ?>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                        <?php the_post_thumbnail('full');
                        } else {
                        echo "<div class='image-full'>"; ?>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                            <?php the_post_thumbnail('full');
                            }
                            echo "</a></div>";/*div end */
                            }
                            ?>
                            <div class="twp-text-align">
                                <?php the_content(); ?>
                            </div>
                            <?php
                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'knight'),
                                'after' => '</div>',
                            ));
                            ?>
            </div><!-- .entry-content -->
        <?php } ?>
    </div>
</article><!-- #post-## -->
