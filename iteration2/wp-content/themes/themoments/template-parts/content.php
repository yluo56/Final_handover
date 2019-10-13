<?php
/**
 * Template part for displaying posts.
 *
 * @package themoments
 */
?>

<div class="col-sm-6">
    <div class="post-block  eq-blocks  wowload fadeInUp">
    <?php if ( has_post_thumbnail() ) : ?>
        <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php the_post_thumbnail('post-thumbs'); ?></a>
    <?php endif; ?>  
    <div class="summary">
                        <h4><a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php the_title(); ?></a></h4>

    <small class="date"><?php the_date(); ?></small>

        <?php the_excerpt(); ?>
        
        <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" title="" class="readmore"><?php esc_html_e('Read More','themoments'); ?> </a>

    </div>
</div>
</div>



