<?php

function dooodl_show_overview_page(){

    $table = new Dooodl_Overview_Table();
   	$current_view = $table->get_view_label();
    $table->prepare_items();


    ?>

    <div class="wrap">

        <div id="icon-users" class="icon32"><br/></div>
        	<?php switch($current_view){
        		case "all": ?>
        		<h2><?php _e('All Dooodl Overview','dooodl'); ?></h2>
        		<div class="dooodl-wrapper">
		        	<p>
		        		<?php _e('On this page you can see all Dooodls that were created by your visitors.','dooodl'); ?></br>
		        		<?php _e('Here you can manage, moderate and remove them.','dooodl'); ?>
		        	</p>
	        	</div>

        	<?php break;
        		case "approved": ?>
        		<h2><?php _e('Approved Dooodls','dooodl'); ?></h2>
        		<div class="dooodl-wrapper">
	        		<p>
		        		<?php _e('On this page you can see all Dooodls that were approved and are now publicly visible on your site.','dooodl'); ?></br>
		        		<?php _e('Here you can remove or disapprove them.','dooodl'); ?>
		        	</p>
	        	</div>

        	<?php break;
        		case "moderation": ?>
        		<h2><?php _e('Dooodl Moderation Queue','dooodl'); ?></h2>
        		<div class="dooodl-wrapper">
	        		<p>
		        		<?php _e('On this page you can see all Dooodls that are in moderation or unnapproved.','dooodl'); ?></br>
		        		<?php _e('These Dooodls are not visible by your visitors. You can approve or delete these.','dooodl'); ?>
		        	</p>
	        	</div>

        	<?php break;
        		case "trash": ?>
        		<h2><?php _e('Trashed Dooodls :-(','dooodl'); ?></h2>
        		<div class="dooodl-wrapper">
	        		<p>
		        		<?php _e('Here you can see the Dooodls that were trashed.','dooodl'); ?></br>
		        		<?php _e('If you want to you can still restore them to the moderation queue or approve them for public viewing.','dooodl'); ?>
		        	</p>
	        	</div>

        	<?php break;
        	} ?>


        <!--
        <form method="post">
		 	<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
		  	<?php $table->search_box(__('Search Dooodls', 'dooodl'), 'search_id'); ?>
		</form>

		-->

        <form action="<?php echo admin_url("admin-post.php"); ?>" id="dooodl-list" method="post">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <?php $table->views(); ?>
            <?php $table->display(); ?>
        </form>

        <!-- Why the fuck is this necessary? -->
        <script type="text/javascript">
        	jQuery(function(){
        		var $action = jQuery('#bulk-action-selector-top');
        		var $action2 = jQuery('#bulk-action-selector-bottom');
        		$action2.change(function(){
        			$action.val($action2.val());
        		});
        	});
        </script>

    </div>

    <?php
}
?>