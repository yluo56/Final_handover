<style>
	#drawblog_area * {
		z-index:10000;
		border-spacing:0px;
		margin:0px;
		padding:0px;
	}
	#drawblog_palette *{
		border-spacing:0px;
		margin:0px;
		padding:0px;
	}
	.drawblog_colors{		
		border-spacing:0px;
		margin:0px;
		padding:0px;
		line-height:10px;
	}
	.drawblog_pens {
		border:none;
		border-spacing:0px;
		margin:0px;
		padding:0px;
	}
	#drawblog_link{
		text-decoration:underline;
	}
	.drawblog_comment_canvas, .drawblog_comment_canvas:active, .drawblog_comment_canvas:focus {
		cursor:url(<?php echo plugins_url();?>/drawblog/icons/pencil.cur), crosshair;
		z-index:10000;
		display:inline;
		border:5px solid #000000;
		border-radius:5px;
		padding:0px;
		margin:0px;
		vertical-align:top;	
	}
	.drawblog_comment_canvas_erase1, .drawblog_comment_canvas_erase1:active, .drawblog_comment_canvas_erase1:focus {
		cursor:url(<?php echo plugins_url();?>/drawblog/icons/eraser.cur), crosshair;
		z-index:10000;
		display:inline;
		border:5px solid #000000;
		border-radius:5px;
		padding:0px;
		margin:0px;
		vertical-align:top;	
	}
	
	.drawblog_comment_canvas_erase1, .drawblog_comment_canvas_erase1:active, .drawblog_comment_canvas_erase1:focus {
		cursor:url(<?php echo plugins_url();?>/drawblog/icons/eraser1.cur), crosshair;
		z-index:10000;
		display:inline;
		border:5px solid #000000;
		border-radius:5px;
		padding:0px;
		margin:0px;
		vertical-align:top;	
	}
	.drawblog_comment_canvas_erase2, .drawblog_comment_canvas_erase2:active, .drawblog_comment_canvas_erase2:focus {
		cursor:url(<?php echo plugins_url();?>/drawblog/icons/eraser2.cur), crosshair;
		z-index:10000;
		display:inline;
		border:5px solid #000000;
		border-radius:5px;
		padding:0px;
		margin:0px;
		vertical-align:top;	
	}
	.drawblog_comment_canvas_erase3, .drawblog_comment_canvas_erase3:active, .drawblog_comment_canvas_erase3:focus {
		cursor:url(<?php echo plugins_url();?>/drawblog/icons/eraser3.cur), crosshair;
		z-index:10000;
		display:inline;
		border:5px solid #000000;
		border-radius:5px;
		padding:0px;
		margin:0px;
		vertical-align:top;	
	}
	.drawblog_comment_canvas_erase4, .drawblog_comment_canvas_erase4:active, .drawblog_comment_canvas_erase4:focus {
		cursor:url(<?php echo plugins_url();?>/drawblog/icons/eraser4.cur), crosshair;
		z-index:10000;
		display:inline;
		border:5px solid #000000;
		border-radius:5px;
		padding:0px;
		margin:0px;
		vertical-align:top;	
	}	
	.drawblog_comment_canvas_erase5, .drawblog_comment_canvas_erase5:active, .drawblog_comment_canvas_erase5:focus {
		cursor:url(<?php echo plugins_url();?>/drawblog/icons/eraser5.cur), crosshair;
		z-index:10000;
		display:inline;
		border:5px solid #000000;
		border-radius:5px;
		padding:0px;
		margin:0px;
		vertical-align:top;	
	}	
</style>
<?php 
if (get_option('drawblog_show_canvas')){
	echo get_option('drawblog_canvas_title');
	?><div id="drawblog_area" style="z-index:100000;">
    <?php
} else {
	?>
	<span onclick="toggleDrawblogArea()" style="cursor:pointer;text-decoration:underline"><?php echo get_option('drawblog_canvas_title');?></span>
	<div id="drawblog_area" style="display:none; z-index:100000;">
    <?php
}
?>
	<span id="drawblog_image_choices"></span>
