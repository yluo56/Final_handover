<?php

define('DOOODL_APPROVED', 			'field_53b6df5971ef5');
define('DOOODL_AUTHOR_NAME',	 	'field_53b6e0a837724');
define('DOOODL_AUTHOR_URL', 		'field_53b6e04f37722');
define('DOOODL_DESCRIPTION', 		'field_53b6e06637723');
define('DOOODL_VERSION', '2.0.9');


function dooodl_get_whtml_template(){
	$html = '<div>
' .__('The latest Doodle:', 'dooodl') . ' <br/><b>%TITLE%</b> <br/>
' . __('by', 'dooodl') . ' <b>%AUTHOR%</b>
<a href="%DOOODL_URL%" target="_blank">

<img style="display: block; margin-bottom: 5px; width: 100%, height: auto;" src="%DOOODL_URL%"/>

</a>

<br/>

<a href="%GALLERY_URL%" target="_blank">' . __('Click here', 'dooodl') . '</a> '. __('to view all %TOTAL_COUNT% doodles that visitors created!', 'dooodl') . '

<br/>
'. __('Feeling creative?', 'dooodl') . '
<a href="%CREATOR_URL%" target="_blank">Click here,</a> '. __('draw your own doodle and add it to this sidebar!', 'dooodl') . '
</div>';

	return $html;
};

function dooodl_shortcode_explanation(){
	$string  = '<h2>'.__('About Dooodl Shortcodes','dooodl') .'</h2>
	<p>'. __('Dooodl comes with a few shortcodes you can use to embed Dooodl items on pages easily.','dooodl') .'</p>

	<hr/>

	<h2>'.__('Widget','dooodl') .'</h2>
	<p>'.__('You can embed the Dooodl sidebar widget inside a page using the [dooodl_widget] shortcode.','dooodl') .'<br/></p>
	<h4>'.__('Example','dooodl') .'</h4>
	<pre style="background-color: #eeeeee; margin: 0; display: inline-block; padding: 5px; border: 1px solid #cccccc;">[dooodl_widget]</pre>

	<hr/>

	<h2>'.__('Creator','dooodl') .'</h2>
	<p>'.__('You can embed the Dooodl sidebar widget inside a page using the [dooodl_creator] shortcode.','dooodl') .'<br/></p>
	<h4>'.__('Example','dooodl') .'</h4>
	<pre style="background-color: #eeeeee; margin: 0; display: inline-block; padding: 5px; border: 1px solid #cccccc;">[dooodl_creator width="800” height="600” css="float:left; margin-top:20px”]</pre>

	<hr/>

	<h2>'.__('Gallery','dooodl') .'</h2>
	<p>'.__('You can embed the Dooodl sidebar widget inside a page using the [dooodl_gallery] shortcode.','dooodl') .'<br/></p>
	<h4>'.__('Example','dooodl') .'</h4>
	<pre style="background-color: #eeeeee; margin: 0; display: inline-block; padding: 5px; border: 1px solid #cccccc;">[dooodl_gallery width="800” height="600” css="float:left; margin-top:20px”]</pre>';

	return $string;
}

?>