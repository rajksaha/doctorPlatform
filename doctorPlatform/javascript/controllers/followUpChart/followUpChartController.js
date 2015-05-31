app.controller('followUpChartController', function($scope, $http, $modal, $rootScope, limitToFilter, $location) {
	
	$scope.invNameData = [];
	$scope.invFollowUpChart = [];
	$scope.followUpChartData = [];
	$scope.recentStart = 0;
	$scope.recentEnd = 0;
	$scope.patientAppoinmentList = [];
	
    $scope.getInvName = function(term) {
        
    	var dataString = 'query=0'+ '&invName=' + term;
        
        return $http({
            method: 'POST',
            url: "phpServices/inv/invService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result) {
        	$scope.invNameData = result.data;
        	return limitToFilter($scope.invNameData, 10);
        });

        
       // return $scope.products;
      };
      
	  $scope.onSelectInvName = function(item, model, label){
		  $scope.selectedInvID = item.id;
	  };
	
	
	  $scope.bringfollowUpChart = function (){
	    	
		  var dataString = "query=0";

          $http({
              method: 'POST',
              url: "phpServices/followUpChart/followUpChart.php",
              data: dataString,
              headers: {'Content-Type': 'application/x-www-form-urlencoded'}
          }).success(function (result) {
        	  $scope.requestPatientAppoinmentList = result;
        	  angular.forEach($scope.requestPatientAppoinmentList, function(value, key) {
        		  	if(value.invReportList != undefined && value.invReportList.length > 0){
        		  		$scope.patientAppoinmentList.push(value);
        		  	}
      			});
          });
	    };

	
	
	

	$scope.inIt = function (){
		$scope.bringfollowUpChart(1);
		
	};
	
	(function(){
		$scope.inIt();
    })()

	
});