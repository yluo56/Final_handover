<?php
/*
* Search Template File
*/
get_header(); ?>
<div class="heading-wrap blog-heading-wrap">
    <div class="heading-layer">
        <div class="heading-title">
            <h4><?php esc_html_e('404 - ARTICLE NOT FOUND', 'college-education'); ?></h4>
        </div>
    </div>
</div>
<div class="college-education-section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="blog-content-area fadeIn animated">
                    <div class="search-area">
                        <p class="spage">
                            <?php esc_html_e("This is embarassing. We can't find what you were looking for.",'college-education'); ?>
                            <br>
                            <?php esc_html_e('Whatever you were looking for was not found, but maybe try looking again or search using the form below.', 'college-education'); ?></p>
                        <?php get_search_form(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer();