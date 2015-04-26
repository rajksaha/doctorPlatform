app.controller('HeaderController', function($scope, $rootScope, $location, $timeout, $modal, blockUI, $http) {
	
	$scope.doctorData = {};
	
    $scope.bringDoctorInfo = function (){
    	
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
	       
	    };
    

    (function(){
		$scope.bringDoctorInfo();
    })()
});