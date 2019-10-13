<?php

if((isset($_POST['drawblog_form_posted'])) && ($_POST['drawblog_form_posted'] == 'Y')) {
	$nonce = $_POST['drawblog_settings_nonce'];
	if (wp_verify_nonce($nonce, 'drawblog_settings')){
		$retval = drawblog_update_options($_POST);
		if ($retval == 'success'){
			?><div class="updated"><p><strong><?php echo __('Options saved.' );?></strong></p></div><?php
		} else {
			?><div class="error"><p><strong><?php echo __('Invalid value: ' ).$retval;?></strong></p></div><?php
		}
	} else {
		?><div class="error"><p><strong><?php echo __('Options did not save. Form values expired. Please try again, or contact drawblog@randytayler.com.' );?></strong></p></div><?php
	}
}
if (isset($_POST['drawblog_debug'])){
	$nonce = $_POST['drawblog_debug_nonce'];
	if (wp_verify_nonce($nonce, 'drawblog_debug')){
		$plugins = wp_get_active_and_valid_plugins();
		foreach ($plugins as $plugin){
			$plugin_list .= preg_replace('/.*plugins/','',$plugin)."\n";
		}
		$theme = wp_get_theme();
		$gd = gd_info();
		$site = site_url();
		$post_class_name = get_option( 'drawblog_post_classname');
		$message = 	"SITE:\n".$site.
					"\n\nPLUGINS:\n".$plugin_list.
					"\n\nTHEME:\n".$theme->Name.': '.$theme->ThemeURI.
					"\n\nGD:\n".var_export($gd,true).
					"\n\nCLASS NAMES:\nPost - ".$post_class_name;
		?><div class="updated"><p><strong>
			<?php echo __('Debug information:' ).'<br>';
			echo "<textarea cols=120 rows=20>$message</textarea>";
		?></strong></p></div><?php		
	} else {
		?><div class="error"><p><strong><?php echo __('Options did not save. Form values expired. Please try again, or contact drawblog@randytayler.com.' );?></strong></p></div><?php
	}
}
$post_class_name = get_option( 'drawblog_post_classname');
$comment_container_class_name = get_option( 'comment_container_class_name');
$comment_class_name = get_option( 'comment_class_name');
if (!$post_class_name){
	$post_class_name = drawblog_determine_classname();
}

