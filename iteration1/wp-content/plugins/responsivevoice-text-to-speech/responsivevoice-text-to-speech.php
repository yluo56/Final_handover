<?php
/*
Plugin Name: ResponsiveVoice Text To Speech
Plugin URI: responsivevoice.com/wordpress-text-to-speech-plugin/?utm_source=wpadmin&utm_medium=plugin&utm_campaign=wprvttsplugin
Description: An easy to use plugin to integrate ResponsiveVoice Text to Speech into your WP blog.
Version: 1.5.16
Author: ResponsiveVoice
Author URI: http://responsivevoice.com
License: GPL2
*/
/*
Copyright 2015-2019  ResponsiveVoice   (email : info@responsivevoice.com)

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

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'RV_custom_plugin_action_links');

function RV_add_listen_button($attributes)
{
    static $id_listenButton = 0;
    $id_listenButton++;

    $postContent = get_the_content();
    $postContent = RV_clean_text($postContent);
    extract(shortcode_atts(array(
        'voice'      => 'UK English Female',
        'buttontext' => 'Listen to this'
    ), $attributes));

	$parameters = RV_extract_extra_parameters($attributes);

    $speakerIcon = "&#128266;";
    // The script can be multiline, but the button should be in a single line, otherwise it can mess up a user's layout.
    $button = '<button id="listenButton'.$id_listenButton.'" class="responsivevoice-button" type="button" value="Play" title="ResponsiveVoice Tap to Start/Stop Speech"><span>'.$speakerIcon.' '.$buttontext.'</span></button>
        <script>
            listenButton' . $id_listenButton . '.onclick = function(){
                if(responsiveVoice.isPlaying()){
                    responsiveVoice.cancel();
                }else{
                    responsiveVoice.speak("' . $postContent . '", "' . $voice . '"' . $parameters . ');
                }
            };
        </script>
    ';

    return $button;
}

function RV_add_bblisten($attributes, $includedtext = "")
{
    static $id_bb = 0;
    $id_bb++;

    $cleantext = RV_clean_text($includedtext);
    extract(shortcode_atts(array(
        'voice'          => 'UK English Female',
        'buttontext'     => 'Play',
        'buttonposition' => 'before'
    ), $attributes));

	$parameters = RV_extract_extra_parameters($attributes);

    $speakerIcon = "&#128266;";
    // The script can be multiline, but the button should be in a single line, otherwise it can mess up a user's layout.
    $bb = '<button id="bb'.$id_bb.'" type="button" value="Play" class="responsivevoice-button" title="ResponsiveVoice Tap to Start/Stop Speech"><span>'.$speakerIcon.' '.$buttontext.'</span></button>
        <script>
            bb' . $id_bb . '.onclick = function(){
                if(responsiveVoice.isPlaying()){
                    responsiveVoice.cancel();
                }else{
                    responsiveVoice.speak("' . $cleantext . '", "' . $voice . '"' . $parameters . ');
                }
            };
        </script>
    ';

    if ($buttonposition == 'after') {
        return $includedtext . $bb;
    } else {
        return $bb . $includedtext;
    }
}

function RV_custom_plugin_action_links($links)
{
    $new_links = array(
        '<a href="https://responsivevoice.com/wordpress-text-to-speech-plugin/?utm_source=wordpress&utm_medium=plugin-action-row&utm_campaign=wp-plugin-launch" target="_blank">FAQ</a>',
        '<a href="https://responsivevoice.com/support/?utm_source=wordpress&utm_medium=plugin-action-row&utm_campaign=wp-plugin-launch" target="_blank">Support</a>',
    );
    $links = array_merge($new_links, $links);

    return $links;
}

/**
 * Finds additional adjustment parameters in the shortcode to add as an object to the JS call
 * @param $attributes
 *
 * @return null|string
 */
