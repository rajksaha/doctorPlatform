app.controller('DrugAdvisorController', function($scope, $http, $modal, $rootScope, limitToFilter, $location) {
	
	$scope.drugAdviceList = {};
	
	
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
	
	$scope.saveGroupOfComplain = function(){
		
		var entryFound = false;
		
		angular.forEach($scope.complainList, function(value, key) {
			if(value.name){
				entryFound = true;
				
				var dataString = {'complainName ': value.name , 'numOfDay' : value.numOfDay ,'dayType' :  value.dayType, 'note' : value.note, 'complainPrescribeID' : value.id, 'query' : 2};
				
		        $http({
		            method: 'POST',
		            url: "phpServices/complain/complainService.php",
		            data: JSON.stringify(dataString),
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        });
			}
		});
		
		
		if(!entryFound){
			$scope.message = "Please Select At-least One Symptom";
			$scope.succcess = false;
			$scope.error = true;
		}
		
	};

    $scope.getSymptoms = function(term) {
    	
    	var data = {'data': term, 'query': 1};
        
        return $http({
            method: 'POST',
            url: "phpServices/complain/complainService.php",
            data: JSON.stringify(data),
            headers: {'Content-Type': 'application/json'}
        }).then(function(result) {
        	$scope.symptoms = result.data;
        	return limitToFilter($scope.symptoms, 10);
        });
    };
    
      $scope.onSelectSymptoms = function(item, model, label){
    	  alert(item.name);
      };

    $scope.init();
});