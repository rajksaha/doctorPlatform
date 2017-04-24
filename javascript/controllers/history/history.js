app.controller('PrescribeHistoryController', function($scope, $stateParams, $http, $modal, $rootScope, limitToFilter, $location) {
	
	
	$scope.historyData = {};
	$scope.historySetteingData = {};
	$scope.paientHistoryList = [];
	$scope.pageName = ""
	$scope.typeCode = "";
	$scope.addByName = false;
	
	$scope.pageNameList = ["M.H", "Obs History", "Gynaecological History", "General History", "Immunization History"];
	
	$scope.bringHistoryDetail = function (){
		
		$scope.historySetteingData = {};
		
		var dataString = "query=0" + '&typeCode=' + $scope.typeCode;;

        $http({
            method: 'POST',
            url: "phpServices/history/historyHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.paientHistoryList = result;
        	angular.forEach($scope.paientHistoryList, function(value, key) {
    			if(parseInt(value.savedHistorysID) > 0){
    				value.addToPrescription = true;
    			}
    		});
        });
	};
	
	if($stateParams.historyType != undefined && $stateParams.historyType != null) {
		$scope.typeCode = $stateParams.historyType;
		var num = 0;
		
		if($scope.typeCode == "OBS"){
			num = 1;
		}else if ($scope.typeCode == "GYNAE") {
			num = 2;
		}else if ($scope.typeCode == "GENERAL") {
			num = 3;
		}else if ($scope.typeCode == "IMMUNIZATION") {
			num = 4;
		}
		$scope.pageName = $scope.pageNameList[num];
		
		if($scope.typeCode != ""){
			$scope.bringHistoryDetail();
		}
	}
	
    $scope.getHistory = function(term) {

    	var dataString = 'query=5'+ '&name=' + term + '&typeCode=' + $scope.typeCode;
        
        return $http({
            method: 'POST',
            url: "phpServices/history/historyHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result) {
        	$scope.historyData = result.data;
        	return limitToFilter($scope.historyData, 10);
        });
      };
      
	  $scope.onSelectHistory = function(item, model, label){
		  $scope.historySetteingData.historyID = item.id;
		  $scope.historySetteingData.shortName = item.shortName;
		  $scope.addByName = true;
	  };
	  
	$scope.addHistoryToDoctorPref = function (){
		
		if(validator.validateForm("#historySetting","#lblMsg",null)) {
			if($scope.addByName == false){
				var dataString = 'query=6'+ '&historyName=' + $scope.historySetteingData.historyName + '&shortName=' + $scope.historySetteingData.shortName +  '&typeCode=' + $scope.typeCode;
		        $http({
		            method: 'POST',
		            url: "phpServices/history/historyHelperService.php",
		            data: dataString,
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        	$scope.addToDoctorPreference(result);
		        });
				
			}else{
				$scope.addToDoctorPreference($scope.historySetteingData.historyID);
			}

		}else{
			alert("what");
		}
	};
	
	$scope.addToDoctorPreference = function (historyID){
		
		var hisID = parseInt(historyID);
		var displayOrder = 1;
		if($scope.paientHistoryList != undefined && $scope.paientHistoryList.length > 0){
			displayOrder = parseInt($scope.paientHistoryList[$scope.paientHistoryList.length -1].displayOrder) + 1;
		}
		
		var dataString = 'query=7'+ '&historyID=' + hisID + '&displayOrder=' + displayOrder;

        $http({
            method: 'POST',
            url: "phpServices/history/historyHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringHistoryDetail();
        });
		
	};
	
	
	
	$scope.bringHistoryOption = function(historydata){
		
		angular.forEach($scope.paientHistoryList, function(value, key) {
			value.optionListON = false;
			value.optionAdderON = false;
		});
		
		var dataString = 'query=1'+ '&historyID=' + historydata.historyID;

        $http({
            method: 'POST',
            url: "phpServices/history/historyHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	historydata.optionList = result;
        	var data = {"id" : -1,"historyOptionID" : -1, "optionName": 'Add Options'};
        	var data1 = {"id" : -2,"historyOptionID" : -2, "optionName": 'Close'};
        	historydata.optionList.unshift(data1,data);
        	historydata.optionSelector = historydata.optionList[0];
        	historydata.optionListON = true;
        	historydata.optionAdderON = false;
        });
	};
	
	$scope.performHistory = function(historydata){
		if(historydata.optionSelector.historyOptionID == -1){
			historydata.optionListON = false;
			historydata.optionAdderON = true;
		}else if(historydata.optionSelector.historyOptionID == -2){
			historydata.optionListON = false;
		}else{
			/*if(historydata.historyResult){
				historydata.historyResult = historydata.historyResult + "," + historydata.optionSelector.optionName;
				historydata.optionListON = false;
			}else{
				historydata.historyResult = historydata.optionSelector.optionName;
				historydata.optionListON = false;
			}*/
			
			historydata.historyResult = historydata.optionSelector.optionName;
			historydata.optionListON = false;
		}
	};
	
	$scope.addHistoryOption = function (historydata){
		if(historydata.optionAdder){
			var dataString = 'query=2'+ '&historyID=' + historydata.historyID + '&historyOptionName=' + historydata.optionAdder ;
	        $http({
	            method: 'POST',
	            url: "phpServices/history/historyHelperService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	historydata.optionAdder = "";
	        	$scope.bringHistoryOption(historydata);
	        });
			
		}else{
			//maybe a pop- up saying please enter a value
			return false;
		}
	};
	
	$scope.saveHistory = function(){
		
		var prescribedHistory = $scope.paientHistoryList;
		
		angular.forEach(prescribedHistory, function(value, key) {
			if(parseInt(value.patientHistoryID) > 0 && value.historyResult){//Update
				var dataString = 'query=11'+ '&historyID=' + value.historyID + '&historyResult=' + value.historyResult ;
		        $http({
		            method: 'POST',
		            url: "phpServices/history/historyHelperService.php",
		            data: dataString,
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        });
			}else if(!parseInt(value.patientHistoryID) &&  value.historyResult){//ADD
				var dataString = 'query=10'+ '&historyID=' + value.historyID + '&historyResult=' + value.historyResult ;
		        $http({
		            method: 'POST',
		            url: "phpServices/history/historyHelperService.php",
		            data: dataString,
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        });
			}else if(parseInt(value.patientHistoryID) > 0 && value.historyResult == ""){//delete
				
				var dataString = 'query=9'+ '&savedHistorysID=' + value.patientHistoryID;
		        $http({
		            method: 'POST',
		            url: "phpServices/history/historyHelperService.php",
		            data: dataString,
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        });
			}
		});
		
		angular.forEach(prescribedHistory, function(value, key) {
			if(parseInt(value.savedHistorysID) > 0 && value.historyResult && value.addToPrescription){
				//do noting
			}else if(parseInt(value.savedHistorysID) > 0 && value.historyResult && !value.addToPrescription){
				
				var dataString = 'query=4'+ '&savedHistorysID=' + value.savedHistorysID;
		        $http({
		            method: 'POST',
		            url: "phpServices/history/historyHelperService.php",
		            data: dataString,
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        });
		        
			}else if(parseInt(value.savedHistorysID) > 0 && value.historyResult == ""){


				var dataString = 'query=4'+ '&savedHistorysID=' + value.savedHistorysID;
		        $http({
		            method: 'POST',
		            url: "phpServices/history/historyHelperService.php",
		            data: dataString,
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        });
		        
			}else if(!(parseInt(value.savedHistorysID) > 0) > 0 && value.historyResult == "" && value.addToPrescription){
				//do noting
			}else if(!(parseInt(value.savedHistorysID) > 0) && value.historyResult  && value.addToPrescription){
				
				var dataString = 'query=3'+ '&historyID=' + value.historyID;
		        $http({
		            method: 'POST',
		            url: "phpServices/history/historyHelperService.php",
		            data: dataString,
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        });
			}
		});
		
		$location.path("/prescription");
	};
	
	$scope.deleteHistoyFromSetting = function (id){
		
		var dataString = 'query=12'+ '&historySettingID=' + id;
        $http({
            method: 'POST',
            url: "phpServices/history/historyHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringHistoryDetail();
        });
	};
	
	

	
});