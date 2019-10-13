<?php
$count = 9;

global $page;

$teller = 0;
$args = array();
$args['post_type'] = 'dooodl';
$args['posts_per_page'] = 12;
$args['orderby'] = 'date';
$args['order'] = 'desc';
$args['paged'] = $page;
$args['meta_query'] = array(
	array('key'=>'approved', 'value'=> 'yes', 'compare'=> '=')
);

$query = new WP_Query($args);


if($query->have_posts()){
	while($query->have_posts()){
			$query->the_post();

			$teller++;
			if($teller == 1){
				echo('<tr>');
			}

			$image_url 		= dooodl_get_image_url(get_the_ID(), array(250,250));
			$author_name 	= get_field("author_name", get_the_ID());
			$author_url 	= get_field("author_url", get_the_ID());
			$description 	= get_field("description", get_the_ID());
			$title 			= get_the_title();
			$date			= get_the_date();

			?>
				<td>

						<p class="title"><?php echo $title ?></p>
						<img src="<?php echo $image_url ?>"/>
						<p class="description">
							<?php echo $description ?>
						</p>

					<span class="date"><?php _e('Created:','dooodl'); ?> <?php echo $date ?></span><br />
					<span class="author">
						<?php _e('Author:', 'dooodl'); ?> <strong><?php echo $author_name ?></strong>
						<?php if($author_url != "http://"){ ?>
						  [ <a href="<?php echo $author_url ?>" target="_blank" rel="nofollow"><?php _e('Link', 'dooodl'); ?></a> ]
						<?php } ?>
					</span>
				</td>
			<?php
			if($teller==3){
				echo ('</tr>');
				$teller =0;
			}

	}
}

?>