function RV_extract_extra_parameters($attributes)
{
	$valid_attributes = array( 'rate', 'pitch', 'volume' );
	$parameters       = null;

	if(!is_array($attributes)){
		return null;
	}

	foreach ($attributes AS $attribute => $value) {
		if ( array_search($attribute, $valid_attributes) === FALSE ) {
			unset($attributes[ $attribute ]);
		}

	}

	if(count($attributes) > 0){
		$parameters = ", " . json_encode($attributes);
	}

	return $parameters;
}


function RV_clean_text($text)
{
    $quotationMarks_from = array("'", '"', "&#8216;", "&#8217;", "&rsquo;", "&lsquo;", "&#8218;", "&#8220;", "&#8221;", "&#8222;", "&ldquo;", "&rdquo;");
    $quotationMarks_to = array("\'", '\"', "\'", "\'", "\'", "\'", "", "\"", "\"", "\"", "\"", "\"");
    $otherMarks_from = array("&nbsp;", "&amp;", "&gt;", "&lt;", "&#8211;");
    $otherMarks_to = array("", "&", "greater than", "less than", "-");

    $text = strip_shortcodes($text);
    $text = wp_strip_all_tags($text, true);
    $text = str_replace($quotationMarks_from, $quotationMarks_to, $text); // Get rid of ASCII quotation marks (single and double, high and low).
    $text = str_replace($otherMarks_from, $otherMarks_to, $text); // Get rid of ASCII codes like &nbsp; and &amp;, etc.
    $text = preg_replace('/\s+/', ' ', trim($text)); // Get rid of /n and /s in the string.

    return $text;
}

function RV_add_voicebox()
{
    $iconUrl = plugin_dir_url(__FILE__) . 'includes/images/responsivevoice-icon-192x192.png';
    $voiceBox = '
        <div style="float:left border: 1px solid #ffffff" id="voicetestdiv">
            <textarea style="-webkit-input-placeholder: color: #555;" placeholder="Paste or type-in a block of text." id="text" cols="45" rows="3"></textarea>
            <br>
            <select id="voiceselection"></select>&nbsp;&nbsp;&nbsp;<button id="playbutton" type="button" value="Play">Play</button>
        </div>
        <script>
            var voicelist = responsiveVoice.getVoices(); 
            var vselect = jQuery("#voiceselection"); 
            jQuery.each(voicelist, function() {	
                vselect.append(jQuery("<option >").val(this.name).text(this.name));
            }); 
            playbutton.onclick = function() {
                responsiveVoice.speak(
                    (jQuery("#text").val()) ? jQuery("#text").val() : jQuery("#text").attr("placeholder"),
                    jQuery("#voiceselection").val()
                ); 
            }; 
            jQuery("#voicetestdiv").hide(); 
            jQuery("#waitingdiv").show(); 
            responsiveVoice.OnVoiceReady = function() {
                jQuery("#voicetestdiv").fadeIn(0.5);
                jQuery("#waitingdiv").fadeOut(0.5);	
            }
        </script>
        <div style="float:left">
            <src="' . $iconUrl . '" height="64" width="64" align="middle" />
            <div>Powered by<br/><b>ResponsiveVoice</b></div>
        </div>
        <div style="clear: both;">&nbsp;</div>
    ';

    return $voiceBox;
}

// BBCode shortcodes
add_shortcode('ResponsiveVoice', 'RV_add_bblisten');
add_shortcode('responsivevoice', 'RV_add_bblisten');

// Voicebox shortcodes
add_shortcode('responsivevoice_box', 'RV_add_voicebox');
add_shortcode('ResponsiveVoiceBox', 'RV_add_voicebox');
add_shortcode('RVTextBox', 'RV_add_voicebox');

// "Listen to this" shortcodes
add_shortcode('responsivevoice_button', 'RV_add_listen_button');
add_shortcode('ListenToPostButton', 'RV_add_listen_button');
add_shortcode('RVListenButton', 'RV_add_listen_button');

// Includes
include 'includes/responsivevoice-includes.php';