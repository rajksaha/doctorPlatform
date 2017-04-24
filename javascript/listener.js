app.run(function($rootScope, $http, $location) {

	(function() {
		// Retrieve configuration values
    })();
	
	/**
     * Holds the logged-in user
     */
    $rootScope.user = {};
    
    /**
     * Holds the navigation items that are displayed on header
     */
    $rootScope.mainNavItem = "";
    $rootScope.mainNavURL = "";
    $rootScope.subNavItem = "";
    
    if(!$rootScope.isVisitedScreen) {
    	$rootScope.isVisitedScreen = false;
    }

    /**
     * Holds the selected property
     */
    $rootScope.property = {};

    /**
     * Holds all the requests which failed due to 401 response.
     */
    $rootScope.requests401 = [];

    $rootScope.$on('event:loginRequired', function () {
        // Hack way to stop blockUI from showing the loading animation

        $rootScope.requests401 = [];

        if ($location.path().indexOf("/login") == -1) {
            originalLocation = $location.path();

            //$rootScope.error = "Please enter a valid username / password";
            $rootScope.warn = true;
            $rootScope.warnMessage = "Please enter a valid username / password";
        }

        $location.path('/login');
    });

    
   
    /**
     * On 'event:loginConfirmed', resend all the 401 requests.
     */
    $rootScope.$on('event:loginConfirmed', function () {
        var i,
            requests = $rootScope.requests401,
            retry = function (req) {
                $http(req.config).then(function (response) {
                    req.deferred.resolve(response);
                });
            };

        for (i = 0; i < requests.length; i += 1) {
            retry(requests[i]);
        }
        $rootScope.requests401 = [];
    });

    /**
     * On 'event:loginRequest' send credentials to the server.
     */
    $rootScope.$on('event:loginRequest', function (event, username, password, callback) {

    	$rootScope.user = {};
    	$rootScope.property = {};

        // set the basic authentication header that will be parsed in the next request and used to authenticate
        httpHeaders.common['Authorization'] = 'Basic ' + Base64Service.encode(username + ':' + password);

        // This only handle the success
        // Error handling happen in config.js
        $http.post('rest/login/authenticate').success(function(response) {
        	        	        	
        	if (callback && typeof(callback) === "function") {
        		callback();
        	}
        	delete httpHeaders.common.Authorization;
            $rootScope.$broadcast('event:loginConfirmed');

            //delete $rootScope.error;
            delete $rootScope.warn;
            delete $rootScope.warnMessage;
        });
    });

    /**
     * On 'logoutRequest' invoke logout on the server.
     */
    $rootScope.$on('event:logoutRequest', function () {
        delete $rootScope.error;
        delete $rootScope.errorMessage;

        delete $rootScope.warn;
        delete $rootScope.warnMessage;

        delete $rootScope.success;
        delete $rootScope.successMessage;

        delete httpHeaders.common.Authorization;
        // We need to route back to menu upon re-login
        originalLocation = "/menu";
    });

    /**
     * On 'clearStatus' to clear the error and warning status
     */
    $rootScope.$on('event:clearStatus', function () {
        delete $rootScope.error;
        delete $rootScope.errorMessage;

        delete $rootScope.warn;
        delete $rootScope.warnMessage;

        delete $rootScope.success;
        delete $rootScope.successMessage;
    });

    $rootScope.$on('event:hideMenu', function () {
        $rootScope.hideMenu = !$rootScope.hideMenu;
    });

    /**
     * clear status in guest booking
     */
    $rootScope.$on('event:clearStatusInBooking', function () {
        delete $rootScope.successStatusInGuestBooking;
        delete $rootScope.successMessage;

        delete $rootScope.errorStatusInGuestBooking;
        delete $rootScope.errorMessage;

    });

    /**
     * On 'errorStatus' error happen
     */
    $rootScope.$on('event:errorStatus', function (event, messageTxt) {
        $rootScope.error = true;
        $rootScope.errorMessage = messageTxt;
    });

    /**
     * On 'warnStatus' warning happen
     */
    $rootScope.$on('event:warnStatus', function (event, messageTxt) {
        $rootScope.warn = true;
        $rootScope.warnMessage = messageTxt;
    });

    /**
     * On 'successStatus' warning happen
     */
    $rootScope.$on('event:successStatus', function (event, messageTxt) {
        $rootScope.success = true;
        $rootScope.successMessage = messageTxt;
    });

    /**
     * On 'errorStatus' error happen
     */
    $rootScope.$on('event:errorStatusInGuestBooking', function (event, messageTxt) {
        $rootScope.errorStatusInGuestBooking = true;
        $rootScope.errorMessageInGuestBooking = messageTxt;
    });

    /**
     * On 'successStatus' warning happen
     */
    $rootScope.$on('event:successStatusInGuestBooking', function (event, messageTxt) {
        $rootScope.successStatusInGuestBooking = true;
        $rootScope.successMessageInGuestBooking = messageTxt;
    });

    
    $rootScope.$on("$locationChangeSuccess", function(event, param){
    	
    	$rootScope.$broadcast('event:urlChanged');
        
    });
    

});

angular.module("template/popover/popover.html", []).run(["$templateCache", function ($templateCache) {
    $templateCache.put("template/popover/popover.html",
      "<div class=\"popover {{placement}}\" ng-class=\"{ in: isOpen(), fade: animation() }\">\n" +
      "  <div class=\"arrow\"></div>\n" +
      "\n" +
      "  <div class=\"popover-inner\">\n" +
      "      <h3 class=\"popover-title\" ng-bind-html=\"title | unsafe\" ng-show=\"title\"></h3>\n" +
      "      <div class=\"popover-content\"ng-bind-html=\"content | unsafe\"></div>\n" +
      "  </div>\n" +
      "</div>\n" +
      "");
}]);
