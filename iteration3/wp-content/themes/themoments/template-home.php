<?php
/**
 * Template Name: Front Page 
 * The template used for displaying front page contents
 *
 * @package themoments
 */
get_header();

if( get_theme_mod( 'themoments-home-banner-display-setting', $default = true ) ) :

  $home_banner_title = get_theme_mod( 'themoments-home-banner-title-setting', esc_html__( 'The Moments', 'themoments' ) );
  $home_banner_desc = get_theme_mod( 'themoments-home-banner-description-setting', esc_html__( 'Theme For Photography', 'themoments' ) );
  $home_banner_link = get_theme_mod( 'themoments-home-banner-link-setting' );

?>

  <!-- Header Image -->
  <div class="banner-top">
      <?php if ( has_header_image() ) : ?>
          <img src="<?php echo esc_url( get_header_image() ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'title' ) ); ?>" class="img-responsive" />
      <?php endif; ?>
      <div class="banner-text wowload fadeInUp">
        <h1>
          <?php
            if( $home_banner_title )
              echo esc_html( $home_banner_title );
          ?>
        </h1>
        <h3>
          <?php
            if( $home_banner_desc )
              echo wp_kses_post( $home_banner_desc );
          ?>          
        </h3>
        <?php if( $home_banner_link ): ?>
          <a href="<?php echo esc_url( $home_banner_link ); ?>" class="btn btn-danger"><?php esc_html_e('Read More', 'themoments'); ?></a>
        <?php endif; ?>
      </div>
  </div>
  <!-- Header Image -->
<?php endif; ?>

<?php if( get_theme_mod( 'themoments-home-about-display-setting', $default = true ) ) : ?>

    <!-- welcome message -->
    <section class="welcome spacer wowload fadeInUp">
        <div class="container">
          <div class="inside-wrapper">
              <?php 
                $about_ID = get_theme_mod( 'themoments-home-about-page' );
                $post = get_post( $about_ID );
                setup_postdata( $post );
              ?>
              <div class="content-block">
                    <h3><?php the_title(); ?></h3>
                    <p><?php the_excerpt(); ?></p> 
                    <a href="<?php echo esc_url( get_permalink( $about_ID ) ); ?>" title="<?php esc_attr_e( 'Read More', 'themoments' ); ?>" class="btn btn-danger"><?php esc_html_e( 'Read More', 'themoments' ); ?></a>
              </div> 
              <?php $about_image = wp_get_attachment_image_src( get_post_thumbnail_id( $about_ID ), 'about-page' ); ?> 
              <?php if( ! empty( $about_image ) ) : ?>
                <div class="message">                  
                    <img src="<?php echo esc_url( $about_image[0] ); ?>" class="img-responsive">
                </div>
              <?php endif; ?>
          </div>
        </div>
    </section>
  <?php
      wp_reset_postdata();
  ?>
    <!-- welcome message -->
<?php endif; ?>



