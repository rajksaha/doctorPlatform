app.controller('DrugHistoryController', function($scope, $http, $modal, $rootScope, limitToFilter, $location) {
	
	$scope.currentDrugList = [];
	$scope.oldDrugList = [];
	$scope.masterUpdate = true;
	
	
	$scope.init = function(){
		$scope.bringCurrentDrugList();
		
		$scope.bringOldDrugList();
    };
    
	$scope.bringCurrentDrugList = function (){
		
		
		var dataString = {'status' : 1, 'query': 1};

        $http({
            method: 'POST',
            url: "phpServices/drugHistory/drugHistoryHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/json'}
        }).success(function (result) {
        	$scope.currentDrugList = result;
        });
		
	};
	
	$scope.bringOldDrugList = function (){
		
		
		var dataString = {'status' : 0,  'query': 1};

        $http({
            method: 'POST',
            url: "phpServices/drugHistory/drugHistoryHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/json'}
        }).success(function (result) {
        	$scope.oldDrugList = result;
        });
		
	};
	
	$scope.addDrug = function(status){
		
		$scope.showDrugAdvice = true;
		var data = {};
		
		data.drugName = "";
		data.editMode = true;
		$scope.masterUpdate = false;
		
		if(status == 1){
			angular.forEach($scope.currentDrugList, function(value, key) {
				value.otherEditMode = true;
			});
			
			$scope.currentDrugList.splice(0,0, data);
		}else{
			angular.forEach($scope.oldDrugList, function(value, key) {
				value.otherEditMode = true;
			});
			
			$scope.oldDrugList.splice(0,0, data);
		}
	};

    $scope.saveDrug = function(data, status) {
    	
    	var data = {'drugName': data.drugName, 'status': status, 'query': 2};
        
    	$http({
            method: 'POST',
            url: "phpServices/drugHistory/drugHistoryHelper.php",
            data: data,
            headers: {'Content-Type': 'application/json'}
        }).success(function (result) {
        	if(status == 1){
        		$scope.bringCurrentDrugList();
        	}else{
        		$scope.bringOldDrugList();
        	}
        });
    };
    
    $scope.delDrug = function(data, status) {
    	
    	var data = {'delId': data.drugHistoryID, 'query': 3};
        
    	$http({
            method: 'POST',
            url: "phpServices/drugHistory/drugHistoryHelper.php",
            data: data,
            headers: {'Content-Type': 'application/json'}
        }).success(function (result) {
        	if(status == 1){
        		$scope.bringCurrentDrugList();
        	}else{
        		$scope.bringOldDrugList();
        	}
        });
    };
    
	$scope.addToPresPast = function(data){
		
		if(data.addedToPres == 0){
			
			var dataStr = {'drugName': data.drugName, 'status': data.currentStatus, 'query': 7};
	        
	    	$http({
	            method: 'POST',
	            url: "phpServices/drugHistory/drugHistoryHelper.php",
	            data: dataStr,
	            headers: {'Content-Type': 'application/json'}
	        }).success(function (result) {
	        	data.contentDetailID = result;
	        	data.addedToPres = true;
	        });
	        
	    }else{
			
	    	var dataStr = {'contentDetailID': data.contentDetailID, 'query': 8};
	        
	    	$http({
	            method: 'POST',
	            url: "phpServices/drugHistory/drugHistoryHelper.php",
	            data: dataStr,
	            headers: {'Content-Type': 'application/json'}
	        }).success(function (result) {
	        	data.addedToPres = false;
	        });
	    }
	    
		};
    

    $scope.init();
});