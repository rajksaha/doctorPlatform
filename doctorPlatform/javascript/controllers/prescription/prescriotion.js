app.controller('PrescriptionController', function($scope, $http, $modal, $rootScope, limitToFilter, $location, $filter, $window, JsonService) {
	
	$scope.menuDataList = [];
	$scope.patientData = {};
	$scope.doctorData = {};
	$scope.patientTypeList =[];
	$scope.appoinmentData ={};
	$scope.patientStateList = [];
	
	$scope.refferedAdderData = {};
	
	$scope.prescribedDrugList = [];
	$scope.numberOfPrescribedDrugs = 0;
	
	$scope.prescribedInvData = [];
	$scope.numberOfInvAdded = 0;
	
	$scope.prescribedComplainData = [];
	
	$scope.prescribedVitalData = [];
	
	$scope.prescribedAdviceData = [];
	
	
	$scope.fixNextVisit = function (){
		
		var filteredDate = $filter('date')($scope.nextVisitData.date, "yyyy-MM-dd"); 
		
		if($scope.nextVisitData.appointmentID){
			
			
			
			var dataString = "query=7" + "&nextVisitDate=" + filteredDate + "&appointmentID=" + $scope.nextVisitData.appointmentID;

	        $http({
	            method: 'POST',
	            url: "phpServices/prescription/prescriptionHelperService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        });
	        
		}else{
			
			var dataString = "query=8" + "&nextVisitDate=" + filteredDate;

	        $http({
	            method: 'POST',
	            url: "phpServices/prescription/prescriptionHelperService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        });
	        
		}
	};
	
	$scope.refDoc = {};
	
    $scope.getRefDoctor = function(term) {
        
    	var dataString = 'query=9'+ '&refDocName=' + term;
        
        return $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result) {
        	$scope.refDoc = result.data;
        	return limitToFilter($scope.refDoc, 10);
        });

        
       // return $scope.products;
      };
      
	  $scope.onSelectRefDocotor = function(item, model, label){
		  $scope.refferedAdderData.doctorAdress = item.doctorAdress;
		  $scope.refferedAdderData.refDocID = item.id;
	  };
	  
	  $scope.saveReffredDoctor = function(refDocData){
		  
		  
		  if(refDocData.refDocID){
			  $scope.addReffredDoctor(refDocData.refDocID);
		  }else{
			  var dataString = 'query=10'+ '&refDocName=' + refDocData.doctorName + '&refDocAdress=' + refDocData.doctorAdress;
		        
			  $http({
		            method: 'POST',
		            url: "phpServices/prescription/prescriptionHelperService.php",
		            data: dataString,
		            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		        }).success(function (result) {
		        	$scope.addReffredDoctor(result);
		        });
		  }
	  };
	  
	  $scope.addReffredDoctor = function(doctorID){
		  
		  var dataString = 'query=11'+ '&refDocID=' + parseInt(doctorID);
	        
		  $http({
	            method: 'POST',
	            url: "phpServices/prescription/prescriptionHelperService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$scope.bringPrescribedRefferedDoctor($scope.appoinmentData.appointmentID);
	        });
	  };
	  
	  $scope.deleteReffredDoctor = function(redDocID){
		  
		  var dataString = 'query=12';
	        
		  $http({
	            method: 'POST',
	            url: "phpServices/prescription/prescriptionHelperService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	$scope.bringPrescribedRefferedDoctor($scope.appoinmentData.appointmentID);
	        });
	  };
	
	$scope.bringPatientInfo = function(){
		
		var dataString = "query=0";

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.patientData = result;
        });
	};
	
	$scope.bringMenu = function(){
		
		var dataString = "query=1";

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.menuDataList = result;
        });
		
	};
	
    $scope.bringDoctorInfo = function (){
    	
        var dataString = "query=0";

        $http({
            method: 'POST',
            url: "phpServices/appointment/appointmentHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.doctorData = result;
        	if($scope.doctorData.patientType == 1){
        		
        		var dataString = "query=2" + "&doctorType=" + $scope.doctorData.category;

                $http({
                    method: 'POST',
                    url: "phpServices/prescription/prescriptionHelperService.php",
                    data: dataString,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (result) {
                	$scope.patientTypeList = result;
                });
        	}
        	
        	if($scope.doctorData.patientState== 1){
        		
        		var dataString = "query=5";

                $http({
                    method: 'POST',
                    url: "phpServices/prescription/prescriptionHelperService.php",
                    data: dataString,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (result) {
                	$scope.patientStateList = result;
                });
        	}
        	
        });
    };
    
    $scope.changePatientType = function(patientType){
    	
    	
    	var dataString = "query=3" + "&patientType=" + patientType.id + "&patientDetailID=" + $scope.patientData.patientDetailID + "&patientID=" + $scope.patientData.patientID;

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.inIt();
        });
    	
    	
    };
    

    
    $scope.changePatientState = function (patientState){
    	
    	var dataString = "query=6" + "&patientState=" + patientState.id;

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.appoinmentData.appointmentType = patientState.id;
        });
    };
    
    
    $scope.bringAppointmentInfo = function (){
    	
    	var dataString = "query=4";

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	
        	$scope.appoinmentData = result;
        	
        	$scope.bringPresCribedDiagnosis($scope.appoinmentData.appointmentID);
        	$scope.bringPresCribedDrugs($scope.appoinmentData.appointmentID);
    		$scope.bringPrescribedInv($scope.appoinmentData.appointmentID);
    		$scope.bringPrescribedAdvice($scope.appoinmentData.appointmentID);
    		$scope.bringPrescribedVital($scope.appoinmentData.appointmentID);
    		$scope.bringPrescribedComplain($scope.appoinmentData.appointmentID);
    		$scope.bringPrescribedFamilyHistory($scope.appoinmentData.appointmentID);
    		$scope.bringPrescribedPastHistory($scope.appoinmentData.appointmentID);
    		$scope.bringPrescribedOBS($scope.appoinmentData.appointmentID, $scope.appoinmentData.patientID);
    		$scope.bringPrescribedMH($scope.appoinmentData.appointmentID, $scope.appoinmentData.patientID);
    		$scope.bringPrescribedNextVisit($scope.appoinmentData.appointmentID);
    		$scope.bringPrescribedRefferedDoctor($scope.appoinmentData.appointmentID);
        });
    };
    
	$scope.bringPresCribedDrugs = function (appointmentID){
		
		var dataString = "query=0" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.prescribedDrugList = result;
        	$scope.numberOfPrescribedDrugs = $scope.prescribedDrugList.length;
        });
	};
	
	$scope.bringPrescribedInv = function (appointmentID){
		
		$scope.invAdderData = {};
		
		var dataString = "query=1" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.prescribedInvData = result;
        	$scope.numberOfInvAdded = $scope.prescribedInvData.length;
        });
	};
	
	$scope.bringPrescribedAdvice = function(appointmentID){
		
		var dataString = "query=2" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.prescribedAdviceData = result;
        });
		
	};
	
	$scope.bringPrescribedVital = function(appointmentID){
		
		var dataString = "query=3" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.prescribedVitalData = result;
        });
		
	};
	
	$scope.bringPrescribedComplain = function(appointmentID){
		
		var dataString = "query=4" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.prescribedComplainData = result;
        });
		
	};
	
	$scope.prescribedMHData = [];
	
	$scope.bringPrescribedMH = function(appointmentID, patientID){
		
		var dataString = "query=5" + '&typeCode=MH' + '&appointmentID=' + appointmentID + '&patientID=' + patientID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.prescribedMHData = result;
        });
		
	};
	
	$scope.prescribedOBSData = [];
	
	$scope.bringPrescribedOBS = function(appointmentID, patientID){
		
		var dataString = "query=5" + '&typeCode=OBS' + '&appointmentID=' + appointmentID + '&patientID=' + patientID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.prescribedOBSData = result;
        });
		
	};
	
	
	$scope.nextVisitData = {};
	
	$scope.bringPrescribedNextVisit = function (appointmentID){
		
		var dataString = "query=7" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	if(result.date){
        		$scope.nextVisitData = result;
        		
        	}else{
        		$scope.nextVisitData = {};
        		$scope.nextVisitData.date = "";
        	}
        	
        	
        });
	};
	
	$scope.refferedDoctorData = {};
	
	$scope.bringPrescribedRefferedDoctor = function (appointmentID){
		
		var dataString = "query=8" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.refferedDoctorData = result;
        });
	};
	
	
	$scope.diagnosisData = {};
	
	$scope.bringPresCribedDiagnosis = function (appointmentID){
		
		var dataString = "query=6" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.diagnosisData = result;
        });
	};
	
	$scope.pastDiseaseList = [];
	
	$scope.bringPrescribedPastHistory = function (appointmentID){
		
		var dataString = "query=9" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.pastDiseaseList = result;
        });
	};
	
	$scope.deletePastHistory = function(id){
		
		var dataString = "query=" + 5 + "&pastHistoryID=" + id;
        
		$http({
            method: 'POST',
            url: "phpServices/history/pastHistoryHelper.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.bringPrescribedPastHistory($scope.appoinmentData.appointmentID);
        });
	};
	
	$scope.familyDiseaseList = [];
	
	$scope.bringPrescribedFamilyHistory = function (appointmentID){
		
		var dataString = "query=10" + '&appointmentID=' + appointmentID;

        $http({
            method: 'POST',
            url: "phpServices/commonServices/prescriptionDetailService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$scope.familyDiseaseList = result;
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
        	$scope.bringPrescribedFamilyHistory($scope.appoinmentData.appointmentID);
        });
	};
	
	
	$scope.print = function (){
		
		//chcek if there is any diagonsis set and any settings connected to it
		
		if($scope.diagnosisData.diseaseID){
			
			
			var dataString = "query=13" + '&diseaseID=' + $scope.diagnosisData.diseaseID + '&doctorID=' + $scope.doctorData.doctorID;

	        $http({
	            method: 'POST',
	            url: "phpServices/prescription/prescriptionHelperService.php",
	            data: dataString,
	            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	        }).success(function (result) {
	        	if(parseInt(result) == -1){
	        		
	        		var prescriptionSettingData = {};
	        		prescriptionSettingData.diseaseID = $scope.diagnosisData.diseaseID;
	        		prescriptionSettingData.diseaseName = $scope.diagnosisData.diseaseName;
	        		prescriptionSettingData.doctorID = $scope.doctorData.doctorID;
	        		
	        		var modalInstance = $modal.open({
	                    templateUrl: 'javascript/templates/prescription/prescriptionSetting.html',
	                    windowClass: 'fade in',
	                    
	                    controller: 'PrescriptionController.PrescriptionSettingController',
	                    resolve: {
	                    	data: function () {
	                            return {
	                            	prescriptionSettingData
	                            };
	                        }
	                    },
	                    backdrop: 'static'
	                });
	                modalInstance.result.then(function(result) {
	                	$scope.printPreview();
	                 });
	                
	                
	        	}else{
	        		$scope.printPreview();
	        	}
	        });
	        
		}else{
    		$scope.printPreview();
    	}
	};
	
	$scope.addCCToPrescription = function(){
		
		var copmplainData = {};
		
		var modalInstance = $modal.open({
            templateUrl: 'javascript/templates/complain/complain.html',
            windowClass: 'fade in',
            
            controller: 'PrescriptionController.PrescribeComplainController',
            resolve: {
            	record: function () {
                    return {
                    	copmplainData
                    };
                }
            },
            backdrop: 'static'
        });
		modalInstance.result.then(function(result) {
			$scope.bringPrescribedComplain($scope.appoinmentData.appointmentID);
	     });
	};
	
	$scope.editFromPresciption = function (copmplainData){

		
		var modalInstance = $modal.open({
            templateUrl: 'javascript/templates/complain/complain.html',
            windowClass: 'fade in',
            
            controller: 'PrescriptionController.PrescribeComplainController',
            resolve: {
            	record: function () {
                    return {
                    	copmplainData
                    };
                }
            },
            backdrop: 'static'
        });
		modalInstance.result.then(function(result) {
			$scope.bringPrescribedComplain($scope.appoinmentData.appointmentID);
	     });
	};
	
	$scope.addDrugsToPrescription = function(){
		
		var drugData = {};
		
		var modalInstance = $modal.open({
			templateUrl: 'javascript/templates/drugs/drugModal.html',
            windowClass: 'fade in',
            
            controller: 'PrescriptionController.PrescribeDrugsController',
            resolve: {
            	record: function () {
                    return {
                    	drugData
                    };
                }
            },
            backdrop: 'static'
        });
		modalInstance.result.then(function(result) {
			$scope.bringPresCribedDrugs($scope.appoinmentData.appointmentID);
	     });
		
	};
	
	$scope.editDrugsFromPresciption = function(drugDataDB){
		
		var drugData = {};
		
		drugData = drugDataDB;
		
		
		var modalInstance = $modal.open({
			templateUrl: 'javascript/templates/drugs/drugModal.html',
            windowClass: 'fade in',
            
            controller: 'PrescriptionController.PrescribeDrugsController',
            resolve: {
            	record: function () {
                    return {
                    	drugData
                    };
                }
            },
            backdrop: 'static'
        });
		modalInstance.result.then(function(result) {
			$scope.bringPresCribedDrugs($scope.appoinmentData.appointmentID);
	     });
		
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
	
    $scope.printPreview = function (){
		
    	
    	var dataString = "query=15";

        $http({
            method: 'POST',
            url: "phpServices/prescription/prescriptionHelperService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	 $window.open("tpdf/" + $scope.doctorData.pdfPage + ".php", '_blank');
        	 $location.path("/appointment");
        	 
        });
    	
    };
    
    $scope.performDiganosis = function (diagnosisData) {
    	
		var modalInstance = $modal.open({
			templateUrl: 'javascript/templates/diagnosis/diagnosis.html',
            windowClass: 'fade in',
            
            controller: 'PrescriptionController.PrescribeDiagnosisController',
            resolve: {
            	record: function () {
                    return {
                    	diagnosisData
                    };
                }
            },
            backdrop: 'static'
        });
		modalInstance.result.then(function(result) {
			$scope.bringPresCribedDiagnosis($scope.appoinmentData.appointmentID);
	     });
    };

	$scope.inIt = function (){
		$scope.bringDoctorInfo();
		$scope.bringPatientInfo();
		$scope.bringMenu();
		$scope.bringAppointmentInfo();
		
	};
	
	(function(){
		$scope.inIt();
    })()

	
});


