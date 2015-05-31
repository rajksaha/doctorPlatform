app.controller('PrescribeSettingsController', function($scope, $http, $modal, $rootScope, limitToFilter, $location, JsonService, $window) {
	
	
	$scope.masterDiseaseData = {};
	$scope.drugSettingList = [];
	$scope.invSettingList = [];
	$scope.advieSettingList = [];
	
	$scope.doctorData = {};
	
	
    $scope.bringDoctorInfo = function (){
    	
        var dataString = "query=0";

        $http({
            method: 'POST',
            url: "phpServices/appointment/appointmentHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.doctorData = result;
        	$rootScope.doctorData = $scope.doctorData;
        });
    };
    
	
	$scope.bringSettings = function (){
		
		
			
		var dataString = "query=" + 3 + '&diagnosisName=' + $scope.diagnosisData.diseaseName;
		

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
	
	$scope.delAdviceFromSetting = function (advciceSettingID){
		
		var dataString = "query=10" + "&advciceSettingID=" + advciceSettingID;

        $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringAdviceSettingData($scope.masterDiseaseData.diseaseID);
        	 
        });
	};
	
	$scope.deleteInvFromSetting = function (invSettingID){
		
		var dataString = "query=11" + "&invSettingID=" + invSettingID;

        $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringInvSettingData($scope.masterDiseaseData.diseaseID);
        	 
        });
	};
	
	$scope.deleteDrugsFromSetting = function (drugSettingID){
		
		var dataString = "query=12" + "&drugSettingID=" + drugSettingID;

        $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringDrugSettingData($scope.masterDiseaseData.diseaseID);
        	 
        });
	};
	
	
	
	$scope.modalForDrugs = function(){
		
	var prescription = {};
		prescription.diseaseID = $scope.masterDiseaseData.diseaseID;
		prescription.doctorID = $scope.doctorData.doctorID;
	var modalInstance = $modal.open({
        templateUrl: 'javascript/templates/settings/addDrugModal.html',
        windowClass: 'fade in',
        
        controller: 'PrescribeSettingsController.AddDrugsToSettings',
        resolve: {
        	data: function () {
                return {
                	prescription
                };
            }
        },
        backdrop: 'static'
    });
    modalInstance.result.then(function(result) {
    	$scope.bringDrugSettingData($scope.masterDiseaseData.diseaseID);
     });
    
	};
	
	$scope.modalForInv = function(){
		
		var prescription = {};
			prescription.diseaseID = $scope.masterDiseaseData.diseaseID;
			prescription.doctorID = $scope.doctorData.doctorID;
		var modalInstance = $modal.open({
	        templateUrl: 'javascript/templates/settings/addInvModal.html',
	        windowClass: 'fade in',
	        
	        controller: 'PrescribeSettingsController.AddInvToSettings',
	        resolve: {
	        	data: function () {
	                return {
	                	prescription
	                };
	            }
	        },
	        backdrop: 'static'
	    });
	    modalInstance.result.then(function(result) {
	    	$scope.bringInvSettingData($scope.masterDiseaseData.diseaseID);
	     });
	    
		};
		
	$scope.modalForAdvice = function(){
		
		var prescription = {};
			prescription.diseaseID = $scope.masterDiseaseData.diseaseID;
			prescription.doctorID = $scope.doctorData.doctorID;
		var modalInstance = $modal.open({
	        templateUrl: 'javascript/templates/settings/addAdviceModal.html',
	        windowClass: 'fade in',
	        
	        controller: 'PrescribeSettingsController.AddAdvcieToSettings',
	        resolve: {
	        	data: function () {
	                return {
	                	prescription
	                };
	            }
	        },
	        backdrop: 'static'
	    });
	    modalInstance.result.then(function(result) {
	    	$scope.bringAdviceSettingData($scope.masterDiseaseData.diseaseID);
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
		  $scope.diagnosisData.diseaseName = item.name;
	  };
	
	$scope.bringData = function (){
		
		$scope.bringDrugSettingData($scope.masterDiseaseData.diseaseID);
    	$scope.bringInvSettingData($scope.masterDiseaseData.diseaseID);
    	$scope.bringAdviceSettingData($scope.masterDiseaseData.diseaseID);
	};
	
	(function(){
		$scope.bringDoctorInfo();
    })()
	
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
				
				
				var dataString = 'drugType='+ drugType +'&drugName='+ drugname +'&diseaseID='+ data.prescription.diseaseID + '&doctorID='+ data.prescription.doctorID + '&drugTime='+ drugTime + '&drugDose=' + drugDose +'&doseUnit='+ doseUnit + '&drugNoOfDay='+ drugNoOfDay +'&drugDayType='+ drugDayType + '&drugWhen='+ drugWhen +'&drugAdvice='+ drugAdvice+ '&drugPrescribeID='+ drugPrescribeID +'&query=' + query;
				
		        $http({
		            method: 'POST',
		            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
		            data: dataString,
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        	$modalInstance.close();
		        });
			
		}else{
			$scope.error = true;
		}
	};
	
	
});