<?php if( get_theme_mod( 'themoments-home-slider-display-setting', $default = true ) ) : ?>
  <!-- theme-slider -->
  <section class="theme-slider  wowload fadeInUp">
    <div class="container">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

          <!-- Wrapper for slides -->
          <div class="carousel-inner" role="listbox">
            <?php             
                $sliderp[0] = get_theme_mod( 'themoments-home-slider-page-1' );
                $sliderp[1] = get_theme_mod( 'themoments-home-slider-page-2' );
                $sliderp[2] = get_theme_mod( 'themoments-home-slider-page-3' );
            
                $args = array (
                  'post_type' => 'page',
                  'post_per_page' => 3,
                  'post__in'         => $sliderp,
                  'orderby'           =>'post__in',
                );
             
              $loop = new WP_Query( $args );
             
              if ( $loop->have_posts() ) :
                $i=0;
              
                while ( $loop->have_posts() ) : $loop->the_post();
            ?>

            <div class="item <?php echo $i == 0 ? 'active' : ''; ?>">
                  <?php if ( has_post_thumbnail() ){
                    $arg =
                      array(
                        'class' => 'img-responsive',
                        'alt' => ''
                      );
                      the_post_thumbnail( 'slider', $arg );
                    } 
                  ?>
              <div class="slide-caption">
                  <div class="slide-caption-details">
                  <h2><?php the_title(); ?></h2>
                  <div class="summary"><?php the_excerpt(); ?></div>
                  <a href="<?php echo esc_url( get_permalink() ); ?>" class="btn btn-danger"><?php esc_html_e( 'Know More', 'themoments' ); ?></a>
                  </div>
              </div>
            </div> <!-- /.end of item -->
          
            <?php
              $i++;
              endwhile;
                wp_reset_postdata();  
              endif;                             
            ?> 
          </div>  <!-- /.end of carousel inner -->

          <!-- Controls -->
          <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"><i class="fa fa-angle-left"></i></span>
            <span class="sr-only"><?php esc_html_e( 'Previous', 'themoments' ); ?></span>
          </a>
          <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"><i class="fa fa-angle-right"></i></span>
            <span class="sr-only"><?php esc_html_e( 'Next', 'themoments' ); ?></span>
          </a>

        </div> <!-- /.end of carousel example -->
    </div>
  </section> <!-- /.end of section -->
  <!-- theme-slider -->
<?php endif; ?>

