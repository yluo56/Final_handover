heyoyaLoggedOut = function(){
	var $, heyoyaReportService;	
	var regEmail = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	var sourcePath = "https://www.heyoya.com/afsc?_rnd=";
	var _hsi, _hssi, _hclki, _hcpmi, _hrt, _hci, _hcsi;
	_hsi = _hssi = _hclki = _hcpmi = _hrt = _hci = _hcsi = "";

	function init(jqueryObj, heyoyaReportObj){
		$ = jqueryObj;
		heyoyaReportService = heyoyaReportObj;
		bindEvents();

		
		initialReport();
		
		if (heyoyaErrorCode != undefined && heyoyaErrorCode != 0){
			heyoyaReportService.report("wp-error-" + heyoyaErrorCode);
			heyoyaErrorCode = 0;
		}
		
		askForSourcePath();
	}
	
	function bindEvents(){
	
		$("#heyoyaSignUpDiv form").on("submit", function(){
			$(this).find("input[type=submit]").val("Please wait...");			
			heyoyaReportService.report("wp-signup-form-submit");
			
			if (validateFields($("#heyoyaSignUpDiv"))){								
				heyoyaReportService.report("wp-signup-form-submit-validation-success", true);
				return true;
			} else {
				$(this).find("input[type=submit]").val($(this).find("input[type=submit]").attr("original_value"));
				heyoyaReportService.report("wp-signup-form-submit-validation-failure");				
				return false;
			}			
		});
		
		$("#heyoyaLoginDiv form").on("submit", function(){
			$(this).find("input[type=submit]").val("Please wait...");		
			heyoyaReportService.report("wp-login-form-submit");
			
			if (validateFields($("#heyoyaLoginDiv"))){
				heyoyaReportService.report("wp-login-form-submit-validation-success", true);
				return true;
			} else {
				$(this).find("input[type=submit]").val($(this).find("input[type=submit]").attr("original_value"));
				heyoyaReportService.report("wp-login-form-submit-validation-failure");
				return false;
			}						 
		});
		
		$("#heyoyaLoginDiv .alternate a").on("click", function(){
			$("#heyoyaAdmin .updated span").addClass("invisible");
			$("#heyoyaAdmin .updated").addClass("invisible");
			
			$("#heyoyaLoginDiv").addClass("invisible");
			$("#heyoyaSignUpDiv").removeClass("invisible");
			
			heyoyaReportService.report("wp-change-mode-login2signup");
		});
		
		$("#heyoyaSignUpDiv .alternate a").on("click", function(){
			$("#heyoyaAdmin .updated span").addClass("invisible");
			$("#heyoyaAdmin .updated").addClass("invisible");
			$("#heyoyaSignUpDiv").addClass("invisible");
			$("#heyoyaLoginDiv").removeClass("invisible");
			
			heyoyaReportService.report("wp-change-mode-signup2login");
		});


	}
	
	function initialReport(){
		if ($("#heyoyaLoginDiv").hasClass("invisible"))
			heyoyaReportService.report("wp-signup-impression");
		else
			heyoyaReportService.report("wp-login-impression");		
	}
	
	function validateFields(baseObj){		
		if (!baseObj)
			return false;
			
		baseObj.find(".updated").addClass("invisible");
		baseObj.find(".updated span").addClass("invisible");

		var validated = true;			

		var emailIsMissing = false;
		var emailInput = baseObj.find(".login_email");
		if (emailInput.val() == ""){
			validated = false;
			emailIsMissing = true;
			baseObj.find(".email_missing").removeClass("invisible");
		}
		
		if (!emailIsMissing && !regEmail.test(emailInput.val())){			
			validated = false;
			baseObj.find(".email_invalid").removeClass("invisible");	
		}

		var passwordInput = baseObj.find(".login_password");
		if (passwordInput.val() == ""){
			validated = false;
			baseObj.find(".password_missing").removeClass("invisible");	
		}

		
		var nameInput = baseObj.find(".signup_fullname");
		if (nameInput.length != 0 && nameInput.val() == ""){
			validated = false;
			baseObj.find(".name_missing").removeClass("invisible");	
		}
		
		if (!validated)
			baseObj.find(".updated").removeClass("invisible");
		
		return validated;

	}
	
	function askForSourcePath(){
		if (!window.heyoyaMessaging)
			return;
		
		window.heyoyaMessaging.init(messagingCallback);
		
		var url = sourcePath; 
		$("#heyoyaAdmin").append("<iframe frameborder=0 style=\"width:10px;height:10px;position:absolute;top:-1000px;border:0;\" src=\"" + url + Math.random() + "\"></iframe>");
	}
	
	function messagingCallback(eventData){
		if (!eventData || !eventData.action || !eventData.value)
			return;

		switch (eventData.action){
			case "hey_afsc":
				var afscResponse;
				try{
					afscResponse = $.parseJSON(eventData.value);
				} catch (err){
					afscResponse = null;
					heyoyaReportService.report("wp-afsc-failed");
					errorReport(err);
				}
				
				if (afscResponse == null || afscResponse == "")
					return;
			
				if (afscResponse._hsi){
					_hsi = afscResponse._hsi;
					$("#heyoyaSignUpDiv form").append("<input type=\"hidden\" name=\"heyoya_options[_hsi]\" id=\"_hsi\" value=\"" + afscResponse._hsi + "\">");		
				}
				
				if (afscResponse._hssi){	
					_hssi = afscResponse._hssi;
					$("#heyoyaSignUpDiv form").append("<input type=\"hidden\" name=\"heyoya_options[_hssi]\" id=\"_hssi\" value=\"" + afscResponse._hssi + "\">");		
				}
			
				if (afscResponse._hclki){
					_hclki = afscResponse._hclki;
					$("#heyoyaSignUpDiv form").append("<input type=\"hidden\" name=\"heyoya_options[_hclki]\" id=\"_hclki\" value=\"" + afscResponse._hclki + "\">");		
				}
			
				if (afscResponse._hcpmi){
					_hcpmi = afscResponse._hcpmi;
					$("#heyoyaSignUpDiv form").append("<input type=\"hidden\" name=\"heyoya_options[_hcpmi]\" id=\"_hcpmi\" value=\"" + afscResponse._hcpmi + "\">");		
				}
			
				if (afscResponse._hrt){
					_hrt = afscResponse._hrt;    
					$("#heyoyaSignUpDiv form").append("<input type=\"hidden\" name=\"heyoya_options[_hrt]\" id=\"_hrt\" value=\"" + afscResponse._hrt + "\">");		
				}
				
				if (afscResponse._hci){
					_hci = afscResponse._hci;    
					$("#heyoyaSignUpDiv form").append("<input type=\"hidden\" name=\"heyoya_options[_hci]\" id=\"_hci\" value=\"" + afscResponse._hci + "\">");		
				}
				
				if (afscResponse._hcsi){
					_hcsi = afscResponse._hcsi;    
					$("#heyoyaSignUpDiv form").append("<input type=\"hidden\" name=\"heyoya_options[_hcsi]\" id=\"_hcsi\" value=\"" + afscResponse._hcsi + "\">");		
				}
				
				break;
		}		
		
	}
	
	function errorReport(msg){
		var additionalParameters = {};

		if (msg != undefined && msg != null && ( Object.prototype.toString.call( msg ) === "[object ErrorEvent]" || Object.prototype.toString.call( msg ) === "[object Event]" )){
            if (msg.message)
                additionalParameters.errorMessage = msg.message;

            if (msg.filename)
                additionalParameters.errorUrl = msg.filename ;

            if (msg.lineno)
                additionalParameters.errorLine = msg.lineno ;

            if (msg.colno)
                additionalParameters.errorColumn = msg.colno ;

            if (msg.error)
                additionalParameters.error = msg.error;
        } else {

            if (msg)
                additionalParameters.errorMessage = msg;

            if (url)
                additionalParameters.errorUrl = url;

            if (line)
                additionalParameters.errorLine = line;

            if (col)
                additionalParameters.errorColumn = col;

            if (error)
                additionalParameters.error = error;
        }

        if (additionalParameters.errorMessage && (additionalParameters.errorMessage.toLowerCase() == "script error." || additionalParameters.errorMessage.toLowerCase() == "script error") && !additionalParameters.errorUrl)
            return;

        heyoyaReportService.report("ClientError", additionalParameters);

	}

	
	return{
		init:init
	}
}();



jQuery(function(){
	heyoyaReport.init(jQuery);
	heyoyaLoggedOut.init(jQuery, heyoyaReport);
});