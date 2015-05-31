app.controller('FamilyHisoryController', function($scope, $http, $modal, $rootScope, limitToFilter, $location, $filter) {
	
	
	$scope.familyHistoryList = [];
	
	$scope.relationList = [];

	$scope.history = {};
	
	$scope.addMoreButton = true;
	
	
	$scope.saveFamilyHistory = function(familyHistoryData){
		
		
		var dataString = "";
		if(familyHistoryData.id){
			
			dataString = "query=" + 4 + '&diseaseName=' + familyHistoryData.diseaseName + '&relation=' + familyHistoryData.relation.id
			+ '&present=' + familyHistoryData.present + '&type=' + familyHistoryData.type + '&detail=' + familyHistoryData.detail + '&familyHistoryID=' + familyHistoryData.id;

		}else{
			dataString = "query=" + 1 + '&diseaseName=' + familyHistoryData.diseaseName + '&relation=' + familyHistoryData.relation.id
			+ '&present=' + familyHistoryData.present + '&type=' + familyHistoryData.type + '&detail=' + familyHistoryData.detail;
		}
		

        $http({
            method: 'POST',
            url: "phpServices/history/familyHistoryHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringFamilyHistoryData();
        });
	};
	
	$scope.bringFamilyHistoryData = function(){
		
		$scope.addMoreButton = true;
		var dataString = "query=0";

        $http({
            method: 'POST',
            url: "phpServices/history/familyHistoryHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.familyHistoryList = result;
        	if($scope.familyHistoryList.length == 0){
        		$scope.addFamilyHistory();
        	}
        });
	};
	
	$scope.addFamilyHistory = function (){
		
		angular.forEach($scope.familyHistoryList, function(value, key) {
			value.otherEditMode = true;
		});
		
		$scope.addMoreButton = false;
		
		$scope.familyHistoryData = {};
		
		$scope.familyHistoryData.relation = $scope.relationList[0];
		
		$scope.familyHistoryData.type = "Consanguinity"; 
		
		$scope.familyHistoryData.present = "Yes";
		
		$scope.familyHistoryData.editMode = true;
		
		$scope.familyHistoryList.splice(0,0, $scope.familyHistoryData);
	};
	
	$scope.editFamilyHistory = function (familyHistoryData){
		
		angular.forEach($scope.familyHistoryList, function(value, key) {
			value.otherEditMode = true;
		});
		
		familyHistoryData.oterEditMode = false;
		familyHistoryData.editMode = true;
		
		angular.forEach($scope.relationList, function(value, key) {
			if(value.id == familyHistoryData.relation){
				familyHistoryData.relation = value;
			}
		});
	};
	
	$scope.addToPresFamily = function(data){
		
		
		if(data.addedToPres == 1){
			
			
			var dataString = "query=" + 3 + "&familyHistoryID=" +  data.id;
			
			$http({
	            method: 'POST',
	            url: "phpServices/history/familyHistoryHelper.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	data.addedToPres = false;
	        });
	        
	    }else{
			
			var dataString = "query=" + 2 + "&familyHistoryID=" + data.id;
	        
			$http({
	            method: 'POST',
	            url: "phpServices/history/familyHistoryHelper.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	data.addedToPres = true;
	        });
	    }
	    
		};
	
	
	$scope.bringRelationList = function(){
		
		
		var dataString = "query=6";

        $http({
            method: 'POST',
            url: "phpServices/history/familyHistoryHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.relationList = result;
        });
        
	};
	
	$scope.deleteFamilyHistory = function(id){
		
		var dataString = "query=" + 5 + "&familyHistoryID=" + id;
        
		$http({
            method: 'POST',
            url: "phpServices/history/familyHistoryHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringFamilyHistoryData();
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
		$scope.bringRelationList();
		$scope.bringFamilyHistoryData();
    })()

	
});