?>
<div class="wrap"><div><table><tr><td><img src="<?php echo plugins_url().'/drawblog/icons/drawblog_logo.png';?>" /></td><td width=50></td><td><img src="<?php echo plugins_url().'/drawblog/icons/fivestar.png';?>" /><br />If you like DrawBlog, please rate it on <a href='http://wordpress.org/extend/plugins/drawblog/'>WordPress</a>.</td></tr>
	</table><h2><?php echo __('DrawBlog Options');?> </h2></td>    
    If you're having trouble, <a href='mailto:drawblog@randytayler.com?subject=Drawblog help'>email me</a> and I'll see what I can do.</div>
	<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    	<input type="hidden" name="drawblog_settings_nonce" value="<?php echo wp_create_nonce('drawblog_settings'); ?>" />
		<input type="hidden" name="drawblog_form_posted" value="Y">

		<table>         
			<tr valign="top">
				<td width="250" scope="row" align="left"><b><?php echo __('Show canvas'); ?></b><div style='padding-left:20px'><?php echo __('Instead of needing to click the canvas title, users will see the full canvas.'); ?></div></td>
				<td width="500">
					<input type="checkbox" name="drawblog_show_canvas" id="drawblog_show_canvas" value="true" <?php if (get_option('drawblog_show_canvas')) echo "checked";?>>
				</td>
			</tr>
			<tr valign="top">
				<td width="250" scope="row" align="left"><b><?php echo __('Canvas title');?></b><div style='padding-left:20px'><?php echo __('The text above the canvas that explains what it is.');?></div></td>
				<td width="500">
					<input type="text" name="drawblog_canvas_title" id="drawblog_canvas_title" value="<?php echo get_option( 'drawblog_canvas_title');?>" size="80">
				</td>
			</tr>                
			<tr valign="top">
				<td width="250" scope="row" align="left"><b><?php echo __('Thumbnail hint');?></b><div style='padding-left:20px'><?php echo __('The text above the canvas, below any thumbnails, that explains how to include the thumbnail in their picture.');?></div></td>
				<td width="500">
					<input type="text" name="drawblog_hint_text1" id="drawblog_hint_text1" value="<?php echo get_option( 'drawblog_hint_text1');?>" size="80">
				</td>
			</tr>                   
			<tr valign="top">
				<td width="250" scope="row" align="left"><b><?php echo __('Checkbox reminder');?></b><div style='padding-left:20px'><?php echo __('The text beside the checkbox to make sure they want to include their picture with their comment.');?></div></td>
				<td width="500">
					<input type="text" name="drawblog_hint_text2" id="drawblog_hint_text2" value="<?php echo get_option( 'drawblog_hint_text2');?>" size="80">
				</td>
			</tr>               
			<tr valign="top">
				<td width="250" scope="row" align="left"><b><?php echo __('Warning - overwrite');?></b><div style='padding-left:20px'><?php echo __('The text that pops up when they select a thumbnail after having drawn, warning them they will overwrite their art.');?></div></td>
				<td width="500">
					<input type="text" name="drawblog_warning1" id="drawblog_warning1" value="<?php echo get_option( 'drawblog_warning1');?>" size="80">
				</td>
			</tr>     
			<tr valign="top">
				<td width="250" scope="row" align="left"><b><?php echo __('Warning - clear all');?></b><div style='padding-left:20px'><?php echo __('The text that pops up to confirm they\'re going to erase their whole picture.');?></div></td>
				<td width="500">
					<input type="text" name="drawblog_warning2" id="drawblog_warning2" value="<?php echo get_option( 'drawblog_warning2');?>" size="80">
				</td>
			</tr>
            <tr valign="top">
				<td width="250" scope="row" align="left"><b><?php echo __('Canvas width');?></b><div style='padding-left:20px'></div></td>
				<td width="500">
					<input type="text" name="drawblog_canvas_width" id="drawblog_canvas_width" value="<?php echo get_option( 'drawblog_canvas_width');?>" size="5">
				</td>
			</tr>            
            <tr valign="top">
				<td width="250" scope="row" align="left"><b><?php echo __('Canvas height');?></b><div style='padding-left:20px'></div></td>
				<td width="500">
					<input type="text" name="drawblog_canvas_height" id="drawblog_canvas_height" value="<?php echo get_option( 'drawblog_canvas_height');?>" size="5">
				</td>
			</tr>      
            <tr valign="top">
				<td width="250" scope="row" align="left"><b><?php echo __('Default canvas background');?></b><div style='padding-left:20px'><?php echo __('If you want to display a default background for every user, enter the image URL here. It must be an image hosted on your own server, due to HTML5 security restrictions.');?></div></td>
				<td width="500" valign="middle">
					<b><?php echo 'http://'.$_SERVER['HTTP_HOST'].'/'; ?></b><input type="text" name="drawblog_default_bg" id="drawblog_default_bg" value="<?php echo stripslashes(get_option( 'drawblog_default_bg'));?>" size="40" >
				</td>
			</tr>   
		</table>
        <br /><br /><br />
		<h2><?php echo __('Advanced options');?></h2> 
		<table>          
            <tr valign="top">
				<td width="250" scope="row" align="left"><b><?php echo __('API key');?></b><div style='padding-left:20px'><?php echo __('Premium users get enhanced options -- more pen widths and colors, and the latest compatibility with themes. Visit <a href="http://drawblog.com">DrawBlog.com</a> for more information.');?></div></td>
				<td width="500">
					<input type="text" name="drawblog_api_key" id="drawblog_api_key" value="<?php echo get_option( 'drawblog_api_key');?>" size="30">
				</td>
			</tr>          
      <tr valign="top">
				<td width="250" scope="row" align="left"><b><?php echo __('Post class name');?></b><div style='padding-left:20px'><?php echo __('To attempt to only grab the images from posts, rather than the whole page, DrawBlog needs the names of its class, as defined by your current theme.'); echo __('This class name can sometimes be guessed by examining your theme, but all themes vary in the names of their classes. If DrawBlog can\'t determine the class automatically, or if you change your theme, you can put it in manually here.');?></div></td>
				<td width="500">
					<input type="text" name="post_class_name" id="post_class_name" value="<?php echo $post_class_name;?>" size="15">
				</td>
			</tr>  
		</table>
		<p>
			<input type="submit" value="<?php echo __('Save Changes') ;?>" />
		</p>

	</form>
    
		<h2><?php echo __('Debugging');?></h2> 
	<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">    
    	<input type="hidden" name="drawblog_debug_nonce" value="<?php echo wp_create_nonce('drawblog_debug'); ?>" />
		<input type="hidden" name="drawblog_debug" value="Y">
		<p><?php echo __('If you\'re having trouble with your installation, you can generate debug information by clicking the button below.');?><br />
			<input type="submit" value="<?php echo __('Generate Debug Information') ;?>" />
		</p>

	</form>
</div>