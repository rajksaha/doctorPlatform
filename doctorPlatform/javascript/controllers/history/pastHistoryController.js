app.controller('PastHistoryController', function($scope, $http, $modal, $rootScope, limitToFilter, $location, $filter) {
	
	
	$scope.pastHistoryList = [];
	
	$scope.relationList = [];

	$scope.history = {};
	
	$scope.addMoreButton = true;
	
	
	$scope.savePastHistory = function(pastHistoryData){
		
		if(validator.validateForm("#validateReq","#lblMsg",null)) {
			
			var filteredStartDate = $filter('date')(pastHistoryData.startDate, "yyyy-MM-dd");
			
			var filteredEndDate = "";
			
			if(pastHistoryData.endDate){
				var filteredEndDate = $filter('date')(pastHistoryData.endDate, "yyyy-MM-dd");
			}
			 
			
			var dataString = "";
			if(pastHistoryData.id){
				
				dataString = "query=" + 4 + '&diseaseName=' + pastHistoryData.diseaseName + '&startDate=' + filteredStartDate
				+ '&endDate=' + filteredEndDate + '&detail=' + pastHistoryData.detail + '&pastHistoryID=' + pastHistoryData.id;

			}else{
				dataString = "query=" + 1 + '&diseaseName=' + pastHistoryData.diseaseName + '&startDate=' + filteredStartDate
				+ '&endDate=' + filteredEndDate + '&detail=' + pastHistoryData.detail;
			}
			

	        $http({
	            method: 'POST',
	            url: "phpServices/history/pastHistoryHelper.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$scope.succcess = true;
				$scope.error = false;
				$scope.message = "Information Updated Successfully";
	        	$scope.bringPastHistoryData();
	        });
		}else{
			$scope.message = "";
			$scope.succcess = false;
			$scope.error = true;
		}
		
		
	};
	
	$scope.bringPastHistoryData = function(){
		
		$scope.addMoreButton = true;
		var dataString = "query=0";

        $http({
            method: 'POST',
            url: "phpServices/history/pastHistoryHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.pastHistoryList = result;
        	if($scope.pastHistoryList.length == 0){
        		$scope.addPastHistory();
        	}
        });
	};
	
	$scope.addPastHistory = function (){
		
		angular.forEach($scope.pastHistoryList, function(value, key) {
			value.otherEditMode = true;
		});
		
		$scope.addMoreButton = false;
		
		$scope.pastHistoryData = {};
		
		$scope.pastHistoryData.startDate = "";
		
		$scope.pastHistoryData.endDate = ""; 
		
		$scope.pastHistoryData.detail = ""; 
		
		$scope.pastHistoryData.editMode = true;
		
		$scope.pastHistoryList.splice(0,0, $scope.pastHistoryData);
	};
	
	$scope.editPastHistory = function (pastHistoryData){
		
		angular.forEach($scope.pastHistoryList, function(value, key) {
			value.otherEditMode = true;
		});
		
		pastHistoryData.oterEditMode = false;
		pastHistoryData.editMode = true;
		
	};
	
	$scope.addToPresPast = function(data){
		
		
		if(data.addedToPres == 1){
			
			
			var dataString = "query=" + 3 + "&pastHistoryID=" +  data.id;
			
			$http({
	            method: 'POST',
	            url: "phpServices/history/pastHistoryHelper.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$scope.succcess = true;
				$scope.error = false;
				$scope.message = "Information Deleted From Prescription";
	        	data.addedToPres = false;
	        });
	        
	    }else{
			
			var dataString = "query=" + 2 + "&pastHistoryID=" + data.id;
	        
			$http({
	            method: 'POST',
	            url: "phpServices/history/pastHistoryHelper.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$scope.succcess = true;
				$scope.error = false;
				$scope.message = "Information Added To Prescription";
	        	data.addedToPres = true;
	        });
	    }
	    
		};
		
		$scope.cancelPastHistory  = function(){
			
			$scope.bringPastHistoryData();
		};
	
	
	
	$scope.deletePastHistory = function(id){
		
		var dataString = "query=" + 5 + "&pastHistoryID=" + id;
        
		$http({
            method: 'POST',
            url: "phpServices/history/pastHistoryHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.succcess = true;
			$scope.error = false;
			$scope.message = "Information Deleted Successfully";
        	$scope.bringPastHistoryData();
        });
	};
	
	$scope.getDisease = function(term) {
    	
    	var dataString = "query=" + 0 + "&data=" + term;
        
        return $http({
            method: 'POST',
            url: "phpServices/diagnosis/diagnosis.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result) {
        	$scope.diagnosisNameData = result.data;
        	return limitToFilter($scope.diagnosisNameData, 10);
        });
    };
    
      $scope.onSelectDisease = function(item, model, label){
      };
	
	
	(function(){
		$scope.bringPastHistoryData();
    })()

	
});