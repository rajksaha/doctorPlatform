app.controller('PrescribeVitalController', function($scope, $http, $modal, $rootScope, limitToFilter, $location) {
	
	
	$scope.vitalData = {};
	$scope.vitalNameData = {};
	$scope.prescribedVitalData = [];
	 $scope.addByName = false;
	
	
    $scope.getVital = function(term) {
        
    	var dataString = 'query=5'+ '&name=' + term;
        
        return $http({
            method: 'POST',
            url: "phpServices/vital/vitalService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result) {
        	$scope.vitalNameData = result.data;
        	return limitToFilter($scope.vitalNameData, 10);
        });

        
       // return $scope.products;
      };
      
	  $scope.onSelectVital = function(item, model, label){
		  $scope.vitalNameData.vitalId = item.vitalId;
		  $scope.vitalData.shortName = item.shortName;
		  $scope.vitalData.unit = item.unit;
		  $scope.addByName = true;
	  };
	  
	  
		$scope.addVitalToDoctorPref = function (){
			
			if(validator.validateForm("#historySetting","#lblMsg",null)) {
				
				
				if($scope.addByName == false){
					
					var dataString = 'query=6'+ '&vitalName=' + $scope.vitalData.vitalName + '&shortName=' + $scope.vitalData.shortName + '&unit=' + $scope.vitalData.unit;

			        $http({
			            method: 'POST',
			            url: "phpServices/vital/vitalService.php",
			            data: dataString,
			            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			        }).success(function (result) {
			        	$scope.addToDoctorPreference(result);
			        });
					
				}else{
					
					$scope.addToDoctorPreference($scope.vitalNameData.vitalId);
				}
				
				
				
			}else{
				alert("what");
			}
		};
		
		$scope.addToDoctorPreference = function (vitalID){
			
			$scope.vitalData = {};
			$scope.vitalNameData = {};
			 $scope.addByName = false;
			var vitID = parseInt(vitalID);
			var displayOrder = 1;
			if($scope.prescribedVitalData != undefined && $scope.prescribedVitalData.length > 0){
				displayOrder = parseInt($scope.prescribedVitalData[$scope.prescribedVitalData.length -1].displayOrder) + 1;
			}
			
			var dataString = 'query=7'+ '&vitalID=' + vitID + '&displayOrder=' + displayOrder;

	        $http({
	            method: 'POST',
	            url: "phpServices/vital/vitalService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$scope.bringVitalDetail();
	        });
			
		};
		
		$scope.deleteVitalFromSetting = function (vitalSettingID){
			
			var dataString = 'query=8'+ '&vitalSettingID=' + vitalSettingID;

	        $http({
	            method: 'POST',
	            url: "phpServices/vital/vitalService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$scope.bringVitalDetail();
	        });
		};
	
	$scope.bringVitalDetail = function (){
		
		var dataString = "query=0";

        $http({
            method: 'POST',
            url: "phpServices/vital/vitalService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.prescribedVitalData = result;
        });
	};
	
	$scope.bringVitalOption = function(vitalData){
		
		angular.forEach($scope.prescribedVitalData, function(value, key) {
			value.optionListON = false;
			value.optionAdderON = false;
		});
		
		var dataString = 'query=1'+ '&vitalID=' + vitalData.vitalId;

        $http({
            method: 'POST',
            url: "phpServices/vital/vitalService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	vitalData.optionList = result;
        	var data = {"id" : -1,"vitalOptionID" : -1, "name": 'Add Options'};
        	var data1 = {"id" : -2,"vitalOptionID" : -2, "name": 'Close'};
        	vitalData.optionList.unshift(data1,data);
        	vitalData.optionSelector = vitalData.optionList[0];
        	vitalData.optionListON = true;
        	vitalData.optionAdderON = false;
        });
	};
	
	$scope.performVital = function(vital){
		if(vital.optionSelector.vitalOptionID == -1){
			vital.optionListON = false;
			vital.optionAdderON = true;
		}else if(vital.optionSelector.vitalOptionID == -2){
			vital.optionListON = false;
		}else{
			if(vital.vitalResult){
				vital.vitalResult = vital.vitalResult + "," + vital.optionSelector.name;
				vital.optionListON = false;
			}else{
				vital.vitalResult = vital.optionSelector.name;
				vital.optionListON = false;
			}
		}
	};
	
	$scope.addVitalOption = function (vitalData){
		if(vitalData.optionAdder){
			var dataString = 'query=2'+ '&vitalID=' + vitalData.vitalId + '&vitalOptionName=' + vitalData.optionAdder ;
	        $http({
	            method: 'POST',
	            url: "phpServices/vital/vitalService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$scope.bringVitalOption(vitalData);
	        });
			
		}else{
			//maybe a pop- up saying please enter a value
			return false;
		}
	};
	
	$scope.saveVital = function(prescribedVital){
		
		angular.forEach(prescribedVital, function(value, key) {
			if(parseInt(value.prescribedVitalID) > 0 && value.vitalResult){ // update
				var dataString = 'query=4'+ '&vitalID=' + value.vitalId + '&vitalResult=' + value.vitalResult ;
		        $http({
		            method: 'POST',
		            url: "phpServices/vital/vitalService.php",
		            data: dataString,
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        });
			}else if(!(parseInt(value.prescribedVitalID) > 0) &&  value.vitalResult){// inssert
				var dataString = 'query=3'+ '&vitalID=' + value.vitalId + '&vitalResult=' + value.vitalResult ;
		        $http({
		            method: 'POST',
		            url: "phpServices/vital/vitalService.php",
		            data: dataString,
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        });
			}else if(parseInt(value.prescribedVitalID) > 0 && value.vitalResult == ""){
				
				var dataString = 'query=9'+ '&prescribedVitalID=' + value.prescribedVitalID;
		        $http({
		            method: 'POST',
		            url: "phpServices/vital/vitalService.php",
		            data: dataString,
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        });
			}
		});
		
		$location.path("/prescription");
	};
	
	
	(function(){
		$scope.bringVitalDetail();
    })()

	
});