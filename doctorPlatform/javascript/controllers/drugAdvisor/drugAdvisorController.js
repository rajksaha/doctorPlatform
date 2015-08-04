app.controller('DrugAdvisorController', function($scope, $http, $modal, $rootScope, limitToFilter, $location) {
	
	$scope.drugAdviceList = [];
	$scope.drugWhenList = [];
	$scope.masterUpdate = true;
	
	
	$scope.init = function(){
		$scope.bringDrugAdviceList();
		
		$scope.bringDrugWhenList();
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
	
	$scope.bringDrugWhenList = function (){
		
		
		var dataString = {'data': 1, 'query': 4};

        $http({
            method: 'POST',
            url: "phpServices/drugAdvisor/drugAdvisorHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/json'}
        }).success(function (result) {
        	
        	$scope.drugWhenList = result;
        	
        	
        });
		
	};
	
	$scope.addDrugAdvice = function(){
		
		angular.forEach($scope.drugAdviceList, function(value, key) {
			value.otherEditMode = true;
		});
		
		$scope.showDrugAdvice = true;
		var drugAdviceData = {};
		
		drugAdviceData.bangla = "";
		drugAdviceData.pdf = "";
		drugAdviceData.editMode = true;
		$scope.masterUpdate = false;
		drugAdviceData.bangla = "";
		
		$scope.drugAdviceList.splice(0,0, drugAdviceData);
		
	};

    $scope.saveDrugAdvice = function(data) {
    	
    	var data = {'bangla': data.bangla, 'pdf': data.pdf, 'query': 2};
        
    	$http({
            method: 'POST',
            url: "phpServices/drugAdvisor/drugAdvisorHelper.php",
            data: data,
            headers: {'Content-Type': 'application/json'}
        }).success(function (result) {
        	$scope.bringDrugAdviceList();
        });
    };
    
    $scope.delDrugAdvice = function(data) {
    	
    	var data = {'delId': data.drugAdviceID, 'query': 3};
        
    	$http({
            method: 'POST',
            url: "phpServices/drugAdvisor/drugAdvisorHelper.php",
            data: data,
            headers: {'Content-Type': 'application/json'}
        }).success(function (result) {
        	$scope.bringDrugAdviceList();
        });
    };
    

    $scope.init();
});