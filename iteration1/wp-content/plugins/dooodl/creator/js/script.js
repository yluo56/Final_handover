(function($) {
	var canvas = false;
	var $canvas = false;
	var sketcher = false;

	var new_brush_url = "";
	var cache_brush_img = false;
	var cache_brush_url = "";
	var selected_color = "#000000";

	var $brushholder = false;

	var ctx = false;
	var WIDTH = 250;
	var HEIGHT = 250;
	var CANVASID = "canvas";

	$(document).ready(init);

	function init() {
		startCanvas();
		startColorPicker();
		startBrushPicker();
		startButtons();
		doIEStuff();
	}

	function gotoStep2() {
		$('#toolkit').hide();
		$('#form').show();
		sketcher.setEnabled(false);
		$canvas.css('cursor', 'auto').css('background-image', 'none');
	}

	function gotoStep1() {
		$('#toolkit').show();
		$('#form').hide();
		sketcher.setEnabled(true);
		updateBrush();
	}

	function doIEStuff() {
		if ($('html').hasClass('ie9')) {
			$brushholder.addClass('ie');
			$canvas.addClass('ie');
		}
	}

	function startButtons() {
		$('#btnSave').click(onSave);
		$('#btnReset').click(onReset);
		$('#btnOk').click(onOk);
		$('#btnBack').click(onBack);

	}

	function onOk() {
		var data = {};
		data.name = $('#name').val();
		data.title = $('#title').val();
		data.url = $('#url').val();
		data.description = $('#description').val();
		data.imagedata = sketcher.toDataURL();

		$('#form').fadeOut(400, null, function() {
			$('#waiting').fadeIn();
			$.post(DooodlVars.post_new, data, function(response, status, xhr) {
				console.log(response);
				$('#waiting').fadeOut(400, null, function() {
					$('#success').fadeIn(400);
				});
			});
		});

		return false;
	}

	function onBack() {
		gotoStep1();
		return false;
	}

	function onSave() {
		gotoStep2();
		return false;
	}

	function onReset() {
		var ok = confirm('This will remove your current drawing. This cannot be undone. \nAre you sure?');
		if (ok) {
			resetToRandomBGColor();
		}
		return false;
	}

	function resetToRandomBGColor() {
		var r = Math.floor(Math.random() * 256);
		var g = Math.floor(Math.random() * 256);
		var b = Math.floor(Math.random() * 256);
		var color = rgbToHex(r, g, b);
		resetBG(color);
	}

	function startColorPicker() {
		$('div#color ul li a').click(function() {
			var $this = $(this);
			setBrushColor($this.data('color'));
			return false;
		});
	}

	function startBrushPicker() {
		$('div#brush ul li a').click(function() {
			var $this = $(this);
			var type = $this.data('type');
			var size = $this.data('size');

			var url = DooodlVars.plugin_root + "creator/images/brush_" + type + "_" + size + ".png";
			setBrushImage(url);
			return false;
		});
	}

	function setBrushImage(brushurl) {
		new_brush_url = brushurl;
		updateBrush();
	}

	function setBrushColor(color) {
		selected_color = color;
		updateBrush();
	}

	function updateBrushholder(url, w, h) {
		var nx = Math.round(w / -2) + 3;
		var ny = Math.round(h / -2) + 3;

		//$canvas.css('cursor','url("'+ url +'") '+ nx +' '+ ny +',auto');
		if (!$brushholder.hasClass('ie')) {
			$brushholder
				.css('background-image', 'url("' + url + '")')
				.css('margin-left', nx + 'px')
				.css('margin-top', ny + 'px')
				.css('width', w + "px")
				.css('height', h + "px");
			$canvas.css('cursor', 'none')
		}
	}

	function updateBrush() {
		if (new_brush_url == cache_brush_url) {
			var brush = document.createElement('canvas');
			brush.width = cache_brush_img.width;
			brush.height = cache_brush_img.height;
			brushctx = brush.getContext('2d');

			//color brush
			brushctx.fillStyle = selected_color;
			brushctx.fillRect(0, 0, brush.width, brush.height);
			brushctx.globalCompositeOperation = "destination-atop";
			brushctx.drawImage(cache_brush_img, 0, 0);

			updateBrushholder(brush.toDataURL(), brush.width, brush.height);
			sketcher.setBrush(brush);
		} else {
			var brushimg = new Image();
			brushimg.src = new_brush_url;
			cache_brush_url = new_brush_url;
			brushimg.onload = function() {
				cache_brush_img = brushimg;

				//create brush
				var brush = document.createElement('canvas');
				brush.width = brushimg.width;
				brush.height = brushimg.height;
				brushctx = brush.getContext('2d');

				//color brush
				brushctx.fillStyle = selected_color;
				brushctx.fillRect(0, 0, brush.width, brush.height);
				brushctx.globalCompositeOperation = "destination-atop";
				brushctx.drawImage(cache_brush_img, 0, 0);

				updateBrushholder(brush.toDataURL(), brush.width, brush.height);
				sketcher.setBrush(brush);
			}
		}
	}

	function startCanvas() {
		$canvas = $(CANVASID);
		$brushholder = $('#brushholder').hide();

		canvas = document.getElementById(CANVASID);
		ctx = canvas.getContext("2d");
		canvas.width = WIDTH;
		canvas.height = HEIGHT;

		$canvas.mouseover(function() {
			if (sketcher.getEnabled()) {
				$brushholder.show()
			}
		});
		$canvas.mouseout(function() {
			$brushholder.hide()
		});

		resetToRandomBGColor();

		sketcher = new Sketcher(CANVASID, null, $);
		setBrushImage(DooodlVars.plugin_root + 'creator/images/brush_round_5.png');
		$(document).mousemove(onMousemove);

	}

	function onMousemove(e) {
		$brushholder.css('top', e.clientY + "px").css('left', e.clientX);
	}

	function resetBG(color) {
		ctx.fillStyle = color;
		ctx.fillRect(0, 0, WIDTH, HEIGHT);
	}

	function rgbToHex(r, g, b) {
		var hex = r.toString(16);
		var hr = hex.length == 1 ? "0" + hex : hex;

		hex = g.toString(16);
		var hg = hex.length == 1 ? "0" + hex : hex;

		hex = b.toString(16);
		var hb = hex.length == 1 ? "0" + hex : hex;

		return "#" + hr + hg + hb;
	}


	function hexToRgb(hex) {
		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
		return result ? {
			r: parseInt(result[1], 16),
			g: parseInt(result[2], 16),
			b: parseInt(result[3], 16)
		} : null;
	}

})(jQuery)