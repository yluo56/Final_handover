<?php
/*
* Single Post template file
*/
get_header();
?>
    <div class="heading-wrap blog-heading-wrap">
        <div class="heading-layer">
            <div class="heading-title">
                <h4><?php the_title(); ?></h4>
            </div>
        </div>
    </div>    
<div class="single-blog-wrapper">
    <div class="college-education-section">
        <div class="container">
            <div class="row responsive">
            <?php 
                $blog_layout_class=(get_theme_mod('blogsinglesidebar',3) == 1)?"9":((get_theme_mod('blogsinglesidebar',3) == 2)?"9":"12");
                if(get_theme_mod('blogsinglesidebar',3) == 1):
                        get_sidebar();
                 endif;
                ?>  
                <div class="col-md-<?php echo esc_attr($blog_layout_class); ?> col-sm-12 col-xs-12 content">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <div class="single-blog-content-area fadeIn animated">
                            <div class="single-blog-content">
                                <div class="title-data fadeIn animated">
                                    <h2><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h2>
                                    <?php if(get_theme_mod('blogSingleMetaTag',1) == 1): ?>
                                          <div class="single-post-meta">
                                            <?php college_education_tag_meta(); ?>
                                            </div>
                                    <?php endif; ?>
                                </div>
                                <?php if ( has_post_thumbnail() ) : ?>
                                 <div class="single-blog-images">
                                    <?php the_post_thumbnail( 'CollegeEducationThumbnailImage', array( 'class' => 'img-responsive') ); ?>
                                </div>
                                <?php endif;
                                    the_content(); 
                                    wp_link_pages( array(
                                    'before' => '<div class="page-links">' . esc_html__( 'Post:', 'college-education' ),
                                    'after'  => '</div>',
                                    ) );
                            ?>
                            </div>                                                      
                        </div>
                        <?php 
                        the_post_navigation( array(
                        'prev_text'          => '<i class="fa fa-arrow-left" aria-hidden="true"></i>'.esc_html__('Previous Post ', 'college-education' ),
                        'next_text'          => esc_html__( 'Next Post ', 'college-education' ) .'<i class="fa fa-arrow-right" aria-hidden="true"></i>',
                        'before_page_number' => '<span class="meta-nav screen-reader-text">'.esc_html__(' Page ', 'college-education' ).'</span>',
                        ) );
                        // If comments are open or we have at least one comment, load up the comment template.
                        if ( comments_open() || get_comments_number() ) :
                            comments_template();
                        endif;
                    endwhile; ?>
                </div>
                  <?php 
                if(get_theme_mod('blogsinglesidebar',3) == 2):
                        get_sidebar();
                 endif;
                ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>