<script type="text/javascript">
	var colors = new Array("00", "FF");
	var pens = new Array(1,3,5);

	function drawblogSelectPics(){
		var post = document.getElementsByClassName("<?php echo get_option( 'drawblog_post_classname');?>");
		var pics = post[0].getElementsByTagName("img");
		var image_title_written = false;
		for (var i in pics){
			if (pics[i].tagName == "IMG"){
				if (!image_title_written) {
					document.write("<br><?php echo get_option( 'drawblog_hint_text1');?><br>");
					image_title_written = true;
				}
				drawblogGetImgData(pics[i].src);
			}
		}
	}
		
	function drawblogGetImgData(imgsrc){
		var skip = false;
		drawblog_skip_domains = new Array('gravatar');
		for (var i in drawblog_skip_domains){
			if (imgsrc.search(drawblog_skip_domains[i])!=-1) skip = true;
		}
		if (!skip){
			if (typeof jQuery == "undefined") {  
				var script = document.createElement("script");
				script.src = "http://jqueryjs.googlecode.com/files/jquery-1.2.6.min.js";
				script.type = "text/javascript";
				document.getElementsByTagName("head")[0].appendChild(script);
				script.onload = function(){
					drawblogDoGetImgData(imgsrc);
				}
			} else {
				drawblogDoGetImgData(imgsrc);
			}
		}
	}
	
	function drawblogDoGetImgData(imgsrc){	
		var image_choices = document.getElementById("drawblog_image_choices");
		jQuery.ajax({url:"<?php echo plugins_url();?>/drawblog/drawblog_imagedata.php",
			type:"post",
			data:"wpdir=<?php echo content_url();?>&imgsrc="+imgsrc,
			success:function(data){
				img_data = JSON.parse(data);
				var newImage = document.createElement("IMG");
				var tn_ratio = (50/img_data[2]);
				newImage.src = img_data[0];
				newImage.height = 50;				
				newImage.width = Math.round(img_data[1] * tn_ratio);
				var newwidth;
				var newheight;
				var hratio = wratio = 1;
				if (img_data[1] > <?php echo $width;?>){
					newwidth = <?php echo $width;?>;
					wratio = <?php echo $width;?>/img_data[1];
				} else newwidth = img_data[1];
				if (img_data[2] > <?php echo $height;?>){
					newheight = <?php echo $height;?>;
					hratio = <?php echo $height;?>/img_data[2];
				} else newheight = img_data[2];
				if (hratio > wratio){
					newheight = img_data[2] * wratio;
				} else if (wratio > hratio){ 
					newwidth = img_data[1] * hratio;
				}
				newImage.onclick = function() {
					drawblogPasteImg(newImage.src, newwidth, newheight);
				};
				newImage.style.cursor = "pointer";
				newImage.style.border = "1px solid #000000";
				image_choices.appendChild(newImage);
			}
		});
	}

	
	function drawblogPasteImg(imgsrc, newwidth, newheight){
		if (drawblogHasDrawn) {
			if (confirm("<?php echo get_option( 'drawblog_warning1');?>")){
				drawblogDoPaste(imgsrc, newwidth, newheight);
			}
		} else {
			drawblogDoPaste(imgsrc, newwidth, newheight);
		}
	}		
	function drawblogDoPaste(imgsrc, newwidth, newheight){
		var imageObj = new Image();
		imageObj.onload = function(){
			drawblogCtx.drawImage(this, 0, 0, newwidth, newheight);
		};
		imageObj.src = imgsrc;
	}
	
	function drawblogBasicPens(){
		for (var i=0; i<colors.length; i++){
			for (var j=0; j<colors.length; j++){
				document.write("<div class='drawblog_colors'>");
				for (var k=0; k<colors.length; k++){
					var color = colors[i] + colors[j] + colors[k];
					document.write("<img src='<?php echo plugins_url();?>/drawblog/icons/transparent.png' width=10 height=10 class='drawblog_colors' onclick='drawblogSetColor(\""+color+"\")' style='background-color:#"+color+"; border:0px; cursor:pointer;'>");
				}
				document.write("</div>");
			}
		}
		document.write("<table cellpadding='0' cellspacing='0' style='padding:0 0 0 0; margin:0 0 0 0; line-height:15px; border:none; width:"+(colors.length*10)+"px' width='"+(colors.length*10)+"'><tr><td height='3' style='padding:0 0 0 0; margin:0 0 0 0; border:0;'></td></tr>");
		for (var i=1; i<=pens.length; i++){
			document.write("<tr><td class='drawblog_pens' id='penwidthbg"+i+"' style='padding:0 0 0 0; margin:0 0 0 0; border:0;'><img src='<?php echo plugins_url();?>/drawblog/icons/penwidth"+i+".png' width='"+((colors.length*10)-2)+"' height='15' onclick='drawblogSetWidth(\""+i+"\")' style='cursor:pointer; margin:0 0 0 0; padding:0 0 0 0;display:inline; line-height:15;background:none' ></td></tr>");
		}
		document.write("</table>");
		document.write("<img src='<?php echo plugins_url();?>/drawblog/icons/pencil.png' onclick='drawblogPencilOn()' style='cursor:pointer' id='pencilbutton'>");
		document.write("<img src='<?php echo plugins_url();?>/drawblog/icons/eraser.png' onclick='drawblogEraserOn()' style='cursor:pointer' id='eraserbutton'>");
		document.write("<img src='<?php echo plugins_url();?>/drawblog/icons/erase_all.png' onclick='drawblogClearAll()' style='cursor:pointer'>");
		if (typeof drawblogButtons == 'function') drawblogButtons();
	}
	
	drawblogSelectPics();
	if (typeof drawblogSelectCommentPics == 'function') drawblogSelectCommentPics();
