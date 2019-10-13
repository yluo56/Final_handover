<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package themoments
 */

?>

	<!-- Tab to top scrolling -->
	
	<div class="widget-main clearfix">
		<div class="container">
		  <div class="popular-news"><?php dynamic_sidebar( 'footer-1' ); ?></div>
		</div>
	</div>

	
	<footer>
		<?php
            wp_nav_menu( array(
                'theme_location'    => 'secondary',
                'container'         => 'div',
                'menu_class'        => 'list-inline',
                 'fallback_cb'      => 'wp_bootstrap_navwalker::fallback',
                'walker'            => new Themoments_wp_bootstrap_navwalker())
            );
        ?>

		<div class="copyright">
            <?php esc_html_e( "Powered by", 'themoments' ); ?> <a href="<?php echo esc_url( 'http://wordpress.org/' ); ?>"><?php esc_html_e( "WordPress", 'themoments' ); ?></a> | <?php esc_html_e( 'Theme by', 'themoments' ); ?> <a href="<?php echo esc_url( 'http://thebootstrapthemes.com/' ); ?>"><?php esc_html_e( 'The Bootstrap Themes','themoments' ); ?></a>
        </div>
	</footer>

    <?php 
        $social = array();
        $social['facebook'] = get_theme_mod( 'facebook_textbox' );
        $social['twitter'] = get_theme_mod( 'twitter_textbox' );
        $social['google-plus'] = get_theme_mod( 'googleplus_textbox' );
        $social['youtube-play'] = get_theme_mod( 'youtube_textbox' );
        $social['linkedin'] = get_theme_mod( 'linkedin_textbox' );
        $social['pinterest'] = get_theme_mod( 'pinterest_textbox' );
        $social['instagram'] = get_theme_mod( 'instagram_textbox' );
        $social = array_filter( $social );
    ?>
	     
    <div class="social-icons">
        <ul>
            <?php foreach ( $social as $key => $value ) { ?>
                <li class="<?php echo $key; ?>"><a href="<?php echo esc_url( $value ); ?>" target="_blank"><i class="fa fa-<?php echo $key; ?>"></i></a></li>
            <?php } ?>
    	</ul>
	</div>
        

<div class="scroll-top-wrapper"> <span class="scroll-top-inner"><i class="fa fa-2x fa-angle-up"></i></span></div> 
		
		<?php wp_footer(); ?>
	</body>
</html>