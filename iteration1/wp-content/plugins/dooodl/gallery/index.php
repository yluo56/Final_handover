<?php
	header('HTTP/1.1 200 OK');

	do_action('dooodl_gallery');

	$args = array();
	$args['post_type'] = 'dooodl';
	$args['posts_per_page'] = -1;
	$args['orderby'] = 'date';
	$args['order'] = 'desc';
	$args['meta_query'] = array(
		array('key'=>'approved', 'value'=> 'yes', 'compare'=> '=')
	);

	$query = new WP_Query($args);

	$theCount = $query->found_posts;

	$args['posts_per_page'] = 12;

	$query = new WP_Query($args);




?><!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Dooodl Viewer</title>

<?php wp_head(); ?>

<?php
	$bodyColor = $dooodl_options['gallery_body_bg_color'];
	$tdColor = $dooodl_options['gallery_dooodl_item_bg_color'];

	$linkBGColor = $dooodl_options['gallery_link_bg_color'];
	$linkTextColor = $dooodl_options['gallery_link_color'];

	$titleTextColor = $dooodl_options['gallery_title_color'];

	$fontColor = $dooodl_options['gallery_text_color'];
	$item_title_color = $dooodl_options['gallery_dooodl_item_title_color'];

	$backgroundColor = $dooodl_options['gallery_title_bg_color'];
	$introBackgroundColor = $dooodl_options['gallery_intro_bg_color'];
	$introTextColor = $dooodl_options['gallery_intro_color'];

	$gallery_custom_css = $dooodl_options['gallery_custom_css'];

?>
<style type="text/css">
	a:link, a:visited, a:active, span.theCount{
		color:<?php echo $linkTextColor; ?>;
		background-color:<?php echo $linkBGColor; ?>;
	}
	a:hover{
		text-decoration:underline;
	}
	body{
		background-color:<?php echo $bodyColor; ?>;
	}
	p.title{
		background-color:<?php echo $backgroundColor; ?>;
		color:<?php echo $item_title_color; ?>;
	}
	#intro{
		color:<?php echo $introTextColor; ?>;
		background-color:<?php echo $introBackgroundColor; ?>;
	}
	table#theList tr td{
		background-color:<?php echo $tdColor; ?>;
		color:<?php echo $fontColor; ?>;
	}
	h1{
		color:<?php echo $titleTextColor; ?>;
		background-color:<?php echo $backgroundColor; ?>;
	}

	<?php print($gallery_custom_css); ?>
</style>
</head>
<body>
	<div id="intro">
    	<h1>Dooodl History Viewer!!</h1>
	    <p>
	        Welcome to the <strong>Dooodl History Viewer</strong>! <br />
	        This is the complete collection of what people have created using the <a href="<?php echo dooodl_create_url('creator'); ?>">Dooodl creator</a> on my blog!<br />
	        Visitors of this blog already created <span class="theCount"><?= $theCount ?></span> Dooodls!! Feeling creative? <a href="<?php echo dooodl_create_url('creator'); ?>">Add your own</a>!!
        </p>
   	</div>
	<div id="theviewer">
    	<div id="htmlcontent">
			<table id="theList">
				<?php
					dooodl_include('gallery/includes/contentdisplay.php');
				?>
			</table>
		</div>
	</div>

<?php wp_footer(); ?>

</body>
</html>