app.controller('PrescriptionController.PrescribeDiagnosisController', function($scope, $http, $modalInstance, limitToFilter, $filter, record) {
	
	$scope.diagnosisData = {};
	
	if(record.diagnosisData.id){
		$scope.diagnosisData = record.diagnosisData;
	}else{
		$scope.diagnosisData = {};
	}
	$scope.diagnosisNameData = {};
	
	$scope.diagnosisNote = "";
	
	$scope.save = function(){
		
		var dataString = "";
		if($scope.diagnosisData.id){
			
			dataString = "query=" + 3 + '&diagnosisName=' + $scope.diagnosisData.diseaseName + '&note=' + $scope.diagnosisData.note + '&id=' + $scope.diagnosisData.id;

		}else{
			dataString = "query=" + 2 + '&diagnosisName=' + $scope.diagnosisData.diseaseName + '&note=' + $scope.diagnosisData.note;
		}
		

        $http({
            method: 'POST',
            url: "phpServices/diagnosis/diagnosis.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	
        	$modalInstance.close();
        	
        });
	};
	
	$scope.cancel = function(){
		$modalInstance.close();
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

	
});

app.controller('PrescriptionController.PrescriptionSettingController', function($scope, $modalInstance, data, $http, $window, $location) {
	
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

app.controller('PrescriptionController.PrescribeComplainController', function($scope, $http, $modalInstance, JsonService, record, limitToFilter) {
	
	$scope.symptom = {};
	$scope.complainList = [];
	$scope.drugNumOfDayList = JsonService.fractionNumberList;
	$scope.drugDayTypeList = JsonService.timesADay;
	
	
	$scope.init = function(){
		if(record.copmplainData.id){
			$scope.bringdrugsDayType(false, null);
		}else{
			$scope.bringdrugsDayType(true, null);
		}
		
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
        		var data = {"title": "Symptom 1","numOfDay" : $scope.drugNumOfDayList[1], "dayType" : $scope.drugDayTypeList[0],"note" : "","id" : 0};
        		$scope.complainList.push(data);
        		data = {"title": "Symptom 2","numOfDay" : $scope.drugNumOfDayList[1], "dayType" : $scope.drugDayTypeList[0],"note" :"","id" : 0};
        		$scope.complainList.push(data);
        		data = {"title": "Symptom 3","numOfDay" : $scope.drugNumOfDayList[1], "dayType" : $scope.drugDayTypeList[0],"note" :"","id" : 0};
        		$scope.complainList.push(data);
        		data = {"title": "Symptom 4","numOfDay" : $scope.drugNumOfDayList[1], "dayType" : $scope.drugDayTypeList[0],"note" :"","id" : 0};
        		$scope.complainList.push(data);
        		
        	}else{
        		
        		$scope.complainData = {"title": "Symptom"};
        		
        		angular.forEach($scope.drugNumOfDayList, function(data, key) {
        			if(data.value == record.copmplainData.durationNum){
        				$scope.complainData.numOfDay = data;
        			}
        		});
        		
        		angular.forEach($scope.drugDayTypeList, function(value, key) {
        			if(value.id == record.copmplainData.durationID){
        				$scope.complainData.dayType = value;
        			}
        		});
        		$scope.complainData.id = record.copmplainData.id;
        		
        		$scope.complainData.name = record.copmplainData.symptomName;
        		
        		$scope.complainList.push($scope.complainData);
        	}
        	
        });
		
	};
	
	$scope.saveGroupOfComplain = function(){
		
		var entryFound = false;
		
		angular.forEach($scope.complainList, function(value, key) {
			if(value.name){
				entryFound = true;
				
				var dataString = {'complainName': value.name , 'numOfDay' : value.numOfDay.value ,'dayType' :  value.dayType.id, 'note' : value.note, 'complainPrescribeID' : value.id, 'query' : 2};
				
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
			alert("Please select at-least one symptom");
		}else{
			$modalInstance.close();
		}
		
		
		
	};
	
	
	$scope.cancelGroupOfComplain = function(){
		$modalInstance.close();
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


app.controller('PrescriptionController.PrescribeDrugsController', function($scope, $http, $modalInstance, limitToFilter, JsonService, record) {
	
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
	$scope.addByName = false;
	
	$scope.drugNameList = {};
	
	$scope.cancelDrug = function (){
		$modalInstance.close();
	};
	
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
	
	$scope.saveDrug = function() {
		
		if(validator.validateForm("#validateReq","#lblMsg_modal",null)) {
				$scope.prepareDrugSaveData();
		}else{
			$scope.error = true; 
		}
		
		
	};
	
	$scope.prepareDrugSaveData = function(){
		
		var drugType = $scope.drugData.drugType.id;
		var drugName =  $scope.drugData.drugName;
		
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
		
		var query = 6;
		var drugPrescribeID = 0;
		if(!$scope.drugData.addMood){
			query = 5;
			drugPrescribeID = $scope.drugData.drugPrescribeID;
		}
		
		var dataString = 'drugType='+ drugType +'&drugName='+ drugName + '&drugTime='+ drugTime + '&drugDose=' + drugDose +'&doseUnit='+ doseUnit + '&drugNoOfDay='+ drugNoOfDay +'&drugDayType='+ drugDayType + '&drugWhen='+ drugWhen +'&drugAdvice='+ drugAdvice+ '&drugPrescribeID='+ drugPrescribeID +'&query=' + query;
		
        $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function (result) {
        	$modalInstance.close();
        });
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
		
        
        var dataString = "query=11" + '&drugID=' + $scope.drugData.drugID + '&drugName=' + $scope.drugData.drugName;

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
		if(record.drugData.id){
			
			$scope.drugData = {};
			$scope.drugData.drugName = record.drugData.drugName;
			$scope.drugData.drugPrescribeID = record.drugData.id;
			$scope.enteredDrugDoseList = record.drugData.drugDose.split(' - ')
			$scope.bringdrugsType(false,record.drugData.drugTypeID,record.drugData.drugTimeID, record.drugData.drugNoOfDay);
			$scope.bringdrugsDayType(false, record.drugData.drugDayTypeID);
			$scope.bringdrugsWhatType(false, record.drugData.drugWhenID);
			$scope.bringdrugsAdviceType(false, record.drugData.drugAdviceID);
			
		}else{
			
			$scope.drugData.drugName = "";
	    	$scope.drugData.addMood = true; 
	    	$scope.drugData.delDrug = false;
			$scope.drugData.editName = false;
	    	$scope.bringdrugsType(true, null, 3, 7);
			$scope.bringdrugsDayType(true , null);
			$scope.bringdrugsWhatType(true, null);
			$scope.bringdrugsAdviceType(true, null);
		}
		
	};
	
    $scope.getDrugName = function(term) {
        
    	var dataString = 'query=8'+ '&drugName=' + term + '&drugType=' + $scope.drugData.drugType.id;
        
        return $http({
            method: 'POST',
            url: "phpServices/drugs/drugsService.php",
            data: dataString,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(result) {
        	$scope.addByName = false;
        	$scope.drugNameList = result.data;
        	return limitToFilter($scope.drugNameList, 10);
        });

        
       // return $scope.products;
      };
      
	  $scope.onSelectDrugName = function(item, model, label){
		  $scope.drugData.drugID = item.drugID;
		  $scope.drugData.delDrug = true;
		  $scope.drugData.editName = true;
	  };
	
	
	  $scope.bringPresCribedDrugs();

	
});