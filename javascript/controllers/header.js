app.controller('HeaderController', function($scope, $rootScope, $location, $timeout, $modal, $http) {
	
	$scope.doctorData = {};
	$scope.displayPresCription = true;
	$scope.dateString = new Date();
	
    $scope.bringDoctorInfo = function (){
    	
    	var page = $location.path();
    	
    	/*if(page == "/appointment" || page == "/prescription"){
    		$scope.displayPresCription = false;
    	}*/
    	
        var dataString = "query=0";

        $http({
            method: 'POST',
            url: "phpServices/appointment/appointmentHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.doctorData = result;
        	$rootScope.doctorData = $scope.doctorData;
        });
    };
	
	 $scope.logout = function () {
		 
		 var dataString = "query=6";

	        $http({
	            method: 'POST',
	            url: "phpServices/appointment/appointmentHelper.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$location.path("/login");
	        });
	       
	 };

	 $scope.toggoleButton = function () {
		$rootScope.hideMenu = !$rootScope.hideMenu;
         $rootScope.$broadcast('event:hideMenu');
     };
    

    (function(){
		$scope.bringDoctorInfo();
    })()
});