<?php
if (!function_exists('knight_banner_slider_args')) :
    /**
     * Banner Slider Details
     *
     * @since Knight 1.0.0
     *
     * @return array $qargs Slider details.
     */
    function knight_banner_slider_args()
    {
        $knight_banner_slider_number = absint(knight_get_option('number_of_home_slider'));
        $knight_banner_slider_from = esc_attr(knight_get_option('select_slider_from'));
        switch ($knight_banner_slider_from) {
            case 'from-page':
                $knight_banner_slider_page_list_array = array();
                for ($i = 1; $i <= $knight_banner_slider_number; $i++) {
                    $knight_banner_slider_page_list = knight_get_option('select_page_for_slider_' . $i);
                    if (!empty($knight_banner_slider_page_list)) {
                        $knight_banner_slider_page_list_array[] = absint($knight_banner_slider_page_list);
                    }
                }
                // Bail if no valid pages are selected.
                if (empty($knight_banner_slider_page_list_array)) {
                    return;
                }
                /*page query*/
                $qargs = array(
                    'posts_per_page' => absint($knight_banner_slider_number),
                    'orderby' => 'post__in',
                    'post_type' => 'page',
                    'post__in' => absint($knight_banner_slider_page_list_array),
                );
                return $qargs;
                break;

            case 'from-category':
                $knight_banner_slider_category = absint(knight_get_option('select_category_for_slider'));
                $qargs = array(
                    'posts_per_page' => absint($knight_banner_slider_number),
                    'post_type' => 'post',
                    'cat' => absint($knight_banner_slider_category),
                );
                return $qargs;
                break;

            default:
                break;
        }
        ?>
        <?php
    }
endif;


if (!function_exists('knight_banner_slider')) :
    /**
     * Banner Slider
     *
     * @since Knight 1.0.0
     *
     */
    function knight_banner_slider()
    {
        $knight_slider_button_text = esc_html(knight_get_option('button_text_on_slider'));
        $knight_slider_layout = esc_attr(knight_get_option('slider_section_layout'));
        $knight_slider_excerpt_number = absint(knight_get_option('number_of_content_home_slider'));
        if (1 != knight_get_option('show_slider_section')) {
            return null;
        }
        $knight_banner_slider_args = knight_banner_slider_args();
        $knight_banner_slider_query = new WP_Query($knight_banner_slider_args); ?>
        <section class="twp-slider-wrapper">
            <div class="container container-sm">
                <?php $rtl_class_c = 'false';
                if(is_rtl()){ 
                    $rtl_class_c = 'true';
                }?>
                <div class="twp-slider-1 <?php echo esc_attr($knight_slider_layout); ?> " data-slick='{"rtl": <?php echo($rtl_class_c); ?>}'>
                    <?php
                    if ($knight_banner_slider_query->have_posts()) :
                        while ($knight_banner_slider_query->have_posts()) : $knight_banner_slider_query->the_post();
                            if (has_excerpt()) {
                                $knight_slider_content = get_the_excerpt();
                            } else {
                                $knight_slider_content = knight_words_count($knight_slider_excerpt_number, get_the_content());
                            }
                            ?>
                            <div class="single-slide">
                                <?php if (has_post_thumbnail()) {
                                    $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
                                    $url = $thumb['0'];  ?>
                                    <div class="slide-bg bg-image animated">
                                        <img src="<?php echo esc_url($url); ?>">
                                    </div>
                                <?php } ?>
                                <div class="slide-text animated secondary-textcolor">
                                    <div class="table-align">
                                        <div class="table-align-cell v-align-bottom">
                                            <h2 class="slide-title"><?php the_title(); ?></h2>
                                            <?php if ($knight_slider_excerpt_number != 0) { ?>
                                                <p class="visible hidden-xs hidden-sm"><?php echo wp_kses_post($knight_slider_content); ?></p>
                                            <?php } ?>
                                            <a href="<?php the_permalink(); ?>" class="btn-link btn-link-primary">
                                                <?php echo esc_html($knight_slider_button_text); ?> <i class="ion-ios-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    endif; ?>
                </div>
            </div>
        </section>
        <!-- end slider-section -->
        <?php
    }
endif;
add_action('knight_action_slider_post', 'knight_banner_slider', 10);