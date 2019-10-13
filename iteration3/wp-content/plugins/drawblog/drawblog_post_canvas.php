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
	.drawblog_post_canvas, .drawblog_post_canvas:active, .drawblog_post_canvas:focus {
		cursor:url(<?php echo plugins_url();?>/drawblog/icons/pencil.cur), crosshair;
		z-index:10000;
		display:inline;
		border:5px solid #000000;
		border-radius:5px;
		padding:0px;
		margin:0px;
		vertical-align:top;	
	}
	.drawblog_post_canvas_erase1, .drawblog_post_canvas_erase1:active, .drawblog_post_canvas_erase1:focus {
		cursor:url(<?php echo plugins_url();?>/drawblog/icons/eraser.cur), crosshair;
		z-index:10000;
		display:inline;
		border:5px solid #000000;
		border-radius:5px;
		padding:0px;
		margin:0px;
		vertical-align:top;	
	}
	
	.drawblog_post_canvas_erase1, .drawblog_post_canvas_erase1:active, .drawblog_post_canvas_erase1:focus {
		cursor:url(<?php echo plugins_url();?>/drawblog/icons/eraser1.cur), crosshair;
		z-index:10000;
		display:inline;
		border:5px solid #000000;
		border-radius:5px;
		padding:0px;
		margin:0px;
		vertical-align:top;	
	}
	.drawblog_post_canvas_erase2, .drawblog_post_canvas_erase2:active, .drawblog_post_canvas_erase2:focus {
		cursor:url(<?php echo plugins_url();?>/drawblog/icons/eraser2.cur), crosshair;
		z-index:10000;
		display:inline;
		border:5px solid #000000;
		border-radius:5px;
		padding:0px;
		margin:0px;
		vertical-align:top;	
	}
	.drawblog_post_canvas_erase3, .drawblog_post_canvas_erase3:active, .drawblog_post_canvas_erase3:focus {
		cursor:url(<?php echo plugins_url();?>/drawblog/icons/eraser3.cur), crosshair;
		z-index:10000;
		display:inline;
		border:5px solid #000000;
		border-radius:5px;
		padding:0px;
		margin:0px;
		vertical-align:top;	
	}
	.drawblog_post_canvas_erase4, .drawblog_post_canvas_erase4:active, .drawblog_post_canvas_erase4:focus {
		cursor:url(<?php echo plugins_url();?>/drawblog/icons/eraser4.cur), crosshair;
		z-index:10000;
		display:inline;
		border:5px solid #000000;
		border-radius:5px;
		padding:0px;
		margin:0px;
		vertical-align:top;	
	}	
	.drawblog_post_canvas_erase5, .drawblog_post_canvas_erase5:active, .drawblog_post_canvas_erase5:focus {
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
<div id="drawblog_area" style="z-index:100000;">
<script type="text/javascript">
	var colors = new Array("00", "FF");
	var pens = new Array(1,3,5);

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
	
</script>


<table cellpadding="0" cellspacing="0" style="border-spacing:0px;padding:0 0 0 0; margin:0 0 0 0; border:none;width:40px" width=40 id="drawblog_palette">
    <tr valign="top"><td bgcolor="#FFFFFF" valign="top" style="vertical-align:top;padding:0 0 0 0; margin:0 0 0 0; ">
        <script type="text/javascript">
            if (typeof drawblogPremiumPens == 'function') drawblogPremiumPens();
            else drawblogBasicPens();
        </script>        	
    </td><td align="left" style='padding:0 0 0 0; margin:0 0 0 0; border:none' ><div style="display:inline">
        <canvas id="drawblog_post_canvas" width="<?php echo $width;?>" height="<?php echo $height;?>" class="drawblog_post_canvas"></canvas></div>
    </td></tr>
    <tr><td colspan="2"><div style="text-align:left"><input type="checkbox" name="drawblog_include_pic" value="true" id="drawblog_include_pic" style="padding:0 0 0 0; margin:0 0 0 0; text-align:left; width:15px;" checked> <label for='drawblog_include_pic'>Include this picture with my post. (Unchecking this box will delete anything you've drawn.)</label>
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
	var drawblogCanvas = document.getElementById("drawblog_post_canvas");
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
		drawblogCanvas.className = 'drawblog_post_canvas_erase'+drawblogSize;
	}	
	function drawblogPencilOn(){
		drawmode = 'draw';
		var eraser = document.getElementById("eraserbutton");
		var pencil = document.getElementById("pencilbutton");
		eraser.style.backgroundColor = "#FFFFFF";
		pencil.style.backgroundColor = "#FFFF33";
		drawblogCtx.fillStyle = drawblogColor;
		drawblogCtx.strokeStyle = drawblogColor;
		drawblogCanvas.className = 'drawblog_post_canvas';
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
	function drawblogPostSubmit(){
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
		var drawblogDefaultBg = '<?php echo get_option('drawblog_default_bg');?>';
		if (drawblogDefaultBg){
			var imageObj = new Image();
			imageObj.onload = function(){
				drawblogCtx.drawImage(this, 0, 0, <?php echo $width;?>,<?php echo $height;?>);
			};
			imageObj.src = '<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/';?>' + drawblogDefaultBg;
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

	if (<?php 
			global $image_exists;
			echo $image_exists;
		?>){
		var imageObj = new Image();
		imageObj.onload = function(){
			drawblogCtx.drawImage(this, 0, 0);
		};
		imageObj.src = '<?php 
			global $post;
			echo content_url(). '/drawblog/images/'.drawblog_get_post_image($post->ID)
		?>';
	}

	var postForm = document.getElementById("post");
	var oldsubmit = postForm.onsubmit;
	postForm.onsubmit = function(){
		var formreturn = true;
		if (oldsubmit) {
			formreturn = oldsubmit(); // just in case some other plugin has a submit override
		}
		if (formreturn) {
			return drawblogPostSubmit();
		} else return false;
	};
	drawblogInitCanvas();
</script><br>
</div>