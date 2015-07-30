app.controller('DrugAdvisorController', function($scope, $http, $modal, $rootScope, limitToFilter, $location) {
	
	$scope.drugAdviceList = {};
	$scope.masterUpdate = true;
	
	
	$scope.init = function(){
		$scope.bringDrugAdviceList();
    };
    
	$scope.bringDrugAdviceList = function (){
		
		
		var dataString = {'data': 1, 'query': 1};

        $http({
            method: 'POST',
            url: "phpServices/drugAdvisor/drugAdvisorHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/json'}
        }).success(function (result) {
        	
        	$scope.drugAdviceList = result;
        	
        	
        });
		
	};
	
	$scope.addDrugAdvice = function(){
		
		var drugAdviceData = {};
		
		drugAdviceData.bangla = "";
		drugAdviceData.editMode = true;
		$scope.masterUpdate = false;
		drugAdviceData.bangla = "";
		
		$scope.drugAdviceList.splice(0,0, drugAdviceData);
		
	};

    $scope.saveDrugAdvice = function(data) {
    	
    	var data = {'data': data.bangla, 'query': 2};
        
    	$http({
            method: 'POST',
            url: "phpServices/drugAdvisor/drugAdvisorHelper.php",
            data: data,
            headers: {'Content-Type': 'application/json'}
        }).success(function (result) {
        	$scope.drugAdviceList = result;
        });
    };
    

    $scope.init();
});