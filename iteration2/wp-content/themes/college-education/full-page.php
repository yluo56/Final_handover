<?php
/**
* Template Name: Full Width
**/
get_header();
$singlepagetitle = 1; 
if(!is_front_page() && is_page())
{  $pagetitle = get_theme_mod('pagetitle',1); 
    if($pagetitle==1): $singlepagetitle =1; else: $singlepagetitle =0; endif;
}
if(is_home()):
    $pagetitle = get_theme_mod('pagetitle',1); 
    if($pagetitle==1): $singlepagetitle =1; else: $singlepagetitle =0;     endif;
endif;

if( $singlepagetitle == 1 && !is_front_page()) : ?> 
<div class="heading-wrap blog-heading-wrap">
    <div class="heading-layer">
        <div class="heading-title"><h4><?php the_title(); ?></h4></div>
    </div>
</div>    
<?php endif;  ?>  
    <div class="container">
        <div class="row">         
            <div class="col-md-12 col-sm-12 col-xs-12 blog-article">
                <?php while ( have_posts() ) : the_post(); ?>
                        <?php the_content();
                        wp_link_pages( array(
                               'before' => '<div class="page-links">' . esc_html__( 'Page:', 'college-education' ),
                               'after'  => '</div>',
                            ) );
                        ?>
                <?php endwhile;  ?> 
            </div>            
        </div>
    </div>
<?php get_footer(); ?>