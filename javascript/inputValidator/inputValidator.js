validator = {
		
	// check valid integer 1, 15, 18
	isInteger : function(val) {
		var re = new RegExp("^[0-9]+$");
		return re.test(val);
	},
	
	isAlphaNum : function(val) {
		var re = new RegExp("^[0-9a-zA-Z]+$");
		return re.test(val);
	},
	
	isDecimalRange: function(val, min, max) {
		val = val.replace(/,/g, '');
		if(!isNaN(val) && (val.length > 0)) {
			if(val >= parseInt(min) && val <= parseInt(max)) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	},
	
	isIntRange: function(val, min, max) {
		var regx = new RegExp("^[0-9]+$");
		if(regx.test(val)) {
			if(parseInt(val) >= parseInt(min) && parseInt(val) <= parseInt(max)) {
				return true;
			} else {
				return false;
			}
		} else {
			if(isNaN(val) || (val.length == 0)) {
				return true;
			} else {
				return false;
			}
		}
	},

	// check valid decimal number 125, 125.50
	isDecimal : function(val) {
		val = val.replace(/,/g, '');
		return !isNaN(val) && (val.length > 0);
	},

	// check valid date mm/dd/yyyy
	isDate : function(val) {
		var dateaprts = val.split('/');
		var dt = new Date(dateaprts[2], dateaprts[0] - 1, dateaprts[1]);
		return (dt.getDate() == dateaprts[1] && dt.getMonth() == dateaprts[0] - 1 && dt.getFullYear() == dateaprts[2]);
	},

    // MM/yyyy format
    isExpDate : function(val) {
        var dateaprts = val.split('/');
        var dt = new Date(dateaprts[1], dateaprts[0] - 1, 1);
        return (dt.getMonth() == dateaprts[0] - 1 && dt.getFullYear() == dateaprts[1]);
    },

	// check valid email abc@yahoo.com
	isEmail : function(val) {
		var pattern  = /^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
		return pattern.test(val);
	},
	
	// check valid web adress www.kaitair.com
	isWebAdress : function(val) {
		// var pattern  = /((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.)[-a-z0-9]+.*)/i;
		
		//var pattern = /((?:^www\.)(?:[a-z0-9]+\.)[-a-z0-9]+.*)/i;
		var pattern = /^(http(s)?:\/\/)?(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/i;
				
		return pattern.test(val);
	},
	
	// check valid url www.url.com
	isUrl : function(val) {
	//	var pattern  = /([\w\.]+\.(?:com|cc|net|ru|org)[^,\s]*)/i;
		var pattern = /^(http(s)?:\/\/)?(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/i;
			
		return pattern.test(val);
	},
	
	markError : function(control, isValid) {
		if (isValid) {
			$(control).closest(".form-group").removeClass("has-error");
			return 0;
		} else {
			$(control).closest(".form-group").addClass("has-error");
			return 1;
		}

	},
	
	validateForm : function(_targetArea, _msgHolder, _msg) {
		
		var notValid = 0;
		var percent = 0;
		var rangeNum = 0;
		var email = 0;
		var url = 0;
		var alpha = 0;
		var phn = 0;
		var website = 0;
		var decimal = 0;
		var targetArea = "";
		var errMsg = "Please provide all required information";
		var desRange = "Please select a value which is greater then 0 and not more then 100";
		var email_msg = "Please write a proper email address";
		var decimal_msg = "Please enter numbers only";
		var url_msg = "Not a valid web address";
		var alpha_msg = "Please select an alphanumaric value(A-Z) and (0-9)";
		var phn_msg = "Please provide valid phone number";
		var webSite_msg = "Not a valid web address";
		var range = "";
		var msgHolder = "#lblMsg";
		
		if (_targetArea) {
			targetArea = _targetArea;
		} else {
			targetArea = $(document);
		}

		if (_msg) {
			errMsg = _msg;
		}

		if (_msgHolder) {
			msgHolder = _msgHolder;
		}
		
		$(targetArea).find("input.required").each(function(i) {
			var currValue = $.trim(this.value);
			notValid += validator.markError(this, currValue.length > 0);
		});
		
		$(targetArea).find("textarea.required").each(function(i) {
			var currValue = $.trim(this.value);
			notValid += validator.markError(this, currValue.length > 0);
		});
		
		$(targetArea).find("input.password").each(function(i) {
			notValid += validator.markError(this, this.value.length > 0);
		});
		
		$(targetArea).find("input.email").each(function(i) {
			var currValue = $.trim(this.value);
			email += validator.markError(this, validator.isEmail(currValue));
		});
		
		$(targetArea).find("input.url").each(function(i) {
			var currValue = $.trim(this.value);
			url += validator.markError(this, validator.isUrl(currValue));
		});
		
		$(targetArea).find("input.emailnr").each(function(i) {
		   var currValue = $.trim(this.value);
		   if (currValue.length > 0) {
			   email += validator.markError(this, validator.isEmail(currValue));
		   }
		});
		
		$(targetArea).find("select.required").each(function(i) {
			notValid += validator.markError(this, this.selectedIndex > 0);
		});
		
		$(targetArea).find("input.integer").each(function(i) {
			var currValue = $.trim(this.value);
			currValue = currValue.replace(/,/g, "");
			notValid += validator.markError(this, (validator.isInteger(currValue) && parseInt(currValue) > 0));
		});
		
		$(targetArea).find("input.alphaNum").each(function(i) {
			var currValue = $.trim(this.value);
			currValue = currValue.replace(/,/g, "");
			alpha += validator.markError(this, (validator.isAlphaNum(currValue)));
		});
		
		$(targetArea).find("input.alphaNumnr").each(function(i) {
			var currValue = $.trim(this.value);
			currValue = currValue.replace(/,/g, "");
			if (currValue.length < 1) {
				currValue = "0";
			}
			alpha += validator.markError(this, (validator.isAlphaNum(currValue)));
		});
		
		$(targetArea).find("input.integernr").each(function(i) {
			var currValue = $.trim(this.value);
			currValue = currValue.replace(/,/g, "");
			if (currValue.length < 1) {
				currValue = "0";
			}
			notValid += validator.markError(this, validator.isInteger(currValue));
		});
		
		$(targetArea).find("input.decrange").each(function(i) {
			var currValue = $.trim(this.value);
			var minValue = $(this).attr("minval");
			var maxValue = $(this).attr("maxval");
			currValue = currValue.replace(/,/g, "");
			percent += validator.markError(this, validator.isDecimalRange(currValue, minValue, maxValue) );
		});
		
		$(targetArea).find("input.intrange").each(function(i) {
			var currValue = $.trim(this.value);
			var minValue = $(this).attr("minval");
			var maxValue = $(this).attr("maxval");
			currValue = currValue.replace(/,/g, "");
			// if value is blank then it will catch by required field
			if(isNaN(currValue) || (currValue.length == 0)) {
				return;
			}
			rangeNum += validator.markError(this, validator.isIntRange(currValue, minValue, maxValue));
			if(rangeNum > 0){
				range = "Please select a value with in this range " + minValue +" to " + maxValue;
			}
		});
		
		$(targetArea).find("input.decimal").each(function(i) {
			var currValue = $.trim(this.value);
			currValue = currValue.replace(/,/g, "");
			var isValid = false;
			if(currValue == ""){
				notValid++;
				validator.markError(this, false)
			}else{
				if ($(this).hasClass('negative')){
					isValid = (validator.isDecimal(currValue) && parseFloat(currValue) != 0);
				}else{
					isValid = (validator.isDecimal(currValue) && parseFloat(currValue) > 0);
				}
				decimal += validator.markError(this, isValid);
			}
			
		});
		
		$(targetArea).find("input.decimalnr").each(function(i) {
			var currValue = $.trim(this.value);
			currValue = currValue.replace(/,/g, "");
			if (currValue.length < 1) {
				currValue = "0";
			}
			notValid += validator.markError(this, validator.isDecimal(currValue));
		});
		
		$(targetArea).find("input.date").each(function(i) {
			var currValue = $.trim(this.value);
			notValid += validator.markError(this, validator.isDate(currValue));
		});
		
		$(targetArea).find("input.phn").each(function(i) {
			var currValue = $.trim(this.value);
			currValue = currValue.replace(/-/g, "");
			currValue = currValue.replace(/\+/g, ""); 
			if (currValue.length > 0) {
				phn += validator.markError(this, (validator.isInteger(currValue) && parseInt(currValue) > 0));
			}
		});
		
		$(targetArea).find("input.website").each(function(i) {
			var currValue = $.trim(this.value);
			
			if($(this).hasClass('required')){
				website += validator.markError(this, validator.isWebAdress(currValue));
			}else{
				if(currValue.length > 0){
					website += validator.markError(this, validator.isWebAdress(currValue));
				}else{
				}
			}
		});
		
		
			
		$(targetArea).find("input.datenr").each(function(i) {
			var currValue = $.trim(this.value);
			if (currValue.length > 0) {
				notValid += validator.markError(this, validator.isDate(currValue));
			}
		});

        $(targetArea).find("input.expdatenr").each(function(i) {
            var currValue = $.trim(this.value);
            if (currValue.length > 0) {
                notValid += validator.markError(this, validator.isExpDate(currValue));
            } else {
                notValid += validator.markError(this, true);      // clear error mark
            }
        });

		if (notValid > 0) {
			$(msgHolder).html(errMsg).parent().removeClass("hidden").addClass("alert-danger");
			if (! _targetArea) {
				$(document).scrollTop(0);
			}
			return false;
		} else if(percent > 0){
			$(msgHolder).html(desRange).parent().removeClass("hidden").addClass("alert-danger");
			if (! _targetArea) {
				$(document).scrollTop(0);
			}
			return false;
		}else if(rangeNum > 0){
			$(msgHolder).html(range).parent().removeClass("hidden").addClass("alert-danger");
			if (! _targetArea) {
				$(document).scrollTop(0);
			}
			return false;
		}else if(email > 0){
			$(msgHolder).html(email_msg).parent().removeClass("hidden").addClass("alert-danger");
			if (! _targetArea) {
				$(document).scrollTop(0);
			}
			return false;
		}else if(url > 0){
			$(msgHolder).html(url_msg).parent().removeClass("hidden").addClass("alert-danger");
			if (! _targetArea) {
				$(document).scrollTop(0);
			}
			return false;
		}else if(alpha > 0){
			$(msgHolder).html(alpha_msg).parent().removeClass("hidden").addClass("alert-danger");
			if (! _targetArea) {
				$(document).scrollTop(0);
			}
			return false;
		}else if(phn > 0){
			$(msgHolder).html(phn_msg).parent().removeClass("hidden").addClass("alert-danger");
			if (! _targetArea) {
				$(document).scrollTop(0);
			}
			return false;
		}else if(website > 0){
			$(msgHolder).html(webSite_msg).parent().removeClass("hidden").addClass("alert-danger");
			if (! _targetArea) {
				$(document).scrollTop(0);
			}
			return false;
		}else if(decimal > 0){
			$(msgHolder).html(decimal_msg).parent().removeClass("hidden").addClass("alert-danger");
			if (! _targetArea) {
				$(document).scrollTop(0);
			}
			return false;
		}else {
			$(msgHolder).html("").addClass("hidden").removeClass("alert-danger");
			return true;
		}
		
	},
	
	isFormValid : function(_targetArea) {
		
		var notValid = 0;
		
		if (_targetArea) {
			targetArea = _targetArea;
		} else {
			targetArea = $(document);
		}
		
		$(targetArea).find("input.required").each(function(i) {
			var currValue = $.trim(this.value);
			notValid += ((currValue.length > 0) ? 0 : 1);
		});
		
		$(targetArea).find("textarea.required").each(function(i) {
			var currValue = $.trim(this.value);
			notValid += ((currValue.length > 0) ? 0 : 1);
		});
		
		$(targetArea).find("input.password").each(function(i) {
			notValid += ((this.value.length > 0) ? 0 : 1);
		});
		
		$(targetArea).find("input.email").each(function(i) {
			var currValue = $.trim(this.value);
			notValid += (validator.isEmail(currValue) ? 0 : 1);
		});
		
		$(targetArea).find("input.url").each(function(i) {
			var currValue = $.trim(this.value);
			notValid += (validator.isUrl(currValue) ? 0 : 1);
		});
		
		$(targetArea).find("input.emailnr").each(function(i) {
		   var currValue = $.trim(this.value);
		   if (currValue.length > 0) {
			   notValid += (validator.isEmail(currValue) ? 0 : 1);
		   }
		});
		
		$(targetArea).find("select.required").each(function(i) {
			notValid += ((this.selectedIndex > 0) ? 0 : 1);
		});
		
		$(targetArea).find("input.integer").each(function(i) {
			var currValue = $.trim(this.value);
			currValue = currValue.replace(/,/g, "");
			notValid += ((validator.isInteger(currValue) && parseInt(currValue)) ? 0 : 1);
		});
		
		$(targetArea).find("input.alphaNum").each(function(i) {
			var currValue = $.trim(this.value);
			currValue = currValue.replace(/,/g, "");
			notValid += (validator.isAlphaNum(currValue) ? 0 : 1);
		});
		
		$(targetArea).find("input.integernr").each(function(i) {
			var currValue = $.trim(this.value);
			currValue = currValue.replace(/,/g, "");
			if (currValue.length < 1) {
				currValue = "0";
			}
			notValid += (validator.isInteger(currValue) ? 0 : 1);
		});
		
		$(targetArea).find("input.decrange").each(function(i) {
			var currValue = $.trim(this.value);
			var minValue = $(this).attr("minval");
			var maxValue = $(this).attr("maxval");
			currValue = currValue.replace(/,/g, "");
			notValid += ((validator.isDecimalRange(currValue, minValue, maxValue)) ? 0 : 1);
		});
		
		$(targetArea).find("input.intrange").each(function(i) {
			var currValue = $.trim(this.value);
			var minValue = $(this).attr("minval");
			var maxValue = $(this).attr("maxval");
			currValue = currValue.replace(/,/g, "");
			// if value is blank then it will catch by required field
			if(isNaN(currValue) || (currValue.length == 0)) {
				notValid += 0;
			} else {
				notValid += ((validator.isIntRange(currValue, minValue, maxValue)) ? 0 : 1);
			}
		});
		
		$(targetArea).find("input.decimal").each(function(i) {
			var currValue = $.trim(this.value);
			currValue = currValue.replace(/,/g, "");
			var isValid = false;
			if ($(this).hasClass('negative'))
				isValid = (validator.isDecimal(currValue) && parseFloat(currValue) != 0);
			else
				isValid = (validator.isDecimal(currValue) && parseFloat(currValue) > 0);
			
			notValid += ((isValid == true) ? 0 : 1);
		});
		
		$(targetArea).find("input.decimalnr").each(function(i) {
			var currValue = $.trim(this.value);
			currValue = currValue.replace(/,/g, "");
			if (currValue.length < 1) {
				currValue = "0";
			}
			notValid += ((validator.isDecimal(currValue)) ? 0 : 1);
		});
		
		$(targetArea).find("input.date").each(function(i) {
			var currValue = $.trim(this.value);
			notValid += ((validator.isDate(currValue)) ? 0 : 1);
		});
		
		$(targetArea).find("input.datenr").each(function(i) {
			var currValue = $.trim(this.value);
			if (currValue.length > 0) {
				notValid += ((validator.isDate(currValue)) ? 0 : 1);
			}
		});

        $(targetArea).find("input.expdatenr").each(function(i) {
            var currValue = $.trim(this.value);
            if (currValue.length > 0) {
                notValid += ((validator.isExpDate(currValue)) ? 0 : 1);
            }
        });

		$(targetArea).find("input.phn").each(function(i) {
			var currValue = $.trim(this.value);
			currValue = currValue.replace(/-/g, "");
			currValue = currValue.replace(/\+/g, ""); 
			if (currValue.length > 0) {
				notValid += ((validator.isInteger(currValue) && parseInt(currValue)) ? 0 : 1);
			}
		});
		
		$(targetArea).find("input.website").each(function(i) {
			var currValue = $.trim(this.value);
			
			if($(this).hasClass('required')){
				notValid += ((this, validator.isWebAdress(currValue)) ? 0 : 1);
			}else{
				if(currValue.length > 0){
					notValid += ((this, validator.isWebAdress(currValue)) ? 0 : 1);
				}
			}
		});
		
		
		if (notValid > 0) {
			return false;
		}else {
			return true;
		}
		
	}
		
};

var keyHandler = {
		
	initIntegerFields: function() {
		$(document).on("keydown", "input.integer, input.intrange, input.integernr", function(event) {
			// Allow backspace, delete, >, <, enter, tab, etc.
			if ((event.keyCode == null) || (event.keyCode == 0) || (event.keyCode == 8) || (event.keyCode == 9) || (event.keyCode == 13) || (event.keyCode == 27) || (event.keyCode == 33) || (event.keyCode == 34) || (event.keyCode == 35) || (event.keyCode == 36) || (event.keyCode == 37) || (event.keyCode == 38) || (event.keyCode == 39) || (event.keyCode == 40) || (event.keyCode == 46)) {
			}
			else {
				// Ensure that it is a number and stop the keypress
				if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105)) {
				}
				else {
					event.preventDefault();
				}
			}
		});
	},
	
	initPhnFields: function() {
		$(document).on("keydown", "input.phn", function(event) {
			// Allow backspace, delete, >, <, enter, tab, etc.
			if ((event.keyCode == null) || (event.keyCode == 107) || (event.keyCode == 109) ||(event.keyCode == 0) || (event.keyCode == 8) || 
				(event.keyCode == 9) || (event.keyCode == 13) || (event.keyCode == 27) || (event.keyCode == 33) || (event.keyCode == 34) || 
				(event.keyCode == 35) || (event.keyCode == 36) || (event.keyCode == 37) || (event.keyCode == 38) || (event.keyCode == 39) || 
				(event.keyCode == 40) || (event.keyCode == 46) || (event.keyCode == 86) || (event.keyCode == 67) || (event.keyCode == 173) || (event.keyCode == 61)) {
			}
			else {
				// Ensure that it is a number and stop the keypress
				if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 16) {
				}else {
					event.preventDefault();
				}
			}
		});
	},
	
	initDates: function() {
		$(document).on("keydown", "input.date, input.datenr, input.expdatenr", function(event) {
			if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 ||
				event.keyCode == 46 || event.keyCode == 191 || (event.keyCode >= 48 && event.keyCode <= 57)){
				// Allow numbers, tab, backspace, delete, left & right arrow, and /
			}
			else {
				event.preventDefault();
			}
		});
	},
	
	initDecimalFields: function() {
		$(document).on("keydown", "input.decimal, input.decimalnr, input.decimalallowzero, input.decrange", function(event) {
			// Allow backspace, delete, >, <, enter, tab, etc.
			if ((event.keyCode == null) || (event.keyCode == 0) || (event.keyCode == 8) || (event.keyCode == 9) || (event.keyCode == 13) || (event.keyCode == 27) || (event.keyCode == 33) || (event.keyCode == 34) || (event.keyCode == 35) || (event.keyCode == 36) || (event.keyCode == 37) || (event.keyCode == 38) || (event.keyCode == 39) || (event.keyCode == 40) || (event.keyCode == 46) || (event.keyCode == 190) || (event.keyCode == 110) ||
					event.keyCode == 173 || event.keyCode == 109 || event.keyCode == 189 || event.keyCode == 86 || event.keyCode == 90 || event.keyCode == 67) {

				if ((event.keyCode == 190) || (event.keyCode == 110)) {
					var fieldValue = this.value;
					var fieldArray = fieldValue.split(/\./);
					if (fieldArray.length > 1) {
						event.preventDefault();
					}
				}
				if (event.keyCode == 109 || event.keyCode == 173 || event.keyCode == 189) {  // negative sign
					if (!$(this).hasClass('negative'))
						event.preventDefault();
					else
					{
						var fieldValue = this.value;
						var fieldArray = fieldValue.split(/-/);
						if (fieldArray.length > 1)
							event.preventDefault();
					}
				}
			}
			else {
				// Ensure that it is a number and stop the keypress
				if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105)) {
					//this.value = addCommas(this.value);
					var fieldValue = this.value;
					var fieldArray = fieldValue.split(/\./);
					if (fieldArray.length > 1) {
						if(fieldArray[1].length >= 2) {
							event.preventDefault(); // should allow only 2 decimal places
						}
					}
				}
				else {
					event.preventDefault();
				}
			}
		});
	}
		
};


$(document).ready(function() {
	keyHandler.initIntegerFields();
	keyHandler.initDecimalFields();
	keyHandler.initPhnFields();
	keyHandler.initDates();
	
});