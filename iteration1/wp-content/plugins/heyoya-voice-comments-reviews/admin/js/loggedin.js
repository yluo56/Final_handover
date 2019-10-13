heyoyaLoggedIn = function(){
	var $, heyoyaReportService, logoutUrl;
	function init(jqueryObj, heyoyaReportObj){
		$ = jqueryObj;		
		heyoyaReportService = heyoyaReportObj;
		logoutUrl = "https://admin.heyoya.com/client-admin/login/logout.heyoya?";
		
		initMessaging();
		loadIframe();
	}
	
	function initMessaging(){
		if (!window.heyoyaMessaging)
			return;
		
		window.heyoyaMessaging.init(messagingCallback);
	}
	
	function messagingCallback(eventData){
		if (!eventData || !eventData.action || !eventData.value)
			return;

		var requestData = {};		
		switch (eventData.action){
			case "hey_logout":
				requestData.action = "logout";
				
				var oImg = document.createElement("img");
		        oImg.setAttribute('src', logoutUrl + "&r1=" + Math.random() + "&r2=" + Math.random() );
		        oImg.setAttribute('width', '1px');
		        oImg.setAttribute('height', '1px');
		        document.body.appendChild(oImg);

				heyoyaReportService.report("wp-admin-logout", true);		        		        
		        
				break;
				
		}		
		
		if (requestData != null){
			$.post(ajaxurl, requestData, function(response) {
				if ($.trim(response) == "1"){
					
					heyoyaReportService.report("wp-admin-" + requestData.action + "-success", requestData.action == "logout");
				} else 
					heyoyaReportService.report("wp-admin-" + requestData.action + "-error");
				
				if (requestData.action == "logout" && $.trim(response) == "1")
					window.location.reload(true); 
			});
		}		
	}
	
	function loadIframe(){
		var url = "https://admin.heyoya.com/client-admin/installation.heyoya?ak=" + $("#heyoyaContainer").attr("aa") + "&at=wp&v=1&returnUrl=installation.heyoya"; 
		
		$("#heyoyaContainer").append("<iframe style=\"width:" + ($("#heyoyaContainer").width()-17) + "px;height:1200px;\" src=\"" + url + "\"></iframe>");
		
		heyoyaReportService.report("wp-loggedin-impression");
	}
	
	
	
	return{
		init:init
	}

}();

jQuery(function(){
	heyoyaReport.init(jQuery);
	heyoyaLoggedIn.init(jQuery, heyoyaReport);
});
