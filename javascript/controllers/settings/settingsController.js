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

app.controller('PrescribeSettingsController.AddDrugsToSettings', function($scope, $modalInstance, data, $http, $window, $location, JsonService,limitToFilter) {
	
	
	$scope.drugTypeList =[];
	$scope.drugNumOfDayList = JsonService.numberList;
	$scope.drugtimesADay = JsonService.timesADay;
	$scope.drugDayTypeList =[];
	$scope.drugsWhenList =[];
	$scope.drugAdviceTypeList =[];
	$scope.drugDoseList =[];
	$scope.drugData = {};
	$scope.drugPeriodicDoseList = [];
	$scope.enteredDrugDoseList = [];
	$scope.addByName = false;
	$scope.doseList = [];
	
	$scope.drugNameList = {};
	
	$scope.cancelDrug = function (){
		$modalInstance.close();
	};
	
	$scope.drugName = "";
	$scope.drugData.addMood = true; 
	$scope.drugData.delDrug = false;
	$scope.drugData.editName = false;
	
	
	$scope.inItDrugs = function (){
		
		$scope.drugData.drugName = "";
    	$scope.drugData.addMood = true; 
    	$scope.drugData.delDrug = false;
    	$scope.drugData.editName = false;
    	$scope.drugData.preiodicList = [];
    	$scope.bringdrugsDayTypeList(true, 1 , 3);
		$scope.bringdrugsWhatType(true, null);
		$scope.bringdrugsAdviceType(true, null);
		
	};
	
	$scope.bringdrugsDayTypeList = function (addMode, typeID, timeID){
		
		var dataString = "query=1";

        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.drugDayTypeList = result;
        	
        	if(addMode){
        		var periodicData = {drugDayTypeList : $scope.drugDayTypeList, drugNumOfDayList : $scope.drugNumOfDayList, doseDataList: [] , numOfDay : 7, durationType : 1, dose: '' };
    	    	$scope.drugData.preiodicList.push(periodicData);
    	    	
    	    	$scope.inItDrugsType(addMode, typeID, timeID, $scope.drugData.preiodicList);
        	}else{
        		
        		angular.forEach($scope.drugData.preiodicList, function(value, key) {
        			
        			value.drugDayTypeList = $scope.drugDayTypeList;
        			value.drugNumOfDayList = $scope.drugNumOfDayList;
        			value.doseDataList = [];
        		});
        		$scope.inItDrugsType(addMode, typeID, timeID, $scope.drugData.preiodicList);
        	}
        	
        	
        });
		
	};
	
	$scope.inItDrugsType = function (addMode, selectedDrugTypeID, selectedTimesADay, preiodicList){
		
		angular.forEach($scope.drugtimesADay, function(value, key) {
			if(value.code == selectedTimesADay){
				$scope.drugData.timesADay = value;
			}
		});
		var dataString = "query=0";

        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.drugTypeList = result;
        	
        	angular.forEach($scope.drugTypeList, function(value, key) {
    			if(value.id == selectedDrugTypeID){
    				$scope.drugData.drugType = value;
    				$scope.preiodicDoseHanleler(addMode, $scope.drugData.drugType.unit, preiodicList, selectedTimesADay);
    			}
    		});
        	
        });
		
	};
	
	
	$scope.preiodicDoseHanleler = function (addMode, unit, preiodicList, selectedTimesADay){
		
		if(selectedTimesADay == -1){
			$scope.drugData.preodicValue = preiodicList.length;
			selectedTimesADay = 3;
		}else if(selectedTimesADay == -2){
			selectedTimesADay = 1;
		}
			
		angular.forEach(preiodicList, function(preiodicData, key) {
			$scope.doseHandeler(unit, preiodicData.doseDataList, selectedTimesADay, preiodicData.dose);
			
			angular.forEach(preiodicData.drugDayTypeList, function(value, key) {
				if(value.id == preiodicData.durationType){
					preiodicData.durationDayType = value;
				}
			});
			
			angular.forEach(preiodicData.drugNumOfDayList, function(data, key) {
				if(data.value == preiodicData.numOfDay){
					preiodicData.dataNumOFDay = data;
				}
			});
			
		});
	};
	
	$scope.doseHandeler = function (unit, doseDataList, selectedTimesADay, dose){
		
		var val = parseFloat(unit);
		if(dose != ''){
			$scope.enteredDrugDoseList = dose.split(' - ');
		}
		
		
		
		for(var i = 0; i< selectedTimesADay; i++){
			if($scope.enteredDrugDoseList.length > 0){
				var data = {"value" : $scope.enteredDrugDoseList[i]};
				unit = $scope.enteredDrugDoseList[i];
			}else{
				var data = {"value" : val};
				unit = val;
			}
			
			doseDataList.push(data);
		}
		$scope.enteredDrugDoseList = [];
	};
	
	$scope.doseChanger = function (change, doseDataList){
		
		angular.forEach(doseDataList, function(data, key) {
			var val = parseFloat(data.value);
			data.value = val + change;
			
		});
		
	};
	
	$scope.timeChanger = function (addMode, drugType, selectedTimesADay, preiod){
		
		$scope.drugData.preiodicList = [];
		
		if(selectedTimesADay == -1){
			
			for(var i = 0; i < preiod; i++){
				
				var periodicData = {drugDayTypeList : $scope.drugDayTypeList, drugNumOfDayList : $scope.drugNumOfDayList, doseDataList: [] , numOfDay : 7, durationType : 1, dose: '' };
		    	$scope.drugData.preiodicList.push(periodicData);
			}
			
			$scope.drugData.preodicValue = preiod;
	    	
		}else{
			var periodicData = {drugDayTypeList : $scope.drugDayTypeList, drugNumOfDayList : $scope.drugNumOfDayList, doseDataList: [] , numOfDay : 7, durationType : 1,  dose: ''};
	    	$scope.drugData.preiodicList.push(periodicData);
		}
		
    	
    	$scope.inItDrugsType(addMode,drugType.id, selectedTimesADay, $scope.drugData.preiodicList);
		
	};
	
	
	$scope.doseMaker = function (unit, numOfTime, change){
		
		var drugDoseList = [];
		
		var val = parseFloat(unit) + change;
		var data = {"value" : val};
		for(var i = 0; i< numOfTime; i++){
			if($scope.enteredDrugDoseList.length > 0){
				var data = {"value" : $scope.enteredDrugDoseList[i]};
				unit = $scope.enteredDrugDoseList[i];
			}else{
				var data = {"value" : val};
				unit = val;
			}
			
			drugDoseList.push(data);
		}
		$scope.enteredDrugDoseList = [];
		
		
		$scope.bringdrugsDayType(true , null, drugDoseList);
	};
	
	
	
		
	$scope.bringdrugsDayType = function (addMood, selectedDayTypeID, doseDataList){
		
		var dataString = "query=1";

        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.drugDayTypeList = result;
        	
        	
        	if(addMood){
        		
    	    	var drugDoseData = {drugDayTypeList : $scope.drugDayTypeList, drugNumOfDayList : $scope.drugNumOfDayList, drugDoseList: doseDataList};
    	    	$scope.drugData.doseList.push(drugDoseData);
    	    	
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
	
	$scope.saveDrug = function(isAnother) {
		
		if($scope.drugData.drugName) {
				$scope.prepareDrugSaveData(isAnother);
		}else{
			$scope.errorMessage = "Please Select Drug Name"; 
			$scope.error = true;
			$("#drugName").addClass('has-error');
		}
		
		
	};
	
	$scope.prepareDrugSaveData = function(isAnother){
		
		
		var drugType = $scope.drugData.drugType.id;
		var drugName =  $scope.drugData.drugName;
		
		var drugTime = $scope.drugData.timesADay.code;
		
		var doseUnit = "";
		if($scope.drugData.optionalInitial != undefined && $scope.drugData.optionalInitial){
			doseUnit = $scope.drugData.drugType.optionalUnitInitial;
		}else{
			doseUnit = $scope.drugData.drugType.unitInitial;
		}
		
		var drugWhen = $scope.drugData.whatType.id;
		
		var drugAdvice = $scope.drugData.adviceType.drugAdviceID;
		
		
		var query = 5;
		var drugPrescribeID = 0;
		
		var dataString = 'drugType='+ drugType +'&drugName='+ drugName + '&drugStr='+ $scope.drugData.drugStr + '&diseaseID='+ data.prescription.diseaseID + '&doctorID='+ data.prescription.doctorID + '&drugTime='+ drugTime +'&doseUnit='+ doseUnit  + '&drugWhen='+ drugWhen +'&drugAdvice='+ drugAdvice +'&query=' + query;
		
        $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	if(drugPrescribeID == 0){
        		drugPrescribeID = parseInt(result);
        	}
        	angular.forEach($scope.drugData.preiodicList, function(preiodicData, key) {
    			
    			var drugDose = "";
    			
    			for(var i = 0;i < preiodicData.doseDataList.length; i++){
    				if(i == 0){
    					drugDose = preiodicData.doseDataList[i].value;
    				}else{
    					drugDose = drugDose + " - "+ preiodicData.doseDataList[i].value;
    				}
    			}
    			
    			var durationType = preiodicData.durationDayType.id;
    			
    			var numOfDay = null;
    			
    			if(durationType < 4){
    				numOfDay = preiodicData.dataNumOFDay.value;
    			}
    			
    			var dataString = "query=13" + '&drugPrescribeID=' + drugPrescribeID + '&dose=' + drugDose + '&numOfDay=' + numOfDay + '&durationType=' + durationType;

    	        $http({
    	            method: 'POST',
    	            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
    	            data: dataString,
    	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    	        }).success(function (result) {

    	        	
    	        });
    			
    		});
        	
        	if(isAnother){
        		$scope.bringPresCribedDrugs();
        	}else{
        		$modalInstance.close();
        	}
        	
        	
        });
		
		
		
	};
	


	

	

	
	

		

	

	

	
	
	
	
	