</script>


<table cellpadding="0" cellspacing="0" style="border-spacing:0px;padding:0 0 0 0; margin:0 0 0 0; border:none;width:40px" width=40 id="drawblog_palette">
    <tr valign="top"><td bgcolor="#FFFFFF" valign="top" style="vertical-align:top;padding:0 0 0 0; margin:0 0 0 0; ">
        <script type="text/javascript">
            if (typeof drawblogPremiumPens == 'function') drawblogPremiumPens();
            else drawblogBasicPens();
        </script>        	
    </td><td align="left" style='padding:0 0 0 0; margin:0 0 0 0; border:none' ><div style="display:inline">
        <canvas id="drawblog_comment_canvas" width="<?php echo $width;?>" height="<?php echo $height;?>" class="drawblog_comment_canvas"></canvas></div>
    </td></tr>
    <tr><td colspan="2"><div style="text-align:left"><input type="checkbox" name="drawblog_include_pic" value="true" id="drawblog_include_pic" style="padding:0 0 0 0; margin:0 0 0 0; text-align:left; width:15px;" <?php if (get_option('drawblog_show_canvas')) echo "checked";?>> <label for='drawblog_include_pic'><?php echo get_option( 'drawblog_hint_text2');?></label>
        </div>
	</td></tr>
</table>
<input type="hidden" name="drawblog_picture" id="drawblog_picture">
    
    
<script type="text/javascript">
	var browseroffset = 5;
	if (navigator.appName == 'Microsoft Internet Explorer') browseroffset = 0; // IE doesn't add our border to cursor position
	
	// initialize the canvas and such
	var drawblogArea = document.getElementById("drawblog_area");
	var drawblogDraw = false;
	var drawblogSize = 1;
	var drawblogColor = "rgba(0,0,0,1)";
	var drawblogCanvas = document.getElementById("drawblog_comment_canvas");
	var drawblogCtx = drawblogCanvas.getContext("2d");
	var lastx, lasty, offsetx, offsety;
	var drawblogHasDrawn = false;
	var drawmode = 'draw';
	var eraseColor = "rgba(255,255,255,1)";	
	var pos;
	
	// paint the canvas white
	drawblogCtx.fillStyle = "white";
	drawblogCtx.fillRect(0,0,<?php echo $width;?>,<?php echo $height;?>);
	
	// set the pen to black, width to 1	
	var pencil = document.getElementById("pencilbutton");
	pencil.style.backgroundColor = "#FFFF33";
	drawblogSetColor("000000");
	drawblogSetWidth(1);
	drawblogCanvas.addEventListener("mousedown", function(e) { e.preventDefault(); }, false); //keeps dragging from changing cursor to text selector in Chrome
	
	// function time!
	function drawblogEraserOn(){		
		drawmode = 'erase';
		var eraser = document.getElementById("eraserbutton");
		var pencil = document.getElementById("pencilbutton");
		eraser.style.backgroundColor = "#FFFF33";
		pencil.style.backgroundColor = "#FFFFFF";
		drawblogCanvas.className = 'drawblog_comment_canvas_erase'+drawblogSize;
	}	
	function drawblogPencilOn(){
		drawmode = 'draw';
		var eraser = document.getElementById("eraserbutton");
		var pencil = document.getElementById("pencilbutton");
		eraser.style.backgroundColor = "#FFFFFF";
		pencil.style.backgroundColor = "#FFFF33";
		drawblogCtx.fillStyle = drawblogColor;
		drawblogCtx.strokeStyle = drawblogColor;
		drawblogCanvas.className = 'drawblog_comment_canvas';
	}
	function drawblogClearAll(){
		var clear = false;
		if (drawblogHasDrawn) {
			if (confirm("<?php echo get_option( 'drawblog_warning2'); ?>")) clear = true;
		} else clear = true;
		if (clear){
			drawblogHasDrawn = false;
			drawblogCtx.fillStyle = "white";
			drawblogCtx.fillRect(0,0,<?php echo $width;?>,<?php echo $height;?>);	
			drawblogCtx.fillStyle = drawblogColor;
			drawblogCtx.strokeStyle = drawblogColor;
		}
	}
	
	function toggleDrawblogArea(){
		if (drawblogArea.style.display == "block") drawblogArea.style.display = "none";
		else {
			drawblogArea.style.display = "block";
			var include_pic = document.getElementById("drawblog_include_pic");
			include_pic.checked = "checked";
		}			
	}		
	function drawblogSetColor(color){
		var r = color.substr(0,2);
		var g = color.substr(2,2);
		var b = color.substr(4,2);
		var r = parseInt(r, 16);
		var g = parseInt(g, 16);
		var b = parseInt(b, 16);
		drawblogColor = "rgba("+r+", "+g+", "+b+",1)";
		drawblogCtx.fillStyle = drawblogColor;
		drawblogCtx.strokeStyle = drawblogColor;
		drawblogCanvas.style.borderColor = "#"+color;
	}
	function drawblogSetWidth(penwidth){
		drawblogCtx.lineWidth = penwidth;
		drawblogSize = penwidth;
		var penbg;
		for (var i=1; i<=pens.length; i++){
			penbg = document.getElementById("penwidthbg"+i);
			penbg.style.backgroundColor = "#FFFFFF";
		}
		penbg = document.getElementById("penwidthbg"+penwidth);
		penbg.style.backgroundColor = "#FFFF33";
		if (drawmode == 'erase') drawblogEraserOn();
	}
	function drawblogCommentSubmit(){
		var includePic = document.getElementById("drawblog_include_pic");
		if (includePic.checked) {
			var canvas_image = drawblogCanvas.toDataURL("image/png");
			var drawblog_picture = document.getElementById("drawblog_picture");
			drawblog_picture.value = canvas_image;
		}
		return true;
	}
	function drawblogFindPos(obj) {
		if (!pos){
			var curleft = curtop = 0;
			if (obj.offsetParent) {
				curleft = obj.offsetLeft;
				curtop = obj.offsetTop;
				while (obj = obj.offsetParent) {
					curleft += obj.offsetLeft;
					curtop += obj.offsetTop;
				}
			}
			pos = [curleft,curtop];
		}	
	}
	
	function drawblogInitCanvas(){
		var drawblogDefaultBg = '<?php echo get_option( 'drawblog_default_bg');?>';
		if (drawblogDefaultBg){
			drawblogDoPaste('<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/';?>'+drawblogDefaultBg, <?php echo $width;?>,<?php echo $height;?>);
		}
		drawblogCanvas.addEventListener ("mousedown", function (ev) {
			var x, y;
			drawblogHasDrawn = true;
			drawblogDraw = true;
			if (navigator.userAgent.search('Firefox') != -1){
				drawblogFindPos(drawblogCanvas);
				x = ev.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
				y = ev.clientY + document.body.scrollTop + document.documentElement.scrollTop;
				x -= pos[0];
				x -= browseroffset;
				y -= pos[1];
				y -= browseroffset;
			} else {
				x = ev.offsetX-browseroffset;
				y = ev.offsetY-browseroffset;
			}
			lastx = x;
			lasty = y;
			if (drawmode == 'draw') {
				drawblogCtx.beginPath();
				drawblogCtx.moveTo(x,y);
				drawblogCtx.arc(x, y, Math.ceil(drawblogSize/4), 0, Math.PI*2, true);
				drawblogCtx.closePath();
				drawblogCtx.stroke();
			} else if (drawmode == 'erase'){				
				drawblogCtx.fillStyle = eraseColor;
				drawblogCtx.strokeStyle = eraseColor;			
				drawblogCtx.beginPath();
				drawblogCtx.arc(x + (drawblogSize*3), y + (drawblogSize*3), drawblogSize*3, 0, Math.PI*2, true);
				drawblogCtx.closePath();
				drawblogCtx.fill();
			}
		});
		drawblogCanvas.addEventListener ("mouseup", function (ev) {
			drawblogDraw = false;
		});
		drawblogCanvas.addEventListener("mouseout", function(ev) {
			drawblogDraw = false;
		});
		drawblogCanvas.addEventListener ("mousemove", function (ev) {
			var x, y;
			if (drawblogDraw){
				if (navigator.userAgent.search('Firefox') != -1){
					drawblogFindPos(drawblogCanvas);
					x = ev.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
					y = ev.clientY + document.body.scrollTop + document.documentElement.scrollTop;
					x -= pos[0];
					x -= browseroffset;
					y -= pos[1];
					y -= browseroffset;
				} else {
					x = ev.offsetX-browseroffset;
					y = ev.offsetY-browseroffset;
				}
				
				if (drawmode == 'draw') {
					drawblogHasDrawn = true;
					drawblogCtx.beginPath();
					drawblogCtx.moveTo(lastx,lasty);
					drawblogCtx.lineTo(x, y);
					drawblogCtx.closePath();
					lastx = x;
					lasty = y;
					drawblogCtx.stroke();
				} else if (drawmode == 'erase'){				
					drawblogCtx.fillStyle = eraseColor;
					drawblogCtx.strokeStyle = eraseColor;			
					drawblogCtx.beginPath();
					drawblogCtx.arc(x + (drawblogSize*3), y + (drawblogSize*3), drawblogSize*3, 0, Math.PI*2, true);
					drawblogCtx.closePath();
					drawblogCtx.fill();
				}
			}
		});		
		if (typeof mobileInit == 'function') mobileInit();	
	}

	var commentForm = document.getElementById("commentform");
	var oldsubmit = commentForm.onsubmit;
	commentForm.onsubmit = function(){
		var formreturn = true;
		if (oldsubmit) {
			formreturn = oldsubmit(); // just in case some other plugin has a submit override
		}
		if (formreturn) {
			return drawblogCommentSubmit();
		} else return false;
	};
	drawblogInitCanvas();
</script><br>
</div>