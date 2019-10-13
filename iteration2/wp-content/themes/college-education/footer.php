<?php
/*
* College Education default footer file
*/
$footer_widget_style = get_theme_mod('footerWidgetStyle',3);
$hide_footer_widget_bar = get_theme_mod('hideFooterWidgetBar',1);
$hide_credit_links = get_theme_mod('hideCreditLinks'); ?>

    <footer>
        <?php if(($hide_footer_widget_bar == 1) || ($hide_footer_widget_bar == '')) : 
            $footer_widget_style = $footer_widget_style+1;
        $footer_column_value = floor(12/($footer_widget_style)); ?>
            <div class="footer-box">
                <div class="container">
                    <div class="row">
                        <?php $k = 1; ?>
                        <?php for( $i=0; $i<$footer_widget_style; $i++) { ?>
                            <?php if (is_active_sidebar('footer-'.$k)) { ?>
                                <div class="col-md-<?php echo esc_attr($footer_column_value); ?> col-sm-<?php echo esc_attr($footer_column_value); ?> col-xs-12"><?php dynamic_sidebar('footer-'.$k); ?></div>
                            <?php }
                            $k++;
                        } ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if(get_theme_mod('hideCopyrightArea',1) != 2) : ?>
            <div class="footer-wrap style1">
                <div class="container">
                    <div class="college-education-section">
                        <div class="row">
                            <div class="col-md-4 col-xs-12 col-sm-4">
                                <div class="copyright fadeIn animated">
                                    <p><?php echo wp_kses_post(get_theme_mod('CopyrightAreaText')); ?></p>
                                    <?php if($hide_credit_links != 2) : ?>                                    
                                        <p><?php esc_html_e('Powered by ','college-education');?><a href="<?php echo esc_url('https://sigmathemes.com/wordpress-themes/college-education'); ?>" target="_blank"><?php esc_html_e('College Education WordPress Theme','college-education'); ?></a></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12 col-sm-4">
                                <?php if(get_theme_mod('footerLogo') != '') : ?>
                                    <div class="footer-logo fadeIn animated">
                                        <?php $footer_logo=get_theme_mod('footerLogo'); $footer_logo=wp_get_attachment_url($footer_logo);?>
                                        <img class="img-responsive" src="<?php echo esc_url($footer_logo); ?>" alt="<?php esc_attr_e('Footer Logo','college-education');?>">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4 col-xs-12 col-sm-4">
                                <div class="footer-social-icon fadeIn animated">
                                    <ul>
                                        <?php for($i=1; $i<=6; $i++) : ?>
                                        <?php if(get_theme_mod('CollegeEducationSocialIcon'.$i) != '' && get_theme_mod('CollegeEducationSocialIconLink'.$i) != '' ): ?>
                                            <li>
                                                <a href="<?php echo esc_url(get_theme_mod('CollegeEducationSocialIconLink'.$i)); ?>" class="fb" title="" target="_blank">
                                                    <i class="fab <?php echo esc_attr(get_theme_mod('CollegeEducationSocialIcon'.$i)); ?>"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php endfor; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </footer>
<?php wp_footer(); ?>
</body>
</html>