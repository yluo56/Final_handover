<?php
if (!function_exists('knight_intro_section')) :
    /**
     * Intro Section
     *
     * @since knight 1.0.0
     *
     */
    function knight_intro_section()
    {
        if (1 != knight_get_option('show_intro_section_section')) {
            return null;
        }
    ?>
    <section class="section-landing section-intro">
        <div class="container">
            <div class="row">
                <?php for ($i=1; $i <= 3; $i++) { ?>
                    <div class="col-md-4 col-sm-4">
                        <div class="image-block bg-image bg-image-3 twp-image-effect">
                            <img src="<?php echo esc_url(knight_get_option('intro_section_image_'.$i)); ?>">
                                <h3>
                                    <a href="<?php echo esc_url(knight_get_option('url_for_intro_'.$i)); ?>">
                                        <span><?php echo esc_html(knight_get_option('title_for_intro_'.$i)); ?></span>
                                    </a>
                                </h3>
                        </div>
                    </div>
                <?php  } ?>
            </div>
        </div>
    </section>
    <?php }
endif;
add_action('knight_action_intro_post', 'knight_intro_section', 20);