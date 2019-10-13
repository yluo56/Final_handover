<?php
header('HTTP/1.1 200 OK');
do_action('dooodl_creator');
?><!doctype html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
    <head>
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
          <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
          <title>Dooodl creator</title>
		  <meta name="keywords" content=""/>
		  <meta name="description" content=""/>

          <meta name="viewport" content="initial-scale=1.0,user-scalable=no,maximum-scale=1.0,minimum-scale=1.0"/>

          <?php wp_head(); ?>
    </head>
    <body>
    <div id="brushholder">&nbsp;</div>

    <div id="container">
    	<div id="left">
        	<div id="logo"></div>
        	<canvas id="canvas"></canvas>


        </div>

        <div id="right">
        	<div id="waiting">
            	<div id="indicator">&nbsp;</div>
                <div id="message"><span class="hidden">Saving... Hang in there...</span></div>
            </div>
            <div id="success">
            	<div id="message">
            		<span class="hidden">Your image has been saved! Check it out!</span>
                </div>
            </div>
        	<div id="form">
            	<ul class="form">
                	<li class="name"><label for="name"><span class="title">Name</span><br/><span class="sub">Who are you?</span></label>			<input type="text" name="name" id="name"/> </li>
                    <li class="title"><label for="title"><span class="title">Title</span><br/><span class="sub">Give your drawing a title!</span></label>		<input type="text" name="title" id="title"/> </li>
                    <li class="url"><label for="url"><span class="title">URL</span><br/><span class="sub">Want to share a link?</span></label>			<input type="text" name="url" id="url"/> </li>
                    <li class="description"><label for="description"><span class="title">Description</span><br/><span class="sub">Why did you draw this? <br/>Tell us something about the image.</span></label>	<textarea name="description" id="description"></textarea></li>
                </ul>
                <a href="#" id="btnBack"><span class="hidden">Go back</span></a>
                <a href="#" id="btnOk"><span class="hidden">Ok</span></a>
            </div>
        	<div id="toolkit">
                <div id="brush">
                    <div id="brushlabel">&nbsp;</div>

                    <ul>
                        <li><a href="#" id="rect_1" data-type="rect" data-size="1">&nbsp;</a></li>
                        <li><a href="#" id="rect_2" data-type="rect" data-size="2">&nbsp;</a></li>
                        <li><a href="#" id="rect_3" data-type="rect" data-size="3">&nbsp;</a></li>
                        <li><a href="#" id="rect_4" data-type="rect" data-size="4">&nbsp;</a></li>
                        <li><a href="#" id="rect_5" data-type="rect" data-size="5">&nbsp;</a></li>
                    </ul>

                    <ul>
                        <li><a href="#" id="round_1" data-type="round" data-size="1">&nbsp;</a></li>
                        <li><a href="#" id="round_2" data-type="round" data-size="2">&nbsp;</a></li>
                        <li><a href="#" id="round_3" data-type="round" data-size="3">&nbsp;</a></li>
                        <li><a href="#" id="round_4" data-type="round" data-size="4">&nbsp;</a></li>
                        <li><a href="#" id="round_5" data-type="round" data-size="5">&nbsp;</a></li>
                    </ul>


                </div>
                <div id="color">
                    <div id="colorlabel">&nbsp;</div>

                    <ul>
                        <li><a href="#" data-color="#fe0000" style="background-color:#fe0000;">&nbsp;</a></li>
                        <li><a href="#" data-color="#d40067" style="background-color:#d40067;">&nbsp;</a></li>
                        <li><a href="#" data-color="#8d0662" style="background-color:#8d0662;">&nbsp;</a></li>
                        <li><a href="#" data-color="#69009e" style="background-color:#69009e;">&nbsp;</a></li>
                        <li><a href="#" data-color="#32009f" style="background-color:#32009f;">&nbsp;</a></li>
                        <li><a href="#" data-color="#356404" style="background-color:#356404;">&nbsp;</a></li>

                        <li><a href="#" data-color="#fc6901" style="background-color:#fc6901;">&nbsp;</a></li>
                        <li><a href="#" data-color="#f50568" style="background-color:#f50568;">&nbsp;</a></li>
                        <li><a href="#" data-color="#9903c7" style="background-color:#9903c7;">&nbsp;</a></li>
                        <li><a href="#" data-color="#6334d0" style="background-color:#6334d0;">&nbsp;</a></li>
                        <li><a href="#" data-color="#3468ce" style="background-color:#3468ce;">&nbsp;</a></li>
                        <li><a href="#" data-color="#319a02" style="background-color:#319a02;">&nbsp;</a></li>

                        <li><a href="#" data-color="#ffcc03" style="background-color:#ffcc03;">&nbsp;</a></li>
                        <li><a href="#" data-color="#ff32cb" style="background-color:#ff32cb;">&nbsp;</a></li>
                        <li><a href="#" data-color="#999bff" style="background-color:#999bff;">&nbsp;</a></li>
                        <li><a href="#" data-color="#639bce" style="background-color:#639bce;">&nbsp;</a></li>
                        <li><a href="#" data-color="#3499ff" style="background-color:#3499ff;">&nbsp;</a></li>
                        <li><a href="#" data-color="#2ecf34" style="background-color:#2ecf34;">&nbsp;</a></li>

                        <li><a href="#" data-color="#ffff9a" style="background-color:#ffff9a;">&nbsp;</a></li>
                        <li><a href="#" data-color="#ff99ce" style="background-color:#ff99ce;">&nbsp;</a></li>
                        <li><a href="#" data-color="#9999cb" style="background-color:#9999cb;">&nbsp;</a></li>
                        <li><a href="#" data-color="#67cdd1" style="background-color:#67cdd1;">&nbsp;</a></li>
                        <li><a href="#" data-color="#2ecece" style="background-color:#2ecece;">&nbsp;</a></li>
                        <li><a href="#" data-color="#35fe00" style="background-color:#35fe00;">&nbsp;</a></li>

                        <li><a href="#" data-color="#ffffff" style="background-color:#ffffff;">&nbsp;</a></li>
                        <li><a href="#" data-color="#cccccc" style="background-color:#cccccc;">&nbsp;</a></li>
                        <li><a href="#" data-color="#9a9a9a" style="background-color:#9a9a9a;">&nbsp;</a></li>
                        <li><a href="#" data-color="#666666" style="background-color:#666666;">&nbsp;</a></li>
                        <li><a href="#" data-color="#343434" style="background-color:#343434;">&nbsp;</a></li>
                        <li><a href="#" data-color="#000000" style="background-color:#000000;">&nbsp;</a></li>

                    </ul>

                    <a href="#save" id="btnSave"><span class="hidden">Save</span></a>
                    <a href="#reset" id="btnReset"><span class="hidden">Reset</span></a>
                    </div>
                </div>

        </div>
    </div>





     <?php wp_footer(); ?>
    </body>
</html>