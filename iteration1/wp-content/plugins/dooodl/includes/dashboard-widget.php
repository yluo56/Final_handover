<?php

function dooodl_show_dashboard_widget(){

	$args = array();
	$args['post_type'] = 'dooodl';
	$args['posts_per_page'] = 5;
	$args['orderby'] = 'date';
	$args['order'] = 'desc';

	$dooodls = new WP_Query($args);

	?>
	<div id="dooodl-activity" class="activity-block">
		<h4><?php _e('Recent activity', 'dooodl');?></h4>
	</div>
	<div class="dooodl-list">
		<?php
			if($dooodls->have_posts()){?>
				<ul>
					<?php while($dooodls->have_posts()){
						$dooodls->the_post();
						$is_approved = get_field('approved');
						$class="";

						if($is_approved == "yes"){
							$class ="doood-approved";
						}
						
						?>
						<li class="dooodl-item <?php echo $class; ?>">
							<img src="<?php echo dooodl_get_image_url(get_the_ID()); ?>" width="100" class="dooodl-img">
							<p class="dooodl-date">
								<span class="dashicons dashicons-calendar-alt"></span>
								<?php _e('Posted on', 'dooood');?>

								<span class='dooodl-date'>
									<?php echo get_the_date('j M Y'); ?>
								</span>
							</p>

							<p class="dooodl-state">
								<?php if($is_approved == "yes"){ ?>
									<span class="dooodl-approved dashicons dashicons-yes"></span><?php _e('Approved', 'dooodl'); ?>
								<?php } else { ?>
									<span class="dooodl-not-approved dashicons dashicons-no"></span><?php _e('Not yet approved', 'dooodl'); ?>
								<?php } ?>
							</p>


							<p class="dooodl-info">
								<span class="dashicons dashicons-admin-users"></span>
								<?php _e('by', 'dooood');?>
								<span class='dooodl-author'>
									<?php the_field('author_name'); ?>
								</span>
							</p>
							<p class="dooodl-description"><?php the_field('description'); ?></p>
						</li>
					<?php } ?>
				</ul>
			<?php } else{ ?>
				<p><?php _e("You haven't received any Dooodls yet... :( "); ?></p>
			<?php } ?>

		<a href="<?php echo admin_url('admin.php?page=dooodl-overview'); ?>"><?php _e('View all', 'dooodl');?></a>
	</div>
	<?php
}

function dooodl_add_dashboard_widget(){
	wp_add_dashboard_widget("dooodl-dashboard-widget", "Dooodl", "dooodl_show_dashboard_widget", false, false );
}



?>