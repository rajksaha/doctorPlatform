app.controller('PrescribeSettingsController', function($scope, $http, $modal, $rootScope, limitToFilter, $location, JsonService, $window) {
	
	
	$scope.masterDiseaseData = {};
	$scope.drugSettingList = [];
	$scope.invSettingList = [];
	$scope.advieSettingList = [];
	
	$scope.bringSettings = function (){
		
		
			
		var dataString = "query=" + 3 + '&diagnosisName=' + $('.diagnosisAdderName').val();
		

        $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	
        	$scope.masterDiseaseData.diseaseID = parseInt(result);
        	
        	$scope.bringData();
        });
	};
	
	$scope.bringDrugSettingData = function (diseaseID){
		
		var dataString = "query=0" + "&diseaseID=" + diseaseID;

        $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.drugSettingList = result;
        	 
        });
	};
	
	$scope.bringInvSettingData = function (diseaseID){
		
		var dataString = "query=1" + "&diseaseID=" + diseaseID;

        $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.invSettingList = result;
        	 
        });
	};
	
	
	$scope.bringAdviceSettingData = function (diseaseID){
		
		var dataString = "query=2" + "&diseaseID=" + diseaseID;

        $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.advieSettingList = result;
        	 
        });
	};
	
	$scope.modalForDrugs = function(){
		
	var masterDiseaseData = $scope.masterDiseaseData.diseaseID;
	var modalInstance = $modal.open({
        templateUrl: 'javascript/templates/settings/addDrugModal.html',
        windowClass: 'fade in',
        
        controller: 'PrescribeSettingsController.AddDrugsToSettings',
        resolve: {
        	data: function () {
                return {
                	masterDiseaseData
                };
            }
        },
        backdrop: 'static'
    });
    modalInstance.result.then(function(result) {
    	$scope.bringData();
     });
    
	};
	
	$scope.bringData = function (){
		
		$scope.bringDrugSettingData($scope.masterDiseaseData.diseaseID);
    	$scope.bringInvSettingData($scope.masterDiseaseData.diseaseID);
    	$scope.bringAdviceSettingData($scope.masterDiseaseData.diseaseID);
	};
	
	
	
});

app.controller('PrescribeSettingsController.AddDrugsToSettings', function($scope, $modalInstance, data, $http, $window, $location, JsonService) {
	
	
	$scope.drugTypeList =[];
	$scope.drugNumOfDayList = JsonService.numberList;
	$scope.drugtimesADay = JsonService.timesADay;
	$scope.drugDayTypeList =[];
	$scope.drugWhatTypeList =[];
	$scope.drugAdviceTypeList =[];
	$scope.drugDoseList =[];
	$scope.drugData = {};
	$scope.drugPeriodicDoseList = [];
	$scope.enteredDrugDoseList = [];
	
	$scope.drugName = "";
	$scope.drugData.addMood = true; 
	$scope.drugData.delDrug = false;
	$scope.drugData.editName = false;
	

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
	
	$scope.bringdrugsType(true, null, 3, 7);
	$scope.bringdrugsDayType(true , null);
	$scope.bringdrugsWhatType(true, null);
	$scope.bringdrugsAdviceType(true, null);
	
	
	$scope.cancelDrugSetting = function (){
		$modalInstance.close();
	};
	
	$scope.saveDrugSetting = function (){
		
		if(validator.validateForm("#validateReq","#lblMsg_modal",null)) {
			
				
				var drugType = $scope.drugData.drugType.id;
				var drugname =  $(".drugAdderName").val();
				
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
					doseUnit = $scope.drugData.drugType.optionalUnitInitial;
				}else{
					doseUnit = $scope.drugData.drugType.unitInitial;
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
				
				var query = 5;
				var drugPrescribeID = 0;
				
				
				var dataString = 'drugType='+ drugType +'&drugname='+ drugname + '&drugTime='+ drugTime + '&drugDose=' + drugDose +'&doseUnit='+ doseUnit + '&drugNoOfDay='+ drugNoOfDay +'&drugDayType='+ drugDayType + '&drugWhen='+ drugWhen +'&drugAdvice='+ drugAdvice+ '&drugPrescribeID='+ drugPrescribeID +'&query=' + query;
				
		        $http({
		            method: 'POST',
		            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
		            data: dataString,
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        	$scope.bringPresCribedDrugs();
		        });
			
		}else{
			$scope.error = true;
		}
	};
	
	
});

app.controller('PrescribeSettingsController.AddInvsToSettings', function($scope, $modalInstance, data, $http, $window, $location) {
	
	$scope.prescriptionSettingData = data;
	
	$scope.savePrint = function (){
		
		
		var dataString = "query=14" + '&diseaseID=' + $scope.prescriptionSettingData.prescriptionSettingData.diseaseID + '&doctorID=' + $scope.prescriptionSettingData.prescriptionSettingData.doctorID;

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$modalInstance.close();
        });
        
    };
    

	
	$scope.printOnly = function (){
		$modalInstance.close();
	};
	
	
});

app.controller('PrescribeSettingsController.AddAdviceToSettings', function($scope, $modalInstance, data, $http, $window, $location) {
	
	$scope.prescriptionSettingData = data;
	
	$scope.savePrint = function (){
		
		
		var dataString = "query=14" + '&diseaseID=' + $scope.prescriptionSettingData.prescriptionSettingData.diseaseID + '&doctorID=' + $scope.prescriptionSettingData.prescriptionSettingData.doctorID;

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$modalInstance.close();
        });
        
    };
    

	
	$scope.printOnly = function (){
		$modalInstance.close();
	};
	
	
});

function lookup(inputString) {
	if(inputString.length == 0) {
		$('.suggetionBox').fadeOut(); // Hide the suggestions box
	} else {
            $.post("phpServices/diagnosis/diagnosis.php", {queryString: ""+inputString+"", query : 0}, function(data) { // Do an AJAX call
			$('.suggetionBox').fadeIn(); // Show the suggestions box
			$('.suggetionBox').html(data); // Fill the suggestions box
		});
	}
}

function autocomplete(dataString) {
	$('.diagnosisAdderName').val(dataString);
	$('.suggetionBox').fadeOut();
	$('.suggetionBox').hide();
}


function lookupDrug(inputString) {
	if(inputString.length == 0) {
		$('.drugSuggetionBox').fadeOut(); // Hide the suggestions box
	} else {
			var type = $("#drugTypeAdder").val();
			
			if(type == 0){
				type =1;
			}
            $.post("phpServices/prescriptionSetting/prescriptionSetting.php", {drugName: ""+inputString+"", query : 4 , drugType : type}, function(data) { // Do an AJAX call
			$('.drugSuggetionBox').fadeIn(); // Show the suggestions box
			$('.drugSuggetionBox').html(data); // Fill the suggestions box
		});
	}
}

function autocomplete(dataString) {
	$('.drugAdderName').val(dataString);
	$('.drugSuggetionBox').fadeOut();
	$('.drugSuggetionBox').hide();
}