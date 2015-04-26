app.controller('PrescribeHistoryController', function($scope, $http, $modal, $rootScope, limitToFilter, $location) {
	
	
	$scope.vitalData = {};
	$scope.prescribedVitalData = [];
	
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
			if(value.prescribedVitalID && value.vitalResult){
				var dataString = 'query=4'+ '&vitalID=' + value.vitalId + '&vitalResult=' + value.vitalResult ;
		        $http({
		            method: 'POST',
		            url: "phpServices/vital/vitalService.php",
		            data: dataString,
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        });
			}else if(value.vitalResult){
				var dataString = 'query=3'+ '&vitalID=' + value.vitalId + '&vitalResult=' + value.vitalResult ;
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