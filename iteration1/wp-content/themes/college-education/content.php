<div class="blog-wrapper">
    <div class="">
        <div class="container">
            <div id="post-<?php the_ID(); ?>" <?php post_class( 'row responsive' ); ?>>
            <?php
                $blog_layout_class=(get_theme_mod('blogsidebar',3) == 1)?"9":((get_theme_mod('blogsidebar',3) == 2)?"9":"12");
                if(get_theme_mod('blogsidebar',3) == 1):
                        get_sidebar();
                 endif;
                ?> 
                <div class="col-md-<?php echo esc_attr($blog_layout_class); ?> col-sm-12 col-xs-12 content blog-layout">
                    <div class="blog-content-area">
                        <?php while ( have_posts() ) : the_post(); ?>
                            <div class="col-xs-12 fadeIn animated">
                                <div class="blog-content">
                                    <div class="col-md-5 col-sm-5 col-xs-12">
                                        <?php if ( has_post_thumbnail() ) : ?>
                                        <div class="blog-images">
                                            <a href="<?php the_permalink(); ?>" ><?php the_post_thumbnail( 'CollegeEducationThumbnailImage', array('class' => 'img-responsive') ); ?></a>
                                        </div>
                                        <?php else: ?>
                                        
                                        <div class="blog-images">
                                            <a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url(get_template_directory_uri()); ?><?php echo esc_url('/assets/images/no-image.jpg'); ?>"></a>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                        <div class="title-data fadeIn animated">
                                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                            <div class="blog-Meta-Tag">
                                            <?php if(get_theme_mod('blogMetaTag',1) == 1): ?>
                                                <?php college_education_tag_meta(); ?>
                                            <?php endif; ?>
                                        </div>
                                        </div>
                                        <?php if(get_theme_mod('blogPostExcerpt',1) == 1): ?> 
                                          <p><?php the_excerpt(); ?></p>
                                        <?php endif; ?> 
                                        <?php if(get_theme_mod('blogPostReadMore',1) == 1): ?> 
                                        <a href="<?php the_permalink();?>" class="btn-light"><?php esc_html_e('READ MORE','college-education'); ?></a>
                                        <?php endif; ?> 
                                    </div>
                                </div>
                            </div>
                        <?php endwhile;  ?> 
                        <?php the_posts_pagination( array(
                            'Previous' => __( 'Back', 'college-education' ),
                            'Next' => __( 'Onward', 'college-education' ),
                        ) );                        
                        ?>
                    </div>
                </div>
               <?php 
                if(get_theme_mod('blogsidebar',3) == 2):
                        get_sidebar();
                 endif;
                ?>
            </div>
        </div>
    </div>
</div>