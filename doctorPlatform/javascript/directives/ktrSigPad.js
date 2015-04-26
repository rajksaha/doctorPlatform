/*
 * 
 */

app.directive('sigpad', function () {
    
	return {
	    templateUrl: 'javascript/directives/sigPad.html',
	    restrict: 'E',
	    scope : { 
	      signatureData: '=ngModel',
	      someCtrlFn: '&callbackFn'
	    },
	    require: 'ngModel',
	    link: function (scope,element,attr,ctrl) {
	      var sigPadAPI = $(element).signaturePad({
	                                  drawOnly:true //,
	                                  //validateFields:false //Removing this will fire the sigpad validation on form submit, but will not stop the form from submitting.
	                                });
	      
	      scope.someCtrlFn({sigData: document.getElementById("sigpad").toDataURL()});
	      // Assign signature pad data to ngModel
	      scope.$watch('signatureData', function (obj) {
	        scope.signatureData = sigPadAPI.getSignature();
	      });
	      
	      // Clear the signature pad
	      $(attr.clearBtn).on('click',function (e) {
	        sigPadAPI.clearCanvas();
	        scope.someCtrlFn({sigData: document.getElementById("sigpad").toDataURL()});
	      });
	      
	      // Validate signature pad.
	      // This isn't working. From what I can tell, the code never gets touched.
	      ctrl.$parsers.unshift(function(viewValue) {
	        if ( sigPadAPI.validateForm() ) {
	          // it is valid
	          ctrl.$setValidity('sigpad', true);
	          return viewValue;
	        } else {
	          // it is invalid, return undefined (no model update)
	          ctrl.$setValidity('sigpad', false);
	          return undefined;
	        }
	      });      
	      
	    }
	  };
	});

/**
 * Regenerate Signature Pad data as Base64 encoded PNG data.
 * This uses the getSignatureImage() function of the Signature Pad API.
 * It only works in modern browsers, and is flaky in IE.
 */
app.directive('regensigpad',function() {
  return {
    template: '<img ng-src="{{pic}}" />',
    restrict: 'E',
    scope: {sigdata:'@'},
    link: function (scope,element,attr,ctrl) {
      // When the sigdata attribute changes...
      attr.$observe('sigdata',function (val) {
        // ... create a blank canvas template and attach the signature pad plugin
        var sigPadAPI = $('<div class="sig sigWrapper"><canvas class="pad" width="436" height="120"></canvas></div>').signaturePad({
                          displayOnly: true
                        }); 
        // regenerate the signature onto the canvas
        sigPadAPI.regenerate(val);
        // convert the canvas to a PNG (Newer versions of Chrome, FF, and Safari only.)
        scope.pic = sigPadAPI.getSignatureImage();
      });
    }
  };
});  


