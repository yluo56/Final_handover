(function($){
	var page = 1;
	var last = false;

	$(document).ready(init);

	$(window).scroll(function() {
		if ($(window).scrollTop() == $(document).height() - $(window).height()) {
			if (!last) lastPostHandler();
		}
	});

	function init() {
		addMoreButton();
		createTiltViewer();
	}

	function addMoreButton() {
		$('table#theList').after('<div id="moreButton"> More... </div><div id="loadingMore">Loading...</div>');
		$('#moreButton').click(lastPostHandler);
		$('#loadingMore').hide();
	}

	function isThisiPhone() {
		var agent = navigator.userAgent.toLowerCase();
		var is_iphone = (agent.indexOf('iphone') != -1);
		var is_ipad = (agent.indexOf('ipad') != -1);
		return is_iphone || is_ipad;
	}

	function lastPostHandler() {
		page++;
		$('#moreButton').hide();
		$('#loadingMore').show();
		var postdata = {};
		postdata.page = page;

		$.post(DooodlVars.gallery_scroll, postdata, function(data) {
			if (data != "") {
				$("table#theList tr:last").after(data);
				$('#moreButton').show();
				$('#loadingMore').hide();
			} else {
				$('#moreButton').hide();
				$('#loadingMore').hide();
				last = true;
			}
		});
	}

	function createTiltViewer() {
		if (DooodlVars.tv.enabled == true) {
			var fo = new SWFObject(DooodlVars.plugin_root + "gallery/assets/TiltViewer.swf", "theviewer", "100%", "600px", "9.0.28", "#000000");

			// XML GALLERY OPTIONS
			// To use local images defined in an XML document, use this block
			fo.addVariable("useFlickr", "false");
			fo.addVariable("xmlURL", DooodlVars.tv.xml_url);
			fo.addVariable("maxJPGSize", "260");

			//GENERAL OPTIONS
			fo.addVariable("useReloadButton", "false");
			fo.addVariable("columns", "8");
			fo.addVariable("rows", "5");
			//fo.addVariable("showFlipButton", "true");
			fo.addVariable("showLinkButton", "true");
			fo.addVariable("linkLabel", "Visit the link!");
			fo.addVariable("frameColor", DooodlVars.tv.frame_color);
			fo.addVariable("backColor", DooodlVars.tv.back_color);
			fo.addVariable("bkgndInnerColor", DooodlVars.tv.bg_outer_color);
			fo.addVariable("bkgndOuterColor", DooodlVars.tv.bg_inner_color);
			//fo.addVariable("langGoFull", "Go Fullscreen");
			//fo.addVariable("langExitFull", "Exit Fullscreen");
			//fo.addVariable("langAbout", "About");

			// END TILTVIEWER CONFIGURATION OPTIONS

			fo.addParam("allowFullScreen", "true");
			fo.write("theviewer");
		}
	}
}(jQuery));