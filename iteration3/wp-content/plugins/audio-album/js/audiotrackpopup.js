jQuery(document).ready(function($) {

	$('.info-popup').click(function() {

		var w = $(this).data('width');
		var h = $(this).data('height');

		var left = (screen.width/2) - (w/2);
		var top = (screen.height/2) - (h/2);

		var NWin = window.open($(this).prop('href'),'','scrollbars=1,width=' + w + ',height=' + h + ',top=' + top + ',left=' + left);

		if (window.focus) {
				NWin.focus();
			}

		return false;
	});
});