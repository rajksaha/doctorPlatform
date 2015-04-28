app.controller('PrescribeDrugsController', function($scope, $http, $modal, $rootScope, limitToFilter, $location, JsonService, $window) {
	
	$scope.drugTypeList =[];
	$scope.drugNumOfDayList = JsonService.numberList;
	$scope.drugtimesADay = JsonService.timesADay;
	$scope.drugDayTypeList =[];
	$scope.drugWhatTypeList =[];
	$scope.drugAdviceTypeList =[];
	$scope.drugDoseList =[];
	$scope.doctorData = $rootScope.doctorData;
	$scope.drugData = {};
	$scope.drugPeriodicDoseList = [];
	$scope.enteredDrugDoseList = [];
	$scope.addByName = false;
	
	$scope.drugNameList = {};
	
	$scope.initializeDrugData = function (drugType, selIndexTimeADay, selIndexNumfDay){
		
		angular.forEach($scope.drugtimesADay, function(value, key) {
			if(value.code == selIndexTimeADay){
				$scope.drugData.timesADay = value;
			}
		});
		angular.forEach($scope.drugNumOfDayList, function(data, key) {
			if(data.value == selIndexNumfDay){
				$scope.drugData.numOFDay = data;
			}
		});
		$scope.fixDose($scope.drugData.timesADay,drugType,0);
	};
	
	$scope.fixPredoicDose = function (row, unit){
		$scope.drugPeriodicDoseList = [];
		var data = {"value" : unit};
		for(var i = 0; i< row; i++){
			var predoicCol = [];
			for(var j = 0; j< 3; j++){
				var data3 = {"predoicCol" : data};
				predoicCol.push(data3);
			}
			if(i==(row-1)){
				var data2 = {"predoicRow" : predoicCol, "numOFDay": $scope.drugNumOfDayList[6], "dayType" : $scope.drugDayTypeList[4]};
			}else{
				var data2 = {"predoicRow" : predoicCol, "numOFDay": $scope.drugNumOfDayList[6], "dayType" : $scope.drugDayTypeList[0]};
			}
			
			$scope.drugPeriodicDoseList.push(data2);
		}
		console.log($scope.drugPeriodicDoseList);
	};
	
	$scope.fixDose = function (timesADay, drugType, change){
		$scope.drugDoseList = [];
		if(timesADay.code == -1){//preodic change
			$scope.drugData.preodicValue = 3;
			$scope.fixPredoicDose(3,drugType.unit);
		}else  if (timesADay.code == -2){//same as
			
		}else if(timesADay.code == -3){//no dose
			
		}else{
			var val = parseFloat(drugType.unit) + change;
			var data = {"value" : val};
			for(var i = 0; i< timesADay.code; i++){
				if($scope.enteredDrugDoseList.length > 0){
					var data = {"value" : $scope.enteredDrugDoseList[i]};
					drugType.unit = $scope.enteredDrugDoseList[i];
				}else{
					var data = {"value" : val};
					drugType.unit = val;
				}
				
				$scope.drugDoseList.push(data);
			}
			$scope.enteredDrugDoseList = [];
		}
		
	};
	
	
	$scope.bringdrugsType = function (addMood,selectedDrugTypeID, selIndexTimeADay, selIndexNumfDay){
		
		var dataString = "query=0";

        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.drugTypeList = result;
        	if(addMood){
        		$scope.drugData.drugType = $scope.drugTypeList[0];
        	}else{
        		angular.forEach($scope.drugTypeList, function(value, key) {
        			if(value.id == selectedDrugTypeID){
        				$scope.drugData.drugType = value;
        			}
        		});
        	}
        	$scope.initializeDrugData($scope.drugData.drugType,selIndexTimeADay,selIndexNumfDay);
        });
		
	};
		
	$scope.bringdrugsDayType = function (addMood, selectedDayTypeID){
		
		var dataString = "query=1";

        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.drugDayTypeList = result;
        	if(addMood){
        		$scope.drugData.dayType = $scope.drugDayTypeList[0];
        	}else{
        		angular.forEach($scope.drugDayTypeList, function(value, key) {
        			if(value.id == selectedDayTypeID){
        				$scope.drugData.dayType = value;
        			}
        		});
        	}
        	
        });
		
	};
	
	$scope.bringdrugsWhatType = function (addMood, selectedWhatTypeID){
		
		var dataString = "query=2";

        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.drugWhatTypeList= result;
        	if(addMood){
        		$scope.drugData.whatType = $scope.drugWhatTypeList[0];
        	}else{
        		angular.forEach($scope.drugWhatTypeList, function(value, key) {
        			if(value.id == selectedWhatTypeID){
        				$scope.drugData.whatType = value;
        			}
        		});
        	}
        });
		
	};
	
	$scope.bringdrugsAdviceType = function (addMood, selectedAdviceTypeID){
		
		var dataString = "query=3";

        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.drugAdviceTypeList = result;
        	if(addMood){
        		$scope.drugData.adviceType = $scope.drugAdviceTypeList[0];
        	}else{
        		angular.forEach($scope.drugAdviceTypeList, function(value, key) {
        			if(value.drugAdviceID == selectedAdviceTypeID){
        				$scope.drugData.adviceType = value;
        			}
        		});
        	}
        });
		
	};
	
	$scope.prepareDrugSaveData = function(drugID){
		
		var drugType = $scope.drugData.drugType.id;
		var drugID =  parseInt(drugID);
		
		var drugTime = $scope.drugData.timesADay.code;
		var drugDose = "";
		if(drugTime > 0){
			for(var i = 0;i < $scope.drugDoseList.length; i++){
				if(i == 0){
					drugDose = $scope.drugDoseList[i].value;
				}else{
					drugDose = drugDose + " - "+ $scope.drugDoseList[i].value;
				}
			}
		}else if(drugTime == -1){
			angular.forEach($scope.drugPeriodicDoseList, function(rowData, key) {
				angular.forEach(rowData.predoicCol, function(colData, key) {
        			
        		});
    		});
			
		}else if(drugTime == -2){
			drugDose = $scope.drugData.sameAsDose;
		}
		var doseUnit = "";
		if($scope.drugData.optionalInitial != undefined && $scope.drugData.optionalInitial){
			doseUnit = $scope.drugData.doseOptionalUnitInitial;
		}else if($scope.drugData.doseUnitInitial != undefined){
			doseUnit = $scope.drugData.doseUnitInitial;
		}
		var drugNoOfDay = "";
		var drugDayType = 6;
		if(drugTime != -1){
			if($scope.drugData.dayType.id != 5){
				drugNoOfDay = $scope.drugData.numOFDay.value;
			}
			drugDayType = $scope.drugData.dayType.id;
		}
		var drugWhen = $scope.drugData.whatType.id;
		
		var drugAdvice = $scope.drugData.adviceType.drugAdviceID;
		
		var query = 6;
		var drugPrescribeID = 0;
		if(!$scope.drugData.addMood){
			query = 5;
			drugPrescribeID = $scope.drugData.drugPrescribeID;
		}
		
		var dataString = 'drugType='+ drugType +'&drugID='+ drugID + '&drugTime='+ drugTime + '&drugDose=' + drugDose +'&doseUnit='+ doseUnit + '&drugNoOfDay='+ drugNoOfDay +'&drugDayType='+ drugDayType + '&drugWhen='+ drugWhen +'&drugAdvice='+ drugAdvice+ '&drugPrescribeID='+ drugPrescribeID +'&query=' + query;
		
        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringPresCribedDrugs();
        });
	};
	
	$scope.saveDrug = function() {
		
		if(!$scope.addByName){
			
			var dataString = "query=9" + '&drugName=' + $scope.drugName + '&drugType=' + $scope.drugData.drugType.id;

	        $http({
	            method: 'POST',
	            url: "phpServices/drugs/drugsService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$scope.prepareDrugSaveData(result);
	        	
	        });
	        
		}else{
			$scope.prepareDrugSaveData($scope.drugData.drugID);
		}
		
	};
	
	$scope.deleteDrugFromDB = function(){
		
		var dataString = "query=10" + '&drugID=' + $scope.drugData.drugID;

        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringPresCribedDrugs();
        	
        });
		
	};
	
	$scope.editDrugName = function(){
		
        
        var dataString = "query=11" + '&drugID=' + $scope.drugData.drugID + '&drugName=' + $scope.drugName;

        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringPresCribedDrugs();
        	
        });
	};
	
	
	// listView Code starts
	
	$scope.prescribedDrugList = [];
	$scope.numberOfPrescribedDrugs = 0;
	
	$scope.editPresCribedDrug = function(drugData){
		
		$scope.drugData = {};
		$scope.drugData.addMood = false;
		$scope.drugName = drugData.drugName;
		$scope.drugData.drugPrescribeID = drugData.id;
		$scope.enteredDrugDoseList = drugData.drugDose.split(' - ')
		$scope.bringdrugsType(false,drugData.drugTypeID,drugData.drugTimeID, drugData.drugNoOfDay);
		$scope.bringdrugsDayType(false, drugData.drugDayTypeID);
		$scope.bringdrugsWhatType(false,drugData.drugWhenID);
		$scope.bringdrugsAdviceType(false, drugData.drugAdviceID);
		
	};
	
	$scope.deletePrescribedDrug = function(drugPrescribeID){
		
		var dataString = "query=7" + '&drugPrescribeID=' + drugPrescribeID;

        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringPresCribedDrugs();
        	
        });
		
	};
	
	$scope.bringPresCribedDrugs = function (){
		
		var dataString = "query=4";

        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.prescribedDrugList = result;
        	$scope.numberOfPrescribedDrugs = $scope.prescribedDrugList.length;
        	
        	//call for reset to AddMode
        	$scope.drugName = "";
        	$scope.drugData.addMood = true; 
        	$scope.drugData.delDrug = false;
  		  	$scope.drugData.editName = false;
        	$scope.bringdrugsType(true, null, 3, 7);
    		$scope.bringdrugsDayType(true , null);
    		$scope.bringdrugsWhatType(true, null);
    		$scope.bringdrugsAdviceType(true, null);
        	
        });
	};
	
    $scope.getDrugName = function(term) {
        
    	var dataString = 'query=8'+ '&drugName=' + term + '&drugType=' + $scope.drugData.drugType.id;
        
        return $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result) {
        	$scope.drugNameList = result.data;
        	return limitToFilter($scope.drugNameList, 10);
        });

        
       // return $scope.products;
      };
      
	  $scope.onSelectDrugName = function(item, model, label){
		  $scope.drugData.drugID = item.drugID;
		  $scope.addByName = true;
		  $scope.drugData.delDrug = true;
		  $scope.drugData.editName = true;
	  };
	
	
	(function(){
		$scope.bringPresCribedDrugs();
		
    })()

	
});