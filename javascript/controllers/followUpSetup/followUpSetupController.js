app.controller('FollowUpSetupController', function($scope, $http, $modal, $rootScope, limitToFilter, $location) {
	
	

	$scope.invNameData = [];
	$scope.invData = {};
	$scope.invFollowUpChart = [];
	$scope.followUpChartData = [];
	$scope.recentStart = 0;
	$scope.recentEnd = 0;
	$scope.patientAppoinmentList = [];
	
	$scope.typeHeadSelected = false;
	
    $scope.getInvName = function(term) {
        
    	var dataString = 'query=0'+ '&invName=' + term;
        
        return $http({
            method: 'POST',
            url: "phpServices/inv/invCategoryService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result) {
        	$scope.invNameData = result.data;
        	return limitToFilter($scope.invNameData, 10);
        });

        
       // return $scope.products;
      };
      
	  $scope.onSelectInvName = function(item, model, label){
		  $scope.invData.id = item.id;
		  $scope.typeHeadSelected = true;
	  };
	

	  $scope.addInvToFollowUp = function(followUpInvName){
		  
		  var dataString = 'query=11'+ '&invName=' + followUpInvName;

	        $http({
	            method: 'POST',
	            url: "phpServices/inv/invCategoryService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$scope.inIt();
	        });
	  };
	  
	  $scope.delete = function(id){
		  
		  var dataString = 'query=13'+ '&id=' + id;

	        $http({
	            method: 'POST',
	            url: "phpServices/inv/invCategoryService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$scope.inIt();
	        });
	  };
	  
	  
	$scope.inIt = function (){
		
		$scope.followUpInvName = "";
		
		var dataString = 'query=12';

        $http({
            method: 'POST',
            url: "phpServices/inv/invCategoryService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.followUpList = result;
        });
	};

	$scope.inIt();

	
});