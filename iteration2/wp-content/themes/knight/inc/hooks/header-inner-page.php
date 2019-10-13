<?php
global $post;
if (!function_exists('knight_single_page_title')) :
    function knight_single_page_title()
    {
        global $post;
        $global_banner_image = get_header_image();
        // Check if single.
        if (is_singular()) {
            if (has_post_thumbnail($post->ID)) {
                $banner_image_single_post = get_post_meta($post->ID, 'knight-meta-checkbox', true);
                if ('yes' == $banner_image_single_post) {
                } else {
                    $banner_image_array = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'knight-header-image');
                    $global_banner_image = $banner_image_array[0];
                }
            }
        }
        ?>

        <div class="page-inner-title inner-banner primary-bgcolor data-bg"
             data-background="<?php echo esc_url($global_banner_image); ?>">
            <header class="entry-header">
                <div class="container">
                    <div class="row">
                        <div class="col-md-10">
                            <?php if (is_singular()) { ?>
                                <div class="entry-meta">
                                    <div class="inner-meta-info">
                                        <?php knight_posted_details(); ?>
                                        <span class="post-category primary-font">
                                            <?php knight_entry_category(); ?>
                                        </span>
                                    </div>
                                </div>
                                <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                            <?php } elseif (is_404()) { ?>
                                <h1 class="entry-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'knight'); ?></h1>
                            <?php } elseif (is_archive()) {
                                the_archive_title('<h1 class="entry-title">', '</h1>'); ?>
                                <?php the_archive_description('<div class="taxonomy-description">', '</div>');
                            } elseif (is_search()) { ?>
                                <h1 class="entry-title"><?php printf(esc_html__('Search Results for: %s', 'knight'), '<span>' . get_search_query() . '</span>'); ?></h1>
                            <?php } else { } ?>
                        </div>
                    </div>
                </div>
            </header><!-- .entry-header -->
            <div class="inner-header-overlay"></div>
        </div>

        <?php
    }
endif;
add_action('knight-page-inner-title', 'knight_single_page_title', 15);