/*	$scope.saveDrugSetting = function (){
		
		if(validator.validateForm("#validateReq","#lblMsg_modal",null)) {
			
				
				var drugType = $scope.drugData.drugType.id;
				var drugname =  $scope.drugData.drugName;
				
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
	};*/
	
	$scope.getDrugName = function(term) {
        
    	var dataString = 'query=4'+ '&drugName=' + term + '&drugType=' + $scope.drugData.drugType.id;
        
        return $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
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
		  $scope.drugData.drugName = item.drugName;
		  $scope.drugData.drugStr = item.strength;
		  $scope.drugData.delDrug = true;
		  $scope.drugData.editName = true;
	  };
	  
	  $scope.inItDrugs();
	
	
});

app.controller('PrescribeSettingsController.AddInvToSettings', function($scope, $modalInstance, data, $http, $window, $location,limitToFilter) {
	
	$scope.postData = data;
	$scope.postData.note = "";
	
	$scope.createInvSetting = function (){
		
		if(validator.validateForm("#validateReq","#lblMsg_modal",null)) {
			var dataString = "query=6" + '&diseaseID=' + $scope.postData.prescription.diseaseID + '&doctorID=' + $scope.postData.prescription.doctorID + "&invName=" + $scope.postData.invName + "&note=" + $scope.postData.note;

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
    
    $scope.getInvNameForMaster = function(term){
		
		
		var dataString = 'query=8'+ '&queryString=' + term;
        
        return $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result) {
        	$scope.invsttingNameData = result.data;
        	return limitToFilter($scope.invsttingNameData, 10);
        });
	};
	
	$scope.onSelectInvNameMaster = function (item, model, label){
	};
	
	$scope.cancel = function (){
		$modalInstance.dismiss('cancel');
	};
	
	
});

