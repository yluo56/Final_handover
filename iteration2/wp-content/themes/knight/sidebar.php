<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Knight
 */

if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside id="secondary" class="widget-area" role="complementary">
    <div class="theiaStickySidebar">
	    <div class="sidebar-wrapper">
	        <?php dynamic_sidebar('sidebar-1'); ?>
	    </div>
	</div>
</aside><!-- #secondary -->