app.controller('PrescribeSettingsController.AddInvToSettings', function($scope, $modalInstance, data, $http, $window, $location) {
	
	$scope.postData = data;
	$scope.postData.note = "";
	
	$scope.createInvSetting = function (){
		
		
		var dataString = "query=6" + '&diseaseID=' + $scope.postData.prescription.diseaseID + '&doctorID=' + $scope.postData.prescription.doctorID + "&invName=" + $(".adderNameInv").val() + "&note=" + $scope.postData.note;

        $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$modalInstance.close();
        });
        
    };
    

	
	$scope.cancel = function (){
		$modalInstance.close();
	};
	
	
});

app.controller('PrescribeSettingsController.AddAdvcieToSettings', function($scope, $modalInstance, data, $http, $window, $location) {
	
	$scope.postData = data;
	
	$scope.saveNewAdviceSetting = function (){
		
		
		var dataString = "query=7" + '&diseaseID=' + $scope.postData.prescription.diseaseID + '&doctorID=' + $scope.postData.prescription.doctorID + "&adviceName=" + $(".adderNameAdvice").val();

        $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$modalInstance.close();
        });
        
    };
    

	
	$scope.cancel = function (){
		$modalInstance.close();
	};
	
	
});




function lookupDrug(inputString) {
	if(inputString.length == 0) {
		$('.drugSuggetionBox').fadeOut(); // Hide the suggestions box
	} else {
			var type = parseInt($("#drugTypeAdder").val()) + 1 ;
            $.post("phpServices/prescriptionSetting/prescriptionSetting.php", {drugName: ""+inputString+"", query : 4 , drugType : type}, function(data) { // Do an AJAX call
			$('.drugSuggetionBox').fadeIn(); // Show the suggestions box
			$('.drugSuggetionBox').html(data); // Fill the suggestions box
		});
	}
}

function autocompleteDrugs(dataString) {
	$('.drugAdderName').val(dataString);
	$('.drugSuggetionBox').fadeOut();
	$('.drugSuggetionBox').hide();
}

function lookupInv(inputString) {
	if(inputString.length == 0) {
		$('.suggetionBox').fadeOut(); // Hide the suggestions box
	} else {
            $.post("phpServices/diagnosis/diagnosis.php", {queryString: ""+inputString+"", query : 0}, function(data) { // Do an AJAX call
			$('.suggetionBoxInv').fadeIn(); // Show the suggestions box
			$('.suggetionBoxInv').html(data); // Fill the suggestions box
		});
	}
}

function autocompleteInv(dataString) {
	$('.adderNameInv').val(dataString);
	$('.suggetionBoxInv').fadeOut();
	$('.suggetionBoxInv').hide();
}

function lookupInv(inputString) {
	if(inputString.length == 0) {
		$('.suggetionBoxInv').fadeOut(); // Hide the suggestions box
	} else {
            $.post("phpServices/prescriptionSetting/prescriptionSetting.php", {queryString: ""+inputString+"", query : 8}, function(data) { // Do an AJAX call
			$('.suggetionBoxInv').fadeIn(); // Show the suggestions box
			$('.suggetionBoxInv').html(data); // Fill the suggestions box
		});
	}
}

function autocompleteInv(dataString) {
	$('.adderNameInv').val(dataString);
	$('.suggetionBoxInv').fadeOut();
	$('.suggetionBoxInv').hide();
}

function lookupAdvice(inputString) {
	if(inputString.length == 0) {
		$('.suggetionBoxAdvice').fadeOut(); // Hide the suggestions box
	} else {
			var lang = parseInt($("#langSelector").val()) ;
            $.post("phpServices/prescriptionSetting/prescriptionSetting.php", {queryString: ""+inputString+"", query : 9, lang : lang}, function(data) { // Do an AJAX call
			$('.suggetionBoxAdvice').fadeIn(); // Show the suggestions box
			$('.suggetionBoxAdvice').html(data); // Fill the suggestions box
		});
	}
}

function autocompleteAdvice(dataString) {
	$('.adderNameAdvice').val(dataString);
	$('.suggetionBoxAdvice').fadeOut();
	$('.suggetionBoxAdvice').hide();
}