app.controller('PrescribeSettingsController.AddAdvcieToSettings', function($scope, $modalInstance, data, $http, $window, $location,limitToFilter) {
	
	$scope.postData = data;
	
	$scope.type = 0;
	
	$scope.saveNewAdviceSetting = function (){
		
		
			
			if(validator.validateForm("#validateReq","#lblMsg_modal",null)) {
				
			
				var dataString = "query=7" + '&diseaseID=' + $scope.postData.prescription.diseaseID + '&doctorID=' + $scope.postData.prescription.doctorID + "&adviceName=" + $scope.name;

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
    
    $scope.getAdvcieName = function(term) {
        
    	var dataString = 'query=9'+ '&queryString=' + term + '&lang=' + $scope.type;
        
        return $http({
            method: 'POST',
            url: "phpServices/prescriptionSetting/prescriptionSetting.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result) {
        	$scope.adviceNameData = result.data;
        	return limitToFilter($scope.adviceNameData, 10);
        });

        
       // return $scope.products;
      };
      
	  $scope.onSelectAdviceName = function(item, model, label){
		  $scope.name = item.advice;
	  };
    

	
	$scope.cancel = function (){
		$modalInstance.dismiss('cancel');
	};
	
	
	
	
});




/*function lookupDrug(inputString) {
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
}*/