<?php if( get_theme_mod( 'feature-category-display-setting', $default = true ) ) : ?>
  <!-- post list  -->
  <section class="front-post-list post-list spacer">
      <div class="container">
        <div class="inside-wrapper">
            <?php
              $category_id = get_theme_mod( 'features_display' );
              $category_link = get_category_link( $category_id );
              $category = get_category( $category_id );
            ?>
            <?php
              if ( get_theme_mod( 'features_title' ) ) :
                $title = get_theme_mod( 'features_title' ); ?>
                <h4><?php echo esc_html( $title ); ?></h4>
              <?php endif; ?>  

            <div class="row">
              <?php
                  $args = array(
                      'cat' => $category_id
                  );
                  $loop = new WP_Query( $args );                                   
                  if ( $loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post();
              ?>
              <div class="col-sm-6">
                  <div class="post-block  wowload fadeInUp eq-blocks">
                    <?php if ( has_post_thumbnail() ) : ?>
                    <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" class="featured-image"><?php the_post_thumbnail( 'post-thumbs' ); ?></a>
                    <?php else : ?>
                    <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" class="featured-image"><img src="<?php echo get_template_directory_uri(); ?>/images/no-blog-thumbnail.jpg" title="<?php the_title_attribute(); ?>" alt="<?php the_title_attribute(); ?>" class="img-responsive" /></a>
                  <?php endif; ?>  
                  <!-- summary -->
                  <div class="summary text-center">
                    <?php $tags = wp_get_object_terms( $post->ID, 'post_tag' ); ?>
                    <div class="post-category">
                      <?php
                      if ( !empty( $tags ) )
                        foreach( $tags as $tag )
                            echo $tag->name." ";
                      ?>
                    </div>
                  <h4><a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
                  
                  </div>
                  <!-- summary -->
                  </div>
              </div>
          <?php
              endwhile;
                  wp_reset_postdata();
              endif;
          ?>
        </div>
        </div>
  <div class="text-center"><a href="<?php echo esc_url( $category_link ); ?>"><?php esc_html_e( 'View All', 'themoments' ); ?></a></div>
            </div>  <!-- /.end of container -->
  </section>  <!-- /.end of section -->
  <!-- post list  -->
<?php endif; ?>


<?php if( get_theme_mod( 'testimonial-category-display-setting', $default = true ) ) : ?>
  <!-- testimonial-services -->
  <?php
    $testimonial_id = get_theme_mod( 'testimonial_display' );
      $args = array(
          'cat' => $testimonial_id
        );
        $testimonials= new WP_Query( $args );
      if ( $testimonials->have_posts() ) :
  ?>
  <div class="testimonial spacer clearfix  wowload fadeInUp">
    <div class="container text-center">
      <div class="inside-wrapper">
        <?php
          if ( get_theme_mod( 'testimonial_title' ) ) :
            $title = get_theme_mod( 'testimonial_title' ); ?>
            <h4><?php echo esc_html( $title ); ?></h4>
          <?php endif; ?>
    <div id="carousel-testimonials" class="carousel slide testimonails" data-ride="carousel">
      <div class="carousel-inner">
    <?php $i = 0; ?>
    <?php while ( $testimonials->have_posts() ) : $testimonials->the_post(); ?>
        <div class="item<?php if ( $i == 0 )  echo ' active'; ?>">
      <?php $image= wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>
      <?php if( ! empty( $image ) ) : ?>
          <img alt="portfolio" src="<?php echo esc_url( $image[0] ); ?>" width="100" class="img-circle">
        <?php endif; ?>
          <p><?php the_content(); ?></p>      
          <h5><?php the_title(); ?></h5>
        </div>
    <?php $i++; endwhile; wp_reset_postdata(); ?>
    </div>

     <!-- Indicators -->
      <ol class="carousel-indicators">
    <?php $i = 0; ?>
    <?php while ( $testimonials->have_posts() ) : $testimonials->the_post(); ?>
        <li data-target="#carousel-testimonials" data-slide-to="<?php echo $i; ?>" <?php if ( $i == 0 ) echo 'class="active"'; ?>></li>
    <?php $i++; endwhile; wp_reset_postdata(); ?>
      </ol>
      <!-- Indicators -->
    </div>
    </div>
    </div> 
  </div> 
  <?php endif; ?>
  <!-- testimonial-services -->
<?php endif; ?>


<?php if( get_theme_mod( 'crew-category-display-setting', $default = true ) ) : ?>
  <!-- team -->
  <?php
    $crew_cat_id = get_theme_mod( 'crew_display' );
      $crew_args = array(
          'cat' => $crew_cat_id
        );
        $crew_mems = new WP_Query( $crew_args );
    
      if ( $crew_mems->have_posts() ) :
  ?>
  <div class="crewmembers spacer text-center">
    <div class="container">
      <?php
        if ( get_theme_mod( 'crew_title' ) ) :
          $title = get_theme_mod( 'crew_title' ); ?>
          <h4><?php echo esc_html( $title ); ?></h4>
        <?php endif; ?>
  <div class="row team">
  <?php while ( $crew_mems->have_posts() ) : $crew_mems->the_post(); ?>
      <div class="col-sm-2 col-xs-6">
      <?php $image= wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>
      <?php if( ! empty( $image ) ) : ?>
        <img src="<?php echo esc_url( $image[0] ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="img-responsive">
      <?php endif; ?>
        <h5><?php the_title(); ?></h5>            
      </div>
  <?php endwhile; wp_reset_postdata(); ?>
  </div> 
  </div>
  </div>
  <?php endif; ?>
  <!-- team -->
<?php endif; ?>



<?php if( get_theme_mod( 'client-category-display-setting', $default = true ) ) : ?>
  <!-- clients -->
  <?php
    $client_cat_id = get_theme_mod( 'client_display' );
      $client_args = array(
        'cat' => $client_cat_id,
        'posts_per_page' => 6
      );
      $client_mems = new WP_Query( $client_args );
    
    if ( $client_mems ->have_posts() ) :
  ?>

  <div class="clients spacer text-center  wowload fadeInUp">
    <div class="container">
    <?php
        if ( get_theme_mod( 'client_section_title' ) ) :
          $title = get_theme_mod( 'client_section_title' ); ?>
          <h4><?php echo esc_html( $title ); ?></h4>
        <?php endif; ?>
    <div class="row">
         <?php while ( $client_mems ->have_posts() ) : $client_mems ->the_post(); ?>
         <?php $image= wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
          if ( ! empty( $image ) ): ?>
            <div class=" col-sm-2 col-xs-6">          
              <img src="<?php echo esc_url( $image[0] ); ?>" alt="<?php esc_attr_e( 'Client', 'themoments' ); ?>"> 
            </div> 
      <?php endif; ?>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
    </div>
  </div>

  <?php endif; ?>
  <!-- clients -->
<?php endif; ?>

<?php get_footer(); ?>