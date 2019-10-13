<?php
/*
    Plugin Name: WordDraw
    Plugin URI: http://burzak.com/proj/worddraw/
    Description: WordDraw enables you and your visitors to draw comments. 
    Version: 0.1-beta
    Author: Evgen Burzak
    Author URI: http://burzak.com
    License: GPL2
    Tags: comment, draw, drawing, image, worddraw, drawform
*/

/*  Copyright 2010  Evgeny Burzak  (email : buzzilo@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// todo quick edit
//
//ini_set('display_errors', 0);

define('wd_debug', 0);
define('wd_image_data_header', 'data:image/png;base64');
// plugin_basename(path) returns wrong result if path inside links
define('wd_plugin_url', WP_PLUGIN_URL.'/worddraw');
define('meta_canvas_size', 'worddraw_canvas_size');
define('meta_allow_drawings', 'worddraw_allow_drawings');

WordDraw::init();

abstract class WordDraw
{
	static $actions = array();
    static $print_bottom_scripts = false;
    static $allow_drawings = false;

    static $width = 400;
    static $height = 300;
        
	static function init()
	{
        /* Both */
        add_filter('comment_text', array('WordDraw', 'comment_text'));

		/* Backend */
        add_action('admin_head', array('WordDraw', 'admin_head'));
        add_filter('comment_row_actions', array('WordDraw', 'comment_row_actions'));
		add_action('admin_print_scripts', array('WordDraw', 'admin_print_scripts'));
		add_action('admin_print_styles', array('WordDraw', 'admin_print_styles'));
		add_action('comment_excerpt', array('WordDraw', 'comment_excerpt'));
		add_filter('the_editor', array('WordDraw', 'the_editor'));
		add_filter('the_editor_content', array('WordDraw', 'the_editor_content'));
		add_filter('user_can_richedit', array('WordDraw', 'user_can_richedit'));
        add_action('admin_menu', array('WordDraw', 'add_custom_box') );
        add_action('save_post', array('WordDraw', 'save_post_metadata'));


		/* Frontend */
        add_action('wp_head', array('WordDraw', 'head'));
		add_action('wp_print_scripts', array('WordDraw', 'wp_print_scripts'));
		add_action('wp_print_styles', array('WordDraw', 'wp_print_styles'));
        add_action('comment_form_after', array('WordDraw', 'comment_form_after'));
        add_filter('comment_form_defaults', array('WordDraw', 'comment_form_defaults'));
        add_filter('comment_form_field_comment', array('WordDraw', 'comment_form_field_comment'));
	}

    /* Adds a custom section to the "advanced" Post and Page edit screens */
    static function add_custom_box() {
      if( function_exists( 'add_meta_box' )) {
        add_meta_box( 'worddraw_settings', __( 'WordDraw', 'worddraw_textdomain' ), 
                    array('WordDraw', 'inner_custom_box'), 'page', 'advanced' );
        add_meta_box( 'worddraw_settings', __( 'WordDraw', 'worddraw_textdomain' ), 
                    array('WordDraw', 'inner_custom_box'), 'post', 'advanced' );
       } else {
        add_action('dbx_post_advanced', 'myplugin_old_custom_box' );
        add_action('dbx_page_advanced', 'myplugin_old_custom_box' );
      }
    }

    /* Prints the inner fields for the custom post/page section */
    static function inner_custom_box($data) 
    {
      $allow_drawings = get_post_meta($data->ID, meta_allow_drawings, true);
      $canvas_size = get_post_meta($data->ID, meta_canvas_size, true);
      
      // set allowed by default if empty
      if($allow_drawings == "")
        $allow_drawings = false;
      else
        $allow_drawings = $allow_drawings == 'true' ? true : false;

      if($canvas_size == "") {
        $canvas_width = self::$width;
        $canvas_height = self::$height;
      }
      else {
          $canvas_size = explode("x", $canvas_size);
        $canvas_width = $canvas_size[0];
        $canvas_height = $canvas_size[1];
      }

      // Use nonce for verification

      echo '<input type="hidden" name="wd_noncename" id="wd_noncename" value="' . 
        wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

      // The actual fields for data entry

      echo '<input type="checkbox" id="'.meta_allow_drawings.'" name="'.meta_allow_drawings.'" value="1" '.
                ($allow_drawings ? 'checked="1"' : '' ).'/>';
      echo '&nbsp;';
      echo '<label for="'.meta_allow_drawings.'">' . __("Allow drawings", 'wd_text' );
      echo '</label> ';
      echo '<br/>';
      echo '<label for="worddraw_canvas_size_width">' . __("Canvas size", 'wd_text' ) . '  ';
      echo '<input type="text" id= "worddraw_canvas_size_width" name="worddraw_canvas_size_width" '.
                'value="'.$canvas_width.'" size="4"/>';
      echo 'x';
      echo '<input type="text" id= "worddraw_canvas_size_height" name="worddraw_canvas_size_height" '.
          'value="'.$canvas_height.'" size="4"/>';
      echo '</label>';
    }

    /* When the post is saved, saves our custom data */
    static  function save_post_metadata( $post_id ) {

      // verify this came from the our screen and with proper authorization,
      // because save_post can be triggered at other times

      if ( !wp_verify_nonce( $_POST['wd_noncename'], plugin_basename(__FILE__) )) {
        return $post_id;
      }

      // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
      // to do anything
      if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
        return $post_id;

      
      // Check permissions
      if ( 'page' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
          return $post_id;
      } else {
        if ( !current_user_can( 'edit_post', $post_id ) )
          return $post_id;
      }

      // OK, we're authenticated: we need to find and save the data

      $allow_drawings = empty($_POST[meta_allow_drawings]) ? "false" : "true";
      $canvas_width = (int) $_POST['worddraw_canvas_size_width'];
      $canvas_height = (int) $_POST['worddraw_canvas_size_height'];
      $canvas_width = $canvas_width < 10 ? self::$width : $canvas_width;
      $canvas_height = $canvas_height < 10 ? self::$height : $canvas_height;
      $canvas_size = $canvas_width . "x" . $canvas_height;

      // Do something with $mydata 
      // probably using add_post_meta(), update_post_meta(), or 
      // a custom table (see Further Reading section below)
      
      add_post_meta($post_id, meta_allow_drawings, $allow_drawings, true) or 
        update_post_meta($post_id, meta_allow_drawings, $allow_drawings);

      add_post_meta($post_id, meta_canvas_size, $canvas_size, true) or 
        update_post_meta($post_id, meta_canvas_size, $canvas_size);

      return $post_id;
    }

    static function drawingCanvas() {
        echo '<div id="wd-container"> </div>';
    }

    static function toolbar () {
        echo '<div id="wd-toolbar"> </div>';
    }

    static function comment_form_defaults ($defaults) {
        if(!self::$allow_drawings) return $defaults;
        $defaults['title_reply'] = 'Draw a Reply';
        // '<p id="wd-notes" class="form-allowed-tags">To reset selected area, click on Marquee tool and then on canvas.</p>'
        $defaults['comment_notes_after'] = '<div id="editor-notes" class="no-script-hidden">'.$defaults['comment_notes_after'].'</div>'; 
        return $defaults;
    }

    static function comment_form_top () {
    }

    static function comment_form_after() {
        if(self::$allow_drawings)
            echo '<p class="wd-powered">Canvas powered by <a href="http://burzak.com/proj/worddraw/">WordDraw</a></p>';
    }

    static function comment_row_actions ($actions) {
        global $comment;
        // todo quick edit: ajax form
        if($actions['quickedit'] and self::is_comment_content_is_image())
            unset($actions['quickedit']);
            //$actions['quickedit'] = '(Quick image edit)';
        return $actions;
    }

    static function is_comment_content_is_image () {
        global $comment;

        if(substr($comment->comment_content, 0, 21) == wd_image_data_header)
            return true;
        return false;
    }

    // this is user drawings
    static function comment_form_field_comment ($field) 
    {
        global $comment;

        if(!self::$allow_drawings) return $field;

        $post_id = $comment->comment_post_ID;
        list($canvas_width, $canvas_height) = self::canvas_size(
                get_post_meta($post_id, meta_canvas_size, true));

        $textarea_container_id = 'comment-text';
        $disabled_name = 'comment-textarea-disabled';
        $field_disabled = $field;
        $field_disabled = str_replace('id="comment"', 'id="'.$disabled_name.'"', $field_disabled);
        $field_disabled = str_replace('name="comment"', 'name="'.$disabled_name.'"', $field_disabled);
        $fallback = " 
            function(err){
                var div = document.getElementById(\"$textarea_container_id\"),
                    textarea = document.getElementById(\"$disabled_name\"),
                    notes = document.getElementById(\"editor-notes\"); 
                div.style.display = \"block\";
                textarea.id = \"comment\";
                textarea.name = \"comment\";
                notes.style.display = \"block\";
            }";

        return self::toolbar() .
               self::drawingCanvas() .
               self::initScript('wd-toolbar', 'wd-container', 'comment', 'comment',
                                    "", // content is empty?
                                    $canvas_width, $canvas_height, $fallback) .
               '<noscript>' . $field . '</noscript>' .
               '<div id="'.$textarea_container_id.'">' . $field_disabled . '</div>';
    }

	# Public Styles
	static function wp_print_styles()
	{
        if(is_admin()) return;
        if(!(is_single() || is_page())) return;
        if(!comments_open()) return;
        wp_enqueue_style('farbtastic');
        wp_enqueue_style('worddraw', wd_plugin_url . '/worddraw.css');
	}
    
	# Public Scripts
	static function wp_print_scripts()
	{
        global $post;

        if(is_admin()) return;
        if(!(is_single() || is_page())) return;
        if(!comments_open()) return;
        $post_id = $post->ID;

        if (get_post_meta($post_id, meta_allow_drawings, true) != 'true')
            return;

        self::$allow_drawings = true;

        // fixme why that required full path for farbtastic?
        wp_enqueue_script('farbtastic', '/wp-admin/js/farbtastic.js', array('jquery'), '1.2' );
        if(wd_debug)
            wp_enqueue_script('worddraw', wd_plugin_url . "/debug.js.php");
        else {
            self::print_scripts();
        }
	}


    static function admin_print_scripts() { 
		if(current_user_can('moderate_comments') && $_GET['action'] == 'editcomment')
		{
            wp_enqueue_script('farbtastic');
            if(wd_debug)
                wp_enqueue_script('worddraw', wd_plugin_url . "/debug.js.php");
            else {
                self::print_scripts();
            }
		}
    }

    static function print_scripts() { 
        wp_enqueue_script('jooscript', wd_plugin_url . "/jooscript.js");
        wp_enqueue_script('fxcanvas', wd_plugin_url . "/fxcanvas.js");
        wp_enqueue_script('drawform', wd_plugin_url . "/drawform.js");
        wp_enqueue_script('worddraw', wd_plugin_url . "/worddraw.js");
        self::$print_bottom_scripts = true;
    }

    static function admin_load()
    {
    }

    static function admin_print_styles(){
		if(current_user_can('moderate_comments') && $_GET['action'] == 'editcomment')
		{
            wp_enqueue_style('farbtastic');
            wp_enqueue_style('worddraw', wd_plugin_url . '/worddraw.css');
		}
    }
    
    static function head () 
    {
        if(self::$print_bottom_scripts) {
            echo '<!--[if IE]><script type="text/javascript" src="'.wd_plugin_url.'/flash_backend.js"></script><![endif]-->' . "\n";
            echo '<comment><script type="text/javascript" src="'.wd_plugin_url.'/canvas_backend.js"></script></comment>' . "\n";
        }
    }

    static function admin_head () {
        self::head();
    }

    static function preprocess_comment ($comment_data) {
        $comment['comment_content'] = $_POST['comment_picture'];
        return $comment;
    }

    // todo (?) replace with src=comment_image.php?id=123 
    static function comment_text ($text) {
        if(substr($text, 0, 21) == wd_image_data_header)
            return '<img src="'.$text.'"/>';

        return $text;
    }

    static function comment_edit_pre ($text) {
        if(self::is_comment_content_is_image())
            return '<image>'; //'&lt;image&gt;';
        else 
            return $text;
    }

    static function comment_excerpt ($excerpt) {
        return self::comment_text($excerpt);
    }

    // this is admin's drawings
    // fixme instead of textarea with image data show image (src=comment_image.php?123)
    static function the_editor ($editor) {
        global $comment, $wp_query;
        $post_id = $comment->comment_post_ID;
        list($canvas_width, $canvas_height) = self::canvas_size(
                get_post_meta($post_id, meta_canvas_size, true));

        $div_open_tag_end = strpos($editor, ">") + 1;
        $div_closed_tag_start = strripos($editor, "<") - $div_open_tag_end;
        $textarea = substr($editor, $div_open_tag_end, $div_closed_tag_start );
        // canvas should have name 'content'
        if(self::is_comment_content_is_image()) 
        {
            $editor = '<div id="editorcontainer" align="center">';
            //$editor .= $drawform;
            $editor .= '<noscript>' . $textarea . '</noscript>';
            $editor .= '</div>';
            $editor .= self::initScript('ed_toolbar', 'editorcontainer', 'comment-image', 'content',
                                            $comment->comment_content, $canvas_width, $canvas_height);
        }
        return $editor;
    }

    static function initScript ($toolbar_id, $container, 
                                    $id, $name, $content, 
                                    $width, $height, 
                                    $fallback = "null") {
        return '<script type="text/javascript">addDOMLoadEvent('.
               'function() { WordDraw["new"](' . implode(',', array(
                        "'" . wd_plugin_url . "'",
                        "'" . $toolbar_id . "'",
                        "'" . $container . "'",
                        "'" . $id . "'",
                        "'" . $name . "'",
                        "'" . $content . "'",
                        $width, $height,
                        $fallback
                    )).'); });'.
                    '(new _CSSStyleSheet).addRule(".no-script-hidden", "display:none")</script>';
    }

    static function the_editor_content ($content) {
        //if(self::is_comment_content_is_image())
            //return "<image>";
        return $content;
    }

    static function canvas_size ($size) {
        if(!empty($size))
            return explode("x", $size);
        else
            return array(self::$width, self::$height);
    }

    static function user_can_richedit ($can_richedit) {
        if(self::is_comment_content_is_image())
            return false;
        else
            return $can_richedit;
    }
}

